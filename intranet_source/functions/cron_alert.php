<?
/*
*
*		Adam Walter, Apr 2015
*
*		include file for email checks against "run in sequence" crons
**********************************************************************/

function SendCronBreakNotify($scriptname, $rotation, $rf_conn){
/*
*	Checks to see if the php file run immediately before this one ($scriptname)
*	was the "correct" previous file for the shell script rotation named ($rotation)
*	If it was not, the script *still esecutes*, but it send a notify email
*	for IT to check the "broken" file.
*
*	This should be run at the start of a PHP script,
*	Immediately after the DB-definition.
********************************************************************************/
	$sql = "SELECT LAST_DONE_SCRIPT
			FROM CRON_ORDER_CHECK
			WHERE CRON_FILE = '".$rotation."'";
	$check = ociparse($rf_conn, $sql);
	ociexecute($check);
	ocifetch($check);
	if(ociresult($check, "LAST_DONE_SCRIPT") != $scriptname){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CRONALERT'";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "lstewart@port.state.de.us";
	//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
			$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", $scriptname, $mailSubject);
		$body = ociresult($email, "NARRATIVE");

		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		mail($mailTO, $mailSubject, $Content, $mailheaders);
	}
}

function UpdateCronBreakNotify($scriptname, $rotation, $rf_conn){
/*
*	Updates the cron-order table for ($rotation), so that the next
*	PHP in a sequential cron can see that ($scriptname) just finished.
*
*	This should be run as the VERY last line of any PHP script.
********************************************************************************/
	$sql = "UPDATE CRON_ORDER_CHECK
			SET LAST_DONE_SCRIPT = '".$scriptname."',
				LAST_CHANGED = SYSDATE
			WHERE CRON_FILE = '".$rotation."'";
	$check = ociparse($rf_conn, $sql);
	ociexecute($check);
}

function Cron_run_Check($script, $test){
/*
*	Checks to see if the .sh rotation ($script) is ready to be run or not.
*
*	If it is, it updates the DB to indicate it is now running, and
*	Passes a value back to the calling .sh file saying so.
********************************************************************************/
	if($test == "Y"){
		$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	} else {
		$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	}
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT READY_TO_RUN
			FROM CRON_ORDER_CHECK
			WHERE CRON_FILE = '".$script."'";
	$check = ociparse($rfconn, $sql);
	ociexecute($check);
	if(!ocifetch($check) || ociresult($check, "READY_TO_RUN") != "Y"){
		// do nothing
	} else {
		// check passed.  update, and send signal to calling shell
		$sql = "UPDATE CRON_ORDER_CHECK
				SET READY_TO_RUN = NULL,
					LAST_CHANGED = SYSDATE
				WHERE CRON_FILE = '".$script."'";
		$set = ociparse($rfconn, $sql);
		ociexecute($set);

		exit(5);
	}
}

function Cron_run_Assign($script, $test){
/*
*	Run at the very end of a .sh rotation ($script) to indicate
*	It is ready for re-run at the next cron activation.
*
********************************************************************************/
	if($test == "Y"){
		$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	} else {
		$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	}
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "UPDATE CRON_ORDER_CHECK
			SET READY_TO_RUN = 'Y',
				LAST_CHANGED = SYSDATE
			WHERE CRON_FILE = '".$script."'";
	$set = ociparse($rfconn, $sql);
	ociexecute($set);
}

?>