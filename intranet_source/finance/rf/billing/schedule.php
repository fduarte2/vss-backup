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
<title>Port of Wilmington - Schedule RF Storage</title>
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

      date1 = x.date.value

      if (date1 == "") {
	 alert("You need to enter the Date to Schedule RF Storage!");
         return false
      }
   }
</script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Schedule RF Storage</font>
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
<?
  if($success == 1){
    printf("You have scheduled $cron_job Successfully!<br />");
  }
  if($success == 2){
    printf("Error scheduling $cron_job- please contact TS!<br />");
  }
  // Now echo current schedule:
  include("connect.php");
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }

  $stmt = "select * from crontab where run = 'f' and cron_job = 'RF Storage'";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
  $rows = pg_num_rows($result);
  if($rows < 0){               // query error, should not happen
    die("Error in this query: $stmt. \n" . pg_last_error($conn));
  }
  
  // Run these jobs and e-mail the user
  for($i = 0; $i < $rows; $i++){
    $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
    $user = $row['cron_user'];
    $time = $row['cron_time'];
    printf("$user @ $time<br />");
  }
?>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
	   printf("Please enter the Date for RF Storage to run.");
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Date:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="post" action="crontab.php" onsubmit="return validate_form()">
                 <input type="text" name="date" size="22" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="images
/show-calendar.gif" width=24 height=22 border=0 /></a>

               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="Schedule">
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
