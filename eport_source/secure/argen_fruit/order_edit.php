<?

	$submit = $HTTP_POST_VARS['submit'];
	$order_num = strtoupper($HTTP_POST_VARS['order_num']);
	if($order_num == ""){
		$order_num = strtoupper($HTTP_GET_VARS['order_num']);
	}

	if($auth_exp == "WRITE" && $submit == "Change Order Number"){
		$orig_order = $HTTP_POST_VARS['orig_order'];
		if(!ereg("^([0-9a-zA-Z])+$", $order_num)){
			echo "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">New Order Number must be Alphanumeric</font><br>";
			$order_num = $orig_order;
		} else {
			$sql = "UPDATE ARGENFRUIT_ORDER_HEADER
					SET ORDER_NUM = '".$order_num."'
						WHERE ORDER_NUM = '".$orig_order."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
			echo "<font size=\"2\" face=\"Verdana\" color=\"#0000FF\"><b>Order Number Changed.</b></font><br>";
		}
	}
//	echo "order num: ".$order_num."<br>";
	
	
	if($auth_port == "WRITE" && $order_num != "" && $HTTP_GET_VARS['setstat'] == "3"){
		$sql = "UPDATE ARGENFRUIT_ORDER_HEADER
				SET STATUS = '3'
				WHERE STATUS = '2'
					AND ORDER_NUM = '".$order_num."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}



	if($auth_exp == "WRITE" && $submit == "Save Order"){
		$cust = $HTTP_POST_VARS['cust'];
		$cons = $HTTP_POST_VARS['cons'];
		$load_num = $HTTP_POST_VARS['load_num'];
		$status = $HTTP_POST_VARS['status'];
		$expec_date = $HTTP_POST_VARS['expec_date'];
		$trans_num = $HTTP_POST_VARS['trans_num'];
//		$voucher = $HTTP_POST_VARS['voucher'];
		$cust_po = str_replace("'", "`", $HTTP_POST_VARS['cust_po']);
		$cust_po = str_replace("\\", "", $cust_po);
		$special_inst = str_replace("'", "`", $HTTP_POST_VARS['special_inst']);
		$special_inst = str_replace("\\", "", $special_inst);
		$load_seq = $HTTP_POST_VARS['load_seq'];
		$temp_req = $HTTP_POST_VARS['temp_req'];
		$primary_order = str_replace("'", "`", $HTTP_POST_VARS['primary_order']);
		$primary_order = str_replace("\\", "", $primary_order);

		
		$bad_order_message = "";
		if($cust == ""){
			$bad_order_message .= "A Customer must be selected.<br>";
		}
		if($expec_date != "" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2})$", $expec_date)){
			$bad_order_message .= "Expected Date must be in MM/DD/YY format.<br>";
		}
		if($load_seq != "1"){
			if($primary_order == ""){
				$bad_order_message .= "If this load is not the first in the Load Sequence, it needs to have a \"primary Order\" entered.<br>";
			} else {
				$sql = "SELECT LOAD_SEQ, PRIMARY_ORDER FROM ARGENFRUIT_ORDER_HEADER WHERE ORDER_NUM = '".$primary_order."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				if(!ocifetch($short_term_data)){
					$bad_order_message .= "The selected Primary Order, ".$order_num.", is not in our system.<br>";
				} elseif(ociresult($short_term_data, "LOAD_SEQ") != "1")
					$bad_order_message .= "The selected Primary Order, ".$primary_order.", cannot be chosen, as it is itself a secondary order to Order# ".ociresult($short_term_data, "PRIMARY_ORDER").".<br>";
			}
		} elseif($load_seq == "1"){
			if($primary_order != ""){
				$bad_order_message .= "The \"Primary Order\" field is only used when the Load Sequence is higher than 1.<br>";
			}
		}

