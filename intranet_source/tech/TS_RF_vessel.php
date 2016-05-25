<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Import RF vessel";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

   $submit = $HTTP_POST_VARS['submit'];
   $vessel = $HTTP_POST_VARS['vessel'];
   $alt_vessel = $HTTP_POST_VARS['alt_vessel'];

	if($submit == "Save"){
		$new_vessel_name = $HTTP_POST_VARS['new_vessel_name'];
		$new_arrival_num_VP = $HTTP_POST_VARS['new_arrival_num_VP'];
		$new_arrival_num_voyage = $HTTP_POST_VARS['new_arrival_num_voyage'];
		$new_vessel_status = $HTTP_POST_VARS['new_vessel_status'];
		$new_ship_prefix = $HTTP_POST_VARS['new_ship_prefix'];
		$new_voyage_num = $HTTP_POST_VARS['new_voyage_num'];
		$new_date_expected = $HTTP_POST_VARS['new_date_expected'];
		$new_date_arrived = $HTTP_POST_VARS['new_date_arrived'];
		$new_date_departed = $HTTP_POST_VARS['new_date_departed'];
		$new_lloyd = $HTTP_POST_VARS['new_lloyd'];
		$new_sort_file = $HTTP_POST_VARS['new_sort_file'];
		$is_new = $HTTP_POST_VARS['is_new'];
		$alt_new_ves = $HTTP_POST_VARS['alt_new_ves'];
		$alt_new_ship_prefix = $HTTP_POST_VARS['alt_new_ship_prefix'];

		if($is_new == "YES"){
			$sql = "INSERT INTO VESSEL_PROFILE (LR_NUM, ARRIVAL_NUM, VESSEL_NAME, VESSEL_STATUS, SHIP_PREFIX, VESSEL_FLAG, LLOYD_NUM) 
					VALUES ('".$vessel."', '".$new_arrival_num_VP."', '".$new_vessel_name."', '".$new_vessel_status."', '".$new_ship_prefix."', '".$new_sort_file."', '".$new_lloyd."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			$sql = "INSERT INTO VOYAGE (LR_NUM, ARRIVAL_NUM, VOYAGE_NUM, DATE_EXPECTED, DATE_ARRIVED, DATE_DEPARTED) VALUES ('".$vessel."', '".$new_arrival_num_voyage."', '".$new_voyage_num."', TO_DATE('".$new_date_expected."', 'MM/DD/YYYY'), TO_DATE('".$new_date_arrived."', 'MM/DD/YYYY'), TO_DATE('".$new_date_departed."', 'MM/DD/YYYY'))";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			if($new_sort_file == "Y"){
				send_notice($vessel, $conn);
			}
			if($new_ship_prefix == "CHILEAN" || $new_ship_prefix == "ARG FRUIT"){
				add_to_job_queue_for_walmart($vessel, $conn, $user);
			}

		} else { // is_new == NO
			$sql = "SELECT VESSEL_FLAG FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$sendmail_flag = $row['VESSEL_FLAG'];

			$sql = "UPDATE VESSEL_PROFILE SET VESSEL_NAME = '".$new_vessel_name."', VESSEL_STATUS = '".$new_vessel_status."', SHIP_PREFIX = '".$new_ship_prefix."', ARRIVAL_NUM = '".$new_arrival_num_VP."', VESSEL_FLAG = '".$new_sort_file."', LLOYD_NUM = '".$new_lloyd."' WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			$sql = "UPDATE VOYAGE SET ARRIVAL_NUM = '".$new_arrival_num_voyage."', VOYAGE_NUM = '".$new_voyage_num."', DATE_EXPECTED = TO_DATE('".$new_date_expected."', 'MM/DD/YYYY'), DATE_ARRIVED = TO_DATE('".$new_date_arrived."', 'MM/DD/YYYY'), DATE_DEPARTED = TO_DATE('".$new_date_departed."', 'MM/DD/YYYY') WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			
			if($sendmail_flag == "N" && $new_sort_file == "Y"){
//				echo "sending notice..<br>";
				send_notice($vessel, $conn);
			}
		}

		// now, we do some diligence on the Alternate Vessel box.
		if($alt_new_ship_prefix == "" && $alt_new_ves != ""){
			echo "<font color=\"#FF0000\">If setting an alternate vessel#, an alternate commodity type must be chosen.<br>The Vessel changes were saved; however, the Alternate Vessel Data was not.<br>Please use <a href=\"TS_RF_vessel.php\">This Link</a> to return to the previous page</font>";
			exit;
		}

		// only do this if the box is filled in, and not with a value that was pre-populated there.
		if($alt_new_ves != "" && $alt_new_ves != $alt_vessel){
			// is the "new alt vessel" not an existing vessel?
			$sql = "SELECT COUNT(*) THE_COUNT FROM VESSEL_PROFILE
					WHERE LR_NUM = '".$alt_new_ves."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] <= 0){
				// if there was an "old alt vessel", get rid of it.
				// but first, check to make sure that it wasn't imported against.
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$alt_vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['THE_COUNT'] >= 1){
					echo "<font color=\"#FF0000\">Could not get rid of Old Alternate vessel; cargo has already been imported against it.</font>";
				} else {
					// delete the old vessel (if it exists; otherwise, these DELETE statements will do nothing)
					$sql = "DELETE FROM VESSEL_PROFILE WHERE LR_NUM = '".$alt_vessel."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "DELETE FROM VOYAGE WHERE LR_NUM = '".$alt_vessel."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "DELETE FROM LR_CONVERSION WHERE LR_NUM = '".$vessel."' AND OPT_ARRIVAL_NUM = '".$alt_vessel."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);



					// and now, since new number doesn't exist, make it and alt-tag it to the original
					$sql = "INSERT INTO VESSEL_PROFILE (LR_NUM, ARRIVAL_NUM, VESSEL_NAME, VESSEL_STATUS, SHIP_PREFIX, VESSEL_FLAG) VALUES ('".$alt_new_ves."', '".$alt_new_ves."', '".$new_vessel_name."', '".$new_vessel_status."', '".$alt_new_ship_prefix."', '".$new_sort_file."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "INSERT INTO VOYAGE (LR_NUM, ARRIVAL_NUM, VOYAGE_NUM, DATE_EXPECTED, DATE_ARRIVED, DATE_DEPARTED) VALUES ('".$alt_new_ves."', '".$new_arrival_num_voyage."', '".$new_voyage_num."', TO_DATE('".$new_date_expected."', 'MM/DD/YYYY'), TO_DATE('".$new_date_arrived."', 'MM/DD/YYYY'), TO_DATE('".$new_date_departed."', 'MM/DD/YYYY'))";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "INSERT INTO LR_CONVERSION
								(LR_NUM, OPT_ARRIVAL_NUM)
							VALUES
								('".$vessel."', '".$alt_new_ves."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				}
			} else {
				// the new "alt vessel" already exists.
				// make sure this isnt going to start a "chain" of alternates
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM LR_CONVERSION
						WHERE LR_NUM = '".$alt_new_ves."' 
							OR OPT_ARRIVAL_NUM = '".$alt_new_ves."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['THE_COUNT'] >= 1){
					echo "<font color=\"#FF0000\">LR# ".$alt_new_ves." already has and/or is an alternate vessel; it cannot be used as one (again).</font>";
				} else {
					// no need to change VP/VOY here, just update the dependency.
					// (this delete might delete nothing)
					$sql = "DELETE FROM LR_CONVERSION WHERE LR_NUM = '".$vessel."' AND OPT_ARRIVAL_NUM = '".$alt_vessel."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "INSERT INTO LR_CONVERSION
								(LR_NUM, OPT_ARRIVAL_NUM)
							VALUES
								('".$vessel."', '".$alt_new_ves."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				}
			}
		}
	}






