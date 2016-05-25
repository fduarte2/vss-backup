<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Equipment Tracking";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Edit Equipment</font><br>
<br>

<center>
<form name="frmEdit" action="Equipment.php">
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
  $sql = "SELECT * FROM EQUIP WHERE ID=" . $ID ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $EditRow = $row ;
  }
?>
<input type="hidden" name="ID" value="<? echo $EditRow["ID"] ?>">

<table bgcolor=#99CCFF>
 <tr><td colspan=2 align=center><b>Edit Equipment</b></td></tr>
 <tr><td colspan=2 bgcolor=#000000></td></tr>
 <tr>
  <td>ID</td>
  <td><i><? echo $EditRow["ID"] ?></i></td>
 </tr>
 <tr valign=top>
  <td>Equipment Type</td>
  <td>
      <select name=optType>
<?
  $sql = "SELECT * FROM EQUIP_TYPE ORDER BY BRAND,MODEL" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    echo "<option" ;
    if ($EditRow["EQUIP_TYPE_ID"] == $row["ID"]) echo " selected" ;
    echo " value=\"" . $row["ID"] . "\"" ;
    echo ">" . $row["BRAND"] . " " . $row["MODEL"] . "</option>" ;
  }
?>
      </select></td>
 </tr>
 <tr valign=top>
  <td>Serial Num</td>
  <td><input type="text" name="txtSerialNum" size=10 value="<? echo $EditRow["SERIALNUM"] ?>"></td>
 </tr>
 <tr valign=top>
  <td>Assign To</td>
  <td><input type="text" name="txtAssignTo" size=15 value="<? echo $EditRow["ASSIGNED_TO"] ?>"></td>
 </tr>
 <tr valign=top>
  <td>Purchased</td>
  <td><input type="text" name="txtPurchased" size=10 value="<? echo date("m/d/Y", strtotime($EditRow["PURCHASED"])) ?>"></td>
 </tr>
 <tr valign=top>
  <td>Notes</td>
  <td><input type="text" name="txtNotes" size=20 value="<? echo $EditRow["NOTES"] ?>"></td>
 </tr>
 <tr valign=top>
  <td>Status</td>
  <td><select name=optStatus>
      <option <? if ($EditRow["STATUS"] == "Ordered") echo " selected" ?>>Ordered</option>
      <option <? if ($EditRow["STATUS"] == "Install") echo " selected" ?>>Install</option>
      <option <? if ($EditRow["STATUS"] == "Active") echo " selected" ?>>Active</option>
      <option <? if ($EditRow["STATUS"] == "Retired") echo " selected" ?>>Retired</option>
      <option <? if ($EditRow["STATUS"] == "Offline") echo " selected" ?>>Offline</option>
      </select>
 </tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Save">
  <input type="reset"></td>
 </tr>
</table>

</form>
<a href="Equipment.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

