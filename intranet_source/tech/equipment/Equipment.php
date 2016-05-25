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
      $sql = "SELECT EQUIP_SEQ.nextval FROM DUAL" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch($cursor)) {
        $NewID = ora_getcolumn($cursor, 0) ;
      }
      $sql = "INSERT INTO EQUIP (ID,EQUIP_TYPE_ID,SERIALNUM,ASSIGNED_TO,PURCHASED,NOTES,STATUS)" ;
      $sql .= " VALUES (" . $NewID . "," . $optType . ",'" . $txtSerialNum . "'" ;
      $sql .= ",'" . $txtAssignTo . "'," ;
      if (strlen($txtPurchased) > 0) {
	$sql .= "'" . date("d-M-Y",strtotime($txtPurchased)) . "'," ;
      } else {
	$sql .= "''," ;
      }
      $sql .= "'" . $txtNotes . "','" . $optStatus . "')" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd=='Edit') {
      $sql = "UPDATE EQUIP SET ASSIGNED_TO='" . $txtAssignTo . "'" ;
      $sql .= ",EQUIP_TYPE_ID=" . $optType ;
      if (strlen($txtPurchased) > 0) {
        $sql .= ", PURCHASED='" . date("d-M-Y",strtotime($txtPurchased)) . "'" ;
      } else {
        $sql .= ", PURCHASED=''" ;
      }
      $sql .= ", SERIALNUM='" . $txtSerialNum . "'" ;
      $sql .= ", STATUS='" . $optStatus . "'" ;
      $sql .= ", NOTES='" . $txtNotes . "'" ;
      $sql .= " WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd=='Delete') {
      $sql = "DELTE FROM EQUIP WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
  }

?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Equipment</font><br>
<br>

<table width=100%>
 <tr>
  <td><a href="AddEquipment.php"><img src="../images/Add.gif" border=0></a></td>
  <td align=right><form>
      <select name="Category">
<?php

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
      <input type="submit" value="Filter">
      </form></td>
 </tr>
</table>
<br>

<center>

<table bgcolor=#99CCFF width=100%>
 <tr>
  <td><b>Category</b></td>
  <td><b>Description</b></td>
  <td><b>Assigned To</b></td>
  <td><b>Date Purchased</b></td>
  <td><b>Status</b></td>
 </tr>
 <tr><td colspan=6 bgcolor=#000000></td></tr>
<?php
   $sql = "SELECT E.*,ET.CATEGORY,ET.DESCRIPTION FROM EQUIP E, EQUIP_TYPE ET WHERE ET.ID=E.EQUIP_TYPE_ID" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    echo "<tr>" ;
    echo "<td>" . $row["CATEGORY"] . "</td>" ;
    echo "<td>" . $row["DESCRIPTION"] . "</td>" ;
    echo "<td>" . $row["ASSIGNED_TO"] . "</td>" ;
    echo "<td>" . $row["DATE"] . "</td>" ;
    echo "<td>" . $row["STATUS"] . "</td>" ;
    echo "<td align=right><a href=\"EditEquipment.php?ID=" . $row["ID"] . "\"><img src=\"../images/Edit.gif\" border=0></a>" ;
    echo "<a href=\"index.php?Cmd=Delete&ID=" . $row["ID"] . "\"><img src=\"../images/Delete.gif\" border=0></a></td>" ;
    echo "</tr>" ;
  }
?>
</table>
<br>

<a href="index.php">Equipment Tracking</a><br>

<br>
<? include("pow_footer.php"); ?>

