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
	$cursor = ora_open($conn);
	$cursor_inner = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$bottom_cols = 4;
	$form_num = 0;

	$filter_item = $HTTP_POST_VARS['filter_item'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "DELETE PO"){
		$dcpo_num = $HTTP_POST_VARS['dcpo_num'];
		$load = $HTTP_POST_VARS['load_num'];
		$sql = "DELETE FROM WDI_LOAD_DCPO
				WHERE DCPO_NUM = '".$dcpo_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
	}
	if($submit == "Retrieve Load" || $submit == "Return To Load Screen" || $submit == "Update Seal and Temp by Destination"){
		$load = $HTTP_POST_VARS['load_num'];
		$order = $HTTP_POST_VARS['order_num'];

		if($load != ""){
			// we good
		} elseif($order != ""){
			// get load...
			$sql = "SELECT LOAD_NUM FROM WDI_LOAD_DCPO
					WHERE DCPO_NUM = '".$order."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$load = $short_term_row['LOAD_NUM'];
			}
		}

		if($load == ""){
			// could not get load
			$submit = "";
			echo "<font color=\"#FF0000\">Please enter a valid load or order #</font><br>";
			if($order != ""){
				echo "<font color=\"#FF0000\">(".$order." Not Found)</font><br>";
			} else {
				echo "<font color=\"#FF0000\">(Retrieve Pressed with empty boxes)</font><br>";
			}
		}
	}

	if($submit == "Update Seal and Temp by Destination"){
//		$load = $HTTP_POST_VARS['load_num'];
		$batch_dest = $HTTP_POST_VARS['batch_dest'];
		$batch_temprec = $HTTP_POST_VARS['batch_temprec'];
		$batch_seal = $HTTP_POST_VARS['batch_seal'];

		$sql = "UPDATE WDI_LOAD_DCPO WLD
				SET TEMP_RECORDER = '".$batch_temprec."',
					TRUCK_SEAL = '".$batch_seal."'
				WHERE WLD.LOAD_NUM = '".$load."'
					AND DEST_ID = '".$batch_dest."'";
//		echo $sql."<br>";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		$sql = "SELECT COUNT(*) THE_COUNT 
				FROM WDI_LOAD_DCPO WLD
				WHERE WLD.LOAD_NUM = '".$load."'
					AND DEST_ID = '".$batch_dest."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		echo "<font color=\"#0000FF\">".$short_term_row['THE_COUNT']." rows have had their Temperature and Seal #'s updated.</font><br>";
	}



	if($submit == "Edit Load Info/Save"){
		$load = $HTTP_POST_VARS['load_num'];
		$load_date = $HTTP_POST_VARS['load_date'];
		$temp = $HTTP_POST_VARS['temp'];
//		$temp_rec = $HTTP_POST_VARS['temp_rec'];
		$pustate = $HTTP_POST_VARS['pustate'];
		$pu_loc = $HTTP_POST_VARS['pu_loc'];
//		$truckin_date = $HTTP_POST_VARS['truckin_date'];
//		$truckin_time = $HTTP_POST_VARS['truckin_time'];
//		$truckin_ampm = $HTTP_POST_VARS['truckin_ampm'];
		$truckin_set = $HTTP_POST_VARS['truckin_set'];
//		$truckout_date = $HTTP_POST_VARS['truckout_date'];
//		$truckout_time = $HTTP_POST_VARS['truckout_time'];
//		$truckout_ampm = $HTTP_POST_VARS['truckout_ampm'];
		$truckout_set = $HTTP_POST_VARS['truckout_set'];
		$appoint_date = $HTTP_POST_VARS['appoint_date'];
		$appoint_time = $HTTP_POST_VARS['appoint_time'];
		$appoint_ampm = $HTTP_POST_VARS['appoint_ampm'];
		$driver_name = $HTTP_POST_VARS['driver_name'];
		$lic_num = $HTTP_POST_VARS['lic_num'];
		$lic_state = $HTTP_POST_VARS['lic_state'];
		$company = $HTTP_POST_VARS['company'];
		$carrier = $HTTP_POST_VARS['carrier'];
		$pustate2 = $HTTP_POST_VARS['pustate2'];
		$pustate3 = $HTTP_POST_VARS['pustate3'];
		$tlltl = $HTTP_POST_VARS['tlltl'];
		$singleteam = $HTTP_POST_VARS['singleteam'];
		$length = $HTTP_POST_VARS['length'];
		$status = $HTTP_POST_VARS['status'];
		$act_stat = $HTTP_POST_VARS['act_stat'];
//		$seal = $HTTP_POST_VARS['seal'];


		$result = all_boxes_valid($load, $load_date, $temp, $pustate, $pu_loc, $truckin_set, $truckout_set, $appoint_date, $appoint_time, $appoint_ampm, $driver_name, $lic_num, $company, $carrier, $pustate2, $pustate3, $tlltl, $singleteam, $length, $status, $act_stat, $conn);

		if($result == ""){
			$more_sql = "";

			
			$sql = "SELECT TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY/HH24/MI/SS') CHECKIN, 
						TO_CHAR(APPOINTMENT_DATETIME, 'MM/DD/YYYY/HH24/MI/SS') APPOINT,
						TRUCK_CHECKIN_TIME,
						TRUCK_CHECKOUT_TIME,
						APPOINTMENT_DATETIME
					FROM WDI_LOAD_HEADER
					WHERE LOAD_NUM = '".$load."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$checkin = $short_term_row['TRUCK_CHECKIN_TIME'];
			$checkout = $short_term_row['TRUCK_CHECKOUT_TIME'];
			$appoint = $short_term_row['APPOINTMENT_DATETIME'];
			if($checkin == ""){
				if($truckin_set == "YES"){
					// truck is being checked in on "this" save
//					echo "chk ".$checkin."<br>apt ".$appoint."<br>aptdate ".$appoint_date."<br>apttime ".$appoint_time."<br>trkset ".$truckin_set."<br>";
					if($appoint_date == "" || $appoint_time == ""){
						// no appointment is made.  Clear that DB field (just in case), set status to 3, and set trucking time.
						$more_sql .= " APPOINTMENT_DATETIME = NULL, ARRIVAL_STATUS = 3, TRUCK_CHECKIN_TIME = SYSDATE, TRUCK_NUMBER = WDI_TRUCK_SEQ.NEXTVAL, ";
					} else {
						// truck is being checked in, AND an appointment is present.  Figure if it needs status 2 or 1.
						$more_sql .= " APPOINTMENT_DATETIME = TO_DATE('".$appoint_date." ".$appoint_time." ".$appoint_ampm."', 'MM/DD/YYYY HH:MI AM'), 
										TRUCK_CHECKIN_TIME = SYSDATE,
										TRUCK_NUMBER = WDI_TRUCK_SEQ.NEXTVAL,
										ARRIVAL_STATUS = 
											DECODE(SIGN(TO_DATE('".$appoint_date." ".$appoint_time." ".$appoint_ampm."', 'MM/DD/YYYY HH:MI AM') - (SYSDATE - (20/1440))), 1, 1, 2), ";
					}
				} else {
					// truck is not yet, and is not being, checked in.  Set APPOINTMENT_DATE, and that's it.
					if($appoint_date != "" && $appoint_time != ""){
						$more_sql .= " APPOINTMENT_DATETIME = TO_DATE('".$appoint_date." ".$appoint_time." ".$appoint_ampm."', 'MM/DD/YYYY HH:MI AM'), ";
					}
				}
			} else {
				// checkin was already done, do NOT change checkin, appointment, OR arrival status
				// I.E. do nothing
			}
			if($checkout == ""){
				if($truckout_set == "YES"){
					$more_sql .= " TRUCK_CHECKOUT_TIME = SYSDATE, ";
				}
			}

//			echo $more_sql."<br>";



//			if($truckin_set == "YES"){
//				$more_sql .= " TRUCK_CHECKIN_TIME = SYSDATE, ";
//			}
//			if($truckout_date != "" && $truckout_time != ""){
//				$more_sql .= " TRUCK_CHECKOUT_TIME = TO_DATE('".$truckout_date." ".$truckout_time." ".$truckout_ampm."', 'MM/DD/YYYY HH:MI AM'), ";
//			}
//			if($appoint_date != "" && $appoint_time != ""){
//				$more_sql .= " APPOINTMENT_DATETIME = TO_DATE('".$appoint_date." ".$appoint_time." ".$appoint_ampm."', 'MM/DD/YYYY HH:MI AM'), ";
//			}
/*
			if($truckin_set == "YES"){
				// appointment time cannot be changed after checkin time is set.
				// set it to 3 if appointment is null, 2 if checkin is 20 or more minutes after appointment, 1 otherwise.
				$more_sql .= " ARRIVAL_STATUS = DECODE(APPOINTMENT_DATETIME, NULL, 3,
														DECODE((APPOINTMENT_DATETIME
*/
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_LOAD_HEADER
					WHERE LOAD_NUM = '".$load."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_row['THE_COUNT'] <= 0){
				// load didnt exist yet, put it in before updating

				$sql = "INSERT INTO WDI_LOAD_HEADER
							(LOAD_NUM,
							LOAD_DATE,
							STATUS)
						VALUES
							('".$load."',
							TO_DATE('".$load_date."', 'MM/DD/YYYY'),
							'".$status."')";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			}

//						TEMP_RECORDER = '".$temp_rec."', TRUCK_SEAL = '".$seal."'
			$sql = "UPDATE WDI_LOAD_HEADER SET
						LOAD_DATE = TO_DATE('".$load_date."', 'MM/DD/YYYY'),
						TEMP = '".$temp."',
						PU_STATE = '".$pustate."',
						PU_LOCATION = '".$pu_loc."',
						DRIVER_NAME = '".$driver_name."',
						TRUCK_LICENSE_NUMBER = '".$lic_num."',
						LICENSE_STATE = '".$lic_state."',
						TRUCKING_COMPANY = '".$company."',
						CARRIER = '".$carrier."',
						".$more_sql."
						PU_STATE2 = '".$pustate2."',
						PU_STATE3 = '".$pustate3."',
						TL_LTL = '".$tlltl."',
						SINGLE_TEAM = '".$singleteam."',
						TRAILER_LENGTH = '".$length."',
						STATUS = '".$status."',
						ACTIVITY_STATUS = '".$act_stat."'
					WHERE LOAD_NUM = '".$load."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
//			echo $sql."<br>";
		} else {
			echo $result;
		}
	}
			

