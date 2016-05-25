<?
/*
*		Adam Walter, June 2014.
*
*		Page for Finance to "delete" bills using the new (as of 6/2014) system
*********************************************************************************/

//	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Print Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }


	$submit == $HTTP_POST_VARS['submit'];
	$prebill == $HTTP_POST_VARS['prebill'];
	$date_from == $HTTP_POST_VARS['date_from'];
	$date_to == $HTTP_POST_VARS['date_to'];
	if($submit == "Retrieve Prebill" && ($prebill == "" && ($date_from == "" || $date_to == ""))){
		echo "<font color=\"#FF0000\">Please enter a Prebill# or date range.<br></font>";
		$submit = "";
	}

	if($submit == "Delete Entire Bill" && $prebill != ""){
		$xfer_type == $HTTP_POST_VARS['xfer_type'];
		$confirm == $HTTP_POST_VARS['confirm'];

		$sql = "SELECT BILLING_TYPE
				FROM BILL_HEADER
				WHERE BILLING_NUM = '".$prebill."'";
		$select = ociparse($bniconn, $sql);
		ociexecute($select);
		ocifetch($select);
		$type = ociresult($select, "BILLING_TYPE");

		$result = ValidateEntry($submit, $confirm, $type, $prebill, $xfer_type, $rfconn);

		if($result == ""){
			$sql = "UPDATE BILL_HEADER
					SET SERVICE_STATUS = 'DELETED'
					WHERE BILLING_NUM = '".$prebill."'";
			$deleted_bills = ociparse($bniconn, $sql);
			ociexecute($deleted_bills);

			if($type == "TRANSFER" && $HTTP_POST_VARS['xfer_type'] != ""){
				if($xfer_type == "fina"){
					$status_set = "= 'B'";
				} elseif($xfer_type == "inv"){
					$status_set = "= NULL";
				} else {
					$status_set = "= 'N'";
				}
	//					FROM BILL_SUB_DETAILS@BNI_TEST
				$sql = "UPDATE CARGO_ACTIVITY
						SET TO_MISCBILL ".$status_set."
						WHERE (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, ACTIVITY_NUM) IN
							(SELECT PALLET_OR_LOT_ID, CUSTOMER_ID, ARRIVAL_NUM, ACTIVITY_NUM
							FROM BILL_SUB_DETAILS@BNI
							WHERE BILLING_NUM = '".$prebill."')";
				$rebill_items = ociparse($rfconn, $sql);
				ociexecute($rebill_items);
			}

			if($type == "TRUCK UNLOADING" && $HTTP_POST_VARS['xfer_type'] != ""){
				if($xfer_type == "fina"){
					$status_set = "= 'B'";
				} elseif($xfer_type == "inv"){
					$status_set = "= NULL";
				} else {
					$status_set = "= 'N'";
				}

				$sql = "SELECT COMMODITY_CODE FROM BILL_HEADER WHERE BILLING_NUM = '".$prebill."'";
				$short_term_data = ociparse($bniconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "COMMODITY_CODE") != "1272" && ociresult($short_term_data, "COMMODITY_CODE") != "1299"){
					// non-paper based deletion

	//					FROM BILL_SUB_DETAILS@BNI_TEST
					$sql = "UPDATE CARGO_ACTIVITY
							SET TO_MISCBILL ".$status_set."
							WHERE (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, ACTIVITY_NUM) IN
								(SELECT PALLET_OR_LOT_ID, CUSTOMER_ID, ARRIVAL_NUM, ACTIVITY_NUM
								FROM BILL_SUB_DETAILS@BNI_TEST
								WHERE BILLING_NUM = '".$prebill."')";
					$rebill_items = ociparse($rfconn, $sql);
					ociexecute($rebill_items);
				} else {
					// paper based deletion.  
					$sql = "SELECT * FROM BILL_SUB_DETAILS WHERE BILLING_NUM = '".$prebill."'";
					$order_sub_details = ociparse($bniconn, $sql);
					ociexecute($order_sub_details);
					while(ocifetch($order_sub_details)){
						$sql = "UPDATE CARGO_ACTIVITY
								SET TO_MISCBILL ".$status_set."
								WHERE CUSTOMER_ID = '".ociresult($order_sub_details, "CUSTOMER_ID")."'
									AND ARRIVAL_NUM = '".ociresult($order_sub_details, "ARRIVAL_NUM")."'
									AND ORDER_NUM = '".ociresult($order_sub_details, "SUB_ORDER_NUM")."'";
						if(ociresult($order_sub_details, "SUB_BATCH_ID") != ''){
							$sql .= " AND BATCH_ID = '".ociresult($order_sub_details, "SUB_BATCH_ID")."'";
						}

						$rebill_items = ociparse($rfconn, $sql);
						ociexecute($rebill_items);
					}
				}
			}
			echo "<font color=\"#0000FF\">Prebill ".$prebill." Deleted.</font><br>";
			$submit = "";
			$prebill = "";
		} else {
			echo "<font color=\"#FF0000\">Could not process Delete.  Reason:<br>".$result."</font>";
			$submit = "Retrieve Prebill";
		}
	}

/*
			if($type == "LABOR"){
				$sql = "UPDATE FINA_LT_HEADER
						SET BILL_STATUS = 'STAGING'
						WHERE LABOR_TICKET_NUM IN
							(SELECT LABOR_TICKET_NUM
							FROM BILL_DETAIL
							WHERE BILLING_NUM = '".$prebill."')";
				$rebill_items = ociparse($bniconn, $sql);
				ociexecute($rebill_items);
			}
*/



