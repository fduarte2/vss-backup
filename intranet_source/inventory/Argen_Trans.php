<?
/*
*	Adam Walter, Jun 2011.
*
*	Page to "pre-transfer cargo" fro Argen Juice.
*
*	A-J customers might barcode their cargo, but they DO NOT CARE
*	which pallets actually get transferred, and the cueckers
*	also do not know (or care).  As such, we have a procedure (for now)
*	To tranfer quantites, and then have the scanner resolve the transfer
*	At time of shipout.
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$modify_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);


	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Retrieve Available Cargo"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cust_from = $HTTP_POST_VARS['cust_from'];
		$comm = $HTTP_POST_VARS['comm'];

		if($vessel == "" || $cust_from == "" || $comm == ""){
			echo "<font color=\"#FF0000\">All 3 choices must be selected</font>";
			$submit = "";
		}
	}

	if($submit == "Save Pallet Transfer"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cust_from = $HTTP_POST_VARS['cust_from'];
		$comm = $HTTP_POST_VARS['comm'];
		$trans_num = $HTTP_POST_VARS['trans_num'];
		$date = $HTTP_POST_VARS['date'];
		$cust_to = $HTTP_POST_VARS['cust_to'];
		$BoL = $HTTP_POST_VARS['BoL']; // array
		$Mark = $HTTP_POST_VARS['Mark']; // array
		$qty_trans = $HTTP_POST_VARS['qty_trans']; // array

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGJUICE_TRANSFERS
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CUSTOMER_FROM = '".$cust_from."'
					AND CUSTOMER_TO = '".$cust_to."'
					AND TRANSFER_NUM = '".$trans_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] > 0){
			echo "<font color=\"#FF0000\">Transfer# already used.</font>";
			$submit = "Retrieve Available Cargo";
		} elseif($trans_num == "" || $cust_to == ""){
			echo "<font color=\"#FF0000\">Transfer# and destination customer must be specified; Please Re-select.</font>";
			$submit = "Retrieve Available Cargo";
		} elseif(!ereg("^([0-9a-zA-Z _-])+$", $trans_num)) {
			echo "<font color=\"#FF0000\">Transfer# can only contain letters, numbers, spaces, and dashes.</font>";
			$submit = "Retrieve Available Cargo";
		} elseif(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)) {
			echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font>";
			$submit = "Retrieve Available Cargo";
		} else {
			// all data was entered; validate, then save
			$error_msg = "";
			$success_msg = "";
			if(!all_rows_validate($vessel, $cust_from, $comm, $BoL, $Mark, $qty_trans, $conn, &$error_msg)){
				echo "<font color=\"#FF0000\">Could not save transfer:<br>".$error_msg."<br>Please Re-enter.</font>";
				$submit = "Retrieve Available Cargo";
			} else {
				for($i = 0; $i < 10; $i++){
					if($BoL[$i] != "" && $Mark[$i] != "" && $qty_trans[$i] != ""){
						$sql = "INSERT INTO ARGJUICE_TRANSFERS
									(ARRIVAL_NUM,
									COMMODITY_CODE,
									CUSTOMER_FROM,
									CUSTOMER_TO,
									TRANSFER_NUM,
									BOL,
									CARGO_DESCRIPTION,
									QTY_TO_TRANS,
									QTY_LEFT_TO_TRANS,
									ACTUAL_DATE,
									INSERT_BY,
									INSERT_ON)
								VALUES
									('".$vessel."',
									'".$comm."',
									'".$cust_from."',
									'".$cust_to."',
									'".$trans_num."',
									'".$BoL[$i]."',
									'".$Mark[$i]."',
									'".$qty_trans[$i]."',
									'".$qty_trans[$i]."',
									TO_DATE('".$date."', 'MM/DD/YYYY'),
									'".$user."',
									SYSDATE)";
//						echo $sql."<br>";
						ora_parse($modify_cursor, $sql);
						ora_exec($modify_cursor);

						$success_msg .= "BoL:  ".$BoL[$i]."  Mark:  ".$Mark[$i]."  <b>QTY:  ".$qty_trans[$i]."</b><br>";
					}
				}

				echo "<font color=\"#0000CC\">Transfer Saved to RF system.<br>".$success_msg."<br>";
				$submit = "";
			}
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Argen Juice Scanner-Cargo Transfer Screen
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="Argen_Trans.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:</td>
		<td><select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT DISTINCT CT.ARRIVAL_NUM, NVL(LR_NUM, 0) THE_VES, DECODE(VESSEL_NAME, NULL, 'N/A', LR_NUM || '-' || VESSEL_NAME) THE_VESSEL 
				FROM VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
				ORDER BY NVL(LR_NUM, 0) DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['ARRIVAL_NUM']; ?>"<? if($row['ARRIVAL_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Customer (from):</td>
		<td><select name="cust_from">
						<option value="">Please Select a Customer</option>
<?
		$sql = "SELECT DISTINCT CUSP.CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE CUSP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
				ORDER BY CUSTOMER_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust_from){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Commodity:</td>
		<td><select name="comm">
						<option value="">Please Select a Commodity</option>
<?
		$sql = "SELECT DISTINCT COMP.COMMODITY_CODE, COMMODITY_NAME 
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
				ORDER BY COMMODITY_CODE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['COMMODITY_CODE']; ?>"<? if($row['COMMODITY_CODE'] == $comm){ ?> selected <? } ?>><? echo $row['COMMODITY_CODE']." - ".$row['COMMODITY_NAME']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Retrieve Available Cargo"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Available Cargo"){
		// make sure there is cargo available...
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE QTY_IN_HOUSE > 0
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$cust_from."'
					AND COMMODITY_CODE = '".$comm."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			echo "<font color=\"#FF0000\">No Cargo available in house for transfer matching selected criteria.</font>";
			include("pow_footer.php");
		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="Argen_Trans.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cust_from" value="<? echo $cust_from; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
	<tr>
		<td align="left"><font size="2" face="Verdana">Transfer#:</td>
		<td><input type="text" name="trans_num" size="12" maxlength="12" value="<? echo $trans_num; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Effective Date</td>
		<td><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Customer (to):</td>
		<td><select name="cust_to">
														<option value="">Please Select a Customer</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_STATUS = 'ACTIVE' ORDER BY CUSTOMER_ID ASC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust_to){?> selected <?}?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
		}
?>
			<select></font></td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" width="600" cellpadding="4" cellspacing="0">
				<tr>
					<td><font face="Verdana" size="2"><b>BoL</b></font></td>
					<td><font face="Verdana" size="2"><b>Mark</b></font></td>
					<td><font face="Verdana" size="2"><b>QTY to transfer</b></font></td>
				</tr>
<?
		for($i = 0; $i < 10; $i++){
?>
				<tr>
					<td><select name="BoL[<? echo $i; ?>]"><option value="">Select a BoL:</option>
<?
			$sql = "SELECT DISTINCT BOL
					FROM CARGO_TRACKING
					WHERE QTY_IN_HOUSE > 0
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust_from."'
						AND COMMODITY_CODE = '".$comm."'
					ORDER BY BOL";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($BoL[$i] == $row['BOL']){
					echo "<option value=\"".$row['BOL']."\" selected>".$row['BOL']."</option>";
				} else {
					echo "<option value=\"".$row['BOL']."\">".$row['BOL']."</option>";
				}
			}	
?>
						</select></td>
					<td><select name="Mark[<? echo $i; ?>]"><option value="">Select a Mark:</option>
<?
			$sql = "SELECT CARGO_DESCRIPTION, COUNT(DISTINCT PALLET_ID) THE_PLT
					FROM CARGO_TRACKING
					WHERE QTY_IN_HOUSE > 0
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust_from."'
						AND COMMODITY_CODE = '".$comm."'
					GROUP BY CARGO_DESCRIPTION";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($Mark[$i] == $row['CARGO_DESCRIPTION']){
					echo "<option value=\"".$row['CARGO_DESCRIPTION']."\" selected>".$row['CARGO_DESCRIPTION']." - InHouse: ".$row['THE_PLT']."</option>";
				} else {
					echo "<option value=\"".$row['CARGO_DESCRIPTION']."\">".$row['CARGO_DESCRIPTION']." - InHouse: ".$row['THE_PLT']."</option>";
				}
			}		
?>
						</select></td>
					<td><input type="text" name="qty_trans[<? echo $i; ?>]" size="4" maxlength="4" value="<? echo $qty_trans[$i]; ?>"></td>
				</tr>
<?
		}
?>
				<tr>
					<td colspan="3" align="left"><input type="submit" name="submit" value="Save Pallet Transfer"></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");









function all_rows_validate($vessel, $cust_from, $comm, $BoL, $Mark, $qty_trans, $conn, &$error_msg){
	$Short_Term_Cursor = ora_open($conn);

	// make sure no duplicate rows
	for($i = 0; $i < 10; $i++){
		if(count(array_keys($BoL, $BoL[$i])) >= 2
				&& count(array_keys($Mark, $Mark[$i])) >= 2
				&& $BoL[$i] != ""
				&& $Mark[$i] != ""){

			$error_msg .= "Duplicate BoL/Mark found on line ".($i + 1)."<br>";
		}
	}
	
	if($error_msg == ""){
		// if no duplicates, then check quantities
		for($i = 0; $i < 10; $i++){
			if(!ereg("^([0-9])*$", $qty_trans[$i])){
				$error_msg .= "Line ".($i + 1)." transfer quantity must be a number.<br>";
			} elseif($BoL[$i] != "" && $Mark[$i] != "" && $qty_trans[$i] != "") {
				$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLT
						FROM CARGO_TRACKING
						WHERE QTY_IN_HOUSE > 0
							AND ARRIVAL_NUM = '".$vessel."'
							AND RECEIVER_ID = '".$cust_from."'
							AND COMMODITY_CODE = '".$comm."'
							AND BOL = '".$BoL[$i]."'
							AND CARGO_DESCRIPTION = '".$Mark[$i]."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['THE_PLT'] < $qty_trans[$i]){
					$error_msg .= "Line ".($i + 1)." cannot transfer more than is in house (or is Mark/BoL mismatch).<br>";
				}
			}
		}
	}

	if($error_msg == ""){
		return true;
	} else {
		return false;
	}
}
?>