<?
/*
*	Adam Walter, Jul 2013.
*
*	Page for management of commodity-specific sizes of Moroccan products.
*************************************************************************/

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];

	$cons_id = $HTTP_POST_VARS['CONSIGNEEID'];
	if($cons_id == ""){
		$cons_id = $HTTP_GET_VARS['CONSIGNEEID'];
	}
//	echo "cust: ".$HTTP_POST_VARS['CUSTOMERID']."<br>";

//	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
//	$description = str_replace("\\", "", $description);
//	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
//	$sizelow = $HTTP_POST_VARS['sizelow'];
//	$sizehigh = $HTTP_POST_VARS['sizehigh'];
//	$weight = $HTTP_POST_VARS['weight'];
	$address = trim(str_replace("'", "`", $HTTP_POST_VARS['address']));
	$address = str_replace("\\", "", $address);
	$city = trim(str_replace("'", "`", $HTTP_POST_VARS['city']));
	$city = str_replace("\\", "", $city);
	$comments = trim(str_replace("'", "`", $HTTP_POST_VARS['comments']));
	$comments = str_replace("\\", "", $comments);
	$consigneename = trim(str_replace("'", "`", $HTTP_POST_VARS['consigneename']));
	$consigneename = str_replace("\\", "", $consigneename);
	$cust_id = $HTTP_POST_VARS['cust_id'];
	$broker_id = $HTTP_POST_VARS['broker_id'];
	$phone = trim(str_replace("'", "`", $HTTP_POST_VARS['phone']));
	$phone = str_replace("\\", "", $phone);
	$phonemobile = trim(str_replace("'", "`", $HTTP_POST_VARS['phonemobile']));
	$phonemobile = str_replace("\\", "", $phonemobile);
	$postalcode = trim(str_replace("'", "`", $HTTP_POST_VARS['postalcode']));
	$postalcode = str_replace("\\", "", $postalcode);
	$stateprovince = trim(str_replace("'", "`", $HTTP_POST_VARS['stateprovince']));
	$stateprovince = str_replace("\\", "", $stateprovince);

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($cons_id, $address, $city, $consigneename, $cust_id, $broker_id, $phone, $phonemobile, $postalcode, $stateprovince, $comments, $cust, $conn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($cons_id == "New"){
				$sql = "INSERT INTO MOR_CONSIGNEE
							(CONSIGNEEID,
							ADDRESS,
							CUSTOMSBROKEROFFICEID,
							CITY,
							COMMENTS,
							CONSIGNEENAME,
							CUSTOMERID,
							PHONE,
							PHONEMOBILE,
							POSTALCODE,
							STATEPROVINCE,
							CUST)
						VALUES
							(MOR_CONSIGNEE_SEQ.NEXTVAL,
							'".$address."',
							'".$broker_id."',
							'".$city."',
							'".$comments."',
							'".$consigneename."',
							'".$cust_id."',
							'".$phone."',
							'".$phonemobile."',
							'".$postalcode."',
							'".$stateprovince."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
				$sql = "UPDATE MOR_CONSIGNEE
						SET ADDRESS = '".$address."',
							CUSTOMSBROKEROFFICEID = '".$broker_id."',
							CITY = '".$city."',
							COMMENTS = '".$comments."',
							CONSIGNEENAME = '".$consigneename."',
							CUSTOMERID = '".$cust_id."',
							PHONE = '".$phone."',
							PHONEMOBILE = '".$phonemobile."',
							POSTALCODE = '".$postalcode."',
							STATEPROVINCE = '".$stateprovince."'
						WHERE CONSIGNEEID = '".$cons_id."'";
//				echo $sql."<br>";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Consignee Info Saved.  Please <a href=\"moroccan_consign_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Consignee Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Consignee Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($cons_id != "" && $cons_id != "New"){
		$sql = "SELECT * FROM MOR_CONSIGNEE 
				WHERE CONSIGNEEID = '".$cons_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$address = ociresult($stid, "ADDRESS");
		$broker_id = ociresult($stid, "CUSTOMSBROKEROFFICEID");
		$city = ociresult($stid, "CITY");
		$comments = ociresult($stid, "COMMENTS");
		$consigneename = ociresult($stid, "CONSIGNEENAME");
		$cust_id = ociresult($stid, "CUSTOMERID");
		$phone = ociresult($stid, "PHONE");
		$phonemobile = ociresult($stid, "PHONEMOBILE");
		$postalcode = ociresult($stid, "POSTALCODE");
		$stateprovince = ociresult($stid, "STATEPROVINCE");
	} else {
		$cons_id = "New";
		$address = "";
		$broker_id = "";
		$city = "";
		$comments = "";
		$consigneename = "";
		$cust_id = "";
		$phone = "";
		$phonemobile = "";
		$postalcode = "";
		$stateprovince = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Consignee Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_consign_addedit_index.php" method="post">
<input type="hidden" name="CONSIGNEEID" value="<? echo $cons_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $cons_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customer ID#:  </td>
		<td><select name="cust_id"><option value="">Select a Customer</option>
<?
	$sql = "SELECT DISTINCT CUSTOMERID, CUSTOMERNAME
			FROM MOR_CUSTOMER
			WHERE CUST = '".$cust."'
			ORDER BY CUSTOMERID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "CUSTOMERID"); ?>"<? if(ociresult($stid, "CUSTOMERID") == $cust_id){?> selected <?}?>><? echo ociresult($stid, "CUSTOMERID")." - ".ociresult($stid, "CUSTOMERNAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Consignee Name:  </td>
		<td><input name="consigneename" type="text" size="10" maxlength="10" value="<? echo $consigneename; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Broker:  </td>
		<td><select name="broker_id"><option value="">Select a Broker</option>
<?
	$sql = "SELECT DISTINCT BROKERID, CONTACTNAME
			FROM MOR_BROKER
			WHERE CUST = '".$cust."'
			ORDER BY BROKERID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "BROKERID"); ?>"<? if(ociresult($stid, "BROKERID") == $broker_id){?> selected <?}?>><? echo ociresult($stid, "BROKERID")." - ".ociresult($stid, "CONTACTNAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Address:  </td>
		<td><input name="address" type="text" size="50" maxlength="50" value="<? echo $address; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">City:  </td>
		<td><input name="city" type="text" size="30" maxlength="30" value="<? echo $city; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">State/Province:  </td>
		<td><input name="stateprovince" type="text" size="30" maxlength="30" value="<? echo $stateprovince; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Postal Code:  </td>
		<td><input name="postalcode" type="text" size="15" maxlength="15" value="<? echo $postalcode; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone:  </td>
		<td><input name="phone" type="text" size="25" maxlength="25" value="<? echo $phone; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone (Mobile):  </td>
		<td><input name="phonemobile" type="text" size="25" maxlength="25" value="<? echo $phonemobile; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Comments:  </td>
		<td><input name="comments" type="text" size="50" maxlength="50" value="<? echo $comments; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Entry"></td>
	</tr>
</form>
</table>




<?
function validate_save($cons_id, $address, $city, $consigneename, $cust_id, $broker_id, $phone, $phonemobile, $postalcode, $stateprovince, $comments, $cust, $rfconn){
	$return = "";

	if($cust_id == ""){
		$return .= "You must choose a Customer.<br>";
	}
	if($broker_id == ""){
		$return .= "You must choose a Broker.<br>";
	}
	if($consigneename == ""){
		$return .= "You cannot leave the Consignee Name Field Blank.<br>";
	}
/*	if(!is_numeric($price) || $price <= 0){
		$return .= "Price must be a positive number (entered: ".$price.").<br>";
	}
	if($sizelow == ""){
		$return .= "You cannot leave the Size (Low) Field Blank.<br>";
	}
	if(!is_numeric($sizelow) || $sizelow <= 0){
		$return .= "Size (Low) must be a positive number (entered: ".$sizelow.").<br>";
	}
	if($sizelow != round($sizelow)){
		$return .= "Size (Low) cannot have a decimal point (entered: ".$sizelow.").<br>";
	}
	if($sizehigh == ""){
		$return .= "You cannot leave the Size (High) Field Blank.<br>";
	}
	if(!is_numeric($sizehigh) || $sizehigh <= 0){
		$return .= "Size (High) must be a positive number (entered: ".$sizehigh.").<br>";
	}
	if($sizehigh != round($sizehigh)){
		$return .= "Size (High) cannot have a decimal point (entered: ".$sizehigh.").<br>";
	}
	if($sizelow  > $sizehigh){
		$return .= "Size - High (entered: ".$sizehigh.") cannot be less than Size - Low (entered: ".$sizelow.").<br>";
	}
	if($weight == ""){
		$return .= "You cannot leave the Weight Field Blank.<br>";
	}
	if(!is_numeric($weight) || $weight <= 0){
		$return .= "Weight must be a positive number (entered: ".$weight.").<br>";
	}*/

	return $return;
}