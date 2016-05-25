<?
	include("v2_valid.php");
	include("argen_fruit_db_def.php");
	include("set_values.php");
	$user = $HTTP_COOKIE_VARS["eport_user_v2"];
	if($user == ""){
      header("Location: ../argen_fruit_login.php");
      exit;
   }

	$auth_exp = PageUserCheck($user, "ARGEN_FRUIT_EXPED", $rfconn);
	$auth_port = PageUserCheck($user, "ARGEN_FRUIT_PORT", $rfconn);
?>

<html>
<head>
<title>Eport of Wilmington - Argentine Fruit Inventory</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("/var/www/secure/argen_fruit/header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "650">
		  <? include("/var/www/secure/argen_fruit/leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("Argen_Ship.php"); ?>
	 <? include("/var/www/secure/argen_fruit/footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
