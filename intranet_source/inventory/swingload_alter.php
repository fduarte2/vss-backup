<?
/*
*
*	Adam Walter, Apr 2013.
*
*	Allows Inventory to adjust the SW status of pallets on an order.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel modification";
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
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Alter Pallets" && ($new_arv != "" || $new_cont != "" || $new_whse != "")){
//		$loopcount = $HTTP_POST_VARS['loopcount'];
		$cust = $HTTP_POST_VARS['cust'];
		$order = $HTTP_POST_VARS['order'];
		$new_arv = $HTTP_POST_VARS['new_arv'];
//		$arv = $HTTP_POST_VARS['arv'];
//		$plt = $HTTP_POST_VARS['plt'];
//		$cont = $HTTP_POST_VARS['cont'];
//		$whse = $HTTP_POST_VARS['whse'];
		$new_cont = $HTTP_POST_VARS['new_cont'];
		$new_whse = $HTTP_POST_VARS['new_whse'];

		$set_sql = "SET ";
		if($new_arv != ""){
			$set_sql .= "ARRIVAL_NUM = '".$new_arv."', ";
		} else {
			$set_sql .= "ARRIVAL_NUM = ARRIVAL_NUM, ";
		}
		if($new_cont != ""){
			$set_sql .= "CONTAINER_ID = '".$new_cont."', ";
		} else {
			$set_sql .= "CONTAINER_ID = CONTAINER_ID, ";
		}
		if($new_whse != ""){
			$set_sql .= "WAREHOUSE_LOCATION = '".$new_whse."' ";
		} else {
			$set_sql .= "WAREHOUSE_LOCATION = WAREHOUSE_LOCATION ";
		}


		$check = Validate_Full_Order($cust, $order, $new_arv, $rfconn);
		if($check != ""){
			echo "<font color=\"#FF0000\">".$check."</font>";
		} else {
			$update_count = 0;
			$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CA.SERVICE_CODE = '6'
						AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
						AND CA.ORDER_NUM = '".$order."'
						AND CA.CUSTOMER_ID = '".$cust."'";
			$current = ociparse($rfconn, $sql);
			ociexecute($current);
			while(ocifetch($current)){
				$sql = "UPDATE CARGO_TRACKING
							".$set_sql."
						WHERE PALLET_ID = '".ociresult($current, "PALLET_ID")."'
							AND ARRIVAL_NUM = '".ociresult($current, "ARRIVAL_NUM")."'
							AND RECEIVER_ID = '".$cust."'";
				$change = ociparse($rfconn, $sql);
				ociexecute($change);

				if($new_arv != ""){
					$sql = "UPDATE CARGO_ACTIVITY
							SET ARRIVAL_NUM = '".$new_arv."'
							WHERE PALLET_ID = '".ociresult($current, "PALLET_ID")."'
								AND ARRIVAL_NUM = '".ociresult($current, "ARRIVAL_NUM")."'
								AND CUSTOMER_ID = '".$cust."'";
					$change = ociparse($rfconn, $sql);
					ociexecute($change);
				}

				$update_count++;
			}
		}
		$submit = "Select Order";
						

							
/*
		for($i = 0; $i < $loopcount; $i++){
			if($cont[$i] != $new_cont[$i] || $whse[$i] != $new_whse[$i]){
				$sql = "UPDATE CARGO_TRACKING
						SET CONTAINER_ID = '".$new_cont[$i]."',
							WAREHOUSE_LOCATION = '".$new_whse[$i]."'
						WHERE PALLET_ID = '".$plt[$i]."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$arv[$i]."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			}
		}
*/


		echo "<font color=\"#0000FF\"><b>".$update_count."  Changes Saved.</b></font>";
	}

		


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Swing Load Changes</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="swingload_alter.php" method="post">
	<tr>
		<td width="10%">Customer:</td>
		<td><input type="text" name="cust" size="10" maxlength="10" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td>Outbound Order#:</td>
		<td><input type="text" name="order" size="15" maxlength="15" value="<? echo $order; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Select Order"><br><hr><br></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		$looper = 0;
		$ctn_count = 0;
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="save_changes" action="swingload_alter.php" method="post">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="order" value="<? echo $order; ?>">
	<tr>
		<td colspan="2">Customer:  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td colspan="2">Outbound Order#:  <? echo $order; ?></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<br>&nbsp;</td>
	</tr>
	<tr>
		<td width="15%">New Arrival#:</td>
		<td><input type="text" name="new_arv" size="12" maxlength="12"><font size="2" face="Verdana"> (Leave blank for no change)</font></td>
	</tr>
	<tr>
		<td>New Container:</td>
		<td><input type="text" name="new_cont" size="20" maxlength="20"><font size="2" face="Verdana"> (Leave blank for no change)</font></td>
	</tr>
	<tr>
		<td>New Warehouse/SW Status:</td>
		<td><input type="text" name="new_whse" size="12" maxlength="12"><font size="2" face="Verdana"> (Leave blank for no change)</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Alter Pallets"></td>
	</tr>
