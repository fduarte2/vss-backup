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

// if user entered mark, we will generate PDF report for this mark
if ($eport_customer_id == 0 && $mark != "") {
  include("by_mark_report.php");
  exit;
}

// get infomation from RF.CCD_CARGO_TRACKING
if ($eport_customer_id != 0)
{
  $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where arrival_num = '$lr_num' and T.receiver_id = C.customer_id 
                  and T.commodity_code = CM.commodity_code
                  and T.receiver_id = $eport_customer_id
	   order by date_received desc";
} else {
  $stmt = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_received, qty_damaged, manifested,
  	          qty_in_house, hatch, deck, warehouse_location, cargo_description, mark
           from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
           where arrival_num = '$lr_num' and T.receiver_id = C.customer_id 
                  and T.commodity_code = CM.commodity_code
	   order by date_received desc";
}

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

// initialize running totals
$qty_expected_total = 0;
$qty_received_total = 0;
$qty_short_total = 0;
$qty_over_total = 0;
$qty_damaged_total = 0;
$pallets_total = 0;
$pallets_scanned = 0;

?>

<html>
<head>
<meta http-equiv="Refresh" content="500">
<title>Eport - Vessel Discharge Reports</title>
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
                  <?
                  // write out the intro and print the header
                  if ($condense == "On") {
		    printf("<br />Port of Wilmington Condensed Vessel Discharge Report");
		  } else {
		    printf("<br />Port of Wilmington Vessel Discharge Report");
		  }
                  ?>
	          </font>
	          <br />
	          <hr color="green">
	          <br />
                  <font size = "3" face="Verdana" color="#0066CC"><?= $lr_num ?> - <?= $vessel_name ?></font>
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
		  <font size = "2" face="Verdana">No customers have cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
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
		  <font size = "2" face="Verdana"><?= $user ?> does not have any cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
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
			<th onmouseover="return overlib('Manifested carton count', CAPTION, 'Cartons Expected');" onmouseout="return nd();"><font size = "2" face="Verdana">Exp</font></th>
			<th onmouseover="return overlib('Actual cartons received', CAPTION, 'Cartons Rcvd');" onmouseout="return nd();"><font size = "2" face="Verdana">Rcvd</font></th>
			<th onmouseover="return overlib('Cartons Received minus Cartons Expected', CAPTION, 'Cartons Short');" onmouseout="return nd();"><font size = "2" face="Verdana">Short</font></th>
			<th onmouseover="return overlib('Cartons Expected minus Cartons Received', CAPTION, 'Cartons Over');" onmouseout="return nd();"><font size = "2" face="Verdana">Over</font></th>
			<th onmouseover="return overlib('Deck / Hatch the pallet was received from', CAPTION, 'Deck / Hatch');" onmouseout="return nd();"><font size = "2"
face="Verdana">D/H</font></th>
			<th onmouseover="return overlib('Warehouse Location at the Port', CAPTION, 'Location');" onmouseout="return nd();"><font size = "2"
