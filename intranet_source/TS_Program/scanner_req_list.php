<?
/*
*	Adam Walter, May 2013
*
*	A page to show upcoming scanner requests
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "SUPV System";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit != ""){
		$super = $HTTP_POST_VARS['super'];
		$date = $HTTP_POST_VARS['date'];
		$hour = $HTTP_POST_VARS['hour'];
		$min = $HTTP_POST_VARS['min'];
		$count = $HTTP_POST_VARS['count'];
		$req_id = $HTTP_POST_VARS['req_id'];

		$go_ahead = true;
		if(!is_numeric($hour) || $hour < 0 || $hour > 23 || $hour != round($hour)){
			$go_ahead = false;
			echo "<font color=\"#FF0000\">Hour value must be a non-decimal number between 0 and 23.<br></font>";
		}
		if(!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $date)){
			$go_ahead = false;
			echo "<font color=\"#FF0000\">Date field must be in MM/DD/YYYY format.<br></font>";
		}
		if(!is_numeric($count) || $count != round($count) || $count < 1){
			$go_ahead = false;
			echo "<font color=\"#FF0000\">".$count." is not an acceptable number of scanners.<br></font>";
		}

		if($go_ahead == false){
			echo "<font color=\"#FF0000\"><b>Please use your browser's Back button, correct the error, and resubmit the request.</b><br></font>";
		} else {
			// alright, DB time
			if($req_id != ""){
				$sql = "UPDATE SUPERVISOR_SCANNER_REQUESTS
						SET SUPER_ID = '".$super."',
							REQUESTED_DATE_TIME = TO_DATE('".$date." ".$hour.":".$min."', 'MM/DD/YYYY HH24:MI'),
							NUM_SCANNER_REQ = '".$count."',
							REQUEST_ENTERED = SYSDATE
						WHERE REQUEST_ID = '".$req_id."'";
			} else {
				$sql = "INSERT INTO SUPERVISOR_SCANNER_REQUESTS
							(SUPER_ID, 
							REQUESTED_DATE_TIME, 
							NUM_SCANNER_REQ, 
							REQUEST_ENTERED, 
							REQUEST_ID)
						VALUES
							('".$super."',
							TO_DATE('".$date." ".$hour.":".$min."', 'MM/DD/YYYY HH24:MI'),
							'".$count."',
							SYSDATE,
							SUPER_SCANNER_REQ_SEQ.NEXTVAL)";
			}
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			echo "<font color=\"#0000FF\"><b>Request Saved.</b></font>";
		}
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Upcoming Scanner requests
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>SuperID</b></font></td>
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Time</b></font></td>
		<td><font size="2" face="Verdana"><b># Scanners</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
	$sql = "SELECT NUM_SCANNER_REQ, TO_CHAR(REQUESTED_DATE_TIME, 'MM/DD/YYYY') REQ_DATE, 
				TO_CHAR(REQUESTED_DATE_TIME, 'HH24:MI') REQ_TIME,
				SUBSTR(TO_CHAR(SYSDATE + 1, 'DAY'), 0, 3) REQ_ORD_DAY,
				SUPER_ID, REQUEST_ID
			FROM SUPERVISOR_SCANNER_REQUESTS
			WHERE REQUESTED_DATE_TIME > SYSDATE
			ORDER BY REQUESTED_DATE_TIME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="5" align="center"><font size="2" face="Verdana"><b>No Pending Requests.</b></font></td>
	</tr>
<?
	} else {
		$counter = 0;
		do {
?>
<form name="update<? echo $counter++; ?>" action="scanner_req_change.php" method="post">
<input type="hidden" name="req_id" value="<? echo ociresult($stid, "REQUEST_ID"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "SUPER_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "REQ_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "REQ_TIME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "NUM_SCANNER_REQ"); ?></font></td>
		<td><input type="submit" name="submit" value="Change Request"></td>
	</tr>
</form>
<?
		} while(ocifetch($stid));
	}
?>
<form name="insert" action="scanner_req_change.php" method="post">
	<tr>
		<td colspan="5" align="center"><input type="submit" name="submit" value="Create New Request"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
