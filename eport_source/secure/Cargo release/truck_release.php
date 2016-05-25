<?
/*
*		Adam Walter, July 2014.
*
*		Trucker "main segment" of Cargo Load Release
*********************************************************************************/


	$pagename = "truck_check_in";
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$sql_filter .= CustV2UserCheck($user, "CUSTOMER_ID", $rfconn);

	
	$submit = $HTTP_POST_VARS['submit'];
	$truck_ID = $HTTP_POST_VARS['truck_ID'];
	if($truck_ID == ""){
		$truck_ID = $HTTP_GET_VARS['truck_ID'];
	}
	$badsave = "";
//	echo "submit:  ".$submit."<br>";
/*
	if($submit == "Check In New Trucker" && $truck_ID == "New"){
		$sql = "INSERT INTO CLR_TRUCK_LOAD_RELEASE
					(PORT_ID,
					LAST_CHANGED_BY,
					LAST_CHANGED_ON)
				(SELECT NVL(MAX(PORT_ID), 0) + 1,
					'".$user."',
					SYSDATE
				FROM CLR_TRUCK_LOAD_RELEASE)";
		$new_entry = ociparse($rfconn, $sql);
		ociexecute($new_entry);

		$sql = "SELECT MAX(PORT_ID) THE_MAX
				FROM CLR_TRUCK_LOAD_RELEASE";
		$get_num = ociparse($rfconn, $sql);
		ociexecute($get_num);
		ocifetch($get_num);
		$truck_ID = ociresult($get_num, "THE_MAX");
	}
*/
	if($submit == "Save" || $submit == "Add More Orders"){
		$driver_name = filter_input($HTTP_POST_VARS['driver_name']);
		$driver_lic_num = filter_input($HTTP_POST_VARS['driver_lic_num']);
		$driver_lic_state = filter_input($HTTP_POST_VARS['driver_lic_state']);
		$clem_order = filter_input($HTTP_POST_VARS['clem_order']);
		$arrival_num = filter_input($HTTP_POST_VARS['arrival_num']);
		$cust = filter_input($HTTP_POST_VARS['cust']);
		$trucking_company = filter_input($HTTP_POST_VARS['trucking_company']);
		$truck_te = filter_input($HTTP_POST_VARS['truck_te']);
		$truck_lic = filter_input($HTTP_POST_VARS['truck_lic']);
		$truck_lic_state = filter_input($HTTP_POST_VARS['truck_lic_state']);
		$trailer_plate = filter_input($HTTP_POST_VARS['trailer_plate']);
		$trailer_plate_state = filter_input($HTTP_POST_VARS['trailer_plate_state']);
		$border_cross = filter_input($HTTP_POST_VARS['border_cross']);
		$seal_num = filter_input($HTTP_POST_VARS['seal_num']);
		$pickup_type = filter_input($HTTP_POST_VARS['pickup_type']);
		$cont = filter_input($HTTP_POST_VARS['cont']);
		$bol = filter_input($HTTP_POST_VARS['bol']);

		if($pickup_type == "BREAKBULK"){
			$cont = "";
			$bol = "";
		}

		$save_valid = ValidateFields($driver_name, $clem_order, $trucking_company, $truck_te, $trailer_plate, $border_cross, $seal_num, $arrival_num, $cust, $cont, $bol, $pickup_type, $submit, $truck_ID, $rfconn);

		if($save_valid != ""){
			$submit = "";
			echo "<font color=\"#FF0000\" size=\"4\">ERROR:  Could not save data:<br><br>".$save_valid."<br>Please fix and Re-Save.</font>";
			$badsave = "bad";
		} else {
			if($truck_ID == "New"){
				$sql = "INSERT INTO CLR_TRUCK_LOAD_RELEASE
							(PORT_ID,
							LAST_CHANGED_BY,
							LAST_CHANGED_ON)
						(SELECT NVL(MAX(PORT_ID), 0) + 1,
							'".$user."',
							SYSDATE
						FROM CLR_TRUCK_LOAD_RELEASE)";
				$new_entry = ociparse($rfconn, $sql);
				ociexecute($new_entry);

				$sql = "SELECT MAX(PORT_ID) THE_MAX
						FROM CLR_TRUCK_LOAD_RELEASE";
				$get_num = ociparse($rfconn, $sql);
				ociexecute($get_num);
				ocifetch($get_num);
				$truck_ID = ociresult($get_num, "THE_MAX");
			}

			$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE SET
						DRIVER_NAME = '".$driver_name."',
						DRIVER_LIC_NUM = '".$driver_lic_num."',
						DRIVER_LIC_STATE = '".$driver_lic_state."',
						CLEM_ORDER_NUM = '".$clem_order."',
						TRUCKING_COMPANY = '".$trucking_company."',
						TRUCK_TE_NUM = '".$truck_te."',
						TRUCK_LIC_NUM = '".$truck_lic."',
						TRUCK_LIC_STATE = '".$truck_lic_state."',
						TRAIL_REEF_PLATE_NUM = '".$trailer_plate."',
						TRAIL_REEF_PLATE_STATE = '".$trailer_plate_state."',
						BORDER_CROSSING = '".$border_cross."',
						SEAL_NUM = '".$seal_num."',
						PICKUP_TYPE = '".$pickup_type."',
						CUSTOMER_ID = '".$cust."',
						ARRIVAL_NUM = '".$arrival_num."',
						CONTAINER_NUM = '".$cont."',
						BOL_EQUIV = '".$bol."',
						LAST_CHANGED_BY = '".$user."',
						LAST_CHANGED_ON = SYSDATE
					WHERE PORT_ID = '".$truck_ID."'";
			$save_info = ociparse($rfconn, $sql);
			ociexecute($save_info);

			$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
					SET CHECKIN = SYSDATE
					WHERE PORT_ID = '".$truck_ID."'
						AND CHECKIN IS NULL";
			$save_info = ociparse($rfconn, $sql);
			ociexecute($save_info);

			if($pickup_type != "BREAKBULK"){
				$sql = "SELECT CLR_KEY
						FROM CLR_MAIN_DATA
						WHERE BOL_EQUIV = '".$bol."'
							AND ARRIVAL_NUM = '".$arrival_num."'
							AND CONTAINER_NUM = '".$cont."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$clr_key = ociresult($short_term_data, "CLR_KEY");

				$sql = "DELETE FROM CLR_TRUCK_MAIN_JOIN
						WHERE TRUCK_PORT_ID = '".$truck_ID."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

				$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
							(MAIN_CLR_KEY,
							TRUCK_PORT_ID)
						VALUES
							('".$clr_key."',
							'".$truck_ID."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
			}

			if($submit == "Add More Orders"){
				$sql = "INSERT INTO CLR_TRUCK_LOAD_RELEASE
							(PORT_ID,
							LAST_CHANGED_BY,
							LAST_CHANGED_ON)
						(SELECT NVL(MAX(PORT_ID), 0) + 1,
							'".$user."',
							SYSDATE
						FROM CLR_TRUCK_LOAD_RELEASE)";
				$new_entry = ociparse($rfconn, $sql);
				ociexecute($new_entry);

				$sql = "SELECT MAX(PORT_ID) THE_MAX
						FROM CLR_TRUCK_LOAD_RELEASE";
				$get_num = ociparse($rfconn, $sql);
				ociexecute($get_num);
				ocifetch($get_num);
				$truck_ID = ociresult($get_num, "THE_MAX");

				$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE SET
							DRIVER_NAME = '".$driver_name."',
							DRIVER_LIC_NUM = '".$driver_lic_num."',
							DRIVER_LIC_STATE = '".$driver_lic_state."',
							TRUCKING_COMPANY = '".$trucking_company."',
							TRUCK_LIC_NUM = '".$truck_lic."',
							TRUCK_LIC_STATE = '".$truck_lic_state."',
							TRAIL_REEF_PLATE_NUM = '".$trailer_plate."',
							TRAIL_REEF_PLATE_STATE = '".$trailer_plate_state."',
							BORDER_CROSSING = '".$border_cross."',
							SEAL_NUM = '".$seal_num."',
							PICKUP_TYPE = '".$pickup_type."',
							CONTAINER_NUM = '".$cont."',
							BOL_EQUIV = '".$bol."',
							LAST_CHANGED_BY = '".$user."',
							LAST_CHANGED_ON = SYSDATE
						WHERE PORT_ID = '".$truck_ID."'";
				$save_info = ociparse($rfconn, $sql);
				ociexecute($save_info);

				$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
						SET CHECKIN = SYSDATE
						WHERE PORT_ID = '".$truck_ID."'
							AND CHECKIN IS NULL";
				$save_info = ociparse($rfconn, $sql);
				ociexecute($save_info);

			}	
		}

	}

	if($submit == "Make Truck"){
		$badsave = "bad"; // used for the "which value do we keep" function later
		$clr_key_populate = $HTTP_POST_VARS['clr_key'];
		$truck_ID = "New";

		$sql = "SELECT * FROM CLR_MAIN_DATA
				WHERE CLR_KEY = '".$clr_key_populate."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);

		$arrival_num = ociresult($short_term_data, "ARRIVAL_NUM");
		$cust = ociresult($short_term_data, "CUSTOMER_ID");
		$cont = ociresult($short_term_data, "CONTAINER_NUM");
		$bol = ociresult($short_term_data, "BOL_EQUIV");
		if(ociresult($short_term_data, "CARGO_MODE") == "HTH" || ociresult($short_term_data, "CARGO_MODE") == "SWING"){
			$pickup_type = ociresult($short_term_data, "CARGO_MODE");
		} else {
			$pickup_type = "BREAKBULK";
		}

	}
?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CLR Trucker Check In
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="ID_select" action="truck_release_index.php" method="post">
	<tr>
		<td width="50%" align="left"><font size="3" face="Verdana">Truck ID#:  
				<select name="truck_ID">
<?
	if(strpos($security_allowance, "M") !== false){
?>
				<option value="New">Check In New Truck</option>
<?
	}
	$sql = "SELECT PORT_ID, NVL(DRIVER_NAME, 'Name Not Yet Entered') THE_NAME 
			FROM CLR_TRUCK_LOAD_RELEASE
				WHERE (GATEPASS_PDF_DATE IS NULL OR TO_CHAR(PORT_ID) = '".$truck_ID."')
			ORDER BY DECODE(DRIVER_NAME, NULL, 'ZZZ', 'AAA'), PORT_ID";
	$truck_data = ociparse($rfconn, $sql);
	ociexecute($truck_data);
	while(ocifetch($truck_data)){
?>
						<option value="<? echo ociresult($truck_data, "PORT_ID"); ?>"<? if($truck_ID == ociresult($truck_data, "PORT_ID")){?> selected <?}?>>
								<? echo ociresult($truck_data, "PORT_ID")." - ".ociresult($truck_data, "THE_NAME"); ?></option>
<?
	}
?>
				</select>&nbsp;&nbsp;<input type="submit" name="submit" value="Select"></td>
		<td><font size="3" face="Verdana">* - Required Fields</font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<!--<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td><font size="3" face="Verdana">--- OR ---</font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Check In New Trucker"><br><hr></td>
	</tr>
<?
	}
