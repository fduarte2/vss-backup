<HTML>
<Head>
<title>Printer Tracking</title>
</Head>
<Body>
<a href="index.php">Printer Management</a>

<?php
$conn = mysql_connect ("localhost", "", "") or die ("Cannot connect to server. Please try again later") ;
mysql_select_db ("Printers") or die ("Database not found. Please try again later") ;

$Command = $_SERVER['QUERY_STRING'] ;
if (strlen($Command) > 0) {
  parse_str ($Command) ;
  if ($Cmd == "Add") {
    list($Item, $Label) = split("-", $PType) ;
    $query = "INSERT INTO Printer (Printer_ID, Printer_Name, Location, Services)" ;
    $query .= " VALUES (" . $Item . ",'" . $PName . "','" . $Loc . "','" . $Serv . "')" ;
    $rs = mysql_query($query) or die("Query contains error: " . $query) ;
  }
  if ($Cmd == "Del") {
    $query = "DELETE FROM Printer WHERE ID=" . $ID ;
    $rs = mysql_query($query) or die("Query contains error") ;
  }
  if ($Cmd == "Mod") {
    list($Item, $Label) = split("-", $MPType) ;
    $query = "UPDATE Printer" ;
    $query .= " SET Printer_ID=" . $Item . ", Printer_Name='" . $MPName . "', Location='" . $MLoc . "', Services='" . $MServ . "'" ;
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
<h2>Printers</h2>

<form name="frmModify">
<input type="hidden" name="Cmd" value="Mod">
<table bgcolor="#000000">
 <tr bgcolor="#AFAFFF">
  <td><b>Printer</td>
  <td><b>Network Name</td>
  <td><b>Location</td>
  <td><b>Services</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>

<?php

$rs = mysql_query("SELECT * FROM Printer") or die ("No Information Available") ;

while ($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
  echo " <tr bgcolor=\"#CFCFCF\">\n" ;
  if ($EditID == $line["ID"]) {
    echo "<td><select name=\"MPType\">" ;
    $rsPrint = mysql_query("SELECT * FROM Printer_Type") or die("None") ;
    while ($Printer = mysql_fetch_array($rsPrint, MYSQL_ASSOC)) {
      if ($Printer["ID"] == $line["Printer_ID"]) {
	echo "<option selected>" . $Printer["ID"] . "-" . $Printer["Brand"] . " " . $Printer["Model"] . "</option>" ;
      } else {
	echo "<option>" . $Printer["ID"] . "-" . $Printer["Brand"] . " " . $Printer["Model"] . "</option>" ;
      }
    }
    echo "</select></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MPName\" value=\"" . $line["Printer_Name"] . "\" size=10></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MLoc\" value=\"" . $line["Location"] . "\" size=15></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MServ\" value=\"" . $line["Services"] . "\" size=15></td>\n" ;
  } else {
    $rsPrint = mysql_query("SELECT * FROM Printer_Type WHERE ID=" . $line["Printer_ID"]) or die("None") ;
    while ($Printer = mysql_fetch_array($rsPrint, MYSQL_ASSOC)) {
      echo "  <td>" . $Printer["Brand"] . " " . $Printer["Model"] . "</td>" ;
    }
    echo "  <td>" . $line["Printer_Name"] . "</td>\n" ;
    echo "  <td>" . $line["Location"] . "</td>\n" ;
    echo "  <td>" . $line["Services"] . "</td>\n" ;
  }

  if ($EditID == $line["ID"]) {
    echo "  <td><input type=\"submit\" value=\"Save\"><input type=\"hidden\" name=\"MID\" value=\"" . $line["ID"] . "\"></td>" ;
  } else {
    echo "  <td><a href=\"printers.php?Cmd=Edit&ID=" . $line["ID"] . "\">Edit</a></td>" ;
  }
  echo "  <td><a href=\"printers.php?Cmd=Del&ID=" . $line["ID"] . "\">Delete</a></td>" ;
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
  <td><b>Printer</td>
  <td><b>Network Name</td>
  <td><b>Location</td>
  <td><b>Services</td>
 </tr>
 <tr>
  <td><select name="PType">

<?php
$rsPrint = mysql_query("SELECT * FROM Printer_Type") or die("None") ;
while ($Printer = mysql_fetch_array($rsPrint, MYSQL_ASSOC)) {
  if ($Printer["ID"] == $line["Printer_ID"]) {
    echo "<option selected>" . $Printer["ID"] . "-" . $Printer["Brand"] . " " . $Printer["Model"] . "</option>" ;
  } else {
    echo "<option>" . $Printer["ID"] . "-" . $Printer["Brand"] . " " . $Printer["Model"] . "</option>" ;
  }
}
echo "</select>\n" ;
mysql_close ($conn) ;
?>
  </td>
  <td><input type="Text" name="PName"></td>
  <td><input type="Text" name="Loc"></td>
  <td><input type="Text" name="Serv"></td>
 </tr>
</table>
<input type="submit" name="cmdSubmit" value="Add">
</form>
<br>
<a href="printer_type.php">Edit Printer Types</a><br>
</Body>
</HTML>