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

   $sql = "select whse, box from warehouse_location order by whse, box";
   $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
   $rows = pg_num_rows($result);

   for ($i = 0; $i < $rows; $i++){
	$row = pg_fetch_row($result, $i);
     	$whs = $row[0];
	$box = $row[1];
	
        $sql = "select REQ_ID, PRODUCT, NEW_SET_POINT, to_char(EFFECTIVE,'mm/dd/yy hh12:mi AM'),
		to_char(EXPIRED, 'mm/dd/yy hh12:mi AM'), USER_NAME, comments from temperature_req 
		where whse ='$whs' and box= $box and effective <= sysdate order by req_id desc ";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	
        if (ora_fetch($cursor)){
		$rId = ora_getcolumn($cursor, 0);
                $prod = ora_getcolumn($cursor, 1);
                $temp = ora_getcolumn($cursor, 2);
                $effect = ora_getcolumn($cursor, 3);
                $expired = ora_getcolumn($cursor, 4);
                $user = ora_getcolumn($cursor, 5);
                $comments = ora_getcolumn($cursor, 6);

		$expired_time = strtotime($expired);
		
                if ($expired_time < $alert_time){
			if ($expired_time >= $time){
				$subject = "Temperatue Request# $rId expire on $expired";
			}else{
				$subject = "Temperatue Request# $rId EXPIRED on $expired";
			}
			
			$mailTO = "rwang@port.state.de.us";
			$mailto = $user."@port.state.de.us,";
			$mailto .= "wstans@port.state.de.us";
			$mailto .= "ddonofrio@port.state.de.us";
        		$mailsubject = $subject;

        		$mailheaders = "From: MailServer@port.state.de.us\r\n";
//        		$mailheaders .= "Cc: ffitzgerald@port.state.de.us,";
			$mailheaders .= "ithomas@port.state.de.us,";
			$mailheaders .= "rhorne@port.state.de.us,";
			$mailheaders .= "bbarker@port.state.de.us\r\n";
			
			$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,rwang@port.state.de.us\r\n";

			$body = "Request #$rId \nBy: " . $user ."\nWharehouse: " . $whs . "  Box: " . $box . "\nNew Set Point: " . $temp . "\nEffective Date: " . $effect. "\nExpiration Date: ". $expired. "\nProduct Stored: " . $prod . "\nComments: " . $comments ;
			mail($mailto, $mailsubject, $body, $mailheaders);
                        //mail($mailTO, $mailsubject, $body, "");
 
		}
	}
   }
    
?>
