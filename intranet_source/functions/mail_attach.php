<?php
function mail_attach($to,$from,$subject,$body,$fname,$data,$priority=3,$type="Application/Octet-Stream"){
 $headers='';
 $mime_boundary = "<<<:" . md5(uniqid(mt_rand(), 1));
 $headers .= "MIME-Version: 1.0\r\n";
 $headers .= "X-Priority: $priority\r\n";
 $headers .= "Content-Type: multipart/mixed;\r\n";
 $headers .= " boundary=\"" . $mime_boundary . "\"\r\n";
 $headers .= "From: $from\r\n";
 $mime = "This is a multi-part message in MIME format.\r\n";
 $mime .= "\r\n";
 $mime .= "--" . $mime_boundary . "\r\n";
 $mime .= "Content-Transfer-Encoding: 7bit\r\n";
 $mime .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
 $mime .= "\r\n";
 $mime .= $body . "\r\n\r\n";
 $mime .= "--" . $mime_boundary . "\r\n";
 $mime .= "Content-Transfer-Encoding: base64\r\n";
 $mime .= "Content-Type: $type;\r\n";
 $mime .=" name=\"$fname\"\r\n";
 $mime .= "Content-Disposition: attachment;\r\n ";
 $mime .= " name=\"$fname\"\r\n\r\n"; 
 $mime .= chunk_split(base64_encode($data)) . "\r\n\r\n";
 $mime .= "--" . $mime_boundary . "--\r\n";
 // All Built, now send the message
 mail($to, $subject, $mime, $headers, "-f$from");
}

function mail_attach_with_cc($to,$from,$cc,$bcc,$subject,$body,$fname,$data,$priority=3,$type="Application/Octet-Stream"){
 $headers='';
 $mime_boundary = "<<<:" . md5(uniqid(mt_rand(), 1));
 $headers .= "MIME-Version: 1.0\r\n";
 $headers .= "X-Priority: $priority\r\n";
 $headers .= "Content-Type: multipart/mixed;\r\n";
 $headers .= " boundary=\"" . $mime_boundary . "\"\r\n";
 $headers .= "From: $from\r\n";
 $headers .= "CC: $cc\r\n";
 $headers .= "Bcc: $bcc\r\n";
 $mime = "This is a multi-part message in MIME format.\r\n";
 $mime .= "\r\n";
 $mime .= "--" . $mime_boundary . "\r\n";
 $mime .= "Content-Transfer-Encoding: 7bit\r\n";
 $mime .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
 $mime .= "\r\n";
 $mime .= $body . "\r\n\r\n";
 $mime .= "--" . $mime_boundary . "\r\n";
 $mime .= "Content-Transfer-Encoding: base64\r\n";
 $mime .= "Content-Type: $type;\r\n";
 $mime .=" name=\"$fname\"\r\n";
 $mime .= "Content-Disposition: attachment;\r\n ";
 $mime .= " name=\"$fname\"\r\n\r\n"; 
 $mime .= chunk_split(base64_encode($data)) . "\r\n\r\n";
 $mime .= "--" . $mime_boundary . "--\r\n";
 // All Built, now send the message
 mail($to, $subject, $mime, $headers, "-f$from");
}
?>
