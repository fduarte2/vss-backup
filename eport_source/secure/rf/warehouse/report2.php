<?
/*
*	Adam Walter, March 2008
*
*	There already is a "report.php" in this folder, but it's original creator
*	designed it in such a way as to make updating it painfully difficult.
*	Rather than modify it, I've decided to rewrite the page.  It will
*	Make life easier in the long run.
*
*	Edit Jan 2009:  Minor verbiage change to "description" column
*****************************************************************************/

// check if it is an authenticated user
$user = $HTTP_COOKIE_VARS["eport_user"];
if ($user == "") {
  header("Location: ../../rf_login.php");
  exit;
}

// get cookie and form values ($ship, $condense and $mark)
$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
list($lr_num, $vessel_name) = split("-", $ship, 2);
if($HTTP_POST_VARS['lr_num'] != ""){
	$lr_num = $HTTP_POST_VARS['lr_num'];
}
$customer = $HTTP_POST_VARS['customer'];

$today = date("F j, Y, g:i A");

// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//$ora_conn = ora_logon("SAG_OWNER@RF.TEST", "RFOWNER");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor = ora_open($ora_conn);
if (!$cursor) {
  printf("Error opening Oracle Server: ");
  printf(ora_errorcode($cursor));
  exit;
}		

// create the body's SQL
if($eport_customer_id == 1131 || $customer == 1131){
	$order_by = " CM.COMMODITY_NAME, VARIETY, REMARK, CARGO_SIZE, CARGO_DESCRIPTION, CT.PALLET_ID";
} else {
	$order_by = " DATE_RECEIVED ASC, PALLET_ID ASC";
}

	$sql_filter = "";

	$comm = $HTTP_POST_VARS['comm'];
	$label = $HTTP_POST_VARS['label'];
	$size = $HTTP_POST_VARS['size'];
	$grower = $HTTP_POST_VARS['grower'];
	$variety = $HTTP_POST_VARS['variety'];

	if($comm != ""){
		$sql_filter .= " AND CT.COMMODITY_CODE = '".$comm."' ";
	}
	if($label != ""){
		$sql_filter .= " AND CT.REMARK = '".$label."' ";
	}
	if($size != ""){
		$sql_filter .= " AND CT.CARGO_SIZE = '".$size."' ";
	}
	if($grower != ""){
		$sql_filter .= " AND CT.CARGO_DESCRIPTION = '".$grower."' ";
	}
	if($variety != ""){
		$sql_filter .= " AND CT.VARIETY = '".$variety."' ";
	}

//echo print_r($HTTP_POST_VARS);
if($submit == "View Ship Report"){
	$sql = "SELECT PALLET_ID, CARGO_DESCRIPTION, CM.COMMODITY_NAME THE_COMM, CP.CUSTOMER_NAME THE_CUST, VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VES,
			NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), '---NR---') THE_REC_DATE, VARIETY, QTY_RECEIVED, FUMIGATION_CODE,
			QTY_IN_HOUSE, WAREHOUSE_LOCATION, REMARK, CARGO_SIZE FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CP, COMMODITY_PROFILE CM, VESSEL_PROFILE VP
			WHERE TO_CHAR(VP.LR_NUM) = CT.ARRIVAL_NUM AND CT.RECEIVER_ID = CP.CUSTOMER_ID AND CT.COMMODITY_CODE = CM.COMMODITY_CODE
			AND CM.COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT') AND QTY_IN_HOUSE > 0 ".$sql_filter;
	if($lr_num != 'x'){
		$sql .= "AND CT.ARRIVAL_NUM = '".$lr_num."' ";
	}
	if ($eport_customer_id != 0){
		if($eport_customer_id == "9999"){
			$sql .= "AND CT.RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
		} else {
			$sql .= "AND CT.RECEIVER_ID = '".$eport_customer_id."' ";
		}
	}elseif($customer != "all" && $customer != ""){
		$sql .= "AND CT.RECEIVER_ID = '".$customer."' ";
	}
	$sql .= "AND DATE_RECEIVED IS NOT NULL ";

	$sql .= "ORDER BY LR_NUM ASC, ".$order_by;
