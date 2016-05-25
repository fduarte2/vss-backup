<?
/*
*		Adam Walter, Dec 2015.
*
*		Allow INVE (or whoever) to update critical information for Containers
*********************************************************************************/


  
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");  echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Grid"){
		$cust_select = $HTTP_POST_VARS['cust_select'];
		$active_select = $HTTP_POST_VARS['active_select'];

		$container = $HTTP_POST_VARS['container'];
		$kg = $HTTP_POST_VARS['kg'];
		$discharge = $HTTP_POST_VARS['discharge'];
		$booking = $HTTP_POST_VARS['booking'];
		$goods = $HTTP_POST_VARS['goods'];
		$goods = str_replace("'", "`", $goods);
		$goods = str_replace("\\", "", $goods);
		$slot = $HTTP_POST_VARS['slot'];
		$slot = str_replace("'", "`", $slot);
		$slot = str_replace("\\", "", $slot);
//		$delete = $HTTP_POST_VARS['delete'];
//		$delete_confirm = $HTTP_POST_VARS['delete_confirm'];
//		$exclude = $HTTP_POST_VARS['exclude'];

		$maxrows = $HTTP_POST_VARS['maxrows'];

		$valid = ValidityCheck($rfconn, $kg, $goods, $slot, $delete, $delete_confirm, $exclude, $booking, $maxrows);

		if($valid != ""){
			echo "<font color=\"#FF0000\">Could not save Changes:<br>".$valid."<br>Please correct and Resubmit.<br></font>";
		} else {
			for($i = 0; $i <= $maxrows; $i++){
				$sql = "SELECT CD.CONTAINER_DETAIL_ID
						FROM CONTAINER_DETAILS CD, CONTAINER_ACTIVITY CA
						WHERE CA.SERVICE_CODE = '11'
							AND CD.CONTAINER_DETAIL_ID = CA.FOREIGN_ID
							AND CA.CONTAINER_ID = '".$container[$i]."'
							AND CA.ARRIVAL_NUM = '".$vessel."'";
				$test_exist = ociparse($rfconn, $sql);
				ociexecute($test_exist);
				if(!ocifetch($test_exist)){
					// new entry.
					$sql = "SELECT CONTAINER_DETAIL_SEQ.NEXTVAL THE_MAX
									FROM DUAL";
					$result = ociparse($rfconn, $sql);
					ociexecute($result);
					ocifetch($result);
					$max_act = ociresult($result, "THE_MAX");

					$sql = "INSERT INTO CONTAINER_DETAILS
								(CONTAINER_DETAIL_ID,
								SLOT,
								KG,
								LOAD_PORT,
								DISCHARGE_PORT,
								TYPE,
								CARRIER,
								DESCRIPTION_OF_GOODS,
								ARRIVAL_NUM,
								BOOKING_NUMBER)
							VALUES
								('".($max_act + 1)."',
								'".$slot[$i]."',
								'".$kg[$i]."',
								'USILG',
								'".$discharge[$i]."',
								'45R1',
								'TGBS',
								'".$goods[$i]."',
								'".$vessel."',
								'".$booking[$i]."')";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);

					$sql = "INSERT INTO CONTAINER_ACTIVITY
								(CONTAINER_ID,
								SERVICE_CODE,
								ACTIVITY_DATE,
								USERNAME,
								FOREIGN_ID,
								ARRIVAL_NUM)
							VALUES
								('".$container[$i]."',
								'11',
								SYSDATE,
								'".$user."',
								'".($max_act + 1)."',
								'".$vessel."')";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);
				} else {
					// updating previous entry
					$sql = "UPDATE CONTAINER_DETAILS
							SET SLOT = '".$slot[$i]."',
								DISCHARGE_PORT = '".$discharge[$i]."',
								DESCRIPTION_OF_GOODS = '".$goods[$i]."',
								BOOKING_NUMBER = '".$booking[$i]."',
								KG = '".$kg[$i]."'
							WHERE CONTAINER_DETAIL_ID = '".ociresult($test_exist, "CONTAINER_DETAIL_ID")."'";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);
				}								
			}

			echo "<font color=\"#0000FF\">Data saved for vessel ".$vessel.".<br></font>";
			$submit = "";
			$vessel = "";
		}
	}
			

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Finalize Containers on Vessel
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="container_edit_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td colspan="10"><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CLEMENTINES', 'CHILEAN', 'ARG FRUIT', 'ARG JUICE')
					AND ARRIVAL_NUM IN
						(SELECT ARRIVAL_NUM FROM CONTAINER_ACTIVITY)
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Containers"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="container_edit_index.php" method="post">
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Save Grid"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>ContainerID</b></font>
		<td><font size="2" face="Verdana"><b>Slot</b></font>
		<td><font size="2" face="Verdana"><b>Yard Scan</b></font>
		<td><font size="2" face="Verdana"><b>Point Scan</b></font>
		<td><font size="2" face="Verdana"><b>Stow Scan</b></font>
		<td><font size="2" face="Verdana"><b>Discharge Port</b></font>
		<td><font size="2" face="Verdana"><b>Weight (KG)</b></font>
		<td><font size="2" face="Verdana"><b>Description of Goods</b></font>
		<td><font size="2" face="Verdana"><b>Booking#</b></font>
