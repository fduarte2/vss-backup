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

?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Installation</font><br>
<br>

<form name="frmInstall" action="Install_List.php">
Computer Name <input type="text" name="CompID" value="DSPC-">
<select name="EquipID">
<?
  $sql = "SELECT * FROM EQUIP_TYPE WHERE CATEGORY='Computer' ORDER BY BRAND,MODEL" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
    echo "<option value=\"" . $row["ID"] . "\">" ;
    echo $row["BRAND"] . " " . $row["MODEL"] ;
    echo "</option>" ;
  }
?>
</select>
Assign To <input type="text" name="User" value="">
<a href="javascript: document.frmInstall.submit() ;"><img src="../images/Install.gif" border=0></a><br>
</form>

<a href="InstallAdmin.php">Install Administration</a><br>
<center>
<br>
<a href="index.php">Equipment Tracking</a><br>
<br>
<? include("pow_footer.php"); ?>

