<?

// delete the cookies to log out
setcookie("eport_user_v2", "", time() - 3600, "/");

header("Location: http://www.eportwilmington.com/login/");

?>
