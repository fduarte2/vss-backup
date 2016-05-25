<?
/*
*
*	Note:  a duplicate of this program is found on the intranet
*
*	/web/web_pages/TS_Program/in_the_future.notyetphp
*
*	Because it aso needs to be emailed, and Eport cant do that.
*
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
		$filename = "Vessel".$vessel."On".date(mdYhis).".xls";
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "Error writing to file, please contact PoW.\n";
			exit;
		}

		$sql = "SELECT CT.PALLET_ID, CT.QTY_RECEIVED, CT.EXPORTER_CODE, CP.COMMODITY_NAME, CT.VARIETY, CT.CARGO_SIZE, CT.CARGO_DESCRIPTION,
					CT.FUMIGATION_CODE, CT.HATCH, CT.CONTAINER_ID, CT.MARK, CT.BATCH_ID, DECODE(SUBSTR(CT.MARK, 0, 2), '21', '64', '94') THE_DEPT
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
				WHERE CT.ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '3000'
					AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
				ORDER BY CT.PALLET_ID";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// no data
			fwrite($handle, "No data for selected vessel.");
		} else {
//			echo $row['PALLET_ID']."<br>";
			fwrite($handle, "<table><tr><td colspan=\"15\">WALMART</td></tr>");
			fwrite($handle, "<tr><td colspan=\"15\">eport/rf/walmart_ves_detail</td></tr>");
			$sql = "SELECT VESSEL_NAME
					FROM VESSEL_PROFILE
					WHERE LR_NUM = '".$vessel."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $st_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//			echo $sql;
			fwrite($handle, "<tr><td>Vessel:</td><td colspan=\"14\">".$vessel." - ".$st_row['VESSEL_NAME']."</td></tr>");
			fwrite($handle, "<tr>
									<td>Pallet Id</td>
									<td>Cartons</td>
									<td>Label</td>
									<td>Commodity</td>
									<td>Variety</td>
									<td>Size</td>
									<td>Description</td>
									<td>Grower</td>
									<td>Exporter</td>
									<td>Fum</td>
									<td>Hatch & Deck</td>
									<td>Cont No</td>
									<td>Temp Rec</td>
									<td>Booking P.O. No.</td>
									<td>Inbound Item No</td>
									<td>Dept. No.</td>
								</tr>");
			do {
//				echo $row['PALLET_ID']."<br>";
				$exp_split = explode("_", $row['EXPORTER_CODE']);
				if($exp_split[0] < 0){
					$exp_split[0] = "MULTIPLE";
				}
				fwrite($handle, "<tr>
									<td>".$row['PALLET_ID']."</td>
									<td>".$row['QTY_RECEIVED']."</td>
									<td></td>
									<td>".$row['COMMODITY_NAME']."</td>
									<td>".$row['VARIETY']."</td>
									<td>".$row['CARGO_SIZE']."</td>
									<td>".$row['CARGO_DESCRIPTION']."</td>
									<td>".$exp_split[0]."</td>
									<td>".$exp_split[1]."</td>
									<td>".$row['FUMIGATION_CODE']."</td>
									<td>".$row['HATCH']."</td>
									<td>".$row['CONTAINER_ID']."</td>
									<td></td>
									<td>".$row['MARK']."</td>
									<td>".$row['BATCH_ID']."</td>
									<td>".$row['THE_DEPT']."</td>
								</tr>");
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			fwrite($handle, "</table>");
		}
		fclose($handle);
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Generate Vessel Detail File
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
<form name="get_data" action="walmart_ves_detail_index.php" method="post">
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