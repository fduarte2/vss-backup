<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on LCS than jsut the budget number itself.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "LCS - Edit Budget";
  $area_type = "LCS";

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
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $Command = $HTTP_POST_VARS['Command'];
  $OldComm = $HTTP_POST_VARS['OldComm'];
  $OldType = $HTTP_POST_VARS['OldType'];
  $NewComm = $HTTP_POST_VARS['NewComm'];
  $NewType = $HTTP_POST_VARS['NewType'];
  $NewBudget = $HTTP_POST_VARS['NewBudget'];
  $NewFtl = $HTTP_POST_VARS['NewFtl'];
  $NewLtl = $HTTP_POST_VARS['NewLtl'];

  if($Command == 'ADD'){
	  $sql = "INSERT INTO BUDGET VALUES ('".$NewComm."', '".$NewType."', '".$NewBudget."', '".$NewFtl."', '".$NewLtl."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'EDIT'){
	  $sql = "UPDATE BUDGET SET BUDGET = '".$NewBudget."', QTY_FTL = '".$NewFtl."', QTY_LTL = '".$NewLtl."' WHERE COMMODITY = '".$OldComm."' AND TYPE = '".$OldType."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'DELETE'){
	  $sql = "DELETE FROM BUDGET WHERE COMMODITY = '".$OldComm."' AND TYPE = '".$OldType."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Edit Budget for Hire-Plan (Units/Hour)
            </font>
         </p>
      </td>
   </tr>
   <tr>
         <td>
            <p align="left">
               <font size="3" face="Verdana" color="#0066CC"><b>Examples of Unit are: Pallet, Bundle, Piece, etc.</b>
               </font>
   	    <hr>
            </p>
         </td>
   
   </tr>
</table>

<table border="0"   width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td><font size="3" face="Verdana"><a href="/lcs/commodity/add_to_lcs_budget.php">Add a new entry to the Budget table.</a></font></td>
   </tr>
   <tr><td>
   	  <table border="1"  cellpadding="4" cellspacing="0">
	     <tr>
		 	 <td width = "300"><nobr>Commodity</nobr></td>
	         <td width = "250"><nobr>Type</nobr></td>
	         <td width = "100">Budget(Units/Hour)</td>
	         <td width = "50">FTL</td>
             <td width = "50">LTL</td>
         </tr>
<?
  $sql = "select b.commodity, b.type, b.budget, b.qty_ftl, b.qty_ltl, c.commodity_name from budget b, commodity c where b.commodity = c.commodity_code(+) order by type, commodity";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch($cursor)){
	  $comm= trim(ora_getcolumn($cursor, 0));
      $type = trim(ora_getcolumn($cursor, 1));
	  $bgt = ora_getcolumn($cursor,2);
	  $ftl = ora_getcolumn($cursor,3);
	  $ltl = ora_getcolumn($cursor,4);
      $cName = ora_getcolumn($cursor,5);

      if ($comm =="") $comm = "&nbsp;";
	  if ($type =="") $type = "&nbsp;";
	  if ($bgt == 0) $bgt ="&nbsp;";
	  if ($ftl == 0) $ftl ="&nbsp;";
      if ($ltl == 0) $ltl ="&nbsp;";
?>
		<tr>
		   <td><a href="/lcs/commodity/modify_lcs_budget.php?comm=<?echo $comm?>&type=<?echo $type?>"><? echo $cName ?></a></td>
		   <td><? echo $type ?></td>
           <td><? echo $bgt ?></td>
           <td><? echo $ftl ?></td>
           <td><? echo $ltl ?></td>
		</tr>
<?
  }
?>
      </table>
   </td></tr>
</table>

<? include("pow_footer.php"); ?>
