<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Finance Reports
          </font>
	  <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can generate Finance Reports.</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
		<tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="gui-pallet-count.php">Giumarra OTR Pallet Count For Season</a></font>
               </td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="Hours_vs_Billed/">Hours Paid vs. Billed Amounts per Vessel</a></font>
               </td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="/director/data_warehouse/reband_report.php">Reband Report (Argen Juice)</a></font>
               </td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="storage_bill_reports/storage_bill_report.php">Storage Invoice Report</a></font>
               </td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="UnbilledLabor/unbilled_labor.php">Unbilled Labor Tickets</a></font>
               </td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="./inbound_report.php">Inbound Report</a></font>
               </td>
            </tr>
<!--           <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="./ticket_to_prebills.php">Labor Ticket Listing</a></font>
               </td>
            </tr> !-->
<!--            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="rf_storage_billed_select.php">RF Storage Invoices (by Customer / Year)</a></font>
               </td>
            </tr> !-->
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
         </table>
      </td>
      <td valign="middle" width="30%">&nbsp;</td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
