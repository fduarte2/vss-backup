<?
/*
*		Adam Walter, July 2014.
*
*		Trucker "main segment" of Cargo Load Release
*
*		
*********************************************************************************/


	$pagename = "truck_check_out";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$truck_ID = $HTTP_GET_VARS['truck_ID'];
	if($truck_ID == ""){
		$truck_ID = $HTTP_POST_VARS['truck_ID'];
	}
	
	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Generate Gatepass"){
		$initials = strtoupper($HTTP_POST_VARS['initials']);
		$t_e_check = strtoupper($HTTP_POST_VARS['t_e_check']);
		$border_ver = strtoupper($HTTP_POST_VARS['border_ver']);

		if($initials == "" || $t_e_check == "" || $border_ver == ""){
			echo "<font color=\"#FF0000\">All 3 fields must be entered.  Save Cancelled.</font>";
//		} elseif($t_e_check != "Y" && $t_e_check != "N"){
//			echo "<font color=\"#FF0000\">T&E Signed Check must either be Y or N.  Save Cancelled.</font>";
		} else {

			$valid_save = ValidateTrucker($truck_ID, $rfconn);
			if($valid_save == ""){
				// set the gatepass date... only if it's not already set, though
				$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
						SET GATEPASS_MADE_ON = SYSDATE,
							CHECKOUT = SYSDATE,
							LAST_CHANGED_BY = '".$user."',
							LAST_CHANGED_ON = SYSDATE,
							CHECKOUT_INIT = '".$initials."',
							T_E_VERIFY = '".$t_e_check."',
							BORDERCORSS_VERIFY = '".$border_ver."',
							GATEPASS_NUM = CLR_GATEPASS_NUM_SEQ.NEXTVAL
						WHERE PORT_ID = '".$truck_ID."'
							AND GATEPASS_MADE_ON IS NULL";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			} else {
				echo "<font color=\"#FF0000\">Could not generate Gatepass:<br>".$valid_save."Please Fix Trucker Info and try again.<br></font>";
			}
		}
	}

	$pdf_allowed = CheckPdfAllowed($truck_ID, $rfconn);



?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CLR Trucker Check Out
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="ID_select" action="truck_checkout_index.php" method="post">
	<tr>
		<td align="left"><font size="3" face="Verdana">Trucker ID#:  <select name="truck_ID"><option value="">Select Truck ID</option>
<?
	$sql = "SELECT PORT_ID, NVL(DRIVER_NAME, 'No Name Yet') THE_NAME 
			FROM CLR_TRUCK_LOAD_RELEASE 
				WHERE (GATEPASS_PDF_DATE IS NULL OR PORT_ID = '".$truck_ID."')
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
				</select></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Select"><BR><HR></td>
	</tr>
