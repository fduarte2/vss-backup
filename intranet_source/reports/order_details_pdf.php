<?
  
  
  // All POW files need this session file included
  include("pow_session.php");

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
//   $cursor_cust = ora_open($conn);
//   $cursor_date = ora_open($conn);
   $cursor_order = ora_open($conn);
//   $cursor_comm = ora_open($conn);
   $cursor_pallet = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);

   $date_start = $HTTP_POST_VARS['date_start'];
   $date_end = $HTTP_POST_VARS['date_end'];
   $cust = $HTTP_POST_VARS['cust'];

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor, $sql);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$customer_name = $short_term_row['CUSTOMER_NAME'];

	$array_format = array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>1,'fontSize'=>8, 'rowGap'=>1, 'xPos'=>45, 'xOrientation'=>'right',
							'cols'=>array('barcode'=>array('width'=>157),
										'comm'=>array('width'=>79),
										'desc'=>array('width'=>96),
										'ship'=>array('width'=>87),
										'qty'=>array('width'=>47), 
										'checker'=>array('width'=>'60', 'justification'=>'right')));
										

	$where_sql = "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP
					WHERE CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CA.PALLET_ID = CT.PALLET_ID
						AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.ARRIVAL_NUM = VP.ARRIVAL_NUM(+)
						AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
						AND CA.DATE_OF_ACTIVITY >= TO_DATE('".$date_start."', 'MM/DD/YYYY')
						AND CA.DATE_OF_ACTIVITY < TO_DATE('".$date_end."', 'MM/DD/YYYY') + 1
						AND CA.SERVICE_CODE NOT IN ('12')
						AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
						AND CA.CUSTOMER_ID = '".$cust."'";

	$heading = array('barcode'=>'BARCODE',
					'comm'=>'COMMODITY',
					'desc'=>'VARIETY / DESCRIPTION',
					'ship'=>'SHIP',
					'qty'=>'QTY',
					'checker'=>'CHKR');

	$pallet_list = array();



   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter');

   $pdf->ezSetMargins(20,20,45,45);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$firstpage = true;
	$sql = "SELECT DISTINCT ORDER_NUM ".$where_sql." ORDER BY ORDER_NUM";

	$ora_success = ora_parse($cursor_order, $sql);
	$ora_success = ora_exec($cursor_order, $sql);
	if(!ora_fetch_into($cursor_order, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>No orders found</b>", 14, $center);
	} else {
		do{
			if($firstpage == true){
				$firstpage = false;
			} else {
				$pdf->ezNewPage();
			}
			$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_START,
							TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_END ".$where_sql." AND ORDER_NUM = '".$order_row['ORDER_NUM']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor, $sql);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$order_start = $short_term_row['THE_START'];
			$order_end = $short_term_row['THE_END'];

			$pdf->ezText("<b>Order Details</b>", 14, $center);
			$pdf->ezText("Customer: ".$customer_name, 10, $center);
			$pdf->ezText("Order#: ".$order_row['ORDER_NUM']."                       Printed On: ".date('m/d/Y'), 10, $center);
			$pdf->ezText("First Scan: ".$order_start."                          Last Scan: ".$order_end, 10, $center);
			$pdf->ezSetDy(-15);
			$pdf->ezText("<b>                                                                                        VARIETY /             </b>", 10, $left);
			$pdf->ezText("<b>BARCODE                                     COMMODITY         DESCRIPTION       SHIP                         QTY                 CHKR</b>", 10, $left);
//			$pdf->ezSetDy(8);
//			$pdf->ezText("<b>____________________________________________________________________________________________</b>", 10, $left);
			$order_pallets = 0;
			$order_cases = 0;

			$sql = "SELECT DISTINCT CA.PALLET_ID, COMP.COMMODITY_NAME, CARGO_DESCRIPTION, NVL(VESSEL_NAME, 'TRUCK') THE_VES, QTY_CHANGE, VARIETY,
						TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') DATE_ACT, ACTIVITY_ID
						".$where_sql." 
						AND ORDER_NUM = '".$order_row['ORDER_NUM']."' 
					ORDER BY CA.PALLET_ID";
//			echo $sql;
			$ora_success = ora_parse($cursor_pallet, $sql);
			$ora_success = ora_exec($cursor_pallet, $sql);
			if(!ora_fetch_into($cursor_pallet, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$pdf->ezText("<b>No data found for ".$order_row['ORDER_NUM'].".</b>", 14, $center);
			} else {
				$pallet_list = array();
				do {
							$order_pallets += 1;
							$order_cases += $pallet_row['QTY_CHANGE'];

							array_push($pallet_list, array('barcode'=>$pallet_row['PALLET_ID'],
															'comm'=>$pallet_row['COMMODITY_NAME'],
															'desc'=>$pallet_row['VARIETY']." ".$pallet_row['CARGO_DESCRIPTION'],
															'ship'=>$pallet_row['THE_VES'],
															'qty'=>$pallet_row['QTY_CHANGE'],
															'checker'=>get_emp_name($pallet_row['DATE_ACT'], $pallet_row['ACTIVITY_ID'], $conn)));


				} while(ora_fetch_into($cursor_pallet, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}

			$pdf->ezTable($pallet_list, '', '', $array_format);
			$pdf->ezSetDy(-8);
			$pdf->ezText("<b>Order Total:                                                       ".$order_pallets." Plt(s)</b>", 8, $left);
			$pdf->ezSetDy(8);
			$pdf->ezText("<b>".$order_cases."                                       </b>", 8, $right);

/*
			$space_padding = "";
			if(strlen($date_pallets) > 3){
				// do nothing
			} else {
				for($i = 3-strlen($date_pallets); $i > 0; $i--){
					$space_padding .= " ";
				}
			}
			$pdf->ezSetDy(8);
			$pdf->ezText("<b>____________________________________________________________________________________________</b>", 10, $left);
			$pdf->ezText("<b>Date Total for ".$date_row['THE_DATE']."                   Cnt = ".$date_pallets.$space_padding."                       Qty2 = ".$date_cases."</b>", 12, $left);
*/
		} while(ora_fetch_into($cursor_order, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
							
	include("redirect_pdf.php");







function get_emp_name($scandate, $activity_ID, $conn){
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('".$scandate."', 'MM/DD/YYYY')";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor, $sql);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] >= 1){
		$sql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$activity_ID."'";
	} else {
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -".strlen($activity_ID).") = '".$activity_ID."'";
	}

	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor, $sql);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return "UKN";
	} else {
		return $short_term_row['THE_EMP'];
	}
}

?>
