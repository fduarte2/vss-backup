<?
   include("set_values.php");
   $user = $HTTP_COOKIE_VARS["eport_user"];
   include("canadian_user_auth_cookies.php");

   if($user == ""){
      header("Location: ../canadian_release_login.php");
      exit;
   }
?>

<html>
<head>
<title>Eport of Wilmington - Canadian Release</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("/var/www/secure/canadian_release/header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "500">
		  <? include("/var/www/secure/canadian_release/leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("seal_info.php"); ?>
	 <? include("/var/www/secure/canadian_release/footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
