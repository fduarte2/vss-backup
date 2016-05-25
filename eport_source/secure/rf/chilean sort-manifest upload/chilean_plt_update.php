<?
/*  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - RF Storage";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	REPLACE ABOVE LOGIC WITH EPORT EQUIVALENT
*/
//	$user_cust_num = 1982; // FOR TESTING
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);
  $VERY_Short_Term_Cursor = ora_open($conn);

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$detail_colspan_default = 11;
	$colspan = $detail_colspan_default;
	$additional_headers = "";
	$additional_columns = "";

	if($user_cust_num == "3000"){
		$colspan += 1;
/*
		$additional_headers = "<td bgcolor=\"#DDDDDD\"><font size=\"2\" face=\"Verdana\"><b>Grower Barcode</b></font></td>
			<td bgcolor=\"#DDDDDD\"><font size=\"2\" face=\"Verdana\"><b>PO</b></font></td>
			<td bgcolor=\"#DDDDDD\"><font size=\"2\" face=\"Verdana\"><b>Temp</b></font></td>";
*/
		$additional_headers = "<td bgcolor=\"#DDDDDD\"><font size=\"2\" face=\"Verdana\"><b>PO</b></font></td>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Upload Sort File (Step 1 of 3)
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="chilean_pallet_update_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
//				AND ('".$HTTP_COOKIE_VARS["eport_customer_id"]."' = 0
//					OR
//					TO_CHAR(LR_NUM) IN 
//						(SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."' OR RECEIVER_ID IN 
//							(SELECT CUSTOMER_ID FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."'))
//					)
   $sql = "SELECT LR_NUM, VESSEL_NAME 
			FROM VESSEL_PROFILE 
			WHERE SHIP_PREFIX = 'CHILEAN'
				AND ARRIVAL_NUM != '4321'
				AND VESSEL_FLAG = 'Y' 
			ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($cur_ves == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select>
		</td>
<?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."'"; 
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($Short_Term_Row['THE_COUNT'] > 0 || $HTTP_COOKIE_VARS["eport_customer_id"] == 0){
?>
		<td align="left">Customer to view:  <select name="view_cust">
<?
		if($HTTP_COOKIE_VARS["eport_customer_id"] == 0){
			$sql = "SELECT DISTINCT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
					FROM CUSTOMER_PROFILE CP, CARGO_TRACKING CT, VESSEL_PROFILE VP
					WHERE CP.CUSTOMER_ID = CT.RECEIVER_ID
						AND CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
						AND VP.VESSEL_FLAG = 'Y'
					ORDER BY CP.CUSTOMER_ID";
		} else {
			$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
					FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
					WHERE CECL.EPORT_LOGIN = '".$user."'
						AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
					ORDER BY CP.CUSTOMER_ID";
		}
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($view_cust == $row['CUSTOMER_ID']){?> selected <?}?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
					</select></td>
<?
	}
?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">(Note:  If the list is empty, there are no vessels available to be sorted at this time.<br>We upload the vessel data as soon as we get it from the shipping line.<br>Please check back again.)</font></td>
		<td><font size="2" face="Verdana">Change to Process effective 4-11-2016:<br>If Hatch/deck info is provided, we will now update our tables to reflect this.<br>Also, if you do not provide a commodity code, we will default to 8060 (Grapes).<br>Please use DSPC commodity codes when uploading (8093=Peaches, 8094=Plums, etc.)<br>If you do not know the code, please contact DSPC.</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Vessel Info"></td>
	</tr>
</form>
</table>
<?
	if($cur_ves != ""){
?>
<br><br>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="file_submit" action="chilean_pallet_update_confirm_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
	<tr>
		<td align="left"><font size="3" face="Verdana">Select Excel File:</font>  <font size="2" face="Verdana"><a href="ImportXLSInstructions.doc">(File Instructions)</a></font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"<? if($user_cust_num == "3000"){?> disabled <?}?>></td>
	</tr>
</form>
</table>
<br><br>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT PALLET_ID, RECEIVER_ID, COMMODITY_NAME, VARIETY, REMARK, CARGO_SIZE, HATCH, QTY_RECEIVED, WAREHOUSE_LOCATION,
			CARGO_DESCRIPTION, BATCH_ID
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP WHERE ARRIVAL_NUM = '".$cur_ves."' AND RECEIVER_ID = '".$user_cust_num."' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE ORDER BY PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">No cargo registered for current Vessel/Customer combination</font></td>
	</tr>
<?
		} else {
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$user_cust_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="<? echo $colspan; ?>" align="center"><font size="3" face="Verdana"><b>Expected Cargo For <? echo $Short_Term_Row['CUSTOMER_NAME']; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>IMP</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT_ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Hatch</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty</b></font></td>
		<td><font size="2" face="Verdana"><b>Loc</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower</b></font></td>
		<td><font size="2" face="Verdana"><b>Package</b></font></td>
		<? echo $additional_headers; ?>
	</tr>
<?
			do {
				if($user_cust_num == "3000"){ // SPECIFIC CUSTOMER SPECIAL INSTRUCTIONS
/*
					$sql = "SELECT GROWER_PALLET_ID, WDI_PO_NUM, WDI_PALLET_TEMP FROM WDI_ADDITIONAL_DATA
							WHERE WDI_PALLET_ID = '".$row['PALLET_ID']."'
							AND WDI_RECEIVER_ID = '3000'
							AND WDI_ARRIVAL_NUM = '".$cur_ves."'";
*/
					$sql = "SELECT MARK 
							FROM CARGO_TRACKING
							WHERE PALLET_ID = '".$row['PALLET_ID']."'
							AND RECEIVER_ID = '3000'
							AND ARRIVAL_NUM = '".$cur_ves."'";
					ora_parse($VERY_Short_Term_Cursor, $sql);
					ora_exec($VERY_Short_Term_Cursor);
					if(!ora_fetch_into($VERY_Short_Term_Cursor, $VERY_Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//						$additional_columns = "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
						$additional_columns = "<td>&nbsp;</td>";
					} else {
/*
						$additional_columns = "<td><font size=\"2\" face=\"Verdana\">".$VERY_Short_Term_Row['GROWER_PALLET_ID']."</font></td>
						<td><font size=\"2\" face=\"Verdana\">".$VERY_Short_Term_Row['WDI_PO_NUM']."</font></td>
						<td><font size=\"2\" face=\"Verdana\">".$VERY_Short_Term_Row['WDI_PALLET_TEMP']."</font></td>";
*/
						$additional_columns = "<td><font size=\"2\" face=\"Verdana\">".$VERY_Short_Term_Row['MARK']."</font></td>";
					}
				}
						
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $user_cust_num; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMODITY_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['VARIETY']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['REMARK']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['CARGO_SIZE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['HATCH']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WAREHOUSE_LOCATION']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['BATCH_ID']; ?>&nbsp;</font></td>
		<? echo $additional_columns; ?>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
</table>
<?
	//include("pow_footer.php");
?>