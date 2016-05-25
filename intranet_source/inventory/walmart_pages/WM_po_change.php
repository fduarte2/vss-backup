<?
/*
*  Adam Walter, Nov 2014.
*
*	Invetory page for changing WM item#'s
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Walmart PO Changearinos";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}


	$submit = $HTTP_POST_VARS['submit'];
	if($submit != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
		$pallet = $HTTP_POST_VARS['pallet'];
		$cust = $HTTP_POST_VARS['cust'];

		if($vessel == "" || $pallet == "" || $cust == ""){
			echo "<font color=\"#FF0000\">All Fields must be selected.</font><br>";
			$submit = "";
		}

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$pallet."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$vessel."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\">Pallet not found in system.</font><br>";
			$submit = "";
		}
	}
	if($submit == "Save Changes"){
		$new_wm_item_num = $HTTP_POST_VARS['new_wm_item_num'];
		$new_gro_item_num = $HTTP_POST_VARS['new_gro_item_num'];
		$new_wm_po_num = filter_input($HTTP_POST_VARS['new_wm_po_num']);
		$new_exporter = filter_input($HTTP_POST_VARS['new_exporter']);
		$new_size = filter_input($HTTP_POST_VARS['new_size']);
		$new_label = filter_input($HTTP_POST_VARS['new_label']);
		$new_var = filter_input($HTTP_POST_VARS['new_var']);
		$new_type = $HTTP_POST_VARS['new_type'];
		$new_cargo_status = $HTTP_POST_VARS['new_cargo_status'];
		$new_country = $HTTP_POST_VARS['new_country'];
		$new_packdate = $HTTP_POST_VARS['new_packdate'];
		$new_USDA_status = $HTTP_POST_VARS['new_USDA_status'];

		$validate = ValidateFields($new_packdate, $new_wm_item_num, $new_gro_item_num, $pallet, $cust, $vessel, $rfconn);

		if($validate == ""){
			if($new_packdate == ""){
				$date_sql = " SUPPLIER_PACKDATE = NULL ";
				$date_hist_sql = " NULL ";
			} else {
				$date_sql = " SUPPLIER_PACKDATE = TO_DATE('".$new_packdate."', 'MM/DD/YYYY') ";
				$date_hist_sql = " TO_DATE('".$new_packdate."', 'MM/DD/YYYY') ";
			}

			$sql = "INSERT INTO WM_ITEM_CHANGELOG
						(PALLET_ID,
						CUSTOMER_ID,
						ARRIVAL_NUM,
						OLD_MARK_WMPO,
						NEW_MARK_WMPO,
						OLD_BOL_WMITEM,
						NEW_BOL_WMITEM,
						OLD_BATCHID_GROWITEM,
						NEW_BATCHID_GROWITEM,
						OLD_EXPORTER,
						NEW_EXPORTER,
						OLD_CARGOSIZE,
						NEW_CARGOSIZE,
						OLD_REMARK_LABEL,
						NEW_REMARK_LABEL,
						OLD_VARIETY,
						NEW_VARIETY,
						OLD_CARGO_TYPE_ID,
						NEW_CARGO_TYPE_ID,
						OLD_COUNTRY_CODE,
						NEW_COUNTRY_CODE,
						OLD_SUPPLIER_PACKDATE,
						NEW_SUPPLIER_PACKDATE,
						OLD_CARGO_STATUS,
						NEW_CARGO_STATUS,
						USERNAME,
						CHANGED_ON)
					(SELECT
						CT.PALLET_ID,
						CT.RECEIVER_ID,
						CT.ARRIVAL_NUM,
						MARK,
						'".$new_wm_po_num."',
						BOL,
						'".$new_wm_item_num."',
						BATCH_ID,
						'".$new_gro_item_num."',
						EXPORTER_CODE,
						'".$new_exporter."',
						CARGO_SIZE,
						'".$new_size."',
						REMARK,
						'".$new_label."',
						VARIETY,
						'".$new_var."',
						CARGO_TYPE_ID,
						'".$new_type."',
						COUNTRY_CODE,
						'".$new_country."',
						SUPPLIER_PACKDATE,
						".$date_hist_sql.",
						CARGO_STATUS,
						'".$new_cargo_status."',
						'".$user."',
						SYSDATE
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = '".$pallet."'
						AND CT.RECEIVER_ID = '".$cust."'
						AND CT.ARRIVAL_NUM = '".$vessel."')";
//			echo $sql."<br>";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$sql = "UPDATE CARGO_TRACKING
					SET BOL = '".$new_wm_item_num."',
						MARK = '".$new_wm_po_num."',
						BATCH_ID = '".$new_gro_item_num."',
						EXPORTER_CODE = '".$new_exporter."',
						CARGO_SIZE = '".$new_size."',
						REMARK = '".$new_label."',
						VARIETY = '".$new_var."',
						CARGO_STATUS = '".$new_cargo_status."',
						CARGO_TYPE_ID = '".$new_type."'
					WHERE PALLET_ID = '".$pallet."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$vessel."'";
//			echo $sql."<br>";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
					SET COUNTRY_CODE = '".$new_country."',
						USDA_HOLD = '".$new_USDA_status."',
						".$date_sql."
					WHERE PALLET_ID = '".$pallet."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$vessel."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
						
			echo "<font color=\"#0000FF\">Pallet Update Saved.</font><br>";
		} else {
			echo "<font color=\"#FF0000\">".$validate."</font>";
		}
	}
				
		

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Attributes change</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form action="WM_po_change.php" method="post" name="the_upload">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<input type="hidden" name="cust" value="3000">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vessel:</font></td>
		<td><select name="vessel"><option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
				WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT')
				AND (LR_NUM = '4321' OR LR_NUM > '12540')
			ORDER BY LR_NUM";
//				WHERE SHIP_PREFIX = 'CHILEAN'
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "LR_NUM")."-".ociresult($short_term_data, "VESSEL_NAME"); ?></option>
<?
	}
?>
				</select></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Pallet ID:</font></td>
		<td><input type="text" name="pallet" size="32" maxlength="32" value="<? echo $pallet; ?>">
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customer:</font></td>
		<td><font size="2" face="Verdana">3000 - Walmart</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="0" cellpadding="4" cellspacing="0">
<form name="stuff" action="WM_po_change.php" method="post">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="pallet" value="<? echo $pallet; ?>">
	<tr>
		<td><font size="2" face="Verdana"><b>Value</b></font></td>
		<td><font size="2" face="Verdana"><b>Current</b></font></td>
		<td><font size="2" face="Verdana"><b>New</b></font></td>
	</tr>
<?


	$sql = "SELECT BOL, MARK, COMMODITY_NAME, CT.COMMODITY_CODE, CARGO_DESCRIPTION, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NA') DATE_REC, QTY_RECEIVED, WAREHOUSE_LOCATION, QTY_IN_HOUSE,
				BATCH_ID, EXPORTER_CODE, CARGO_SIZE, REMARK, VARIETY, CARGO_TYPE_ID, COUNTRY_CODE, TO_CHAR(SUPPLIER_PACKDATE, 'MM/DD/YYYY') THE_PACK, NVL(CARGO_STATUS, 'Good') THE_STAT, NVL(USDA_HOLD, 'NONE') THE_USDA
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, COMMODITY_PROFILE COMP
				WHERE CT.PALLET_ID = '".$pallet."'
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE";
	$pallet_data = ociparse($rfconn, $sql);
	ociexecute($pallet_data);
	ocifetch($pallet_data);
//	$item_num = ociresult($short_term_data, "BOL");
//	$po_num = ociresult($short_term_data, "MARK");
?>
	<tr>
		<td><font size="2" face="Verdana">Commodity</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "COMMODITY_CODE")."-".ociresult($pallet_data, "COMMODITY_NAME"); ?></font></td>
		<td rowspan="5"><font size="2" face="Verdana">For any changes<br>to these values,<br>Please use the<br>Pallet Correction Screen<br>before this one.</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Description</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "CARGO_DESCRIPTION"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Received Date</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "DATE_REC"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Warehouse Location</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "WAREHOUSE_LOCATION"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">QTY In House</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "QTY_IN_HOUSE"); ?></font></td>
	</tr>
	<tr>
		<td colspan="3"><br><br></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">WM Item#</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "BOL"); ?></font></td>
		<td><select name="new_wm_item_num"><option value="">Please Select</option>
<?
	$sql = "SELECT ITEM_NUM, WM_COMMODITY_NAME
			FROM WM_ITEM_COMM_MAP
				WHERE COMMODITY_CODE = '".ociresult($pallet_data, "COMMODITY_CODE")."'
			ORDER BY ITEM_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "ITEM_NUM"); ?>"<? if(ociresult($short_term_data, "ITEM_NUM") == ociresult($pallet_data, "BOL")){?> selected <?}?>>
							<? echo ociresult($short_term_data, "ITEM_NUM")." - ".ociresult($short_term_data, "WM_COMMODITY_NAME"); ?></option>
<?
	}
?>
						</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Grower Item#</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "BATCH_ID"); ?></font></td>
		<td><input type="text" name="new_gro_item_num" size="10" maxlength="10" value="<? echo ociresult($pallet_data, "BATCH_ID"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Receiving PO#</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "MARK"); ?></font></td>
		<td><input type="text" name="new_wm_po_num" size="10" maxlength="10" value="<? echo ociresult($pallet_data, "MARK"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Exporter</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "EXPORTER_CODE"); ?></font></td>
		<td><input type="text" name="new_exporter" size="20" maxlength="20" value="<? echo ociresult($pallet_data, "EXPORTER_CODE"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Size Code</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "CARGO_SIZE"); ?></font></td>
		<td><input type="text" name="new_size" size="6" maxlength="6" value="<? echo ociresult($pallet_data, "CARGO_SIZE"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Label Code</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "REMARK"); ?></font></td>
		<td><input type="text" name="new_label" size="20" maxlength="20" value="<? echo ociresult($pallet_data, "REMARK"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Variety</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "VARIETY"); ?></font></td>
		<td><input type="text" name="new_var" size="20" maxlength="20" value="<? echo ociresult($pallet_data, "VARIETY"); ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Cargo Type</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "CARGO_TYPE_ID"); ?></font></td>
		<td><select name="new_type"><option value="">Please Select</option>
<?
	$sql = "SELECT *
			FROM WM_CARGO_TYPE
			ORDER BY CARGO_TYPE_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CARGO_TYPE_ID"); ?>"<? if(ociresult($short_term_data, "CARGO_TYPE_ID") == ociresult($pallet_data, "CARGO_TYPE_ID")){?> selected <?}?>>
							<? echo ociresult($short_term_data, "CARGO_TYPE_ID")." - ".ociresult($short_term_data, "WM_PROGRAM"); ?></option>
<?
	}
?>
						</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Walmart-Pallet Status</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "THE_STAT"); ?></font></td>
		<td><select name="new_cargo_status"><option value="">Good</option>
					<option value="REJECTED"<? if("REJECTED" == ociresult($pallet_data, "THE_STAT")){?> selected <?}?>>REJECTED</option>
					<option value="A-Q"<? if("A-Q" == ociresult($pallet_data, "THE_STAT")){?> selected <?}?>>A-Q</option>
					<option value="A-C"<? if("A-C" == ociresult($pallet_data, "THE_STAT")){?> selected <?}?>>A-C</option>
					<option value="HOLD"<? if("HOLD" == ociresult($pallet_data, "THE_STAT")){?> selected <?}?>>HOLD</option>
				</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">USDA Status</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "THE_USDA"); ?></font></td>
		<td><font size="2" face="Verdana"><input type="radio" name="new_USDA_status" value=""<? if(ociresult($pallet_data, "THE_USDA") == "NONE"){?> checked <?}?>>&nbsp;NONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="new_USDA_status" value="Y"<? if(ociresult($pallet_data, "THE_USDA") == "Y"){?> checked <?}?>>&nbsp;HELD BY USDA
		</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Country Code</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "COUNTRY_CODE"); ?></font></td>
		<td><select name="new_country"><option value="">Please Select</option>
<?
	$sql = "SELECT COUNTRY_CODE, COUNTRY_NAME
			FROM COUNTRY
			ORDER BY COUNTRY_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COUNTRY_CODE"); ?>"<? if(ociresult($short_term_data, "COUNTRY_CODE") == ociresult($pallet_data, "COUNTRY_CODE")){?> selected <?}?>>
							<? echo ociresult($short_term_data, "COUNTRY_CODE")." - ".ociresult($short_term_data, "COUNTRY_NAME"); ?></option>
<?
	}
?>
						</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Supplier Packdate</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pallet_data, "THE_PACK"); ?></font></td>
		<td><input type="text" name="new_packdate" size="10" maxlength="10" value="<? echo ociresult($pallet_data, "THE_PACK"); ?>"></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Changes"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");




function ValidateFields($new_packdate, $new_wm_item_num, $new_gro_item_num, $pallet, $cust, $vessel, $rfconn){
	if($new_packdate != "" && !ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $new_packdate)){
		return "Supplier Packdate not in MM/DD/YYYY format.<br>";
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM WM_ITEMNUM_MAPPING
			WHERE ITEM_NUM = '".$new_gro_item_num."'
				AND WM_ITEM_NUM = '".$new_wm_item_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") <= 0){
		return "Grower# not valid for the selected WM#.<br>";
	}

	$sql = "SELECT QTY_IN_HOUSE 
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$pallet."'
				AND RECEIVER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "QTY_IN_HOUSE") <= 10){
		return "Pallet no longer in-House.  Cannot change specs.<br>";
	}
}

function filter_input($input){
	$return = $input;
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);
	$return = strtoupper($return);

	return $return;
}