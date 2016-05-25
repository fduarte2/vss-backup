<?
/*
*
*	Adam Walter, Aug-Sep 2013.
*
*	Main Listing screen for Moroccan Clementine Orders.
*
***********************************************************************************/

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");

	$ordernum = $HTTP_POST_VARS['ordernum'];
	$orderdet = $HTTP_POST_VARS['orderdet'];
	$picklist = $HTTP_POST_VARS['picklist'];
	if($picklist == "NEW"){
		$picklist = "";
	}
	$submit = $HTTP_POST_VARS['submit'];

	$submitted_values = array();

	$submitted_values['pkg'] = $HTTP_POST_VARS['pkg'];
	$submitted_values['plts'] = sql_safe($HTTP_POST_VARS['plts']);
	$submitted_values['pl_size'] = sql_safe($HTTP_POST_VARS['pl_size']);
	$submitted_values['comments'] = sql_safe($HTTP_POST_VARS['comments']);

	if($submit == "Save"){
		// whee!
		$save_message = validate_entries($submitted_values, $action_type, $ordernum, $orderdet, $picklist, $cust, $conn);
		if($save_message == ""){
			// checks passed.  save.

			if($action_type == "ADD"){
				$sql = "INSERT INTO MOR_PICKLIST
							(ORDERNUM,
							ORDERDETAILID,
							PICKLISTID,
							COMMENTS,
							PACKINGHOUSE,
							PALLETQTY,
							PICKLISTSIZE,
							CUST)
						VALUES
							('".$ordernum."',
							'".$orderdet."',
							MOR_PICKLIST_SEQ.NEXTVAL,
							'".$submitted_values['comments']."',
							'".$submitted_values['pkg']."',
							'".$submitted_values['plts']."',
							'".$submitted_values['pl_size']."',
							'".$cust."')";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);

				$sql = "SELECT MAX(PICKLISTID) JUST_INSERTED
						FROM MOR_PICKLIST
						WHERE ORDERNUM = '".$ordernum."'
							AND ORDERDETAILID = '".$orderdet."'
							AND CUST = '".$cust."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
				ocifetch($update);
				$picklist = ociresult($update, "JUST_INSERTED");
			} else {
				$sql = "UPDATE MOR_PICKLIST
						SET 
							COMMENTS = '".$submitted_values['comments']."',
							PACKINGHOUSE = '".$submitted_values['pkg']."',
							PALLETQTY = '".$submitted_values['plts']."',
							PICKLISTSIZE = '".$submitted_values['pl_size']."'
						WHERE
							PICKLISTID = '".$picklist."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
			}

			echo "<font color=\"#0000FF\">Picklist Saved.</font><br><br>";
			ocicommit($conn);
			
		} else {
			// echo error to screen.
			echo "<font color=\"#FF0000\"><b>Changes were not saved for the following reasons:<br><br>".$save_message."<br>Please resubmit after correction.</b></font>";
		}
	}
		

	if($picklist == ""){
		$action_type = "ADD";
//		$new_disabled = "disabled";
//		$not_new_disabled = "";
	} else {
		$action_type = "UPDATE";
//		$new_disabled = "";
//		$not_new_disabled = "disabled";
	}

	$display_info = array();
	get_display_values($display_info, $submitted_values, $submit, $ordernum, $orderdet, $picklist, $cust, $conn);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Picklist Modification</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="4" face="Verdana"><b>Order # <? echo $ordernum; ?></b></font></td>
	</tr>
</table>

