<?
/*
*	Screen that will sort a WIP file for inventory, saving them OODLES of time.
*	Oct 2010.
********************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Sort WIP File";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
//  $conn = ora_logon("SAG_OWNER@172.22.15.238", "orcl");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);

	$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rf_conn));
		exit;
	}
   $Short_Term_Cursor_RF = ora_open($rf_conn);

   $submit = $HTTP_POST_VARS['submit'];
   $vessel = $HTTP_POST_VARS['vessel'];

	if($submit != "" && $HTTP_POST_FILES['import_file']['name'] != "" && $vessel != ""){
/*		$sql = "SELECT NVL(MAX(UPLOAD_ID), 0) THE_MAX
				FROM WIP_UPLOADS";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$upload_num = $Short_Term_Row['THE_MAX'] + 1; */

		$impfilename = "ves".$vessel."on".date(mdYhis)."file".basename($HTTP_POST_FILES['import_file']['name']."id".$upload_num);
		$target_path_import = "./WIP_uploads/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}
/*
		// record the upload....
		$sql = "ISNERT INTO WIP_UPLOADS
					(UPLOAD_ID,
					LR_NUM,
					UPLOAD_USER,
					UPLOAD_TIME,
					FILE_NAME)
				VALUES
					('".$upload_num."',
					'".$vessel."',
					'".$user."',
					SYSDATE,
					'".$impfilename."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
*/
		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		$upload_valid = true;

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$useable_data[($i - 1)]["shipline"] = trim($data->sheets[0]['cells'][$i][1]);
//			$useable_data[($i - 1)]["shiplinenum"] = trim($data->sheets[0]['cells'][$i][2]);
//			$useable_data[($i - 1)]["port"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["custname"] = str_replace("'", "`", trim($data->sheets[0]['cells'][$i][2]));
			$useable_data[($i - 1)]["bol"] = trim($data->sheets[0]['cells'][$i][3]);
//			$useable_data[($i - 1)]["other_bol"] = trim($data->sheets[0]['cells'][$i][4]);
//			$useable_data[($i - 1)]["commcode"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["commname"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["plts"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["ctns"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["kgs"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["lbs"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["cont_num"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["cont_type"] = trim($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["nonfum_num"] = trim($data->sheets[0]['cells'][$i][11]);
//			$useable_data[($i - 1)]["notes"] = trim($data->sheets[0]['cells'][$i][12]);
//			$useable_data[($i - 1)]["action"] = trim($data->sheets[0]['cells'][$i][13]);
			$useable_data[($i - 1)]["cont_id"] = trim($data->sheets[0]['cells'][$i][12]);
//			$useable_data[($i - 1)]["comments"] = trim($data->sheets[0]['cells'][$i][15]);
			$useable_data[($i - 1)]["custnum"] = trim($data->sheets[0]['cells'][$i][13]);

