<?

// delete the cookies to log out
setcookie("eport_user", "", time() - 4000000, "/");
setcookie("eport_email", "", time() - 4000000, "/");
setcookie("eport_theme", "", time() - 4000000, "/");
setcookie("eport_customer_id", "", time() - 4000000, "/");
setcookie("eport_user_type", "", time() - 4000000, "/");

//header("Location: http://www.eportwilmington.com/login/");
header("Location: ../login/");

?>
