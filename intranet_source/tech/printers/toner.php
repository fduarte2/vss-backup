<HTML>
<Head>
<title>Printer Tracking - Toner</title>
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
    $query = "INSERT INTO Toner_Type (Part_Num, Stocked, Ordered)" ;
    $query .= " VALUES ('" . $Part . "'," . $Stock . "," . $Order . ")" ;
    $rs = mysql_query($query) or die("Query contains error") ;
  }
  if ($Cmd == "Del") {
    $query = "DELETE FROM Toner_Type WHERE ID=" . $ID ;
    $rs = mysql_query($query) or die("Query contains error") ;
  }
  if ($Cmd == "Mod") {
    $query = "UPDATE Toner_Type" ;
    $query .= " SET Part_Num='" . $MPart . "', Stocked=" . $MStock . ", Ordered=" . $MOrder ;
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
<h2>Toner</h2>

<form name="frmModify">
<input type="hidden" name="Cmd" value="Mod">
<table bgcolor="#000000">
 <tr bgcolor="#AFAFFF">
  <td><b>Part Number</td>
  <td><b>In Stock</td>
  <td><b>Ordered</td>
  <td><b>Used In</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>

<?php

$rs = mysql_query("SELECT * FROM Toner_Type") or die ("No Information Available") ;

while ($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
  echo " <tr bgcolor=\"#CFCFCF\">\n" ;
  if ($EditID == $line["ID"]) {
    echo "  <td><input type=\"text\" name=\"MPart\" value=\"" . $line["Part_Num"] . "\" size=10></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MStock\" value=\"" . $line["Stocked"] . "\" size=3></td>\n" ;
    echo "  <td><input type=\"text\" name=\"MOrder\" value=\"" . $line["Ordered"] . "\" size=3></td>\n" ;
  } else {
    echo "  <td>" . $line["Part_Num"] . "</td>\n" ;
    echo "  <td>" . $line["Stocked"] . "</td>\n" ;
    echo "  <td>" . $line["Ordered"] . "</td>\n" ;
  }
  $rsPrint = mysql_query("SELECT * FROM Printer_Type WHERE Toner_ID=" . $line["ID"]) or die ("None for " . $line["ID"]) ;
  $iCount = 0 ;
  $Display = "" ;
  while ($Printer = mysql_fetch_array($rsPrint, MYSQL_ASSOC)) {
    if ($iCount > 0) $Printer .= ", " ;
    $Display .= $Printer["Brand"] . " " . $Printer["Model"] ;
    $iCount++ ;
  }
  echo "  <td>" . $Display . "</td>" ;
  if ($EditID == $line["ID"]) {
    echo "  <td><input type=\"submit\" value=\"Save\"><input type=\"hidden\" name=\"MID\" value=\"" . $line["ID"] . "\"></td>" ;
  } else {
    echo "  <td><a href=\"toner.php?Cmd=Edit&ID=" . $line["ID"] . "\">Edit</a></td>" ;
  }
  echo "  <td><a href=\"toner.php?Cmd=Del&ID=" . $line["ID"] . "\">Delete</a></td>" ;
  echo " </tr>\n" ;
}

mysql_free_result ($rs) ;
mysql_close ($conn) ;

?>
</table>
</form>

<center>
<form name="frmToner">
<input type="hidden" name="Cmd" value="Add">
<table>
 <tr>
  <td><b>Part Num</td>
  <td><b>In Stock</td>
  <td><b>Ordered</td>
 </tr>
 <tr>
  <td><input type="Text" name="Part"></td>
  <td><input type="Text" name="Stock"></td>
  <td><input type="Text" name="Order"></td>
 </tr>
</table>
<input type="submit" name="txtSubmit" value="Add">
</form>

</Body>
</HTML>