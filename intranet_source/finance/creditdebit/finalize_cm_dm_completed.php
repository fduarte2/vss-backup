<?
/*
*	Adam Walter, Aug 2013
*
*	Finance page to finalize credit and debit memos
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

	$inv_date = $HTTP_POST_VARS['the_date'];
	if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $inv_date)) {
	  echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format (entered as ".$inv_date.".<br><a href=\"rf_create_invoices.php\">Click Here</a> to return to the previous Screen.</font>";
	  exit;
	}

	$sql = "SELECT max(invoice_num) INV_COUNT FROM BILLING WHERE INVOICE_NUM < 980000000";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$max_bill = ociresult($short_term_data, "INV_COUNT");

//	$next_year_bill = 24000000 + date('y', mktime(0,0,0,date('m'),date('d')+182,date('y')));
	$next_year_bill = (9800 + date('y', mktime(0,0,0,date('m'),date('d')+182,date('y')))) * 10000;

	if(($max_bill + 1) < $next_year_bill){
		$first_bill = $next_year_bill;
	} else {
		$first_bill = ($max_bill + 1);
	}
	$last_bill = 0;

	$sql = "SELECT DISTINCT MEMO_NUM 
			FROM BILLING
			WHERE SERVICE_STATUS LIKE '%PRE%'
				AND BILLING_TYPE IN ('CM', 'DM')
			ORDER BY MEMO_NUM";
	$update_data = ociparse($bniconn, $sql);
	ociexecute($update_data);
	if(!ocifetch($update_data)){
		// do nothing
	} else {
		$last_bill = $first_bill - 1;
		do {
			$last_bill++;
			$sql = "UPDATE BILLING 
					SET SERVICE_STATUS = DECODE(TRIM(BILLING_TYPE), 'CM', 'CREDITMEMO', 'DEBITMEMO'),
						INVOICE_DATE = TO_DATE('".$inv_date."', 'MM/DD/YYYY'),
						INVOICE_NUM = '".$last_bill."',
						SERVICE_DATE = DECODE(SERVICE_DATE, NULL, TO_DATE('".$inv_date."', 'MM/DD/YYYY'), SERVICE_DATE)
					WHERE MEMO_NUM = '".ociresult($update_data, "MEMO_NUM")."'";
			$modify_data = ociparse($bniconn, $sql);
			ociexecute($modify_data);
		} while(ocifetch($update_data));
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Credit/Debit Memos Results
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	if($last_bill == 0){
?>
	<tr>
		<td><font size="3" face="Verdana"><b>No Invoices were Generated.</b></font></td>
	</tr>
<?
	} else {
?>
<form name="meh" action="print_cm_dm.php" method="post">
<input type="hidden" name="start_num_inv" value="<? echo $first_bill; ?>">
<input type="hidden" name="end_num_inv" value="<? echo $last_bill; ?>">
<input type="hidden" name="printout_type" value="Memo-Invoice Range">
<input type="hidden" name="completed_status" value="FINALIZED">
	<tr>
		<td><font size="3" face="Verdana"><b><? echo ($last_bill - $first_bill + 1); ?> Memos Generated.</b></font></td>
	</tr>
	<tr>
		<td align="left"><input type="submit" name="submit" value="Click Here to go to Printouts."><br><br></td>
	</tr>
</form>
<?
	}
?>
</table>

<? include("pow_footer.php"); ?>