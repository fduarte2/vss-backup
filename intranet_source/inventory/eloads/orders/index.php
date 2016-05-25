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
	    <font size="5" face="Verdana" color="#0066CC">Orders
	    </font>
	    <hr><? include("../eload_links.php"); ?>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="100%">
<?
  include("../eloads_globals.php");
  include("compareDate.php");

  $conn = ora_logon(RF, PASS);
  $cursor = ora_open($conn);
  $found_something = 0;

  // Only show Pending Loads
  $now = time();
  $seven_days_ago = date('m/d/Y');
  $stmt = "select to_char(req_date, 'MM/DD/YYYY HH24:MI:SS') req_date, to_char(create_date, 'MM/DD/YYYY HH24:MI:SS') create_date, to_char(sent_date, 'MM/DD/YYYY HH24:MI:SS') sent_date, order_num, status from eloads_confirmation where status in ('" . fileStart . "', '" . fileCreate . "', '" . fileSent . "', '" . fileUnknown . "', '" . fileRecv . "') and req_date > to_date('$seven_days_ago', 'MM/DD/YYYY') order by req_date desc";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if($found_something == 0){
        $found_something = 1;
        ?>
        <br /><br /><b>Pending Orders (Click for details): </b><br />
        <a name="table">
        <table bgcolor="#f0f0f0" bordercolor="black" width="100%" border="2" cellpadding="4" cellspacing="1">
        <th>Re-Send</th><th>Order Number</th><th>Request Date</th><th>Creation Time<th>Sent Date</th><th>Status</th>
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
       case fileStart:
         $status_color = startColor;
         $status_text = "Start";
         $def_string = "Order has just been sent - please wait for Port System to process...";
         break;
       case fileCreate:
         $status_color = createColor;
         $status_text = "Created";
         $def_string = "Port system has created the file - waiting for Oppy to pick it up...";
         break;
       case fileSent:
         $status_color = sentColor;
         $status_text = "Sent";
         $def_string = "Oppy has picked up file - waiting for Confirmation...";
         break;
       case fileRecv:
         $status_color = receivedColor;
         $status_text = "Received";
         $def_string = "Oppy has picked up file - waiting for Confirmation...";
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
     if($sent_date != "&nbsp;"){
       $lag_minutes = DateDiff('n', strtotime($row['SENT_DATE']), $now);
       if($lag_minutes > 10){
         // OPP problem
         $status_color = "red";
         $status_string = "Contact OPPY";
         $def_string = "File has been sent, but we have not Received confirmation - Please call " . OPPY_NUMBER . "!";
       }
       $def_string .= " ($lag_minutes minutes)";
     }

     // Make the Overlib statement
     $on_click =  "onclick=\"return overlib('$def_string', STICKY, CAPTION, '$load_num', CENTER);\" onmouseout=\"nd();\"";

     printf("<tr bgcolor=\"$status_color\"><td align=\"center\"><a href=\"gen_load.php?load_num=$load_num\"><img src=\"images/re-send.gif\" border=\"0\" alt=\"Re-Send\"></a></td><td $on_click><font color=\"black\">$load_num</td><td $on_click>$request_date</td><td $on_click>$creation_date</td><td $on_click>$sent_date</td><td $on_click>$status_text</td></font></a></tr>\n"); 
  }
  if($found_something == "1"){
    printf("</table><br />\n");
  }
  else{
    printf("<b>No Pending Orders found!</b>\n");
  }

  $found_something = 0;
  $seven_days_ago = date('m/d/Y');
  // Now show orders loading
  $stmt = "select to_char(ep.recv_date, 'MM/DD/YYYY HH24:MI:SS') recv_date, pl.* from pl_order_head pl, eloads_picklist ep where ep.recv_date > to_date('$seven_days_ago', 'MM/DD/YYYY') and pl.load_num = ep.load_num and pl.order_num not in (select order_num from eloads_confirmation where req_date > to_date('$seven_days_ago', 'MM/DD/YYYY')) order by ep.recv_date desc";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if($found_something == 0){
        $found_something = 1;
        ?>
        <br /><br /><b>Orders Currently Loading (Click for details): </b><br />
        <a name="table2">
        <table bgcolor="#f0f0f0" bordercolor="black" width="100%" border="2" cellpadding="4" cellspacing="1">
        <th>Order Number</th><th>Picklist Received Date</th><th>Status</th>
        <?
     }
     $order_num = $row['ORDER_NUM'];
     if($row['RECV_DATE'] != ""){
       $recv_date = date('m/d/Y H:i:s', strtotime($row['RECV_DATE']));
     }else{
       $recv_date = "&nbsp;";
     }

     $status_color = startColor;
     $status_text = "Loading...";
     $def_string = "Order is currently loading... (Click order number for details)";

     // Here we determine if the load has reached the RED!
     $lag_minutes = DateDiff('n', strtotime($row['RECV_DATE']), $now);
     if($lag_minutes > 140){
       // This is our problem- file not created
       $status_color = "red";
       $status_text = "Loading Problem?";
       $def_string = "Order has been loading for $lag_minutes minutes!  Please check the status.";
     }
     $def_string .= " ($lag_minutes minutes)";
   // Make the Overlib statement
   $on_click =  "onclick=\"return overlib('$def_string', STICKY, CAPTION, '$order_num', CENTER);\" onmouseout=\"nd();\"";

     printf("<tr bgcolor=\"$status_color\"><td align=\"center\"><font color=\"black\"><a href=\"checker_tally_html.php?order_num=$order_num\">$order_num</a></font></td><td $on_click>$recv_date</td><td $on_click>$status_text</td></font></a></tr>\n"); 
  }
  if($found_something == "1"){
    printf("</table><br />");
  }
  else{
    printf("<b>No Loading Orders found!</b><br />");
  }

?>
<font size="2" face="Verdana" color="#0066CC"><a href="javascript:void(0);" onclick="refresh()"><img src="images/search.gif" border="0"> Refresh</a><br /><br />
Last Update: <?= date('m/d/y H:i:s') ?><br /></font>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
