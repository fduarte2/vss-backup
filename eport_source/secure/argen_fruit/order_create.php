<?

//	note:  the "error checking" of this form takes place in the _index file, in case you are wondering
//	where all of the variable definitions and stuff are.

//  (this decision is because, if the submission is good, it needs to auto-redirect to the 
//	edit page, but headers canot be passed if page output already has, which the _index files starts)


















?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#330000"><b>Create New Order</b>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
		if($bad_message != ""){
			echo "<font color=\"#FF0000\">New Order could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="order_create_index.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana" color="#330000"><b>Order #:</b></td>
		<td><input type="text" name="order_num" size="20" maxlength="20" value="<? echo $order_num; ?>">&nbsp;<font size="2" face="Verdana" color="#330000">Any spaces in the Order# will be removed.</font>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000"><b>Customer #:</b></td>
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
<!--	<tr>
		<td align="left"><font size="3" face="Verdana" color="#330000"><b>Voucher #:</b></font></td>
		<td><select name="voucher"><option value="">Select a Voucher#</option>
<?
	$sql = "SELECT DISTINCT BATCH_ID FROM CARGO_TRACKING CT, ARGFRUIT_EXPED AE
			WHERE AE.LOGIN_NAME = '".$user."'
				AND CT.RECEIVER_ID = AE.CUSTOMER_ID
				AND BATCH_ID IS NOT NULL
			ORDER BY BATCH_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "BATCH_ID"); ?>"<? if(ociresult($stid, "BATCH_ID") == $voucher){?> selected <?}?>><? echo ociresult($stid, "BATCH_ID"); ?></option>
<?
	}
?>			
			</select></td>
	</tr> !-->
	<tr>
		<td align="left"><font size="3" face="Verdana" color="#330000">Transporter #:</font></td>
		<td><select name="trans_num"><option value="">Select a Trans#</option>
<?
	$sql = "SELECT TRANSPORT_ID, '-' || COMPANY_NAME THE_NAME
			FROM ARGENFRUIT_TRANSPORT
			ORDER BY TRANSPORT_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "TRANSPORT_ID"); ?>"<? if(ociresult($stid, "TRANSPORT_ID") == $trans_num){?> selected <?}?>><? echo ociresult($stid, "TRANSPORT_ID").ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>			
			</select></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Customer PO#:</font></td>
		<td><input type="text" name="cust_po" size="20" maxlength="20" value="<? echo $cust_po; ?>"></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Pickup Date:</font>&nbsp;</td>
		<td><input type="text" name="expec_date" size="10" maxlength="10" value="<? echo $expec_date; ?>"><font size="2" face="Verdana" color="#330000">(mm/dd/yyyy format)</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New Order"></td>
	</tr>
</form>
</table>