?> !-->
</form>
</table>
<?
	if($truck_ID != ""){
		$sql = "SELECT TLR.*
				FROM CLR_TRUCK_LOAD_RELEASE TLR
				WHERE TO_CHAR(PORT_ID) = '".$truck_ID."'";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="update" action="truck_release_index.php" method="post">
<input type="hidden" name="truck_ID" value="<? echo $truck_ID; ?>">
	<tr>
		<td colspan="3" align="left"><font size="3" face="Verdana" color="#0000A0"><b>Truck ID#:  <? echo $truck_ID; ?></b></font><BR><BR></td>
	</tr>
	<tr>
		<td width="15%">&nbsp;</td>
		<td width="25%"><font size="2" face="Verdana"><b>Current:</b></font></td>
		<td><font size="2" face="Verdana"><b>New:</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver Name:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "DRIVER_NAME"); ?></font></td>
		<td><input type="text" name="driver_name" size="30" maxlength="30" value="<? echo GetBadSaveValue(ociresult($main_data, "DRIVER_NAME"), $driver_name, $badsave); ?>">*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "DRIVER_LIC_NUM"); ?></font></td>
		<td><input type="text" name="driver_lic_num" size="10" maxlength="10" value="<? echo GetBadSaveValue(ociresult($main_data, "DRIVER_LIC_NUM"), $driver_lic_num, $badsave); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License State:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "DRIVER_LIC_STATE"); ?></font></td>
		<td><input type="text" name="driver_lic_state" size="4" maxlength="4" value="<? echo GetBadSaveValue(ociresult($main_data, "DRIVER_LIC_STATE"), $driver_lic_state, $badsave); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Order#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo trim(ociresult($main_data, "CLEM_ORDER_NUM")); ?></font></td>
		<td><input type="text" name="clem_order" size="12" maxlength="12" value="<? echo trim(GetBadSaveValue(ociresult($main_data, "CLEM_ORDER_NUM"), $clem_order, $badsave)); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Arrival#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo trim(ociresult($main_data, "ARRIVAL_NUM")); ?></font></td>
		<td><select name="arrival_num"><option value="">Select a Vessel</option>
