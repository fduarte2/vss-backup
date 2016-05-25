<?

// delete the cookies to log out
setcookie("eport_user", "", time()-1200, "/");
setcookie("eport_email", "", time()-1200, "/");
setcookie("eport_theme", "", time()-1200, "/");
setcookie("eport_customer_id", "", time()-1200, "/");
setcookie("eport_user_subtype", "", time()-1200, "/");
setcookie("eport_user_type", "", time()-1200, "/");
setcookie("eport_book_flag", "", time()-1200, "/");

//header("Location: http://www.eportwilmington.com/login/");
header("Location: ../login/");

?>
