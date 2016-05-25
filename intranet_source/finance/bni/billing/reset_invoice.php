<?
   include("pow_session.php");

   // unset cookie
   setcookie("invoice_num", "");

   // go back to previous page
   header("Location: update_invoice.php");
?>
