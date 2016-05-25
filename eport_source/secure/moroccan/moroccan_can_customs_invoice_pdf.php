<?
	include 'class.ezpdf.php';

	$order = "FFS1007";
	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];


//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	include("db_def.php");

//	$pdf = new Cezpdf('letter','landscape');
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(20,20,55,55);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT CARRIERNAME, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') DELV_DATE, TRAILERNUM, TRUCKTAG, SNMGNUM, CUSTOMERPO, MO.CONSIGNEEID, SEALNUM, VP.VESSEL_NAME,
				CONSIGNEENAME, MCON.ADDRESS CONADD, MCON.CITY, MCON.STATEPROVINCE CONPROV, MCON.PHONE, MB.BORDERCROSSING, MB.CUSTOMSBROKER, MB.CONTACTNAME,
				MB.PHONE, MB.FAX, MCOM.DC_COMMODITY_NAME, MCUS.CUSTOMERNAME, MCUS.ADDRESS CUSADD, MCUS.STATEPROVINCE CUSPROV, MCUS.POSTALCODE, MO.ZUSER2, MO.TRANSPORTCHARGES
			FROM MOR_ORDER MO, MOR_TRANSPORTER MT, MOR_CONSIGNEE MCON, MOR_BROKER MB, MOR_COMMODITY MCOM, MOR_CUSTOMER MCUS, VESSEL_PROFILE VP
			WHERE MO.ORDERNUM = '".$order."'
				AND MO.TRANSPORTERID = MT.TRANSPORTID
				AND MO.CONSIGNEEID = MCON.CONSIGNEEID
				AND MCON.CUSTOMSBROKEROFFICEID = MB.BROKERID
				AND MO.COMMODITYCODE = MCOM.PORT_COMMODITY_CODE
				AND MO.CUSTOMERID = MCUS.CUSTOMERID
				AND MO.VESSELID = VP.LR_NUM
				AND MO.CUST = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist for customer ".$cust."</b>", 14, $center);
	} else {

		$carriername = ociresult($stid, "CARRIERNAME");
		$delivery = ociresult($stid, "DELV_DATE");
		$cust_po = ociresult($stid, "CUSTOMERPO");
		$snmg = ociresult($stid, "SNMGNUM");
		$seal = ociresult($stid, "SEALNUM");
		$trailer = ociresult($stid, "TRAILERNUM");
		$trucknum = ociresult($stid, "TRUCKTAG");
		$consname = ociresult($stid, "CONSIGNEENAME");
		$consaddr = ociresult($stid, "CONADD");
		$conscity = ociresult($stid, "CITY");
		$consstate = ociresult($stid, "CONPROV");
		$consphone = ociresult($stid, "PHONE");
		$bordercross = ociresult($stid, "BORDERCROSSING");
		$broker = ociresult($stid, "CUSTOMSBROKER");
		$brokerphone = ociresult($stid, "PHONE");
		$brokerfax = ociresult($stid, "FAX");
		$brokercontact = ociresult($stid, "CONTACTNAME");
		$cusname = ociresult($stid, "CUSTOMERNAME");
		$cusaddr = ociresult($stid, "CUSADD");
		$cusstate = ociresult($stid, "CUSPROV");
		$cuspost = ociresult($stid, "POSTALCODE");
		$commname = ociresult($stid, "DC_COMMODITY_NAME");
		$vesname = ociresult($stid, "VESSEL_NAME");
		$temprecord = ociresult($stid, "ZUSER2");
		$transcharge = ociresult($stid, "TRANSPORTCHARGES");

		$pdf->ezSetY(765);
		$pdf->ezText("Canada Customs Invoice And Confirmation of Sale", 6, $center);

		$vendor_table = array();
		array_push($vendor_table, array('line'=>"1. Vendor (Name and Address)"));
		array_push($vendor_table, array('line'=>"<b>".$broker."</b>"));
		array_push($vendor_table, array('line'=>"<b>".$brokercontact."</b>"));
		array_push($vendor_table, array('line'=>"<b>".$brokerphone."</b>"));
		array_push($vendor_table, array('line'=>"<b>".$brokerfax."</b>"));

		$pdf->ezSetY(750);
		$pdf->ezTable($vendor_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'rowGap'=>1,
															'colGap'=>2));

		$purchaser_table = array();
		array_push($purchaser_table, array('line'=>"2. Purchaser (Name and Address)"));
		array_push($purchaser_table, array('line'=>$cusname));
		array_push($purchaser_table, array('line'=>$cusaddr));
		array_push($purchaser_table, array('line'=>$cusstate));
		array_push($purchaser_table, array('line'=>$cuspost));

		$pdf->ezSetY(750);
		$pdf->ezTable($purchaser_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$exporter_table = array();
		array_push($exporter_table, array('line'=>"3. Exporter (Name and Address)"));
		array_push($exporter_table, array('line'=>"<b>".$broker."</b>"));
		array_push($exporter_table, array('line'=>"<b>".$brokercontact."</b>"));
		array_push($exporter_table, array('line'=>"<b>".$brokerphone."</b>"));
		array_push($exporter_table, array('line'=>"<b>".$brokerfax."</b>"));

		$pdf->ezSetY(700);
		$pdf->ezTable($exporter_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'rowGap'=>1,
															'colGap'=>2));

		$consign_table = array();
		array_push($consign_table, array('line'=>"4. Consignee (Name and Address)"));
		array_push($consign_table, array('line'=>$consname));
		array_push($consign_table, array('line'=>$consaddr));
		array_push($consign_table, array('line'=>$consstate));
		array_push($consign_table, array('line'=>""));

		$pdf->ezSetY(700);
		$pdf->ezTable($consign_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$carrier_table = array();
		array_push($carrier_table, array('line'=>"5a. Carrier (Name and Address)"));
		array_push($carrier_table, array('line'=>$carriername));
		array_push($carrier_table, array('line'=>""));
		array_push($carrier_table, array('line'=>""));

		$pdf->ezSetY(650);
		$pdf->ezTable($carrier_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'rowGap'=>1,
															'colGap'=>2));

		$Five_b_table = array();
		array_push($Five_b_table, array('line'=>"5b. Place of Direct shipment to Canada"));
		array_push($Five_b_table, array('line'=>"<b>Wilmington</b>"));

		$pdf->ezSetY(650);
		$pdf->ezTable($Five_b_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$Five_c_table = array();
		array_push($Five_c_table, array('line'=>"5c. Conveyance Identification No."));
		array_push($Five_c_table, array('line'=>"<b>".$order."</b>"));

		$pdf->ezSetY(610);
		$pdf->ezTable($Five_c_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'rowGap'=>1,
															'colGap'=>2));

		$six_a_table = array();
		array_push($six_a_table, array('line'=>"6a. Date of Direct Shipment to Canada"));
		array_push($six_a_table, array('line'=>"<b>Unknown</b>"));

		$pdf->ezSetY(630);
		$pdf->ezTable($six_a_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$six_b_table = array();
		array_push($six_b_table, array('line'=>"6b. Date Purchased"));
		array_push($six_b_table, array('line'=>"<b>Unknown</b>"));

		$pdf->ezSetY(610);
		$pdf->ezTable($six_b_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$five_d_table = array();
		array_push($five_d_table, array('line'=>"5d Transportation:  Give Mode (Type, routing, & Travel temperature)"));
		array_push($five_d_table, array('line'=>"<b>".$vesname."</b>"));
		array_push($five_d_table, array('line'=>""));
		array_push($five_d_table, array('line'=>""));

		$pdf->ezSetY(590);
		$pdf->ezTable($five_d_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'rowGap'=>1,
															'colGap'=>2));

		$country_of_xport_table = array();
		array_push($country_of_xport_table, array('line'=>"7. Country of Transport"));
		array_push($country_of_xport_table, array('line'=>"<b>USA</b>"));

		$pdf->ezSetY(590);
		$pdf->ezTable($country_of_xport_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>1,
															'fontSize'=>7,
															'width'=>250,
															'xPos'=>'right',
															'xOrientation'=>'left',
															'rowGap'=>1,
															'colGap'=>2));

		$how_sold = array();
		array_push($how_sold, array('heading'=>"8.  Check How Sold",
									'phone'=>"Telephone",
									'letter'=>"Letter",
									'fax'=>"Facsimile",
									'person'=>"In Person",
									'other'=>"Other"));
		array_push($how_sold, array('heading'=>"",
									'phone'=>"",
									'letter'=>"",
									'fax'=>"",
									'person'=>"",
									'other'=>""));

		$pdf->ezSetY(550);
		$pdf->ezTable($how_sold, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>250,
													'xPos'=>'left',
													'xOrientation'=>'right',
													'rowGap'=>1,
													'colGap'=>2));

		$sale_mode = array();
		array_push($sale_mode, array('line'=>"9. Sale Made            FOB"));
		array_push($sale_mode, array('line'=>"Delivered  X"));

		$pdf->ezSetY(570);
		$pdf->ezTable($sale_mode, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>250,
													'xPos'=>'right',
													'xOrientation'=>'left',
													'rowGap'=>1,
													'colGap'=>2));

		$currency_table = array();
		array_push($currency_table, array('line'=>"10. Currency of Settlement"));
		array_push($currency_table, array('line'=>"<b>CAD</b>"));

		$pdf->ezSetY(550);
		$pdf->ezTable($currency_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>250,
													'xPos'=>'right',
													'xOrientation'=>'left',
													'rowGap'=>1,
													'colGap'=>2));

		$activity_table = array();
		array_push($activity_table, array('eleven'=>"11. Harmonized System\r\nTarriff Item",
											'twelve'=>"12. Specifications of Commodities (general descriotion and characteristics\r\nsuch as grade, quality, kind of packages, marks, and numbers)",
											'thirteen'=>"13. Country of origin\r\nby commodity",
											'fourteen'=>"14. Net Weight / pkg\r\nkgs or lbs",
											'fifteen'=>"15. No. of Pkgs\r\nby commodity",
											'sixteen'=>"16. Price per pkg",
											'seventeen'=>"17. Total $ per commodity"));

		$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_CHANGE) THE_CTN, SUM(WEIGHT * QTY_CHANGE) THE_WEIGHT, DC_COMMODITY_NAME, MCS.DESCR, HARMONIZEDTARIFF,
					CT.WEIGHT, MOD.PRICE
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, MOR_COMMODITY MCOM, MOR_COMMODITYSIZE MCS, MOR_ORDER MO, MOR_ORDERDETAIL MOD
				WHERE ORDER_NUM = '".$order."'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = MCOM.PORT_COMMODITY_CODE
					AND CA.ORDER_NUM = MO.ORDERNUM
					AND MO.ORDERNUM = MOD.ORDERNUM
					AND TO_NUMBER(CT.CARGO_SIZE) >= MCS.SIZELOW
					AND TO_NUMBER(CT.CARGO_SIZE) <= MCS.SIZEHIGH
					AND MOD.ORDERSIZEID = MCS.SIZEID
				GROUP BY DC_COMMODITY_NAME, MCS.DESCR, HARMONIZEDTARIFF, CT.WEIGHT, MOD.PRICE";
		$c_a = ociparse($conn, $sql);
		ociexecute($c_a);
		while(ocifetch($c_a)){
			array_push($activity_table, array('eleven'=>ociresult($c_a, "HARMONIZEDTARIFF"),
												'twelve'=>ociresult($c_a, "DESCR"),
												'thirteen'=>"Morocco",
												'fourteen'=>ociresult($c_a, "WEIGHT"),
												'fifteen'=>ociresult($c_a, "THE_CTN"),
												'sixteen'=>ociresult($c_a, "PRICE"),
												'seventeen'=>ociresult($c_a, "PRICE") * ociresult($c_a, "THE_CTN")));
		}



		$pdf->ezSetY(500);
		$pdf->ezTable($activity_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>7,
													'width'=>500,
													'rowGap'=>1,
													'colGap'=>2));




		$PO_table = array();
		array_push($PO_table, array('line'=>"18. Purchase Order No."));
		array_push($PO_table, array('line'=>"<b>".$cust_po."</b>"));

		$pdf->ezSetY(340);
		$pdf->ezTable($PO_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>250,
													'xPos'=>'left',
													'xOrientation'=>'right',
													'rowGap'=>1,
													'colGap'=>2));

		$ordernum_table = array();
		array_push($ordernum_table, array('line'=>"19. Commercial Invoice No."));
		array_push($ordernum_table, array('line'=>"<b>".$order."</b>"));

		$pdf->ezSetY(340);
		$pdf->ezTable($ordernum_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>250,
													'xPos'=>'right',
													'xOrientation'=>'left',
													'rowGap'=>1,
													'colGap'=>2));

		$temp_table = array();
		array_push($temp_table, array('line'=>"22. Special Agreements and related expenses (e.g. transport, cooling palletization, inspection, brokerage, temperature recorder, tectrol, etc.)"));
		array_push($temp_table, array('line'=>"TEMP RECORDER#:  ".$temprecord."                 $26.00"));

		$pdf->ezSetY(320);
		$pdf->ezTable($temp_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>502,
													'rowGap'=>1,
													'colGap'=>2));

		$table_23_thru_25 = array();
		array_push($table_23_thru_25, array('line'=>"23. Date of Delivery if Delivered Sale:  ".$delivery,
											'amt'=>"Indicate Amount",
											'in21'=>"If included in Field 21",
											'not21'=>"Not included in Field 21"));
		array_push($table_23_thru_25, array('line'=>"24. Transportation Charges, expenses, and insurance from the place of direct shipment to Canada:",
											'amt'=>"3500",
											'in21'=>"x",
											'not21'=>""));
		array_push($table_23_thru_25, array('line'=>"25. Transportation Charges, expenses to the place of direct shipment to Canada:",
											'amt'=>$transcharge,
											'in21'=>"x",
											'not21'=>""));

		$pdf->ezSetY(300);
		$pdf->ezTable($table_23_thru_25, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>7,
													'width'=>502,
													'rowGap'=>1,
													'colGap'=>2));

		$purchaser_table = array();
		array_push($purchaser_table, array('line'=>"26. Purchaser or Agent",
											'twenty7'=>"27. Vendor or Agent"));
		array_push($purchaser_table, array('line'=>"Signature & Date   _____________________________________",
											'twenty7'=>"Signature & Date   _____________________________________"));
		array_push($purchaser_table, array('line'=>"Representing:       ".$cusname,
											'twenty7'=>"Representing:       Fresh Fruit Maroc SA"));

		$pdf->ezSetY(240);
		$pdf->ezTable($purchaser_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>7,
													'width'=>502,
													'rowGap'=>1,
													'colGap'=>2));

		$bottom_table = array();
		array_push($bottom_table, array('line'=>"The signer hereby certifies that signer is authorized by the purchaser or the vendor named above to sign and authenticate the same on the purchaser's or vendor's behald.  It is"));
		array_push($bottom_table, array('line'=>"understood, unless otherwise stated herein, that this sale is made in contemplation of and subject to, and that all items described hereby are found at shipping point to be in"));
		array_push($bottom_table, array('line'=>"conformity with the Canada Agricultural Products Act, the Canadian Food & Drugs Act, the Plant Quarantine Act and their Prospective regulations"));

		$pdf->ezSetY(180);
		$pdf->ezTable($bottom_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>1,
													'fontSize'=>6,
													'width'=>502,
													'rowGap'=>1,
													'colGap'=>2));

	}

	include("redirect_pdf.php");
