<?
// Seth Morecraft  (22-JAN-03)
// Prints the Verifications for receiving.
include("pow_session.php");
$user = $userdata['username'];

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

// In mm/dd/YYYY format
$today = date('Y-m-d');
$month = $HTTP_POST_VARS["month"];
$year = $HTTP_POST_VARS["year"];
$report_type = $HTTP_POST_VARS["report_type"];

if($report_type == "Monthly Statistics"){
  $intro = "Injury & Incidents Report - Port of Wilmington";
  $start_date = date('Y-m-d', mktime (0,0,0, $month, 1, $year));
  $temp_date = date('t', mktime (0,0,0, $month, 1, $year));
  $end_date = date('Y-m-d', mktime (0,0,0, $month, $temp_date, $year));
  $order_by = "location" ;
}
else if($report_type == "Yearly Statistics"){
  $intro = "Yearly Safety Statistics - Port of Wilmington";
  $start_date = date('Y-m-d', mktime (0,0,0, 1, 1, $year));
  $temp_date = date('t', mktime (0,0,0, 12, 1, $year));
  $end_date = date('Y-m-d', mktime (0,0,0, 12, $temp_date, $year));
  $order_by = "location" ;
}
if($report_type == "By Supervisor"){
  $intro = "Yearly Safety Statistics by Supervisor" ;
  $start_date = date('Y-m-d', mktime (0,0,0, 1, 1, $year));
  $temp_date = date('t', mktime (0,0,0, 12, 1, $year));
  $end_date = date('Y-m-d', mktime (0,0,0, 12, $temp_date, $year));
  $order_by = " supervisor" ;
}

