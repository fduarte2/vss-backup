<?
	include("set_values.php");
	$user = $HTTP_COOKIE_VARS["eport_user_v2"];
	if($user == ""){
      header("Location: ../dole9722_login.php");
      exit;
   }
?>

<html>
<head>
<title>Eport of Wilmington - Canadian Home</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("/var/www/secure/dole9722/header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "650">
		  <? include("/var/www/secure/dole9722/leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("/var/www/secure/dole9722/upload_success_summary.php"); ?>
	 <? include("/var/www/secure/dole9722/footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
