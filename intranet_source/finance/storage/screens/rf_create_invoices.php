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


?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Generate Scanned Billing Invoices
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="rf_create_invoices_completed.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Invoice Date:&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><input type="text" name="the_date" size="10" maxlength="10" value="<? echo date('m/d/Y'); ?>"><!--<a href="javascript:show_calendar('meh.the_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a> !--></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Finalize Invoicing"><br><br></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Start</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Stop</b></font></td>
	</tr>
<?
	$sql = "SELECT CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT,
				TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_STOP
			FROM RF_BILLING
			WHERE SERVICE_STATUS = 'PREINVOICE'
				AND BILLING_TYPE IN ('PLT-STRG')
			ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, SERVICE_UNIT";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="7" align="center"><font size="2" face="Verdana">No Currently-Existing Preinvoices.</font></td>
	</tr>
<?
	} else {
		do{
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['CUSTOMER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMODITY_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SERVICE_QTY']." ".$row['SERVICE_UNIT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SERVICE_AMOUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_START']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STOP']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>

<? include("pow_footer.php"); ?>