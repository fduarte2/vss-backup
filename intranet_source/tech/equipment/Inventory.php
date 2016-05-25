<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Scanner Inventory";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);

  if (isset($Cmd)) {
    if ($Cmd == "Add") {
      $sql = "SELECT ID FROM SCAN_SUPER WHERE NAME='" . $optAssignTo . "'" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $Super_ID = $row["ID"] ;
      }
      $sql = "INSERT INTO SCAN_UNIT (ID,PROGRAM,ASSIGN_TO,LOCATION,DAMAGED) VALUES ('" . $txtUnit . "','" . $optProgram . "','" . $Super_ID . "','" . $optLocation . "',0)" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Edit") {
      $sql = "SELECT ID FROM SCAN_SUPER WHERE NAME='" . $optAssignTo . "'" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $Super_ID = $row["ID"] ;
      }
      $sql = "UPDATE SCAN_UNIT SET PROGRA='" . $optProgram . "',ASSIGN_TO='" . $Super_ID . "',LOCATION='" . $optLocation . "' WHERE ID='" . $txtUnit . "'" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Delete") {
      $sql = "DELETE FROM SCAN_UNIT WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Damaged") {
      $sql = "SELECT SCAN_DMG_SEQ.nextval FROM DUAL" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch($cursor)) {
        $NewID = ora_getcolumn($cursor, 0) ;
      }
      $sql = "INSERT INTO SCAN_DAMAGE (ID,UNIT_ID,PROBLEM,DAMAGED_DATE) VALUES ($NewID,'" . $txtUnit . "','" . $txtProblem . "','" . date("d-M-Y", strtotime($txtDate)) . "')" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);

      $sql = "UPDATE SCAN_UNIT SET DAMAGED=1 WHERE ID='" . $txtUnit . "'" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Shipped") {
      $sql = "UPDATE SCAN_DAMAGE SET SHIPPED_DATE='" . date("d-M-Y") . "' WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      $sql = "UPDATE SCAN_UNIT SET DAMAGED=2 WHERE ID=" . $UnitID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Returned") {
      $sql = "UPDATE SCAN_DAMAGE SET RETURN_DATE='" . date("d-M-Y") . "' WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      $sql = "UPDATE SCAN_UNIT SET DAMAGED=0 WHERE ID=" . $UnitID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
  }
?>

<font size="5" face="Verdana" color="#0066CC">Scanner Inventory</font><br>
<hr>
<br>

<center>
<!-- Start Pagination Table -->
<table width=100%>
<tr><td align=center>

<form name="frmSort" action="index.php">
<table width=100%>
 <tr>
  <td>Filter By
      <select name="optFilter">
      <option selected>Unit</option>
      <option>Name</option>
      <option>Location</option>
      </select>
      <input type="text" name="txtFilter">
      <input type="submit" value="Filter"></td>
  <td>&nbsp;&nbsp;</td>
  <td align=right><a href="AddScanner.php"><img src="/tech/images/Add.gif" border=0></a>
      <a href="index.php">Back to Scanner Main</a>
      </td>
 </tr>
</table>
</form>

</td></tr>
<tr><td align=center>

<table border=0 bgcolor=#99CCFF width=100%>
 <tr>
  <td><b>Unit</b></td>
  <td><b>Type</b></td>
  <td><b>Supervisor</b></td>
  <td><b>Location</b></td>
  <td><b>Status</b></td>
  <td><b>&nbsp;</b></td>
 </tr>
 <tr><td colspan=7 bgcolor=#000000></td></tr>