<?
		$sql = "SELECT * 
				FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CHILEAN', 'CLEMENTINES')
					AND ARRIVAL_NUM IN (SELECT ARRIVAL_NUM FROM CLR_MAIN_DATA)
				ORDER BY LR_NUM DESC";
		$arv_data = ociparse($rfconn, $sql);
		ociexecute($arv_data);
		while(ocifetch($arv_data)){
?>
				<option value="<? echo ociresult($arv_data, "ARRIVAL_NUM"); ?>" <? if(ociresult($arv_data, "ARRIVAL_NUM") == GetBadSaveValue(ociresult($main_data, "ARRIVAL_NUM"), $arrival_num, $badsave)){ ?> selected <? } ?>><? echo ociresult($arv_data, "ARRIVAL_NUM")." - ".ociresult($arv_data, "VESSEL_NAME"); ?></option>
<?
		}
?>
			</select>*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Customer#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo trim(ociresult($main_data, "CUSTOMER_ID")); ?></font></td>
		<td><select name="cust"><option value="">Select a Customer</option>
<?
		$sql = "SELECT * 
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID IN (SELECT RECEIVER_ID FROM SCANNER_ACCESS)
					AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM CLR_MAIN_DATA)
					".$sql_filter."
				ORDER BY CUSTOMER_ID";
		$cust_data = ociparse($rfconn, $sql);
		ociexecute($cust_data);
		while(ocifetch($cust_data)){
?>
				<option value="<? echo ociresult($cust_data, "CUSTOMER_ID"); ?>" <? if(ociresult($cust_data, "CUSTOMER_ID") == GetBadSaveValue(ociresult($main_data, "CUSTOMER_ID"), $cust, $badsave)){ ?> selected <? } ?>><? echo ociresult($cust_data, "CUSTOMER_NAME"); ?></option>
<?
		}
