<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "E-Loads - Load Requests";
  $area_type = "ELOA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<script type="text/javascript">
  function validate_mod(){
    x = document.gen_load_form
    load_num = x.load_num.value
    length = load_num.length
    if(length != 10){
      alert("Load Number must be 10 Characters!!  Please try again.");
      return false;
    }
    return true;
  }

  function refresh(){
    document.location.href="index.php";
  }
</script>

<body link="#336633" vlink="#999999" alink="#999999">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Load Requests
	    </font>
	    <hr><? include("../eload_links.php"); ?>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana">Enter new Loads to be sent out from here.</font>
         </p>
         <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
               <td width="5%">&nbsp;</td>
               <td width="30%" align="right" valign="top">
                  <font size="2" face="Verdana">Generate Load Number:</font></td>
               <td width="45%" align="left">
               <form name="gen_load_form" method="Post" action="gen_load.php" onsubmit="return validate_mod()">
                  <input type="textbox" name="load_num" size="10" maxlength="10" value="LD00">
               </td>
               <td width="20%">&nbsp;</td>
            </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
               <td align="left">
                  <input type="submit" value="Generate">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset">
              </td>
              </form>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td colspan="4">&nbsp;</td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
<?
  include("../eloads_globals.php");
  include("compareDate.php");
  // Only show Started, Created, Sent or Unknown Loads
  $seven_days_ago = date('m/d/Y', mktime (0,0,0, date('m'), date('d') - 7, date('Y')));
  $stmt = "select to_char(req_date, 'MM/DD/YYYY HH24:MI:SS') req_date, to_char(create_date, 'MM/DD/YYYY HH24:MI:SS') create_date, to_char(sent_date, 'MM/DD/YYYY HH24:MI:SS') sent_date, load_num, status from eloads_picklist where status in ('" . fileStart . "', '" . fileCreate . "', '" . fileSent . "', '" . fileUnknown . "') and req_date > to_date('$seven_days_ago', 'MM/DD/YYYY') order by req_date desc";
  $conn = ora_logon(RF, PASS);
  $cursor = ora_open($conn);
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  $now = time();
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if($found_something == 0){
        $found_something = 1;
        ?>
        <br /><br /><b>Pending Loads (Click for details): </b><br />
        <a name="table">
        <table bgcolor="#f0f0f0" bordercolor="black" width="65%" border="2" cellpadding="4" cellspacing="1">
        <th>Re-Send</th><th>Load Number</th><th>Request Date</th><th>Creation Time<th>Sent Date</th><th>Status</th>
        <?
     }
     $load_num = $row['LOAD_NUM'];
     if($row['REQ_DATE'] != ""){
       $request_date = date('m/d/Y H:i:s', strtotime($row['REQ_DATE']));
     }else{
       $request_date = "&nbsp;";
     }
     if($row['CREATE_DATE'] != ""){
       $creation_date = date('m/d/Y H:i:s', strtotime($row['CREATE_DATE']));
     }else{
       $creation_date = "&nbsp;";
     }
     if($row['SENT_DATE'] != ""){
       $sent_date = date('m/d/Y H:i:s', strtotime($row['SENT_DATE']));
     }else{
       $sent_date = "&nbsp;";
     }
     $status = $row['STATUS'];

     switch($status){
       case fileStart:
         $status_color = startColor;
         $status_text = "Start";
         $def_string = "You have just entered this Load - please wait for Port System to process...";
         break;
       case fileCreate:
         $status_color = createColor;
         $status_text = "Created";
         $def_string = "Port system has created the file - waiting for Oppy to pick it up...";
         break;
       case fileSent:
         $status_color = sentColor;
         $status_text = "Sent";
         $def_string = "Oppy has picked up file - waiting for Picklist...";
         break;
       case fileUnknown:
         $status_color = unknownColor;
         $status_text = "Unknown!";
         $def_string = "I am not sure whats happening with this Load!";
         break;
       default:
         $status_color = "cyan";
         $status_text = "Unknown!";
         $def_string = "I am not sure whats happening with this Load!";
         break;
     }
     // Here we determine if the load has reached the RED!
     if($creation_date == "&nbsp;"){
       $lag_minutes = DateDiff('n', strtotime($row['REQ_DATE']), $now);
       //echo "Hey : $lag_minutes " . strftime('%Hh%M %A %d %b',$now) . " - " . strftime('%Hh%M %A %d %b', strtotime($row['REQ_DATE']));
       if($lag_minutes > 5){
         // This is our problem- file not created
         $status_color = "red";
         $status_text = "Contact TS!";
         $def_string = "Load has been entered, but the Port system is not creating a file!  Please Call TS on Port 4!";
       }
       $def_string .= " ($lag_minutes minutes)";
     }
     if($creation_date != "&nbsp;" && $sent_date == "&nbsp;"){
       $lag_minutes = DateDiff('n', strtotime($row['CREATE_DATE']), $now);
       if($lag_minutes > 10){
         // This is OPPS problem- they havent sent anything back!
         $status_color = "red";
         $status_text = "Conact OPPY";
         $def_string = "File has been created but not picked up by Oppy - Please call " . OPPY_NUMBER . "!";
       }
       $def_string .= " ($lag_minutes minutes)";
     }
     if($sent_date != "&nbsp;"){
       $lag_minutes = DateDiff('n', strtotime($row['SENT_DATE']), $now);
       if($lag_minutes > 10){
         // OPP problem
         $status_color = "red";
         $status_string = "Contact OPPY";
         $def_string = "File has been sent, but we have not received a Picklist - Please call " . OPPY_NUMBER . "!";
       }
       $def_string .= " ($lag_minutes minutes)";
     }

     // Make the Overlib statement
     $on_click =  "onclick=\"return overlib('$def_string', STICKY, CAPTION, '$load_num', CENTER);\" onmouseout=\"nd();\"";

     printf("<tr bgcolor=\"$status_color\"><td align=\"center\"><a href=\"gen_load.php?load_num=$load_num\"><img src=\"images/re-send.gif\" border=\"0\" alt=\"Re-Send\"></a></td><td $on_click><font color=\"black\">$load_num</td><td $on_click>$request_date</td><td $on_click>$creation_date</td><td $on_click>$sent_date</td><td $on_click>$status_text</td></font></a></tr>\n"); 
  }
  if($found_something == "1"){
    printf("</table><br />");
  }
  else{
    printf("<b>No Records found for this week!</b><br />");
  }

?>
<font size="2" face="Verdana" color="#0066CC"><a href="javascript:void(0);" onclick="refresh()"><img src="images/search.gif" border="0"> Refresh</a><br /><br />
Last Update: <?= date('m/d/y H:i:s') ?><br /></font>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
