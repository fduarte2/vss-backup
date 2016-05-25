<?
/* Adam Walter, May 2007.
*
*  Hot Topics, v2.0
*
*  This page is designed to allow the directors, and especially Gene, to maintain
*  A listing of important current events for the Port.
*
*  This page has several auxiliary pages for entering data, but all actual
*  Update / Insert functions are in THIS page.
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

  $today = date('m/d/y');

  $user_occ = $userdata['user_occ'];

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $INNERcursor = ora_open($conn);

  include("connect.php");
  $PGconn = pg_connect ("host=$host dbname=$db user=$dbuser");
  $query ="select sub_type from ccd_user where email = '".$user_email."' and active = 'TRUE'";
  $result = pg_query($PGconn, $query) or
            die("Error in query: $query. " .  pg_last_error($PGconn));
  $rows = pg_num_rows($result);
  if ($rows >0){
  $row = pg_fetch_row($result, 0);
  	$authorization = $row[0];
  }

// while some of the placements of these definitions are redundant, I feel it increases code read-ability
// Since 4 different pages link back to here
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Close Topic"){
		$row_id = $HTTP_POST_VARS['row_id'];
		$sql = "UPDATE HOT_TOPICS SET STATUS = 'CLOSED', LAST_UPDATE_BY = '".$user."' WHERE HT_ROW_ID = '".$row_id."'";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

	if($submit == "Add Detail"){
		$row_id = $HTTP_POST_VARS['row_id'];
		$new_det_id = $HTTP_POST_VARS['new_det_id'];
		$new_stat_date = $HTTP_POST_VARS['new_stat_date'];
		$new_notes = $HTTP_POST_VARS['new_notes'];
		$new_comp_date = $HTTP_POST_VARS['new_comp_date'];

		$new_notes = str_replace("'", "`", $new_notes);
		$new_notes = str_replace("\"", "", $new_notes);
		$new_notes = str_replace("\\", "", $new_notes);

		$sql = "INSERT INTO HOT_TOPIC_DETAIL (HT_ROW_ID, HT_DETAIL_ID, STATUS_DATE, INITIAL_ENTRY_DATE, UPDATE_USER, NOTES) VALUES ('".$row_id."', '".$new_det_id."', to_date('".$new_stat_date."', 'MM/DD/YYYY'), SYSDATE, '".$user."', '".$new_notes."')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "UPDATE HOT_TOPICS SET COMPLETION_DATE = to_date('".$new_comp_date."', 'MM/DD/YYYY') WHERE HT_ROW_ID = '".$row_id."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

	if($submit == "Add Topic"){
		$auth = $HTTP_POST_VARS['auth'];
		$new_TOPIC = $HTTP_POST_VARS['new_TOPIC'];
		$new_start_date = $HTTP_POST_VARS['new_start_date'];
		$new_comp_date = $HTTP_POST_VARS['new_comp_date'];
		$init_stat_date = $HTTP_POST_VARS['init_stat_date'];
		$notes = $HTTP_POST_VARS['notes'];

// error checks

		if($new_TOPIC == ""){
			echo "<font color=\"#FF0000\">The Goals / Projects / Tasks field is required; no entry has been added.</font>";
		} else {
			$notes = str_replace("'", "`", $notes);
			$notes = str_replace("\"", "", $notes);
			$notes = str_replace("\\", "", $notes);

			$new_TOPIC = str_replace("'", "`", $new_TOPIC);
			$new_TOPIC = str_replace("\"", "", $new_TOPIC);
			$new_TOPIC = str_replace("\\", "", $new_TOPIC);

// due to recent change, $new_comp_date may be a date or "ASAP".  acting accordingly.
		if(strtoupper($new_comp_date) == 'ASAP' || $new_comp_date == ''){
			$new_comp_date = strtoupper($new_comp_date);
			$go_ahead = 1;
		} else {
			$temp = split("/", $new_comp_date);
			if($temp[0] > 0 && $temp[0] < 13 && $temp[1] > 0 && $temp[1] < 32 && $temp[2] > 2005){
				$go_ahead = 1;
			} else {
				$go_ahead = 0;
			}
		}
	
// this next check may seem REALLY wierd, but the idea is to prevent people from clicking the "add" button repeatedly.
// basically, by not showing an error, it will go through the first time, but to the person who clicked "add"
// 18 times, they'll think nothing's wrong.
			$sql = "SELECT * FROM HOT_TOPICS WHERE START_DATE = to_date('".$new_start_date."', 'MM/DD/YYYY') AND TOPIC = '".$new_TOPIC."' AND COMPLETION_DATE = '".$new_comp_date."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				if($go_ahead == 1){
					$sql = "INSERT INTO HOT_TOPICS (HT_ROW_ID, USER_LEVEL, TOPIC, START_DATE, COMPLETION_DATE, STATUS) VALUES (HOT_TOPICS_SEQ.NEXTVAL, '".$auth."', '".$new_TOPIC."', to_date('".$new_start_date."', 'MM/DD/YYYY'), '".$new_comp_date."', 'OPEN')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

					$sql = "SELECT HT_ROW_ID FROM HOT_TOPICS WHERE TOPIC = '".$new_TOPIC."' AND STATUS = 'OPEN'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$row_id = $row['HT_ROW_ID'];

					$sql = "INSERT INTO HOT_TOPIC_DETAIL (HT_ROW_ID, HT_DETAIL_ID, STATUS_DATE, INITIAL_ENTRY_DATE, UPDATE_USER, NOTES) VALUES ('".$row_id."', '1', to_date('".$init_stat_date."', 'MM/DD/YYYY'), SYSDATE, '".$user."', '".$notes."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				} else {
					echo "<font color=\"#FF0000\">Invalid Completion Date, no entry recorded</font>";
				}
			}
		}
	}

	if($submit == "Edit Topic"){
		$new_start_date = $HTTP_POST_VARS['new_start_date'];
		$new_comp_date = $HTTP_POST_VARS['new_comp_date'];
		$new_stat_date = $HTTP_POST_VARS['new_stat_date'];
		$new_notes = $HTTP_POST_VARS['new_notes'];
		$det_id = $HTTP_POST_VARS['det_id'];
		$row_id = $HTTP_POST_VARS['row_id'];

		$new_notes = str_replace("'", "`", $new_notes);
		$new_notes = str_replace("\"", "", $new_notes);
		$new_notes = str_replace("\\", "", $new_notes);

// due to recent change, $new_comp_date may be a date or "ASAP".  acting accordingly.
		if(strtoupper($new_comp_date) == 'ASAP' || $new_comp_date == ''){
			$new_comp_date = strtoupper($new_comp_date);
			$go_ahead = 1;
		} else {
			$temp = split("/", $new_comp_date);
			if($temp[0] > 0 && $temp[0] < 13 && $temp[1] > 0 && $temp[1] < 32 && $temp[2] > 2005){
				$go_ahead = 1;
			} else {
				$go_ahead = 0;
			}
		}

		if($go_ahead == 1){
			$sql = "UPDATE HOT_TOPICS SET START_DATE = to_date('".$new_start_date."', 'MM/DD/YYYY'), COMPLETION_DATE = '".$new_comp_date."' WHERE HT_ROW_ID = '".$row_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			$sql = "UPDATE HOT_TOPIC_DETAIL SET NOTES = '".$new_notes."' WHERE HT_ROW_ID = '".$row_id."' AND HT_DETAIL_ID = '".$det_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		} else {
			echo "<font color=\"#FF0000\">Invalid Completion Date, no entry recorded</font>";
		}
	}

	
	$row_counter = 0;
	$TR_format_check = 0;
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana">Hot-List   ---   <a href="hot_topic_print.php?auth=ALL">Print All</a></font>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
/* This is a bunch of giant nested while loops, so I'll spell them out up here for ease of understanding.
*  The outer loop gets the number of distinct rows in HOT_TOPICS for a given department with a status of OPEN.
*  Each record is a topic, so for each loop, I create a <TR>.
*  I then do a count(*) on HOT_TOPIC_DETAILS for each HT_ROW_ID, and define the Rowcount of the first <TD>
*  Within each <TR> to be that count.  I then start the inner loop, which takes the HT_ROW_ID, and grabs
*  Every record within HOT_TOPIC_DETAILS for display in the <TR>.
***************************************************************************************************************/
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" width="100%" align="center"><font size="2" face="Verdana">Jump to:&nbsp;&nbsp;&nbsp;<a href="#OPS">Operations</a>&nbsp;&nbsp;&nbsp;<a href="#MRKT">Marketing</a>&nbsp;&nbsp;&nbsp;<a href="#TECH">Tech Solutions</a>&nbsp;&nbsp;&nbsp;<a href="#HR">HR</a>&nbsp;&nbsp;&nbsp;<a href="#FINA">Finance</a>&nbsp;&nbsp;&nbsp;<a href="#ENG">Engineering</a></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>
	<tr> <!-- step 1:  Exec Director !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="EXEC">Executive Director:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'EXEC' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "EXEC"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
					
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
				<td align="center" rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="EXEC"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="EXEC"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 2:  Operations !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="OPS">Operations:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'OPS' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "OPS"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "OPS"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="OPS"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "OPS" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="OPS"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 3:  Marketing !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="MRKT">Marketing:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'MRKT' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "MRKT"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "MRKT"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="MRKT"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "MRKT" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="MRKT"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 4:  Tech Solutions !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="TECH">Tech Solutions:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'TECH' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "TECH"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "TECH"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="TECH"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "TECH" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="TECH"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 5:  HR !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="HR">HR:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'HR' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "HR"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "HR"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="HR"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "HR" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="HR"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 6:  Finance !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="FINA">Finance:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'FINA' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "FINA"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "FINA"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="FINA"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "FINA" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="FINA"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>



	<tr> <!-- step 7:  Engineering !-->
		<td colspan="2" width="100%" align="center"><font size="3" face="Verdana"><b><a name="ENG">Engineering:</a></b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" align="center">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="3%" align="center">#</td>
				<td width="20%" align="center"><font size="2" face="Verdana"><b>Goals / Projects / Tasks</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Start Date</b></font></td>
				<td width="7%" align="center"><font size="2" face="Verdana"><b>Completion Date (Est.)</b></font></td>
				<td width="8%" align="center"><font size="2" face="Verdana"><b>Status Date</b></font></td>
				<td width="47%" align="center"><font size="2" face="Verdana"><b>Notes</b></font></td>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>Options</b></font></td>
			</tr>
