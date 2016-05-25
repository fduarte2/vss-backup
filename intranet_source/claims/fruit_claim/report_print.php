<?
include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

// include the date comparing function
include("compareDate.php");

// To be used to eliminate trailing zeros
$trans = array(".00"=>"");

// make postgreSQL connection
/* no longer used

$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
} */

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

$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$customer = $HTTP_POST_VARS["customer"];
list($customer_id, $report_customer_name) = split(",", $customer);
$claim_type = $HTTP_POST_VARS["claim_type"];
$ispct = $HTTP_POST_VARS["ispct"];
// echo $ispct."toplevel";
$status = $HTTP_POST_VARS["status"];
$order_by = $HTTP_POST_VARS["order_by"];
$start_date = $HTTP_POST_VARS["start_date"];
$end_date = $HTTP_POST_VARS["end_date"];
$season = $HTTP_POST_VARS["season"];

$report_type = $HTTP_GET_VARS['report_type'];

if($report_type == "pdf"){
// default values set if this report is accessed from a link from a pdf page rather than  dspc-s16/claims/fruit/claim/report.php
	$customer_id = $HTTP_GET_VARS['customer'];

	if($customer_id != ""){
		$sql = "SELECT CUSTOMER_NAME FROM CLAIM_SEASON_CUSTOMER_RF WHERE CUSTOMER_ID = '".$customer_id."'";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$report_customer_name = $row['CUSTOMER_NAME'];
	} else {
		$report_customer_name = "All Customers";
	}

	$claim_type = "";
	$ispct = "none";
	$status = "";
	$order_by = " h.invoice_num asc ";
	$start_date = "";
	$end_date = "";
	$season = $HTTP_GET_VARS['season'];
}





