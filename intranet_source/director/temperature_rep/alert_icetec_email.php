<?
   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
     	include("pow_session.php");
   	$user = $userdata['username'];
   }

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
        printf("Error logging on to the Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("</body></html>");
        exit;
   }
   $cursor = ora_open($conn);

   include("connect_data_warehouse.php");
   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
   }

   $time = time();
   $alert_time = mktime(0,0,0,date('m'), date('d')+3, date('y'));

   $run_date = date('m/d/y');
   $range = 7.5;
   $meat_range = 13;

   $sql = " select Max(date) from warehouse_temperature where date >='$run_date' and date <='$run_date"." 23:59:00'";

   $result = pg_query($pg_conn, $sql);
   $rows = pg_num_rows($result);
   if ($rows >0){
        $row = pg_fetch_row($result, 0);
        $rDate = $row[0];

   	$sql = "select l.whse, l.box, t.temperature, r.actual_set_point, r.set_point, t.avg_temp,  r.commodity from ((warehouse_location l left outer join warehouse_temperature t on t.date = '$rDate' and t.location = l.whse||l.old_box||'TEMP') left outer join temperature_req r on r.run_date = '$rDate' and r.whse = l.whse and r.box = l.box)  order by l.whse, l.box";
   	$result = pg_query($pg_conn, $sql);
        $rows = pg_num_rows($result);
        $j = 0;
        $pre_whse = "A";
        for ($i = 0; $i < $rows; $i++){
                $row = pg_fetch_row($result, $i);
                $whse = $row[0];
                $box = $row[1];
                $temp = $row[2];
                if ($temp <>"") $temp = number_format($temp, 1);
		$actual_set_point = $row[3];
                $set_point = $row[4];
                $avg_temp = $row[5];
                if ($avg_temp <>"") $avg_temp = number_format($avg_temp, 1);
		$prod = $row[6];

                if ($temp =="" || $temp == "0.0")
                        $temp = "Unavailable";
                if ($avg_temp =="" || $avg_temp == "0.0")
                        $avg_temp = "Unavailable";

		$alert = false;
		
		if ($actual_set_point <> "Repair" && ($avg_temp == "Unavailable" || $temp == "Unavailable")){
			if ($unvStr == ""){
				$unvStr = "  ".$whse.$box;
			}else{
				$unvStr .= ", ".$whse.$box;
			}
		}else if ($prod == "Meat" && $avg_temp > $meat_range){
			$alert = true;
		}else if ($prod <> "Meat" && abs($avg_temp - $set_point)> $range){
			$alert = true; 
		}
		if ($set_point == "Shut Down" || $actual_set_point == "Repair"){
			$alert = false;
		}
		if ($alert == true){
			$alertStr .= "  ".$whse.$box." - Request ".$set_point.", current was ".$temp.", and 24hr average was ".$avg_temp."\r\n";
		}
	}
   } 

   if ($unvStr <>"" ||$alterStr <>""){
	if ($unvStr <>"" && $alertStr <>""){
		$i = "1.";
		$j = "2.";
	}else{
		$i = "";
		$j = "";
	}

	$body = "Mike,\r\n\r\n";
	$body .= "Concerns from what your system responded today at ".date('h:i A', strtotime($rDate))."\r\n\r\n";
 
	if ($unvStr <>""){
		$body .= $i."Your system did not provided the Current Temperature for the following boxes:\r\n";
		$body .= $unvStr."\r\n";
		$body .= "Is something wrong?  Please fix and let me know.\r\n\r\n";
	}

	if ($alertStr <>""){
		$body .= $j."The following boxes are far from the requested temperature:\r\n\r\n";
		$body .= $alertStr."\r\n\r\n";
		$body .= "Is something wrong with the sensors?  Or were these boxes actually that warm?\r\n\r\n";
		$body .= "Thanks.\r\n\r\n";
		$body .= "Inigo";
	}



	$mailTO1 = "ithomas@port.state.de.us";

	$mailTO ="mwebster@icetec.biz";
	$mailheaders = "From: " . "ithomas@port.state.de.us\r\n";
//        $mailheaders .= "Cc: " . "chaase@icetec.biz,vanjab@icetec.biz,rhorne@port.state.de.us,ithomas@port.state.de.us\r\n";
        $mailheaders .= "Bcc: rwang@port.state.de.us"; 
//	$mailheaders .= "Content-Type: text/html\r\n";

      	$mailsubject = "Temperature Alert";

        mail($mailTO1, $mailsubject, $body, $mailheaders);
//	mail($mailTO, $mailsubject, $body, $mailheaders);


   }
pg_close($pg_conn );
    
?>
