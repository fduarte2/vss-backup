<?
/* Created Adam Walter, Feb 2007.
*  Page retrieves order and pallet counts for a given date range,
*  Broken down by date, commodity, and customer (in that order)
*  Shoop da Whoop.
*/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Truck Totals Page";
  $area_type = "SUPV"; /// ??? no marketing area :(

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

   include("connect.php");

   $BNIconn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($BNIconn < 1){
	    printf("Error logging on to the DB Server: ");
       	printf(ora_errorcode($BNIconn));
       	exit;
   }
   $BNIcursor = ora_open($BNIconn);
   $BNICommCursor = ora_open($BNIconn);
   $BNICustCursor = ora_open($BNIconn);

   $RFconn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($RFconn < 1){
	    printf("Error logging on to the DB Server: ");
       	printf(ora_errorcode($RFconn));
       	exit;
   }
   $RFcursor = ora_open($RFconn);
   $RFCommCursor = ora_open($RFconn);
   $RFCustCursor = ora_open($RFconn);

   $PAPIconn = ora_logon("PAPINET@RF", "OWNER");
   if($PAPIconn < 1){
	    printf("Error logging on to the DB Server: ");
       	printf(ora_errorcode($PAPIconn));
       	exit;
   }
   $PAPIcursor = ora_open($PAPIconn);
//   $PAPICommCursor = ora_open($PAPIconn);
//   $PAPICustCursor = ora_open($PAPIconn);

   $submit = $HTTP_POST_VARS['submit'];
   $startDate = $HTTP_POST_VARS['startDate'];
   $endDate = $HTTP_POST_VARS['endDate'];
   $bgcolor_alt = "#FFFFFF";

   if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $startDate) && $submit == 'submit'){
	   $BadStart = 1;
   }
   if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $endDate) && $submit == 'submit'){
	   $BadEnd = 1;
   }

   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $startDate)){
	   $temp = split("/", $startDate);
//	   $pgStart = date("Y-m-d", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
//	   $oraStart = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
	   $absStart = mktime(0,0,0,$temp[0],$temp[1],$temp[2]);
   }
   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $endDate)){
	   $temp = split("/", $endDate);
//	   $pgEnd = date("Y-m-d", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
//	   $oraEnd = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1]+1,$temp[2]));
	   $absEnd = mktime(0,0,0,$temp[0],$temp[1]+1,$temp[2]);
   }


