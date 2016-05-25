<?


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

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$filter_item = $HTTP_POST_VARS['filter_item'];
	$date = $HTTP_POST_VARS['date'];

	$item_clause = "";
	$date_clause = "";

	if($filter_item != ""){
		$item_clause .= " AND WLD.DCPO_NUM IN (SELECT DCPO_NUM FROM WDI_LOAD_DCPO_ITEMNUMBERS WHERE ITEM_NUM = '".$filter_item."')";
	} 
	if($date != ""){
		$date_clause .= " TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$date."'";
	} else {
		$date_clause .= " LOAD_DATE >= SYSDATE - 1 ";
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Projected Order Screen (Lookup by Item# / Date)</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="filter" action="walmart_proj_item_lookup.php" method="post">
	<tr>
		<td colspan="<? echo $bottom_cols; ?>">Item#:  <select name="filter_item"><option value="">All</option>
<?
	$sql = "SELECT DISTINCT WICM.ITEM_NUM, WICM.WM_COMMODITY_NAME
			FROM WM_ITEM_COMM_MAP WICM, WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
			WHERE WICM.ITEM_NUM = WLDI.ITEM_NUM
			AND WLD.DCPO_NUM = WLDI.DCPO_NUM
			AND WLD.LOAD_NUM = WLH.LOAD_NUM
			AND STATUS = 'ACTIVE'
			AND LOAD_DATE >= SYSDATE - 1
			AND TO_CHAR(WLD.DCPO_NUM) NOT IN (SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID'))
			ORDER BY ITEM_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $short_term_row['ITEM_NUM']; ?>"<? if($filter_item == $short_term_row['ITEM_NUM']){?> selected <?}?>><? echo $short_term_row['ITEM_NUM']. " - ".$short_term_row['WM_COMMODITY_NAME']; ?></option>
<?
	}
?>
		</select></td>
	</tr>
	<tr>
		<td colspan="<? echo $bottom_cols; ?>">Load Date:  <input type="text" name="date" value="<? echo $date; ?>" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td colspan="<? echo $bottom_cols; ?>"><input type="submit" name="submit" value="Filter Orders"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
	// since DATE_EXPECTED has no timestamp, being >= SYSDATE - 1 means that today will show as well
		$sql = "SELECT WLD.DCPO_NUM, LOAD_DATE, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE, WLH.LOAD_NUM 
				FROM WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
				WHERE ".$date_clause."
				AND STATUS = 'ACTIVE'
				AND WLD.LOAD_NUM = WLH.LOAD_NUM
				AND TO_CHAR(WLD.DCPO_NUM) NOT IN (SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6')
				".$item_clause."
				ORDER BY LOAD_DATE, WLD.LOAD_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>No Outstanding Projected Orders.</td>
	</tr>
</table>
<?
		} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font face="Verdana" size="2"><b>Load#</b></font></td>
		<td><font face="Verdana" size="2"><b>DCPO-Order #</b></font></td>
		<td><font face="Verdana" size="2"><b>Overview</b></font></td>
		<td><font face="Verdana" size="2"><b>Expected Load Date</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
			do {
?>
<form name="form<? echo $form_num; ?>" action="walmart_proj.php" method="post">
<input type="hidden" name="load_num" value="<? echo $row['LOAD_NUM']; ?>">
	<tr>
		<td valign="top"><font face="Verdana" size="2"><? echo $row['LOAD_NUM']; ?></font></td>
		<td valign="top"><font face="Verdana" size="2"><? echo $row['DCPO_NUM']; ?></font></td>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="70%" align="left"><font face="Verdana" size="2"><b>Item</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Plt</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Ctn</b></font></td>
				</tr>
<?
				$sql = "SELECT WICM.ITEM_NUM, WICM.WM_COMMODITY_NAME, SUM(PALLETS) THE_PLT, SUM(CASES) THE_CTN
						FROM WM_ITEM_COMM_MAP WICM, WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
						WHERE WICM.ITEM_NUM = WLDI.ITEM_NUM
						AND WLDI.DCPO_NUM = '".$row['DCPO_NUM']."'
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLD.LOAD_NUM = WLH.LOAD_NUM
						GROUP BY WICM.ITEM_NUM, WICM.WM_COMMODITY_NAME";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<tr>
					<td align="left"><font face="Verdana" size="2"><? echo $short_term_row['ITEM_NUM']." ".$short_term_row['WM_COMMODITY_NAME']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $short_term_row['THE_PLT']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $short_term_row['THE_CTN']; ?></font></td>
				</tr>
<?
				}
?>
			</table>
		</td>
		<td valign="top"><font face="Verdana" size="2"><? echo $row['THE_DATE']; ?>&nbsp;</font></td>
		<td valign="top"><input type="submit" name="submit" value="Retrieve Load"></td>
	</tr>
</form>
<?
				$form_num++;
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
</table>
<?
	include("pow_footer.php");
?>
