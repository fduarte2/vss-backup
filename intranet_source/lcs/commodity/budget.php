<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on BNI than jsut the budget number itself.
*/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "LCS - Edit Budget";
  $area_type = "LCS";

  include("utility.php");

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
//  $user_occ = $userdata['user_occ'];

//  if (stristr($user_occ,"Financial Analyst") == false && array_search('ROOT', $user_types) === false ){
  if (array_search('ROOT', $user_types) === false && (array_search('DIRE', $user_types) === false || array_search('FINA', $user_types) === false) ){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $org = array("_");
  $rep = array("X");


  $Command = $HTTP_POST_VARS['Command'];
  $OldComm = $HTTP_POST_VARS['OldComm'];
  $OldType = $HTTP_POST_VARS['OldType'];
  $NewComm = $HTTP_POST_VARS['NewComm'];
  $NewType = $HTTP_POST_VARS['NewType'];
  $NewBudget = $HTTP_POST_VARS['NewBudget'];
  $NewGrouping = $HTTP_POST_VARS['NewGrouping'];


  if($Command == 'ADD'){
	  $sql = "INSERT INTO BUDGET VALUES ('".$NewType."', '".$NewComm."', '".$NewBudget."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);

	  $sql = "UPDATE COMMODITY_PROFILE SET GROUP_CODE = '".$NewGrouping."' WHERE COMMODITY_CODE = '".$NewComm."'"; 
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'EDIT'){
	  $sql = "UPDATE BUDGET SET BUDGET = '".$NewBudget."' WHERE COMMODITY_CODE = '".$OldComm."' AND TYPE = '".$OldType."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);

	  $sql = "UPDATE COMMODITY_PROFILE SET GROUP_CODE = '".$NewGrouping."' WHERE COMMODITY_CODE = '".$OldComm."'"; 
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
   }

  if($Command == 'DELETE'){
	  $sql = "DELETE FROM BUDGET WHERE COMMODITY_CODE = '".$OldComm."' AND TYPE = '".$OldType."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);

	  $sql = "UPDATE COMMODITY_PROFILE SET GROUP_CODE = NULL WHERE COMMODITY_CODE = '".$OldComm."' OR GROUP_CODE = '".$OldComm."'"; 
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Edit BNI Budget</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td><font size="3" face="Verdana"><a href="/lcs/commodity/add_to_bni_budget.php">Add a new entry to the BNI budget table</a></font></td>
   </tr>
   <tr><td>
      <table border="1"  cellpadding="4" cellspacing="0">
      	 <tr>
            <td><nobr>Type</nobr></td>
	        <td><nobr>Commodity Grouping</nobr></td>
	        <td><nobr>Commodity Name</nobr></td>
	        <td>Budget(Tons/Hrs)</td>
			<td>Budget Group</td>
        </tr>
<?
  $sql = "select b.commodity_code, b.type, b.budget, c.commodity_name, c.group_code from budget b, commodity_profile c where b.commodity_code = to_char(c.commodity_code(+)) order by type, commodity_code";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  while (ora_fetch($cursor)){ 
	  $comm= trim(ora_getcolumn($cursor, 0));
      $type = trim(ora_getcolumn($cursor, 1));
	  $bgt = ora_getcolumn($cursor,2);
      $cName = ora_getcolumn($cursor,3);
      $dis_comm = str_replace($org, $rep, $comm);
	  $group = ora_getcolumn($cursor, 4);
		
      if ($comm =="") $comm = "&nbsp;";
	  if ($type =="") $type = "&nbsp;";
	  if ($bgt == 0) $bgt ="&nbsp;";
	  if ($group == "") $group = "&nbsp;";
?>
		<tr>
           <td><? echo $type ?></td>
		   <td><a href="/lcs/commodity/modify_bni_budget.php?comm=<?echo $comm?>&type=<?echo $type?>"><? echo $dis_comm ?></a></td>
		   <td><? echo html($cName) ?></td>
           <td><? echo $bgt ?></td>
		   <td><? echo $group ?></td>

		</tr>
<?
  }
?>
   	</table>
   </td></tr>
</table>
<?
  include("pow_footer.php"); 
?>