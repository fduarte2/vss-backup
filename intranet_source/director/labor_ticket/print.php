<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";
/*
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
*/

function htmlText($text){
	if ($text =="")
		$text = "&nbsp;";
	return $text;
}
   $user = $HTTP_COOKIE_VARS[lcs_user];
   if($HTTP_SERVER_VARS["argv"][1]<>"email" && $user == ""){
//      header("Location: ../../lcs_login.php");
//      exit;
   }
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
//    printf("Error logging on to the Oracle Server: ");
//    printf(ora_errorcode($conn));
//    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

   $sDate = $HTTP_POST_VARS[sDate];
   $eDate = $HTTP_POST_VARS[eDate];
   $isPrint = $HTTP_POST_VARS[rPrint];

   $arrHeading1 = array('sup'=>'', 'sHours'=>'<b>Business Hours</b> (8am-12pm & 1pm-5pm)', 'other'=>'<b>Non Business Hours</b>', 'tVar'=>'<b>Total</b>');

   $arrHeading2 = array('sup'=>'<b>Supervisor</b>', 'bPaid'=>'<b>Paid</b>', 'bTicketed'=>'<b>Ticketed</b>', 'bVar'=>'<b>Balance</b>', 'nbPaid'=>'<b>Paid</b>','nbTicketed'=>'<b>Ticketed</b>','nbVar'=>'<b>Balance</b>', 'tPaid'=>'<b>Paid</b>','tTicketed'=>'<b>Ticketed</b>', 'tVar'=>'<b>Balance</b>');


   $arrCol1 = array('sup'=>array('width'=>120, 'justification'=>'left'),
                   'sHours'=>array('width'=>201, 'justification'=>'center'),
                   'other'=>array('width'=>201, 'justification'=>'center'),
		   'tVar'=>array('width'=>201, 'justification'=>'center'));

   $arrCol2 = array('sup'=>array('width'=>120, 'justification'=>'left'),
                   'bPaid'=>array('width'=>67, 'justification'=>'center'),
                   'bTicketed'=>array('width'=>67, 'justification'=>'center'),
		   'bVar'=>array('width'=>67, 'justification'=>'center'),
		   'nbPaid'=>array('width'=>67, 'justification'=>'center'),
                   'nbTicketed'=>array('width'=>67, 'justification'=>'center'),
                   'nbVar'=>array('width'=>67, 'justification'=>'center'),
                   'tPaid'=>array('width'=>67, 'justification'=>'center'),
                   'tTicketed'=>array('width'=>67, 'justification'=>'center'),
                   'tVar'=>array('width'=>67, 'justification'=>'center'));

   $heading1 = array();
   $heading2 = array();	
   array_push($heading1, $arrHeading1);
   array_push($heading2, $arrHeading2);

   //retrive data to display
   $type[0] = "Operations";
   $type[1] = "Crane";
   $type[2] = "Maintenance";
   $type[3] = "Security";

   $data[0] = array();
   $data[1] = array();
   $data[2] = array();
   $data[3] = array();

