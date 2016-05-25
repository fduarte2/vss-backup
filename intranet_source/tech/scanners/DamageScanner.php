<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Scanner Assignment - Add Scanner";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

?>

<font size="5" face="Verdana" color="#0066CC">Scanner Inventory</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Damage Scanner</font><br>
<br>

<center>
<form name="frmDamage" action="Inventory.php">
<input type="hidden" name="Cmd" value="Damaged">

<?php
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);
  $sql = "SELECT * FROM SCAN_UNIT WHERE ID=" . $ID ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $EditRow = $row ;
  }
?>
<input type="hidden" name="txtUnit" value="<? echo $EditRow["ID"] ?>">

<table bgcolor=#66AAEE>
 <tr bgcolor=#3388DD><td colspan=2 align=center><b>Damaged Scanner</b></td></tr>
 <tr>
  <td>Unit Number</td>
  <td><i><? echo $EditRow["ID"] ?></i></td>
 </tr>
 <tr><td>Problem</td><td><input type="text" name="txtProblem" size=40></td></tr>
 <tr><td>Date</td><td><input type="text" name="txtDate" value="<? echo date("m/d/Y") ; ?>" size=40></td></tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Save">
  <input type="reset"></td>
 </tr>
</table>

</form>
<a href="Inventory.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

