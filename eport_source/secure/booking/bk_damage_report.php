<?
	include("useful_info.php");
	include("get_employee_name.php");
	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
/*
	$submit = $_GET['submit'];
	$arv_num = $_GET['arv_num'];
	$cust = $_GET['cust'];
	$manifest = $_GET['manifest'];

	if($submit != "" && $arv_num == ""){
		echo "<font color=\"#FF0000\">Please Enter an Arrival #</font><br>";
		$submit = "";
	}

	if($submit != "" && $cust == ""){
		echo "<font color=\"#FF0000\">Please Enter a Customer #</font><br>";
		$submit = "";
	}
*/
?>
<h1>Booking Paper InHouse Damages</h1>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="14" align="center"><font size="3" face="Verdana">InHouse Damaged Booking Paper as of:  <? echo date('m/d/Y h:i:s'); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Product Code</b></font></td>	
		<td><font size="2" face="Verdana"><b>DMG Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Occurred</b></font></td>
		<td><font size="2" face="Verdana"><b>Dia</b></font></td>
		<td><font size="2" face="Verdana"><b>Width</b></font></td>
		<td><font size="2" face="Verdana"><b>Length</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
		<td><font size="2" face="Verdana"><b># Rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival Num</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
	</tr>
<?
	if($eport_customer_id != "0"){
		$cust_sql = " AND CT.RECEIVER_ID = '".$eport_customer_id."' ";
	} else {
		$cust_sql = " ";
	}

	$sql = "SELECT CT.RECEIVER_ID, CT.PALLET_ID, BOOKING_NUM, WAREHOUSE_CODE, PRODUCT_CODE, DAMAGE_TYPE, OCCURRED, DIAMETER, WIDTH, LENGTH, WEIGHT, QTY_IN_HOUSE, CT.ARRIVAL_NUM, TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') THE_REC  
			FROM CARGO_TRACKING CT, BOOKING_DAMAGES BD, BOOKING_ADDITIONAL_DATA BAD
			WHERE CT.RECEIVER_ID = 338
				AND CT.RECEIVER_ID = BD.RECEIVER_ID
				AND CT.PALLET_ID =  BD.PALLET_ID
				AND CT.ARRIVAL_NUM = BD.ARRIVAL_NUM
				AND CT.PALLET_ID =  BAD.PALLET_ID
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.QTY_IN_HOUSE > 0
				".$cust_sql."
			ORDER BY RECEIVER_ID, BOOKING_NUM, PALLET_ID";
	$bk_data = ociparse($rfconn, $sql);
	ociexecute($bk_data);
	if(!ocifetch($bk_data)){
?>
		<tr><td colspan="14" align="center"><b>None</b></td></tr>
<?
	} else {
//		$total = 0;
		do { 
?>
	<tr>
		<td><? echo ociresult($bk_data, "RECEIVER_ID"); ?></td>
		<td><? echo ociresult($bk_data, "PALLET_ID"); ?></td>
		<td><? echo ociresult($bk_data, "BOOKING_NUM"); ?></td>
		<td><? echo ociresult($bk_data, "WAREHOUSE_CODE"); ?></td>
		<td><? echo ociresult($bk_data, "PRODUCT_CODE"); ?></td>
		<td><? echo ociresult($bk_data, "DAMAGE_TYPE"); ?></td>
		<td><? echo ociresult($bk_data, "OCCURRED"); ?></td>
		<td><? echo ociresult($bk_data, "DIAMETER"); ?></td>
		<td><? echo ociresult($bk_data, "WIDTH"); ?></td>
		<td><? echo ociresult($bk_data, "LENGTH"); ?></td>
		<td><? echo ociresult($bk_data, "WEIGHT"); ?></td>
		<td><? echo ociresult($bk_data, "QTY_IN_HOUSE"); ?></td>
		<td><? echo ociresult($bk_data, "ARRIVAL_NUM"); ?></td>
		<td><? echo ociresult($bk_data, "THE_REC"); ?></td>
	</tr>
<?
		} while(ocifetch($bk_data));
?>
</table>
<?
	}