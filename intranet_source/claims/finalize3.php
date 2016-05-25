<?
  include("pow_session.php");

  include("connect.php"); 
  include("billing_functions.php");

  // Finish off a claim
  $claim_id = $HTTP_POST_VARS['claim_id'];
  $complete = $HTTP_POST_VARS['complete'];
  $letter = $HTTP_POST_VARS['letter'];
  $check_num = $HTTP_POST_VARS['check_num'];
  $check_date = $HTTP_POST_VARS['check_date'];
  $letter_date = $HTTP_POST_VARS['letter_date'];
  $signature = $HTTP_POST_VARS['signature'];
  $letter_date = date('m/d/Y', strtotime($letter_date));
  $claim_info = array();
  $claim_info2 = array();
  $general_info = array();
  $comments_array = array();

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if (!$conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
	}
	$cursor = ora_open($conn);
	$ex_postgres_cursor = ora_open($conn);   

  if($signature == "frank"){
    $name = "Frank Vignuli";
    $title = "Director of Operations";
  }
  if($signature == "gene"){
    $name = "Gene Bailey";
    $title = "Executive Director";
  }	
  if($signature == "marty"){
    $name = "Marty McLaughlin";
    $title = "Manager, Quality Assurance";
  }
  if($signature == "victor"){
    $name = "Victor F. Farkas";
    $title = "Controller";
  }


  // open a connection to the database server
/*  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn) {
     die("Could not open connection to database server");
  }*/

  if($complete == "on"){
    // Mark this information as completed!
    if($check_num == ""){
        $check_num = "0";
    //  printf("Please put in a check number!\n");
    //  exit;
    }
    $sql = "update claim_log_oracle 
			set completed = 't', 
				check_num = '$check_num', 
				check_date = TO_DATE('$check_date', 'MM/DD/YYYY'),
				letter_date = TO_DATE('$letter_date', 'MM/DD/YYYY')
			where claim_id = '$claim_id'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  }

  if($letter != "on"){
    header("Location: finalize.php?input=$claim_id");
    exit;
  }
  
    // Initialize PDF writer
    include 'class.ezpdf.php';
    $pdf = new Cezpdf('letter','portriat');
    $pdf -> ezSetMargins(20,70,30,30);
    $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
    $pdf->openHere('XYZ', 0, 800, 1.25);
