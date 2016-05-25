<?
/*
*		Adam Walter, July 2014.
*
*		Line Release and history view.
*********************************************************************************/


  
	$pagename = "ams_release";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$key = $HTTP_GET_VARS['key'];
//	$vessel = $HTTP_GET_VARS['vessel'];
//	$bol = $HTTP_GET_VARS['bol'];
//	$cust = $HTTP_GET_VARS['cust'];
//	$cont = $HTTP_GET_VARS['cont'];
//	$order = $HTTP_GET_VARS['order'];


	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $HTTP_POST_VARS['comments'] == ""){
		echo "<font color=\"#FF0000\">You must enter Comments in order to Manually Override.</font><br>";
		$key = $HTTP_POST_VARS['key'];
		$submit = "";
	}
	if($submit == "Set AMS Release Time and Save Comments"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\\", "", $comments);

		if($comments == ""){
			$comments = "None";
		}

//				WHERE ARRIVAL_NUM = '".$vessel."'
//					AND CUSTOMER_ID = '".$cust."'
//					AND BOL_EQUIV = '".$bol."'
		$sql = "UPDATE CLR_MAIN_DATA
				SET AMS_RELEASE = SYSDATE
				WHERE CLR_KEY = '".$key."'
					AND AMS_RELEASE IS NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

		$sql = "UPDATE CLR_AMS_RELEASE
				SET APPLIED_TO_CLR = SYSDATE
				WHERE CLR_KEY = '".$key."'
					AND APPLIED_TO_CLR IS NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

		$sql = "UPDATE CLR_AMS_RELEASE
				SET HOLD_CLEARED_ON = SYSDATE
				WHERE CLR_KEY = '".$key."'
					AND APPLIED_TO_CLR IS NOT NULL
					AND HOLD_CLEARED_ON IS NULL
					AND EDI_CODE_TYPE = 'HOLD'";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);


//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
		$sql = "INSERT INTO CLR_AMS_RELEASE
					(USERNAME,
					EDI_DATE_TIME,
					APPLIED_TO_CLR,
					COMMENTS,
					ACTION_TYPE,
					EDI_CODE,
					EDI_CODE_TYPE,
					CLR_KEY,
					CLR_UNIQ_AMSHIST_KEY)
				VALUES
					('".$user."',
					SYSDATE,
					SYSDATE,
					'".$comments."',
					'RELEASE SET (Manual)',
					'Manual',
					'Override',
					'".$key."',
					CLR_UNIQ_AMSHIST_SEQ.NEXTVAL)";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);
	} elseif($submit == "Remove AMS Release"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\\", "", $comments);

		if($comments == ""){
			$comments = "None";
		}

//				WHERE ARRIVAL_NUM = '".$vessel."'
//					AND CUSTOMER_ID = '".$cust."'
//					AND BOL_EQUIV = '".$bol."'
		$sql = "UPDATE CLR_MAIN_DATA
				SET AMS_RELEASE = NULL
				WHERE CLR_KEY = '".$key."'
					AND AMS_RELEASE IS NOT NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
		$sql = "INSERT INTO CLR_AMS_RELEASE
					(USERNAME,
					APPLIED_TO_CLR,
					EDI_DATE_TIME,
					COMMENTS,
					ACTION_TYPE,
					EDI_CODE,
					EDI_CODE_TYPE,
					CLR_KEY,
					CLR_UNIQ_AMSHIST_KEY)
				VALUES
					('".$user."',
					SYSDATE,
					SYSDATE,
					'".$comments."',
					'RELEASE REMOVED (Manual)',
					'Manual',
					'Override',
					'".$key."',
					CLR_UNIQ_AMSHIST_SEQ.NEXTVAL)";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);
	}

//			WHERE ARRIVAL_NUM = '".$vessel."'
//				AND CUSTOMER_ID = '".$cust."'
//				AND BOL_EQUIV = '".$bol."'";
	$sql = "SELECT NVL(TO_CHAR(AMS_RELEASE, 'MM/DD/YYYY HH24:MI:SS'), 'NOT RELEASED') THE_REL, TO_CHAR(ORIGINAL_INSERT, 'MM/DD/YYYY HH24:MI:SS') THE_INS,
				ARRIVAL_NUM, BOL_EQUIV, CUSTOMER_ID, CONTAINER_NUM
			FROM CLR_MAIN_DATA
			WHERE CLR_KEY = '".$key."'";
	$rel_data = ociparse($rfconn, $sql);
	ociexecute($rel_data);
	ocifetch($rel_data);
	$release = ociresult($rel_data, "THE_REL");
	$cust = ociresult($rel_data, "CUSTOMER_ID");
	$vessel = ociresult($rel_data, "ARRIVAL_NUM");
	$bol = ociresult($rel_data, "BOL_EQUIV");
	$cont = ociresult($rel_data, "CONTAINER_NUM");
	$inserted = ociresult($rel_data, "THE_INS");

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	if(!ocifetch($ves_data)){
		$vesname = $vessel." - Unknown";
	} else {
		$vesname = ociresult($ves_data, "VESSEL_NAME");
	}


