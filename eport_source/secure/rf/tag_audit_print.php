<?
   // File: tag_audit_print.php
   //
   // This page generates a PDF file of the tracker report

   // form processor
   if(!$pallet_id){
     printf("Missing Pallet ID- please go back and try again (Should not get here).");
     exit;
   }
   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);
   $loop_cursor = ora_open($ora_conn);
   
   // Check to see if we have any like values
   $stmt = "select count(*) count from cargo_tracking where pallet_id like '%$pallet_id%'";
   if($ves != ""){
	   $stmt .= " and arrival_num = '".$ves."'";
   }
   if($cust != ""){
	   $stmt .= " and receiver_id = '".$cust."'";
   }
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $count = $count['COUNT'];
   if($count <= 0){
     printf("Invalid Pallet ID- please try again!");
     exit;
   }
   else if($count > 1){
     header("Location: tag_audit.php?pallet_id=$pallet_id");
     exit;
   }

   if($season == "" || $season == date('Y', mktime(0,0,0,date('m') + 4, date('d'), date('Y')))){
	   $cargo_tracking_table = "cargo_tracking";
	   $cargo_activity_table = "cargo_activity";
   } else {
	   $cargo_tracking_table = "cargo_tracking_".$season;
	   $cargo_activity_table = "cargo_activity_".$season;
   }
//	echo "VES: ".$ves."\r\n<br>";
//	echo "CUST: ".$cust."\r\n<br>";
   // Check to see if we have any like values
//   $stmt = "select count(*) count from cargo_tracking where pallet_id like '%$pallet_id%'";
   $stmt = "select distinct arrival_num, receiver_id from $cargo_tracking_table where pallet_id like '%$pallet_id%'";
   if($ves != ""){
	   $stmt .= " and arrival_num = '".$ves."'";
   }
   if($cust != ""){
	   $stmt .= " and receiver_id = '".$cust."'";
   }
