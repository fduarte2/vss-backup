<?
   include("pow_session.php");
   // The form processor
   $ccd_lot_id = $HTTP_POST_VARS["selected_lot"];
   $lr_num = $HTTP_POST_VARS["selected_ship"];

   // set the cookie for ccd lot id and lr_num
   setcookie("ccd_lot_id", $ccd_lot_id, time() + 28800);
   setcookie("lrnum", $lr_num, time() + 28800);

   // delete the cookies for order_num
   setcookie("order_num", "");
   setcookie("mark", "");

   header("Location: add_ccds.php");
?>
