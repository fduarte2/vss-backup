<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to edit steel Carriers.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel orderss";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$ID = $HTTP_POST_VARS['ID'];
	$submit = $HTTP_POST_VARS['submit'];

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
	
	$sql = "SELECT *
			FROM STEEL_SHIPPING_TABLE
			WHERE SHIP_TO_ID = '".$ID."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$name = ociresult($stid, "NAME");
	$address_1 = ociresult($stid, "ADDRESS_1");
	$address_2 = ociresult($stid, "ADDRESS_2");
	$city = ociresult($stid, "CITY");
	$state = ociresult($stid, "STATE");
	$zip = ociresult($stid, "ZIP");
	$steel_type = ociresult($stid, "STEEL_TYPE");

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Edit Ship To Address - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" width="65%" cellpadding="4" cellspacing="0">
<form name="save" action="steel_ship_view.php" method="post">
<input type="hidden" name="ID" value="<? echo $ID; ?>">
	<tr>
		<td colspan="2"><b>EDIT</b></td>
	</tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="name" size="35" maxlength="35" value="<? echo $name; ?>"><font size="2">(required)</font></td>
	</tr>
	<tr>
		<td>Address:</td>
		<td><input type="text" name="address_1" size="35" maxlength="35" value="<? echo $address_1; ?>"><font size="2">(required)</font></td>
	</tr>
	<tr>
		<td>Address continued:</td>
		<td><input type="text" name="address_2" size="35" maxlength="35" value="<? echo $address_2; ?>"></td>
	</tr>
	<tr>
		<td>City:</td>
		<td><input type="text" name="city" size="20" maxlength="20" value="<? echo $city; ?>"><font size="2">(required)</font></td>
	</tr>
	<tr>
		<td>State:</td>
		<td><input type="text" name="state" size="4" maxlength="4" value="<? echo $state; ?>"><font size="2">(required)</font></td>
	</tr>
	<tr>
		<td>Zip:</td>
		<td><input type="text" name="zip" size="10" maxlength="10" value="<? echo $zip; ?>"><font size="2">(required)</font></td>
	</tr>
	<tr>
		<td>Steel Type:</td>
		<td><select name="steel_type"><option value="">Select a Type:</option>
<?
	$sql = "SELECT DISTINCT STEEL_TYPE FROM STEEL_COMM_TO_TYPE_MAP
			ORDER BY STEEL_TYPE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
				<option value="<? echo ociresult($stid, "STEEL_TYPE"); ?>"<? if(ociresult($stid, "STEEL_TYPE") == $steel_type){?> selected <?}?>><? echo ociresult($stid, "STEEL_TYPE"); ?></option>
<?
	}
?>
		</td>
	</tr>
	<tr>
		<td colspan="6"><input type="submit" name="submit" value="<? echo $submit; ?>"></td>
	</tr>
</form>
</table>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="delete" action="steel_ship_view.php" method="post">
<input type="hidden" name="ID" value="<? echo $ID; ?>">
	<tr>
		<td><hr><b>DELETE</b></td>
	</tr>
<?
	$sql = "SELECT DONUM, CUSTOMER_ID, LR_NUM, COMMODITY_CODE 
			FROM STEEL_PRELOAD_DO_INFORMATION
			WHERE SHIP_TO_ID = '".$ID."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
?>
	<tr>
		<td>Click the button below to permanently remove this SHIP-TO entry.<br><b>NOTE:</b> This action cannot be undone once used;<br>The entry will need to be re-created manually if it is deleted in error.</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Delete Shipper">
	</tr>
<?
	} else {
?>
	<tr>
		<td>The selected SHIP-TO line cannot be deleted because it is in use by the following orders.<br>They will need to be changed to a different SHIP-TO entry first.</td>
	</tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td>DO#</td>
					<td>Customer#</td>
					<td>LR#</td>
					<td>Commodity</td>
				</tr>
<?
		do {
?>
				<tr>
					<td><? echo ociresult($stid, "DONUM"); ?></td>
					<td><? echo ociresult($stid, "CUSTOMER_ID"); ?></td>
					<td><? echo ociresult($stid, "LR_NUM"); ?></td>
					<td><? echo ociresult($stid, "COMMODITY_CODE"); ?></td>
				</tr>
<?
		} while(ocifetch($stid));
?>
			</table>
		</td>
	</tr>
<?
	}
?>				
</table>
<?
	include("pow_footer.php");
?>