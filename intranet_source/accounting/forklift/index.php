<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Fork Lift Import";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fork Lift Import</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>

<script type="text/javascript">
   function validate()
   {
      x = document.myform
      expense = x.expense.value
      date1 = x.date1.value
      date2 = x.date2.value
      submitOK="True"
 
      if (expense == "" || expense == "0.00"){
         alert("You must provide a valid hourly expense.")
         submitOK="False"
      }
      if (submitOK == "False"){
         return false
      }
      var go = confirm("Are you sure about the date range " + date1 + " - " + date2 + "?");
       if(go == true){
         return true
       }
       else{
         return false
       }
   }
</script>

<? $today = date('m/d/y'); ?>
   <p><font size="2" face="Verdana">
   Please fill in the Date Range in the proper format and also the hourly rate
for the fork lift expense.
   Click Import botton to begin the transaction.</font></p>

   <FORM METHOD="POST" ACTION="process.php" NAME="myform" target="_new" onsubmit="return validate()">
	<table align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	     <tr>
		<td colspan="4">&nbsp;</td>
	     </tr>
	     <tr>
		<td width="30%">&nbsp;</td>
		<td width="55%" align="center" valign="top">
       <font size="2" face="Verdana"><center>Enter Date Range:</font><br /></center>
<input type="text" name="date1" size="10" value="<? echo $today; ?>">-&nbsp;to&nbsp;-<input type="text" size="10" name="date2" value="<?  echo $today; ?>"><br /><br />
	<center>Enter Hourly Expense:</center>
        <input type="text" name="expense" size="10" value="0.00"><br />
		<td width="65%" align="left">
		<td width="5%">&nbsp;</td>
	     </tr>
	     <tr>
		<td>&nbsp;</td>
	   	<td colspan="2" align="center">
		   <table>
		      <tr>
			 <td width="10%" align="right"> 
			    <input type="Submit" value="  Import  ">&nbsp;&nbsp;
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="35%" align="left">
			    <input type="Reset" value="  Reset  ">
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
   </FORM>
<center>
<a href="import_tips.doc" target="_new"><font size="2" face="Verdana">Import Documentation</font></a>
</center>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
