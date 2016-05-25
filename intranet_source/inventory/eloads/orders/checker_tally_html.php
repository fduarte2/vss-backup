<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "E-Loads - Checker Tally";
  $area_type = "ELOA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  $today = date('m/d/Y H:i:s');

  $user = $userdata['username'];
?>
<script type="text/javascript">
  function refresh(){
    document.location.href="checker_tally_html.php?order_num=<?= $order_num ?>";
  }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Checker Tally
            </font>
           <hr><? include("../eload_links.php"); ?>
      </td>
   </tr>
</table>

<table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
               <td width="5%">&nbsp;</td>
               <td width="30%" align="right" valign="top">
                  <font size="2" face="Verdana">Order Number:</font></td>
               <td width="45%" align="left">
               <form name="order_form" method="Get" action="checker_tally_html.php" onsubmit="return validate_mod()">
                  <input type="textbox" name="order_num" size="10" maxlength="10" value="<?= $order_num ?>">
               </td>
               <td width="20%">&nbsp;</td>
            </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
               <td align="left">
                  <input type="submit" value="View">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset">
              </td>
              </form>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td colspan="4">&nbsp;</td>
            </tr>
         </table>

<?
  include("../eloads_globals.php");
  $conn = ora_logon(RF, PASS);
  $cursor1 = ora_open($conn);
  $cursor2 = ora_open($conn);

  $stmt = "select to_char(A.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_OF_ACTIVITY, QTY_CHANGE, QTY_LEFT, SERVICE_CODE, PALLET_ID, ACTIVITY_ID, ARRIVAL_NUM, C.CUSTOMER_NAME from cargo_activity A, customer_profile C where A.customer_id = C.customer_id and order_num like '%$order_num%' order by order_num, date_of_activity";

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);
ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CARGO_TRACKING and 
	  CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

if($rows1 == 0){
  // no records have a similar order number
?>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <font size = "2" face="Verdana">
                  No orders having an order number like <b><?= $order_num ?></b> were shipped out of the Port of Wilmington! 
                  <br /><br />Please <a href="./">go back</a> and re-enter the order number you would like to see.
                  </font>
                  <br /><br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Visited by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
<?
   include("pow_footer.php");
   exit;
}

$customer_name = $row1['CUSTOMER_NAME'];
$date_of_activity = date('m/d/Y', strtotime($row1['DATE_OF_ACTIVITY']));
$employee = $row1['ACTIVITY_ID'];
$arrival_num = $row1['ARRIVAL_NUM'];
$service_code = $row1['SERVICE_CODE'];

// Set the checker name
$per_stmt = "select LOGIN_ID from per_owner.personnel where employee_id = '$employee'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From PER_OWNER.PERSONNEL. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $checker = $row3['LOGIN_ID'];
}
else{
  $checker = "UNKNOWN";
}

// Get the Vessel name
$per_stmt = "select VESSEL_NAME from vessel_profile where arrival_num = '$arrival_num'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From VESSEL_PROFILE. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $vessel_name = $row3['VESSEL_NAME'];
}
else{
  $vessel_name = "UNKNOWN";
}
if($vessel_name == "")
  $vessel_name = "UNKNOWN";

// Get the Shipment name
$per_stmt = "select SERVICE_NAME from service_category where service_code = '$service_code'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From SERVICE_CATEGORY. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $shipment = $row3['SERVICE_NAME'];
}
else{
  $shipment = "UNKNOWN";
}

$general_info = array();
$lot_info = array();
$damage_info = array();

$start_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));

// general information
?>

<a href="checker_tally.php?order_num=<?= $order_num ?>"><img src="images/print-icon.gif" border="0">Print Format</a><br /><br />
  <table bgcolor="#f0f0f0" bordercolor="black" width="100%" border="2" cellpadding="4" cellspacing="1">
   <th>Customer</th><th>Order Number</th><th>Date</th><th>Checker</th><th>Shipment</th>
     <tr><td><?= $customer_name ?></td><td><?= $order_num ?></td><td><?= $date_of_activity ?></td><td><?= $checker ?></td><td><?= $shipment ?></td></tr>
</table>
<br /><br />
<?
$pallets = 0;
$ctns = 0;

// process one fetched record at a time
do {
  $pallets++;
  $end_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));
  // Get some information about the pallet
  $pallet_id = $row1['PALLET_ID'];
  $qty = $row1['QTY_CHANGE'];
  $ctns += $qty;

  if($eport_customer_id != 0){
    $stmt2 = "select MARK, CARGO_DESCRIPTION, C.COMMODITY_NAME, V.VESSEL_NAME from cargo_tracking T, commodity_profile C, vessel_profile V where T.arrival_num = V.arrival_num and T.commodity_code = C.commodity_code and pallet_id = '$pallet_id' and receiver_id = '$eport_customer_id'";
  }
  else{
    $stmt2 = "select MARK, CARGO_DESCRIPTION, C.COMMODITY_NAME from cargo_tracking T, commodity_profile C where T.commodity_code = C.commodity_code and pallet_id = '$pallet_id'";
  }
  $ora_success = ora_parse($cursor2, $stmt2);
  $ora_success = ora_exec($cursor2);

  if (!$ora_success){
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From CARGO_TACKING. Please Try Again Later.");
    exit;
  }
  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  // add the line item
  array_push($lot_info, array('mark'=>$row2['MARK'], 'desc'=>$row2['CARGO_DESCRIPTION'], 'comm'=>$row2['COMMODITY_NAME'], 
			      'pallet'=>$row1['PALLET_ID'], 'qty'=>$row1['QTY_CHANGE'], 'qty_left'=>$row2['VESSEL_NAME'],
			      'comments'=>$row1['ACTIVITY_DESCRIPTION'],
			      'time'=>$row1['DATE_OF_ACTIVITY']));

} while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)); 

array_push($lot_info, array('mark'=>"Total:", 'desc'=>'', 'comm'=>'', 
			    'pallet'=>$pallets, 'qty'=>$ctns, 'qty_left'=>'',
			    'comments'=>'',
			    'time'=>''));

?>
Start Time: <?= $start_time ?> - End Time: <?= $end_time ?><br /><br />
  <table bgcolor="#f0f0f0" bordercolor="black" width="100%" border="2" cellpadding="4" cellspacing="1">
   <th>Lot/Mark</th><th>Desc</th><th>Commodity</th><th>Pallet</th><th>Qty</th><th>Qty Left</th><th>Comments</th><th>Scan Time</th>
<? 
foreach ($lot_info as $key => $value) {
   //echo "Key: $key; Value: $value<br />\n";
   echo "<tr>";
   foreach ($value as $key2 => $value2){
     if($value2 == "Total:")
       $last_line = 1;
     //echo "Key2: $key2; Value2: $value2<br />\n";
     if($value2 == "")
       $value2 = "&nbsp;";
     if($key2 == "pallet" && $last_line != 1)
       echo "<td><a href=\"../loads/tag_audit.php?pallet_id=$value2\">$value2</a></td>";
     else
       echo "<td>$value2</td>";
   }
   echo "</tr>\n";
}
?>
</table>
<br /><br />
<font size="2" face="Verdana" color="#0066CC"><a href="javascript:void(0);" onclick="refresh()"><img src="images/search.gif" border="0"> Refresh</a><br /><br />Last Update: <?= date('m/d/y H:i:s') ?><br /></font>

<? include("pow_footer.php"); ?>
