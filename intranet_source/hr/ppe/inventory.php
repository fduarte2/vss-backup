<?
// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Personal Protection Equipment";
$area_type = "HRMS";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
  printf("Access Denied from HRMS system");
  include("pow_footer.php");
  exit;
}
?>


<script type="text/javascript">
  function ClickButton(Cmd, ID) {
    document.frmInventory.Cmd.value = Cmd
    document.frmInventory.ID.value = ID
    document.frmInventory.submit()
  }

  function validate_add() {
    x = document.frmAddInventory
      
    ID = x.ID.value
    Description = x.Description.value
    Quantity = x.Quantity.value

    if (ID == "") {
      alert("Please enter the ID number!")
      return false
    }

    if (Description == "") {
      alert("Please enter the Description!")
      return false
    }

    if (Quantity == "") {
      alert("Please enter the Quantity")
      return false
    }

    return true
  }
</script>

<?
include("connect.php");
$conn = ora_logon("LABOR@LCS", "LABOR");
if($conn < 1){
  printf("Error logging on to the Oracle Server: ");
  printf(ora_errorcode($conn));
  printf("</body></html>");
  exit;
}
$cursor = ora_open($conn) ;
$cursor2 = ora_open($conn) ;

$EditID = -1 ;
if (isset($HTTP_POST_VARS["Cmd"])) {
  $Cmd = $HTTP_POST_VARS["Cmd"] ;
  if ($Cmd == "Edit") {
    $EditID = $HTTP_POST_VARS["ID"] ; 
  }
  if ($Cmd == "Save") {
    if ($HTTP_POST_VARS["Active"] == "on")
      $Active = 1 ;
    else
      $Active = 0 ;
    $query = "UPDATE PPE_INVENTORY SET ID='" . $HTTP_POST_VARS["NewID"] . "',DESCRIPTION='" . $HTTP_POST_VARS["Description"] . "'" ;
    $query .= ",ACTIVE=" . $Active . ",NOTES='" . $HTTP_POST_VARS["Notes"] . "',CATEGORY='" . $HTTP_POST_VARS["Category"] . "'" ;
    $query .= ", QUANTITY=" . $HTTP_POST_VARS["Quantity"] . " WHERE ID='" . $HTTP_POST_VARS["ID"] . "'" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
   }
  if ($Cmd == "Add") {
    if ($HTTP_POST_VARS["Active"] == 1)
      $Active = 1 ;
    else
      $Active = 0 ;
    $query = "INSERT INTO PPE_INVENTORY (ID,DESCRIPTION,CATEGORY,ACTIVE,NOTES,QUANTITY)" ;
    $query .= " VALUES ('" . $HTTP_POST_VARS["ID"] . "','" . $HTTP_POST_VARS["Description"] . "'" ;
    $query .= ",'" . $HTTP_POST_VARS["Category"] . "'," . $Active . ",'" . $HTTP_POST_VARS["Notes"] . "'," . $HTTP_POST_VARS["Quantity"] . ")" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
  }
}

?>

<font size="5" face="Verdana" color=#0066CC>Personal Protection Equipment</font><br>
<hr>
<font size="2" face="Verdana"><b>Inventory</b></font><br>
<br>

<form name="frmInventory" method="POST" action="inventory.php">
<input type="hidden" name="ID" value="-1">
<input type="hidden" name="Cmd" value="">
<table width="100%" bgcolor="#f0f0f0" border=0>
 <tr>
  <td><font size="2"><b>ID</b></td>
  <td><font size="2"><b>Description</b></td>
  <td><font size="2"><b>Category</b></td>
  <td align="center"><font size="2"><b>Qty</b></td>
  <td><font size="2"><b>Active</b></td>
  <td><font size="2"><b>Notes</b></td>
  <td></td>
 </tr>
<?