//			print_r($useable_data[($i - 1)]);
		}

		$upload_valid = validate_upload($useable_data, $conn, $rf_conn);

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
/*			$sql = "SELECT RECEIVER_ID
					FROM 
						(SELECT CHILEAN_CONSIGNEE_NAME CCN, RECEIVER_ID
							FROM CHILEAN_CUSTOMER_MAP_V2)
						CCM
					WHERE CCN = '".$useable_data[($i - 1)]["custname"]."'";
			ora_parse($Short_Term_Cursor_RF, $sql);
			ora_exec($Short_Term_Cursor_RF);
			ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$useable_data[($i - 1)]["custnum"] = $Short_Term_Row['RECEIVER_ID']; */
			$sql = "SELECT DSPC_COMMODITY_CODE
					FROM 					
						(SELECT CHILEAN_COMMODITY_NAME CCC, DSPC_COMMODITY_CODE
						FROM CHILEAN_COMMODITY_MAP_V2)
						CCM
					WHERE CCC = '".$useable_data[($i - 1)]["commname"]."'";
			ora_parse($Short_Term_Cursor_RF, $sql);
			ora_exec($Short_Term_Cursor_RF);
			ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$useable_data[($i - 1)]["commcode"] = $Short_Term_Row['DSPC_COMMODITY_CODE'];
		}


		if($upload_valid != ""){
			echo "<font color=\"#FF0000\">The following errors were found in the uploaded file:<br><br>".$upload_valid."<br>Please Correct and Resubmit.</font>";
			// this file didn't work, update accordingly.
/*			$sql = "UPDAET WIP_UPLOADS
					SET VALID = 'N'
					WHERE UPLOAD_ID = '".$upload_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor); */
		} else {
			// blank the table for its next use
//			$sql = "DELETE FROM EXCEL_MANIFEST_SORT WHERE LR_NUM = '".$vessel."'";
			$sql = "DELETE FROM EXCEL_MANIFEST_SORT";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			// insert into sorting table
			for($i = 1; $i <= $data->sheets[0]['numRows']; $i++){
				// due to an off-by-1 error i cant track, I'm circumventing a blank line...
				if($useable_data[($i - 1)]['shipline'] != ""){
/*					$sql = "INSERT INTO EXCEL_MANIFEST_SORT
								(SHIP_LINE_NAME,
								SHIP_LINE_NUM,
								PORT,
								CUSTOMER_ID,
								CUSTOMER_NAME,
								BOL,
								OTHER_BOL,
								COMMODITY_CODE,
								COMMODITY_NAME,
								PLTS,
								CTNS,
								KGS,
								LBS,
								CONTAINERS,
								CONTAINER_TYPE,
								NONFUME,
								NOTES,
								ACTION,
								CONTAINER_NUM,
								LR_NUM,
								UPLOAD_ID)
							VALUES
								('".$useable_data[($i - 1)]['shipline']."',
								'".$useable_data[($i - 1)]['shiplinenum']."',
								'".$useable_data[($i - 1)]['port']."',
								'".$useable_data[($i - 1)]['custnum']."',
								'".$useable_data[($i - 1)]['custname']."',
								'".$useable_data[($i - 1)]['bol']."',
								'".$useable_data[($i - 1)]['other_bol']."',
								'".$useable_data[($i - 1)]['commcode']."',
								'".$useable_data[($i - 1)]['commname']."',
								'".$useable_data[($i - 1)]['plts']."',
								'".$useable_data[($i - 1)]['ctns']."',
								'".$useable_data[($i - 1)]['kgs']."',
								'".$useable_data[($i - 1)]['lbs']."',
								'".$useable_data[($i - 1)]['cont_num']."',
								'".$useable_data[($i - 1)]['cont_type']."',
								'".$useable_data[($i - 1)]['nonfum_num']."',
								'".$useable_data[($i - 1)]['notes']."',
								'".$useable_data[($i - 1)]['action']."',
								'".$useable_data[($i - 1)]['cont_id']."',
								'".$vessel."',
								'".$upload_num."')"; */

//								OTHER_BOL,
//								NOTES,
//								ACTION,
//								COMMENTS
//								'".$useable_data[($i - 1)]['other_bol']."',
//								'".$useable_data[($i - 1)]['notes']."',
//								'".$useable_data[($i - 1)]['action']."',
//								'".$useable_data[($i - 1)]['comments']."'					
					$sql = "INSERT INTO EXCEL_MANIFEST_SORT
								(SHIP_LINE_NAME,
								CUSTOMER_ID,
								CUSTOMER_NAME,
								BOL,
								COMMODITY_CODE,
								COMMODITY_NAME,
								PLTS,
								CTNS,
								KGS,
								LBS,
								CONTAINERS,
								CONTAINER_TYPE,
								NONFUME,
								CONTAINER_NUM,
								LR_NUM)
							VALUES
								('".$useable_data[($i - 1)]['shipline']."',
								'".$useable_data[($i - 1)]['custnum']."',
								'".$useable_data[($i - 1)]['custname']."',
								'".$useable_data[($i - 1)]['bol']."',
								'".$useable_data[($i - 1)]['commcode']."',
								'".$useable_data[($i - 1)]['commname']."',
								'".$useable_data[($i - 1)]['plts']."',
								'".$useable_data[($i - 1)]['ctns']."',
								'".$useable_data[($i - 1)]['kgs']."',
								'".$useable_data[($i - 1)]['lbs']."',
								'".$useable_data[($i - 1)]['cont_num']."',
								'".$useable_data[($i - 1)]['cont_type']."',
								'".$useable_data[($i - 1)]['nonfum_num']."',
								'".$useable_data[($i - 1)]['cont_id']."',
								'".$vessel."')";
//					echo $sql."<br>";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				}
			}

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
					WHERE LR_NUM = '".$vessel."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				echo "Vessel ".$vessel." not found in Vessel Table.\n";
				exit;
			} else {
				$vessel_name = $Short_Term_Row['VESSEL_NAME'];
			}

			$filename = "./safe_to_delete_tempfiles/Vessel".$vessel."ManifestSort.xls";
			$handle = fopen($filename, "w");
			if (!$handle){
				echo "File ".$filename." could not be opened, please contact TS.\n";
				exit;
			}
			$filenameA = "./safe_to_delete_tempfiles/Vessel".$vessel."ManifestNonFumSort.xls";
			$handleA = fopen($filenameA, "w");
			if (!$handleA){
				echo "File ".$filenameA." could not be opened, please contact TS.\n";
				exit;
			}
			$filenameB = "./safe_to_delete_tempfiles/Vessel".$vessel."ManifestPreFumSort.xls";
			$handleB = fopen($filenameB, "w");
			if (!$handleB){
				echo "File ".$filenameB." could not be opened, please contact TS.\n";
				exit;
			}
			$filenameC = "./safe_to_delete_tempfiles/Vessel".$vessel."ManifestContainerSort.xls";
			$handleC = fopen($filenameC, "w");
			if (!$handleC){
				echo "File ".$filenameC." could not be opened, please contact TS.\n";
				exit;
			}
			$filenameCombo4 = "./safe_to_delete_tempfiles/Vessel".$vessel."Manifest4FileCombinationSort.xls";
			$handleCombo4 = fopen($filenameCombo4, "w");
			if (!$handleCombo4){
				echo "File ".$filenameCombo4." could not be opened, please contact TS.\n";
				exit;
			}


			$filename2 = "./safe_to_delete_tempfiles/Vessel".$vessel."CustomerSort.xls";
			$handle2 = fopen($filename2, "w");
			if (!$handle2){
				echo "File ".$filename2." could not be opened, please contact TS.\n";
				exit;
			}

			$filename3 = "./safe_to_delete_tempfiles/Vessel".$vessel."FCLLCLSort.xls";
			$handle3 = fopen($filename3, "w");
			if (!$handle3){
				echo "File ".$filename3." could not be opened, please contact TS.\n";
				exit;
			}

//								<td>Other B/L</td>
/*
*		Part 1:  by shipping line
********************************/
/*		---A : all cargo */

			$output = "<table><tr><td colspan=\"13\" align=\"center\"><font size=\"4\"><b>".$vessel_name."</b></font></td></tr>
							
							<tr>
								<td align=\"center\"><b>OWNER</b></td>
								<td align=\"center\"><b>OWNER#</b></td>
								<td align=\"center\"><b>B/L</b></td>
								<td align=\"center\"><b>COMM</b></td>
								<td align=\"center\"><b>COMM#</b></td>
								<td align=\"center\"><b>PLTS</b></td>
								<td align=\"center\"><b>CTNS</b></td>
								<td align=\"center\"><b>KGS</b></td>
								<td align=\"center\"><b>LBS</b></td>
								<td colspan=\"2\" align=\"center\"><b>CONT</b></td>
								<td align=\"center\"><b>NONFUM</b></td>
								<td width=\"600\" align=\"center\">CONTAINER</td>
							</tr>";

			$sql = "SELECT * 
					FROM EXCEL_MANIFEST_SORT 
					WHERE LR_NUM = '".$vessel."'
					ORDER BY SHIP_LINE_NAME DESC, CUSTOMER_ID, BOL, COMMODITY_CODE";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No Pallets for All-Cargo file.";
//				exit;
			} else {
				$cur_line = $row['SHIP_LINE_NAME'];
				$cur_cust = $row['CUSTOMER_ID'];
				$cur_vesgroup = $row['GROUP_TOTAL'];

				$line_total_plt = 0;
				$line_total_ctn = 0;
				$line_total_kg = 0;
				$line_total_lb = 0;
				$line_total_nonfum = 0;
				$line_total_cont = 0;

				$cust_total_plt = 0;
				$cust_total_ctn = 0;
				$cust_total_kg = 0;
				$cust_total_lb = 0;
				$cust_total_nonfum = 0;
				$cust_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				$output .= "<tr>
								<td align=\"left\"><b>".$cur_line." CARGO:</b></td>
								<td colspan=\"12\">&nbsp;</td>
							</tr>";

				do {
					
					if(($cur_cust != $row['CUSTOMER_ID']) || 
							($cur_line != $row['SHIP_LINE_NAME'])){
						// either new cust or new shipline, show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_plt."</b></td>
										<td><b>".$cust_total_ctn."</b></td>
										<td><b>".$cust_total_kg."</b></td>
										<td><b>".$cust_total_lb."</b></td>
										<td><b>".$cust_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$cust_total_plt = 0;
						$cust_total_ctn = 0;
						$cust_total_kg = 0;
						$cust_total_lb = 0;
						$cust_total_nonfum = 0;
						$cust_total_cont = 0;

						$cur_cust = $row['CUSTOMER_ID'];
					}
					if(($cur_line != $row['SHIP_LINE_NAME'])){
						// new shipline, show shipline total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align=\"right\"><b>".$cur_line." TOTAL:</b></td>
										<td><b>".$line_total_plt."</b></td>
										<td><b>".$line_total_ctn."</b></td>
										<td><b>".$line_total_kg."</b></td>
										<td><b>".$line_total_lb."</b></td>
										<td><b>".$line_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$line_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$line_total_plt = 0;
						$line_total_ctn = 0;
						$line_total_kg = 0;
						$line_total_lb = 0;
						$line_total_nonfum = 0;
						$line_total_cont = 0;

						$cur_line = $row['SHIP_LINE_NAME'];
						$output .= "<tr>
										<td align=\"left\"><b>".$cur_line." CARGO:</b></td>
										<td colspan=\"12\">&nbsp;</td>
									</tr>";
					}

					
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td align=\"right\">".$row['BOL']."</td>
									<td align=\"right\">".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
									<td>".$row['CONTAINER_NUM']."</td>
								</tr>";

					$line_total_plt += $row['PLTS'];
					$line_total_ctn += $row['CTNS'];
					$line_total_kg += $row['KGS'];
					$line_total_lb += $row['LBS'];
					$line_total_nonfum += $row['NONFUME'];
					$line_total_cont += $row['CONTAINERS'];

					$cust_total_plt += $row['PLTS'];
					$cust_total_ctn += $row['CTNS'];
					$cust_total_kg += $row['KGS'];
					$cust_total_lb += $row['LBS'];
					$cust_total_nonfum += $row['NONFUME'];
					$cust_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_plt."</b></td>
								<td><b>".$cust_total_ctn."</b></td>
								<td><b>".$cust_total_kg."</b></td>
								<td><b>".$cust_total_lb."</b></td>
								<td><b>".$cust_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align=\"right\"><b>".$cur_line." TOTAL:</b></td>
								<td><b>".$line_total_plt."</b></td>
								<td><b>".$line_total_ctn."</b></td>
								<td><b>".$line_total_kg."</b></td>
								<td><b>".$line_total_lb."</b></td>
								<td><b>".$line_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$line_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td colspan=\"5\" align=\"right\"><b>VESSEL TOTAL:</b></td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "</table>";


			}
			fwrite($handle, $output);
			fwrite($handleCombo4, $output);
			fwrite($handleCombo4, "<br><br><br>");
			fclose($handle);
//				$attach1=chunk_split(base64_encode($output));

			/*		---B : NonFum cargo */

			$output = "<table><tr><td colspan=\"13\" align=\"center\"><font size=\"4\"><b>".$vessel_name."</b></font></td></tr>
							
							<tr>
								<td align=\"center\"><b>OWNER</b></td>
								<td align=\"center\"><b>OWNER#</b></td>
								<td align=\"center\"><b>B/L</b></td>
								<td align=\"center\"><b>COMM</b></td>
								<td align=\"center\"><b>COMM#</b></td>
								<td align=\"center\"><b>PLTS</b></td>
								<td align=\"center\"><b>CTNS</b></td>
								<td align=\"center\"><b>KGS</b></td>
								<td align=\"center\"><b>LBS</b></td>
								<td colspan=\"2\" align=\"center\"><b>CONT</b></td>
								<td align=\"center\"><b>NONFUM</b></td>
								<td>&nbsp;</td>
							</tr>";

			$sql = "SELECT * 
					FROM EXCEL_MANIFEST_SORT 
					WHERE LR_NUM = '".$vessel."'
						AND NONFUME > 0
					ORDER BY SHIP_LINE_NAME DESC, CUSTOMER_ID, BOL, COMMODITY_CODE";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No Pallets for NonFum file.";
//				exit;
			} else {
				$cur_line = $row['SHIP_LINE_NAME'];
				$cur_cust = $row['CUSTOMER_ID'];
				$cur_vesgroup = $row['GROUP_TOTAL'];

				$line_total_plt = 0;
				$line_total_ctn = 0;
				$line_total_kg = 0;
				$line_total_lb = 0;
				$line_total_nonfum = 0;
				$line_total_cont = 0;

				$cust_total_plt = 0;
				$cust_total_ctn = 0;
				$cust_total_kg = 0;
				$cust_total_lb = 0;
				$cust_total_nonfum = 0;
				$cust_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				$output .= "<tr>
								<td><b>".$cur_line." NON FUM CARGO:</b></td>
								<td colspan=\"12\">&nbsp;</td>
							</tr>";

				do {
					
					if(($cur_cust != $row['CUSTOMER_ID']) || 
							($cur_line != $row['SHIP_LINE_NAME'])){
						// either new cust or new shipline, show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_plt."</b></td>
										<td><b>".$cust_total_ctn."</b></td>
										<td><b>".$cust_total_kg."</b></td>
										<td><b>".$cust_total_lb."</b></td>
										<td><b>".$cust_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$cust_total_plt = 0;
						$cust_total_ctn = 0;
						$cust_total_kg = 0;
						$cust_total_lb = 0;
						$cust_total_nonfum = 0;
						$cust_total_cont = 0;

						$cur_cust = $row['CUSTOMER_ID'];
					}
					if(($cur_line != $row['SHIP_LINE_NAME'])){
						// new shipline, show shipline total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align=\"right\"><b>".$cur_line." NON FUM TOTAL:</b></td>
										<td><b>".$line_total_plt."</b></td>
										<td><b>".$line_total_ctn."</b></td>
										<td><b>".$line_total_kg."</b></td>
										<td><b>".$line_total_lb."</b></td>
										<td><b>".$line_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$line_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$line_total_plt = 0;
						$line_total_ctn = 0;
						$line_total_kg = 0;
						$line_total_lb = 0;
						$line_total_nonfum = 0;
						$line_total_cont = 0;

						$cur_line = $row['SHIP_LINE_NAME'];
						$output .= "<tr>
										<td><b>".$cur_line." NON FUM CARGO:</b></td>
										<td colspan=\"12\">&nbsp;</td>
									</tr>";
					}

					
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td align=\"right\">".$row['BOL']."</td>
									<td align=\"right\">".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
									<td>&nbsp;</td>
								</tr>";

					$line_total_plt += $row['PLTS'];
					$line_total_ctn += $row['CTNS'];
					$line_total_kg += $row['KGS'];
					$line_total_lb += $row['LBS'];
					$line_total_nonfum += $row['NONFUME'];
					$line_total_cont += $row['CONTAINERS'];

					$cust_total_plt += $row['PLTS'];
					$cust_total_ctn += $row['CTNS'];
					$cust_total_kg += $row['KGS'];
					$cust_total_lb += $row['LBS'];
					$cust_total_nonfum += $row['NONFUME'];
					$cust_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_plt."</b></td>
								<td><b>".$cust_total_ctn."</b></td>
								<td><b>".$cust_total_kg."</b></td>
								<td><b>".$cust_total_lb."</b></td>
								<td><b>".$cust_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align=\"right\"><b>".$cur_line." NON FUM TOTAL:</b></td>
								<td><b>".$line_total_plt."</b></td>
								<td><b>".$line_total_ctn."</b></td>
								<td><b>".$line_total_kg."</b></td>
								<td><b>".$line_total_lb."</b></td>
								<td><b>".$line_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$line_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td align=\"right\" colspan=\"5\"><b>VESSEL NON FUM TOTAL</b></td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "</table>";


			}
			fwrite($handleA, $output);
			fwrite($handleCombo4, $output);
			fwrite($handleCombo4, "<br><br><br>");
			fclose($handleA);
//				$attach1=chunk_split(base64_encode($output));

			/*		---C : PreFum cargo */

			$output = "<table><tr><td colspan=\"13\" align=\"center\"><font size=\"4\"><b>".$vessel_name."</b></font></td></tr>
							
							<tr>
								<td align=\"center\"><b>OWNER</b></td>
								<td align=\"center\"><b>OWNER#</b></td>
								<td align=\"center\"><b>B/L</b></td>
								<td align=\"center\"><b>COMM</b></td>
								<td align=\"center\"><b>COMM#</b></td>
								<td align=\"center\"><b>PLTS</b></td>
								<td align=\"center\"><b>CTNS</b></td>
								<td align=\"center\"><b>KGS</b></td>
								<td align=\"center\"><b>LBS</b></td>
								<td colspan=\"2\" align=\"center\"><b>CONT</b></td>
								<td align=\"center\"><b>NONFUM</b></td>
								<td>&nbsp;</td>
							</tr>";

			$sql = "SELECT * 
					FROM EXCEL_MANIFEST_SORT 
					WHERE LR_NUM = '".$vessel."'
						AND (NONFUME IS NULL OR NONFUME = 0)
					ORDER BY SHIP_LINE_NAME DESC, CUSTOMER_ID, BOL, COMMODITY_CODE";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No Pallets for PreFum file.";
//				exit;
			} else {
				$cur_line = $row['SHIP_LINE_NAME'];
				$cur_cust = $row['CUSTOMER_ID'];
				$cur_vesgroup = $row['GROUP_TOTAL'];

				$line_total_plt = 0;
				$line_total_ctn = 0;
				$line_total_kg = 0;
				$line_total_lb = 0;
				$line_total_nonfum = 0;
				$line_total_cont = 0;

				$cust_total_plt = 0;
				$cust_total_ctn = 0;
				$cust_total_kg = 0;
				$cust_total_lb = 0;
				$cust_total_nonfum = 0;
				$cust_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				$output .= "<tr>
								<td><b>".$cur_line." PRE FUM CARGO:</b></td>
								<td colspan=\"12\">&nbsp;</td>
							</tr>";

				do {
					
					if(($cur_cust != $row['CUSTOMER_ID']) || 
							($cur_line != $row['SHIP_LINE_NAME'])){
						// either new cust or new shipline, show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_plt."</b></td>
										<td><b>".$cust_total_ctn."</b></td>
										<td><b>".$cust_total_kg."</b></td>
										<td><b>".$cust_total_lb."</b></td>
										<td><b>".$cust_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$cust_total_plt = 0;
						$cust_total_ctn = 0;
						$cust_total_kg = 0;
						$cust_total_lb = 0;
						$cust_total_nonfum = 0;
						$cust_total_cont = 0;

						$cur_cust = $row['CUSTOMER_ID'];
					}
					if(($cur_line != $row['SHIP_LINE_NAME'])){
						// new shipline, show shipline total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align=\"right\"><b>".$cur_line." PRE FUM TOTAL:</b></td>
										<td><b>".$line_total_plt."</b></td>
										<td><b>".$line_total_ctn."</b></td>
										<td><b>".$line_total_kg."</b></td>
										<td><b>".$line_total_lb."</b></td>
										<td><b>".$line_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$line_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$line_total_plt = 0;
						$line_total_ctn = 0;
						$line_total_kg = 0;
						$line_total_lb = 0;
						$line_total_nonfum = 0;
						$line_total_cont = 0;

						$cur_line = $row['SHIP_LINE_NAME'];
						$output .= "<tr>
										<td><b>".$cur_line." PRE FUM CARGO:</b></td>
										<td colspan=\"12\">&nbsp;</td>
									</tr>";
					}

					
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td align=\"right\">".$row['BOL']."</td>
									<td align=\"right\">".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
									<td>&nbsp;</td>
								</tr>";

					$line_total_plt += $row['PLTS'];
					$line_total_ctn += $row['CTNS'];
					$line_total_kg += $row['KGS'];
					$line_total_lb += $row['LBS'];
					$line_total_nonfum += $row['NONFUME'];
					$line_total_cont += $row['CONTAINERS'];

					$cust_total_plt += $row['PLTS'];
					$cust_total_ctn += $row['CTNS'];
					$cust_total_kg += $row['KGS'];
					$cust_total_lb += $row['LBS'];
					$cust_total_nonfum += $row['NONFUME'];
					$cust_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_plt."</b></td>
								<td><b>".$cust_total_ctn."</b></td>
								<td><b>".$cust_total_kg."</b></td>
								<td><b>".$cust_total_lb."</b></td>
								<td><b>".$cust_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align=\"right\"><b>".$cur_line." PRE FUM TOTAL:</b></td>
								<td><b>".$line_total_plt."</b></td>
								<td><b>".$line_total_ctn."</b></td>
								<td><b>".$line_total_kg."</b></td>
								<td><b>".$line_total_lb."</b></td>
								<td><b>".$line_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$line_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td align=\"right\" colspan=\"5\"><b>VESSEL PRE FUM TOTAL</b></td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "</table>";


			}
			fwrite($handleB, $output);
			fwrite($handleCombo4, $output);
			fwrite($handleCombo4, "<br><br><br>");
			fclose($handleB);
//				$attach1=chunk_split(base64_encode($output));

			/*		---D : Container cargo */

			$output = "<table><tr><td colspan=\"13\" align=\"center\"><font size=\"4\"><b>".$vessel_name."</b></font></td></tr>
							
							<tr>
								<td align=\"center\"><b>OWNER</b></td>
								<td align=\"center\"><b>OWNER#</b></td>
								<td align=\"center\"><b>B/L</b></td>
								<td align=\"center\"><b>COMM</b></td>
								<td align=\"center\"><b>COMM#</b></td>
								<td align=\"center\"><b>PLTS</b></td>
								<td align=\"center\"><b>CTNS</b></td>
								<td align=\"center\"><b>KGS</b></td>
								<td align=\"center\"><b>LBS</b></td>
								<td colspan=\"2\" align=\"center\"><b>CONT</b></td>
								<td align=\"center\"><b>NONFUM</b></td>
								<td>&nbsp;</td>
							</tr>";

			$sql = "SELECT * 
					FROM EXCEL_MANIFEST_SORT 
					WHERE LR_NUM = '".$vessel."'
						AND CONTAINERS > 0
					ORDER BY SHIP_LINE_NAME DESC, CUSTOMER_ID, BOL, COMMODITY_CODE";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No pallets for Container file.";
//				exit;
			} else {
				$cur_line = $row['SHIP_LINE_NAME'];
				$cur_cust = $row['CUSTOMER_ID'];
				$cur_vesgroup = $row['GROUP_TOTAL'];

				$line_total_plt = 0;
				$line_total_ctn = 0;
				$line_total_kg = 0;
				$line_total_lb = 0;
				$line_total_nonfum = 0;
				$line_total_cont = 0;

				$cust_total_plt = 0;
				$cust_total_ctn = 0;
				$cust_total_kg = 0;
				$cust_total_lb = 0;
				$cust_total_nonfum = 0;
				$cust_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				$output .= "<tr>
								<td><b>".$cur_line." CONT CARGO:</b></td>
								<td colspan=\"12\">&nbsp;</td>
							</tr>";

				do {
					
					if(($cur_cust != $row['CUSTOMER_ID']) || 
							($cur_line != $row['SHIP_LINE_NAME'])){
						// either new cust or new shipline, show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_plt."</b></td>
										<td><b>".$cust_total_ctn."</b></td>
										<td><b>".$cust_total_kg."</b></td>
										<td><b>".$cust_total_lb."</b></td>
										<td><b>".$cust_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$cust_total_plt = 0;
						$cust_total_ctn = 0;
						$cust_total_kg = 0;
						$cust_total_lb = 0;
						$cust_total_nonfum = 0;
						$cust_total_cont = 0;

						$cur_cust = $row['CUSTOMER_ID'];
					}
					if(($cur_line != $row['SHIP_LINE_NAME'])){
						// new shipline, show shipline total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align=\"right\"><b>".$cur_line." CONT TOTAL:</b></td>
										<td><b>".$line_total_plt."</b></td>
										<td><b>".$line_total_ctn."</b></td>
										<td><b>".$line_total_kg."</b></td>
										<td><b>".$line_total_lb."</b></td>
										<td><b>".$line_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$line_total_nonfum."</b></td>
										<td>&nbsp;</td>
									</tr>";

						$line_total_plt = 0;
						$line_total_ctn = 0;
						$line_total_kg = 0;
						$line_total_lb = 0;
						$line_total_nonfum = 0;
						$line_total_cont = 0;

						$cur_line = $row['SHIP_LINE_NAME'];
						$output .= "<tr>
										<td><b>".$cur_line." CONT CARGO:</b></td>
										<td colspan=\"12\">&nbsp;</td>
									</tr>";
					}

					
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td align=\"right\">".$row['BOL']."</td>
									<td align=\"right\">".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
									<td>&nbsp;</td>
								</tr>";

					$line_total_plt += $row['PLTS'];
					$line_total_ctn += $row['CTNS'];
					$line_total_kg += $row['KGS'];
					$line_total_lb += $row['LBS'];
					$line_total_nonfum += $row['NONFUME'];
					$line_total_cont += $row['CONTAINERS'];

					$cust_total_plt += $row['PLTS'];
					$cust_total_ctn += $row['CTNS'];
					$cust_total_kg += $row['KGS'];
					$cust_total_lb += $row['LBS'];
					$cust_total_nonfum += $row['NONFUME'];
					$cust_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_plt."</b></td>
								<td><b>".$cust_total_ctn."</b></td>
								<td><b>".$cust_total_kg."</b></td>
								<td><b>".$cust_total_lb."</b></td>
								<td><b>".$cust_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align=\"right\"><b>".$cur_line." CONT TOTAL:</b></td>
								<td><b>".$line_total_plt."</b></td>
								<td><b>".$line_total_ctn."</b></td>
								<td><b>".$line_total_kg."</b></td>
								<td><b>".$line_total_lb."</b></td>
								<td><b>".$line_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$line_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td align=\"right\" colspan=\"5\"><b>VESSEL CONT TOTAL</b></td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "</table>";


			}
			fwrite($handleC, $output);
			fwrite($handleCombo4, $output);
			fclose($handleC);
			fclose($handleCombo4);
//				$attach1=chunk_split(base64_encode($output));

/*
*		Part 2:  by cust
********************************/
			$output = "<table><tr><td colspan=\"12\" align=\"center\">".$vessel_name."</td></tr>
							
							<tr>
								<td align=\"center\">OWNER</td>
								<td align=\"center\">OWNER#</td>
								<td align=\"center\">B/L</td>
								<td align=\"center\">COMM</td>
								<td align=\"center\">COMM#</td>
								<td align=\"center\">PLTS</td>
								<td align=\"center\">CTNS</td>
								<td align=\"center\">KGS</td>
								<td align=\"center\">LBS</td>
								<td colspan=\"2\" align=\"center\">CONT</td>
								<td align=\"center\">NONFUM</td>
							</tr>";

			$sql = "SELECT * FROM EXCEL_MANIFEST_SORT
					WHERE LR_NUM = '".$vessel."'
					ORDER BY CUSTOMER_ID, BOL, COMMODITY_CODE";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No Pallets for By-Custoemr file.";
//				exit;
			} else {
//				$cur_line = $row['SHIP_LINE_NUM'];
				$cur_cust = $row['CUSTOMER_ID'];

				$cust_total_plt = 0;
				$cust_total_ctn = 0;
				$cust_total_kg = 0;
				$cust_total_lb = 0;
				$cust_total_nonfum = 0;
				$cust_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				do {
					if($cur_cust != $row['CUSTOMER_ID']) {
						// new cust show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_plt."</b></td>
										<td><b>".$cust_total_ctn."</b></td>
										<td><b>".$cust_total_kg."</b></td>
										<td><b>".$cust_total_lb."</b></td>
										<td><b>".$cust_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$cust_total_nonfum."</b></td>
									</tr>";

						$cust_total_plt = 0;
						$cust_total_ctn = 0;
						$cust_total_kg = 0;
						$cust_total_lb = 0;
						$cust_total_nonfum = 0;
						$cust_total_cont = 0;

						$cur_cust = $row['CUSTOMER_ID'];
					}
											
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td>".$row['BOL']."</td>
									<td>".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
								</tr>";

					$cust_total_plt += $row['PLTS'];
					$cust_total_ctn += $row['CTNS'];
					$cust_total_kg += $row['KGS'];
					$cust_total_lb += $row['LBS'];
					$cust_total_nonfum += $row['NONFUME'];
					$cust_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_plt."</b></td>
								<td><b>".$cust_total_ctn."</b></td>
								<td><b>".$cust_total_kg."</b></td>
								<td><b>".$cust_total_lb."</b></td>
								<td><b>".$cust_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$cust_total_nonfum."</b></td>
							</tr>";

				$output .= "<tr>
								<td><b>GRAND TOTAL</b></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
							</tr>";
				$output .= "</table>";

			
			}
			fwrite($handle2, $output);
			fclose($handle2);
//				$attach2=chunk_split(base64_encode($output));

/*
*		Part 3:  by FCL/LCL
********************************/
//								<td align=\"center\">Other B/L</td>
//								<td align=\"center\">NOTES</td>
//								<td align=\"center\">ACTION</td>
			$output = "<table><tr><td colspan=\"13\" align=\"center\">".$vessel_name."</td></tr>
							<tr>
								<td align=\"center\">OWNER</td>
								<td align=\"center\">OWNER#</td>
								<td align=\"center\">B/L</td>
								<td align=\"center\">COMM</td>
								<td align=\"center\">COMM#</td>
								<td align=\"center\">PLTS</td>
								<td align=\"center\">CTNS</td>
								<td align=\"center\">KGS</td>
								<td align=\"center\">LBS</td>
								<td colspan=\"2\" align=\"center\">CONT</td>
								<td align=\"center\">NONFUM</td>
								<td align=\"center\">CONTAINER</td>
							</tr>";

			$sql = "SELECT * FROM EXCEL_MANIFEST_SORT
					WHERE LR_NUM = '".$vessel."'
						AND CONTAINER_TYPE IS NOT NULL
					ORDER BY CONTAINER_TYPE, CUSTOMER_ID, CONTAINER_NUM, BOL";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$output .= "No Pallets for FCL/LCL file.";
//				exit;
			} else {
				$cur_FCL = $row['CONTAINER_TYPE'];

				$FCL_total_plt = 0;
				$FCL_total_ctn = 0;
				$FCL_total_kg = 0;
				$FCL_total_lb = 0;
				$FCL_total_nonfum = 0;
				$FCL_total_cont = 0;

				$grand_total_plt = 0;
				$grand_total_ctn = 0;
				$grand_total_kg = 0;
				$grand_total_lb = 0;
				$grand_total_nonfum = 0;
				$grand_total_cont = 0;

				$output .= "<tr>
								<td><b>".$cur_FCL."</b></td>
								<td colspan=\"12\">&nbsp;</td>
							</tr>";
				do {
					if($cur_FCL != $row['CONTAINER_TYPE']) {
						// new cust show cust total
						$output .= "<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$FCL_total_plt."</b></td>
										<td><b>".$FCL_total_ctn."</b></td>
										<td><b>".$FCL_total_kg."</b></td>
										<td><b>".$FCL_total_lb."</b></td>
										<td><b>".$FCL_total_cont."</b></td>
										<td>&nbsp;</td>
										<td><b>".$FCL_total_nonfum."</b></td>
									</tr>";
						$output .= "<tr>
										<td><b>".$row['CONTAINER_TYPE']."</b></td>
										<td colspan=\"12\">&nbsp;</td>
							</tr>";

						$FCL_total_plt = 0;
						$FCL_total_ctn = 0;
						$FCL_total_kg = 0;
						$FCL_total_lb = 0;
						$FCL_total_nonfum = 0;
						$FCL_total_cont = 0;

						$cur_FCL = $row['CONTAINER_TYPE'];
					}

//									<td>".$row['ACTION']."</td>
//									<td>".$row['OTHER_BOL']."</td>
//									<td>".$row['NOTES']."</td>
					$output .= "<tr>
									<td>".$row['CUSTOMER_NAME']."</td>
									<td>".$row['CUSTOMER_ID']."</td>
									<td>".$row['BOL']."</td>
									<td>".$row['COMMODITY_NAME']."</td>
									<td>".$row['COMMODITY_CODE']."</td>
									<td>".$row['PLTS']."</td>
									<td>".$row['CTNS']."</td>
									<td>".$row['KGS']."</td>
									<td>".$row['LBS']."</td>
									<td>".$row['CONTAINERS']."</td>
									<td>".$row['CONTAINER_TYPE']."</td>
									<td>".$row['NONFUME']."</td>
									<td>".$row['CONTAINER_NUM']."</td>
								</tr>";

					$FCL_total_plt += $row['PLTS'];
					$FCL_total_ctn += $row['CTNS'];
					$FCL_total_kg += $row['KGS'];
					$FCL_total_lb += $row['LBS'];
					$FCL_total_nonfum += $row['NONFUME'];
					$FCL_total_cont += $row['CONTAINERS'];

					$grand_total_plt += $row['PLTS'];
					$grand_total_ctn += $row['CTNS'];
					$grand_total_kg += $row['KGS'];
					$grand_total_lb += $row['LBS'];
					$grand_total_nonfum += $row['NONFUME'];
					$grand_total_cont += $row['CONTAINERS'];

				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$FCL_total_plt."</b></td>
								<td><b>".$FCL_total_ctn."</b></td>
								<td><b>".$FCL_total_kg."</b></td>
								<td><b>".$FCL_total_lb."</b></td>
								<td><b>".$FCL_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$FCL_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "<tr>
								<td><b>GRAND TOTAL</b></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_plt."</b></td>
								<td><b>".$grand_total_ctn."</b></td>
								<td><b>".$grand_total_kg."</b></td>
								<td><b>".$grand_total_lb."</b></td>
								<td><b>".$grand_total_cont."</b></td>
								<td>&nbsp;</td>
								<td><b>".$grand_total_nonfum."</b></td>
								<td>&nbsp;</td>
							</tr>";
				$output .= "</table>";

			
			}
			fwrite($handle3, $output);
			fclose($handle3);
//				$attach3=chunk_split(base64_encode($output));

/*
			$sql = "UPDTAE WIP_UPLOADS
					SET VALID = 'Y'
					WHERE UPLOAD_ID = '".$upload_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
*/
/*
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'WIPFILES'";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
			ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$mailTO = $email_row['TO'];
			$mailTO = str_replace("_0_", $userdata['user_email'], $mailTO);

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
			$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = $email_row['SUBJECT'];

			$body = $email_row['NARRATIVE'];

			$mailSubject = str_replace("_1_", $vessel_name, $mailSubject);
			
			$body = str_replace("_2_", $vessel_name, $body);

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"Vessel".$vessel."ManifestSort.xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach1;
			$Content.="\r\n";

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"Vessel".$vessel."CustomerSort.xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach2;
			$Content.="\r\n";

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"Vessel".$vessel."FCLLCLSort.xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach3;
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
							'".$user."',
							SYSDATE,
							'EMAIL',
							'WIPFILES',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($ED_cursor, $sql);
				ora_exec($ED_cursor);

				echo "<font color=\"#0000FF\">Sort files have been emailed.</font>";
			}
*/		}
	}
	

				

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Sort WIP file</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="file_submit" action="sort_a_WIP.php" method="post">
	<tr>
		<td><font size="3" face="Verdana">Enter Vessel:</font><input type="text" name="vessel" size="10" maxlength="10" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Sort File"></td>
	</tr>
