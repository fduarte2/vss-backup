<?
/*
*	Adam Walter, Nov-Dec 2011
*
*	Page to deleted specific pallets from a prebill.
*************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
	printf("Error logging on to the BNI Oracle Server: ");
	printf(ora_errorcode($conn));
	printf("Please try later!");
	exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_modify = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$bill_num = $HTTP_POST_VARS['bill_num'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Delete Pallets"){
		$deleted_pallets = "";
		$delete_Y_N = $HTTP_POST_VARS['delete_Y_N'];

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM RF_BILLING_DETAIL
				WHERE SUM_BILL_NUM = '".$bill_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$max_rows = $short_term_row['THE_COUNT'];

		for($i = 0; $i < $max_rows; $i++){
			if($delete_Y_N[$i] != ""){
				$sql = "SELECT SERVICE_QTY, SERVICE_QTY2, SERVICE_AMOUNT
						FROM RF_BILLING_DETAIL
						WHERE SUM_BILL_NUM = '".$bill_num."'
							AND PALLET_ID = '".$delete_Y_N[$i]."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty1 = $short_term_row['SERVICE_QTY'];
				$qty2 = $short_term_row['SERVICE_QTY2'];
				$amt = $short_term_row['SERVICE_AMOUNT'];

				$sql = "UPDATE RF_BILLING_DETAIL
						SET SERVICE_STATUS = 'DELETED'
						WHERE SUM_BILL_NUM = '".$bill_num."'
							AND PALLET_ID = '".$delete_Y_N[$i]."'";
				ora_parse($cursor_modify, $sql);
				ora_exec($cursor_modify);

				$sql = "UPDATE RF_BILLING
						SET SERVICE_QTY = SERVICE_QTY - ".$qty1.",
							SERVICE_QTY2 = SERVICE_QTY2 - ".$qty2.",
							SERVICE_AMOUNT = SERVICE_AMOUNT - ".$amt."
						WHERE BILLING_NUM = '".$bill_num."'";
				ora_parse($cursor_modify, $sql);
				ora_exec($cursor_modify);

				$deleted_pallets .= "<br>".$delete_Y_N[$i];
			}
		}

		$sql = "SELECT TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START 
				FROM RF_BILLING 
				WHERE BILLING_NUM = '".$bill_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$bill_date = $row['THE_START'];

		$sql = "UPDATE CARGO_TRACKING CT 
				SET BILLING_STORAGE_DATE = TO_DATE('".$bill_date."', 'MM/DD/YYYY')
				WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) = 
				(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM RF_BILLING_DETAIL RBD
				WHERE SUM_BILL_NUM = '".$bill_num."'
					AND RBD.SERVICE_STATUS = 'DELETED'
					AND CT.PALLET_ID = RBD.PALLET_ID
					AND CT.RECEIVER_ID = RBD.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = RBD.ARRIVAL_NUM)";
		ora_parse($cursor_modify, $sql);
		ora_exec($cursor_modify);

		echo "<font color=\"#0000FF\">The following pallets have been removed from bill ".$bill_num.":".$deleted_pallets."<br>";
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Remove Pallets from Storage Prebills</font>
         <hr>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bill" action="delete_pallet_from_storage_bill.php" method="post">
	<tr>
		<td>Pre-Invoice#:&nbsp;&nbsp;<input type="text" name="bill_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Pre-Invoice"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Pre-Invoice"){
		if($bill_num == "" || !is_numeric($bill_num)) {
			echo "<font color=\"#FF0000\">Invoice # must be entered, and numeric.</font>";
		} else {
			$sql = "SELECT SERVICE_STATUS FROM RF_BILLING WHERE BILLING_NUM = '".$bill_num."'";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			if(!ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				echo "<font color=\"#FF0000\">Invoice # does not exist.</font>";
			} elseif($first_row['SERVICE_STATUS'] != 'PREINVOICE'){
				echo "<font color=\"#FF0000\">Can only modify Preinvoices; Billing number ".$bill_num." shows as being status ".$first_row['SERVICE_STATUS']."</font>";
			} else {
				// finally, passed checks.  display pallets on storage prinvoice.
				$sql = "SELECT CUSTOMER_ID, ARRIVAL_NUM, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, 
							COMMODITY_CODE, PALLET_ID, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT
						FROM RF_BILLING_DETAIL
						WHERE SUM_BILL_NUM = '".$bill_num."'
							AND SERVICE_STATUS != 'DELETED'
						ORDER BY PALLET_ID";
				ora_parse($cursor_second, $sql);
				ora_exec($cursor_second);
				if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					echo "<font color=\"#FF0000\">No Pallets found in Prebill '".$bill_num."'</font><br>";
				} else {
					$rowcounter = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="delete_plts" action="delete_pallet_from_storage_bill.php" method="post">
<input type="hidden" name="bill_num" value="<? echo $bill_num; ?>">
	<tr bgcolor="#DDEEDD">
		<td align="center" colspan="9"><font size="3" face="Verdana"><b>Prebill Search Results:  Prebill#<? echo $bill_num; ?><br>NOTE:  PALLETS DELETED FROM PREBILL CANNOT BE UNDONE.<br>If it turns out the pallets did, in fact, need to be billed, a new bill will need to be generated.</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Delete?</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Cust</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Bill Start</b></font></td>
		<td><font size="2" face="Verdana"><b>Bill End</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty Billed</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
	</tr>
<?
					do {
						if($bgcolor == "#FFFFFF"){
							$bgcolor = "#EEEEEE";
						} else {
							$bgcolor = "#FFFFFF";
						}
?>
<!--	<input type="hidden" name="barcode[<? echo $rowcounter; ?>]" value="<? echo $second_row['PALLET_ID']; ?>"> !-->
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><input type="checkbox" name="delete_Y_N[<? echo $rowcounter; ?>]" value="<? echo $second_row['PALLET_ID']; ?>"></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['CUSTOMER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['COMMODITY_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['THE_START']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['THE_END']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['SERVICE_QTY']." (".$second_row['SERVICE_UNIT'].")"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $second_row['SERVICE_AMOUNT']; ?></font></td>
	</tr>
<?
						$rowcounter++;
					} while(ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Delete Pallets"></td>
	</tr>
</form>
</table>
<?
				}
			}
		}
	}
	include("pow_footer.php");
?>
