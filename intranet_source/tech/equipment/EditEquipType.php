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
<font size="4" face="Verdana" color="#0066CC">Edit Equipment Type</font><br>
<br>

<center>
<form name="frmEdit" action="EquipType.php">
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
  $sql = "SELECT * FROM EQUIP_TYPE WHERE ID=" . $ID ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $EditRow = $row ;
  }
?>
<input type="hidden" name="ID" value="<? echo $EditRow["ID"] ?>">

<table bgcolor=#99CCFF>
 <tr><td colspan=2 align=center><b>Edit Type</b></td></tr>
 <tr><td colspan=2 bgcolor=#000000></td></tr>
 <tr>
  <td>ID</td>
  <td><i><? echo $EditRow["ID"] ?></i></td>
 </tr>
 <tr valign=top>
  <td>Category</td>
  <td><input type="text" name="txtCategory" value="<? echo $EditRow["CATEGORY"] ?>"> 
      <select name=optCat OnClick="JavaScript: document.frmEdit.txtCategory.value = document.frmEdit.optCat.value">
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
  <td><input type="text" name="txtBrand" value="<? echo $EditRow["BRAND"] ?>"></td>
 </tr>
 <tr valign=top>
  <td>Model</td>
  <td><input type="text" name="txtModel" value="<? echo $EditRow["MODEL"] ?>"></td>
 </tr>
 <tr valign=top>
  <td>Description</td>
  <td><input type="text" name="txtDescription" value="<? echo $EditRow["DESCRIPTION"] ?>"></td>
 </tr>
 <tr>
  <td colspan=2 align=center><input type="submit" value="Save">
  <input type="reset"></td>
 </tr>
</table>

</form>
<a href="EquipType.php">Cancel</a><br>

<br>

<? include("pow_footer.php"); ?>

