<?php
/*
*	Charles Marttinen, April 2015
*
*	Report that gets all the Walmart pallets for a given filter
*
************************************************************************/

// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Inventory System - Walmart";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
	printf("Access Denied from INVE system");
	include("pow_footer.php");
	exit;
}

//$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
 $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";
if($rfconn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

$url_this_page = 'WM_PO_InOut.php';
$url_details_page = 'WM_PO_InOut_details.php';
$customer = 3000;

//Validate and copy form values into variables
$item_num = $_GET['BOL'];
//We are making the assumption that any values passed in through the URL are valid
if ($item_num != '') {
	$received_status = $_GET['RcvdSts'];
	$cargo_type = 8;
	$submit = "Retrieve Data";
}
else {
	$submit = $_POST['submit'];
	$vessel = $_POST['vessel'];
	$cargo_type = $_POST['cargo_type'];
	$service_code = $_POST['service_code'];
	$received_status = $_POST['received_status'];
	
	$bpo_num = $_POST['bpo_num'];
	$bpo_num_freef = $_POST['bpo_num_freef'];
	
	$item_num = $_POST['item_num'];
	$item_num_freef = $_POST['item_num_freef'];
	
	if ($bpo_num_freef != '') $bpo_num = $bpo_num_freef;
	if ($bpo_num != '' && !DoesBPOExist($customer, $bpo_num, $rfconn)) {
		echo '<font size="2" face="Verdana" color="FF0000">The BPO number you have entered is not valid.<br/>Please note that BPO numbers for which no pallets have yet been delivered are considered invalid.</font>';
		$submit = '';
	}
	
	if ($item_num_freef != '') $item_num = $item_num_freef;
	if ($item_num != '' && !DoesWMItemExist($customer, $item_num, $rfconn)) {
		echo '<font size="2" face="Verdana" color="FF0000">The Walmart Item number you have entered is not valid.</font>';
		$submit = '';
	}
}


// echo "Submit: $submit<br/>Vessel: $vessel<br/>Service Code: $service_code<br/>Cargo Type: $cargo_type<br/>BPO#: $bpo_num<br/>Item#: $item_num<br/>Customer: $customer";
?>

<!--Title-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">BPO In/Out Report</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<!--Form-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<form name="the_form" action="<?php echo $url_this_page; ?>" method="post">
	<tr>
	
	
	
		<td>Vessel:</td>
		<td><select name="vessel">
			<option value="" selected>ALL</option>
<?php
$all_vessels = GetVessels($customer, $rfconn);
while(ocifetch($all_vessels)) {
?>
			<option value="<?php echo ociresult($all_vessels, "ARRIVAL_NUM"); ?>"
					<?php if(ociresult($all_vessels, "ARRIVAL_NUM") == $vessel) { ?> selected <?php } ?>>
				<?php echo ociresult($all_vessels, "THE_VESSEL"); ?>
			</option>
<?php } ?>
		</select></td>
		
		
		
		
		<td><table border="1" width="100%" cellpadding="2" cellspacing="0" rules="none">
			<tr>
				<td>BPO:</td>
				<td><select name="bpo_num">
					<option value="">ALL</option>
<?php
$all_po_numbers = GetBPOs($customer, $vessel, $cargo_type, '', $service_code, '', $rfconn);
while(ocifetch($all_po_numbers)){
?>
					<option value="<?php echo ociresult($all_po_numbers, "MARK"); ?>"<?php if(ociresult($all_po_numbers, "MARK") == $bpo_num) { ?> selected <?php } ?>>
						<?php echo ociresult($all_po_numbers, "MARK"); ?>
					</option>
<?php } ?>
				</select></td>
			</tr>
			<tr>
				<td>OR&nbsp;freeform:</td>
				<td><input type="text" name="bpo_num_freef" value="<?php echo $bpo_num; ?>"></td>
			</tr>
		</table></td>
		
		
		
		<td><table border="1" width="100%" cellpadding="2" cellspacing="0" rules="none">
			<tr>
				<td>Walmart Item&nbsp;#:</td>
				<td><select name="item_num">
						<option value="" selected>ALL</option>
<?php
$all_item_nums = GetWMItems($customer, $vessel, $cargo_type, '', $service_code, '', $rfconn);
while(ocifetch($all_item_nums)) {
?>
						<option value="<?php echo ociresult($all_item_nums, "BOL"); ?>"<?php if(ociresult($all_item_nums, "BOL") == $item_num): ?> selected <?php endif; ?>>
							<?php echo ociresult($all_item_nums, "BOL"); ?>
						</option>
<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>OR&nbsp;freeform:</td>
				<td><input type="text" name="item_num_freef" value="<?php echo $item_num; ?>"></td>
			</tr>
		</table></td>
		
		
		
	</tr>
	<tr>
	
	
	
		<td>Service Code:</td>
		<td>
			<select name="service_code">
				<option value="" <?php if ($service_code == '') echo "selected"; ?>>ALL</option>
				<option value="1" <?php if ($service_code == '1') echo "selected"; ?>>1 IN</option>
				<option value="6" <?php if ($service_code == '6') echo "selected"; ?>>6 SHIP_OUT</option>
				<option value="9" <?php if ($service_code == '9') echo "selected"; ?>>9 RECOUP</option>
			</select>
		</td>
		
		
		
		<td><table border="0" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td>Cargo Type:</td>
				<td>
					<select name="cargo_type">
						<option value="" selected>ALL</option>
<?php
$all_cargo_types = GetCargoTypes($rfconn);
while(ocifetch($all_cargo_types)){
	$ct_id = ociresult($all_cargo_types, "CARGO_TYPE_ID");
	$ct_name = $ct_id . ' ' . ociresult($all_cargo_types, "WM_PROGRAM");
	?>
						<option value="<?php echo $ct_id; ?>"<?php if($ct_id == $cargo_type) { ?> selected <?php } ?>>
							<?php echo $ct_name; ?>
						</option>
<?php } ?>
					</select>
				</td>
			</tr>
		</table></td>
		
		
		
		<td><table border="0" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td>Received Status:</td>
				<td>
					<select name="received_status">
						<option value="" <?php if ($received_status == '') echo "selected"; ?>>All Received</option>
						<option value="a1" <?php if ($received_status == 'a1') echo "selected"; ?>>In-House, >10 cases</option>
						<option value="a2" <?php if ($received_status == 'a2') echo "selected"; ?>>In-House, >0 and ≤10 cases</option>
						<option value="b" <?php if ($received_status == 'b') echo "selected"; ?>>Not Scanned</option>
						<option value="c" <?php if ($received_status == 'c') echo "selected"; ?>>In-House + Not Scanned</option>
					</select>
				</td>
			</tr>
		</table></td>
		
		
		
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Data"></td>
	</tr>
	</form>
	<tr>
		<td>
			<form name="the_form" action="<?php echo $url_this_page; ?>" method="post">
				<input type="submit" value="Clear Filter">
			</form>
		</td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	

</table>

<?php
if ($submit != '') {
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Click a Pallet ID for more details on that pallet.</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Note: The totals below include only the listed pallets that match the selected filter.</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Tip: Press Ctrl + F to search the page.</font></td>
	</tr>
</table>
<hr>
<?php
}

//++++++++++++++++++++
//If you press Retrieve Data

if($submit != '') {
	PrintOpenTable();
	PrintColHeaders();
	
	$gr_totals = new Counter;
	$ves_totals = new Counter;
	$bpo_totals = new Counter;
	
	$all_data = GetPalletData($customer, $vessel, $bpo_num, $service_code, $item_num, $cargo_type, $received_status, $rfconn);
	while(ocifetch($all_data)) {
		
		//Set values that are used multiple times
		$this_ves = ociresult($all_data, "ARRIVAL_NUM");
		$this_ves_name = $this_ves . ' - ' . ociresult($all_data, "VESSEL_NAME");
		$this_bpo = ociresult($all_data, "BPO");
		$this_serv_code = ociresult($all_data, "THE_SERVICE_CODE");
		$this_qty_chg = ociresult($all_data, "QTY_CHANGE");

		//Subtotals
		if ($last_bpo != '' && $this_bpo != $last_bpo) { //if it's blank, it means there was no last BPO
			PrintBPOTotal($last_bpo, $bpo_totals->GetCaseCount(), $bpo_totals->GetPalletCount(), $service_code);
			$bpo_totals->ResetCounts();
		}
		if ($last_ves != '' && $this_ves != $last_ves) { //likewise
			PrintVesTotal($last_ves_name, $ves_totals->GetCaseCount(), $ves_totals->GetPalletCount());
			$ves_totals->ResetCounts();
		}
		
		//Headers
		if ($this_ves != $last_ves) PrintVesHeader($this_ves_name);
		if ($this_bpo != $last_bpo) PrintBPOHeader($this_bpo);
		
		$the_url = "$url_details_page?id=" . ociresult($all_data, "PALLET_ID") . "&vessel=$this_ves";
		$the_link = '<a href="' . $the_url . '">' . ociresult($all_data, "PALLET_ID") . '</a>';
		
		PrintRow($this_serv_code,
				ociresult($all_data, "WM_ITEM_NUM"),
				ociresult($all_data, "THE_GROWER_NUM"),
				ociresult($all_data, "THE_ORDER_NUM"),
				ociresult($all_data, "CARGO_TYPE"),
				ociresult($all_data, "WAREHOUSE_LOCATION"),
				ociresult($all_data, "THE_ACTIVITY_DATE"),
				ociresult($all_data, "THE_CARGO_STATUS"),
				$the_link,
				$this_qty_chg,
				ociresult($all_data, "QTY_LEFT"));
		
		$gr_totals->AddToPalletCount($this_serv_code);
		$ves_totals->AddToPalletCount($this_serv_code);
		$bpo_totals->AddToPalletCount($this_serv_code);
		
		$gr_totals->AddToCaseCount($this_serv_code, $this_qty_chg);
		$ves_totals->AddToCaseCount($this_serv_code, $this_qty_chg);
		$bpo_totals->AddToCaseCount($this_serv_code, $this_qty_chg);
		
		$last_ves = $this_ves;
		$last_ves_name = $this_ves_name;
		$last_bpo = $this_bpo;
	}
	
	if ($last_bpo != '') {
		PrintBPOTotal($this_bpo, $bpo_totals->GetCaseCount(), $bpo_totals->GetPalletCount());
		PrintVesTotal($this_ves_name , $ves_totals->GetCaseCount(), $ves_totals->GetPalletCount());
		
		//Only show the Grand Totals row if there is more than one vessel
		if ($gr_totals->GetCaseCount() !== $ves_totals->GetCaseCount()) {
			PrintGrTotal($gr_totals->GetCaseCount(), $gr_totals->GetPalletCount());
		}
	}
	else PrintNoResults();
	PrintCloseTable();
	
} //end if

include("pow_footer.php");
exit;






//++++++++++++++++++
//Functions


function GetCargoTypes($rfconn)
//Returns an Oracle SQL object that contains a result set.
{
	$sql = "select * from WM_CARGO_TYPE";
	$all_cargo_types = ociparse($rfconn, $sql);
	ociexecute($all_cargo_types);
	return $all_cargo_types;
}

function GetVessels($customer, $rfconn)
//Returns an Oracle SQL object that contains a result set.
{
	$sql = "select distinct CT.ARRIVAL_NUM || ' - ' || VP.VESSEL_NAME as THE_VESSEL,
				CT.ARRIVAL_NUM
			from CARGO_TRACKING CT
			inner join VESSEL_PROFILE VP
				on CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
			where RECEIVER_ID = '".$customer."'
			order by THE_VESSEL desc";
	$all_vessels = ociparse($rfconn, $sql);
	ociexecute($all_vessels);
	return $all_vessels;
}

function GetWMItems($customer, $vessel, $cargo_type, $item_num, $service_code, $bpo, $rfconn)
//Returns an Oracle SQL object that contains a result set.
{
	$sql = "select DISTINCT BOL
			from CARGO_TRACKING
			where RECEIVER_ID = '".$customer."'";
	if ($vessel != "") $sql .= " and ARRIVAL_NUM = '".$vessel."'";
	if ($cargo_type != "") $sql .= " and CARGO_TYPE_ID = '".$cargo_type."'";
	if ($bpo != "") $sql .= " and MARK = '".$bpo."'";
	if ($item_num != '') $sql .= " and BOL = '".$item_num."'";
	$sql .= " order by BOL";
	// echo "<pre>$sql</pre>";
	
	$all_item_nums = ociparse($rfconn, $sql);
	ociexecute($all_item_nums);
	return $all_item_nums;
}

function DoesWMItemExist($customer, $item_num, $rfconn)
//Returns true if item_num is a valid WM Item# for customer.
{
	$result = GetWMItems($customer, '', '', $item_num, '', '', $rfconn);
	return ocifetch($result);
}

function GetBPOs($customer, $vessel, $cargo_type, $item_num, $service_code, $bpo, $rfconn)
//Returns an Oracle SQL object that contains a result set.
{
	$sql = "select DISTINCT CT.MARK
			from CARGO_TRACKING CT
			join CARGO_ACTIVITY CA
				on CT.PALLET_ID = CA.PALLET_ID
				and CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				and CT.RECEIVER_ID = CA.CUSTOMER_ID
			where CT.RECEIVER_ID = '".$customer."'
				and CA.SERVICE_CODE in (1, 6, 9, 11)
				and (CA.ACTIVITY_DESCRIPTION is null or (CA.ACTIVITY_DESCRIPTION <> 'VOID' and CA.ACTIVITY_DESCRIPTION <> 'RETURN'))";
	if ($vessel != '') $sql .= " and CT.ARRIVAL_NUM = '".$vessel."'";
	if ($bpo != '') $sql .= " and CT.MARK = '".$bpo."'";
	if ($cargo_type != '')  $sql .= " and CT.CARGO_TYPE_ID = '".$cargo_type."'";
	if ($item_num != '') $sql .= " and CT.BOL = '".$item_num."'";
	if ($service_code != '') $sql .= " and CA.SERVICE_CODE = '".$service_code."'";
	$sql .= " order by MARK";
	// echo "<pre>$sql</pre>";
	
	$all_po_numbers = ociparse($rfconn, $sql);
	ociexecute($all_po_numbers);
	return $all_po_numbers;
}

function DoesBPOExist($customer, $bpo, $rfconn)
//Returns true if bpo is a valid BPO# for customer.
{
	$result = GetBPOs($customer, '', '', '', '', $bpo, $rfconn);
	return ocifetch($result);
}

function GetPalletData($customer, $vessel, $bpo, $service_code, $item_num, $cargo_type, $received_status, $rfconn)
//Returns an Oracle SQL object that contains a result set.
{
	if ($cargo_type != '') $cargo_t = "and CT.CARGO_TYPE_ID = '".$cargo_type."'";
	if ($item_num != '') $item_n = "and CT.BOL = '".$item_num."'";
	if ($service_code != '') $serv_c = "and CA.SERVICE_CODE = '".$service_code."'";
	if ($bpo != '') $bpo_x = "and CT.MARK = '".$bpo."'";
	if ($vessel != '') $ves_x = "and CT.ARRIVAL_NUM = '".$vessel."'";
	if ($received_status != '') {
		$rcvd_st = "and CT.ARRIVAL_NUM in 
						(select to_char(LR_NUM) from WDI_VESSEL_RELEASE)
					and (CT.CARGO_STATUS is null or CT.CARGO_STATUS != 'HOLD')
					and (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')";
		if ($received_status == 'a1') {
			$rcvd_st .= " and CT.DATE_RECEIVED is not null
							and CT.QTY_IN_HOUSE > 10";
		}
		elseif($received_status == 'a2') {
			$rcvd_st .= " and CT.DATE_RECEIVED is not null
							and CT.QTY_IN_HOUSE between 1 and 10";
		}
		elseif ($received_status == 'b') {
			$rcvd_st .= " and CT.DATE_RECEIVED is null
							and CT.QTY_IN_HOUSE > 0";
		}
		elseif ($received_status == 'c') {
			$rcvd_st .= " and CT.QTY_IN_HOUSE > 0";
		}
	} else $rcvd_st = " and CA.SERVICE_CODE in (1, 6, 9, 11)";
	
	$sql = "select CT.ARRIVAL_NUM,
			CT.MARK as BPO,
			CT.PALLET_ID,
			decode(CA.SERVICE_CODE, 1, 'IN', 6, 'SHIP_OUT', 9, 'RECOUP', 'NA') as THE_SERVICE_CODE,
			nvl(to_char(abs(CA.QTY_CHANGE)), 'NA') as QTY_CHANGE,
			nvl(to_char(CA.QTY_LEFT), 'NA') as QTY_LEFT,
			NVL(CA.ORDER_NUM, '&nbsp;') as THE_ORDER_NUM,
			CT.WAREHOUSE_LOCATION,
			decode(CA.DATE_OF_ACTIVITY, null, 'NA', TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH:MM:SS AM')) as THE_ACTIVITY_DATE,
			CT.BOL as WM_ITEM_NUM,
			CT.BATCH_ID as THE_GROWER_NUM,
			NVL(CT.CARGO_STATUS, '&nbsp;') as THE_CARGO_STATUS,
			WM_PROGRAM as CARGO_TYPE,
			VP.VESSEL_NAME
		from CARGO_TRACKING CT
		left join CARGO_ACTIVITY CA
			on CT.PALLET_ID = CA.PALLET_ID
			and CT.RECEIVER_ID = CA.CUSTOMER_ID
			and CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
		join WM_CARGO_TYPE WM
			on CT.CARGO_TYPE_ID = WM.CARGO_TYPE_ID
		join VESSEL_PROFILE VP
			on CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
		join CARGO_TRACKING_ADDITIONAL_DATA CTAD
			on CT.PALLET_ID = CTAD.PALLET_ID
			and CT.RECEIVER_ID = CTAD.RECEIVER_ID
			and CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
		where RECEIVER_ID = '".$customer."'
			and (CA.ACTIVITY_DESCRIPTION is null or (CA.ACTIVITY_DESCRIPTION <> 'VOID' and CA.ACTIVITY_DESCRIPTION <> 'RETURN'))
			$cargo_t $item_n $serv_c $bpo_x $ves_x $rcvd_st
			order by CT.ARRIVAL_NUM desc, CT.MARK asc, THE_SERVICE_CODE asc, CA.DATE_OF_ACTIVITY asc";
			
	// echo "<pre>$sql</pre>";
	
	$pallet_data = ociparse($rfconn, $sql);
	ociexecute($pallet_data);
	return $pallet_data;
}

class Counter
{
	var $pallets = array('in' => 0, 'out' => 0, 'recoup' => 0, 'na' => 0);
	var $cases = array('in' => 0, 'out' => 0, 'recoup' => 0, 'na' => 0);
	
	function GetPalletCount() {
		return $this->pallets;
	}
	
	function GetCaseCount() {
		return $this->cases;
	}
	
	function AddToPalletCount($in_out_or_recoup) {
		switch ($in_out_or_recoup) {
			case 'IN':
				$this->pallets['in']++;
				return true;
			case 'SHIP_OUT':
				$this->pallets['out']++;
				return true;
			case 'RECOUP':
				$this->pallets['recoup']++;
				return true;
			case 'NA':
				$this->pallets['na'] ++;
				return true;
			default: return false;
		}
	}
	
	function AddToCaseCount($in_out_or_recoup, $value) {
		switch ($in_out_or_recoup) {
			case 'IN':
				$this->cases['in'] += $value;
				return true;
			case 'SHIP_OUT':
				$this->cases['out'] += $value;
				return true;
			case 'RECOUP':
				$this->cases['recoup'] += $value;
				return true;
			case 'NA':
				$this->cases['na'] += $value;
				return true;
			default: return false;
		}
	}
	
	function ResetCounts() {
		$this->pallets = array('in' => 0, 'out' => 0, 'recoup' => 0, 'na' => 0);
		$this->cases = array('in' => 0, 'out' => 0, 'recoup' => 0, 'na' => 0);
	}
}

function PrintOpenTable()
{
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?php
}

function PrintCloseTable()
{
?>
</table>
<?php
}

function PrintColHeaders()
{
?>
	<tr>
		<td colspan="13" align="center"><font size="4" face="Verdana"><b>BPO In/Out Report</b></font></td>
	</tr>
	<tr>
		<td bgcolor="C8CBE0"><font size="3" face="Verdana"><b>Vessel</b></font></td>
		<td bgcolor="E0DDC8"><font size="2" face="Verdana"><b>BPO</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Walmart Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower#</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Cargo Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases Left</b></font></td>
	</tr>
<?php
}

function PrintRow($service_code, $wm_item_num, $grower_num, $order_num, $cargo_type, $location, $date, $cargo_status, $pallet_id, $qty_change, $qty_left)
{
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><?php echo $service_code; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $wm_item_num; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $grower_num; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $order_num; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $cargo_type; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $location; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $date; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $cargo_status; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $pallet_id; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $qty_change; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $qty_left; ?></font></td>
	</tr>
<?php
}

function PrintVesHeader($ves_name)
{
?>
	<tr>
		<td colspan="13" bgcolor="C8CBE0"><font size="3" face="Verdana"><b><?php echo $ves_name; ?></b></font></td>
	</tr>
<?php
}

function PrintBPOHeader($bpo)
{
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="12" bgcolor="E0DDC8"><font size="2" face="Verdana"><b>BPO <?php echo $bpo; ?></b></font></td>
	</tr>
<?php
}

function PrintBPOTotal($bpo, $case_counts, $pallet_counts)
{
	if ($case_counts['in'] == 0 && $case_counts['out'] == 0 && $case_counts['recoup'] != 0) {
		$msg = "Total for BPO $bpo: {$case_counts['recoup']} cases, {$pallet_counts['recoup']} pallets";
	}
	elseif ($case_counts['in'] == 0 && $case_counts['out'] != 0 && $case_counts['recoup'] == 0) {
		$msg = "Total for BPO $bpo: {$case_counts['out']} cases, {$pallet_counts['out']} pallets";
	}
	elseif ($case_counts['in'] != 0 && $case_counts['out'] == 0 && $case_counts['recoup'] == 0) {
		$msg = "Total for BPO $bpo: {$case_counts['in']} cases, {$pallet_counts['in']} pallets";
	}
	else {
		$cases_remaining = $case_counts['in'] - $case_counts['out'];
		$msg = "Total for BPO $bpo: ({$case_counts['in']} in - {$case_counts['out']} out) = $cases_remaining cases remaining; {$pallet_counts['in']} pallets in, {$pallet_counts['out']} pallets out";
	}
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td bgcolor="E0C8D7" colspan="11" align="right">
			<font size="2" face="Verdana"><b><?php echo $msg; ?></b></font>
		</td>
	</tr>
<?php
}

function PrintVesTotal($vessel, $case_counts, $pallet_counts)
{
	if ($case_counts['in'] == 0 && $case_counts['out'] == 0 && $case_counts['recoup'] != 0) {
		$msg = "Total for $vessel: {$case_counts['recoup']} cases, {$pallet_counts['recoup']} pallets";
	}
	elseif ($case_counts['in'] == 0 && $case_counts['out'] != 0 && $case_counts['recoup'] == 0) {
		$msg = "Total for $vessel: {$case_counts['out']} cases, {$pallet_counts['out']} pallets";
	}
	elseif ($case_counts['in'] != 0 && $case_counts['out'] == 0 && $case_counts['recoup'] == 0) {
	$msg = "Total for $vessel: {$case_counts['in']} cases, {$pallet_counts['in']} pallets";
	}
	else {
		$cases_remaining = $case_counts['in'] - $case_counts['out'];
		$msg = "Total for $vessel: ({$case_counts['in']} in - {$case_counts['out']} out) = $cases_remaining cases remaining; {$pallet_counts['in']} pallets in, {$pallet_counts['out']} pallets out";
	}
?>
	<tr>
		<td>&nbsp;</td>
		<td bgcolor="C8E0D1" colspan="12" align="right">
			<font size="3" face="Verdana"><b><?php echo $msg; ?></b></font>
		</td>
	</tr>
<?php
}

function PrintGrTotal($case_counts, $pallet_counts)
{
	if ($case_counts['in'] == 0 && $case_counts['out'] == 0 && $case_counts['recoup'] != 0) {
		$msg = "Grand total: {$case_counts['recoup']} cases, {$pallet_counts['recoup']} pallets";
	}
	elseif ($case_counts['in'] == 0 && $case_counts['out'] != 0 && $case_counts['recoup'] == 0) {
		$msg = "Grand total: {$case_counts['out']} cases, {$pallet_counts['out']} pallets";
	}
	elseif ($case_counts['in'] != 0 && $case_counts['out'] == 0 && $case_counts['recoup'] == 0) {
	$msg = "Grand total: {$case_counts['in']} cases, {$pallet_counts['in']} pallets";
	}
	else {
		$cases_remaining = $case_counts['in'] - $case_counts['out'];
		$msg = "Grand total: ({$case_counts['in']} in - {$case_counts['out']} out) = $cases_remaining cases remaining; {$pallet_counts['in']} pallets in, {$pallet_counts['out']} pallets out";
	}
?>
	<tr>
		<td colspan="13" rowspan="2"align="right">
			<font size="3" face="Verdana"><b><?php echo $msg; ?></b></font>
		</td>
	</tr>
<?php
}

function PrintNoResults()
{
?>
<tr>
	<td colspan="13"><font size="3" face="Verdana" color="red">No results were returned for the selected filter.<br/>Please select a new filter and try again.</font>
	</td>
</tr>
<?php
}
?>