//		if($voucher == ""){
//			$bad_order_message .= "A Voucher# must be selected.<br>";
//		}
		$status_change = GetValidStatusForAlter($auth_exp, $auth_port, $status, $order_num, $rfconn);
		if($status_change === false){
			$bad_order_message .= "Order Status has proceeded to a point it can no longer be modified from.<br>";
		}
		if($bad_order_message != ""){
			echo "<font color=\"#FF0000\">Order Header info could not be saved for the following reasons:<br><br>".$bad_order_message."<br>Please correct and resubmit.</font><br>";
		} else {
//						VOUCHER_NUM = '".$voucher."',
			$sql = "UPDATE ARGENFRUIT_ORDER_HEADER
					SET CUSTOMER_ID = '".$cust."',
						CUSTOMER_PO = '".$cust_po."',
						CONSIGNEE_ID = '".$cons."',
						STATUS = '".$status."',
						LOAD_NUM = '".$load_num."',
						TRANSPORT_ID = '".$trans_num."',
						SPECIAL_INST = '".$special_inst."',
						LOAD_SEQ = '".$load_seq."',
						PRIMARY_ORDER = '".$primary_order."',
						TEMPERATURE_REQUEST = '".$temp_req."',
						EXPECTED_DATE = TO_DATE('".$expec_date."', 'MM/DD/YY')
					WHERE ORDER_NUM = '".$order_num."'";
//			echo $sql."<br>";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			if($status == "3"){
				// if they are setting it as truck-loading, clear out any previous checkouttime
				$sql = "UPDATE ARGENFRUIT_CHECKIN_ID
						SET CHECK_OUT = NULL
						WHERE CHECKIN_ID = (SELECT CHECKIN_ID FROM ARGENFRUIT_ORDER_HEADER WHERE ORDER_NUM = '".$order_num."')";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			}

			echo "<font color=\"#0000FF\">Order Header info Saved.</font><br>";
		}


		$detail = $HTTP_POST_VARS['detail'];
		$comm = $HTTP_POST_VARS['comm'];
		$voucher = str_replace("'", "`", $HTTP_POST_VARS['voucher']);
		$voucher = str_replace("\\", "", $voucher);
		$variety = str_replace("'", "`", $HTTP_POST_VARS['variety']);
		$variety = str_replace("\\", "", $variety);
		$import_code = str_replace("'", "`", $HTTP_POST_VARS['import_code']);
		$import_code = str_replace("\\", "", $import_code);
		$import_size = str_replace("'", "`", $HTTP_POST_VARS['import_size']);
		$import_size = str_replace("\\", "", $import_size);
		$cartons = str_replace("'", "`", $HTTP_POST_VARS['cartons']);
		$cartons = str_replace("\\", "", $cartons);
		$comments = str_replace("'", "`", $HTTP_POST_VARS['comments']);
		$comments = str_replace("\\", "", $comments);

		for($i = 0; $i < 10; $i++){
			$bad_detail_message = "";
			// we only care about the lines that have a carton entry...
			// AND if the order can be changed at all
			if($cartons[$i] > 0 && is_numeric($cartons[$i]) && $status_change === true){
				// some checks need to go here, but for now, I just want the insert/update for demonstration.
				if($voucher[$i] == ""){
					$bad_detail_message .= "There was a Carton Count entered on line ".($i + 1).", but no Voucher was selected.<br>";
				}
				if($variety[$i] == ""){
					$bad_detail_message .= "There was a Carton Count entered on line ".($i + 1).", but no Variety was selected.<br>";
				}
				if($comm[$i] == ""){
					$bad_detail_message .= "There was a Carton Count entered on line ".($i + 1).", but no Commodity was selected.<br>";
				}
				if($import_code[$i] == "" && $import_size[$i] == ""){
					$bad_detail_message .= "There was a Carton Count entered on line ".($i + 1)."; either an Import code or Size needs to be entered as well.<br>";
				}
				$available = GetAvailableCartons($voucher[$i], $variety[$i], $import_code[$i], $import_size[$i], $order_num, $user, $rfconn);
				if($available < $cartons[$i]){
					$bad_detail_message .= "On line ".($i + 1).", the amount requested (".$cartons[$i].") is more than the amount available (".$available.")<br>";
				}
				if($bad_detail_message != ""){
					echo "<font color=\"#FF0000\">Detail Line ".($i + 1)." could not be saved for the following reason(s):<br><br>".$bad_detail_message."<br>Original Value has been restored.</font><br>";
				} else {
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM ARGENFRUIT_ORDER_DETAIL
							WHERE ORDER_NUM = '".$order_num."'
								AND ORDER_DETAIL = '".$detail[$i]."'";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);
					$count_check = ociresult($short_term_data, "THE_COUNT");

					if($count_check > 0){
						$sql = "UPDATE ARGENFRUIT_ORDER_DETAIL
								SET VARIETY = '".$variety[$i]."',
									IMPORT_CODE = '".$import_code[$i]."',
									IMPORT_SIZE = '".$import_size[$i]."',
									CARTONS = '".$cartons[$i]."',
									VOUCHER_NUM = '".$voucher[$i]."',
									COMMODITY_CODE = '".$comm[$i]."',
									COMMENTS = '".$comments[$i]."'
								WHERE ORDER_NUM = '".$order_num."'
									AND ORDER_DETAIL = '".$detail[$i]."'";
					} else {
						$sql = "INSERT INTO ARGENFRUIT_ORDER_DETAIL
									(ORDER_NUM,
									ORDER_DETAIL,
									VARIETY,
									IMPORT_CODE,
									IMPORT_SIZE,
									VOUCHER_NUM,
									COMMODITY_CODE,
									CARTONS,
									COMMENTS)
								VALUES
									('".$order_num."',
									'".$detail[$i]."',
									'".$variety[$i]."',
									'".$import_code[$i]."',
									'".$import_size[$i]."',
									'".$voucher[$i]."',
									'".$comm[$i]."',
									'".$cartons[$i]."',
									'".$comments[$i]."')";
					}
//					echo $sql."<br>";
					$modify = ociparse($rfconn, $sql);
					ociexecute($modify);

					echo "<font color=\"#0000FF\">Detail Line ".($i + 1)." info Saved.</font><br>";
				}
			} elseif($cartons[$i] != "" && !is_numeric($cartons[$i])) {
				echo "<font color=\"#FF0000\">Detail Line ".($i + 1)." could not be saved for the following reason(s):<br><br>A non-numeric carton value was entered.<br>Original Value has been restored.</font><br>";
			} else {
				// clear any potential line
				$sql = "DELETE FROM ARGENFRUIT_ORDER_DETAIL
						WHERE ORDER_NUM = '".$order_num."'
							AND ORDER_DETAIL = '".$detail[$i]."'";
				$modify = ociparse($rfconn, $sql);
				ociexecute($modify);
			}
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Order Details Page<? if($order_num != ""){?> ORDER#: <? echo $order_num;}?><b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_select_1" action="order_edit_index.php" method="post"> 
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Pending Orders:</td>
		<td align="left"><select name="order_num" onchange="document.order_select_1.submit(this.form)"><option value="">Select an Order:</option>
<?

	$sql = "SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER 
			WHERE STATUS NOT IN ('7', '8') 
			ORDER BY ORDER_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "ORDER_NUM"); ?>"<? if($order_num == ociresult($stid, "ORDER_NUM")){?> selected <?}?>><? echo ociresult($stid, "ORDER_NUM"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
<form name="order_select_2" action="order_edit_index.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Freeform Order# Entry:</td>
		<td align="left"><input type="text" name="order_num" size="20" maxlength="20" value="<? echo $order_num; ?>"></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><br><hr><br></td>
	</tr>
</table>

<?
	if($order_num != ""){
		$row_count = 0;

	$sql = "SELECT AOH.*, TO_CHAR(EXPECTED_DATE, 'MM/DD/YY') THE_DATE, ACI.TEMP_RECORDER 
			FROM ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_CHECKIN_ID ACI
			WHERE ORDER_NUM = '".$order_num."'
				AND AOH.CHECKIN_ID = ACI.CHECKIN_ID(+)";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$status = ociresult($short_term_data, "STATUS");
	$cust = ociresult($short_term_data, "CUSTOMER_ID");
	$cons = ociresult($short_term_data, "CONSIGNEE_ID");
	$cust_po = ociresult($short_term_data, "CUSTOMER_PO");
	$expec_date = ociresult($short_term_data, "THE_DATE");
	$trans_num = ociresult($short_term_data, "TRANSPORT_ID");
//	$voucher = ociresult($short_term_data, "VOUCHER_NUM");
	$driver_num = ociresult($short_term_data, "CHECKIN_ID");
	$special_inst = ociresult($short_term_data, "SPECIAL_INST");
	$load_seq = ociresult($short_term_data, "LOAD_SEQ");
	$primary_order = ociresult($short_term_data, "PRIMARY_ORDER");
	$temp_req = ociresult($short_term_data, "TEMPERATURE_REQUEST");
	$temp_num = ociresult($short_term_data, "TEMP_RECORDER");


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_change" action="order_edit_index.php" method="post"> 
<input type="hidden" name="orig_order" value="<? echo $order_num; ?>">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000"><b>Change Order#:</b></td>
		<td align="left"><input type="text" name="order_num" size="20" maxlength="20" value="<? echo $order_num; ?>"></td>
	</tr>
<?
	if($status >= "9"){
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana" color="#FF0000">Cannot change Order# for COMPLETE/CANCELLED orders.</font></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Change Order Number"></td>
	</tr>
<?
	}
?>
</form>
	<tr>
		<td colspan="2"><br><hr><br></td>
	</tr>
</table>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_edit" action="order_edit_index.php" method="post">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
?>
	<tr>
		<td width="15%"><font size="2" face="Verdana" color="#330000"><b>Customer #:</b></td>
		<td><select name="cust"><option value="">Select a Customer</option>
<?
	$sql = "SELECT CUSTOMER_CODE, CUSTOMER_CODE || '-' || CUSTOMER_NAME THE_NAME
			FROM EXP_CUSTOMER
			ORDER BY CUSTOMER_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "CUSTOMER_CODE"); ?>"<? if(ociresult($stid, "CUSTOMER_CODE") == $cust){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>			
			</select></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana" color="#330000">Consignee #:</font></td>
		<td><select name="cons"><option value="">Select a Consignee</option>
<?
	$sql = "SELECT CONSIGNEE_CODE, CONSIGNEE_CODE || '-' || CONSIGNEE_NAME THE_NAME
			FROM EXP_CONSIGNEE
			ORDER BY CONSIGNEE_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "CONSIGNEE_CODE"); ?>"<? if(ociresult($stid, "CONSIGNEE_CODE") == $cons){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>			
			</select></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Customer PO#:</font></td>
		<td><input type="text" name="cust_po" size="20" maxlength="20" value="<? echo $cust_po; ?>"></td>
	</tr>
