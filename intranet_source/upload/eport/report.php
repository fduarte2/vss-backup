<?
// 05/14/03 - Created by Lynn F. Wang
// Description: Creates a table show lots' information pulled from RF.CCD_CARGO_TRACKING for a specific
// ship, and/or mark, and/or a specific customer.  

// check if it is an authenticated user
$user = $HTTP_COOKIE_VARS["eport_user"];
if ($user == "") {
  header("Location: ../ccds_login.php");
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

// if user entered mark, we will generate PDF report for this mark
if ($eport_customer_id == 0 && $mark != "") {
  include("by_mark_report.php");
  exit;
}

// get infomation from RF.CCD_CARGO_TRACKING
if ($eport_customer_id != 0)
{
  $stmt = "select lot_id, mark, po_num, C.customer_short_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_expected, qty_received, (qty_received - qty_expected) 
  	          qty_not_received, pallet_count_expected, pallet_count_received, 
	          (pallet_count_received - pallet_count_expected) pallet_count_not_received
           from CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C 
           where arrival_num = '$lr_num' and T.receiver_id = C.customer_id 
                  and T.receiver_id = $eport_customer_id
	   order by lot_id, mark, po_num";
} else {
  $stmt = "select lot_id, mark, po_num, C.customer_short_name customer, 
                  to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
	          qty_expected, qty_received, (qty_received - qty_expected)
  	          qty_not_received, pallet_count_expected, pallet_count_received, 
	          (pallet_count_received - pallet_count_expected) pallet_count_not_received
           from CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C 
           where arrival_num = '$lr_num' and T.receiver_id = C.customer_id 
           order by lot_id, mark, po_num";
}

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_TRACKING and 
	  CCD_CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

// initialize running totals
$qty_expected_total = 0;
$qty_received_total = 0;
$qty_short_total = 0;
$qty_damaged_total = 0;
$pallet_expected_total = 0;
$pallet_received_total = 0;
$pallet_short_total = 0;

?>

<html>
<head>
<meta http-equiv="Refresh" content="300">
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
	          <hr>
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
		  }
                  ?>
		  
		  <table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
		  <?
		  if ($eport_customer_id != 0) {
		  ?>
		     <caption align="left">
		       <font size = "3" face="Verdana" color="#0066CC">Customer: <?= $row1['CUSTOMER']  ?></font>
		     </caption>
		  <?
		  }
                  ?>
		     <tr>
			<th nowrap 
		            onmouseover="return overlib('C&S lot number of a specific mark', CAPTION, 'Lot ID');" 
		            onmouseout="return nd();"><font size = "2" face="Verdana">Lot ID</font></th>
			<th onmouseover="return overlib('The mark related to a specific C&S lot', CAPTION, 'Mark');" onmouseout="return nd();"><font size = "2" face="Verdana">Mark</font></th>
			<th onmouseover="return overlib('Customer PO number', CAPTION, 'PO #');" 
		            onmouseout="return nd();"><font size = "2" face="Verdana">PO #</font></th>
		  <?
		     if ($eport_customer_id == 0) {
		  ?>
		        <th onmouseover="return overlib('Owner of specific C&S lot', CAPTION, 'Customer');" 
		            onmouseout="return nd();"><font size = "2" face="Verdana">Customer</font></th>
		  <?
		     }
		  ?>
			<th onmouseover="return overlib('Manifested carton count', CAPTION, 'Cases Expected');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Cases Expected</font></th>
			<th onmouseover="return overlib('Actual cartons received', CAPTION, 'Cases Rcvd');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Cases Rcvd</font></th>
			<th onmouseover="return overlib('Cases Received minus Cases Expected', CAPTION, 'Cases Short');" onmouseout="return nd();"><font size = "2" face="Verdana">Cases Short</font></th>
			<th onmouseover="return overlib('Manifested pallet count', CAPTION, 'Pallets Expected');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Pallets Expected</font></th>
			<th onmouseover="return overlib('Actual pallets received', CAPTION, 'Pallets Rcvd');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Pallets Rcvd</font></th>
			<th onmouseover="return overlib('Pallets Received minus Pallets Expected', CAPTION, 'Pallets Short');" onmouseout="return nd();"><font size = "2" face="Verdana">Pallets Short</font></th>
	                <th onmouseover="return overlib('Actual date and time cargo scanned', CAPTION, 'Time Scanned');" onmouseout="return nd();"><font size = "2" face="Verdana">Time Scanned</font></th>
		     </tr>
	       <?
		 do {
		   // update qantities
		   $qty_expected_total += $row1['QTY_EXPECTED']; 
		   $qty_received_total += $row1['QTY_RECEIVED'];
		   $qty_short_total += $row1['QTY_NOT_RECEIVED'];
		   $pallet_expected_total += $row1['PALLET_COUNT_EXPECTED'];
		   $pallet_received_total += $row1['PALLET_COUNT_RECEIVED'];
		   $pallet_short_total += $row1['PALLET_COUNT_NOT_RECEIVED'];

		   if ($eport_customer_id == 0) {
		     // check to see if we need to add Damage line
		     $stmt = "select d.pallet_id, d.qty_damaged, p.description
                              from CCD_CARGO_DAMAGED d, CCD_DAMAGE_PROFILE p 
                              where d.arrival_num = '$lr_num' and d.damage_type_id = p.damage_type_id 
                                 and d.lot_id = '" . $row1['LOT_ID'] . "' and d.mark = '" . $row1['MARK'] . "'";

		     $ora_success = ora_parse($cursor2, $stmt);
		     $ora_success = ora_exec($cursor2);
		     ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		     $rows2 = ora_numrows($cursor2);

		     if (!$ora_success) {
		       // close Oracle connection
		       ora_close($cursor2);
		       ora_logoff($ora_conn);
		       printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_DAMAGED and 
	                       CCD_CUSTOMER_PROFILE. Please Try Again Later.");
		       exit;
		     }

		     if ($rows2 > 0) {
		       $qty_damaged_total += $row2['QTY_DAMAGED'];
		     }
		   }
		   
		   if ($condense == "On") {
		     if ($row1['QTY_NOT_RECEIVED'] != 0 || $row1['PALLET_COUNT_NOT_RECEIVED'] != 0) {
	       ?>
		     <tr>
                        <td><font size = "2" face="Verdana"><?= $row1['LOT_ID'] ?></font></td>
			<td><font size = "2" face="Verdana"><?= $row1['MARK'] ?></font></td>
			<td><font size = "2" face="Verdana"><?= $row1['PO_NUM'] ?></font></td>
		  <?
		       if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "2" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
		  <?
		       }
		  ?>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_EXPECTED'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_RECEIVED'] ?></font></td>
			<td align="center">
	                   <font size = "2" face="Verdana"><?= $row1['QTY_NOT_RECEIVED'] ?></font></td>
			<td align="center">
	                   <font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_EXPECTED'] ?></font></td>
			<td align="center">
	                   <font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_RECEIVED'] ?></font></td>
			<td align="center">
	                   <font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_NOT_RECEIVED'] ?></font></td>
                        <td nowrap><font size = "2" face="Verdana"><?= $row1['DATE_RECEIVED'] ?></font></td>
		     </tr>
	          <?
	               if ($eport_customer_id == 0 && $rows2 > 0) {
	          ?>		     
	       	     <tr>
                        <td align="left" colspan="11">
	                   <font size = "2" face="Verdana" color="red">
	                   Damage: Pallet with Lot ID <?= $row1['LOT_ID'] ?> 
		           and Pallet ID <?= $row2['PALLET_ID'] ?>
		           Had <?= $row2['QTY_DAMAGED'] ?> Case(s) Damaged. &nbsp;
	                   Damage Reason: <?= $row2['DESCRIPTION'] ?>
	                   </font>
	                </td>
                     </tr>
	       <?
		       }
		     }
		   } else {
	       ?>
	             <tr>
	                <td><font size = "2" face="Verdana"><?= $row1['LOT_ID'] ?></font></td>
                        <td><font size = "2" face="Verdana"><?= $row1['MARK'] ?></font></td>
                        <td><font size = "2" face="Verdana"><?= $row1['PO_NUM'] ?></font></td>
		  <?
	              if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "2" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
		  <?
		      }
		  ?>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_EXPECTED'] ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_RECEIVED'] ?></font></td>
	                <td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_NOT_RECEIVED'] ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_EXPECTED'] ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_RECEIVED'] ?></font></td>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['PALLET_COUNT_NOT_RECEIVED'] ?></font></td>
	                <td nowrap><font size = "2" face="Verdana"><?= $row1['DATE_RECEIVED'] ?></font></td>
                     </tr>
	          <?
	              if ($rows2 > 0) {
	          ?>		     
	       	     <tr>
                        <td align="left" colspan="11">
	                   <font size = "2" face="Verdana" color="red">
	                   Damage: Pallet with Lot ID <?= $row1['LOT_ID'] ?> 
		           and Pallet ID <?= $row2['PALLET_ID'] ?>
		           Had <?= $row2['QTY_DAMAGED'] ?> Case(s) Damaged. &nbsp;
	                   Damage Reason: <?= $row2['DESCRIPTION'] ?>
	                   </font>
	                </td>
                     </tr>
	       <?
		      }
		   }
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
                        <td colspan="3">&nbsp;</td>
		     <?
			}
                     ?>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $qty_expected_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $qty_received_total ?></font></td>
		        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $qty_short_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $pallet_expected_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $pallet_received_total ?></font></td>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $pallet_short_total ?></font></td>
			<td>&nbsp;</td>
                     </tr>
		<?
		   if ($eport_customer_id == 0) {
		?>
		     <tr>
                        <td colspan="11"><font size = "2" face="Verdana" color="red">
		           Damage Total: <?= $qty_damaged_total ?> Cases</font>
		        </td>
                     </tr>
		
 	        <?
		   }
		?>
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
