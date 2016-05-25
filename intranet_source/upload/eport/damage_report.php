<?
//  5/24/05 - Created by Richard Wanag
// Description: Creates a table show damaged pallets pulled from RF.CCD_CARGO_TRACKING for a specific
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

// get infomation from RF.CCD_CARGO_DAMAGE, RF.CCD_CARGO_TRACKING
$stmt = "select t.lot_id, c.customer_short_name customer, d.qty_damaged, d.description,
	 	to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received
	 from   CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C,
	        (select lot_id, arrival_num, mark, description, sum(qty_damaged) qty_damaged
	        from CCD_CARGO_DAMAGED D, CCD_DAMAGE_PROFILE P
	        where d.damage_type_id = p.damage_type_id and d.damage_type_id <>'DP'
	        group by lot_id, arrival_num, mark, description) D
	 where  t.arrival_num = d.arrival_num and t.lot_id = d.lot_id and t.mark = d.mark and
	 	t.receiver_id = c.customer_id(+) and t.arrival_num = '$lr_num' ";
if ($eport_customer_id != 0)
{
	$stmt .= " and t.receiver_id = $eport_customer_id ";
}
$stmt .= " order by t.lot_id ";

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_DAMAGED, CCD_CARGO_TRACKING and 
	  CCD_CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

// initialize running totals
$qty_damaged_total = 0;

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
		  <font size = "3" face="Verdana" color="#0066CC">Damage Report</font>
	          <br /><br />
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
			<th nowrap align="left"
		            onmouseover="return overlib('C&S lot number of a specific mark', CAPTION, 'Lot ID');" 
		            onmouseout="return nd();"><font size = "2" face="Verdana">Lot ID</font></th>
<!--
			<th align="left"
			    onmouseover="return overlib('The mark related to a specific C&S lot', CAPTION, 'Mark');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Mark</font></th>
			<th onmouseover="return overlib('Customer PO number', CAPTION, 'PO #');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">PO #</font></th>
-->
		  <?
		     if ($eport_customer_id == 0) {
		  ?>
		        <th align="left"
			    onmouseover="return overlib('Owner of specific C&S lot', CAPTION, 'Customer');" 
		            onmouseout="return nd();"><font size = "2" face="Verdana">Customer</font></th>
		  <?
		     }
		  ?>
                        <th onmouseover="return overlib('Damaged carton count', CAPTION, 'Cases Damaged');"
                            onmouseout="return nd();"><font size = "2" face="Verdana">Cases Damaged</font></th>
                        <th align="left"
			    onmouseover="return overlib('Damage Reason', CAPTION, 'Damage Reason');"
			    onmouseout="return nd();"><font size = "2" face="Verdana">Damage Reason</font></th>
                        <th align="left"
			    onmouseover="return overlib('Actual date and time cargo scanned', CAPTION, 'Time Scanned');" 
			    onmouseout="return nd();"><font size = "2" face="Verdana">Time Scanned</font></th>
		      </tr>
					     
<?
		 do {
                   // update qantities
                   $qty_damaged_total += $row1['QTY_DAMAGED'];
?>		       
	             <tr>
	                <td><font size = "2" face="Verdana"><?= $row1['LOT_ID'] ?></font></td>
<!--
			<td><font size = "2" face="Verdana"><?= $row1['MARK'] ?></font></td>
                        <td><font size = "2" face="Verdana"><?= $row1['PO_NUM'] ?></font></td>
-->
		  <?
	              if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "2" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
		  <?
		      }
		  ?>
                        <td align="center"><font size = "2" face="Verdana"><?= $row1['QTY_DAMAGED'] ?></font></td>
			<td align="left"><font size = "2" face="Verdana"><?= $row1['DESCRIPTION'] ?></font></td>
			<td nowrap><font size = "2" face="Verdana"><?= $row1['DATE_RECEIVED'] ?></font></td>
		      </tr>
		<?
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       ?>

		     <tr>
                        <td><font size = "2" face="Verdana" color="#0066CC">Total:</td>
		     <?
		        if ($eport_customer_id != 0) {
		     ?>
		        <!--<td colspan="2">&nbsp;</td>-->
		     <?
		        } else {
		     ?>
                        <td >&nbsp;</td>
		     <?
			}
                     ?>
                        <td align="center"><font size = "2" face="Verdana" color="#0066CC">
			   <?= $qty_damaged_total ?></font></td>
			<td colspan="2">&nbsp;</td>
		     </tr>
		<?
	
		   //get damage summary
		   if ($qty_damaged_total > 0){
		   	$stmt = "select sum(d.qty_damaged) qty_damaged, d.description
		                 from   CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C,
			           	(select lot_id, arrival_num, mark, description, sum(qty_damaged) qty_damaged
				   	from CCD_CARGO_DAMAGED D, CCD_DAMAGE_PROFILE P
				   	where d.damage_type_id = p.damage_type_id and d.damage_type_id <>'DP'
				   	group by lot_id, arrival_num, mark, description) D
				 where  t.arrival_num = d.arrival_num and t.lot_id = d.lot_id and t.mark = d.mark and
				   	t.receiver_id = c.customer_id(+) and t.arrival_num = '$lr_num' ";
		   	if ($eport_customer_id != 0)
		   	{
				$stmt .= " and t.receiver_id = $eport_customer_id ";
		   	}
		   	$stmt .= " group by d.description order by sum(d.qty_damaged) desc";
								
		   	ora_parse($cursor1, $stmt);
		   	ora_exec($cursor1);
			
		?>
		    <tr>
		       <td colspan="11"><font size = "2" face="Verdana" color="red"><b>Damage Summay:</b></font></td>
		    </tr>   
		<?
		   	while(ora_fetch($cursor1)){
		   		$tot_damage += ora_getcolumn($cursor1, 0);
		?>

		    <tr>
		       <td colspan="11"><font size = "2" face="Verdana" color="red">
		       		<?= ora_getcolumn($cursor1, 1) ?>: <?= ora_getcolumn($cursor1, 0) ?> Case(s)</font></td>
		    </tr>
		<?
			}
		?>
		    <tr>
		       <td colspan="11"><font size = "2" face="Verdana" color="red">
		       <b>TOTAL DAMAGES: <?= $tot_damage?> Case(s)</b></font></td>
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