/*    
    $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
    if (!$bni_conn) {
      printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
      printf("Please report to TS!");
      exit;
    }
    $cursor = ora_open($bni_conn);
    */
    // generate and execute a query
    $sql = "select * from claim_log_oracle where claim_id = '$claim_id' order by line_id";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	if(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
    
    // get the number of rows in the resultset
//    $rows = pg_num_rows($result);
    
//    if ($rows > 0) {
//      $row = pg_fetch_array($result, 0, PGSQL_ASSOC);

      $ship_type = $row['VESSEL_TYPE'];
      $cust = $row['CUSTOMER_ID'];
      $system = $row['SYSTEM'];
      $letter_type = $row['LETTER_TYPE'];
      if($system == "CCDS"){
	$col_name = "Lot ID";
	$col_cont = $row['CCD_LOT_ID'];
	$mark = $row['CCD_MARK'];
        $vessel_bl = $row['VESSEL_BL'];
	$is_ccds = true;
      } else if ($system == "BNI"){
	$col_name = "BOL";
	$col_cont = $row['BNI_BL'];
	$mark = $row['BNI_MARK'];
	$is_ccds = false;
      } else {
	$col_name = "Pallets:";
        $col_cont = $rows;
        $mark = $row['BNI_MARK'];
        $vessel_bl = $row['BNI_BL'];
	$is_ccds = false;
      }

      // If this is a Shipper Letter- We need to send page 1 to them
      /*
      if($letter_type == "Shipper" || $letter_type == "RShipper"){
        if($ship_type == "K"){
          $cust = "96420";
          $is_ccds = true;
        }
        if($ship_type == "C"){
          $cust = "90186";
          $is_ccds = true;
        }
      }*/

      $customer_info = getCustomerInfo($bni_conn, $cursor, $cust, $is_ccds);

      $claim_date = date('m/d/y', strtotime($row['CLAIM_DATE']));
      $invoice_num = $row['CUSTOMER_INVOICE_NUM'];
      $vessel_name = $row['VESSEL_NAME'];
      $voyage = $row['VOYAGE'];
      $exporter = $row['EXPORTER'];
      $exporter = strtoupper($exporter);

      // Shared information
      array_push($general_info, array('first'=>"Claim Number: ", 'second'=>$invoice_num, 
                                      'third'=>'Vessel: ', 'fourth'=>$vessel_name));

      array_push($general_info, array('first'=>"Claim Date: ", 'second'=>$claim_date,
                                      'third'=>'Voyage:', 'fourth'=>$voyage));

     if($vessel_bl == ""){
      if($mark != ""){
        array_push($general_info, array('first'=>"Mark:", 'second'=>$mark, 
                                  'third'=>'', 'fourth'=>''));
      }
      else{
        array_push($general_info, array('first'=>"", 'second'=>'', 
                                  'third'=>'', 'fourth'=>''));
      }
     }
     else{
      if($mark != ""){
        array_push($general_info, array('first'=>"Mark:", 'second'=>$mark, 
                                  'third'=>'Vessel BL:', 'fourth'=>$vessel_bl));
      }
      else{
        array_push($general_info, array('first'=>"", 'second'=>'', 
                                  'third'=>'Vessel BL:', 'fourth'=>$vessel_bl));
      }
     }
     if($exporter != ""){
       array_push($general_info, array('first'=>"Exporter:", 'second'=>$exporter, 
                                  'third'=>'', 'fourth'=>''));
     }

      // iterate through resultset
      $lines_count = 0;
      $qty_count = 0;
      $dollar_total = 0;
      $denied_total = 0;
      $shipper_total = 0;
      $add_ref = 0;
      $amount = 0;
      $total_amount = 0;

		do{
 //     for($i=0; $i<$rows; $i++){
//	$row = pg_fetch_array($result, $i, PGSQL_ASSOC);
        $letter_type = $row['LETTER_TYPE'];

	// Gather information
        if($is_ccds){
          $commodity_name = $row['PRODUCT_NAME'] . " " . $row['CCD_CUT'];
        }
        else{
          $commodity_name = $row['PRODUCT_NAME'];
        }
	$weight = $row['WEIGHT'];
	$cost = $row['COST'];

        if($letter_type == "Shipper" || $letter_type == "RShipper"){ 
          // Letter to the shipper to cover the total amount
	  $amount = $row['AMOUNT'] + $row['SHIPPER_AMOUNT'];
	  $total_amount = $row['AMOUNT'];
          $shipper_qty = $row['SHIPPER_AMOUNT'];
          $qty_claimed = $row['SHIPPER_QTY'] + $row['QUANTITY_CLAIMED'] + $row['DENIED_QTY'];
        }
        else if($letter_type == "Split"){
          // Letter to customer AND agent
	  $qty_claimed = $row['QUANTITY_CLAIMED'] + $row['SHIPPER_QTY'];
  	  //$shipper_qty = $row['shipper_amount'];
  	  $shipper_qty = 0;
          // We are printing the customers copy
          $amount = $row['AMOUNT'] + $row['SHIPPER_AMOUNT'];
          $total_amount = $amount;
        }
        else{
          // Normal letter to the customer
	  $qty_claimed = $row['QUANTITY_CLAIMED'] + $row['DENIED_QTY'];
  	  $shipper_qty = $row['SHIPPER_AMOUNT'];
          // 8-08-04 - Per Astrid: Subtract the denied dollar total
          if($row['DENIED_QTY'] == 0 && $row['DENIED_QUANTITY'] > 0){
            $amount = $row['AMOUNT'];
            $total_amount = $row['AMOUNT'] - $row['DENIED_QUANTITY'];
          }
          else{
            $amount = $row['AMOUNT'] + $row['DENIED_QUANTITY'];
            $total_amount = $row['AMOUNT'];
          }
        }
	$qty_denied = $row['DENIED_QUANTITY'];
        $comments = $row['NOTES'];

        $lines_count++;
        $qty_count += $qty_claimed;
        $dollar_total += $amount;
        $denied_total += $qty_denied;
        $shipper_total += $shipper_qty;
        $overall_total += $total_amount;
 
        if($system == "RF"){
          $key = $row['RF_PALLET_ID'];
        }
        if($system == "CCDS"){
          $key = $row['CCD_LOT_ID'];
        }
        if($system == "BNI"){
          $key = $row['BNI_BL'];
        }

        // Format
        $cost = number_format($cost, 4, '.', '');
        $weight = number_format($weight, 2, '.', '');
        $amount = number_format($amount, 2, '.', '');
        $qty_denied = number_format($qty_denied, 2, '.', '');
        $total_amount = number_format($total_amount, 2, '.', '');
        $shipper_qty = number_format($shipper_qty, 2, '.', '');

        // Add to the reference table
        if($comments != ""){
          if($key == "LABOR INVOICE"){
            $ref = "INV";
          }
          else{
            $ref = substr($key, -4);
          }
          $ref_string = "Ref: $ref";
          array_push($comments_array, array('ref'=>$ref_string, 'comments'=>$comments));
          $add_ref = 1;
        }
        else{
          $ref_string = "";
        }

        $unit = $row['UNIT'];
        if($qty_claimed > 1){
          $unit .= "s";
        }

        array_push($claim_info, array('key'=>$key, 'comm'=>$commodity_name, 'qty'=>$qty_claimed, 'weight'=>$weight, 
                                      'cost'=>"$" . $cost, 'amount'=>"$" . $amount, 'system'=>"$" . $total_amount, 
                                      'denied_qty'=>$row['denied_qty'], 'denied'=>"$" . $qty_denied, 'ship_line'=>"$" . $shipper_qty, 'comments'=>$ref_string, 'unit'=>$unit, 'gallons'=>$row['GALLONS']));

      } while(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
    
    $dollar_total = number_format($dollar_total, 2, '.', '');
    $overall_total = number_format($overall_total, 2, '.', '');
    $denied_total = number_format($denied_total, 2, '.', '');
    $shipper_total = number_format($shipper_total, 2, '.', '');
    // add the total line
    array_push($claim_info, array('comm'=>'Total:', 'qty'=>$qty_count, 'weight'=>'', 
      'cost'=>'', 'amount'=>"$$dollar_total", 'system'=>"$$overall_total", 
      'denied'=>"$" . $denied_total, 'ship_line'=>"$" . $shipper_total, 'comments'=>''));
    
    // write customer information
    $pdf->ezSetDy(-100);
    $pdf->ezText($letter_date, 10, $left);
    $pdf->ezSetDy(-30);
    $pdf->ezText($customer_info, 10, $left);
    $pdf->ezSetDy(-20);
    
    // write general information
    $pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'right', 'width'=>150), 
					  'second'=>array('justification'=>'left', 'width'=>100),
					  'third'=>array('justification'=>'right', 'width'=>150), 
					  'fourth'=>array('justification'=>'left', 'width'=>150)),
			    'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>550));
    
    $pdf->ezSetDy(-20);
    if($system == "CCDS"){
      $pdf->ezTable($claim_info, array('key'=>'Tracking #', 'comm'=>'Commodity', 'qty'=>'QTY', 'weight'=>'Weight', 'cost'=>'Price/LB', 
				     'amount'=>'Total', 'system'=>'CCDS', 'denied'=>'Denied', 
				     'ship_line'=>'Ship Line', 'comments'=>'Ref.'), 
		  '', array('cols'=>array('comm'=>array('justification'=>'center'), 
                                          'key'=>array('justification'=>'center'),
					  'qty'=>array('justification'=>'center'),
					  'weight'=>array('justification'=>'right'), 
					  'cost'=>array('justification'=>'right'),
					  'amount'=>array('justification'=>'right'),
					  'system'=>array('justification'=>'right'), 
					  'denied'=>array('justification'=>'right'),
					  'ship_line'=>array('justification'=>'right'), 
					  'comments'=>array('justification'=>'left')),
			    'shaded'=>0, 'showHeadings'=>1, 'showLines'=>2, 'width'=>550));
    }
    if($system == "RF"){
      $pdf->ezTable($claim_info, array('key'=>'Pallet ID', 'comm'=>'Commodity', 'qty'=>'QTY', 'cost'=>'Price/QTY', 
				     'amount'=>'Total', 'system'=>'POW', 'denied'=>'Denied', 
				     'ship_line'=>'Ship Line', 'comments'=>'Ref'), 
		  '', array('cols'=>array('comm'=>array('justification'=>'center'), 
                                          'key'=>array('justification'=>'center'),
					  'qty'=>array('justification'=>'center'),
					  'weight'=>array('justification'=>'right'), 
					  'cost'=>array('justification'=>'right'),
					  'amount'=>array('justification'=>'right'),
					  'system'=>array('justification'=>'right'), 
					  'denied'=>array('justification'=>'right'),
					  'ship_line'=>array('justification'=>'right'), 
					  'comments'=>array('justification'=>'left')),
			    'shaded'=>0, 'showHeadings'=>1, 'showLines'=>2, 'width'=>550));
    }
    if($system == "BNI"){
      $pdf->ezTable($claim_info, array('key'=>'WO #', 'comm'=>'Commodity', 'qty'=>'QTY', 'unit'=>'Unit', 'gallons'=>'Gallons', 'cost'=>'Price/QTY', 
				     'amount'=>'Total', 'system'=>'POW', 'denied'=>'Denied', 
				     'ship_line'=>'Ship Line', 'comments'=>'Ref'), 
		  '', array('cols'=>array('comm'=>array('justification'=>'center'), 
                                          'key'=>array('justification'=>'center'),
					  'qty'=>array('justification'=>'center'),
					  'weight'=>array('justification'=>'right'), 
					  'cost'=>array('justification'=>'right'),
					  'amount'=>array('justification'=>'right'),
					  'system'=>array('justification'=>'right'), 
					  'denied'=>array('justification'=>'right'),
					  'ship_line'=>array('justification'=>'right'), 
					  'comments'=>array('justification'=>'left')),
			    'shaded'=>0, 'showHeadings'=>1, 'showLines'=>2, 'width'=>550));
    }


    $pdf->ezSetDy(-20);

    // Add that comments table!
    if($add_ref == 1){
      $pdf->ezTable($comments_array, array('ref'=>'Ref', 'comments'=>'Comments'), '', 
       array('cols'=>array('ref'=>array('justification'=>'left', 'width'=>60), 'comments'=>array('justification'=>'left')), 
       'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>550));
    }

    // write closing information
    if($lines_count< 15){
      //$pdf->ezNewPage();
      $pdf->ezSetDy(-100);
    }
    else{
      $pdf->ezSetDy(-20);
    }
      $pdf->ezText("Sincerely,", 10, $left);
      $pdf->ezSetDy(-30);
      $pdf->ezText("$name\n$title", 10, $left);
    //$pdf->ezSetDy(-50);
    
/*****************************************************************************/

    // Ok, that was page 1- lets see if this is a split!
    if($letter_type == "Split"){
      $pdf->ezNewPage();
      // generate and execute a query
      $sql = "select * from claim_log_oracle where claim_id = '$claim_id'";
//      $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
    
      // get the number of rows in the resultset
//      $rows = pg_num_rows($result);
    
//      $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

      $cust = $row['CUSTOMER_ID'];
      $system = $row['SYSTEM'];
      if($system == "CCDS"){
	$col_name = "Lot ID";
	$col_cont = $row['CCD_LOT_ID'];
	$mark = $row['CCD_MARK'];
	$is_ccds = true;
      } else if ($system == "BNI"){
  	$col_name = "BOL";
  	$col_cont = $row['BNI_BL'];
	$mark = $row['BNI_MARK'];
	$is_ccds = false;
      } else {
	$col_name = "Pallets:";
        $col_cont = $rows;
        $mark = "";
	$is_ccds = false;
      }

      // Ok, we have already printed the customers copy - now for the agent
      if($ship_type == "K"){
        $cust = "96420";
        $is_ccds = true;
      }
      if($ship_type == "C"){
        $cust = "90186";
        $is_ccds = true;
      }

      // Get the agents address
      $customer_info = getCustomerInfo($bni_conn, $cursor, $cust, $is_ccds);

      $claim_date = date('m/d/y', strtotime($row['CLAIM_DATE']));
      $invoice_num = $row['customer_invoice_num'];
      $vessel_name = $row['vessel_name'];
      $voyage = $row['voyage'];

      // Shared information
      $general_info = array();
      array_push($general_info, array('first'=>"Invoice Number: ", 'second'=>$invoice_num, 
                                      'third'=>'Vessel: ', 'fourth'=>$vessel_name. " " . $voyage));

      array_push($general_info, array('first'=>"Invoice Date: ", 'second'=>$claim_date,
                                      'third'=>$col_name . ': ', 'fourth'=>$col_cont));

      if($vessel_bl == ""){
        array_push($general_info, array('first'=>"Mark: ", 'second'=>$mark, 
                                      'third'=>'', 'fourth'=>''));
      }
      else{
        array_push($general_info, array('first'=>"Mark: ", 'second'=>$mark, 
                                      'third'=>'Vessel BL:', 'fourth'=>$vessel_bl));
      }

      // iterate through resultset
      $lines_count = 0;
      $qty_count = 0;
      $dollar_total = 0;
      $denied_total = 0;
      $shipper_total = 0;

		do {
//      for($i=0; $i<$rows; $i++){
//	$row = pg_fetch_array($result, $i, PGSQL_ASSOC);

	// Gather information
        $system = $row['SYSTEM'];
        if($is_ccds){
          $commodity_name = $row['PRODUCT_NAME'] . " " . $row['CCD_CUT'];
        }
        else{
          $commodity_name = $row['PRODUCT_NAME'];
        }
	$weight = $row['WEIGHT'];
	$cost = $row['COST'];

        if($letter_type == "Shipper" || $letter_type == "RShipper"){ 
          // Letter to the shipper to cover the total amount
	  $amount = $row['AMOUNT'] + $row['SHIPPER_AMOUNT'];
  	  $shipper_qty = 0;
          $qty_claimed = $row['SHIPPER_QTY'] + $row['QUANTITY_CLAIMED'];
        }
        else if($letter_type == "Split"){
          // Letter to customer AND agent
	  $qty_claimed = $row['SHIPPER_QTY'];
  	  $shipper_qty = $row['SHIPPER_QTY'];
          // We are printing the shippers copy
          $amount = $row['SHIPPER_AMOUNT'];
          $weight = $weight / ($row['SHIPPER_QTY'] + $row['QUANTITY_CLAIMED']);
          $weight = round($weight * $row['SHIPPER_QTY'], 2);
        }
        else{
          // Normal letter to the customer
	  $qty_claimed = $row['QUANTITY_CLAIMED'];
  	  $shipper_qty = $row['SHIPPER_QTY'];
          $amount = $row['AMOUNT'];
        }
	$qty_denied = $row['DENIED_QUANTITY'];
        $comments = $row['NOTES'];

        $lines_count++;
        $qty_count += $qty_claimed;
        $dollar_total += $amount;
        $denied_total += $qty_denied;
        $shipper_total += $shipper_qty;

        array_push($claim_info2, array('comm'=>$commodity_name, 'qty'=>$qty_claimed, 'weight'=>$weight, 
                                      'cost'=>"$" . $cost, 'amount'=>"$" . $amount, 'system'=>"$" . $amount, 
                                      'denied'=>$qty_denied, 'ship_line'=>$shipper_qty, 'comments'=>$comments));

      } while(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
    
    // add the total line
    array_push($claim_info2, array('comm'=>"Total:", 'qty'=>$qty_count, 'weight'=>'', 
                                 'cost'=>'', 'amount'=>"$" . $dollar_total, 'system'=>"$" . $dollar_total, 
				  'denied'=>$denied_total, 'ship_line'=>$shipper_total, 'comments'=>''));
    
    // write customer information
    $pdf->ezSetDy(-100);
    $pdf->ezText($today, 10, $left);
    $pdf->ezSetDy(-30);
    $pdf->ezText($customer_info, 10, $left);
    $pdf->ezSetDy(-30);
    
    // write general information
    $pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'right', 'width'=>150), 
					  'second'=>array('justification'=>'left', 'width'=>100),
					  'third'=>array('justification'=>'right', 'width'=>150), 
					  'fourth'=>array('justification'=>'left', 'width'=>150)),
			    'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>550));
    
    $pdf->ezSetDy(-20);
    $pdf->ezTable($claim_info2, array('comm'=>'Commodity', 'qty'=>'QTY', 'weight'=>'Weight', 'cost'=>'Price/LB', 
				     'amount'=>'Amount', 'system'=>'Agent', 'denied'=>'Denied', 
				     'ship_line'=>'Ship Line', 'comments'=>'Ref'), 
		  '', array('cols'=>array('comm'=>array('justification'=>'center'), 
					  'qty'=>array('justification'=>'center'),
					  'weight'=>array('justification'=>'center'), 
					  'cost'=>array('justification'=>'left'),
					  'amount'=>array('justification'=>'left'),
					  'system'=>array('justification'=>'center'), 
					  'denied'=>array('justification'=>'left'),
					  'ship_line'=>array('justification'=>'left'), 
					  'comments'=>array('justification'=>'left')),
			    'shaded'=>0, 'showHeadings'=>1, 'showLines'=>2, 'width'=>550));
    
    // write closing information
    $pdf->ezSetDy(-20);
    $pdf->ezText("Sincerely,", 10, $left);
    $pdf->ezSetDy(-30);
    $pdf->ezText("$name\n$title\nDiamond State Port Corporation", 10, $left);
    }
  } // if(split)
/*****************************************************************************/

  // redirect to a temporary PDF file instead of directly writing to the browser
  include("redirect_pdf.php");
  exit;
?>
