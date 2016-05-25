<?
/*
*	Adam Walter, Dec 2011.
*
*	This page is so that Inventory (BEN!) can "move the pallets of 
*	Kopke into the warehouses", which is shorthand for "free time start".
*************************************************************************/



  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Kopke Validation";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$modify_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);


	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];

	if($submit == "Save" && $vessel != ""){
		$any_cargo = $HTTP_POST_VARS['any_cargo'];
		$qc_date = $HTTP_POST_VARS['qc_date'];
		$qc_hour = $HTTP_POST_VARS['qc_hour'];
		$qc_min = $HTTP_POST_VARS['qc_min'];
		$qc_contact = $HTTP_POST_VARS['qc_contact'];
		$edit_reason = $HTTP_POST_VARS['edit_reason'];

		$save = true;

		if($any_cargo == "yes"){

			if($qc_date == "" || $qc_contact == ""){
				echo "<font color=\"#ff0000\">Both QC contact and QC date are required.</font>";
				$save = false;
			} elseif(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $qc_date)){
				echo "<font color=\"#ff0000\">QC date must be in MM/DD/YYYY format.</font>";
				$save = false;
			} elseif($qc_hour == "" || !is_numeric($qc_hour) || $qc_hour < 0 || $qc_hour > 23){
				echo "<font color=\"#ff0000\">Hour value must be between 0 and 23.</font>";
				$save = false;
			} elseif($qc_min == "" || !is_numeric($qc_min) || $qc_min < 0 || $qc_min > 59){
				echo "<font color=\"#ff0000\">Minute value must be between 0 and 59.</font>";
				$save = false;
			}

			if($save){
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM WDI_EXPECTED_QC
						WHERE ARRIVAL_NUM = '".$vessel."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($Short_Term_row['THE_COUNT'] > 0){
					// one more check
					if($edit_reason == ""){
						echo "<font color=\"#ff0000\">Edit Reason must be entered if this isn't this vessel's first entry.</font>";
						$save = false;
					}


					// ok, the SQL for a modification...
					$sql = "UPDATE WDI_EXPECTED_QC
							SET ENTERED_BY = '".$user."',
								QC_PERSON = '".$qc_contact."',
								ENTRY_DATE_TIME = SYSDATE,
								EXPECTED_QC_DATE_TIME = TO_DATE('".$qc_date." ".$qc_hour.":".$qc_min."', 'MM/DD/YYYY HH24:MI'),
								EDIT_REASON = '".$edit_reason."',
								ANY_WALMART_CARGO = 'Y'
							WHERE ARRIVAL_NUM = '".$vessel."'";
				} else {
					// new record.

					$sql = "INSERT INTO WDI_EXPECTED_QC
								(ARRIVAL_NUM,
								ENTERED_BY,
								QC_PERSON,
								ENTRY_DATE_TIME,
								EXPECTED_QC_DATE_TIME,
								ANY_WALMART_CARGO)
							VALUES
								('".$vessel."',
								'".$user."',
								'".$qc_contact."',
								SYSDATE,
								TO_DATE('".$qc_date." ".$qc_hour.":".$qc_min."', 'MM/DD/YYYY HH24:MI'),
								'Y')";
				}

				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor);

				$sql = "UPDATE VOYAGE
						SET DATE_DEPARTED = TO_DATE('".$qc_date." ".$qc_hour.":".$qc_min."', 'MM/DD/YYYY HH24:MI')
						WHERE LR_NUM = '".$vessel."'";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor);

				echo "<font color=\"#0000FF\">QC Data saved.</font>";
			}
		} elseif($any_cargo == "no"){
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM WDI_EXPECTED_QC
					WHERE ARRIVAL_NUM = '".$vessel."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_row['THE_COUNT'] > 0){
				// ok, the SQL for a modification...
				$sql = "UPDATE WDI_EXPECTED_QC
						SET ANY_WALMART_CARGO = 'N',
							ENTRY_DATE_TIME = SYSDATE
						WHERE ARRIVAL_NUM = '".$vessel."'";
			} else {
				$sql = "INSERT INTO WDI_EXPECTED_QC
							(ARRIVAL_NUM,
							ENTRY_DATE_TIME,
							ANY_WALMART_CARGO)
						VALUES
							('".$vessel."',
							SYSDATE,
							'N')";
			}
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
		}
	}


				
	/*
	if($submit == "Set Vessel Kopke-To-Warehouse Flag" && $vessel != ""){
		$time = date('H');
		if($time >= 22){
			echo "<font color=\"#FF0000\">Cannot assign pallets to-storage for Kopke after 10PM.  Please try again tomorrow.</font>";
		} else {
			$sql = "INSERT INTO KOPKE_VESSEL_XFER_TO_STORAGE
						(ARRIVAL_NUM,
						TRANSFER_DATE,
						TRANSFER_USER)
					VALUES
						('".$vessel."',
						SYSDATE,
						'".$user."')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

			echo "<font color=\"#0000FF\">Vessel ".$vessel." Set.</font>";
		}
	}
	*/
