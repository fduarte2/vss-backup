<?php
/*
*	Charles Marttinen, April 2015
*
*	A drill-down page for Walmart pallets
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
if($rfconn < 1) {
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

$pallet_id = $_GET['id'];
$vessel = $_GET['vessel'];
$customer = 3000;

?>

<!--Title-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Walmart Attributes</font>
         <hr>
      </td>
   </tr>
</table>

<?php
$sql = "select VESSEL_NAME
		from VESSEL_PROFILE
		where ARRIVAL_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vessel_name = ociresult($short_term_data, "VESSEL_NAME");
?>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet ID:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $pallet_id; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $vessel . " - " . $vessel_name; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $customer; ?> - Walmart</font></td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	
<?php
$sql = "select BOL,
			MARK,
			COMMODITY_NAME,
			CT.COMMODITY_CODE,
			CARGO_DESCRIPTION,
			nvl(to_char(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NA') as DATE_REC,
			QTY_RECEIVED,
			WAREHOUSE_LOCATION,
			QTY_IN_HOUSE,
			BATCH_ID,
			EXPORTER_CODE,
			CARGO_SIZE,
			REMARK,
			VARIETY,
			CARGO_TYPE_ID,
			COUNTRY_CODE,
			to_char(SUPPLIER_PACKDATE, 'MM/DD/YYYY') as THE_PACK
		from CARGO_TRACKING CT,
			CARGO_TRACKING_ADDITIONAL_DATA CTAD,
			COMMODITY_PROFILE COMP
		where CT.PALLET_ID = '".$pallet_id."'
			and CT.RECEIVER_ID = '".$customer."'
			and CT.ARRIVAL_NUM = '".$vessel."'
			and CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
			and CT.RECEIVER_ID = CTAD.RECEIVER_ID
			and CT.PALLET_ID = CTAD.PALLET_ID
			and CT.COMMODITY_CODE = COMP.COMMODITY_CODE";
$pallet_data = ociparse($rfconn, $sql);
ociexecute($pallet_data);
ocifetch($pallet_data);

?>

	<tr>
		<td><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($pallet_data, "COMMODITY_CODE")." - ".ociresult($pallet_data, "COMMODITY_NAME"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Description:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($pallet_data, "CARGO_DESCRIPTION"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Received Date:</b></font></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($pallet_data, "DATE_REC"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Warehouse Location:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($pallet_data, "WAREHOUSE_LOCATION"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>QTY In House:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($pallet_data, "QTY_IN_HOUSE"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>WM Item#:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "BOL"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Grower Item#:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "BATCH_ID"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Receiving PO#:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "MARK"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Exporter:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "EXPORTER_CODE"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Size Code:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "CARGO_SIZE"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Label Code:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "REMARK"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Variety:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "VARIETY"); ?></font></td>
	</tr>
<?php
$sql = "select * from WM_CARGO_TYPE where CARGO_TYPE_ID = '" . ociresult($pallet_data, "CARGO_TYPE_ID") . "'";
$short_term_data = ociparse($rfconn, $sql);
ociexecute($short_term_data);
ocifetch($short_term_data);
$cargo_type = ociresult($pallet_data, "CARGO_TYPE_ID") . " - " . ociresult($short_term_data, "WM_PROGRAM");
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Cargo Type:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cargo_type; ?></font></td>
	</tr>
<?php
$sql = "select COUNTRY_CODE, COUNTRY_NAME
		from COUNTRY
		where COUNTRY_CODE = '" . ociresult($pallet_data, "COUNTRY_CODE") . "'";
$short_term_data = ociparse($rfconn, $sql);
ociexecute($short_term_data);
ocifetch($short_term_data);
$country = ociresult($pallet_data, "COUNTRY_CODE") . " - " . ociresult($short_term_data, "COUNTRY_NAME");
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Country Code:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $country; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Supplier Packdate:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "THE_PACK"); ?></font></td>
	</tr>
</table>

<br/><br/>

<!--Activity table-->
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana"><b>Pallet Activity</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>#</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>From Location</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases Left</b></font></td>
		<td><font size="2" face="Verdana"><b>Checker</b></font></td>
	</tr>
<?
$sql = "select CA.ACTIVITY_NUM,
			nvl(to_char(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS'), 'NA') as THE_ACT_DATE,
			CA.SERVICE_CODE || ' ' || decode(SC.SERVICE_NAME, Null, 'UNKNOWN', 'Docking', 'Scanned In', SC.SERVICE_NAME) as THE_SERV_NAME,
			nvl(CA.ORDER_NUM, 'NA') as THE_ORDER_NUM,
			nvl(CA.FROM_LOCATION, 'NA') as THE_LOCATION,
			nvl(CA.ACTIVITY_DESCRIPTION, '&nbsp;') as THE_ACT_DESC,
			CA.QTY_CHANGE,
			CA.QTY_LEFT,
			ARRIVAL_NUM
		from CARGO_ACTIVITY CA
		left join SERVICE_CATEGORY SC
			on CA.SERVICE_CODE=SC.SERVICE_CODE
		where CA.PALLET_ID='".$pallet_id."'
			and CA.CUSTOMER_ID='".$customer."'
			and CA.ARRIVAL_NUM='".$vessel."'
		order by CA.ACTIVITY_NUM";
$pallet_activity = ociparse($rfconn, $sql);
ociexecute($pallet_activity);

while(ocifetch($pallet_activity)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "ACTIVITY_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "THE_ACT_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "THE_SERV_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "THE_ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "THE_LOCATION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "THE_ACT_DESC"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "QTY_CHANGE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_activity, "QTY_LEFT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo get_employee_for_print($pallet_id, ociresult($pallet_activity, "ARRIVAL_NUM"), $customer, ociresult($pallet_activity, "ACTIVITY_NUM"), $rfconn);; ?></font></td>
	</tr>
<?
}
?>
</table>

<?php




//I "stole" this function from eport: rf/tally/fruit_out_print.php
function get_employee_for_print($Barcode, $LR, $cust, $act_num, $rfconn){
	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."<br>";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$date = ociresult($short_term_data, "THE_DATE");
	$emp_no = ociresult($short_term_data, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);
	if(ociresult($main_data, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($emp_no).") = '".$emp_no."'"; 
	}
//	echo $sql."\n";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);

	return ociresult($main_data, "THE_EMP");
}




include("pow_footer.php");
?>