<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Payroll Accrual";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript">
   function validate_date()
   {
      x = document.date_form
      start_date = x.start_date.value
      end_date = x.end_date.value

      if(start_date == "")
      {
         alert("Please enter the start date!")
         return false
      }

      if(end_date == "")
      {
         alert("Please enter the end date!")
         return false
      }

      return true
    }
</script>

<?
   // calulate the start date and end date
   // get the last day of the past month first
   $end_date = date("m/d/Y", mktime(0, 0, 0, date("m"), 0, date("Y")));
   $end_date_info = getdate(strtotime($end_date));

   if($end_date_info['wday'] == 0){	// if it is Sunday
      printf("Last Day of the past month is the end of the last payroll period. 
	      No Payroll data is to be accrued!");
      $end_date = "";
      $start_date = "";
   }elseif($end_date_info['wday'] == 1){
      $start_date = $end_date;
   }else{
      $start_date = date("m/d/Y", strtotime("last Monday", strtotime($end_date)));
   }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
    	 <font size="5" face="Verdana" color="#0066CC">Payroll Accrual</font>
	 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <br />
   	 <font size="2" face="Verdana">
	    Please input start date and end date, then click on OK botton to begin the transaction.</font>
	 <br /><br />
      </td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td align="center">
   	 <form method="Post" name="date_form" action="process.php" onsubmit="return validate_date()">
	 <table align="center" width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
   	    <tr>
      	       <td colspan="4">&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td width="5%">&nbsp;</td>
               <td width="35%" align="right" valign="top">
	          <font size="2" face="Verdana">Start Date:</font></td>
               <td width="55%" align="left">
   		  <input type="text" name="start_date" size="20" value=<?= $start_date ?>>
      	       </td>
      	       <td width="5%">&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td>&nbsp;</td>
               <td align="right" valign="top">
	          <font size="2" face="Verdana">End Date:</font></td>
               <td align="left">
   		  <input type="text" name="end_date" size="20" value=<?= $end_date ?>>
      	       </td>
      	       <td>&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td>&nbsp;</td>
      	       <td colspan="2" align="center">
	 	  <table border="0">
	    	     <tr>
	       		<td width="40%" align="right" valign="middle"> 
		  	   <input type="Submit" value=" Submit ">
	       		</td>
 	       		<td width="20%">&nbsp;</td>
	       		<td width="40%" align="left" valign="middle">
		  	   <input type="Reset" value=" Reset  ">
	       		</td>
    	    	     </tr>
         	  </table>		
      	       </td>
               <td>&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td colspan="4">&nbsp;</td>
   	    </tr>
	 </table>
         </form>
	 <font size="2" face="Verdana"><a href="accrual_table.php">Accrual Values Table</a><BR></font><hr>
	 <a href="accrual_tips.doc" target="_new">
	    <font size="2" face="Verdana">Payroll Accrual Help</font>
	 </a>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