?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Expected QC Date and Time</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0"  cellpadding="4" cellspacing="0">
<form name="a_form" action="WM_expected_QC.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">Vessel:&nbsp;</font></td>
		<td><select name="vessel" onchange="document.a_form.submit(this.form)"><option value="">Select Vessel</option>
<?
	$sql = "SELECT VP.LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE VP, VOYAGE VOY
			WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT')
				AND VP.LR_NUM >= 11577
				AND VP.LR_NUM = VOY.LR_NUM
				AND VOY.DATE_EXPECTED >= SYSDATE - 180
				AND VP.LR_NUM NOT IN
					(SELECT LR_NUM FROM WDI_VESSEL_RELEASE)
			ORDER BY LR_NUM DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $Short_Term_row['LR_NUM']; ?>"<? if( $Short_Term_row['LR_NUM'] == $vessel){?> selected <?}?>>
						<? echo $Short_Term_row['LR_NUM']."-".$Short_Term_row['VESSEL_NAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
</form>
</table>
<?
	if($vessel != ""){
		$sql = "SELECT VESSEL_NAME, LR_NUM, TO_CHAR(ENTRY_DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_SUB, ANY_WALMART_CARGO,
					EDIT_REASON, ENTERED_BY, QC_PERSON, TO_CHAR(EXPECTED_QC_DATE_TIME, 'MM/DD/YYYY') THE_EXPEC,
					TO_CHAR(EXPECTED_QC_DATE_TIME, 'HH24') EXPEC_HOUR, TO_CHAR(EXPECTED_QC_DATE_TIME, 'MI') EXPEC_MIN
				FROM VESSEL_PROFILE VP, WDI_EXPECTED_QC WEQ
				WHERE VP.ARRIVAL_NUM = WEQ.ARRIVAL_NUM
					AND LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$last_entered = "N/A";
			$last_edit_reason = "";
			$last_sub_user = "N/A";
			$last_sub_qc_rep = "";
			$last_expected_qc = "MM/DD/YYYY";
			$last_qc_hour = "HH";
			$last_qc_min = "MI";
			$last_anycargo = "Y";
		} else {
			$last_entered = $Short_Term_row['THE_SUB'];
			$last_edit_reason = $Short_Term_row['EDIT_REASON'];
			$last_sub_user = $Short_Term_row['ENTERED_BY'];
			$last_sub_qc_rep = $Short_Term_row['QC_PERSON'];
			$last_expected_qc = $Short_Term_row['THE_EXPEC'];
			$last_qc_hour = $Short_Term_row['EXPEC_HOUR'];
			$last_qc_min = $Short_Term_row['EXPEC_MIN'];
			$last_anycargo = $Short_Term_row['ANY_WALMART_CARGO'];
		}
?>
<table border="0"  cellpadding="4" cellspacing="0">
<form name="b_form" action="WM_expected_QC.php" method="post">
<input name="vessel" type="hidden" value="<? echo $vessel; ?>">
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Walmart Cargo?:</font></td>
	</tr>
	<tr>
		<td width="10%"><input type="radio" name="any_cargo" value="yes"<? if($last_anycargo == "Y"){?> checked<?}?>><font size="2" face="Verdana">Yes</font></td>
		<td width="90%"><input type="radio" name="any_cargo" value="no"<? if($last_anycargo == "N"){?> checked<?}?>><font size="2" face="Verdana">No</font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Last Entered on:</font></td>
		<td width="90%"><font size="2" face="Verdana"><? echo $last_entered; ?></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Last Entered by:</font></td>
		<td width="90%"><font size="2" face="Verdana"><? echo $last_sub_user; ?></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Expected QC Date:</font></td>
		<td width="90%"><input type="text" name="qc_date" size="12" maxlength="10" value="<? echo $last_expected_qc; ?>">&nbsp;&nbsp;&nbsp;
						<input type="text" name="qc_hour" size="2" maxlength="2" value="<? echo $last_qc_hour; ?>">:
						<input type="text" name="qc_min" size="2" maxlength="2" value="<? echo $last_qc_min; ?>">
						<font size="2" face="Verdana">(24-hour time entry)</font>
							</td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">QC Contact:</font></td>
		<td width="90%"><select name="qc_contact"><option value="">Select QC Contact</option>
							<option value="Lamar Baber" <? if($last_sub_qc_rep == "Lamar Baber"){?>selected<?}?>>Lamar Baber</option>
							<option value="Philip Pearson" <? if($last_sub_qc_rep == "Philip Pearson"){?>selected<?}?>>Philip Pearson</option>
							<option value="Ronald Rybnikar" <? if($last_sub_qc_rep == "Ronald Rybnikar"){?>selected<?}?>>Ronald Rybnikar</option>
				</select></td>
	</tr>
<?
	if($last_sub_qc_rep != ""){
?>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Reason for Edit:</font></td>
		<td width="90%"><input type="text" name="edit_reason" size="30" maxlength="50" value="<? echo $last_edit_reason; ?>"></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>