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
  
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);

   $range = 2.5;
   $out_of_range = 0;
   $req_expired = 0;
   $not_set = 0;

   $run_date = $HTTP_POST_VARS["run_date"];
   if ($run_date ==""){
	$run_date = date('m/d/Y');
   }

   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   $deg = chr(248);


   $arrHeading1 = array('box'=>'','temp'=>'<b>Current</b>','req'=>'<b>Temperature Request</b>');
   $arrCol1 = array('box'=>array('width'=>29, 'justification'=>'center'),
                   'temp'=>array('width'=>95, 'justification'=>'center'),
                   'req'=>array('width'=>370, 'justification'=>'center'));

   $arrHeading = array('box'=>'<b>WING</b>','prod'=>'<b>Commodity</b>','temp'=>'<b>Current (Icetec)</b>','avg'=>'<b>24hr Avg (Icetec)</b>','actul_set'=>'<b> Set For (E&M)</b>','set_point'=>'<b>Request (OPS)</b>','effective'=>'<b>Start</b>','expired'=>'<b>End</b>','user'=>'<b>By</b>','rId'=>'<b>Req#</b>','comments'=>'<b>Requestor Comments</b>','status'=>'<b>E&M</b>');
   $arrCol = array('box'=>array('width'=>35, 'justification'=>'center'),
                   'prod'=>array('width'=>60, 'justification'=>'center'),
                   'temp'=>array('width'=>55, 'justification'=>'center'),
                   'actul_set'=>array('width'=>55, 'justification'=>'center'),
                   'avg'=>array('width'=>55, 'justification'=>'center'),
                   'set_point'=>array('width'=>50, 'justification'=>'center'),
                   'effective'=>array('width'=>42, 'justification'=>'center'),
		   'expired'=>array('width'=>42, 'justification'=>'center'),
                   'user'=>array('width'=>45, 'justification'=>'center'),
		   'rId'=>array('width'=>30, 'justification'=>'center'),
		   'comments'=>array('width'=>190,'justification'=>'left'),
		   'status'=>array('width'=>100,'justification'=>'left'));

   $heading = array();
   $heading1 = array();
   array_push($heading, $arrHeading);
   array_push($heading1, $arrHeading1);
 
   $sql = "select * from product_temp";
   ora_parse($cursor, $sql);
   ora_exec($cursor);

   $prodTemp = array();
   while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $prodTemp[$row['PRODUCT']]['LOW_ALERT']=$row['LOW_ALERT'];
        $prodTemp[$row['PRODUCT']]['HIGH_ALERT']=$row['HIGH_ALERT'];
        $prodTemp[$row['PRODUCT']]['LOW_WARNING']=$row['LOW_WARNING'];
        $prodTemp[$row['PRODUCT']]['HIGH_WARNING']=$row['HIGH_WARNING'];
   }

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

	if (strtotime($run_date) <= strtotime('09/12/2005 23:00')){
  		$sql = "select l.whse, l.box, t.temperature, r.actual_set_point, r.set_point, r.effective_date, r.expiration_date, r.commodity, r.user_name, r.comments, r.req_id, r.low_temp, r.high_temp, t.avg_temp, r.status from ((warehouse_location l left outer join warehouse_temperature t on t.date = '$rDate' and t.location = l.whse||l.old_box||'TEMP') left outer join temperature_req r on r.run_date = '$rDate' and r.whse = l.whse and r.box = l.box)  order by l.whse, l.box";
	}else{
		$sql = "select l.whse, l.box, t.temperature, r.actual_set_point, r.set_point, r.effective_date, r.expiration_date, r.commodity, r.user_name, r.comments, r.req_id, r.low_temp, r.high_temp, t.avg_temp, r.status from ((warehouse_location l left outer join warehouse_temperature t on t.date = '$rDate' and t.location = l.whse||l.box||'TEMP') left outer join temperature_req r on r.run_date = '$rDate' and r.whse = l.whse and r.box = l.box)  order by l.whse, l.box";
	}
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
		$actual_set_point = $row[3];
		$set_point = $row[4];
		$effective = $row[5];
                if ($effective <>"") $effective = date('m/d/y', strtotime($row[5]));
                $expired = $row[6];
                if ($expired <>"") {
			$expired = date('m/d/y', strtotime($row[6]));
			$expired_time =  strtotime($row[6]);
		}
		$prod = $row[7];
		$user = $row[8];
		$comments = $row[9];
		$comments = substr($comments, 0, 45);
                $rId = $row[10];
		$low = $row[11];
		$high = $row[12];
		$avg_temp = $row[13];
		$status = $row[14];

		if ($avg_temp <>"") $avg_temp = number_format($avg_temp, 1);

                if ($temp =="")
                        $temp = "Unavailable";
                if ($avg_temp =="")
                        $avg_temp = "Unavailable";


		if ($avg_temp <> "" && $avg_temp <> "Unavailable" && $set_point <>"Shut Down"){
			if (($avg_temp-$set_point) > $prodTemp[$prod]['HIGH_ALERT'] ||
                            ($set_point - $avg_temp) > $prodTemp[$prod]['LOW_ALERT']) {
                                $out_of_range ++;
                                //$avg_temp = $avg_temp."*";
                                if ($strOut_of_range == ""){
                                        $strOut_of_range .= $whse."-".$box;
                                }else{
                                        $strOut_of_range .= ", ".$whse."-".$box;
                                }
                                $box = $box."*";
			}
		}
