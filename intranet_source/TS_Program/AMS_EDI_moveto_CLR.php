<?
/*
*		Adam Walter, Dec 2013
*
*		Reads fromt he canadian ams edi table, and applies
*		holds and releases to the AMS status in C_L_R
*
*		C_L_R stands for CANADIAN_LOAD_RELEASE in thsi document, btw.
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us";
	$mailheaders = "FROM:PoW@NoReplies.com";


	$sql = "SELECT DISTINCT LLOYD_NUM, BOL_EQUIV, CONTAINER_NUM
			FROM CANADIAN_AMSEDI_DETAIL CAD, CANADIAN_AMSEDI_HEADER CAH
			WHERE CAD.KEY_ID = CAH.KEY_ID
				AND PUSH_TO_CLR IS NULL
				AND LLOYD_NUM IN (SELECT LLOYD_NUM FROM CANADIAN_LOAD_RELEASE WHERE GATEPASS_PDF_DATE IS NULL)
			ORDER BY LLOYD_NUM, BOL_EQUIV, CONTAINER_NUM";
	$distinct_list = ociparse($rfconn, $sql);
	ociexecute($distinct_list);
	while(ocifetch($distinct_list)){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CANADIAN_LOAD_RELEASE
				WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
					AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
					AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
					AND GATEPASS_PDF_DATE IS NULL";
		$count_check = ociparse($rfconn, $sql);
		ociexecute($count_check);
		ocifetch($count_check);
//		echo $sql."\n";
		if(ociresult($count_check, "THE_COUNT") >= 2){
			// duplicate record.  notify, and skip.
			mail($mailTO, "AMS-EDI duplicate record detected", "LLOYD = ".ociresult($distinct_list, "LLOYD_NUM")."  CONT = ".ociresult($distinct_list, "CONTAINER_NUM")."  BOL = ".ociresult($distinct_list, "BOL_EQUIV"), $mailheaders);
		} elseif(ociresult($count_check, "THE_COUNT") <= 0){
			// we got an EDI for a pending (we hope) record, do nothing
		} else {
			// ok, single match.  let's do this.


			// get the list of "pending" EDI lines.
			$sql = "SELECT CAH.ORIGINAL_FILENAME, CAH.ISA_ID, CAH.GS_ID, CAH.ST_ID, CAD.LLOYD_NUM, CAD.VOYAGE_NUM, CAD.BOL_EQUIV, CAD.CONTAINER_NUM, CAD.QTY, 
						CAD.COMMENTS, TO_CHAR(POSTTIME, 'MM/DD/YYYY HH24:MI:SS') THE_POST, CAD.EDI_CODE
					FROM CANADIAN_AMSEDI_DETAIL CAD, CANADIAN_AMSEDI_HEADER CAH
					WHERE CAD.KEY_ID = CAH.KEY_ID
						AND LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
						AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
						AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
						AND PUSH_TO_CLR IS NULL
					ORDER BY TO_DATE(TO_CHAR(POSTTIME, 'MM/DD/YYYY HH24:MI:SS'), 'MM/DD/YYYY HH24:MI:SS'), CAD.EDI_CODE DESC";
//			echo $sql."\n";
			$main_data = ociparse($rfconn, $sql);
			ociexecute($main_data);
			while(ocifetch($main_data)){
				$action_type = "";
				$code_removed = "";

				$orig_file = ociresult($main_data, "ORIGINAL_FILENAME");
				$lloyd = ociresult($main_data, "LLOYD_NUM");
				$voyage = ociresult($main_data, "VOYAGE_NUM");
				$bol = ociresult($main_data, "BOL_EQUIV");
				$container = ociresult($main_data, "CONTAINER_NUM");
				$EDI_qty = ociresult($main_data, "QTY");
				$comments = ociresult($main_data, "COMMENTS");
				$post_time = ociresult($main_data, "THE_POST");
				$edi_code = ociresult($main_data, "EDI_CODE");

				$ISA = ociresult($main_data, "ISA_ID");
				$GS = ociresult($main_data, "GS_ID");
				$ST = ociresult($main_data, "ST_ID");

				GetActionType($edi_code, $action_code, $code_removed, $rfconn);

				$sql = "SELECT CASES, ARRIVAL_NUM FROM CANADIAN_LOAD_RELEASE
						WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
							AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
							AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
							AND GATEPASS_PDF_DATE IS NULL";
				$CLR_data = ociparse($rfconn, $sql);
				ociexecute($CLR_data);
				ocifetch($CLR_data);
				$CLR_qty = ociresult($CLR_data, "CASES");
				$vessel = ociresult($CLR_data, "ARRIVAL_NUM");


				if($action_code == "HOLD"){
					// a shold is a hold.  this is the base case, so apply immediately, no other checks.
					$sql = "UPDATE CANADIAN_LOAD_RELEASE
							SET AMS_STATUS = NULL, 
								AMS_COMMENTS = 'Placed on HOLD by EDI, code ".$edi_code."',
								MOST_REC_CHANGER_ID = 'AMSEDI'
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND GATEPASS_PDF_DATE IS NULL";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
					$sql = "INSERT INTO CANADIAN_RELEASE_HISTORY
								(ARRIVAL_NUM,
								ACTIVITY_DATE,
								CONTAINER_NUM,
								BOL,
								USER_ID,
								COMMENTS,
								RELEASE_SECTION,
								ACTIVITY_DESC)
							VALUES
								('".$vessel."',
								SYSDATE,
								'".$container."',
								'".$bol."',
								'AMSEDI',
								'HOLD CODE ".$edi_code." BY AMSEDI - ISA: ".$ISA."  GS: ".$GS."  ST: ".$ST."  Posted: ".$post_time."',
								'AMS',
								'Date Set via Website')";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
							SET PUSH_TO_CLR = SYSDATE
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND POSTTIME = TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
								AND PUSH_TO_CLR IS NULL
								AND EDI_CODE = '".$edi_code."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				} elseif($action_code == "REMOVE RELEASE"){
					// jsut like a hold, a release-removal is a "definietly do this" proposition.
					$sql = "UPDATE CANADIAN_LOAD_RELEASE
							SET AMS_STATUS = NULL, 
								AMS_COMMENTS = 'Placed on NON-RELEASE by EDI, code ".$edi_code."',
								MOST_REC_CHANGER_ID = 'AMSEDI'
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND GATEPASS_PDF_DATE IS NULL";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
					$sql = "INSERT INTO CANADIAN_RELEASE_HISTORY
								(ARRIVAL_NUM,
								ACTIVITY_DATE,
								CONTAINER_NUM,
								BOL,
								USER_ID,
								COMMENTS,
								RELEASE_SECTION,
								ACTIVITY_DESC)
							VALUES
								('".$vessel."',
								SYSDATE,
								'".$container."',
								'".$bol."',
								'AMSEDI',
								'RELEASE REMOVE CODE ".$edi_code." BY AMSEDI - ISA: ".$ISA."  GS: ".$GS."  ST: ".$ST."  Posted: ".$post_time."',
								'AMS',
								'Date Set via Website')";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
							SET PUSH_TO_CLR = SYSDATE
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND POSTTIME = TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
								AND PUSH_TO_CLR IS NULL
								AND EDI_CODE = '".$edi_code."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				} elseif($action_code == "RELEASE"){
					// a release is a release IF AND ONLY IF the quantity of the release is >= our quantity.  If not, we ignore it, 
					// assuming that if they release "more" later on, the next release will be a full value, and not a "sum" of previous releases.
					// also, whether or not the cargo gets a "release time" is dependant on if there are any outstanding holds.
					// because of these factors, I don't need to check or manipulate the "HOLD_CLEARED_ON" field.  Releases are binary.

					if($EDI_qty >= $CLR_qty){
						$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
								SET PUSH_TO_CLR = SYSDATE
								WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
									AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
									AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
									AND POSTTIME = TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
									AND PUSH_TO_CLR IS NULL
									AND EDI_CODE = '".$edi_code."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);
						$sql = "INSERT INTO CANADIAN_RELEASE_HISTORY
									(ARRIVAL_NUM,
									ACTIVITY_DATE,
									CONTAINER_NUM,
									BOL,
									USER_ID,
									COMMENTS,
									RELEASE_SECTION,
									ACTIVITY_DESC)
								VALUES
									('".$vessel."',
									SYSDATE,
									'".$container."',
									'".$bol."',
									'AMSEDI',
									'RELEASED BY AMSEDI - Code: ".$edi_code."  ISA: ".$ISA."  GS: ".$GS."  ST: ".$ST."  Posted: ".$post_time."',
									'AMS',
									'Date Set via Website')";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);

						$any_holds = AreThereAnyHolds(ociresult($distinct_list, "LLOYD_NUM"), ociresult($distinct_list, "BOL_EQUIV"), ociresult($distinct_list, "CONTAINER_NUM"), $rfconn);

						if(!$any_holds){

							$sql = "UPDATE CANADIAN_LOAD_RELEASE
									SET AMS_STATUS = SYSDATE, 
										AMS_COMMENTS = 'Released by EDI, code ".$edi_code."',
										MOST_REC_CHANGER_ID = 'AMSEDI'
									WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
										AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
										AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
										AND GATEPASS_PDF_DATE IS NULL";
							$update = ociparse($rfconn, $sql);
							ociexecute($update);
						}
					}
				} elseif($action_code == "REMOVE HOLD"){
					// this is the fun one.  First, we see if there exists a prior hold to apply this to.
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM CANADIAN_AMSEDI_DETAIL
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND POSTTIME <= TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
								AND PUSH_TO_CLR IS NOT NULL
								AND HOLD_CLEARED_ON IS NULL
								AND EDI_CODE = '".$code_removed."'";
					echo $sql."\n";
					$held_data = ociparse($rfconn, $sql);
					ociexecute($held_data);
					ocifetch($held_data);
					if(ociresult($held_data, "THE_COUNT") >= 1){
						// there is a (or, possibly, more than 1, but they are all "lumped" since they have no discrening features) hold to remove
						$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
								SET HOLD_CLEARED_ON =  TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
								WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
									AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
									AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
									AND PUSH_TO_CLR IS NOT NULL
									AND HOLD_CLEARED_ON IS NULL
									AND EDI_CODE = '".$code_removed."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);
							
						$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
								SET PUSH_TO_CLR = SYSDATE
								WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
									AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
									AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
									AND POSTTIME = TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
									AND PUSH_TO_CLR IS NULL
									AND EDI_CODE = '".$edi_code."'";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);
						$sql = "INSERT INTO CANADIAN_RELEASE_HISTORY
									(ARRIVAL_NUM,
									ACTIVITY_DATE,
									CONTAINER_NUM,
									BOL,
									USER_ID,
									COMMENTS,
									RELEASE_SECTION,
									ACTIVITY_DESC)
								VALUES
									('".$vessel."',
									SYSDATE,
									'".$container."',
									'".$bol."',
									'AMSEDI',
									'HOLD CLEARED BY AMSEDI - ISA: ".$ISA."  GS: ".$GS."  ST: ".$ST."  Posted: ".$post_time."',
									'AMS',
									'Date Set via Website')";
						$update = ociparse($rfconn, $sql);
						ociexecute($update);

						$any_holds = AreThereAnyHolds(ociresult($distinct_list, "LLOYD_NUM"), ociresult($distinct_list, "BOL_EQUIV"), ociresult($distinct_list, "CONTAINER_NUM"), $rfconn);
						if(!$any_holds){
							$sql = "UPDATE CANADIAN_LOAD_RELEASE
									SET AMS_STATUS = SYSDATE, 
										AMS_COMMENTS = 'Released by EDI, code ".$edi_code."',
										MOST_REC_CHANGER_ID = 'AMSEDI'
									WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
										AND BOL = '".ociresult($distinct_list, "BOL_EQUIV")."'
										AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
										AND GATEPASS_PDF_DATE IS NULL";
							$update = ociparse($rfconn, $sql);
							ociexecute($update);
						}
					}
				} elseif($action_code == "IGNORE"){
					// unused (by the port at least) code
					$sql = "UPDATE CANADIAN_AMSEDI_DETAIL
							SET PUSH_TO_CLR = SYSDATE
							WHERE LLOYD_NUM = '".ociresult($distinct_list, "LLOYD_NUM")."'
								AND BOL_EQUIV = '".ociresult($distinct_list, "BOL_EQUIV")."'
								AND CONTAINER_NUM = '".ociresult($distinct_list, "CONTAINER_NUM")."'
								AND POSTTIME = TO_DATE('".$post_time."', 'MM/DD/YYYY HH24:MI:SS')
								AND PUSH_TO_CLR IS NULL
								AND EDI_CODE = '".$edi_code."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
					$sql = "INSERT INTO CANADIAN_RELEASE_HISTORY
								(ARRIVAL_NUM,
								ACTIVITY_DATE,
								CONTAINER_NUM,
								BOL,
								USER_ID,
								COMMENTS,
								RELEASE_SECTION,
								ACTIVITY_DESC)
							VALUES
								('".$vessel."',
								SYSDATE,
								'".$container."',
								'".$bol."',
								'AMSEDI',
								'INFORMATION ONLY - Code: ".$edi_code."  ISA: ".$ISA."  GS: ".$GS."  ST: ".$ST."  Posted: ".$post_time."',
								'AMS',
								'Date Set via Website')";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				}
			}
		}
	}









function GetActionType($edi_code, &$action_code, &$code_removed, $rfconn){
	// function to determine, and return, what exactly this line of the EDI does
	$sql = "SELECT * FROM CANADIAN_AMSEDI_CODES
			WHERE EDI_CODE = '".$edi_code."'";
//	if($edi_code == "7I"){
//		echo $sql."\n";
//	}
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$action_code = ociresult($short_term_data, "ACTION");
		$code_removed = ociresult($short_term_data, "CODE_REMOVED");
//		if($edi_code == "7I"){
//			echo "Code 7I - code removed DB: ".ociresult($short_term_data, "CODE_REMOVED")."    code removed var: ".$code_removed."\n";
//		}
	} else {
		$action_code = "";
		$code_removed = "";
		$sql = "INSERT INTO CANADIAN_AMSEDI_CODES
					(EDI_CODE,
					ACTION)
				VALUES
					('".$edi_code."',
					'REVIEW')";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us";
		$mailheaders = "FROM:PoW@NoReplies.com";
		mail($mailTO, "AMS-EDI new code (".$edi_code.") found", "", $mailheaders);
	
	}
}






function AreThereAnyHolds($lloyd, $bol, $cont, $rfconn){
	// goes through the current table
	// check for release first
	// PLEASE NOTE:  if there was more than 1 CLR entry, this sql would fail, but the first sql in this program makes sure that can't happen.
	$sql = "SELECT count(*) THE_COUNT
			FROM CANADIAN_AMSEDI_DETAIL CAD, CANADIAN_AMSEDI_CODES CAC
			WHERE PUSH_TO_CLR IS NOT NULL
				AND HOLD_CLEARED_ON IS NULL
				AND CAD.EDI_CODE = CAC.EDI_CODE
				AND ACTION IN ('HOLD')
				AND CAD.LLOYD_NUM = '".$lloyd."'
				AND CAD.BOL_EQUIV = '".$bol."'
				AND CAD.CONTAINER_NUM = '".$cont."'";
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