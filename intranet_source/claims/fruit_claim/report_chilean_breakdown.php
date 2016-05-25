<?
include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

include("../claim_function.php");
// include the date comparing function
//include("compareDate.php");

// To be used to eliminate trailing zeros
$trans = array(".00"=>"");

// make Oracle connection
$conn = ora_logon("SAG_OWNER@$bni", "SAG");
$cursor = ora_open($conn);


$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$season = $HTTP_POST_VARS["season"];
// $customer = $HTTP_POST_VARS["customer"];  ---NOT USED---

$system = "RF";
$data = array();

//get customer name
$cust = getRFSeasonCustomerList($cursor, $season);

while(list($cust_id, $cust_name)=each($cust)){
  $sql = "select a.submission, b.denial, b.shipper, b.pow, c.acceptance
          from
          (select customer_id, sum(claim_amt) as submission
          from claim_header_rf h, claim_body_rf b
          where h.claim_id = b.claim_id and customer_id = $cust_id and h.season='$season' and b.status <> 'D'
          group by customer_id) a,
          (select customer_id, sum(denied_amt) as denial, sum(ship_line_amt) as shipper, sum(port_amt) as pow
          from claim_header_rf h, claim_body_rf b
          where h.claim_id = b.claim_id and customer_id = $cust_id and h.season='$season' and b.status IN ('C', 'O')
          group by customer_id) b,   
          (select customer_id, sum(claim_amt) as acceptance
          from claim_header_rf h, claim_body_rf b
          where h.claim_id = b.claim_id and customer_id = $cust_id and h.season='$season' and b.status <>'D' and h.ispercentage = 'Y'
          group by customer_id) c 
	  where a.customer_id = b.customer_id(+) and a.customer_id = c.customer_id(+) ";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  
  while (ora_fetch($cursor)){
        $submission = round(ora_getcolumn($cursor, 0),2);
 	$denial = round(ora_getcolumn($cursor, 1),2);
        $shipper = round(ora_getcolumn($cursor, 2),2);
        $pow = round(ora_getcolumn($cursor, 3),2);
	$acceptance = round(ora_getcolumn($cursor, 4),2);
	$sixty_eight = round(0.68*$acceptance, 2);

        $tot_submission += $submission;
	$tot_denial += $denial;
	$tot_shipper += $shipper;
	$tot_pow += $pow;
	$tot_acceptance += $acceptance;
	$tot_sixty_eight += $sixty_eight;

	if ($submission > 0){
		$dis_sub = '$'.number_format($submission, 2, '.',',');
	}else{
		$dis_sub = "-";
	}
	if ($denial > 0){
		$dis_denial = '$'.number_format($denial, 2, '.',',');
	}else{
		$dis_denial = "-";
	}
	if ($shipper > 0){
		$dis_shipper = '$'.number_format($shipper, 2, '.',',');
	}else{
		$dis_shipper = "-";
	}
	if ($pow > 0){
		$dis_pow = '$'.number_format($pow, 2, '.',',');
	}else{
		$dis_pow = "-";
	}
	if ($acceptance > 0){
		$dis_accp = '$'.number_format($acceptance, 2, '.',',');
	}else{
		$dis_accp = "-";
	}
	if ($sixty_eight >0){
		$dis_68 = '$'.number_format($sixty_eight, 2, '.',',');
	}else{
		$dis_68 = "-";
	}
	array_push($data, array('cust'=>$cust_name,
				'sub'=>$dis_sub,
                                'denial'=>$dis_denial,
                                'shipper'=>$dis_shipper,
                                'pow'=>$dis_pow,
								' '=>' ',
								'accp'=>$dis_accp,
                                'sixty_eight'=>$dis_68,
				'url'=>"http://$hostname/claims/fruit_claim/report_print.php?season=$season&customer=$cust_id&report_type=pdf"));
//				'url'=>"http://$hostname/claims/fruit_claim/report_detail.php?season=$season&customer=$cust_id"));
  }
}
   array_push($data, array('cust'=>'<b>Total</b>',
                           'sub'=>'<b>$'.number_format($tot_submission, 2, '.',',').'</b>',
                           'denial'=>'<b>$'.number_format($tot_denial, 2, '.',',').'</b>',
                           'shipper'=>'<b>$'.number_format($tot_shipper, 2, '.',',').'</b>',
                           'pow'=>'<b>$'.number_format($tot_pow, 2, '.',',').'</b>',
						   ' '=>' ',
						   'accp'=>'<b>$'.number_format($tot_acceptance, 2, '.',',').'</b>',
                           'sixty_eight'=>'<b>$'.number_format($tot_sixty_eight, 2, '.',',').'</b>'));



// close database connections
ora_close($cursor);
ora_logoff($conn);

 
   $arrHeading = array( 'cust'=>'<b>Customer</b>',
			'sub'=>'<b>Claim Submission</b>',
			'pow'=>'<b>POW</b>',
			'denial'=>'<b>Denial</b>',
			'shipper'=>'<b>Pacific Seaways</b>', 
			' ' => ' ',
			'accp'=>'<b>Claim Acceptance</b>',
			'sixty_eight'=>'<b>68%</b>');
// note I can't get the column shading to work, but the intent (to separate the columns via space) does, so I'm leaving it in.
   $arrCol = array('cust'=>array('width'=>150, 'justification'=>'left'),
                   'sub'=>array('width'=>90, 'justification'=>'right'),
                   'pow'=>array('width'=>90, 'justification'=>'right'),
                   'denial'=>array('width'=>90, 'justification'=>'right'),
                   'shipper'=>array('width'=>90, 'justification'=>'right'),
				   ' ' =>array('width'=>6, 'shaded'=>2),
				   'accp'=>array('width'=>90, 'justification'=>'right'),
                   'sixty_eight'=>array('width'=>90,'justification'=>'right'),
		   'cust'=>array('link'=>'url'));
 
   $heading = array();
   array_push($heading, $arrHeading);

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(20,20,65,65);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>$season POW Claim Comparison Report</b>", 14, $center);
   $pdf->ezSetDy(-5);


   $pdf->ezSetDy(-20);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');

   $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>730,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2,'fontSize'=>8,'width'=>730,'cols'=>$arrCol));


// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");
?>