<!--		<td><font size="2" face="Verdana"><b>Exclude from Output File?</b></font> !-->
	</tr>
<?

		$sql = "SELECT DISTINCT CONTAINER_ID
				FROM CONTAINER_ACTIVITY CA
				WHERE SERVICE_CODE IN (39, 40, 41)
					AND CA.ARRIVAL_NUM = '".$vessel."'
				ORDER BY CONTAINER_ID";
		$containers = ociparse($rfconn, $sql);
		ociexecute($containers);
		if(!ocifetch($containers)){
?>
	<tr>
		<td colspan="13" align="center"><font size="2" face="Verdana">No data found for vessel.</font></td>
	</tr>
<?
			} else {
				$row = -1;
?>
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<?
				do {
					$row++;

					$display_data = array();

					$sql = "SELECT TO_CHAR(ACTIVITY_DATE, 'MM/DD/YYYY HH24:MI') THE_ACT, USERNAME
							FROM CONTAINER_ACTIVITY
							WHERE CONTAINER_ID = '".ociresult($containers, "CONTAINER_ID")."'
								AND ARRIVAL_NUM = '".$vessel."'
								AND SERVICE_CODE = '39'
							ORDER BY ACTIVITY_DATE DESC";
					$yard = ociparse($rfconn, $sql);
					ociexecute($yard);
					if(!ocifetch($yard)){
						// nothing
					} else {
						$display_data['yardscantime'] = ociresult($yard, "THE_ACT");
						$display_data['yardscanby'] = ociresult($yard, "USERNAME");
					}
					$sql = "SELECT TO_CHAR(ACTIVITY_DATE, 'MM/DD/YYYY HH24:MI') THE_ACT, USERNAME
							FROM CONTAINER_ACTIVITY
							WHERE CONTAINER_ID = '".ociresult($containers, "CONTAINER_ID")."'
								AND ARRIVAL_NUM = '".$vessel."'
								AND SERVICE_CODE = '40'
							ORDER BY ACTIVITY_DATE DESC";
					$yard = ociparse($rfconn, $sql);
					ociexecute($yard);
					if(!ocifetch($yard)){
						// nothing
					} else {
						$display_data['pointscantime'] = ociresult($yard, "THE_ACT");
						$display_data['pointscanby'] = ociresult($yard, "USERNAME");
					}
					$sql = "SELECT TO_CHAR(ACTIVITY_DATE, 'MM/DD/YYYY HH24:MI') THE_ACT, USERNAME, VALUE
							FROM CONTAINER_ACTIVITY
							WHERE CONTAINER_ID = '".ociresult($containers, "CONTAINER_ID")."'
								AND ARRIVAL_NUM = '".$vessel."'
								AND SERVICE_CODE = '41'
							ORDER BY ACTIVITY_DATE DESC";
					$yard = ociparse($rfconn, $sql);
					ociexecute($yard);
					if(!ocifetch($yard)){
						// nothing
					} else {
						$display_data['stowscantime'] = ociresult($yard, "THE_ACT");
						$display_data['stowscanby'] = ociresult($yard, "USERNAME");
						$display_data['stowscanslot'] = ociresult($yard, "VALUE");
					}

					$sql = "SELECT * FROM CONTAINER_DETAILS CD, CONTAINER_ACTIVITY CA
							WHERE CA.CONTAINER_ID = '".ociresult($containers, "CONTAINER_ID")."'
								AND CA.ARRIVAL_NUM = '".$vessel."'
								AND CA.SERVICE_CODE = '11'
								AND CA.FOREIGN_ID = CD.CONTAINER_DETAIL_ID
							ORDER BY ACTIVITY_DATE DESC";  // order by shouldnt be necessary, but if for whatever reason service code 11 is not unique for a container/vessel...
					$pre_11 = ociparse($rfconn, $sql);
					ociexecute($pre_11);
					if(!ocifetch($pre_11)){
						// slot already set above
						$display_data['discharge'] = "";
						$display_data['kg'] = "";
						$display_data['goods'] = "";
						$display_data['booking'] = "";
					} else {
						$display_data['stowscanslot'] = ociresult($pre_11, "SLOT");
						$display_data['discharge'] = ociresult($pre_11, "DISCHARGE_PORT");
						$display_data['kg'] = ociresult($pre_11, "KG");
						$display_data['goods'] = ociresult($pre_11, "DESCRIPTION_OF_GOODS");
						$display_data['booking'] = ociresult($pre_11, "BOOKING_NUMBER");
					}


?> 
	<tr>
		<input type="hidden" name="container[<? echo $row; ?>]" value="<? echo ociresult($containers, "CONTAINER_ID"); ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($containers, "CONTAINER_ID"); ?></font></td>
		<td><input type="text" name="slot[<? echo $row; ?>]" size="10" maxlength="10" value="<? echo $display_data['stowscanslot']; ?>"></td>
		<td><font size="2" face="Verdana"><? echo $display_data['yardscantime']; ?><br><? echo $display_data['yardscanby']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $display_data['pointscantime']; ?><br><? echo $display_data['pointscanby']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $display_data['stowscantime']; ?><br><? echo $display_data['stowscanby']; ?></font></td>
		<td><select name="discharge[<? echo $row; ?>]">
				<option value=""></option>
				<option value="CLCLD"<? if($display_data['discharge'] == "CLCLD"){?> selected <?}?>>CLCLD</option>
				<option value="CLVAP"<? if($display_data['discharge'] == "CLVAP"){?> selected <?}?>>CLVAP</option>
				<option value="WILM"<? if($display_data['discharge'] == "WILM"){?> selected <?}?>>WILM</option>
				<option value="USLAX"<? if($display_data['discharge'] == "USLAX"){?> selected <?}?>>USLAX</option>


				</select></td>
		<td><input type="text" name="kg[<? echo $row; ?>]" size="10" maxlength="10" value="<? echo $display_data['kg']; ?>"></td>
		<td><input type="text" name="goods[<? echo $row; ?>]" size="80" maxlength="100" value="<? echo $display_data['goods']; ?>"></td>
		<td><input type="text" name="booking[<? echo $row; ?>]" size="20" maxlength="100" value="<? echo $display_data['booking']; ?>"></td>
<!--		<td><input type="checkbox" name="delete[<? echo $row; ?>]" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="delete_confirm[<? echo $row; ?>]" size="10" maxlength="10"></td> !-->
<!--		<td><input type="checkbox" name="exclude[<? echo $row; ?>]" value="Yes"></td> !-->
	</tr>
<?
				} while(ocifetch($containers));
			}
