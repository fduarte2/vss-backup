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
<title>Eport of Wilmington - Dole Wing-H Home</title>
<script src="../functions/sorttable.js"></script>
</head>

<body  BGCOLOR=#FFFF99 topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "500">
		  <? include("leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
<? 
	if($auth_exp !== false || $auth_port !== false){
		include("order_list.php");
	} else {
		echo "<font color=\"#FF0000\"><b>User not authorized to view this page.</b></font>";
	}
?>
	 <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>