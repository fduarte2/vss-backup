<!-- Vessel Discharge Report - Main Page -->
<!--
<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      ship = x.ship.value

      if (ship == "") {
	 alert("You need to select a vessel to view its discharge report!")
         return false
      }
   }
</script>
!-->
<?
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$cust = $HTTP_POST_VARS['cust'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$fume = $HTTP_POST_VARS['fume'];
	$swingload = $HTTP_POST_VARS['swingload'];
	$submit = $HTTP_POST_VARS['submit'];
?>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fumigation Report</font>
         <br />
	 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="get_data" action="fumigation_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">Customer:&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="cust"><? if($eport_customer_id == 0){ ?> <option value="all">All</option> <? } ?>
<?
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE
			WHERE 1 = 1";
	if($eport_customer_id != 0){
		$sql .= " AND CUSTOMER_ID = '".$eport_customer_id."'";
	}
	$sql .= " ORDER BY CUSTOMER_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Vessel:&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="vessel"><option value="all">All</option>
<?
/*	$sql = "SELECT DISTINCT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE VP, CARGO_TRACKING CT
			WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
			AND CT.COMMODITY_CODE IN
				(SELECT COMMODITY_CODE
				FROM COMMODITY_PROFILE
				WHERE COMMODITY_TYPE = 'CHILEAN')
			ORDER BY LR_NUM DESC";*/
	$sql = "SELECT DISTINCT
                   VP.ARRIVAL_NUM THE_LR,
                   LR_NUM || '-' || VESSEL_NAME THE_VESSEL
            FROM VESSEL_PROFILE VP
            INNER JOIN CARGO_TRACKING CT
                  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
            WHERE
                VP.SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT')
            ORDER BY VP.ARRIVAL_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['THE_LR']; ?>"<? if($row['THE_LR'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_LR']." - ".$row['THE_VESSEL']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Fume Status:&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="fume"><option value="all">All</option>
								<option value="N" <? if($fume == "N"){ ?> selected <? } ?>>N</option>
								<option value="Y" <? if($fume == "Y"){ ?> selected <? } ?>>Y</option>
								<option value="Unspec" <? if($fume == "Unspec"){ ?> selected <? } ?>>Unspecified</option>
					</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Swingloads?:&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="swingload"><option value="N" <? if($swingload == "N"){ ?> selected <? } ?>>N</option>
								<option value="Y" <? if($swingload == "Y"){ ?> selected <? } ?>>Y</option>
								<option value="all" <? if($swingload == "all"){ ?> selected <? } ?>>All</option>
					</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Pallets"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
<?
	if($submit != ""){
		$output = "<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\">";

		$sql = "SELECT CT.ARRIVAL_NUM, VESSEL_NAME, COMMODITY_CODE, PALLET_ID, CARGO_SIZE, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_REC,
					QTY_RECEIVED, RECEIVER_ID, FUMIGATION_CODE, VARIETY, REMARK, WAREHOUSE_LOCATION, HATCH
				FROM CARGO_TRACKING CT, VESSEL_PROFILE VP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
				AND DATE_RECEIVED IS NOT NULL";
		if($cust != "all"){
			$sql .= " AND RECEIVER_ID = '".$cust."'";
		}
		if($vessel != "all"){
			$sql .= " AND CT.ARRIVAL_NUM = '".$vessel."'";
		}
		switch($fume){
			case "all":
				// do nothing
			break;
			case "N":
				$sql .= " AND FUMIGATION_CODE = 'N'";
			break;
			case "Y":
				$sql .= " AND FUMIGATION_CODE = 'Y'";
			break;
			case "Unspec":
				$sql .= " AND FUMIGATION_CODE IS NULL";
			break;
		}
		switch($swingload){
			case "all":
				// do nothing
			break;
			case "N":
				$sql .= " AND (WAREHOUSE_LOCATION IS NULL OR WAREHOUSE_LOCATION != 'SW')";
			break;
			case "Y":
				$sql .= " AND WAREHOUSE_LOCATION = 'SW'";
			break;
		}
		$sql .= " ORDER BY CT.ARRIVAL_NUM, RECEIVER_ID, PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output .= "<tr><td><font size=\"2\" face=\"Verdana\">No Pallets Received that match selected criteria</font></td></tr></table>";
			$makefile = false;
			$XLS_line = "";
		} else {
			$total_plt = 0;

			$makefile = true;
			$filename = "FumeReport".date('mdYhis').".xls";
			// ok, we have pallets!
			// lets do somthing...
			$XLS_line = "<tr><td align=\"center\" colspan=\"12\"><a href=\"fum_excels/".$filename."\">Click Here for an XLS version of this report</a></td></tr>";
//			$output .= "<tr><td align=\"center\" colspan=\"12\"><a href=\"fum_excels/".$filename."\">Click Here for an XLS version of this report</a></td></tr>";
			$output .= "Placeholder";
			$output .= "<tr>
							<td><font size=\"2\" face=\"Verdana\"><b>Vessel</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Commodity Code</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Barcode</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Cargo Size</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Date Received</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Qty Received</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Cust#</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Fumigation Code</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Variety</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Remark</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Warehouse Loc</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Hatch</b></font></td>
						</tr>";
			do {
				$total_plt++;
				$output .= "<tr>
								<td><font size=\"2\" face=\"Verdana\">".$row['ARRIVAL_NUM']." - ".$row['VESSEL_NAME']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['COMMODITY_CODE']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['PALLET_ID']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['CARGO_SIZE']."&nbsp;</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_REC']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['QTY_RECEIVED']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['RECEIVER_ID']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['FUMIGATION_CODE']."&nbsp;</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['VARIETY']."&nbsp;</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['REMARK']."&nbsp;</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_LOCATION']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['HATCH']."&nbsp;</font></td>
							</tr>";
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$output .= "<tr bgcolor=\"#00FF00\">
							<td colspan=\"2\"><font size=\"2\" face=\"Verdana\"><b>Total:</b></font></td>
							<td colspan=\"10\" align=\"left\"><font size=\"2\" face=\"Verdana\"><b>".$total_plt."</b></font></td>
						</tr>";

			$output .= "</table>";
		}

		if($makefile == true){
			$fp = fopen("fum_excels/".$filename, "w");
			fwrite($fp, str_replace("Placeholder", "", $output));
			fclose($fp);
		}

		echo str_replace("Placeholder", $XLS_line, $output);
	}
?>

