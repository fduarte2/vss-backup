<?

if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   include("pow_session.php");
   $user = $userdata['username'];
}
   // initiate the pdf writer
//   include 'class.ezpdf.php';
//   $pdf = new Cezpdf('letter','portrait');


   include("connect_data_warehouse.php");
 
   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
   }
  
   $run_date = $HTTP_POST_VARS["run_date"];
   if ($run_date ==""){
	$run_date = date('m/d/Y');
   }

   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   $deg = chr(248);


   $arrHeading1 = array('box'=>'<b>Box</b>','temp'=>'<b>Current</b>','req'=>'<b>Temperature Request</b>', 'comm'=>'<b>Warehouse Commodity</b>');
   $arrCol1 = array('box'=>array('width'=>29, 'justification'=>'center'),
                   'temp'=>array('width'=>90, 'justification'=>'center'),
                   'req'=>array('width'=>185, 'justification'=>'center'),
                   'comm'=>array('width'=>270,'justification'=>'center'));

   $arrHeading = array('box'=>'','temp'=>'<b>Temp(°F)</b>','epoint'=>'<b>Set Point</b>','set_point'=>'<b>Set Point</b>', 'effective'=>'<b>As of</b>','user'=>'<b>By</b>','prod'=>'<b>Expected</b>', 'comm'=>'<b>Actual</b>', 'pComm'=>'<b>Possible</b>');
   $arrCol = array('box'=>array('width'=>29, 'justification'=>'center'),
                   'temp'=>array('width'=>45, 'justification'=>'center'),
                   'epoint'=>array('width'=>45, 'justification'=>'center'),
                   'set_point'=>array('width'=>50, 'justification'=>'center'),
                   'effective'=>array('width'=>45, 'justification'=>'center'),
                   'prod'=>array('width'=>70, 'justification'=>'center'),
                   'user'=>array('width'=>90, 'justification'=>'center'),
                   'comm'=>array('width'=>100,'justification'=>'center'),
                   'pComm'=>array('width'=>100,'justification'=>'center'));

   $heading = array();
   $heading1 = array();
   array_push($heading, $arrHeading);
   array_push($heading1, $arrHeading1);
 
   //get data
  $data[0] = array();
  $data[1] = array();
  $data[2] = array();
  $data[3] = array();
  $data[4] = array();

  $warehouse[0] = "WING A";
  $warehouse[1] = "WING B";
  $warehouse[2] = "WING C";
  $warehouse[3] = "WING D";
  $warehouse[4] = "WING E";

  $sql = " select Max(date) from warehouse_temperature where date >='$run_date' and date <='$run_date"." 23:59:00'";


  $result = pg_query($pg_conn, $sql);
  $rows = pg_num_rows($result);
  if ($rows >0){
	$row = pg_fetch_row($result, 0);
        $rDate = $row[0];        

  	$sql = "select l.whse, l.box, t.temperature, r.set_point, r.effective_date, r.commodity, r.user_name, c.commodity, c.qty, t.setpoint from (((warehouse_location l left outer join warehouse_temperature t on t.date = '$rDate' and t.location = l.whse||l.old_box||'TEMP') left outer join temperature_req r on r.run_date = '$rDate' and r.whse = l.whse and r.box = l.box) left outer join warehouse_commodity c on c.run_date = '$rDate' and c.location = l.whse||l.box ) order by l.whse, l.box";

       	$result = pg_query($pg_conn, $sql);
 	$rows = pg_num_rows($result);
        $j = 0;
	$pre_whse = "A";
	for ($i = 0; $i < $rows; $i++){
		$row = pg_fetch_row($result, $i);
		$whse = $row[0];
		$box = $row[1];
		$temp = $row[2];
		if ($temp <>"") $temp = number_format($row[2], 1);
		$set_point = $row[3];
		$effective = $row[4];
                if ($effective <>"") $effective = date('m/d/y', strtotime($row[4]));
		$prod = $row[5];
		$user = $row[6];
		$comm = $row[7];
		$qty = number_format($row[8],0);
		$setpoint = $row[9];
		if ($qty == 0) $qty = "";

		if ($j == 0 || $whse <> $pre_whse){
			$sql = "select commodity from warehouse_commodity where run_date = '$rDate' and  location = '".$whse."Unknown'";
			$result2 = pg_query($pg_conn, $sql);
        		$rows2 = pg_num_rows($result2);
                        if ($rows2 > 0){
                                $row2 = pg_fetch_row($result2, 0);
				$pComm = $row2[0];
			}else{
				$pComm = "";
			}

		}
		if ($whse <> $pre_whse) {
			$j++;
			$pre_whse = $whse;
		}
		if ($box <> "Unknown"){
                        $comm = ucwords(strtolower($comm));
                        $pComm = ucwords(strtolower($pComm));
			array_push($data[$j], array('whse'=>$whse, 'box'=>$box, 'temp'=>$temp, 'epoint'=>$setpoint, 'set_point'=>$set_point, 'effective'=>$effective, 'prod'=>$prod,'user'=>$user, 'comm'=>$comm, 'qty'=>$qty, 'pComm'=>$pComm));
		}
	}
   }
   

