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
<font size="4" face="Verdana" color="#0066CC">Add Scanner</font><br>
<br>

<center>
<form name="frmAdd" action="Inventory.php">
<input type="hidden" name="Cmd" value="Add">

<?php
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
?>

<table bgcolor=#66AAEE>
 <tr bgcolor=#3388DD><td colspan=2 align=center><b>Add Scanner</b></td></tr>
 <tr>
  <td>Unit Number</td>
  <td><input type="text" name="txtUnit" size=4></td>
 </tr>
 <tr valign=top>
  <td>Program</td>
  <td><select name="optProgram">
      <option>FRUIT</option>
	  <option>JUICE</option>
      <option>CLEMENTINES</option>
      <option>PAPER</option>
	  <option>STEEL</option>
      <option>SECURITY</option>
      </select></td>
 </tr>
 <tr valign=top>
  <td>Assign To</td>
  <td><select name="optAssignTo">
<?php
   $cursor = ora_open($conn);
   $sql = "select Name from scan_super order by Name";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     echo "  <option>" . $row["NAME"] . "</option>\n" ;
   }
?>
      </select></td>
 </tr>
 <tr valign=top>
  <td>Location</td>
  <td><select name="optLocation">
<?php
   $cursor = ora_open($conn);
   $sql = "select Location from scan_location order by Location";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     echo "  <option>" . $row["LOCATION"] . "</option>\n" ;
   }
?>
      </select></td>
 </tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Add">
  <input type="reset"></td>
 </tr>
</table>

</form>

<br>
<a href="Inventory.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