/*
			if (($prod=="Meat" && ($avg_temp>10 || $avg_temp<0)) || ($prod <>"Meat" && abs($avg_temp-$set_point)>$range)) {
                                $out_of_range ++;
                                //$avg_temp = $avg_temp."*";
                                if ($strOut_of_range == ""){
                                        $strOut_of_range .= $whse."-".$box;
                                }else{
                                        $strOut_of_range .= ", ".$whse."-".$box;
                                }
				$box = $box."*";
			}

		}

		if ($temp <>"" && $prod <>"" && $prod <>"Box Empty"){
			$sql = "select * from product_temp 
				where product = '$prod' and low_temp <= $temp and high_temp >= $temp";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if (ora_fetch($cursor) == false){
				$out_of_range ++; 
				$temp = $temp."*";
				if ($strOut_of_range == ""){
					$strOut_of_range .= $whse."-".$box;
				}else{
					$strOut_of_range .= ", ".$whse."-".$box;
				}
			}
                }
*/		
		if ($expired_time < time()){
			if ($actual_set_point <>"Repair"){
				$req_expired ++;
				if ($strExpired == ""){
					$strExpired .= $whse."-".$box;
				}else{
					$strExpired .= ", ".$whse."-".$box;
				}
			}
			$set_point = "Expired";
		}

		if ($set_point == ""){
			if ($strNoReq == ""){
				$strNoReq .= $whse."-".$box;
                        }else{
                                $strNoReq .= ", ".$whse."-".$box;
                        }

			$set_point = "None";
			$effective = "None";
			$expired = "None";
			$prod = "None";
			$user = "None";
			$comments = "";
                }

 		if ($actual_set_point == ""){
			$not_set ++;
			$actual_set_point = "Not Set";
		}

		if ($whse <> $pre_whse) {
			$j++;
			$pre_whse = $whse;
		}
		if ($actual_set_point == "Repair"){
                        $set_point = "N/A";
                        $temp = "Repair";
                        $avg_temp = "Repair";
                }

		if ($temp =="")
			$temp = "Unavailable";
                if ($avg_temp =="")
                        $avg_temp = "Unavailable";
 
                if ($prod == "" && $set_point <> "None" ) 
			$prod = "Empty";
		
		if ($box <> "Unknown"){
                        $comm = ucwords(strtolower($comm));
                        $pComm = ucwords(strtolower($pComm));
			array_push($data[$j], array('whse'=>$whse, 'box'=>$whse.'-'.$box, 'temp'=>$temp, 'avg'=>$avg_temp,'actul_set'=>$actual_set_point, 'set_point'=>$set_point, 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'status'=>$status));
		}
	}
   }
   ora_close($cursor);
   ora_logoff($conn);   

   if ($out_of_range == 0 && $req_expired == 0 && $not_set == 0){
	$subject = "No Temperature Alert";
   }else{
	$subject = "TEMP ALERT: ";
	if ($out_of_range == 1){
		$subject .= "Out-of-Range: 1 Box. ";
	}else if ($out_of_range > 1){
		$subject .= "Out-of-Range: $out_of_range Boxes. ";
	}
	if ($req_expired  == 1){
		$subject .= "Expired: 1 Box. ";
	}else if ($req_expired > 1){
		$subject .= "Expired: $req_expired Boxes. ";
	}
	/* commented out by Adam Walter 2/27/06 */
	//	if ($not_set == 1){
	//		$subject .= "Not Set: 1 Box";
	//	}else if ($not_set > 1){
	//		$subject .= "Not Set: $not_set Boxes";
	//	}
   }
	
//   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   	// initiate the pdf writer
   	include 'class.ezpdf.php';
//   	$pdf = new Cezpdf('letter','portrait');
        $pdf = new Cezpdf('letter','landscape');

   	$pdf->ezSetMargins(10,10,20,20);
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
  	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	// Write out the intro.
   	// Print Receiving Header
//   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<b>Daily Temperature Status</b>", 20, $center);
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<i>". date('m/d/Y h:i A', strtotime($rDate))." </i>", 10, $center);

   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
