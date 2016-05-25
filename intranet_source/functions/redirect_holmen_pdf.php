<?
   // STM- For Automatic E-Mail
   // The following variables need to be declared
   // $email == "on"
   // $customer_id and $ship_to_id = Customers you want to send the e-mail to
   // $email_user = E-Mail username  (From) XXX Cookie
   // $email_user_address = E-Mail user address (From) XXX Cookie
   // $email_file = File name for E-Mail
   // $email_file_desc = New File name for the E-mail

   include("mail_attach.php");

   // create a temporary PDF file instead of directly writing to the browser
   // to avoid a warning message of nonsecuar information sent with secuar information
   $pdfcode = $pdf->ezOutput();

   $dir = '/var/www/html/upload/pdf_files';
   if (!file_exists($dir)) {
     mkdir ($dir, 0775);
   }

   $fname = tempnam($dir . '/', 'PDF_') . '.pdf';
   $fp = fopen($fname, 'w');
   fwrite($fp, $pdfcode);
   fclose($fp);

   // Auto-Email code
   if ($email == "on") {
     // These are for the responses
     $sender_name = "Port of Wilmington";
     $sender_address = "edi@port.state.de.us";

     // get the connection descriptions if needed
     include("connect.php");
     $conn = ora_logon("PAPINET@$rf", "OWNER");
     $cursor = ora_open($conn);
     
     // get receiver information
     $stmt = "select * from emailing_profile where (customer_id = '$customer_id' or customer_id = '$ship_to_id') and status = 'ACTIVE' order by customer_id, contact";
     ora_parse($cursor, $stmt);
     ora_exec($cursor);
     ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

     $rows = ora_numrows($cursor);
     $receiver = "";

     if ($rows > 0) {
       // there is at least one row
       do {
	 $receiver_name = $row['CONTACT'];
	 $receiver_address = $row['EMAIL_ADDRESS'];

	 if ($receiver == "") {
	   $receiver .= "\"$receiver_name\" <$receiver_address>";
	 } else {
	   $receiver .= ", \"$receiver_name\" <$receiver_address>";
	 }
       } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

       $body = "Please find your $email_file_desc attached in PDF format sent from $sender_name <$sender_address>.  You will need Adobe Acrobat Reader available at http://www.adobe.com (free download).";
       
       // File should be named $email_file_desc for the user
       mail_attach($receiver, "\"$sender_name\" <$sender_address>", $email_file_desc, $body, $email_file, 
		   $pdfcode, 3, "application/pdf");
     }
   }

   // get file name and redirect to the file
   list($junk1, $junk2, $junk3, $junk4, $junk5, $junk, $filename) = split("/", $fname);
   header("Location: /upload/pdf_files/$filename");

   // remove files in this directory that are older than 5 mins
   if ($d = @opendir($dir)) {
      while (($file = readdir($d)) !== false) {
	 if (substr($file,0,4)=="PDF_"){
	    // then check to see if this one is too old
	    $ftime = filemtime($dir.'/'.$file);
	    if (time() - $ftime > 300){
	      unlink($dir.'/'.$file);
	    }
	 }
      }  
      
      closedir($d);
   }

?>