?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add / Modify RF Vessel</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="vessel_check" action="TS_RF_vessel.php" method="post">
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana">Enter Vessel:</font><input type="text" name="vessel" size="10" maxlength="10" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Check Vessel"></td>
	</tr>
</form>
<form name="new_info" action="TS_RF_vessel.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<?
	if($submit != ""){
		$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) { // if vessel already exists
			$vessel_name = $row['VESSEL_NAME'];
			$vessel_status = $row['VESSEL_STATUS'];
			$ship_prefix = $row['SHIP_PREFIX'];
			$arrival_num_VP = $row['ARRIVAL_NUM'];
			$sort_flag = $row['VESSEL_FLAG'];
			$lloyd = $row['LLOYD_NUM'];

			$sql = "SELECT VOYAGE_NUM, ARRIVAL_NUM, TO_CHAR(DATE_EXPECTED, 'MM/DD/YYYY') THE_EXP, TO_CHAR(DATE_ARRIVED, 'MM/DD/YYYY') THE_ARR, TO_CHAR(DATE_DEPARTED, 'MM/DD/YYYY') THE_DEP
					FROM VOYAGE WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$voyage_num = $row['VOYAGE_NUM'];
			$date_expected = $row['THE_EXP'];
			$date_arrived = $row['THE_ARR'];
			$date_departed = $row['THE_DEP'];
			$arrival_num_voyage = $row['ARRIVAL_NUM'];

			$sql = "SELECT SHIP_PREFIX, VP.LR_NUM
					FROM VESSEL_PROFILE VP, LR_CONVERSION LC
					WHERE VP.LR_NUM = LC.OPT_ARRIVAL_NUM
						AND LC.LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$alt_ves = $row['LR_NUM'];
				$alt_ves_prefix = $row['SHIP_PREFIX'];
			}

			$alt_box = "";
			$sql = "SELECT LR_NUM
					FROM LR_CONVERSION LC
					WHERE OPT_ARRIVAL_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$alt_box = "disabled";
			}
			
