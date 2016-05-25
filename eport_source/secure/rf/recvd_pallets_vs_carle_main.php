<?php
/*
*	Charles Marttinen, April 2015
*
*	Report that ??
*
************************************************************************/

$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
$user = $HTTP_COOKIE_VARS["eport_user"];

$url_this_page = 'recvd_pallets_vs_carle.php';
// $url_this_page = 'recvd_pallets_vs_carle_test.php';

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
// rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";
if($rfconn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

//Get form values
$submit = $_POST['submit'];
$vessel = $_POST['vessel'];
$report_type = $_POST['report_type'];
$load_port = $_POST['load_port'];
$exporter = $_POST['exporter'];
$commodity = $_POST['commodity'];
$hatch = strtoupper($_POST['hatch']);
$hatch_cont = $_POST['hatch_cont'];
$hatch_not_cont = $_POST['hatch_not_cont'];
$hatch_other = $_POST['hatch_other'];
if ($hatch_cont != '') $hatch = 'CONTAINER';
if ($hatch_not_cont != '') $hatch = 'BELOW DECK';
if ($hatch_other != '') $hatch = 'OTHER';

if ($vessel == '' && $submit != '') {
	echo '<font size="3" face="Verdana" color="red">Please select a vessel.</font><br/>';
	$submit = '';
	$report_type = '';
}
if ($user == 'tcval') $load_port = 'VALPARAISO';


PrintTitle('Received Pallets vs Carle File');

//FORM
PrintOpenTable(0); //with a 0 width border

PrintOpenForm($url_this_page);
PrintReportMenu($report_type);
$ves_name = PrintVesselMenu($vessel, $rfconn); //print vessel menu AND get the *name* of the selected vessel

switch ($report_type) {
	case 'incorrect_receiver':
		//fall-through to next line
	case 'not_received':
		if ($user == 'tcval') {
			PrintHiddenControl('load_port', 'VALPARAISO');
		} else PrintLoadingPortMenu($vessel, $load_port, $rfconn);
		PrintExporterMenu($vessel, $exporter, $rfconn);
		PrintCommodityMenu($vessel, $report_type, $commodity, $rfconn);
		PrintField('Hatch', $hatch);
		break;
		
	case 'not_in_carle':
		PrintCommodityMenu($vessel, $report_type, $commodity, $rfconn);
		PrintField('Hatch', $hatch);
		break;
}

PrintSubmitButton('Retrieve Data', 'submit');
PrintCloseForm();

PrintOpenForm($url_this_page);
PrintSubmitButton('Clear Filter', 'clear');
PrintCloseForm();

PrintCloseTable();


//When you press the Retrieve Data button
if ($submit == 'Retrieve Data') {
	$total_pallet_count = GetTotalPalletCount($vessel, $rfconn);
	$total_carle_count = GetTotalCarlePalletCount($vessel, $rfconn);
	PrintTipnTotals($total_pallet_count, $total_carle_count, $vessel, $report_type);
	
	PrintOpenTable();
	
	$i = 0;
	switch ($report_type) {
		case "not_in_carle":
			$cols = 8;
			$msg = "<b>Pallets Received, but Not in Carle File for $ves_name</b>";
			PrintHeaderRow($msg, $cols);
			PrintRow('<b>Hatch</b>',
					 '<b>Container ID</b>',
					 '<b>Receiver/Consignee</b>',
					 '<b>Barcode</b>',
					 '<b>Time Scanned</b>',
					 '<b>Cases</b>',
					 '<b>Location</b>',
					 '<b>Commodity</b>'
					 );
			$data = GetData_NotInCarle($vessel, $commodity, $hatch, $rfconn);
			while(ocifetch($data)) {
				PrintRow(ociresult($data, 'THE_HATCH'),
						 ociresult($data, 'THE_CONTAINER'),
						 ociresult($data, 'RECEIVER'),
						 ociresult($data, 'BARCODE'),
						 ociresult($data, 'TIME_SCANNED'),
						 ociresult($data, 'CASES'),
						 ociresult($data, 'LOCATION'),
						 ociresult($data, 'COMMODITY')
						 );
				$i++;
			}
			break;
			
		case "not_received":
			$cols = 10;
			$msg = "<b>Pallets in Carle, but Not Received for $ves_name</b>";
			PrintHeaderRow($msg, $cols);
			PrintRow('<b>Voyage</b>',
					 '<b>Loading Port</b>',
					 '<b>Exporter</b>',
					 '<b>Receiver/Consignee</b>',
					 '<b>Hatch</b>',
					 '<b>Container</b>',
					 '<b>Barcode</b>',
					 '<b>Row#</b>',
					 '<b>Cases</b>',
					 '<b>Commodity</b>');
			$data = GetData_NotReceived($vessel, $load_port, $exporter, $commodity, $hatch, $rfconn);
			while(ocifetch($data)) {
				PrintRow(ociresult($data, 'VOYAGE'),
						 ociresult($data, 'LOADING_PORT'),
						 ociresult($data, 'THE_EXPORTER'),
						 ociresult($data, 'CONSIGNEE'),
						 ociresult($data, 'HATCH'),
						 ociresult($data, 'CONTAINER'),
						 ociresult($data, 'BARCODE'),
						 ociresult($data, 'THE_ROW_NUM'),
						 ociresult($data, 'CASES'),
						 ociresult($data, 'COMMODITY')
						 );
				$i++;
			}
			break;
			
		case "incorrect_receiver":
			$cols = 11;
			$msg = "<b>Pallets in the Carle File with an Incorrect Receiver for $ves_name</b>";
			PrintHeaderRow($msg, $cols);
			PrintRow('<b>Loading Port</b>',
					 '<b>Exporter</b>',
					 '<b>Carle File Consignee</b>',
					 '<b>Consignee Name</b>',
					 '<b>Actual Consignee</b>',
					 '<b>Hatch</b>',
					 '<b>Container ID</b>',
					 '<b>POW Scan Time</b>',
					 '<b>Barcode</b>',
					 '<b>Cases</b>',
					 '<b>Commodity</b>');
			$data = GetData_IncorrectReceiver($vessel, $load_port, $exporter, $commodity, $hatch, $rfconn);
			while(ocifetch($data)) {
				PrintRow(ociresult($data, 'LOADING_PORT'),
						 ociresult($data, 'EXPORTER'),
						 ociresult($data, 'CARLE_CONSIGNEE_CODE'),
						 ociresult($data, 'CARLE_RECEIVER'),
						 ociresult($data, 'CT_RECEIVER'),
						 ociresult($data, 'HATCH'),
						 ociresult($data, 'CONTAINER'),
						 ociresult($data, 'SCANNED_TIME'),
						 ociresult($data, 'BARCODE'),
						 ociresult($data, 'CASES'),
						 ociresult($data, 'COMMODITY')
						 );
				$i++;
			}
			break;
	}
	if ($i > 0) PrintTotalsRow($i, $cols);
	elseif ($i != -1) {
		$msg = 'No results were returned for the selected filter.<br/>Please select a new filter and try again.';
		PrintErrorRow($msg, $cols);
	}
	PrintCloseTable;
}

include("pow_footer.php");
exit;










////////////////////////////////////////////////
//"PRINT" FUNCTIONS

function PrintTitle($string)
{
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC"><?php echo $string; ?></font>
         <hr>
      </td>
   </tr>
</table>
<?php
}

function PrintOpenTable($border_width = 1)
{
?>
<table border="<?php echo $border_width; ?>" width="100%" cellpadding="4" cellspacing="0">
<?php
}

function PrintCloseTable()
{
?>
</table>
<?php
}

function PrintOpenForm($link)
{
?>
<form name="the_form" action="<?php echo $link; ?>" method="post">
<?php
}

function PrintCloseForm()
{
?>
</form>
<?php
}

function PrintHiddenControl($name, $value)
{
?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
<?php
}

function PrintVesselMenu($vessel, $rfconn)
//Also RETURNS the name of the selected vessel
{
?>
	<tr>
		<td><font size="2" face="Verdana">Vessel:</font></td>
		<td><select name="vessel">
			<option value="" selected>&mdash;Select a vessel&mdash;</option>
<?php
	$all_vessels = GetVessels($rfconn);
	while(ocifetch($all_vessels)) {
		$this_ves = ociresult($all_vessels, "ARRIVAL_NUM");
		$this_ves_name = ociresult($all_vessels, "THE_VESSEL");
		if($this_ves == $vessel) $ves_name = $this_ves_name; 
?>
			<option value="<?php echo $this_ves; ?>"<?php if($this_ves == $vessel) { ?> selected<?php } ?>><?php echo $this_ves_name; ?></option>
<?php
	}
?>
		</select></td>
	</tr>
<?php
	return $ves_name;
}

function PrintReportMenu($report_type)
{
?>
	<tr>
		<td><font size="2" face="Verdana">Report Type:</font></td>
		<td><select name="report_type">
			<option value="not_in_carle" <?php if($report_type == 'not_in_carle') { ?> selected <?php } ?>>Received, but not in Carle</option>
			<option value="not_received" <?php if($report_type == 'not_received') { ?> selected <?php } ?>>In Carle, but not received</option>
			<option value="incorrect_receiver" <?php if($report_type == 'incorrect_receiver') { ?> selected <?php } ?>>Incorrect receiver in Carle</option>
		</select></td>
	</tr>
<?php
}

function PrintLoadingPortMenu($vessel, $load_port, $rfconn)
{
?>
	<tr>
		<td><font size="2" face="Verdana">Loading Port:</font></td>
		<td><select name="load_port">
			<option value="" selected>ALL</option>
<?php
	$list = GetLoadPorts($vessel, $rfconn);
	while(ocifetch($list)) {
		$this = ociresult($list, "LOADING_PORT");
?>
			<option value="<?php echo $this; ?>"<?php if($this == $load_port) { ?> selected<?php } ?>><?php echo $this; ?></option>
<?php } ?>
		</select></td>
	</tr>
<?php
}

function PrintExporterMenu($vessel, $exporter, $rfconn)
{
?>
	<tr>
		<td><font size="2" face="Verdana">Exporter:</font></td>
		<td><select name="exporter">
			<option value="" selected>ALL</option>
<?php
	$list = GetExporters($vessel, $rfconn);
	while(ocifetch($list)) {
		$code = ociresult($list, "EXPORTER_CODE");
		$name = ociresult($list, "EXPORTER");
?>
			<option value="<?php echo $code; ?>"<?php if($code == $exporter) { ?> selected<?php } ?>><?php echo $name; ?></option>
<?php } ?>
		</select></td>
	</tr>
<?php
}

function PrintCommodityMenu($vessel, $report_type, $commodity, $rfconn)
{
?>
	<tr>
		<td><font size="2" face="Verdana">Commodity:</font></td>
		<td><select name="commodity">
			<option value="" selected>ALL</option>
<?php
	$list = GetCommodities($vessel, $report_type, $rfconn);
	while(ocifetch($list)) {
		$code = ociresult($list, "COMMODITY_CODE");
		$name = ociresult($list, "COMMODITY_NAME");
?>
			<option value="<?php echo $code; ?>"<?php if($code == $commodity) { ?> selected<?php } ?>><?php echo $name; ?></option>
<?php } ?>
		</select></td>
	</tr>
<?php
}

function PrintField($title, $value)
{
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $title; ?>:</font></td>
			<td><input type="text" name="hatch" value="<?php echo $value; ?>"> or 
			<input type="checkbox" name="hatch_cont">Container, or
			<input type="checkbox" name="hatch_not_cont">Below-deck, or
			<input type="checkbox" name="hatch_other">Other
		</td>
	</tr>
<?php
}

function PrintSubmitButton($value, $name)
{
?>
	<tr><td><input type="submit" name="<?php echo $name; ?>" value="<?php echo $value; ?>"></td></tr>
<?php
}

function PrintTipnTotals($total_pallet_count, $total_carle_count, $vessel, $report_type)
{
?>
<br/>
<table>
	<tr>
		<td><font size="2" face="Verdana">
			<b><?php echo $total_pallet_count; ?></b> pallets were scanned at the Port of Wilmington from vessel <?php echo $vessel?>.
		</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">
			<b><?php echo $total_carle_count; ?></b> pallets were listed in the Carle file for vessel <?php echo $vessel?>.
		</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td><font size="2" face="Verdana">Tip: Press Ctrl + F to search the page.</font></td>
	</tr>
</table>
<br/>
<?php
}

function PrintHeaderRow($msg, $colspan = '1')
{
?>
	<tr>
		<td colspan="<?php echo $colspan; ?>" align="center"><font size="3" face="Verdana"><?php echo $msg; ?></font></td>
	</tr>
<?php
}

function PrintRow()
//Accepts a variable number of arguments
{
?>
	<tr>
<?php
	foreach (func_get_args() as $value) {
?>
		<td><font size="2" face="Verdana"><?php echo $value; ?></font></td>
<?php
	}
?>
	</tr>
<?php
}

function PrintTotalsRow($rows, $colspan = 1)
{
?>
	<tr>
		<td colspan="<?php echo $colspan; ?>" align="right"><font size="3" face="Verdana"><b>Total Rows: <?php echo $rows; ?></b></font></td>
	</tr>
<?php
}

function PrintErrorRow($msg, $colspan = 1)
{
?>
<tr>
	<td colspan="<?php echo $colspan; ?>"><font size="3" face="Verdana" color="red"> <?php echo $msg; ?></font>
	</td>
</tr>
<?php
}








///////////////////////////////////
//"GET" FUNCTIONS


function GetVessels($rfconn)
//Returns an Oracle SQL object that contains a result set of all CHILEAN vessels.
{
	$sql = "select distinct LR_NUM as ARRIVAL_NUM,
				LR_NUM || ' - ' || VP.VESSEL_NAME as THE_VESSEL
			from ORIGINAL_MANIFEST_DETAILS_V2 OM2
			join ORIGINAL_MANIFEST_HEADER OMH
				on OM2.TRANSACTION_ID = OMH.TRANSACTION_ID
			join VESSEL_PROFILE VP
				on VP.ARRIVAL_NUM = OMH.LR_NUM
			where OMH.PUSHED_TO_CT = 'Y'
				and OMH.LR_NUM in
					(select ARRIVAL_NUM
					 from VESSEL_PROFILE
					 where SHIP_PREFIX = 'CHILEAN')
				and OMH.LR_NUM >= '12755'
			order by LR_NUM desc";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

function GetLoadPorts($arrival_num, $rfconn)
//Returns an Oracle SQL object that contains a result set of valid loading ports for the selected vessel (arrival#)
{
	if ($arrival_num != '') $ar_sql = "and H.LR_NUM = '".$arrival_num."'";
	$sql = "select distinct D.LOADING_PORT
			from ORIGINAL_MANIFEST_DETAILS_V2 D
			join ORIGINAL_MANIFEST_HEADER H
				on H.TRANSACTION_ID = D.TRANSACTION_ID
			where D.LOADING_PORT is not null
			$ar_sql
			order by D.LOADING_PORT";
	// echo "<pre>$sql</pre>";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

function GetExporters($arrival_num, $rfconn)
//Returns an Oracle SQL object that contains a result set of exporters for the selected vessel
{
		if ($arrival_num != '') $ar_sql = "and H.LR_NUM = '".$arrival_num."'";
	$sql = "select distinct D.EXPORTER,
				D.EXPORTER_CODE
			from ORIGINAL_MANIFEST_DETAILS_V2 D
			join ORIGINAL_MANIFEST_HEADER H
				on D.TRANSACTION_ID = H.TRANSACTION_ID
			$ar_sql
			order by D.EXPORTER";
	// echo "<pre>$sql</pre>";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

/*function GetReceivers($arrival_num, $rfconn)
//Returns an Oracle SQL object that contains a result set of exporters for the selected vessel INCOMPLETE
{
		if ($arrival_num != '') $ar_sql = "and H.LR_NUM = '".$arrival_num."'";
	$sql = "select distinct CP.CUSTOMER_NAME as RECEIVER,
				CT.RECEIVER_ID
			from CARGO_TRACKING CT
			left join CUSTOMER_PROFILE CP
				on CP.CUSTOMER_ID = CT.RECEIVER_ID
			join COMMODITY_PROFILE CMP
				on CMP.COMMODITY_CODE = CT.COMMODITY_CODE
			where CT.PALLET_ID in
					(select PALLET_ID
					from CARGO_TRACKING
					where DATE_RECEIVED is not null
						and ARRIVAL_NUM = '12755'
					minus
					select PALLET
					from ORIGINAL_MANIFEST_DETAILS_V2
					where TRANSACTION_ID in 
						(select TRANSACTION_ID
						from ORIGINAL_MANIFEST_HEADER
						where LR_NUM = '12755'))
				and CT.RECEIVER_ID <> '9722'
				and CT.ARRIVAL_NUM = '12755'
			order by RECEIVER_ID";
	// echo "<pre>$sql</pre>";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}*/

function GetCommodities($arrival_num, $report_type, $rfconn)
//Returns an Oracle SQL object that contains a result set of commodities that match the 
//corresponding arrival# and report type.
{
	switch ($report_type) {
		case 'not_received':
			if ($arrival_num != '') $ar = "and H.LR_NUM = '".$arrival_num."'";
			$sql = "select distinct CP.COMMODITY_NAME,
						CP.COMMODITY_CODE
					from ORIGINAL_MANIFEST_DETAILS_V2 D
					left join COMMODITY_MAP_DEL CMD
						on CMD.COD_FRU = D.COMMODITY_CODE_FILE
					left join COMMODITY_PROFILE CP
						on CMD.COMMODITY_CODE = CP.COMMODITY_CODE
					left join ORIGINAL_MANIFEST_HEADER H
						on H.TRANSACTION_ID = D.TRANSACTION_ID
					where CP.COMMODITY_TYPE = 'CHILEAN'
						$ar
					order by CP.COMMODITY_NAME";
			break;
		default: //all other cases
			if ($arrival_num != '') $ar = "and CT.ARRIVAL_NUM = '".$arrival_num."'";
			$sql = "select distinct CP.COMMODITY_NAME,
						CP.COMMODITY_CODE
					from COMMODITY_PROFILE CP
					left join CARGO_TRACKING CT
						on CT.COMMODITY_CODE = CP.COMMODITY_CODE
					where CP.COMMODITY_TYPE = 'CHILEAN'
						$ar
					order by CP.COMMODITY_NAME";
			break;
	}
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

function GetTotalPalletCount($vessel, $rfconn)
//Returns the total number of pallets that have been scanned from the given vessel
{
	$sql = "select count(distinct PALLET_ID) as TOTAL_PALLETS
			from CARGO_TRACKING
			where ARRIVAL_NUM = '".$vessel."'
				and DATE_RECEIVED is not null
				and RECEIVER_ID <> '9722'";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	ocifetch($result);
	return ociresult($result, "TOTAL_PALLETS");
}

function GetTotalCarlePalletCount($vessel, $rfconn)
//Returns the total number of pallets that were listed in the Carle file for the given vessel
{
	$sql = "select count (distinct D.PALLET) as PALLETS_IN_CARLE
			from ORIGINAL_MANIFEST_DETAILS_V2 D
			join ORIGINAL_MANIFEST_HEADER H
				on D.TRANSACTION_ID = H.TRANSACTION_ID
			where H.LR_NUM = '".$vessel."'";
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	ocifetch($result);
	return ociresult($result, "PALLETS_IN_CARLE");
}

function GetData_NotInCarle($arrival_num, $commodity, $hatch, $rfconn)
//Returns an Oracle SQL object that contains a result set of pallets that have been received at POW
//but are NOT listed in the Carle file.
{
	if ($commodity != '') $commmidity_sql = "and CT.COMMODITY_CODE = '".$commodity."'";
	if ($hatch != '') $hatch_sql = "and CT.HATCH = '".$hatch."'";
	if ($hatch == 'CONTAINER') $hatch_sql = "and (CT.CONTAINER_ID is not null
												  or CT.HATCH = 'CONT'
												  or CT.HATCH = '0D')";
	elseif ($hatch == 'BELOW DECK') $hatch_sql = "and CT.CONTAINER_ID is null
												  and CT.HATCH in ('1A', '1B', '1C', '1D',
																   '2A', '2B', '2C', '2D',
																   '3A', '3B', '3C', '3D',
																   '4A', '4B', '4C', '4D')";
	elseif ($hatch == 'OTHER') $hatch_sql = "and CT.HATCH not in ('1A', '1B', '1C', '1D',
																  '2A', '2B', '2C', '2D',
																  '3A', '3B', '3C', '3D',
																  '4A', '4B', '4C', '4D',
																  '0D', 'CONT')
											 and CT.CONTAINER_ID is null";
	
	$sql = "select nvl(CT.CONTAINER_ID, '&mdash;') as THE_CONTAINER,
				nvl(decode(CT.HATCH, '0D', 'CONT', CT.HATCH), '&nbsp;') as THE_HATCH,
				CP.CUSTOMER_NAME as RECEIVER,
				CT.PALLET_ID as BARCODE,
				to_char(CT.DATE_RECEIVED, 'MM/DD/YY HH:MI:SS AM') as TIME_SCANNED,
				CT.QTY_RECEIVED as CASES,
				nvl(CT.WAREHOUSE_LOCATION, '&nbsp;') as LOCATION,
				CMP.COMMODITY_NAME as COMMODITY
			from CARGO_TRACKING CT
				left join CUSTOMER_PROFILE CP
					on CP.CUSTOMER_ID = CT.RECEIVER_ID
				join COMMODITY_PROFILE CMP
					on CMP.COMMODITY_CODE = CT.COMMODITY_CODE
			where CT.ARRIVAL_NUM = '".$arrival_num."'
				and CT.PALLET_ID in
					(select PALLET_ID
					from CARGO_TRACKING
					where ARRIVAL_NUM = '".$arrival_num."'
						and DATE_RECEIVED is not null
					minus
					select PALLET
					from ORIGINAL_MANIFEST_DETAILS_V2
					where TRANSACTION_ID in 
						(select TRANSACTION_ID
						from ORIGINAL_MANIFEST_HEADER
						where LR_NUM = '".$arrival_num."'))
					$commmidity_sql $hatch_sql and CT.RECEIVER_ID <> '9722'
			order by CT.ARRIVAL_NUM,
				CT.CONTAINER_ID asc nulls first,
				THE_HATCH asc nulls first,
				CT.PALLET_ID,
				CT.DATE_RECEIVED,
				CT.QTY_RECEIVED,
				CT.WAREHOUSE_LOCATION";
	// echo "<pre>$sql</pre>";
	
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

function GetData_NotReceived($arrival_num, $load_port, $exporter, $commodity, $hatch, $rfconn)
//Returns an Oracle SQL object that contains a result set of pallets that have NOT been received at POW
//but ARE listed in the Carle file (the reverse of GetData_NotInCarle()).
{
	if ($commodity != '') $commmidity_sql = "and CMD.COMMODITY_CODE = '".$commodity."'";
	if ($hatch != '') $hatch_sql = "and D.HATCH || D.DECK = '".$hatch."'";
	if ($hatch == 'CONTAINER') $hatch_sql = "and D.CONTAINER is not null";
	elseif ($hatch == 'BELOW DECK') $hatch_sql = "and D.CONTAINER is null";
	if ($load_port != '') $load_port_sql = "and D.LOADING_PORT = '".$load_port."'";
	if ($exporter != '') $exporter_sql = "and D.EXPORTER_CODE = '".$exporter."'";
	
	$sql = "select D.VOYAGE,
				D.LOADING_PORT,
				D.EXPORTER || ' ' || D.EXPORTER_CODE as THE_EXPORTER,
				D.CONSIGNEE,
				D.PALLET as BARCODE,
				max(D.ROW_NUM) as THE_ROW_NUM,
				D.CASES,
				D.COMMODITY,
				D.HATCH || nvl(D.DECK, 'CONT') as HATCH,
				nvl(D.CONTAINER, '&mdash;') as CONTAINER
			from ORIGINAL_MANIFEST_DETAILS_V2 D
			join ORIGINAL_MANIFEST_HEADER H
				on H.TRANSACTION_ID = D.TRANSACTION_ID
			join COMMODITY_MAP_DEL CMD
				on CMD.COD_FRU = D.COMMODITY_CODE_FILE
			where D.PALLET in
					(select PALLET
					from ORIGINAL_MANIFEST_DETAILS_V2 D2
					join ORIGINAL_MANIFEST_HEADER H2
						on D2.TRANSACTION_ID = H2.TRANSACTION_ID
					where H2.LR_NUM = '".$arrival_num."'
					minus
					select PALLET_ID
					from CARGO_TRACKING
					where ARRIVAL_NUM = '".$arrival_num."'
						and DATE_RECEIVED is not null)
				$commmidity_sql $load_port_sql $exporter_sql $hatch_sql and H.LR_NUM = '".$arrival_num."'
			group by 
				D.PALLET,
				D.VOYAGE,
				D.LOADING_PORT,
				D.EXPORTER,
				D.EXPORTER_CODE,
				D.CONSIGNEE,
				D.CASES,
				H.LR_NUM,
				D.COMMODITY,
				D.HATCH,
				D.DECK,
				D.CONTAINER
			order by D.VOYAGE,
				D.LOADING_PORT,
				D.EXPORTER,
				D.CONSIGNEE,
				BARCODE,
				D.CASES";
	// echo "<pre>$sql</pre>";
	
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}

function GetData_IncorrectReceiver($arrival_num, $load_port, $exporter, $commodity, $hatch, $rfconn)
//Returns an Oracle SQL object that contains a result set of pallets that are listed in the Carle file and have
//been received at POW, but have the incorrect receiver listed in the Carle file.
{
	if ($commodity != '') $commmidity_sql = "and CT.COMMODITY_CODE = '".$commodity."'";
	if ($hatch != '') $hatch_sql = "and D.HATCH || D.DECK = '".$hatch."'";
	if ($hatch == 'CONTAINER') $hatch_sql = "and D.CONTAINER is not null";
	elseif ($hatch == 'BELOW DECK') $hatch_sql = "and D.CONTAINER is null";
	if ($load_port != '') $load_port_sql = "and D.LOADING_PORT = '".$load_port."'";
	if ($exporter != '') $exporter_sql = "and D.EXPORTER_CODE = '".$exporter."'";
	
	$sql = "select distinct D.PALLET as BARCODE,
				CP1.CUSTOMER_NAME as CT_RECEIVER,
				decode(to_char(D.CONSIGNEE_CODE), '0', '&mdash;', D.CONSIGNEE) as CARLE_RECEIVER,
				D.CONSIGNEE_CODE as CARLE_CONSIGNEE_CODE,
				to_char(CT.DATE_RECEIVED, 'YYYY/MM/DD HH:MI:SS AM') as SCANNED_TIME,
				CT.QTY_RECEIVED as CASES,
				CMP.COMMODITY_NAME as COMMODITY,
				D.EXPORTER,
				D.LOADING_PORT,
				D.HATCH || nvl(D.DECK, 'CONT') as HATCH,
				nvl(D.CONTAINER, '&mdash;') as CONTAINER
			from ORIGINAL_MANIFEST_DETAILS_V2 D
			left join CHILEAN_CUSTOMER_MAP_V2 CC
				on CC.CHILEAN_CONSIGNEE_CODE = D.CONSIGNEE_CODE
			join CARGO_TRACKING CT
				on CT.PALLET_ID = D.PALLET
			left join CUSTOMER_PROFILE CP1
				on CT.RECEIVER_ID = CP1.CUSTOMER_ID
			left join CUSTOMER_PROFILE CP2
				on CC.RECEIVER_ID = CP2.CUSTOMER_ID
			join COMMODITY_PROFILE CMP
				on CMP.COMMODITY_CODE = CT.COMMODITY_CODE
			where D.TRANSACTION_ID in
					(select TRANSACTION_ID
					 from ORIGINAL_MANIFEST_HEADER
					 where LR_NUM = '".$arrival_num."')
				and (CC.RECEIVER_ID is null or CT.RECEIVER_ID <> CC.RECEIVER_ID)
				and CT.DATE_RECEIVED is not null
				$commmidity_sql $load_port_sql $exporter_sql $hatch_sql and CT.RECEIVER_ID <> '9722'
			order by D.EXPORTER,
				CARLE_RECEIVER,
				CT_RECEIVER";
	// echo "<pre>$sql</pre>";
	
	$result = ociparse($rfconn, $sql);
	ociexecute($result);
	return $result;
}
?>