<?
   include("../set_values.php");
   $user = $HTTP_COOKIE_VARS[financeuser];
   if($user == ""){
      header("Location: ../../finance_login.php");
      exit;
   }

?>

<html>
<head>
<title>Port of Wilmington Eport - Activity Report</title>
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
	       <td width = "90%" valign = "top" height = "500">
		  <? include("leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
<!-- Activity - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      date1 = x.start_date.value
      date2 = x.end_date.value

      if (date1 == "" || date2 == "") {
	 alert("You need to enter an Order Number or an Activity Date to view the Activity Report!");
         return false
      }
   }
</script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Void Activity Report</font>
         <br />
	 <hr>
      </td>
   </tr>
<?
   if ($eport_customer_id != 0) {
?>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana">
	 | <a href="../">Home</a>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
<?
   }
?>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
	   printf("Please enter Start Date and End Date to view the Activity Report.");
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Start Date:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="void_report.php" onsubmit="return validate_form()">
                 <input type="text" name="start_date" size="22" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.start_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="images
/show-calendar.gif" width=24 height=22 border=0 /></a>

               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right"><font size="2" face="Verdana">Date:</font></td>
	       <td align="left">
	          <input type="text" name="end_date" size="22" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.end_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View the Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>
	 <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