/*
        $pdf->ezSetDy(-5);
        $pdf->ezText("Expected-what supervisor expects to be in the box.", 8, $right);
        $pdf->ezText("Actual-what is in the box per the inventory system.", 8, $right);
        $pdf->ezText("Possible-cargo coded only at warehouse level-box number not recorded.", 8, $right);
*/


	for ($i = 0; $i < 5; $i++) {
                $pdf->ezSetDy(-10);

/*
		$pdf->ezSetDy(-15);
                $pdf->ezText("<b>".$warehouse[$i]."</b>", 8, $left);
		$pdf->ezSetDy(-2);
*/
		if ($i == 0){
//        		$pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>494,'fontSize'=>8,'cols'=>$arrCol1));
        		$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>494,'fontSize'=>8,'cols'=>$arrCol));

		}
/*
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

                        $pdf->line(59,663,553,663);
                        $pdf->line(88,649,553,649);
                        $pdf->line(59,663,59,636);
                        $pdf->line(88,663,88,636);
                        $pdf->line(183,663,183,636);
                        $pdf->line(553,663,553,636);
                        
                        $pdf->line(133,649,133,636);
                        $pdf->line(233,649,233,636);
                        $pdf->line(313,649,313,636);
                        $pdf->line(393,649,393,636);
                        $pdf->line(483,649,483,636);
*/
//               	$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>575,'fontSize'=>8,'cols'=>$arrCol));

  		$pdf->ezTable($data[$i], $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>575,'fontSize'=>8,'cols'=>$arrCol));
	}
	if ($out_of_range > 0)
	//	$pdf->ezText("<i>*Out-of-Range</i> (Temperature ragne: Meat 0-10°F; Other Requested temp±".$range."°F)", 8, $left);
/*
   	$today = date('m/j/y');
   	$format = "Port of Wilmington, Printed on:" . $today;  
   	$pdf->line(20,40,590,40);

  	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	//$pdf->line(70,822,578,822);
   	$pdf->addText(25,34,6, $format);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');
*/
   $arg = $HTTP_SERVER_VARS["argv"][1];
   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   	$pdf->ezStream();
   }else{
       	// output
      	$pdfcode = $pdf->ezOutput();

 	$File=chunk_split(base64_encode($pdfcode));


  	$mailTO = "lstewart@port.state.de.us";
        $mailTO = "rwang@port.state.de.us";

	$mailTo1 = "gbailey@port.state.de.us,";
	$mailTo1 .="rhorne@port.state.de.us,";
	$mailTo1 .="bbarker@port.state.de.us";
         
   	$mailsubject = $subject;

   	$mailheaders = "From: MailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: ithomas@port.state.de.us,tkeefer@port.state.de.us,wstans@port.state.de.us,jharoldson@port.state.de.us,";
        $mailheaders .= "btyler@port.state.de.us,";
        $mailheaders .= "ddonofrio@port.state.de.us,";
        $mailheaders .= "dgamble@port.state.de.us,";
        $mailheaders .= "gharris@port.state.de.us,";
// 3-20-22006 edited by Adam Walter $mailheaders .= "ffitzgerald@port.state.de.us,";
// 2-17-2006 edited by DHC        $mailheaders .= "mcutler@port.state.de.us,";
        $mailheaders .= "michaelp@port.state.de.us,";
        $mailheaders .= "paulk@port.state.de.us,";
// 2-17-2006 edited by DHC        $mailheaders .= "rbetts@port.state.de.us,";
        $mailheaders .= "rcorbin@port.state.de.us,";
        $mailheaders .= "drobinson@port.state.de.us,";
        $mailheaders .= "rsmith@port.state.de.us\r\n";

    	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,lstewart@port.state.de.us\r\n";

       	$mailheaders .= "MIME-Version: 1.0\r\n";
      	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
    	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
       	$mailheaders .= "X-Mailer: PHP4\r\n";
    	$mailheaders .= "X-Priority: 3\r\n";
       	$mailheaders .= "Return-Path: MailServer@port.state.de.us\r\n";
     	$mailheaders .= "This is a multi-part Contentin MIME format.\r\n";


       	$Content="--MIME_BOUNDRY\r\n";
       	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
     	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
       	$Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
	if ($strOut_of_range <>"")
		$Content .= "Engineering Alert:  Temp Out of Range in ". $strOut_of_range ."\r\n\r\n";
        if ($strExpired <>"")
		$Content .= "Operations Alert:  Request Expired for ". $strExpired."\r\n";
	if ($strNoReq <>"")
		$Content .= "                   No Request for ". $strNoReq;
        $Content.="\r\n";
       	$Content.="--MIME_BOUNDRY\r\n";
       	$Content.="Content-Type: application/pdf; name=\"Daily Temperature Status.pdf\"\r\n";
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