<?
	$sql = "SELECT HT_ROW_ID, TOPIC, to_char(START_DATE, 'MM/DD/YYYY') ST_DATE, COMPLETION_DATE FROM HOT_TOPICS WHERE USER_LEVEL = 'ENG' AND STATUS = 'OPEN' ORDER BY HT_ROW_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="9" align="center"><font size="2" face="Verdana">No Current Hot Topics</font></td>
			</tr>
<?
	} else {
		do {
			$row_counter++;

			$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."'";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$num_details = $INNERrow['THE_COUNT'];
?>
			<tr>
				<td rowspan="<? echo $num_details; ?>" align="center"><font size="2" face="Verdana"><? echo $row_counter; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana"><? echo $row['TOPIC']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['ST_DATE']; ?></font></td>
				<td rowspan="<? echo $num_details; ?>"><font size="2" face="Verdana">&nbsp;<? echo $row['COMPLETION_DATE']; ?></font></td>
<?
			$sql = "SELECT NOTES, to_char(STATUS_DATE, 'MM/DD/YYYY') STAT_DATE, HT_DETAIL_ID FROM HOT_TOPIC_DETAIL WHERE HT_ROW_ID = '".$row['HT_ROW_ID']."' ORDER BY HT_DETAIL_ID";
			ora_parse($INNERcursor, $sql);
			ora_exec($INNERcursor);
			while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($TR_format_check == 1){
					echo "<tr>";
				}
?>
				<td><font size="2" face="Verdana">
<?
				if($authorization == "ENG"){
?>
					<a href="hot_topic_edit.php?row_id=<? echo $row['HT_ROW_ID']; ?>&det_id=<? echo $INNERrow['HT_DETAIL_ID']; ?>"><? echo $INNERrow['STAT_DATE']; ?></a>
<?
				} else {
					echo $INNERrow['STAT_DATE'];
				}
?>
					</font></td>
				<td><font size="2" face="Verdana"><? echo $INNERrow['NOTES']; ?></font></td>
<?
				if($TR_format_check == 0){
					if($authorization == "EXEC"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_close.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Close</a></font></td>
<?
					} elseif($authorization == "ENG"){
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana"><a href="hot_topic_add_detail.php?row_id=<? echo $row['HT_ROW_ID']; ?>">Add Update</a></font></td>
<?
					} else {
?>
				<td align="center" rowspan="<? echo $num_details; ?>" colspan="2"><font size="2" face="Verdana">&nbsp;</font></td>
<?
					}
				}
?>
			</tr>
<?
			$TR_format_check = 1;
			}

			$TR_format_check = 0;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$row_counter = 0;
?>
		</table>
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><form name="action_1" action="hot_topic_add.php" method="post"><input type="hidden" name="auth" value="ENG"><input type="submit" name="submit" value="Add New Topic" <? if($authorization != "ENG" && $authorization != "EXEC"){?> disabled <?}?>></form></td>
		<td width="50%" align="center"><form name="action_2" action="hot_topic_print.php" method="post"><input type="hidden" name="auth" value="ENG"><input type="submit" name="submit" value="Print List"></form></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">&nbsp;</td>
	</tr>
</table>
<!-- <a href="hot_topic.php">Test Refresh</a> !-->
<?
	include("pow_footer.php");
?>