<form name="order_info" action="moroccan_picklist_modify_index.php" method="post">
<input type="hidden" name="ordernum" value="<? echo $ordernum; ?>">
<input type="hidden" name="orderdet" value="<? echo $orderdet; ?>">
<input type="hidden" name="picklist" value="<? echo $picklist; ?>">
<input type="hidden" name="action_type" value="<? echo $action_type; ?>">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	$sql = "SELECT MOS.DESCR, MCUST.CUSTOMERNAME, MCON.CONSIGNEENAME, TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP,
				VP.VESSEL_NAME, MO.LOADTYPE, MO.COMMENTS, MO.VESSELID, MO.ORDERSTATUSID
			FROM MOR_ORDER MO, MOR_ORDERSTATUS MOS, MOR_CUSTOMER MCUST, MOR_CONSIGNEE MCON, VESSEL_PROFILE VP
			WHERE MO.ORDERSTATUSID = MOS.ORDERSTATUSID
				AND MO.CUSTOMERID = MCUST.CUSTOMERID
				AND MO.CONSIGNEEID = MCON.CONSIGNEEID
				AND MO.VESSELID = VP.LR_NUM
				AND MO.CUST = '".$cust."'
				AND MO.ORDERNUM = '".$ordernum."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order# <? echo $ordernum; ?> Not found in system for customer <? echo $cust; ?></b></font></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="4"><font size="3" face="Verdana"><b>Order Information:</b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Vessel:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "VESSELID")." - ".ociresult($stid, "VESSEL_NAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Order#:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><b><? echo $ordernum; ?></b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Customer:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Pickup Date:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "THE_PICKUP"); ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Consignee:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "CONSIGNEENAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Comments:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Orderstatus:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "ORDERSTATUSID")." - ".ociresult($stid, "DESCR"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Load Type:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "LOADTYPE"); ?></font></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;<hr></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT ORDERDETAILID, COMMENTS, ORDERQTY, ORDERSIZEID, MOD.PRICE, MOD.SIZEHIGH, MOD.SIZELOW, MOD.WEIGHTKG, DESCR
				FROM MOR_ORDERDETAIL MOD, MOR_COMMODITYSIZE MC
				WHERE ORDERNUM = '".$ordernum."'
					AND MOD.ORDERSIZEID = MC.SIZEID
					AND MOD.ORDERDETAILID = '".$orderdet."'
					AND MOD.CUST = '".$cust."'";
		$details = ociparse($conn, $sql);
		ociexecute($details);
		if(!ocifetch($details)){
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order# <? echo $ordernum; ?> has no Details chosen for it; cannot generate picklist.</b></font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="4"><font size="3" face="Verdana"><b>Order Detail Information:</b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Size:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($details, "ORDERSIZEID")." - ".ociresult($details, "DESCR"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">QTY:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><b><? echo ociresult($details, "ORDERQTY"); ?></b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Size (Low):</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($details, "SIZELOW"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Size (High):</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($details, "SIZEHIGH"); ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Weight:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($details, "WEIGHTKG"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Comments:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($details, "COMMENTS"); ?></font></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;<hr></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Packing House:</font></td>
		<td width="40%" align="left"><select name="pkg"><option value="">Please Select a Packing House</option>
<?
			$sql = "SELECT PACK_HOUSE_ID, PACK_HOUSE_NAME
					FROM MOR_PACK_HOUSE
					WHERE CUST = '".$cust."'
					ORDER BY PACK_HOUSE_ID";
			$stid = ociparse($conn, $sql);
			ociexecute($stid);
			while(ocifetch($stid)){
//				echo "PKG: ".$display_info['pkg']."a<br>result: ".ociresult($stid, "PACK_HOUSE_ID")."a<br>";
//				echo "compare: ".(ociresult($stid, "PACK_HOUSE_ID") == $display_info['pkg'])."<br>";
?>
						<option value="<? echo ociresult($stid, "PACK_HOUSE_ID"); ?>"<? if(ociresult($stid, "PACK_HOUSE_ID") == $display_info['pkg']){?> selected <?}?>><? echo ociresult($stid, "PACK_HOUSE_ID")." (".ociresult($stid, "PACK_HOUSE_NAME").")"; ?></option>

<?
			}
?>
				</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Pallet Count:</font></td>
		<td><input type="text" name="plts" size="5" maxlength="5" value="<? echo $display_info['plts']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Pick List Size:</font></td>
		<td><input type="text" name="pl_size" size="5" maxlength="5" value="<? echo $display_info['pl_size']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Comments:</font></td>
		<td><input type="text" name="comments" size="50" maxlength="50" value="<? echo $display_info['comments']; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"></td>
	</tr>
</table>
</form>
<?
		}
	}


























function sql_safe($variable){
	$variable = str_replace("'", "`", $variable);
	$variable = str_replace("\\", "", $variable);
	$variable = str_replace("\"", "", $variable);
	$variable = str_replace("#", "", $variable);
	$variable = str_replace("@", "", $variable);
	$variable = str_replace("&", "", $variable);
	$variable = trim($variable);

	return $variable;
}

function get_display_values(&$display_info, $submitted_values, $submit, $ordernum, $orderdet, $picklist, $cust, $conn){

	if($submit == "Save"){
		$display_info['pkg'] = $submitted_values['pkg'];
		$display_info['plts'] = $submitted_values['plts'];
		$display_info['pl_size'] = $submitted_values['pl_size'];
		$display_info['comments'] = $submitted_values['comments'];
	} else {
		$sql = "SELECT MP.PACKINGHOUSE, MP.PALLETQTY, MP.PICKLISTSIZE, MP.COMMENTS
				FROM MOR_PICKLIST MP
				WHERE ORDERNUM = '".$ordernum."'
					AND ORDERDETAILID = '".$orderdet."'
					AND PICKLISTID = '".$picklist."'
					AND CUST = '".$cust."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);

		$display_info['pkg'] = trim(ociresult($stid, "PACKINGHOUSE"));
		$display_info['plts'] = ociresult($stid, "PALLETQTY");
		$display_info['pl_size'] = ociresult($stid, "PICKLISTSIZE");
		$display_info['comments'] = ociresult($stid, "COMMENTS");
	}
}



function validate_entries($submitted_values, $action_type, $ordernum, $orderdet, $picklist, $cust, $conn){
	$error = "";

	if($action_type == "UPDATE"){
		$sql = "SELECT ORDERSTATUSID FROM MOR_ORDER WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "ORDERSTATUSID") == "10"){
			$error .= "Order# ".$ordernum." cannot be changed; it's already cancelled.<br>";
		}
	}

	if($submitted_values['pkg'] == ""){
		$error .= "A Packing House must be chosen.<br>";
	}
	if($submitted_values['plts'] == "" || !is_numeric($submitted_values['plts']) || $submitted_values['plts'] != round($submitted_values['plts'])){
		$error .= "Pallet QTY is required and must be a whole number.<br>";
	}
	if($submitted_values['pl_size'] == "" || !is_numeric($submitted_values['pl_size']) || $submitted_values['pl_size'] != round($submitted_values['pl_size'])){
		$error .= "Picklist Size is required and must be a whole number.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values['comments'])){
		$error .= "Comments (if entered) must be AlphaNumeric.<br>";
	}

	return $error;
	
}