?>
<input type="hidden" name="is_new" value="NO">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana" color="#0000FF">This vessel is currently in the RF system.<BR>Existing information below.</font></td>
	</tr>
<?
		} else {
			$vessel_name = "";
			$vessel_status = "";
			$ship_prefix = "";
			$voyage_num = "";
			$date_expected = "";
			$date_arrived = "";
			$date_departed = "";
			$lloyd = "";
?>
<input type="hidden" name="is_new" value="YES">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana" color="#0000FF">This vessel does not currently exist in the RF system.</font></td>
	</tr>
<?
		}
?>
<input type="hidden" name="alt_vessel" value="<? echo $alt_ves; ?>">
	<tr>
		<td colspan="2">&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vessel Name:</font></td>
		<td><input type="text" name="new_vessel_name" size="20" maxlength="30" value="<? echo $vessel_name; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Arrival Num (vessel profile):</font></td>
		<td><input type="text" name="new_arrival_num_VP" size="10" maxlength="12" value="<? echo $arrival_num_VP; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">(Optional)</font></td>
		<td>
			<table width="50%" cellpadding="1" cellspacing="0">
				<tr>
					<td><font size="2" face="Verdana">Alternate Vessel:&nbsp;</font></td>
					<td><input type="text" name="alt_new_ves" size="10" maxlength="12" value="<? echo $alt_ves; ?>" <? echo $alt_box; ?>></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<td><font size="2" face="Verdana">Alternate Prefix:&nbsp;</font></td>
					<td><select name="alt_new_ship_prefix" <? echo $alt_box; ?>><option value=""<? if($alt_ves_prefix == ""){?> selected <?}?>></option>
<?
		$sql = "SELECT DISTINCT COMMODITY_TYPE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IS NOT NULL ORDER BY COMMODITY_TYPE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['COMMODITY_TYPE']; ?>"<? if($alt_ves_prefix == $row['COMMODITY_TYPE']){?> selected <?}?>><? echo $row['COMMODITY_TYPE']; ?></option>
<?
		}