// check if the dates are in acceptable format
// if it is null, keep it untouched; if it is later than today, set it to be today
if ($start_date != "") {
  $return = strtotime($start_date);
  if ($return == -1) {			// invalid date format
    die ("The start date you entered, $start_date, is not in an acceptable format.\n
	    You may use the format as in the following example, 12/31/2003.");
  } else {
    $start_date = date('m/d/Y', $return);	// start date cannot be later than today
    if (compareDate($start_date, $today) > 0 ) {
      $start_date = $today;
    }
  }
}

if ($end_date != "") {
  $return = strtotime($end_date);
  if ($return == -1) {			// invalid date format
    die ("The end date you entered, $end_date, is not in an acceptable format.\n
          You may use the format as in the following example, 12/31/2003.");
  } else {
    $end_date = date('m/d/Y', $return);	// end date cannot be later than today
    if (compareDate($end_date, $today) > 0 ) {
      $end_date = $today;
    }
  }
}

// switch the dates if start date is later than end date
if ($start_date != "" && $end_date != "") {
  if (compareDate($start_date, $end_date) > 0) {
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
  }
}

// Get prebill update information from ccds.billing_change
$stmt = "select customer_name, invoice_num, invoice_date, vessel ||' '||voyage, pallet_id, bol, exporter,
			claim_qty, claim_price, claim_amt, port_qty, port_amt, denied_qty, denied_amt,
			ship_line_qty, ship_line_amt, claim_type, b.status, h.claim_id, b.claim_body_id, ispercentage, prod, h.entry_date
		from $claim_header h, $claim_body b, customer_profile c
		where h.claim_id = b.claim_id 
			and h.customer_id = c.customer_id 
			and (h.status is null or h.status <>'D') 
			and (b.status is null or b.status <>'D') ";

if ($customer_id != "") {
  $stmt .= " and h.customer_id = $customer_id ";
}

if($claim_type != ""){
  $stmt .= " and claim_type = '$claim_type'";
}

if ($ispct<>"none"){
	if($ispct == "Y"){
		$stmt .= " and ispercentage = 'Y' and claim_cargo_type = 'CHILEAN' and vessel != 'TRUCK'";
	}elseif($ispct == "N"){
		$stmt .= " and (ispercentage is null or ispercentage <> 'Y') and claim_cargo_type = 'CHILEAN' and vessel != 'TRUCK'";
	}elseif($ispct == "CHILEANTRUCK"){
		$stmt .= " and (ispercentage is null or ispercentage <> 'Y') and claim_cargo_type = 'CHILEAN' and vessel = 'TRUCK'";
	}else {
		$stmt .= " and claim_cargo_type = '".$ispct."' ";
	}
}

if ($status <> ""){
   $stmt .= " and h.status = '$status' and b.status = '$status' ";  
}

if ($start_date != "") {
    $stmt .= " and invoice_date  >= to_date('$start_date','mm/dd/yyyy') ";
}

if ($end_date != "") {
    $stmt .= " and invoice_date <= to_date('$end_date','mm/dd/yyyy')";
}

if ($season != ""){
	$stmt .= " and h.season = $season ";
}

$stmt = $stmt . " order by " . $order_by;

//echo $stmt;
//exit;
$statement = ora_parse($cursor, $stmt);
ora_exec($cursor);

$tot_claim_qty = 0;
$tot_claim_amt = 0;
$tot_port_qty = 0;
$tot_port_amt = 0;
$tot_denied_qty = 0;
$tot_denied_amt = 0;
$tot_ship_line_qty = 0;
$tot_ship_line_amt = 0;

$data = array();
$general_info = array();

while(ora_fetch($cursor)){
		$cust = ora_getcolumn($cursor,0);
        $invoice_num = ora_getcolumn($cursor,1);
        $invoice_date = ora_getcolumn($cursor,2);
        $ves = ora_getcolumn($cursor,3);
        $pallet_id = ora_getcolumn($cursor,4);
        $bol = ora_getcolumn($cursor,5);
        $exporter = ora_getcolumn($cursor,6);
        $claim_qty = ora_getcolumn($cursor,7);
        $claim_price = ora_getcolumn($cursor,8);
        $claim_amt = ora_getcolumn($cursor,9);
        $port_qty = ora_getcolumn($cursor,10);
        $port_amt = ora_getcolumn($cursor,11);
        $denied_qty = ora_getcolumn($cursor,12);
        $denied_amt = ora_getcolumn($cursor,13);
        $ship_line_qty = ora_getcolumn($cursor,14);
        $ship_line_amt = ora_getcolumn($cursor,15);
        $claim_type = ora_getcolumn($cursor,16);
        $status = ora_getcolumn($cursor,17);
		$claim_id = ora_getcolumn($cursor,18);
        $claim_body_id = ora_getcolumn($cursor,19);
        $ispct2 = ora_getcolumn($cursor,20);
		$product = ora_getcolumn($cursor,21);

		$invoice_date = date('m/d/y', strtotime($invoice_date));
        list($junk, $temp_cust) = split("-", $cust);
    	if($temp_cust != "")
      	$cust = $temp_cust;
    	// Max of 10 Characters
    	$cust = substr($cust, 0, 10);

        if ($claim_type =="Warehouse Damage"){
		$claim_type = "W-Damage";
	}else if ($claim_type == "Mis-Shipment"){
		$claim_type = "M-Ship";
	}


	$tot_claim_qty += $claim_qty;
	$tot_claim_amt += $claim_amt;
        $tot_port_qty += $port_qty;
        $tot_port_amt += $port_amt;
        $tot_denied_qty += $denied_qty;
        $tot_denied_amt += $denied_amt;
        $tot_ship_line_qty += $ship_line_qty;
        $tot_ship_line_amt += $ship_line_amt;


	array_push($data, array('cust'=>$cust, 
				'inv_num'=>$invoice_num,
				'inv_date'=>$invoice_date,
				'ves'=>$ves,
				'pallet_id'=>$pallet_id,
				'bol'=>$bol,
				'exporter'=>$exporter,
				'claim_qty'=>$claim_qty,	
				'claim_price'=>'$'.number_format($claim_price,4,'.',''),
				'claim_amt'=>'$'.number_format($claim_amt,2,'.',''),
				'port_qty'=>$port_qty,
				'port_amt'=>'$'.number_format($port_amt,2,'.',''),
				'denied_qty'=>$denied_qty,
				'denied_amt'=>'$'.number_format($denied_amt,2,'.',''),
				'ship_line_qty'=>$ship_line_qty,
				'ship_line_amt'=>'$'.number_format($ship_line_amt,2,'.',''),
				'claim_type'=>$claim_type,
				'ispct'=>$ispct2,
				'prod'=>$product,
				'status'=>$status,	
				'url'=>"http://$hostname/claims/fruit_claim/claim_tracker.php?claim_id=$claim_id",
				'url2'=>"http://$hostname/claims/fruit_claim/claim_tracker.php?claim_id=$claim_id&claim_body_id=$claim_body_id"));
			
}
//echo "array_filled;";
//exit;

array_push($data, array('claim_qty'=>$tot_claim_qty,
			'claim_amt'=>'$'.number_format($tot_claim_amt,2,'.',''),
 	              	'port_qty'=>$tot_port_qty,
               	        'port_amt'=>'$'.number_format($tot_port_amt,2,'.',''),
                        'denied_qty'=>$tot_denied_qty,
    	                'denied_amt'=>'$'.number_format($tot_denied_amt,2,'.',''),
            	        'ship_line_qty'=>$tot_ship_line_qty,
                       	'ship_line_amt'=>'$'.number_format($tot_ship_line_amt,2,'.','')));
//echo "array_filled<br>";
//exit;


// Code to generate PDF file of the report

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf -> ezSetMargins(20,60,10,10);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

$pdf->ezStartPageNumbers(720, 32, 9, '','',1);

switch($ispct){
	case 'Y':
//		echo "Y".$ispct;
		$pdf->ezText($season." POW 68% Internal Claim Report", 16, $center);
		break;
	case 'N':
//		echo "N".$ispct;
		$pdf->ezText($season." POW 100% Internal Claim Report", 16, $center);
		break;
	case 'none':
//		echo "N".$ispct;
		$pdf->ezText($season." POW 100% Internal Claim Report", 16, $center);
		break;
	default:
//		echo "none".$ispct;
		$pdf->ezText($season." POW Internal Claim Report - ".$ispct, 16, $center);
		break;
}

//echo "isPCT check completed";
//exit;


//$pdf->ezText("\n", 10, $center);

if ($report_customer_name == "") {
  $report_customer_name = "Not Specified";
}
 

if ($start_date == "" && $end_date == "") {
  $change_date = "Not Specified";
} elseif ($start_date != "" && $end_date != "") {
  $change_date = $start_date . " - " . $end_date;
} elseif ($start_date != "") {
  $change_date = "Since $start_date";
} else {
  $change_date = "Up to $end_date";
}
 
array_push($general_info, array('first'=>"Customer:  $report_customer_name", 
				'second'=>'', 
				'third'=>'',
				'fourth'=>'',
				'fifth'=>"Date of Claim: $change_date"));
//echo "Header completed";
//exit;

$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 'fifth'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'third'=>array('justification'=>'center'),
				      'fifth'=>array('justification'=>'right')),
			'shaded'=>0, 'showLines'=>0, 'fontSize'=>11, 'width'=>760));


