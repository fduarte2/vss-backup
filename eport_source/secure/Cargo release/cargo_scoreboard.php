<?
/*
*	Adam Walter, Sep 2013.
*
*	splash page showing release status of containers.
*************************************************************************/

	$pagename = "clr_main_board";  
	include("cargo_db_def.php");


//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$submit = $HTTP_POST_VARS['submit'];
	$clr_key = $HTTP_POST_VARS['clr_key'];
	if($submit == "Delete" && $clr_key != ""){
		$sql = "SELECT BOL_EQUIV, ARRIVAL_NUM, CONTAINER_NUM
				FROM CLR_MAIN_DATA
				WHERE CLR_KEY = '".$clr_key."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE BOL = '".ociresult($short_term_data, "BOL_EQUIV")."'
					AND NVL(CONTAINER_ID, 'NC') = '".ociresult($short_term_data, "CONTAINER_NUM")."'
					AND ARRIVAL_NUM = '".ociresult($short_term_data, "ARRIVAL_NUM")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);

//		if(ociresult($short_term_data, "THE_COUNT") >= 1){
//			echo "<font color=\"#FF0000\">Cannot Delete Line; Cargo with this BoL/Container/ARV# is found in our system.</font><br>";
//		} else {
			$sql = "DELETE FROM CLR_MAIN_DATA
					WHERE CLR_KEY = '".$clr_key."'";
//			echo $sql;
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);

			echo "<font color=\"#0000FF\">Cargo Line ".$clr_key." Deleted.</font><br>";
