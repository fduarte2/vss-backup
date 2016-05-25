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

if (isset($HTTP_POST_VARS["Cmd"])) {
  $Cmd = $HTTP_POST_VARS["Cmd"] ;
  if ($Cmd == "NewProfile") {
    $query = "INSERT INTO PPE_Profile (ID,SHIRT_SIZE,PANTS_SIZE,COAT_SIZE,GLOVE_SIZE,HAT_SIZE,SHOE_SIZE) VALUES ('" . $HTTP_POST_VARS["ID"] . "','M','M','M','M','M','M')" ;
    $out = ora_parse($cursor2, $query);
    if (!ora_exec($cursor2)) echo "Cannot execute: " . $query . "<br>" ;
  }
}

$Profiles = "" ;
$LCSUsers = "" ;

$query = "SELECT * FROM EMPLOYEE ORDER BY EMPLOYEE_NAME" ;
$out = ora_parse($cursor, $query);
ora_exec($cursor);
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $query = "SELECT * FROM PPE_Profile WHERE ID='" . $row["EMPLOYEE_ID"] . "'" ;
  $out = ora_parse($cursor2, $query);
  ora_exec($cursor2);
  if (ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $Profiles .= "<option value=\"" . $row["EMPLOYEE_ID"] . "\">" . $row["EMPLOYEE_NAME"] . "</option>\n" ;
  } else {
    $LCSUsers .= "<option value=\"" . $row["EMPLOYEE_ID"] . "\">" . $row["EMPLOYEE_NAME"] . "</option>\n" ;
  }
}

?>

<font size="5" face="Verdana" color=#0066CC>Personal Protection Equipment</font><br>
<hr>
<font size="2" face="Verdana"><b>Profiles</b></font><br>
<br>

<form name="frmLookup" method="POST" action="profile.php">
<table width="100%" border=0 bgcolor=#f0f0f0>
<tr><td><b>View Profile</b></td></tr>
 <tr><td>&nbsp;</td></tr>
 <tr><td align=center>Select Profile
     <select name="ID"> <? echo $Profiles ; ?></select></td></tr>
 <tr><td align=center><input type="submit" value="Lookup"> <input type="reset"></td></tr>
 <tr><td>&nbsp;</td></tr>
</table>
</form>

<form name="frmNewProfile" method="POST" action="index.php">
<input type="hidden" name="Cmd" value="NewProfile">
<table width="100%" bgcolor=#f0f0f0>
 <tr><td><b>Create Profile</b></td></tr>
 <tr><td>&nbsp;</td></tr>
 <tr><td align=center>Select LCS User
     <select name="ID"><? echo $LCSUsers ; ?></select></td></tr>
 <tr><td align=center><input type="submit" value="Create"> <input type="reset"></td></tr>
 <tr><td>&nbsp;</td></tr>
</table>
</form>
<br>

<center>
<a href="inventory.php">Inventory</a>
<a href="Reports">Reports</a><br>

<?
ora_close($cursor) ;
ora_close($cursor2) ;

include ("pow_footer.php") ;
?>
