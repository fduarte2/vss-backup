<?

  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

  $customer_name = $HTTP_GET_VARS['customer_name'];

  $sql = "SELECT * FROM MARKETING_CUSTOMER_PROFILE WHERE CUSTOMER_NAME = '".$customer_name."'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

//  $customer_name = $row['CUSTOMER_NAME'];
//  $corporate_name = $row['CORPORATE_NAME'];
  $address_one = $row['ADDRESS_ONE'];
  $address_two = $row['ADDRESS_TWO'];
  $city = $row['CITY'];
  $state = $row['STATE'];
  $zip = $row['ZIP'];
  $contact_one = $row['CONTACT_ONE'];
  $contact_two = $row['CONTACT_TWO'];
  $contact_three = $row['CONTACT_THREE'];
  $contact_four = $row['CONTACT_FOUR'];
  $contract_eff = $row['CONTRACT_EFF'];
  $termination = $row['TERMINATION'];
  $notice_period = $row['NOTICE_PERIOD'];
  $date_relation_started = $row['DATE_RELATION_STARTED'];
  $contractual_rates = $row['CONTRACTUAL_RATES'];
  $terminal_agreement_extract = $row['TERMINAL_AGREEMENT_EXTRACT'];
  $terminal_agreement = $row['TERMINAL_AGREEMENT'];
  $commodity = $row['COMMODITY'];
  $special_one = $row['SPECIAL_ONE'];
  $special_two = $row['SPECIAL_TWO'];
  $special_three = $row['SPECIAL_THREE'];
  $comments = $row['COMMENTS'];

  $sql = "SELECT to_char(CONTRACT_EFF, 'MM/DD/YYYY') EFF, to_char(TERMINATION, 'MM/DD/YYYY') TERM, to_char(NOTICE_PERIOD, 'MM/DD/YYYY') NOTICE, to_char(DATE_RELATION_STARTED, 'MM/DD/YYYY') STARTED FROM MARKETING_CUSTOMER_PROFILE WHERE CUSTOMER_NAME = '".$customer_name."'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $contract_eff = $row['EFF'];
  $termination = $row['TERM'];
  $notice_period = $row['NOTICE'];
  $date_relation_started = $row['STARTED'];

  $this_year = date('y');
  $right_now = date('m/d/Y h:i a');

	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','portrait');
	$pdf->ezSetMargins(40,40,50,40);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	$pdf->ezText("<i>Confidential</i>", 20, $center);

	$pdf->ezSetDy(-10);
	$pdf->ezText("<b>Customer Information</b>", 20, $center);
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<i>As of $right_now</i>", 12, $center);
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<i>Customer Grouping:  $customer_name</i>", 12, $left);
   	$pdf->ezText("<i>Address (1):  $address_one</i>", 12, $left);
   	$pdf->ezText("<i>Address (2):  $address_two</i>", 12, $left);
   	$pdf->ezText("<i>City, State, Zip:  $city, $state  $zip</i>", 12, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Contact (1):  $contact_one</i>", 12, $left);
   	$pdf->ezText("<i>Contact (2):  $contact_two</i>", 12, $left);
   	$pdf->ezText("<i>Contact (3):  $contact_three</i>", 12, $left);
   	$pdf->ezText("<i>Contact (4):  $contact_four</i>", 12, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Contract Effective Date:  $contract_eff</i>", 12, $left);
   	$pdf->ezText("<i>Termination Date:  $termination</i>", 12, $left);
   	$pdf->ezText("<i>Notice Period:  $notice_period</i>", 12, $left);
   	$pdf->ezText("<i>Date Commercial Relationship Started:  $date_relation_started</i>", 12, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Commodity:  $commodity</i>", 12, $left);
   	$pdf->ezText("<i>Special (1):  $special_one</i>", 12, $left);
   	$pdf->ezText("<i>Special (2):  $special_two</i>", 12, $left);
   	$pdf->ezText("<i>Special (3):  $special_three</i>", 12, $left);

	$pdf->ezSetDy(-10);

	$pdf->ezText("<i>Comments:  $comments</i>", 12, $left);

	$pdf->ezSetDy(-20);

	$colHeading = array('one'=>'', 'two'=>'Current Fiscal Year', 'three'=>'', 'four'=>'', 'five'=>'Last Fiscal Year', 'six'=>'', 'seven'=>'Prior Year 1', 'eight'=>'Prior Year 2', 'nine'=>'Prior Year 3', 'ten'=>'Prior Year 4');
	$row = array();
//	array_push($row, $colHeading);
/*
	$colOptions = array('one'=>array('width'=>55, 'justification'=>'left'),
						'two'=>array('width'=>55, 'justification'=>'center'),
						'three'=>array('width'=>55, 'justification'=>'center'),
						'four'=>array('width'=>55, 'justification'=>'center'),
						'five'=>array('width'=>55, 'justification'=>'center'),
						'six'=>array('width'=>55, 'justification'=>'center'),
						'seven'=>array('width'=>55, 'justification'=>'center'),
						'eight'=>array('width'=>55, 'justification'=>'center'),
						'nine'=>array('width'=>55, 'justification'=>'center'),
						'ten'=>array('width'=>55, 'justification'=>'center'));
*/
	$colOptions = array('one'=>array('justification'=>'left'),
						'two'=>array('justification'=>'center'),
						'three'=>array('justification'=>'center'),
						'four'=>array('justification'=>'center'),
						'five'=>array('justification'=>'center'),
						'six'=>array('justification'=>'center'),
						'seven'=>array('justification'=>'center'),
						'eight'=>array('justification'=>'center'),
						'nine'=>array('justification'=>'center'),
						'ten'=>array('justification'=>'center'));

	
//	$pdf->ezTable($row, $colHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>550,'fontSize'=>12, 'cols'=>$colOptions));

//	$row2 = array();
	array_push($row, array('one'=>'', 'two'=>'YTD', 'three'=>'FY '.$this_year.' Budget YTD', 'four'=>'Variance to Budget', 'five'=>'YTD', 'six'=>'Variance to Last Year', 'seven'=>'12 Month (A)', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Revenue', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Contribution', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Contrib Per Unit', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
//	array_push($row, array('one'=>'Productivity', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Tonnage / Units', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Ship Calls', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Claims / Expense', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));

	$pdf->ezTable($row, $colHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>550,'fontSize'=>8, 'cols'=>$colOptions));

	$pdf->ezSetDy(-10);
   	$pdf->ezText("<i>Customers Numbers associated with group:  $customer_name</i>", 12, $left);
   	$pdf->ezText("<i>121 - Test Customer Number 1</i>", 12, $left);
   	$pdf->ezText("<i>1940 - Test Customer Number 2</i>", 12, $left);


//   	$pdf->line(50,40,560,40);
   	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	$pdf->addText(60,34,6, $msg);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

	$pdfcode = $pdf->ezOutput();

   $dir = '/var/www/html/upload/pdf_files';
   if (!file_exists($dir)) {
     mkdir ($dir, 0775);
   }

   $fname = tempnam($dir . '/', 'PDF_') . '.pdf';
   $fp = fopen($fname, 'w');
   fwrite($fp, $pdfcode);
   fclose($fp);

   list($junk1, $junk2, $junk3, $junk4, $junk5, $junk, $filename) = split("/", $fname);
   header("Location: /upload/pdf_files/$filename");

  // clean out files older than 5 minutes; code taken from redirect_pdf.php
   if ($d = @opendir($dir)) {
      while (($file = readdir($d)) !== false) {
	 if (substr($file,0,4)=="PDF_"){
	    // then check to see if this one is too old
	    $ftime = filemtime($dir.'/'.$file);
	    if (time() - $ftime > 300){
	      unlink($dir.'/'.$file);
	    }
	 }
      }  
      
      closedir($d);
   }



?>