$query = "SELECT * FROM PPE_INVENTORY ORDER BY ACTIVE, ID, DESCRIPTION" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  if ($EditID == $row["ID"]) {
?>
 <tr valign="top">
  <td><input type="text" name="NewID" size="10" value=<? echo "\"" . $row["ID"] . "\"" ; ?>></td>
  <td><input type="text" name="Description" size="40" value=<? echo "\"" . $row["DESCRIPTION"] . "\"" ; ?>></td>
  <td><select name="Category">
      <option <? if ($row["CATEGORY"] == "Coats") echo "selected" ; ?>>Coats</option>
      <option <? if ($row["CATEGORY"] == "Masks") echo "selected" ; ?>>Masks</option>
      <option <? if ($row["CATEGORY"] == "Glasses") echo "selected" ; ?>>Glasses</option>
      <option <? if ($row["CATEGORY"] == "Gloves") echo "selected" ; ?>>Gloves</option>
      <option <? if ($row["CATEGORY"] == "Hats") echo "selected" ; ?>>Hats</option>
      <option <? if ($row["CATEGORY"] == "Pants") echo "selected" ; ?>>Pants</option>
      <option <? if ($row["CATEGORY"] == "Shirts") echo "selected" ; ?>>Shirts</option>
      <option <? if ($row["CATEGORY"] == "Shoes") echo "selected" ; ?>>Shoes</option>
      </select></td>
  <td align="center"><input type="text" name="Quantity" size="3" value=<? echo "\"" . $row["QUANTITY"] . "\"" ?>></td>
  <td><input type="checkbox" name="Active" <? if ($row["ACTIVE"] == 1) echo "checked" ; ?>></td>
  <td><textarea name="Notes" cols="40" rows="1"><? echo $row["NOTES"] ; ?></textarea></td>
  <td><input type="button" value="Save" <? echo "OnClick=\"javascript: ClickButton('Save','" . $row["ID"] . "') ;\"" ; ?> >
      <input type="button" value="Cancel" <? echo "OnClick=\"javascript: ClickButton('Cancel','" . $row["ID"] . "') ;\"" ; ?> ></td>
 </tr>
<?
  } else {
?>
 <tr valign="top">
  <td><font size="2"><? echo $row["ID"] ; ?></td>
  <td><font size="2"><? echo $row["DESCRIPTION"] ; ?></td>
  <td><font size="2"><? echo $row["CATEGORY"] ; ?></td>
  <td align="center"><font size="2"><? echo $row["QUANTITY"] ; ?></td>
  <td><font size="2"><? if ($row["ACTIVE"] == 1) echo "Active" ; else echo "Inactive" ; ?></td>
  <td><font size="2"><? echo $row["NOTES"] ; ?></td>
  <td><input type="button" value="Edit" <? echo " OnClick=\"javascript: ClickButton('Edit','" . $row["ID"] . "') ; \"" ; ?> ></td>
 </tr>
<?
  }
}

?>
</table>
</form>
<br>
<hr>
<font size="2" face="Verdana"><b>Add Inventory Item</b></font><br>
<br>

<form name="frmAddInventory" method="POST" action="inventory.php" onsubmit="return validate_add()">
<input type="hidden" name="Cmd" value="Add">
<table width="100%" bgcolor="#f0f0f0">
 <tr>
  <td width="5%">&nbsp;</td>
  <td>ID</td>
  <td>Description</td>
  <td>Category</td>
  <td>Qty</td>
  <td align=center>Active</td>
  <td>Notes</td>
  <td width="5%">&nbsp;</td>
 </tr>
 <tr valign="top">
  <td></td>
  <td><input type="text" name="ID" size="10" value=""></td>
  <td><input type="text" name="Description" size="40" value=""></td>
  <td>
    <select name="Category">
      <option>Coats</option>
      <option>Masks</option>
      <option>Glasses</option>
      <option>Gloves</option>
      <option>Hats</option>
      <option>Pants</option>
      <option>Shirts</option>
      <option>Shoes</option>
    </select>
  </td>
  <td><input type="text" name="Quantity" size="3" value="1"></td>
  <td align=center><input type="checkbox" name="Active" checked value="1"></td>
  <td><textarea name="Notes" rows="3" cols="50"></textarea></td>
  <td></td>
 </tr>
 <tr><td align=center colspan=7><input type="submit" value="Add"> <input type="reset"></td></tr>
</table>
</form>

<?
ora_close($cursor) ;
ora_close($cursor2) ;

include("ppe_links.php") ;
include("pow_footer.php") ;
?>
