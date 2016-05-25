<?
/* Adam Walter, October 2006.
*  This script allows OPS to add to / create orders manually for Clementine
*  fruit.  The logic of this program seems to be lacking, but it's because of 2 things:
*  1)  There is no "order" table to deal with in RF, as all orders are solely defined 
*  as the sum of their Cargo_activity values.
*  2)  I do not update Cargo_tracking with new QTY_IN_HOUSE values because
*  there is a Database trigger that automatically subtracts from QTY_IN_HOUSE
*  whenever a Crago_activity entry is made for a given pallet #.
*
*  modified Feb 28, 2007.  Making most of the input fields optional.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine Function Area";
  $area_type = "INVE"; /// ??? no marketing area :(

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  $cursor = ora_open($conn);

  $order_number = $HTTP_POST_VARS['order_number'];
  $vessel_number = $HTTP_POST_VARS['vessel_number'];
  $submit = $HTTP_POST_VARS['submit'];
  
  $pkg_house = $HTTP_POST_VARS['pkg_house'];
  if(strlen($pkg_house) < 4 || strlen($pkg_house) > 5){
	  $pkg_house = "\n";
  }

  $qty_label = $HTTP_POST_VARS['qty_label'];
  // wierd way to get around the error that IE defaults to turning \n into \r\n
  if(trim($qty_label) == "" || trim($qty_label) == "00" || $qty_label == "00\n"){
	  $qty_label = "\n";
  }
  if(strlen($qty_label) < 4 && $qty_label != "" && $qty_label != "\n" && $qty_label != "00\n"){
	  for($i = strlen($qty_label); $i < 4; $i++){
		  $qty_label = "0".$qty_label;
	  }
  }

//  echo $qty_label;


  $from_vessel = $HTTP_POST_VARS['from_vessel'];
//  $all_vessel = $HTTP_POST_VARS['all_vessel'];

  if($from_vessel == "" && $submit == "Retrieve Order"){
	  $from_vessel = $vessel_number;
  }




  $size_count = $HTTP_POST_VARS['size_count'];

  if($size_count == ""){
	  $size_count = "\n";
  }
  
  $temp = split("-", $HTTP_POST_VARS['add_pallet_id']);
  $add_pallet_id = $temp[0];
  $add_pallet_vessel = $temp[1];
  $pallet_update_amount = $HTTP_POST_VARS['pallet_update_amount'];
  $pallet_update_damaged = $HTTP_POST_VARS['pallet_update_damaged'];
  if($pallet_update_damaged == ""){
	  $pallet_update_damaged = 0;
  }

  $pallet_6digit = $HTTP_POST_VARS['pallet_6digit'];

  if($pallet_6digit == ""){
	  $pallet_6digit = "\n";
  }

  $now = date('m/d/Y H:i');

  if($HTTP_POST_VARS['customer_number'] != ""){
	  $customer_number = $HTTP_POST_VARS['customer_number'];
  } else {
	  // not 439 anymore, now 835
	  $customer_number = 835;
  }

  if($submit == "Add Entry" && $pallet_update_amount > 0){
// add the information, assuming a valid amount of QTY is entered
	  $sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$add_pallet_id."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $outer_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($outer_row['QTY_IN_HOUSE'] < $pallet_update_amount || $pallet_update_damaged > $pallet_update_amount){
		  echo "<font size=\"3\" color=\"FF0000\">INVALID QUANTITY ENTERED.</font><BR>";
	  } else {
// lots of logic here
		  $current_damaged = $outer_row['QTY_DAMAGED'];
		  $new_damaged = $current_damaged + $pallet_update_damaged;

		  $insert_arrival_num = $outer_row['ARRIVAL_NUM'];

//		  $vessel_number = $outer_row['ARRIVAL_NUM'];

		  $sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$add_pallet_id."' AND ARRIVAL_NUM = '".$add_pallet_vessel."'";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
		  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $new_activity_num = $row['THE_MAX'];
		  if($new_activity_num == ""){
			  $new_activity_num = 1;
		  }

		  $sql = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = '".$new_damaged."' WHERE PALLET_ID = '".$add_pallet_id."' AND ARRIVAL_NUM = '".$add_pallet_vessel."'";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
		  $sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM) VALUES ('".$new_activity_num."', '6', '".$pallet_update_amount."', '0', 'Damaged:".$pallet_update_damaged."', '".$order_number."', '".$customer_number."', to_date('".$now."', 'MM/DD/YYYY HH24:MI'), '".$add_pallet_id."', '".$add_pallet_vessel."')";
  		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
	  }
  }


  


?>

<script type="text/javascript">
   function change_vessel_status() {
	   if (document.update_stuff.all_vessel.checked == true){
		   document.update_stuff.from_vessel.disabled = true
	   } else {
		   document.update_stuff.from_vessel.disabled = false
	   }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementines: Manual Order Entry</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="retrieve_stuff" action="clementine_manual_entry.php" method="post">
		<td width="25%" align="center">Order #: <input type="text" size="10" maxlength="15" name="order_number" value="<? echo $order_number; ?>"></td>
		<td width="25%" align="center">LR #: <input type="text" size="10" maxlength="10" name="vessel_number" value="<? echo $vessel_number; ?>"></td>
		<td width="25%" align="center">Customer #: <input type="text" size="10" maxlength="10" name="customer_number" value="<? echo $customer_number; ?>"></td>
		<td width="25%" align="center"><input type="submit" name="submit" value="Retrieve Order"></td>
	</form>
	</tr>
<?
	// only display if a button is pressed AND an info is present... I.E. the rest of the page.
	if(($submit == "Retrieve Order" || $submit == "Add Entry" || $submit == "List Available") && $order_number != "" && $vessel_number != "" && $customer_number != ""){
?>
	<tr>
	<form name="update_stuff" action="clementine_manual_entry.php" method="post">
	<input type="hidden" name="order_number" value="<? echo $order_number; ?>">
	<input type="hidden" name="customer_number" value="<? echo $customer_number; ?>">
	<input type="hidden" name="vessel_number" value="<? echo $vessel_number; ?>">
		<td colspan="4" align="center">
		<hr>
		<table border="0" cellpadding="4" cellspacing="0">
			<tr>
				<td align="center" colspan="6"><font size="3" face="Verdana">Select Pallet to Add:</font></td>
			</tr>
			<tr>
				<td width="18%" align="center"><font size="2" face="Verdana">Packaging House (4 or 5 characters): <br><input type="text" name="pkg_house" size="10" maxlength="10" value="<? echo $pkg_house; ?>"></font></td>
				<td width="20%" align="center"><font size="2" face="Verdana">Quantity on Label: <br><input type="text" name="qty_label" size="10" maxlength="10" value="<? echo $qty_label; ?>"></font></td>
				<td width="20%" align="center"><font size="2" face="Verdana">Size / Count: <br><input type="text" name="size_count" size="10" maxlength="10" value="<? echo $size_count; ?>"></font></td>
				<td width="13%" align="center"><font size="2" face="Verdana">Pallet ID<br><input type="text" name="pallet_6digit" size="10" maxlength="10" value="<? echo $pallet_6digit; ?>"></font></td>
				<td width="13%" align="center"><font size="2" face="Verdana">From Vessel:<br><input type="text" name="from_vessel" size="10" maxlength="10" value="<? echo $from_vessel; ?>"></font></td>
				<td align="right"><input type="submit" name="submit" value="List Available"></td>
			</tr>
		</table>
		</td>
	</form>
	</tr>
<?
// this area is done if the "List Available" button or the "Add entry" button is pressed
//		if(($submit == "List Available" || $submit == "Add Entry") && $order_number != ""){
		if(($submit == "List Available") && $order_number != ""){
			$sqlbody = "FROM CARGO_TRACKING CT, CLM_SIZEMAP CS WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM AND CT.RECEIVER_ID = '".$customer_number."' AND CT.QTY_IN_HOUSE > 0 AND ARRIVAL_NUM NOT LIKE '91%'";
			if($pkg_house != "\n"){
				$sqlbody .= " AND CT.EXPORTER_CODE LIKE '".$pkg_house."%'";
			}
			if($qty_label != "\n"){
				$sqlbody .= " AND SUBSTR(CT.PALLET_ID, 25, 4) = '".$qty_label."'";
			}
			if($size_count != "\n"){
				$sqlbody .= " AND CS.DC = '".$size_count."'";
			}
			if($pallet_6digit != "\n"){
				$sqlbody .= " AND SUBSTR(CT.PALLET_ID, 19, 6) LIKE '%".$pallet_6digit."%'";
			}
			if($from_vessel != ""){ 
				$sqlbody .= " AND CT.ARRIVAL_NUM = '".$from_vessel."'";
			}


			$sql = "SELECT COUNT(*) THE_COUNT ".$sqlbody;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] < 1){
?>
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana"><i>No pallets that match input values</i></font></td>
	</tr>
<?
			} else {
				$total_rows = 0;
				$sql = "SELECT * ".$sqlbody." ORDER BY ARRIVAL_NUM, PALLET_ID";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
?>
	<form name="insert_pallet" action="clementine_manual_entry.php" method="post">
	<input type="hidden" name="order_number" value="<? echo $order_number; ?>">
	<input type="hidden" name="customer_number" value="<? echo $customer_number; ?>">
	<input type="hidden" name="vessel_number" value="<? echo $vessel_number; ?>">
	<input type="hidden" name="pkg_house" value="<? echo $pkg_house; ?>">
	<input type="hidden" name="qty_label" value="<? echo $qty_label; ?>">
	<input type="hidden" name="size_count" value="<? echo $size_count; ?>">
	<input type="hidden" name="from_vessel" value="<? echo $from_vessel; ?>">
	<tr>
		<td colspan="4" align="center">
		<table border="1" cellpadding="2" cellspacing="0">
			<tr>
				<td><font size="3" face="Verdana" align="center">Vessel</font></td>
				<td><font size="3" face="Verdana" align="center">Pallet</font></td>
				<td><font size="3" face="Verdana" align="center">QTY available</font></td>
				<td><font size="3" face="Verdana" align="center">Add to Order:</font></td>
<?
				while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td align="center"><font size="2" face="Verdana" align="center"><? echo $row['ARRIVAL_NUM']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['QTY_IN_HOUSE']; ?></font></td>
				<td align="center"><input type="radio" name="add_pallet_id" size="10" maxlength="10" value="<? echo $row['PALLET_ID']."-".$row['ARRIVAL_NUM']; ?>"></td>
			</tr>
<?
				}
?>
			<tr>
				<td colspan="4" align="center">QTY from pallet to Add:  <input type="text" name="pallet_update_amount" size="10" maxlength="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Damaged:  <input type="text" name="pallet_update_damaged" size="10" maxlength="10"></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><input type="submit" name="submit" value="Add Entry"></td>
			</tr>
		</table></td>
	</tr>
	</form>
<?
			}
		}
?>
	<tr>
		<td colspan="4" align="center"><hr><font size="3" face="Verdana">Order <? echo $order_number; ?> for Customer <? echo $customer_number; ?> on Vessel <? echo $vessel_number; ?>:</font></td>
	</tr>
<?
		$sql = "SELECT count(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_number."' AND ARRIVAL_NUM = '".$vessel_number."' AND CUSTOMER_ID = '".$customer_number."' ORDER BY PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$rowcount = $row['THE_COUNT'];

		if($rowcount < 1){
// this part is if there does not exist an order for the supplied number
?>
	<tr>
		<td colspan="4" align="center"><font size="2" face="Verdana"><i>No records for given parameters at this time</i></font></td>
	</tr>
<?
		} else {
// this part for if there are records for given order
?>
	<tr>
		<td colspan="4" align="center">
		<table border="1" cellpadding="4" cellspacing="0">
			<tr>
				<td align="center">#</td>
				<td align="center">Pallet Barcode</td>
				<td align="center">LR Number</td>
				<td align="center">Date of Activity</td>
				<td align="center">QTY shipped</td>
				<td align="center">Description:</td>
			</tr>
<?
			$temp_counter = 0;
			$sql = "SELECT PALLET_ID PALLET, ARRIVAL_NUM, to_char(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH12:MI AM') THE_DATE, QTY_CHANGE, ACTIVITY_DESCRIPTION FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_number."' AND CUSTOMER_ID = '".$customer_number."' ORDER BY PALLET_ID";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$temp_counter++;
?>
			<tr>
				<td align="center"><font size="2" face="Verdana"><? echo $temp_counter; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['PALLET']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['QTY_CHANGE']; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $row['ACTIVITY_DESCRIPTION']; ?></font></td>
			</tr>
<?
			}
?>
		</table></td>
	</tr>
<?
		}
	} // end section that displays if ANY button is pressed at all
?>
</table>

<? include("pow_footer.php"); ?>