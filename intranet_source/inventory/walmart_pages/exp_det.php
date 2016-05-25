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
	$date = $HTTP_GET_VARS['date'];

//	echo $item."<br>".$date;

?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Walmart Status</b></font></td>
	</tr>
<?
	//DATE_DEPARTED >= SYSDATE	AND 
	$sql = "SELECT BOL, CT.ARRIVAL_NUM, CT.PALLET_ID, QTY_IN_HOUSE, NVL(CARGO_STATUS, 'GOOD') THE_STAT 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
			WHERE CT.RECEIVER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM NOT IN
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND CT.ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM) FROM VOYAGE
						WHERE DATE_DEPARTED <= TO_DATE('".$date."', 'MM/DD/YYYY')
					)
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND BOL = '".$item."'
			ORDER BY CT.ARRIVAL_NUM, CT.PALLET_ID";
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
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_IN_HOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STAT']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>