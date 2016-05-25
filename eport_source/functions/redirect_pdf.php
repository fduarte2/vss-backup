<?
   // create a temporary PDF file instead of directly writing to the browser
   // to avoid a warning message of nonsecuar information sent with secuar information
   $pdfcode = $pdf->ezOutput();

   $dir = '/var/www/upload/pdf_files';
   if (!file_exists($dir)) {
     mkdir ($dir, 0775);
   }

   $fname = tempnam($dir . '/', 'PDF_') . '.pdf';
   $fp = fopen($fname, 'w');
   fwrite($fp, $pdfcode);
   fclose($fp);

   list($junk, $junk2, $junk3, $junk4, $junk5, $tname) = split("/", $fname);

   header("Location: /upload/pdf_files/$tname");

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
