<?
   include("pow_session.php");
   $previous_page = $HTTP_POST_VARS["page_filename"];

   // unset the order_num cookie
   setcookie("ccd_lot_id", "");
   setcookie("lrnum", "");

   // go back to previous page
   header("Location: $previous_page");
?>
