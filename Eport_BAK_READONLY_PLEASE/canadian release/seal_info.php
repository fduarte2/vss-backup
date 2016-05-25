<?
/*
*	Adam Walter, Sep 2013.
*
*	page for viewing/updating Seal info.
*************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$vessel = $HTTP_GET_VARS['vessel'];
	$cont = $HTTP_GET_VARS['cont'];
	$bol = $HTTP_GET_VARS['bol'];

	$submit = $HTTP_POST_VARS['submit'];
	if($submit != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$seal_num = $HTTP_POST_VARS['seal_num'];
		$T_E_y_n = strtoupper($HTTP_POST_VARS['T_E_y_n']);
		$T_E_clerk = $HTTP_POST_VARS['T_E_clerk'];

		if($seal_num == "" || $T_E_y_n == "" || $T_E_clerk == ""){
			echo "<font color=\"#FF0000\">All 3 fields must be entered.  Save Cancelled.</font>";
		} elseif($T_E_y_n != "Y" && $T_E_y_n != "N"){
			echo "<font color=\"#FF0000\">T&E Signed Check must either be Y or N.  Save Cancelled.</font>";
		} else {
			if($T_E_y_n == "N"){
				$gatepass_sql = "NULL";
			} else {
				$gatepass_sql = "CANADIAN_GATEPASS_SEQ.NEXTVAL";
			}
			// clear the gatepass_pdf field, that gets set when they go to printout.
			$sql = "UPDATE CANADIAN_LOAD_RELEASE
					SET T_E_YES_NO = '".$T_E_y_n."',
						T_E_SIGNAUTH_BY = '".$T_E_clerk."',
						SEAL_TIME = DECODE(SEAL_NUM, '".$seal_num."', SEAL_TIME, NULL),
						SEALER = DECODE(SEAL_NUM, '".$seal_num."', SEALER, NULL),
						SEAL_NUM = '".$seal_num."',
						GATEPASS_NUM = ".$gatepass_sql.",
						GATEPASS_PDF_DATE = NULL,
						GATEPASS_ISSUED = SYSDATE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);

			echo "<font color=\"#0000FF\">Seal Information Saved.</font>";
		}
	}

	$sql = "SELECT DECODE(AMS_STATUS, NULL, 'ON HOLD', TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_CHECK1,
				DECODE(LINE_STATUS, NULL, 'ON HOLD', TO_CHAR(LINE_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_CHECK2,
				DECODE(BROKER_STATUS, NULL, 'ON HOLD', TO_CHAR(BROKER_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_CHECK3
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_CHECK1") == "ON HOLD" || ociresult($short_term_data, "THE_CHECK2") == "ON HOLD" || ociresult($short_term_data, "THE_CHECK3") == "ON HOLD"){
		$update_allowed = "NO";
	} else {
		$update_allowed = "YES";
	}
	$pdf_allowed = CheckPdfAllowed($vessel, $cont, $bol, $rfconn);
/*	if(ociresult($short_term_data, "GATE_CHECK") == 'NONE' || ociresult($short_term_data, "THE_SEALER") == 'NONE' || ociresult($short_term_data, "BORDER_CHECK") == 'NONE'){
		$pdf_allowed = "NO";
	} else {
		$pdf_allowed = "YES";
	}*/

	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
			WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "THE_VESSEL");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Seal Information</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?><br>&nbsp;&nbsp;&nbsp;PoW Vessel#:&nbsp;&nbsp;&nbsp;<? echo $vesname; ?><br>&nbsp;&nbsp;&nbsp;Container:&nbsp;&nbsp;&nbsp;<? echo $cont; ?><br>&nbsp;&nbsp;&nbsp;BoL:&nbsp;&nbsp;&nbsp;<? echo $bol; ?>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><a href="canadian_scoreboard_index.php?vessel=<? echo $vessel;?>">Click Here</a> to return to the Main Board.</font></td>
	</tr>
<?
	if($pdf_allowed == ""){
?>
	<tr>
		<td><font size="3" face="Verdana"><a href="canadian_gatepass.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"">Click Here</a> to print the gate pass.</font></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td><font size="2" face="Verdana" color="#FF0000"><b>Cannot Print Gatepass for the following reasons:<br><? echo $pdf_allowed; ?></b></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td><hr></td>
	</tr>
</table>

<?
	if($seal_auth == "Y" && $update_allowed == "YES"){
		DisplayEditPage($rfconn, $vessel, $cont, $bol);
	} else {
		DisplayViewPage($rfconn, $vessel, $cont, $bol);
	}





function DisplayEditPage($rfconn, $vessel, $cont, $bol){

	$sql = "SELECT SEAL_NUM, T_E_YES_NO, T_E_SIGNAUTH_BY, SEALER, TO_CHAR(SEAL_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_SEALTIME, GATEPASS_NUM
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="data_submit" action="seal_info_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>Current:</b></font></td>
		<td><font size="2" face="Verdana"><b>New:</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Gatepass#:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "GATEPASS_NUM"); ?></font></td>
		<td><font size="2" face="Verdana">(Set by System)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Seal#:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SEAL_NUM"); ?></font></td>
		<td><input type="text" name="seal_num" size="30" maxlength="30" value="<? echo ociresult($short_term_data, "SEAL_NUM"); ?>"</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">T&E Verified?:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "T_E_YES_NO"); ?></font></td>
		<td><input type="text" name="T_E_y_n" size="1" maxlength="1" value="<? echo ociresult($short_term_data, "T_E_YES_NO"); ?>"</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Verifying Port Clerk:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "T_E_SIGNAUTH_BY"); ?></font></td>
		<td><input type="text" name="T_E_clerk" size="20" maxlength="20" value="<? echo ociresult($short_term_data, "T_E_SIGNAUTH_BY"); ?>"</td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Sealed By#:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SEALER")."<br>".ociresult($short_term_data, "THE_SEALTIME"); ?></font></td>
		<td><font size="2" face="Verdana">(Set by Scanner)</font></td>
	</tr>
<?
	//if(ociresult($short_term_data, "SEALER") != ""){
?>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Seal Information And Generate Gatepass"></td>
	</tr>
<?
	//} else {
?>
<!--	<tr>
		<td colspan="3"><font size="2" face="Verdana" color="#FF0000"><b>Cannot assign Gatepass to this truck; Seal Scan has not yet occurred.</td>
	</tr> !-->
<?
	//}
?>
</table>
<?
}



