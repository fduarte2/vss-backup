<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Ship Import";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
    	 <font size="5" face="Verdana" color="#0066CC">Ship Manifest Import</font>
	 <hr>
      </td>
   </tr>
</table>

<?
 include("uploading.php");
?>

<hr>

<font size="2"><b>Transaction</b></font><br>

<form name="TranNum" method="POST" action="process.php">
<input type="hidden" name="Cmd" value="Pop">
<table width="100%" bgcolor="#f0f0f0">
 <tr><td></td></tr>
 <tr><td align=center>Enter Transaction Number</td></tr>
 <tr><td align=center><input type="text" name="TranNum"></td></tr>
 <tr><td align=center><input type="submit" value="Retrieve"> <input type="reset"></td></tr>
 <tr><td></td></tr>
</table>

<?
 include("pow_footer.php"); 
?>
