<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Temperature Change Request";
  $area_type = "SUPV";
/*
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
*/

  // 8/12/02 - Created by Seth Morecraft
  // Actually processes the form that was submitted via the form in entry.php
  // Sends a Mail message to $mailCC users and $mailTO defined below.
  // Also logs the entry to the LCS.TEMP_REQ table.

  // Set the variables.
  $subject = "Temperature Change Request";
  $mailTO = "bbarker@port.state.de.us";
//  $mailTO = "rwang@port.state.de.us";

  // Add people here (; separated list) 
  $mailCC = "rhorne@port.state.de.us,wstans@port.state.de.us,fvignuli@port.state.de.us,ddonofrio@port.state.de.us";
//  $mailCC = "rwang@port.state.de.us,ffitzgerald@port.state.de.us";

  $user = $HTTP_POST_VARS["user"];
  if($user == ""){
    printf("Username error!  Please go back to <a href=\"index.php\">Index</a> and re-login!<br /></body></html>");
    exit;
  }
  $mail = $HTTP_POST_VARS["mail"];
  if($mail == ""){
    printf("Username error!  Please go back to <a href=\"index.php\">Index</a> and re-login!<br /></body></html>");
    exit;
  }

  $whse = $HTTP_POST_VARS["whse"];
  $box = $HTTP_POST_VARS["box"];
  $temp = $HTTP_POST_VARS["temp"];
  $lTemp = $HTTP_POST_VARS["lTemp"];
  $hTemp = $HTTP_POST_VARS["hTemp"]; 
  $product = $HTTP_POST_VARS["product"];
  $duration = $HTTP_POST_VARS["duration"];
  $comments = str_replace("'", "`", $HTTP_POST_VARS["comments"]);
  $effect = $HTTP_POST_VARS["effect"];
  $time = $HTTP_POST_VARS["time"];
  $expired = $HTTP_POST_VARS["expired"];
  $exp_time = $HTTP_POST_VARS["exp_time"]; 
  $mail_sent = "Y";  // Will get defined below

  if ($lTemp ==""){
	$l_temp = "null";
  }else{
	$l_temp = $lTemp;
  }
  if ($hTemp == ""){
	$h_temp = "null";
  }else{
        $h_temp = $hTemp;
  }

  if (strtotime($effect." ".$time) < time()){
	$msg = "Effective Time can not earlier than current time.";
  }else if (strtotime($effect." ".$time) >= strtotime($expired." ".$exp_time)){
	$msg = "Expiration Time can not earlier than Effective Time";
  } 

  if ($msg <>""){
	header("Location: index.php?whse=$whse&box=$box&temp=$temp&effect=$effect&time=$time&expired=$expired&exp_time=&exp_time&product=$product&comments=$comments&msg=$msg");
	exit;
  }

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
?>
<p align="center">
<font size="3">
<p align="left">

