<?
  include("pow_session.php");
  include("billing_functions.php");

  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf"; 

  // Finish off a claim
  $claim_id = $HTTP_POST_VARS['claim_id'];
  $signature_id = $HTTP_POST_VARS['signature_id'];
  $letter_date = $HTTP_POST_VARS['date'];

  $arrCol1 = array('1st'=>array('width'=>60, 'justification'=>'left'),
                   '2nd'=>array('width'=>125, 'justification'=>'left'),
                   '3rd'=>array('width'=>60, 'justification'=>'left'),
                   '4th'=>array('width'=>110,'justification'=>'left'),              
                   '5th'=>array('width'=>60,'justification'=>'left'),
		   '6th'=>array('width'=>90,'justification'=>'left'));

  $arrCol2 = array('1st'=>array('width'=>60, 'justification'=>'left'),
                   '2nd'=>array('width'=>445, 'justification'=>'left'));

  $arrCol3 = array('1st'=>array('width'=>120, 'justification'=>'left'),
                   '2nd'=>array('width'=>120, 'justification'=>'left'),
                   '3rd'=>array('width'=>120, 'justification'=>'left'),
                   '4th'=>array('width'=>130,'justification'=>'left'));

  $sql = "select signature, title from claim_letter_signature where id = $signature_id";
  $statment = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$signature = ora_getcolumn($cursor, 0);
	$title = ora_getcolumn($cursor, 1);
  }
  
  $sql = "select customer_id, invoice_num, invoice_date 
	  from $claim_header 
	  where claim_id = $claim_id";

  $statment = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
        $cust_id = ora_getcolumn($cursor, 0);
        $invoice_num = ora_getcolumn($cursor, 1);
        $invoice_date = ora_getcolumn($cursor, 2);
	$invoice_date = date('m/d/Y', strtotime($invoice_date));
   }
   $customer_info = getCustomerInfo($conn, $cursor, $cust_id, "claims");

   $sql = "select count(*)
           from $claim_body
           where claim_id = $claim_id and (status is null or status <>'D')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
	$count = ora_getcolumn($cursor, 0);
   }

   if ($count == 0){
	exit;
   }

   // Initialize PDF writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portriat');
   $pdf -> ezSetMargins(40,70,55,55);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezStartPageNumbers(555, 20, 10, 'left');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

