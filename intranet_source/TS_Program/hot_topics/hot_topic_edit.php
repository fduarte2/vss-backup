<?
/* Adam Walter, May 2007.
*  I am to write a program, semi-analogous to the Meeting Agenda, which instead
*  Handles the "Hot Topics" of the Exec-Directors affairs.  The top-half of the
*  Current Meeting Agenda program can be stripped off (crane% downtime and newspaper
*  Ads run aren't really big time items), but some additional functionality, and
*  Therefore some additional PG tables, will be needed.  This should, indeed, be
*  An exercise in excitment for all.
*
*  This file handles the editing of said page.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];


  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }

  $user_occ = $userdata['user_occ'];
/*
  if (stristr($user_occ,"Director") == false && array_search('ROOT', $user_types) === false ){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }
*/
  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
//  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);

  include("connect.php");
  $PGconn = pg_connect ("host=$host dbname=$db user=$dbuser");
  $query ="select sub_type from ccd_user where email = '".$user_email."' and active = 'TRUE'";
  $result = pg_query($PGconn, $query) or
            die("Error in query: $query. " .  pg_last_error($PGconn));
  $rows = pg_num_rows($result);
  if ($rows >0){
  $row = pg_fetch_row($result, 0);
  	$auth = $row[0];
  }



	$row_id = $HTTP_GET_VARS['row_id'];
	$det_id = $HTTP_GET_VARS['det_id'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hot-List (Edit)
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	$sql = "SELECT TOPIC, COMPLETION_DATE, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE FROM HOT_TOPICS WHERE HT_ROW_ID = '".$row_id."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$sql = "SELECT to_char(STATUS_DATE, 'MM/DD/YYYY') ST_DATE, NOTES FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row_id."' AND HT_DETAIL_ID = '".$det_id."'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="hot_topic.php#<? echo $auth; ?>" method="post">
<input type="hidden" name="row_id" value="<? echo $row_id; ?>">
<input type="hidden" name="det_id" value="<? echo $det_id; ?>">
<input type="hidden" name="submit" value="Edit Topic">
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Goal / Project / Task:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Start Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><input type="text" name="new_start_date" value="<? echo $row['ST_DATE']; ?>" size="10" maxlength="10"></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Estimated Completion Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><input type="text" name="new_comp_date" value="<? echo $row['COMPLETION_DATE']; ?>" size="10" maxlength="10"></font></td>
	</tr>
	<tr> 
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Status Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row2['ST_DATE']; ?></font></td>
	</tr> 
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Notes:<br>(Required; No double quotes please)&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><textarea name="new_notes" cols="50" rows="8"><? echo $row2['NOTES']; ?></textarea></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana"><input type="submit" name="varied" value="Save"></td>
		<td>&nbsp;</td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>