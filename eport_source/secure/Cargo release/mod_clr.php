<?
/*
*		Adam Walter, Dec 2014.
*
*		Modify a CLR line... VERY TOUCHY.
*********************************************************************************/


  
	$pagename = "mod_clr";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");



	$clr_key = $HTTP_POST_VARS['clr_key'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Changes"){
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$cust = $HTTP_POST_VARS['cust'];
		$shipline = $HTTP_POST_VARS['shipline'];
		$broker = $HTTP_POST_VARS['broker'];
		$plts = $HTTP_POST_VARS['plts'];
		$cases = $HTTP_POST_VARS['cases'];
		$comm = $HTTP_POST_VARS['comm'];
		$mode = $HTTP_POST_VARS['mode'];
		$loadtype = $HTTP_POST_VARS['loadtype'];
		$destination = $HTTP_POST_VARS['destination'];
		$wt = $HTTP_POST_VARS['wt'];
		$wt_unit = $HTTP_POST_VARS['wt_unit'];

		$sql = "SELECT CUSTOMER_NAME
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID = '".$cust."'";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$new_cons = ociresult($main_data, "CUSTOMER_NAME");

		$sql = "UPDATE CLR_MAIN_DATA
				SET BOL_EQUIV = '".$bol."',
					CONTAINER_NUM = '".$cont."',
					CUSTOMER_ID = '".$cust."',
					CONSIGNEE = '".$new_cons."',
					SHIPLINE = '".$shipline."',
					BROKER = '".$broker."',
					PLTCOUNT = '".$plts."',
					QTY = '".$cases."',
					COMMODITY_CODE = '".$comm."',
					CARGO_MODE = '".$mode."',
					LOAD_TYPE = '".$loadtype."',
					DESTINATION = '".$destination."',
					WEIGHT = '".$wt."',
					WEIGHT_UNIT = '".$wt_unit."'
				WHERE CLR_KEY = '".$clr_key."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		echo "<font color=\"#0000FF\">Entry Saved.  <a href=\"cargo_scoreboard_index.php\">Click Here</a> to return to the Main Board.</font><br>";
	}
	




?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">CLR Cargo Main Board</font>
</font>
	    <hr><font size="3" face="Verdana" color="#DD4455"><b>IMPORTANT NOTE:  YOU CAN CHANGE THESE VALUES TO ANYTHING YOU WANT, BUT CHANGES COULD LEAD TO UNEXPECTED RESULTS.</b></font>
	 </p>
      </td>
   </tr>
</table>
<?
	$sql = "SELECT * FROM CLR_MAIN_DATA
			WHERE CLR_KEY = '".$clr_key."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);

	$sql = "SELECT VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".ociresult($main_data, "ARRIVAL_NUM")."'";
	$vessel_data = ociparse($rfconn, $sql);
	ociexecute($vessel_data);
	ocifetch($vessel_data);
	$vesname = ociresult($vessel_data, "VESSEL_NAME");

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="mod_clr_index.php" method="post">
<input type="hidden" name="clr_key" value="<? echo $clr_key; ?>">
	<tr>
		<td width="15%"><font size="3" face="Verdana"><b>CLR Main Key:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $clr_key; ?></b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="3" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo ociresult($main_data, "ARRIVAL_NUM")."-".$vesname; ?></b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Container#</font></td>
		<td><input type="text" name="cont" size="20" maxlength="20" value="<? echo ociresult($main_data, "CONTAINER_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">BoL</font></td>
		<td><input type="text" name="bol" size="20" maxlength="20" value="<? echo ociresult($main_data, "BOL_EQUIV"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Consignee:&nbsp;&nbsp;</td>
		<td><select name="cust">
<?
//			WHERE CUSTOMER_ID IN
//				(SELECT CUSTOMER_ID FROM CLR_MAIN_DATA WHERE ARRIVAL_NUM = '".$vessel."')
	$sql = "SELECT CUSTOMER_ID, SUBSTR(CUSTOMER_NAME, 0, 30) THE_CUST
			FROM CUSTOMER_PROFILE
			ORDER BY CUSTOMER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"<? if(ociresult($main_data, "CUSTOMER_ID") == ociresult($short_term_data, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "THE_CUST"); ?></option>
<?
	}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Shipline:</td>
		<td><input type="text" name="shipline" size="10" maxlength="10" value="<? echo ociresult($main_data, "SHIPLINE"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Broker:&nbsp;&nbsp;</td>
		<td><input type="text" name="broker" size="60" maxlength="60" value="<? echo ociresult($main_data, "BROKER"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Pallets:&nbsp;&nbsp;</td>
		<td><input type="text" name="plts" size="10" maxlength="10" value="<? echo ociresult($main_data, "PLTCOUNT"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Cases:&nbsp;&nbsp;</td>
		<td><input type="text" name="cases" size="6" maxlength="6" value="<? echo ociresult($main_data, "QTY"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Commodity:&nbsp;&nbsp;</td>
		<td><select name="comm">
