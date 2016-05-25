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

	$size_id = $HTTP_POST_VARS['SIZEID'];
	if($size_id == ""){
		$size_id = $HTTP_GET_VARS['SIZEID'];
	}

	$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description'])); // quotes are bad for databases
	$description = str_replace("\\", "", $description);
	$price = trim(str_replace("$", "", $HTTP_POST_VARS['price'])); // if they put the dollar sign on, take it off for inserting
	$sizelow = $HTTP_POST_VARS['sizelow'];
	$sizehigh = $HTTP_POST_VARS['sizehigh'];
	$weight = $HTTP_POST_VARS['weight'];
	$excel_col = $HTTP_POST_VARS['excel_col'];

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$check = validate_save($size_id, $description, $price, $sizelow, $sizehigh, $weight, $excel_col, $cust, $conn);
		if($check == ""){
			// time to save.
//			echo "Size: ".$size_id."<br>";
			if($size_id == "New"){
				$sql = "INSERT INTO MOR_COMMODITYSIZE
							(SIZEID,
							DESCR,
							PRICE,
							SIZELOW,
							SIZEHIGH,
							WEIGHTKG,
							ORDER_ENT_EXCEL_COL,
							CUST)
						VALUES
							(MOR_COMMSIZE_SEQ.NEXTVAL,
							'".$description."',
							'".$price."',
							'".$sizelow."',
							'".$sizehigh."',
							'".$weight."',
							'".$excel_col."',
							'".$cust."')";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			} else {
				// updating existing job.
				$sql = "UPDATE MOR_COMMODITYSIZE
						SET DESCR = '".$description."',
							PRICE = '".$price."',
							SIZEHIGH = '".$sizehigh."',
							SIZELOW = '".$sizelow."',
							WEIGHTKG = '".$weight."',
							ORDER_ENT_EXCEL_COL = '".$excel_col."'
						WHERE SIZEID = '".$size_id."'";
//				echo $sql."<br>";
				$stid = ociparse($conn, $sql);
				$success = ociexecute($stid);
			}
			if($stid !== false && $success !== false){
				echo "<font color=\"#0000FF\">Commodity Size Saved.  Please <a href=\"moroccan_commsize_index.php\">Click Here</a> To return to the listing page.<br></br>";
				include("footer.php");
				exit;
			} else {
				echo "<font color=\"#FF0000\">Could not save Commodity Size Entry.<br>Please take a screenshot of the following Error message, and email to your contact at the Port of Wilmington.<br>".$sql."</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Commodity Size Entry:<br>".$check."Refreshing Screen to previous values.<br></font>";
		}
	}



	if($size_id != "" && $size_id != "New"){
		$sql = "SELECT * FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$size_id."'";
//		echo $sql."<br>";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$size_id = ociresult($stid, "SIZEID");
		$description = ociresult($stid, "DESCR");
		$price = ociresult($stid, "PRICE");
		$sizelow = ociresult($stid, "SIZELOW");
		$sizehigh = ociresult($stid, "SIZEHIGH");
		$weight = ociresult($stid, "WEIGHTKG");
		$excel_col = ociresult($stid, "ORDER_ENT_EXCEL_COL");
	} else {
		$size_id = "New";
		$description = "";
		$price = "";
		$sizelow = "";
		$sizehigh = "";
		$weight = "";
		$excel_col = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Commodity Size Maintenance</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="moroccan_commsize_addedit_index.php" method="post">
<input type="hidden" name="SIZEID" value="<? echo $size_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $size_id; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Description:  </td>
		<td><input name="description" type="text" size="50" maxlength="50" value="<? echo $description; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Price:  </td>
		<td><input name="price" type="text" size="10" maxlength="10" value="<? echo $price; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Size (Low):  </td>
		<td><input name="sizelow" type="text" size="10" maxlength="10" value="<? echo $sizelow; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Size (High):  </td>
		<td><input name="sizehigh" type="text" size="10" maxlength="10" value="<? echo $sizehigh; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Weight (KG):  </td>
		<td><input name="weight" type="text" size="10" maxlength="10" value="<? echo $weight; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Excel Column<br>(Order Entry Form):  </td>
		<td><select name="excel_col"><option value="">Exclude From Order Entry</option>
<?
	for($i = 7; $i <= 100; $i++){
?>
			<option value="<? echo $i; ?>"<? if($i == $excel_col){?> selected <?}?>><? echo $i; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Entry"></td>
	</tr>
</form>
</table>




<?
function validate_save($size_id, $description, $price, $sizelow, $sizehigh, $weight, $excel_col, $cust, $conn){
	$return = "";

	if($description == ""){
		$return .= "You cannot leave the Description Field Blank.<br>";
	}
	if($price == ""){
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
	}

	if($excel_col != ""){
		$sql = "SELECT * FROM MOR_COMMODITYSIZE
				WHERE CUST = '".$cust."'
				AND ORDER_ENT_EXCEL_COL = '".$excel_col."'
					AND SIZEID != '".$size_id."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){
			$return .= "Excel Column ".$excel_col." is already used by Size# ".ociresult($stid, "SIZEID")." (".ociresult($stid, "DESCR").")<br>";
		}
	}

	return $return;
}