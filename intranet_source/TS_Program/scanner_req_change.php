<?
/*
*	Adam Walter, May 2013
*
*	A page to allow entry of upcoming scanner requests
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "SUPV System";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$req_id = $HTTP_POST_VARS['req_id'];
	if($req_id != ""){
		$sql = "SELECT NUM_SCANNER_REQ, TO_CHAR(REQUESTED_DATE_TIME, 'MM/DD/YYYY') REQ_DATE, 
					TO_CHAR(REQUESTED_DATE_TIME, 'HH24') REQ_HOUR,
					TO_CHAR(REQUESTED_DATE_TIME, 'MI') REQ_MIN,
					SUBSTR(TO_CHAR(SYSDATE + 1, 'DAY'), 0, 3) REQ_ORD_DAY,
					SUPER_ID, REQUEST_ID
				FROM SUPERVISOR_SCANNER_REQUESTS
				WHERE REQUEST_ID = '".$req_id."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$super = ociresult($stid, "SUPER_ID");
		$date = ociresult($stid, "REQ_DATE");
		$hour = ociresult($stid, "REQ_HOUR");
		$min = ociresult($stid, "REQ_MIN");
		$count = ociresult($stid, "NUM_SCANNER_REQ");
	} else {
		$super = "";
		$date = "";
		$hour = "";
		$min = "";
		$count = "";
	}




?>
<script type="text/javascript" src="/functions/calendar.js"></script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Scanner Requests Modification Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="scanner_req_list.php" method="post">
<input type="hidden" name="req_id" value="<? echo $req_id; ?>">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Supervisor:  </font></td>
		<td><select name="super">
<?
	$sql = "SELECT SCANNER_NAME FROM SCANNER_SUPERVISOR ORDER BY SCANNER_NAME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "SCANNER_NAME"); ?>" 
								<? if(ociresult($stid, "SCANNER_NAME") == $super){?>selected<?}?>><? echo ociresult($stid, "SCANNER_NAME"); ?></option>
<?
	}
?>
		
			</select></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Request Date:  </font></td>
		<td><input type="text" name="date" size="15" maxlength="10" value="<? echo $date; ?>"><a href="javascript:show_calendar('meh.date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Request Time:  </font></td>
		<td><input type="text" name="hour" size="4" maxlength="2" value="<? echo $hour; ?>">:<select name="min">
								<option value="00" <? if($min == "00"){?>selected<?}?>>00</option>
								<option value="15" <? if($min == "15"){?>selected<?}?>>15</option>
								<option value="30" <? if($min == "30"){?>selected<?}?>>30</option>
								<option value="45" <? if($min == "45"){?>selected<?}?>>45</option>
							</select></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">#Scanners:  </font></td>
		<td><input type="text" name="count" size="4" maxlength="2" value="<? echo $count; ?>">
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Submit"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");