<?php
// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Steel Picklist";
$area_type = "INVE";

// Provides header / leftnav
// include("pow_header.php");
// if($access_denied) {
	// printf("Access Denied from INVE system");
	// include("pow_footer.php");
	// exit;
// }

// include("barcode.php"); //For printing barcodes

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
<html moznomarginboxes mozdisallowselectionprint>

<style>
@media all
{
	body {
		font-family: Verdana, serif;
		font-size: medium;
	}
	.alert {color: red;}
	#printButton {
		text-align: right;
		padding: 4px;
	}
	.fatfont {font-weight: bold;}
	tr {page-break-inside: avoid;}
	table {font-family : Verdana, sans-serif;}
	.barcode {
		font-family: "Courier New", monospace;
		text-align: center;
	}
	caption {
		font-weight: bold;
		font-size: large;
	}
}
@media screen
{
	table {font-size: medium;}
	.barcode {font-size: large;}
}
@media print
{
	table {font-size: small;}
	thead {display: table-header-group;}
	#printButton, #alert {display: none;}
	.newpage {page-break-before: always}
	.barcode {font-size: medium;}
}
@page
{
	size: auto;
	margin: 15mm;
	@bottom-right {content: counter(page) " of " counter(pages);}
}
</style>
<?php

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo '<div class="alert" id="alert">Currently using the RF.TEST database!</div>';
if($rfconn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

//	$PORT_num = $HTTP_POST_VARS['PORT_num'];

if(GetCount($PORT_num, $rfconn) <= 0) {
?>
<span class="alert">Port Order # <?php echo $PORT_num; ?> not found in system. Please press the back button and try again.
<?php
	exit;
}

?>
<div id="printButton">
	<button type="button" onClick="window.print()">Print Picklist</button>
</div>
<?php

$header_info = array();
$header_info['PORT_num'] = $PORT_num;
$stid = GetOrderData($header_info['PORT_num'], $rfconn);

$header_info['arrival'] = ociresult($stid, "LR_NUM");
$header_info['cust'] = ociresult($stid, "CUSTOMER_ID");
$header_info['comm'] = ociresult($stid, "COMMODITY_CODE");
$header_info['DO_num'] = ociresult($stid, "DONUM");
$header_info['carrier'] = ociresult($stid, "CAR_NAME");
$header_info['ship_to'] = ociresult($stid, "SHIP_NAME");
$header_info['add1'] = ociresult($stid, "ADDRESS_1");
$header_info['add2'] = ociresult($stid, "ADDRESS_2");
$header_info['city'] = ociresult($stid, "CITY");
$header_info['state'] = ociresult($stid, "STATE");
$header_info['zip'] = ociresult($stid, "ZIP");
$header_info['driver'] = ociresult($stid, "DRIVER_NAME");
$header_info['lic_num'] = ociresult($stid, "LICENSE_NUM");
$header_info['lic_state'] = ociresult($stid, "LICENSE_STATE");
$header_info['clerk'] = ociresult($stid, "CLERK_INITIALS");
$header_info['trucker'] = ociresult($stid, "TRUCKING_COMPANY");
$header_info['remark'] = ociresult($stid, "REMARK_PICKLIST");
$header_info['custname'] = GetCustomerName($header_info['cust'], $rfconn);
$header_info['commname'] = GetCommodityName($header_info['comm'], $rfconn);
$header_info['vesname'] = $header_info['arrival'] . " - " . GetVesselName($header_info['arrival'], $rfconn);

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<thead>
		<tr>
			<td colspan="7">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<caption>Port of Wilmington Steel Picklist (generated <?php echo date('m/d/Y h:i:s a'); ?>)</caption>
					<tr><td class="label">DO #:</td>
						<td  colspan="2"><?php echo $header_info['DO_num']; ?></td>
						<td rowspan="7">
							<table cellpadding="0">
								<tr><td>Port Order #:</td>
								</tr>
								<tr><td><img src="/functions/barcode.php?codetype=code39&size=40&text=<?php echo $header_info['PORT_num']; ?>" alt="<?php echo $header_info['PORT_num']; ?>" /></td>
<!--								<img src="/barcode/gen_barcode.php?type=C39&code=<?php echo $header_info['PORT_num']; ?>" /> !-->
								</tr>
								<tr><td class="barcode"><?php echo $header_info['PORT_num']; ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>Vessel:</td>
						<td colspan="2"><?php echo $header_info['vesname']; ?></td>
					</tr>
					<tr><td>Commodity:</td>
						<td colspan="2"><?php echo $header_info['commname']; ?></td>
					</tr>
					<tr><td>Customer:</td>
						<td colspan="2"><?php echo $header_info['custname']; ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>Load Date:</td>
						<td colspan="2"><?php echo date('m/d/Y'); ?></td>
					</tr>
					<tr><td>PoW Clerk:</td>
						<td><?php echo $header_info['clerk']; ?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td>
						<td>Main Carrier:</td>
						<td><?php echo $header_info['carrier']; ?></td>
					</tr>
					<tr><td>Driver Name:</td>
						<td><?php echo $header_info['driver']; ?></td>
					</tr>
					<tr><td>Trucking Company:</td>
						<td><?php echo $header_info['trucker']; ?></td>
						<td rowspan="4" valign="top" class="label">Ship To:</td>
						<td rowspan="4"><?php echo $header_info['ship_to'],
														'<br/>', $header_info['add1'],
														'<br/>', $header_info['add2'],
														'<br/>', $header_info['city'] . ', ' . $header_info['state'] . ' ' . $header_info['zip']; ?></td>
					</tr>
					<tr><td>&nbsp;</td>
					<tr><td>State/Prov:</td>
						<td><?php echo $header_info['lic_state']; ?></td>
					<tr><td>License Plate #:</td>
						<td><?php echo $header_info['lic_num']; ?></td>
				</table>
			</td>
		</tr>
		<tr class="fatfont">
			<th>Code</th>
			<th>Pallet ID</th>
			<th>Mark</th>
			<th>Date Received</th>
			<th>Pieces</th>
			<th>lb/piece</th>
			<th>Total lb</th>
		</tr>
	</thead>
	<tbody><?php
$pallet_total = 0;
$weight_total = 0;
$table_counter = 0;

$plts = GetAvailablePallets($header_info, $rfconn);

if (!ocifetch($plts)) {
?>		<td colspan="7" class="alert">No In-House pallets showing as available.</td><?php
} else {
	
	do {?>
		<tr>
			<td><?php echo ociresult($plts, "WAREHOUSE_LOCATION"); ?></td>
			<td><?php echo ociresult($plts, "PALLET_ID"); ?></td>
			<td><?php echo ociresult($plts, "CARGO_DESCRIPTION"); ?></td>
			<td><?php echo ociresult($plts, "DATE_REC"); ?></td>
			<td><?php echo ociresult($plts, "QTY_IN_HOUSE"); ?></td>
			<td><?php echo number_format(ociresult($plts, "WT_PER")); ?></td>
			<td><?php echo number_format(ociresult($plts, "QTY_IN_HOUSE") * round(ociresult($plts, "WT_PER"))); ?></td>
		</tr><?php
		$table_counter++;
		$pallet_total += ociresult($plts, "QTY_IN_HOUSE");
		$weight_total += ociresult($plts, "QTY_IN_HOUSE") * round(ociresult($plts, "WT_PER"));

	} while(ocifetch($plts));
?>		<tr>
			<td class="fatfont" colspan="4">Totals:</td>
			<td class="fatfont"><?php echo $pallet_total; ?></td>
			<td class="fatfont">&nbsp;</td>
			<td class="fatfont"><?php echo number_format($weight_total); ?></td>
		</tr>
	</tbody>
</table><?php
}

// include("pow_footer.php");
exit;

	
	
	
//FUNCTIONS

function GetCount($port_num, $rfconn)
{
	$sql = "SELECT
				COUNT(*) THE_COUNT
			FROM
				STEEL_ORDERS
			WHERE
				PORT_ORDER_NUM = '$port_num'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return ociresult($stid, "THE_COUNT");
}

function GetOrderData($port_num, $rfconn)
{
	$sql = "SELECT
				CUSTOMER_ID,
				LR_NUM,
				COMMODITY_CODE,
				SO.DONUM,
				NVL(SC.NAME, 'NOT SELECTED') CAR_NAME,
				SST.NAME SHIP_NAME,
				ADDRESS_1,
				ADDRESS_2,
				CITY,
				STATE,
				ZIP,
				DRIVER_NAME,
				LICENSE_NUM,
				LICENSE_STATE,
				CLERK_INITIALS,
				TRUCKING_COMPANY,
				REMARK_PICKLIST
			FROM
				STEEL_ORDERS SO,
				STEEL_PRELOAD_DO_INFORMATION SPDI,
				STEEL_SHIPPING_TABLE SST,
				STEEL_CARRIERS SC
			WHERE
				SO.DONUM = SPDI.DONUM
				AND SC.CARRIER_ID(+) = SPDI.CARRIER_ID
				AND SST.SHIP_TO_ID = SPDI.SHIP_TO_ID
				AND SO.PORT_ORDER_NUM = '$port_num'";
	// echo "<pre>$sql</pre>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return $stid;
}

function GetCustomerName($cust_id, $rfconn)
{
	$sql = "SELECT
				CUSTOMER_NAME
			FROM
				CUSTOMER_PROFILE
			WHERE
				CUSTOMER_ID = '$cust_id'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return ociresult($stid, "CUSTOMER_NAME");
}

function GetCommodityName($comm, $rfconn)
{
	$sql = "SELECT
				COMMODITY_NAME
			FROM
				COMMODITY_PROFILE
			WHERE
				COMMODITY_CODE = '$comm'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return ociresult($stid, "COMMODITY_NAME");
}

function GetVesselName($vessel, $rfconn)
{
$sql = "SELECT
			VESSEL_NAME
		FROM
			VESSEL_PROFILE
		WHERE
			TO_CHAR(LR_NUM) = '$vessel'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return ociresult($stid, "VESSEL_NAME");
}

function GetAvailablePallets($data_arr, $rfconn)
//Returns an Oracle SQL object that contains a result set of pallets in-house for a specified order.
{
	$sql = "SELECT
				PALLET_ID,
				CARGO_DESCRIPTION,
				WAREHOUSE_LOCATION,
				QTY_IN_HOUSE,
				(WEIGHT / QTY_RECEIVED) WT_PER,
				NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NR') DATE_REC
			FROM
				CARGO_TRACKING
			WHERE
				RECEIVER_ID = '".$data_arr['cust']."'
				AND ARRIVAL_NUM = '".$data_arr['arrival']."'
				AND REMARK = '".$data_arr['DO_num']."'
				AND QTY_IN_HOUSE > 0";
	// echo "<pre>$sql</pre>";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}
?>