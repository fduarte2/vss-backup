<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to review steel Shippers.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel Ship";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$ID = $HTTP_POST_VARS['ID'];
	$submit = $HTTP_POST_VARS['submit'];
	$name = $HTTP_POST_VARS['name'];
	$address_1 = $HTTP_POST_VARS['address_1'];
	$address_2 = $HTTP_POST_VARS['address_2'];
	$city = $HTTP_POST_VARS['city'];
	$state = $HTTP_POST_VARS['state'];
	$zip = $HTTP_POST_VARS['zip'];
	$steel_type = $HTTP_POST_VARS['steel_type'];

	if(($submit == "Edit Shipper" || $submit == "Create Shipper")&& ($name == "" || $address_1 == "" || $city == "" || $state == "" || $zip == "" || $steel_type == "")){
		echo "<font color=\"#FF0000\">Some required fields were missing.  Could not process save.</font>";
		$submit = "";
	}

	if($submit == "Edit Shipper"){
		$sql = "UPDATE STEEL_SHIPPING_TABLE
				SET NAME = '".$name."',
					ADDRESS_1 = '".$address_1."',
					ADDRESS_2 = '".$address_2."',
					CITY = '".$city."',
					STATE = '".$state."',
					ZIP = '".$zip."',
					STEEL_TYPE = '".$steel_type."'
				WHERE SHIP_TO_ID = '".$ID."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Row edited.</font>";
	} elseif($submit == "Create Shipper") {
		$sql = "INSERT INTO STEEL_SHIPPING_TABLE
					(SHIP_TO_ID,
					NAME,
					ADDRESS_1,
					ADDRESS_2,
					CITY,
					STATE,
					ZIP,
					STEEL_TYPE,
					CREATED_LOGIN_ID)
				VALUES
					(STEEL_SHIPPING_TABLE_SEQ.NEXTVAL,
					'".$name."',
					'".$address_1."',
					'".$address_2."',
					'".$city."',
					'".$state."',
					'".$zip."',
					'".$steel_type."',
					'".$user."')";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Row created.</font>";
	} elseif($submit == "Delete Shipper") {
		$sql = "DELETE FROM STEEL_SHIPPING_TABLE
				WHERE SHIP_TO_ID = '".$ID."'"; 
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Row Deleted.</font>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Ship to Addresses - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" width="65%" cellpadding="4" cellspacing="0">
<form name="new" action="steel_ship_edit.php" method="post">
	<tr>
		<td colspan="8" align="center"><input type="submit" name="submit" value="Create Shipper"></td>
	</tr>
</form>
	<tr>
		<td>Name</td>
		<td>Address</td>
		<td>Address (cont)</td>
		<td>City</td>
		<td>State</td>
		<td>Zip</td>
		<td>Steel Type</td>
		<td>&nbsp;</td>
	</tr>
<?
	$sql = "SELECT SHIP_TO_ID, NAME, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP, STEEL_TYPE
			FROM STEEL_SHIPPING_TABLE
			ORDER BY NAME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
<form name="edit<? echo ociresult($stid, "SHIP_TO_ID"); ?>" action="steel_ship_edit.php" method="post">
<input type="hidden" name="ID" value="<? echo ociresult($stid, "SHIP_TO_ID"); ?>">
	<tr>
		<td><font size="2"><? echo ociresult($stid, "NAME"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "ADDRESS_1"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "ADDRESS_2"); ?>&nbsp;</font></td>
		<td><font size="2"><? echo ociresult($stid, "CITY"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "STATE"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "ZIP"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "STEEL_TYPE"); ?></font></td>
		<td><input type="submit" name="submit" value="Edit Shipper"></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");