for($i = 0; $i < count($type); $i++){
   $tot_bPaid = 0;
   $tot_nbPaid = 0;
   $tot_bTicketed = 0;
   $tot_nbTicketed = 0;


   $sql = "select user_name, sum(bPaid), sum(nbPaid), sum(bTicketed), sum(nbTicketed), p.user_id
	   from paid_hours_vs_labor_ticket p, supervisor_type s
	   where p.user_id = s.user_id and s.type = '$type[$i]' and  
	   hire_date >= to_date('$sDate','mm/dd/yyyy') and hire_date <= to_date('$eDate','mm/dd/yyyy')	
	   group by user_name, p.user_id order by user_name";	

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
	$user = ora_getcolumn($cursor, 0);
        $bPaid = ora_getcolumn($cursor, 1);
        $nbPaid = ora_getcolumn($cursor, 2);
        $bTicketed = ora_getcolumn($cursor, 3);
        $nbTicketed = ora_getcolumn($cursor, 4);
	$user_id = ora_getcolumn($cursor, 5);


	$tot_bPaid += $bPaid;
	$tot_nbPaid += $nbPaid;
	$tot_bTicketed += $bTicketed;
	$tot_nbTicketed += $nbTicketed;

	array_push($data[$i], array('user_id'=>$user_id,
				'sup'=>ucwords(strtolower($user)), 
				'bPaid'=>number_format($bPaid,1,'.',','), 
				'bTicketed'=>number_format($bTicketed,1,'.',','),
				'bVar'=>number_format($bPaid - $bTicketed, 1,'.',','),
				'nbPaid'=>number_format($nbPaid,1,'.',','),
				'nbTicketed'=>number_format($nbTicketed,1,'.',','),
				'nbVar'=>number_format($nbPaid - $nbTicketed,1,'.',','),
				'tPaid'=>number_format($bPaid + $nbPaid,1,'.',','),
                                'tTicketed'=>number_format($bTicketed + $nbTicketed,1,'.',','), 
				'tVar'=>number_format($bPaid + $nbPaid - $bTicketed - $nbTicketed ,1,'.',',')));
   }
	
   array_push($data[$i], array('sup'=>'<b>Total</b>',
                           'bPaid'=>'<b>'.number_format($tot_bPaid,1,'.',',').'</b>',
                           'bTicketed'=>'<b>'.number_format($tot_bTicketed,1,'.',',').'</b>',
                           'bVar'=>'<b>'.number_format($tot_bPaid - $tot_bTicketed, 1,'.',',').'</b>',
                           'nbPaid'=>'<b>'.number_format($tot_nbPaid,1,'.',',').'</b>',
                           'nbTicketed'=>'<b>'.number_format($tot_nbTicketed,1,'.',',').'</b>',
                           'nbVar'=>'<b>'.number_format($tot_nbPaid - $tot_nbTicketed,1,'.',',').'</b>',
                           'tPaid'=>'<b>'.number_format($tot_bPaid + $tot_nbPaid,1,'.',',').'</b>',
                           'tTicketed'=>'<b>'.number_format($tot_bTicketed + $tot_nbTicketed,1,'.',',').'</b>',
                           'tVar'=>'<b>'.number_format($tot_bPaid + $tot_nbPaid - $tot_bTicketed - $tot_nbTicketed ,1,'.',',').'</b>'));
}


   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(40,40,50,40);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $format = "Printed On: " . date('m/d/y g:i A');

   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->addText(650, 580,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Paid Hours vs Labor Tickets</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i> $sDate to $eDate </i></b>", 18, $center);
//   $pdf->ezSetDy(-15);

   

   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
 
   for($i = 0; $i < count($type); $i++){
        if (count($data[$i])>0) {
   		$pdf->ezSetDy(-15);
   		$pdf->ezText("<b>$type[$i]</b>", 12, $center);
                $pdf->ezSetDy(-5);
   		$pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   		$pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));

   		$pdf->ezTable($data[$i], $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
	}
   }
   $pdf->ezStream();

?>		

<?

/*
   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
//        $pdf->ezStream();
   }else{

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";

	$mailTo = "gbailey@port.state.de.us,";
	$mailTo .= "ithomas@port.state.de.us,";
//        $mailTo .= "ffitzgerald@port.state.de.us,";
	$mailTo .="parul@port.state.de.us";

        $mailsubject = "Productivity Report";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: wstans@port.state.de.us,jjaffe@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,rwang@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        $mailheaders .= "X-Mailer: PHP4\r\n";
        $mailheaders .= "X-Priority: 3\r\n";
        $maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        $maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"Productivity Report.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";

//      mail($mailTo1, $mailsubject, $Content, $mailheaders);
//      mail($mailTo, $mailsubject, $Content, $mailheaders);
*/


?> 