?>
			</select>*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Truck Company:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRUCKING_COMPANY"); ?></font></td>
		<td><input type="text" name="trucking_company" size="20" maxlength="20" value="<? echo GetBadSaveValue(ociresult($main_data, "TRUCKING_COMPANY"), $trucking_company, $badsave); ?>">*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Scan Truck-Specific T&E#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRUCK_TE_NUM"); ?></font></td>
		<td><input type="text" name="truck_te" size="25" maxlength="25" value="<? echo GetBadSaveValue(ociresult($main_data, "TRUCK_TE_NUM"), $truck_te, $badsave); ?>">
				<font size="2" face="Verdana">* for non-Domestic Orders</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Pickup Type:</b></font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "PICKUP_TYPE"); ?></font></td>
		<td align="left"><select name="pickup_type"><option value="">Please Select</option>
											<option value="SWING"<? if(GetBadSaveValue(ociresult($main_data, "PICKUP_TYPE"), $pickup_type, $badsave) == "SWING"){?> selected <?}?>>SWING</option>
											<option value="HTH"<? if(GetBadSaveValue(ociresult($main_data, "PICKUP_TYPE"), $pickup_type, $badsave) == "HTH"){?> selected <?}?>>HTH</option>
											<option value="BREAKBULK"<? if(GetBadSaveValue(ociresult($main_data, "PICKUP_TYPE"), $pickup_type, $badsave) == "BREAKBULK"){?> selected <?}?>>BREAK BULK</option>
						</select>*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Container#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "CONTAINER_NUM"); ?></font></td>
		<td><input type="text" name="cont" size="20" maxlength="20" value="<? echo GetBadSaveValue(ociresult($main_data, "CONTAINER_NUM"), $cont, $badsave); ?>">
				<font size="2" face="Verdana">* for SWING and HTH</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">BoL#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "BOL_EQUIV"); ?></font></td>
		<td><input type="text" name="bol" size="20" maxlength="20" value="<? echo GetBadSaveValue(ociresult($main_data, "BOL_EQUIV"), $bol, $badsave); ?>">
				<font size="2" face="Verdana">* for SWING and HTH</font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><hr></td>
	</tr>
