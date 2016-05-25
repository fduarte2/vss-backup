<?

// delete the cookies to log out
setcookie("eport_user", "", time()-12000000, "/");
setcookie("eport_email", "", time()-12000000, "/");
setcookie("eport_theme", "", time()-12000000, "/");
setcookie("eport_customer_id", "", time()-12000000, "/");
setcookie("eport_user_type", "", time()-12000000, "/");
setcookie("eport_user_subtype", "", time()-12000000, "/");
setcookie("eport_DT_flag", "", time()-12000000, "/");

//header("Location: http://www.eportwilmington.com/login/");
header("Location: ../login/");

?>
