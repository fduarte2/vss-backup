<?
   $user = $HTTP_COOKIE_VARS[directoruser];
   if($user == ""){
      header("Location: ../../director_login.php");
      exit;
   }
   $date = date('m/d/Y');
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Warehouse Utilization Report
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p>
	    <font size="2" face="Verdana">Please select a date and enter a multiplying factor for
pallet size in Sq Feet and click Submit to view the Warehouse Utilization Report.<br /><br />
Please note that the earliest date with data is 05/20/2003.<br />
	 </p>
	 <p>
            <form action="warehouse.php" method="post" name="warehouse">
              As of Date: <input type="textbox" name="run_date" size=15 value="<? echo $date; ?>"><a href="javascript:show_calendar('warehouse.run_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a><br />
              <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Factor: <input type="textbox" name="factor" value="13.33" size="10"><br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;<input type="reset" value="Reset">
            </form>
         </p>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

</body>
</html>