<?
		$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.CONTAINER_ID, CT.WAREHOUSE_LOCATION, CA.QTY_CHANGE
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CA.SERVICE_CODE = '6'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = '".$order."'
					AND CA.CUSTOMER_ID = '".$cust."'";
		$current = ociparse($rfconn, $sql);
		ociexecute($current);
		if(!ocifetch($current)){
?>
	<tr>
		<td colspan="2"><font color="#FF0000">This customer/order combination does not have any non-voided shipout activity in our system.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="2"><table border="1" width="100%" cellpadding="3" cellspacing="0">
			<tr>
				<td>Pallet ID</td>
				<td>Arrival#</td>
				<td>QTY Shipped</td>
				<td>Container ID</td>
				<td>Swingload Status</td>
			</tr>
<?
			do {
?>
<!-- <input type="hidden" name="arv[<? echo $looper; ?>]" value="<? echo ociresult($current, "ARRIVAL_NUM"); ?>"> !-->
<!-- <input type="hidden" name="plt[<? echo $looper; ?>]" value="<? echo ociresult($current, "PALLET_ID"); ?>"> !-->
<!-- <input type="hidden" name="cont[<? echo $looper; ?>]" value="<? echo ociresult($current, "CONTAINER_ID"); ?>"> !-->
<!-- <input type="hidden" name="whse[<? echo $looper; ?>]" value="<? echo ociresult($current, "WAREHOUSE_LOCATION"); ?>"> !-->
			<tr>
				<td><? echo ociresult($current, "PALLET_ID"); ?></td>
				<td><? echo ociresult($current, "ARRIVAL_NUM"); ?></td>
				<td><? echo ociresult($current, "QTY_CHANGE"); ?></td>
				<td><? echo ociresult($current, "CONTAINER_ID"); ?>&nbsp;</td>
				<td><? echo ociresult($current, "WAREHOUSE_LOCATION"); ?>&nbsp;</td>
			</tr>
<?
				$ctn_count += ociresult($current, "QTY_CHANGE");
				$looper++;
			} while(ocifetch($current));
?>
			<tr>
				<td colspan="2"><b>Total Pallets:  <? echo $looper; ?></b></td>
				<td colspan="3"><b>Total Ctns:  <? echo $ctn_count; ?></b></td>
			</tr>
		</table></td>
	</tr>
<!-- <input type="hidden" name="loopcount" value="<? echo $looper; ?>"> !-->
<?
		}
	}
	include("pow_footer.php");









function Validate_Full_Order($cust, $order, $new_arv, $rfconn){
	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.CONTAINER_ID, CT.WAREHOUSE_LOCATION, CA.QTY_CHANGE
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CA.SERVICE_CODE = '6'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = '".$order."'
					AND CA.CUSTOMER_ID = '".$cust."'";
	$current = ociparse($rfconn, $sql);
	ociexecute($current);
	while(ocifetch($current)){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".ociresult($current, "PALLET_ID")."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$new_arv."'";
		$check = ociparse($rfconn, $sql);
		ociexecute($check);
		ocifetch($check);
		if(ociresult($check, "THE_COUNT") >= 1){
			return "Pallet ID ".ociresult($current, "PALLET_ID")." cannot be changed to vessel ".$new_arv.", that pallet already exists for that vessel.  Operation cancelled.";
		}
	}

	return "";
}
		


?>