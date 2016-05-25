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

	if (trim($user) == "") {
		echo "Please login the Intranet!";
		exit;
	}


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

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">HD requests
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
		<td colspan="9" align="center"><input type="submit" name="submit" value="Create New Helpdesk Entry"></td>
	</tr>
</form>
	<tr>
		<td><font size="2" face="Verdana"><b>ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Submitter</b></font></td>
		<td><font size="2" face="Verdana"><b>Assigned To</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Category</b></font></td>
		<td><font size="2" face="Verdana"><b>Priority</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Created</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Closed</b></font></td>
	</tr>
<?
	$total = 0;
	$sql = "SELECT TO_CHAR(DATE_CREATED, 'MM/DD/YYYY HH24:MI:SS') DATE_CREATE, JOBID, USERID, DESCRIPTION, HC_DESCRIPTION, HP_DESCRIPTION, HS_DESCRIPTION, TS_CONTACT, MASTERID, TS_ID,
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
		<td colspan="9" align="center"><font size="2">No Helpdesks to display.</font></td>
	</tr>
<?
	} else {
		do {
			$total++;
			$jobid = ociresult($stid, "JOBID");
			$masterid = ociresult($stid, "MASTERID");
			$display_desc = ociresult($stid, "THE_DESC");
			$userid = ociresult($stid, "USERID");
			$description = ociresult($stid, "DESCRIPTION");
			$category = ociresult($stid, "HC_DESCRIPTION");
			$priority = ociresult($stid, "HP_DESCRIPTION");
			$status = ociresult($stid, "HS_DESCRIPTION");
			$created = ociresult($stid, "DATE_CREATE");
			$ts_contact = ociresult($stid, "TS_CONTACT");
			$closed_on = ociresult($stid, "DATE_CLOSED");
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo DisplayHD($hd_view, $jobid, $display_desc, $masterid); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $userid; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ts_contact; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $description; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $category; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $priority; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $status; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $created; ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo $closed_on; ?></font></td>
	</tr>
<?
		} while(ocifetch($stid));
?>
	<tr>
		<td colspan="8" align="left"><font size="2"><b>TOTAL:</b></font></td>
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