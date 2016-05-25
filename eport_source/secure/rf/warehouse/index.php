<?
   include("set_values.php");
   $eport_customer_id = 0;
   $user = $HTTP_COOKIE_VARS['eport_user'];
   if($user == ""){
      header("Location: ../../login.php");
      exit;
   }

?>

<html>
<head>
<title>Port of Wilmington Eport - Inventory Reports</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
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
		  <? include("../leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("main.php"); ?>
	 <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