//   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   	// initiate the pdf writer
   	include 'class.ezpdf.php';
   	$pdf = new Cezpdf('letter','portrait');

   	$pdf->ezSetMargins(40,40,30,20);
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
  	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	// Write out the intro.
   	// Print Receiving Header
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<b>Daily Temperature Log</b>", 24, $center);
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<i>As of ". date('m/d/Y h:i A', strtotime($rDate))." </i>", 12, $center);

   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
        $pdf->ezSetDy(-5);
        $pdf->ezText("Expected-what supervisor expects to be in the box.", 8, $right);
        $pdf->ezText("Actual-what is in the box per the inventory system.", 8, $right);
        $pdf->ezText("Possible-cargo coded only at warehouse level-box number not recorded.", 8, $right);



	for ($i = 0; $i < 5; $i++) {
		$pdf->ezSetDy(-15);
                $pdf->ezText("<b>".$warehouse[$i]."</b>", 8, $left);
		$pdf->ezSetDy(-2);
		if ($i == 0){
        		$pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>510,'fontSize'=>8,'cols'=>$arrCol1));
        		$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>510,'fontSize'=>8,'cols'=>$arrCol));

		}
                        $pdf->line(19,630,593,630);
                        $pdf->line(48,616,593,616);
                        $pdf->line(19,630,19,602);
                        $pdf->line(48,630,48,602);
                        $pdf->line(138,630,138,602);
                        $pdf->line(323,630,323,602);
                        $pdf->line(593,630,593,602);
                        $pdf->line(93,616,93,602);
                        $pdf->line(188,616,188,602);
                        $pdf->line(233,616,233,602);
                        $pdf->line(393,616,393,602);
                        $pdf->line(493,616,493,602);
/*
                        $pdf->line(40,658,571,658);
                        $pdf->line(116,644,571,644);
                        $pdf->line(41,658,41,630);
                        $pdf->line(71,658,71,630);
                        $pdf->line(116,658,116,630);
                        $pdf->line(301,658,301,630);
                        $pdf->line(571,658,571,630);
                        $pdf->line(166,644,166,630);
                        $pdf->line(211,644,211,630);
                        $pdf->line(371,644,371,630);
                        $pdf->line(471,644,471,630);
*/
  		$pdf->ezTable($data[$i], $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>8,'cols'=>$arrCol));
	}

   	$today = date('m/j/y');
   	$format = "Port of Wilmington, Printed on:" . $today;  
   	$pdf->line(50,40,560,40);

  	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	//$pdf->line(70,822,578,822);
   	$pdf->addText(60,34,6, $format);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   	$pdf->ezStream();
   }else{

       	// output
      	$pdfcode = $pdf->ezOutput();

 	$File=chunk_split(base64_encode($pdfcode));


  	$mailTO = "rwang@port.state.de.us";

	$mailTo1 = "gbailey@port.state.de.us,";
	$mailTo1 .="rhorne@port.state.de.us,";
	$mailTo1 .="bbarker@port.state.de.us";
         
   	$mailsubject = "Daily Temperature Log";

   	$mailheaders = "From: MailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: ithomas@port.state.de.us,wstans@port.state.de.us,jharoldson@port.state.de.us\r\n";
    	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,rwang@port.state.de.us\r\n";
       	$mailheaders .= "MIME-Version: 1.0\r\n";
      	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
    	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
       	$mailheaders .= "X-Mailer: PHP4\r\n";
    	$mailheaders .= "X-Priority: 3\r\n";
       	$maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
     	$maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


       	$Content="--MIME_BOUNDRY\r\n";
       	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
     	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
       	$Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
       	$Content.="--MIME_BOUNDRY\r\n";
       	$Content.="Content-Type: application/pdf; name=\"Daily Temperature Log.pdf\"\r\n";
      	$Content.="Content-disposition: attachment\r\n";
      	$Content.="Content-Transfer-Encoding: base64\r\n";
      	$Content.="\r\n";
    	$Content.=$File;
       	$Content.="\r\n";
      	$Content.="--MIME_BOUNDRY--\n";


//     	mail($mailTO, $mailsubject, $Content, $mailheaders);
    	mail($mailTo1, $mailsubject, $Content, $mailheaders);
 
}
pg_close($pg_conn );
?>
