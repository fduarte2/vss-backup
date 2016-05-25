<?
/*Stephen Adu, June 2009.
*  
*	This cron job is to send an email to Inventory Dept. anytime a receiver ID changed in the Database.
**************************************************************************************/


    $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
    if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);



	$sql = "SELECT PALLET_ID, OLD_RECEIVER_ID, NEW_RECEIVER_ID, TO_CHAR(CHANGED_TIME, 'DD-MON-YYYY HH:MI:SS') THE_TIME, USER_PROGRAM, USERNAME FROM RECEIVER_NOTIFY_CHG";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body = "Pallet ID: ".$row['PALLET_ID']."\r\nOld Receiver ID: ".$row['OLD_RECEIVER_ID']."\r\nNew Receiver ID: ".$row['NEW_RECEIVER_ID']."\r\nChanged Time: ".$row['THE_TIME']."\r\nUSER PROGRAM: ".$row['USER_PROGRAM']."\r\nUsername: ".$row['USERNAME']."\r\n";
		$mailSubject = "Receiver ID Changed";
		$mailheaders = "From: " . "No-Reply@port.state.de.us\r\n";
		$mailheaders .= "Bcc: " . "sadu@port.state.de.us\r\n";
		$mailTo = "bdempsey@port.state.de.us,martym@port.state.de.us,schapman@port.state.de.us\r\n";

		mail($mailTo, $mailSubject, $body, $mailheaders);

	}

	$sql = "DELETE FROM RECEIVER_NOTIFY_CHG";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
?>
