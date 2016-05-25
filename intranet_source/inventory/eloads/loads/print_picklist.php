<?
   // File: print_picklist.php
   //
   // This page generate a PDF file of the Oppy Picklist

// Returns a variable $pdf_code which had the picklist PDF in it  You can print
// /save/e-mail this 
//function print_picklist($load_num){
   // form processor
   if(!$load_num){
     printf("Missing Load Number- please go back and try again (Should not get here).");
     exit;
   }
   include("../eloads_globals.php");
   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

   $ora_conn = ora_logon(RF, PASS);
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);

   $customer_id = "1512";
   $customer_name = "1512";

   $stmt = "select * from pl_head where load_num = '$load_num'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $pl_head, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   
   $load_num = $pl_head['LOAD_NUM'];
   if(!$load_num){
     echo "No Load number found in pl_head!";
     exit;
   }
   $stmt = "select * from pl_order_head where load_num = '$load_num' order by order_num";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);

   // Loop for each order
   ora_fetch_into($cursor, $pl_order_head, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $order_num = $pl_order_head['ORDER_NUM'];

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

   // Write out the intro.
   $pdf->ezText("<b>Order Picklist</b>", 18, $center);
   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>Port of Wilmington</b>", 12, $left);
   $pdf->ezText("1 Hausel Road                                                                                                        Phone (302) 472-7710", 12, $left);
   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                             Fax (302) 472-7742", 12, $left);
   $pdf->ezSetDy(-15);

   // Get the Customer Barcode
   $barcode_file = $customer_id . ".jpg";
   $barcode_file2 = $order_num . ".jpg";
   $cmd = "wget -O $barcode_file http://dspc-intranet/barcode/gen_barcode.php\?type=C39\&code=$customer_id";
   $cmd2 = "wget -O $barcode_file2 http://dspc-intranet/barcode/gen_barcode.php\?type=C39\&code=$order_num";
   system($cmd, $stat);
   system($cmd2, $stat);

   $pdf->ezText("<b>                                   Owner: $customer_name                                 Order Number: $order_num\n</b>", 12, $left);
   $pdf->addJpegFromFile($barcode_file, 89, $pdf->y-50, 175, 0);
   $pdf->addJpegFromFile($barcode_file2, 314, $pdf->y-50, 175, 0);
   $pdf->ezSetDy(-62);
   $pdf->ezText(":", 11, $left);

   // Remove the images
   if (file_exists($barcode_file)) {
     unlink ($barcode_file);
   }
   if (file_exists($barcode_file2)) {
     unlink ($barcode_file2);
   }


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