<?php
   //$sql = "SELECT SCAN_UNIT.ID, SCAN_UNIT.PROGRAM, SCAN_SUPER.NAME, SCAN_UNIT.LOCATION, SCAN_UNIT.DAMAGED FROM SCAN_UNIT, SCAN_SUPER WHERE SCAN_UNIT.ASSIGN_TO=SCAN_SUPER.ID";
   $sql = "SELECT u.ID, u.PROGRAM, s.NAME, u.LOCATION, u.DAMAGED, d.ID AS DMG_ID FROM SCAN_UNIT u, SCAN_SUPER s, SCAN_DAMAGE d WHERE u.ASSIGN_TO=s.ID AND u.ID=d.UNIT_ID(+)";
   if (isset($optFilter)) {
     if (strlen($txtFilter) > 0) {
       if ($optFilter == "Unit") $sql .= " AND UNIT LIKE '%" . $txtFilter . "%'" ;
       if ($optFilter == "Name") $sql .= " AND NAME LIKE '%" . $txtFilter . "%'" ;
       if ($optFilter == "Program") $sql .= " AND PROGRAM LIKE '%" . $txtFilter . "%'" ;
       if ($optFilter == "Location") $sql .= " AND LOCATION LIKE '%" . $txtFilter . "%'" ;
     }
   }
   if (isset($optSort)) {
     if ($optSort == "Unit") $sql .= " ORDER BY ID" ;
     if ($optSort == "Name") $sql .= " ORDER BY Name" ;
     if ($optSort == "Program") $sql .= " ORDER BY Program" ;
     if ($optSort == "Location") $sql .= " ORDER BY Location" ;
   } else {
     $sql .= " ORDER BY  ID" ;
   }
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   $Total = 0 ;
   $Damaged = 0 ;
   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     if ($row["DAMAGED"] == 0) {
       $DamageMod = "" ;
     } else {
       $Damaged++ ;
       if ($row["DAMAGED"] == 1) {
         $DamageMod = " <font color=#FF0000>" ;
       } else {
         $DamageMod = " <font color=#FFFF00>" ;
       }
     }
     echo " <tr>" ;
     echo "  <td>" . $DamageMod . $row["ID"] . "</td>\n" ;
     echo "  <td>" . $DamageMod . $row["PROGRAM"] . "</td>\n" ;
     echo "  <td>" . $DamageMod . $row["NAME"] . "</td>\n" ;
     echo "  <td>" . $DamageMod . $row["LOCATION"] . "</td>\n" ;
     if ($row["DAMAGED"] == 0) {
       echo "  <td>Available</td>\n" ;
     } else {
       if ($row["DAMAGED"] == 1) {
         echo "  <td>" . $DamageMod . "Damaged</td>\n" ;
       } else {
         echo "  <td>" . $DamageMod . "Shipped</td>\n" ;
       }
     }
     echo "  <td align=right><a href=\"EditScanner.php?ID=" . $row["ID"] . "\"><img src=\"/tech/images/Edit.gif\" border=0></a>\n" ;
     echo "      <a href=\"index.php?Cmd=Delete&ID=" . $row["ID"] . "\"><img src=\"/tech/images/Delete.gif\" border=0></a></td>\n" ;
     if ($row["DAMAGED"] == 0) {
       echo "  <td><a href=\"DamageScanner.php?ID=" . $row["ID"] . "\"><img src=\"/tech/images/Damaged.gif\" border=0></a></td>\n" ;
     } else {
       if ($row["DAMAGED"] == 1) {
         echo "  <td><a href=\"Inventory.php?Cmd=Shipped&ID=" . $row["DMG_ID"] . "&UnitID=" . $row["ID"] . "\"><img src=\"/tech/images/Shipped.gif\" border=0></a></td>\n" ;
       } else {
         echo "  <td><a href=\"Inventory.php?Cmd=Returned&ID=" . $row["DMG_ID"] . "&UnitID=" . $row["ID"] . "\"><img src=\"/tech/images/Returned.gif\" border=0></a></td>\n" ;
       }
     }
     echo " <tr>" ;
     $Total++ ;
   }
   echo "<tr><td colspan=7 bgcolor=#000000></td></tr>" ;
   echo "<tr><td colspan=7 align=right><b>Total Scanners: " . $Total . " (" . $Damaged . " Damaged)</b></td></tr>" ;
?>
</table>

<!-- End Pagination Table -->
</td></tr>
</table>

<br>
<? include("pow_footer.php"); ?>