face="Verdana">Location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></th>
	                <th onmouseover="return overlib('Actual date and time cargo scanned', CAPTION, 'Time Scanned');" onmouseout="return nd();"><font size = "2" face="Verdana">Time Scanned</font></th>
		     </tr>
	       <?
		 do {
                 //  $stmt = "select exporter_code from email_manifest_psw where plt_id = '" . $row1['PALLET_ID'] . "'";
                 //  $ora_success = ora_parse($cursor2, $stmt);
                 //  $ora_success = ora_exec($cursor2);

                 //  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                 //  $rows2 = ora_numrows($cursor2);
                 //  if($rows2 > 0){
                 //    $exporter = $row2['EXPORTER_CODE'];
                 //  }
                 //  else{
                 //    $exporter = "";
                 //  }
                   $pallets_total++;
                   $qty_expected = 0;
                   $qty_short = "";
                   $qty_over = "";
                   $qty_received = $row1['QTY_RECEIVED'];
                   $date_received = $row1['DATE_RECEIVED'];
		   // This pallet was expected
                   if($row1['MANIFESTED'] == "Y"){
  		     $qty_expected_total += $row1['QTY_RECEIVED']; 
                     $qty_expected = $row1['QTY_RECEIVED'];
                   }
                   // Not Received
                   if($row1['DATE_RECEIVED'] == ""){
                     $date_received = "Not Scanned...";
                     $qty_received = 0;
                   }
                   else{
                     $qty_received_total += $row1['QTY_RECEIVED'];
                     $pallets_scanned++;
                   }
                   // Damages
		   $qty_damaged_total += $row1['QTY_DAMAGED'];
                   if($row1['QTY_DAMAGED'] == "0"){
                     $qty_damaged = "";
                   }
                   else{
                     $qty_damaged = $row1['QTY_DAMAGED'];
                   }
                   // Pallet was not expected
                   if($qty_received > $qty_expected){
                     $qty_over = $qty_received - $qty_expected;
                     $qty_over_total += $qty_over;
                   }
                   if($qty_received < $qty_expected){
                     $qty_short = $qty_expected - $qty_received;
		     $qty_short_total += $qty_short;
                   }
 
		   if ($condense == "On") {
		     if ($row1['DATE_RECEIVED'] == "" || $row1['MANIFESTED'] == "") {
	       ?>
		     <tr>
			<td><font size = "1" face="Verdana"><?= $row1['MARK'] ?></font></td>
			<td><font size = "1" face="Verdana"><?= $row1['COMMODITY'] ?></font></td>
                        <td><font size = "1" face="Verdana"><a href="../tag_audut.php?pallet_id=<?= $row1['PALLET_ID'] ?>><?= $row1['PALLET_ID'] ?></font></a></td>
	                <td><font size = "1" face="Verdana"><?= $row1['CARGO_DESCRIPTION'] ?></font></td>
		  <?
		       if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "1" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
			<td><font size = "1" face="Verdana" color="red"><?= $qty_damaged ?></font></td>
		  <?
		       }
                  ?>

			<td align="center"><font size = "1" face="Verdana"><?= $qty_expected ?></font></td>
			<td align="center"><font size = "1" face="Verdana"><?= $qty_received ?></font></td>
			<td align="center">
	                   <font size = "1" face="Verdana" color="red"><?= $qty_short ?></font></td>
			<td align="center">
	                   <font size = "1" face="Verdana" color="red"><?= $qty_over ?></font></td>
			<td align="center">
	                   <font size = "1" face="Verdana"><?= $row1['HATCH'] ?></font></td>
	                <td align="center">
                           <font size = "1" face="Verdana"><?= $row1['WAREHOUSE_LOCATION'] ?></font></td>
                        <td nowrap><font size = "1" face="Verdana"><?= $date_received ?></font></td>
		     </tr>
	          <?
                     } // Keep going, there is nothing to see here :)
		   } else { // We are not Condensing this report
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
                        <td align="center"><font size = "1" face="Verdana"><?= $qty_expected ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $qty_received ?></font></td>
	                <td align="center"><font size = "1" face="Verdana" color="red"><?= $qty_short ?></font></td>
	                <td align="center"><font size = "1" face="Verdana"
color="red"><?= $qty_over ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['HATCH'] ?></font></td>
                        <td align="center"><font size = "1" face="Verdana"><?= $row1['WAREHOUSE_LOCATION'] ?></font></td>
	                <td nowrap><font size = "1" face="Verdana"><?= $date_received ?></font></td>
                     </tr>
	       <?
		   }
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       ?>

		     <tr>
                        <td><font size = "2" face="Verdana" color="#0066CC">Total:</td>
		     <?
		        if ($eport_customer_id != 0) {
		     ?>
		        <td colspan="3">&nbsp;</td>
		     <?
		        } else {
		     ?>
                        <td colspan="2">&nbsp;</td>
                        <td align="center"><font size = "2" face="Verdana"
color="red">Damage Total<br />
			   <?= $qty_damaged_total ?></font></td>
		     <?
			}
                     ?>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Expected Total<br />
			   <?= $qty_expected_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Received Total<br />
			   <?= $qty_received_total ?></font></td>
		        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Short Total<br />
			   <?= $qty_short_total ?></font></td>
		        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">Over Total<br />
			   <?= $qty_over_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"
color="#0066CC">% Scanned:<br />
                           <? $percent = ($pallets_scanned / $pallets_total) * 100;
                              $percent = round($percent, 2);
                              echo "$pallets_scanned / $pallets_total = $percent" ?>%</font></td>
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
