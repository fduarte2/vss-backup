<?


  // All POW files need this session file included
  include("pow_session.php");
/*
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
*/
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	$item = $HTTP_GET_VARS['itemnum'];
	$order = $HTTP_GET_VARS['order'];

?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Code</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Changed</b></font></td>
		<td><font size="2" face="Verdana"><b>Walmart Status</b></font></td>
		<td><font size="2" face="Verdana"><b>USDA Hold</b></font></td>
	</tr>
<?
//				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.BOL, NVL(CARGO_STATUS, 'GOOD') THE_STAT, CA.ORDER_NUM, SC.SERVICE_CODE || ' - ' || SC.SERVICE_NAME THE_SERV,
				CA.QTY_CHANGE, NVL(USDA_HOLD, '&nbsp;') THE_USDA
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, SERVICE_CATEGORY SC
			WHERE CA.CUSTOMER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CA.SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND ORDER_NUM IN
					(SELECT TO_CHAR(WLDI.DCPO_NUM)
					FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
					WHERE WLDI.DCPO_NUM = WLD.DCPO_NUM
						AND WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND WLH.LOAD_DATE >= (SYSDATE - 1)
						AND WLDI.ITEM_NUM = '".$item."'
						AND WLDI.DCPO_NUM != '".$order."')
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CA.SERVICE_CODE = SC.SERVICE_CODE
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND CT.BOL = '".$item."'
			ORDER BY CA.ORDER_NUM, CT.PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="9"><font size="2" face="Verdana">No Pallets fit this criteria</font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_SERV']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STAT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_USDA']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>