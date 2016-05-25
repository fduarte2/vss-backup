<?
// Seth Morecraft  (2-MAY-03)
// Shows Supervisors what labor tickets remain to be billed.

include("pow_session.php");

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','portrait');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
  $pdf->ezText("LCS\nUn-Billed Labor Ticket Report\n\n", 12, $center);

  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);

  $sql = "select h.ticket_num, h.service_date, h.customer_id, h.commodity_code, h.vessel_id, h.location_id, h.job_description, u.user_name, c.customer_name, h.service_group, h.bill_status from labor_ticket_header h, lcs_user u, customer c where (h.bill_status is null or h.bill_status ='U') and h.service_date > '01-JAN-02' and h.user_id = u.user_id and h.customer_id = c.customer_id order by h.ticket_num";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  // Build an array based on the coolness...
  $data = array();
  while (ora_fetch($cursor)){
    $ticket = ora_getcolumn($cursor, 0);
    $date = ora_getcolumn($cursor, 1);
    $user_name = ora_getcolumn($cursor, 7);
    $customer = ora_getcolumn($cursor, 8);
    $service = ora_getcolumn($cursor, 9);
    $status = ora_getcolumn($cursor, 10);

    if($date == ""){
      $date = "Unknown";
    }
    else{
      $date = date('m/d/Y', strtotime($date));
    }
    array_push($data, array('ticket'=>$ticket, 'date'=>$date, 'customer'=>$customer, 'user'=>$user_name,'service'=>$service.'X','status'=>$status));
  }

  $pdf->ezTable($data, array('ticket'=>'Ticket Number', 'date'=>'Service Date', 'service'=>'Service','customer'=>'Customer', 'user'=>'Supervisor','status'=>'Status'), '', array('fontSize'=>10, 'showHeadings'=>1, 'shaded'=>0, 'width'=>500, 'showLines'=>2));

$pdf->ezStream();
ora_close($cursor);
?>
