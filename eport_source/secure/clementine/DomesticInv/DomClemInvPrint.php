<?
/*
*	Adam Walter, October 2007.
*	This page is linked from DomClemInv.php, and reproduces a single
*	Container, for printing purposes.
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$Outer_Cursor = ora_open($conn);
	$Inner_Cursor = ora_open($conn);

	$container = $HTTP_GET_VARS['container'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	$total_pallets = 0;
	$total_cases = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>Container:  <? echo $container; ?></b></font></td>
	<tr>
	<tr>
<!--		<td><font size="2" face="Verdana"><b>Container</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Case Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipped on (if applicable)</b></font></td>
		<td><font size="2" face="Verdana"><b>Outgoing Order (if applicable)</b></font></td>
	</tr>
<?
//											AND SUBSTR(ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
		$sql = "SELECT CT.CONTAINER_ID THE_CONT, CT.PALLET_ID THE_PALLET, TO_NUMBER(CT.CARGO_SIZE) THE_SIZE, DC_CARGO_DESC DISP_SIZE, QTY_RECEIVED, 
				TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC, NVL(CT.CARGO_STATUS, 'GOOD') THE_STATUS, CTE.MIN_DATE DATE_ORDERED_BY,
				CA.DATE_ACT ACTIVITY_DATE, CA.ORDER_NUM THE_ORDER
				FROM DC_CARGO_TRACKING CT, (SELECT PALLET_ID, ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_ACT, ORDER_NUM
											FROM CARGO_ACTIVITY CA
											WHERE SERVICE_CODE = '6'
											AND ACTIVITY_DESCRIPTION IS NULL
											AND CUSTOMER_ID = '".$cust."'".$sub_sql.") CA,
										(SELECT CONTAINER_ID, MIN(DATE_RECEIVED) MIN_DATE
											FROM CARGO_TRACKING
											WHERE RECEIVER_ID = '".$cust."'".$sub_sql."
											AND DATE_RECEIVED > '01-oct-2007' ";
/*		if($current_option == "current"){
			$sql .= "AND CONTAINER_ID IN (SELECT CONTAINER_ID FROM CARGO_TRACKING WHERE MARK != 'SHIPPED' OR MARK IS NULL) ";
		} */

		$sql .=								"GROUP BY CONTAINER_ID
											ORDER BY MIN(DATE_RECEIVED)) CTE
				WHERE CT.CONTAINER_ID = CTE.CONTAINER_ID
				AND CT.RECEIVER_ID = '".$cust."'".$sub_sql."
				AND CT.DATE_RECEIVED > '01-oct-2007'
				AND CT.PALLET_ID = CA.PALLET_ID (+)
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM (+)
				AND CT.CONTAINER_ID = '".$container."' 
				ORDER BY DATE_ORDERED_BY, THE_SIZE, DISP_SIZE, DATE_REC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total_pallets++;
			$total_cases += $row['QTY_RECEIVED'];
?>
	<tr>
<!--		<td><font size="2" face="Verdana"><? echo $row['THE_CONT']; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DISP_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STATUS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ACTIVITY_DATE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ORDER']; ?>&nbsp;</font></td>
	</tr>
<?
		}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Pallets:  <? echo $total_pallets; ?></b></font></td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>Cases:  <? echo $total_cases; ?></b></font></td>
		<td colspan="4">&nbsp;</td>
	</tr>
</table>