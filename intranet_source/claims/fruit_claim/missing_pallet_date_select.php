<?
/*
*	Adam Walter, Jan/Feb 2008.
*	A page to input a date range for missing_pallet.php
**********************************************************************/
  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Open Claim Report";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
/*  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  } */
  include("connect.php");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Missing Pallet Date Selection
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_pick" action="missing_pallet.php" method="post">
	<tr>
		<td width="40%" align="right"><font size="2" face="Verdana">Start Date:</font></td>
		<td width="60%" align="left"><font size="2" face="Verdana"><input name="start_date" type="text" size="10">&nbsp;&nbsp;&nbsp;(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="40%" align="right"><font size="2" face="Verdana">End Date:</font></td>
		<td width="60%" align="left"><font size="2" face="Verdana"><input name="end_date" type="text" size="10">&nbsp;&nbsp;&nbsp;(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="40%" align="right">&nbsp;</td>
		<td width="60%" align="left"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>