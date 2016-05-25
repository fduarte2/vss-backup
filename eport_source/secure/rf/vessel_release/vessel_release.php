<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);
/*
  if($user != "wdiinsp"){
	  echo "user not authorized to use this page.  Please use your browser's back button, or choose another link to continue";
	  exit;
  }
*/

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];

	$message = "";
	if($submit == "Release to PoW" && $vessel != ""){
		$sql = "INSERT INTO WDI_VESSEL_RELEASE (LR_NUM, EPORT_USER, RELEASE_TIME) VALUES
				('".$vessel."',
				'".$user."',
				SYSDATE)";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		// 2 pending emails... one for WM, other for ops.
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					VARIABLE_LIST,
					COMPLETION_STATUS)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'VR',
					'".$vessel."',
					'PENDING')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					VARIABLE_LIST,
					COMPLETION_STATUS)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'VR_OPS',
					'".$vessel."',
					'PENDING')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					VARIABLE_LIST,
					COMPLETION_STATUS)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'WMAFTERQCRLS',
					'".$vessel."',
					'PENDING')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$message = "<font size=\"3\" color=\"#0000FF\">".$vessel." - ".$Short_Term_Row['VESSEL_NAME']." Has been released to PoW, and will now show on Pallet Movement reports</font>";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Release Vessel to PoW
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><? echo $message; ?></td>
	</tr>
<form name="get_data" action="vessel_release_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."' OR RECEIVER_ID IN (SELECT CUSTOMER_ID FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."')) AND LR_NUM NOT IN (SELECT LR_NUM FROM WDI_VESSEL_RELEASE) AND LR_NUM >= 10873 ORDER BY LR_NUM DESC";
   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT') AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."') AND LR_NUM NOT IN (SELECT LR_NUM FROM WDI_VESSEL_RELEASE) AND LR_NUM >= 10873 ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td align="left"><input type="submit" name="submit" value="Release to PoW" <? if($user != "wdits"){?> disabled<?}?>>
				<font size="2" face="Verdana">(Note:  Only user wdiinsp can release vessels)</font></td>
	</tr>
</form>
</table>