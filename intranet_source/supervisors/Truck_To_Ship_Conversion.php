<?
/* Created June 2006, Adam Walter
* This program allows Ben Dempsey to automatically change the vessel number
* of trucked in cargo, which right now is automatically assigned $orig_vessel,
* to a number of his choosing, provided that number is 6 digits long and
* not already taken.  This file also uses file Execute_Ship_Conversion.php
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisors Applications";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $pre_page_cursor = ora_open($conn);
  $verification_cursor = ora_open($conn);
  $testing_cursor = ora_open($conn);

  $error_message = 'none';


  $intended_vessel = $HTTP_POST_VARS['intended_vessel'];
  $warehouse_location = $HTTP_POST_VARS['warehouse_location'];
  $check = $HTTP_GET_VARS['status'];
  $orig_vessel = $HTTP_POST_VARS['orig_vessel'];

// default intended vessel # is the date in mmddyy format
/*  not anymore it doesnt
  if(is_null($intended_vessel)){
	  $intended_vessel = date(mdy);
  }
*/

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Vessel to Inbound Truck Conversion
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($orig_vessel == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="orig" action="http://dspc-s16/supervisors/Truck_To_Ship_Conversion.php" method="post">
	<tr>
		<td align="center"><font size="3" face="Verdana">Current LR#:  <input type="text" name="orig_vessel" size="10" maxlength="10"></font></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Check Vessel"></font></td>
	</tr>
</table>
<?
	} else {

		// this... interesting SQL statement is just a way to figure out if there are entries in both VP and CA
		// that have an arrival number of $orig_vessel, and that are "truckin" status
		$sql = "SELECT * FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP WHERE CA.ARRIVAL_NUM = '".$orig_vessel."' AND VP.LR_NUM = '".$orig_vessel."' AND VP.SHIP_PREFIX = 'TRUCKEDIN'";
		$statement = ora_parse($pre_page_cursor, $sql);
		ora_exec($pre_page_cursor);
		if(!ora_fetch_into($pre_page_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

	// show this if there are no truck-$orig_vessels's in the table
	?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="2%">&nbsp;</td>
		<td><font size="3" face="Verdana">There is currently no Truck-in inventory with a vessel number of <? echo $orig_vessel; ?>.</font></td>
	</tr>
</table>
<?
	// if there are truck-$orig_vessels's in the table, show this
		} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="2%">&nbsp;</td>
		<form action="http://dspc-s16/supervisors/Truck_To_Ship_Conversion.php?status=check" method="post">
		<input type="hidden" name="orig_vessel" value="<? echo $orig_vessel; ?>">
		<td width="30%" align="left">Inbound Order ID:</td>
		<td align="left"><input type="textbox" name="intended_vessel" size="10" value=<? echo $orig_vessel; ?>></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="30%" align="left">Warehouse Location:</td>
		<td align="left"><input type="textbox" name="warehouse_location" size="4" maxlength="1" value=<? echo $warehouse_location; ?>></td>
	</tr>
	<tr>
		<td colspan="3" align="left">&nbsp;&nbsp;&nbsp;<input type="submit" name="unnecessary" value="check"></td>
		</form>
	</tr>
	<tr>
		<td height="2">&nbsp;</td>
	</tr>
<?
	// show this part if the "check" button is pressed and there are truck-$orig_vessels's
			if($check == 'check'){
	// set so that cookies don't cause this next section to show multiple times
				$check = "";
/*
				if(strlen($intended_vessel) != 6 || !is_numeric($intended_vessel)){
					$error_message = "Intended Vessel Number was not a 6 digit number.";
				}
*/
				if(is_numeric($warehouse_location)){
					$error_message = "Warehouse cannot be a number.";
				}

				if(!isset($warehouse_location)){
					$error_message = "Warehouse was not entered.";
				}

				$sql = "SELECT COUNT(*) TOTAL FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$intended_vessel."'";
				$statement = ora_parse($testing_cursor, $sql);
				ora_exec($testing_cursor);
				ora_fetch_into($testing_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				
				if($data['TOTAL'] > 0){
					$error_message = "Vessel Number already in use.";
				}

				$sql = "SELECT COUNT(*) COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$orig_vessel."'";
				$statement = ora_parse($verification_cursor, $sql);
				ora_exec($verification_cursor);
				ora_fetch_into($verification_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$tracking_count = $data['COUNT'];

				$sql = "SELECT COUNT(*) COUNT FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$orig_vessel."'";
				$statement = ora_parse($verification_cursor, $sql);
				ora_exec($verification_cursor);
				ora_fetch_into($verification_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$activity_count = $data['COUNT'];

				if($error_message == 'none'){
?>
	<tr>
		<form action="http://dspc-s16/supervisors/Execute_Ship_Conversion.php" method="post">
		<input type="hidden" name="orig_vessel" value="<? echo $orig_vessel; ?>">
		<td width="2%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">There are <? echo $tracking_count; ?> records (pallets) in inventory for vessel <? echo $orig_vessel; ?>, and <? echo $activity_count; ?> records of activity.</font></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">This program will convert the Vessel Numbers from <? echo $orig_vessel; ?> to Inbound Order ID <? echo $intended_vessel;?> and assign them as being in warehouse <? echo $warehouse_location; ?>.</font></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">If this is all correct, click the "submit" button.</font></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;<input type="hidden" name="vessel_number" value="<? echo $intended_vessel; ?>"><input type="hidden" name="warehouse_choice" value="<? echo $warehouse_location; ?>"></td>
		<td>&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="submit">
		</form>
	</tr>

<?
				} else {
?>
	<tr>
		<td width="2%">&nbsp;</td>
		<td colspan="2"><? echo $error_message; ?></td>
	</tr>
<?
				}
			}
?>
</table>
<?
		}
	}
?>

<? include("pow_footer.php"); ?>
