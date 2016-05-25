<?
   // File: track_report_print.php
   //
   // This page generate a PDF file of the tracker report

   // form processor
   include("pow_session.php");

   $lr_num = $HTTP_POST_VARS["lr_num"];
   $lot_id = $HTTP_POST_VARS["lot_id"];
   $lot_free = $HTTP_POST_VARS["lot_free"];
   $mark_free = $HTTP_POST_VARS["mark_free"];
   $ccd_lot_id = $HTTP_POST_VARS["ccd_lot_id"];
   $ccd_lot_free = $HTTP_POST_VARS["ccd_lot_free"];

   if ($lr_num == "") {
      $lr_num = $HTTP_COOKIE_VARS["lrnum"];
   }

   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

   $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if (!$conn) {
      die("Could not open connection to database server");
   }

   // Get ccd_lot_id and not set the cookies -- different from set_lot.php
   if ($ccd_lot_id == "" && $ccd_lot_free == "Tracking Number") {	// user select lot from manifest

      if($lot_id == ""){
         $lot_id = trim($lot_free);
         $mark = trim($mark_free);
      }else{
         list($slot_id, $mark) = split("[, ]", $lot_id, 2);
         $lot_id = trim($slot_id);
         $mark = trim($mark);
      }

      if ($lot_id == "" || $lot_id == "Lot") {
         $stmt = "select ccd_lot_id from ccd_received where lr_num = '$lr_num' and mark = '$mark'";
      } else {
         $stmt = "select ccd_lot_id from ccd_received where lr_num = '$lr_num' and lot_id = '$lot_id'
	          and mark = '$mark'";
      }

      $result = pg_query($conn, $stmt) or 
                die("Error in query: $stmt. " .  pg_last_error($conn));
      $rows = pg_num_rows($result);

      if ($rows <= 0) {	// not ccd_lot_id is found
         die("No CCD Lot ID is found for lr_num: '$lr_num', lot_id: '$lot_id', and mark: '$mark'. 
	      Please check if you entered the correct Lot, Mark.");
      } else {
         $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
         $ccd_lot_id = $row['ccd_lot_id'];
      }

   }else{				// user select lot using ccd_lot_id

      if ($ccd_lot_id != "") {
	 $stmt = "select lr_num from ccd_received where ccd_lot_id = '$ccd_lot_id'";
	 $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
         $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	 $lr_num =  $row['lr_num'];
      } else {
	 $stmt = "select lr_num from ccd_received where ccd_lot_id = '$ccd_lot_free'";
	 $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
	 $rows = pg_num_rows($result);

         if ($rows <= 0) {	// not ccd_lot_id is found
            die("Invalid Tracking Number! Please go back to previous page and re-enter it.");
         } else {
            $ccd_lot_id = $ccd_lot_free;
            $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	    $lr_num =  $row['lr_num'];
         }
      }
   }      

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

   // Write out the intro.
   include("tally_header.php");

   $pdf->ezSetDy(-15);
   $pdf->ezText("<b>Tracking #:  $ccd_lot_id</b>", 12, $center);

   $pdf->ezSetDy(-30);
   $pdf->ezText("Lot Information:", 11, $left);

   // get lot information from ccds.ccd_received
   $stmt = "select C.customer_name, R.*
            from ccd_received R, ccd_customer C
            where C.customer_id = R.customer_id and ccd_lot_id = '$ccd_lot_id'";

   $result = pg_query($conn, $stmt) or 
             die("Error in query: $stmt. " .  pg_last_error($conn));
   $row = pg_fetch_array($result, 0, PGSQL_ASSOC);

   // format the dates
   if ($row['production_date'] != "") {
      $production_date = date('m/d/y', strtotime($row['production_date']));
   } else {
      $production_date = "";
   }

   if ($row['receiving_date'] != "") {
      $receiving_date = date('m/d/y', strtotime($row['receiving_date']));
   } else {
      $receiving_date = "";
   }

   if ($row['inspection_date'] != "") {
      $inspection_date = date('m/d/y', strtotime($row['inspection_date']));
   } else {
      $inspection_date = "";
   }

   if ($row['free_time_end'] != "") {
      $free_time_end = date('m/d/y', strtotime($row['free_time_end']));
   } else {
      $free_time_end = "";
   }

   if ($row['release_date'] != "") {
      $release_date = date('m/d/y', strtotime($row['release_date']));
   } else {
      $release_date = "";
   }

   $print_data = array();	// store data to be printed

   array_push($print_data, 
	      array('first'=>"Importer:\nLot ID:\nProduct:\nPallets Rcvd:\nGross Weight:\nBrand:\n" . 
			     "Establishment:\nInspection Date:", 
		    'second'=>$row['customer_name'] . "\n" . $row['lot_id'] . "\n" . $row['product'] . "\n" . 
                              strtr($row['pallets_received'], $trans) . "\n" . 
		              strtr($row['gross_weight'], $trans) . "\n" . $row['brand'] . "\n" . 
		              $row['establishment'] . "\n" . $inspection_date,
		    'third'=>'',
		    'fourth'=>"Vessel:\nMark:\nContainer:\nCases Expected:\nNet Weight:\nCut:\n" . 
		              "Production Date:\nRelease Date:\n", 
		    'fifth'=>$row['vessel'] . "\n" . $row['mark'] . "\n" . $row['container'] . "\n" . 
                              strtr($row['cases_expected'], $trans) . "\n" . strtr($row['net_weight'], $trans) . 
		              "\n" . $row['cut'] . "\n" . $production_date . "\n" . $release_date,
		    'sixth'=>'',
		    'seventh'=>"Voyage:\nPO #:\nOrigin:\nCases Rcvd:\nLocation:\nUSDA:\nReceiving Date:\n" . 
		               "Billing Storage Date:", 
		    'eighth'=>$row['voyage'] . "\n" . $row['po_num'] . "\n" . $row['origin'] . "\n" .
                              strtr($row['cases_received'], $trans) . "\n" . $row['location'] . "\n" . 
			      $row['usda'] . "\n" . $receiving_date . "\n" . $free_time_end,
              ));


   $pdf->ezTable($print_data,  array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 
				     'fifth'=>'', 'sixth'=>'', 'seventh'=>'', 'eighth'=>''), '', 
			       array('cols'=>array('first'=>array('justification'=>'left'), 
					           'second'=>array('justification'=>'left'),
					           'fourth'=>array('justification'=>'left'),
					           'fifth'=>array('justification'=>'left'),
					           'seventh'=>array('justification'=>'left'),
					           'eighth'=>array('justification'=>'left')),
				     'shaded'=>0, 'showLines'=>0, 'width'=>545));


   // get transaction information from ccd_activity
   $stmt = "select A.execution_date, C1.customer_name as from_customer, C2.customer_name as to_customer, 
		A.transaction_type, A.order_num, A.cases, A.pallets, A.transaction_status, A.storage_date, A.order_po
	     from ccd_activity A, ccd_customer C1, ccd_customer C2
             where A.ccd_lot_id = '$ccd_lot_id' and C1.customer_id = A.from_customer_id and 
		C2.customer_id = A.to_customer_id
	     order by A.execution_date, A.order_num";

   $result = pg_query($conn, $stmt) or 
             die("Error in query: $stmt. " .  pg_last_error($conn));

   // get the number of rows in the resultset
   $rows = pg_num_rows($result);

   $print_data = array();	// reset the array

   if ($rows > 0) {
      $pdf->ezSetDy(-30);
      $pdf->ezText("Transaction History:\n", 11, $left);
      $pdf->ezSetDy(-10);

      // put each record into an array to be printed
      for ($i=0; $i<$rows; $i++) {
         $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
	 if ($row['execution_date'] != "") {
            $execution_date = date('m/d/y', strtotime($row['execution_date']));
	 } else {
            $execution_date = "";
	 }

	 if ($row['storage_date'] != "") {
            $storage_date = date('m/d/y', strtotime($row['storage_date']));
	 } else {
            $storage_date = "";
	 }

         array_push($print_data, 
	            array('first'=>$execution_date, 'second'=>$row['from_customer'], 
	                  'third'=>$row['to_customer'], 'fourth'=>$row['transaction_type'], 
			  'fifth'=>$row['order_num'], 'sixth'=>strtr($row['pallets'], $trans), 
                          'seventh'=>strtr($row['cases'], $trans), 'seventh_half'=>$row['order_po'], 'eighth'=>$storage_date, 
			  'ninth'=>$row['transaction_status']));
      }
   }

   $pdf->ezTable($print_data, array('first'=>'Date', 'second'=>'From', 'third'=>'To', 
                                    'fourth'=>'Trans Type', 'fifth'=>'Order #', 'sixth'=>'Pallets', 
                                    'seventh'=>'Cases', 'seventh_half'=>'PO#', 'eighth'=>'Storage Date', 'ninth'=>'Status'), '', 
		              array('cols'=>array('first'=>array('justification'=>'center'), 
					          'second'=>array('justification'=>'left'),
					          'third'=>array('justification'=>'left'), 
					          'fourth'=>array('justification'=>'left'),
					          'fifth'=>array('justification'=>'center'),
					          'sixth'=>array('justification'=>'center'),
					          'seventh'=>array('justification'=>'center'),
					          'seventh_half'=>array('justification'=>'center'),
					          'eighth'=>array('justification'=>'center'),
					          'ninth'=>array('justification'=>'center')),
				    'shaded'=>0, 'showLines'=>2, 'width'=>545));

   // close database connection
   pg_free_result($result);
   pg_close($conn);

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

   // redirect to a temporary PDF file instead of directly writing to the browser
   include("redirect_pdf.php");

?>
