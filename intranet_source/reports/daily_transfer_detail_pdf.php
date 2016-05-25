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
   $cursor_vessel = ora_open($conn);
   $cursor_comm = ora_open($conn);
   $cursor_pallet = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);

   $the_date = $HTTP_POST_VARS['the_date'];

	$array_format = array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0,'fontSize'=>8, 'rowGap'=>1, 'xPos'=>45, 'xOrientation'=>'right',
							'cols'=>array('LR'=>array('width'=>72),
										'ord'=>array('width'=>85),
										'date'=>array('width'=>79),
										'comm'=>array('width'=>36),
										'commname'=>array('width'=>67),
										'plt'=>array('width'=>147),
										'qty'=>array('width'=>30, 'justification'=>'right')));
										

	$where_sql = "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP
					WHERE CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CA.PALLET_ID = CT.PALLET_ID
						AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.ARRIVAL_NUM = VP.ARRIVAL_NUM(+)
						AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
						AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$the_date."'
						AND CA.SERVICE_CODE = '11'
						AND CA.CUSTOMER_ID != '1'";

	$heading = array('LR'=>'ARRIVAL',
					'ord'=>'ORDER',
					'date'=>'DATE',
					'comm'=>'CODE',
					'commname'=>'NAME',
					'plt'=>'PALLET ID',
					'qty'=>'QTY');

	$pallet_list = array();



   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter');

   $pdf->ezSetMargins(20,20,45,45);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT DISTINCT CA.CUSTOMER_ID, CUSP.CUSTOMER_NAME ".$where_sql." ORDER BY CA.CUSTOMER_ID";

	$ora_success = ora_parse($cursor_cust, $sql);
	$ora_success = ora_exec($cursor_cust, $sql);
	if(!ora_fetch_into($cursor_cust, $cust_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>No transfers found for given date.</b>", 14, $center);
	} else {
		do{
			if($cust_pallets > 0){
				$pdf->ezNewPage();
			}
			$pdf->ezText("<b>Daily Transfer Report</b>", 14, $left);
			$pdf->ezText("<b>Date of Transfer: ".$the_date."</b>", 14, $left);
			$pdf->ezText("<b>Printed On: ".date('m/d/Y')."</b>", 14, $left);
			$pdf->ezText("<b>Customer: ".$cust_row['CUSTOMER_NAME']."</b>", 14, $left);
			$pdf->ezSetDy(-20);
			$pdf->ezText("<b>ARRIVAL      ORDER            DATE             CODE  NAME        PALLET ID                         QTY</b>", 12, $left);
			$pdf->ezSetDy(8);
			$pdf->ezText("<b>____________________________________________________________________________________________</b>", 10, $left);
			$cust_pallets = 0;
			$cust_cases = 0;
/*
			array_push($pallets, array('LR'=>'-------',
										'ord'=>'-----',
										'date'=>'----',
										'comm'=>'----',
										'commname'=>'----',
										'plt'=>'---------',
										'qty'=>'---'));
*/

			$sql = "SELECT DISTINCT CA.ARRIVAL_NUM, NVL(VP.VESSEL_NAME, 'TRUCKED IN CARGO') THE_VES
					".$where_sql." AND CA.CUSTOMER_ID = '".$cust_row['CUSTOMER_ID']."' ORDER BY CA.ARRIVAL_NUM";
//			echo $sql;
			$ora_success = ora_parse($cursor_vessel, $sql);
			$ora_success = ora_exec($cursor_vessel, $sql);
			if(!ora_fetch_into($cursor_vessel, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$pdf->ezText("<b>No Vessels found for ".$cust_row['CUSTOMER_ID'].".</b>", 14, $center);
			} else {
				do {
					$pdf->ezSetDy(-5);
					$pdf->ezText("<b>".$vessel_row['THE_VES']."</b>", 10, $left);
					$vessel_pallets = 0;
					$vessel_cases = 0;

					$sql = "SELECT DISTINCT COMP.COMMODITY_CODE, COMP.COMMODITY_NAME 
							".$where_sql." 
								AND CA.CUSTOMER_ID = '".$cust_row['CUSTOMER_ID']."'
								AND CA.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
							ORDER BY COMP.COMMODITY_CODE";
//					echo $sql;
					$ora_success = ora_parse($cursor_comm, $sql);
					$ora_success = ora_exec($cursor_comm, $sql);
					if(!ora_fetch_into($cursor_comm, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$pdf->ezText("<b>No Commodities found for ".$cust_row['CUSTOMER_ID']."/".$vessel_row['ARRIVAL_NUM'].".</b>", 14, $center);
					} else {
						do {
							$comm_pallets = 0;
							$comm_cases = 0;

							$sql = "SELECT DISTINCT CA.PALLET_ID, CA.QTY_CHANGE, CA.ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY   HH24:MI') THE_DATE
									".$where_sql." 
										AND CA.CUSTOMER_ID = '".$cust_row['CUSTOMER_ID']."'
										AND CA.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
										AND CT.COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."'
									ORDER BY CA.ORDER_NUM, CA.PALLET_ID";
							$ora_success = ora_parse($cursor_pallet, $sql);
							$ora_success = ora_exec($cursor_pallet, $sql);
							if(!ora_fetch_into($cursor_pallet, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
								$pdf->ezText("<b>No Pallets found for ".$cust_row['CUSTOMER_ID']."/".$vessel_row['ARRIVAL_NUM']."/".$comm_row['COMMODITY_CODE'].".</b>", 14, $center);
							} else {
								$pallet_list = array();
								do {
									$cust_pallets += 1;
									$cust_cases += $pallet_row['QTY_CHANGE'];
									$vessel_pallets += 1;
									$vessel_cases += $pallet_row['QTY_CHANGE'];
									$comm_pallets += 1;
									$comm_cases += $pallet_row['QTY_CHANGE'];

									array_push($pallet_list, array('LR'=>$vessel_row['ARRIVAL_NUM'],
																	'ord'=>$pallet_row['ORDER_NUM'],
																	'date'=>$pallet_row['THE_DATE'],
																	'comm'=>$comm_row['COMMODITY_CODE'],
																	'commname'=>$comm_row['COMMODITY_NAME'],
																	'plt'=>$pallet_row['PALLET_ID'],
																	'qty'=>$pallet_row['QTY_CHANGE']));
								} while(ora_fetch_into($cursor_pallet, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
							}

							$pdf->ezTable($pallet_list, '', '', $array_format);
							$pdf->ezSetDy(-5);
							$pdf->ezText("<b>Commodity Sub-Total for ".$comm_row['COMMODITY_CODE']."-".$comm_row['COMMODITY_NAME']."     Plts = ".$comm_pallets."      Cases = ".$comm_cases."       </b>", 8, $right);

						} while(ora_fetch_into($cursor_comm, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
					}

					$pdf->ezSetDy(-5);
					$pdf->ezText("<b>Vessel Sub-Total for ".$vessel_row['THE_VES']."                           Plts = ".$vessel_pallets."      Cases = ".$vessel_cases."     </b>", 10, $right);

				} while (ora_fetch_into($cursor_vessel, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}

			$pdf->ezSetDy(8);
			$pdf->ezText("<b>____________________________________________________________________________________________</b>", 10, $left);
			$pdf->ezText("<b>Customer Total for ".$cust_row['CUSTOMER_NAME']."                           Plts = ".$cust_pallets."      Cases = ".$cust_cases."</b>", 12, $left);

		} while(ora_fetch_into($cursor_cust, $cust_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
							
	include("redirect_pdf.php");
?>
