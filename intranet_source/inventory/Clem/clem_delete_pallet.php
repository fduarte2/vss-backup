<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to pribnt Steel picklists.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel BoL";
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

	$submit = $HTTP_POST_VARS['submit'];
	$Barcode = $HTTP_POST_VARS['barcode'];
	$cust = $HTTP_POST_VARS['cust'];
	$LR = $HTTP_POST_VARS['LR'];

	if($submit == "Retrieve Pallet"){
		$sql = "SELECT COMMODITY_CODE FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$LR."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			echo "<font color=\"#FF0000\">No pallet in system matches Barcode/LR/Cust combination.</font>";
			$submit = "";
		} elseif(substr(ociresult($stid, "COMMODITY_CODE"), 0, 2) != "56"){
			echo "<font color=\"#FF0000\">This screen is for Clementines only.</font>";
			$submit = "";
		}
	}

	if($submit == "DELETE PALLET"){
		$sql = "DELETE FROM CARGO_ACTIVITY
				WHERE PALLET_ID = '".$Barcode."'
					AND CUSTOMER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$LR."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		$sql = "DELETE FROM CARGO_TRACKING_EXT
				WHERE PALLET_ID = '".$Barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$LR."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		$sql = "DELETE FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$LR."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		echo "<font color=\"#FF0000\">Pallet ".$Barcode." (ship ".$LR."  cust ".$cust.") Deleted.</font>";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Clementine Pallet Delete
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="get" action="clem_delete_pallet.php" method="post">
	<tr>
		<td>Barcode: <input type="text" name="barcode" size="32" maxlength="32" value="<? echo $Barcode; ?>"></td>
	</tr>
	<tr>
		<td>Customer: <input type="text" name="cust" size="10" maxlength="10" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td>LR: <input type="text" name="LR" size="10" maxlength="10" value="<? echo $LR; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Pallet"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Pallet"){
?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="delete" action="clem_delete_pallet.php" method="post">
<input type="hidden" name="barcode" value="<? echo $Barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="LR" value="<? echo $LR; ?>">
<?
		$sql = "SELECT DISTINCT RB.SERVICE_STATUS, DECODE(RB.SERVICE_STATUS, 'INVOICED', RB.INVOICE_NUM, RB.BILLING_NUM) THE_BILLNUM
				FROM RF_BILLING RB, RF_BILLING_DETAIL RBD
				WHERE RB.BILLING_NUM = RBD.SUM_BILL_NUM
					AND RB.SERVICE_STATUS IN ('INVOICED', 'PREINVOICE')
					AND RBD.PALLET_ID = '".$Barcode."'
					AND RB.CUSTOMER_ID = '".$cust."'
					AND RB.ARRIVAL_NUM = '".$LR."'
					AND RBD.SERVICE_STATUS != 'DELETED'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			// do nothing if no bills
		} else {
?>
	<tr><td>
	<table border="1" width="50%" cellpadding="4" cellspacing="0">
	<tr><td colspan="2"><font color="#FF0000" size="2"><b>WARNING:</b>  The following storage bills<br>Have this pallet on them.<br>It is Inventory's responsiblity to<br>Notify Finance that this pallet<br>is being deleted.</font></td>
	</tr>
	<tr>
		<td><font size="2"><b>Bill#</b></td>
		<td><font size="2"><b>Status</b></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><? echo ociresult($stid, "THE_BILLNUM"); ?></td>
		<td><? echo ociresult($stid, "SERVICE_STATUS"); ?></td>
	</tr>
<?
			} while(ocifetch($stid));
?>
	</table></td></tr>
<?
		}
?>
	<tr>
		<td colspan="2"><br><br><br><b>ARE YOU SURE YOU WISH TO DELETE THIS PALLET?</b></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="DELETE PALLET"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");