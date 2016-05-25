<?
/* Adam Walter, 8/25/07
*	This file creates a customer-to-order file for Susan Bricks
*********************************************************************************/



	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$order_cursor = ora_open($conn2);
	$size_cursor = ora_open($conn2);
	$main_cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel_num = $HTTP_POST_VARS['vessel_num'];
	$cust_num = $HTTP_POST_VARS['cust_num'];
	// $eport_customer_id comes from index.php
	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
		$extra_sql = "";
//		$extra_sql = "WHERE PORT_CUSTOMER_ID = '".$eport_customer_id."'";
	}
/*
	$order_array = array();
	$size_array = array();
	$order_counter = 0;
	$size_counter = 0;
*/

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Reconciliation Report</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<form name="order1" action="generate_file.php" method="post">
		<td width="45%" align="center"><font size="3" face="Verdana">Vessel:<BR><select name="vessel_num"><option value="">Select A Vessel</option>
<?
	$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_NUM, VP.VESSEL_NAME THE_VES FROM CARGO_TRACKING CT, VESSEL_PROFILE VP 
			WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM) AND CT.COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND WEIGHT_UNIT IS NOT NULL ORDER BY THE_NUM";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<option value="<? echo $row['THE_NUM']; ?>"><? echo $row['THE_NUM']."-".$row['THE_VES']; ?></option>
<?
	}
?>
			</select></td>
		<td width="10%">&nbsp;</td>
		<td width="45%" align="center"><font size="3" face="Verdana">Customer:<BR><select name="cust_num"><option value="">Select A Customer</option>
<?
	$sql = "SELECT CUSTOMERID, CUSTOMERNAME FROM DC_CUSTOMER ".$extra_sql." ORDER BY CUSTOMERID";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<option value="<? echo $row['CUSTOMERID']; ?>"><? echo $row['CUSTOMERID']."-".$row['CUSTOMERNAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input name="submit" type="submit" value="Retrieve"><BR><HR></td>
	</tr></form>
</table>