<?
	$sql = "SELECT '-' || DRIVER_NAME THE_DRIVER
			FROM ARGENFRUIT_CHECKIN_ID
			WHERE CHECKIN_ID = '".$driver_num."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
?>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Driver#:</font></td>
		<td><font size="3" face="Verdana" color="#330000">N/A</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<? if($auth_port != ""){?><a href="driver_checkin_info_index.php?order_num=<? echo $order_num; ?>">Assign Driver</a><? } ?></td>
</td>
	</tr>
<?
	} else {
?>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Driver#:</font></td>
		<td><font size="3" face="Verdana" color="#330000"><? echo $driver_num.ociresult($stid, "THE_DRIVER"); ?></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<? if($auth_port != ""){?><a href="driver_checkin_info_index.php?order_num=<? echo $order_num; ?>">Change Driver</a><? } ?></td>
	</tr>
<?
	}
?>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Pickup Date:</font></td>
		<td><input type="text" name="expec_date" size="10" maxlength="10" value="<? echo $expec_date; ?>">
		<? if($auth_port != ""){
				if($temp_num == "" && $temp_req == "Y"){?><font color=\"FF0000\" size="2" face="Verdana">A Temperature Recorder has been requested.  Cannot check truck out until one is entered via the link above.</font><?}
				elseif($status != "3"){?><font color=\"FF0000\" size="2" face="Verdana">Cannot Check Out Truck until it is in "Truck Loading" Status.</font><? }
				else{?><a href="http://intranet/inventory/ArgenFruit/sign.php?order_num=<? echo $order_num; ?>">Check Out Driver</a></font><?}
			}?></td>
	</tr>
<?
//	$voucher_array = GetVoucherArray($rfconn, $user);
?>
<!--	<tr>
		<td width="15%"><font size="2" face="Verdana" color="#330000"><b>Voucher #:</b></td>
		<td><select name="voucher"><option value="">Select a Voucher</option>
		<? PopulateVoucher($voucher_array, $voucher, $order_num, $user, $rfconn); ?>
					</select></td>
	</tr> !-->
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Transporter:</td>
		<td align="left"><select name="trans_num"><option value="">Select an ID:</option>
<?

	$sql = "SELECT TRANSPORT_ID, TRANSPORT_ID || ' ' || COMPANY_NAME THE_NAME
			FROM ARGENFRUIT_TRANSPORT 
			ORDER BY TRANSPORT_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "TRANSPORT_ID"); ?>"<? if($trans_num == ociresult($stid, "TRANSPORT_ID")){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>
					</select>
					<? if($auth_port != "" && $status =="9"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="http://intranet/inventory/ArgenFruit/argen_fruit_bol.php?order_num=<? echo $order_num; ?>">Reprint BoL</a><?}?></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Status:</font></td>
		<td><select name="status"><? PopulateStatusBox($auth_exp, $auth_port, $status, $rfconn); ?></select>
		<? if($status =="2" || $status =="3"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://intranet/inventory/ArgenFruit/argen_fruit_picklist.php?order_num=<? echo $order_num; ?>">Picklist</a><?}?>
		<? if($status =="2"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="order_edit_index?order_num=<? echo $order_num; ?>&setstat=3">Set Status To: Truckloading</a><?}?></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Special Instructions:</font></td>
		<td><input type="text" name="special_inst" size="50" maxlength="200" value="<? echo $special_inst; ?>"></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Load Sequence:</font></td>
		<td align="left"><select name="load_seq">
					<option value="1"<? if($load_seq == 1){?> selected <?}?>>1</option>
					<option value="2"<? if($load_seq == 2){?> selected <?}?>>2</option>
					<option value="3"<? if($load_seq == 3){?> selected <?}?>>3</option>
					<option value="4"<? if($load_seq == 4){?> selected <?}?>>4</option>
					<option value="5"<? if($load_seq == 5){?> selected <?}?>>5</option></select>
					&nbsp;&nbsp;&nbsp;&nbsp;Load Behind Order:&nbsp;&nbsp;
					<select name="primary_order"><option value=""></option>
<?
	$sql = "SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER
			WHERE STATUS IN ('1', '2')
				AND ORDER_NUM != '".$order_num."'
			ORDER BY ORDER_NUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "ORDER_NUM"); ?>"<? if($primary_order == ociresult($stid, "ORDER_NUM")){?> selected <?}?>><? echo ociresult($stid, "ORDER_NUM"); ?></option>
<?
	}
