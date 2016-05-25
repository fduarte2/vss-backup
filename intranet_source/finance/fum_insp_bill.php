<?
/*
*
*	Adam Walter, May 2014.
*
*	A screen for finance to bill fums and inspections.
*	
*	NOTE: Additions to the Customer/Commodity menus can be made on around lines 344 and 362.
*	Adding these should not have any unintended consequences down-stream.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "FUMINSP";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$conn = ocilogon("SAG_OWNER", "SAG", "BNI");
	// $conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST"); echo "<font color=\"#FF0000\">Currently using the BNI.TEST database!</font><br/>";
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	
	$url_this_page = 'fum_insp_bill.php';
	

	$date = trim($HTTP_POST_VARS['date']);
	$vessel = trim($HTTP_POST_VARS['vessel']);
	$cust = trim($HTTP_POST_VARS['cust']);
	$comm = trim($HTTP_POST_VARS['comm']);
	$serv = trim($HTTP_POST_VARS['serv']);

	if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $date) && $date != ""){
		echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font><br>";
		$submit = "";
	}

	$submit = $HTTP_POST_VARS['submit'];
	$billing_num = $HTTP_POST_VARS['billing_num'];
	if($billing_num == ""){
		$billing_num = "New";
	}

	if($submit != ""){
		if($date == "" || $vessel == "" || $cust == "" || $comm == "" || $serv == ""){
			echo "<font color=\"#FF0000\">All 5 of the top boxes must be filled to perform any action.</font><br>";
			$submit = "";
		}
	}

	if($submit == "Save"){

		$rate = trim($HTTP_POST_VARS['rate']);
		$rate_unit = trim($HTTP_POST_VARS['rate_unit']);
		$bill_qty = trim($HTTP_POST_VARS['bill_qty']);
		$bill_unit = trim($HTTP_POST_VARS['bill_unit']);
		$desc = trim($HTTP_POST_VARS['desc']);
		$cont_ord = trim(str_replace("'", "`", $HTTP_POST_VARS['cont_ord']));
		$cont_ord = str_replace("\"", "", $cont_ord);
		$cont_ord = str_replace("\\", "", $cont_ord);
		$type = trim($HTTP_POST_VARS['type']);
		$unloadreload = trim($HTTP_POST_VARS['unloadreload']);
		$asset = trim($HTTP_POST_VARS['asset']);
		$gl = trim($HTTP_POST_VARS['gl']);

		// they are trying to save a detail record.  Validate...
		$proceed = "";

		if($rate_unit == $bill_unit){
			// this is fine
			$conversion_factor = 1;
		} else {
			// is there a conversion?
			$sql = "SELECT CONVERSION_FACTOR
					FROM UNIT_CONVERSION
					WHERE PRIMARY_UOM = '".$bill_unit."'
						AND SECONDARY_UOM = '".$rate_unit."'";
			$short_term_data = ociparse($conn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				// no conversion.  Error.
				$proceed .= "Could not find a conversion rate from ".$bill_unit." to ".$rate_unit.".<br>";
			} else {
				// conversion exists
				$conversion_factor = ociresult($short_term_data, "CONVERSION_FACTOR");
			}
		}

		if(!is_numeric($rate) || $rate <= 0){
			$proceed .= "Rate must be positive and numeric.<br>";
		}
		if(!is_numeric($bill_qty) || $bill_qty <= 0){
			$proceed .= "Billed QTY must be positive and numeric.<br>";
		}
		if($asset == ""){
			$proceed .= "Asset Code cannot be empty.<br>";
		}
		if($gl == ""){
			$proceed .= "GL Code cannot be empty.<br>";
		}
		if(round($rate * $bill_qty * $conversion_factor, 2) <= 0){
			$proceed .= "The selected values would result in a bill for zero dollars.<br>";
		}
//		if($cont_ord == ""){
//			$proceed .= "Container/Order list cannot be empty.<br>";
//		}


		if($proceed != ""){
			echo "<font color=\"#FF0000\">".$proceed."</font>";
			if($billing_num != "New"){
				echo "<font color=\"#FF0000\"><br>Restoring previous values to screen.<br></font>";
			}
		} else {
			$unload_2lines = false;
			// passed checks, do save.
			if($billing_num == "New"){
				$sql = "SELECT MAX(BILLING_NUM) THE_MAX FROM BILLING";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$next_bill_num = ociresult($short_term_data, "THE_MAX") + 1;

				if($unloadreload == "Both"){
					$unloadreload = "Unload";
					$unload_2lines = true;
				}

				$sql = "INSERT INTO BILLING
							(CUSTOMER_ID,
							SERVICE_CODE,
							BILLING_NUM,
							EMPLOYEE_ID,
							SERVICE_START,
							SERVICE_STOP,
							SERVICE_DATE,
							SERVICE_AMOUNT,
							SERVICE_STATUS,
							SERVICE_DESCRIPTION,
							LR_NUM,
							ARRIVAL_NUM,
							COMMODITY_CODE,
							INVOICE_NUM,
							SERVICE_QTY,
							SERVICE_UNIT,
							SERVICE_RATE,
							LABOR_TYPE,
							BILLING_TYPE,
							COMMODITY_NAME,
							EQUIPMENT_TYPE,
							ASSET_CODE,
							SERVICE_NUM,
							GL_CODE)
						VALUES
							('".$cust."',
							'".$serv."',
							'".$next_bill_num."',
							'4',
							TO_DATE('".$date."', 'MM/DD/YYYY'),
							TO_DATE('".$date."', 'MM/DD/YYYY'),
							TO_DATE('".$date."', 'MM/DD/YYYY'),
							'".round($rate * $bill_qty * $conversion_factor, 2)."',
							'PREINVOICE',
							'".$cont_ord."',
							'".$vessel."',
							'1',
							'".$comm."',
							'0',
							'".$bill_qty."',
							'".$bill_unit."',
							'".$rate."',
							'".$rate_unit."',
							'FUMINSP',
							'".$unloadreload."',
							'".$type."',
							'".$asset."',
							'".$desc."',
							'".$gl."')";
				$edit = ociparse($conn, $sql);
				ociexecute($edit);

				if($unload_2lines){
					$next_bill_num++;
					$sql = "INSERT INTO BILLING
								(CUSTOMER_ID,
								SERVICE_CODE,
								BILLING_NUM,
								EMPLOYEE_ID,
								SERVICE_START,
								SERVICE_STOP,
								SERVICE_DATE,
								SERVICE_AMOUNT,
								SERVICE_STATUS,
								SERVICE_DESCRIPTION,
								LR_NUM,
								ARRIVAL_NUM,
								COMMODITY_CODE,
								INVOICE_NUM,
								SERVICE_QTY,
								SERVICE_UNIT,
								SERVICE_RATE,
								LABOR_TYPE,
								BILLING_TYPE,
								COMMODITY_NAME,
								EQUIPMENT_TYPE,
								ASSET_CODE,
								SERVICE_NUM,
								GL_CODE)
							VALUES
								('".$cust."',
								'".$serv."',
								'".$next_bill_num."',
								'4',
								TO_DATE('".$date."', 'MM/DD/YYYY'),
								TO_DATE('".$date."', 'MM/DD/YYYY'),
								TO_DATE('".$date."', 'MM/DD/YYYY'),
								'".round($rate * $bill_qty * $conversion_factor, 2)."',
								'PREINVOICE',
								'".$cont_ord."',
								'".$vessel."',
								'1',
								'".$comm."',
								'0',
								'".$bill_qty."',
								'".$bill_unit."',
								'".$rate."',
								'".$rate_unit."',
								'FUMINSP',
								'Reload',
								'".$type."',
								'".$asset."',
								'".$desc."',
								'".$gl."')";
					$edit = ociparse($conn, $sql);
					ociexecute($edit);
				}

			} else {
				$sql = "UPDATE BILLING SET
							SERVICE_AMOUNT = '".round($rate * $bill_qty * $conversion_factor, 2)."',
							SERVICE_DESCRIPTION = '".$cont_ord."',
							SERVICE_QTY = '".$bill_qty."',
							SERVICE_UNIT = '".$bill_unit."',
							SERVICE_RATE = '".$rate."',
							LABOR_TYPE = '".$rate_unit."',
							COMMODITY_NAME = '".$unloadreload."',
							EQUIPMENT_TYPE = '".$type."',
							GL_CODE = '".$gl."'
						WHERE BILLING_NUM = '".$billing_num."'";
				$edit = ociparse($conn, $sql);
				ociexecute($edit);
			}


			$billing_num = "New";

			echo "<font color=\"#0000FF\">Bill Processed.  See table below for entry.</font><br>";
		}
	}

	if($submit == "Delete"){
		$sql = "UPDATE BILLING SET
					SERVICE_STATUS = 'DELETED'
				WHERE BILLING_NUM = '".$billing_num."'";
		$edit = ociparse($conn, $sql);
		ociexecute($edit);
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Fumigations and Inspections Billing
		</font>
		<br/><font size="2" face="Verdana" color="#0066CC">If the Bill To Customer or Commodity you require is not listed, please contact Technology Solutions and the addition will be made immediately.
		<font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="header" action="<?php echo $url_this_page; ?>" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Service Date:  </font></td>
		<td><input type="text" name="date" size="15" maxlength="10" value="<? echo $date; ?>"><a href="javascript:show_calendar('header.date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Arrival:  </font></td>
		<td><select name="vessel">
<?
		$sql = "SELECT VP.LR_NUM, VESSEL_NAME 
				FROM VESSEL_PROFILE VP, VOYAGE VOY
				WHERE VP.LR_NUM = VOY.LR_NUM
					AND (VESSEL_NAME LIKE '%DOLE%'
						OR
						VESSEL_NAME LIKE '%HELENE%'
						OR
						VESSEL_NAME LIKE '%TOKYO%'
						OR
						VESSEL_NAME LIKE '%WARNOW WHALE%'
						OR
						VESSEL_NAME LIKE '%AGARUM%'
						OR
						VESSEL_NAME LIKE '%CRYSTAL BAY%'
						OR
						VESSEL_NAME LIKE '%CHIQUITA EXPRESS%'
						OR
						VESSEL_NAME LIKE '%CHIQUITA TRADER%'
						OR
						VESSEL_NAME LIKE '%TIGER%'
						OR
						VP.LR_NUM = '-1')
					AND (VOY.DATE_EXPECTED IS NULL OR VOY.DATE_EXPECTED >= (SYSDATE - 365))
				ORDER BY VP.LR_NUM DESC";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if($vessel == ociresult($short_term_data, "LR_NUM")){?> selected <?}?>><? echo ociresult($short_term_data, "VESSEL_NAME"); ?></option>
<?
		}
?>					</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Bill To Customer:  </font></td>
		<td><select name="cust">
<?
		$sql = "select CUSTOMER_ID,
					CUSTOMER_NAME
				from CUSTOMER_PROFILE
				where CUSTOMER_ID in ('397', '720', '721', '1803', '402', '1608')
				order by CUSTOMER_ID";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"<? if($cust == ociresult($short_term_data, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
		}
?>					</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Commodity:  </font></td>
		<td><select name="comm">
<?
		$sql = "select COMMODITY_CODE,
					COMMODITY_NAME
				from COMMODITY_PROFILE
				where COMMODITY_CODE in ('1121', '1221', '5302', '5305', '5310')
				order by COMMODITY_CODE";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>"<? if($comm == ociresult($short_term_data, "COMMODITY_CODE")){?> selected <?}?>><? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
		}
?>					</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Service:  </font></td>
		<td><select name="serv">
<?
		$sql = "SELECT SERVICE_CODE, SERVICE_NAME FROM SERVICE_CATEGORY WHERE SERVICE_CODE IN ('6525', '6545') ORDER BY SERVICE_CODE";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo ociresult($short_term_data, "SERVICE_CODE"); ?>"<? if($serv == ociresult($short_term_data, "SERVICE_CODE")){?> selected <?}?>><? echo ociresult($short_term_data, "SERVICE_NAME"); ?></option>
<?
		}
?>					</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		if($submit == "Clear" || $submit == "Delete"){
			// they want a fresh detail screen
			$billing_num = "New";
			$rate = "";
			$rate_unit = "";
			$bill_qty = "";
			$bill_unit = "";
			$desc = "";
			$cont_ord = "";
			$type = "";
			$unloadreload = "";
			$asset = "";
			$gl = "";
		} elseif($billing_num == "New"){
			if($unload_2lines){
				// only thing we need to alter from previous saved data
				$unloadreload = "Both";
			}
		} else {
			$sql = "SELECT *
					FROM BILLING
					WHERE BILLING_NUM = '".$billing_num."'";
			$data = ociparse($conn, $sql);
			ociexecute($data);
			ocifetch($data);
			$rate = ociresult($data, "SERVICE_RATE");
			$rate_unit = ociresult($data, "LABOR_TYPE");
			$bill_qty = ociresult($data, "SERVICE_QTY");
			$bill_unit = ociresult($data, "SERVICE_UNIT");
			$cont_ord = ociresult($data, "SERVICE_DESCRIPTION");
			$desc = ociresult($data, "SERVICE_NUM");
			$type = ociresult($data, "EQUIPMENT_TYPE");
			$unloadreload = ociresult($data, "COMMODITY_NAME");
			$asset = ociresult($data, "ASSET_CODE");
			$gl = ociresult($data, "GL_CODE");
		}

		if($billing_num == "New"){
			$instruction = "CREATE NEW PREINVOICE: Enter data below and save to create a new preinvoice.";
		} else {
			$instruction = "EDIT PREINVOICE:  Edit data below and save to edit a preinvoice.";
		}
		
?>
<hr>
<form name="details" action="<?php echo $url_this_page; ?>" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<input type="hidden" name="billing_num" value="<? echo $billing_num; ?>">
<input type="hidden" name="date" value="<? echo $date; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
<input type="hidden" name="serv" value="<? echo $serv; ?>">
<?
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");

	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
			WHERE COMMODITY_CODE = '".$comm."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$commname = ociresult($stid, "COMMODITY_NAME");

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$vesname = $vessel."-".ociresult($stid, "VESSEL_NAME");

	$sql = "SELECT SERVICE_NAME FROM SERVICE_CATEGORY
			WHERE SERVICE_CODE = '".$serv."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$servname = ociresult($stid, "SERVICE_NAME");

?>
	<tr>
		<td colspan="4"><font size="4" face="Verdana"><b><? echo $instruction; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font size="3" face="Verdana"><b>Current Invoice Criteria:</b></font>
								<font color="#FF0000">(Note: To Save under a different set of criteria, change the above boxes, <b>AND press Retrieve Data.)</b></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Service Date:  <? echo $date; ?></font></td>
		<td colspan="2"><font size="2" face="Verdana">Arrival:  <? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Bill To Customer:  <? echo $custname; ?></font></td>
		<td colspan="2"><font size="2" face="Verdana">Commodity:  <? echo $commname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Service:  <? echo $servname; ?></font></td>
		<td colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
	<tr>
		<td colspan="4"><hr></td>
	</tr>
	<tr>
		<td colspan="4"><font size="2" face="Verdana">Billing#:&nbsp;&nbsp;&nbsp;&nbsp;</font><font size="3" face="Verdana" color="#00AA00"><b><? echo $billing_num; ?></b></font></td>
	</tr>
	<tr>
		<td width="30%"><font size="2" face="Verdana">Rate:</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="rate" size="10" maxlength="10" value="<? echo number_format($rate, 2); ?>"></td>
		<td><font size="2" face="Verdana">Rate Unit:</font>&nbsp;&nbsp;&nbsp;&nbsp;<select name="rate_unit">
<?
		$sql = "SELECT DISTINCT UOM FROM UNITS ORDER BY UOM";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo trim(ociresult($short_term_data, "UOM")); ?>"<? if(trim($rate_unit) == trim(ociresult($short_term_data, "UOM"))){?> selected <?}?>><? echo trim(ociresult($short_term_data, "UOM")); ?></option>
<?
		}
?>					</select></td>
		<td width="30%"><font size="2" face="Verdana">Billable QTY:</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="bill_qty" size="10" maxlength="10" value="<? echo $bill_qty; ?>"></td>
		<td><font size="2" face="Verdana">Billable Unit:</font>&nbsp;&nbsp;&nbsp;&nbsp;<select name="bill_unit">
<?
		$sql = "SELECT DISTINCT UOM FROM UNITS ORDER BY UOM";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
				<option value="<? echo trim(ociresult($short_term_data, "UOM")); ?>"<? if(trim($bill_unit) == trim(ociresult($short_term_data, "UOM"))){?> selected <?}?>><? echo trim(ociresult($short_term_data, "UOM")); ?></option>
<?
		}
?>					</select></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Description:</font>&nbsp;&nbsp;&nbsp;&nbsp;<select name="desc">
<?
		$sql = "SELECT DESC_ID, DESC_TEXT FROM FUMINSP_DESCRIPTIONS ORDER BY DESC_ID";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
		<option value="<? echo ociresult($short_term_data, "DESC_ID"); ?>"<? if($desc == ociresult($short_term_data, "DESC_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "DESC_TEXT"); ?></option>
<?
		}
?>					</select>&nbsp;&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana"><a href="fuminsp_descs.php">Manage Descriptions</a></font></td>
		<td colspan="2"><font size="2" face="Verdana">Container:</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="cont_ord" size="75" maxlength="200" value="<? echo $cont_ord; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Type:</font>&nbsp;&nbsp;&nbsp;&nbsp;<select name="type">
<?
		$sql = "SELECT DESC_ID, TYPE_TEXT FROM FUMINSP_TYPES ORDER BY DESC_ID";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
		<option value="<? echo ociresult($short_term_data, "DESC_ID"); ?>"<? if($type == ociresult($short_term_data, "DESC_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "TYPE_TEXT"); ?></option>
<?
		}
?>					</select>&nbsp;&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana"><a href="fuminsp_descs.php">Manage Types</a></font></td>
		<td colspan="2"><font size="2" face="Verdana"><b>Unload/Reload:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="unloadreload" value=""<? if($unloadreload == ""){?> checked <?}?>>None&nbsp;&nbsp;&nbsp;
						<input type="radio" name="unloadreload" value="Unload"<? if($unloadreload == "Unload"){?> checked <?}?>>Unload&nbsp;&nbsp;&nbsp;
						<input type="radio" name="unloadreload" value="Reload"<? if($unloadreload == "Reload"){?> checked <?}?>>Reload&nbsp;&nbsp;&nbsp;
						<input type="radio" name="unloadreload" value="Both"<? if($unloadreload == "Both"){?> checked <?}?>>Both (Will create both an Unload and a Reload Preinvoice line)</font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Asset:</font>&nbsp;&nbsp;&nbsp;&nbsp;<select name="asset"><option value=""> </option>
<?
		$sql = "SELECT DISTINCT ASSET_CODE FROM ASSET_PROFILE WHERE ASSET_CODE LIKE 'W%' ORDER BY ASSET_CODE";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
		<option value="<? echo ociresult($short_term_data, "ASSET_CODE"); ?>"<? if($asset == ociresult($short_term_data, "ASSET_CODE")){?> selected <?}?>><? echo ociresult($short_term_data, "ASSET_CODE"); ?></option>
<?
		}
?>					</select></td></td>
		<td colspan="2"><font size="2" face="Verdana">GL:</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="gl" size="10" maxlength="10" value="<? echo $gl; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"></td>
		<td colspan="2" align="right"><input type="submit" name="submit" value="Clear"></td>
	</tr>

</table>
</form>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>Preinvoices</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Billing#</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>Rate</b></font></td>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Actions</b></font></td>
	</tr>
<?
		$form_num = 0;
		$sql = "SELECT *
				FROM BILLING BIL, FUMINSP_DESCRIPTIONS FD, FUMINSP_TYPES FT
				WHERE BIL.BILLING_TYPE = 'FUMINSP'
					AND BIL.SERVICE_STATUS = 'PREINVOICE'
					AND BIL.CUSTOMER_ID = '".$cust."'
					AND BIL.LR_NUM = '".$vessel."'
					AND BIL.COMMODITY_CODE = '".$comm."'
					AND BIL.SERVICE_CODE = '".$serv."'
					AND TO_CHAR(BIL.SERVICE_DATE, 'MM/DD/YYYY') = '".$date."'
					AND BIL.SERVICE_NUM = FD.DESC_ID
					AND BIL.EQUIPMENT_TYPE = FT.DESC_ID
				ORDER BY BILLING_NUM";
//		echo $sql."<br>";
		$pending = ociparse($conn, $sql);
		ociexecute($pending);
		while(ocifetch($pending)){
			$form_num++;
?>
<form name="edit<? echo $form_num; ?>" action="<?php echo $url_this_page; ?>" method="post">
<input type="hidden" name="billing_num" value="<? echo ociresult($pending, "BILLING_NUM"); ?>">
<input type="hidden" name="date" value="<? echo $date; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
<input type="hidden" name="serv" value="<? echo $serv; ?>">
	<tr>
		<td><? echo ociresult($pending, "BILLING_NUM"); ?></td>
		<td><? echo ociresult($pending, "DESC_TEXT")." ".ociresult($pending, "SERVICE_DESCRIPTION")." ".ociresult($pending, "TYPE_TEXT")." ".ociresult($pending, "COMMODITY_NAME"); ?></td>
		<td><? echo ociresult($pending, "SERVICE_QTY")." ".ociresult($pending, "SERVICE_UNIT"); ?></td>
		<td><? echo "$".number_format(ociresult($pending, "SERVICE_RATE"), 2)."/".ociresult($pending, "LABOR_TYPE"); ?></td>
		<td><? echo "$".number_format(ociresult($pending, "SERVICE_AMOUNT"), 2); ?></td>
		<td><input type="submit" name="submit" value="Edit"></td>
		<td><input type="submit" name="submit" value="Delete"></td>
	</tr>
</form>
<?
		}
?>
</table>
<hr>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana"><b>History</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Billing#</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>Rate</b></font></td>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
	</tr>
<?
		$sql = "SELECT *
				FROM BILLING BIL, FUMINSP_DESCRIPTIONS FD, FUMINSP_TYPES FT
				WHERE BIL.BILLING_TYPE = 'FUMINSP'
					AND BIL.SERVICE_STATUS != 'PREINVOICE'
					AND BIL.CUSTOMER_ID = '".$cust."'
					AND BIL.LR_NUM = '".$vessel."'
					AND BIL.COMMODITY_CODE = '".$comm."'
					AND BIL.SERVICE_CODE = '".$serv."'
					AND TO_CHAR(BIL.SERVICE_DATE, 'MM/DD/YYYY') = '".$date."'
					AND BIL.SERVICE_NUM = FD.DESC_ID
					AND BIL.EQUIPMENT_TYPE = FT.DESC_ID
				ORDER BY BILLING_NUM";
		$done = ociparse($conn, $sql);
		ociexecute($done);
		while(ocifetch($done)){
?>
	<tr>
		<td><? echo ociresult($done, "BILLING_NUM"); ?></td>
		<td><? echo ociresult($done, "DESC_TEXT")." ".ociresult($done, "SERVICE_DESCRIPTION")." ".ociresult($done, "TYPE_TEXT")." ".ociresult($done, "COMMODITY_NAME"); ?></td>
		<td><? echo ociresult($done, "SERVICE_QTY")." ".ociresult($done, "SERVICE_UNIT"); ?></td>
		<td><? echo "$".number_format(ociresult($done, "SERVICE_RATE"), 2)."/".ociresult($done, "LABOR_TYPE"); ?></td>
		<td><? echo "$".number_format(ociresult($done, "SERVICE_AMOUNT"), 2); ?></td>
		<td><? echo ociresult($done, "SERVICE_STATUS"); ?></td>
	</tr>
<?
		}
?>
</table>
<?
	}
	include("pow_footer.php");