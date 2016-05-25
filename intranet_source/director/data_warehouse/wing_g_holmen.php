<?

// Adam Walter, June 2007.
// Due to a... rather hard to describe situation, the Dole segment
// of this php script is being excised to its own script.
// as such, I am modifying the code as necessary, and changing the filename
// from wing_g_inv_process.php to wing_g_holmen.php.
// if you wish to see the old file, it is at wing_g_inv_process.php.bak06182007
// I could probably pare this down further, but as it "works" right now,
// I won't.

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "WING G INVENTORY";
  $area_type = "DIRE";

  // Provides header / leftnav

  include("connect_data_warehouse.php");
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $sDate = $HTTP_POST_VARS[sDate];
  $eDate = $HTTP_POST_VARS[eDate];

  if($HTTP_SERVER_VARS["argv"][1] == "email"){
	$sDate = date('m/d/Y', mktime(0,0,0,date("m"), date("d"), date("Y") - 2));
	$eDate = date('m/d/Y');
  }

  $today = date('m/d/y h:i A');
  if ($HTTP_POST_VARS['submit']<>"" || $HTTP_SERVER_VARS["argv"][1] == "email"){
	$sTime = strtotime($sDate);
	$eTime = strtotime($eDate);

        $dayOfWeek = date('w', $sTime);

	$monday = date('m/d/Y', mktime(0,0,0,date("m", $sTime), date("d", $sTime) + 1 - $dayOfWeek, date("Y", $sTime)));
	
        $mon_time = strtotime($monday);

	while ($mon_time < $eTime){
        	$next_monday = date('m/d/Y',mktime(0,0,0,date("m", $mon_time),date("d", $mon_time)+7 ,date("Y", $mon_time)));

		$sql = "select sum(inv_qty) as inv from holmen_activity
			where activity_date ='$monday'";	
                $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
                $row = pg_fetch_row($result, 0);
		$holmen_inv = $row[0];

		$sql = "select sum(out_qty) as out, sum(in_qty)as in from holmen_activity 
			where activity_date >='$monday' and activity_date <'$next_monday'";
 	        $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
        	$row = pg_fetch_row($result, 0);
		$holmen_out = $row[0];
		$holmen_in = $row[1];
		if ($holmen_out == "")
			 $holmen_out = 0;
                if ($holmen_in == "")
			 $holmen_in  = 0;
		$holmen_end = $holmen_inv + $holmen_in - $holmen_out;
		$total = $holmen_end;
		
		$strOut .= "<tr>
				<td>$monday</td>
				<td>$holmen_inv</td>
                                <td>$holmen_out</td>
                                <td>$holmen_in</td>
                                <td>$holmen_end</td>
			    </tr>";


		$monday = $next_monday;
		$mon_time = strtotime($monday);
	}
        $table = "<TABLE border=1 CELLSPACING=1>";
                $table .= "<tr><td colspan=5 align=center><font size = 5><b>Holmen Paper Inventory</b></font><br/><b><i>Date: ".$sDate." to ".$eDate."</i> </b><br \>Printed on: ".$today."</td></tr>";
		$table .= "<tr>
                                <td valign=top rowspan=2><b>Day of Week</b></td>
                                <td align='center' colspan=4><b>Holmen</b></td>
                            </tr>";
                $table .= "<tr>
                                <td><b>Start</b></td>
				<td><b>Ship out</b></td>
				<td><b>Received</b></td>
                                <td><b>End</b></td>				
                            </tr>";
                $table .= "$strOut";

                $table .= "</table>";

                //export to excel
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-Disposition: attachment; filename=HolmenExport.xls");


	if($HTTP_SERVER_VARS["argv"][1]<>"email"){
                echo ("$table");
	}else{
      
        	$File=chunk_split(base64_encode($table));

		$mailTO = "awalter@port.state.de.us";
        	//$mailTO = "ithomas@port.state.de.us";

        	$mailTo1 = "tkeefer@port.state.de.us";

	        $mailsubject = "Holmen Paper Inventory";

        	$mailheaders = "From: MailServer@port.state.de.us\r\n";
		$mailheaders .= "Cc: jharoldson@port.state.de.us\r\n";
		$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us\r\n";

	        $mailheaders .= "MIME-Version: 1.0\r\n";
        	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        	$mailheaders .= "X-Mailer: PHP4\r\n";
	        $mailheaders .= "X-Priority: 3\r\n";
        	$maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
	        $maileaders  .= "This is a multi-part Content in MIME format.\r\n";


        	$Content="--MIME_BOUNDRY\r\n";
	        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        	$Content.="\r\n";

	        $Content.="--MIME_BOUNDRY\r\n";
        	$Content.="Content-Type: application/pdf; name=\"Holmen Paper.xls\"\r\n";
        	$Content.="Content-disposition: attachment\r\n";
        	$Content.="Content-Transfer-Encoding: base64\r\n";
        	$Content.="\r\n";
        	$Content.=$File;
        	$Content.="\r\n";
        	$Content.="--MIME_BOUNDRY--\n";


      		//mail($mailTO, $mailsubject, $Content, $mailheaders);
        	mail($mailTo1, $mailsubject, $Content, $mailheaders);
	}	
  }
?>
