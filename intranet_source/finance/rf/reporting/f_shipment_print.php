<?
   // File: f_shipment_print.php
   //
   // This page generate a PDF file of the Warehouse F - Shipment report

   include("pow_session.php");

   // form value $vessel

   // Connect to the database
   include("connect.php");

   // get vessel # and name
   list($lr_num, $vessel_name) = split(",", $vessel, 2);

   // make database connection
   include("connect.php");
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($conn);

   $stmt = "select ca.customer_id, cp.customer_name, count(*) pallets, sum(qty_change) cases from cargo_activity ca, cargo_tracking ct, customer_profile cp where ca.customer_id = cp.customer_id and ca.arrival_num = ct.arrival_num and ca.customer_id = ct.receiver_id and ca.pallet_id = ct.pallet_id and ca.arrival_num = '$lr_num' and ct.warehouse_location like 'F%' and service_code = 6 and (activity_description is null or activity_description <> 'VOID') group by ca.customer_id, cp.customer_name order by ca.customer_id";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);

   ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $rows = ora_numrows($cursor);

   if ($rows <= 0) {
     header("Location: f_shipment.php?return_lr_num=$lr_num");
   }

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->openHere('XYZ', 0, 800, 1.0);
   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

   // Write out the intro.
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>WAREHOUSE F - DIRECT SHIPMENT REPORT</b>", 16, $center);
   $pdf->ezSetDy(-20);

   // array to store information to print
   $data = array();

   // vessel & date
   array_push($data, array('first'=>'<b>Vessel:</b>', 'second'=>"<b>$lr_num - $vessel_name</b>", 
			   'third'=>'', 'fourth'=>"<b>As Of " . date('m/d/Y g:i:s A') . "</b>", 
			   'fifth'=>''));

   $pdf->ezTable($data, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 
				    'fifth'=>''), '', 
		 array('cols'=>array('first'=>array('justification'=>'right'), 
				     'second'=>array('justification'=>'left'),
				     'fourth'=>array('justification'=>'right')),
		       'shaded'=>0, 'showLines'=>0, 'fontSize'=>12, 'width'=>545));
   $pdf->ezSetDy(-15);

   // print shipment informaton for each customer
   $data = array();
   $num_rows = 1;
   $pallets = 0;
   $cases = 0;

   do {
     $pallets += $row['PALLETS'];
     $cases += $row['CASES'];

     array_push($data, array('first'=>$row['CUSTOMER_NAME'], 
			     'second'=>$row['PALLETS'],
			     'third'=>$row['CASES']));
   } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

   // close Oracle connection
   ora_close($cursor);
   ora_logoff($conn);

   // the total line
   array_push($data, array('first'=>"Total:", 
			   'second'=>"$pallets PALLETS",
			   'third'=>"$cases CASES"));

   $pdf->ezTable($data, array('first'=>'CUSTOMER', 'second'=>'PALLET COUNT', 'third'=>'CASE COUNT'),
		 '', array('cols'=>array('first'=>array('justification'=>'left'), 
					 'second'=>array('justification'=>'left'),
					 'third'=>array('justification'=>'left')),
			   'shaded'=>0, 'showLines'=>2, 'width'=>545));

   $pdf->ezSetDy(-10);

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

   include("redirect_pdf.php");
?>