</form>
<?
	if($truck_ID != ""){
		$sql = "SELECT TLR.*, NVL(PORT_OF_ENTRY, 'TBD') USA_SIDE, NVL(CAN_PORT_OF_ENTRY, 'TBD') CAN_SIDE, 
						NVL(TO_CHAR(SEAL_TIME, 'MM/DD/YYYY HH24:MI:SS'), 'SEAL NOT YET SCANNED') THE_SEAL_TIME
				FROM CLR_TRUCK_LOAD_RELEASE TLR, CLR_BORDERCROSSING CB
				WHERE PORT_ID = '".$truck_ID."'
					AND TLR.BORDER_CROSSING = CB.BORDERCROSSING_ID(+)";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
		if($pdf_allowed == true){
?>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><a href="cargo_gatepass.php?truck_id=<? echo $truck_ID; ?>">Click Here</a> to print/reprint the gate pass.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana" color="#FF0000">&nbsp;</font></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana" color="#0000A0"><b>Selected ID#:  <? echo $truck_ID; ?></b></font><BR><BR></td>
	</tr>
<!--	<? T_E_To_Screen($truck_ID, $rfconn); ?> !-->
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>T&E#:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_data, "TRUCK_TE_NUM"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Border Crossing:</b></font></td>
		<td><font size="2" face="Verdana">USA:&nbsp;&nbsp;&nbsp;<? echo ociresult($main_data, "USA_SIDE"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Canada:&nbsp;&nbsp;&nbsp;<? echo ociresult($main_data, "CAN_SIDE"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Seal #:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_data, "SEAL_NUM"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Seal Scanned On:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_data, "THE_SEAL_TIME"); ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<form name="update" action="truck_checkout_index.php" method="post">
<input type="hidden" name="truck_ID" value="<? echo $truck_ID; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Port Clerk Initials:</b></font></td>
		<td><input type="text" name="initials" size="5" maxlength="5" value="<? echo ociresult($main_data, "CHECKOUT_INIT"); ?>"></td>
	</tr>
<?
	if(ociresult($main_data, "BORDERCROSSING_ID") != "12"){
?>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>T&E Checked:</b></font></td>
		<td><select name="t_e_check"><option value="">Please Select</option>
									<option value="Y"<? if(ociresult($main_data, "T_E_VERIFY") == "Y"){?> selected <?}?>>Y</option>
									<option value="N"<? if(ociresult($main_data, "T_E_VERIFY") == "N"){?> selected <?}?>>N</option>
				</select></td>
	</tr>
<?
	} else {
?>
	<input type="hidden" name="t_e_check" value="Y">
<?
	}	
?>	
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Border Crossing Verified with Driver:</b></font></td>
		<td><select name="border_ver"><option value="">Please Select</option>
									<option value="Y"<? if(ociresult($main_data, "BORDERCORSS_VERIFY") == "Y"){?> selected <?}?>>Y</option>
									<option value="N"<? if(ociresult($main_data, "BORDERCORSS_VERIFY") == "N"){?> selected <?}?>>N</option>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Gatepass" 
					<? if((ociresult($main_data, "THE_SEAL_TIME") == "SEAL NOT YET SCANNED" && (ociresult($main_data, "PICKUP_TYPE") != "HTH" || ociresult($main_data, "BORDER_CROSSING") != "12"))
											|| strpos($security_allowance, "M") === false){?> disabled <?}?>></td>
	</tr>
</form>
<?
}













function CheckPdfAllowed($truck_ID, $rfconn){
	$sql = "SELECT NVL(TO_CHAR(GATEPASS_NUM), 'NONE') THE_GATEPASS
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID = '".$truck_ID."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	if(ociresult($short_term_data, "THE_GATEPASS") == "NONE"){
		return false;
	} else {
		return true;
	}

}

function ValidateTrucker($truck_ID, $rfconn){
	$return = "";

	$sql = "SELECT NVL(SEAL_NUM, 'NONE') SEAL_CHECK,
				NVL(TO_CHAR(SEAL_TIME), 'NONE') SEALTIME_CHECK,
				NVL(TO_CHAR(DRIVER_NAME), 'NONE') DRIVER_CHECK,
				NVL(TO_CHAR(TRUCKING_COMPANY), 'NONE') TRUCK_COMP_CHECK,
				NVL(TO_CHAR(TRAIL_REEF_PLATE_NUM), 'NONE') TRAILNUM_CHECK,
				NVL(TO_CHAR(GREATEST(AMS_RELEASE, LINE_RELEASE, BROKER_RELEASE), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL,
				NVL(TO_CHAR(BORDER_CROSSING), 'NONE') BORDER_CROSSING_CHECK,
				TRUCK_TE_NUM, T_E_NUM, PICKUP_TYPE, DESTINATION
			FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_TRUCK_MAIN_JOIN CTMJ, CLR_MAIN_DATA CMD
			WHERE CTLR.PORT_ID = '".$truck_ID."'
				AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
				AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		$return .= "Cannot Find joined cargo to test the Release Status of";
	} else {
		do {
			if(ociresult($short_term_data, "SEAL_CHECK") == "NONE" && (ociresult($short_term_data, "BORDER_CROSSING_CHECK") != "12" || ociresult($short_term_data, "PICKUP_TYPE") != "HTH")){
				$return .= "No Seal # has yet been issued.<br>";
			}
			if(ociresult($short_term_data, "BORDER_CROSSING_CHECK") == "NONE"){
				$return .= "No Bordercrossing Associted with Truck.<br>";
			}
			if(ociresult($short_term_data, "SEALTIME_CHECK") == "NONE" && (ociresult($short_term_data, "BORDER_CROSSING_CHECK") != "12" || ociresult($short_term_data, "PICKUP_TYPE") != "HTH")){
				$return .= "Security has not yet scanned the Seal.<br>";
			}
			if(ociresult($short_term_data, "DRIVER_CHECK") == "NONE"){
				$return .= "Diver Name field is blank.<br>";
			}
			if(ociresult($short_term_data, "TRUCK_COMP_CHECK") == "NONE"){
				$return .= "Truck Company field is blank.<br>";
			}
			if(ociresult($short_term_data, "TRAILNUM_CHECK") == "NONE"){
				$return .= "Trailer License field is blank.<br>";
			}
			if(ociresult($short_term_data, "THE_FINAL") == "ON HOLD"){
				$return .= "Some Cargo Scanned to this Truck has not been Released.<br>";
			}
/*			if(ociresult($short_term_data, "BORDER_CROSSING_CHECK") != "12" && ociresult($short_term_data, "TRUCK_TE_NUM") == "" && ociresult($short_term_data, "T_E_NUM") == ""){
				$return .= "No T&E could be found at either the Cargo or Truck level for this non-domestic cargo.<br>";
			}*/
			if(ociresult($short_term_data, "BORDER_CROSSING_CHECK") != "12" && ociresult($short_term_data, "TRUCK_TE_NUM") == ""){
				$return .= "No T&E could be found for this non-domestic cargo.<br>";
			}
			if(ociresult($short_term_data, "BORDER_CROSSING_CHECK") == "12" && ociresult($short_term_data, "DESTINATION") == "CANADIAN"){
				$return .= "Border Crossing says Domestic, but attached cargo is listed as Canadian.<br>";
			}
			if(ociresult($short_term_data, "BORDER_CROSSING_CHECK") != "12" && ociresult($short_term_data, "DESTINATION") == "DOMESTIC"){
				$return .= "Border Crossing says Non-Domestic, but attached cargo is listed as Domestic.<br>";
			}
		} while(ocifetch($short_term_data));
	}
/*
	if(ociresult($short_term_data, "PICKUP_TYPE") != "HTH"){
		$sql = "SELECT * 
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE PORT_ID = '".$truck_ID."'";
		$ord_data = ociparse($rfconn, $sql);
		ociexecute($ord_data);
		if(!ocifetch($ord_data)){
			$ord = "UNKNOWN"; // the CHILEAN logic goes here, for now, nothing
		} else {
			$ord = ociresult($main_data, "CLEM_ORDER_NUM");
		}
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = TRIM('".$ord."')
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
		$count_data = ociparse($rfconn, $sql);
		ociexecute($count_data);
		ocifetch($count_data);
		if(ociresult($count_data, "THE_PLTS") <= 0){
			$return .= "At Truck Checkin, Order# entered was ".$ord.".  There are no pallets scanned out on this Order#.<br>";
		}
	}
*/
	return $return;
} 





function T_E_To_Screen($truck_ID, $rfconn){
?>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>T&E:</b></font></td>
<?
		$sql = "SELECT TRUCK_TE_NUM 
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE PORT_ID = '".$truck_ID."'";
		$file_data = ociparse($rfconn, $sql);
		ociexecute($file_data);
		ocifetch($file_data);
		if(ociresult($file_data, "TRUCK_TE_NUM") != ""){
?>
		<td><font size="2" face="Verdana">T&E# <? echo ociresult($file_data, "TRUCK_TE_NUM"); ?></font></td>
<?
		} else {

			$sql = "SELECT * 
					FROM CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
					WHERE CTMJ.TRUCK_PORT_ID = '".$truck_ID."'
						AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY
					ORDER BY CLR_KEY";
			$file_data = ociparse($rfconn, $sql);
			ociexecute($file_data);
			if(!ocifetch($file_data)){
?>
		<td><font size="2" face="Verdana">No File Found</font></td>
<?
			} else {
?>
		<td><font size="2" face="Verdana"><a href="./upload_clr_t_e/<? echo ociresult($file_data, "T_E_FILE"); ?>"><? echo ociresult($file_data, "T_E_FILE"); ?></a></font>
<?
				while(ocifetch($file_data)){
?>
		<br><font size="2" face="Verdana"><a href="./upload_clr_t_e/<? echo ociresult($file_data, "T_E_FILE"); ?>"><? echo ociresult($file_data, "T_E_FILE"); ?></a></font>
<?
				}
?>
			</td>
<?
			}
		}
?>
	</tr>
<?
}
?>