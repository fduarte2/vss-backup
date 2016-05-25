<?/*
*	Adam Walter, Aug 2013
*
*	Finance page to create credit and debit memos
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HD System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
/*  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }*/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$invoice_num = $HTTP_POST_VARS['invoice_num'];
	$submit = $HTTP_POST_VARS['submit'];
	$system = $HTTP_POST_VARS['system'];

	if($submit == "Delete this Memo"){
		$memo_num = $HTTP_POST_VARS['memo_num'];

		$sql = "UPDATE BILLING
				SET SERVICE_STATUS = 'DELETED'
				WHERE MEMO_NUM = '".$memo_num."'";
//		echo $sql;
		$update_data = ociparse($bniconn, $sql);
		ociexecute($update_data);
	}
	if($submit != "" && !is_numeric($invoice_num)){
		echo "<font color=\"#FF0000\">Invoice# must be numeric.</font>";
		$invoice_num = "";
		$submit = "";
	}




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Credit/Debit Memo
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="retrieve_invoice" action="credit_debit_memo_gen.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Invoice# (-1 for Freeform):</b></font></td>
		<td><input type="text" name="invoice_num" size="15" maxlength="15" value="<? echo $invoice_num; ?>"></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">System:</font></td>
		<td><input type="radio" name="system" value="RF" <? if($system == "RF"){?>checked<?}?>>Scanned Storage (V1) Only&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="system" value="BNI"<? if($system == "BNI"){?>checked<?}?>>All Other V1 bills&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="system" value="V2"<? if($system == "V2"){?>checked<?}?>>Billing V2 System</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Invoice"><hr></td>
	</tr>
</form>
</table>
<?
	
	if($invoice_num != ""){
		if($system == "BNI"){
			$sql = "SELECT TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') SERV_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, NVL(VP.VESSEL_NAME, 'UKN/MULTIPLE') THE_VES, 
						SUM(SERVICE_AMOUNT) THE_SUM
					FROM BILLING BIL, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
					WHERE BIL.CUSTOMER_ID = CUSP.CUSTOMER_ID
						AND BIL.LR_NUM = TO_CHAR(VP.LR_NUM(+))
						AND COMP.COMMODITY_CODE = NVL(BIL.COMMODITY_CODE,  0)
						AND BIL.INVOICE_NUM = '".$invoice_num."'
					GROUP BY TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY'), TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY'), CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, VP.VESSEL_NAME";
		} elseif($system == "RF") {
			$sql = "SELECT TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') SERV_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, NVL(VP.VESSEL_NAME, 'TRUCK') THE_VES, 
						SERVICE_AMOUNT THE_SUM
					FROM RF_BILLING@RF.PROD BIL, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
					WHERE BIL.CUSTOMER_ID = CUSP.CUSTOMER_ID
						AND BIL.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
						AND BIL.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND BIL.INVOICE_NUM = '".$invoice_num."'";
		} else {
			$sql = "SELECT TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') SERV_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, NVL(VP.VESSEL_NAME, 'TRUCK') THE_VES, 
						SUM(SERVICE_AMOUNT) THE_SUM
					FROM BILL_HEADER BH, BILL_DETAIL BD, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
					WHERE BH.CUSTOMER_ID = CUSP.CUSTOMER_ID
						AND BH.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
						AND BH.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND BH.INVOICE_NUM = '".$invoice_num."'
						AND BH.BILLING_NUM = BD.BILLING_NUM
					GROUP BY TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY'), TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY'), CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, VP.VESSEL_NAME";
		}
		//echo $sql."<br>";
		$inv_data = ociparse($bniconn, $sql);
		ociexecute($inv_data, OCI_NO_AUTO_COMMIT);
		if(!ocifetch($inv_data)){
			echo "<font color=\"#FF0000\">No such invoice found in selected system</font>";
			include("pow_footer.php");
			exit;
		}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vessel:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "THE_VES"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Customer:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "CUSTOMER_NAME"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Commodity:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "COMMODITY_NAME"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Date of Service:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "SERV_DATE"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Invoice Date:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "INV_DATE"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Invoice Total:</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($inv_data, "THE_SUM"); ?></font></td>
	</tr>
