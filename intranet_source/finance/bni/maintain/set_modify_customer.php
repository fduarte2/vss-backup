<?
  include("pow_session.php");
  $cust = $HTTP_POST_VARS["cust"];
  setcookie("cust", $cust, time() + 28800);
  header("Location: modify_customer.php");
?>

