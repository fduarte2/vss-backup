<?

// delete the cookies to log out
setcookie("eport_user", "", time() - 3600, "/");
setcookie("eport_email", "", time() - 3600, "/");
setcookie("eport_theme", "", time() - 3600, "/");
setcookie("eport_customer_id", "", time() - 3600, "/");

header("Location: ../login/");

?>
