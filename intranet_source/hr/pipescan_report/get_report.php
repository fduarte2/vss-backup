<?
/*
*	Adam Walter, August, 2008.
*
*	This script, in conjuction with PipeScannerUpload.php, handles
*	The uploading and displaying of PipeScanner scans.
*
*	There will also be affiliated Crons for mailing.
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HR System";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	$month = $HTTP_POST_VARS['retrieve_month'];
	$year = $HTTP_POST_VARS['retrieve_year'];
	$day = $HTTP_POST_VARS['retrieve_day'];
	$hour = $HTTP_POST_VARS['retrieve_hour'];

	$time_in_question = $month."/".$day."/".$year." ".$hour;
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Pipe-Scanner Report Retrieval
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	$sql = "SELECT TO_CHAR(START_SCAN, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_SCAN, 'MM/DD/YYYY HH24:MI') THE_END, DETAIL_FILE_NAME, EXCEPTION_FILE_NAME, TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY HH24:MI') THE_UPLOAD, UPLOADED_BY, COMMENTS, SKIP_REASONS FROM PIPESCAN_LOG WHERE START_SCAN <= TO_DATE('".$time_in_question."', 'MM/DD/YYYY HH24') AND END_SCAN >= TO_DATE('".$time_in_question."', 'MM/DD/YYYY HH24') ORDER BY UPLOAD_TIME DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">The scan time entered does not fall within the start and end times of any uploaded file.</font></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Start Scan Time:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_START']; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">End Scan Time:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_END']; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">File Upload Time:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_UPLOAD']; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">File Uploaded by:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['UPLOADED_BY']; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">File Comments:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMENTS']; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Skip reasons:</font></td>
		<td><textarea rows="5" cols="100" readonly><? echo $row['SKIP_REASONS']; ?></textarea></font></td>
	</tr>
	<tr>
		<td colspan="2" width="15%" align="center"><font size="2" face="Verdana"><a href="<? echo $row['DETAIL_FILE_NAME']; ?>">View Detail File</a></font></td>
	</tr>
	<tr>
		<td colspan="2" width="15%" align="center"><font size="2" face="Verdana"><a href="<? echo $row['EXCEPTION_FILE_NAME']; ?>">View Exception File</a></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>