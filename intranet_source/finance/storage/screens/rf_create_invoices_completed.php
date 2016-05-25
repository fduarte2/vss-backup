<?
/*
*	Adam Walter, Oct-Nov 2011
*
*	This is the script to "INVOICE" all outstanding SCANNED-preinvoices
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2011)
*****************************************************************************/

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
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }

  $Short_Term_Cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);

  $inv_date = $HTTP_POST_VARS['the_date'];
  if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $inv_date)) {
	  echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format (entered as ".$inv_date.".<br><a href=\"rf_create_invoices.php\">Click Here</a> to return to the previous Screen.</font>";
	  exit;
  }

	$sql = "SELECT max(invoice_num) INV_COUNT FROM RF_BILLING";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$max_bill = $row['INV_COUNT'];

//	$next_year_bill = 24000000 + date('y', mktime(0,0,0,date('m'),date('d')+182,date('y')));
	$next_year_bill = (2400 + date('y', mktime(0,0,0,date('m'),date('d')+182,date('y')))) * 10000;

	if(($max_bill + 1) < $next_year_bill){
		$first_bill = $next_year_bill;
	} else {
		$first_bill = ($max_bill + 1);
	}
	$last_bill = 0;

	$sql = "SELECT BILLING_NUM 
			FROM RF_BILLING
			WHERE SERVICE_STATUS = 'PREINVOICE'
				AND BILLING_TYPE IN ('PLT-STRG')
			ORDER BY BILLING_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$last_bill = $first_bill - 1;
		do {
			$last_bill++;
			$sql = "UPDATE RF_BILLING 
					SET SERVICE_STATUS = 'INVOICED',
						INVOICE_DATE = TO_DATE('".$inv_date."', 'MM/DD/YYYY'),
						INVOICE_NUM = '".$last_bill."'
					WHERE BILLING_NUM = '".$row['BILLING_NUM']."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Scanned Invoice Results
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
		<td><font size="3" face="Verdana"><b>No Invoices were Generated.<br>Try again, like, next week or something.</b></font></td>
	</tr>
<?
	} else {
?>
<form name="meh" action="rf_print_invoices.php" method="post">
<input type="hidden" name="start_num" value="<? echo $first_bill; ?>">
<input type="hidden" name="end_num" value="<? echo $last_bill; ?>">
<input type="hidden" name="invoice_type" value="INVOICE">
	<tr>
		<td><font size="3" face="Verdana"><b>Invoices <? echo $first_bill."  through  ".$last_bill; ?> Generated.</b></font></td>
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