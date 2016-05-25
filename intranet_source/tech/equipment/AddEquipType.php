<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Equipment Tracking - Add Equipment";
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
<font size="4" face="Verdana" color="#0066CC">Add Equipment Type</font><br>
<br>

<center>
<form name="frmAdd" action="EquipType.php">
<input type="hidden" name="Cmd" value="Add">

<?php
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);
?>

<table bgcolor=#99CCFF>
 <tr><td colspan=2 align=center><b>Add Type</b></td></tr>
 <tr><td colspan=2 bgcolor=#000000></td></tr>
 <tr>
  <td>Category</td>
  <td><input type="text" name="txtCategory" size=10>
      <select name=optCat OnClick="JavaScript: document.frmAdd.txtCategory.value = document.frmAdd.optCat.value">
<?
  $sql = "SELECT DISTINCT CATEGORY FROM EQUIP_TYPE ORDER BY CATEGORY" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    echo "<option>" ;
    echo $row["CATEGORY"] ;
    echo "</option>" ;
  }
?>
    </select>
    </td>
 </tr>
 <tr valign=top>
  <td>Brand</td>
  <td><input type="text" name="txtBrand" size=10></td>
 </tr>
 <tr valign=top>
  <td>Model</td>
  <td><input type="text" name="txtModel" size=15></td>
 </tr>
 <tr valign=top>
  <td>Description</td>
  <td><input type="text" name="txtDescription" size=10></td>
 </tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Add">
  <input type="reset"></td>
 </tr>
</table>

</form>

<br>
<a href="EquipType.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

