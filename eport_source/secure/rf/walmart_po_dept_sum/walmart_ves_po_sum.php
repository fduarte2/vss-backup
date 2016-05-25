<?
/*
*
*	Walmart.  summary be vessela nd po, broken down by "dept"
*	(read:  sams, and everything else)
*****************************************************************/
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
//    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  if($user_cust_num != 3000){
	  echo "user not authorized to use this page.  Please use your browser's back button, or choose another link to continue";
	  exit;
  }


	$cursor = ora_open($conn);         // general purpose
	$Short_Term_Cursor = ora_open($conn);

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	if($submit != "" && $vessel != ""){
		$filename = "VesselPOSum".$vessel."On".date(mdYhis).".xls";
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "Error writing to file, please contact PoW.\n";
			exit;
		}
		fwrite($handle, "<table><tr><td colspan=\"9\">Walmart Vessel P.O. Summary by Department</td></tr>");
		fwrite($handle, "<tr><td colspan=\"9\">Eport/rf/walmart_po_dept_sum/walmart_ves_po_sum_index.php</td></tr>");
		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $st_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		fwrite($handle, "<tr><td>Vessel:</td><td colspan=\"8\">".$vessel." - ".$st_row['VESSEL_NAME']."</td></tr>");
		fwrite($handle, "<tr><td>Date:</td><td colspan=\"8\" align=\"left\">".date('m/d/Y h:i')."</td></tr></table>");
		fwrite($handle, "<table border =\"1\">
							<tr>
								<td>Dept</td>
								<td>P.O. Number</td>
								<td>Exporter</td>
								<td>Grower Item Num</td>
								<td>WM Item Num</td>
								<td>Recv PO</td>
								<td>Description</td>
								<td>Pallets</td>
								<td>Cases</td>
							</tr>");
		$plts_tot = 0;
		$ctns_tot = 0;

//DECODE(SUBSTR(CT.MARK, 0, 2), '21', '64', '94')

		fwrite($handle, "<tr><td colspan=\"9\">064 - Sam's Club</td></tr>");
		// sams first
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(CT.QTY_RECEIVED) THE_CTNS, CT.EXPORTER_CODE, CT.BOL,
					CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND SUBSTR(CT.MARK, 0, 2) = '21'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.EXPORTER_CODE, CT.BOL, CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON')
				ORDER BY CT.MARK";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// no data
			fwrite($handle, "<tr><td colspan=\"9\" align=\"center\">No Sams Club Pallets Found</td></tr>");
		} else {
			$plts_sub = 0;
			$ctns_sub = 0;

			do {
				$plts_sub += $row['THE_PLTS'];
				$plts_tot += $row['THE_PLTS'];
				$ctns_sub += $row['THE_CTNS'];
				$ctns_tot += $row['THE_CTNS'];

				$temp = explode("_", $row['EXPORTER_CODE']);
				$exp = $temp[0]."-".$temp[1];

				fwrite($handle, "<tr>
									<td></td>
									<td>".$row['MARK']."</td>
									<td>".$exp."</td>
									<td>".$row['BATCH_ID']."</td>
									<td>".$row['BOL']."</td>
									<td>".$row['MARK']."</td>
									<td>".$row['THE_VAR']."</td>
									<td>".$row['THE_PLTS']."</td>
									<td>".$row['THE_CTNS']."</td>
								</tr>");
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			fwrite($handle, "<tr><td colspan=\"7\">Dept 064 Totals:</td><td>".$plts_sub."</td><td>".$ctns_sub."</td></tr>");
		}
//		fclose($handle);

		fwrite($handle, "<tr><td colspan=\"9\">094 - Wal-Mart Supercenter</td></tr>");
		// everyone NOT sams
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(CT.QTY_RECEIVED) THE_CTNS, CT.EXPORTER_CODE, CT.BOL,
					CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND SUBSTR(CT.MARK, 0, 2) != '21'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.EXPORTER_CODE, CT.BOL, CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON')
				ORDER BY CT.MARK";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// no data
			fwrite($handle, "<tr><td colspan=\"9\" align=\"center\">No Walmart Supercenter Pallets Found</td></tr>");
		} else {
			$plts_sub = 0;
			$ctns_sub = 0;

			do {
				$plts_sub += $row['THE_PLTS'];
				$plts_tot += $row['THE_PLTS'];
				$ctns_sub += $row['THE_CTNS'];
				$ctns_tot += $row['THE_CTNS'];

				$temp = explode("_", $row['EXPORTER_CODE']);
				$exp = $temp[0]."-".$temp[1];

				fwrite($handle, "<tr>
									<td></td>
									<td>".$row['MARK']."</td>
									<td>".$exp."</td>
									<td>".$row['BATCH_ID']."</td>
									<td>".$row['BOL']."</td>
									<td>".$row['MARK']."</td>
									<td>".$row['THE_VAR']."</td>
									<td>".$row['THE_PLTS']."</td>
									<td>".$row['THE_CTNS']."</td>
								</tr>");
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			fwrite($handle, "<tr><td colspan=\"7\">Dept 094 Totals:</td><td>".$plts_sub."</td><td>".$ctns_sub."</td></tr>");
		}
	
		fwrite($handle, "<tr><td colspan=\"9\">&nbsp;</td></tr>");
		fwrite($handle, "<tr><td colspan=\"7\">Grand Totals:</td><td>".$plts_tot."</td><td>".$ctns_tot."</td></tr>");
		fwrite($handle, "</table>");
		fclose($handle);
	}




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Vessel PO summary by Dept
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($submit == "" || $vessel == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="walmart_ves_po_sum_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($vessel == ""){?> selected <?}?>>Select a Vessel</option>
<?
	   // POPULATE TOP DROPDOWN BOX
	   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT') AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."') AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '3000') ORDER BY LR_NUM DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($vessel == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
		}
?>
					</select>
		</td>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Info"></td>
	</tr>
</form>
</table>
<?
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana">File Generated.</font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><a href="<? echo $filename; ?>">Click Here</a> for the Excel Spreadsheet.</font></td>
	</tr>
</table>
<?
	}
?>