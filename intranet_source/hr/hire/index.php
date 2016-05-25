<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Safety Stats";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }
?>

<script language="JavaScript">
  <? include("js_func.php") ?>

  function safety_validate() {
    x = document.mod_form
    var asless = true;
    incident_date = x.incident_date.value
    if(incident_date == ""){
      alert("Please enter an Incident date!");
      asless = false
    }
    return asless;
  }
</script>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Safety Statistics
            </font>
	    <hr>
         </p>
      </td>
   </tr>
</table>

Reports:
<form name="report_form" action="report.php" method="Post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Month: </font></td>
         <td align="left">
            <select name="month">
<?
  for($i = 1; $i <= 12; $i++){
    printf("<option value=\"$i\" ");
    if($i == date('n')){
      printf("selected=\"true\"");
    }
    // Year doesn't matter
    printf(">%s</option>\n", date('F',  mktime (0,0,0, $i, 1, 2004)));
  }
?>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Year: </font></td>
         <td align="left">
            <select name="year">
<?
  for($i = date('Y') - 5; $i <= date('Y'); $i++){
    if($i < 2004)  // program start date
      continue;
    printf("<option value=\"$i\" ");
    if($i == date('Y')){
      printf("selected=\"true\"");
    }
    // Year doesn't matter
    printf(">%s</option>\n", date('Y',  mktime (0,0,0, 1, 1, $i)));
  }
?>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Report Type: </font></td>
         <td align="left">
            <select name="report_type">
              <option>Monthly Statistics</option>
              <option>Yearly Statistics</option>
              <option>By Supervisor</option>
            </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td colspan="2" align="center">
            <table>
               <tr>
                  <td width="40%" align="right"> 
                     <input type="Submit" value="Create Report">&nbsp;&nbsp;
                  </td>
                  <td width="5%">&nbsp;</td>
                  <td width="55%" align="left">
                      <input type="Reset" value="Reset"> </form>
                  </td>
               </tr>
            </table>            
         </td>
         <td>&nbsp;</td>
      </tr>
</table>

<br />
Older Reports:<br />
<a href="AccidentAnalysis01_04.doc">Accident Analysis 2001-Sept 2004</a><br />
<a href="AccidentCauses01_04.doc">Accident Cause Summary 2001-Sept 2004</a><br />


<hr>

<a href="incidents.php">View Incidents</a>

<br /><br />
<form name="mod_form" action="add.php" method="Post" onsubmit="return safety_validate()">
<?
  if($input == 1){
    printf("<b>Incident save successful.</b><br /><br />");
  }
?>
Add a new Incident:<br />
<table border="0" bgcolor="#C5C5C0" width="100%" cellpadding="4" cellspacing="0">
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Incident Date: </font></td>
         <td align="left">
            <input type="text" name="incident_date" value=""><a href="javascript:show_calendar('mod_form.incident_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Location: </font></td>
         <td align="left">
         <select name="location">
           <option value="" selected="true">Please Select</option>
           <option value="Auto Berth">Auto Berth</option>
           <option value="Wing A">Wing A</option>
           <option value="Wing B">Wing B</option>
           <option value="Wing C">Wing C</option>
           <option value="Wing D">Wing D</option>
           <option value="Wing E">Wing E</option>
           <option value="Wing F">Wing F</option>
           <option value="Wing G">Wing G</option>
           <option value="Wing H">Wing H</option>
           <option value="Dock">Dock</option>
           <option value="Mtn Shop">Mtn Shop</option>
           <option value="Hiring Hall">Hiring Hall</option>
           <option value="Crane">Crane</option>
           <option value="Grounds">Grounds</option>
           <option value="Admin Bldg">Admin Bldg</option>
           <option value="Bulk Yards">Bulk Yards</option>
           <option value="Steel Yards">Steel Yards</option>
           <option value="Lumber Yards">Lumber Yards</option>
           <option value="1st Point of Landing">1st Point of Landing</option>
           <option value="Undetermined">Undetermined</option>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Accident Type: </font></td>
         <td align="left">
         <select name="medical_code">
           <option value="" selected="true">Please Select</option>
           <option value="Medical Only">Medical Only</option>
           <option value="Medical/Leave">Medical/Lost Time</option>
           <option value="Report Only">Report Only</option>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Classification: </font></td>
         <td align="left">
         <select name="classification">
           <option value="" selected="true">Please Select</option>
           <option>Struck By</option>
           <option>Slip, Trip & Fall</option>
           <option>Sprain, Strain (Back)</option>
           <option>Sprain, Strain (Others)</option>
           <option>Foreign Objects (Eye)</option>
           <option>Punctures, Cuts</option>
           <option>Contusions</option>
           <option>Caught In/By</option>
           <option>Burns</option>
	   <option>Cummulative Trauma Disorder</option>
	   <option>Misc</option>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Supervisor: </font></td>
         <td align="left">
         <select name="supervisor">
<!-- We need to Add certain people as defaults (but they are not in these groups) -->
         <option value="Supervisor Unknown">Supervisor Unknown</option>
<?
  $conn = ora_logon("LABOR@LCS", "LABOR");

  if($conn < 1){
   printf("Error logging on to the Oracle Server: ");
   printf(ora_errorcode($conn));
   printf("</body></html>");
   exit;
  }

  $cursor = ora_open($conn);
  $sql = "select user_name from lcs_user where status = 'A' and group_id in ('4', '2') order by user_name";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $super = "William Stansbury,Martin McLaughlin,Victor Farkas,Antonia Brizolaki" ;
  while (ora_fetch($cursor)){
    $username = ora_getcolumn($cursor, 0);
    //printf("<option value=\"%s\">%s</option>", $username, $username);
    $super .= "," . $username ;
  }
  $names = explode(",", $super) ;
  sort($names) ;
  for ($i = 0; $i< count($names) ; $i++)
    echo "<option value=\"" . $names[$i] . "\">" . $names[$i] . "</option>" ;


?>
         </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right">
	    <font size="2" face="Verdana">Employee Classification: </font></td>
	 <td align="left">
	 <select name="employee_classification">
           <option value="" selected="true">Please Select</option>
           <option>A</option>
           <option>B</option>
           <option>C</option>
           <option>Admin</option>
           <option>Supervisor</option>
           <option>Guards</option>
           <option>Other</option>
	 </select>
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Notes: </font></td>
	 <td align="left">
	   <input type="text" name="notes" value="" size="45" maxlength="200">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td colspan="2" align="center">
            <table>
               <tr>
                  <td width="40%" align="right"> 
                     <input type="Submit" value="Create Incident">&nbsp;&nbsp;
                  </td>
                  <td width="5%">&nbsp;</td>
                  <td width="55%" align="left">
                      <input type="Reset" value="Reset Form "> </form>
                  </td>
               </tr>
            </table>            
         </td>
         <td>&nbsp;</td>
      </tr>
</table>

<?


//$names = explode(",", $super) ;
//for ($i = 0; $i< count($names) ; $i++) {
//  $a = explode(" ", $names[$i]) ;
//  $names[$i] = $a[1] . ", " . $a[0] ;
//}
//sort($names) ;
//for ($i = 0; $i< count($names) ; $i++) {
//  echo $names[$i] . "<br>" ;
//}


include("pow_footer.php");
?>