function DisplayViewPage($rfconn, $vessel, $cont, $bol){
	$sql = "SELECT SEAL_NUM, T_E_YES_NO, T_E_SIGNAUTH_BY
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Seal#:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SEAL_NUM"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">T&E Signed?</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "T_E_YES_NO"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Verifying Clerk:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "T_E_SIGNAUTH_BY"); ?></font></td>
	</tr>
</table>
<?
	}


function CheckPdfAllowed($vessel, $cont, $bol, $rfconn){
	$return = "";

	$sql = "SELECT NVL(GATEPASS_NUM, 'NONE') GATE_CHECK, 
				NVL(SEALER, 'NONE') THE_SEALER, 
				NVL(TO_CHAR(BORDER_CROSSING), 'NONE') BORDER_CHECK,
				NVL(TO_CHAR(OCEAN_BOL), 'NONE') BOL_CHECK,
				NVL(TO_CHAR(REEFER_PLATE_NUM), 'NONE') PLATE_CHECK,
				NVL(TO_CHAR(CONSIGNEE), 'NONE') CONSIGN_CHECK,
				NVL(TO_CHAR(T_AND_E_DISPLAY), 'NONE') TE_CHECK,
				NVL(TO_CHAR(SEAL_NUM), 'NONE') SEAL_CHECK,
				NVL(TO_CHAR(SEAL_TIME), 'NONE') SEALTIME_CHECK,
				NVL(TO_CHAR(COMMODITY), 'NONE') CARGO_CHECK,
				NVL(TO_CHAR(CASES), 'NONE') CASE_CHECK,
				NVL(TO_CHAR(T_E_SIGNAUTH_BY), 'NONE') TEAUTH_CHECK		
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "GATE_CHECK") == "NONE"){
		$return .= "No Gatepass # has yet been issued.<br>";
	}
	if(ociresult($short_term_data, "THE_SEALER") == "NONE"){
		$return .= "Security has not yet scanned the Seal.<br>";
	}
	if(ociresult($short_term_data, "BORDER_CHECK") == "NONE"){
		$return .= "No Border Crossing has been entered for the Truck.<br>";
	}
	if(ociresult($short_term_data, "BOL_CHECK") == "NONE"){
		$return .= "No BOL present.<br>";
	}
	if(ociresult($short_term_data, "PLATE_CHECK") == "NONE"){
		$return .= "No License Plate entered.<br>";
	}
	if(ociresult($short_term_data, "CONSIGN_CHECK") == "NONE"){
		$return .= "No Consignee.  Contact TS for this one.<br>";
	}
	if(ociresult($short_term_data, "TE_CHECK") == "NONE"){
		$return .= "No T&E Found.<br>";
	}
	if(ociresult($short_term_data, "SEAL_CHECK") == "NONE"){
		$return .= "No Seal # has yet been issued.<br>";
	}
	if(ociresult($short_term_data, "SEALTIME_CHECK") == "NONE"){
		$return .= "No Seal Time has been Recorded.<br>";
	}
	if(ociresult($short_term_data, "CARGO_CHECK") == "NONE"){
		$return .= "No Commodity.  Contact TS for this one.<br>";
	}
	if(ociresult($short_term_data, "CASE_CHECK") == "NONE"){
		$return .= "No Casecount found; this is set at the time of the Security Scan.<br>";
	}
	if(ociresult($short_term_data, "TEAUTH_CHECK") == "NONE"){
		$return .= "No one has authorized the T&E yet.<br>";
	}

	$sql = "SELECT ORDERSTATUSID FROM DC_ORDER
			WHERE TRIM(ORDERNUM) = '".$bol."'
				AND TRIM(TRUCKTAG) = '".$cont."'
				AND VESSELID = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		// this isn't a DC order, skip this check.
	} elseif(ociresult($short_term_data, "ORDERSTATUSID") == 9){
		// this order is good to go from DC side, no problem.
	} else {
		$return .= "Eport2 does not yet show this order as Complete.<br>";
	}


	return $return;
}