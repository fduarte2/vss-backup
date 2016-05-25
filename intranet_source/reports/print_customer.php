<?
  // All POW files need this session file included
  include("pow_session.php");
  include("specific_page_access.php");

  $user = $HTTP_GET_VARS['user'];
   $today = date('m/j/y');

  // initiate the pdf writer
  include 'class.ezpdf.php';
  $pdf = new Cezpdf('letter','landscape');
  $pdf -> ezSetMargins(20,30,30,30);
  $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
  $pdf->ezSetDy(-10);
  $pdf->ezText("<b>BNI Customer Report</b>", 16, $center);
  $pdf->ezSetDy(-10);
  $pdf->ezText("<b>$today</b>", 12, $right);
  $pdf->ezSetDy(-10);

  $show_all_option = $HTTP_GET_VARS['show'];

  include("defines.php");
  include("connect.php");

   // Connect to Oracle Database
   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){

      printf("Error logging on to the BNI Oracle Server: ");
      printf(ora_errorcode($conn_bni));
      printf("Please try later!");
      exit;
   }
//	$pdf->ezText("user: ".$user);
  if(specific_page_access("customer_printout", $user) === false){
    $pdf->ezText("Access Denied from This Report.");
    include("redirect_pdf.php");
  }

   //Open Cursor
   $cursor = ora_open($conn_bni);

   if($show_all_option == "active"){
	   $extra_sql = "and customer_status = 'ACTIVE'";
   } else {
	   $extra_sql = "";
   }

   $sql = "select * from customer_profile where 1 = 1 ".$extra_sql." order by customer_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();

   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
   {
      array_push($data, array('name'=>$row['CUSTOMER_NAME'], 'id'=>$row['CUSTOMER_ID'],
               'address1'=>$row['CUSTOMER_ADDRESS1'],
               'city'=>$row['CUSTOMER_CITY'], 'state'=>$row['CUSTOMER_STATE'],
               'zip'=>$row['CUSTOMER_ZIP'], 'phone'=>$row['CUSTOMER_PHONE'], 
               'email'=>$row['CUSTOMER_EMAIL'], 'status'=>$row['CUSTOMER_STATUS']));
 
   }

 $pdf->ezTable($data, array('name'=>'Customer Name', 'id'=>'Id', 'address1'=>'Address', 'city'=>'City', 'state'=>'State', 'zip'=>'ZipCode', 'phone'=>'Phone', 'email'=>'E-Mail', 'status'=>'Status'), '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>776, 'cols'=>array('name'=>array('width'=>100))));
   $format = "Port of Wilmington, " . $today . " Printed by " . $user;
   $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->line(20,822,578,822);
   $pdf->addText(50,34,6, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

 ora_close($cursor);
 ora_logoff($conn_bni);


   // redirect to a temporary PDF file instead of directly writing to the browser
   include("redirect_pdf.php");

?>

