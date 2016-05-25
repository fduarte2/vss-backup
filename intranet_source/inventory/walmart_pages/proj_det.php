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
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>PLTs To Ship</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases To Ship</b></font></td>
	</tr>
<?
	$sql = "SELECT WLDI.DCPO_NUM, PALLETS, ITEM_NUM, CASES
			FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
			WHERE WLDI.DCPO_NUM = WLD.DCPO_NUM
				AND WLH.LOAD_NUM = WLD.LOAD_NUM
				AND WLH.STATUS = 'ACTIVE'
				AND WLH.LOAD_DATE >= (SYSDATE - 1)
				AND WLDI.ITEM_NUM = '".$item."'
				AND WLDI.DCPO_NUM != '".$order."'
			ORDER BY WLDI.DCPO_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="5"><font size="2" face="Verdana">No Pallets fit this criteria</font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['DCPO_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ITEM_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLETS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CASES']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>