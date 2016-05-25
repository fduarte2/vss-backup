<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Library";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Library system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];

  // I have been asked to further restrict access to thsi viewpage.  not my favorite method, but...
  if($user != "gbailey" && $user != "tkeefer" && $user != "rhorne" && $user != "fvignuli" && $user != "skennard" && $user != "ithomas" && $user != "parul" && $user != "dthomp" && $user != "tstest")
  {
    printf("Access Denied from Library system");
    include("pow_footer.php");
    exit;
  }

function array_sort($array, $key)
{
for ($i = 0; $i < sizeof($array); $i++) {
		$sort_values[$i] = $array[$i][$key];
} 
if (sizeof($array) > 0) {
 asort ($sort_values);
 reset ($sort_values);
 while (list ($arr_key, $arr_val) = each ($sort_values)) {
 		$sorted_arr[] = $array[$arr_key];
 }
}
return $sorted_arr;
}

    $dir ="/web/web_pages/director/library";


    $array_index_ppt = 0;
    $array_index_doc = 0;
    $array_index_other = 0;
    $ppt_name = array();
    $doc_name = array();
    $other = array();    
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                if ($ipos = strrpos($file, ".")){
			$time = filemtime($file);
			if (substr($file, $ipos + 1 )=="ppt"){
                                array_push($ppt_name,array('mtime'=>$time,'fname'=>$file)); 
                                $array_index_ppt ++;
			}else if (substr($file, $ipos + 1 )=="doc"){
                                array_push($doc_name, array('mtime'=>$time, 'fname'=>$file));
                                $array_index_doc ++;
                        }else if (substr($file, $ipos + 1 ) <> "php" && substr($file, $ipos + 1 ) <> "swp" && $file <>".."){
				array_push($other, array('mtime'=>$time, 'fname'=>$file));
                                $array_index_other ++;

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
	 
	    <font size="5" face="Verdana" color="#0066CC">Library&nbsp;&nbsp;&nbsp;<a href="http://172.22.15.17" target="http://172.22.15.17">Search Directors Area (Trial Version)</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="file_search.php">New Search</a>
	    </font>
	    <hr>
	 
      </td>
   </tr>
</table>

<?
if($user == "ithomas" || $user == "dthomp" || $user == "tstest")
  {
?>
	<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
      <td width="2%">&nbsp;</td>
      <td> <font size="4" face="Verdana" color="#0066CC"><b><a href="library_upload.php">Upload File</a></b></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</table>
<?
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<!--
	<tr>
      <td width="2%">&nbsp;</td>
      <td> <font size="4" face="Verdana" color="#0066CC"><b><a href="agreement_contract_lease/">Port Agreements / Contracts / Leases</a></b></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
 !-->  
   <tr>
      <td width="2%">&nbsp;</td>
      <td> <font size="4" face="Verdana" color="#0066CC"><b>To Board of Directors:</b></font></td>
   </tr>

   <tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="weekly_board_report/">Weekly Reports</a></td>
   </tr>

   <tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="board_books/">Board Books</a></td>
   </tr>

	<tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="board_minutes/">Board Meeting Minutes</a></td>
	</tr>

	<tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="agreement_contract_lease/">Port Agreements / Contracts / Leases / Other Items</a></td>
	</tr>
	<tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="resolutions/">Resolutions</a></td>
	</tr>
<!--
   <tr>
      <td width="2%">&nbsp;</td>
      <td> <a href="board_other/index.php">Other Items</a></td>
   </tr>
!-->
<?
   $sorted_doc_name = array_sort($doc_name, 'mtime');
   for ($i = $array_index_doc -1 ; $i >=0; $i--){
?>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/library/<?echo $sorted_doc_name[$i][fname]?>"><?echo $sorted_doc_name[$i][fname]?></a>
      </td>
   </tr>

<?
   }
?>
   <tr> <td colspan="2">&nbsp;</td>
   </tr>




   <tr>
      <td width="2%">&nbsp;</td>
      <td > <font size="4" face="Verdana" color="#0066CC"><b>PowerPoint:</b></font></td>
   </tr>
<?
   $sorted_ppt_name = array_sort($ppt_name, 'mtime');
   for ($i = $array_index_ppt -1; $i >= 0; $i--){

?>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/library/<?echo $sorted_ppt_name[$i][fname]?>"><?echo $sorted_ppt_name[$i][fname] ?></a>
      </td>
   </tr>

<?
   }
	// adding new section for leases files, 4/18/2008
?>
   <tr> <td colspan="2">&nbsp;</td>
   <tr>
      <td width="2%">&nbsp;</td>
      <td > <font size="4" face="Verdana" color="#0066CC"><b>Lease:</b></font></td>
   </tr>
   <tr>
      <td dth="2%">&nbsp;</td>
      <td> <a href="lease/index.php">DSP_MMP Lease & Easement - 4/1/2008</a></td>
   </tr>
   <tr> <td colspan="2">&nbsp;</td>

   </tr>

   <tr>
      <td dth="2%">&nbsp;</td>
      <td> <font size="4" face="Verdana" color="#0066CC"><b>Other:</b></font></td>
   </tr>
   <tr>
      <td dth="2%">&nbsp;</td>
	<td> <i>If you are unable to change viewing size of the video, please check/change your default viewer settings. For assistance contact Data Center(ext 7839).</i></td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/library/empman">Employee Manual</a>
      </td>
   </tr>


<?
   $sorted_other = array_sort($other, 'mtime');
   for ($i = $array_index_other -1 ; $i >=0; $i--){
?>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/library/<?echo $sorted_other[$i][fname]?>"><?echo $sorted_other[$i][fname]?></a>
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
