<?

// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Personal Protection Equipment - Issue Equipement";
$area_type = "HRMS";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
  printf("Access Denied from HRMS system");
  include("pow_footer.php");
  exit;
}

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

if (isset($HTTP_POST_VARS["ID"])) $ID = $HTTP_POST_VARS["ID"] ;

$EditID = -1 ;
if (isset($HTTP_POST_VARS["Cmd"])) {
  //echo "CMD: " . $Cmd . "<br>" ;
  $Cmd = $HTTP_POST_VARS["Cmd"] ;
  if ($Cmd == "New") {
    $query = "SELECT MAX(ID) FROM PPE_ISSUE" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
    if (ora_fetch_into($cursor, $row))
      $NewID = $row[0] + 1 ;
    else
      $NewID = 1 ;
    
    //0 - Requested; 1 - Issued; 2 - Inactive
    if (strlen($HTTP_POST_VARS["Issued"]) == 0)
      $Active = 0 ;
    else
      $Active = 1 ;
    $query = "INSERT INTO PPE_ISSUE (ID,EMPLOYEE_ID,EQUIPMENT_ID,DATE_REQUESTED,ACTIVE" ;
    if (strlen($HTTP_POST_VARS["Issued"]) > 0) $query .= ",DATE_ISSUED" ;
    $query .= ") VALUES (" . $NewID . ",'" . $HTTP_POST_VARS["ID"] . "','" . $HTTP_POST_VARS["EquipmentID"] . "'" ;
    $query .= ",'" . date("d-M-Y", strtotime($HTTP_POST_VARS["Requested"])) . "'," . $Active ;
    if (strlen($HTTP_POST_VARS["Issued"]) > 0) 
      $query .= ",'" . date("d-M-Y", strtotime($HTTP_POST_VARS["Issued"])) . "')" ;
    else
      $query .= ")" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
    if ($Active == 1) {
      //Deduct from Inventory
      $query = "SELECT * FROM PPE_INVENTORY WHERE ID='" . $HTTP_POST_VARS["EquipmentID"] . "'" ;
      $out = ora_parse($cursor, $query);
      ora_exec($cursor);
      if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$query = "UPDATE PPE_INVENTORY SET QUANTITY=" . ($row["QUANTITY"] - 1) . " WHERE ID='" . $row["ID"] . "'" ;
	$out = ora_parse($cursor2, $query);
	ora_exec($cursor2);
      }
    }
  }
  if ($Cmd == "Inactive") {
    $query = "UPDATE PPE_ISSUE SET ACTIVE=2 WHERE ID=" . $HTTP_POST_VARS["IssueID"]  ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
  }
  if ($Cmd == "IssueEdit") {
    $EditID = $HTTP_POST_VARS["IssueID"] ;
  }
  if ($Cmd == "IssueSave") {
    //Lookup Old Record
    $query = "SELECT * FROM PPE_ISSUE WHERE ID=" . $HTTP_POST_VARS["IssueID"] ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
    if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      //Add to archive
      $query = "INSERT INTO PPE_ARCHIVE (ID, EMPLOYEE_ID, EQUIPMENT_ID, DATE_REQUESTED, DATE_ISSUED, ACTIVE, DATE_CREATED)" ;
      $query .= " VALUES (" . $row["ID"] . ",'" . $row["EMPLOYEE_ID"] . "','" . $row["EQUIPMENT_ID"] . "'," ;
      $query .= "TO_DATE('" . date("m/d/Y", strtotime($row["DATE_REQUESTED"])) . "','MM/DD/YYYY'),TO_DATE('" . date("m/d/Y", strtotime($row["DATE_ISSUED"])) . "','MM/DD/YYYY')" ;
      $query .= "," . $row["ACTIVE"] . ",TO_DATE('" . date("m/d/Y", strtotime($row["DATE_CREATED"])) . "','MM/DD/YYYY'))" ;
      $out = ora_parse($cursor2, $query);
      ora_exec($cursor2);
      //Update Record
      $query = "UPDATE PPE_ISSUE SET EQUIPMENT_ID='" . $HTTP_POST_VARS["modEquipmentID"] . "'" ;
      $query .= ",DATE_REQUESTED=TO_DATE('" . date("m/d/Y",strtotime($HTTP_POST_VARS["modRequested"])) . "','MM/DD/YYYY')" ;
      $query .= ",DATE_ISSUED=TO_DATE('" . date("m/d/Y",strtotime($HTTP_POST_VARS["modIssued"])) . "','MM/DD/YYYY')" ;
      $query .= " WHERE ID=" . $HTTP_POST_VARS["IssueID"] ;
      $out = ora_parse($cursor, $query);
      ora_exec($cursor);
    }
  }
  if ($Cmd == "Issue") {
    $query = "SELECT * FROM PPE_INVENTORY WHERE ID='" . $HTTP_POST_VARS["EquipmentID"] . "'" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
    if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      if ($row["QUANTITY"] > 0) {
	$query = "UPDATE PPE_INVENTORY SET QUANTITY=" . ($row["QUANTITY"]-1) . " WHERE ID='" . $row["ID"] . "'" ;
	$out = ora_parse($cursor2, $query);
	ora_exec($cursor2);

	$query = "UPDATE PPE_ISSUE SET ACTIVE=1,DATE_ISSUED=TO_DATE('" . date("m/d/Y") . "','mm/dd/yyyy') WHERE ID=" . $HTTP_POST_VARS["IssueID"]  ;
	$out = ora_parse($cursor2, $query);
	ora_exec($cursor2);
      }
    }
  }
}

