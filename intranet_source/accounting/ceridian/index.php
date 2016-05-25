<?
        $user = $HTTP_COOKIE_VARS[accountinguser];
        if($user == ""){
          header("Location: ../../accounting_login.php");
          exit;
        }
?>

<html>
<head>
<title>Port of Wilmington Accounting - Ceridian Import</title>
</head>

<body link="#000080" vlink="#000080" alink="#000080" BGCOLOR=#FFFFFF topmargin="1" leftmargin="0">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td colspan="2" width="100%">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" height = "500" valign = "top"  bgcolor="#4D76A5">
         <table>
	    <tr>
	       <td width = "5%">&nbsp;</td>
	       <td width = "95%">
		  <? include("leftnav.php"); ?>
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