?>
								</select></td>
				</tr>
			</table>
		</td>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Arrival Num (voyage):</font></td>
		<td><input type="text" name="new_arrival_num_voyage" size="5" maxlength="4" value="<? echo $arrival_num_voyage; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vessel Status:</font></td>
		<td><input type="text" name="new_vessel_status" size="10" maxlength="20" value="<? echo $vessel_status; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Ship Prefix:</font></td>
		<td><select name="new_ship_prefix"><option value=""<? if($ship_prefix == ""){?> selected <?}?>></option>
<?
		$sql = "SELECT DISTINCT COMMODITY_TYPE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IS NOT NULL ORDER BY COMMODITY_TYPE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['COMMODITY_TYPE']; ?>"<? if($ship_prefix == $row['COMMODITY_TYPE']){?> selected <?}?>><? echo $row['COMMODITY_TYPE']; ?></option>
<?
		}
?>
							</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Voyage Num:</font></td>
		<td><input type="text" name="new_voyage_num" size="10" maxlength="10" value="<? echo $voyage_num; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Date Expected:</font></td>
		<td><input type="text" name="new_date_expected" size="10" maxlength="20" value="<? echo $date_expected; ?>"><font size="2" face="Verdana">  (MM/DD/YYYY format please)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Date Arrived:</font></td>
		<td><input type="text" name="new_date_arrived" size="10" maxlength="20" value="<? echo $date_arrived; ?>"><font size="2" face="Verdana">  (MM/DD/YYYY format please)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Date Departed:</font></td>
		<td><input type="text" name="new_date_departed" size="10" maxlength="20" value="<? echo $date_departed; ?>"><font size="2" face="Verdana">  (MM/DD/YYYY format please)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">LLoyd#:</font></td>
		<td><input type="text" name="new_lloyd" size="10" maxlength="20" value="<? echo $lloyd; ?>"><font size="2" face="Verdana"></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Allow Upload of SortFile:</font></td>
		<td><input type="radio" name="new_sort_file" value="N" <? if($sort_flag == "N"){ ?> checked <?}?>><font size="2" face="Verdana">N</font>&nbsp;&nbsp;&nbsp;<input type="radio" name="new_sort_file" value="Y" <? if($sort_flag == "Y"){ ?> checked <?}?>><font size="2" face="Verdana">Y</font></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="Save"></td>
	</tr>
<?
	}
?>
</form>
</table>
<?
	include("pow_footer.php");



function send_notice($vessel, $conn){
	$cursor = ora_open($conn);

	$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel_name = $row['LR_NUM']." - ".$row['VESSEL_NAME'];

	$mailTo = "";
	$mailSubject = "";
	$mailHeaders = "";
	$body = "";

	$mailTo = "schapman@port.state.de.us\r\n";
	$mailSubject = "Vessel ".$vessel_name." is now accepting Sort File Uploads.";

	$mailHeaders = "From:  POWNoReply@port.state.de.us\r\n";
	$mailHeaders .= "CC:  martym@port.state.de.us,ddonofrio@port.state.de.us,wstans@port.state.de.us,twhite@port.state.de.us\r\n";
	$mailHeaders .= "BCC:  ithomas@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us";

	$sql = "SELECT EMAIL_ADDR FROM EMAIL_LIST";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailHeaders .= ",".$row['EMAIL_ADDR'];
	}
//	echo "email sent to ".$mailHeaders."<br>";

	$mailHeaders .= "\r\n";

	mail($mailTo, $mailSubject, $body, $mailHeaders);
}

function add_to_job_queue_for_walmart($vessel, $conn, $user){
	$cursor = ora_open($conn);

	$sql = "INSERT INTO JOB_QUEUE
			(JOB_ID,
			SUBMITTER_ID,
			SUBMISSION_DATETIME,
			JOB_TYPE,
			JOB_DESCRIPTION,
			VARIABLE_LIST,
			COMPLETION_STATUS)
		VALUES
			(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
			'".$user."',
			SYSDATE,
			'EMAIL',
			'NVE1',
			'".$vessel."',
			'PENDING')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
}

?>