if (!isset($ID)) {
  echo "ID not set" ;
  exit ;
}

$query = "SELECT * FROM PPE_PROFILE WHERE ID='" . $ID . "'" ;
$out = ora_parse($cursor, $query) ;
ora_exec($cursor) ;
if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $CoatSize = $row["COAT_SIZE"] ;
  $ShoeSize = $row["SHOE_SIZE"] ;
  $HatSize = $row["HAT_SIZE"] ;
  $GloveSize = $row["GLOVE_SIZE"] ;
  $ShirtSize = $row["SHIRT_SIZE"] ;
  $PantsSize = $row["PANTS_SIZE"] ;
}

$query = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID='" . $ID . "'" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $LCSName = $row["EMPLOYEE_NAME"] ;
}

?>

<font size="5" face="Verdana" color=#0066CC>Personal Protection Equipment</font><br>
<hr>
<font size="3" face="Verdana"><b>Issue Equipment for <? echo $LCSName . " (" . $ID . ")" ?></b></font><br>
<br>

<script language="Javascript">

  function Issued(Cmd,IssueID) {
    document.frmIssued.Cmd.value= Cmd ;
    document.frmIssued.IssueID.value = IssueID ;
    document.frmIssued.submit() ;
  }

</script>

<form name="frmIssued" method="POST" action="issue.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<input type="hidden" name="IssueID" value="">
<input type="hidden" name="Cmd" value="">
<table border=0>
 <tr><td colspan=2><font size="2"><b>Active Equipment</b></td></tr>
<?

$query = "SELECT S.ID AS ID, S.DATE_ISSUED, N.ID AS E_ID, N.DESCRIPTION FROM PPE_ISSUE S,PPE_INVENTORY N" ;
$query .= " WHERE S.EQUIPMENT_ID=N.ID AND S.EMPLOYEE_ID='" . $ID . "' AND S.ACTIVE=1 ORDER BY S.DATE_ISSUED DESC" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
 <tr valign=top>
  <td><font size="2"><? echo date("m/d/Y", strtotime($row["S.DATE_ISSUED"])) ; ?></font></td>
  <td><font size="2"><? echo $row["E_ID"] . "-" . $row["DESCRIPTION"] ; ?></font></td>
  <td><input type="button" value="Deactivate" OnClick=<? echo "\"javascript: Issued('Inactive', '" . $row["ID"] . "') ;\"" ; ?>></td>
  <td><input type="button" value="Edit" OnClick=<? echo "\"javascript: Issued('IssueEdit', '" . $row["ID"] . "') ;\"" ; ?>></td>
 </tr>
<?
}

?>
</table>
</form>

<? if ($EditID > -1) { ?>
<form name="frmEditIssue" method="POST" action="issue.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<input type="hidden" name="IssueID" value=<? echo "\"" . $IssueID . "\"" ?> >
<input type="hidden" name="Cmd" value="IssueSave">
<table bgcolor="#f0f0f0" width="100%" border=0>
 <tr><td colspan=2><b>Edit Issue <? echo $EditName ;?></b></td></tr>
<?
 $query = "SELECT * FROM PPE_ISSUE WHERE ID=" . $EditID ;
 $out = ora_parse($cursor, $query);
 ora_exec($cursor);
 if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
   echo "<tr><td align=right>Equipment</td><td><input type=\"text\" name=\"modEquipmentID\" value=\"" . $row["EQUIPMENT_ID"] . "\"></td></tr>" ;
   echo "<tr><td align=right>Requested</td><td><input type=\"text\" name=\"modRequested\" value=\"" . date("m/d/Y", strtotime($row["DATE_REQUESTED"])) . "\"></td></tr>" ;
   echo "<tr><td align=right>Issued</td><td><input type=\"text\" name=\"modIssued\" value=\"" . date("m/d/Y", strtotime($row["DATE_ISSUED"])) . "\"></td></tr>" ;
   echo "<tr><td align=center colspan=2><input type=\"submit\" value=\"Save\">" ;
   echo " <input type=\"button\" value=\"Cancel\" OnClick=\"javascript: document.frmEditIssue.Cmd.value='' ; document.frmEditIssue.submit() ; \"></td></tr>" ;
 }
