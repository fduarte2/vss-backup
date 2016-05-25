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
   $cursor_cust = ora_open($conn);
   $cursor_date = ora_open($conn);
   $cursor_order = ora_open($conn);
//   $cursor_comm = ora_open($conn);
//   $cursor_pallet = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);

   $date_start = $HTTP_POST_VARS['date_start'];
   $date_end = $HTTP_POST_VARS['date_end'];
   $wing = $HTTP_POST_VARS['wing'];

	$array_format = array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0,'fontSize'=>8, 'rowGap'=>1, 'xPos'=>45, 'xOrientation'=>'right',
							'cols'=>array('date'=>array('width'=>72),
										'cust'=>array('width'=>85),
										'order'=>array('width'=>79),
										'barcodes'=>array('width'=>61),
										'qty2'=>array('width'=>47),
										'wing'=>array('width'=>37, 'justification'=>'center'),
										'firstscan'=>array('width'=>55),
										'lastscan'=>array('width'=>55)));
										

	$where_sql = "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP
					WHERE CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CA.PALLET_ID = CT.PALLET_ID
						AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.ARRIVAL_NUM = VP.ARRIVAL_NUM(+)
						AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
						AND CA.DATE_OF_ACTIVITY >= TO_DATE('".$date_start."', 'MM/DD/YYYY')
						AND CA.DATE_OF_ACTIVITY < TO_DATE('".$date_end."', 'MM/DD/YYYY') + 1
						AND CA.SERVICE_CODE NOT IN ('1', '9', '12')
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND CA.ACTIVITY_NUM != '1'
						AND CA.CUSTOMER_ID != '1'";
	if($wing != "All"){
		$where_sql .= " AND DECODE(COMP.COMMODITY_TYPE, 'CLEMENTINES', SUBSTR(WAREHOUSE_LOCATION, 0, 1), ACTIVITY_BILLED) = '".$wing."'";
	} else {
		$where_sql .= " AND (
							CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'CLEMENTINES') 
							OR 
							ACTIVITY_BILLED IN (SELECT WHS_ID FROM WAREHOUSE_LIST)
							)";
	}

	$heading = array('date'=>'DATE',
					'cust'=>'CUSTOMER',
					'order'=>'ORDER#',
					'barcodes'=>'BARCODES',
					'qty2'=>'QTY2',
					'wing'=>'WING',
					'firstscan'=>'FIRST SCAN',
					'lastscan'=>'LAST SCAN');

	$order_list = array();



   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter');

   $pdf->ezSetMargins(20,20,45,45);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE ".$where_sql." ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";

	$ora_success = ora_parse($cursor_date, $sql);
	$ora_success = ora_exec($cursor_date, $sql);
	if(!ora_fetch_into($cursor_date, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>No outbound activity found for given date range.</b>", 14, $center);
	} else {
		do{
			if($date_pallets == 0){
//				$pdf->ezNewPage();
				$pdf->ezText("<b>Daily Outbound Activity Summary</b>", 14, $center);
				$pdf->ezText("From: ".$date_start." to ".$date_end, 10, $left);
				$pdf->ezText("Printed On: ".date('m/d/Y'), 10, $left);
			}
//			$pdf->ezText("<b>Customer: ".$cust_row['CUSTOMER_NAME']."</b>", 14, $left);
			$pdf->ezSetDy(-20);
			$pdf->ezText("<b>".$date_row['THE_DATE']."</b>", 12, $left);
			$pdf->ezText("<b>                                                        ORDER#           COUNT           QTY2       WING      START        END</b>", 10, $left);
			$pdf->ezSetDy(8);
			$pdf->ezText("<b>____________________________________________________________________________________________</b>", 10, $left);
			$date_pallets = 0;
			$date_cases = 0;

			$sql = "SELECT DISTINCT CA.CUSTOMER_ID, CUSP.CUSTOMER_NAME ".$where_sql." 
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_row['THE_DATE']."' 
					ORDER BY CA.CUSTOMER_ID";
//			echo $sql;
			$ora_success = ora_parse($cursor_cust, $sql);
			$ora_success = ora_exec($cursor_cust, $sql);
			if(!ora_fetch_into($cursor_cust, $cust_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$pdf->ezText("<b>No data found for ".$date_row['THE_DATE'].".</b>", 14, $center);
			} else {
				do {
					$pdf->ezSetDy(-5);
					$pdf->ezText("<b>     ".$cust_row['CUSTOMER_NAME']."</b>", 10, $left);
					$cust_pallets = 0;
					$cust_cases = 0;

					$sql = "SELECT ORDER_NUM, COUNT(DISTINCT CA.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CTNS, 
								DECODE(COMP.COMMODITY_TYPE, 'CLEMENTINES', NVL(SUBSTR(WAREHOUSE_LOCATION, 0, 1), 'NA'), NVL(ACTIVITY_BILLED, 'NA')) THE_WING,
								TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_END
							".$where_sql." 
								AND CA.CUSTOMER_ID = '".$cust_row['CUSTOMER_ID']."'
								AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_row['THE_DATE']."'
							GROUP BY ORDER_NUM, DECODE(COMP.COMMODITY_TYPE, 'CLEMENTINES', NVL(SUBSTR(WAREHOUSE_LOCATION, 0, 1), 'NA'), NVL(ACTIVITY_BILLED, 'NA'))
							ORDER BY ORDER_NUM, DECODE(COMP.COMMODITY_TYPE, 'CLEMENTINES', NVL(SUBSTR(WAREHOUSE_LOCATION, 0, 1), 'NA'), NVL(ACTIVITY_BILLED, 'NA'))";
//					echo $sql;
					$ora_success = ora_parse($cursor_order, $sql);
					$ora_success = ora_exec($cursor_order, $sql);
					if(!ora_fetch_into($cursor_order, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$pdf->ezText("<b>No Orders for ".$cust_row['CUSTOMER_ID']."/".$date_row['THE_DATE'].".</b>", 14, $center);
					} else {
						$order_list = array();
						do {
//							$comm_pallets = 0;
//							$comm_cases = 0;

							$cust_pallets += $order_row['THE_PLTS'];
							$cust_cases += $order_row['THE_CTNS'];
							$date_pallets += $order_row['THE_PLTS'];
							$date_cases += $order_row['THE_CTNS'];

							array_push($order_list, array('date'=>'',
															'cust'=>'',
															'order'=>$order_row['ORDER_NUM'],
															'barcodes'=>$order_row['THE_PLTS'],
															'qty2'=>$order_row['THE_CTNS'],
															'wing'=>$order_row['THE_WING'],
															'firstscan'=>$order_row['THE_START'],
															'lastscan'=>$order_row['THE_END']));


						} while(ora_fetch_into($cursor_order, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
					}

					$space_padding = "";
					if(strlen($cust_pallets) > 3){
						// do nothing
					} else {
						for($i = 3-strlen($cust_pallets); $i > 0; $i--){
							$space_padding .= "  ";
						}
					}
					$pdf->ezTable($order_list, '', '', $array_format);
					$pdf->ezSetDy(-8);
					$pdf->ezText("<b>          Customer Sub-Total for ".substr($cust_row['CUSTOMER_ID']."                    ", 0, 20)."Cnt = ".$cust_pallets.$space_padding."       Qty2 = ".$cust_cases."     </b>", 10, $left);

				} while (ora_fetch_into($cursor_cust, $cust_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}

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
			$pdf->ezText("<b>Date Total for ".$date_row['THE_DATE']."                   Cnt = ".$date_pallets.$space_padding."       Qty2 = ".$date_cases."</b>", 12, $left);

		} while(ora_fetch_into($cursor_date, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
							
	include("redirect_pdf.php");
?>
