<?
/*
*		Adam Walter, Apr 2015
*
*		Finanance can directly edit a V2 bill from this screen.
*
*****************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($bniconn));
		exit;
	}

  // Define some vars for the skeleton page
  $title = "Finance System - V2 editing";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	$submit = $HTTP_POST_VARS['submit'];
	$bill_num_manual = $HTTP_POST_VARS['bill_num_manual']; // the prebill number manual entry
	if($bill_num_manual != ""){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM BILL_HEADER
				WHERE BILLING_NUM = '".$bill_num_manual."'
					AND SERVICE_STATUS = 'PREINVOICE'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") < 1){
			echo "<font color=\"#FF0000\">Bill# ".$bill_num_manual." is not a V2 Prebill.";
			$billing_num = "";
			$bill_num_manual = "";
		} else {
			$billing_num = $bill_num_manual;
		}
	} else {
		$billing_num = $HTTP_POST_VARS['billing_num'];
	}

	if($submit == "Save Changes"){
		// modifications to the grid
		$det_line = $HTTP_POST_VARS['det_line']; // the individual detail lines (array).

		// these are all arrays as well.
		$rate = filter_input($HTTP_POST_VARS['rate']);
		$rate = array_map("strtoupper", $rate);
		$rate_unit = filter_input($HTTP_POST_VARS['rate_unit']);
		$rate_unit = array_map("strtoupper", $rate_unit);
		$qty = filter_input($HTTP_POST_VARS['qty']);
		$qty = array_map("strtoupper", $qty);
		$amt = filter_input($HTTP_POST_VARS['amt']);
		$amt = array_map("strtoupper", $amt);
		$desc = filter_input($HTTP_POST_VARS['desc']);
		$desc = array_map("strtoupper", $desc);
		$delete = $HTTP_POST_VARS['delete'];

		$error_msg = "";

		$i = 1;
		while($det_line[$i] != ""){
			if($delete[$i] == "delete"){
				// the "and already not deleted" clause is just to not perform a redundant update.
				$sql = "UPDATE BILL_DETAIL
						SET DETAIL_LINE_STATUS = 'DELETED'
						WHERE BILLING_NUM = '".$billing_num."'
							AND DETAIL_LINE = '".$det_line[$i]."'
							AND (DETAIL_LINE_STATUS IS NULL OR DETAIL_LINE_STATUS != 'DELETED')";
				$update = ociparse($bniconn, $sql);
				ociexecute($update);
			} else {
				$this_row_error = "";
				// update grid (maybe)
				$validate_fields = ValidateFields($bniconn, $qty[$i], $rate[$i], $rate_unit[$i], $desc[$i], $amt[$i]);
				if($validate_fields != ""){
					$this_row_error .= "Row ".($i).":<br>".$validate_fields;
				}
				if($this_row_error == ""){
					$sql = "UPDATE BILL_DETAIL
							SET SERVICE_AMOUNT = '".$amt[$i]."',
								SERVICE_QTY = '".$qty[$i]."',
								SERVICE_RATE = '".$rate[$i]."',
								SERVICE_DESCRIPTION = '".$desc[$i]."',
								SERVICE_RATE_UNIT = '".$rate_unit[$i]."',
								DETAIL_LINE_STATUS = NULL
							WHERE BILLING_NUM = '".$billing_num."'
								AND DETAIL_LINE = '".$det_line[$i]."'";
					$update = ociparse($bniconn, $sql);
					ociexecute($update);
				} else {
					$error_msg .= $this_row_error;
				}
			}

			$i++;
		}

		echo "<font color=\"#0000FF\">Save request Processed.</font><br>";	

		if($error_msg != ""){
			DoStuffWithError($error_msg);
		}
	} elseif($submit == "Add Entry"){
		// new line to the bill
		$new_qty = strtoupper(filter_input($HTTP_POST_VARS['new_qty'])); 
		$new_rate = strtoupper(filter_input($HTTP_POST_VARS['new_rate'])); 
		$new_rate_unit = strtoupper(filter_input($HTTP_POST_VARS['new_rate_unit'])); 
		$new_desc = strtoupper(filter_input($HTTP_POST_VARS['new_desc'])); 
		$new_amt = strtoupper(filter_input($HTTP_POST_VARS['new_amt'])); 

		$sql = "SELECT MAX(DETAIL_LINE) THE_MAX
				FROM BILL_DETAIL
					WHERE BILLING_NUM = '".$billing_num."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$next_num = ociresult($short_term_data, "THE_MAX") + 1;

		$insert_error = "";
		$validate_fields = ValidateFields($bniconn, $new_qty, $new_rate, $new_rate_unit, $new_desc, $new_amt);
		if($validate_fields != ""){
			$insert_error .= "For New Entry values: ".$validate_fields."<br>";
		}

		if($insert_error == ""){
			$sql = "INSERT INTO BILL_DETAIL
						(SERVICE_AMOUNT,
						SERVICE_QTY,
						SERVICE_RATE,
						SERVICE_DESCRIPTION,
						SERVICE_UNIT,
						SERVICE_RATE_UNIT,
						BILLING_NUM,
						DETAIL_LINE)
					VALUES
						('".$new_amt."',
						'".$new_qty."',
						'".$new_rate."',
						'".$new_desc."',
						'".$new_rate_unit."',
						'".$new_rate_unit."',
						'".$billing_num."',
						'".$next_num."')";
			$insert = ociparse($bniconn, $sql);
			ociexecute($insert);
			echo "<font color=\"#0000FF\">Add Line request Processed.</font><br>";
		} else {
			DoStuffWithError($insert_error);
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Edit V2 Bill</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bill" action="v2_edit.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Prebill#:</font></td>
		<td align="left"><select name="billing_num"><option value="">---</option>
<?
//				WHERE CUSTOMER_STATUS = 'ACTIVE' AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE') ORDER BY CUSTOMER_ID ASC";
		$sql = "SELECT BILLING_NUM 
				FROM BILL_HEADER
				WHERE SERVICE_STATUS = 'PREINVOICE'
				ORDER BY BILLING_NUM";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			// do nothing
		} else {
			do {
?>
						<option value="<? echo ociresult($short_term_data, "BILLING_NUM"); ?>"<? if(ociresult($short_term_data, "BILLING_NUM") == $billing_num){ ?> selected <? } ?>><? echo ociresult($short_term_data, "BILLING_NUM") ?></option>
<?
			} while(ocifetch($short_term_data));
		}
?>
												<select>&nbsp;&nbsp;&nbsp;---OR---&nbsp;&nbsp;&nbsp;</font>
												<input type="text" name="bill_num_manual" size="10" maxlength="10" value="<? echo $billing_num; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	if($billing_num != ""){
		$sql = "SELECT BH.*, TO_CHAR(BH.SERVICE_DATE, 'MM/DD/YYYY') THE_DATE 
				FROM BILL_HEADER BH
				WHERE BILLING_NUM = '".$billing_num."'";
		$bill_head_data = ociparse($bniconn, $sql);
		ociexecute($bill_head_data);
		ocifetch($bill_head_data);
?>
	<tr>
		<td width="15%">&nbsp;</td>
		<td width="25%"><font size="2" face="Verdana"><b>Current:</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>New:</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Arrival#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo GetArvDisp(ociresult($bill_head_data, "ARRIVAL_NUM"), $bniconn); ?></font></td>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Customer#:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo GetCustDisp(ociresult($bill_head_data, "CUSTOMER_ID"), $bniconn); ?></font></td>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Service Code:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo GetServDisp(ociresult($bill_head_data, "SERVICE_CODE"), $bniconn); ?></font></td>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Service Date:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo ociresult($bill_head_data, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>BILL TYPE:</b></font></td>
		<td width="25%"><font size="2" face="Verdana"><b><? echo ociresult($bill_head_data, "BILLING_TYPE"); ?></b></font></td>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><hr></td>
	</tr>
<form name="update" action="v2_edit.php" method="post">
<input type="hidden" name="billing_num" value="<? echo $billing_num; ?>">
	<tr>
		<td colspan="3">
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td><font size="2" face="VErdana"><b>Detail Line</b></font></td>
					<td><font size="2" face="VErdana"><b>Description</b></font></td>
					<td><font size="2" face="VErdana"><b>QTY Billed</b></font></td>
					<td><font size="2" face="VErdana"><b>Rate</b></font></td>
					<td><font size="2" face="VErdana"><b>Rate Unit</b></font></td>
					<td><font size="2" face="VErdana"><b>Amount</b></font></td>
					<td><font size="2" face="VErdana"><b>Delete Line From Prebill?</b></td>
				</tr>
<?
			$sql = "SELECT BD.*
					FROM BILL_DETAIL BD
					WHERE BILLING_NUM = '".$billing_num."'
						ORDER BY DETAIL_LINE";
//			echo $sql."<br>";
			$bill_detail_data = ociparse($bniconn, $sql);
			ociexecute($bill_detail_data);
			while(ocifetch($bill_detail_data)){
				$detline_num = ociresult($bill_detail_data, "DETAIL_LINE");

				if(ociresult($bill_detail_data, "DETAIL_LINE_STATUS") == "DELETED"){
					$bgcolor="#FFFFCC";
				} else {
					$bgcolor="#FFFFFF";
				}
?>
				<input type="hidden" name="det_line[<? echo $detline_num; ?>]" value="<? echo ociresult($bill_detail_data, "DETAIL_LINE"); ?>">
				<tr>
					<td bgcolor="<? echo $bgcolor; ?>"><font size="1" face="Verdana"><? echo ociresult($bill_detail_data, "DETAIL_LINE"); ?></font></td>
					<td bgcolor="<? echo $bgcolor; ?>"><input type="text" name="desc[<? echo $detline_num; ?>]" size="50" maxlength="500" value="<? echo ociresult($bill_detail_data, "SERVICE_DESCRIPTION"); ?>"><br>
						<? $fontcolor = FontChoice(ociresult($bill_detail_data, "SERVICE_DESCRIPTION"), ociresult($bill_detail_data, "ORIG_SERV_DESC")); ?>
						<font size="1" face="Verdana" color="<? echo $fontcolor; ?>">Originally:  <? echo ociresult($bill_detail_data, "ORIG_SERV_DESC"); ?></font>
						</td>
					<td bgcolor="<? echo $bgcolor; ?>"><input type="text" name="qty[<? echo $detline_num; ?>]" size="8" maxlength="8" value="<? echo ociresult($bill_detail_data, "SERVICE_QTY"); ?>"><br>
						<? $fontcolor = FontChoice(ociresult($bill_detail_data, "SERVICE_QTY"), ociresult($bill_detail_data, "ORIG_QTY")); ?>
						<font size="1" face="Verdana" color="<? echo $fontcolor; ?>">Originally:  <? echo ociresult($bill_detail_data, "ORIG_QTY"); ?></font>
						</td>
					<td bgcolor="<? echo $bgcolor; ?>"><input type="text" name="rate[<? echo $detline_num; ?>]" size="8" maxlength="8" value="<? echo ociresult($bill_detail_data, "SERVICE_RATE"); ?>"><br>
						<? $fontcolor =	FontChoice(ociresult($bill_detail_data, "SERVICE_RATE"), ociresult($bill_detail_data, "ORIG_SERVICE_RATE")); ?>
						<font size="1" face="Verdana" color="<? echo $fontcolor; ?>">Originally:  <? echo ociresult($bill_detail_data, "ORIG_SERVICE_RATE"); ?></font>
						</td>
					<td bgcolor="<? echo $bgcolor; ?>"><select name="rate_unit[<? echo $detline_num; ?>]" value="<? echo ociresult($bill_detail_data, "SERVICE_RATE_UNIT"); ?>">
<?
				$sql = "SELECT DISTINCT UNIT
						FROM NONSTORAGE_RATE
						WHERE BILL_TYPE = '".ociresult($bill_head_data, "BILLING_TYPE")."'";
				$bill_types = ociparse($bniconn, $sql);
				ociexecute($bill_types);
				while(ocifetch($bill_types)){
?>
								<option value="<? echo ociresult($bill_types, "UNIT"); ?>"<? if(ociresult($bill_types, "UNIT") == ociresult($bill_detail_data, "SERVICE_RATE_UNIT")){?> selected <?}?>>
								<? echo ociresult($bill_types, "UNIT"); ?></option>
<?
				}
?>
						</select><br>
						<? $fontcolor =	FontChoice(ociresult($bill_detail_data, "SERVICE_RATE_UNIT"), ociresult($bill_detail_data, "ORIG_RATE_UNIT")); ?>
						<font size="1" face="Verdana" color="<? echo $fontcolor; ?>">Originally:  <? echo ociresult($bill_detail_data, "ORIG_RATE_UNIT"); ?></font>
						</td>
					<td bgcolor="<? echo $bgcolor; ?>"><input type="text" name="amt[<? echo $detline_num; ?>]" size="10" maxlength="10" value="<? echo ociresult($bill_detail_data, "SERVICE_AMOUNT"); ?>"><br>
						<? $fontcolor = FontChoice(ociresult($bill_detail_data, "SERVICE_AMOUNT"), ociresult($bill_detail_data, "ORIG_SERV_AMT")); ?>
						<font size="1" face="Verdana" color="<? echo $fontcolor; ?>">Originally:  <? echo ociresult($bill_detail_data, "ORIG_SERV_AMT"); ?></font>
						</td>
					<td><input type="checkbox" name="delete[<? echo $detline_num; ?>]" value="delete" <? if(ociresult($bill_detail_data, "DETAIL_LINE_STATUS") == "DELETED"){?> checked <?}?>><br>
				</tr>
<?
			}
?>
				<tr>
					<td colspan="8" align="left"><input type="submit" name="submit" value="Save Changes"></td>
				</tr>				
			</table>
		</td>
	</tr>
</form>
	<tr>
		<td colspan="3" align="left"><hr></td>
	</tr>
<form name="addnew" action="v2_edit.php" method="post">
<input type="hidden" name="billing_num" value="<? echo $billing_num; ?>">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>New Entry</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">QTY:</font></td>
		<td align="left" colspan="2"><input type="text" name="new_qty" size="8" maxlength="8" value=""></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate:</font></td>
		<td align="left" colspan="2"><input type="text" name="new_rate" size="8" maxlength="8" value=""></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate Unit:</font></td>
		<td align="left" colspan="2"><select name="new_rate_unit">
<?
			$sql = "SELECT DISTINCT UNIT
					FROM NONSTORAGE_RATE
					WHERE BILL_TYPE = '".ociresult($bill_head_data, "BILLING_TYPE")."'";
			$bill_types = ociparse($bniconn, $sql);
			ociexecute($bill_types);
			while(ocifetch($bill_types)){
?>
								<option value="<? echo ociresult($bill_types, "UNIT"); ?>"><? echo ociresult($bill_types, "UNIT"); ?></option>
<?
			}
?>
		
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Description:</font></td>
		<td align="left" colspan="2"><input type="text" name="new_desc" size="50" maxlength="500" value=""></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Total:</font></td>
		<td align="left" colspan="2"><input type="text" name="new_amt" size="10" maxlength="10" value=""></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><input type="submit" name="submit" value="Add Entry"></td>
	</tr>				
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");




function ValidateFields($bniconn, $qty, $rate, $rate_unit, $desc, $amt){
	$return = "";
/*
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM LABOR_CATEGORY
			WHERE LABOR_TYPE = '".$labor_type."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") <= 0 && $labor_type != "DELE"){
		$return .= "Labor Type ".$labor_type." is not valid.<br>";
	}


	$sql = "SELECT COUNT(*) THE_COUNT
			FROM SERVICE_CATEGORY
			WHERE SERVICE_CODE = '".$serv."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") <= 0){
		$return .= "Service Code ".$serv." is not valid.<br>";
	}
	

	$temp_start = explode(":", $start_time);
	if(!is_numeric($temp_start[0]) || $temp_start[0] < 0 || $temp_start[0] > 23 || !is_numeric($temp_start[1]) || $temp_start[1] < 0 || $temp_start[1] > 59){
		$return .= "The Start Time of ".$start_time." is not valid.<br>";
	}
	$temp_end = explode(":", $end_time);
	if(!is_numeric($temp_end[0]) || $temp_end[0] < 0 || $temp_end[0] > 23 || !is_numeric($temp_end[1]) || $temp_end[1] < 0 || $temp_end[1] > 59){
		$return .= "The End Time of ".$start_time." is not valid.<br>";
	}
*/
	if(!is_numeric($qty) || $qty <= 0 || $qty != round($qty, 2)){
		$return .= "The QTY field cannot be ".$qty.", it must be larger than 0 with at most 2 decimals.<br>";
	}
	if(!is_numeric($rate) || $rate <= 0 || $rate != round($rate, 2)){
		$return .= "The Rate field cannot be ".$rate.", it must be larger than 0 with at most 2 decimals.<br>";
	}
	if(!is_numeric($amt) || $amt <= 0 || $amt != round($amt, 2)){
		$return .= "The Amount field cannot be ".$amt.", it must be larger than 0 with at most 2 decimals.<br>";
	}

	// no comments check
	return $return;
}

function DoStuffWithError($error){
	echo "<font color=\"#FF0000\">The following lines were not saved:<br><br>".$error."<br></font>";
}

function GetCustDisp($cust, $bniconn){
	$sql = "SELECT CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$custname = ociresult($short_term_data, "CUSTOMER_NAME");
	}

	if($custname != ""){
		return $custname;
	} else {
		return $cust;
	}
}

function GetArvDisp($arv, $bniconn){
	$sql = "SELECT VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$arv."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$arvname = ociresult($short_term_data, "VESSEL_NAME");
	}

	if($arvname != ""){
		return $arvname;
	} else {
		return $arv;
	}
}

function GetServDisp($serv, $bniconn){
	$sql = "SELECT SERVICE_NAME
			FROM SERVICE_CATEGORY
			WHERE SERVICE_CODE = '".$serv."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$servname = ociresult($short_term_data, "SERVICE_NAME");
	}

	if($servname != ""){
		return $servname;
	} else {
		return $serv;
	}
}

function filter_input($input){
	$return = $input;
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);
//	$return = array_map("strtoupper", $return);

	return $return;
}


function FontChoice($current, $orig){

	if($current != $orig){
		return "#FF0000";
	}

	return "#000000";
}