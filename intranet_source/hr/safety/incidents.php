<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Safety Stats - Incidents";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }
include("connect.php");
// Connect

$conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$conn) die("Could not open connection to database server");

?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
  <tr>
    <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Safety Statistics
            </font>
	    <hr>
            <font size="3" face="Verdana" color="#0066CC">Incidents</font>
         </p>
      </td>
   </tr>
</table>
<br>
<br>

<?
$EditID = -1 ;
$offset = 0 ;
if (isset($HTTP_POST_VARS["Offset"])) $offset = $HTTP_POST_VARS["Offset"] ;
$Filter = "" ;
if (isset($HTTP_POST_VARS["Filter"])) {
  $Filter = stripslashes($HTTP_POST_VARS["Filter"]) ;
}
//echo "CMD: " . $Cmd ;
if (isset($Cmd)) {
  if ($Cmd == "Filter") {
    if ($HTTP_POST_VARS["FilterType"] == "Date")
      $Filter = "incident_date" ;
    if ($HTTP_POST_VARS["FilterType"] == "Location")
      $Filter = "location" ;
    if ($HTTP_POST_VARS["FilterType"] == "Accident Type")
      $Filter = "medical_code" ;
    if ($HTTP_POST_VARS["FilterType"] == "Classification")
      $Filter = "classification" ;
    if ($HTTP_POST_VARS["FilterType"] == "Supervisor")
      $Filter = "supervisor" ;
    if ($HTTP_POST_VARS["FilterType"] == "Employee Classification")
      $Filter = "employee_classification" ;
    $Filter .= $HTTP_POST_VARS["FilterMethod"] . "'" . $HTTP_POST_VARS["FilterText"] . "'" ;
  }
  if ($Cmd == "Edit") $EditID = $HTTP_POST_VARS["ID"] ;
  if ($Cmd == "EditEntry") {
    $EditID = -1 ;
    $query = "UPDATE saftey_stats set incident_date='". $HTTP_POST_VARS["ModDate"] . "',location='" . $HTTP_POST_VARS["ModLocation"]. "'" ;
    $query .= ",medical_code='" . $HTTP_POST_VARS["ModMedicalType"]. "',classification='" . $HTTP_POST_VARS["ModClass"]. "'";
    $query .= ",supervisor='" . $HTTP_POST_VARS["ModSupervisor"]. "',employee_classification='" . $HTTP_POST_VARS["ModEmpClass"]. "'" ;
    $query .= ",notes='" . $HTTP_POST_VARS["ModNotes"]. "' WHERE incident_id=" . $HTTP_POST_VARS["ID"] ;
	$rs = pg_query($conn, $query) or die("Error in query: $query. " .  pg_last_error($conn));
//	echo $query ;
  }
}

$ItemsPerPage = 10 ;

?>

<form name="frmFilter" method="POST" action="incidents.php">
<input type="hidden" name="Cmd" value="Filter">
Filter by
<select name="FilterType">
<option>Date</option>
<option>Location</option>
<option>Accident Type</option>
<option>Classification</option>
<option>Supervisor</option>
<option>Employee Classification</option>
</select>
<select name="FilterMethod">
<option>=</option>
<option><</option>
<option>></option>
<option><=</option>
<option>>=</option>
</select>
<input type="text" name="FilterText">
<input type="submit" value="Go">
</form>
<? 
if (strlen($Filter) > 0) echo "<font size=2>Using <b>" . $Filter . "</b></font><br>" ;
?>
<br>

<form name="frmEdit" method="POST" action="incidents.php">
<input type="hidden" name="Cmd" value="Edit">
<input type="hidden" name="ID" value="">
<input type="hidden" name="Filter" value="<? echo $Filter ; ?>">
<input type="hidden" name="Offset" value="<? echo $offset ; ?>">
</form>

<form name="frmEditEntry" method="POST" action="incidents.php">
<input type="hidden" name="Cmd" value="EditEntry">
<input type="hidden" name="ID" value="<? echo $EditID ; ?>">
<input type="hidden" name="Filter" value="<? echo $Filter ; ?>">
<input type="hidden" name="Offset" value="<? echo $offset ; ?>">
<table width="100%" cellpadding=3>
 <tr valign="top" bgcolor="#0066CC">
  <td><b>Date</b></td>
  <td><b>Location</b></td>
  <td><b>Classification</b></td>
  <td><b>Accident Type</b></td>
  <td><b>Supervisor</b></td>
  <td><b>Emp Class</b></td>
  <td><b>Notes</b></td>
  <td><b>&nbsp;</b></td>
 </tr>
<?

