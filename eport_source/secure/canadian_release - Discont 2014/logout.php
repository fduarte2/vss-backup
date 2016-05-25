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
setcookie("eport_user_type", "$user_type", time() - 3600, "/");
setcookie("eport_user_subtype", "$user_subtype", time() - 3600, "/");
setcookie("eport_user_ams_auth", "$ams_auth", time() - 3600, "/");
setcookie("eport_user_line_auth", "$line_auth", time() - 3600, "/");
setcookie("eport_user_ohl_auth", "$ohl_auth", time() - 3600, "/");
setcookie("eport_user_trucker_auth", "$trucker_auth", time() - 3600, "/");
setcookie("eport_user_seal_auth", "$seal_auth", time() - 3600, "/");
setcookie("eport_user_mode_auth", "$mode_auth", time() - 3600, "/");

header("Location: http://www.eportwilmington.com/login/");

?>
