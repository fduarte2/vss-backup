<?
/*
*	Adam Walter, Sep 2013.
*
*	splash page showing release status of containers.
*************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$submit = $HTTP_POST_VARS['submit'];

	// first batch is the "release removals"
	if($submit == "Remove Broker Release"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);
		$log_desc = "Container put Back On Hold";

		if($comments == ""){
			echo "<font color=\"#FF0000\">Comments field cannot be blank.</font>";
		} else {
			$sql = "SELECT GATEPASS_NUM FROM CANADIAN_LOAD_RELEASE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "GATEPASS_NUM") != ""){
				echo "<font color=\"#FF0000\"><b>Gatepass has already been generated.  Please Contact Port of Wilmington.</font>";
			} else {
				// it checks, remove the release.
				$sql = "UPDATE CANADIAN_LOAD_RELEASE
						SET BROKER_STATUS = NULL,
							BROKER_COMMENTS = '".$comments."',
							MOST_REC_CHANGER_ID = '".$user."'
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND CONTAINER_NUM = '".$cont."'
							AND BOL = '".$bol."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

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
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'OHL',
							'".$log_desc."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

				SendHoldMail("OHL", $cont, $vessel, $bol, $comments, $user, $rfconn);
			}
		}
	}
	if($submit == "Remove Line Release"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);
		$log_desc = "Container put Back On Hold";

		if($comments == ""){
			echo "<font color=\"#FF0000\">Comments field cannot be blank.</font>";
		} else {
			$sql = "SELECT GATEPASS_NUM FROM CANADIAN_LOAD_RELEASE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "GATEPASS_NUM") != ""){
				echo "<font color=\"#FF0000\"><b>Gatepass has already been generated.  Please Contact Port of Wilmington.</font>";
			} else {
				// it checks, remove the release.
				$sql = "UPDATE CANADIAN_LOAD_RELEASE
						SET LINE_STATUS = NULL,
							LINE_COMMENTS = '".$comments."',
							MOST_REC_CHANGER_ID = '".$user."'
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND CONTAINER_NUM = '".$cont."'
							AND BOL = '".$bol."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

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
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'LINE',
							'".$log_desc."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

				SendHoldMail("LINE", $cont, $vessel, $bol, $comments, $user, $rfconn);
			}
		}
	}
	if($submit == "Remove AMS Release"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);
		$log_desc = "Container put Back On Hold";

		if($comments == ""){
			echo "<font color=\"#FF0000\">Comments field cannot be blank.</font>";
		} else {
			$sql = "SELECT GATEPASS_NUM FROM CANADIAN_LOAD_RELEASE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "GATEPASS_NUM") != ""){
				echo "<font color=\"#FF0000\"><b>Gatepass has already been generated.  Please Contact Port of Wilmington.</font>";
			} else {
				// it checks, remove the release.
				$sql = "UPDATE CANADIAN_LOAD_RELEASE
						SET AMS_STATUS = NULL,
							AMS_COMMENTS = '".$comments."',
							MOST_REC_CHANGER_ID = '".$user."'
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND CONTAINER_NUM = '".$cont."'
							AND BOL = '".$bol."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

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
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'AMS',
							'".$log_desc."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

				SendHoldMail("AMS", $cont, $vessel, $bol, $comments, $user, $rfconn);			
			}
		}
	}

	// Now, some "set release times"
	if($submit == "Set AMS Release Time and Comments"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);

		$sql = "UPDATE CANADIAN_LOAD_RELEASE
				SET AMS_STATUS = SYSDATE,
					AMS_COMMENTS = '".$comments."',
					MOST_REC_CHANGER_ID = '".$user."'
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

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
					'".$cont."',
					'".$bol."',
					'".$user."',
					'".$comments."',
					'AMS',
					'Date Set via Website')";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		echo "<font color=\"#0000FF\">AMS-Release Time Saved.</font>";
	}
	if($submit == "Set Line Release Time and Comments"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);

		$sql = "UPDATE CANADIAN_LOAD_RELEASE
				SET LINE_STATUS = SYSDATE,
					LINE_COMMENTS = '".$comments."',
					MOST_REC_CHANGER_ID = '".$user."'
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

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
					'".$cont."',
					'".$bol."',
					'".$user."',
					'".$comments."',
					'LINE',
					'Date Set via Website')";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		echo "<font color=\"#0000FF\">Line-Release Time Saved.</font>";
	}
	if($submit == "Set Broker Release Time and Comments" && (($HTTP_POST_FILES['import_file']['name'] != "" && substr($HTTP_POST_FILES['import_file']['name'], -3) == "pdf") || $HTTP_POST_VARS['T_E_already_set'] != "NONE")){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = AlphaNumericAndTick($HTTP_POST_VARS['comments']);
		$extra_sql_main = "";
		$log_desc = "Date Set via Website";

		echo "<font color=\"#0000FF\">OHL-Release Time Saved.<br></font>";

		if($HTTP_POST_FILES['import_file']['name'] == ""){
			// no file, do nothing
		} elseif(substr($HTTP_POST_FILES['import_file']['name'], -3) != "pdf"){
			echo "<font color=\"#FF0000\">Uploaded T&E file Not accepted; must be in PDF format.</font>";
		} else {
			$impfilename = date(mdYhis)."file".AlphaNumericAndTick($HTTP_POST_FILES['import_file']['name']);
			$target_path_import = "./upload_t_e/".$impfilename;

			if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
				system("/bin/chmod a+r $target_path_import");
			} else {
				echo "Error on file upload.  Please contact Port of Wilmington";
				exit;
			}

			$extra_sql_main = "T_AND_E_DISPLAY = '".$HTTP_POST_FILES['import_file']['name']."',
						T_AND_E = '".$impfilename."', ";
			$log_desc = "Date Set via Website, T and E file ".AlphaNumericAndTick($HTTP_POST_FILES['import_file']['name'])." uploaded";

			echo "<font color=\"#0000FF\">T&E File Saved.</font>";
		}

		if($HTTP_POST_FILES['import_file']['name'] == "DOMESTIC.pdf"){
			$more_update_ohl_sql = " T_E_YES_NO = 'Y', T_E_SIGNAUTH_BY = '".$user."', ";
		} else {
			$more_update_ohl_sql = "";
		}

		$sql = "UPDATE CANADIAN_LOAD_RELEASE
				SET BROKER_STATUS = SYSDATE, ".$extra_sql_main."
					BROKER_COMMENTS = '".$comments."',".$more_update_ohl_sql."
					MOST_REC_CHANGER_ID = '".$user."'
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
//		echo $sql."<br>";

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
					'".$cont."',
					'".$bol."',
					'".$user."',
					'".$comments."',
					'OHL',
					'".$log_desc."')";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

	} elseif($submit == "Set OHL Release Time and Comments" && ($HTTP_POST_FILES['import_file']['name'] == "" || substr($HTTP_POST_FILES['import_file']['name'], -3) != "pdf") && $HTTP_POST_VARS['T_E_already_set'] == "NONE"){
		echo "<font color=\"#FF0000\">Cannot set OHL Release time without a valid T&E file.</font>";
	}

	// And lastly, other stuff.
	if($submit == "Save Trucker Information"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$trailer_num = AlphaNumericAndTick($HTTP_POST_VARS['trailer_num']);
		$seal_num = AlphaNumericAndTick($HTTP_POST_VARS['seal_num']);
		$driver_name = AlphaNumericAndTick($HTTP_POST_VARS['driver_name']);
		$driver_lic = AlphaNumericAndTick($HTTP_POST_VARS['driver_lic']);
		$driver_lic_state = AlphaNumericAndTick($HTTP_POST_VARS['driver_lic_state']);
		$reefer_plate = AlphaNumericAndTick($HTTP_POST_VARS['reefer_plate']);
		$reefer_plate_state = AlphaNumericAndTick($HTTP_POST_VARS['reefer_plate_state']);
		$border_crossing = AlphaNumericAndTick($HTTP_POST_VARS['border_crossing']);
		$truck_company = AlphaNumericAndTick($HTTP_POST_VARS['truck_company']);

		if($seal_num != ""){
			$more_seal_info_sql = " SEAL_NUM = '".$seal_num."', SEALER = '".$user."', SEAL_TIME = SYSDATE, ";
		} else {
			$more_seal_info_sql = "";
		}

		$sql = "UPDATE CANADIAN_LOAD_RELEASE
				SET DRIVER_NAME = '".$driver_name."',
					DRIVER_LICENSE_NUM = '".$driver_lic."',
					DRIVER_LIC_STATE = '".$driver_lic_state."',
					REEFER_PLATE_NUM = '".$reefer_plate."',
					REEFER_PLATE_STATE = '".$reefer_plate_state."',
					BORDER_CROSSING = '".$border_crossing."',
					TRUCK_COMPANY = '".$truck_company."',
					TRAILER_NUM = '".$trailer_num."',".$more_seal_info_sql."
					TRUCKER_TIME_ENTER = SYSDATE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
//		echo $sql."<br>";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		echo "<font color=\"#0000FF\">Trucker Information Saved.</font>";
	}
	if($submit == "Insert Record"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$container = AlphaNumericAndTick($HTTP_POST_VARS['container']);
		$order = AlphaNumericAndTick($HTTP_POST_VARS['order']);
		$consignee = AlphaNumericAndTick($HTTP_POST_VARS['consignee']);
		$t_and_e = AlphaNumericAndTick($HTTP_POST_VARS['t_and_e']);
		$seal = AlphaNumericAndTick($HTTP_POST_VARS['seal']);
/*
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$order."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND CUSTOMER_ID = '".$consignee."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$row_count = ociresult($short_term_data, "THE_COUNT");
*/
//		if($container == "" || $order == "" || $consignee == "" || $t_and_e == "" || $seal == ""){
		if($container == "" || $order == "" || $consignee == "" || $seal == ""){
			echo "<font color=\"#FF0000\">All fields, other than T&E, must be entered.  Save Cancelled.</font>";
//		} elseif($row_count <= 0) {
//			echo "<font color=\"#FF0000\">Order Number not found.  Save Cancelled.</font>";
		} else {
			if($t_and_e != ""){
				$pdf_entry = "DEFAULT.pdf";
				$broker_entry = "SET BY DIRECT INSERT";
				$broker_time = "SYSDATE";
			} else {
				$pdf_entry = "";
				$broker_entry = "";
				$broker_time = "NULL";
			}
			$sql = "INSERT INTO CANADIAN_LOAD_RELEASE
						(ARRIVAL_NUM,
						LLOYD_NUM,
						UPLOAD_DATE,
						CONTAINER_NUM,
						CONTAINER_TYPE,
						OPERATO_R,
						EXPORTER,
						BOL,
						CONSIGNEE,
						CONSIGNEE_ID,
						COMMODITY,
						LOT,
						EXPECTED_PICKUP,
						FINAL_PORT,
						CARGO_MODE,
						SEAL_NUM,
						BROKER_STATUS,
						BROKER_COMMENTS,
						T_AND_E,
						T_AND_E_DISPLAY,
						MOST_REC_CHANGER_ID,
						CARGO_TYPE)
					(SELECT 
						'".$vessel."',
						VP.LLOYD_NUM,
						SYSDATE,
						'".$container."',
						'CLEM',
						'FFM',
						'FFM',
						'".$order."',
						CP.CUSTOMER_NAME,
						'".$consignee."',
						'CLEMENTINE',
						'NONE',
						SYSDATE,
						'CANADA',
						'CLEMENTINE',
						'".$seal."',
						".$broker_time.",
						'".$broker_entry."',
						'".$pdf_entry."',
						'".$t_and_e."',
						'".$UID."',
						'CLEMENTINE'
					FROM VESSEL_PROFILE VP, CUSTOMER_PROFILE CP
					WHERE TO_CHAR(VP.LR_NUM) = '".$vessel."'
						AND CP.CUSTOMER_ID = '".$consignee."'
					)";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);

			echo "<font color=\"#0000FF\">Entry Inserted.</font>";
		}
	}
	if($submit == "Edit Record"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$container = $HTTP_POST_VARS['cont'];
		$order = $HTTP_POST_VARS['bol'];
		$t_and_e = AlphaNumericAndTick($HTTP_POST_VARS['t_and_e_disp']);

		if($t_and_e != ""){
			$pdf_entry = "DEFAULT.pdf";
			$broker_entry = "SET BY DIRECT INSERT";
			$broker_time = "SYSDATE";
		} else {
			$pdf_entry = "";
			$broker_entry = "";
			$broker_time = "NULL";
		}
		$sql = "UPDATE CANADIAN_LOAD_RELEASE
				SET BROKER_STATUS = ".$broker_time.",
					BROKER_COMMENTS = '".$broker_entry."',
					T_AND_E = '".$pdf_entry."',
					T_AND_E_DISPLAY = '".$t_and_e."'
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND BOL = '".$order."'
					AND CONTAINER_NUM = '".$container."'";
//		echo $sql."<br>";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		echo "<font color=\"#0000FF\">T&E altered.</font>";
	}
	if($submit != "" && $HTTP_POST_VARS['vessel'] != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
//		setcookie("can_vessel", "$vessel", time() + 86400, "/");
		// step 1 upod arriving at this screen:  load any new DC orders
		$sql = "SELECT TRIM(ORDERNUM) THE_ORDER 
				FROM DC_ORDER
				WHERE ORDERSTATUSID >= 4
					AND ORDERSTATUSID != 10
					AND TRUCKTAG IS NOT NULL
					AND VESSELID = '".$vessel."'
					AND (TRIM(ORDERNUM), TRIM(TRUCKTAG), VESSELID) NOT IN
						(SELECT BOL, CONTAINER_NUM, ARRIVAL_NUM
						FROM CANADIAN_LOAD_RELEASE)";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
			$sql = "INSERT INTO CANADIAN_LOAD_RELEASE
						(ARRIVAL_NUM,
						LLOYD_NUM,
						UPLOAD_DATE,
						CONTAINER_NUM,
						CONTAINER_TYPE,
						OPERATO_R,
						EXPORTER,
						BOL,
						CONSIGNEE,
						COMMODITY,
						LOT,
						EXPECTED_PICKUP,
						FINAL_PORT,
						CARGO_MODE,
						SEAL_NUM,
						BROKER_STATUS,
						BROKER_COMMENTS,
						T_AND_E,
						T_AND_E_DISPLAY,
						DRIVER_NAME,
						REEFER_PLATE_NUM,
						TRUCK_COMPANY,
						TRUCKER_TIME_ENTER,
						MOST_REC_CHANGER_ID,
						BORDER_CROSSING,
						CARGO_TYPE)
					(SELECT 
						'".$vessel."',
						VP.LLOYD_NUM,
						SYSDATE,
						DCO.TRUCKTAG,
						'CLEM',
						'FFM',
						'FFM',
						'".ociresult($short_term_data, "THE_ORDER")."',
						DCC.CONSIGNEENAME,
						'CLEMENTINE',
						'NONE',
						SYSDATE,
						'CANADA',
						'CLEMENTINE',
						TRIM(DCO.SEALNUM),
						DECODE(DCO.TRAILERNUM, NULL, NULL, SYSDATE),
						DECODE(DCO.TRAILERNUM, NULL, NULL, 'AUTOFILLED FROM EPORT2'),
						DECODE(DCO.TRAILERNUM, NULL, NULL, 'DEFAULT.pdf'),
						DECODE(DCO.TRAILERNUM, NULL, NULL, DCO.TRAILERNUM),
						TRIM(DCO.DRIVERNAME),
						TRIM(DCO.TRUCKTAG),
						DCO.TRANSPORTERID,
						SYSDATE,
						'EPORT2',
						NVL(CB.BORDERCROSSING_ID, NULL),
						'CLEMENTINE'
					FROM DC_ORDER DCO, VESSEL_PROFILE VP, DC_CONSIGNEE DCC, CANADIAN_BORDERCROSSING CB
					WHERE TRIM(DCO.VESSELID) = TO_CHAR(VP.LR_NUM)
						AND DCO.CONSIGNEEID = DCC.CONSIGNEEID
						AND DCO.PARSPORTOFENTRYNUM = CB.PARSPORTOFENTRYNUM(+)
						AND TRIM(DCO.ORDERNUM) = '".ociresult($short_term_data, "THE_ORDER")."'
						AND DCO.VESSELID = '".$vessel."'
					GROUP BY VP.LLOYD_NUM, DCO.TRUCKTAG, DCC.CONSIGNEENAME, TRIM(DCO.SEALNUM), CB.BORDERCROSSING_ID, TRIM(DCO.DRIVERNAME), TRIM(DCO.TRUCKTAG), DCO.TRANSPORTERID,
						DECODE(DCO.TRAILERNUM, NULL, NULL, SYSDATE), DECODE(DCO.TRAILERNUM, NULL, NULL, 'AUTOFILLED FROM EPORT2'), DECODE(DCO.TRAILERNUM, NULL, NULL, 'DEFAULT.pdf'),
						DECODE(DCO.TRAILERNUM, NULL, NULL, DCO.TRAILERNUM)
					)";
//			echo $sql."<br>";
			$update_data = ociparse($rfconn, $sql);
			ociexecute($update_data);
		}





		// step 2:  update any DC orders that only had partial information up till now
		$sql = "SELECT * FROM CANADIAN_LOAD_RELEASE
				WHERE CARGO_TYPE = 'CLEMENTINE'
					AND ARRIVAL_NUM = '".$vessel."'
					AND GATEPASS_NUM IS NULL";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
			$sql = "SELECT TRIM(TRAILERNUM) THE_TE, PARSPORTOFENTRYNUM
					FROM DC_ORDER
					WHERE VESSELID = '".$vessel."'
						AND TRIM(ORDERNUM) = '".ociresult($short_term_data, "BOL")."'
						AND TRUCKTAG = '".ociresult($short_term_data, "CONTAINER_NUM")."'";
			$short_term_data2 = ociparse($rfconn, $sql);
			ociexecute($short_term_data2);
			if(!ocifetch($short_term_data2)){
				// non-DC order, do nothing
			} else {
				$te = ociresult($short_term_data2, "THE_TE");
				$parsborder = ociresult($short_term_data2, "PARSPORTOFENTRYNUM");
				if($te != "" && $te != ociresult($short_term_data, "T_AND_E_DISPLAY")){
					// different T&E than in our DB.  update.
					$sql = "UPDATE CANADIAN_LOAD_RELEASE
							SET T_AND_E_DISPLAY = '".$te."',
								T_AND_E = 'DEFAULT.pdf',
								BROKER_STATUS = SYSDATE,
								BROKER_COMMENTS = 'SET BY EPORT2',
								MOST_REC_CHANGER_ID = 'EPORT2'
							WHERE CARGO_TYPE = 'CLEMENTINE'
								AND ARRIVAL_NUM = '".$vessel."'
								AND GATEPASS_NUM IS NULL
								AND BOL = '".ociresult($short_term_data, "BOL")."'
								AND CONTAINER_NUM = '".ociresult($short_term_data, "CONTAINER_NUM")."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				}
				if($parsborder != ""){
					$sql = "SELECT BORDERCROSSING_ID
							FROM CANADIAN_BORDERCROSSING
							WHERE PARSPORTOFENTRYNUM = '".$parsborder."'";
					$short_term_data3 = ociparse($rfconn, $sql);
					ociexecute($short_term_data3);
					if(!ocifetch($short_term_data3)){
						// not one of our PARS, do nothing.
					} else {
						$border = ociresult($short_term_data3, "BORDERCROSSING_ID");
						if($border != ociresult($short_term_data, "BORDER_CROSSING")){
							// different border cross than we have listed.  Update.

							$sql = "UPDATE CANADIAN_LOAD_RELEASE
									SET BORDER_CROSSING = '".$border."',
										MOST_REC_CHANGER_ID = 'EPORT2'
									WHERE CARGO_TYPE = 'CLEMENTINE'
										AND ARRIVAL_NUM = '".$vessel."'
										AND GATEPASS_NUM IS NULL
										AND BOL = '".ociresult($short_term_data, "BOL")."'
										AND CONTAINER_NUM = '".ociresult($short_term_data, "CONTAINER_NUM")."'";
							$update = ociparse($rfconn, $sql);
							ociexecute($update);
						}
					}
				}
			}
		}
	}




	$sql_filter = "";

	$ams_filter = $HTTP_POST_VARS['ams_filter'];
	if($ams_filter == "HOLD"){
		$sql_filter .= " AND AMS_STATUS IS NULL";
	} elseif($ams_filter == "RELEASE"){
		$sql_filter .= " AND AMS_STATUS IS NOT NULL";
	}
	$line_filter = $HTTP_POST_VARS['line_filter'];
	if($line_filter == "HOLD"){
		$sql_filter .= " AND LINE_STATUS IS NULL";
	} elseif($line_filter == "RELEASE"){
		$sql_filter .= " AND LINE_STATUS IS NOT NULL";
	}
	$ohl_filter = $HTTP_POST_VARS['ohl_filter'];
	if($ohl_filter == "HOLD"){
		$sql_filter .= " AND BROKER_STATUS IS NULL";
	} elseif($ohl_filter == "RELEASE"){
		$sql_filter .= " AND BROKER_STATUS IS NOT NULL";
	}
	$final_filter = $HTTP_POST_VARS['final_filter'];
	if($final_filter == "HOLD"){
		$sql_filter .= " AND (BROKER_STATUS IS NULL OR AMS_STATUS IS NULL OR LINE_STATUS IS NULL)";
	} elseif($final_filter == "RELEASE"){
		$sql_filter .= " AND (BROKER_STATUS IS NOT NULL AND AMS_STATUS IS NOT NULL AND LINE_STATUS IS NOT NULL)";
	}
	$cont_filter = $HTTP_POST_VARS['cont_filter'];
	if($cont_filter != ""){
		$sql_filter .= " AND CONTAINER_NUM LIKE '%".$cont_filter."%'";
	}
	$gatepass_date_filter = $HTTP_POST_VARS['gatepass_date_filter'];
	$all_dates = $HTTP_POST_VARS['all_dates'];
	if($all_dates != "yes"){
		if($gatepass_date_filter != ""){
			$sql_filter .= " AND TO_CHAR(GATEPASS_ISSUED, 'MM/DD/YYYY') = '".$gatepass_date_filter."'";
		} else {
			$sql_filter .= " AND (GATEPASS_ISSUED IS NULL OR TO_CHAR(GATEPASS_ISSUED, 'MM/DD/YYYY') = '".date('m/d/Y')."')";
		}
	}
	// LAME HARDCODING ALERT
	// takes this out once a "expediter reference table" is ready for Adams
