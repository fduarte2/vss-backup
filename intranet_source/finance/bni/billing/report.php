<?
// Developer:   Lynn F. Wang
//
// Description: This page creates a PDF file that shows prebill update information of selected customer(s), 
//              billing type(s), and/or a specific update date range.
// History:     Created on 10/17/2003

// check if it is an authenticated user
include("pow_session.php");
$user = $userdata['username'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

// include the date comparing function
include("compareDate.php");

// To be used to eliminate trailing zeros
$trans = array(".00"=>"");

// make Oracle connection
$ora_conn = ora_logon("SAG_OWNER@$bni", "SAG");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor = ora_open($ora_conn);
if (!$cursor) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor));
  exit;
}		

$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}		

// make postgreSQL connection
$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
}

$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$customer = trim($HTTP_POST_VARS["customer"]);
list($customer_id, $customer_name) = split(",", $customer);
$billing_type = trim($HTTP_POST_VARS["billing_type"]);
$order_by = trim($HTTP_POST_VARS["order_by"]);
$start_date = trim($HTTP_POST_VARS["start_date"]);
$end_date = trim($HTTP_POST_VARS["end_date"]);

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
$stmt = "select * from billing_change";

if ($customer_id != "") {
  $stmt = $stmt . " where customer_id = $customer_id";
}

if ($billing_type != "") {
  if (strpos($stmt, "where") === false) {
    $stmt = $stmt . " where billing_type = '$billing_type'";
  } else {
    $stmt = $stmt . " and billing_type = '$billing_type'";
  } 
}

if ($start_date != "") {
  if (strpos($stmt, "where") === false) {
    $stmt = $stmt . " where change_time >= '$start_date'";
  } else {
    $stmt = $stmt . " and change_time >= '$start_date'";
  } 
}

if ($end_date != "") {
  if (strpos($stmt, "where") === false) {
    $stmt = $stmt . " where change_time <= '$end_date'";
  } else {
    $stmt = $stmt . " and change_time <= '$end_date'";
  } 
}

$stmt = $stmt . " order by " . $order_by;

$result = pg_query($pg_conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($pg_conn));
$pg_rows = pg_num_rows($result);

$change_info = array();
$general_info = array();

// has changes in postgreSQL table billing_change that match with the search criteria.
if ($pg_rows > 0) {
  // fetch one row at a time
  for ($i=0; $i<$pg_rows; $i++) {
    $row = pg_fetch_array($result, $i, PGSQL_ASSOC);

    list($date_part, $the_rest) = split(" ", $row['change_time'], 2);
    $change_date = date('m/d/y', strtotime($date_part));

    list($first_name, $the_rest) = split(" ", $row['username'], 2);
    list($action, $the_rest) = split(" ", $row['action'], 2);

    if ($row['service_date'] != "") {
      $service_date = date('m/d/y', strtotime($row['service_date']));
    }

    array_push($change_info, array('date'=>$change_date, 'user'=>$first_name, 'action'=>$action, 
				   'billing_num'=>$row['billing_num'], 'billing_type'=>$row['billing_type'], 
				   'cust'=>$row['customer_id'], 'lr_num'=>$row['lr_num'], 
				   'src_date'=>$service_date, 'qty'=>strtr($row['service_qty'], $trans), 
				   'rate'=>strtr($row['service_rate'], $trans), 
				   'amount'=>strtr($row['service_amount'], $trans), 
				   'unit'=>$row['service_unit'], 'reason'=>$row['change_reason']));
  }
}

// Get prebill update information from bni.edit_delete_bills
$stmt = "select * from edit_delete_bills";

if ($customer_id != "") {
  $stmt = $stmt . " where new_customer_id = $customer_id";
}

if ($start_date != "") {
  if (strpos($stmt, "where") === false) {
    $stmt = $stmt . " where date_of_change >= to_date('$start_date', 'MM/DD/YYYY')";
  } else {
    $stmt = $stmt . " and date_of_change >= to_date('$start_date', 'MM/DD/YYYY')";
  }
}