<?
		$sql = "SELECT *
				FROM COMMODITY_PROFILE
				ORDER BY COMMODITY_CODE";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>"<? if(ociresult($main_data, "COMMODITY_CODE") == ociresult($short_term_data, "COMMODITY_CODE")){?> selected <?}?>><? echo ociresult($short_term_data, "COMMODITY_CODE")."-".ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Cargo Mode:</td>
		<td><select name="mode">
							<option value="HTH"<? if(ociresult($main_data, "CARGO_MODE") == "HTH"){?> selected <?}?>>HTH</option>
							<option value="SWING"<? if(ociresult($main_data, "CARGO_MODE") == "SWING"){?> selected <?}?>>SWING</option>
							<option value="STRIP"<? if(ociresult($main_data, "CARGO_MODE") == "STRIP"){?> selected <?}?>>STRIP</option>
							<option value="FUME"<? if(ociresult($main_data, "CARGO_MODE") == "FUME"){?> selected <?}?>>FUME</option>
							<option value="PREFUMED"<? if(ociresult($main_data, "CARGO_MODE") == "PREFUMED"){?> selected <?}?>>PREFUME</option>
							<option value="PREINSPD"<? if(ociresult($main_data, "CARGO_MODE") == "PREINSPD"){?> selected <?}?>>PREINSPD</option>
							<option value="BREAKBULK"<? if(ociresult($main_data, "CARGO_MODE") == "BREAKBULK"){?> selected <?}?>>BREAKBULK</option>
				</select></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Load Type:</td>
		<td><select name="loadtype">
							<option value="FCL"<? if(ociresult($main_data, "LOAD_TYPE") == "FCL"){?> selected <?}?>>FCL</option>
							<option value="FCL/LCL"<? if(ociresult($main_data, "LOAD_TYPE") == "FCL/LCL"){?> selected <?}?>>FCL/LCL</option>
							<option value="FCL/FCL"<? if(ociresult($main_data, "LOAD_TYPE") == "FCL/FCL"){?> selected <?}?>>FCL/FCL</option>
							<option value="LCL/LCL"<? if(ociresult($main_data, "LOAD_TYPE") == "LCL/LCL"){?> selected <?}?>>LCL/LCL</option>
							<option value="LCL"<? if(ociresult($main_data, "LOAD_TYPE") == "LCL"){?> selected <?}?>>LCL</option>
							<option value="BBULK"<? if(ociresult($main_data, "LOAD_TYPE") == "BBULK"){?> selected <?}?>>BBULK</option>
							<option value="HOLD"<? if(ociresult($main_data, "LOAD_TYPE") == "HOLD"){?> selected <?}?>>HOLD</option>
				</select></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Destination:</td>
		<td><select name="destination">
							<option value="DOMESTIC"<? if(ociresult($main_data, "DESTINATION") == "DOMESTIC"){?> selected <?}?>>DOMESTIC</option>
							<option value="CANADIAN"<? if(ociresult($main_data, "DESTINATION") == "CANADIAN"){?> selected <?}?>>CANADIAN</option>
				</select></font></td>
	</tr>
<?
		$sql = "SELECT (NVL('".ociresult($main_data, "WEIGHT")."', 0) * CONVERSION_FACTOR) CONV_WEIGHT
				FROM UNIT_CONVERSION_FROM_BNI UCFB 
				WHERE PRIMARY_UOM = '".ociresult($main_data, "WEIGHT_UNIT")."'
					AND SECONDARY_UOM = 'LB'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data); 
		if(!ocifetch($short_term_data)){
			$lbs_equiv = 0;
		} else {
			$lbs_equiv = ociresult($short_term_data, "CONV_WEIGHT");
		}
?>
	<tr>
		<td><font size="2" face="Verdana">Current Weight:</font></td>
		<td><font size="2" face="Verdana"><? echo number_format(ociresult($main_data, "WEIGHT"), 2)." ".ociresult($main_data, "WEIGHT_UNIT"); ?> = <? echo number_format(round($lbs_equiv), 2); ?>&nbsp;LB, <? echo round($lbs_equiv / ociresult($main_data, "QTY"), 2); ?>&nbsp;LB/case</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Weight:</td>
		<td><input type="text" name="wt" size="10" maxlength="10" value="<? echo ociresult($main_data, "WEIGHT"); ?>">&nbsp;&nbsp;
			<select name="wt_unit">
							<option value="LB"<? if(ociresult($main_data, "WEIGHT_UNIT") == "LB"){?> selected <?}?>>LBS</option>
							<option value="KG"<? if(ociresult($main_data, "WEIGHT_UNIT") == "KG"){?> selected <?}?>>KG</option>
				</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Changes">
	</tr>
</form>
</table>