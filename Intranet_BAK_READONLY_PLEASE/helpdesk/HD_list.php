<?
/*
*	Adam Walter, May 2013
*
*	A page to show non-complete HD items
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HD System";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");
/*  if($access_denied){
    printf("Access Denied from ROOT system");
    include("pow_footer.php");
    exit;
  }*/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	$user = "ithomas"; // REMOVE ASAP

	$sql = "SELECT HELPDESK_ACCESS FROM INTRANET_USERS
			WHERE USERNAME = '".$user."'";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "HELPDESK_ACCESS") == "admin" || ociresult($stid, "HELPDESK_ACCESS") == "operator"){
		$hd_view = "all";
	} else {
		$hd_view = "self";
	}
	$permission = ociresult($stid, "HELPDESK_ACCESS");

	$view_contact = $HTTP_POST_VARS['view_contact'];
	if($view_contact == ""){
		$view_contact = $user;
	}
	$view_status = $HTTP_POST_VARS['view_status'];
	if($view_status == ""){
		$view_status = "open";
	}
	$view_submitted = $HTTP_POST_VARS['view_submitted'];
	if($view_submitted == ""){
		$view_submitted = "all";
	}
	$view_priority = $HTTP_POST_VARS['view_priority'];
	if($view_priority == ""){
		$view_priority = "all";
	}
	$view_class = $HTTP_POST_VARS['view_class'];

	$closed_start = $HTTP_POST_VARS['closed_start'];
	$closed_end = $HTTP_POST_VARS['closed_end'];
	$created_start = $HTTP_POST_VARS['created_start'];
	$created_end = $HTTP_POST_VARS['created_end'];


	$submit = $HTTP_POST_VARS['submit'];
	$masterid = $HTTP_POST_VARS['masterid'];
	if($submit == "Yes"){
		$sql = "UPDATE HELPDESK
				SET JOB_STATUS = '7'
				WHERE MASTERID = '".$masterid."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
	} elseif($submit == "No"){
		$problem = $HTTP_POST_VARS['problem'];
		if($problem == ""){
			echo "<font color=\"#FF0000\"><b>Could not save; uncomplete helpdesk notifications require a reason.</b></font>";
		} else {
			$sql = "UPDATE HELPDESK
					SET JOB_STATUS = '3',
						USER_TEST_REJECT_ON = SYSDATE,
						DESCRIPTION = DESCRIPTION || '\r\n\r\n".date('m/d/Y')." Follow-up: ".$problem."'
					WHERE MASTERID = '".$masterid."'";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">HD requests</font><br><font size="4" color="#0066CC">Note:  Lines in Green need your action to confirm completion or not.<br>If additional work is required, a brief note must be entered.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($permission == "admin" || $permission == "operator"){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="view_choice" action="HD_list.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">TS Contact:</font></td>
		<td>
			<select name="view_contact">
				<option value="<? echo $user; ?>"<? if($view_contact == $user){?> selected <?}?>><? echo $user; ?></option>
				<option value="all"<? if($view_contact == "all"){?> selected <?}?>>All Users</option>
<?
		if($permission == "admin"){
			$sql = "SELECT USERNAME FROM INTRANET_USERS WHERE HELPDESK_ACCESS IN ('admin', 'operator') ORDER BY USERNAME";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			while(ocifetch($short_term_data)){
				if(ociresult($short_term_data, "USERNAME") != $user){
?>
			<option value="<? echo ociresult($short_term_data, "USERNAME"); ?>"<? if($view_contact == ociresult($short_term_data, "USERNAME")){?> selected <?}?>>
						<? echo ociresult($short_term_data, "USERNAME"); ?></option>
<?
				}
			}
		}
?>
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Status:</font></td>
		<td>
			<select name="view_status">
				<option value="all"<? if($view_status == "all"){?> selected <?}?>>All Status</option>
				<option value="open"<? if($view_status == "open"){?> selected <?}?>>All Not Closed</option>
				<option value="closed"<? if($view_status == "closed"){?> selected <?}?>>All Closed</option>
<?
		$sql = "SELECT STATUS_ID, HS_DESCRIPTION FROM HELPDESK_STATUS ORDER BY STATUS_ID";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
			<option value="<? echo ociresult($short_term_data, "STATUS_ID"); ?>"<? if($view_status == ociresult($short_term_data, "STATUS_ID")){?> selected <?}?>>
						<? echo ociresult($short_term_data, "HS_DESCRIPTION"); ?></option>
<?
		}
?>
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Submitter:</font></td>
		<td>
			<select name="view_submitted">
				<option value="all"<? if($view_submitted == "all"){?> selected <?}?>>All</option>
<?
		$sql = "SELECT USERNAME FROM INTRANET_USERS ORDER BY USERNAME";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
			<option value="<? echo ociresult($short_term_data, "USERNAME"); ?>"<? if($view_submitted == ociresult($short_term_data, "USERNAME")){?> selected <?}?>>
						<? echo ociresult($short_term_data, "USERNAME"); ?></option>
<?
		}
?>
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Priority:</font></td>
		<td>
			<select name="view_priority">
				<option value="all"<? if($view_priority == "all"){?> selected <?}?>>All</option>
<?
		$sql = "SELECT PRIORITY_ID, HP_DESCRIPTION FROM HELPDESK_PRIORITY ORDER BY PRIORITY_ID";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
			<option value="<? echo ociresult($short_term_data, "PRIORITY_ID"); ?>"<? if($view_priority == ociresult($short_term_data, "PRIORITY_ID")){?> selected <?}?>>
						<? echo ociresult($short_term_data, "HP_DESCRIPTION"); ?></option>
<?
		}
