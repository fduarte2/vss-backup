<? 
	
	$file="/u01/html/upload/cubes/inventory/" . date('Y-m') . ".cube";


        $mailTo = "hdadmin@port.state.de.us,awalter@port.state.de.us";
        $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
        $mailheaders .= "Content-Type: text/html\r\n";

  	$body = "$file: " . date('Y-m-d H:i:s', filemtime($file));
      	$mailsubject = "Cube File Attribute";
	(mail($mailTo, $mailsubject, $body, $mailheaders));







?>

