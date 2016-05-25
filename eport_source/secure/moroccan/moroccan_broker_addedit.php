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

	$broker_id = $HTTP_POST_VARS['BROKERID'];
	if($broker_id == ""){
		$broker_id = $HTTP_GET_VARS['BROKERID'];
	}
//	echo "cust: ".$HTTP_POST_VARS['CUSTOMERID']."<br>";

//	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
//	$description = str_replace("\\", "", $description);
//	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
//	$sizelow = $HTTP_POST_VARS['sizelow'];
//	$sizehigh = $HTTP_POST_VARS['sizehigh'];
//	$weight = $HTTP_POST_VARS['weight'];
	$client = trim(str_replace("'", "`", $HTTP_POST_VARS['client']));
	$client = str_replace("\\", "", $client);
	$border_crossing = trim(str_replace("'", "`", $HTTP_POST_VARS['border_crossing']));
	$border_crossing = str_replace("\\", "", $border_crossing);
	$comments = trim(str_replace("'", "`", $HTTP_POST_VARS['comments']));
	$comments = str_replace("\\", "", $comments);
	$customs_broker = trim(str_replace("'", "`", $HTTP_POST_VARS['customs_broker']));
	$customs_broker = str_replace("\\", "", $customs_broker);
	$contact_name = trim(str_replace("'", "`", $HTTP_POST_VARS['contact_name']));
	$contact_name = str_replace("\\", "", $contact_name);
	$email1 = trim(str_replace("'", "`", $HTTP_POST_VARS['email1']));
	$email1 = str_replace("\\", "", $email1);
	$email2 = trim(str_replace("'", "`", $HTTP_POST_VARS['email2']));
	$email2 = str_replace("\\", "", $email2);
	$email3 = trim(str_replace("'", "`", $HTTP_POST_VARS['email3']));
	$email3 = str_replace("\\", "", $email3);
	$email4 = trim(str_replace("'", "`", $HTTP_POST_VARS['email4']));
	$email4 = str_replace("\\", "", $email4);
	$email5 = trim(str_replace("'", "`", $HTTP_POST_VARS['email5']));
	$email5 = str_replace("\\", "", $email5);
	$phone = trim(str_replace("'", "`", $HTTP_POST_VARS['phone']));
	$phone = str_replace("\\", "", $phone);
	$fax = trim(str_replace("'", "`", $HTTP_POST_VARS['fax']));
	$fax = str_replace("\\", "", $fax);
	$dest = trim(str_replace("'", "`", $HTTP_POST_VARS['dest']));
	$dest = str_replace("\\", "", $dest);

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($dest, $contact_name, $customs_broker, $client, $border_crossing, $comments, $email1, $email2, $email3, $phone, $fax, $email4, $email5, $cust, $rfconn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($broker_id == "New"){
				$sql = "INSERT INTO MOR_BROKER
							(BROKERID,
							CLIENT,
							BORDERCROSSING,
							COMMENTS,
							CUSTOMSBROKER,
							CONTACTNAME,
							EMAIL1,
							EMAIL2,
							EMAIL3,
							EMAIL4,
							EMAIL5,
							PHONE,
							FAX,
							DESTINATIONS,
							CUST)
						VALUES
							(MOR_BROKER_SEQ.NEXTVAL,
							'".$client."',
							'".$border_crossing."',
							'".$comments."',
							'".$customs_broker."',
							'".$contact_name."',
							'".$email1."',
							'".$email2."',
							'".$email3."',
							'".$email4."',
							'".$email5."',
							'".$phone."',
							'".$fax."',
							'".$dest."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
				$sql = "UPDATE MOR_BROKER
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
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Broker Info Saved.  Please <a href=\"moroccan_broker_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Broker Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Broker Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($broker_id != "" && $broker_id != "New"){
		$sql = "SELECT * FROM MOR_BROKER WHERE BROKERID = '".$broker_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$client = ociresult($stid, "CLIENT");
		$border_crossing = ociresult($stid, "BORDERCROSSING");
		$comments = ociresult($stid, "COMMENTS");
		$customs_broker = ociresult($stid, "CUSTOMSBROKER");
		$contact_name = ociresult($stid, "CONTACTNAME");
		$email1 = ociresult($stid, "EMAIL1");
		$email2 = ociresult($stid, "EMAIL2");
		$email3 = ociresult($stid, "EMAIL3");
		$email4 = ociresult($stid, "EMAIL4");
		$email5 = ociresult($stid, "EMAIL5");
		$dest = ociresult($stid, "DESTINATIONS");
		$phone = ociresult($stid, "PHONE");
		$fax = ociresult($stid, "FAX");
	} else {
		$broker_id = "New";
		$client = "";
		$border_crossing = "";
		$comments = "";
		$customs_broker = "";
		$contact_name = "";
		$email1 = "";
		$email2 = "";
		$email3 = "";
		$email4 = "";
		$email5 = "";
		$dest = "";
		$phone = "";
		$fax = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Broker Maintenance</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_broker_addedit_index.php" method="post">
<input type="hidden" name="BROKERID" value="<? echo $broker_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $broker_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Contact Name:  </td>
		<td><input name="contact_name" type="text" size="50" maxlength="50" value="<? echo $contact_name; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customs Broker:  </td>
		<td><input name="customs_broker" type="text" size="50" maxlength="50" value="<? echo $customs_broker; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Client:  </td>
		<td><input name="client" type="text" size="50" maxlength="50" value="<? echo $client; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Border Crossing:  </td>
		<td><input name="border_crossing" type="text" size="50" maxlength="50" value="<? echo $border_crossing; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone:  </td>
		<td><input name="phone" type="text" size="25" maxlength="25" value="<? echo $phone; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Fax:  </td>
		<td><input name="fax" type="text" size="25" maxlength="25" value="<? echo $fax; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Destinations:  </td>
		<td><input name="dest" type="text" size="50" maxlength="50" value="<? echo $dest; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email#1:  </td>
		<td><input name="email1" type="text" size="50" maxlength="50" value="<? echo $email1; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email#2:  </td>
		<td><input name="email2" type="text" size="50" maxlength="50" value="<? echo $email2; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email#3:  </td>
		<td><input name="email3" type="text" size="50" maxlength="50" value="<? echo $email3; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email#4:  </td>
		<td><input name="email4" type="text" size="50" maxlength="50" value="<? echo $email4; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email#5:  </td>
		<td><input name="email5" type="text" size="50" maxlength="50" value="<? echo $email5; ?>"></td>
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
function validate_save($dest, $contact_name, $customs_broker, $client, $border_crossing, $comments, $email1, $email2, $email3, $phone, $fax, $email4, $email5, $cust, $rfconn){
	$return = "";

	if($border_crossing == ""){
		$return .= "You cannot leave the Border Crossing Field Blank.<br>";
	}
	if($client == ""){
		$return .= "You cannot leave the Client Field Blank.<br>";
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