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

<font size="5" face="Verdana" color="#0066CC">Inventory Assignment</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Edit Scanner</font><br>
<br>

<center>
<form name="frmEdit" action="Inventory.php">
<input type="hidden" name="Cmd" value="Edit">

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
 <tr bgcolor=#3388DD><td colspan=2 align=center><b>Edit Scanner</b></td></tr>
 <tr>
  <td>Unit Number</td>
  <td><i><? echo $EditRow["ID"] ?></i></td>
 </tr>
 <tr valign=top>
  <td>Program</td>
  <td><select name="optProgram">
      <option<? if ($EditRow["PROGRAM"] == "FRUIT") echo " selected" ; ?>>FRUIT</option>
      <option<? if ($EditRow["PROGRAM"] == "PAPER") echo " selected" ; ?>>PAPER</option>
      <option<? if ($EditRow["PROGRAM"] == "SECURITY") echo " selected" ; ?>>SECURITY</option>
      <option<? if ($EditRow["PROGRAM"] == "JUICE") echo " selected" ; ?>>FRUIT</option>
      <option<? if ($EditRow["PROGRAM"] == "STEEL") echo " selected" ; ?>>PAPER</option>
      </select></td>
 </tr>
 <tr valign=top>
  <td>Assign To</td>
  <td><select name="optAssignTo">
<?php
   $sql = "select * from scan_super order by Name";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     echo "  <option" ;
     if ($EditRow["ASSIGN_TO"] == $row["ID"]) echo " selected" ;
     echo ">" . $row["NAME"] . "</option>\n" ;
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
     echo "  <option" ;
     if ($EditRow["LOCATION"] == $row["LOCATION"]) echo " selected" ;
     echo ">" . $row["LOCATION"] . "</option>\n" ;
   }
?>
      </select></td>
 </tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Save">
  <input type="reset"></td>
 </tr>
</table>

</form>
<a href="Inventory.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

