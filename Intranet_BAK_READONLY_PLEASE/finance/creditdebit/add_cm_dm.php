<?
/*
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
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$inv_num = $HTTP_POST_VARS['inv_num'];
	$submit = $HTTP_POST_VARS['submit'];
	$system = $HTTP_POST_VARS['system'];

	if($submit == "Save Memo"){
		$memo_num = $HTTP_POST_VARS['memo_num'];
		$billing_num = $HTTP_POST_VARS['billing_num'];
		$gl_code = $HTTP_POST_VARS['gl_code'];
		$asset_code = $HTTP_POST_VARS['asset_code'];
		$service_code = $HTTP_POST_VARS['service_code'];
		$cust = $HTTP_POST_VARS['cust'];
		$comm = $HTTP_POST_VARS['comm'];
		$desc = str_replace("\\", "", str_replace("'", "`", $HTTP_POST_VARS['desc']));
		$memo_amt = $HTTP_POST_VARS['memo_amt'];
		$org_amt = $HTTP_POST_VARS['org_amt'];
		$memtype = $HTTP_POST_VARS['memtype'];

		$perform_save = ValidateSave($memtype, $memo_num, $inv_num, $billing_num, $gl_code, $asset_code, $service_code, $cust, $comm, $memo_amt, $org_amt, $bniconn);

		if($perform_save == ""){
			if($memtype == "CM"){
				$status = "PRECREDIT";
			} else {
				$status = "PREDEBIT";
			}

			$sql = "SELECT SUBSTR(EMPLOYEE_ID, -4) THE_EMP FROM AT_EMPLOYEE WHERE INTRANET_LOGIN_ID = '".$user."'";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
			if(ocifetch($short_term_data)){
				$emp_id = ociresult($short_term_data, "THE_EMP");
			} else {
				$emp_id = 0;
			}

			$sql = "SELECT MAX(BILLING_NUM) THE_MAX FROM BILLING";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
			ocifetch($short_term_data);
			$billing_num_new = ociresult($short_term_data, "THE_MAX") + 1;

			$i = 0;
			
			while($billing_num[$i] != ""){
	//			echo $i." billing: ".$billing_num[$i]."   memo: ".$memo_amt[$i]."   gl: ".$gl_code[$i]."<br>";
				if($memo_amt[$i] != ""){

					// we only want to save a line with an actual amount.

					if($system == "BNI"){
						$sql = "INSERT INTO BILLING
									(CUSTOMER_ID,
									SERVICE_CODE,
									BILLING_NUM,
									EMPLOYEE_ID,
									SERVICE_START,
									SERVICE_STOP,
									SERVICE_AMOUNT,
									SERVICE_STATUS,
									SERVICE_DESCRIPTION,
									LR_NUM,
									ARRIVAL_NUM,
									COMMODITY_CODE,
									INVOICE_NUM,
									SERVICE_DATE,
									THRESHOLD_QTY,
									BILLING_TYPE,
									ASSET_CODE,
									ORIG_INVOICE_NUM,
									MEMO_NUM,
									GL_CODE)
								(SELECT
									'".$cust[$i]."',
									'".$service_code[$i]."',
									'".$billing_num_new."',
									'".$emp_id."',
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_START),
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_STOP),
									'".$memo_amt[$i]."',
									'".$status."',
									'".$desc[$i]."',
									LR_NUM,
									ARRIVAL_NUM,
									'".$comm[$i]."',
									'0',
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_DATE),
									'".$billing_num[$i]."',
									'".$memtype."',
									'".$asset_code[$i]."',
									INVOICE_NUM,
									'".$memo_num."',
									'".$gl_code[$i]."'
								FROM BILLING
								WHERE BILLING_NUM = '".$billing_num[$i]."')";
					} else {
						$sql = "INSERT INTO BILLING
									(CUSTOMER_ID,
									SERVICE_CODE,
									BILLING_NUM,
									EMPLOYEE_ID,
									SERVICE_START,
									SERVICE_STOP,
									SERVICE_AMOUNT,
									SERVICE_STATUS,
									SERVICE_DESCRIPTION,
									LR_NUM,
									ARRIVAL_NUM,
									COMMODITY_CODE,
									INVOICE_NUM,
									SERVICE_DATE,
									THRESHOLD_QTY,
									BILLING_TYPE,
									ASSET_CODE,
									ORIG_INVOICE_NUM,
									MEMO_NUM,
									GL_CODE)
								(SELECT
									'".$cust[$i]."',
									'".$service_code[$i]."',
									'".$billing_num_new."',
									'".$emp_id."',
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_START),
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_STOP),
									'".$memo_amt[$i]."',
									'".$status."',
									'".$desc[$i]."',
									DECODE(LENGTH(TRIM(TRANSLATE(ARRIVAL_NUM, '0123456789',' '))), NULL, TO_NUMBER(ARRIVAL_NUM), -1),
									'1',
									'".$comm[$i]."',
									'0',
									DECODE(INVOICE_NUM, -1, NULL, SERVICE_DATE),
									'".$billing_num[$i]."',
									'".$memtype."',
									'".$asset_code[$i]."',
									INVOICE_NUM,
									'".$memo_num."',
									'".$gl_code[$i]."'
								FROM RF_BILLING@RF.PROD
								WHERE BILLING_NUM = '".$billing_num[$i]."')";
					}
//					echo $sql."<br>";
					$update_data = ociparse($bniconn, $sql);
					ociexecute($update_data, OCI_NO_AUTO_COMMIT);
				}
				$billing_num_new++;
				$i++;
			}
			
			echo "Memo ".$memo_num." Saved.  Please <a href=\"credit_debit_memo_gen.php\">Click Here</a> To return to the Memo List Page.";
			ocicommit($bniconn);
			include("pow_footer.php");
			exit;
		} else {
			echo "<font color=\"#FF0000\">Could not save memo:<br>";
			echo $perform_save."</font><br>";
		}

	}




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Credit/Debit Memo Create
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="add_cm_dm.php" method="post">
<input type="hidden" name="inv_num" value="<? echo $inv_num; ?>">
<input type="hidden" name="system" value="<? echo $system; ?>">
	<tr>
		<td width="20%"><font size="3" face="Verdana">Invoice# (-1 is Freeform):</font></td>
		<td><font size="3" face="Verdana"><? echo $inv_num; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="3" face="Verdana">Memo#:</font></td>
		<td><input type="text" name="memo_num" size="12" maxlength="12" value=""></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Memo Type:</font></td>
		<td><input type="radio" name="memtype" value="CM" checked>CreditMemo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="memtype" value="DM">DebitMemo</td>
	</tr>

<?
	if($system == "BNI"){
		$sql = "SELECT ASSET_CODE, SERVICE_CODE, LR_NUM, CUSTOMER_ID, COMMODITY_CODE, GL_CODE, SERVICE_DESCRIPTION, SERVICE_AMOUNT, BILLING_NUM THE_BIL
				FROM BILLING
				WHERE INVOICE_NUM = '".$inv_num."'";
	} else {
		$sql = "SELECT ASSET_CODE, RFB.SERVICE_CODE, ARRIVAL_NUM, CUSTOMER_ID, COMMODITY_CODE, GL_CODE, SERVICE_DESCRIPTION, SERVICE_AMOUNT, BILLING_NUM THE_BIL
				FROM RF_BILLING@RF.PROD RFB, SERVICE_CATEGORY SC
				WHERE INVOICE_NUM = '".$inv_num."'
					AND RFB.SERVICE_CODE = SC.SERVICE_CODE";
	}
//	echo $sql."<br>";
	$billing_data = ociparse($bniconn, $sql);
	ociexecute($billing_data);
	if(!ocifetch($billing_data)){
?>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>No Live Invoice found matching criteria.  Please return to the previous page, or contact TS if you feel this is in error.</b></font></td>
	</tr>
</table>
<?
	} else {
		$row = 0;
?>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice Asset Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo Asset Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice Service Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo Service Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice Cust#</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo Cust#</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice Comm#</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo Comm#</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice-GL</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo-GL</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Invoice Amount</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo Amount</b></font></td>
	</tr>
<?
		do {
?>
	<input type="hidden" name="billing_num[<? echo $row; ?>]" value="<? echo ociresult($billing_data, "THE_BIL"); ?>">
	<input type="hidden" name="org_amt[<? echo $row; ?>]" value="<? echo ociresult($billing_data, "SERVICE_AMOUNT"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "ARRIVAL_NUM"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "ASSET_CODE"); ?></font></td>
		<td><input type="text" name="asset_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "ASSET_CODE"); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "SERVICE_CODE"); ?></font></td>
		<td><input type="text" name="service_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "SERVICE_CODE"); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "CUSTOMER_ID"); ?></font></td>
		<td><input type="text" name="cust[<? echo $row; ?>]" size="6" maxlength="6" value="<? echo ociresult($billing_data, "CUSTOMER_ID"); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "COMMODITY_CODE"); ?></font></td>
		<td><input type="text" name="comm[<? echo $row; ?>]" size="12" maxlength="12" value="<? echo ociresult($billing_data, "COMMODITY_CODE"); ?>"></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($billing_data, "GL_CODE"); ?></font></td>
		<td><input type="text" name="gl_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "GL_CODE"); ?>"></td>
		<td><input type="text" name="desc[<? echo $row; ?>]" size="80" maxlength="2000" value="<? echo ociresult($billing_data, "SERVICE_DESCRIPTION"); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "SERVICE_AMOUNT"); ?></font></td>
		<td><input type="text" name="memo_amt[<? echo $row; ?>]" size="13" maxlength="13" value=""></td>
	</tr>
<?
			$row++;
		} while(ocifetch($billing_data));
?>
	<tr>
		<td colspan="14" align="center"><input type="submit" name="submit" value="Save Memo"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");







function ValidateSave($memtype, $memo_num, $inv_num, $billing_num, $gl_code, $asset_code, $service_code, $cust, $comm, $memo_amt, $org_amt, $bniconn){
	$return = "";

	if($memo_num == ""){
		$return .= "No Memo# was entered.<br>";
	}

	$i = 0;
	
	while($billing_num[$i] != ""){
		if($memo_amt[$i] != ""){
			if($gl_code[$i] == ""){
				$return .= "Line ".($i + 1)." had an amount, but no GL code.<br>";
			}
			if(abs($memo_amt[$i]) > abs($org_amt[$i]) && $memtype == "CM" && $inv_num != "-1"){
				$return .= "Line ".($i + 1)." had an amount larger than the original invoice amount (which is not allowed on CreditMemos).<br>";
			}
			if($asset_code[$i] == ""){
				$return .= "Line ".($i + 1)." had an amount, but no Asset code.<br>";
			}
			if($service_code[$i] == ""){
				$return .= "Line ".($i + 1)." had an amount, but no Service code.<br>";
			}
			if($cust[$i] == ""){
				$return .= "Line ".($i + 1)." had an amount, but no Customer.<br>";
			}
			if($comm[$i] == ""){
				$return .= "Line ".($i + 1)." had an amount, but no Commodity.<br>";
			}
		}
		$i++;
	}

	return $return;
}
