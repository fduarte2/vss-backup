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

  $EditID = -1 ;
  if (isset($Cmd)) {
    if ($Cmd == "Add") {
      $sql = "SELECT EQUIP_INSTALL_TASK_SEQ.nextval FROM DUAL" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
      if (ora_fetch($cursor)) {
        $NewID = ora_getcolumn($cursor, 0) ;
      }
      $sql = "INSERT INTO EQUIP_INSTALL_TASK (ID,STEP_ID,SUBSTEP_ID,LABEL,DESCRIPTION)" ;
      $sql .= " VALUES (" . $NewID . "," . $txtStep . ",'" . $txtSubStep . "'" ;
      $sql .= ",'" . $txtLabel . "','" . stripslashes($txtDescription) . "')" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Modify") {
      $sql = "UPDATE EQUIP_INSTALL_TASK SET STEP_ID=" . $txtStepID ;
      $sql .= ",SUBSTEP_ID='" . $txtSubStepID . "',LABEL='" . $txtLabel . "'" ;
      $sql .= ",DESCRIPTION='" . stripslashes($txtDescription) . "'" ;
      $sql .= " WHERE ID=" . $ID ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
    if ($Cmd == "Edit") {
      $EditID = $ID ;
    }
  }
?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Installation</font><br>
<br>

<center>
<form name="frmEdit" action="InstallAdmin.php">
<input type="hidden" name="Cmd" value="Modify">
<input type="hidden" name="ID" value="<? echo $EditID ?>">

<table bgcolor=#99CCFF width=100%>
 <tr>
  <td colspan=2><b>Step</b></td>
  <td width=30%><b>Label</b></td>
  <td width=*><b>Description</b></td>
  <td></td>
 </tr>
 <tr><td colspan=54 bgcolor=#000000></td></tr>
<?
  $sql = "SELECT * FROM EQUIP_INSTALL_TASK ORDER BY STEP_ID,SUBSTEP_ID" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $Step = -1 ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($row["ID"] == $EditID) {
      echo " <tr valign=top>" ;
      echo "  <td><input type=\"text\" name=\"txtStepID\" value=\"" . $row["STEP_ID"] . "\" size=2></td>" ;
      echo "  <td><input type=\"text\" name=\"txtSubStepID\" value=\"" . $row["SUBSTEP_ID"] . "\" size=2></td>" ;
      echo "  <td><input type=\"text\" name=\"txtLabel\" value=\"" . $row["LABEL"] . "\" size=20></td>" ;
      echo "  <td><textarea name=\"txtDescription\" cols=60 rows=5>" . $row["DESCRIPTION"] . "</textarea></td>" ;
      echo "  <td><a href=\"javascript: document.frmEdit.submit() ;\"><img src=\"../images/Save.gif\" border=0></a>" ;
      echo " </tr>" ;
    } else {
      echo " <tr valign=top>" ;
      if ($row["STEP_ID"] == $Step) {
	echo "  <td></td>" ;
      } else {
	echo "  <td align=right>" . $row["STEP_ID"] . "</td>" ;
	$Step = $row["STEP_ID"] ;
      }
      echo "  <td>" . $row["SUBSTEP_ID"] . ".</td>" ;
      echo "  <td>" . $row["LABEL"] . "</td>" ;
      echo "  <td>" . $row["DESCRIPTION"] . "</td>" ;
      echo "  <td><a href=\"InstallAdmin.php?Cmd=Edit&ID=" . $row["ID"] . "\"><img src=\"../images/Edit.gif\" border=0></a>" ;
      echo " </tr>" ;
    }
  }
?>
</table>
</form>
<br>
<form name="frmAdd" action="InstallAdmin.php">
<input type="hidden" name="Cmd" value="Add">
<table bgcolor=#66AAEE>
 <tr><td align=center colspan=4><b>Add Step</b></td></tr>
 <tr><td colspan=4 bgcolor=#000000></td></tr>
 <tr>
  <td align=right>Step</td><td><input type="text" name="txtStep" size=2>
      <input type="text" name="txtSubStep" size=2></td>
  <td align=right>Label</td><td><input type="text" name="txtLabel" size=30></td>
 </tr>
 <tr valign=top><td align=right>Description</td>
     <td align=center colspan=3><textarea name="txtDescription" cols=40 rows=3></textarea></td></tr>
  <tr><td colspan=4 align=center><input type="submit" value="Add"> <input type="reset"></td></tr>
</table>
</form>

<a href="Install.php">Install Menu</a>

<br>
<? include("pow_footer.php"); ?>

