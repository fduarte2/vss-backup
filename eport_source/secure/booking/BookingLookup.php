<?

   include("set_values.php");
   $user = $HTTP_COOKIE_VARS["eport_user"];
   $eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
   $type = $HTTP_COOKIE_VARS["eport_user_type"];
   $book_flag = $HTTP_COOKIE_VARS["eport_book_flag"];


   if($user == "" || $book_flag != "T"){
      header("Location: ../booking_login.php");
      exit;
   }

?>

<html>
<head>
<title>Eport of Wilmington - Booking Paper Inventory Report</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
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
	       <td width = "90%" valign = "top" height = "650">
		  <? include("leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
         <? include("BookingLookup_main.php"); ?>
	 <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