//	echo $sql."<br>";
} else { // trucked cargo
	$sql = "SELECT PALLET_ID, CARGO_DESCRIPTION, CM.COMMODITY_NAME THE_COMM, CP.CUSTOMER_NAME THE_CUST, FUMIGATION_CODE,
				NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), '---NR---') THE_REC_DATE, ARRIVAL_NUM || '- TRUCK' THE_VES,
				VARIETY, QTY_RECEIVED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, REMARK, CARGO_SIZE
			FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CP, COMMODITY_PROFILE CM
			WHERE CT.RECEIVER_ID = CP.CUSTOMER_ID AND CT.COMMODITY_CODE = CM.COMMODITY_CODE
				AND CM.COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT') AND QTY_IN_HOUSE > 0 AND RECEIVING_TYPE = 'T'".$sql_filter;
	if ($eport_customer_id != 0){
		if($eport_customer_id == "9999"){
			$sql .= "AND CT.RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
		} else {
			$sql .= "AND CT.RECEIVER_ID = '".$eport_customer_id."' ";
		}
	}elseif($customer != "all" && $customer != ""){
		$sql .= " AND CT.RECEIVER_ID = '".$customer."' ";
	}
	if ($truck_rec == "no"){
		$sql .= " AND DATE_RECEIVED IS NULL";
	} elseif ($truck_rec == "yes") {
		$sql .= " AND DATE_RECEIVED IS NOT NULL";
		if($date_from != ""){
			$sql .= " AND DATE_RECEIVED >= TO_DATE('".$date_from."', 'MM/DD/YYYY')";
		}
		if($date_to != ""){
			$sql .= " AND DATE_RECEIVED <= TO_DATE('".$date_to." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		}
	}

	$sql .= " ORDER BY ARRIVAL_NUM ASC, ".$order_by;
}

$ora_success = ora_parse($cursor, $sql);
$ora_success = ora_exec($cursor);

ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows = ora_numrows($cursor);

// modify variable for page display later
if($vessel_name == 'x'){
	$vessel_name = "All Vessels";
}

// initialize some totals

$ship_total_pallets = 0;
$ship_received_cartons = 0;
$ship_inhouse_cartons = 0;

$all_total_pallets = 0;
$all_received_cartons = 0;
$all_inhouse_cartons = 0;

$current_row_bg_color = "#FFFFFF";

// begin page

?>

<html>
<head>
<!-- <meta http-equiv="Refresh" content="500"> !-->
<title>Eport - Inventory Report</title>
</head>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">
<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
		<form name="filters" action="report2.php" method="post">
		<input type="hidden" name="lr_num" value="<? echo $lr_num; ?>">
		<input type="hidden" name="customer" value="<? echo $customer; ?>">
	    <tr>
			<td>Commodity&nbsp;&nbsp;&nbsp;<select name="comm"><? MakeDropdownOptions("COMMODITY_CODE", $comm, $lr_num, $customer, $eport_customer_id, $user, $ora_conn); ?></select>&nbsp;&nbsp;&nbsp;</td>
			<td>Label&nbsp;&nbsp;&nbsp;<select name="label"><? MakeDropdownOptions("REMARK", $label, $lr_num, $customer, $eport_customer_id, $user, $ora_conn); ?></select>&nbsp;&nbsp;&nbsp;</td>
			<td>Size&nbsp;&nbsp;&nbsp;<select name="size"><? MakeDropdownOptions("CARGO_SIZE", $size, $lr_num, $customer, $eport_customer_id, $user, $ora_conn); ?></select>&nbsp;&nbsp;&nbsp;</td>
			<td>Grower&nbsp;&nbsp;&nbsp;<select name="grower"><? MakeDropdownOptions("CARGO_DESCRIPTION", $grower, $lr_num, $customer, $eport_customer_id, $user, $ora_conn); ?></select>&nbsp;&nbsp;&nbsp;</td>
			<td>Variety&nbsp;&nbsp;&nbsp;<select name="variety"><? MakeDropdownOptions("VARIETY", $variety, $lr_num, $customer, $eport_customer_id, $user, $ora_conn); ?></select>&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
	       <td colspan="5" align="center"><input type="submit" name="submit" value="<? echo $submit; ?>"></td>
		</tr>
		</form>
		<tr>
	       <td colspan="5" align="center">
	          <br />
	          <hr color="green">
	          <br />
                  <font size = "3" face="Verdana">Report Generated On <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 </td>
   </tr>

