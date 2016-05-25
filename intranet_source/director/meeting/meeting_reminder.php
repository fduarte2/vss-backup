<?

        $mailTO = "lstewart@port.state.de.us";
        $mailTO ="ithomas@port.state.de.us";

        $mailTo1 = "gbailey@port.state.de.us,";
//        $mailTo1 .="dniessen@port.state.de.us,";
        $mailTo1 .="fvignuli@port.state.de.us,";
        $mailTo1 .="ithomas@port.state.de.us,";
	$mailTo1 .="rhorne@port.state.de.us,";
        $mailTo1 .="skennard@port.state.de.us,";
        $mailTo1 .="parul@port.state.de.us,";
        $mailTo1 .="tkeefer@port.state.de.us";

        $mailsubject = "Automated Reminder - Weekly Board Notes";
        //$mailheaders = "Errors: rwang@port.state.de.us\n";
        $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "lstewart@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        $mailheaders .= "X-Mailer: PHP4\r\n";
        $mailheaders .= "X-Priority: 3\r\n";
        $mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        $mailheaders  .= "This is a multi-part Content in MIME format.\r\n";


        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        $Content.="Reminder!!!!   Weekly Board input is due by 12:00 noon on Thursdays............\r\n\r\n";
	$Content.="Thank you.\r\n\r\n*** PLEASE NOTE THAT THIS IS AN AUTOMATED REMINDER THAT GOES OUT NO MATTER WHAT.  AS ALWAYS, CHECK YOUR EMAIL AND/OR YOUR CALENDAR FOR UPDATES, IF ANY. THANK YOU! ***";


//        mail($mailTO, $mailsubject, $Content, $mailheaders);
        mail($mailTo1, $mailsubject, $Content, $mailheaders);


?>
