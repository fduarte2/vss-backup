<?
/*
*	Screen that lets INV add/adjust paper codes already in C_T.
*	Jul 2012.
********************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Dole EDI codes";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$DT = $HTTP_POST_VARS['DT'];
	$received = $HTTP_POST_VARS['received'];
	$new_code = $HTTP_POST_VARS['new_code'];

	if($submit == "Check DT"){
		$output = "<table width=\"100%\" border=\"1\">";
		$sql = "SELECT COUNT(*) THE_COUNT, BATCH_ID THE_CODE, RECEIVER_ID, ARRIVAL_NUM
				FROM CARGO_TRACKING
				WHERE COMMODITY_CODE = '1272'";
		if($received == "no"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		} else {
			$sql .= " AND DATE_RECEIVED IS NOT NULL";
		}
		$sql .= " AND BOL = '".$DT."'
				GROUP BY BATCH_ID, RECEIVER_ID, ARRIVAL_NUM
				ORDER BY BATCH_ID";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			$output .= "<tr><td align=\"center\">No rolls for this criteria.</td></tr>";
			$submit_tag = "disabled";
		} else {
			$submit_tag = "";
			$output .= "<tr><td align=\"center\" colspan=\"4\"><b>Rolls to be Updated</b></td></tr>";
			$output .= "<tr>
							<td>ARV#</td>
							<td>Cust</td>
							<td>Rolls</td>
							<td>Current Code</td>
						</tr>";
			do {
				$output .= "<tr>
								<td>".ociresult($stid, "ARRIVAL_NUM")."</td>
								<td>".ociresult($stid, "RECEIVER_ID")."</td>
								<td>".ociresult($stid, "THE_COUNT")."</td>
								<td>".ociresult($stid, "THE_CODE")."</td>
							</tr>";
			} while(ocifetch($stid));
			$output .= "</table>";
		}
	} elseif ($submit == "Save" && $new_code != ""){
		$sql = "INSERT INTO DOLEPAPER_MANUAL_CODE_CHANGE
					(PALLET_ID,
					ARRIVAL_NUM,
					CUSTOMER_ID,
					CHANGED_ON,
					OLD_CODE,
					NEW_CODE)
				(SELECT PALLET_ID, ARRIVAL_NUM, RECEIVER_ID, SYSDATE, BATCH_ID, '".$new_code."' FROM CARGO_TRACKING WHERE BOL = '".$DT."'";
		if($received == "no"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		} else {
			$sql .= " AND DATE_RECEIVED IS NOT NULL";
		}
		$sql .= ")";					
		$stid = ociparse($conn, $sql);
		ociexecute($stid);

		$sql = "UPDATE CARGO_TRACKING
				SET BATCH_ID = '".$new_code."',
				CARGO_DESCRIPTION = SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) 
										|| '".$new_code."' || 
									SUBSTR(CARGO_DESCRIPTION, INSTR(CARGO_DESCRIPTION, ' ', 1, 2))
				WHERE BOL = '".$DT."'
				AND COMMODITY_CODE = '1272'";
		if($received == "no"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		} else {
			$sql .= " AND DATE_RECEIVED IS NOT NULL";
		}

		$stid = ociparse($conn, $sql);
		ociexecute($stid);

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'DOLEPAPERCODECHG'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);

		$mailTO = ociresult($stid, "TO");
		$mailheaders = "From: ".ociresult($stid, "FROM")."\r\n";

		if(ociresult($stid, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($stid, "CC")."\r\n";
		}
		if(ociresult($stid, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($stid, "BCC")."\r\n";
		}
		$mailheaders .= "Content-Type: text/html\r\n";

		$mailSubject = ociresult($stid, "SUBJECT");

		$body = ociresult($stid, "NARRATIVE");
		$body = str_replace("_0_", $DT, $body);
		$body = str_replace("_1_", $new_code, $body);

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
						'".$user."',
						SYSDATE,
						'EMAIL',
						'DOLEPAPERCODECHG',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($stid, "CC")."',
						'".ociresult($stid, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$stid = ociparse($conn, $sql);
			ociexecute($stid);
		}

		echo "<font color=\"#0000FF\">".$DT." has been changed to a code of ".$new_code.".  Email sent.</font>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Paper Code Change</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="first" action="Paper_code_change.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana">Dock Ticket:</font></td>
		<td><input type="text" size="10" maxlength="10" name="DT" value="<? echo $DT; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="radio" name="received" value="yes"<? if($received == "yes"){?> checked <?}?>><font size="3" face="Verdana">Received&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><input type="radio" name="received" value="no"<? if($received == "no"){?> checked <?}?>><font size="3" face="Verdana">Not-Received</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Check DT"><hr></td>
	</tr>
</form>
<?
	if($submit != "" && $DT != ""){
?>
<form name="second" action="Paper_code_change.php" method="post">
<input type="hidden" name="DT" value="<? echo $DT; ?>">
<input type="hidden" name="received" value="<? echo $received; ?>">
	<tr>
		<td><font size="3" face="Verdana">New Paper Code:</font></td>
		<td><input type="text" size="10" maxlength="10" name="new_code" value="<? echo $new_code; ?>">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save" <? echo $submit_tag; ?>><hr></td>
	</tr>
	<tr>
		<td colspan="2"><? echo $output; ?></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");