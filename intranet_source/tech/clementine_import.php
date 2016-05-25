<?
/*
*	Adam Walter, Oct-Nov 2011
*
*	This is the script to upload wird-looking Clementine files into CT.
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Lanie's System";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }

  $user = $userdata['username'];

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	ora_commitoff($conn);
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Submit Clementine Manifest" && $HTTP_POST_FILES['update_file']['name'] != ""){
		$vessel = $HTTP_POST_VARS['vessel_num'];
		$comm = $HTTP_POST_VARS['comm'];
		$filename = basename($HTTP_POST_FILES['update_file']['name']);
		$target_path = "./uploads/". $filename;

		$duplicate_pallet_list = "";
		$imported_plt_count = 0;

		$result = "";

		$file_contents = file_get_contents($HTTP_POST_FILES['update_file']['tmp_name']);
		$attachment = chunk_split(base64_encode($file_contents)); 

		if($comm == "" || $vessel == ""){
			echo "<font color=\"#FF0000\"><b>Vessel and Commodity must be chosen</b></font><br>";
		} elseif(!move_uploaded_file($HTTP_POST_FILES['update_file']['tmp_name'], $target_path)){
			echo "<font color=\"#FF0000\"><b>Error during file upload; please contact TS</b></font><br>";
			exit;
		} elseif(substr(basename($HTTP_POST_FILES['update_file']['name']), -3) != "txt"){
			echo "<font color=\"#FF0000\"><b>File is not a TXT.  Please use your browser's back button.</b></font><br>";
		} else {			
			// all values entered.  lets deal with file.
			$line_num = 0; // we increment at start of loop, so begin with 0

			$file = fopen($target_path, "r");
			while(!feof($file) && $result == ""){ //make sure we havent already hit a bad line, no sense looping through a 5000 line file if line 3 was bad
				$line = trim(fgets($file));
//				echo "Line: ".$line."<br>";
				if($line != ""){ // make sure this isnt the blank line at the end of the file
					$line_num++;
					

					$result = validate_line_data($line, $vessel, $cust, $conn);
					if($result == ""){
						// define some variables
						$hatch = substr($line, 6, 2);
						$Barcode = substr($line, 8, 30);
						$QTY = substr($line, 32, 3);
						$size = substr($line, 35, 3);
						$bol = substr($line, 50, 16);
						if($QTY == 450){
							$weight = 1.8;
						} elseif((0 + $size) <= 50){
							$weight = 2.3;
						} else {
							$weight = 10;
						}
						$exporter = substr($line, 21, 4);

						if(substr($bol, 0, 4) == "SGNV"){
							$bol = substr($line, 54, 12); 
						}


						// dont want to violate the CARGO_TRACKING primary key...
						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
								WHERE ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$cust."'
								AND PALLET_ID = '".$Barcode."'";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						if($row['THE_COUNT'] >= 1){
							$duplicate_pallet_list .= $Barcode." (Line ".$line_num.")<br>";
						} else {
							// passed inspection, not a duplicate, lets insert.
							// (0 + value) calculations strip leading zeroes
							$sql = "INSERT INTO CARGO_TRACKING
										(ARRIVAL_NUM,
										COMMODITY_CODE,
										RECEIVER_ID,
										PALLET_ID,
										HATCH,
										QTY_RECEIVED,
										WEIGHT,
										WEIGHT_UNIT,
										MARK,
										BATCH_ID,
										CARGO_SIZE,
										BOL,
										EXPORTER_CODE,
										CARGO_DESCRIPTION,
										FROM_SHIPPING_LINE,
										SHIPPING_LINE,
										QTY_IN_HOUSE,
										RECEIVING_TYPE,
										MANIFESTED,
										SOURCE_NOTE,
										SOURCE_USER)
									VALUES
										('".$vessel."',
										'".$comm."',
										'".$cust."',
										'".$Barcode."',
										'".$hatch."',
										'".(0 + $QTY)."',
										'".$weight."',
										'KG',
										'MANIFESTED',
										'".$QTY."',
										'".(0 + $size)."',
										'".$bol."',
										'".$exporter."',
										'".$exporter."-".$size."-".$QTY."',
										'6000',
										'6000',
										'".(0 + $QTY)."',
										'S',
										'Y',
										'".$filename."',
										'".$user."')";
							ora_parse($cursor, $sql);
							ora_exec($cursor);
							$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
									SET CLEM_CT_RAWSTRING = '".$line."'
									WHERE PALLET_ID = '".$Barcode."'
										AND RECEIVER_ID = '".$cust."'
										AND ARRIVAL_NUM = '".$vessel."'";
							ora_parse($cursor, $sql);
							ora_exec($cursor);
										
							$imported_plt_count++;
						}
					}
				}
			}
			fclose($file);

			if($result != ""){
				echo "<font color=\"#FF0000\">File upload aborted.  Reason:<br>".$result."<br>On Line ".$line_num."</font><br>";
				echo "<font color=\"#FF0000\">Please use your Browser's Back button to return to the previous screen.</font><br>";
				ora_rollback($conn);
				exit;
			} else {
				echo "<font color=\"#0000DD\">File upload successful.<br>".$imported_plt_count." of ".$line_num." Pallets saved.</font><br>";
				if($duplicate_pallet_list != ""){
					echo "<font color=\"#9900FF\">The following Pallets were skipped, due to being duplicates already in the system:<br>".$duplicate_pallet_list."</font>";
				}
				ora_commit($conn);
			}

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'CLEMENTINEIMPORT'";
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
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
			$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = $email_row['SUBJECT'];

			$body = $email_row['NARRATIVE'];

			$mailSubject = str_replace("_0_", $vessel, $mailSubject);
			
			$body = str_replace("_1_", $imported_plt_count, $body);

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$Content.="\r\n";
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"".$filename."\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attachment;
			$Content.="\r\n";
			$Content.="--MIME_BOUNDRY--\n";


			if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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
							'CLEMENTINEIMPORT',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			}
		}
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementine Import Script
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form enctype="multipart/form-data" name="meh" action="clementine_import.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="3" face="Verdana">Vessel:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="vessel_num"><option value="">Please Select a Vessel.</option>
<?
	$sql = "SELECT LR_NUM, TO_CHAR(LR_NUM) || '-' || VESSEL_NAME THE_VES 
			FROM VESSEL_PROFILE 
			WHERE SHIP_PREFIX = 'CLEMENTINES'
			ORDER BY LR_NUM DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['LR_NUM']; ?>"><? echo $Short_Term_row['THE_VES']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="3" face="Verdana">Commodity:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="comm">
<?
	$sql = "SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'CLEMENTINES' ORDER BY COMMODITY_CODE";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['COMMODITY_CODE']; ?>"><? echo $Short_Term_row['COMMODITY_CODE']; ?></option>
<?
	}
?>
			</select></td>
	</tr>	
	<tr>
		<td>File:</td>
		<td><input type="file" name="update_file"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Submit Clementine Manifest"></td>
	</tr>
</form>
</table>
<? include("pow_footer.php");



function validate_line_data($line, $vessel, &$cust, $conn){
	$Short_Term_Cursor = ora_open($conn);
/*
	echo "BoL:  ".substr($line, 50, 16)."<br>";
	echo "BD:  ".substr($line, 66, 1)."<br>";
	echo "Hatch:  ".substr($line, 6, 2)."<br>";
	echo "Pallet:  ".substr($line, 8, 30)."<br>";
	echo "CTNS/Batch:  ".substr($line, 32, 3)."<br>";
	echo "Size:  ".substr($line, 35, 3)."<br>";
	echo "Weightstring:  ".substr($line, 32, 6)."<br>";
	echo "XPTR:  ".substr($line, 21, 4)."<br>";
*/
	// full line check
	if(!ereg("^[0-9a-zA-Z]+$", $line)){
		return "Line must be Alpha-Numeric.  Invalid characters found.";
	}

	// split the line into variables that are... easier for me to read ;p

	if(strlen($line) != 67){
		return "Line not precisely 67 characters long";
	}

	$sql = "SELECT CUSTOMER_ID FROM CLEM_CUST_MAP_IMPORT
			WHERE DESCRIPTION_ID = '".substr($line, 66, 1)."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return "Non B/D value found.";
	} else {
		$cust = $Short_Term_row['CUSTOMER_ID'];
	}

	$qty_rec = substr($line, 32, 3);
	if(!is_numeric($qty_rec)){
		return "Carton QTY (characters 33 through 35) must be a number.";
	}

	// no errors.  exit function.
	return "";
}