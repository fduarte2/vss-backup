<?
/*
*	Adam Walter, August, 2008.
*
*	This script, in conjuction with get_report.php, handles
*	The uploading and displaying of PipeScanner scans.
*
*	There will also be affiliated Crons for mailing.
*************************************************************************/

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

	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $HTTP_POST_FILES['detail_file']['name'] != ""){
//		echo basename($HTTP_POST_FILES['the_file']['name'])."<br>";
		$start_month = $HTTP_POST_VARS['start_month'];
		$start_day = $HTTP_POST_VARS['start_day'];
		$start_year = $HTTP_POST_VARS['start_year'];
		$start_hour = $HTTP_POST_VARS['start_hour'];

		$start_time = $start_month."/".$start_day."/".$start_year." ".$start_hour;

		$end_month = $HTTP_POST_VARS['end_month'];
		$end_day = $HTTP_POST_VARS['end_day'];
		$end_year = $HTTP_POST_VARS['end_year'];
		$end_hour = $HTTP_POST_VARS['end_hour'];

//		print_r($_POST);

		$end_time = $end_month."/".$end_day."/".$end_year." ".$end_hour;

		$detail_filename = $start_year.$start_month.$start_day.$start_hour."to".$end_year.$end_month.$end_day.$end_hour."detail.";
		$detfilename = basename($HTTP_POST_FILES['detail_file']['name']);
//		echo $filename."<br>";
		$temp = split("\.", $detfilename);
//		print_r($temp);
		$detail_filename .= $temp[1];

		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\"", "``", $comments);
		$comments = str_replace("\\", "", $comments);

		$skip_reasons = trim($HTTP_POST_VARS['skip_reasons']);
		$skip_reasons = str_replace("'", "`", $skip_reasons);
		$skip_reasons = str_replace("\"", "``", $skip_reasons);
		$skip_reasons = str_replace("\\", "", $skip_reasons);

		$target_path_detail = "./".$detail_filename;
//		echo $target_path."<br>";

		if(move_uploaded_file($HTTP_POST_FILES['detail_file']['tmp_name'], $target_path_detail)){
			$sql = "INSERT INTO PIPESCAN_LOG_SPRINKLER (START_SCAN, END_SCAN, UPLOAD_TIME, UPLOADED_BY, COMMENTS, SKIP_REASONS, DETAIL_FILE_NAME) VALUES(TO_DATE('".$start_time."', 'MM/DD/YYYY HH24'), TO_DATE('".$end_time."', 'MM/DD/YYYY HH24'), SYSDATE, '".$user."', '".$comments."', '".$skip_reasons."', '".$detail_filename."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			system("/bin/chmod a+r $target_path_detail");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Sprinkler-Pipe-Scanner Upload / Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="get_report_sprinkler.php" method="post">
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana">Review / Retrieve File</font></td>
	</tr>
	<tr>
		<td width="48%" align="right"><font size="2" face="Verdana">Most Recent File:</font></td>
		<td width="4%">&nbsp;</td>
<?
	$sql = "SELECT TO_CHAR(START_SCAN, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_SCAN, 'MM/DD/YYYY HH24:MI') THE_END, UPLOAD_TIME, DETAIL_FILE_NAME 
			FROM PIPESCAN_LOG_SPRINKLER 
			ORDER BY UPLOAD_TIME DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<td width="48%" align="left"><font size="2" face="Verdana">No Uploaded Files</font></td>
<?
	} else {
		$filename_det = $row['DETAIL_FILE_NAME'];
?>
		<td width="48%" align="center"><? echo $row['THE_START']." to ".$row['THE_END']; ?><br><a href="<? echo $filename_det; ?>">Details</a></td>
<?
	}
?>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana"><b>---OR---</b></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">Enter Date of Scan:&nbsp;&nbsp;&nbsp;&nbsp;</font>
				<font size="2" face="verdana">Month:<input type="text" name="retrieve_month" value="MM" maxlength="2" size="4">&nbsp;&nbsp;
												Day:<input type="text" name="retrieve_day" value="DD" maxlength="2" size="4">&nbsp;&nbsp;
												Year:<input type="text" name="retrieve_year" value="YYYY" maxlength="4" size="4">&nbsp;&nbsp;
												Hour:<input type="text" name="retrieve_hour" value="HH" maxlength="2" size="4">(24h)
				</font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Check for Report"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="put_data" action="sprinkler_upload.php" method="post">
	<tr>
<?
	$sql = "SELECT TO_CHAR(START_SCAN, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_SCAN, 'MM/DD/YYYY HH24:MI') THE_END, UPLOAD_TIME 
			FROM PIPESCAN_LOG_SPRINKLER 
			ORDER BY UPLOAD_TIME DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<td colspan="3" align="center"><font size="2" face="Verdana">No Uploaded Files</font></td>
<?
	} else {
		$start_scan = $row['THE_START'];
		$end_scan = $row['THE_END'];
?>
		<td colspan="3" align="center"><font size="2" face="Verdana">Latest Uploaded Scanfile:  <? echo $start_scan." to ".$end_scan; ?></font></td>
<?
	}
?>
	</tr>
	<tr>
		<td colspan="3" align="Center"><font size="2" face="Verdana"><b>File Upload</b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Starting Scan:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2">
				<font size="2" face="verdana">Month:<input type="text" name="start_month" value="<? echo date('m'); ?>" maxlength="2" size="4">&nbsp;&nbsp;
												Day:<input type="text" name="start_day" value="<? echo date('d'); ?>" maxlength="2" size="4">&nbsp;&nbsp;
												Year:<input type="text" name="start_year" value="<? echo date('Y'); ?>" maxlength="4" size="4">&nbsp;&nbsp;
												Hour:<input type="text" name="start_hour" value="6" maxlength="2" size="4">(24h)
				</font></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Ending Scan:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2">
				<font size="2" face="verdana">Month:<input type="text" name="end_month" value="<? echo date('m'); ?>" maxlength="2" size="4">&nbsp;&nbsp;
												Day:<input type="text" name="end_day" value="<? echo date('d'); ?>" maxlength="2" size="4">&nbsp;&nbsp;
												Year:<input type="text" name="end_year" value="<? echo date('Y'); ?>" maxlength="4" size="4">&nbsp;&nbsp;
												Hour:<input type="text" name="end_hour" value="<? echo date('H'); ?>" maxlength="2" size="4">(24h)
				</font></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Upload Comments:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2"><input type="text" name="comments" size="50" maxlength="100"></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Skip Reasons:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2"><textarea rows="5" cols="100" name="skip_reasons"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Detail File:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2"><input type="file" name="detail_file"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Upload File"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">Note:  If multiple files are uploaded with overlapping scans, retrieving said scans later will return the most recently uploaded version.</font></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>