?>
<!--<script type="text/javascript" src="/functions/calendar.js"></script>!-->

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Projected Order Manual Screen</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select_bills" action="walmart_proj.php" method="post">
	<tr>
		<td>Enter Load #:</td>
		<td><input type="text" name="load_num" size="15" maxlength="12" value="<? echo $load; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><b>--- OR ---</b></td>
	</tr>
	<tr>
		<td>Enter PO (order) #:</td>
		<td><input type="text" name="order_num" size="15" maxlength="12" value="<? echo $order; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Load"></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><br><hr><br></td>
	</tr>
</form>
</table>

<?
	if($submit != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="load_edit" action="walmart_proj.php" method="post">
<input type="hidden" name="load_num" value="<? echo $load; ?>">
<?
//					TEMP_RECORDER, TRUCK_SEAL,
		$sql = "SELECT TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD,
					TEMP,
					PU_STATE,
					PU_LOCATION,
					NVL(TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') TRUCKIN_DATE,
					NVL(TO_CHAR(TRUCK_CHECKOUT_TIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') TRUCKOUT_DATE,
					TO_CHAR(APPOINTMENT_DATETIME, 'MM/DD/YYYY') APPOINT_DATE,
					TO_CHAR(APPOINTMENT_DATETIME, 'HH:MI') APPOINT_TIME,
					TO_CHAR(APPOINTMENT_DATETIME, 'AM') APPOINT_AMPM,
					DRIVER_NAME,
					TRUCK_LICENSE_NUMBER,
					TRUCKING_COMPANY,
					CARRIER,
					PU_STATE2,
					PU_STATE3,
					TL_LTL,
					SINGLE_TEAM,
					TRAILER_LENGTH,
					STATUS,
					ACTIVITY_STATUS,
					LICENSE_STATE
				FROM WDI_LOAD_HEADER
				WHERE LOAD_NUM = '".$load."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$load_message = "(NEW Load)";
			$load_date = "";
			$temp = "";
//			$temp_rec = "";
			$pustate = "";
			$pu_loc = "";
//			$truckin_date = "";
//			$truckin_time = "";
//			$truckin_ampm = "";
			$truckin = "";
			$truckout_date = "";
			$truckout_time = "";
			$truckout_ampm = "";
			$driver_name = "";
			$lic_num = "";
			$lic_state = "";
			$company = "";
			$carrier = "";
			$pustate2 = "";
			$pustate3 = "";
			$tlltl = "";
			$singleteam = "";
			$length = "";
			$status = "";
			$act_stat = "";
//			$seal = "";
		} else {
			$load_message = "";
			$load_date = $short_term_row['THE_LOAD'];
			$temp = $short_term_row['TEMP'];
//			$temp_rec = $short_term_row['TEMP_RECORDER'];
			$pustate = $short_term_row['PU_STATE'];
			$pu_loc = $short_term_row['PU_LOCATION'];
//			$truckin_date = $short_term_row['TRUCKIN_DATE'];
//			$truckin_time = $short_term_row['TRUCKIN_TIME'];
//			$truckin_ampm = $short_term_row['TRUCKIN_AMPM'];
			$truckin = $short_term_row['TRUCKIN_DATE'];
//			$truckout_date = $short_term_row['TRUCKOUT_DATE'];
//			$truckout_time = $short_term_row['TRUCKOUT_TIME'];
//			$truckout_ampm = $short_term_row['TRUCKOUT_AMPM'];
			$truckout = $short_term_row['TRUCKOUT_DATE'];
			$appoint_date = $short_term_row['APPOINT_DATE'];
			$appoint_time = $short_term_row['APPOINT_TIME'];
			$appoint_ampm = $short_term_row['APPOINT_AMPM'];
			$driver_name = $short_term_row['DRIVER_NAME'];
			$lic_num = $short_term_row['TRUCK_LICENSE_NUMBER'];
			$lic_state = $short_term_row['LICENSE_STATE'];
			$company = $short_term_row['TRUCKING_COMPANY'];
			$carrier = $short_term_row['CARRIER'];
			$pustate2 = $short_term_row['PU_STATE2'];
			$pustate3 = $short_term_row['PU_STATE3'];
			$tlltl = $short_term_row['TL_LTL'];
			$singleteam = $short_term_row['SINGLE_TEAM'];
			$length = $short_term_row['TRAILER_LENGTH'];
			$status = $short_term_row['STATUS'];
			$act_stat = $short_term_row['ACTIVITY_STATUS'];
//			$seal = $short_term_row['TRUCK_SEAL'];
		}
?>
	<tr>
		<td colspan="6" align="center"><font size="4" face="Verdana"><b>Load <? echo $load." ".$load_message; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Load Date:</b></font></td>
		<td><input type="text" name="load_date" size="10" maxlength="10" value="<? echo $load_date; ?>"></td>
		<td><font size="2" face="Verdana"><b>Load Status:</b></font></td>
		<td><select name="status"><option value="ACTIVE"<? if ($status == "ACTIVE"){?> selected <?}?>>ACTIVE</option>
												<option value="CANCELLED"<? if ($status == "CANCELLED"){?> selected <?}?>>CANCELLED</option>
									</select></td>
		<td><font size="2" face="Verdana">Activity Status:</font></td>
		<td><select name="act_stat">
<?
		$sql = "SELECT ORDERSTATUSID, DESCR
				FROM WDI_TRUCK_ACTIVITY_STATUS 
				ORDER BY ORDERSTATUSID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['ORDERSTATUSID']; ?>" <? if($row['ORDERSTATUSID'] == $act_stat){ ?> selected <?}?>><? echo $row['ORDERSTATUSID']." - ".$row['DESCR']; ?></option> 
<?
		}
?>
				</select></td>
	</tr>
	<tr>
		<td align="center" colspan="6"><font size="2" face="Verdana">Appointment Date:</font>&nbsp;&nbsp;&nbsp;<input type="text" name="appoint_date" size="10" maxlength="10" value="<? echo $appoint_date; ?>"<? if($truckin != "NONE"){?> disabled <?}?>><font size="1" face="Verdana">(MM/DD/YYYY)&nbsp;</font>
						<input type="text" name="appoint_time" size="5" maxlength="5" value="<? echo $appoint_time; ?>"<? if($truckin != "NONE"){?> disabled <?}?>><font size="1" face="Verdana">(HH:MI)&nbsp;</font>
						<select name="appoint_ampm"<? if($truckin != "NONE"){?> disabled <?}?>><option value="AM"<? if ($appoint_ampm == "AM"){?> selected <?}?>>AM</option>
												<option value="PM"<? if ($appoint_ampm == "PM"){?> selected <?}?>>PM</option>
									</select></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" align="right"><font size="2" face="Verdana">Temp:</font></td>
		<td colspan="3"><input type="text" name="temp" size="6" maxlength="6" value="<? echo $temp; ?>"></td>
<!--		<td align="right"><font size="2" face="Verdana">Seal#:</font></td>
		<td colspan="2"><input type="text" name="seal" size="15" maxlength="15" value="<? echo $seal; ?>"></td>
		<td><font size="2" face="Verdana">Temp Recorder:</font></td>
		<td><input type="text" name="temp_rec" size="6" maxlength="6" value="<? echo $temp_rec; ?>"></td> !-->
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Driver Name:</font></td>
		<td><input type="text" name="driver_name" size="25" maxlength="25" value="<? echo $driver_name; ?>"></td>
		<td><font size="2" face="Verdana">License Plate (State):</font></td>
		<td><input type="text" name="lic_state" size="3" maxlength="2" value="<? echo $lic_state; ?>"></td>
		<td><font size="2" face="Verdana">License Plate Number:</font></td>
		<td><input type="text" name="lic_num" size="8" maxlength="8" value="<? echo $lic_num; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Trucking Company:</font></td>
		<td><input type="text" name="company" size="15" maxlength="15" value="<? echo $company; ?>"></td>
		<td><font size="2" face="Verdana">Carrier:</font></td>
		<td><input type="text" name="carrier" size="6" maxlength="6" value="<? echo $carrier; ?>"></td>
		<td><font size="2" face="Verdana">Trailer Length:</font></td>
		<td><input type="text" name="length" size="4" maxlength="4" value="<? echo $length; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Pickup State1:</font></td>
		<td><input type="text" name="pustate" size="2" maxlength="2" value="<? echo $pustate; ?>"></td>
		<td><font size="2" face="Verdana">Pickup State2:</font></td>
		<td><input type="text" name="pustate2" size="2" maxlength="2" value="<? echo $pustate2; ?>"></td>
		<td><font size="2" face="Verdana">Pickup State3:</font></td>
		<td><input type="text" name="pustate3" size="2" maxlength="2" value="<? echo $pustate3; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Pickup Location:</font></td>
		<td><input type="text" name="pu_loc" size="15" maxlength="15" value="<? echo $pu_loc; ?>"></td>
		<td><font size="2" face="Verdana">TL/LTL:</font></td>
		<td><input type="text" name="tlltl" size="4" maxlength="4" value="<? echo $tlltl; ?>"></td>
		<td><font size="2" face="Verdana">Single/Team:</font></td>
		<td><input type="text" name="singleteam" size="2" maxlength="2" value="<? echo $singleteam; ?>"></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><font size="2" face="Verdana">Check-In:</font>
<!--		<td colspan="2"><input type="text" name="truckin_date" size="10" maxlength="10" value="<? echo $truckin_date; ?>"><font size="1" face="Verdana">(MM/DD/YYYY)&nbsp;</font>
						<input type="text" name="truckin_time" size="5" maxlength="5" value="<? echo $truckin_time; ?>"><font size="1" face="Verdana">(HH:MI)&nbsp;</font>
						<select name="truckin_ampm"><option value="AM"<? if ($truckin_ampm == "AM"){?> selected <?}?>>AM</option>
												<option value="PM"<? if ($truckin_ampm == "PM"){?> selected <?}?>>PM</option>
									</select></td> !-->
<?		
		if($truckin == "NONE"){ 
?>
		<td colspan="2"><font size="2" face="Verdana"><? echo $truckin; ?>&nbsp;&nbsp;&nbsp;(<input type="checkbox" name="truckin_set" value="YES">Check-In Truck)</font></td>
<?
		} else {
?>
		<td colspan="2"><font size="2" face="Verdana"><? echo $truckin; ?></font></td>
<?
		}
?>
		<td align="right"><font size="2" face="Verdana">Check-Out:</font>
<?		
		if($truckin != "NONE" && $truckout == "NONE"){ 
?>
		<td colspan="2"><font size="2" face="Verdana"><? echo $truckout; ?>&nbsp;&nbsp;&nbsp;(<input type="checkbox" name="truckout_set" value="YES">Check-Out Truck)</font></td>
<?
		} elseif($truckin == "NONE"){
?>
		<td colspan="2"><font size="2" face="Verdana">Truck Needs to be Checked-In Before it can be Checked-Out</font></td>
<?
		} else {
?>
		<td colspan="2"><font size="2" face="Verdana"><? echo $truckout; ?></font></td>
<?
		}
?>
<!--		<td colspan="2"><input type="text" name="truckout_date" size="10" maxlength="10" value="<? echo $truckout_date; ?>"><font size="1" face="Verdana">(MM/DD/YYYY)&nbsp;</font>
						<input type="text" name="truckout_time" size="5" maxlength="5" value="<? echo $truckout_time; ?>"><font size="1" face="Verdana">(HH:MI)&nbsp;</font>
						<select name="truckout_ampm"><option value="AM"<? if ($truckout_ampm == "AM"){?> selected <?}?>>AM</option>
												<option value="PM"<? if ($truckout_ampm == "PM"){?> selected <?}?>>PM</option>
									</select></td> !-->
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6"><input type="submit" name="submit" value="Edit Load Info/Save"></td>
	</tr>
	<tr>
		<td colspan="7" align="center">&nbsp;</td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT DCPO_NUM, SAMS_SC, BKHL_TRAFFIC, VENDOR_NAME, TO_CHAR(DELIVERY_DATE, 'MM/DD/YYYY') DELV_DATE, 
					DEST_ID, TEMP_RECORDER, TRUCK_SEAL
				FROM WDI_LOAD_DCPO WLD
				WHERE WLD.LOAD_NUM = '".$load."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<tr><td colspan=\"7\" align=\"center\"><font size=\"2\" face=\"Verdana\">No Orders currently for this Load#</font></td></tr>";
		} else {
			$form_num = 0;
?>
	<tr>
		<td colspan="10" align="center"><font size="3" face="Verdana"><a target="walmart_proj_picklist.php?load=<? echo $load; ?>" href="walmart_proj_picklist.php?load=<? echo $load; ?>">Print Picklist For All DCPO's</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="walmart_proj_bol.php?load=<? echo $load; ?>" href="walmart_proj_bol.php?load=<? echo $load; ?>">Print BOL's For All DCPO's</a><br></td>
	</tr>

<form name="update_seal_and_rec" action="walmart_proj.php" method="post">
<input type="hidden" name="load_num" value="<? echo $load; ?>">
<input type="hidden" name="order_num" value="<? echo $order; ?>">
	<tr>
		<td colspan="10" align="center">
		<table>
			<tr>
				<td colspan="4" align="center"><font size="3" face="Verdana">Batch Update By Destination</font></td>
			</tr>
			<tr>
				<td align="center" width="25%"><font size="2" face="Verdana">Destination:   <input type="text" name="batch_dest" size="10" maxlength="10"></font></td>
				<td align="center" width="25%"><font size="2" face="Verdana">(New) Seal#:   <input type="text" name="batch_seal" size="15" maxlength="15"></font></td>
				<td align="center" width="25%"><font size="2" face="Verdana">(New) Temp Rec#:   <input type="text" name="batch_temprec" size="20" maxlength="20"></font></td>
				<td align="center" width="25%"><input type="submit" name="submit" value="Update Seal and Temp by Destination"></td>
			</tr>
		</table>
		</td>
	</tr>
</form>
	<tr><td colspan="10">&nbsp</td></tr>
	<tr>
		<td><font size="2" face="Verdana"><b>DCPO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Destination</b></font></td>
		<td><font size="2" face="Verdana"><b>Seal #</b></font></td>
		<td><font size="2" face="Verdana"><b>Temp Rec #</b></font></td>
		<td><font size="2" face="Verdana"><b>SAMS/SC</b></font></td>
		<td><font size="2" face="Verdana"><b>BKHL/TRAFFIC</b></font></td>
		<td><font size="2" face="Verdana"><b>VENDOR NAME</b></font></td>
		<td><font size="2" face="Verdana"><b>DELIVERY DATE</b></font></td>
		<td><font size="2" face="Verdana"><b>PALLET OVERVIEW</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
			do {
?>
<form name="form<? echo $form_num; ?>" action="walmart_proj.php" method="post">
<input type="hidden" name="dcpo_num" value="<? echo $row['DCPO_NUM']; ?>">
<input type="hidden" name="load_num" value="<? echo $load; ?>">
	<tr>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['DCPO_NUM']; ?>&nbsp</font>
		<? if($act_stat == "1"){ ?>
		<br><input type="submit" name="submit" value="DELETE PO">
		<? } ?></td>
</form>
<form name="form<? echo $form_num; ?>" action="walmart_proj_detail.php" method="post">
<input type="hidden" name="dcpo_num" value="<? echo $row['DCPO_NUM']; ?>">
		<td valign="top"><font size="2" face="Verdana"><? echo $row['DEST_ID']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['TRUCK_SEAL']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['TEMP_RECORDER']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['SAMS_SC']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['BKHL_TRAFFIC']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['VENDOR_NAME']; ?>&nbsp</font></td>
		<td valign="top"><font size="2" face="Verdana"><? echo $row['DELV_DATE']; ?>&nbsp</font></td>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="70%" align="left"><font face="Verdana" size="2"><b>Item</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Plt</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Ctn</b></font></td>
				</tr>
							
<?
				$sql = "SELECT ITEM_NUM, SUM(PALLETS) THE_PLT, SUM(CASES) THE_CTN
						FROM WDI_LOAD_DCPO_ITEMNUMBERS
						WHERE DCPO_NUM = '".$row['DCPO_NUM']."'
						GROUP BY ITEM_NUM
						ORDER BY ITEM_NUM";
				ora_parse($cursor_inner, $sql);
				ora_exec($cursor_inner);
				if(!ora_fetch_into($cursor_inner, $row_inner, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					?> <td colspan="3"><font size=\"2\" face=\"Verdana\"><b>No Details Yet Entered</b></font></td> <?
				} else {
					do {
?>
				<tr>
					<td align="left"><font face="Verdana" size="2"><? echo $row_inner['ITEM_NUM']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $row_inner['THE_PLT']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $row_inner['THE_CTN']; ?></font></td>
				</tr>
<?
					} while(ora_fetch_into($cursor_inner, $row_inner, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
?>
			</table>
		</td>	
		<td valign="top"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
<?
				$form_num++;
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_LOAD_HEADER
				WHERE LOAD_NUM = '".$load."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] > 0){
?>
<form name="add_dcpo" action="walmart_proj_detail.php" method="post">
<input type="hidden" name="load_num" value="<? echo $load; ?>">
	<tr>
		<td colspan="10" align="center">Add New DCPO# to this Load:&nbsp;&nbsp;<input type="text" name="new_dcpo" size="10" maxlength="10" value="">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Add DCPO"></td>
	</tr>
</form>
</table>
<?
		} else {
?>
	<tr>
		<td colspan="7" align="center">Please Save Load Information (at Minimum:  Load Date) before adding DCPOs.</td>
	</tr>
<?
		}
	}
	include("pow_footer.php");






function all_boxes_valid($load, $load_date, $temp, $pustate, $pu_loc, $truckin_set, $truckout_set, $appoint_date, $appoint_time, $appoint_ampm, $driver_name, $lic_num, $company, $carrier, $pustate2, $pustate3, $tlltl, $singleteam, $length, $status, $act_stat, $conn){

	$Short_Term_Cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	$return = "";

	if($carrier != "" && !ereg("^([0-9a-zA-Z _-])+$", $carrier)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Carrier field.  (entered: ".$carrier.")</font><br>";
	}

	if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $load_date)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Load date must be in MM/DD/YYYY format.  (entered: ".$load_date.")</font><br>";
	}

	if(!ereg("^([0-9a-zA-Z _`/-])*$", $temp)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Temperature Field.  (entered: ".$temp.")</font><br>";
	}
/*	if(!ereg("^([0-9a-zA-Z _`/-])*$", $temp_rec)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Temperature Recorder.  (entered: ".$temp_rec.")</font><br>";
	}*/

	if(!ereg("^([a-zA-Z])*$", $pustate)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Pick-up State must be letters only.  (entered: ".$pustate.")</font><br>";
	}
	if(!ereg("^([a-zA-Z])*$", $pustate2)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Pick-up State2 must be letters only.  (entered: ".$pustate2.")</font><br>";
	}
	if(!ereg("^([a-zA-Z])*$", $pustate3)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Pick-up State3 must be letters only.  (entered: ".$pustate3.")</font><br>";
	}

	if(!ereg("^([0-9a-zA-Z _-])*$", $pu_loc)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Pickup Location.  (entered: ".$pu_loc.")</font><br>";
	}

	if($appoint_date != "" && $appoint_time != ""){
		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $appoint_date)){
			$return .= "<font size=\"3\" color=\"#FF0000\">Appointment date must be in MM/DD/YYYY format.  (entered: ".$appoint_date.")</font><br>";
		}
		if(!ereg("^([0-9]{1,2}):([0-9]{2})$", $appoint_time)){
			$return .= "<font size=\"3\" color=\"#FF0000\">Appointment time must be in HH:MI format.  (entered: ".$appoint_time.")</font><br>";
		}
	}
/*	if($truckout_date != "" && $truckout_time != ""){
		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $truckout_date)){
			$return .= "<font size=\"3\" color=\"#FF0000\">Truck-Out date must be in MM/DD/YYYY format.  (entered: ".$truckout_date.")</font><br>";
		}
		if(!ereg("^([0-9]{1,2}):([0-9]{2})$", $truckout_time)){
			$return .= "<font size=\"3\" color=\"#FF0000\">Truck-Out time must be in HH:MI format.  (entered: ".$truckout_time.")</font><br>";
		}
	}*/

	if(!ereg("^([0-9a-zA-Z _`/-])*$", $driver_name)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Driver Name Field.  (entered: ".$driver_name.")</font><br>";
	}

	if(!ereg("^([0-9a-zA-Z _-])*$", $lic_num)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in License Number.  (entered: ".$lic_num.")</font><br>";
	}

	if(!ereg("^([0-9a-zA-Z _`/-])*$", $company)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in trucking Company.  (entered: ".$company.")</font><br>";
	}

	if(!ereg("^([a-zA-Z])*$", $tlltl)){
		$return .= "<font size=\"3\" color=\"#FF0000\">TL/LTL must be letters only.  (entered: ".$tlltl.")</font><br>";
	}

	if(!ereg("^([a-zA-Z])*$", $singleteam)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Single/Team Field must be letters only.  (entered: ".$singleteam.")</font><br>";
	}

	if(!ereg("^([0-9a-zA-Z _-])*$", $length)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Trailer Length.  (entered: ".$length.")</font><br>";
	}

	return $return;
}

?>