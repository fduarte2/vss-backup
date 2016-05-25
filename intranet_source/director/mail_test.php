<?	
        $mailTO ="psheman@port.state.de.us";
        $mailsubject = "test";

        $mailheaders  = "From: " . "MailServer@port.state.de.us\r\n";
//        $mailheaders .= "Bcc: " . "rwang@port.state.de.us\r\n";
/*        $mailheaders .= "MIME-Version: 1.0\r\n";
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
        $Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
 //       $Content.="Content-Type: application/pdf; name=\"Agenda.pdf\"\r\n";
 //       $Content.="Content-disposition: attachment\r\n";
 //       $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
  
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";
*/
	
	$Content = "This is a test email.";
        mail($mailTO, $mailsubject, $Content, $mailheaders);



?>
