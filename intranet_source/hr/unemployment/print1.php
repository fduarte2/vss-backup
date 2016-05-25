<?

  include("pow_session.php");
  
  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $reqId = $HTTP_GET_VARS[reqId];

  $sql = "select employee_name,ssn, pay_rate, to_char(pay_period_end_date,'mm/dd/yy'),holiday_pay,to_char(holiday,'mm/dd/yy')
	  from unemployment_claim_header where request_id = $reqId";
  ora_parse($cursor, $sql);
  ora_exec($cursor);

  if (ora_fetch($cursor)){
	$empName = ora_getcolumn($cursor, 0);
        $ssn = ora_getcolumn($cursor, 1);
        $bRate = ora_getcolumn($cursor, 2);
        $eDate = ora_getcolumn($cursor, 3);
        $hPay = ora_getcolumn($cursor, 4);
        $holiday = ora_getcolumn($cursor, 5);
  }

  $sql = "select to_char(date_worked,'mm/dd/yy'),wage+gratuities, hours_worked, houes_absent
	 from unemployment_claim_body where request_id = $reqId order by date_worked";
  ora_parse($cursor, $sql);
  ora_exec($cursor);

  $i = 0;
  while (ora_fetch($cursor)){
	$hDate[$i] = ora_getcolumn($cursor, 0);
        $pay[$i] = ora_getcolumn($cursor, 1);
        $hour[$i] = ora_getcolumn($cursor, 2);
        $absent[$i] = ora_getcolumn($cursor, 3);
    
        $tot_pay += $pay[$i];
	$tot_hour += $hour[$i];
	$tot_absent += $absent[$i]; 
	
 	$pay[$i] = "$".number_format($pay[$i], 2,'.',',');
        $hour[$i] = number_format($hour[$i], 1,'.',',');
        $absent[$i] = number_format($absent[$i], 1,'.',',');

 	$i ++;
  }
  $tot_pay = "$".number_format($tot_pay, 2,'.',',');
  $tot_hour = number_format($tot_hour, 1,'.',',');
  $tot_absent = number_format($tot_absent, 1,'.',',');

        // initiate the pdf writer
        include 'class.ezpdf.php';
        $pdf = new Cezpdf('letter','landscape');


        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
        $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
        $pdf->setFontFamily('Helvetica.afm', $tmp);
        
	$pdf->addJpegFromFile('../images/unemployment.JPG', 0, 0, 800, 600);

        $pdf->addText(120,495, 10, $ssn);
	$pdf->addText(350,495, 10, $empName);
        $pdf->addText(125,471, 10, $eDate);
        $pdf->addText(300,471, 10, '$'.number_format($bRate, 2, '.',','));
	if ($hPay <>"")
        	$pdf->addText(505,471, 10, '$'.number_format($hPay, 2, '.',','));
        $pdf->addText(655,471, 10, $holiday);

        $pdf->addText(75,360, 10, $hDate[0]);
        $pdf->addText(125,360, 10, $pay[0]);
        $pdf->addText(185,360, 10, $hour[0]);
        if ($gratuities[0] <> "")
        	$pdf->addText(238,360, 10, number_format($gratuities[0], 2, '.',','));
        if ($absent[0] <>"")
		$pdf->addText(292,360, 10, $absent[0]);

        $pdf->addText(75,348, 10, $hDate[1]);
        $pdf->addText(125,348, 10, $pay[1]);
        $pdf->addText(185,348, 10, $hour[1]);
	if ($gratuities[1] <> "")
        	$pdf->addText(238,348, 10, number_format($gratuities[1], 2, '.',','));
        if ($absent[1] <>"")
		$pdf->addText(292,348, 10, $absent[1]);

        $pdf->addText(75,336, 10, $hDate[2]);
        $pdf->addText(125,336, 10, $pay[2]);
        $pdf->addText(185,336, 10, $hour[2]);
	if ($gratuities[2] <> "")
        	$pdf->addText(238,336, 10, number_format($gratuities[2], 2, '.',','));
        if ($absent[2] <>"")	
		$pdf->addText(292,336, 10, $absent[2]);

        $pdf->addText(75,324, 10, $hDate[3]);
        $pdf->addText(125,324, 10, $pay[3]);
        $pdf->addText(185,324, 10, $hour[3]);
	if ($gratuities[3] <> "")
        	$pdf->addText(238,324, 10, number_format($gratuities[3], 2, '.',','));
        if ($absent[3] <>"")
		$pdf->addText(292,324, 10, $absent[3]);

        $pdf->addText(75,312, 10, $hDate[4]);
        $pdf->addText(125,312, 10, $pay[4]);
        $pdf->addText(185,312, 10, $hour[4]);
	if ($gratuities[4] <> "")
        	$pdf->addText(238,312, 10, number_format($gratuities[4], 2, '.',','));
        if ($absent[4] <>"")
		$pdf->addText(292,312, 10, $absent[4]);

        $pdf->addText(75,300, 10, $hDate[5]);
        $pdf->addText(125,300, 10, $pay[5]);
        $pdf->addText(185,300, 10, $hour[5]);
	if ($gratuities[5] <> "")
        	$pdf->addText(238,300, 10, number_format($gratuities[5], 2, '.',','));
        if ($absent[5] <>"")
		$pdf->addText(292,300, 10, $absent[5]);

        $pdf->addText(75,288, 10, $hDate[6]);
        $pdf->addText(125,288, 10, $pay[6]);
        $pdf->addText(185,288, 10, $hour[6]);
	if ($gratuities[6] <> "")
        	$pdf->addText(238,288, 10, number_format($gratuities[6], 2, '.',','));
        if ($absent[6] <>"")
		$pdf->addText(292,288, 10, $absent[6]);

        $pdf->addText(125,277, 10, $tot_pay);
        $pdf->addText(185,277, 10, $tot_hour);

	if ($tot_gratuities <> "")
        	$pdf->addText(238,277, 10, number_format($tot_gratuities, 2, '.',','));
        if ($tot_absent <>"")
		$pdf->addText(292,277, 10, $tot_absent);


	$pdf->addText(430, 277, 10, date('m/d/y'));

  $pdf->ezStream();

  ora_close($cursor);
  ora_logoff($conn);

?>