?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">AMS-Release And History
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Consignee:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cust; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>BoL:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Container:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cont; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>CLR Line Created On:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $inserted; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Cargo ID:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $key; ?></font></td>
	</tr>
</table>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="ID_select" action="ams_release_hist_index.php" method="post">
<!--<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>"> !-->
<input type="hidden" name="key" value="<? echo $key; ?>">
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Current Release Time:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $release; ?>&nbsp;&nbsp;&nbsp;(If there are unresolved codes below, either resolve the codes, or manually release with appropriate authorization)</font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left"><input type="text" name="comments" size="50" maxlength="50"></td>
	</tr>		
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Set AMS Release Time and Save Comments">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Remove AMS Release"><BR></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="2"><hr><font size="2" face="Verdana">Delays between Time1 and Time2 below are affected by slowdown of Internet or the time when CBP transmits the file, neither of which is controlled by PoW.<br><br>It is best to advise the brokers to file the releases at least half an hour prior to truck arrival to avoid delays.<br><br>QTY on a Release needs to be greater than or equal to the original cargo's QTY, and QTY on a HOLD REMOVAL neeeds to be greater than or equal to the QTY on HOLD for the related code.<br><br>If there is a Manual Override, the time of the override will be applied to all unprocessed EDI in column Time3.</font></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>UserName</b></font>
		<td><font size="2" face="Verdana"><b>EDI Code</b></font>
		<td><font size="2" face="Verdana"><b>Code Type</b></font>
		<td><font size="2" face="Verdana"><b>QTY</b></font>
		<td><font size="2" face="Verdana"><b>Date/Time Inside CBP file (Time1)</b></font>
		<td><font size="2" face="Verdana"><b>Sequence #</b></font>
		<td><font size="2" face="Verdana"><b>Date/Time PoW Received File (Time2)</b></font>
		<td><font size="2" face="Verdana"><b>Date/Time Action was Applied (Time3)</b></font>
		<td><font size="2" face="Verdana"><b>Comments</b></font>
<!--		<td><font size="2" face="Verdana"><b>Type</b></font> !-->
		<td><font size="2" face="Verdana"><b>Description of Action</b></font>
	</tr>
<?
//			WHERE ARRIVAL_NUM = '".$vessel."'
//				AND CUSTOMER_ID = '".$cust."'
//				AND BOL_EQUIV = '".$bol."'
	$sql = "SELECT CLR.*, TO_CHAR(EDI_DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_EDI_DATE, TO_CHAR(APPLIED_TO_CLR, 'MM/DD/YYYY HH24:MI:SS') THE_APPLIED_DATE 
			FROM CLR_AMS_RELEASE CLR
			WHERE CLR_KEY = '".$key."'
			ORDER BY EDI_DATE_TIME DESC, CLR_UNIQ_AMSHIST_KEY DESC";
	$hist_data = ociparse($rfconn, $sql);
	ociexecute($hist_data);
	if(!ocifetch($hist_data)){
?>
	<tr>
		<td colspan="7" align="center"><font size="2" face="Verdana"><b>No Release Activity Found.</b></font></td>
	</tr>
<?
	} else {
		do {
			if(ociresult($hist_data, "EDI_CODE_TYPE") == "TO BE RESOLVED"){
				$bgcolor = "#FFFFCC";
			} else {
				$bgcolor = "#FFFFFF";
			}
			$username_box = GetUserNameBox(ociresult($hist_data, "USERNAME"), ociresult($hist_data, "KEY_ID"), $received_datetime, $rfconn);

?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $username_box; ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "EDI_CODE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "EDI_CODE_TYPE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "QTY"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "THE_EDI_DATE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "CLR_UNIQ_AMSHIST_KEY"); ?></font>
		<td><font size="2" face="Verdana"><? echo $received_datetime; ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "THE_APPLIED_DATE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "COMMENTS"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "ACTION_TYPE"); ?></font>
	</tr>
<?
		} while(ocifetch($hist_data));
	}
?>
</table>


<? 
	
function GetUserNameBox($user, $release_key, &$received_datetime, $rfconn){
	if($user != "EDI"){
		$received_datetime = "";
		return $user;
	} else {
		$sql = "SELECT SPLIT_FILENAME, TO_CHAR(DATE_FILESPLIT, 'MM/DD/YYYY HH24:MI:SS') THE_DATE_FILESPLIT
				FROM CLR_AMS_350_EDI CAE, CANADIAN_AMSEDI_HEADER CAH
				WHERE CAE.KEY_ID = CAH.KEY_ID
					AND CAE.AMS350_UNIQ_ID = '".$release_key."'";
		$hist_data = ociparse($rfconn, $sql);
		ociexecute($hist_data);
		if(!ocifetch($hist_data)){
			$received_datetime = "";
			return $user;
		} else {
			$received_datetime = ociresult($hist_data, "THE_DATE_FILESPLIT");
			return "<a href=\"http://intranet/TS_Program/can_AMSEDI_filesplit/split_files/processed/".ociresult($hist_data, "SPLIT_FILENAME")."\">".$user."</a>";
		}
	}
}
