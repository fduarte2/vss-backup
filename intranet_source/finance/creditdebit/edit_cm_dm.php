<?
/*
*	Adam Walter, Aug 2013
*
*	Finance page to edit credit and debit memos
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

	$memo_num = $HTTP_POST_VARS['memo_num'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Memo"){
		$gl_code = $HTTP_POST_VARS['gl_code'];
		$asset_code = $HTTP_POST_VARS['asset_code'];
		$service_code = $HTTP_POST_VARS['service_code'];
		$cust = $HTTP_POST_VARS['cust'];
		$comm = $HTTP_POST_VARS['comm'];
		$desc = $HTTP_POST_VARS['desc'];
		$memo_amt = $HTTP_POST_VARS['memo_amt'];
		$memo_num = $HTTP_POST_VARS['memo_num'];

		$sql = "SELECT SUBSTR(EMPLOYEE_ID, -4) THE_EMP FROM AT_EMPLOYEE WHERE INTRANET_LOGIN_ID = '".$user."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
		if(ocifetch($short_term_data)){
			$emp_id = ociresult($billing_data, "THE_EMP");
		} else {
			$emp_id = 0;
		}

		$i = 0;
		
		while($billing_num[$i] != ""){
			$sql = "UPDATE BILLING
					SET SERVICE_AMOUNT = '".$memo_amt[$i]."',
						GL_CODE = '".$gl_code[$i]."',
						ASSET_CODE = '".$asset_code[$i]."',
						SERVICE_CODE = '".$service_code[$i]."',
						CUSTOMER_ID = '".$cust[$i]."',
						COMMODITY_CODE = '".$comm[$i]."',
						SERVICE_DESCRIPTION = '".$desc[$i]."',
						EMPLOYEE_ID = '".$emp_id."'
					WHERE THRESHOLD_QTY = '".$billing_num[$i]."'
						AND MEMO_NUM = '".$memo_num[$i]."'";
			$update_data = ociparse($bniconn, $sql);
			ociexecute($update_data, OCI_NO_AUTO_COMMIT);
			$i++;
		}

		
		echo "Memo ".$memo_num." Updated.  Please <a href=\"credit_debit_memo_gen.php\">Click Here</a> To return to the Memo List Page.";
		ocicommit($bniconn);
		include("pow_footer.php");
		exit;
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Credit/Debit Memo Edit
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="edit_cm_dm.php" method="post">
<input type="hidden" name="memo_num" value="<? echo $memo_num; ?>">
	<tr>
		<td width="15%"><font size="3" face="Verdana">Memo#:</font></td>
		<td><font size="3" face="Verdana"><? echo $memo_num; ?></font></td>
	</tr>
<?
	$sql = "SELECT ASSET_CODE, SERVICE_CODE, LR_NUM, CUSTOMER_ID, COMMODITY_CODE, GL_CODE, SERVICE_DESCRIPTION, SERVICE_AMOUNT, THRESHOLD_QTY THE_BIL, SERVICE_STATUS
			FROM BILLING
			WHERE MEMO_NUM = '".$memo_num."'";
	echo $sql."<br>";
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
	<tr>
		<td width="15%"><font size="3" face="Verdana">Memo Type:</font></td>
		<td><font size="3" face="Verdana"><? echo ociresult($billing_data, "SERVICE_STATUS"); ?></font></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Asset Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Cust#</b></font></td>
		<td><font size="2" face="Verdana"><b>Comm#</b></font></td>
		<td><font size="2" face="Verdana"><b>GL</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
	</tr>
<?
		do {
?>
	<input type="hidden" name="billing_num[<? echo $row; ?>]" value="<? echo ociresult($billing_data, "THE_BIL"); ?>">
	<input type="hidden" name="memo_num[<? echo $row; ?>]" value="<? echo $memo_num; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($billing_data, "LR_NUM"); ?></font></td>
		<td><input type="text" name="asset_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "ASSET_CODE"); ?>"></td>
		<td><input type="text" name="service_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "SERVICE_CODE"); ?>"></td>
		<td><input type="text" name="cust[<? echo $row; ?>]" size="6" maxlength="6" value="<? echo ociresult($billing_data, "CUSTOMER_ID"); ?>"></td>
		<td><input type="text" name="comm[<? echo $row; ?>]" size="12" maxlength="12" value="<? echo ociresult($billing_data, "COMMODITY_CODE"); ?>"></td>
		<td><input type="text" name="gl_code[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($billing_data, "GL_CODE"); ?>"></td>
		<td><input type="text" name="desc[<? echo $row; ?>]" size="100" maxlength="2000" value="<? echo ociresult($billing_data, "SERVICE_DESCRIPTION"); ?>"></td>
		<td><input type="text" name="memo_amt[<? echo $row; ?>]" size="13" maxlength="13" value="<? echo ociresult($billing_data, "SERVICE_AMOUNT"); ?>"></td>
	</tr>
<?
			$row++;
		} while(ocifetch($billing_data));
?>
	<tr>
		<td colspan="8" align="center"><input type="submit" name="submit" value="Save Memo"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");