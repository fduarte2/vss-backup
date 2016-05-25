<?
   include("pow_session.php");
   $user = $userdata['username'];
	
   $sDate = $HTTP_POST_VARS[start_date];
   $eDate = $HTTP_POST_VARS[end_date];
   $sortBy = $HTTP_POST_VARS[sortBy];

   if (isset($HTTP_POST_VARS[export])){
      header("Location: export.php?start_date=$sDate&end_date=$eDate");
   }

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $today = date('F j, Y');
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);

   $pdf->ezSetMargins(40,40,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $pdf->ezStartPageNumbers(300, 20, 8, '','',1);
   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>SECURITY LOG REPORT</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<i>From $sDate to $eDate</i>", 12, $center);
   $pdf->ezSetDy(-15);

   $today = date('m/j/y H:i:s');
   $format = "Printed On: " . $today;
 //  $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
 // $pdf->line(45,30,555,30);
   $pdf->addText(450,760,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   if ($sortBy =="date"){
	$strSort = " order by activity_date ";
	$arrHeading = array('aDate'=>'<b>DATE</b>', 'officer'=>'<b>OFFICER</b>', 'loc'=>'<b>LOCATION</b>');
   } else if ($sortBy =="officer"){
	$strSort = " order by login_id, activity_date ";
	$arrHeading = array('officer'=>'<b>OFFICER</b>', 'aDate'=>'<b>DATE</b>', 'loc'=>'<b>LOCATION</b>');
   } else if ($sortBy =="location") {
	$strSort = " order by l.location_code, activity_date ";
	$arrHeading = array('loc'=>'<b>LOCATION</b>', 'aDate'=>'<b>DATE</b>', 'officer'=>'<b>OFFICER</b>',);
   }
   
   $arrCol = array('aDate'=>array('width'=>100, 'justification'=>'left'),
                      'officer'=>array('width'=>60, 'justification'=>'center'),
                      'loc'=>array('width'=>350, 'justification'=>'left'));

   $sql = "select to_char(activity_date, 'mm/dd/yyyy hh24:mi:ss'), login_id, l.location_code, c.description from security_log l, security_location c  where activity_date >= TO_DATE('".$sDate." 00:00:00 ', 'MM/DD/YYYY hh24:mi:ss') and activity_date <= TO_DATE('".$eDate." 23:59:59', 'MM/DD/YYYY hh24:mi:ss') and l.location_code = c.location_code(+) " . $strSort;
//echo $sql;
   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();
   while (ora_fetch($cursor)){
	$aDate = ora_getcolumn($cursor, 0);
	$office = trim(ora_getcolumn($cursor, 1));
	$lCode = trim(ora_getcolumn($cursor, 2));
	$lDesc = trim(ora_getcolumn($cursor, 3));	

      	// added to $data for printing
      	array_push($data, array('aDate'=>$aDate, 'officer'=>$office, 'loc'=>"(".$lCode.")  ".$lDesc));

        $count  += 1;     
   }
   	
   $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$arrCol));

   $pdf->ezStream();

   ora_close($cursor);

?>
