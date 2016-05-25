<HTML>
<Head>
<title>Printer Tracking - Printer Types</title>
</Head>
<Body>
<a href="index.php">Printer Management</a>:<a href="printers.php">Printers</a>

<?php
$conn = mysql_connect ("localhost", "", "") or die ("Cannot connect to server. Please try again later") ;
mysql_select_db ("Printers") or die ("Database not found. Please try again later") ;

$Command = $_SERVER['QUERY_STRING'] ;
if (strlen($Command) > 0) {
  parse_str ($Command) ;
  if ($Cmd == "Add") {
    list($Item, $Desc) = split("-", $Toner) ;
    $query = "INSERT INTO Printer_Type (Brand, Model, Type, Toner_ID)" ;
    $query .= " VALUES ('" . $Brand . "','" . $Model . "','" . $Type . "'," . $Item . ")" ;
    $rs = mysql_query($query) or die("Query contains error") ;
  }
  if ($Cmd == "Del") {
    $query = "DELETE FROM Printer_Type WHERE ID=" . $ID ;
    $rs = mysql_query($query) or die("Query contains error") ;
  }
  if ($Cmd == "Mod") {
    list($Item, $Desc) = split("-", $MToner) ;
    $query = "UPDATE Printer_Type" ;
    $query .= " SET Brand='" . $MBrand . "', Model='" . $MModel . "', Type='" . $MType . "', Toner_ID=" . $Item ;
    $query .= " WHERE ID=" . $MID ;
    $rs = mysql_query($query) or die("Query contains error:<br>" . $query) ;
  }
  if ($Cmd == "Edit") {
    $EditID = $ID ;
  } else {
    $EditID = -1 ;
  }

}

?>

<center>
<h2>Printer Type</h2>

<form name="frmModify">
<input type="hidden" name="Cmd" value="Mod">
<table bgcolor="#000000">
 <tr bgcolor="#AFAFFF">
  <td><b>Brand</td>
  <td><b>Model</td>
  <td><b>Type</td>
  <td><b>Toner</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>

<?php

$rs = mysql_query("SELECT * FROM Printer_Type") or die ("No Information Available") ;

while ($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
  echo " <tr bgcolor=\"#CFCFCF\">\n" ;
  if ($EditID == $line["ID"]) {
    echo "  <td><input type=\"text\" name=\"MBrand\" value=\"" . $line["Brand"] . "\" size=15></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MModel\" value=\"" . $line["Model"] . "\" size=15></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MType\" value=\"" . $line["Type"] . "\" size=6></td>\n" ;
    $rsToner = mysql_query("SELECT * FROM Toner_Type") or die ("No Information Available1") ;
    echo "  <td><select name=\"MToner\">\n" ;
    while ($Toners = mysql_fetch_array($rsToner, MYSQL_ASSOC)) {
      echo "  <option>" . $Toners["ID"] . "-" . $Toners["Part_Num"] . "</option>\n" ;
    }
    echo "</select></td>\n" ;
  } else {
    echo "  <td>" . $line["Brand"] . "</td>\n" ;
    echo "  <td>" . $line["Model"] . "</td>\n" ;
    echo "  <td>" . $line["Type"] . "</td>\n" ;
    $query = "SELECT * FROM Toner_Type WHERE ID=" . $line["Toner_ID"] ;
    $rsToner = mysql_query($query) or die ("No Information Available: " . $query) ;
    while ($Toners = mysql_fetch_array($rsToner, MYSQL_ASSOC)) {
      echo "  <td>" . $Toners["Part_Num"] . "</td>\n" ;
    }
  }
  if ($EditID == $line["ID"]) {
    echo "  <td><input type=\"submit\" value=\"Save\"><input type=\"hidden\" name=\"MID\" value=\"" . $line["ID"] . "\"></td>" ;
  } else {
    echo "  <td><a href=\"printer_type.php?Cmd=Edit&ID=" . $line["ID"] . "\">Edit</a></td>" ;
  }
  echo "  <td><a href=\"printer_type.php?Cmd=Del&ID=" . $line["ID"] . "\">Delete</a></td>" ;
  echo " </tr>\n" ;
}

mysql_free_result ($rs) ;

?>
</table>
</form>

<center>
<form name="frmPrinter">
<input type="hidden" name="Cmd" value="Add">
<table>
 <tr>
  <td><b>Brand</td>
  <td><b>Model</td>
  <td><b>Type</td>
  <td><b>Toner</td>
 </tr>
 <tr>
  <td><input type="Text" name="Brand"></td>
  <td><input type="Text" name="Model"></td>
  <td><input type="Text" name="Type"></td>
  <td><select name="Toner">
<?php
$rs = mysql_query("SELECT * FROM Toner_Type") or die ("No Information Available") ;
while ($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
  echo "<option>" . $line["ID"] . "-" . $line["Part_Num"] . "</option>" ;
}
mysql_close ($conn) ;
?>
    </select>
  </td>
 </tr>
</table>
<input type="submit" name="cmdSubmit" value="Add">
</form>

</Body>
</HTML>