?>
					</select><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1 indicates the first order to be loaded onto the truck)</font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Temperature Recorder?:</font></td>
		<td align="left"><select name="temp_req">
					<option value="Y"<? if($temp_req == "Y"){?> selected <?}?>>Y</option>
					<option value="N"<? if($temp_req == "N"){?> selected <?}?>>N</option></select></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Voucher</font></td>
		<td><font size="2" face="Verdana" color="#330000">Commodity</font></td>
		<td><font size="2" face="Verdana" color="#330000">Variety</font></td>
		<td><font size="2" face="Verdana" color="#330000">Import Code</font></td>
		<td><font size="2" face="Verdana" color="#330000">Size</font></td>
		<td><font size="2" face="Verdana" color="#330000">Cartons to Ship</font></td>
		<td width="50%"><font size="2" face="Verdana">Comments</font></td>
	</tr>
<?
		$detail = array();
		$voucher_array = GetVoucherArray($rfconn, $user);
		$comm_array = GetCommArray($rfconn, $user);
		$variety_array = GetVarietyArray($rfconn, $user);
		$import_code_array = GetImpCodeArray($rfconn, $user);
		$import_size_array = GetImpSizeArray($rfconn, $user);

		 
		$sql = "SELECT ORDER_DETAIL, VOUCHER_NUM, VARIETY, IMPORT_CODE, IMPORT_SIZE, CARTONS, COMMODITY_CODE, PALLETS, COMMENTS
				FROM ARGENFRUIT_ORDER_DETAIL
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			$detail[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "ORDER_DETAIL");
			$voucher[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "VOUCHER_NUM");
			$comm[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "COMMODITY_CODE");
			$variety[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "VARIETY");
			$import_code[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "IMPORT_CODE");
			$import_size[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "IMPORT_SIZE");
			$cartons[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "CARTONS");
			$pallets[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "PALLETS");
			$comments[ociresult($stid, "ORDER_DETAIL")] = ociresult($stid, "COMMENTS");

