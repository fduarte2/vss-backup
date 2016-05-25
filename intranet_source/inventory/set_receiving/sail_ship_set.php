<?
  include("pow_session.php");
  $lr_num = $HTTP_POST_VARS["lr_num"];
  setcookie("lr_num", $lr_num, time() + 28800);
  header("Location: index.php");
?>