?>
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Class:</font></td>
		<td>
			<select name="view_class">
				<option value="all"<? if($view_class == "all"){?> selected <?}?>>All</option>
				<option value="HD"<? if($view_class == "HD"){?> selected <?}?>>Standard HD Only</option>
				<option value="TS"<? if($view_class == "TS"){?> selected <?}?>>TS Internals Only</option>
		</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Close Date (On or After):</font></td>
		<td><input type="text" name="closed_start" size="10" maxlength="10" value="<? echo $closed_start; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Close Date (On or Before):</font></td>
		<td><input type="text" name="closed_end" size="10" maxlength="10" value="<? echo $closed_end; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Created Date (On or After):</font></td>
		<td><input type="text" name="created_start" size="10" maxlength="10" value="<? echo $created_start; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Created Date (On or Before):</font></td>
		<td><input type="text" name="created_end" size="10" maxlength="10" value="<? echo $created_end; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Filter Results"></td>
	</tr>

	</form>
</table>
<?
	}
?>		


<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="new" action="HD_enter.php" method="post">
	<tr>
		<td colspan="11" align="center"><input type="submit" name="submit" value="Create New Helpdesk Entry"></td>
	</tr>
</form>
	<tr bgcolor="#33CCFF">
		<td><font size="2" face="Verdana"><b>ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Submitter</b></font></td>
		<td><font size="2" face="Verdana"><b>Assigned To</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Category</b></font></td>
		<td><font size="2" face="Verdana"><b>Priority</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Created</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Closed</b></font></td>
		<td align="center" colspan="2"><font size="2" face="Verdana"><b>Completed?</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Requires Additional Work?</b></font></td> !-->
	</tr>
