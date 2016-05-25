<?
// 05/14/03 - Created by Lynn F. Wang
// - Then Changed for RF on 28-OCT-03 - STM
// Description: Creates a table show lots' information pulled from RF.CCD_CARGO_TRACKING for a specific
// ship, and/or mark, and/or a specific customer.  

// check if it is an authenticated user
   $user = $HTTP_COOKIE_VARS[financeuser];
   if($user == ""){
      header("Location: ../../finance_login.php");
      exit;
   }

// get cookie and form values ($ship, $condense and $mark)
$eport_customer_id = 0;

$today = date("F j, Y, g:i A");

// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}		

$cursor2 = ora_open($ora_conn);
if (!$cursor2) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor2));
  exit;
}		

// if user entered mark, we will generate PDF report for this mark
if ($order_num != "") {
  include("checker_tally.php");
  exit;
}

// get infomation from RF.CCD_CARGO_TRACKING
if ($eport_customer_id != 0)
{
  $stmt = "select T.pallet_id, CM.commodity_name commodity, C.customer_name customer, 
	          T.arrival_num, T.qty_received, T.qty_in_house, T.warehouse_location, T.cargo_description, T.mark,
                  A.activity_num, A.order_num, to_char(a.date_of_activity, 'MM/DD/YY HH24:MI:SS') date_of_activity,
                  A.qty_left, A.qty_change, SC.service_name
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM, CARGO_ACTIVITY A, SERVICE_CATEGORY SC
           where A.customer_id = $eport_customer_id and A.date_of_activity > to_date('$date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and A.date_of_activity < to_date('$date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and A.customer_id = C.customer_id 
                  and A.pallet_id = T.pallet_id
                  and A.customer_id = T.receiver_id
                  and A.service_code = SC.service_code
                  and T.commodity_code = CM.commodity_code
	   order by date_of_activity desc";
} else {
  $stmt = "select T.pallet_id, CM.commodity_name commodity, C.customer_name customer, 
	          T.arrival_num, T.qty_received, T.qty_in_house, T.warehouse_location, T.cargo_description, T.mark,
                  A.activity_num, A.order_num, to_char(a.date_of_activity, 'MM/DD/YY HH24:MI:SS') date_of_activity,
                  A.qty_left, A.qty_change, SC.service_name
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM, CARGO_ACTIVITY A, SERVICE_CATEGORY SC
           where A.date_of_activity > to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and A.date_of_activity < to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and A.customer_id = C.customer_id 
                  and A.pallet_id = T.pallet_id
                  and A.customer_id = T.receiver_id
                  and A.service_code = SC.service_code
                  and A.service_code = '12'
                  and T.commodity_code = CM.commodity_code
	   order by date_of_activity desc";
}

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

$date = "$start_date to $end_date";

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

// initialize running totals
$pallets_total = 0;

?>

<html>
<head>
<meta http-equiv="Refresh" content="500">
<title>Eport - Void Activity Report</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
	          </font>
	          <br />
	          <hr color="green">
	          <br />
                  <font size = "3" face="Verdana" color="#0066CC">Activity For: <?= $date ?></font>
	          <br /><br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
		  <?
		  if ($rows1 == 0) {
		    if ($eport_customer_id == 0) {
		  ?>		      
		  <font size = "2" face="Verdana">No customers have Void Activity for <?= $date ?>!  
		  Please go back to select another date to view Activity Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?> Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>
		  <?
		    } else {
		  ?>
		  <font size = "2" face="Verdana"><?= $user ?> does not have any Void Activity for <?= $date ?>!  
		  Please go back to select another date to view Activity Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>

		  <?
		    }
		    exit;
		  } // rows == 0
                  ?>
		  <?
		  if ($eport_customer_id != 0) {
		  ?>
		     <caption align="center">
		       <font size = "3" face="Verdana" color="#0066CC">Customer: <?= $row1['CUSTOMER']  ?></font>
		     </caption>
		  <?
		  }
                  ?>
		  
		  <table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
		     <tr>
			<th onmouseover="return overlib('Lot/Mark ID Given by the customer to identify this pallet group.', CAPTION, 'Lot/Mark');" onmouseout="return nd();"><font size = "2" face="Verdana">Lot/Mark</font></th>
			<th onmouseover="return overlib('Commodity Name of the product on the pallet', CAPTION, 'Commodity');" onmouseout="return nd();"><font size = "2" face="Verdana">Comm</font></th>
			<th nowrap onmouseover="return overlib('Pallet ID of a specific Pallet', CAPTION, 'Pallet ID');" onmouseout="return nd();"><font size = "2" face="Verdana">Pallet ID</font></th>
			<th nowrap onmouseover="return overlib('Description of the Pallet', CAPTION, 'Description');" onmouseout="return nd();"><font size = "2"
face="Verdana">Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></th>
		  <?
		     if ($eport_customer_id == 0) {
		  ?>
		        <th onmouseover="return overlib('Receiver of specific Pallet', CAPTION, 'Receiver');" onmouseout="return nd();"><font size = "2" face="Verdana">Receiver</font></th>
		  <?
		     }
		  ?>
			<th onmouseover="return overlib('Ship the Pallet came in on.', CAPTION, 'Arrival Number');" onmouseout="return nd();"><font size = "2" face="Verdana">Arrival Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></th>
	                <th onmouseover="return overlib('Order Number this Activity was based on', CAPTION, 'Order Number');" onmouseout="return nd();"><font size = "2" face="Verdana">Order #</font></th>
	                <th onmouseover="return overlib('Quantity originally received at the Port', CAPTION, 'Quantity Received');" onmouseout="return nd();"><font size = "2" face="Verdana">Qty Rec</font></th>
	                <th onmouseover="return overlib('Quantity that was used on this Activity', CAPTION, 'Quantity Used');" onmouseout="return nd();"><font size = "2" face="Verdana">Qty Used</font></th>
	                <th onmouseover="return overlib('Service type that took place on this pallet', CAPTION, 'Service Name');" onmouseout="return nd();"><font size = "2" face="Verdana">Service</font></th>
	                <th onmouseover="return overlib('Quantity remaining in Storage after this activity.', CAPTION, 'Quantity in House');" onmouseout="return nd();"><font size = "2" face="Verdana">Qty Left</font></th>
	                <th onmouseover="return overlib('Date / Time of Activity', CAPTION, 'Date Transaction took place.');" onmouseout="return nd();"><font size = "2" face="Verdana">Date</font></th>
		     </tr>
	       <?
		 do {
                   $pallets_total++;
                   $date_received = $row1['DATE_OF_ACTIVITY'];
	       ?>
	             <tr>
                        <td><font size = "1" face="Verdana"><?= $row1['MARK'] ?></font></td>
                        <td><font size = "1" face="Verdana"><?= $row1['COMMODITY'] ?></font></td>
	                <td><font size = "1" face="Verdana"><?= $row1['PALLET_ID'] ?></font></td>
	                <td><font size = "1" face="Verdana"><?= $row1['CARGO_DESCRIPTION'] ?></font></td>
		  <?
	              if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "1" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
		  <?
		      }
		  ?>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['ARRIVAL_NUM'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['ORDER_NUM'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['QTY_RECEIVED'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['QTY_CHANGE'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['SERVICE_NAME'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['QTY_LEFT'] ?></font></td>
	                <td nowrap><font size = "1" face="Verdana"><?= $date_received ?></font></td>
                     </tr>
	       <?
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       ?>

		     <tr>
                        <td><font size = "2" face="Verdana" color="#0066CC">Activity Total:</font></td>
			<td><font size = "2" face="Verdana" color="#0066CC"><?= $pallets_total ?></font></td>
		        <td colspan="1">&nbsp;</td>
                     </tr>
		  </table>
	          <br /><br />
	          <hr>
	          <font size = "2" face="Verdana">&copy;2003 Port of Wilmington, DE, Diamond State Port 
                  Corporation. All Rights Reserved.</font>
	       </td>
	    </tr>
         </table>
      </td>
   </tr>
</table>

</body>
</html>
