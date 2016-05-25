<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on LCS than jsut the budget number itself.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ACCT - ADP GL Code Table";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }

  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
  $user_occ = $userdata['user_occ'];


  include("connect.php");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $Command = $HTTP_POST_VARS['Command'];
  $GL_code = $HTTP_POST_VARS['GL_code'];
  $NewValue = $HTTP_POST_VARS['NewValue'];
  $NewDesc = $HTTP_POST_VARS['NewDesc'];

  if($Command == 'ADD'){
	  $sql = "INSERT INTO FINANCE_ADP_CONVERSION (GL_ACCT, BENEFIT_ALLOCATION, DESCRIPTION) VALUES ('".$GL_code."', '".$NewValue."', '".$NewDesc."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'EDIT'){
	  $sql = "UPDATE FINANCE_ADP_CONVERSION SET BENEFIT_ALLOCATION = '".$NewValue."', DESCRIPTION = '".$NewDesc."' WHERE GL_ACCT = '".$GL_code."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'DELETE'){
	  $sql = "DELETE FROM FINANCE_ADP_CONVERSION WHERE GL_ACCT = '".$GL_code."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Edit GL Codes for ADP Import
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="center"><font size="3" face="Verdana"><a href="/accounting/adp/add_to_GL.php">Add a new entry to the GL Code table.</a></font></td>
   </tr>
   <tr><td>
   	  <table border="1" width="100%" cellpadding="4" cellspacing="0">
	     <tr>
		 	 <td width = "15%"><b><font size="2" face="Verdana"><nobr>GL code</nobr></font></b></td>
	         <td width = "25%"><b><font size="2" face="Verdana"><nobr>Benefit Allocation %</nobr></font></b></td>
	         <td width = "60%"><b><font size="2" face="Verdana">Description</font></b></td>
         </tr>
<?
  $sql = "SELECT * FROM FINANCE_ADP_CONVERSION ORDER BY GL_ACCT";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  $GL_code= $row['GL_ACCT'];
      $value = $row['BENEFIT_ALLOCATION'];
	  $desc = $row['DESCRIPTION'];

	  if ($value ==""){ $value = "&nbsp;"; }
	  if ($desc == ""){ $desc ="&nbsp;"; }
?>
		<tr>
		   <td><font size="2" face="Verdana"><a href="/accounting/adp/modify_GL.php?GL_code=<? echo $GL_code; ?>"><? echo $GL_code; ?></a></font></td>
		   <td><font size="2" face="Verdana"><? echo $value ?></font></td>
           <td><font size="2" face="Verdana"><? echo $desc ?></font></td>
		</tr>
<?
  }
?>
      </table>
   </td></tr>
</table>

<? include("pow_footer.php"); ?>