?>
</table>
</form>
<?}?>
<hr>

<script language="JavaScript">
function Issue(IssueID,EquipmentID) {
  document.frmIssue.IssueID.value = IssueID ;
  document.frmIssue.EquipmentID.value = EquipmentID ;
  document.frmIssue.submit() ;
}
</script>

<form name="frmIssue" method="POST" action="issue.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<input type="hidden" name="IssueID" value="" >
<input type="hidden" name="Cmd" value="Issue">
<input type="hidden" name="EquipmentID" value="">
<table>
 <tr><td colspan=2><font size="2"><b>Requested Equipment</b></td></tr>
<?

$query = "SELECT S.ID AS ID, S.DATE_ISSUED, N.ID AS E_ID, N.DESCRIPTION, N.QUANTITY FROM PPE_ISSUE S,PPE_INVENTORY N WHERE S.EQUIPMENT_ID=N.ID" ;
$query .= " AND S.EMPLOYEE_ID='" . $ID . "' AND S.ACTIVE=0 ORDER BY S.DATE_ISSUED DESC" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
 <tr>
  <td><font size="2"><? echo date("m/d/Y", strtotime($row["DATE_ISSUED"])) ; ?></font></td>
  <td><font size="2"><? echo $row["E_ID"] . "-" . $row["DESCRIPTION"] ; ?></font></td>
  <td>
<? if ($row["QUANTITY"] > 0)
  echo "<input type=\"button\" value=\"Issue\" OnClick=\"javascript: Issue(" . $row["ID"] . ",'" . $row["E_ID"] . "') ;\" >" ;
?>
  </td>
 </tr>
<?
}

?>
</table>
</form>
<hr>

<!-- Begin Organizational Table -->
<table width="100%" bgcolor="#f0f0f0"><tr valign=top><td>

<form name="frmRequest" method="POST" action="issue.php">
<input type="hidden" name="Cmd" value="New">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<table width="100%" bgcolor="#f0f0f0">
 <tr><td colspan=2><b>Issue Equipment</b></td></tr>
 <tr><td>&nbsp;</td></tr>
 <tr><td align=right>Equipment</td><td><select name="EquipmentID">
<?
  $query = "SELECT * FROM PPE_INVENTORY WHERE ACTIVE=1 AND QUANTITY > 0 ORDER BY ID,DESCRIPTION" ;
  $out = ora_parse($cursor, $query);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    echo "  <option value=\"" . $row["ID"] . "\">" . $row["ID"] . "-" . $row["DESCRIPTION"] . "</option>\n" ;
  }
?>
 <tr><td align=right>Requested</td><td><input type="text" name="Requested" value=<? echo "\"" . date("m/d/Y") . "\"" ; ?>>
 <tr><td align=right>Issued</td><td><input type="text" name="Issued" value=""></td></tr>
 <tr><td colspan=2 align=center><input type="submit" value="Issue"> <input type="reset"></td></tr>
</table>
</form>

<!-- Right Pane -->
</td><td>

<table bgcolor="#c0c0ff" align=right width="100%">
 <tr><td colspan=2 align=center><font size="1"><b>Employee&apos; Sizes</font></td></tr>
 <tr><td colspan=2 bgcolor="#000000"></td></tr>
 <tr><td><font size="1">Coat</td> <td><font size="1"><? echo $CoatSize ; ?></td></tr>
 <tr><td><font size="1">Shoe</td> <td><font size="1"><? echo $ShoeSize ; ?></td></tr>
 <tr><td><font size="1">Hat</td>  <td><font size="1"><? echo $HatSize ; ?></td></tr>
 <tr><td><font size="1">Glove</td><td><font size="1"><? echo $GloveSize ; ?></td></tr>
 <tr><td><font size="1">Shirt</td><td><font size="1"><? echo $ShirtSize ; ?></td></tr>
 <tr><td><font size="1">Pants</td><td><font size="1"><? echo $PantsSize ; ?></td></tr>
</table>

<!-- End Organizational Table -->
</td></tr></table>

<table width="100%"><tr><td>

<form name="frmReturn" method="POST" action="profile.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<a href="javascript: document.frmReturn.submit() ;">Return to Profile</a>
</form>

</td><td align=right>

<form name="frmSwitch" method="POST" action="profile.php">
<font size="2">Switch Profile: </font>
<select name="ID" onchange="document.frmSwitch.submit(this.form)">
<?
$query = "SELECT * FROM PPE_Profile, EMPLOYEE WHERE EMPLOYEE_ID=ID ORDER BY EMPLOYEE_NAME" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  echo "<option value=\"" . $row["ID"] . "\">" . $row["EMPLOYEE_NAME"] . "</option>" ;
} 
?>
</select>
</form>

</td></tr></table>

<?

ora_close($cursor) ;
ora_close($cursor2) ;

include("ppe_links.php") ;
include("pow_footer.php") ;
?>