//		}
	}

	$sql_filter = "";

	if($submit == "Retrieve / Refresh"){
		$ams_filter_cmd = $HTTP_POST_VARS['ams_filter_cmd'];
		$line_filter_cmd = $HTTP_POST_VARS['line_filter_cmd'];
		$dest_filter_cmd = $HTTP_POST_VARS['dest_filter_cmd'];
		$final_filter_cmd = $HTTP_POST_VARS['final_filter_cmd'];
		$cont_filter_cmd = $HTTP_POST_VARS['cont_filter_cmd'];
		$cons_filter_cmd = $HTTP_POST_VARS['cons_filter_cmd'];
		$bol_filter_cmd = $HTTP_POST_VARS['bol_filter_cmd'];
		$shipline_filter_cmd = $HTTP_POST_VARS['shipline_filter_cmd'];
		$broker_filter_cmd = $HTTP_POST_VARS['broker_filter_cmd'];
		$comm_filter_cmd = $HTTP_POST_VARS['comm_filter_cmd'];
		$mode_filter_cmd = $HTTP_POST_VARS['mode_filter_cmd'];
		$load_filter_cmd = $HTTP_POST_VARS['load_filter_cmd'];
		$containers_only_cmd = $HTTP_POST_VARS['containers_only_cmd'];
	} else {
		$ams_filter_cmd = $HTTP_COOKIE_VARS['ams_filter_cmd'];
		$line_filter_cmd = $HTTP_COOKIE_VARS['line_filter_cmd'];
		$dest_filter_cmd = $HTTP_COOKIE_VARS['dest_filter_cmd'];
		$final_filter_cmd = $HTTP_COOKIE_VARS['final_filter_cmd'];
		$cont_filter_cmd = $HTTP_COOKIE_VARS['cont_filter_cmd'];
		$cons_filter_cmd = $HTTP_COOKIE_VARS['cons_filter_cmd'];
		$bol_filter_cmd = $HTTP_COOKIE_VARS['bol_filter_cmd'];
		$shipline_filter_cmd = $HTTP_COOKIE_VARS['shipline_filter_cmd'];
		$broker_filter_cmd = $HTTP_COOKIE_VARS['broker_filter_cmd'];
		$comm_filter_cmd = $HTTP_COOKIE_VARS['comm_filter_cmd'];
		$mode_filter_cmd = $HTTP_COOKIE_VARS['mode_filter_cmd'];
		$load_filter_cmd = $HTTP_COOKIE_VARS['load_filter_cmd'];
		$containers_only_cmd = $HTTP_COOKIE_VARS['containers_only_cmd'];
	}


	if($ams_filter_cmd == "HOLD"){
		$sql_filter .= " AND AMS_RELEASE IS NULL";
	} elseif($ams_filter_cmd == "RELEASE"){
		$sql_filter .= " AND AMS_RELEASE IS NOT NULL";
	}
	if($line_filter_cmd == "HOLD"){
		$sql_filter .= " AND LINE_RELEASE IS NULL";
	} elseif($line_filter_cmd == "RELEASE"){
		$sql_filter .= " AND LINE_RELEASE IS NOT NULL";
	}
	if($dest_filter_cmd != ""){
		$sql_filter .= " AND DESTINATION = '".$dest_filter_cmd."'";
	}
	if($final_filter_cmd == "HOLD"){
		$sql_filter .= " AND (BROKER_RELEASE IS NULL OR AMS_RELEASE IS NULL OR LINE_RELEASE IS NULL)";
	} elseif($final_filter_cmd == "RELEASE"){
		$sql_filter .= " AND (BROKER_RELEASE IS NOT NULL AND AMS_RELEASE IS NOT NULL AND LINE_RELEASE IS NOT NULL)";
	}
	if($cont_filter_cmd != ""){
		$sql_filter .= " AND CONTAINER_NUM LIKE '%".$cont_filter_cmd."%'";
	}
	if($cons_filter_cmd != ""){
		$sql_filter .= " AND CMD.CUSTOMER_ID = '".$cons_filter_cmd."'";
	}
	if($bol_filter_cmd != ""){
		$sql_filter .= " AND BOL_EQUIV LIKE '%".$bol_filter_cmd."%'";
	}
	if($shipline_filter_cmd != ""){
		$sql_filter .= " AND SHIPLINE = '".$shipline_filter_cmd."'";
	}
	if($broker_filter_cmd != ""){
		$sql_filter .= " AND BROKER LIKE '%".$broker_filter_cmd."%'";
	}
	if($comm_filter_cmd != ""){
		$sql_filter .= " AND COMMODITY LIKE '%".$comm_filter_cmd."%'";
	}
	if($mode_filter_cmd != ""){
		$sql_filter .= " AND CARGO_MODE = '".$mode_filter_cmd."'";
	}
	if($load_filter_cmd != ""){
		$sql_filter .= " AND LOAD_TYPE = '".$load_filter_cmd."'";
	}
	if($containers_only_cmd == "Y"){
		$sql_filter .= " AND CONTAINER_NUM != 'NC'";
	}

	$sql_filter .= CustV2UserCheck($user, "CMD.CUSTOMER_ID", $rfconn);
	$sql_filter .= ShipLineV2UserCheck($user, "CMD.SHIPLINE", $rfconn);

	if($vessel == ""){
		$vessel = $HTTP_GET_VARS['vessel']; // just incase they got back here via hyperlink
	}

