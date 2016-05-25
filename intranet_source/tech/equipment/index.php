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
      $sql = "INSERT INTO EQUIP (DESCRIPTION,ASSIGNED_TO,PURCHASED)" ;
      $sql .= " VALUES ('" . $txtDescription . "'" ;
      $sql .= ",'" . $txtAssignTo . "','" . date("d-M-Y",strtotime($txtPurchased)) . "')" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd=='Edit') {
      $sql = "UPDATE EQUIP SET ASSIGNED_TO='" . $txtAssignTo . "'" ;
      if (strlen($txtPurchased) > 0) {
        $sql .= ", PURCHASED='" . date("d-M-Y",strtotime($txtPurchased)) . "'" ;
      }
      $sql .= ", SERIALNUM='" . $txtSerialNum . "'" ;
      $sql .= ", STATUS='" . $optStatus . "'" ;
      $sql .= ", NOTES='" . $txtNotes . "'" ;
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
<br>

<center>
<table width=100%>
 <tr>
  <td><a href="EquipType.php">Equipment Types</a></td>
  <td>Go here to create new types of equipment or edit existing equipment.</td>
 </tr>
 <tr>
  <td><a href="Equipment.php">Equipment</a></td>
  <td>Go here to create or modify assigned equipment.</td>
 </tr>
 <tr>
  <td><a href="Install.php">Installation</a></td>
  <td>Go here to setup a new computer.</td>
 </tr>
</table>

<br>
<? include("pow_footer.php"); ?>