//			$row_count++;
		}

		// prepare blank lines.  The reason for the IF statement in here is if the users put in orders on non-sequential lines
		for($row_count = 0; $row_count < 10; $row_count++){
			if($detail[$row_count] == ""){
				$detail[$row_count] = $row_count;
				$voucher[$row_count] = "";
				$comm[$row_count] = "";
				$variety[$row_count] = "";
				$import_code[$row_count] = "";
				$import_size[$row_count] = "";
				$cartons[$row_count] = "";
				$pallets[$row_count] = "";
				$comments[$row_count] = "";
			}
		}

		for($i = 0; $i < $row_count; $i++){
?>
	<tr>
		<input type="hidden" name="detail[<? echo $i; ?>]" value="<? echo $detail[$i]; ?>">
		<td><select name="voucher[<? echo $i; ?>]"><? PopulateVoucher($voucher_array, $voucher[$i], $order_num, $user, $rfconn); ?></select>
					<!-- <input type="text" name="voucher[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $voucher[$i]; ?>">!--></td>
		<td><select name="comm[<? echo $i; ?>]"><? PopulateComm($comm_array, $comm[$i], $order_num, $user, $rfconn); ?></select>
					<!-- <input type="text" name="comm[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $comm[$i]; ?>">!--></td>
		<td><select name="variety[<? echo $i; ?>]"><? PopulateVariety($variety_array, $variety[$i], $order_num, $user, $rfconn); ?></select>
					<!--<input type="text" name="variety[<? echo $i; ?>]" size="20" maxlength="20" value="<? echo $variety[$i]; ?>">!--></td>
		<td><select name="import_code[<? echo $i; ?>]"><? PopulateImpCode($import_code_array, $import_code[$i], $order_num, $user, $rfconn); ?></select>
					<!--<input type="text" name="import_code[<? echo $i; ?>]" size="20" maxlength="20" value="<? echo $import_code[$i]; ?>">!--></td>
		<td><select name="import_size[<? echo $i; ?>]"><? PopulateImpSize($import_size_array, $import_size[$i], $order_num, $user, $rfconn); ?></select>
					<!--<input type="text" name="import_size[<? echo $i; ?>]" size="20" maxlength="20" value="<? echo $import_size[$i]; ?>">!--></td>
		<td><input type="text" name="cartons[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $cartons[$i]; ?>"></td>
		<td><input type="text" name="comments[<? echo $i; ?>]" size="50" maxlength="200" value="<? echo $comments[$i]; ?>"></td>
	</tr>
<?
		}
	if($auth_exp == "WRITE"){
?>
	<tr>
		<td colspan="5"><input type="submit" name="submit" value="Save Order"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="5"><font size="3" face="Verdana" color="#330000">Only the Customer can perform a General Save</font></td>
	</tr>
<?
	}