<!--	<tr>
		<td width="15%"><font size="2" face="Verdana">Truck License #:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRUCK_LIC_NUM"); ?></font></td>
		<td><input type="text" name="truck_lic" size="10" maxlength="10" value="<? echo ociresult($main_data, "TRUCK_LIC_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Truck License State:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRUCK_LIC_STATE"); ?></font></td>
		<td><input type="text" name="truck_lic_state" size="4" maxlength="4" value="<? echo ociresult($main_data, "TRUCK_LIC_STATE"); ?>"></td>
	</tr> !-->
	<tr>
		<td width="15%"><font size="2" face="Verdana">Trailer/Reefer Plate:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRAIL_REEF_PLATE_NUM"); ?></font></td>
		<td><input type="text" name="trailer_plate" size="10" maxlength="10" value="<? echo GetBadSaveValue(ociresult($main_data, "TRAIL_REEF_PLATE_NUM"), $trailer_plate, $badsave); ?>">*</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Trailer/Reefer Plate State:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "TRAIL_REEF_PLATE_STATE"); ?></font></td>
		<td><input type="text" name="trailer_plate_state" size="10" maxlength="10" value="<? echo GetBadSaveValue(ociresult($main_data, "TRAIL_REEF_PLATE_STATE"), $trailer_plate_state, $badsave); ?>"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><hr></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Border Crossing:</font></td>
<?
		$sql = "SELECT * FROM CLR_BORDERCROSSING
				WHERE BORDERCROSSING_ID = '".ociresult($main_data, "BORDER_CROSSING")."'";
		$bc_data = ociparse($rfconn, $sql);
		ociexecute($bc_data);
		ocifetch($bc_data);
?>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($bc_data, "BORDERCROSSING_ID")." - US: ".ociresult($bc_data, "PORT_OF_ENTRY")." /  CAN:".ociresult($bc_data, "CAN_PORT_OF_ENTRY"); ?></font></td>
		<td><select name="border_cross"><option value=""></option>
<?
		$sql = "SELECT * 
				FROM CLR_BORDERCROSSING 
				ORDER BY BORDERCROSSING_ID";
		$bc_data = ociparse($rfconn, $sql);
		ociexecute($bc_data);
		while(ocifetch($bc_data)){
?>
				<option value="<? echo ociresult($bc_data, "BORDERCROSSING_ID"); ?>" <? if(ociresult($bc_data, "BORDERCROSSING_ID") == GetBadSaveValue(ociresult($main_data, "BORDER_CROSSING"), $border_cross, $badsave)){ ?> selected <? } ?>><? echo ociresult($bc_data, "BORDERCROSSING_ID")." - US: ".ociresult($bc_data, "PORT_OF_ENTRY")." /  CAN: ".ociresult($bc_data, "CAN_PORT_OF_ENTRY"); ?></option>
<?
		}
?>
			</select>*</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><hr></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Scan Seal #:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($main_data, "SEAL_NUM"); ?></font></td>
		<td><input type="text" name="seal_num" size="20" maxlength="20" value="<? echo GetBadSaveValue(ociresult($main_data, "SEAL_NUM"), $seal_num, $badsave); ?>">
				<font size="2" face="Verdana">* if either non-Domestic OR not HTH</font></td>
	</tr>