$query = "select * from saftey_stats" ;
if (strlen($Filter) > 0) $query .= " WHERE " . $Filter ;
$query .= " order by incident_date" ;
$rs = pg_query($conn, $query) or die("Error in query: $query. " .  pg_last_error($conn));
$rows = pg_num_rows($rs);
$iCount = 0 ;
while ($iCount < $rows) {
  $row = pg_fetch_array($rs, $x, PGSQL_ASSOC);
  if ($iCount >= ($offset*$ItemsPerPage) && $iCount < ($offset*$ItemsPerPage+$ItemsPerPage)) {
    if (fmod($iCount,2) == 0)
      echo "<tr valign=\"top\">" ;
    else
      echo "<tr valign=\"top\" bgcolor=\"#c0c0c0\">" ;
    if ($EditID == $row["incident_id"]) {
?>
  <td><input type="text" name="ModDate" value="<? echo date("m/d/Y",strtotime($row["incident_date"])) ; ?>"></td>
  <td><select name="ModLocation">
     <option value="Auto Berth" <? if ($row["location"] == "Auto Berth") echo "selected" ?>>Auto Berth</option>
     <option value="Wing A" <? if ($row["location"] == "Wing A") echo "selected" ?>>Wing A</option>
     <option value="Wing B" <? if ($row["location"] == "Wing B") echo "selected" ?>>Wing B</option>
     <option value="Wing C" <? if ($row["location"] == "Wing C") echo "selected" ?>>Wing C</option>
     <option value="Wing D" <? if ($row["location"] == "Wing D") echo "selected" ?>>Wing D</option>
     <option value="Wing E" <? if ($row["location"] == "Wing E") echo "selected" ?>>Wing E</option>
     <option value="Wing F" <? if ($row["location"] == "Wing F") echo "selected" ?>>Wing F</option>
     <option value="Wing G" <? if ($row["location"] == "Wing G") echo "selected" ?>>Wing G</option>
     <option value="Wing H" <? if ($row["location"] == "Wing H") echo "selected" ?>>Wing H</option>
     <option value="Dock" <? if ($row["location"] == "Dock") echo "selected" ?>>Dock</option>
     <option value="Mtn Shop" <? if ($row["location"] == "Mtn Shop") echo "selected" ?>>Mtn Shop</option>
     <option value="Crane" <? if ($row["location"] == "Crane") echo "selected" ?>>Crane</option>
     <option value="Grounds" <? if ($row["location"] == "Grounds") echo "selected" ?>>Grounds</option>
     <option value="Admin Bldg" <? if ($row["location"] == "Admin Bldg") echo "selected" ?>>Admin Bldg</option>
     <option value="Bulk Yards" <? if ($row["location"] == "Bulk Yards") echo "selected" ?>>Bulk Yards</option>
     <option value="Hiring Hall" <? if ($row["location"] == "Hiring Hall") echo "selected" ?>>Hiring Hall</option>
     <option value="Steel Yards" <? if ($row["location"] == "Steel Yards") echo "selected" ?>>Steel Yards</option>
     <option value="Lumber Yards" <? if ($row["location"] == "Lumber Yards") echo "selected" ?>>Lumber Yards</option>
     <option value="1st Pt Landing" <? if ($row["location"] == "1st Pt Landing") echo "selected" ?>>1st Pt Landing</option>
     <option value="Undetermined" <? if ($row["location"] == "Undetermined") echo "selected" ?>>Undetermined</option>
     </select></td>
  <td><select name="ModClass">
     <option <? if ($row["classification"] == "Struck By") echo "selected" ?>>Struck By</option>
     <option <? if ($row["classification"] == "Slip, Trip & Fall") echo "selected" ?>>Slip, Trip & Fall</option>
     <option <? if ($row["classification"] == "Sprain, Strain (Back)") echo "selected" ?>>Sprain, Strain (Back)</option>
     <option <? if ($row["classification"] == "Sprain, Strain (Others)") echo "selected" ?>>Sprain, Strain (Others)</option>
     <option <? if ($row["classification"] == "Foreign Objects (Eye)") echo "selected" ?>>Foreign Objects (Eye)</option>
     <option <? if ($row["classification"] == "Punctures, Cuts") echo "selected" ?>>Punctures, Cuts</option>
     <option <? if ($row["classification"] == "Contusions") echo "selected" ?>>Contusions</option>
     <option <? if ($row["classification"] == "Caught In/By") echo "selected" ?>>Caught In/By</option>
     <option <? if ($row["classification"] == "Burns") echo "selected" ?>>Burns</option>
     <option <? if ($row["classification"] == "Cummulative Trauma Disorder") echo "selected" ?>>Cummulative Trauma Disorder</option>
     <option <? if ($row["classification"] == "Misc") echo "selected" ?>>Misc</option>
     </select></td>
  <td><select name="ModMedicalType">
     <option value="Unknown" >Unknown</option>
     <option value="Medical Only" <? if ($row["medical_code"] == "Medical Only") echo "selected" ?>>Medical Only</option>
     <option value="Medical/Leave" <? if ($row["medical_code"] == "Medical/Leave") echo "selected" ?>>Medical/Leave</option>
     <option value="Report Only" <? if ($row["medical_code"] == "Report Only") echo "selected" ?>>Report Only</option>
     </select></td>
  <td><select name="ModSupervisor">
     <option value="Supervisor Unknown">Supervisor Unknown</option>
<?
  $connLCS = ora_logon("LABOR@LCS", "LABOR");
  if($connLCS < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($connLCS));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($connLCS);
  $sql = "select user_name from lcs_user where status = 'A' and group_id in ('4', '2') order by user_name";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $super = "William Stansbury,Martin McLaughlin,Victor Farkas,Antonia Brizolaki" ;
  while (ora_fetch($cursor)){
    $username = ora_getcolumn($cursor, 0);
    $super .= "," . $username ;
  }
  $names = explode(",", $super) ;
  sort($names) ;
  for ($i = 0; $i< count($names) ; $i++) {
    echo "<option value=\"" . $names[$i] . "\"" ;
    if ($row["supervisor"] == $names[$i]) echo " selected" ;
    echo ">" . $names[$i] . "</option>" ;
  }
  ora_close($connLCS) ;
?>
     </select></td>
  <td><select name="ModEmpClass">
     <option>Unknown</option>
     <option <? if ($row["employee_classification"] == "A") echo "selected" ?>>A</option>
     <option <? if ($row["employee_classification"] == "B") echo "selected" ?>>B</option>
     <option <? if ($row["employee_classification"] == "C") echo "selected" ?>>C</option>
     <option <? if ($row["employee_classification"] == "Admin") echo "selected" ?>>Admin</option>
     <option <? if ($row["employee_classification"] == "Supervisor") echo "selected" ?>>Supervisor</option>
     <option <? if ($row["employee_classification"] == "Guards") echo "selected" ?>>Guards</option>
     <option <? if ($row["employee_classification"] == "Other") echo "selected" ?>>Other</option>
     </select></td>
  <td><textarea name="ModNotes"><? echo $row["notes"] ;?></textarea></td>
  <td><input type="submit" value="Save">
      <input type="button" value="Cancel" OnClick="javascript: document.frmEdit.ID.value = '-1' ; document.frmEdit.submit() ;"></td>

<?
    } else {
      echo " <td>" . date("m/d/Y",strtotime($row["incident_date"])) . "</td>" ;
      echo " <td>" . $row["location"] . "</td>" ;
      echo " <td>" . $row["classification"] . "</td>" ;
      echo " <td>" . $row["medical_code"] . "</td>" ;
      echo " <td>" . $row["supervisor"] . "</td>" ;
      echo " <td>" . $row["employee_classification"] . "</td>" ;
      echo " <td>" . $row["notes"] . "</td>" ;
      echo " <td><input type=\"button\" value=\"Edit\" OnClick=\"javascript: document.frmEdit.ID.value = " . $row["incident_id"] . " ; document.frmEdit.submit() ;\"></td>" ;
    }
    echo "</tr>\n" ;

  }
  $iCount++ ;
}
pg_close($conn);
?>
</table>
</form>
<br>

