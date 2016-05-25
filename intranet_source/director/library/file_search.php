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

//    $dir ="/web/web_pages/director/library/agreement_contract_lease";

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	
	$filter_key = SafeInput($HTTP_POST_VARS['filter_key']);
	if($filter_key != ""){
		$where_filter = " AND UPPER(KEYWORDS) LIKE '%".$filter_key."%' ";
	}
	if($HTTP_POST_VARS['order'] != ""){
		$order_clause = $HTTP_POST_VARS['order'];
	} else {
		$order_clause = "DOCUMENT_NAME";
	}
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 
	    <font size="5" face="Verdana" color="#0066CC">File Search
	    </font>
	    <hr>
	 
      </td>
   </tr>
</table>



<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="file_search.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Filter by Keyword:</b></font></td>
		<td><input type="text" name="filter_key" size="20" maxlength="20" value="<? echo $filter_key; ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Sort By:</b></font></td>
		<td><select name="order">
			<option value="DOCUMENT_NAME"<? if($order_clause == "DOCUMENT_NAME"){?> selected <?}?>>File Name</option>
			<option value="KEYWORDS"<? if($order_clause == "KEYWORDS"){?> selected <?}?>>Keywords</option>
			<option value="EFFECTIVE_DATE"<? if($order_clause == "EFFECTIVE_DATE"){?> selected <?}?>>Effective Date</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Filter"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Filename</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Keywords</b></font></td>
		<td><font size="2" face="Verdana"><b>Effective Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Expiration Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Active?</b></font></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
<?
	$sql = "SELECT DS.*, TO_CHAR(EFFECTIVE_DATE, 'MM/DD/YYYY') THE_EFFECTIVE, TO_CHAR(EXPIRATION_DATE, 'MM/DD/YYYY') THE_EXPIRE
			FROM DOCUMENT_STORE DS
			WHERE 1 = 1 ".$where_filter."
			ORDER BY ".$order_clause;
	$files = ociparse($bniconn, $sql);
	ociexecute($files);
	if(!ocifetch($files)){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana"><b>No files found matching search criteria.</b></font></td>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="./<? echo ociresult($files, "SUB_DIRECTORY"); ?>/<? echo ociresult($files, "DOCUMENT_NAME"); ?>"><? echo ociresult($files, "DOCUMENT_NAME"); ?></a></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($files, "DESCRIPTION"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($files, "KEYWORDS"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($files, "THE_EFFECTIVE"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($files, "THE_EXPIRE"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($files, "ACTIVE"); ?></font></td>
		<td><font size="2" face="Verdana"><a href="./library_edit_file.php?key_id=<? echo ociresult($files, "DOCUMENT_ID"); ?>">Edit</a></font></td>
	</tr>
<?
		} while(ocifetch($files));
	}
?>
</table>
<?
	include("pow_footer.php");











/*
function array_sort($array, $key){
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
*/

function SafeInput($string){
	$return = strtoupper($string);
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);

	return $return;
}