//	if($user == "ADAMS"){
//		$sql_filter .= " AND CONSIGNEE_ID = '1656'";
//	}
	if($eport_customer_id == 9999){
		$sql_filter .= " AND (CARGO_TYPE, CONSIGNEE_ID) IN (SELECT CARGO_SYSTEM, CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."')";
	}
	if($eport_customer_id != 9999 && $eport_customer_id != 0 && is_numeric($eport_customer_id)){
		$sql_filter .= " AND CONSIGNEE_ID = '".$eport_customer_id."'";
	}
	if($shipline != ""){
		$sql_filter .= " AND SHIPPING_LINE = '".$shipline."'";
	}
	if($broker != ""){
		$sql_filter .= " AND BROKER = '".$broker."'";
	}
	if($vessel == ""){
		$vessel = $HTTP_GET_VARS['vessel']; // just incase they got back here via hyperlink
	}
	if($vessel == ""){
		$vessel = $HTTP_COOKIE_VARS["can_vessel"]; // last case chance, if they are using the Leftnav link
	}

?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">All Cargo Main Board</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?> 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="canadian_scoreboard_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td colspan="10"><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CLEMENTINES', 'CHILEAN')
					AND TO_CHAR(LR_NUM) IN
						(SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>AMS Release:</b>&nbsp;&nbsp;</td>
		<td><select name="ams_filter">
					<option value="">All</option>
					<option value="HOLD"<? if($ams_filter == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($ams_filter == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Line Release:</b>&nbsp;&nbsp;</td>
		<td><select name="line_filter">
					<option value="">All</option>
					<option value="HOLD"<? if($line_filter == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($line_filter == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Broker Release:</b>&nbsp;&nbsp;</td>
		<td><select name="ohl_filter">
					<option value="">All</option>
					<option value="HOLD"<? if($ohl_filter == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($ohl_filter == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Final Release:</b>&nbsp;&nbsp;</td>
		<td><select name="final_filter">
					<option value="">All</option>
					<option value="HOLD"<? if($final_filter == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($final_filter == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Container ID:</b>&nbsp;&nbsp;</td>
		<td colspan="8"><input type="text" name="cont_filter" size="20" maxlength="20" value="<? echo $cont_filter; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(Partial Values Accepted)</font>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Gate Pass Date:</b>&nbsp;&nbsp;</td>
		<td colspan="8"><input type="text" name="gatepass_date_filter" size="10" maxlength="10" value="<? echo $gatepass_date_filter; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.gatepass_date_filter');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="all_dates" value="yes" <? if($all_dates == "yes"){?>checked<?}?>> View All Dates</td>
	</tr>
	<tr>
		<td colspan="11"><input type="submit" name="submit" value="Retrieve / Refresh"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
//	echo "Include: ".$include_auth."<br>ves: ".$vessel."<br>";
	if($include_auth == "Y" && $vessel != ""){
?>
<form name="addclemline" action="add_clem_entry_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td colspan="19" align="center"><input type="submit" name="submit" value="Add Entry">
	</tr>
</form>
<?
	}
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>CONTAINER / TRAILER</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CONSIGNEE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>BOL / ORDER</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>OCEAN BOL</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>PALLETS</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CASES</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>COMMODITY</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>MODE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>MFSTD</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>AMS RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>LINE RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>BROKER RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>T&E FILE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>FINAL RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>TRUCKER INFO</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>GATE PASS</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>SEAL</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>FAX</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Action</b></font></td>
	</tr>
<?
	$sql = "SELECT CONSIGNEE, NVL(T_AND_E, 'NONE') THE_TE, T_AND_E_DISPLAY, CONTAINER_NUM, BOL, CARGO_MODE, FINAL_PORT, OCEAN_BOL, CASES, PALLET_COUNT, GATEPASS_NUM, CARGO_TYPE, COMMODITY,
				DECODE(AMS_STATUS, NULL, 'ON HOLD', TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
				DECODE(LINE_STATUS, NULL, 'ON HOLD', TO_CHAR(LINE_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
				DECODE(BROKER_STATUS, NULL, 'ON HOLD', TO_CHAR(BROKER_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_BROKER,
				NVL(TO_CHAR(GREATEST(AMS_STATUS, BROKER_STATUS, LINE_STATUS), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL,
				DECODE(CBP_FAX, NULL, 'NOT SENT', TO_CHAR(CBP_FAX, 'MM/DD/YYYY HH:MI AM')) THE_CBP_FAX,
				NVL(TO_CHAR(GATEPASS_PDF_DATE), 'NONE') GATEPASS_CHECK, MFSTD
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				".$sql_filter."
			ORDER BY GATEPASS_NUM DESC NULLS FIRST, CONSIGNEE, CONTAINER_NUM, BOL";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		switch(ociresult($short_term_data, "THE_FINAL")){
			case "ON HOLD":
				$bgcolorfinal="#FFCCCC";
			break;
			default:
				$bgcolorfinal="#CCFFCC";
			break;
		}
		switch(ociresult($short_term_data, "THE_CBP_FAX")){
			case "NOT SENT":
				$bgcolorfax="#FFFFCC";
			break;
			default:
				$bgcolorfax="#CCFFCC";
			break;
		}

?>
	<tr>
		<? DisplayContainer($rfconn, $include_auth, ociresult($short_term_data, "GATEPASS_CHECK"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); ?>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CONTAINER_NUM"); ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo substr(ociresult($short_term_data, "CONSIGNEE"), 0, 15); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BOL"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "OCEAN_BOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PALLET_COUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CASES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "COMMODITY"); ?></font></td>
		<? DisplayMode(ociresult($short_term_data, "CARGO_MODE"), ociresult($short_term_data, "CARGO_TYPE"), $mode_auth, ociresult($short_term_data, "GATEPASS_CHECK"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); ?>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "MFSTD"); ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CARGO_MODE"); ?></font></td> !-->
		<? DisplayRelease("ams", $ams_auth, ociresult($short_term_data, "THE_AMS"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, $rfconn); ?>
		<? DisplayRelease("line", $line_auth, ociresult($short_term_data, "THE_LINE"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, $rfconn); ?>
		<? DisplayRelease("ohl", $ohl_auth, ociresult($short_term_data, "THE_BROKER"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, $rfconn); ?>
<?
		if(ociresult($short_term_data, "THE_TE") != "NONE"){
?>
		<td bgcolor="#CCFFCC"><font size="2" face="Verdana"><a href="./upload_t_e/<? echo ociresult($short_term_data, "THE_TE"); ?>"><? echo ociresult($short_term_data, "T_AND_E_DISPLAY"); ?></a></font></td>
<?
		} else {
?>
		<td bgcolor="#FFFFCC"><font size="2" face="Verdana">None</font></td>
<?
		}
?>
		<td bgcolor="<? echo $bgcolorfinal; ?>"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_FINAL"); ?></font></td>
		<? DisplayTrucker($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); ?>
		<? DisplayGatePass($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, "GATEPASS_NUM"); ?>
		<? DisplaySeal($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, "SEAL_NUM"); ?>
		<td bgcolor="<? echo $bgcolorfax; ?>"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CBP_FAX"); ?></font></td>
		<? DisplayActions($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, $delete_auth, $general_edit_auth); ?>
	</tr>
<?
	}
?>
</table>







<?

function GetAMSBGC($cont, $bol, $vessel, $rfconn){
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CANADIAN_AMSEDI_DETAIL CAD, CANADIAN_AMSEDI_CODES CAC, VESSEL_PROFILE VP
			WHERE CAD.EDI_CODE = CAC.EDI_CODE
				AND CAD.LLOYD_NUM = VP.LLOYD_NUM
				AND VP.LR_NUM = '".$vessel."'
				AND CAD.BOL_EQUIV = '".$bol."'
				AND CAD.CONTAINER_NUM = '".$cont."'
				AND CAC.ACTION IN ('HOLD')
				AND CAD.PUSH_TO_CLR IS NOT NULL
				AND CAD.HOLD_CLEARED_ON IS NULL";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$count = ociresult($short_term_data, "THE_COUNT");

	if($count == 0){
		return "#FFFFCC";
	} else {
		// uncleared holds.  turn this RED.
		return "#FFCCCC";
	}
}



function DisplayRelease($column, $auth, $datetime, $cont, $bol, $vessel, $rfconn){
	switch($column){
		case "ams":
			$pagename = "ams_release_index.php";
		break;
		case "line":
			$pagename = "line_release_index.php";
		break;
		case "ohl":
			$pagename = "ohl_release_index.php";
		break;
	}

	switch($datetime){
		case "ON HOLD":
			if($column == "ams"){
				$bgcolor = GetAMSBGC($cont, $bol, $vessel, $rfconn);
			} else {
				$bgcolor="#FFFFCC";
			}
			$released = "";
		break;
		default:
			$bgcolor="#CCFFCC";
			$released = "Released";
		break;
	}


//	if($auth == "Y"){  all customers can view link now, authorization handled in-page
	if(true){
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $released; ?><br><a href="<? echo $pagename; ?>?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $datetime; ?></a>
<!--		<br>
		<a href="release_status_history_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>&type=<? echo strtoupper($column); ?>">Status Change History</a></font></td> !-->
<?
	} else {
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $datetime; ?></font></td>
<?
	}
}

function DisplayTrucker($rfconn, $cont, $bol, $vessel){
	$sql = "SELECT NVL(TO_CHAR(TRUCKER_TIME_ENTER, 'MM/DD/YYYY HH:MI AM'), 'NONE') THE_TIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_TIME") == "NONE"){
		$display = "None";
		$bgcolor = "#FFFFCC";
	} else {
		$display = ociresult($short_term_data, "THE_TIME");
		$bgcolor = "#CCFFCC";
	}
?>

		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="trucker_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
}



function DisplayGatePass($rfconn, $cont, $bol, $vessel, $column){
	$sql = "SELECT NVL(".$column.", 'NONE') THE_CHECK, NVL(TO_CHAR(GATEPASS_PDF_DATE), 'NONE') THE_BGCOLOR_CHECK
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_CHECK") == "NONE"){
		$display = "None";
	} else {
		$display = ociresult($short_term_data, "THE_CHECK");
	}
	if(ociresult($short_term_data, "THE_BGCOLOR_CHECK") == "NONE"){
		$bgcolor = "#FFFFCC";
	} else {
		$bgcolor = "#CCFFCC";
	}

	$sql = "SELECT NVL(TO_CHAR(SEAL_TIME, 'MM/DD/YYYY'), 'NONE') THE_SEALTIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_SEALTIME") != "NONE"){
?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="seal_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
	} else {
?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><? echo $display; ?></td>
<?
	}
}


function DisplaySeal($rfconn, $cont, $bol, $vessel, $column){
	$sql = "SELECT NVL(".$column.", 'NONE') THE_CHECK, NVL(TO_CHAR(SEAL_TIME, 'MM/DD/YYYY'), 'NONE') THE_SEALTIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_CHECK") == "NONE"){
		$display = "None";
		$bgcolor = "#FFFFCC";
	} else {
		$display = ociresult($short_term_data, "THE_CHECK");
		if(ociresult($short_term_data, "THE_SEALTIME") == "NONE"){
			$bgcolor = "#FFFFCC";
		} else {
			$bgcolor = "#CCFFCC";
		}
	}

?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="seal_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
}

function DisplayActions($rfconn, $cont, $bol, $vessel, $delete_auth, $general_edit_auth){
	$sql = "SELECT NVL(TO_CHAR(CBP_FAX, 'MM/DD/YYYY'), 'NONE') THE_CHECK
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
//	echo "cookie: ".$HTTP_COOKIE_VARS["eport_user_delete_auth"];
?>
	<td>
<?
	if(ociresult($short_term_data, "THE_CHECK") == "NONE" && $delete_auth == "Y"){
?>
		<font size="2" face="Verdana"><a href="general_override_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>">Delete</a><br></font>
<?
	} else {
		// nothing
	}
	if(ociresult($short_term_data, "THE_CHECK") == "NONE" && $general_edit_auth == "Y"){
?>
		<font size="2" face="Verdana"><a href="general_edit_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>">Edit</a><br></font>
<?
	} else {
		// nothing
	}
?>
	&nbsp;</td>
<?
}





function SendHoldMail($release_type, $cont, $vessel, $bol, $comments, $user, $rf_conn){
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CANHOLD".$release_type."'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $cont, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $cont."\r\n", $body);
	$body = str_replace("_1_", $vessel."\r\n", $body);
	$body = str_replace("_2_", $bol."\r\n", $body);
	$body = str_replace("_3_", $user."\r\n", $body);
	$body = str_replace("_4_", $comments."\r\n", $body);

//	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
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
					JOB_BODY,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'INSTANTCRON',
					SYSDATE,
					'EMAIL',
					'CANHOLD".$release_type."',
					SYSDATE,
					'PENDING',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."',
					'".$cont."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
//	}
}



function DisplayMode($mode, $type, $auth, $gatepass_check, $cont, $bol, $vessel){
// only display a hyperlink if:
	// this is Chilean
	if($type == "CHILEAN"){
		// user is authorized
		if($auth == "Y"){
			// gatepass hasnt yet been printed
			if($gatepass_check == "NONE"){
?>
		<td><font size="2" face="Verdana"><a href="mode_change_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $mode; ?></a>
<?
				return;
			}
		}
	}

	// if the above didn't happen, just display.
?>
		<td><font size="2" face="Verdana"><? echo $mode; ?></font></td>
<?
}

function DisplayContainer($rfconn, $include_auth, $gatepass_check, $cont, $bol, $vessel){
	if($include_auth == "Y" && $gatepass_check == "NONE"){
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><a href="edit_entry_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $cont; ?></a>
<?
	} else {
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $cont; ?></font></td>
<?
	}
}
