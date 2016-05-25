<?

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Library";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Library system");
    include("pow_footer.php");
    exit;
  }

  $user = $userdata['username'];


   $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if (!$bni_conn) {
	  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($bni_conn));
	  printf("Please report to TS!");
	  exit;
	}
	$Short_Term_Cursor = ora_open($bni_conn);

   $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if (!$rf_conn) {
	  printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	  printf("Please report to TS!");
	  exit;
	}
	$ED_cursor = ora_open($rf_conn);
	$mod_cursor = ora_open($rf_conn);


	$submit = $HTTP_POST_VARS['submit'];
	$type = $HTTP_POST_VARS['folder'];
	$keywords = SafeInput($HTTP_POST_VARS['keywords']);
	$desc = SafeInput($HTTP_POST_VARS['desc']);
	$efective = SafeInput($HTTP_POST_VARS['efective']);
	$expire = SafeInput($HTTP_POST_VARS['expire']);
	$active = SafeInput($HTTP_POST_VARS['active']);
	//echo $submit."<br>";
	if($submit == "Upload"){
		if($HTTP_POST_FILES['import_file']['name'] == ""){
			echo "<font color=\"#FF0000\">No file was uploaded.  If you believe this was a system error, please place an entry on helpdesk, and then call TS.</font><br>";
		} elseif($type == "") {
			echo "<font color=\"#FF0000\">One of the destination folders needs to be chosen.</font><br>";
		} else {
			$impfilename = basename($HTTP_POST_FILES['import_file']['name']);

			switch($type){
				case "Weekly Board Report":
					$folder = "weekly_board_report";
				break;

				case "Board Book":
					$folder = "board_books";
				break;

				case "Board Meeting Minutes":
					$folder = "board_minutes";
				break;

				case "Contracts etc":
					$folder = "agreement_contract_lease";
				break;

				case "Resolutions":
					$folder = "resolutions";
				break;

			}

			$target_path_import = "./".$folder."/".$impfilename;

			if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
				system("/bin/chmod a+r $target_path_import");
				echo "<font color=\"#0000FF\">File Copied successfully to ".$type." folder.</font><br>";

				$safened_filename = str_replace("'", "`", $HTTP_POST_FILES['import_file']['name']);

				$sql = "INSERT INTO LIBRARY_UPLOAD_LOG
							(USERNAME,
							DATETIME,
							UPLOAD_TYPE,
							FILE_NAME)
						VALUES
							('".$user."',
							SYSDATE,
							'".$type."',
							'".$safened_filename."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				$sql = "SELECT MAX(DOCUMENT_ID) + 1 THE_MAX
						FROM DOCUMENT_STORE";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$next_num = $Short_Term_row['THE_MAX'];

				$sql = "INSERT INTO DOCUMENT_STORE
							(DOCUMENT_ID,
							DOCUMENT_NAME,
							KEYWORDS,
							DESCRIPTION,
							EFFECTIVE_DATE,
							EXPIRATION_DATE,
							ACTIVE,
							SUB_DIRECTORY,
							EDITED_BY,
							EDITED_FROM)
						VALUES
							('".$next_num."',
							'".$safened_filename."',
							'".$keywords."',
							'".$desc."',
							TO_DATE('".$efective."', 'MM/DD/YYYY'),
							TO_DATE('".$expire."', 'MM/DD/YYYY'),
							'".$active."',
							'".$folder."',
							'".$user."',
							'library_upload.php')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				$sql = "SELECT TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI:SS') THE_TIME FROM DUAL";
				ora_parse($ED_cursor, $sql);
				ora_exec($ED_cursor);
				ora_fetch_into($ED_cursor, $temp_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$upload_time = $temp_row['THE_TIME'];

				$sql = "SELECT * FROM EMAIL_DISTRIBUTION
						WHERE EMAILID = 'LIBRARY_UPLOAD'";
				ora_parse($ED_cursor, $sql);
				ora_exec($ED_cursor);
				ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$mailTO = $email_row['TO'];
				$mailheaders = "From: ".$email_row['FROM']."\r\n";

				if($email_row['CC'] != ""){
					$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
				}
				if($email_row['BCC'] != ""){
					$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
				}

				$mailSubject = $email_row['SUBJECT'];
				$mailSubject = str_replace("_0_", $type, $mailSubject);

				$body = $email_row['NARRATIVE']."\r\n";
				$body_replace = "File:  ".$HTTP_POST_FILES['import_file']['name']."\r\nUploaded by:  ".$user."\r\nUploaded on:  ".$upload_time."\r\n";
				$body = str_replace("_1_", $body_replace, $body);

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
								'LIBRARY_UPLOAD',
								SYSDATE,
								'COMPLETED',
								'".$mailTO."',
								'".$email_row['CC']."',
								'".$email_row['BCC']."',
								'".substr($body, 0, 2000)."')";
					ora_parse($mod_cursor, $sql);
					ora_exec($mod_cursor);
				}
			} else 	{
				echo "<font color=\"#FF0000\"><b>Directory could not accept uploaded file.  Please contact TS.</b></font><br>";
				exit;
			} 
		}
	}
?>







<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Director's Area: Library File Upload
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<form enctype="multipart/form-data" name="file_submit" action="library_upload.php" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">Select File To Upload:</font>&nbsp;&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana"><b>NOTE:  Files have a maximum size of 20MB.  If you need to upload a larger file, please place an entry on Helpdesk.</font></td>
	</tr>
	<tr>
		<td colspan="2" ><input type="file" name="import_file"></td>
	</tr>
	<br>
	<tr>
		<td colspan="2" >
			<input type="radio" name="folder" value="Weekly Board Report">Weekly Board Report</input><br>
			<input type="radio" name="folder" value="Board Book">Board Book</input><br>
			<input type="radio" name="folder" value="Board Meeting Minutes">Board Meeting Minutes</input><br>
			<input type="radio" name="folder" value="Contracts etc">Agreements, Contracts, Leases, Other</input><br>
			<input type="radio" name="folder" value="Resolutions">Resolutions</input><br>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td colspan="2"><font size="3" face="Verdana">Optional:</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Keyword(s):</font></td>
		<td><input type="text" name="keywords" size="30" maxlength="60"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Description:</font></td>
		<td><input type="text" name="desc" size="30" maxlength="200"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Effective Date:</font></td>
		<td><input type="text" name="efective" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Expiry Date:</font></td>
		<td><input type="text" name="expire" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Active:</font></td>
		<td><input type="text" name="active" size="1" maxlength="1"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");

















function SafeInput($string){
	$return = strtoupper($string);
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);

	return $return;
}

?>