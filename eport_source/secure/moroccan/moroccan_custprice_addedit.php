<?
/*
*	Adam Walter, Jul 2013.
*
*	Page for management of commodity-specific sizes of Moroccan products.
*************************************************************************/

/*
//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
*/
	include("db_def.php");


//	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");

	$cust_id = $HTTP_POST_VARS['CUSTOMERID'];
	if($cust_id == ""){
		$cust_id = $HTTP_GET_VARS['CUSTOMERID'];
	}
	$comm = $HTTP_POST_VARS['COMMODITY_CODE'];
	if($comm == ""){
		$comm = $HTTP_GET_VARS['COMMODITY_CODE'];
	}
	$size_id = $HTTP_POST_VARS['SIZEID'];
	if($size_id == ""){
		$size_id = $HTTP_GET_VARS['SIZEID'];
	}
	$eff_date = $HTTP_POST_VARS['EFFECTIVEDATE'];
	if($eff_date == ""){
		$eff_date = $HTTP_GET_VARS['EFFECTIVEDATE'];
	}
	$is_new = $HTTP_POST_VARS['is_new'];
//	echo "cust: ".$HTTP_POST_VARS['CUSTOMERID']."<br>";

//	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
//	$description = str_replace("\\", "", $description);
//	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
//	$sizelow = $HTTP_POST_VARS['sizelow'];
//	$sizehigh = $HTTP_POST_VARS['sizehigh'];
//	$weight = $HTTP_POST_VARS['weight'];
	$comments = trim(str_replace("'", "`", $HTTP_POST_VARS['comments']));
	$comments = str_replace("\\", "", $comments);
	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($cust_id, $comm, $size_id, $eff_date, $price, $is_new, $comments, $cust, $rfconn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($is_new == "Yes"){
				$sql = "INSERT INTO MOR_CUSTPRICE
							(CUSTOMERID,
							COMMODITY_CODE,
							SIZEID,
							EFFECTIVEDATE,
							COMMENTS,
							PRICE,
							CUST)
						VALUES
							('".$cust_id."',
							'".$comm."',
							'".$size_id."',
							TO_DATE('".$eff_date."', 'MM/DD/YYYY'),
							'".$comments."',
							'".$price."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
/*				$sql = "UPDATE MOR_BROKER
						SET CLIENT = '".$client."',
							BORDERCROSSING = '".$border_crossing."',
							COMMENTS = '".$comments."',
							CUSTOMSBROKER = '".$customs_broker."',
							CONTACTNAME = '".$contact_name."',
							EMAIL1 = '".$email1."',
							EMAIL2 = '".$email2."',
							EMAIL3 = '".$email3."',
							EMAIL4 = '".$email4."',
							EMAIL5 = '".$email5."',
							DESTINATIONS = '".$dest."',
							PHONE = '".$phone."',
							FAX = '".$fax."'
						WHERE BROKERID = '".$broker_id."'";
//				echo $sql."<br>";
				$success_parse = ora_parse($Short_Term_Cursor, $sql);
				$success_exec = ora_exec($Short_Term_Cursor);*/
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Customer-Price Info Saved.  Please <a href=\"moroccan_custprice_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Customer-Price Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Customer-Price Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($broker_id != "" && $broker_id != "New"){
		$sql = "SELECT * FROM MOR_CUSTPRICE 
				WHERE CUSTOMERID = '".$cust_id."'
					AND COMMODITY_CODE = '".$comm."'
					AND SIZEID = '".$size_id."'
					AND TO_CHAR(EFFECTIVE_DATE, 'MM/DD/YYYY') = '".$eff_date."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$comments = ociresult($stid, "COMMENTS");
		$price = ociresult($stid, "PRICE");
		$is_new = "No";
	} else {
		$cust_id = "New";
		$comm = "New";
		$size_id = "New";
		$eff_date = "";
		$comments = "";
		$price = "";
		$is_new = "Yes";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Customer-Price Maintenance</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_custprice_addedit_index.php" method="post">
<input type="hidden" name="is_new" value="<? echo $is_new; ?>">
	<tr>
		<td><font size="2" face="Verdana">Customer ID#:  </td>
		<td><select name="CUSTOMERID"><option value="">Select a Customer</option>
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
		<td><font size="2" face="Verdana">Commodity:  </td>
		<td><select name="COMMODITY_CODE"><option value="">Select a Commodity</option>
<?
	$sql = "SELECT DISTINCT PORT_COMMODITY_CODE, DC_COMMODITY_NAME
			FROM MOR_COMMODITY
			WHERE CUST = '".$cust."'
			ORDER BY PORT_COMMODITY_CODE";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "PORT_COMMODITY_CODE"); ?>"<? if(ociresult($stid, "PORT_COMMODITY_CODE") == $comm){?> selected <?}?>><? echo ociresult($stid, "DC_COMMODITY_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Size:  </td>
		<td><select name="SIZEID"><option value="">Select a Size</option>
<?
	$sql = "SELECT DISTINCT SIZEID, DESCR
			FROM MOR_COMMODITYSIZE
			WHERE CUST = '".$cust."'
			ORDER BY SIZEID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "SIZEID"); ?>"<? if(ociresult($stid, "SIZEID") == $size_id){?> selected <?}?>><? echo ociresult($stid, "SIZEID")." - ".ociresult($stid, "DESCR"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Effective Date:  </td>
		<td><input name="EFFECTIVEDATE" type="text" size="10" maxlength="10" value="<? echo $eff_date; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Comments:  </td>
		<td><input name="comments" type="text" size="50" maxlength="50" value="<? echo $comments; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Price:  </td>
		<td><input name="price" type="text" size="10" maxlength="10" value="<? echo $price; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Entry"></td>
	</tr>
</form>
</table>




<?
function validate_save($cust_id, $comm, $size_id, $eff_date, $price, $is_new, $comments, $cust, $rfconn){
	$Short_Term_Cursor = ora_open($rfconn);
	$return = "";

	if($cust_id == ""){
		$return .= "You must choose a Customer.<br>";
	}
	if($comm == ""){
		$return .= "You must choose a Commodity.<br>";
	}
	if($size_id == ""){
		$return .= "You must choose a Size.<br>";
	}
	if($comm == ""){
		$return .= "You must choose a Commodity.<br>";
	}
	if($eff_date == ""){
		$return .= "You must choose an Effective Date.<br>";
	}
	if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $eff_date)){
		$return .= "Effective Date must be in MM/DD/YYYY format.<br>";
	}
	if($price == ""){
		$return .= "You cannot leave the Price Field Blank.<br>";
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