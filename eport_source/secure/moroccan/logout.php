<?

// delete the cookies to log out
//setcookie("eport_user", "", time());
//setcookie("eport_email", "", time());
//setcookie("eport_theme", "", time());
//setcookie("eport_customer_id", "", time());

//header("Location: http://www.eportwilmington.com/login/");
setcookie("eport_user", "", time() - 3600, "/");
setcookie("eport_email", "", time() - 3600, "/");
setcookie("eport_theme", "", time() - 3600, "/");
setcookie("eport_customer_id", "", time() - 3600, "/");
setcookie("eport_mor_customer_id", "", time() - 3600, "/");

header("Location: https://www.eportwilmington.com/login/");

?>
