<?
   include("set_values.php");
   include("eport_functions.php");
   $user = $HTTP_COOKIE_VARS["eport_user"];

	if($HTTP_POST_VARS['vessel'] != ""){
		setcookie("cargo_vessel", "$vessel", time() + 86400, "/");
	}
	if($vessel == ""){
		$vessel = $HTTP_COOKIE_VARS["cargo_vessel"]; // last case chance, if they are using the Leftnav link
	}

	if($HTTP_POST_VARS['submit'] == "Retrieve / Refresh"){
		setcookie("ams_filter_cmd", $HTTP_POST_VARS['ams_filter_cmd'], time() + 86400, "/");
		setcookie("line_filter_cmd", $HTTP_POST_VARS['line_filter_cmd'], time() + 86400, "/");
		setcookie("ohl_filter_cmd", $HTTP_POST_VARS['ohl_filter_cmd'], time() + 86400, "/");
		setcookie("final_filter_cmd", $HTTP_POST_VARS['final_filter_cmd'], time() + 86400, "/");
		setcookie("cont_filter_cmd", $HTTP_POST_VARS['cont_filter_cmd'], time() + 86400, "/");
		setcookie("cons_filter_cmd", $HTTP_POST_VARS['cons_filter_cmd'], time() + 86400, "/");
		setcookie("bol_filter_cmd", $HTTP_POST_VARS['bol_filter_cmd'], time() + 86400, "/");
		setcookie("shipline_filter_cmd", $HTTP_POST_VARS['shipline_filter_cmd'], time() + 86400, "/");
		setcookie("broker_filter_cmd", $HTTP_POST_VARS['broker_filter_cmd'], time() + 86400, "/");
		setcookie("comm_filter_cmd", $HTTP_POST_VARS['comm_filter_cmd'], time() + 86400, "/");
		setcookie("mode_filter_cmd", $HTTP_POST_VARS['mode_filter_cmd'], time() + 86400, "/");
		setcookie("load_filter_cmd", $HTTP_POST_VARS['load_filter_cmd'], time() + 86400, "/");
		setcookie("containers_only_cmd", $HTTP_POST_VARS['containers_only_cmd'], time() + 86400, "/");
	}

   if($user == ""){
      header("Location: ../cargo_release_login.php");
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
         <? include("/var/www/secure/cargo_release/header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "500">
		  <? include("/var/www/secure/cargo_release/leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("cargo_scoreboard.php"); ?>
	 <? include("/var/www/secure/cargo_release/footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