<form name="addnew" action="add_cm_dm.php" method="post">
<input type="hidden" name="inv_num" value="<? echo $invoice_num; ?>">
<input type="hidden" name="system" value="<? echo $system; ?>">
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add New Memo to this invoice"></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6">Current Credit Memos:</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Memo#</b></font></td>
		<td><font size="2" face="Verdana"><b># of Memo lines</b></font></td>
		<td><font size="2" face="Verdana"><b>Total $ Value</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
		$edit_cm = 0;
		$sql = "SELECT COUNT(*) NUM_LINES, SUM(SERVICE_AMOUNT) THE_AMT, MEMO_NUM, SERVICE_STATUS
				FROM BILLING
				WHERE ORIG_INVOICE_NUM = '".$invoice_num."'
					AND BILLING_TYPE = 'CM'
				GROUP BY MEMO_NUM, SERVICE_STATUS
				ORDER BY MEMO_NUM";
		$cm_data = ociparse($bniconn, $sql);
		ociexecute($cm_data, OCI_NO_AUTO_COMMIT);
		while(ocifetch($cm_data)){
?>
	<form name="edit_cm<? echo $edit_cm; ?>" action="edit_cm_dm.php" method="post">
	<input type="hidden" name="memo_num" value="<? echo ociresult($cm_data, "MEMO_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($cm_data, "MEMO_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($cm_data, "NUM_LINES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($cm_data, "THE_AMT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($cm_data, "SERVICE_STATUS"); ?></font></td>
<? 
	if(ociresult($cm_data, "SERVICE_STATUS") == "PRECREDIT"){
?>
		<td><input type="submit" name="submit" value="Edit this Memo"></td></form>
		<form name="delete_cm" action="credit_debit_memo_gen.php" method="post">
		<input type="hidden" name="memo_num" value="<? echo ociresult($cm_data, "MEMO_NUM"); ?>">
		<input type="hidden" name="invoice_num" value="<? echo $invoice_num; ?>">
		<td><input type="submit" name="submit" value="Delete this Memo"></td></form>
<?
	} else {
?>
		<td colspan="2"><font size="2" face="Verdana">Memo Finalized.</font></td>
<?
	}
?>
	</tr>
	</form>
<?
			$edit_cm++;
		}
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr></td>
	</tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6">Current Debit Memos:</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Memo#</b></font></td>
		<td><font size="2" face="Verdana"><b># of Memo lines</b></font></td>
		<td><font size="2" face="Verdana"><b>Total $ Value</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
		$edit_dm = 0;
		$sql = "SELECT COUNT(*) NUM_LINES, SUM(SERVICE_AMOUNT) THE_AMT, MEMO_NUM, SERVICE_STATUS
				FROM BILLING
				WHERE ORIG_INVOICE_NUM = '".$invoice_num."'
					AND BILLING_TYPE = 'DM'
				GROUP BY MEMO_NUM, SERVICE_STATUS
				ORDER BY MEMO_NUM";
		$dm_data = ociparse($bniconn, $sql);
		ociexecute($dm_data, OCI_NO_AUTO_COMMIT);
		while(ocifetch($dm_data)){
?>
	<form name="edit_dm<? echo $edit_cm; ?>" action="edit_cm_dm.php" method="post">
	<input type="hidden" name="memo_num" value="<? echo ociresult($dm_data, "MEMO_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($dm_data, "MEMO_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($dm_data, "NUM_LINES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($dm_data, "THE_AMT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($dm_data, "SERVICE_STATUS"); ?></font></td>
<? 
	if(ociresult($dm_data, "SERVICE_STATUS") == "PREDEBIT"){
?>
		<td><input type="submit" name="submit" value="Edit this Memo"></td></form>
		<form name="delete_dm" action="credit_debit_memo_gen.php" method="post">
		<input type="hidden" name="memo_num" value="<? echo ociresult($dm_data, "MEMO_NUM"); ?>">
		<input type="hidden" name="invoice_num" value="<? echo $invoice_num; ?>">
		<td><input type="submit" name="submit" value="Delete this Memo"></td></form>
<?
	} else {
?>
		<td colspan="2"><font size="2" face="Verdana">Memo Finalized.</font></td>
<?
	}
?>
	</tr>
	</form>
<?
			$edit_dm++;
		}
?>
</table>
<?
	}
	include("pow_footer.php");