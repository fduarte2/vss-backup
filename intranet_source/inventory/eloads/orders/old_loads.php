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

<script type="text/javascript" src="/functions/calendar.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<script type="text/javascript">
  function refresh(){
    document.location.href="old_loads.php";
  }
</script>

<body link="#336633" vlink="#999999" alink="#999999">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Completed Orders
	    </font>
	   <hr><? include("../eload_links.php"); ?>
      </td>
   </tr>
</table>
<?
  $start_date = $HTTP_POST_VARS["start_date"];
  if($start_date == ""){
    $start_date = date('m/d/Y', mktime (0,0,0, date('m'), date('d') - 7, date('Y')));
  }

  $end_date = $HTTP_POST_VARS["end_date"];
  if($end_date == ""){
    $end_date = date('m/d/Y');
  }
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana">Enter a date range to view completed
loads.</font>
         </p>
         <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
               <td width="5%">&nbsp;</td>
               <td width="30%" align="right" valign="top">
                  <font size="2" face="Verdana">Start Date:</font></td>
               <td width="45%" align="left">
               <form name="date_range_form" method="Post" action="old_loads.php">
                  <input type="textbox" name="start_date" size="15" maxlength="15" value="<?= $start_date ?>">
<a href="javascript:show_calendar('date_range_form.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
               </td>
               <td width="20%">&nbsp;</td>
            </tr>
            <tr>
               <td width="5%">&nbsp;</td>
               <td width="30%" align="right" valign="top">
                  <font size="2" face="Verdana">End Date:</font></td>
               <td width="45%" align="left">
                  <input type="textbox" name="end_date" size="15" maxlength="15" value="<?= $end_date ?>">
<a href="javascript:show_calendar('date_range_form.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
               </td>
               <td width="20%">&nbsp;</td>
            </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
               <td align="left">
                  <input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset">
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
  // Only show Received Loads
  $stmt = "select to_char(req_date, 'MM/DD/YYYY HH24:MI:SS') req_date, to_char(create_date, 'MM/DD/YYYY HH24:MI:SS') create_date, to_char(sent_date, 'MM/DD/YYYY HH24:MI:SS') sent_date, order_num, status from eloads_confirmation where status in ('" . fileFinalSent . "') and req_date > to_date('$start_date', 'MM/DD/YYYY') and req_date < to_date('$end_date', 'MM/DD/YYYY') order by req_date desc";
  $conn = ora_logon(RF, PASS);
  $cursor = ora_open($conn);
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  $now = time();
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if($found_something == 0){
        $found_something = 1;
        ?>
        <br /><br /><b>Completed Orders (Click for details): </b><br />
        <a name="table">
        <table bgcolor="#f0f0f0" bordercolor="black" width="100%" border="2" cellpadding="4" cellspacing="1">
        <th>Re-Start</th><th>Order Number</th><th>Request Date</th><th>Creation Time<th>Sent Date</th><th>Status</th>
        <?
     }
     $load_num = $row['ORDER_NUM'];
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
       case fileFinalSent:
         $status_color = receivedColor;
         $status_text = "Final Sent";
         $def_string = "The Final Confirmation has been sent to Oppy.  Transaction Complete";
         break;
       case fileUnknown:
         $status_color = unknownColor;
         $status_text = "Unknown!";
         $def_string = "I am not sure whats happening with this Order!";
         break;
       default:
         $status_color = "cyan";
         $status_text = "Unknown!";
         $def_string = "I am not sure whats happening with this Order!";
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
         $def_string = "Order has been entered, but the Port system is not creating a file!  Please Call TS on Port 4!";
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

     // Make the Overlib statement
     $on_click =  "onclick=\"return overlib('$def_string', STICKY, CAPTION, '$load_num', CENTER);\" onmouseout=\"nd();\"";

     printf("<tr bgcolor=\"$status_color\"><td align=\"center\"><a href=\"resend_order.php?order_num=$load_num\"><img src=\"images/re-send.gif\" border=\"0\" alt=\"Re-Send\"></a></td><td><a href=\"checker_tally_html.php?order_num=$load_num\"><font color=\"black\">$load_num</a></td><td $on_click>$request_date</td><td $on_click>$creation_date</td><td $on_click>$sent_date</td><td $on_click>$status_text</td></font></tr>\n"); 
  }
  if($found_something == "1"){
    printf("</table><br />");
  }
  else{
    printf("<b>No Records found for this week!</b>");
  }

?>
<font size="2" face="Verdana" color="#0066CC"><a href="javascript:void(0);" onclick="refresh()"><img src="images/search.gif" border="0"> Refresh</a><br /><br />
Last Update: <?= date('m/d/y H:i:s') ?><br /></font>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
