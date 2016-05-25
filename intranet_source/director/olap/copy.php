<?
   $path = "/web/web_pages/upload/remote_cubes";
   $dest = "/web/web_pages/upload/cubes";

   chdir($path);
   $dir = dir(".");
   $dir-> rewind();
   while ($dName = $dir->read())
   {

   	if ($dName != "."  && $dName != ".."){
		if (is_dir($dName) && !is_link($dName))
      		{
    			if ($dh = opendir($path."/".$dName)) {
        			while (($file = readdir($dh)) !== false) {
					$fileInfo = pathinfo($file);
   					$extension = $fileInfo["extension"];
                			if ($extension == "cube"){
                                		if (copy($path."/".$dName."/".$file,$dest."/".$dName."/".$file)){
                                        		unlink($path."/".$dName."/".$file);
                        			}
                			}
        			}
        			closedir($dh);
    			}
     		}
	}
      
   }
   $dir->close();
?>
