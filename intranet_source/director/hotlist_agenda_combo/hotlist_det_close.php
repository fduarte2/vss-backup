<?
/*
*	Adam Walter, Dec 2010
*	
*	Hotlist History (and executive closing) page.
*
*	I'd describe what this page does, but the comment title is
*	Also the user manual.
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }
  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);


//  $user_occ = $userdata['user_occ'];
//  if (stristr($user_occ,"Director") == false && array_search('ROOT', $user_types) === false ){
  if (array_search('DIRE', $user_types) === false && array_search('ROOT', $user_types) === false ){
	printf("Access Denied");
    include("pow_footer.php");
    exit;
  }

  
  include("connect.php");
/*
   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }

   $username = $userdata['username'];
   $user_email = $userdata['user_email'];
*/

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $INNERcursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	// figure out who gets to do what on this page...
	$sql ="SELECT EDIT_PERMISSION FROM AGENDA_HOTLIST_AUTH WHERE INTRANET_LOGIN = '".strtoupper($user)."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$authority_type = $row['EDIT_PERMISSION'];

	$HL = $HTTP_GET_VARS['HL'];

	if($submit == "Close Item"){
		$HL = $HTTP_POST_VARS['HL_head_ID'];

		$sql = "UPDATE HOT_LIST_HEADER
				SET STATUS = 'CLOSED',
				COMPLETION_DATE = SYSDATE
				WHERE HL_ROW_ID = '".$HL."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
	}

	$sql = "SELECT USER_LEVEL, ITEM_DESCRIPTION, TO_CHAR(START_DATE, 'MM/DD/YYYY HH24:MI') THE_START, STATUS
			FROM HOT_LIST_HEADER
			WHERE HL_ROW_ID = '".$HL."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$created = $row['THE_START'];
	$desc = $row['ITEM_DESCRIPTION'];
	$dept = $row['USER_LEVEL'];
	$status = $row['STATUS'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Holist Details</font>&nbsp;&nbsp;&nbsp;&nbsp;<font size="3" face="Verdana">
												<a href="hotlist_agenda_combo.php">Click here to return to the Agenda/HotList page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($authority_type == "EXEC" && $status == 'OPEN'){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<form name="close_HL" action="hotlist_det_close.php" method="post">
	<input type="hidden" name="HL_head_ID" value="<? echo $HL; ?>">
	<tr>
		<td><font size="3" face="Verdana"><b>Would you like to<br>Close this Item?</b></font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Close Item"><br><br></td>
	</tr>
	</form>
</table>
<?
	}
?>
<table border="1" width="400" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana"><b>HL#:  <? echo $HL; ?></b></font></td>
	</tr>
<!--	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Topic:  <? echo $desc; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Dept:  <? echo $dept; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Created On:  <? echo $created; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Status:  <? echo $status; ?></b></font></td>
	</tr> !-->
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Topic:  <? echo $desc; ?></b></font><br>
										<font size="2" face="Verdana"><b>Dept:  <? echo $dept; ?></b></font><br>
										<font size="2" face="Verdana"><b>Created On:  <? echo $created; ?></b></font><br>
										<font size="2" face="Verdana"><b>Status:  <? echo $status; ?></b></font>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Updated On:</b></font></td>
		<td><font size="2" face="Verdana"><b>Update Notes:</b></font></td>
	</tr>
<?
	$sql = "SELECT HL_DETAIL_ID, NOTES, TO_CHAR(UPDATE_DATE, 'MM/DD/YYYY HH24:MI') THE_UPDATE
			FROM HOT_LIST_DETAIL
			WHERE HL_ROW_ID = '".$HL."'
			ORDER BY HL_DETAIL_ID DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['THE_UPDATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['NOTES']; ?></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>