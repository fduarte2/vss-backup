<?
/*
*	Adam Walter, Sep 2013.
*
*	page for editing any and all rows.
*	HIGHLY FLAMMABLE
*************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}
	if($HTTP_GET_VARS['vessel'] != "" && $HTTP_GET_VARS['cont'] != "" && $HTTP_GET_VARS['bol'] != ""){
		$vessel = $HTTP_GET_VARS['vessel'];
		$cont = $HTTP_GET_VARS['cont'];
		$bol = $HTTP_GET_VARS['bol'];
	} else {
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
	}
	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
			WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "THE_VESSEL");

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Edit Entry"){
		$sql = "SELECT CARGO_TYPE
				FROM CANADIAN_LOAD_RELEASE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$cargo_type_check = ociresult($short_term_data, "CARGO_TYPE");

		$sql = "SELECT CASES
				FROM CANADIAN_LOAD_RELEASE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$check_cases = ociresult($short_term_data, "CASES");

		$bad_message = "";
		$new_consignee = str_replace("'", "`", $HTTP_POST_VARS['new_consignee']);
		$new_consignee = str_replace("\\", "", $new_consignee);
		$new_ocean_bol = str_replace("'", "`", $HTTP_POST_VARS['new_ocean_bol']);
		$new_ocean_bol = str_replace("\\", "", $new_ocean_bol);
		$new_pallets = str_replace("'", "`", $HTTP_POST_VARS['new_pallets']);
		$new_pallets = str_replace("\\", "", $new_pallets);
		$new_comm = str_replace("'", "`", $HTTP_POST_VARS['new_comm']);
		$new_comm = str_replace("\\", "", $new_comm);
		$new_cases = str_replace("'", "`", $HTTP_POST_VARS['new_cases']);
		$new_cases = str_replace("\\", "", $new_cases);
		$new_mfstd = str_replace("'", "`", $HTTP_POST_VARS['new_mfstd']);
		$new_mfstd = str_replace("\\", "", $new_mfstd);
		$new_cont = str_replace("'", "`", $HTTP_POST_VARS['new_cont']);
		$new_cont = str_replace("\\", "", $new_cont);
		$new_bol = str_replace("'", "`", $HTTP_POST_VARS['new_bol']);
		$new_bol = str_replace("\\", "", $new_bol);
		$new_mode = str_replace("'", "`", $HTTP_POST_VARS['new_mode']);
		$new_mode = str_replace("\\", "", $new_mode);
		$new_mode = strtoupper($new_mode);
		$remove_release = $HTTP_POST_VARS['remove_release'];

		if($new_pallets != "" && (!is_numeric($new_pallets) || $new_pallets < 0 || strlen($new_pallets) > 7 || $new_pallets != round($new_pallets))){
			$bad_message .= "New Pallet Count must be a positive, whole number<br>";
		}
		if($new_cases != "" && (!is_numeric($new_cases) || $new_cases < 0 || strlen($new_cases) > 7 || $new_cases != round($new_cases))){
			$bad_message .= "New Case Count must be a positive, whole number<br>";
		}
		if(!ereg("^([a-zA-Z0-9`/\. -])+$", $new_consignee)){
//			echo "cons: ".$new_consignee."<br>";
			$bad_message .= "New Consignee must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9`/ ])*$", $new_ocean_bol)){
			$bad_message .= "New Ocean-BOL, if entered, must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9`/ ])+$", $new_comm)){
			$bad_message .= "New Commodity must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9`/ ])*$", $new_mfstd)){
//			echo "mfstd: ".$new_mfstd."<br>";
			$bad_message .= "New MFSTD, if entered, must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9 ])+$", $new_cont)){
			$bad_message .= "New Container/Trailer# must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9 ])+$", $new_bol)){
			$bad_message .= "New BoL/Order# must be alphanumeric.<br>";
		}
		if(!ereg("^([a-zA-Z0-9 ])+$", $new_mode)){
			$bad_message .= "New Mode must be alphanumeric.<br>";
		}
		if($new_cont != $cont || $new_bol != $bol){
//			echo "newcont: ".$new_cont." cont: ".$cont." newbol: ".$new_bol." bol: ".$bol."<br>";
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CANADIAN_LOAD_RELEASE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$new_cont."'
						AND BOL = '".$new_bol."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") > 0){
				$bad_message .= "You cannot change the CONT#/BOL# to CONT:".$new_cont." BoL:".$new_bol." because that combination is already in the system for this vessel.<br>";
			}

			if($cargo_type_check == "CHILEAN"){
				$bad_message .= "You cannot change the CONT#/BOL# on Chilean cargo<br>";
			}
		}



		if($bad_message == ""){
			// no errors.  go for it.
			if($remove_release == "Y"){
				$release_sql = ", AMS_STATUS = NULL, AMS_COMMENTS = NULL, LINE_STATUS = NULL, LINE_COMMENTS = NULL, BROKER_STATUS = NULL, BROKER_COMMENTS = NULL";
			} else {
				$release_sql = "";
			}
			$sql = "UPDATE CANADIAN_LOAD_RELEASE
					SET CONSIGNEE = '".$new_consignee."',
						OCEAN_BOL = '".$new_ocean_bol."',
						PALLET_COUNT = '".$new_pallets."',
						COMMODITY = '".$new_comm."',
						CASES = '".$new_cases."',
						CONTAINER_NUM = '".$new_cont."',
						BOL = '".$new_bol."',
						CARGO_MODE = '".$new_mode."',
						MFSTD = '".$new_mfstd."'".$release_sql."
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
			if($new_cont != $cont || $new_bol != $bol){
				$sql = "UPDATE CANADIAN_RELEASE_HISTORY
						SET BOL = '".$new_bol."',
							CONTAINER_NUM = '".$new_cont."'
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND CONTAINER_NUM = '".$cont."'
							AND BOL = '".$bol."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				// also, in this case, the new bol is the current bol, so update for the rest of the page.
				$cont = $new_cont;
				$bol = $new_bol;
			}
			echo "<font color=\"#0000FF\">Line Updated.<br></font>";
			if($check_cases != $new_cases){
				echo "<font color=\"#0000FF\">Please Note:  Case Count has been changed.  Automatic AMS releases are by case count; this may impact the automatic release.</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Cannot update data for the following reason(s):<br><br>".$bad_message."</font>";
		}
	}













	$sql = "SELECT CONSIGNEE, OCEAN_BOL, PALLET_COUNT, COMMODITY, CASES, MFSTD, CARGO_MODE,
				DECODE(AMS_STATUS, NULL, 'ON HOLD', TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
				DECODE(LINE_STATUS, NULL, 'ON HOLD', TO_CHAR(LINE_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
				DECODE(BROKER_STATUS, NULL, 'ON HOLD', TO_CHAR(BROKER_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_BROKER,
				DECODE(GATEPASS_PDF_DATE, NULL, 'NONE', TO_CHAR(GATEPASS_PDF_DATE, 'MM/DD/YYYY HH:MI AM')) THE_GATEPASS_CHECK
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$cur_consignee = ociresult($short_term_data, "CONSIGNEE");
	$cur_ocean_bol = ociresult($short_term_data, "OCEAN_BOL");
	$cur_pallets = ociresult($short_term_data, "PALLET_COUNT");
	$cur_comm = ociresult($short_term_data, "COMMODITY");
	$cur_cases = ociresult($short_term_data, "CASES");
	$cur_mfstd = ociresult($short_term_data, "MFSTD");
	$cur_ams = ociresult($short_term_data, "THE_AMS");
	$cur_line = ociresult($short_term_data, "THE_LINE");
	$cur_ohl = ociresult($short_term_data, "THE_BROKER");
	$cur_mode = ociresult($short_term_data, "CARGO_MODE");
	$gatepass_check = ociresult($short_term_data, "THE_GATEPASS_CHECK");

	if($bad_message == true || $submit == ""){
		$new_consignee = $cur_consignee;
		$new_ocean_bol = $cur_ocean_bol;
		$new_pallets = $cur_pallets;
		$new_comm = $cur_comm;
		$new_cases = $cur_cases;
		$new_mfstd = $cur_mfstd;
		$new_bol = $bol;
		$new_cont = $cont;
		$new_mode = $cur_mode;
		$remove_release = "";
	} else {
		$new_consignee = str_replace("'", "`", $HTTP_POST_VARS['new_consignee']);
		$new_consignee = str_replace("\\", "", $new_consignee);
		$new_ocean_bol = str_replace("'", "`", $HTTP_POST_VARS['new_ocean_bol']);
		$new_ocean_bol = str_replace("\\", "", $new_ocean_bol);
		$new_pallets = str_replace("'", "`", $HTTP_POST_VARS['new_pallets']);
		$new_pallets = str_replace("\\", "", $new_pallets);
		$new_comm = str_replace("'", "`", $HTTP_POST_VARS['new_comm']);
		$new_comm = str_replace("\\", "", $new_comm);
		$new_cases = str_replace("'", "`", $HTTP_POST_VARS['new_cases']);
		$new_cases = str_replace("\\", "", $new_cases);
		$new_mfstd = str_replace("'", "`", $HTTP_POST_VARS['new_mfstd']);
		$new_mfstd = str_replace("\\", "", $new_mfstd);
		$new_cont = str_replace("'", "`", $HTTP_POST_VARS['new_cont']);
		$new_cont = str_replace("\\", "", $new_cont);
		$new_bol = str_replace("'", "`", $HTTP_POST_VARS['new_bol']);
		$new_bol = str_replace("\\", "", $new_bol);
		$new_mode = str_replace("'", "`", $HTTP_POST_VARS['new_mode']);
		$new_mode = str_replace("\\", "", $new_mode);
		$new_mode = strtoupper($new_mode);
		$remove_release = $HTTP_POST_VARS['remove_release'];
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC"><b>Main Board EDIT Entry</b>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($general_edit_auth != "Y"){
		echo "<font color=\"#FF0000\">This user is not authorized for this page.  Cancelling script.</font>";
		exit;
	}
	if($gatepass_check != "NONE"){
		echo "<font color=\"#FF0000\">Selected Entry has already printed it's Gatepass.  Cannot Edit.</font>";
		exit;
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="general_edit_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td colspan="1" width="10%"><font size="4" face="Verdana" color="#0000A0">Vessel:</td>
		<td colspan="2" align="left"><font size="4" face="Verdana" color="#0000A0"><? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="4" face="Verdana" color="#0000A0">Container:</td>
		<td colspan="2" align="left"><font size="4" face="Verdana" color="#0000A0"><? echo $cont; ?></font></td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="4" face="Verdana" color="#0000A0">Bol/Order:</td>
		<td colspan="2" align="left"><font size="4" face="Verdana" color="#0000A0"><? echo $bol; ?></font></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td width="20%"><font size="3" face="Verdana"><b>Current:</b></font></td>
		<td><font size="3" face="Verdana"><b>New:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Container:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cont; ?></font></td>
		<td><input type="text" name="new_cont" size="20" maxlength="20" value="<? echo $new_cont; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">BoL:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $bol; ?></font></td>
		<td><input type="text" name="new_bol" size="20" maxlength="20" value="<? echo $new_bol; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Consignee:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_consignee; ?></font></td>
		<td><input type="text" name="new_consignee" size="40" maxlength="40" value="<? echo $new_consignee; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Ocean BOL:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_ocean_bol; ?></font></td>
		<td><input type="text" name="new_ocean_bol" size="20" maxlength="20" value="<? echo $new_ocean_bol; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Pallet Count:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_pallets; ?></font></td>
		<td><input type="text" name="new_pallets" size="7" maxlength="7" value="<? echo $new_pallets; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Case Count:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_cases; ?></font></td>
		<td><input type="text" name="new_cases" size="7" maxlength="7" value="<? echo $new_cases; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Commodity:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_comm; ?></font></td>
		<td><input type="text" name="new_comm" size="40" maxlength="40" value="<? echo $new_comm; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Mode:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_mode; ?></font></td>
		<td><input type="text" name="new_mode" size="20" maxlength="20" value="<? echo $new_mode; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">Note:  Spelling is very important for this field.</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">MFSTD:</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo $cur_mfstd; ?></font></td>
		<td><input type="text" name="new_mfstd" size="30" maxlength="30" value="<? echo $new_mfstd; ?>"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="2" face="Verdana">AMS Release:</font></td>
		<td colspan="2" align="left"><font size="2" face="Verdana"><? echo $cur_ams; ?></font></td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="2" face="Verdana">Line Release:</font></td>
		<td colspan="2" align="left"><font size="2" face="Verdana"><? echo $cur_line; ?></font></td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="2" face="Verdana">Broker Release:</font></td>
		<td colspan="2" align="left"><font size="2" face="Verdana"><? echo $cur_ohl; ?></font></td>
	</tr>
	<tr>
		<td colspan="1" width="10%"><font size="2" face="Verdana">Clear <b>ALL</b> Release Information?:</font></td>
		<td colspan="2" align="left"><input type="checkbox" name="remove_release" value="Y" <? if($remove_release == "Y"){?>checked<?}?>></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Edit Entry"></td>
	</tr>
</form>
</table>
