<?
   include("pow_session.php");
   $previous_page = $HTTP_POST_VARS["page_filename"];

   $lr_num = $HTTP_POST_VARS["lr_num"];
   setcookie("lrnum", $lr_num, time() + 28800);
   header("Location: $previous_page");
?>
