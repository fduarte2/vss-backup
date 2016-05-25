<?
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	$lcsconn = ocilogon("SAG_OWNER", "SAG", "BNI");
	if($lcsconn < 1){
		printf("Error logging on to the LCS Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	if($order_num != ""){
		$header_info = "ORDER#: ";
		$search_item = $order_num;
		$search_field = "ORDER_NUM";
		$print_field = "PALLET_ID";
		$print_table_column = "Pallet";
	}
	if($pallet_num != ""){
		$header_info = "PALLET ID: ";
		$search_item = $pallet_num;
		$search_field = "PALLET_ID";
		$print_field = "ORDER_NUM";
		$print_table_column = "Order#";
	}

	if($eport_customer_id != 0){
		$sql_cust = " AND CA.CUSTOMER_ID = '".$eport_customer_id."' ";
	} else {
		$sql_cust = "";
	}

	$sql = "SELECT TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_ACT, TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') DATE_ACT_NOTIME, 
				VARIETY || ' - ' || REMARK || ' - ' || CARGO_SIZE DESCR, COMP.COMMODITY_NAME,
				QTY_CHANGE, QTY_LEFT, SERVICE_CODE, CA.PALLET_ID, ACTIVITY_ID, CA.ARRIVAL_NUM, ORDER_NUM, CUSP.CUSTOMER_NAME, CA.DATE_OF_ACTIVITY
			FROM CARGO_ACTIVITY CA, CUSTOMER_PROFILE CUSP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
			WHERE CA.CUSTOMER_ID = CUSP.CUSTOMER_ID
				AND CA.".$search_field." = '".$search_item."'
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				".$sql_cust."
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL
				ORDER BY PALLET_ID, DATE_OF_ACTIVITY";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data)){
		
?>
<html>
<head>
<title>Eport - Activity Reports</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
                  <br />Port of Wilmington Activity Report<br /></font>
	          <hr>
	          <br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <font size = "2" face="Verdana">
                  No data with <? echo $header_info." ".$search_item; ?> were shipped out of the Port of Wilmington! 
                  <br /><br />Please <a href="./">go back</a> and re-enter.
                  </font>
                  <br /><br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Visited by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>
<?
	} else {
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM LABOR.EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen(ociresult($main_data, "ACTIVITY_ID")).") = '".ociresult($main_data, "ACTIVITY_ID")."'"; 
		$short_term_data = ociparse($lcsconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$emp = "UNKNOWN";
		} else {
			$emp = ociresult($short_term_data, "THE_EMP");
		}

		$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') FIRST_ACT, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') LAST_ACT
				FROM CARGO_ACTIVITY CA
				WHERE ".$search_field." = '".$search_item."'
					".$sql_cust."
					AND CA.SERVICE_CODE = '6'
					AND CA.ACTIVITY_DESCRIPTION IS NULL";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$first_scan = ociresult($short_term_data, "FIRST_ACT");
		$last_scan = ociresult($short_term_data, "LAST_ACT");

		$service_name = "Delivery"; // (There used to be a SQL to determine this, bt the main SQL now only looks for activity = 6, so...

		// initiate pdf writer
		include 'class.ezpdf.php';
		$pdf = new Cezpdf('letter','portriat');
		$pdf -> ezSetMargins(20,30,30,30);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

		// write out the intro. for the 1st page
		$pdf->ezText("<b>Port of Wilmington Tally</b>", 16, $center);
		$pdf->ezText("\n", 10, $center);
		$pdf->ezSetDy(-25);

		$general_info = array();

		// general information
		array_push($general_info, array('first'=>"Customer:  ".ociresult($main_data, "CUSTOMER_NAME"),
						'second'=>$header_info." ".$search_item,
						'third'=>"First Scan:  ".ociresult($main_data, "DATE_ACT_NOTIME")));
		array_push($general_info, array('first'=>"Checker:  ".$emp,
						'second'=>"",
						'third'=>"Shipment: ".$service_name));

		$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>''), 
				  '', array('cols'=>array('first'=>array('justification'=>'left'), 
							  'second'=>array('justification'=>'left'),
							  'third'=>array('justification'=>'left')),
					'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>12, 'width'=>540));


		$pdf->ezSetDy(-25);

		$rows = 0;
		$ctns = 0;

		$lot_info = array();
		$damage_info = array();

		do {
			$rows++;
			$ctns += ociresult($main_data, "QTY_CHANGE");
//'pallet'=>ociresult($main_data, "PALLET_ID"), 
			// add the line item
			array_push($lot_info, array('desc'=>ociresult($main_data, "DESCR"),
										'comm'=>ociresult($main_data, "COMMODITY_NAME"), 
										'dynamic'=>ociresult($main_data, $print_field),
										'qty'=>ociresult($main_data, "QTY_CHANGE"),
										'vessel'=>GetVesName(ociresult($main_data, "ARRIVAL_NUM"), $rfconn),
										'date'=>ociresult($main_data, "DATE_ACT")));

		} while(ocifetch($main_data));

		array_push($lot_info, array('desc'=>"Total:", 'comm'=>'', 
						'dynamic'=>$rows, 'qty'=>$ctns, 'vessel'=>'',
						'date'=>''));

		$pdf->ezText("Start Time (First Scan): ".$first_scan." - End Time (Last Scan): ".$last_scan);
		$pdf->ezSetDy(-10);

		$pdf->ezText("<b>Detail Information:</b>", 12, $left);
		$pdf->ezSetDy(-10);
		$pdf->ezTable($lot_info, array('desc'=>'Desc', 'comm'=>'Comm', 
						  'dynamic'=>$print_table_column, 'qty'=>'Quantity',
						  'vessel'=>'Vessel', 'date'=>'Date/Time'), 
				  '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>540, 'showLines'=>2, 
					'cols'=>array('desc'=>array('justification'=>'center'),
							  'comm'=>array('justification'=>'center'),
							  'dynamic'=>array('justification'=>'center'),
							  'qty'=>array('justification'=>'center'),
							  'vessel'=>array('justification'=>'center'),
							  'date'=>array('justification'=>'center'))));

		$all = $pdf->openObject();
		$pdf->saveState();
		$pdf->setStrokeColor(0,0,0,1);
		$pdf->addText(50,34,7,"Port of Wilmington, Printed: ".date('m/d/Y')."    Total Rows: ".$rows);
		$pdf->line(30,40,575,40);
		$pdf->restoreState();
		$pdf->closeObject();
		$pdf->addObject($all,'all');

		// redirect to a temporary PDF file instead of directly writing to the browser
		include("redirect_pdf.php");
	}

	
	
	
	function GetVesName($vessel, $rfconn){
		$sql = "select VESSEL_NAME from VESSEL_PROFILE where ARRIVAL_NUM = '".$vessel."'";
		$vesname = ociparse($rfconn, $sql);
		ociexecute($vesname);
		if(!ocifetch($vesname)){
			return $vessel."-"."TRUCKED-IN";
		} else {
			return ociresult($vesname, "VESSEL_NAME"); //$vessel."-".
		}
	}

?>