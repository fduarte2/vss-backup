<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Employee Manual";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];

   function array_sort($array, $key)
   {
   	for ($i = 0; $i < sizeof($array); $i++) {
       		$sort_values[$i] = $array[$i][$key];
   	} 
   	asort ($sort_values);
   	reset ($sort_values);
   	while (list ($arr_key, $arr_val) = each ($sort_values)) {
         	$sorted_arr[] = $array[$arr_key];
   	}
   	return $sorted_arr;
   }

    $dir ="/web/web_pages/director/library/empman";


    $array_index_doc = 0;
    $doc_name = array();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
			$time = filemtime($file);
			if (substr($file, $ipos + 1 ) <> "php" && substr($file, $ipos + 1 ) <> "swp" && $file <>".."){
				array_push($doc_name, array('mtime'=>$time, 'fname'=>$file));
                                $array_index_doc ++;
                        }
                }
        }
        closedir($dh);
    }
 
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 
	    <font size="5" face="Verdana" color="#0066CC">Library
	    </font>
	    <hr>
	 
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 

<?
   $sorted_doc_name = array_sort($doc_name, 'mtime');
   for ($i = $array_index_doc -1 ; $i >=0; $i--){
?>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/library/empman/<?echo $sorted_doc_name[$i][fname]?>"><?echo $sorted_doc_name[$i][fname]?></a>
      </td>
   </tr>

<?
   }
?>





   <tr> <td colspan="2">&nbsp;</td>     
      
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
