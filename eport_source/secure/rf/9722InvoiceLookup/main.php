<!-- Activity - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      order_num = x.order_num.value
      date = x.date.value

      if (order_num == "" && date == "") {
	 alert("You need to enter an Invoice Number");
         return false
      }
   }
</script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Dole Fresh Invoice Report</font>
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
	 &nbsp;
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
         if($input == "1"){
           printf("<b>Invoice Number not found!  <br /><br /></b>");
         }
         if ($eport_customer_id != 0) {
	   printf("Please enter an Invoice Number");
	 } else {
	   printf("Please enter an Invoice Number");
	 }
     ?>

         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Invoice Number:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="report.php" onsubmit="return validate_form()">
                 <input type="textbox" name="invoice_num" size="22">
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	   	<tr>
			<td colspan="4"><hr></td>
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
