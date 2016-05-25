<?
    $dir ="/web/web_pages/upload/";
    

    //copy warehouse inventory cube   
    $path = "remote_cubes/inventory";
    $dest = "cubes/inventory";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                         	if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
					unlink($dir.$path."/".$file);
				}       
                        }
                }
        }
        closedir($dh);
    }

    //copy finance cube
    $path = "remote_cubes/finance";
    $dest = "cubes";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

    //copy lcs cube
    $path = "remote_cubes/lcs";
    $dest = "cubes/lcs";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

    //copy vessel cube
    $path = "remote_cubes/vessel";
    $dest = "cubes/vessel";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

    //copy productivity cube
    $path = "remote_cubes/productivity";
    $dest = "cubes";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

    //copy billing cube
    $path = "remote_cubes/billing";
    $dest = "cubes/billing";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

    //copy holmen paper cube
    $path = "remote_cubes/holmen";
    $dest = "cubes/holmen";
    if ($dh = opendir($dir.$path)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
                        if (substr($file, $ipos + 1 )=="cube"){
                                if (copy($dir.$path."/".$file,$dir.$dest."/".$file)){
                                        unlink($dir.$path."/".$file);
                                }
                        }
                }
        }
        closedir($dh);
    }

?>
