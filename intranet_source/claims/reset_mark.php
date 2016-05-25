<?
   include("pow_session.php");
   $previous_page = $HTTP_POST_VARS["page_filename"];

   // unset the mark cookie
   setcookie("mark", "");

   // go back to previous page
   header("Location: $previous_page");
?>
