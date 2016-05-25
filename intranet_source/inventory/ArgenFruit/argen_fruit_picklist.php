<?
//
//	This page generates a PDF Pick List for outstanding Argen Fruit orders.
//
//	The PDF can be generated directly (skipping the form) by including the order number and whether to include cargo details in the URL:
//	"http://intranet/inventory/ArgenFruit/argen_fruit_picklist.php?order_num=12345&include_details=yes"
//
//******************************************************************************************

  // All POW files need this session file included
  include("pow_session.php");


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $_POST['submit'];
	$order_num = $_POST['order_num'];
	$include_details = $_POST['include_details'];
	if($order_num == ""){
		$order_num = $_GET['order_num'];
		$include_details = $_GET['include_details']; //the only valid value is "yes"
	}
	if($order_num == ""){

		// Define some vars for the skeleton page
		$title = "Inventory System - Walmart";
		$area_type = "INVE";

		// Provides header / leftnav
		include("pow_header.php");
		if($access_denied){
			printf("Access Denied from INVE system");
			include("pow_footer.php");
			exit;
		}
?>

<h1>Argen Fruit Picklist Screen</h1>

<form name="select_bills" action="argen_fruit_picklist.php" method="post">
	<div class="field">
		<label for="order_num">Enter Order #:</label>
		<input id="order_num" type="text" name="order_num" size="15" maxlength="12" value="<? echo $order_num; ?>">
	</div>
	<div class="field">
		<input id="incl_details" type="checkbox" name="include_details" value="yes" checked>
		<label for="incl_details">Include Cargo Details</label>
	</div>
	<div class="submitButton">
		<input type="submit" name="submit" value="Retrieve Load">
	</div>
</form>

<?
		include("pow_footer.php");
		
	} else {
		include 'class.ezpdf.php';
		$pdf = new Cezpdf('letter');
		$pdf -> ezSetMargins(10,10,10,10);

		$sql = "SELECT AST.STATUS, CUSTOMER_NAME, NVL(CONSIGNEE_NAME, 'N/A') THE_CONS, AST.DESCR, LOAD_NUM
				FROM ARGENFRUIT_ORDER_HEADER AOH, EXP_CONSIGNEE ECON, EXP_CUSTOMER ECUST, ARGFRUIT_STATUS AST
				WHERE AOH.ORDER_NUM = '".$order_num."'
					AND AOH.CUSTOMER_ID = ECUST.CUSTOMER_CODE
					AND AOH.CONSIGNEE_ID = ECON.CONSIGNEE_CODE(+)
					AND AOH.STATUS = AST.STATUS";
		//echo $sql;
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			$pdf->ezText("<b>Order ".$order_num." Not Found in System</b>", 12);
			include("redirect_pdf.php");
		} 
		elseif(ociresult($stid, "STATUS") > 5) {
			$pdf->ezText("<b>Order ".$order_num." status is ".ociresult($stid, "DESCR").". Cannot Generate Picklist.</b>", 12);
			include("redirect_pdf.php");
		}
		$order_status = 'ORDER STATUS: ' . ociresult($stid, "STATUS") . '-' . ociresult($stid, "DESCR");
		
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$order_num."'
					AND SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CUSTOMER_ID = '1626'";
		// echo $sql;
		$extra_message = ociparse($rfconn, $sql);
		ociexecute($extra_message);
		ocifetch($extra_message);
		if(ociresult($extra_message, "THE_PLTS") > 0){
			$more_detail = "IMPORTANT NOTE:  ".ociresult($extra_message, "THE_PLTS")." pallets have already been scanned on this order at the time this picklist was generated!";
		} else {
			$more_detail = "";
		}

		$status = ociresult($stid, "DESCR");
		$cust = ociresult($stid, "CUSTOMER_NAME");
		$cons = ociresult($stid, "THE_CONS");
		$load_num = ociresult($stid, "LOAD_NUM");
		$voucher = ociresult($stid, "VOUCHER_NUM");
		//$voucher = '';

		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezSetY(765);
		$pdf->ezText("Argentine Fruit Order - Printed ".date('m/d/Y h:i:s a'), 14, $center);
		$pdf->ezSetY(745);
		$pdf->ezText("Destination Customer: ".$cust, 12, $left);
		$pdf->ezSetY(745);
		$pdf->ezText("Destination Consignee: ".$cons, 12, $right);
		$pdf->ezSetY(725);
		$pdf->ezText($more_detail, 10, $center);
		$pdf->ezSetY(705);
		$pdf->ezText("<b>CUSTOMER: Bridges (1626)</b>", 12, $center);
		$pdf->ezSetY(685);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*1626*", 48, $center);
		$pdf->ezSetY(655);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezText("________________________________________________________________________________________", 12, $center);
		$pdf->ezSetY(635);
		$pdf->ezText($order_status, 10, $left);
		$pdf->ezSetY(635);
		$pdf->ezText("ORDER NUMBER:  ".$order_num, 12, $right);
		$pdf->ezSetY(615);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*".$order_num."*", 48, $right);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezSetY(585);
		$pdf->ezText("________________________________________________________________________________________", 12, $center);
		$pdf->ezSetDy(-10);

		$detail_array = array();
		$pallet_list_array = array();
		$case_sum = 0;

		$sql = "SELECT *
				FROM ARGENFRUIT_ORDER_DETAIL
				WHERE ORDER_NUM = '".$order_num."'
				ORDER BY ORDER_DETAIL";
		$details = ociparse($rfconn, $sql);
		ociexecute($details);
		while(ocifetch($details)) {
			$location_list = "";
			$need_location_newline = false;
			$vsl_list = "";
			$label_list = "";
			$case_sum += ociresult($details, "CARTONS");


			$sql = "select *
					from 
					(	SELECT
							COUNT(*) THE_COUNT,
							COUNT(distinct CA.PALLET_ID) AS PLTS_SCANNED,
							NVL(WAREHOUSE_LOCATION, 'UNKNOWN') THE_HOUSE,
							CT.ARRIVAL_NUM, SUBSTR(VESSEL_NAME, 0, 22) THE_VES,
							SUBSTR(REMARK, INSTR(REMARK, '-')+1) THE_LABEL
						FROM CARGO_TRACKING CT
						JOIN
							VESSEL_PROFILE VP
							ON CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
						LEFT JOIN
							CARGO_ACTIVITY CA
							ON CA.PALLET_ID = CT.PALLET_ID
							AND CA.CUSTOMER_ID = CT.RECEIVER_ID
							AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
							AND CA.SERVICE_CODE = '6'
							AND CA.ORDER_NUM = '$order_num'
						WHERE RECEIVER_ID = '1626'
							AND CT.QTY_IN_HOUSE > 0
							AND CT.DATE_RECEIVED IS NOT NULL
							AND CT.VARIETY = '".ociresult($details, "VARIETY")."'
							AND CT.BATCH_ID = '".ociresult($details, "VOUCHER_NUM")."'
							AND (CT.BOL = '".ociresult($details, "IMPORT_CODE")."' OR CT.CARGO_SIZE = '".ociresult($details, "IMPORT_SIZE")."')
						GROUP BY
							  NVL(WAREHOUSE_LOCATION, 'UNKNOWN'),
							  CT.ARRIVAL_NUM,
							  SUBSTR(VESSEL_NAME, 0, 22),
							  SUBSTR(REMARK, INSTR(REMARK, '-')+1)
						ORDER BY
							  CT.ARRIVAL_NUM,
							  SUBSTR(REMARK,
							  INSTR(REMARK, '-')+1)
					)";
					// where (PLTS_SCANNED <> 0 OR QTY_IN_HOUSE <> 0)";
			// echo "<pre>$sql</pre>";
			
			$sql = "SELECT
						COUNT(*) THE_COUNT,
						NVL(WAREHOUSE_LOCATION, 'UNKNOWN') THE_HOUSE,
						CT.ARRIVAL_NUM, SUBSTR(VESSEL_NAME, 0, 22) THE_VES,
						SUBSTR(REMARK, INSTR(REMARK, '-')+1) THE_LABEL,
						case
							when QTY_IN_HOUSE = 0 and count(CA.PALLET_ID) <> 0 then count(CA.PALLET_ID)
							when QTY_IN_HOUSE <> 0 then -2
							else -1
						end as PLTS_SCANNED,
						QTY_IN_HOUSE
					FROM CARGO_TRACKING CT
					JOIN VESSEL_PROFILE VP
						ON CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					LEFT JOIN
						CARGO_ACTIVITY CA
						ON
							CA.PALLET_ID = CT.PALLET_ID
							AND CA.CUSTOMER_ID = CT.RECEIVER_ID
							AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
							AND CA.SERVICE_CODE = '6'
							AND CA.ORDER_NUM = '$order_num'
					WHERE
						RECEIVER_ID = '1626'
					--    AND QTY_IN_HOUSE > 0
						AND CT.DATE_RECEIVED IS NOT NULL
							AND CT.VARIETY = '".ociresult($details, "VARIETY")."'
							AND CT.BATCH_ID = '".ociresult($details, "VOUCHER_NUM")."'
						AND (CT.BOL = '".ociresult($details, "IMPORT_CODE")."' OR CT.CARGO_SIZE = '".ociresult($details, "IMPORT_SIZE")."')
					GROUP BY
						  NVL(WAREHOUSE_LOCATION, 'UNKNOWN'),
						  CT.ARRIVAL_NUM,
						  SUBSTR(VESSEL_NAME, 0, 22),
						  SUBSTR(REMARK, INSTR(REMARK, '-')+1),
						  QTY_IN_HOUSE
					ORDER BY
						  CT.ARRIVAL_NUM,
						  SUBSTR(REMARK, INSTR(REMARK, '-')+1)";
			$locations = ociparse($rfconn, $sql);
			ociexecute($locations);
			while (ocifetch($locations)) {
				
				// if (ociresult($locations, 'PLTS_SCANNED') == '0') {
					// continue;
				// }
				
				if($need_location_newline){
					$location_list .= "\n";
					$vsl_list .= "\n";
					$label_list .= "\n";
				}
				
				if (ociresult($locations, 'PLTS_SCANNED') == -1) {
					$need_location_newline = false;
					continue;
				}
				elseif (ociresult($locations, "PLTS_SCANNED") == -2) {
					$location_list .= ociresult($locations, "THE_COUNT")." AVAILABLE @ ".ociresult($locations, "THE_HOUSE");
					$need_location_newline = true;
				} else {
					$location_list .= ociresult($locations, 'PLTS_SCANNED') . ' already scanned @ ' . ociresult($locations, "THE_HOUSE");
					$need_location_newline = true;
				}
				$vsl_list .= ociresult($locations, "ARRIVAL_NUM")." - ".ociresult($locations, "THE_VES");
				$label_list .= ociresult($locations, "THE_LABEL");
				
			}

			array_push($detail_array, array('cases'=>ociresult($details, "CARTONS"),
											'voucher'=>ociresult($details, "VOUCHER_NUM"),
											'variety'=>ociresult($details, "VARIETY"),
											'code'=>ociresult($details, "IMPORT_CODE"),
											'size'=>ociresult($details, "IMPORT_SIZE"),
											'loc'=>$location_list,
											'LR'=>$vsl_list,
											'label'=>$label_list));
			
			//Get the individual pallets and prepare a table array
			$sql = "SELECT distinct
						CT.QTY_RECEIVED AS QTY,
						CT.VARIETY,
						CT.CARGO_SIZE,
						CT.REMARK AS GRADE_LABEL,
						CT.BATCH_ID AS VOUCHER_NUM,
						nvl(CT.WAREHOUSE_LOCATION, 'UNKNOWN') AS LOCATION,
						CT.PALLET_ID
					FROM
						CARGO_TRACKING CT
					WHERE
						RECEIVER_ID = '1626'
						AND CT.QTY_IN_HOUSE > 0
						AND CT.DATE_RECEIVED IS NOT NULL
						AND CT.VARIETY = '".ociresult($details, "VARIETY")."'
						AND CT.BATCH_ID = '".ociresult($details, "VOUCHER_NUM")."'
						AND (CT.BOL = '".ociresult($details, "IMPORT_CODE")."' OR CT.CARGO_SIZE = '".ociresult($details, "IMPORT_SIZE")."')
					ORDER BY
						LOCATION,
						CT.PALLET_ID";
			$pallets = ociparse($rfconn, $sql);
			ociexecute($pallets);
			while(ocifetch($pallets)) {
				//Insert a blank line
				if ($last_location != '' && ociresult($pallets, "LOCATION") != $last_location) {
					array_push($pallet_list_array, '');
				}
				
				array_push($pallet_list_array, array('qty'=>ociresult($pallets, "QTY"),
													 'variety'=>ociresult($pallets, "VARIETY"),
													 'size'=>ociresult($pallets, "CARGO_SIZE"),
													 'grade'=>ociresult($pallets, "GRADE_LABEL"),
													 'voucher_num'=>ociresult($pallets, "VOUCHER_NUM"),
													 'label'=>ociresult($pallets, "LABEL"),
													 'location'=>ociresult($pallets, "LOCATION"),
													 'pallet_id'=>ociresult($pallets, "PALLET_ID")));
				
				$last_location = ociresult($pallets, "LOCATION");
			}
			

		}


		$pdf->ezTable($detail_array, array('cases'=>"CARTONS",
											'voucher'=>"VOUCHER",
											'variety'=>"VARIETY",
											'code'=>"IMPORT CODE",
											'size'=>"IMPORT SIZE",
											'loc'=>"POW LOCATION",
											'LR'=>"VESSEL",
											'label'=>"LABEL"),
									"ORDER DETAILS BELOW",
									array('cols'=>array('cases'=>array('justification'=>'left', 'width'=>50),
														'voucher'=>array('justification'=>'left'),
														'variety'=>array('justification'=>'left'),
														'code'=>array('justification'=>'left'),
														'size'=>array('justification'=>'left'),
														'loc'=>array('justification'=>'left'),
														'LR'=>array('justification'=>'left'),
														'label'=>array('justification'=>'left')),
											'shaded'=>0, 
											'showLines'=>2,
											'fontSize'=>8,
											'colGap'=>3,
											'width'=>575));


		$pdf->ezSetDy(-15);
		$total_table = array();
		array_push($total_table, array('casesum'=>$case_sum,
							'text'=>"TOTAL ORDER"));

		$pdf->ezTable($total_table,
					'',
					'',
					array('cols'=>array('casesum'=>array('justification'=>'left', 'width'=>50),
														'text'=>array('justification'=>'center')),
									'shaded'=>0, 
									'showLines'=>2,
									'showHeadings'=>0,
									'colGap'=>3,
									'width'=>575));

		$pdf->ezSetDy(-20);
		$pdf->ezText("<b>NO SLC - SHIPPER LOAD AND COUNT, DRIVER MUST COUNT LOAD</b>", 12, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("DRIVER MUST CHOCK WHEELS PRIOR TO LOADING ________(DRIVER INITIALS) & RETURN CHOCK TO HANGER UPON COMPLETION\n", 6, $center);
		$pdf->ezText("DRIVER TRAILER WAS PRE-COOLED TO 34 _____ DEGREES F ______________________ (Driver Initials)\n", 8, $center);
		$pdf->ezText("CLEANLINESS:   ___GOOD   ___FAIR   ___POOR           ODOR:   ___YES   ___NO\n", 8, $center);
		$pdf->ezText("ACCEPTED FOR LOADING:   ___YES PROCEED   ___NO - CONTACT SUPERVISOR\n\n", 8, $center);
		$pdf->ezText("CHECKER__________________________SIGN    DRIVER_________________________________SIGN", 8, $center);

		if ($include_details == 'yes') {
			$pdf->ezNewPage();
			$pdf->ezTable($pallet_list_array, array('qty'=>"Qty",
											   'variety'=>"Variety",
											   'size'=>"Size",
											   'grade'=>"Grade - Label",
											   'voucher_num'=>"Voucher #",
											   'location'=>"Location",
											   'pallet_id'=>"Pallet ID"),
										"DETAILS OF AVAILABLE CARGO",
										array('cols'=>array('qty'=>array('justification'=>'left', 'width'=>50),
															'variety'=>array('justification'=>'left'),
															'size'=>array('justification'=>'left'),
															'grade'=>array('justification'=>'left'),
															'voucher_num'=>array('justification'=>'left'),
															'location'=>array('justification'=>'left'),
															'pallet_id'=>array('justification'=>'left')),
												'shaded'=>0, 
												'showLines'=>2,
												'fontSize'=>8,
												'colGap'=>3,
												'width'=>575));
		}






		include("redirect_pdf.php");
	}
