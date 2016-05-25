<?
/* Adam Walter, May 2007.
*  I am to write a program, semi-analogous to the Meeting Agenda, which instead
*  Handles the "Hot Topics" of the Exec-Directors affairs.  The top-half of the
*  Current Meeting Agenda program can be stripped off (crane% downtime and newspaper
*  Ads run aren't really big time items), but some additional functionality, and
*  Therefore some additional PG tables, will be needed.  This should, indeed, be
*  An exercise in excitment for all.
*
*  This file handles the closing of said page.
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


	$row_id = $HTTP_GET_VARS['row_id'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hot-List (Closure)
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

	$sql = "SELECT HT_DETAIL_ID, to_char(STATUS_DATE, 'MM/DD/YYYY') ST_DATE, NOTES FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row_id."' ORDER BY HT_DETAIL_ID DESC";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="close" action="hot_topic.php" method="post">
<input type="hidden" name="row_id" value="<? echo $row_id; ?>">
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Goal / Project / Task:&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Start Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><? echo $row['ST_DATE']; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Estimated Completion Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><? echo $row['COMPLETION_DATE']; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Most Recently Entered Status Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><? echo $row2['ST_DATE']; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Most Recent Update:&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><? echo $row2['NOTES']; ?></font></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;Are you sure you wish to<BR>&nbsp;&nbsp;&nbsp;close this topic?</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Close Topic"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>