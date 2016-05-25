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

	$trans_id = $HTTP_POST_VARS['TRANSPORTID'];
	if($trans_id == ""){
		$trans_id = $HTTP_GET_VARS['TRANSPORTID'];
	}
//	echo "cust: ".$HTTP_POST_VARS['CUSTOMERID']."<br>";

//	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
//	$description = str_replace("\\", "", $description);
//	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
//	$sizelow = $HTTP_POST_VARS['sizelow'];
//	$sizehigh = $HTTP_POST_VARS['sizehigh'];
//	$weight = $HTTP_POST_VARS['weight'];
	$carriername = trim(str_replace("'", "`", $HTTP_POST_VARS['carriername']));
	$carriername = str_replace("\\", "", $carriername);
	$contact_name = trim(str_replace("'", "`", $HTTP_POST_VARS['contact_name']));
	$contact_name = str_replace("\\", "", $contact_name);
	$email = trim(str_replace("'", "`", $HTTP_POST_VARS['email']));
	$email = str_replace("\\", "", $email);
	$fax = trim(str_replace("'", "`", $HTTP_POST_VARS['fax']));
	$fax = str_replace("\\", "", $fax);
	$irsnum = trim(str_replace("'", "`", $HTTP_POST_VARS['irsnum']));
	$irsnum = str_replace("\\", "", $irsnum);
	$phone1 = trim(str_replace("'", "`", $HTTP_POST_VARS['phone1']));
	$phone1 = str_replace("\\", "", $phone1);
	$phone2 = trim(str_replace("'", "`", $HTTP_POST_VARS['phone2']));
	$phone2 = str_replace("\\", "", $phone2);
	$phonemobile1 = trim(str_replace("'", "`", $HTTP_POST_VARS['phonemobile1']));
	$phonemobile1 = str_replace("\\", "", $phonemobile1);
	$phonemobile2 = trim(str_replace("'", "`", $HTTP_POST_VARS['phonemobile2']));
	$phonemobile2 = str_replace("\\", "", $phonemobile2);
	$rate1 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate1']));
	$rate1 = str_replace("\\", "", $rate1);
	$rate2 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate2']));
	$rate2 = str_replace("\\", "", $rate2);
	$rate3 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate3']));
	$rate3 = str_replace("\\", "", $rate3);
	$rate4 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate4']));
	$rate4 = str_replace("\\", "", $rate4);
	$rate5 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate5']));
	$rate5 = str_replace("\\", "", $rate5);
	$rate6 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate6']));
	$rate6 = str_replace("\\", "", $rate6);
	$rate7 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate7']));
	$rate7 = str_replace("\\", "", $rate7);
	$rate8 = trim(str_replace("'", "`", $HTTP_POST_VARS['rate8']));
	$rate8 = str_replace("\\", "", $rate8);
	$USBond = trim(str_replace("'", "`", $HTTP_POST_VARS['USBond']));
	$USBond = str_replace("\\", "", $USBond);
	$comments = trim(str_replace("'", "`", $HTTP_POST_VARS['comments']));
	$comments = str_replace("\\", "", $comments);

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($carriername, $contact_name, $email, $fax, $irsnum, $phone1, $phone2, $phonemobile1, $phonemobile2, $rate1, $rate2, $rate3, $rate4, $rate5, $rate6, $rate7, $rate8, $USBond, $comments, $cust, $conn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($trans_id == "New"){
				$sql = "INSERT INTO MOR_TRANSPORTER
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
				$sql = "UPDATE MOR_TRANSPORTER
						SET CARRIERNAME = '".$carriername."',
							CONTACTNAME = '".$contact_name."',
							COMMENTS = '".$comments."',
							EMAIL = '".$email."',
							FAX = '".$fax."',
							IRSNUM = '".$irsnum."',
							PHONE1 = '".$phone1."',
							PHONE2 = '".$phone2."',
							PHONECELL1 = '".$phonemobile1."',
							PHONECELL2 = '".$phonemobile2."',
							RATE1GTAMILTONWHITBY = '".$rate1."',
							RATE2CAMBRIDGE = '".$rate2."',
							RATE3OTTAWA = '".$rate3."',
							RATE4MONTREAL = '".$rate4."',
							RATE5QUEBEC = '".$rate5."',
							RATE6MONCTON = '".$rate6."',
							RATE7DEBERT = '".$rate7."',
							RATE8OTHER = '".$rate8."',
							USBONDNUM = '".$USBond."'
						WHERE TRANSPORTID = '".$trans_id."'";
//				echo $sql."<br>";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Transporter Info Saved.  Please <a href=\"moroccan_transporter_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Transporter Info.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Transporter Info:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($trans_id != "" && $trans_id != "New"){
		$sql = "SELECT * FROM MOR_TRANSPORTER WHERE TRANSPORTID = '".$trans_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$carriername = ociresult($stid, "CARRIERNAME");
		$contact_name = ociresult($stid, "CONTACTNAME");
		$comments = ociresult($stid, "COMMENTS");
		$email = ociresult($stid, "EMAIL");
		$fax = ociresult($stid, "FAX");
		$irsnum = ociresult($stid, "IRSNUM");
		$phone1 = ociresult($stid, "PHONE1");
		$phone2 = ociresult($stid, "PHONE2");
		$phonemobile1 = ociresult($stid, "PHONECELL1");
		$phonemobile2 = ociresult($stid, "PHONECELL2");
		$rate1 = ociresult($stid, "RATE1GTAMILTONWHITBY");
		$rate2 = ociresult($stid, "RATE2CAMBRIDGE");
		$rate3 = ociresult($stid, "RATE3OTTAWA");
		$rate4 = ociresult($stid, "RATE4MONTREAL");
		$rate5 = ociresult($stid, "RATE5QUEBEC");
		$rate6 = ociresult($stid, "RATE6MONCTON");
		$rate7 = ociresult($stid, "RATE7DEBERT");
		$rate8 = ociresult($stid, "RATE8OTHER");
		$USBond = ociresult($stid, "USBONDNUM");
	} else {
		$trans_id = "New";
		$carriername = "";
		$contact_name = "";
		$comments = "";
		$email = "";
		$fax = "";
		$irsnum = "";
		$phone1 = "";
		$phone2 = "";
		$phonemobile1 = "";
		$phonemobile2 = "";
		$rate1 = "";
		$rate2 = "";
		$rate3 = "";
		$rate4 = "";
		$rate5 = "";
		$rate6 = "";
		$rate7 = "";
		$rate8 = "";
		$USBond = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Transporter Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_transporter_addedit_index.php" method="post">
<input type="hidden" name="TRANSPORTID" value="<? echo $trans_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $trans_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Carrier Name:  </td>
		<td><input name="carriername" type="text" size="30" maxlength="30" value="<? echo $carriername; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Contact Name:  </td>
		<td><input name="contact_name" type="text" size="50" maxlength="50" value="<? echo $contact_name; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Email:  </td>
		<td><input name="email" type="text" size="50" maxlength="50" value="<? echo $email; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Fax:  </td>
		<td><input name="fax" type="text" size="25" maxlength="25" value="<? echo $fax; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">IRS#:  </td>
		<td><input name="irsnum" type="text" size="15" maxlength="15" value="<? echo $irsnum; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone1:  </td>
		<td><input name="phone1" type="text" size="25" maxlength="25" value="<? echo $phone1; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone2:  </td>
		<td><input name="phone2" type="text" size="25" maxlength="25" value="<? echo $phone2; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone1 (cell):  </td>
		<td><input name="phonemobile1" type="text" size="25" maxlength="25" value="<? echo $phonemobile1; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Phone2 (cell):  </td>
		<td><input name="phonemobile2" type="text" size="25" maxlength="25" value="<? echo $phonemobile2; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 1 (GTA Milton Whitby):  </td>
		<td><input name="rate1" type="text" size="7" maxlength="7" value="<? echo $rate1; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 2 (Cambridge):  </td>
		<td><input name="rate2" type="text" size="7" maxlength="7" value="<? echo $rate2; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 3 (Ottawa):  </td>
		<td><input name="rate3" type="text" size="7" maxlength="7" value="<? echo $rate3; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 4 (Montreal):  </td>
		<td><input name="rate4" type="text" size="7" maxlength="7" value="<? echo $rate4; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 5 (Quebec):  </td>
		<td><input name="rate5" type="text" size="7" maxlength="7" value="<? echo $rate5; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 6 (Moncton):  </td>
		<td><input name="rate6" type="text" size="7" maxlength="7" value="<? echo $rate6; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 7 (Debert):  </td>
		<td><input name="rate7" type="text" size="7" maxlength="7" value="<? echo $rate7; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Rate 8 (Other):  </td>
		<td><input name="rate8" type="text" size="7" maxlength="7" value="<? echo $rate8; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">US Bond #:  </td>
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
function validate_save($carriername, $contact_name, $email, $fax, $irsnum, $phone1, $phone2, $phonemobile1, $phonemobile2, $rate1, $rate2, $rate3, $rate4, $rate5, $rate6, $rate7, $rate8, $USBond, $comments, $cust, $rfconn){
	$return = "";

	if($carriername == ""){
		$return .= "You cannot leave the Carrier Name Field Blank.<br>";
	}
	if($rate1 != "" && (!is_numeric($rate1) || $rate1 <= 0)){
		$return .= "Rate #1 (if entered) must be a positive number (entered: ".$rate1.").<br>";
	}
	if($rate2 != "" && (!is_numeric($rate2) || $rate2 <= 0)){
		$return .= "Rate #2 (if entered) must be a positive number (entered: ".$rate2.").<br>";
	}
	if($rate3 != "" && (!is_numeric($rate3) || $rate3 <= 0)){
		$return .= "Rate #3 (if entered) must be a positive number (entered: ".$rate3.").<br>";
	}
	if($rate4 != "" && (!is_numeric($rate4) || $rate4 <= 0)){
		$return .= "Rate #4 (if entered) must be a positive number (entered: ".$rate4.").<br>";
	}
	if($rate5 != "" && (!is_numeric($rate5) || $rate5 <= 0)){
		$return .= "Rate #5 (if entered) must be a positive number (entered: ".$rate5.").<br>";
	}
	if($rate6 != "" && (!is_numeric($rate6) || $rate6 <= 0)){
		$return .= "Rate #6 (if entered) must be a positive number (entered: ".$rate6.").<br>";
	}
	if($rate7 != "" && (!is_numeric($rate7) || $rate7 <= 0)){
		$return .= "Rate #7 (if entered) must be a positive number (entered: ".$rate7.").<br>";
	}
	if($rate8 != "" && (!is_numeric($rate8) || $rate8 <= 0)){
		$return .= "Rate #8 (if entered) must be a positive number (entered: ".$rate8.").<br>";
	}
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