<?php
/*
*	Charles Marttinen, April 2015
*
*	This report gets a list of all pallets in house for a given customer, min 10 cases/pallet
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

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";
if($rfconn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

$url_this_page = 'Qty_InHouse.php';


//Copy form values into variables
$submit = $_POST['submit'];
if($submit == "Retrieve Data") {
	$customer = $_POST['customer'];
	$arrival_num = $_POST['arrival_num'];
	
	if (!is_numeric($customer)) {
		echo '<font size="2" face="Verdana" color="FF0000">The customer must be a number.</font>';
		$submit = '';
	}
	elseif (!DoesCustExist($customer, $rfconn)) {
		echo '<font size="2" face="Verdana" color="FF0000">The customer you have entered does not exist.</font>';
		$submit = '';
	}
	
	if (!DoesArrivalNumExist($arrival_num, $rfconn)) {
		$arrival_num = '';
	}
}
?>

<!--Title-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Quantity In-House Report by Customer</font><br/>
		 <font size="3" face="Verdana" color="#0066CC">For Chilean Fruit case counts greater than 10</font>
         <hr>
      </td>
   </tr>
</table>

<!--Form-->
<table border="0" cellpadding="4" cellspacing="0">
	<form name="the_form" action="<?php echo $url_this_page; ?>" method="post">
	<tr>
			<td>Customer:</td>
			<td><input type="number" name="customer" value="<?php echo $customer; ?>">&nbsp;&nbsp;&nbsp;</td>
			
<?php
$sql = "select distinct CT.ARRIVAL_NUM,
			CT.ARRIVAL_NUM || ' ' || VP.VESSEL_NAME as THE_VESSEL
		from CARGO_TRACKING CT
		left join VESSEL_PROFILE VP
			on VP.ARRIVAL_NUM = CT.ARRIVAL_NUM
		where CT.RECEIVER_ID = '".$customer."'
			and CT.QTY_IN_HOUSE > 10
			and CT.DATE_RECEIVED is not null
		order by CT.ARRIVAL_NUM";
$all_arrival_nums = ociparse($rfconn, $sql);

if ($submit != '' && $customer != '') {
?>
			<td>Arrival#:</td>
			<td>
				<select name="arrival_num">
					<option value="">ALL</option>
<?php
	ociexecute($all_arrival_nums);
	
	while(ocifetch($all_arrival_nums)){
		$ar = ociresult($all_arrival_nums, "ARRIVAL_NUM");
		$ar_name = ociresult($all_arrival_nums, "THE_VESSEL");
?>
					<option value="<?php echo $ar; ?>"<?php if($ar == $arrival_num) { ?> selected <?php } ?>>
						<?php echo $ar_name; ?>
					</option>
<?php
	}
?>
				</select>
			</td>
<?php
}
?>
	</tr>
	<tr>
		<td>
			<input type="submit" name="submit" value="Retrieve Data">
		</td>
	</tr>
	</form>
</table>

<?php
//If you press the button
if ($submit != '') {
	$cust_name = GetCustName($customer, $rfconn);
	
?>
	
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="5" align="center">
			<font size="2" face="Verdana"><b>In-House Quantity Report for <?php echo $cust_name; ?></b></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="C8CBE0"><font size="2" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty In-House</b></font></td>
	<tr>

<?php
	//Initialise counters
	$ttl_cases = $ttl_plts= 0;

	ociexecute($all_arrival_nums); //reuse cursor
	while(ocifetch($all_arrival_nums)){
		$ar = ociresult($all_arrival_nums, "ARRIVAL_NUM");
		
		if ($arrival_num == '' || $ar == $arrival_num) {
			
			//Initialise counters
			$ar_ttl_cases = $ar_ttl_plts= 0;

			$sql = "select CT.ARRIVAL_NUM,
						CT.RECEIVER_ID,
						CT.PALLET_ID,
						nvl(CT.VARIETY, 'N/A') as THE_VARIETY,
						to_char(CT.DATE_RECEIVED, 'MM/DD/YYYY HH:MI:SS AM') as THE_DATE,
						CT.QTY_IN_HOUSE,
						CT.ARRIVAL_NUM || ' ' || VP.VESSEL_NAME as THE_VESSEL
					from CARGO_TRACKING CT
					left join VESSEL_PROFILE VP
						on VP.ARRIVAL_NUM = CT.ARRIVAL_NUM
					where CT.QTY_IN_HOUSE > 10
						and CT.RECEIVER_ID = '".$customer."'
						and CT.DATE_RECEIVED is not null";
			if ($ar != '') {
				$sql .= " and CT.ARRIVAL_NUM = '".$ar."'";
			}
			$sql .= " order by CT.ARRIVAL_NUM asc";
				
			// echo "<pre>$sql</pre>";
			
			$row = ociparse($rfconn, $sql);
			ociexecute($row);
			ocifetch($row);
			$ar_name = trim(ociresult($row, "THE_VESSEL"));
?>
	<tr>
		<td colspan="5" bgcolor="C8CBE0"><font size="2" face="Verdana"><b><?php echo $ar_name; ?></b></font></td>
	</tr>

<?php
			do {
				$ar_ttl_cases += ociresult($row, "QTY_IN_HOUSE");
				$ar_ttl_plts++;
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><?php echo ociresult($row, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($row, "THE_VARIETY"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($row, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($row, "QTY_IN_HOUSE"); ?></font></td>
	</tr>
<?php
			} while(ocifetch($row))
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="3" bgcolor="E0DDC8" align="right"><font size="2" face="Verdana"><b><?php echo "Total for Arrival# $ar_name:"; ?></b></font></td>
		<td bgcolor="E0DDC8"><font size="2" face="Verdana"><?php echo number_format($ar_ttl_cases), " cases<br/>", number_format($ar_ttl_plts), " pallet(s)"; ?></font></td>
	</tr>
<?php
			$ttl_cases += $ar_ttl_cases;
			$ttl_plts += $ar_ttl_plts;
		}
	}
	
	if ($arrival_num == '') {
?>
	<tr>
		<td colspan="4" bgcolor="C8E0D1"><font size="3" face="Verdana"><b><?php echo "Grand Total for all Arrival Numbers:"; ?></b></font></td>
		<td bgcolor="C8E0D1"><font size="2" face="Verdana"><?php echo number_format($ttl_cases), " cases<br/>", number_format($ttl_plts), " pallet(s)"; ?></font></td>
	</tr>
<?php
	}
?>
</table>

<?php
}



function DoesCustExist($cust, $rfconn)
{
	$sql = "select RECEIVER_ID from CARGO_TRACKING where RECEIVER_ID = '".$cust."'";
	$row = ociparse($rfconn, $sql);
	ociexecute($row);
	return ocifetch($row);
}

function GetCustName($cust, $rfconn)
{
	$sql = "select CUSTOMER_NAME from CUSTOMER_PROFILE where CUSTOMER_ID = '".$cust."'";
	$row = ociparse($rfconn, $sql);
	ociexecute($row);
	ocifetch($row);
	return ociresult($row, 'CUSTOMER_NAME');
}

function DoesArrivalNumExist($ar, $rfconn)
{
	if ($arrival_num == '') return true;
	
	$sql = "select ARRIVAL_NUM from CARGO_TRACKING where ARRIVAL_NUM = '".$ar."'";
	$row = ociparse($rfconn, $sql);
	ociexecute($row);
	return ocifetch($row);
}


include("pow_footer.php");
?>