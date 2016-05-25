<?
  // All POW files need this session file included
  include("pow_session.php");
  include("connect.php");

  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }

  $title = "Guard Gate Visitors";

  $user = $userdata['username'];
  // Display all users if the guards are logged in
  $area_type = "HRMS";
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  // Use the date the user asked for
  $report_date = $HTTP_POST_VARS["report_date"];

  $today = date('Y-m-d', strtotime($report_date));
  $start_today = date('Y-m-d', strtotime($report_date));
  $start_today .= " 01:00:00";
  $end_today = date('Y-m-d', strtotime($report_date));
  $end_today .= " 23:59:59";

  $stmt = "select * from security_gate where (reservation_date between '$start_today' and '$end_today') or (reservation_date <= '$start_today' and end_date >= '$today') order by reservation_date desc";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  $rows = pg_num_rows($result);
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<script language="Javascript1.2">
function printpage() {
  window.print();  
}
</script>

<html>
<head>
<meta http-equiv="Refresh" content="120">
<title>Eport - Vessel Discharge Reports</title>
</head>

<form action="report.php" method="Post" name="gg_report_form">
<b>Select a Date: </b>&nbsp;
<input type="textbox" name="report_date" size="10" maxlength="10"> <a href="javascript:show_calendar('gg_report_form.report_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
&nbsp;&nbsp;<input type="submit" value="View Report">
</form>
<?
  if($rows == 0){
    printf("<b>There are no requests for $report_date</b>");
  }
  else{
?>
<form><input type=button value="Print" onClick="printpage()"></form><br />

<b>Requests for <?= date('D, F d, Y', strtotime($report_date)) ?></b><br /><br />
      (Note that the report refreshes itself every 2 minutes.)<br /><br />
<table width=\"100%\" border="1" cellpadding="4" cellspacing="0">
  <th>Request Num</th><th>Requestor</th><th>Visitor</th><th>ETA<th>Through</th><th>Comments</th>
<?
    for($i = 0; $i < $rows; $i++){
      $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
      printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['request_number'], $row['requestor'], $row['visitor_name'], date('H:i:s',strtotime($row['reservation_date'])), date('m/d/y', strtotime($row['end_date'])), $row['comments']);
     }
     printf("</table>");
    }
include("pow_footer.php");
?>

</html>