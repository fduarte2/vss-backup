<?
   include("pow_session.php");

   // File: track_report_print.php
   //
   // This page generate a PDF file of the tracker report

   // form processor
   if(!$claim_id){
     printf("Missing Claim ID- please go back and try again (Should not get here).");
     exit;
   }

   $ora_conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if (!$ora_conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf"; 
 
  $sql = "select customer_name, invoice_num, invoice_date, receive_date, check_num, check_date, amt_paid,status
          from $claim_header h, customer_profile c
          where h.customer_id = c.customer_id and claim_id = $claim_id";

  $statment = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
        $cust = ora_getcolumn($cursor, 0);
        $invoice_num = ora_getcolumn($cursor, 1);
        $invoice_date = ora_getcolumn($cursor, 2);
	$received_date = ora_getcolumn($cursor, 3);
	$check_num = ora_getcolumn($cursor, 4);
        $check_date = ora_getcolumn($cursor, 5);
        $amt_paid = ora_getcolumn($cursor, 6);
        $status = ora_getcolumn($cursor, 7);
      
        list($junk, $temp_cust) = split("-", $cust);
        if($temp_cust != "")
        $cust = $temp_cust;
	
	if ($invoice_date <>"")
		$invoice_date = date('m/d/y', strtotime($invoice_date));
        if ($received_date <>"")
                $received_date = date('m/d/y', strtotime($received_date));
        if ($check_date <>"")
                $check_date = date('m/d/y', strtotime($check_date));

	if ($status == "C"){
		$status = "Closed";
	}else{
		$status = "Open";
	}
   }


   $sql = "select sum(claim_amt), sum(port_amt), sum(denied_amt), sum(ship_line_amt)
           from $claim_body
           where claim_id = $claim_id and (status is null or status <>'D') ";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
        $tot_claim_amt = ora_getcolumn($cursor, 0);
        $tot_port_amt = ora_getcolumn($cursor, 1);
        $tot_denied_amt = ora_getcolumn($cursor, 2);
        $tot_ship_line_amt = ora_getcolumn($cursor, 3);
   }


  $arrCol1 = array('1st'=>array('width'=>95, 'justification'=>'left'),
                   '2nd'=>array('width'=>60, 'justification'=>'left'),
                   '3rd'=>array('width'=>100, 'justification'=>'left'),
                   '4th'=>array('width'=>60,'justification'=>'left'),
                   '5th'=>array('width'=>110,'justification'=>'left'),
                   '6th'=>array('width'=>60,'justification'=>'left'));

  $arrCol2 = array('1st'=>array('width'=>60, 'justification'=>'left'),
                   '2nd'=>array('width'=>435, 'justification'=>'left'));

  $arrCol3 = array('1st'=>array('width'=>120, 'justification'=>'left'),
                   '2nd'=>array('width'=>120, 'justification'=>'left'),
                   '3rd'=>array('width'=>120, 'justification'=>'left'),
                   '4th'=>array('width'=>130,'justification'=>'left'));



   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->ezSetMargins(20,30,60,60);
 
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   // Write out the intro.
   $pdf->ezText("<b>Claim Report</b>", 18, $center);
   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>Port of Wilmington</b>", 10, $left);                                    
   $pdf->ezText("1 Hausel Road                                                                                                                     Phone (302) 472-7710", 10, $left);
   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                                          Fax (302) 472-7742", 10, $left);
   $pdf->ezSetDy(-15);
   $pdf->ezText("<b>Customer: $cust\nClaim Invoice #:  $invoice_num</b>", 10, $left);

   $pdf->ezSetDy(-30);
   $pdf->ezText("Claim Information:", 10, $left);
   $pdf->ezSetDy(-10);

   $data = array();
   array_push($data, 	  array('1st'=>'Invoice Date:',
                         	'2nd'=>$invoice_date,
                                '3rd'=>'Date Received:',
                                '4th'=>$received_date,
                                '5th'=>'Status:',
                                '6th'=>$status));
   array_push($data,      array('1st'=>'Total Claim Amount:',
                                '2nd'=>'$'.number_format($tot_claim_amt,2,'.',''),
                                '3rd'=>'',
                                '4th'=>'',
                                '5th'=>'',
                                '6th'=>''));
   array_push($data,      array('1st'=>'Total Port Amount:',
                                '2nd'=>'$'.number_format($tot_port_amt,2,'.',''),
                                '3rd'=>'Total Denied Amount:',
                                '4th'=>'$'.number_format($tot_denied_amt,2,'.',''),
                                '5th'=>'Total Ship Line Amount:',
                                '6th'=>'$'.number_format($tot_ship_line_amt, 2,'.','')));
   array_push($data,      array('1st'=>'',
                                '2nd'=>'',
                                '3rd'=>'',
                                '4th'=>'',
                                '5th'=>'',
                                '6th'=>''));
   array_push($data,      array('1st'=>'Check #:',
                                '2nd'=>$check_num,
                                '3rd'=>'Check Date:',
                                '4th'=>$check_date,
                                '5th'=>'Check Amount:',
                                '6th'=>'$'.number_format($amt_paid, 2,'.','')));

   $pdf->ezTable($data, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol1));
   $pdf->ezSetDy(-30);


  $arrCol1 = array('1st'=>array('width'=>60, 'justification'=>'left'),
                   '2nd'=>array('width'=>125, 'justification'=>'left'),
                   '3rd'=>array('width'=>60, 'justification'=>'left'),
                   '4th'=>array('width'=>100,'justification'=>'left'),
                   '5th'=>array('width'=>60,'justification'=>'left'),
                   '6th'=>array('width'=>90,'justification'=>'left'));

   $sql = "select  claim_body_id, vessel, voyage, pallet_id,
                   claim_qty, claim_unit, claim_price, claim_amt,
                   port_amt,denied_amt,ship_line_amt, 
		   port_qty, denied_qty, ship_line_qty, claim_type,notes, bol, exporter
           from $claim_body
           where claim_id = $claim_id and (status is null or status <>'D') ";

   if ($claim_body_id <>"")
	$sql .= " and claim_body_id = $claim_body_id ";

   $sql .= " order  by pallet_id";

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

        if ($is2rdClaim =="Y"){
                $is2rdClaim ="Yes";
	}else{
		$is2rdClaim = "No";
	}

	if($status =="C" && $post_notes <>""){
		if ($internal_notes <>""){
			$internal_notes .="\n". $post_notes;
		}else{
			$internal_notes = $post_notes;
		}
	}
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
                                '5th'=>'Claim Type:',
                                '6th'=>$claim_type));
        array_push($data, array('1st'=>'Pallet Id:',
                                '2nd'=>$pId,
                                '3rd'=>'BOL:',
                                '4th'=>$bol,
                                '5th'=>'Exporter',
                                '6th'=>$exporter));
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
        array_push($comments, array('1st'=>'Internal:','2nd'=>$internal_notes));


        $pdf->ezTable($data, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol1));
        $pdf->ezTable($comments, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>520,'cols'=>$arrCol2));
        $pdf->ezSetDy(-10);
}



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


   ora_close($cursor);
   ora_logoff($ora_conn);
   include("redirect_pdf.php");
?>
