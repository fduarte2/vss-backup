<?

  include("pow_session.php");
  
  //get parmeters
  $reqId = $HTTP_POST_VARS[reqId];
  $empName = $HTTP_POST_VARS[empName];
  $ssn = $HTTP_POST_VARS[ssn];
  $bRate = $HTTP_POST_VARS[bRate];
  $eDate = $HTTP_POST_VARS[eDate];

  $hDate = $HTTP_POST_VARS[hDate];
  $pay = $HTTP_POST_VARS[pay];
  $hour = $HTTP_POST_VARS[hour];
  $gratuities = $HTTP_POST_VARS[gratuities];
  $absent = $HTTP_POST_VARS[absent];
  $hPay = $HTTP_POST_VARS[hPay];
  $holiday = $HTTP_POST_VARS[holiday];

  $size = count($hDate);



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
		$pdf->addText(292,360, 10, number_format($absent[0], 2, '.',','));

        $pdf->addText(75,348, 10, $hDate[1]);
        $pdf->addText(125,348, 10, $pay[1]);
        $pdf->addText(185,348, 10, $hour[1]);
	if ($gratuities[1] <> "")
        	$pdf->addText(238,348, 10, number_format($gratuities[1], 2, '.',','));
        if ($absent[1] <>"")
		$pdf->addText(292,348, 10, number_format($absent[1], 2, '.',','));

        $pdf->addText(75,336, 10, $hDate[2]);
        $pdf->addText(125,336, 10, $pay[2]);
        $pdf->addText(185,336, 10, $hour[2]);
	if ($gratuities[2] <> "")
        	$pdf->addText(238,336, 10, number_format($gratuities[2], 2, '.',','));
        if ($absent[2] <>"")	
		$pdf->addText(292,336, 10, number_format($absent[2], 2, '.',','));

        $pdf->addText(75,324, 10, $hDate[3]);
        $pdf->addText(125,324, 10, $pay[3]);
        $pdf->addText(185,324, 10, $hour[3]);
	if ($gratuities[3] <> "")
        	$pdf->addText(238,324, 10, number_format($gratuities[3], 2, '.',','));
        if ($absent[3] <>"")
		$pdf->addText(292,324, 10, number_format($absent[3], 2, '.',','));

        $pdf->addText(75,312, 10, $hDate[4]);
        $pdf->addText(125,312, 10, $pay[4]);
        $pdf->addText(185,312, 10, $hour[4]);
	if ($gratuities[4] <> "")
        	$pdf->addText(238,312, 10, number_format($gratuities[4], 2, '.',','));
        if ($absent[4] <>"")
		$pdf->addText(292,312, 10, number_format($absent[4], 2, '.',','));

        $pdf->addText(75,300, 10, $hDate[5]);
        $pdf->addText(125,300, 10, $pay[5]);
        $pdf->addText(185,300, 10, $hour[5]);
	if ($gratuities[5] <> "")
        	$pdf->addText(238,300, 10, number_format($gratuities[5], 2, '.',','));
        if ($absent[5] <>"")
		$pdf->addText(292,300, 10, number_format($absent[5], 2, '.',','));

        $pdf->addText(75,288, 10, $hDate[6]);
        $pdf->addText(125,288, 10, $pay[6]);
        $pdf->addText(185,288, 10, $hour[6]);
	if ($gratuities[6] <> "")
        	$pdf->addText(238,288, 10, number_format($gratuities[6], 2, '.',','));
        if ($absent[6] <>"")
		$pdf->addText(292,288, 10, number_format($absent[6], 2, '.',','));

        $pdf->addText(125,277, 10, $pay[7]);
        $pdf->addText(185,277, 10, $hour[7]);

	for ($i = 0; $i < 7; $i++){
		$tot_gratuities += $gratuities[$i];
		$tot_absent += $absent[$i]; 
       	}

	if ($tot_gratuities <> "")
        	$pdf->addText(238,277, 10, number_format($tot_gratuities, 2, '.',','));
        if ($tot_absent <>"")
		$pdf->addText(292,277, 10, number_format($tot_absent, 2, '.',','));

/*
        // Write out the intro.
        // Print Receiving Header
        $pdf->ezSetDy(-10);
        $pdf->ezText("<b>UNEMPLOYMENT CLAIM</b>", 24, $center);
        $pdf->ezSetDy(-35);
        $pdf->ezText("  EMPLOYEE NAME:                                <b>$empName</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  SOCIAL SECURITY NUMBER:              <b>$ssn</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  EMPLOYEE GROSS HOURLY RATE:   <b>$".number_format($bRate, 2, '.',',')."</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  PAY PERIOD ENDING:                           <b>$eDate</b>", 10, $left);

       $pdf->line(50,680,560,680);
       $pdf->line(50,598,560,598);
       $pdf->line(50,680,50,598);
       $pdf->line(560,680,560,598);

       $pdf->line(150,665,545,665);
       $pdf->line(201,644,545,644);
       $pdf->line(229,623,545,623);
       $pdf->line(165,602,545,602);

	$pdf->ezSetDy(-25);
        $pdf->ezTable($data, $arrHeading2, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>10,'cols'=>$arrCol2));

        $pdf->ezSetDy(-25);
     	$pdf->ezText("  During the pay period of this report, the above name employee worked reduced hours due to lack of work.", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  I CERTIFY that answers and wage information are correct as indicated on this form.", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  Employers name:       <b>Diamond State Port Corporation</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  Address:                     <b>1 Hausel Road, Wilmington, DE 19801-5852</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  Phone Number:          <b>302-472-7678</b>", 10, $left);
        $pdf->ezSetDy(-10);
        $pdf->ezText("  Employer Signature:", 10, $left);

       $pdf->line(50,370,560,370);
       $pdf->line(50,240,560,240);
       $pdf->line(50,370,50,240);
       $pdf->line(560,240,560,370);

       $pdf->line(140,308,545,308);
       $pdf->line(102,287,545,287);
       $pdf->line(132,266,545,266);
       $pdf->line(155,245,545,245);

*/
$pdf->ezStream();

if ($reqId <>"")
{
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $sql = "update unemployment_claim_request set status = 'Processed' where request_id = $reqId";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  ora_close($cursor);
  ora_logoff($conn);
}
?>