?>
</form>
</table>
<?
	}















function GetValidStatusForAlter($auth_exp, $auth_port, $new_status, $order_num, $rfconn){
	$sql = "SELECT * FROM ARGENFRUIT_ORDER_HEADER
			WHERE ORDER_NUM = '".$order_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$status = ociresult($short_term_data, "STATUS");

	if($status == $new_status){
		// no change = good to go
		return true;
	}

	if($auth_exp == "WRITE"){
		if($status == "1" && ($new_status == "2" || $new_status == "10")){
			return true;
		}
		if($status == "2" && ($new_status == "1" || $new_status == "10")){
			return true;
		}
		if($status == "10" && ($new_status == "1" || $new_status == "2")){
			return true;
		}
	}

	if($auth_port == "WRITE"){
		if($status == "2" && ($new_status == "3" || $new_status == "3")){
			return true;
		}
		if($status == "3" && ($new_status == "2" || $new_status == "9")){
			return true;
		}
		if($status == "9" && ($new_status == "3" || $new_status == "3")){
			return true;
		}
	}

	return false;
}

function PopulateStatusBox($auth_exp, $auth_port, $status, $rfconn){
	$in_list = "('".$status."' ";
	if($auth_exp == "WRITE"){
		if($status == "1"){
			$in_list .= ", '2', '10'";
		}
		if($status == "2"){
			$in_list .= ", '1', '10'";
		}
		if($status == "10"){
			$in_list .= ", '1', '2'";
		}
	}
	if($auth_port == "WRITE"){
		if($status == "2"){
			$in_list .= ", '3'";
		}
		if($status == "3"){
			$in_list .= ", '2', '9'";
		}
		if($status == "9"){
			$in_list .= ", '3'";
		}
	}
	$in_list .= ")";

	$sql = "SELECT STATUS, DESCR
			FROM ARGFRUIT_STATUS
			WHERE STATUS IN ".$in_list."
			ORDER BY STATUS";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
		<option value="<? echo ociresult($stid, "STATUS"); ?>"<? if(ociresult($stid, "STATUS") == $status){?> selected <?}?>><? echo ociresult($stid, "STATUS")."-".ociresult($stid, "DESCR"); ?></option>
<?
	}
}
//
function PopulateComm($array, $comm, $order_num, $user, $rfconn){
	for($i = 0; $i < sizeof($array); $i++){
		$sql = "SELECT '-' || COMMODITY_NAME THE_COMM
				FROM COMMODITY_PROFILE 
				WHERE COMMODITY_CODE = '".$array[$i]."'";
//		echo $sql;
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
?>
		<option value="<? echo $array[$i]; ?>"<? if($array[$i] == $comm){?> selected <?}?>><? echo $array[$i].ociresult($stid, "THE_COMM"); ?></option>
<?
	}
}
function PopulateVoucher($array, $voucher, $order_num, $user, $rfconn){
	for($i = 0; $i < sizeof($array); $i++){
?>
		<option value="<? echo $array[$i]; ?>"<? if($array[$i] == $voucher){?> selected <?}?>><? echo $array[$i]; ?></option>
<?
	}
}
function PopulateVariety($array, $variety, $order_num, $user, $rfconn){
	for($i = 0; $i < sizeof($array); $i++){
?>
		<option value="<? echo $array[$i]; ?>"<? if($array[$i] == $variety){?> selected <?}?>><? echo $array[$i]; ?></option>
<?
	}
}
function PopulateImpCode($array, $import_code, $order_num, $user, $rfconn){
	for($i = 0; $i < sizeof($array); $i++){
?>
		<option value="<? echo $array[$i]; ?>"<? if($array[$i] == $import_code){?> selected <?}?>><? echo $array[$i]; ?></option>
<?
	}
}
function PopulateImpSize($array, $import_size, $order_num, $user, $rfconn){
	for($i = 0; $i < sizeof($array); $i++){
?>
		<option value="<? echo $array[$i]; ?>"<? if($array[$i] == $import_size){?> selected <?}?>><? echo $array[$i]; ?></option>
<?
	}
}
function GetVoucherArray($rfconn, $user){
	$return = array();
	array_push($return, "");
	$sql = "SELECT DISTINCT BATCH_ID FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND BATCH_ID IS NOT NULL
			ORDER BY BATCH_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		array_push($return, ociresult($stid, "BATCH_ID"));
	}

	return $return;
}
function GetCommArray($rfconn, $user){
	$return = array();
	array_push($return, "");
	$sql = "SELECT DISTINCT CP.COMMODITY_CODE, COMMODITY_NAME
			FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE, COMMODITY_PROFILE CP
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND BATCH_ID IS NOT NULL
			ORDER BY CP.COMMODITY_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		array_push($return, ociresult($stid, "COMMODITY_CODE"));
	}

	return $return;
}
function GetVarietyArray($rfconn, $user){
	$return = array();
	array_push($return, "");
	$sql = "SELECT DISTINCT VARIETY FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND VARIETY IS NOT NULL
			ORDER BY VARIETY";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		array_push($return, ociresult($stid, "VARIETY"));
	}

	return $return;
}
function GetImpCodeArray($rfconn, $user){
	$return = array();
	array_push($return, "");
	$sql = "SELECT DISTINCT BOL FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND BOL IS NOT NULL
			ORDER BY BOL";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		array_push($return, ociresult($stid, "BOL"));
	}

	return $return;
}
function GetImpSizeArray($rfconn, $user){
	$return = array();
	array_push($return, "");
	$sql = "SELECT DISTINCT CARGO_SIZE FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND CARGO_SIZE IS NOT NULL
			ORDER BY CARGO_SIZE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		array_push($return, ociresult($stid, "CARGO_SIZE"));
	}

	return $return;
}



