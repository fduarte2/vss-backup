<?

// delete the cookies to log out
setcookie("eport_user", "", time());
setcookie("eport_email", "", time());
setcookie("eport_theme", "", time());
setcookie("eport_customer_id", "", time());

header("Location: http://www.eportwilmington.com/login/");

?>
