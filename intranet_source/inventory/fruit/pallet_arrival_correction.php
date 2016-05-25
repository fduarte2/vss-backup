<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Pallet Arrival Correction";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_Owner@RF", "owner");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn);


	$pallet_id = $HTTP_POST_VARS['pallet_id'];
	$customer_id = $HTTP_POST_VARS['customer_id'];
	$old_arrival_number = $HTTP_POST_VARS['old_arrival_number'];
	$new_arrival_number = $HTTP_POST_VARS['new_arrival_number'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "submit"){
		if($pallet_id != "" && $old_arrival_number != "" && $new_arrival_number != "" && $customer_id != ""){
			$sql = "SELECT RECEIVING_TYPE FROM CARGO_TRACKING WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND RECEIVER_ID = '".$customer_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$is_trucked = $row['RECEIVING_TYPE'];

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND RECEIVER_ID = '".$customer_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$CT_count = $row['THE_COUNT'];

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND CUSTOMER_ID = '".$customer_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$CA_count = $row['THE_COUNT'];

			$sql = "UPDATE CARGO_TRACKING SET ARRIVAL_NUM = '".$new_arrival_number."' WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND RECEIVER_ID = '".$customer_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);


			if($is_trucked != "T"){
				$sql = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '".$new_arrival_number."' WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND CUSTOMER_ID = '".$customer_id."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			} else {
				$sql = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = '".$new_arrival_number."' WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND ACTIVITY_NUM = 1 AND CUSTOMER_ID = '".$customer_id."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '".$new_arrival_number."' WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$old_arrival_number."' AND CUSTOMER_ID = '".$customer_id."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			}
		} else {
			echo "<font color=ff0f00 size=3 face=verdana>Not all fields were entered, no data changed.</font>";
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="5" face="Verdana" color="#0066CC">Edit Pallet Arrival</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="1" cellspacing="0">
<?
	if($submit == "submit" && $pallet_id != "" && $old_arrival_number != "" && $new_arrival_number != "" && $customer_id != ""){
?>
	<tr>
		<td colspan="3"><font color="663300" size="2" face="Verdana"><? echo $CT_count; ?> Inventory records and <? echo $CA_count; ?> Activity Records modified.</font></td>
	</tr>
<?
		if($is_trucked == "T"){
?>
	<tr>
		<td colspan="3"><font color="663300" size="2" face="Verdana">Note that since the pallet was trucked in, the received Order Number has also been changed to reflect the new Arrival Number.</font></td>
	</tr>
<?
		}
	}
?>
<form name="whosawhatchit" action="pallet_arrival_correction.php" method="post">
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Pallet #:</font></td>
		<td><input type="text" size="15" maxlength="20" value="<? echo $pallet_id; ?>" name="pallet_id"></td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Customer #:</font></td>
		<td><input type="text" size="15" maxlength="20" value="<? echo $customer_id; ?>" name="customer_id"></td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Old Arrival #:</font></td>
		<td><input type="text" size="15" maxlength="20" value="<? echo $old_arrival_number; ?>" name="old_arrival_number"></td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">New Arrival #:</font></td>
		<td><input type="text" size="15" maxlength="20" value="<? echo $new_arrival_number; ?>" name="new_arrival_number"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="submit"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">Note:  This page is to be used if a pallet was entered into our system twice;<br>once with an incorrect arrival# (which then had activity run against it), and afterwards, entered again correctly.<br>To use this program, delete the CORRECT pallet from inventory first,<br>then change the INCORRECT one via this screen.</td>
	</tr>
</form>
</table>
<? include("pow_footer.php"); ?>
