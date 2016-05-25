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
         <font size="5" face="Verdana" color="#0066CC">Storage Billing Pages
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
	    <font size="2" face="Verdana"><b>From here you can access Storage applications.</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
            <tr>
               <td valign="middle" width="10%"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  Delete Prebills&nbsp;&nbsp;<a href="delete_bni_storage_prebills.php">BNI (Unscanned)</a>&nbsp;&nbsp;<a href="delete_rf_storage_prebills.php">RF (Scanned - Entire Bill)</a>&nbsp;&nbsp;<a href="delete_pallet_from_storage_bill.php">RF (Scanned - Individual Pallet)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  Un-Delete Prebills&nbsp;&nbsp;<a href="undelete_rf_storage_prebills.php">RF (Scanned)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">Manual Storage Re-run&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="storage_manualbills.php">BNI (Unscanned)</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="rf_storage_manualbills_v2.php">RF (Scanned)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="reset_free_time.php">Reset Cargo Free Time (Both Systems)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="rf_create_invoices.php">RF (Scanned) Pre-invoice to Invoice Finalization</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="rf_print_invoices_values.php">RF (Scanned) Print Preinvoices/Invoices</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="rf_storage_email_file.php">Generate RF (Scanned) Email-.txt File</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="rf_storage_pallet_startstop.php">RF (Scanned) Start/Stop Storage Bills</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="../../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="finance_storage_bill_estimate_rf.php">Expected vs. Actual Storage Billing Details for Scanned Cargo (RF)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
          </table>
      </td>
      <td valign="middle" width="30%">
         <img border="0" src="../../images/warehouse_e.jpg" width="218" height="170">
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