//   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
   $pdf->openHere('XYZ', 0, 800, 1.25);

   // write customer information
   $pdf->ezSetDy(-100);
   $pdf->ezText($letter_date, 10, $left);
   $pdf->ezSetDy(-30);
   $pdf->ezText($customer_info, 10, $left);
   $pdf->ezSetDy(-30);

  
   $letter_body = "Invoice#: $invoice_num                Date:  $invoice_date";

   $pdf->ezText($letter_body, 10, $left);
   $pdf->ezSetDy(-10);

   $sql = "select  claim_body_id, vessel, voyage, pallet_id,
                   claim_qty, claim_unit, claim_price, claim_amt,
                   port_amt,denied_amt,ship_line_amt, port_qty, denied_qty, ship_line_qty, claim_type,notes, bol, exporter
           from $claim_body
           where claim_id = $claim_id and (status is null or status <>'D') order by pallet_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor); 
   $i = 0;
   while (ora_fetch($cursor)){
       	$claim_body_id = ora_getcolumn($cursor, 0);
     	$ves = ora_getcolumn($cursor, 1);
     	$voy = ora_getcolumn($cursor, 2);
      	$pId = ora_getcolumn($cursor, 3);
       	$claim_qty = ora_getcolumn($cursor, 4);
       	$claim_unit = ora_getcolumn($cursor, 5);
      	$claim_price = ora_getcolumn($cursor, 6);
      	$claim_amt = ora_getcolumn($cursor, 7);
       	$port_amt = ora_getcolumn($cursor, 8);
      	$denied_amt = ora_getcolumn($cursor, 9);
       	$ship_line_amt = ora_getcolumn($cursor, 10);
        $port_qty = ora_getcolumn($cursor, 11);
        $denied_qty = ora_getcolumn($cursor, 12);
        $ship_line_qty = ora_getcolumn($cursor, 13);
     	$claim_type = ora_getcolumn($cursor, 14);
	$notes = ora_getcolumn($cursor, 15);
	$bol = ora_getcolumn($cursor, 16);
        $exporter = ora_getcolumn($cursor, 17);

	
    
	$tot_port += $port_amt;
	$tot_denied += $denied_amt;
	$tot_ship_line += $ship_line_amt;
	$tot_claim += $claim_amt;

	$i++;
	$data = array();
	$comments = array();

	array_push($data, array('1st'=>'Vessel:',
				'2nd'=>$ves,
				'3rd'=>'Voyage:',
				'4th'=>$voy,
                                '5th'=>'Exporter:',
                                '6th'=>$exporter)); 
        array_push($data, array('1st'=>'Pallet Id:',
                                '2nd'=>$pId,
                                '3rd'=>'BOL:',
                                '4th'=>$bol,
                                '5th'=>'',
                                '6th'=>''));
        array_push($data, array('1st'=>'QTY:',
                                '2nd'=>$claim_qty,
                                '3rd'=>'Price:',
                                '4th'=>'$'.number_format($claim_price, 2,'.',''),
                                '5th'=>'Total:',
                                '6th'=>'$'.number_format($claim_amt,2,'.','')));
        array_push($data, array('1st'=>'POW Qty:',
                                '2nd'=>$port_qty,
                                '3rd'=>'Denied Qty:',
                                '4th'=>$denied_qty,
                                '5th'=>'S-Line Qty:',
                                '6th'=>$ship_line_qty));
        array_push($data, array('1st'=>'POW Amt:',
                                '2nd'=>'$'.number_format($port_amt,2,'.',''),
                                '3rd'=>'Denied Amt:',
                                '4th'=>'$'.number_format($denied_amt,2,'.',''),
                                '5th'=>'S-Line Amt:',
                                '6th'=>'$'.number_format($ship_line_amt,2,'.','')));
        array_push($comments, array('1st'=>'Comments:','2nd'=>$notes));


  	$before_page_num = $pdf->ezPageCount;
  	$pdf->transaction('start');
	
	$pdf->ezTable($data, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol1));
        $pdf->ezTable($comments, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol2));
        
       	$after_page_num = $pdf->ezPageCount;

  	if ($before_page_num != $after_page_num) {
    		$pdf->transaction('rewind');
    		$pdf->ezNewPage();
    		$pdf->ezSetY(740);

		$pdf->ezTable($data, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol1));
        	$pdf->ezTable($comments, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol2));
	}
	$pdf->transaction('commit');

	$pdf->ezSetDy(-10);		
}
    $sum = array();
    array_push($sum, array('1st'=>'<b>Total: $'.number_format($tot_claim, 2, '.','').'</b>',
                           '2nd'=>'<b>POW: $'.number_format($tot_port, 2, '.','').'</b>',
                           '3rd'=>'<b>Denied: $'.number_format($tot_denied, 2, '.','').'</b>',
                           '4th'=>'<b>Ship Line: $'.number_format($tot_ship_line, 2, '.','').'</b>')); 			   

    $pdf->ezTable($sum, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol3));

    $before_page_num = $pdf->ezPageCount;
    $pdf->transaction('start');

    // write closing information
    $pdf->ezSetDy(-30);
    $pdf->ezText("Sincerely,", 10, $left);
    $pdf->ezSetDy(-30);
    $pdf->ezText("$signature\n$title\nDiamond State Port Corporation", 10, $left);

    $after_page_num = $pdf->ezPageCount;
    if ($before_page_num != $after_page_num) {
        $pdf->transaction('rewind');
        $pdf->ezNewPage();
        $pdf->ezSetY(740);

        $pdf->ezSetDy(-30);
        $pdf->ezText("Sincerely,", 10, $left);
        $pdf->ezSetDy(-30);
        $pdf->ezText("$signature\n$title\nDiamond State Port Corporation", 10, $left);

    }
    $pdf->transaction('commit');

  ora_close($cursor);
  ora_logoff($conn);
  // redirect to a temporary PDF file instead of directly writing to the browser
  include("redirect_pdf.php");
  exit;
			
?>