<?
  $conn = ora_logon("LABOR@LCS", "LABOR");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);
  $today = date('m-j-y-H:i:s');
 
  $sql = "select req_id.nextval from dual";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$req_id = ora_getcolumn($cursor, 0);
  }
  
  $sql = "insert into TEMPERATURE_REQ (req_id, user_name, req_date, whse, box, new_set_point, product, est_duration, comments, mail_sent, effective, expired,low_temp,high_temp) values (".$req_id.", '" . $user . "', to_date('" . $today . "', 'MM-DD-YY-HH24:MI:SS'), '" . $whse . "', '" . $box . "', '" . $temp . "', '" . $product . "', '" . $duration . "', '" . $comments . "', '" . $mail_sent . "', to_date('".$effect." ".$time. "', 'mm/dd/yy hh:mi AM'), to_date('".$expired." ".$exp_time. "', 'mm/dd/yy hh:mi AM'),$l_temp, $h_temp)";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
 
	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
			WHERE DATE_RECEIVED IS NOT NULL AND
						((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
							OR
						 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
							OR
						 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10)
							OR
						(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
						AND ARRIVAL_NUM != '4321'
						AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse.$box."%'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$pallets = ociresult($stid, "THE_COUNT");

	$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING 
			WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$whse.$box."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse.$box."%') 
			AND DATE_RECEIVED IS NOT NULL 
			AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$pallets += ociresult($stid, "THE_SUM");

	$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING 
			WHERE UPPER(WAREHOUSE_LOCATION) = 'WING ".$whse."' 
			AND DATE_RECEIVED IS NOT NULL 
			AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$pallets += ociresult($stid, "THE_SUM");


	if($pallets > 0 && $temp == "Shut Down"){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WHSESHUTDOWNPALLETS'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$table_1 = "<br><table border=\"1\">
							<tr>
								<td>Customer</td>
								<td>Commodity</td>
								<td>Pallet ID</td>
								<td>Cases In House</td>
							</tr>";
		$sql = "SELECT PALLET_ID, CUSTOMER_NAME, COMMODITY_NAME, CT.COMMODITY_CODE, QTY_IN_HOUSE 
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP
				WHERE DATE_RECEIVED IS NOT NULL AND
							((CT.COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
								OR
							 ((CT.COMMODITY_CODE = '5310' OR CT.COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
								OR
							 (CT.COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10)
								OR
							(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
							AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
							AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
							AND ARRIVAL_NUM != '4321'
							AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse.$box."%'
							AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
							AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				ORDER BY CUSTOMER_NAME, COMMODITY_NAME";
//		echo $sql."<br>";
		$pallets = ociparse($rfconn, $sql);
		ociexecute($pallets);
		if(!ocifetch($pallets)){
			$table_1 .= "<tr><td colspan=\"4\" align=\"center\">No Scanned-Cargo Pallets In House for this Warehouse/box</td></tr>";
		} else {
			do {
				$table_1 .= "<tr>
								<td>".ociresult($pallets, "CUSTOMER_NAME")."</td>
								<td>".ociresult($pallets, "COMMODITY_CODE")."-".ociresult($pallets, "COMMODITY_NAME")."</td>
								<td>".ociresult($pallets, "PALLET_ID")."</td>
								<td>".ociresult($pallets, "QTY_IN_HOUSE")."</td>
							</tr>";
			} while(ocifetch($pallets));
			$table_1 .= "</table><br>";
		}

		$table_2 = "<br><table border=\"1\">
							<tr>
								<td>Customer</td>
								<td>Commodity</td>
								<td>BoL</td>
								<td>Mark</td>
								<td>Description</td>
								<td>QTY In House</td>
							</tr>";
		$sql = "SELECT decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE) THE_QTY, CUSTOMER_NAME, COMMODITY_NAME, CT.COMMODITY_CODE, CARGO_BOL, CARGO_MARK, CARGO_DESCRIPTION
				FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
				WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$whse.$box."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse.$box."%') 
				AND DATE_RECEIVED IS NOT NULL 
				AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )
				AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.LOT_NUM = CM.CONTAINER_NUM
				AND QTY_IN_HOUSE > 0";
		$pallets = ociparse($bniconn, $sql);
		ociexecute($pallets);
		if(!ocifetch($pallets)){
			$table_2 .= "<tr><td colspan=\"6\" align=\"center\">No Unscanned-Cargo In House for this Warehouse/box</td></tr>";
		} else {
			do {
				$table_2 .= "<tr>
								<td>".ociresult($pallets, "CUSTOMER_NAME")."</td>
								<td>".ociresult($pallets, "COMMODITY_CODE")."-".ociresult($pallets, "COMMODITY_NAME")."</td>
								<td>".ociresult($pallets, "CARGO_BOL")."</td>
								<td>".ociresult($pallets, "CARGO_MARK")."</td>
								<td>".ociresult($pallets, "CARGO_DESCRIPTION")."</td>
								<td>".ociresult($pallets, "THE_QTY")."</td>
							</tr>";
			} while(ocifetch($pallets));
		}
		$sql = "SELECT decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE) THE_QTY, CUSTOMER_NAME, COMMODITY_NAME, CT.COMMODITY_CODE, CARGO_BOL, CARGO_MARK, CARGO_DESCRIPTION
				FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
				WHERE UPPER(WAREHOUSE_LOCATION) = 'WING ".$whse."' 
				AND DATE_RECEIVED IS NOT NULL 
				AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )
				AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.LOT_NUM = CM.CONTAINER_NUM
				AND QTY_IN_HOUSE > 0";
		$pallets = ociparse($bniconn, $sql);
		ociexecute($pallets);
		if(!ocifetch($pallets)){
			// do nothing
		} else {
			$table_2 .= "<tr><td colspan=\"6\" align=\"center\">The following Unscanned cargo was found in Wing ".$whse.", but without a box indicated:</td></tr>";
			do {
				$table_2 .= "<tr>
								<td>".ociresult($pallets, "CUSTOMER_NAME")."</td>
								<td>".ociresult($pallets, "COMMODITY_CODE")."-".ociresult($pallets, "COMMODITY_NAME")."</td>
								<td>".ociresult($pallets, "CARGO_BOL")."</td>
								<td>".ociresult($pallets, "CARGO_MARK")."</td>
								<td>".ociresult($pallets, "CARGO_DESCRIPTION")."</td>
								<td>".ociresult($pallets, "THE_QTY")."</td>
							</tr>";
			} while(ocifetch($pallets));
		}
		$table_2 .= "</table><br>";

//		echo "tables:".$table_1.$table_2;

		$mailTO = ociresult($email, "TO");
		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}
		$mailheaders .= "Content-Type: text/html\r\n";

		$mailSubject = ociresult($email, "SUBJECT");

		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", $whse, $body);
		$body = str_replace("_1_", $box, $body);
		$body = str_replace("_2_", $table_1.$table_2, $body);
		$body = str_replace("_8_", $user, $body);
		$body = str_replace("_9_", $req_id, $body);

		$body = "<html><body>".$body."</body></html>";

//		echo "mailTO:".$mailTO;
//		echo "body:".$body;

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'INSTANTCRON',
						SYSDATE,
						'EMAIL',
						'WHSESHUTDOWNPALLETS',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		} else {
			echo "Mail routine Fail";
		}
	}
	







  if($mail_sent == "Y"){
    $body = "New Temperature Request ".$req_id."\nFor: " . $whse."-".$box ." \nBy: " .$user."\nRequest(°F)".$temp."\nStart: " . $effect." ".$time . "\nEnd: ". $expired." ".$exp_time . "\nCommodity: " . $product . "\nComments: " .$comments;

    $mailsubject = "Temperature Change Request #$req_id"; 
    $mailheaders = "From: " . $mail . "\n"; 
    $mailheaders .= "Cc: " . $mailCC . "\n"; 
    $mailheaders .= "Bcc: " . $mail . ",archive@port.state.de.us\n";
    $mailheaders .= "X-Mailer: PHP4 Mail Function on Apache/Linux\n"; 

    if (mail($mailTO, $mailsubject, $body, $mailheaders)) { 
       print("<center>The new change temperature request (#$req_id)  has been sent successfully to Randall Horne and Bob Barker!<br />If you need to make more requests, <a href=\"index.php\">Please Click Here!</a>.<br />\n"); 
    } 
    else { 
       $sql = "update temperature_req set mail_sent = 'N' where req_id = $req_id";
       $statement = ora_parse($cursor, $sql);
       ora_exec($cursor);
       print("Error: The message could not be sent!<br />Please send a message to <a href=\"hdadmin@port.state.de.us\">hdadmin@port.state.de.us</a> to report the error!\n"); 
    } 
  }
  else{
    print("Error: The message could not be sent!  Please go back and try again...");
  }

  ora_close($cursor);
  ora_logoff($conn);

?>
</p>
         <? include("pow_footer.php"); ?>
