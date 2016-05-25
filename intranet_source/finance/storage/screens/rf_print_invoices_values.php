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
/*
//  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }

  $Short_Term_Cursor = ora_open($conn);
  $modify_cursor = ora_open($conn); */
?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Print Scanned Storage Invoices
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="rf_print_invoices.php" method="post">
	<tr>
		<td>Starting Invoice#:&nbsp;&nbsp;<input type="text" name="start_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td>Ending Invoice#:&nbsp;&nbsp;<input type="text" name="end_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="radio" name="invoice_type" value="INVOICE" checked>&nbsp;Invoiced&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="invoice_type" value="PREINVOICE">PreInvoice</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Invoices"></td>
	</tr>
</form>
</table>

<? include("pow_footer.php"); ?>