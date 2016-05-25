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

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);

  if (isset($Cmd)) {
    if ($Cmd=='Add') {
      $sql = "SELECT EQUIP_TYPE_SEQ.nextval FROM DUAL" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch($cursor)) {
        $NewID = ora_getcolumn($cursor, 0) ;
      }
      $sql = "INSERT INTO EQUIP_TYPE (ID,CATEGORY,BRAND,MODEL,DESCRIPTION)" ;
      $sql .= " VALUES ('" . $NewID . "','" . $txtCategory . "','" . $txtBrand . "'" ;
      $sql .= ",'" . $txtModel . "','" . $txtDescription . "')" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd=='Edit') {
      $sql = "UPDATE EQUIP_TYPE SET CATEGORY='" . $txtCategory . "'" ;
      $sql .= ", DESCRIPTION='" . $txtDescription . "'" ;
      $sql .= ", BRAND='" . $txtBrand . "'" ;
      $sql .= ", MODEL='" . $txtModel . "'" ;
      $sql .= " WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd=='Delete') {
      $sql = "DELETE FROM EQUIP_TYPE WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
  }

?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Equipment Types</font><br>
<br>

<a href="AddEquipType.php"><img src="../images/Add.gif" border=0></a><br>
<br>

<center>

<table bgcolor=#99CCFF width=100%>
<?php
   $sql = "SELECT * FROM EQUIP_TYPE ORDER BY CATEGORY, BRAND, MODEL, DESCRIPTION" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $Cat = "" ;
  $Count = 0 ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($Cat != $row["CATEGORY"]) {
      if ($Count > 0) {
        echo "</table>\n" ;
	echo "<br>\n" ;
        echo "<table bgcolor=99CCFF width=100%>\n" ;
      }
      echo "<tr><td colspan=5><b>" .$row["CATEGORY"] . "</b></td></tr>\n" ;
      echo " <tr>\n" ;
      echo "  <td><i>Brand</i></td>\n" ;
      echo "  <td><i>Model</i></td>\n" ;
      echo "  <td><i>Description</i></td>\n" ;
      echo " </tr>\n" ;
      echo " <tr><td colspan=5 bgcolor=#000000></td></tr>\n" ;
      $Cat = $row["CATEGORY"] ;
    } else {
      if (strlen($row["CATEGORY"]) == 0) {
      echo "<tr><td colspan=5><b>UnAssigned</b></td></tr>\n" ;
      echo " <tr>\n" ;
      echo "  <td><i>Brand</i></td>\n" ;
      echo "  <td><i>Model</i></td>\n" ;
      echo "  <td><i>Description</i></td>\n" ;
      echo " </tr>\n" ;
      echo " <tr><td colspan=5 bgcolor=#000000></td></tr>\n" ;
      }
    }
    echo " <tr>\n" ;
    echo "  <td>" . $row["BRAND"] . "</td>\n" ;
    echo "  <td>" . $row["MODEL"] . "</td>\n" ;
    echo "  <td>" . $row["DESCRIPTION"] . "</td>\n" ;
    echo "  <td align=right><a href=\"EditEquipType.php?ID=" . $row["ID"] . "\"><img src=\"../images/Edit.gif\" border=0></a>\n" ;
    echo "    <a href=\"EquipType.php?Cmd=Delete&ID=" . $row["ID"] . "\"><img src=\"../images/Delete.gif\" border=0></a></td>\n" ;
    echo " </tr>\n" ;
    $Count++ ;
  }
?>
</table>
<br>

<a href="index.php">Equipment Tracking</a><br>

<br>
<? include("pow_footer.php"); ?>

