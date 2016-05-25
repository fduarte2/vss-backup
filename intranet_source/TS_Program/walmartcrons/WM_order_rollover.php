<?
/*
*	Sends out email notifications to Walmart -
*		Expected Order rollovers is cargo present
*
*	Mar 2011.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $load_cursor = ora_open($conn);
   $order_cursor = ora_open($conn);
   $per_order_cursor = ora_open($conn);
   $short_term_cursor = ora_open($conn);

   $mod_cursor = ora_open($conn);
   $cursor2 = ora_open($conn); // email cursor

   $run_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
//   $run_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')));



	// I am being explicitly told to do the calculation this way:
	// Step 1 - get current IH amounts, store in 1-dimensional array (by item#)
		// A)  QTY IN HOUSE > 0, DATE_RECEIVED NOT NULL, VESSEL IS RELEASED
		// + B)  VESSEL NOT RELEASED, EXPECTED RELEASE DATE <= day of report run

		// --- put in email "starting QTYs"

	// Step 2 - Loop - go through all orders from the day that just ended, ordered by order#
		// "rollover orders" defined as any order in table that does not have ANY pallets on it yet (I.E. no service code 6's in CARGO ACTIVITY)

		// if all quantites for order are available in Step 1 Array, then roll over order, and subtract from step 1 Array
			// --- add to email "order rolled over" (with info of order)
		// if ANY quantity on order is insufficient in Step 1 Array, do NOT roll order over.
			// --- add to email "order NOT rolled", display what order would have had

	// Step 3
		// send email, write job queue result

	$any_loads_at_all = false;

	$available_array = array();
//	$IH_output = "\r\n";
//	$rolled_orders = "\r\n";
//	$cannot_roll_orders = "\r\n";
	$IH_output = "<table border=1><tr><td>Item</td><td>PLT Count</td></tr>";
	$IH_after_roll = "<table border=1><tr><td>Item</td><td>PLT Count</td></tr>";
	$rolled_orders = "<table border=1><tr><td>Load</td><td>Order</td><td>Item</td><td>PLT Count</td><td>Case Count</td></tr>";
	$cannot_roll_orders = "<table border=1><tr><td>Load</td><td>Order</td><td>Item</td><td>PLT Count</td></tr>";

	// STEP 1
	// in house
	$sql = "SELECT COUNT(*) THE_COUNT, BOL
			FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID IN 
					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
					WHERE CUSTOMER_GROUP = 'WALMART')
				AND DATE_RECEIVED IS NOT NULL
				AND CT.ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND CT.BOL != 'MULTIPLE'
			GROUP BY BOL
			ORDER BY BOL";
	ora_parse($short_term_cursor, $sql);
	ora_exec($short_term_cursor);
	while(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$available_array[$row['BOL']] += $row['THE_COUNT'];
	}
/*
	// REMOVED PER HD 9264
	// expected In house S.O.D. tomorrow
	$sql = "SELECT COUNT(*) THE_COUNT, BOL
			FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
			WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
				AND CT.RECEIVER_ID IN 
					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP
					WHERE CUSTOMER_GROUP = 'WALMART')
				AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
				AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY'),
					NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".date('m/d/Y')."'
				AND CT.BOL != 'MULTIPLE'
			GROUP BY BOL
			ORDER BY BOL";
	ora_parse($short_term_cursor, $sql);
	ora_exec($short_term_cursor);
	while(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$available_array[$row['BOL']] += $row['THE_COUNT'];
	}
*/
	foreach ($available_array as $k => $v) {
//		$IH_output .= $k."\t".$v."\r\n";
		$IH_output .= "<tr><td>".$k."</td><td>".$v."</td></tr>";
	}