?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">CLR Cargo Main Board</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?> 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="cargo_scoreboard_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td colspan="10"><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CLEMENTINES', 'CHILEAN', 'ARG FRUIT', 'ARG JUICE')
					AND ARRIVAL_NUM IN
						(SELECT ARRIVAL_NUM FROM CLR_MAIN_DATA)
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
		<td width="10%"><font size="2" face="Verdana"><b>AMS Release:</b>&nbsp;&nbsp;</td>
		<td><select name="ams_filter_cmd">
					<option value="">All</option>
					<option value="HOLD"<? if($ams_filter_cmd == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($ams_filter_cmd == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Line Release:</b>&nbsp;&nbsp;</td>
		<td><select name="line_filter_cmd">
					<option value="">All</option>
					<option value="HOLD"<? if($line_filter_cmd == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($line_filter_cmd == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Destination:</b>&nbsp;&nbsp;</td>
		<td><select name="dest_filter_cmd">
					<option value="">All</option>
					<option value="CANADIAN"<? if($dest_filter_cmd == "CANADIAN"){?> selected <?}?>>CANADIAN</option>
					<option value="DOMESTIC"<? if($dest_filter_cmd == "DOMESTIC"){?> selected <?}?>>DOMESTIC</option>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Final Release:</b>&nbsp;&nbsp;</td>
		<td><select name="final_filter_cmd">
					<option value="">All</option>
					<option value="HOLD"<? if($final_filter_cmd == "HOLD"){?> selected <?}?>>On Hold</option>
					<option value="RELEASE"<? if($final_filter_cmd == "RELEASE"){?> selected <?}?>>Released</option>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Broker:</b>&nbsp;&nbsp;</td>
		<td><nobr><input type="text" name="broker_filter_cmd" size="20" maxlength="60" value="<? echo $broker_filter_cmd; ?>"><font size="1" face="Verdana">&nbsp;&nbsp;(Partial OK)</nobr></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>BoL:</b>&nbsp;&nbsp;</td>
		<td><nobr><input type="text" name="bol_filter_cmd" size="20" maxlength="20" value="<? echo $bol_filter_cmd; ?>"><font size="1" face="Verdana">&nbsp;&nbsp;(Partial OK)</nobr></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Consignee:</b>&nbsp;&nbsp;</td>
		<td><select name="cons_filter_cmd"><option value="">All</option>
<?
		$sql = "SELECT CUSTOMER_ID, SUBSTR(CUSTOMER_NAME, 0, 25) THE_CUST
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID IN
					(SELECT CUSTOMER_ID FROM CLR_MAIN_DATA WHERE ARRIVAL_NUM = '".$vessel."')
				ORDER BY CUSTOMER_ID";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"<? if($cons_filter_cmd == ociresult($short_term_data, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "THE_CUST"); ?></option>
<?
		}
?>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Shipline:</b></td>
		<td><select name="shipline_filter_cmd"><option value="">All</option>
<?
		$sql = "SELECT DISTINCT SHIPLINE
				FROM CLR_MAIN_DATA
				WHERE ARRIVAL_NUM = '".$vessel."'
				ORDER BY SHIPLINE";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "SHIPLINE"); ?>"<? if($shipline_filter_cmd == ociresult($short_term_data, "SHIPLINE")){?> selected <?}?>><? echo ociresult($short_term_data, "SHIPLINE"); ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Container ID:</b>&nbsp;&nbsp;</td>
		<td><nobr><input type="text" name="cont_filter_cmd" size="20" maxlength="20" value="<? echo $cont_filter_cmd; ?>"><font size="1" face="Verdana">&nbsp;&nbsp;(Partial OK)</nobr></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Commodity:</b>&nbsp;&nbsp;</td>
		<td><nobr><input type="text" name="comm_filter_cmd" size="20" maxlength="45" value="<? echo $comm_filter_cmd; ?>"><font size="1" face="Verdana">&nbsp;&nbsp;(Partial OK)</nobr></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Cargo Mode:</b></td>
		<td><select name="mode_filter_cmd"><option value="">All</option>
<?
		$sql = "SELECT DISTINCT CARGO_MODE
				FROM CLR_MAIN_DATA
				WHERE ARRIVAL_NUM = '".$vessel."'
				ORDER BY CARGO_MODE";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CARGO_MODE"); ?>"<? if($mode_filter_cmd == ociresult($short_term_data, "CARGO_MODE")){?> selected <?}?>><? echo ociresult($short_term_data, "CARGO_MODE"); ?></option>
<?
		}
?>
				</select></font></td>
		<td width="5%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana"><b>Load Type:</b></td>
		<td><select name="load_filter_cmd"><option value="">All</option>
<?
		$sql = "SELECT DISTINCT LOAD_TYPE
				FROM CLR_MAIN_DATA
				WHERE ARRIVAL_NUM = '".$vessel."'
				ORDER BY LOAD_TYPE";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "LOAD_TYPE"); ?>"<? if($load_filter_cmd == ociresult($short_term_data, "LOAD_TYPE")){?> selected <?}?>><? echo ociresult($short_term_data, "LOAD_TYPE"); ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Containers Only?:</b>&nbsp;&nbsp;</td>
		<td><input type="checkbox" name="containers_only_cmd" value="Y"<? if($containers_only_cmd == "Y"){?> checked <?}?>></td>
		<td colspan="9">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="11"><input type="submit" name="submit" value="Retrieve / Refresh"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>CARGO ID#</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CONTAINER#</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CONSIGNEE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>BOL</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>SHIP LINE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>BROKER</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>PALLETS</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CASES</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>COMMODITY</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>MODE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>LOAD TYPE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>DESTINATION</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>AMS RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="line_ship_release_index?vessel=<? echo $vessel; ?>">LINE RELEASE</a></b></font></td>
<!--		<td align="center"><font size="2" face="Verdana"><b>BROKER RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>T&E FILE</b></font></td> !-->
		<td align="center"><font size="2" face="Verdana"><b>FINAL RELEASE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>TRUCKER LINKS</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>ACTIONS</b></font></td>
	</tr>
<?
	$total_plts = 0;
	$total_cs = 0;
	$sql = "SELECT NVL(CONTAINER_NUM, 'NC (Break Bulk)') THE_CONT, CUSTOMER_NAME, BOL_EQUIV, PLTCOUNT, QTY, COMMODITY_NAME, CARGO_MODE, CMD.CUSTOMER_ID, BROKER, LOAD_TYPE, SHIPLINE,
				DECODE(AMS_RELEASE, NULL, 'ON HOLD', TO_CHAR(AMS_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
				DECODE(LINE_RELEASE, NULL, 'ON HOLD', TO_CHAR(LINE_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
				DECODE(BROKER_RELEASE, NULL, 'ON HOLD', TO_CHAR(BROKER_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_BROKER,
				NVL(TO_CHAR(GREATEST(AMS_RELEASE, LINE_RELEASE, BROKER_RELEASE), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL,
				NVL(T_E_FILE, 'NONE') THE_TE, T_E_NUM, CLR_KEY, DESTINATION
			FROM CLR_MAIN_DATA CMD, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CMD.CUSTOMER_ID = CUSP.CUSTOMER_ID(+)
				AND CMD.COMMODITY_CODE = COMP.COMMODITY_CODE(+)
				".$sql_filter."
			ORDER BY BOL_EQUIV, NVL(CONTAINER_NUM, 'NC (Break Bulk)')";
/*	$sql = "SELECT CONSIGNEE, NVL(T_AND_E, 'NONE') THE_TE, T_AND_E_DISPLAY, CONTAINER_NUM, BOL, CARGO_MODE, FINAL_PORT, OCEAN_BOL, CASES, PALLET_COUNT, GATEPASS_NUM, CARGO_TYPE, COMMODITY,
				DECODE(AMS_STATUS, NULL, 'ON HOLD', TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
				DECODE(LINE_STATUS, NULL, 'ON HOLD', TO_CHAR(LINE_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
				DECODE(BROKER_STATUS, NULL, 'ON HOLD', TO_CHAR(BROKER_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_BROKER,
				NVL(TO_CHAR(GREATEST(AMS_STATUS, BROKER_STATUS, LINE_STATUS), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL,
				DECODE(CBP_FAX, NULL, 'NOT SENT', TO_CHAR(CBP_FAX, 'MM/DD/YYYY HH:MI AM')) THE_CBP_FAX,
				NVL(TO_CHAR(GATEPASS_PDF_DATE), 'NONE') GATEPASS_CHECK, MFSTD
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				".$sql_filter."
			ORDER BY GATEPASS_NUM DESC NULLS FIRST, CONSIGNEE, CONTAINER_NUM, BOL"; */
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		switch(ociresult($short_term_data, "THE_FINAL")){
			case "ON HOLD":
				$bgcolorfinal="#FFCCCC";
			break;
			default:
				$bgcolorfinal="#CCFFCC";
			break;
		}
/*		switch(ociresult($short_term_data, "THE_CBP_FAX")){
			case "NOT SENT":
				$bgcolorfax="#FFFFCC";
			break;
			default:
				$bgcolorfax="#CCFFCC";
			break;
		} */

		$total_plts += ociresult($short_term_data, "PLTCOUNT");
		$total_cs += ociresult($short_term_data, "QTY");
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CLR_KEY"); ?></font></td> 
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CONT"); PrePrepTruck(ociresult($short_term_data, "CLR_KEY"), $user, $rfconn); ?></font></td> 
		<td><font size="2" face="Verdana"><? echo substr(ociresult($short_term_data, "CUSTOMER_NAME"), 0, 25); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BOL_EQUIV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SHIPLINE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BROKER"); ?></font></td>
<!--		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "OCEAN_BOL"); ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PLTCOUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "QTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CARGO_MODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "LOAD_TYPE"); ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "MFSTD"); ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DESTINATION"); ?></font></td>
		<? DisplayRelease("ams", ociresult($short_term_data, "THE_AMS"), ociresult($short_term_data, "CLR_KEY"), $rfconn); ?>
		<? DisplayRelease("line", ociresult($short_term_data, "THE_LINE"), ociresult($short_term_data, "CLR_KEY"), $rfconn); ?>
		<? //DisplayRelease("ohl", ociresult($short_term_data, "THE_BROKER"), ociresult($short_term_data, "CLR_KEY"), $rfconn); ?>
<?
	//	if(ociresult($short_term_data, "THE_TE") != "NONE"){
?>
		<!-- <td bgcolor="#CCFFCC"><font size="2" face="Verdana">
				<a href="./upload_clr_t_e/<? echo ociresult($short_term_data, "THE_TE"); ?>"><? echo ociresult($short_term_data, "T_E_NUM"); ?></a></font></td> !-->
<?
		//} else {
?>
		<!--<td bgcolor="#FFFFCC"><font size="2" face="Verdana">None</font></td> !-->
<?
	//	}
?>
		<td bgcolor="<? echo $bgcolorfinal; ?>"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_FINAL"); ?></font></td>
		<td><? DisplayTruckLink(ociresult($short_term_data, "CLR_KEY"), $rfconn); ?></td> 
		<? echo DisplayActionButtons(ociresult($short_term_data, "CLR_KEY"), $security_allowance); ?>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="6"><font size="2" face="Verdana"><b>Totals:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_plts; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_cs; ?></b></font></td>
		<td colspan="9">&nbsp;</td>
	</tr>
</table>







<?

//function GetAMSBGC($cont, $bol, $vessel, $rfconn){
function GetAMSBGC($key_id, $rfconn){
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_AMS_RELEASE CAR
			WHERE CAR.CLR_KEY = '".$key_id."'
				AND CAR.EDI_CODE_TYPE IN ('HOLD')
				AND CAR.APPLIED_TO_CLR IS NOT NULL
				AND CAR.HOLD_CLEARED_ON IS NULL";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$count = ociresult($short_term_data, "THE_COUNT");

	if($count == 0){
		return "#FFFFCC";
	} else {
		// uncleared holds.  turn this RED.
		return "#FFCCCC";
	}
}



//function DisplayRelease($column, $datetime, $cont, $bol, $vessel, $rfconn){
function DisplayRelease($column, $datetime, $key_id, $rfconn){
	switch($column){
		case "ams":
			$pagename = "ams_release_hist_index.php";
		break;
		case "line":
			$pagename = "line_release_hist_index.php";
		break;
		case "ohl":
			$pagename = "broker_release_hist_index.php";
		break;
	}

	switch($datetime){
		case "ON HOLD":
			if($column == "ams"){
				$bgcolor = GetAMSBGC($key_id, $rfconn);
			} else {
				$bgcolor="#FFFFCC";
			}
			$released = "";
		break;
		default:
			$bgcolor="#CCFFCC";
			$released = "Released";
		break;
	}


	// authorizations can edit this "if" clause if needed
	if(true){
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $released; ?><br><a href="<? echo $pagename; ?>?key=<? echo $key_id; ?>"><? echo $datetime; ?></a>
<!--		<br>
		<a href="release_status_history_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>&type=<? echo strtoupper($column); ?>">Status Change History</a></font></td> !-->
<?
	} else {
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $datetime; ?></font></td>
<?
	}
}

function DisplayTrucker($rfconn, $cont, $bol, $vessel){
	$sql = "SELECT NVL(TO_CHAR(TRUCKER_TIME_ENTER, 'MM/DD/YYYY HH:MI AM'), 'NONE') THE_TIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_TIME") == "NONE"){
		$display = "None";
		$bgcolor = "#FFFFCC";
	} else {
		$display = ociresult($short_term_data, "THE_TIME");
		$bgcolor = "#CCFFCC";
	}
?>

		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="trucker_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
}



function DisplayGatePass($rfconn, $cont, $bol, $vessel, $column){
	$sql = "SELECT NVL(".$column.", 'NONE') THE_CHECK, NVL(TO_CHAR(GATEPASS_PDF_DATE), 'NONE') THE_BGCOLOR_CHECK
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_CHECK") == "NONE"){
		$display = "None";
	} else {
		$display = ociresult($short_term_data, "THE_CHECK");
	}
	if(ociresult($short_term_data, "THE_BGCOLOR_CHECK") == "NONE"){
		$bgcolor = "#FFFFCC";
	} else {
		$bgcolor = "#CCFFCC";
	}

	$sql = "SELECT NVL(TO_CHAR(SEAL_TIME, 'MM/DD/YYYY'), 'NONE') THE_SEALTIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_SEALTIME") != "NONE"){
?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="seal_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
	} else {
?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><? echo $display; ?></td>
<?
	}
}


function DisplaySeal($rfconn, $cont, $bol, $vessel, $column){
	$sql = "SELECT NVL(".$column.", 'NONE') THE_CHECK, NVL(TO_CHAR(SEAL_TIME, 'MM/DD/YYYY'), 'NONE') THE_SEALTIME
			FROM CANADIAN_LOAD_RELEASE CLR
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_CHECK") == "NONE"){
		$display = "None";
		$bgcolor = "#FFFFCC";
	} else {
		$display = ociresult($short_term_data, "THE_CHECK");
		if(ociresult($short_term_data, "THE_SEALTIME") == "NONE"){
			$bgcolor = "#FFFFCC";
		} else {
			$bgcolor = "#CCFFCC";
		}
	}

?>
		<td bgcolor=<? echo $bgcolor; ?>><font size="2" face="Verdana"><a href="seal_info_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $display; ?></a></td>
<?
}

function DisplayActions($rfconn, $cont, $bol, $vessel, $delete_auth, $general_edit_auth){
	$sql = "SELECT NVL(TO_CHAR(CBP_FAX, 'MM/DD/YYYY'), 'NONE') THE_CHECK
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
//	echo "cookie: ".$HTTP_COOKIE_VARS["eport_user_delete_auth"];
?>
	<td>
<?
	if(ociresult($short_term_data, "THE_CHECK") == "NONE" && $delete_auth == "Y"){
?>
		<font size="2" face="Verdana"><a href="general_override_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>">Delete</a><br></font>
<?
	} else {
		// nothing
	}
	if(ociresult($short_term_data, "THE_CHECK") == "NONE" && $general_edit_auth == "Y"){
?>
		<font size="2" face="Verdana"><a href="general_edit_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>">Edit</a><br></font>
<?
	} else {
		// nothing
	}
?>
	&nbsp;</td>
<?
}





function SendHoldMail($release_type, $cont, $vessel, $bol, $comments, $user, $rf_conn){
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CANHOLD".$release_type."'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $cont, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $cont."\r\n", $body);
	$body = str_replace("_1_", $vessel."\r\n", $body);
	$body = str_replace("_2_", $bol."\r\n", $body);
	$body = str_replace("_3_", $user."\r\n", $body);
	$body = str_replace("_4_", $comments."\r\n", $body);

//	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'INSTANTCRON',
					SYSDATE,
					'EMAIL',
					'CANHOLD".$release_type."',
					SYSDATE,
					'PENDING',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."',
					'".$cont."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
//	}
}



function DisplayMode($mode, $type, $auth, $gatepass_check, $cont, $bol, $vessel){
// only display a hyperlink if:
	// this is Chilean
	if($type == "CHILEAN"){
		// user is authorized
		if($auth == "Y"){
			// gatepass hasnt yet been printed
			if($gatepass_check == "NONE"){
?>
		<td><font size="2" face="Verdana"><a href="mode_change_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $mode; ?></a>
<?
				return;
			}
		}
	}

	// if the above didn't happen, just display.
?>
		<td><font size="2" face="Verdana"><? echo $mode; ?></font></td>
<?
}

function DisplayContainer($rfconn, $include_auth, $gatepass_check, $cont, $bol, $vessel){
	if($include_auth == "Y" && $gatepass_check == "NONE"){
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><a href="edit_entry_index.php?vessel=<? echo $vessel; ?>&cont=<? echo $cont; ?>&bol=<? echo $bol; ?>"><? echo $cont; ?></a>
<?
	} else {
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $cont; ?></font></td>
<?
	}
}

function DisplayTruckLink($cargo_ID, $rfconn){
	$sql = "SELECT TRUCK_PORT_ID
			FROM CLR_TRUCK_MAIN_JOIN
			WHERE MAIN_CLR_KEY = '".$cargo_ID."'
				ORDER BY TRUCK_PORT_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		echo "<font size=\"2\" face=\"Verdana\">No Trucks Currently Attached.</font></td>";
	} else {
		do {
			echo "<font size=\"2\" face=\"Verdana\"><a href=\"truck_scoreboard_index.php?truck_ID=".ociresult($short_term_data, "TRUCK_PORT_ID")."\">".ociresult($short_term_data, "TRUCK_PORT_ID")."</a></font> ";
		} while(ocifetch($short_term_data));
	}
}

function DisplayActionButtons($key_id, $security_allowance){
	$return = "<td>&nbsp;";
	if(strpos($security_allowance, "M") !== false){
		$return .= "<form name=\"mod\" action=\"mod_clr_index.php\" method=\"post\"><input type=\"hidden\" name=\"clr_key\" value=\"".$key_id."\">
									<input type=\"submit\" name=\"submit\" value=\"Modify\"></form>";
	}
	if(strpos($security_allowance, "D") !== false){
		$return .= "<form name=\"del\" action=\"cargo_scoreboard_index.php\" method=\"post\"><input type=\"hidden\" name=\"clr_key\" value=\"".$key_id."\">
									<input type=\"submit\" name=\"submit\" value=\"Delete\"></form>";
	}

	$return .= "</td>";

	return $return;
}
/*
		 DisplayContainer($rfconn, $include_auth, ociresult($short_term_data, "GATEPASS_CHECK"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); 
		<? DisplayMode(ociresult($short_term_data, "CARGO_MODE"), ociresult($short_term_data, "CARGO_TYPE"), $mode_auth, ociresult($short_term_data, "GATEPASS_CHECK"), ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); 
		 DisplayTrucker($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel); 
		 DisplayGatePass($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, "GATEPASS_NUM"); 
		 DisplaySeal($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, "SEAL_NUM"); 
		<td bgcolor="<? echo $bgcolorfax; ?>"><font size="2" face="Verdana"> echo ociresult($short_term_data, "THE_CBP_FAX"); </font></td>
		 DisplayActions($rfconn, ociresult($short_term_data, "CONTAINER_NUM"), ociresult($short_term_data, "BOL"), $vessel, $delete_auth, $general_edit_auth); 
*/

function PrePrepTruck($key_id, $user, $rfconn){
	$sql = "SELECT PERMISSION_LIST
			FROM EPORT_LOGIN_PAGEPERMISSIONS
			WHERE USERNAME = '".$user."'
				AND PAGE_NAME = 'truck_check_in'";
	$security = ociparse($rfconn, $sql);
	ociexecute($security);
	if(!ocifetch($security) || ociresult($security, "PERMISSION_LIST") == ""){
		$security_allowance = "N";
	} else {
		$security_allowance = ociresult($security, "PERMISSION_LIST");
	}

	if(strpos($security_allowance, "M") !== false){
		// they have truck checkin access.
?>
	<form name="maketruck<? echo $key_id; ?>" action="truck_release_index.php" method="post">
	<input type="hidden" name="clr_key" value="<? echo $key_id; ?>">
	<input type="submit" name="submit" value="Make Truck">
	</form>
<?
	}
}
	