if ($end_date != "") {
  if (strpos($stmt, "where") === false) {
    $stmt = $stmt . " where date_of_change <= to_date('$end_date', 'MM/DD/YYYY')";
  } else {
    $stmt = $stmt . " and date_of_change <= to_date('$end_date', 'MM/DD/YYYY')";
  }
}

// order-by for Oracle 
switch ($order_by) {
  case "change_time desc":
    $ora_order_by = "date_of_change desc";
    break;

  case "action asc, billing_type asc, billing_num desc":
    $ora_order_by = "action asc, billing_num desc";
    break;
  
  case "billing_num desc, change_time desc":
    $ora_order_by = "billing_num desc, date_of_change desc";
    break;
 
  case "customer_id asc, billing_type asc, change_time desc":
    $ora_order_by = "new_customer_id asc, date_of_change desc";
    break;
    
  default:
    $ora_order_by = "date_of_change desc";
    break;
}

$stmt = $stmt . " order by " . $ora_order_by;

$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);

while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $change_date = date('m/d/y', strtotime($row['DATE_OF_CHANGE']));
  $action = ($row['ACTION'] == 'E' ? 'Edit' : 'Delete');

  // get billing info
  $stmt = "select * from billing where billing_num = " . $row['BILLING_NUM'] . 
     " and customer_id = " . $row['NEW_CUSTOMER_ID'];
  $ora_success = ora_parse($cursor1, $stmt);
  $ora_success = ora_exec($cursor1);
  ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $ora_rows1 = ora_numrows($cursor1);

  // use the billing info if we have it
  if ($ora_rows1 > 0) {
    // change the date format
    if ($row1['SERVICE_DATE'] != "") {
      $service_date = date('m/d/y', strtotime($row1['SERVICE_DATE']));
    }

    array_push($change_info, array('date'=>$change_date, 'user'=>$row['INITIALS'], 'action'=>$action, 
				   'billing_num'=>$row['BILLING_NUM'], 'billing_type'=>$row1['BILLING_TYPE'], 
				   'cust'=>$row['NEW_CUSTOMER_ID'], 'lr_num'=>$row1['LR_NUM'], 
				   'src_date'=>$service_date, 'qty'=>$row1['SERVICE_QTY'], 
				   'rate'=>$row1['SERVICE_RATE'], 'amount'=>$row1['SERVICE_AMOUNT'], 
				   'unit'=>$row1['SERVICE_UNIT'], 'reason'=>$row['COMMENTS']));

  } else {
    array_push($change_info, array('date'=>$change_date, 'user'=>$row['INITIALS'], 'action'=>$action, 
				   'billing_num'=>$row['BILLING_NUM'], 'billing_type'=>'',
				   'cust'=>$row['NEW_CUSTOMER_ID'], 'lr_num'=>'', 'src_date'=>'', 'qty'=>'',
				   'rate'=>'', 'amount'=>'', 'unit'=>'', 'reason'=>$row['COMMENTS']));
  }
}

$ora_rows = ora_numrows($cursor);
$rows = $pg_rows + $ora_rows;

// close database connections
pg_close($pg_conn);
ora_close($cursor);
ora_close($cursor1);
ora_logoff($ora_conn);

// no records message
if ($rows == 0) {
  // no records retrieved
?>

<html>
<head>
<title>BNI - Prebill Update Report</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">Prebill Update Report</font>
	          <br />
	          <hr>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <font size = "2" face="Verdana">
                  As Of <?= $timestamp ?>, there is no prebill updating information that matches your search 
                  criteria. <br />Please modify your search criteria and try it again.
		  <br /><br />
		  <hr>
		  Port of Wilmington, Printed by <?= $user ?>
                  </font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>

<?
   exit;
}

// Code to generate PDF file of the report

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

// write out the intro. for the 1st page
$pdf->ezText("Prebill Update Report", 16, $center);
//$pdf->ezText("\n", 10, $center);