// Bounds check
if($end_date > $today){
  $end_date = $today;
}

  include("connect.php");
  // Connect
  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$connection){
   die("Could not open connection to database server");
  }
  $sql = "select * from saftey_stats where incident_date between '$start_date' and '$end_date' order by " . $order_by . ";" ;
  $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
  $rows = pg_num_rows($result);
  if($rows == 0){
    header("Location: index.php?report=1");
    exit;
  }

  $ShowHR = false ;
  if ($user == "lstewart") $ShowHR = true ;
  if ($user == "wgagnon") $ShowHR = true ;
  if ($user == "skennard") $ShowHR = true ;
  if ($user == "gbailey") $ShowHR = true ;
  if ($user == "fvignuli") $ShowHR = true ;
  if ($user == "wstans") $ShowHR = true ;

  // Write out the intro.
  $pdf->ezText("<b>$intro</b>", 16, $center);
  $pdf->ezText("\n", 6, $center);
  $start_date = date('m/d/Y', strtotime($start_date));
  $end_date = date('m/d/Y', strtotime($end_date));
  $pdf->ezText("$start_date to $end_date", 12, $center);
  $pdf->ezText("\n", 6, $center);

  $row = pg_fetch_array($result, 0, PGSQL_ASSOC);

  if($report_type == "By Supervisor")
    $my_location = $row['supervisor'];
    else
    $my_location = $row['location'] ;
  // Init totals
  $struck_by_tot = 0; $slip_trip_tot = 0; $back_sprain_tot = 0;
  $others_sprain_tot = 0; $foreign_object_tot = 0; $puncture_tot = 0;
  $contusion_tot = 0; $caught_tot = 0; $burns_tot = 0; $misc_tot = 0; $CTD_tot = 0 ;
  $stat_info = array();
  $stat_type = array() ;
  $stat_class = array() ;
  $struck_by = 0; $slip_trip = 0; $back_sprain = 0; $others_sprain = 0;
  $foreign_object = 0; $puncture = 0; $contusion = 0; $caught = 0;
  $burns = 0; $misc = 0; $CTD = 0;
  $location_tot = 0;
  $overall_tot = 0;
    
  $MO = 0 ; $MLT = 0 ; $RO = 0 ;
  
  for($x = 0; $x < $rows; $x++){
    $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
    $overall_tot++;
    if($report_type == "By Supervisor") {
      $test = $row['supervisor'] ;
      $Category = "Supervisor" ;
    } else {
      $test = $row['location'] ;
      $Category = "Location" ;
    }

    if($my_location != $test){
      if ($my_location == "") $my_location="Other" ;
      $location_tot = $struck_by + $slip_trip + $back_sprain + $others_sprain + $foreign_object + $puncture + $contusion + $caught + $burns + $misc;
      array_push($stat_info, array('location'=>$my_location, 'struck_by'=>$struck_by, 'slip_trip'=>$slip_trip, 'back_sprain'=>$back_sprain, 'others_sprain'=>$others_sprain, 'foreign_object'=>$foreign_object, 'puncture'=>$puncture, 'contusion'=>$contusion, 'caught'=>$caught, 'burns'=>$burns,'CTD'=>$CTD, 'misc'=>$misc, 'tot'=>$location_tot));
      
      $struck_by = 0; $slip_trip = 0; $back_sprain = 0; $others_sprain = 0;
      $foreign_object = 0; $puncture = 0; $contusion = 0; $caught = 0;
      $burns = 0; $misc = 0; $CTD = 0;
      $location_tot = 0;
      
      $my_location = $test ;
    }
    if($row['classification'] == "Struck By"){
      $struck_by_tot += 1;
      $struck_by += 1;
    }
    else if($row['classification'] == "Slip, Trip & Fall"){
      $slip_trip_tot += 1;
      $slip_trip += 1;
    }
    else if($row['classification'] == "Sprain, Strain (Back)"){
      $back_sprain_tot += 1;
      $back_sprain += 1;
    }
    else if($row['classification'] == "Sprain, Strain (Others)"){
      $others_sprain_tot += 1;
      $others_sprain += 1;
    }
    else if($row['classification'] == "Foreign Objects (Eye)"){
      $foreign_object_tot += 1;
      $foreign_object += 1;
    }
    else if($row['classification'] == "Punctures, Cuts"){
      $puncture_tot += 1;
      $puncture += 1;
    }
    else if($row['classification'] == "Contusions"){
      $contusion_tot += 1;
      $contusion += 1;
    }
    else if($row['classification'] == "Caught In/By"){
      $caught_tot += 1;
      $caught += 1;
    }
    else if($row['classification'] == "Burns"){
      $burns_tot += 1;
      $burns += 1;
    }
    else if ($row['classification'] == "Cumulative Trauma Disorder") {
      $CTD_tot++ ;
      $CTD++ ;
    }
    else if($row['classification'] == "Misc"){
      $misc_tot += 1;
      $misc += 1;
    }
    if ($row['medical_code'] == "Medical Only" ) $MO++ ;
    if ($row['medical_code'] == "Medical/Leave") $MLT++ ;
    if ($row['medical_code'] == "Report Only") $RO++ ;
  }
  // Final line
  $location_tot = $struck_by + $slip_trip + $back_sprain + $others_sprain + $foreign_object + $puncture + $contusion + $caught + $burns + $CTD + $misc;
  array_push($stat_info, array('location'=>$my_location, 'struck_by'=>$struck_by, 'slip_trip'=>$slip_trip, 'back_sprain'=>$back_sprain, 'others_sprain'=>$others_sprain, 'foreign_object'=>$foreign_object, 'puncture'=>$puncture, 'contusion'=>$contusion, 'caught'=>$caught, 'burns'=>$burns, 'CTD'=>$CTD, 'misc'=>$misc, 'tot'=>$location_tot));
  // Totals
  array_push($stat_info, array('location'=>'Total', 'struck_by'=>$struck_by_tot, 'slip_trip'=>$slip_trip_tot, 'back_sprain'=>$back_sprain_tot, 'others_sprain'=>$others_sprain_tot, 'foreign_object'=>$foreign_object_tot, 'puncture'=>$puncture_tot, 'contusion'=>$contusion_tot, 'caught'=>$caught_tot, 'burns'=>$burns_tot, 'CTD'=>$CTD_tot, 'misc'=>$misc_tot, 'tot'=>$overall_tot));

  $YPos = $pdf->ezTable($stat_info, array('location'=>$Category, 'struck_by'=>'Struck By', 'slip_trip'=>'Slip/Trip/Fall', 'back_sprain'=>'Back Sprain', 'others_sprain'=>'Sprain (Other)', 'foreign_object'=>'Foreign Obj (Eye)', 'puncture'=>'Puncture/Cut', 'contusion'=>'Contusion', 'caught'=>'Caught In/By', 'burns'=>'Burn', 'CTD'=>'CTD', 'misc'=>'Misc', 'tot'=>'Total'), '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>730, 'showLines'=>2));

  $NewY = $YPos ;

  if($report_type == "Monthly Statistics" && $ShowHR){
    //Accident Type
    $NewY = $pdf->ezText("",12,$left) ; 

    $sql = "select * from saftey_stats where incident_date between '01/01/" . date("Y") . "' and '" . date("m/d/Y") . "' order by medical_code ;";
    $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($result);
    $Class = "" ;
    $iCount = 0 ;
    $iTotal = 0 ;
    for($x = 0; $x < $rows; $x++){
      $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
      if ($Class == $row["medical_code"]) {
        $iCount++ ;
      } else {
	if ($Class == "Medical Only") array_push($stat_type, array('Type'=>'Medical Only','MT'=>$MO,'YTD'=>$iCount,'YTDPct'=>($MO/$iCount*100) . "%")) ;
        if ($Class == "Medical/Leave") array_push($stat_type, array('Type'=>'Medical/Leave','MT'=>$MLT,'YTD'=>$iCount,'YTDPct'=>($MLT/$iCount*100) . "%")) ;
        if ($Class == "Report Only") array_push($stat_type, array('Type'=>'Report Only','MT'=>$RO,'YTD'=>$iCount,'YTDPct'=>($RO/$iCount*100) . "%")) ;
        $Class = trim($row["medical_code"]) ;
        $iCount = 1 ;
      }
      $iTotal++ ;
    }
    if ($Class == "Medical Only") array_push($stat_type, array('Type'=>'Medical Only','MT'=>$MO,'YTD'=>$iCount,'YTDPct'=>($MO/$iCount*100) . "%")) ;
    if ($Class == "Medical/Leave") array_push($stat_type, array('Type'=>'Medical/Leave','MT'=>$MLT,'YTD'=>$iCount,'YTDPct'=>($MLT/$iCount*100) . "%")) ;
    if ($Class == "Report Only") array_push($stat_type, array('Type'=>'Report Only','MT'=>$RO,'YTD'=>$iCount,'YTDPct'=>($RO/$iCount*100) . "%")) ;
    array_push($stat_type, array('Type'=>'Total', 'MT'=>($MO + $MLT + $RO), 'YTD'=>$iTotal, 'YTDPct'=>'')) ;
    $PosY = $pdf->ezTable($stat_type, array('Type'=>'Type', 'MT'=>'MT', 'YTD'=>'YTD', 'YTDPct'=>'YTD%'), 'Incident Type', array('xOrientation'=>'right', 'xPos'=>'left', 'showHeadings'=>1, 'shaded'=>0, 'showLines'=>1)) ;

    //Employee Classification
    $pdf->ezSetY($NewY) ;
    $sql = "select * from saftey_stats where incident_date between '$start_date' and '$end_date' order by employee_classification ;";
    $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($result);
    $Class = "" ;
    $iCount = 0 ;
    for($x = 0; $x < $rows; $x++){
      $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
      if ($Class == $row["employee_classification"]) {
	$iCount++ ;
      } else {
	if (strlen($Class) > 0) array_push($stat_class, array('Class'=>$Class, 'Value'=>$iCount)) ;
	$Class = $row["employee_classification"] ;
	$iCount = 1 ;
      }
    }
    array_push($stat_class, array('Class'=>$Class, 'Value'=>$iCount)) ;
    $NewY = $pdf->ezTable($stat_class, array('Class'=>'Class', 'Value'=>'MT'), 'Classification', array('xOrientation'=>'left', 'xPos'=>'right', 'showHeadings'=>1, 'shaded'=>0, 'showLines'=>1)) ;
    $NewY -= 12 ;
  }

  if($report_type == "Yearly Statistics" && $ShowHR){
    //Accident Type
    $NewY = $pdf->ezText("",12,$left) ;
    array_push($stat_type, array('Type'=>'Medical Only', 'Value'=>$MO)) ;
    array_push($stat_type, array('Type'=>'Medical/Leave', 'Value'=>$MLT)) ;
    array_push($stat_type, array('Type'=>'Report Only', 'Value'=>$RO)) ;
    array_push($stat_type, array('Type'=>'Total', 'Value'=>($MO + $MLT + $RO))) ;
    $PosY = $pdf->ezTable($stat_type, array('Type'=>'Type', 'Value'=>'YTD'), 'Incident Type', array('xOrientation'=>'right', 'xPos'=>'left', 'showHeadings'=>1, 'shaded'=>0, 'showLines'=>1)) ;

    //Employee Classification
    $pdf->ezSetY($NewY) ;
    $sql = "select * from saftey_stats where incident_date between '$start_date' and '$end_date' order by employee_classification ;";
    $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($result);
    $Class = "" ;
    $iCount = 0 ;
    for($x = 0; $x < $rows; $x++){
      $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
      if ($Class == $row["employee_classification"]) {
        $iCount++ ;
      } else {
        if (strlen($Class) > 0) array_push($stat_class, array('Class'=>$Class, 'Value'=>$iCount)) ;
        $Class = $row["employee_classification"] ;
        $iCount = 1 ;
      }
    }
    array_push($stat_class, array('Class'=>$Class, 'Value'=>$iCount)) ;
    $NewY = $pdf->ezTable($stat_class, array('Class'=>'Class', 'Value'=>'MT'), 'Classification', array('xOrientation'=>'left', 'xPos'=>'right', 'showHeadings'=>1, 'shaded'=>0, 'showLines'=>1)) ;

  }

//$pdf->ezText($YPos . ":" . $NewY,12,$left) ;

  //Notes
  if ($NewY < $YPos)
    $pdf->ezSetY($NewY) ;
  else
    $pdf->ezSetY($YPos-12) ;
  $pdf->ezText("",12,$left) ;
  $YPos = $pdf->ezText("Notes:",12,$left) ;
  $pdf->setStrokeColor(0,0,0,1);
  $pdf->line(30,$YPos - 2,750,$YPos - 2);

  $sql = "select * from saftey_stats where incident_date between '$start_date' and '$end_date' order by incident_date;";
  $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
  $rows = pg_num_rows($result);
  for($x = 0; $x < $rows; $x++){
    $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
    $line = date("m/d/Y", strtotime($row["incident_date"])) . " [" . $row["classification"] . "/" . $row["employee_classification"] . "]" ;
    $line .= " " . $row["notes"] ;
    $pdf->ezText($line,10,$left) ;
  }

  // Line at the bottom of the page
  $today = date('m/j/y');
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

  pg_close($connection);

  // redirect to a temporary PDF file instead of directly writing to the browser
  include("redirect_pdf.php");

?>