?>
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Save Grid"></td>
	</tr>
<input name="maxrows" type="hidden" value="<? echo $row; ?>">
</form>
</table>
<?
	}
	include("pow_footer.php");













function ValidityCheck($rfconn, $kg, $goods, $slot, $delete, $delete_confirm, $exclude, $booking, $maxrows){
	$return = "";

	for($i = 0; $i <= $maxrows; $i++){
		//character-based checks
		if($kg[$i] == ""){
			// this is fine
		} elseif(!is_numeric($kg[$i])){
			$return .= "Weight (in KG) must be a number on line ".($i + 1).".  Was entered as ".$kg[$i].".<br>";
		} elseif($kg[$i] <= 0){
			$return .= "Weight (in KG) must be greater than 0 on line ".($i + 1).".  Was entered as ".$kg[$i].".<br>";
		}
/*
		if($slot[$i] == ""){
			// this is fine
		} elseif(!i have no idea){
			$return .= "slot must be something on line ".($i + 1).".<br>";
		}

		if($goods[$i] == ""){
			// this is fine
		} elseif(!i have no idea){
			$return .= "Description of Goods must be something on line ".($i + 1).".<br>";
		}

		if($booking[$i] == ""){
			// this is fine
		} elseif(!i have no idea){
			$return .= "Booking must be something on line ".($i + 1).".<br>";
		}
*/
/*
		if($delete[$i] == "Yes" && strtoupper($delete_confirm[$i]) != "DELETE"){ 
			$return .= "To prevent accidental deletions, if you want to delete a row, you have to type in \"DELETE\" in the box next to it.<br>";
		}
*/
	}
	return $return;
}