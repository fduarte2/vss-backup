<?
   include("pow_session.php");
   $previous_page = $HTTP_POST_VARS["page_filename"];

   // unset cookie
   setcookie("billing_num", "");

   // go back to previous page
   header("Location: $previous_page");
?>