</form>
<?
	if($filename != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td><font size="3" face = "Verdana" color="#0066CC">Report Generated.</font></td>
	</tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename; ?>">Manifest Sort</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filenameA; ?>">Manifest Sort (NonFum Only)</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filenameB; ?>">Manifest Sort (Prefum Only)</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filenameC; ?>">Manifest Sort (Containerized only)</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filenameCombo4; ?>">Four-In-One</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename2; ?>">Customer Sort</a>
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename3; ?>">FCLLCL Sort</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	}
	include("pow_footer.php");















function validate_upload($useable_data, $conn, $rf_conn){
   $cursor = ora_open($conn);
   $RFcursor = ora_open($rf_conn);

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";
/*
			$useable_data[($i - 1)]["shipline"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["custname"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["bol"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["other_bol"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["commname"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["plts"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["ctns"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["kgs"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["lbs"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["cont_num"] = trim($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["cont_type"] = trim($data->sheets[0]['cells'][$i][11]);
			$useable_data[($i - 1)]["nonfum_num"] = trim($data->sheets[0]['cells'][$i][12]);
			$useable_data[($i - 1)]["notes"] = trim($data->sheets[0]['cells'][$i][13]);
			$useable_data[($i - 1)]["action"] = trim($data->sheets[0]['cells'][$i][14]);
			$useable_data[($i - 1)]["cont_id"] = trim($data->sheets[0]['cells'][$i][15]);
			$useable_data[($i - 1)]["comments"] = trim($data->sheets[0]['cells'][$i][16]);
*/


// step 2, for each line...
//	$i = 1;
	$i = 0;
	while($useable_data[$i]["shipline"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

// 2 - 1)  shipline
		if(strlen($useable_data[$i]["shipline"]) > 40){
			$return .= "Line: ".$badline." - Shipping Line cannot be longer than 40 characters.<br>";
		}
/*
		if($useable_data[$i]["shipline"] != "" && !ereg("^([0-9a-zA-Z])+$", $useable_data[$i]["shipline"])){
			$return .= "Line: ".$badline." - Shipping Line must be Alphanumeric.<br>";
		}
*/
		if($useable_data[$i]["shipline"] == ""){
			$return .= "Line: ".$badline." - Shipping Line is Required.<br>";
		}
/*
// 2 - 2)  shiplinenum
		if(strlen($useable_data[$i]["shiplinenum"]) > 4){
			$return .= "Line: ".$badline." - Shipping Line# cannot be longer than 4 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["shiplinenum"])){
			$return .= "Line: ".$badline." - Shipping Line# is required, and must be alphanumeric.<br>";
		}
*//*
// 2 - 3)  port
		if(strlen($useable_data[$i]["port"]) > 40){
			$return .= "Line: ".$badline." - Port cannot be longer than 40 characters.<br>";
		}

		if($useable_data[$i]["port"] == ""){
			$return .= "Line: ".$badline." - Port is required.<br>";
		}
*//*
// 2 - 4)  custnum
		if(strlen($useable_data[$i]["custnum"]) > 6){
			$return .= "Line: ".$badline." - Customer# cannot be longer than 6 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["custnum"])){
			$return .= "Line: ".$badline." - Customer# is requried, and must be numeric.<br>";
		}
*/
// 2 - 5)  custname
		if(strlen($useable_data[$i]["custname"]) > 40){
			$return .= "Line: ".$badline." - Customer Name cannot be longer than 40 characters.<br>";
		}
/*
		if($useable_data[$i]["custname"] == ""){
			$return .= "Line: ".$badline." - Customer Name must only be alphanumeric.<br>";
		}
*/
// 2 - 6)  bol
		if(strlen($useable_data[$i]["bol"]) > 20){
			$return .= "Line: ".$badline." - BoL cannot be longer than 20 characters.<br>";
		}

		if($useable_data[$i]["bol"] == ""){
			$return .= "Line: ".$badline." - BoL is required.<br>";
		}

// 2 - 7)  other_bol
/*		if(strlen($useable_data[$i]["other_bol"]) > 20){
			$return .= "Line: ".$badline." - Other-BoL cannot be longer than 15 characters.<br>";
		}
*//*
		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["other_bol"])){
			$return .= "Line: ".$badline." - Other-BoL must be alphanumeric.<br>";
		}
*//*
// 2 - 8)  commcode
		if(strlen($useable_data[$i]["commcode"]) > 12){
			$return .= "Line: ".$badline." - Commodity Code cannot be longer than 12 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["commcode"])){
			$return .= "Line: ".$badline." - Commodity Code is required, and must be numeric.<br>";
		}
*/
// 2 - 9)  commname
		if(strlen($useable_data[$i]["commname"]) > 20){
			$return .= "Line: ".$badline." - Commodity Name cannot be longer than 20 characters.<br>";
		}
/*
		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["commname"])){
			$return .= "Line: ".$badline." - Commodity Name must be alphanumeric.<br>";
		}
*/
// 2 - 10)  plts
		if(strlen($useable_data[$i]["plts"]) > 4){
			$return .= "Line: ".$badline." - Pallet Count cannot be longer than 4 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["plts"])){
			$return .= "Line: ".$badline." - Pallet Count is required, and must be numeric.<br>";
		}

		if($useable_data[$i]["plts"] < 0){
			$return .= "Line: ".$badline." - Pallet Count cannot be negative.<br>";
		}

// 2 - 11)  ctns
		if(strlen($useable_data[$i]["ctns"]) > 6){
			$return .= "Line: ".$badline." - Carton Count cannot be longer than 6 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["ctns"])){
			$return .= "Line: ".$badline." - Carton Count is required, and must be numeric.<br>";
		}

// 2 - 12)  kgs
		if(strlen($useable_data[$i]["kgs"]) > 11){
			$return .= "Line: ".$badline." - KGS cannot be longer than 10 digits.<br>";
		}

		if(!ereg("^([0-9\.])+$", $useable_data[$i]["kgs"])){
			$return .= "Line: ".$badline." - KGS is required, and must be numeric.<br>";
		}

// 2 - 13)  lbs
		if(strlen($useable_data[$i]["lbs"]) > 11){
			$return .= "Line: ".$badline." - LBS cannot be longer than 10 digits.<br>";
		}

		if(!ereg("^([0-9\.])+$", $useable_data[$i]["lbs"])){
			$return .= "Line: ".$badline." - LBS is required, and must be numeric.<br>";
		}

// 2 - 14)  cont_num
		if(strlen($useable_data[$i]["cont_num"]) > 11){
			$return .= "Line: ".$badline." - Container Count cannot be longer than 10 digits.<br>";
		}

		if(!ereg("^([0-9\.])*$", $useable_data[$i]["cont_num"])){
			$return .= "Line: ".$badline." - Container Count must be numeric.<br>";
		}

		if($useable_data[$i]["cont_num"] != "" && $useable_data[$i]["cont_num"] < 0){
			$return .= "Line: ".$badline." - Container Count must not be negative.<br>";
		}

// 2 - 15)  cont_type
		if(strlen($useable_data[$i]["cont_type"]) > 20){
			$return .= "Line: ".$badline." - Container Type cannot be longer than 20 characters.<br>";
		}
/*
		if($useable_data[$i]["cont_type"] != "" && !ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["cont_type"])){
			$return .= "Line: ".$badline." - Container Type must be alphanumeric.<br>";
		}
*/
// 2 - 16)  nonfum_num
		if(strlen($useable_data[$i]["nonfum_num"]) > 20){
			$return .= "Line: ".$badline." - NonFume# cannot be longer than 4 digits.<br>";
		}

		if($useable_data[$i]["nonfum_num"] != "" && !ereg("^([0-9])+$", $useable_data[$i]["nonfum_num"])){
			$return .= "Line: ".$badline." - NonFume# must be numeric.<br>";
		}

		if($useable_data[$i]["nonfum_num"] != "" && $useable_data[$i]["nonfum_num"] < 0){
			$return .= "Line: ".$badline." - NonFume# must not be negative.<br>";
		}

// 2 - 17)  notes
/*		if(strlen($useable_data[$i]["notes"]) > 200){
			$return .= "Line: ".$badline." - Notes cannot be longer than 200 characters.<br>";
		}
*//*
		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["notes"])){
			$return .= "Line: ".$badline." - Notes must be alphanumeric.<br>";
		}
*/
// 2 - 18)  action
/*		if(strlen($useable_data[$i]["action"]) > 50){
			$return .= "Line: ".$badline." - Action cannot be longer than 50 characters.<br>";
		}
*//*
		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["action"])){
			$return .= "Line: ".$badline." - Action must be alphanumeric.<br>";
		}
*/
// 2 - 19)  cont_id
		if(strlen($useable_data[$i]["cont_id"]) > 100){
			$return .= "Line: ".$badline." - Container ID cannot be longer than 100 characters.<br>";
		}
/*
		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["cont_id"])){
			$return .= "Line: ".$badline." - Container ID must be alphanumeric.<br>";
		}
*/
// 2 - 20)  comments
/*		if(strlen($useable_data[$i]["comments"]) > 100){
			$return .= "Line: ".$badline." - Comments cannot be longer than 100 characters.<br>";
		}
*/
// section 3:  valid string, invalid option

// 3 - 1)  Shipping Line
/*		$sql = "SELECT COUNT(*) THE_COUNT
				FROM EXCEL_MANIFEST_SORT_ORDER
				WHERE SHIPLINE = '".$useable_data[$i]["shipline"]."'";
		@ora_parse($cursor, $sql);
		@ora_exec($cursor);
		@ora_fetch_into($cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] <= 0){
			$return .= "Line: ".$badline." - Shipping Line invalid; please refer to the Instruction document.<br>";
		}
*/
// 3 - 2)  Commodity code
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM 
					(SELECT CHILEAN_COMMODITY_NAME CCC, DSPC_COMMODITY_CODE
						FROM CHILEAN_COMMODITY_MAP_V2
					)
					CCM
				WHERE CCC = '".$useable_data[$i]["commname"]."'";
		@ora_parse($RFcursor, $sql);
		@ora_exec($RFcursor);
		@ora_fetch_into($RFcursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] <= 0){
			$return .= "Line: ".$badline." - Commodity Name not in Commodity Table.<br>";
		}
/*
// 3 - 3)  Customer#
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM 
					(SELECT CHILEAN_CONSIGNEE_NAME CCN, RECEIVER_ID
						FROM CHILEAN_CUSTOMER_MAP_V2)
					CCM
				WHERE CCN = '".$useable_data[$i]["custname"]."'";
//		echo $sql."<br>";
		@ora_parse($RFcursor, $sql);
		@ora_exec($RFcursor);
		@ora_fetch_into($RFcursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] <= 0){
			$return .= "Line: ".$badline." - Customer Name not in Customer Table.<br>";
		}
*/
		$i++;

	}

	return $return;

}
?>