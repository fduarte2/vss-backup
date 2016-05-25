<?
/*
*		Adam Walter, Dec 2013
*
*		Reads fromt he canadian ams edi table, and applies
*		holds and releases to the AMS status in C_L_R
*
*		C_L_R stands for CANADIAN_LOAD_RELEASE in thsi document, btw.
*
*		--may 2015
*		For any cargo that was released, but gets UNRELEASED
*		By a future EDI, send an email.
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	include("cron_alert.php");
	SendCronBreakNotify("push_CLREDI_to_hist_table.php", "CBP EDI", $rfconn);

	// get each "line" in CLR that has pending data in the AMS_release table,a s indicated by the Key
	$sql = "SELECT DISTINCT CLR_KEY
			FROM CLR_AMS_RELEASE
			WHERE APPLIED_TO_CLR IS NULL
			ORDER BY CLR_KEY";
	$distinct_list = ociparse($rfconn, $sql);
	ociexecute($distinct_list);
	while(ocifetch($distinct_list)){
		// for each line that needs "work", proceed in order with the date-time of the EDI requests
		$sql = "SELECT CAR.*, TO_CHAR(EDI_DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_EDI_DATE
				FROM CLR_AMS_RELEASE CAR
				WHERE APPLIED_TO_CLR IS NULL
					AND CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'
				ORDER BY EDI_DATE_TIME, CLR_UNIQ_AMSHIST_KEY";
//		echo $sql."\n";
		$AMSrel_list = ociparse($rfconn, $sql);
		ociexecute($AMSrel_list);
		while(ocifetch($AMSrel_list)){

			$EDI_qty = ociresult($AMSrel_list, "QTY");
			$sql = "SELECT *
					FROM CLR_MAIN_DATA
					WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
			$CLR_data = ociparse($rfconn, $sql);
			ociexecute($CLR_data);
			ocifetch($CLR_data);
			$AMS350_qty = ociresult($CLR_data, "QTY");

			if(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "TO BE RESOLVED"){
				// this is a HARD BREAK.  Stop the current line of CLR right here.
				// we dont even update the EDI line as being "processed", because we want to try it again each time until it's defined.
				$sql = "UPDATE CLR_MAIN_DATA
						SET AMS_RELEASE = NULL,
							MOST_RECENT_EDIT_DATE = SYSDATE,
							MOST_RECENT_EDIT_BY = 'AMSEDI'
						WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				continue 2;
			} elseif(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "HOLD"){
				// if this is going to UNRELEASE something, we need an email
				CheckAndMaybeEmail(ociresult($distinct_list, "CLR_KEY"), ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY"), $rfconn);

				// a hold is a hold.  this is the base case, so apply immediately, no other checks.
							//AND GATEPASS_PDF_DATE IS NULL";
				$sql = "UPDATE CLR_MAIN_DATA
						SET AMS_RELEASE = NULL,
							MOST_RECENT_EDIT_DATE = SYSDATE,
							MOST_RECENT_EDIT_BY = 'AMSEDI'
						WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				$sql = "UPDATE CLR_AMS_RELEASE
						SET APPLIED_TO_CLR = SYSDATE
						WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

			} elseif(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "REMOVE RELEASE"){
				// if this is going to UNRELEASE something, we need an email
				CheckAndMaybeEmail(ociresult($distinct_list, "CLR_KEY"), ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY"), $rfconn);

				// just like a hold, a release-removal is a "definietly do this" proposition.
							//AND GATEPASS_PDF_DATE IS NULL";
				$sql = "UPDATE CLR_MAIN_DATA
						SET AMS_RELEASE = NULL, 
							MOST_RECENT_EDIT_DATE = SYSDATE,
							MOST_RECENT_EDIT_BY = 'AMSEDI'
						WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				$sql = "UPDATE CLR_AMS_RELEASE
						SET APPLIED_TO_CLR = SYSDATE
						WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			} elseif(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "RELEASE"){
				// a release is a release IF AND ONLY IF the quantity of the release is >= our quantity.  If not, we ignore it, 
				// assuming that if they release "more" later on, the next release will be a full value, and not a "sum" of previous releases.
				// also, whether or not the cargo gets a "release time" is dependant on if there are any outstanding holds.
				// because of these factors, I don't need to check or manipulate the "HOLD_CLEARED_ON" field.  Releases are binary.

				if($EDI_qty < $CLR_qty){
					$sql = "UPDATE CLR_AMS_RELEASE
							SET APPLIED_TO_CLR = SYSDATE, 
								COMMENTS = COMMENTS || ' EDI rejected - insufficient QTY'
							WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				} else {
					$any_holds = AreThereAnyHolds(ociresult($distinct_list, "CLR_KEY"), $rfconn);

					// if it is currently held, we do NOT want to move this to CLR yet.
					if(!$any_holds){
						$sql = "UPDATE CLR_MAIN_DATA
								SET AMS_RELEASE = SYSDATE, 
									MOST_RECENT_EDIT_DATE = SYSDATE,
									MOST_RECENT_EDIT_BY = 'AMSEDI'
								WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);

						$sql = "UPDATE CLR_AMS_RELEASE
								SET APPLIED_TO_CLR = SYSDATE
								WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);
					} else {
						// there were holds.  leave this EDI unfinished until next run, when it may or may not be needed.
					}
				}
			} elseif(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "REMOVE HOLD"){
				// this is the fun one.  First, we see if there exists a prior hold to apply this to.
				$sql = "SELECT CODE_REMOVED
						FROM CANADIAN_AMSEDI_CODES
						WHERE EDI_CODE = '".ociresult($AMSrel_list, "EDI_CODE")."'";
				$holdcode_data = ociparse($rfconn, $sql);
				ociexecute($holdcode_data);
				ocifetch($holdcode_data);
				$code_removed = ociresult($holdcode_data, "CODE_REMOVED");

				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CLR_AMS_RELEASE
						WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'
							AND APPLIED_TO_CLR IS NOT NULL
							AND HOLD_CLEARED_ON IS NULL
							AND EDI_CODE = '".$code_removed."'
							AND EDI_DATE_TIME < TO_DATE('".ociresult($AMSrel_list, "THE_EDI_DATE")."', 'MM/DD/YYYY HH24:MI:SS')";
				$held_data = ociparse($rfconn, $sql);
				ociexecute($held_data);
				ocifetch($held_data);
				if(ociresult($held_data, "THE_COUNT") >= 1){
					// great!  theres a line this one "counters".  Let's counter it.
					$sql = "UPDATE CLR_AMS_RELEASE
							SET HOLD_CLEARED_ON = SYSDATE
							WHERE HOLD_CLEARED_ON IS NULL
								AND APPLIED_TO_CLR IS NOT NULL
								AND EDI_CODE = '".$code_removed."'
								AND CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'
								AND EDI_DATE_TIME < TO_DATE('".ociresult($AMSrel_list, "THE_EDI_DATE")."', 'MM/DD/YYYY HH24:MI:SS')";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$sql = "UPDATE CLR_AMS_RELEASE
							SET APPLIED_TO_CLR = SYSDATE
							WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$any_holds = AreThereAnyHolds(ociresult($distinct_list, "CLR_KEY"), $rfconn);
					$is_released = IsItReleased(ociresult($distinct_list, "CLR_KEY"), $rfconn);

					if(!$any_holds && $is_released){
						$sql = "UPDATE CLR_MAIN_DATA
								SET AMS_RELEASE = SYSDATE, 
									MOST_RECENT_EDIT_DATE = SYSDATE,
									MOST_RECENT_EDIT_BY = 'AMSEDI'
								WHERE CLR_KEY = '".ociresult($distinct_list, "CLR_KEY")."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);

						$sql = "UPDATE CLR_AMS_RELEASE
								SET APPLIED_TO_CLR = SYSDATE
								WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);
					} else {
						// there were holds, or the cargo is not yet released.  leave this EDI unfinished until next run, when it may or may not be needed.
					}
				}
			} elseif(ociresult($AMSrel_list, "EDI_CODE_TYPE") == "IGNORE"){
				$sql = "UPDATE CLR_AMS_RELEASE
						SET APPLIED_TO_CLR = SYSDATE
						WHERE CLR_UNIQ_AMSHIST_KEY = '".ociresult($AMSrel_list, "CLR_UNIQ_AMSHIST_KEY")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			}
		}
	}
	UpdateCronBreakNotify("move_350_hist_to_CLR_main.php", "CBP EDI", $rfconn);



function AreThereAnyHolds($CLR_key, $rfconn){
	// goes through the current table
	// check for release first
	// PLEASE NOTE:  if there was more than 1 CLR entry, this sql would fail, but the first sql in this program makes sure that can't happen.
	$sql = "SELECT count(*) THE_COUNT
			FROM CLR_AMS_RELEASE
			WHERE APPLIED_TO_CLR IS NOT NULL
				AND HOLD_CLEARED_ON IS NULL
				AND CLR_KEY = '".$CLR_key."'
				AND EDI_CODE IN
				(SELECT EDI_CODE FROM CANADIAN_AMSEDI_CODES WHERE ACTION = 'HOLD')";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") >= 1){
		// no records means no pending "unholds"
		return true;
	} else {
		return false;
	}

}

function IsItReleased($CLR_key, $rfconn){
	$sql = "SELECT MAX(APPLIED_TO_CLR), MAX(CLR_UNIQ_AMSHIST_KEY), ACTION
			FROM CLR_AMS_RELEASE CAR, CANADIAN_AMSEDI_CODES CAD
			WHERE APPLIED_TO_CLR IS NOT NULL
				AND CLR_KEY = '".$CLR_key."'
				AND CAD.ACTION IN ('RELEASE', 'REMOVE RELEASE')
				AND CAR.EDI_CODE = CAD.EDI_CODE
			GROUP BY ACTION
			ORDER BY MAX(APPLIED_TO_CLR) DESC, MAX(CLR_UNIQ_AMSHIST_KEY) DESC";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data) || ociresult($short_term_data, "ACTION") != "RELEASE"){
		// the "and" clause above means that we consider cargo "released" IF AND ONLY IF it both A) has a release at all, and B) the most recent code was a RELEASE, and not a REMOVE-RELEASE
		return false;
	} else {
		return true;
	}

}

function CheckAndMaybeEmail($CLR_key, $AMS_EDI_key, $rfconn){
	// this function is called from the 2 action codes that have the potential to remove a RELEASE status.
	// Precheck:  was it actually in said status before the removal?
	$sql = "SELECT NVL(TO_CHAR(AMS_RELEASE, 'MM/DD/YYYY'), 'NR') THE_REL, ARRIVAL_NUM, CONSIGNEE, BOL_EQUIV, BROKER
			FROM CLR_MAIN_DATA
			WHERE CLR_KEY = '".$CLR_key."'";
	$CLR_DATA = ociparse($rfconn, $sql);
	ociexecute($CLR_DATA);
	ocifetch($CLR_DATA);
	if(ociresult($CLR_DATA, "THE_REL") == "NR"){
		// do nothing
	} else {
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".ociresult($CLR_DATA, "ARRIVAL_NUM")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$vesname = ociresult($CLR_DATA, "ARRIVAL_NUM")."-".ociresult($short_term_data, "VESSEL_NAME");

		// we need to send an email notification
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'EDIRELTOHOLD'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "lstewart@port.state.de.us";
			$mailheaders .= "Bcc: ithomas@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
//				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
//				$mailheaders = str_replace("_6_", GetBrokerEmail(??), $mailheaders);
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}
		$mailheaders .= "Content-Type: text/html\r\n";

		$mailSubject = ociresult($email, "SUBJECT");

//								<td><font size=\"2\" face=\"Verdana\"><b>Date/Time Action was Applied (Time3)</b></font></td>
		$bottom_table = "<table border=\"1\">
							<tr>
								<td><font size=\"2\" face=\"Verdana\"><b>UserName</b></font>
								<td><font size=\"2\" face=\"Verdana\"><b>EDI Code</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Code Type</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Date/Time Inside CBP file (Time1)</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Sequence #</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Date/Time PoW Received File (Time2)</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Comments</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Description of Action</b></font>
							</tr>";
		$sql = "SELECT CLR.*, TO_CHAR(EDI_DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_EDI_DATE, TO_CHAR(APPLIED_TO_CLR, 'MM/DD/YYYY HH24:MI:SS') THE_APPLIED_DATE
				FROM CLR_AMS_RELEASE CLR
				WHERE CLR_UNIQ_AMSHIST_KEY = '".$AMS_EDI_key."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$username_box = GetUserNameBox(ociresult($short_term_data, "USERNAME"), ociresult($short_term_data, "KEY_ID"), $received_datetime, $rfconn);
//							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "THE_APPLIED_DATE")."</font></td>
		$bottom_table .= "<tr bgcolor=>
							<td><font size=\"2\" face=\"Verdana\">".$username_box."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "EDI_CODE")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "EDI_CODE_TYPE")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "QTY")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "THE_EDI_DATE")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "CLR_UNIQ_AMSHIST_KEY")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".$received_datetime."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "COMMENTS")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "ACTION_TYPE")."</font></td>
						</tr></table>";


		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", date('m/d/Y h:i:s'), $body);
		$body = str_replace("_1_", $vesname, $body);
		$body = str_replace("_2_", ociresult($CLR_DATA, "CONSIGNEE"), $body);
		$body = str_replace("_3_", ociresult($CLR_DATA, "BOL_EQUIV"), $body);
		$body = str_replace("_4_", ociresult($CLR_DATA, "BROKER"), $body);
		$body = str_replace("_5_", $CLR_key, $body);
		$body = str_replace("_6_", $bottom_table, $body);
		$body = str_replace("_br_", "<br>", $body);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'INSTANT',
						SYSDATE,
						'EMAIL',
						'EDIRELTOHOLD',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			$update_email = ociparse($rfconn, $sql);
			ociexecute($update_email);
		}
	}
}

function GetUserNameBox($user, $release_key, &$received_datetime, $rfconn){
	if($user != "EDI"){
		$received_datetime = "";
		return $user;
	} else {
		$sql = "SELECT SPLIT_FILENAME, TO_CHAR(DATE_FILESPLIT, 'MM/DD/YYYY HH24:MI:SS') THE_DATE_FILESPLIT
				FROM CLR_AMS_350_EDI CAE, CANADIAN_AMSEDI_HEADER CAH
				WHERE CAE.KEY_ID = CAH.KEY_ID
					AND CAE.AMS350_UNIQ_ID = '".$release_key."'";
		$hist_data = ociparse($rfconn, $sql);
		ociexecute($hist_data);
		if(!ocifetch($hist_data)){
			$received_datetime = "";
			return $user;
		} else {
			$received_datetime = ociresult($hist_data, "THE_DATE_FILESPLIT");
			return $user;
		}
	}
}