<form name="frmPage" method="POST" action="incidents.php">
<input type="hidden" name="Cmd" value="Page">
<input type="hidden" name="Filter" value="<? echo $Filter ;?>">
<input type="hidden" name="Offset" value="">
<?
echo "Page: " ;
$MaxPage = ($rows/$ItemsPerPage) ;
if ($offset > 0)
  echo "<a href=\"javascript: document.frmPage.Offset.value=" . ($Offset - 1) . " ; document.frmPage.submit();\">&lt;&lt;</a> " ;
else
  echo "<font color=#c0c0c0>&lt;&lt;</font> " ;
for ($i=0; $i<$MaxPage; $i++) {
  if ($offset != $i)
    echo "<a href=\"javascript: document.frmPage.Offset.value=" . $i . " ; document.frmPage.submit();\">" . ($i + 1) . "</a> " ;
  else
    echo "<b>" . ($i + 1) . "</b> " ;
}
if ($offset < $MaxPage-1) 
  echo "<a href=\"javascript: document.frmPage.Offset.value=" . ($Offset + 1) . " ; document.frmPage.submit();\">&gt;&gt;</a>" ;
else
  echo "<font color=#c0c0c0>&gt;&gt;</font> " ;
echo "<br>" ;
?>
</form>

<hr>
<a href="index.php">Back to Safety Statistics</a>

<?
include("pow_footer.php");
?>