<?
		if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="Save"></td>
		<td><input type="submit" name="submit" value="Add More Orders"><font size="2" face="Verdana">(Applies only to Breakbulk)</font></td>
	</tr>
<?
		}
?>
</form>
</table>

<?
	}







function filter_input($input){
	$return = $input;
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);
	$return = strtoupper($return);

	return $return;
}


function ValidateFields($driver_name, $clem_order, $trucking_company, $truck_te, $trailer_plate, $border_cross, $seal_num, $arrival_num, $cust, $cont, $bol, $pickup_type, $submit, $truck_ID, $rfconn){
	$return = "";

	if($truck_ID == "New"){
		$truck_ID = 0;
		// assing a"new" truck to 0 (which isn't used) for the later checks involving uniqueness of order/container
	}

	if($driver_name == ""){
		$return .= "Driver Name is required.<br>";
	}
	if($clem_order == "" && $pickup_type == "BREAKBULK"){
		$return .= "Order# is required for Breakbulk Orders.<br>";
	}
	if($trucking_company == ""){
		$return .= "Trucking Company is required.<br>";
	}
	if($truck_te == "" && $border_cross != "12"){
		$return .= "T&E# is required for non-Domestic Orders.<br>";
	}
	if($trailer_plate == ""){
		$return .= "Trailer License Plate is required.<br>";
	}
	if($border_cross == ""){
		$return .= "Border Crossing is required.<br>";
	}
	if($seal_num == "" && ($pickup_type != "HTH" || $border_cross != "12")){
		$return .= "Seal# is required.<br>";
	}
	if($arrival_num == ""){
		$return .= "Arrival# is required.<br>";
	}
	if($cust == ""){
		$return .= "Customer is required.<br>";
	}
	if(($pickup_type == "SWING" || $pickup_type == "HTH") && ($cont == "" || $bol == "")){
		$return .= "The Container and BoL fields are required for SWING or HTH orders.<br>";
	}
	if($pickup_type == "HTH" && $clem_order != $cont){
		$return .= "The Order# field for HTH Shipments must match the Container# field.<br>";
	}

	if($pickup_type != "BREAKBULK"){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_MAIN_DATA
				WHERE BOL_EQUIV = '".$bol."'
					AND ARRIVAL_NUM = '".$arrival_num."'
					AND CONTAINER_NUM = '".$cont."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$return .= "The Container(".$cont.")/BoL(".$bol.") combination was not found on vessel ".$arrival_num.".<br>";
		}
	}

	// HD 11314.  is this a good idea to add arrival number of "allow" for breakbulks to duplicate an order#?
	if($clem_order != "" && $pickup_type != "HTH"){
		$sql = "SELECT PORT_ID
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE CLEM_ORDER_NUM = '".$clem_order."'
					AND ARRIVAL_NUM = '".$arrival_num."'
					AND PORT_ID != '".$truck_ID."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(ocifetch($short_term_data)){
			$return .= "The Order# ".$clem_order." is already in use on truck ID# ".ociresult($short_term_data, "PORT_ID")." for vessel ".$arrival_num."<br>";
		}
	}

	if($cont != ""){
		$sql = "SELECT PORT_ID
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE CONTAINER_NUM = '".$cont."'
					AND ARRIVAL_NUM = '".$arrival_num."'
					AND PORT_ID != '".$truck_ID."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(ocifetch($short_term_data)){
			$return .= "The Container ".$cont." is already in use on truck ID# ".ociresult($short_term_data, "PORT_ID")."<br>";
		}
	}


	if($submit == "Add More Orders" && $pickup_type != "BREAKBULK"){
		$return .= "Only Breakbulk orders can have the Additional Orders option applied to them.<br>";
	}


	return $return;
}

function GetBadSaveValue($DB_val, $saved_val, $save_check){
	if($save_check != "bad"){
		return $DB_val;
	} else {
		return $saved_val;
	}

	return $DB_val;
}