?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Delete Prebills<br></font><font size="3" face="Verdana" color="#0066CC">
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="delete_prebills.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Prebill#:  </font></td>
		<td><input type="text" name="prebill" size="20" maxlength="20" value="<? echo $prebill; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>---OR---</b></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date From:  </font></td>
		<td><input type="text" name="date_from" size="10" maxlength="10" value="<? echo $date_from; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date To:  </font></td>
		<td><input type="text" name="date_to" size="10" maxlength="10" value="<? echo $date_to; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Prebill"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="delete" action="delete_prebills.php" method="post">
<input type="hidden" name="prebill" value="<? echo $prebill; ?>">
	<tr>
		<td width="100%"><hr><font size="3" face="Verdana">Prebill #:  <? echo $prebill; ?></td>
	</tr>
<?
		$sql = "SELECT BH.BILLING_NUM, BH.CUSTOMER_ID, BH.ARRIVAL_NUM, BD.SERVICE_QTY, BD.SERVICE_UNIT, BD.SERVICE_AMOUNT, BD.DETAIL_LINE, BH.SERVICE_STATUS, BD.SERVICE_DESCRIPTION, BH.BILLING_TYPE
				FROM BILL_HEADER BH, BILL_DETAIL BD
				WHERE BH.BILLING_NUM = BD.BILLING_NUM
					AND BH.BILLING_NUM = '".$prebill."'
				ORDER BY BD.DETAIL_LINE";
		$bills = ociparse($bniconn, $sql);
		ociexecute($bills);
		if(!ocifetch($bills)){
?>
	<tr>
		<td><font size="3" face="verdana"><b>Selected Billing# not found in system.</b></font></td>
	</tr>
<?
		} elseif(ociresult($bills, "SERVICE_STATUS") != "PREINVOICE") {
?>
	<tr>
		<td><font size="3" face="verdana"><b>Selected Billing# cannot be deleted; it's current status is <? echo ociresult($bills, "SERVICE_STATUS"); ?>.</b></font></td>
	</tr>
<?
		} else {
			$sql = "SELECT NVL(VESSEL_NAME, 'TRUCK') THE_VES
					FROM VESSEL_PROFILE
					WHERE TO_CHAR(LR_NUM) = '".ociresult($bills, "ARRIVAL_NUM")."'";
			$ves = ociparse($bniconn, $sql);
			ociexecute($ves);
			if(!ocifetch($ves)){
				$vesname = ociresult($bills, "ARRIVAL_NUM");
			} else {
				$vesname = ociresult($ves, "THE_VES");
			}

			$sql = "SELECT CUSTOMER_NAME
					FROM CUSTOMER_PROFILE
					WHERE TO_CHAR(CUSTOMER_ID) = '".ociresult($bills, "CUSTOMER_ID")."'";
			$cust = ociparse($bniconn, $sql);
			ociexecute($cust);
			ocifetch($cust);
			$custname = ociresult($cust, "CUSTOMER_NAME");

?>
	<tr>
		<td>Customer:  <? echo $custname; ?></td>
	</tr>
	<tr>
		<td>Arrival#:  <? echo $vesname; ?></td>
	</tr>
	<tr>
		<td>Bill Type:  <? echo ociresult($bills, "BILLING_TYPE"); ?></td>
	</tr>
<?
			if(ociresult($bills, "BILLING_TYPE") == "TRANSFER" || ociresult($bills, "BILLING_TYPE") == "TRUCK UNLOADING"){
?>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<? echo ociresult($bills, "BILLING_TYPE"); ?> Specific Option:  <input type="radio" name="xfer_type" value="fina">Rerun Prebill Later
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="xfer_type" value="inv">Return to Inventory for Review
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="xfer_type" value="del">Permanently Delete&nbsp;<input type="text" name="confirm" size="3" maxlength="3">(initials required for permanent delete)</td>
	</tr>
<?
	}
?>
	<tr>
		<td><input type="submit" name="submit" value="Delete Entire Bill"></td>
	</tr>
	<tr>
		<td>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<td><font size="2" face="Verdana"><b>Bill Detail Line</b></font></td>
				<td><font size="2" face="Verdana"><b>Service Description</b></font></td>
				<td><font size="2" face="Verdana"><b>Service QTY</b></font></td>
				<td><font size="2" face="Verdana"><b>Service Amount</b></font></td>
			</tr>
<?
			do {
?>
			<tr>
				<td><font size="2" face="Verdana"><? echo ociresult($bills, "DETAIL_LINE"); ?></font></td>
				<td><font size="2" face="Verdana"><? echo ociresult($bills, "SERVICE_DESCRIPTION"); ?></font></td>
				<td><font size="2" face="Verdana"><? echo ociresult($bills, "SERVICE_QTY")." ".ociresult($bills, "SERVICE_UNIT"); ?></font></td>
				<td><font size="2" face="Verdana">$<? echo number_format(ociresult($bills, "SERVICE_AMOUNT"), 2); ?></font></td>
			</tr>
<?
			} while(ocifetch($bills));
?>
		</table>
		</td>
	</tr>
<?
		}
	}
?>
</form>
</table>
<?
	include("pow_footer.php");











function ValidateEntry($submit, $confirm, $type, $prebill, $xfer_type, $rfconn){
	$return = "";
	if($type == "TRANSFER"){
		if($xfer_type == ""){
			$return .= "Transfer bills must have a \"delete type\" selected.<br>";
		}

		if($xfer_type == "del" && $confirm == ""){
			$return .= "For security, Permanent deletions must have your initials entered.<br>";
		}
	}
	if($type == "TRUCK UNLOADING"){
		if($xfer_type == ""){
			$return .= "Transfer bills must have a \"delete type\" selected.<br>";
		}

		if($xfer_type == "del" && $confirm == ""){
			$return .= "For security, Permanent deletions must have your initials entered.<br>";
		}
	}

	return $return;
}

?>