<?
	$total = 0;
	$sql = "SELECT TO_CHAR(DATE_CREATED, 'MM/DD/YYYY HH24:MI:SS') DATE_CREATE, JOBID, USERID, DESCRIPTION, HC_DESCRIPTION, HP_DESCRIPTION, HS_DESCRIPTION, TS_CONTACT, MASTERID, TS_ID, JOB_STATUS,
				DECODE(TS_ID, NULL, 'HD' || JOBID, 'TS' || TS_ID) THE_DESC,
				DECODE(JOB_STATUS, 7, TO_CHAR(LAST_CHANGED, 'MM/DD/YYYY'), 8, TO_CHAR(LAST_CHANGED, 'MM/DD/YYYY'), '') DATE_CLOSED
			FROM HELPDESK HD, HELPDESK_PRIORITY HP, HELPDESK_CATEGORY HC, HELPDESK_STATUS HS
			WHERE HD.PRIORITY = HP.PRIORITY_ID
				AND HD.JOB_CATEGORY = HC.CAT_ID
				AND HD.JOB_STATUS = HS.STATUS_ID";
	if($hd_view == "self"){
		$sql .= " AND USERID = '".$user."'";
	}
	if($view_contact != "all" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND TS_CONTACT = '".$view_contact."'";
	}
	if($view_status != "all" && ($permission == "admin" || $permission == "operator")){
		if($view_status == "open"){
			$sql .= " AND JOB_STATUS NOT IN ('7', '8')";
		} elseif($view_status == "closed"){
			$sql .= " AND JOB_STATUS IN ('7', '8')";
		} else {
			$sql .= " AND JOB_STATUS = '".$view_status."'";
		}
	}
	if($view_submitted != "all" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND USERID = '".$view_submitted."'";
	}
	if($view_priority != "all" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND PRIORITY = '".$view_priority."'";
	}
	if($view_class != "all" && ($permission == "admin" || $permission == "operator")){
		if($view_class == "TS"){
			$sql .= " AND TS_ID IS NOT NULL";
		} else {
			$sql .= " AND JOBID IS NOT NULL";
		}
	}

	if($closed_start != "" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND JOB_STATUS IN ('7', '8') AND LAST_CHANGED >= TO_DATE('".$closed_start."', 'MM/DD/YYYY')";
	}
	if($closed_end != "" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND JOB_STATUS IN ('7', '8') AND LAST_CHANGED <= TO_DATE('".$closed_end."', 'MM/DD/YYYY') + 1";
	}
	if($created_start != "" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND JOB_STATUS IN ('7', '8') AND DATE_CREATED >= TO_DATE('".$created_start."', 'MM/DD/YYYY')";
	}
	if($created_end != "" && ($permission == "admin" || $permission == "operator")){
		$sql .= " AND JOB_STATUS IN ('7', '8') AND DATE_CREATED <= TO_DATE('".$created_end."', 'MM/DD/YYYY') + 1";
	}

//	echo $sql;
//	$sql .= " ORDER BY USERID, DATE_CREATED DESC";
	$sql .= " ORDER BY MASTERID DESC";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="11" align="center"><font size="2">No Helpdesks to display.</font></td>
	</tr>
<?
	} else {
	$form_num = 0;
		do {
			$form_num++;

			$total++;
			$jobid = ociresult($stid, "JOBID");
			$masterid = ociresult($stid, "MASTERID");
			$display_desc = ociresult($stid, "THE_DESC");
			$userid = ociresult($stid, "USERID");
			$description = ociresult($stid, "DESCRIPTION");
			$category = ociresult($stid, "HC_DESCRIPTION");
			$priority = ociresult($stid, "HP_DESCRIPTION");
			$status = ociresult($stid, "HS_DESCRIPTION");
			$statusid = ociresult($stid, "JOB_STATUS");
			$created = ociresult($stid, "DATE_CREATE");
			$ts_contact = ociresult($stid, "TS_CONTACT");
			$closed_on = ociresult($stid, "DATE_CLOSED");

			if($statusid == "6"){
				$bgcolor="#00EE00";
			} elseif($statusid == "7" || $statusid == "8"){
				$bgcolor="#FFFFFF";
			} else {
				$bgcolor="#FFFF33";
			}

?>
<form name="confirm<? echo $forum_num; ?>" action="HD_list.php" method="post">
<input type="hidden" name="masterid" value="<? echo $masterid; ?>">
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo DisplayHD($hd_view, $jobid, $display_desc, $masterid); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $userid; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ts_contact; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $description; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $category; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $priority; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $status; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $created; ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo $closed_on; ?></font></td>
		<? echo DisplayConfirmBoxes($user, $userid, $statusid); ?>
	</tr>
</form>
<?
		} while(ocifetch($stid));
?>
	<tr>
		<td colspan="10" align="left"><font size="2"><b>TOTAL:</b></font></td>
		<td align="right"><font size="2"><b><? echo $total; ?></b></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");





function DisplayHD($hd_view, $jobid, $display_desc, $masterid){
	if($hd_view == "self"){
		return $display_desc;
	} else {
		return "<a href=\"HD_enter.php?ID=".$masterid."\">".$display_desc."</a>";
	}
}

function DisplayConfirmBoxes($user, $userid, $statusid){
	if($statusid != "6" || $user != $userid){
?>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
<?
	} else {
		// we do this if the user needs to confirm a HD
?>
	<td><input type="submit" name="submit" value="Yes"></td>
	<td align="center" bgcolor="#FF0000"><input type="submit" name="submit" value="No"><br><font size="2">*Reason:<br></font><input type="text" name="problem" size="20" maxlength="50"></td>
<?
	}
}