//	$IH_output .= "\r\n";
//	$IH_output .= "<tr><td colspan=2>&nbsp;</td></tr></table>";
	$IH_output .= "</table><br>";




	// STEP 2
	// for each load...
	$sql = "SELECT DISTINCT WLH.LOAD_NUM
			FROM WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
			WHERE TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$run_date."'
			AND WLH.LOAD_NUM = WLD.LOAD_NUM
			AND STATUS != 'CANCELLED'
			AND TO_CHAR(DCPO_NUM) NOT IN
				(SELECT ORDER_NUM FROM CARGO_ACTIVITY
				WHERE CUSTOMER_ID = '3000'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				)
			ORDER BY LOAD_NUM";
	ora_parse($load_cursor, $sql);
	ora_exec($load_cursor);
	while(ora_fetch_into($load_cursor, $load_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$any_loads_at_all = true;

		$can_rollover = true;
		$load_num = $load_row['LOAD_NUM'];

		// figure each order we need to check...
		$sql = "SELECT DISTINCT DCPO_NUM 
				FROM WDI_LOAD_DCPO 
				WHERE LOAD_NUM = '".$load_num."'
				AND TO_CHAR(DCPO_NUM) NOT IN
					(SELECT ORDER_NUM FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '3000'
						AND SERVICE_CODE = '6'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					)
				ORDER BY DCPO_NUM";
		ora_parse($order_cursor, $sql);
		ora_exec($order_cursor);
		while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order_num = $order_row['DCPO_NUM'];

			// check all parts of order for availability, set rollover flag to "false" if any dont pass
			$sql = "SELECT PALLETS, ITEM_NUM
					FROM WDI_LOAD_DCPO_ITEMNUMBERS
					WHERE DCPO_NUM = '".$order_num."'
					ORDER BY ITEM_NUM";
			ora_parse($per_order_cursor, $sql);
			ora_exec($per_order_cursor);
			while(ora_fetch_into($per_order_cursor, $suborder_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($available_array[$suborder_row['ITEM_NUM']] < $suborder_row['PALLETS']){
					$can_rollover = false;
				}
			}
		}

		// if all parts pass, redo above loops, but instead of "checking" each record, we update availability instead
		if($can_rollover == true){
			$rolled_orders .= "<tr><td><b>".$load_num."</b></td><td></td><td></td><td></td></tr>";

			$sql = "SELECT DISTINCT DCPO_NUM 
					FROM WDI_LOAD_DCPO 
					WHERE LOAD_NUM = '".$load_num."'
					AND TO_CHAR(DCPO_NUM) NOT IN
						(SELECT ORDER_NUM FROM CARGO_ACTIVITY
						WHERE CUSTOMER_ID = '3000'
							AND SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						)
					ORDER BY DCPO_NUM";
			ora_parse($order_cursor, $sql);
			ora_exec($order_cursor);
			while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){		
				$order_num = $order_row['DCPO_NUM'];
	
				$rolled_orders .= "<tr><td></td><td><b>".$order_num."</b></td><td></td><td></td></tr>";

				$sql = "SELECT PALLETS, CASES, ITEM_NUM
						FROM WDI_LOAD_DCPO_ITEMNUMBERS
						WHERE DCPO_NUM = '".$order_num."'
						ORDER BY ITEM_NUM";
				ora_parse($per_order_cursor, $sql);
				ora_exec($per_order_cursor);
				while(ora_fetch_into($per_order_cursor, $suborder_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$available_array[$suborder_row['ITEM_NUM']] -= $suborder_row['PALLETS'];
	//				$rolled_orders .= "\t".$suborder_row['ITEM_NUM']."\t".$suborder_row['PLT_COUNT']."\r\n";
					$rolled_orders .= "<tr><td></td><td></td><td>".$suborder_row['ITEM_NUM']."</td><td>".$suborder_row['PALLETS']."</td><td>".$suborder_row['CASES']."</td></tr>";
				}
			}


			$sql = "UPDATE WDI_LOAD_HEADER WLH
					SET LOAD_DATE = LOAD_DATE + 1
					WHERE LOAD_NUM = '".$load_num."'";
//			echo $sql."\n";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);		
		} else {
			$cannot_roll_orders .= "<tr><td><b>".$load_num."</b></td><td></td><td></td><td></td></tr>";

			$sql = "SELECT DISTINCT DCPO_NUM 
					FROM WDI_LOAD_DCPO 
					WHERE LOAD_NUM = '".$load_num."'
					AND TO_CHAR(DCPO_NUM) NOT IN
						(SELECT ORDER_NUM FROM CARGO_ACTIVITY
						WHERE CUSTOMER_ID = '3000'
							AND SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						)
					ORDER BY DCPO_NUM";
			ora_parse($order_cursor, $sql);
			ora_exec($order_cursor);
			while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){		
				$order_num = $order_row['DCPO_NUM'];
				$cannot_roll_orders .= "<tr><td></td><td><b>".$order_row['DCPO_NUM']."</b></td><td></td><td></td></tr>";

				$sql = "SELECT PALLETS, CASES, ITEM_NUM
					FROM WDI_LOAD_DCPO_ITEMNUMBERS
					WHERE DCPO_NUM = '".$order_num."'
					ORDER BY ITEM_NUM";
				ora_parse($per_order_cursor, $sql);
				ora_exec($per_order_cursor);
				while(ora_fetch_into($per_order_cursor, $suborder_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					if($available_array[$suborder_row['ITEM_NUM']] <= $suborder_row['PALLETS']){
						$color="#FF0000";
					} else {
						$color="#000000";
					}
	//				$cannot_roll_orders .= "\t".$suborder_row['ITEM_NUM']."\t".$suborder_row['PLT_COUNT']."\r\n";
					$cannot_roll_orders .= "<tr><td></td><td></td><td><font color=\"".$color."\">".$suborder_row['ITEM_NUM']."</font></td><td><font color=\"".$color."\">".$suborder_row['PALLETS']."</font></td><td><font color=\"".$color."\">".$suborder_row['CASES']."</font></td></tr>";
				}
			}
		}
	}

	foreach ($available_array as $k => $v) {
		$IH_after_roll .= "<tr><td>".$k."</td><td>".$v."</td></tr>";
	}

//	$rolled_orders .= "<tr><td colspan=3>&nbsp;</td></tr></table>";
//	$cannot_roll_orders .= "<tr><td colspan=3>&nbsp;</td></tr></table>";
	$rolled_orders .= "</table><br>";
	$cannot_roll_orders .= "</table><br>";
	$IH_after_roll .= "</table><br>";



	// Step 3
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'WDIROLLOVER'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$mailheaders = "From: ".$email_row['FROM']."\r\n";
	if($email_row['TEST'] == "Y"){
		$mailTO = "awalter@port.state.de.us";
//		$mailTO = "martym@port.state.de.us,ltreut@port.state.de.us";
		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
		$mailheaders .= "Bcc: archive@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us\r\n";
	} else {
		$mailTO = $email_row['TO'];
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
	}
	$mailheaders .= "Content-Type: text/html\r\n";
/*	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";
*/
	$mailSubject = $email_row['SUBJECT'];

	if($any_loads_at_all){
		$body = $email_row['NARRATIVE'];

		$body = str_replace("_0_", "<html><body>".$IH_output."</body></html>", $body);
		$body = str_replace("_1_", "<html><body>".$rolled_orders."</body></html>", $body);
		$body = str_replace("_2_", "<html><body>".$IH_after_roll."</body></html>", $body);
		$body = str_replace("_3_", "<html><body>".$cannot_roll_orders."</body></html>", $body);
	} else {
		$body = "No loads to Rollover.\r\n";
	}
/*
	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	echo $Content."\n\n";
*/
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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'WDIROLLOVER',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($Content, 0, 2000)."')";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}