$pdf->ezSetDy(-15);
    // Push a totals line
    $arrHeading = array('cust'=>'Customer',
                     	'inv_num'=>'Invoice#',
					  	'inv_date'=>'Date',
               	        'ves'=>'Vessel',
     	                'pallet_id'=>'Pallet Id',
    	            	'exporter'=>'Exporter',
              	        'prod'=>'Commodity',
       	                'claim_qty'=>'Claim Qty',
              	        'claim_price'=>'Claim Price',
       	                'claim_amt'=>'Claim Amount',
                      	'port_qty'=>'Port Qty',
             	        'port_amt'=>'Port Amount',
      	                'denied_qty'=>'Denied Qty',
                      	'denied_amt'=>'Denied Amount',
             	        'ship_line_qty'=>'S-Line Qty',
     	                'ship_line_amt'=>'S-Line Amount',
                    	'claim_type'=>'Claim Type',
						'ispct'=>'Is %',
                  		'status'=>'St');
/*
    $arrCol   =    array('cust'=>array('width'=>60, 'justification'=>'left'),
                        'inv_num'=>array('width'=>45, 'justification'=>'left'),
                        'inv_date'=>array('width'=>42, 'justification'=>'left'),
                        'ves'=>array('justification'=>'left'),
                        'pallet_id'=>array('width'=>100, 'justification'=>'center'),
                        'exporter'=>array('justification'=>'left'),
                        'prod'=>array('justification'=>'left'),
                        'claim_qty'=>array('width'=>31, 'justification'=>'center'),
                        'claim_price'=>array('width'=>45, 'justification'=>'center'),
                        'claim_amt'=>array('width'=>45, 'justification'=>'right'),
                        'port_qty'=>array('width'=>25, 'justification'=>'center'),
                        'port_amt'=>array('width'=>45, 'justification'=>'right'),
                        'denied_qty'=>array('width'=>36, 'justification'=>'center'),
                        'denied_amt'=>array('width'=>45, 'justification'=>'right'),
                        'ship_line_qty'=>array('width'=>36, 'justification'=>'center'),
                        'ship_line_amt'=>array('width'=>46, 'justification'=>'right'),
                        'claim_type'=>array('width'=>55, 'justification'=>'center'),
                        'ispct'=>array('width'=>20, 'justification'=>'center'),
                        'status'=>array('width'=>18, 'justification'=>'left'),
						'inv_num'=>array('link'=>'url'),
                        'pallet_id'=>array('link'=>'url2'));
*/

    $arrCol   =    array('cust'=>array('width'=>60, 'justification'=>'left'),
                        'inv_num'=>array('width'=>45, 'justification'=>'left', 'link'=>'url'),
                        'inv_date'=>array('width'=>42, 'justification'=>'left'),
                        'ves'=>array('justification'=>'left'),
                        'pallet_id'=>array('width'=>70, 'justification'=>'center', 'link'=>'url2'),
                        'exporter'=>array('width'=>30, 'justification'=>'left'),
                        'prod'=>array('justification'=>'left'),
                        'claim_qty'=>array('width'=>31, 'justification'=>'center'),
                        'claim_price'=>array('width'=>45, 'justification'=>'center'),
                        'claim_amt'=>array('width'=>45, 'justification'=>'right'),
                        'port_qty'=>array('width'=>25, 'justification'=>'center'),
                        'port_amt'=>array('width'=>45, 'justification'=>'right'),
                        'denied_qty'=>array('width'=>36, 'justification'=>'center'),
                        'denied_amt'=>array('width'=>45, 'justification'=>'right'),
                        'ship_line_qty'=>array('width'=>36, 'justification'=>'center'),
                        'ship_line_amt'=>array('width'=>46, 'justification'=>'right'),
                        'claim_type'=>array('width'=>55, 'justification'=>'center'),
                        'ispct'=>array('width'=>20, 'justification'=>'center'),
                        'status'=>array('width'=>18, 'justification'=>'left'));

/*
for($i = 0; $i < sizeof($data); $i++){
	print_r($data[$i]);
	echo "<br><br>";
}
exit;
*/
   $pdf->ezTable($data, $arrHeading, '',array('showHeadings'=>1, 'shaded'=>0, 'width'=>775, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrCol));

//echo "Table completed";
//exit;

// footer
$text = "Port of Wilmington, printed by $user, $timestamp";
$pdf->line(28,40,754,40);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(28,950,754,950);
$pdf->addText(40,34,6, $text);

//echo "PDF compiled";
//exit;

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");
?>
