<?
   include("mail_attach.php");
   // STM- For Automatic E-Mail
   // The following variables need to be declared
   // $email == "on"
   // $email_id = Customer ID you want to send the e-mail to
   // $email_user = E-Mail username  (From) XXX Cookie
   // $email_user_address = E-Mail user address (From) XXX Cookie
   // $email_file = File name for E-Mail
   // $email_file_desc = New File name for the E-mail
   $cc_address = "\"Lisa Treut\" <ltreut@port.state.de.us>";
   //$cc_address = "\"David\" <davidh@port.state.de.us>";

   // These are for the responses
   $super_email_user = "CCDS";
   $super_email_user_address = "ColdChain@port.state.de.us";

   // create a temporary PDF file instead of directly writing to the browser
   // to avoid a warning message of nonsecuar information sent with secuar information
   if($pdfcode_email == ""){
     $pdfcode_email = $pdf->ezOutput();
   }
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
   if($email == "on"){
     if($email_id == ""){
       ;  // Do nothing if there is no e-mail to customer id set!
     }
     else{
       if($host == ""){
         // get the connection descriptions if needed
         include("connect.php");
       }

       $email_conn = pg_connect("host=$host dbname=$db user=$dbuser");
       $sql = "select * from ccd_customer where customer_id = '$email_id'";
       $result = pg_query($email_conn, $sql);
       $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
       $email_option = $row['auto_email'];
       if($email_option == "t"){
         $customer_name = $row['customer_name'];
         $body = "Please find your $email_file_desc attached in PDF format sent from $super_email_user <$super_email_user_address>.\nYou will need Adobe Acrobat Reader available at http://www.adobe.com (free download).";
         $email_address = $row['email'];
         // Richmond....
         //if($special_email == "on"){
         //  $email_cc = "ccds@packersmarketing.com";
        // }
        // else{
         $email_cc = $row['email2'];
        // }
         if($email_cc != ""){
           // File should be named $email_file_desc for the user
           mail_attach("\"$customer_name\" <$email_address>, \"$customer_name\" <$email_cc>, \"$email_user\" <$email_user_address>", "\"$super_email_user\" <$super_email_user_address>", "(CCDS) $email_file_desc", $body, $email_file, $pdfcode_email, 3, "application/pdf");
         }
         else{
           mail_attach("\"$customer_name\" <$email_address>, \"$email_user\" <$email_user_address>", "\"$super_email_user\" <$super_email_user_address>", "(CCDS) $email_file_desc", $body, $email_file, $pdfcode_email, 3, "application/pdf");
         }
      }
      if($email_id2 != ""){ // We are printing a confirmation of Transfer
                               // This goes to 2 people!
        $sql = "select * from ccd_customer where customer_id = '$email_id2'";
        $result = pg_query($email_conn, $sql);
        $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
        $email_option = $row['auto_email'];
        if($email_option == "t"){
          $customer_name = $row['customer_name'];
          $body = "Please find your $email_file_desc attached in PDF format sent from $super_email_user <$super_email_user_address>.\nYou will need Adobe Acrobat Reader available at http://www.adobe.com (free download).";
          $email_address = $row['email'];
          $email_cc = $row['email2'];
          if($email_cc != ""){
            // File should be named $email_file_desc for the user
            mail_attach("\"$customer_name\" <$email_address>, \"$customer_name\" <$email_cc>, \"$email_user\" <$email_user_address>", "\"$super_email_user\" <$super_email_user_address>", "(CCDS) $email_file_desc", $body, $email_file, $pdfcode_email, 3, "application/pdf");
          }
          else{
            mail_attach("\"$customer_name\" <$email_address>, \"$email_user\" <$email_user_address>", "\"$super_email_user\" <$super_email_user_address>", "(CCDS) $email_file_desc", $body, $email_file, $pdfcode_email, 3, "application/pdf");
          }
        }
      }
    }
  }

   // get file name and redirect to the file
   list($junk1, $junk2, $junk3, $junk4, $junk5, $junk, $filename) = split("/", $fname);
   header("Location: /upload/pdf_files/$filename");

   // remove files in this directory that are older than 5 mins
   if ($d = @opendir($dir)) {
      while (($file = readdir($d)) !== false) {
//	 if (substr($file,0,4)=="PDF_"){
	 if ($file != "." && $file != ".."){
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