// while not the most efficient coding procedure, I'm making both halves of this webpage in this file, 
// one for each half of the "if submitted" statement.
// at least it will be easier to read and modify.  ~Adam Walter, Feb 2007
   if($submit != 'submit' || $BadStart == 1 || $BadEnd == 1){
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Truck Totals Report</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
<form name="print_form" action="truck_activity.php" method="post">
   <tr>
	  <td width="1%">&nbsp;</td>
	  <td colspan="2"><font size="3" face="Verdana">Please select a date range:</font></td>
   </tr>
   <tr>
	  <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Begin Date:&nbsp;&nbsp;</font>
		  <input type="textbox" name="startDate" size="15" maxlength="20" value="<? echo $startDate; ?>">
		  <a href="javascript:show_calendar('print_form.startDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../../images/show-calendar.gif" width=24 height=22 border=0></a>
		  <? if($BadStart == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?>
	  </td>
   </tr>
   <tr>
	  <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">End Date:&nbsp;&nbsp;</font>
		  <input type="textbox" name="endDate" size="15" maxlength="20" value="<? echo $endDate; ?>">
		  <a href="javascript:show_calendar('print_form.endDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../../images/show-calendar.gif" width=24 height=22 border=0></a>
		  <? if($BadEnd == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?>
	  </td>
   </tr>
   <tr>
	  <td width="3%" colspan="2">&nbsp;</td>
	  <td><input type="submit" name="submit" value="submit"><br><br><br></td>
   </tr>
</form>
</table>
<?
   } else {
?>
<table cellpadding="3" cellspacing="0" border="1">
   <tr bgcolor="7777EE">
	  <td><font size="3" face="Verdana">DATE</font></td>
	  <td><font size="3" face="Verdana">Commodity</font></td>
	  <td><font size="3" face="Verdana">Customer</font></td>
	  <td><font size="3" face="Verdana">Distinct Orders</font></td>
	  <td><font size="3" face="Verdana">Quantity</font></td>
	  <td><font size="3" face="Verdana">Unit</font></td>
   </tr>
<?
	// outer loop:  for each date
		for($i = $absStart; $i <= $absEnd; $i += 86400){
			$currentComm = "";
			$startDate = date("m/d/Y", $i);
			$endDate = date("m/d/Y", ($i + 86400));
?>
	<tr bgcolor="ba4b45">
		<td colspan="6"><font size="3" face="Verdana"><b><? echo date("l m/d/y", $i); ?></b></font></td>
	</tr>
<?
			// BNI segment of output
			$sql = "SELECT COMMODITY_CODE, CUSTOMER_ID, COUNT(DISTINCT ORDER_NUM) THE_COUNT, SUM(QTY_CHANGE) THE_CHANGE, QTY_UNIT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE DATE_OF_ACTIVITY >= TO_DATE('".$startDate."', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY < TO_DATE('".$endDate."', 'MM/DD/YYYY') AND CA.LOT_NUM = CT.LOT_NUM GROUP BY COMMODITY_CODE, CUSTOMER_ID, QTY_UNIT ORDER BY COMMODITY_CODE, CUSTOMER_ID";
			ora_parse($BNIcursor, $sql);
			ora_exec($BNIcursor);
			// inner loop:  for each commodity
			while(ora_fetch_into($BNIcursor, $BNIrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($bgcolor_alt == "#FFFFFF"){
					$bgcolor_alt = "#F0F0F0";
				} else {
					$bgcolor_alt = "#FFFFFF";
				}
				
				// do this if new commodity only
				if($BNIrow['COMMODITY_CODE'] != $currentComm){
					$currentComm = $BNIrow['COMMODITY_CODE'];
					$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$currentComm."'";
					ora_parse($BNICommCursor, $sql);
					ora_exec($BNICommCursor);
					ora_fetch_into($BNICommCursor, $BNICommrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr bgcolor="45ba6f">
		<td>&nbsp;</td>
		<td colspan="5"><font size="3" face="Verdana"><? echo $BNICommrow['COMMODITY_NAME']; ?></font></td>
	</tr>
<?
				}
				
				// this is done every pass of the inner loop
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$BNIrow['CUSTOMER_ID']."'";
				ora_parse($BNICustCursor, $sql);
				ora_exec($BNICustCursor);
				ora_fetch_into($BNICustCursor, $BNICustrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

?>
	<tr bgcolor="<? echo $bgcolor_alt; ?>">
		<td>&nbsp;</td><td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $BNICustrow['CUSTOMER_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $BNIrow['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $BNIrow['THE_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $BNIrow['QTY_UNIT']; ?></font></td>
	</tr>
<?
			}

			// RF segment of output
			$currentComm = "";
			$sql = "SELECT CT.COMMODITY_CODE THE_COMM, CT.RECEIVER_ID THE_OWNER, COUNT(DISTINCT ORDER_NUM) THE_COUNT, COUNT(DISTINCT CT.PALLET_ID) THE_CHANGE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CT.PALLET_ID = CA.PALLET_ID AND CA.SERVICE_CODE = '6' AND DATE_OF_ACTIVITY >= TO_DATE('".$startDate."', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY < TO_DATE('".$endDate."', 'MM/DD/YYYY') GROUP BY CT.COMMODITY_CODE, CT.RECEIVER_ID ORDER BY CT.COMMODITY_CODE, CT.RECEIVER_ID";
			ora_parse($RFcursor, $sql);
			ora_exec($RFcursor);
			// inner loop:  for each commodity
			while(ora_fetch_into($RFcursor, $RFrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($bgcolor_alt == "#FFFFFF"){
					$bgcolor_alt = "#F0F0F0";
				} else {
					$bgcolor_alt = "#FFFFFF";
				}

				// do this if new commodity only
				if($RFrow['THE_COMM'] != $currentComm){
					$currentComm = $RFrow['THE_COMM'];
					$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$currentComm."'";
					ora_parse($RFCommCursor, $sql);
					ora_exec($RFCommCursor);
					ora_fetch_into($RFCommCursor, $RFCommrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr bgcolor="45ba6f">
		<td>&nbsp;</td>
		<td colspan="5"><font size="3" face="Verdana"><? echo $currentComm."-".$RFCommrow['COMMODITY_NAME']; ?></font></td>
	</tr>
<?
				}

				// this is done every pass of the inner loop
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$RFrow['THE_OWNER']."'";
				ora_parse($RFCustCursor, $sql);
				ora_exec($RFCustCursor);
				ora_fetch_into($RFCustCursor, $RFCustrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

?>
	<tr bgcolor="<? echo $bgcolor_alt; ?>">
		<td>&nbsp;</td><td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $RFCustrow['CUSTOMER_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $RFrow['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $RFrow['THE_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana">PLT</font></td>
	</tr>
<?
			}

			// Papinet segment of output (Holmen Paper only, basically)
			$currentComm = "";
			$sql = "SELECT COUNT(*) THE_COUNT, SUM(PACKAGE_BOOKED) THE_BOOKED FROM ORDER_HEADER WHERE CALLOFF_STATUS = 'COMPLETED' AND DELIVERY_END >= TO_DATE('".$startDate."', 'MM/DD/YYYY')	AND DELIVERY_END < TO_DATE('".$endDate."', 'MM/DD/YYYY')";
			ora_parse($PAPIcursor, $sql);
			ora_exec($PAPIcursor);
			// no loop:  single entry
			ora_fetch_into($PAPIcursor, $PAPIrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($bgcolor_alt == "#FFFFFF"){
					$bgcolor_alt = "#F0F0F0";
				} else {
					$bgcolor_alt = "#FFFFFF";
				}

?>
	<tr bgcolor="45ba6f">
		<td>&nbsp;</td>
		<td colspan="5"><font size="3" face="Verdana">All Holmen Paper</font></td>
	</tr>
	<tr bgcolor="<? echo $bgcolor_alt; ?>">
		<td>&nbsp;</td><td>&nbsp;</td>
		<td><font size="2" face="Verdana">Holmen</font></td>
		<td><font size="2" face="Verdana"><? echo $PAPIrow['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? if($PAPIrow['THE_COUNT'] != 0){ echo $PAPIrow['THE_BOOKED']; } else { echo "0"; } ?></font></td>
		<td><font size="2" face="Verdana">PKG</font></td>
	</tr>
<?
		}
	}
  include("pow_footer.php");
?>