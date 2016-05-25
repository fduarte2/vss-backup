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

if (isset($HTTP_POST_VARS["Cmd"])) {
  $Cmd = $HTTP_POST_VARS["Cmd"] ;
  if ($Cmd == "Save") {
    $query = "UPDATE PPE_PROFILE SET COAT_SIZE='" . $HTTP_POST_VARS["Coat_Size"] . "',SHOE_SIZE='" . $HTTP_POST_VARS["Shoe_Size"] . "'" ;
    $query .= ",HAT_SIZE='" . $HTTP_POST_VARS["Hat_Size"] . "',GLOVE_SIZE='" . $HTTP_POST_VARS["Glove_Size"] . "'" ;
    $query .= ",SHIRT_SIZE='" . $HTTP_POST_VARS["Shirt_Size"] . "',PANTS_SIZE='" . $HTTP_POST_VARS["Pants_Size"] . "'" ;
    $query .= " WHERE ID='" . $ID . "'" ;
    $out = ora_parse($cursor, $query);
    ora_exec($cursor);
  }
} else {
  $Cmd = "" ;
}

if (!isset($ID)) {
  echo "ID not set" ;
  exit ;
}

$query = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID='" . $ID . "'" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $LCSName = $row["EMPLOYEE_NAME"] ;
}

$query = "SELECT * FROM PPE_Profile WHERE ID='" . $ID . "'" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $CoatSize = $row["COAT_SIZE"] ; 
  $ShoeSize = $row["SHOE_SIZE"] ;
  $HatSize = $row["HAT_SIZE"] ;
  $GloveSize = $row["GLOVE_SIZE"] ;
  $ShirtSize = $row["SHIRT_SIZE"] ;
  $PantsSize = $row["PANTS_SIZE"] ;
}

$CoatItem = "" ;
$ShoeItem = "" ;
$HatItem = "" ;
$GloveItem = "" ;
$ShirtItem = "" ;
$PantsItem = "" ;
$query = "SELECT * FROM PPE_ISSUE S,PPE_INVENTORY N WHERE S.EQUIPMENT_ID=N.ID AND S.EMPLOYEE_ID='" . $ID . "' AND S.ACTIVE=1" ;
$query .= " ORDER BY DATE_ISSUED" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $Line = date("m/d/Y", strtotime($row["DATE_ISSUED"])) . " " . $row["DESCRIPTION"] ;
  if ($row["CATEGORY"] == "Coat") $CoatItem .= $Line . "<br>" ;
  if ($row["CATEGORY"] == "Shoe") $ShoeItem .= $Line . "<br>";
  if ($row["CATEGORY"] == "Hat") $HatItem .= $Line . "<br>";
  if ($row["CATEGORY"] == "Glove") $GloveItem .= $Line . "<br>";
  if ($row["CATEGORY"] == "Shirt") $ShirtItem .= $Line . "<br>";
  if ($row["CATEGORY"] == "Pants") $PantsItem .= $Line . "<br>";
}


?>

<script language="JavaScript">
  function ClickButton(Cmd, Type) {
    document.frmSize.Cmd.value = Cmd ;
    document.frmSize.Type.value = Type ;
    document.frmSize.submit() ;
  }
</script>

<font size="5" face="Verdana" color=#0066CC>Personal Protection Equipment</font><br>
<hr>
<font size="2" face="Verdana"><b>Profiles</b></font><br>
<br>

<? if ($Cmd == "Edit") { ?>

<form name="frmSize" method="POST" action="profile.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<input type="hidden" name="Cmd" value="Save">
<table width="100%" bgcolor="#f0f0f0">
 <tr><td colspan=3><b><? echo $LCSName . " (" . $ID . ")" ; ?></b></td></tr>
 <tr><td colspan=3>&nbsp;</td></tr>
 <tr><td><b>Item</b></td><td><b>Size</b></td></tr>
 <tr><td>Coat Size</td> <td><input type="text" name="Coat_Size"  size="4" value=<? echo "\"" . $CoatSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td>Shoe Size</td> <td><input type="text" name="Shoe_Size"  size="4" value=<? echo "\"" . $ShoeSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td>Hat Size</td>  <td><input type="text" name="Hat_Size"   size="4" value=<? echo "\"" . $HatSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td>Glove Size</td><td><input type="text" name="Glove_Size" size="4" value=<? echo "\"" . $GloveSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td>Shirt Size</td><td><input type="text" name="Shirt_Size" size="4" value=<? echo "\"" . $ShirtSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td>Pants Size</td><td><input type="text" name="Pants_Size" size="4" value=<? echo "\"" . $PantsSize . "\"" ; ?>></td><td>&nbsp;</td></tr>
 <tr><td align=center colspan=3><input type="submit" value="Save Profile"> <input type="button" value="Cancel" OnClick="Javascript: document.frmSize.Cmd.value='Cancel'; document.frmSize.submit() ;"></td></tr>
</table>
</form>

<? } else { ?>

<table width="100%" border=0>
 <tr><td colspan=3><b><? echo $LCSName . " (" . $ID . ")" ; ?></b></td></tr>
 <tr><td colspan=3>&nbsp;</td></tr>
 <tr><td><b>Item</b></td><td><b>Size</b></td>            <td><b>Current Issues</b></td></tr>
 <tr valign=top><td>Coat Size</td>  <td><? echo $CoatSize ; ?></td> <td><? echo $CoatItem ; ?></td></tr>
 <tr valign=top><td>Shoe Size</td>  <td><? echo $ShoeSize ; ?></td> <td><? echo $ShoeItem ; ?></td></tr>
 <tr valign=top><td>Hat Size</td>   <td><? echo $HatSize ; ?></td>  <td><? echo $HatItem ; ?></td></tr>
 <tr valign=top><td>Glove Size</td> <td><? echo $GloveSize ; ?></td><td><? echo $GloveItem ; ?></td></tr>
 <tr valign=top><td>Shirt Size</td> <td><? echo $ShirtSize ; ?></td><td><? echo $ShirtItem ; ?></td></tr>
 <tr valign=top><td>Pants Size</td> <td><? echo $PantsSize ; ?></td><td><? echo $PantsItem ; ?></td></tr>
 <tr><td align=center colspan=3>
   <form name="frmSize" method="POST" action="profile.php">
   <input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
   <input type="hidden" name="Cmd" value="Edit">
   <input type="submit" value="Edit Profile">
   </form></td></tr>
 </table>

<? } ?>

<table width="100%"><tr><td>

<form name="frmIssue" method="POST" action="issue.php">
<input type="hidden" name="ID" value=<? echo "\"" . $ID . "\"" ; ?>>
<a href="javascript: document.frmIssue.submit() ;">Issue Equipment</a>
</form>

</td><td align=right>

<form name="frmSwitch" method="POST" action="profile.php">
<font size="2">Switch Profile: </font><select name="ID">
<?
$query = "SELECT * FROM PPE_PROFILE P, EMPLOYEE E WHERE E.EMPLOYEE_ID=P.ID ORDER BY E.EMPLOYEE_NAME" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  echo "<option value=\"" . $row["ID"] . "\">" . $row["EMPLOYEE_NAME"] . "</option>" ;
} 
?>
</select> <input type="submit" value="Go">
</form>

</td></tr></table>

<?

ora_close($cursor) ;
ora_close($cursor2) ;

include("ppe_links.php") ;
include("pow_footer.php") ;
?>
