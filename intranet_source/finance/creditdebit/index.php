<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF Reports";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Credit/Debit Memos
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana"><b>From here you can access the Memo System:</b></font>
	 <table border="0" width="95%" cellpadding="2" cellspacing="0">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
	    <tr>
	       <td valign="middle" width="8%"><img src="/images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                 <a href="credit_debit_memo_gen.php">Create Memo</a></font>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="/images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                 <a href="print_cm_dm_values.php">Print</a></font>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="/images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="finalize_cm_dm.php">Finalize</a></font>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	 </table>
      </td>
      <td valign="middle" width="40%">
        <p><img border="0" src="/images/FSI-comp.jpg" width="218" height="170"></p>
      </td>
      <td width="1%">&nbsp;</td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
