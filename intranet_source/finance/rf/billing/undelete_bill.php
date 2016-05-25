<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Undelete Prebill";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Un-Delete Prebills - Main page -->

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Un-Delete Prebill</font>
         <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<?
   // Make Oracle Database connection
   include("connect.php");

   $conn = ora_logon("SAG_OWNER@$rf", "OWNER");
   if (!$conn) {
      printf("Error logging on to the RF Oracle Server: " . ora_errorcode($conn));
      printf("Please report to TS!");
      exit;
   }
   $cursor = ora_open($conn);

   $page = "un-delete";
   $rf_billing = "rf_billing";
   include("select_bill.php");

   // close database connection
   ora_close($cursor);
   ora_logoff($conn);
   include("pow_footer.php");
?>
