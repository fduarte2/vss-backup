<?php
/*
*	Charles Marttinen, April 2015
*
*	Report that 
*
************************************************************************/

$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
$user = $HTTP_COOKIE_VARS["eport_user"];
$view_cust = $HTTP_POST_VARS['view_cust'];
if($view_cust != ""){
	$user_cust_num = $view_cust;
} else {
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
}

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
if($rfconn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

$submit = $HTTP_POST_VARS['submit'];
if($submit == "Retrieve Data") {
	
}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Storage Recap Report</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="storage_recap_report.php" method="post">
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"><br><hr><br></td>
	</tr>
</form>

<?php
//If Retrieve button pressed
if($submit != ""){
	$grand_total_pcs = 0;
	$grand_total_wt = 0;
?>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana"><b>Results for Storage Recap Report:</b></font></td>
	</tr>
	<tr>
		<td bgcolor="FFCC99"><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Roll#</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight Pounds</b></font></td>
		<td><font size="2" face="Verdana"><b>Short Tons</b></font></td>
		<td><font size="2" face="Verdana"><b>Stuffed Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Days on Dock</b></font></td>
		<td><font size="2" face="Verdana"><b>Storage Costs</b></font></td>
	<tr>









<?php 
		$sql = "select distinct RECEIVER_ID from BOOKING_ADDITIONAL_DATA";
		$all_customers = ociparse($rfconn, $sql);
		ociexecute($all_customers);
		if(!ocifetch($all_customers)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana"><b>Error: no customers</b></font></td>
	</tr>
<?php
		} else {
			do {
				$order_pcs = 0;
				$order_wt = 0;
?>
	<tr>
		<td colspan="9" bgcolor="FFCC99"><font size="2" face="Verdana"><b><?php echo ociresult($all_customers, "RECEIVER_ID"); ?></b></font></td>
	</tr>
<?php
				$sql = "select CP.CUSTOMER_NAME,
							BAD.PALLET_ID as THE_ROLL_NUMBER,
							BAD.BOOKING_NUM,
							ROUND(CT.WEIGHT * UC1.CONVERSION_FACTOR, 2) as THE_LBS,
							ROUND(CT.WEIGHT * UC2.CONVERSION_FACTOR, 2) as THE_TONS,
							TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') as THE_STUFFED_DATE,
							CT.DATE_RECEIVED,
							(trunc(sysdate) - trunc(CT.DATE_RECEIVED)) as THE_DAYS_ON_DOCK
						from BOOKING_ADDITIONAL_DATA BAD
						left join CARGO_TRACKING CT
							on BAD.PALLET_ID = CT.PALLET_ID
							and BAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
							and BAD.RECEIVER_ID = CT.RECEIVER_ID
						left join CUSTOMER_PROFILE CP
							on BAD.RECEIVER_ID = CP.CUSTOMER_ID
						inner join UNIT_CONVERSION_FROM_BNI UC1
							on UC1.PRIMARY_UOM = CT.WEIGHT_UNIT
							and UC1.SECONDARY_UOM = 'LB'
						inner join UNIT_CONVERSION_FROM_BNI UC2
							on UC2.PRIMARY_UOM = CT.WEIGHT_UNIT
							and UC2.SECONDARY_UOM = 'TON'
						left join CARGO_ACTIVITY CA
							on BAD.PALLET_ID = CA.PALLET_ID
							and BAD.ARRIVAL_NUM = CA.ARRIVAL_NUM
							and BAD.RECEIVER_ID = CA.CUSTOMER_ID
							and CA.SERVICE_CODE = '6'
						where BAD.RECEIVER_ID = '314'
						order by CUSTOMER_NAME asc, BOOKING_NUM asc";
						
				$rolls = ociparse($rfconn, $sql);
				ociexecute($rolls);
				while(ocifetch($rolls)){
					$order_pcs += ociresult($rolls, "THE_DAYS_ON_DOCK");
					$order_wt += round(ociresult($rolls, "THE_LBS"));
					$grand_total_pcs += ociresult($rolls, "THE_DAYS_ON_DOCK");
					$grand_total_wt += round(ociresult($rolls, "THE_LBS"));
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "THE_ROLL_NUMBER"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "BOOKING_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "THE_LBS"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "THE_TONS"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "THE_STUFFED_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo ociresult($rolls, "DATE_RECEIVED"); ?></font></td>
		<td><font size="2" face="Verdana"><?php echo round(ociresult($rolls, "THE_DAYS_ON_DOCK")); ?></font></td>
	</tr>
<?php
				}
?>
	<tr>
		<td colspan="7" bgcolor="FF9999"><font size="2" face="Verdana"><b><?php echo ociresult($all_customers, "RECEIVER_ID"); ?> Total:</b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><?php echo $order_pcs; ?></b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><?php echo $order_wt; ?></b></font></td>
	</tr>
<?php
			} while(ocifetch($all_customers));
		}
?>
	<tr bgcolor="99CCFF">
		<td colspan="7"><font size="3" face="Verdana"><b>GRAND Total:</b></font></td>
		<td><font size="2" face="Verdana"><b><?php echo $grand_total_pcs; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><?php echo $grand_total_wt; ?></b></font></td>
	</tr>
<?php

	}
include("pow_footer.php");
?>