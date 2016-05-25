<?
/*
*		Adam Walter, Dec 2015
*
*	BAPLIE input.
*
*****************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Baplie System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	// email variables that don't change throughout the program
//	$mailTO = "awalter@port.state.de.us,scorbin@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us";
	$mailTO = "awalter@port.state.de.us";

//	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "lstewart@port.state.de.us,awalter@port.state.de.us,ithomas@port.state.de.us, martym@port.state.de.us\r\n"; 
//	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";	

//	$path = "/web/web_pages/TS_Program/BaplieEDI/";
//	$path = "/web/web_pages/TS_Testing/";


	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".".date(mdYhis);
//	$target_path_import = "./uploaded_manifests/".$impfilename;
	$target_path_import = "./BaplieFiles/".$impfilename;

	if($submit != ""){
		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}
		if($vessel == ""){
			echo "<font color=\"#FF0000\">Must enter a Vessel.</font><br>";
			$submit = "";
		}
	}

	if($submit != ""){
		$fp = fopen($target_path_import, "r");
		while($temp = fgets($fp)){

			// nested switch-while statements to reference EDI blocks
			$line = explode("+", trim($temp));
			$line = str_replace("'", "", $line);  // remove the end-of-line apostrophe

			switch ($line[0]) {
				// I am ordering these in the order they first appeared in our sample BAPLIE file.  Note that, with the exception of the "container block" loop and the UN* lines, their order is largely unnecessary

				case "UNB":  // first line of EDI
					// no relevant data
				break;

				case "UNH":  // second line of EDI
					// no relevant data
				break;

				case "BGM":  // third line of EDI "begining of message"
					// no relevant data
				break;

				case "DTM":  // DTM-137, time of baplie transmission
					// no relevant data
				break;

				case "TDT":  // details of Transport
					// no relevant data
				break;
	/*
				case "LOC":  // LOC-5, EDI sender
					// no relevant data
				break;

				case "LOC":  // LOC-61, EDI receiver
					// no relevant data
				break;
	*/
				case "DTM":  // DTM-136, date of vessel departure
					// no relevant data
				break;

				case "RFF":  // Voyage identification
					// no relevant data
				break;

				case "LOC":  // if this is the LOC-147 line, then we start an internal dataset
					if($line[1] == "147"){
						// reset vars
						$tempslot = explode(":", $line[2]);
						$slot = $tempslot[0];
						$cont_num = ""; 
						$barcode = "";
						$cargo_desc = "";
						$weight = "";
						$load_port = "";
						$temperature = "";
						$cont_type = "";
						$cont_full = "";
						$resp_carrier = "";
						$write = true;

						while ($line[0] != "NAD" && $temp = fgets($fp)) {
							$line = explode("+", trim($temp));
							$line = str_replace("'", "", $line);  // remove the end-of-line apostrophe

							if($line[1] == "AAI" && $line[0] == "FTX"){
								$line = str_replace(" ", "", $line);  // remove the spaces
								// "General Info"
								$barcode = $line[4];
							}
							if($line[1] == "AAA" && $line[0] == "FTX"){
								// "Description of Goods"
								$cargo_desc = $line[4]; //?
							}
							if($line[1] == "9" && $line[0] == "LOC"){
								// port of loading
								$tempload = explode(":", $line[2]);
								$load_port = $tempload[0]; 
							}
							if($line[1] == "11" && $line[0] == "LOC"){
								// Cargo Destination
								$tempdest = explode(":", $line[2]);
								if($tempdest[0] != "USILG"){
									$write = false;
								}
							}
							if($line[1] == "WT" && $line[0] == "MEA"){
								// weight in KG
								$temp_val = explode(":", $line[3]);
								$weight = $temp_val[1];
							}
							if($line[1] == "2" && $line[0] == "TMP"){
								// temperature in C
								$temp_val = explode(":", $line[2]);
								$temperature = $temp_val[0];
							}
							if($line[1] == "CN" && $line[0] == "EQD"){
								$line = str_replace(" ", "", $line);  // remove the spaces
								// "Equipment Details"
								$cont_num = $line[2]; 
								$cont_type = $line[3]; 
								if($line[6] == "5"){
									$cont_full = "Full";
								} else {
									$cont_full = "Empty";
								}
							}
							if($line[1] == "CA" && $line[0] == "NAD"){
								// Responsible Carrier 
								$temp_carrier = explode(":", $line[2]);
								$resp_carrier = $temp_carrier[0];
							}
						}

						if($write){
							// write SQLs here for inserts
							$sql = "SELECT CONTAINER_DETAIL_SEQ.NEXTVAL THE_MAX
											FROM DUAL";
							$result = ociparse($rfconn, $sql);
							ociexecute($result);
							ocifetch($result);
							$max_act = ociresult($result, "THE_MAX");

							if($barcode != ""){
								$sql = "INSERT INTO CONTAINER_ACTIVITY
											(CONTAINER_ID,
											SERVICE_CODE,
											ACTIVITY_DATE,
											VALUE,
											ACTIVITY_NUM,
											USERNAME)
										VALUES
											('".$cont_num."',
											'1',
											SYSDATE,
											'".$barcode."',
											'1',
											'".$user."')";
	//							echo $sql."<br>";
								$insert = ociparse($rfconn, $sql);
								ociexecute($insert);
							}
							$sql = "INSERT INTO CONTAINER_ACTIVITY
										(CONTAINER_ID,
										SERVICE_CODE,
										ACTIVITY_DATE,
										FOREIGN_ID,
										ACTIVITY_NUM,
										USERNAME,
										ARRIVAL_NUM)
									VALUES
										('".$cont_num."',
										'10',
										SYSDATE,
										'".($max_act)."',
										'2',
										'".$user."',
										'".$vessel."')";
//							echo $sql."<br>";
							$insert = ociparse($rfconn, $sql);
							ociexecute($insert);
							$sql = "INSERT INTO CONTAINER_DETAILS
										(CONTAINER_DETAIL_ID,
										SLOT,
										KG,
										LOAD_PORT,
										TEMP_CELSIUS,
										TYPE,
										LOADED,
										CARRIER,
										DISCHARGE_PORT,
										ARRIVAL_NUM,
										DESCRIPTION_OF_GOODS)
									VALUES
										('".($max_act)."',
										'".$slot."',
										'".$weight."',
										'".$load_port."',
										'".$temperature."',
										'".$cont_type."',
										'".$cont_full."',
										'".$resp_carrier."',
										'USILG',
										'".$vessel."',
										'".$cargo_desc."')";
//							echo $sql."<br>";
							$insert = ociparse($rfconn, $sql);
							ociexecute($insert);

						}
					}
				break;

				case "UNT":  // Message Trailer
					// no relevant data
				break;
				case "UNZ":  // Interchange Trailer
					// no relevant data
				break;
			}

		}
			
		fclose($fp);
		echo "<font color=\"#0000FF\">File Imported.</font><br>";

	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Baplie File (.EDI file) Import
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	echo $link;
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="BaplieIn.php" method="post">
	<tr>
		<td width="15%" align="left">Vessel:</td>
		<td align="left"><input name="vessel" type="text" size="15" maxlength="15" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Import Baplie"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");