if ($customer_name == "") {
  $customer_name = "Not Specified";
}
 
if ($billing_type == "") {
  $billing_type = "Not Specified";
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
 
array_push($general_info, array('first'=>"Customer:  $customer_name", 
				'second'=>'', 
				'third'=>"Billing Type:  $billing_type",
				'fourth'=>'',
				'fifth'=>"Date of Change: $change_date"));

$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 'fifth'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'third'=>array('justification'=>'center'),
				      'fifth'=>array('justification'=>'right')),
			'shaded'=>0, 'showLines'=>0, 'fontSize'=>11, 'width'=>725));


$pdf->ezSetDy(-15);

switch ($order_by) {
 case "change_time desc":
   $pdf->ezTable($change_info, array('date'=>'Change Date', 'user'=>'User', 'action'=>'Action', 
				     'billing_num'=>'Billing #', 'billing_type'=>'Billing Type', 
				     'cust'=>'Customer', 'lr_num'=>'Vessel #', 'src_date'=>'Srv Date', 
				     'qty'=>'QTY', 'rate'=>'Rate', 'amount'=>'Amount', 'unit'=>'Unit', 
				     'reason'=>'Comment'), 
		 '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			   'cols'=>array('cust'=>array('justification'=>'left'))));
   break;

 case "action asc, billing_type asc, billing_num desc":
   $pdf->ezTable($change_info, array('action'=>'Action', 'billing_type'=>'Billing Type', 
				     'billing_num'=>'Billing #', 'date'=>'Change Date', 'user'=>'User', 
				     'cust'=>'Customer', 'lr_num'=>'Vessel #', 'src_date'=>'Srv Date', 
				     'qty'=>'QTY', 'rate'=>'Rate', 'amount'=>'Amount', 'unit'=>'Unit', 
				     'reason'=>'Comment'), 
		 '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			   'cols'=>array('cust'=>array('justification'=>'left'))));
   break;

 case "billing_num desc, change_time desc":
   $pdf->ezTable($change_info, array('billing_num'=>'Billing #', 'date'=>'Change Date', 'action'=>'Action', 
				     'billing_type'=>'Billing Type', 'user'=>'User', 
				     'cust'=>'Customer', 'lr_num'=>'Vessel #', 'src_date'=>'Srv Date', 
				     'qty'=>'QTY', 'rate'=>'Rate', 'amount'=>'Amount', 'unit'=>'Unit', 
				     'reason'=>'Comment'), 
		 '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			   'cols'=>array('cust'=>array('justification'=>'left'))));
   break;

 case "customer_id asc, billing_type asc, change_time desc":
   $pdf->ezTable($change_info, array('cust'=>'Customer', 'billing_type'=>'Billing Type', 'date'=>'Change Date', 
				     'action'=>'Action', 'billing_num'=>'Billing #', 'user'=>'User', 
				     'lr_num'=>'Vessel #', 'src_date'=>'Srv Date', 
				     'qty'=>'QTY', 'rate'=>'Rate', 'amount'=>'Amount', 'unit'=>'Unit', 
				     'reason'=>'Comment'), 
		 '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			   'cols'=>array('cust'=>array('justification'=>'left'))));
   break;
   
 default:
   $pdf->ezTable($change_info, array('date'=>'Change Date', 'user'=>'User', 'action'=>'Action', 
				     'billing_num'=>'Billing #', 'billing_type'=>'Billing Type', 
				     'cust'=>'Customer', 'lr_num'=>'Vessel #', 'src_date'=>'Srv Date', 
				     'qty'=>'QTY', 'rate'=>'Rate', 'amount'=>'Amount', 'unit'=>'Unit', 
				     'reason'=>'Comment'), 
		 '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			   'cols'=>array('cust'=>array('justification'=>'left'))));
   break;
}

// footer
$text = "Port of Wilmington, printed by $user, $timestamp";
$pdf->line(28,40,754,40);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(28,950,754,950);
$pdf->addText(40,34,6, $text);

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");

?>
