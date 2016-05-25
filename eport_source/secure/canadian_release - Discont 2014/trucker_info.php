<?
/*
*	Adam Walter, Sep 2013.
*
*	page for viewing/updating Trucker info.
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

	$sql = "SELECT NVL(TO_CHAR(CBP_FAX, 'MM/DD/YYYY'), 'NONE') THE_CHECK, NVL(T_AND_E_DISPLAY, 'NONE') THE_TE
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$update_allowed = ociresult($short_term_data, "THE_CHECK");
	$current_TE = ociresult($short_term_data, "THE_TE");

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
	    <font size="5" face="Verdana" color="#0066CC">Trucker Information  -  <br>    <b>Container:  <? echo $cont; ?>     <br>    BoL/Order:  <? echo $bol; ?>     <br>    Current T&E file:  <? echo $current_TE; ?></b><br>    <b>PoW Vessel#:  <? echo $vesname; ?></b>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($trucker_auth == "Y" && $update_allowed == "NONE"){
		DisplayEditPage($rfconn, $vessel, $cont, $bol);
	} else {
		DisplayViewPage($rfconn, $vessel, $cont, $bol);
	}




function DisplayEditPage($rfconn, $vessel, $cont, $bol){

	$sql = "SELECT DRIVER_NAME, DRIVER_LICENSE_NUM, DRIVER_LIC_STATE, REEFER_PLATE_NUM, REEFER_PLATE_STATE, CARGO_TYPE,
				BORDER_CROSSING, PORT_OF_ENTRY, CAN_PORT_OF_ENTRY, TRUCK_COMPANY, TRAILER_NUM, CONSIGNEE, SEAL_NUM
			FROM CANADIAN_LOAD_RELEASE CLR, CANADIAN_BORDERCROSSING CB
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND CLR.BORDER_CROSSING = CB.BORDERCROSSING_ID(+)";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	if(ociresult($short_term_data, "TRAILER_NUM") != "" || ociresult($short_term_data, "CARGO_TYPE") != "CLEMENTINE"){
		$trailer_populate = ociresult($short_term_data, "TRAILER_NUM");
	} else {
		$trailer_populate = $cont;
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="data_submit" action="canadian_scoreboard_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Consignee:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CONSIGNEE"); ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>Current:</b></font></td>
		<td><font size="2" face="Verdana"><b>New:</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Trucking Company:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCK_COMPANY"); ?></font></td>
		<td><input type="text" name="truck_company" size="50" maxlength="50" value="<? echo ociresult($short_term_data, "TRUCK_COMPANY"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Trailer Number:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo $trailer_populate; ?></font></td>
		<td><input type="text" name="trailer_num" size="20" maxlength="20" value="<? echo $trailer_populate; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Reefer Plate (Required If Swingload):</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "REEFER_PLATE_NUM"); ?></font></td>
		<td><input type="text" name="reefer_plate" size="20" maxlength="20" value="<? echo ociresult($short_term_data, "REEFER_PLATE_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Reefer Plate (State):</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "REEFER_PLATE_STATE"); ?></font></td>
		<td><input type="text" name="reefer_plate_state" size="2" maxlength="2" value="<? echo ociresult($short_term_data, "REEFER_PLATE_STATE"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver Name:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_NAME"); ?></font></td>
		<td><input type="text" name="driver_name" size="30" maxlength="100" value="<? echo ociresult($short_term_data, "DRIVER_NAME"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_LICENSE_NUM"); ?></font></td>
		<td><input type="text" name="driver_lic" size="20" maxlength="20" value="<? echo ociresult($short_term_data, "DRIVER_LICENSE_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License (State):</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_LIC_STATE"); ?></font></td>
		<td><input type="text" name="driver_lic_state" size="2" maxlength="2" value="<? echo ociresult($short_term_data, "DRIVER_LIC_STATE"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Seal #:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SEAL_NUM"); ?></font></td>
		<td><input type="text" name="seal_num" size="30" maxlength="30" value="<? echo ociresult($short_term_data, "SEAL_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Border Crossing:</font></td>
		<td width="15%"><font size="2" face="Verdana">USA-Side: <? echo ociresult($short_term_data, "PORT_OF_ENTRY"); ?><br>Canadian-Side: <? echo ociresult($short_term_data, "CAN_PORT_OF_ENTRY"); ?></font></td>
		<td><select name="border_crossing"><option value="">Please Select</option>
<?
	$sql = "SELECT BORDERCROSSING_ID, PORT_OF_ENTRY, CAN_PORT_OF_ENTRY
			FROM CANADIAN_BORDERCROSSING
			ORDER BY BORDERCROSSING_ID";
	$short_term_data2 = ociparse($rfconn, $sql);
	ociexecute($short_term_data2);
	while(ocifetch($short_term_data2)){
?>
						<option value="<? echo ociresult($short_term_data2, "BORDERCROSSING_ID"); ?>"<? if(ociresult($short_term_data2, "BORDERCROSSING_ID") == ociresult($short_term_data, "BORDER_CROSSING")){?> selected <?}?>><? echo "USA: ".ociresult($short_term_data2, "PORT_OF_ENTRY")."      Canada:".ociresult($short_term_data2, "CAN_PORT_OF_ENTRY"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Trucker Information"></td>
	</tr>
</table>
<?
}






function DisplayViewPage($rfconn, $vessel, $cont, $bol){

	$sql = "SELECT DRIVER_NAME, DRIVER_LICENSE_NUM, DRIVER_LIC_STATE, REEFER_PLATE_NUM, REEFER_PLATE_STATE, BORDER_CROSSING, PORT_OF_ENTRY, TRUCK_COMPANY
			FROM CANADIAN_LOAD_RELEASE CLR, CANADIAN_BORDERCROSSING CB
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND CLR.BORDER_CROSSING = CB.BORDERCROSSING_ID(+)";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver Name:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_NAME"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_LICENSE_NUM"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Driver License (State)::</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_LIC_STATE"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Truck License:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "REEFER_PLATE_NUM"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Truck License (State):</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "REEFER_PLATE_STATE"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Trucking Company:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCK_COMPANY"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Border Crossing:</font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PORT_OF_ENTRY"); ?></font></td>
	</tr>
</table>
<?
	}
?>