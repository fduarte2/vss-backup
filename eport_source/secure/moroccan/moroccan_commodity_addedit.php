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

	$comm_id = $HTTP_POST_VARS['PORT_COMMODITY_CODE'];
	if($comm_id == ""){
		$comm_id = $HTTP_GET_VARS['PORT_COMMODITY_CODE'];
	}
//	echo "cust: ".$HTTP_POST_VARS['CUSTOMERID']."<br>";

//	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
//	$description = str_replace("\\", "", $description);
//	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
//	$sizelow = $HTTP_POST_VARS['sizelow'];
//	$sizehigh = $HTTP_POST_VARS['sizehigh'];
//	$weight = $HTTP_POST_VARS['weight'];
	$comm_name = trim(str_replace("'", "`", $HTTP_POST_VARS['comm_name']));
	$comm_name = str_replace("\\", "", $comm_name);
	$tariff = trim(str_replace("'", "`", $HTTP_POST_VARS['tariff']));
	$tariff = str_replace("\\", "", $tariff);

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($comm_name, $tariff, $cust, $conn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($comm_id == "New"){
				// SHOULD NOT HAPPEN, code is left here in case one day i have to add it, but this SQL simply wont work.
				$sql = "INSERT INTO NOTHING
							(TRANSPORTID,
							CARRIERNAME,
							CONTACTNAME,
							COMMENTS,
							EMAIL,
							FAX,
							IRSNUM,
							PHONE1,
							PHONE2,
							PHONECELL1,
							PHONECELL2,
							RATE1GTAMILTONWHITBY,
							RATE2CAMBRIDGE,
							RATE3OTTAWA,
							RATE4MONTREAL,
							RATE5QUEBEC,
							RATE6MONCTON,
							RATE7DEBERT,
							RATE8OTHER,
							USBONDNUM,
							CUST)
						VALUES
							(MOR_TRANSPORTER_SEQ.NEXTVAL,
							'".$carriername."',
							'".$contact_name."',
							'".$comments."',
							'".$email."',
							'".$fax."',
							'".$irsnum."',
							'".$phone1."',
							'".$phone2."',
							'".$phonemobile1."',
							'".$phonemobile2."',
							'".$rate1."',
							'".$rate2."',
							'".$rate3."',
							'".$rate4."',
							'".$rate5."',
							'".$rate6."',
							'".$rate7."',
							'".$rate8."',
							'".$USBond."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
				$sql = "UPDATE MOR_COMMODITY
						SET DC_COMMODITY_NAME = '".$comm_name."',
							HARMONIZEDTARIFF = '".$tariff."'
						WHERE PORT_COMMODITY_CODE = '".$comm_id."'";
//				echo $sql."<br>";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Commodity Info Saved.  Please <a href=\"moroccan_commodity_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Commodity Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Commodity Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($comm_id != "" && $comm_id != "New"){
		$sql = "SELECT * FROM MOR_COMMODITY WHERE PORT_COMMODITY_CODE = '".$comm_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$comm_name = ociresult($stid, "DC_COMMODITY_NAME");
		$tariff = ociresult($stid, "HARMONIZEDTARIFF");
	} else {
		$comm_id = "New";
		$comm_name = "";
		$tariff = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Commodity Code Maintenance</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_commodity_addedit_index.php" method="post">
<input type="hidden" name="PORT_COMMODITY_CODE" value="<? echo $comm_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">Commodity Code:  </td>
		<td><font size="2" face="Verdana"><? echo $comm_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Commodity Name:  </td>
		<td><input name="comm_name" type="text" size="30" maxlength="30" value="<? echo $comm_name; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Harmonized System Tariff:  </td>
		<td><input name="tariff" type="text" size="15" maxlength="15" value="<? echo $tariff; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Entry"></td>
	</tr>
</form>
</table>




<?
function validate_save($carriername, $contact_name, $cust, $rfconn){
	$return = "";

/*	if($price == ""){
		$return .= "You cannot leave the Price Field Blank.<br>";
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