<?
	// no cargo to dispaly
	if($rows == 0){
?>
	<tr>
		<td align="center"><font size = "2" face="Verdana">This report shows cargo actually In-House at the Port.  If you are looking for cargo yet to arrive, or cargo that has already left the Port, please <a href="chilean_recon_eport_index.php">Click Here</a>.</font></td>
	</tr>
</table>
</body>
</html>
<?
	// have cargo, prepare table
	} else {
		if ($eport_customer_id != 0 && $eport_customer_id != 9999) {
?>
	     <tr align="center">
	       <td><font size = "3" face="Verdana" color="#0066CC">Customer: <? echo $row['THE_CUST'];  ?></font></td>
	     </tr>
<?
		}
?>

	<tr>
		<td>
			<table width="100%" align="center" border="1" cellpadding="2" cellspacing="2">
				<tr bgcolor="#CC9900">
					<td><font size="2" face="Verdana"><b>Variance</b></font></td>
					<td><font size="2" face="Verdana"><b>Comm</b></font></td>
					<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
					<td><font size="2" face="Verdana"><b>Label</b></font></td>
					<td><font size="2" face="Verdana"><b>Variety</b></font></td>
					<td><font size="2" face="Verdana"><b>Size</b></font></td>
					<td><font size="2" face="Verdana"><b>Grower</b></font></td>
					<td><font size="2" face="Verdana"><b>Fume Code</b></font></td>
					<td><font size="2" face="Verdana"><b>Manifested</b></font></td>
					<td><font size="2" face="Verdana"><b>In Storage</b></font></td>
					<td><font size="2" face="Verdana"><b>Location</b></font></td>
					<td><font size="2" face="Verdana"><b>Received</b></font></td>
				</tr>
				<tr bgcolor="#EE2233">
					<td colspan="12" align="left"><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
				</tr>
<?
		$current_ves = $row['THE_VES'];
		do {
//			$temp = split("/", $row['REMARK']);
//			$type = $temp[0];
//			$size = $temp[1];
			// loop cargo
			// if new vessel, display totals and new heading
			if($current_ves != $row['THE_VES']){
?>
				<tr bgcolor="#6699FF">
					<td colspan="2" align="right"><font size="2" face="Verdana">Vessel Total Pallets:</font></td>
					<td><font size="2" face="Verdana"><? echo $ship_total_pallets; ?></font></td>
					<td align="right" colspan="5"><font size="2" face="Verdana">Vessel Total Cartons:</font></td>
					<td align="right"><font size="2" face="Verdana">Received:  <? echo $ship_received_cartons; ?></font></td>
					<td align="right"><font size="2" face="Verdana">In House:  <? echo $ship_inhouse_cartons; ?></font></td>
					<td colspan="2" align="right"><font size="2" face="Verdana">&nbsp;</font></td>
				</tr>
				<tr bgcolor="#EE2233">
					<td colspan="12" align="left"><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
				</tr>
<?
				$current_ves = $row['THE_VES'];
				$ship_total_pallets = 0;
				$ship_received_cartons = 0;
				$ship_inhouse_cartons = 0;
			}

			// now, display row.
			if($current_row_bg_color == "#FFFFFF"){
				$current_row_bg_color = "#F0F0F0";
			} else {
				$current_row_bg_color = "#FFFFFF";
			}

			if($row['QTY_RECEIVED'] - $row['QTY_IN_HOUSE'] == 0){
				$first_column = "&nbsp;";
			} else {
				$first_column = $row['QTY_RECEIVED'] - $row['QTY_IN_HOUSE'];
			}
?>
				<tr bgcolor="<? echo $current_row_bg_color; ?>">
<?
			if($row['QTY_RECEIVED'] - $row['QTY_IN_HOUSE'] == 0){
?>
					<td align="center"><font size="1" face="Verdana">&nbsp;</font></td>
<?
			} else {
?>
					<td bgcolor="#FFFF00" align="center"><font size="1" face="Verdana"><? echo $row['QTY_RECEIVED'] - $row['QTY_IN_HOUSE']; ?></font></td>
<?
			}
?>
					<td><font size="1" face="Verdana"><? echo $row['THE_COMM']; ?></font></td>
					<td><font size="1" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
					<td><font size="1" face="Verdana"><? echo $row['REMARK']; ?>&nbsp;</font></td>
					<td><font size="1" face="Verdana"><? echo $row['VARIETY']; ?>&nbsp;</font></td>
					<td><font size="1" face="Verdana"><? echo $row['CARGO_SIZE']; ?>&nbsp;</font></td>
					<td><font size="1" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?>&nbsp;</font></td>
					<td><font size="1" face="Verdana"><? echo $row['FUMIGATION_CODE']; ?>&nbsp;</font></td>
					<td><font size="1" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
					<td><font size="1" face="Verdana"><? echo $row['QTY_IN_HOUSE']; ?></font></td>
					<td><font size="1" face="Verdana"><? echo $row['WAREHOUSE_LOCATION']; ?></font></td>
					<td><font size="1" face="Verdana"><? echo $row['THE_REC_DATE']; ?></font></td>
				</tr>
<?
				$ship_total_pallets++;
				$ship_received_cartons += $row['QTY_RECEIVED'];
				$ship_inhouse_cartons += $row['QTY_IN_HOUSE'];
				$all_total_pallets++;
				$all_received_cartons += $row['QTY_RECEIVED'];
				$all_inhouse_cartons += $row['QTY_IN_HOUSE'];
			
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
				<tr bgcolor="#6699FF">
					<td colspan="2" align="right"><font size="2" face="Verdana">Vessel Total Pallets:</font></td>
					<td><font size="2" face="Verdana"><? echo $ship_total_pallets; ?></font></td>
					<td colspan="5" align="right"><font size="2" face="Verdana">Vessel Total Cartons:</font></td>
					<td align="right"><font size="2" face="Verdana">Received:  <? echo $ship_received_cartons; ?></font></td>
					<td align="right"><font size="2" face="Verdana">In House:  <? echo $ship_inhouse_cartons; ?></font></td>
					<td colspan="2" align="right"><font size="2" face="Verdana">&nbsp;</font></td>
				</tr>
				<tr bgcolor="#33CC99">
					<td colspan="2" align="right"><font size="2" face="Verdana"><b>Grand Total Pallets:</b></font></td>
					<td><font size="2" face="Verdana"><b><? echo $all_total_pallets; ?></b></font></td>
					<td colspan="5" align="right"><font size="2" face="Verdana"><b>Grand Total Cartons:</b></font></td>
					<td align="right"><font size="2" face="Verdana"><b>Received:  <? echo $all_received_cartons; ?></b></font></td>
					<td align="right"><font size="2" face="Verdana"><b>In House:  <? echo $all_inhouse_cartons; ?></b></font></td>
					<td colspan="2" align="right"><font size="2" face="Verdana">&nbsp;</font></td>
				</tr>
			</table>
		</td>
	</tr>
<?
	}
?>
</table>
</body>
</html>






<?
function MakeDropdownOptions($DB_field, $cur_value, $lr_num, $cust, $eport_customer_id, $user, $ora_conn){
?>
	<option value="">All</option>
<?
	$cursor = ora_open($ora_conn);

	$sql = "SELECT DISTINCT CT.".$DB_field.", DECODE('".$DB_field."', 'COMMODITY_CODE', '-' || COMMODITY_NAME, '') COMM_NAME_IF_NEED
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE ARRIVAL_NUM = '".$lr_num."'
				AND DATE_RECEIVED IS NOT NULL
				AND QTY_IN_HOUSe > 0
				AND CT.".$DB_field." IS NOT NULL
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
	if ($eport_customer_id != 0){
		if($eport_customer_id == "9999"){
			$sql .= " AND CT.RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
		} else {
			$sql .= " AND CT.RECEIVER_ID = '".$eport_customer_id."' ";
		}
	}elseif($cust != "all" && $customer != ""){
		$sql .= " AND RECEIVER_ID = '".$customer."' ";
	}
	$sql .= " ORDER BY ".$DB_field;

	echo $sql."<br>";

	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$comm_name = $row['COMM_NAME_IF_NEED'];
?>
		<option value="<? echo $row[$DB_field]; ?>"<? if($row[$DB_field] == $cur_value){?> selected <?}?>><? echo $row[$DB_field].$comm_name; ?></option>;
<?
	}
}
