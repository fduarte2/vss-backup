<?

	$fd=fopen ("Instruction.doc", "r");
   	$File=fread($fd,filesize("Instruction.doc"));
   	fclose ($fd);
   	$File=chunk_split(base64_encode($File));

        $mailTO = "awalter@port.state.de.us";
//        $mailTO ="ithomas@port.state.de.us";

        $mailTo1 = "gbailey@port.state.de.us,";
//        $mailTo1 .="dniessen@port.state.de.us,";
        $mailTo1 .="fvignuli@port.state.de.us,";
        $mailTo1 .="ithomas@port.state.de.us,";
	$mailTo1 .="rhorne@port.state.de.us,";
        $mailTo1 .="skennard@port.state.de.us,";
        $mailTo1 .="parul@port.state.de.us,";
        $mailTo1 .="tkeefer@port.state.de.us";

        $mailsubject = "Hot List Items and Directors Meeting Agenda";
        //$mailheaders = "Errors: rwang@port.state.de.us\n";
        $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "lstewart@port.state.de.us\r\n,awalter@port.state.de.us";
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
        $Content.="Reminder to enter your items for tomorrow's Directors Meeting and to update the Hot List by 11 PM tonight.\r\n\r\n";
//		$Content.="To update the Director's hot list, follow these steps:\r\n";
//		$Content.="Log onto Intranet and go to Director's page.  Choose Agenda/Hot List from the menu\r\n";
//		$Content.="Then choose Hot List\r\n";
//		$Content.="Now you can update the hot list under your area\r\n";
//		$content.="(Create a new Entry or Edit prior entry).\r\n\r\n";
		$Content.="*** PLEASE NOTE THAT THIS A WEEKLY REMINDER THAT GOES OUT NO MATTER WHAT.  AS ALWAYS, CHECK YOUR EMAIL AND/OR YOUR CALENDAR FOR UPDATES, IF ANY. THANK YOU! ***";
//		$Content.="For a tutorial see attachment below: \r\n";
//        $Content.="\r\n\r\n";
//        $Content.="--MIME_BOUNDRY\r\n";
//        $Content.="Content-Type: application/word; name=\"Instruction.doc\"\r\n";
//        $Content.="Content-disposition: attachment\r\n";
//        $Content.="Content-Transfer-Encoding: base64\r\n";
//        $Content.="\r\n";
//        $Content.=$File;
//        $Content.="\r\n";
//        $Content.="--MIME_BOUNDRY--\n";

//        mail($mailTO, $mailsubject, $Content, $mailheaders);
        mail($mailTo1, $mailsubject, $Content, $mailheaders);


?>