//   echo $stmt; exit;
   $ora_success = ora_parse($loop_cursor, $stmt);
   $ora_success = ora_exec($loop_cursor);
   if(!ora_fetch_into($loop_cursor, $loop_count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     printf("No pallets found.  Please use your browser's back button to return to the previous page.");
     exit;
   } else {
	   $firstpage = true;
	   include 'class.ezpdf.php';
	   $pdf = new Cezpdf('letter','portrait');
	   $pdf->ezSetMargins(20,30,30,30);
	   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

		do {
			if($firstpage == true){
				$firstpage = false;
			} else {
				$pdf->ezNewPage();
			}

		   // Grab the cargo tracking information
		   $stmt = "select ct.pallet_id, ct.cargo_description, ct.warehouse_location, ct.arrival_num, ct.qty_received, ct.qty_damaged, ct.qty_in_house, ct.free_time_end, ct.billing_storage_date, to_char(date_received, 'MM/DD/YYYY HH24:MI:SS') date_received, cp.commodity_name, cust_p.customer_name, ct.receiver_id 
					from $cargo_tracking_table ct, commodity_profile cp, customer_profile cust_p where pallet_id like '%$pallet_id%' and cp.commodity_code = ct.commodity_code and ct.receiver_id = cust_p.customer_id and ct.receiver_id = '".$loop_count['RECEIVER_ID']."' and ct.arrival_num = '".$loop_count['ARRIVAL_NUM']."'";
//			echo $stmt; exit;
		   $ora_success = ora_parse($cursor, $stmt);
		   $ora_success = ora_exec($cursor);
		   ora_fetch_into($cursor, $cargo_tracking, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		   // Init some VARS
		   $pallet_id = $cargo_tracking['PALLET_ID'];
		   $customer_name = $cargo_tracking['CUSTOMER_NAME'];
		   $customer_num = $cargo_tracking['RECEIVER_ID'];
		   $commodity = $cargo_tracking['COMMODITY_NAME'];
		   $cargo_description = substr($cargo_tracking['CARGO_DESCRIPTION'], 0, 10);
		   $warehouse_location = $cargo_tracking['WAREHOUSE_LOCATION'];
		   $vessel_number = $cargo_tracking['ARRIVAL_NUM'];
		   $qty_received = $cargo_tracking['QTY_RECEIVED'];
		   $qty_damaged = $cargo_tracking['QTY_DAMAGED'];
		   $qty_in_house = $cargo_tracking['QTY_IN_HOUSE'];

		   $stmt = "select vessel_name from vessel_profile where arrival_num = '$vessel_number'";
		   $ora_success = ora_parse($cursor, $stmt);
		   $ora_success = ora_exec($cursor);
		   ora_fetch_into($cursor, $vessel_name, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		   $vessel_name = $vessel_name['VESSEL_NAME'];
		   if($vessel_name == ""){
			 $vessel_name = "Trucked In";
		   }

		   // format the dates
		   if ($cargo_tracking['DATE_RECEIVED'] != "") {
			  $date_received = date('m/d/y h:i:s A', strtotime($cargo_tracking['DATE_RECEIVED']));
			  $qty_received_text = "Qty Received:";
		   } else {
			  $date_received = "N/A";
			  // Reset Qty if we have not received this pallet
			  $qty_received_text = "Qty Expected:";
			  $qty_damaged = "";
			  $qty_in_house = "";
		   }
		   if ($cargo_tracking['FREE_TIME_END'] != "") {
			  $free_time_end = date('m/d/y', strtotime($cargo_tracking['FREE_TIME_END']));
		   } else {
			  $free_time_end = "";
		   }
		   if ($cargo_tracking['BILLING_STORAGE_DATE'] != "") {
			  $billing_storage_date = date('m/d/y', strtotime($cargo_tracking['BILLING_STORAGE_DATE']));
		   } else {
			  $billing_storage_date = "";
		   }


		   // Activity Log
		/*   $stmt = "select * from activity_log where pallet_id = '$pallet_id' order by
		rowid";
		   $ora_success = ora_parse($cursor, $stmt);
		   $ora_success = ora_exec($cursor);
		   ora_fetch_into($cursor, $activity_log, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		   $receiver = $activity_log['CHECKER_NAME'];
		   if($receiver == ""){
			 $receiver = "N/A";
		   }*/
		   $receiver = get_checker_name($pallet_id, $customer_num, $vessel_number, '1', $ora_conn, $cargo_activity_table);

		   // Write out the intro.
		   $pdf->ezText("<b>Fruit Tag Audit</b>", 18, $center);
		   $pdf->ezSetDy(-20);
		   $pdf->ezText("<b>Port of Wilmington</b>", 12, $left);
		   $pdf->ezText("1 Hausel Road                                                                                                        Phone (302) 472-7710", 12, $left);
		   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                             Fax (302) 472-7742", 12, $left);
		   $pdf->ezSetDy(-15);
		   $pdf->ezText("<b>Owner: $customer_name\nPallet ID:  $pallet_id</b>", 12, $left);

		   $pdf->ezSetDy(-30);
		   $pdf->ezText("Pallet Information:", 11, $left);


		   // build a small table for activity details
		   $pallet_data = array();	// store data to be printed
		   $print_data = array();

		   // Cargo Activity
		   $stmt = "select ca.order_num, ca.qty_change, to_char(date_of_activity, 'MM/DD/YYYY HH24:MI:SS') date_of_activity, cp.customer_name, sc.service_name, activity_num 
					from $cargo_activity_table ca, customer_profile cp, service_category sc 
					where pallet_id = '$pallet_id' and ca.customer_id = cp.customer_id and ca.service_code = sc.service_code 
					and ca.customer_id = '".$loop_count['RECEIVER_ID']."' and ca.arrival_num = '".$loop_count['ARRIVAL_NUM']."' 
					order by activity_num";
		   $ora_success = ora_parse($cursor, $stmt);
		   $ora_success = ora_exec($cursor);
		   while(ora_fetch_into($cursor, $cargo_activity, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			 // Push each activity line
			 $order_num = $cargo_activity['ORDER_NUM'];

			 if ($cargo_activity['DATE_OF_ACTIVITY'] != "") {
				$date_of_activity = date('m/d/y h:i:s A', strtotime($cargo_activity['DATE_OF_ACTIVITY']));
			 } else {
				$date_of_activity = "";
			 }

			 array_push($pallet_data, array('order_num'=>$order_num, 'date_of_activity'=>$date_of_activity, 'cases'=>$cargo_activity['QTY_CHANGE'], 'customer'=>$cargo_activity['CUSTOMER_NAME'], 'service'=>$cargo_activity['SERVICE_NAME'], 'checker'=>get_checker_name($pallet_id, $customer_num, $vessel_number, $cargo_activity['ACTIVITY_NUM'], $ora_conn, $cargo_activity_table)));
		   }
		  

		   if($qty_in_house <= 0){
			 $billing_storage_date = "No Storage to Bill";
		   }
		  
		   // Print the initial array that shows pallet information
		   array_push($print_data, 
				  array('first'=>"Commodity:\nCargo Desc:\nReceiver:\n\n\n$qty_received_text\n", 
					'second'=>"$commodity\n$cargo_description\n$receiver\n\n\n$qty_received\n",
					'third'=>'',
					'fourth'=>"Vessel:\nPort Location:\nDate Received:\n\n\nQty Damaged:\n",
					'fifth'=>"$vessel_name\n$warehouse_location\n$date_received\n\n\n$qty_damaged\n",
					'sixth'=>'',
					'seventh'=>"Vessel Number:\nFree Time End:\nNext Storage Period:\n\n\nQty In House:\n",
					'eighth'=>"$vessel_number\n$free_time_end\n$billing_storage_date\n\n\n$qty_in_house\n"));

		   $pdf->ezTable($print_data,  array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 
							 'fifth'=>'', 'sixth'=>'', 'seventh'=>'', 'eighth'=>''), '', 
						   array('cols'=>array('first'=>array('justification'=>'left'), 
									   'second'=>array('justification'=>'left'),
									   'fourth'=>array('justification'=>'left'),
									   'fifth'=>array('justification'=>'left'),
									   'seventh'=>array('justification'=>'left'),
									   'eighth'=>array('justification'=>'left')),
							 'shaded'=>0, 'showLines'=>0, 'width'=>545));

		   $pdf->ezSetDy(-10);

		   $pdf->ezTable($pallet_data, array('order_num'=>'Order #', 'customer'=>'Customer', 'date_of_activity'=>'Date', 'cases'=>'Cases Change', 'service'=>'Service Type', 'checker'=>'Employee'
		),
			 '',  array('cols'=>array('order_num'=>array('justification'=>'left'),
		'customer'=>array('justification'=>'left'),
		'date_of_activity'=>array('justification'=>'left'),
		'cases'=>array('justification'=>'right'),
		'service'=>array('justification'=>'center'),
		'checker'=>array('justification'=>'center')),
			 'shaded'=>0, 'showLines'=>2, 'width'=>545));

		   $today = date('m/j/y');
		   $format = "Port of Wilmington, " . $today;
		   $pdf->line(20,40,578,40);
		   $all = $pdf->openObject();
		   $pdf->saveState();
		   $pdf->setStrokeColor(0,0,0,1);
		   $pdf->line(20,822,578,822);
		   $pdf->addText(50,34,6, $format);
		   $pdf->restoreState();
		   $pdf->closeObject();
		   $pdf->addObject($all,'all');
		} while(ora_fetch_into($loop_cursor, $loop_count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
   }

   include("redirect_pdf.php");









function get_checker_name($barcode, $cust, $vessel, $act_num, $ora_conn, $ca){
	$cursor = ora_open($ora_conn);


	$stmt = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID 
			FROM $ca
			WHERE PALLET_ID = '".$barcode."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $stmt."<br>";
	$ora_success = ora_parse($cursor, $stmt);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $cargo_activity, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$date = $cargo_activity['THE_DATE'];
	$ID = $cargo_activity['ACTIVITY_ID'];

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('".$date."', 'MM/DD/YYYY')";
	$ora_success = ora_parse($cursor, $stmt);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $scan_date, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($scan_date['THE_COUNT'] >= 1){
		$sql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$ID."'";
	} else {
		while(strlen($ID) < 4){
			$ID = "0".$ID;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -".strlen($ID).") = '".$ID."'";
	}
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $checker, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	return $checker['THE_EMP'];
}



?>
