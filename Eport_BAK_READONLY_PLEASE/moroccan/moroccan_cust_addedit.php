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

	$cust_id = $HTTP_POST_VARS['CUSTOMERID'];
	if($cust_id == ""){
		$cust_id = $HTTP_GET_VARS['CUSTOMERID'];
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
	$customername = trim(str_replace("'", "`", $HTTP_POST_VARS['customername']));
	$customername = str_replace("\\", "", $customername);
	$customershortname = trim(str_replace("'", "`", $HTTP_POST_VARS['customershortname']));
	$customershortname = str_replace("\\", "", $customershortname);
	$destcode = trim(str_replace("'", "`", $HTTP_POST_VARS['destcode']));
	$destcode = str_replace("\\", "", $destcode);
	$needpars = $HTTP_POST_VARS['needpars'];
	$origin = trim(str_replace("'", "`", $HTTP_POST_VARS['origin']));
	$origin = str_replace("\\", "", $origin);
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
		$check = validate_save($customershortname, $customername, $address, $city, $comments, $destcode, $needpars, $origin, $phone, $phonemobile, $postalcode, $stateprovince, $cust, $rfconn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($cust_id == "New"){
				$sql = "INSERT INTO MOR_CUSTOMER
							(CUSTOMERID,
							ADDRESS,
							CITY,
							COMMENTS,
							CUSTOMERNAME,
							CUSTOMERSHORTNAME,
							DESTCODE,
							NEEDPARS,
							ORIGIN,
							PHONE,
							PHONEMOBILE,
							POSTALCODE,
							STATEPROVINCE,
							CUST)
						VALUES
							(MOR_CUST_SEQ.NEXTVAL,
							'".$address."',
							'".$city."',
							'".$comments."',
							'".$customername."',
							'".$customershortname."',
							'".$destcode."',
							'".$needpars."',
							'".$origin."',
							'".$phone."',
							'".$phonemobile."',
							'".$postalcode."',
							'".$stateprovince."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
				$sql = "UPDATE MOR_CUSTOMER
						SET ADDRESS = '".$address."',
							CITY = '".$city."',
							COMMENTS = '".$comments."',
							CUSTOMERNAME = '".$customername."',
							CUSTOMERSHORTNAME = '".$customershortname."',
							DESTCODE = '".$destcode."',
							NEEDPARS = '".$needpars."',
							ORIGIN = '".$origin."',
							PHONE = '".$phone."',
							PHONEMOBILE = '".$phonemobile."',
							POSTALCODE = '".$postalcode."',
							STATEPROVINCE = '".$stateprovince."'
						WHERE CUSTOMERID = '".$cust_id."'";
//				echo $sql."<br>";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Customer Info Saved.  Please <a href=\"moroccan_cust_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Customer Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Customer Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($cust_id != "" && $cust_id != "New"){
		$sql = "SELECT * FROM MOR_CUSTOMER WHERE CUSTOMERID = '".$cust_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$address = ociresult($stid, "ADDRESS");
		$city = ociresult($stid, "CITY");
		$comments = ociresult($stid, "COMMENTS");
		$customername = ociresult($stid, "CUSTOMERNAME");
		$customershortname = ociresult($stid, "CUSTOMERSHORTNAME");
		$destcode = ociresult($stid, "DESTCODE");
		$needpars = ociresult($stid, "NEEDPARS");
		$origin = ociresult($stid, "ORIGIN");
		$phone = ociresult($stid, "PHONE");
		$phonemobile = ociresult($stid, "PHONEMOBILE");
		$postalcode = ociresult($stid, "POSTALCODE");
		$stateprovince = ociresult($stid, "STATEPROVINCE");
	} else {
		$cust_id = "New";
		$address = "";
		$city = "";
		$comments = "";
		$customername = "";
		$customershortname = "";
		$destcode = "";
		$needpars = "";
		$origin = "";
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
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Customer Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_cust_addedit_index.php" method="post">
<input type="hidden" name="CUSTOMERID" value="<? echo $cust_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $cust_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customer (short) Name:  </td>
		<td><input name="customershortname" type="text" size="30" maxlength="30" value="<? echo $customershortname; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customer Name:  </td>
		<td><input name="customername" type="text" size="50" maxlength="50" value="<? echo $customername; ?>"></td>
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
		<td><font size="2" face="Verdana">State / Province:  </td>
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
		<td><font size="2" face="Verdana">Phone (mobile):  </td>
		<td><input name="phonemobile" type="text" size="25" maxlength="25" value="<? echo $phonemobile; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Need PARS?:  </td>
		<td><select name="needpars">
				<option value="1"<? if($needpars == "1"){?> selected <?}?>>Yes</option>
				<option value="0"<? if($needpars == "0"){?> selected <?}?>>No</option>
				</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Origin:  </td>
		<td><input name="origin" type="text" size="30" maxlength="30" value="<? echo $origin; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Destination Code:  </td>
		<td><input name="destcode" type="text" size="25" maxlength="25" value="<? echo $destcode; ?>"></td>
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
function validate_save($customershortname, $customername, $address, $city, $comments, $destcode, $needpars, $origin, $phone, $phonemobile, $postalcode, $stateprovince, $cust, $rfconn){
	$return = "";

	if($customername == ""){
		$return .= "You cannot leave the Customer Name Field Blank.<br>";
	}
	if($needpars == ""){
		$return .= "You cannot leave the Needpars Blank.<br>";
	}
/*	if($price == ""){
		$return .= "You cannot leave the Price Field Blank.<br>";
	}
	if(!is_numeric($price) || $price <= 0){
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