function GetAvailableCartons($voucher, $variety, $import_code, $import_size, $order_num, $user, $rfconn){
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
	$sql = "SELECT SUM(QTY_IN_HOUSE) THE_SUM
			FROM CARGO_TRACKING
			WHERE BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$IH = ociresult($short_term_data, "THE_SUM");

	// the amount of pallets already reerved on other orders...
//				AND (AOD.IMPORT_CODE = '".$import_code."' OR AOD.IMPORT_SIZE = '".$import_size."')
	$sql = "SELECT SUM(CARTONS) THE_SUM
			FROM ARGENFRUIT_ORDER_DETAIL AOD, ARGENFRUIT_ORDER_HEADER AOH
			WHERE AOD.ORDER_NUM != '".$order_num."'
				AND AOD.VOUCHER_NUM = '".$voucher."'
				AND AOD.VARIETY = '".$variety."'
				AND AOH.STATUS IN ('1', '2', '3')
				AND AOD.ORDER_NUM = AOH.ORDER_NUM
				AND (IMPORT_CODE IS NULL OR IMPORT_CODE = '".$import_code."')
				AND (IMPORT_SIZE IS NULL OR IMPORT_SIZE = '".$import_size."')";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$reserved = ociresult($short_term_data, "THE_SUM");

	// this one is a bit trickier... we want all pallets scanned-out on orders that aren't yet done.
	// we need this value to "add back into" the values above above, since said pallet, while not being "in house",
	// DOES cancel 1 of the "reserved" pallets.
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')
				AND CT.BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CT.CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR CT.BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL)
				AND CA.ORDER_NUM IN
					(SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER					
					WHERE STATUS IN ('1', '2', '3')
					AND ORDER_NUM != '".$order_num."')";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$scanned_on_reserve = ociresult($short_term_data, "THE_SUM");

	$total_available = ($IH + $scanned_on_reserve) - $reserved;

	return $total_available;
}

?>