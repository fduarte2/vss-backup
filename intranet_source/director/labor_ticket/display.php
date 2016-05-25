<?
   $user = $HTTP_COOKIE_VARS[directoruser];
   if($user == ""){
      header("Location: ../../director_login.php");
      exit;
   }
?>

<html>
<head>
<title>Port of Wilmington Director - Paid Hours vs Labor Tickets</title>
</head>

<body link="#000080" vlink="#000080" alink="#000080">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width="100%">
         <? include("../header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width="10%" valign="top"  bgcolor="#4D76A5">
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
      <?
         include("main3.php");
	 include("footer.php");
      ?>
      </td>
   </tr>
</table>

</body>
</html>
