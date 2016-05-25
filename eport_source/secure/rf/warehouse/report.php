<?
// 05/14/03 - Created by Lynn F. Wang
// - Then Changed for RF on 28-OCT-03 - STM
// Description: Creates a table show lots' information pulled from RF.CCD_CARGO_TRACKING for a specific
// ship, and/or mark, and/or a specific customer.  

// check if it is an authenticated user
$user = $HTTP_COOKIE_VARS["eport_user"];
if ($user == "") {
  header("Location: ../../rf_login.php");
  exit;
}

// get cookie and form values ($ship, $condense and $mark)
$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
list($lr_num, $vessel_name) = split("-", $ship, 2);

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

// get infomation from RF.CCD_CARGO_TRACKING
if ($eport_customer_id != 0)
{
  if($lr_num == "x"){
    $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where T.receiver_id = C.customer_id 
                  and T.commodity_code = CM.commodity_code
                  and T.receiver_id = $eport_customer_id
                  and qty_in_house > 0
                  and date_received is not null
	   order by date_received desc";
   }
   else{
    $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where T.receiver_id = C.customer_id 
                  and T.commodity_code = CM.commodity_code
                  and T.receiver_id = $eport_customer_id
                  and T.arrival_num = '$lr_num'
                  and qty_in_house > 0
                  and date_received is not null
	   order by date_received desc";
   }
} else {
  if($lr_num == "x"){
    $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where T.receiver_id = C.customer_id 
                 and T.commodity_code = CM.commodity_code
                 and qty_in_house > 0
	   order by date_received desc";
  }
  else{
    $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where T.receiver_id = C.customer_id 
                 and T.commodity_code = CM.commodity_code
                 and qty_in_house > 0
                 and T.arrival_num = '$lr_num'
	   order by date_received desc";
  }
}

//echo "$stmt";
$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

$customer_name = $row1['CUSTOMER'];

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CARGO_TRACKING and 
	  CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

// initialize running totals
$qty_in_house_total = 0;
$pallets_total = 0;
$qty_received_total = 0;
$qty_damaged_total = 0;

?>

<html>
<head>
<meta http-equiv="Refresh" content="500">
<title>Eport - Inventory Report</title>
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
	          <br />
	          <hr color="green">
	          <br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <?
                    if($lr_num != "x"){
                  ?>
                  Inventory from <?= $vessel_name ?>
                  <?
                    }
                  ?>
		  <?
		  if ($rows1 == 0) {
		    if ($eport_customer_id == 0) {
		  ?>		      
		  <font size = "2" face="Verdana">No customers have cargo in inventory!</font>
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
		  <font size = "2" face="Verdana"><?= $user ?> does not have any cargo stored at the Port of Wilmington.
		  </font>
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
face="Verdana">Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></th>
		  <?
		     if ($eport_customer_id == 0) {
		  ?>
		        <th onmouseover="return overlib('Receiver of specific Pallet', CAPTION, 'Receiver');" onmouseout="return nd();"><font size = "2" face="Verdana">Receiver</font></th>
		        <th onmouseover="return overlib('Damaged cases on the pallet', CAPTION, 'Damages');" onmouseout="return nd();"><font size = "2" face="Verdana">Damages</font></th>
		  <?
		     }
		  ?>
			<th onmouseover="return overlib('Manifested carton count', CAPTION, 'Cartons Manifested');" onmouseout="return nd();"><font size = "2" face="Verdana">Manifested</font></th>
			<th onmouseover="return overlib('Cartons in Storage', CAPTION, 'Cartons in Storage');" onmouseout="return nd();"><font size = "2" face="Verdana">Qty in Storage</font></th>
			<th onmouseover="return overlib('Warehouse Location at the Port', CAPTION, 'Location');" onmouseout="return nd();"><font size = "2" face="Verdana">Location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></th>
	                <th onmouseover="return overlib('Actual date and time cargo scanned', CAPTION, 'Time Scanned');" onmouseout="return nd();"><font size = "2" face="Verdana">Date Received</font></th>
		     </tr>
	       <?
		 do {
                   $pallets_total++;
                   $qty_in_house = $row1['QTY_IN_HOUSE'];
                   $qty_in_house_total += $qty_in_house;
                   $qty_received = $row1['QTY_RECEIVED'];
                   $qty_received_total += $qty_received;
                   $date_received = $row1['DATE_RECEIVED'];
                   $qty_damaged = $row1['QTY_DAMAGED'];
                   $qty_damaged_total += $qty_damaged;
		   // This pallet was expected
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
                        <td><font size = "1" face="Verdana" color="red"><?= $qty_damaged ?></font></td>
		  <?
		      }
		  ?>
	                <td align="center"><font size = "1" face="Verdana"><?= $qty_received ?></font></td>
	                <td align="center"><font size = "1" face="Verdana"><?= $qty_in_house ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['WAREHOUSE_LOCATION'] ?></font></td>
	                <td nowrap><font size = "1" face="Verdana"><?= $date_received ?></font></td>
                     </tr>
	       <?
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       ?>

		     <tr>
                        <td><font size = "2" face="Verdana" color="#0066CC">Total:</td>
		     <?
		        if ($eport_customer_id != 0) {
		     ?>
		        <td colspan="2">&nbsp;</td>
		     <?
		        } else {
		     ?>
                        <td colspan="1">&nbsp;</td>
                        <td align="center"><font size = "2" face="Verdana" color="red">Damage Total<br />
			   <?= $qty_damaged_total ?></font></td>
		     <?
			}
                     ?>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Pallets Total<br />
			   <?= $pallets_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Manifested Total<br />
			   <?= $qty_received_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Stored Total<br />
			   <?= $qty_in_house_total ?></font></td>
		        <td align="center">&nbsp;</td>
		        <td align="center">&nbsp;</td>
		        <td align="center">&nbsp;</td>
			<td>&nbsp;</td>
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
