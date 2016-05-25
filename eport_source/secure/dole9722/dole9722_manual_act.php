<?
/*
*	Adam Walter, Jan 2015.
*
*	splash page showing release status of containers.
*************************************************************************/

	$pagename = "dole9722_manual_plt";  
	include("dole9722_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");


	$submit = $HTTP_POST_VARS['submit'];

	$ship_date = $HTTP_POST_VARS['ship_date'];
	if($ship_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $ship_date)){
		echo "<font color=\"#FF0000\">Ship Date must be in MM/DD/YYYY format</font><br>";
		$ship_date = "";
		$submit = "";
	}

	if($HTTP_POST_VARS['pallet'] != ""){
		$pallet = strtoupper($HTTP_POST_VARS['pallet']);
	} else {
		$pallet = strtoupper($HTTP_GET_VARS['pallet']);
	}
	if($HTTP_POST_VARS['IB_ves'] != ""){
		$IB_ves = $HTTP_POST_VARS['IB_ves'];
	} else {
		$IB_ves = $HTTP_GET_VARS['IB_ves'];
	}

	if($pallet != ""){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '9722'
					AND PALLET_ID = '".$pallet."'";
		$check = ociparse($rfconn, $sql);
		ociexecute($check);
		ocifetch($check);
		if(ociresult($check, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\" size=\"4\">Pallet ".$pallet." is not in our system.  Please use the <a href=\"dole9722_manual_plt_index.php?pallet=".$pallet."\">Entry Screen</a> to create it first.</font>";
			$pallet = "";
		} elseif(ociresult($check, "THE_COUNT") >= 2){
			echo "<font color=\"#FF0000\">Pallet ".$pallet." is a duplicate.  Please contact PoW.</font>";
			$pallet = "";
		}
	}

	if($submit == "Save"){

		$order = $HTTP_POST_VARS['order'];
		$ship_qty = $HTTP_POST_VARS['ship_qty'];
		$time_out_h = $HTTP_POST_VARS['time_out_h'];
		$time_out_m = $HTTP_POST_VARS['time_out_m'];

		$result = ValidateEntry($pallet, $IB_ves, $ship_date, $order, $ship_qty, $time_out_h, $time_out_m, $rfconn);
		if($result != ""){
			echo "<font color=\"#FF0000\">Cannot Accept Entry:<br>".$result."<br>Please Correct and Retry.</font>";
		} else {
			$sql = "SELECT DOLE_POOL
					FROM CARGO_TRACKING_ADDITIONAL_DATA
					WHERE PALLET_ID = '".$pallet."'
						AND ARRIVAL_NUM = '".$IB_ves."'
						AND RECEIVER_ID = '9722'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$pool = ociresult($short_term_data, "DOLE_POOL");


			$sql = "DELETE FROM CARGO_ACTIVITY
					WHERE PALLET_ID = '".$pallet."'
						AND ARRIVAL_NUM = '".$IB_ves."'
						AND CUSTOMER_ID = '9722'
						AND ACTIVITY_NUM != '1'";
			$clear = ociparse($rfconn, $sql);
			ociexecute($clear);

			$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
					VALUES
					('2',
					'6',
					'".$ship_qty."',
					'8761',
					'".$order."',
					'9722',
					TO_DATE('".$ship_date." ".$time_out_h.":".$time_out_m.":30', 'MM/DD/YYYY HH24:MI:SS'),
					'".$pallet."',
					'".$IB_ves."')";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "UPDATE CARGO_TRACKING
					SET QTY_IN_HOUSE = QTY_RECEIVED - ".$ship_qty."
					WHERE PALLET_ID = '".$pallet."' 
						AND RECEIVER_ID = '9722' 
						AND ARRIVAL_NUM = '".$IB_ves."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEFROBPALLET'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "lstewart@port.state.de.us";
				$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
			} else {
				$mailTO = ociresult($email, "TO");
				if(ociresult($email, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
				}
				if(ociresult($email, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
				}
			}

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", $pallet, $mailSubject);

			$body = ociresult($email, "NARRATIVE");

			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY,
						VARIABLE_LIST)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'".$user."',
						SYSDATE,
						'EMAIL',
						'DOLEFROBPALLET',
						SYSDATE,
						'PENDING',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."',
						'".$pallet.";".$pool.";".$ship_date." ".$time_out_h.":".$time_out_m."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);

			echo "<font color=\"#0000FF\">Pallet ".$pallet." ShipOut Information Saved.</font>";
		}
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh - Add or Update Outbound Pallet Information</font><br>
		<font size="4" face="Verdana" color="#0066CC">Use this screen to Ship out a pallet, or update ship out information.</font><br><br>
		<font size="4" face="Verdana" color="#FF0000">Note:  If the "Save" button is pressed, the first transaction of this pallet (I.E. the Inbound) will be preserved, and all subsequent transactions (if any) will be replaced by one ship-out transaction.</font>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="get_data" action="dole9722_manual_act_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet:</b></font></td>
		<td><input type="text" name="pallet" size="32" maxlength="32" value="<? echo $pallet; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Pallet Info"></td>
	</tr>
</form>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr></td>
	</tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
<?
	if($pallet != ""){
		$sql = "SELECT ORDER_NUM, SERVICE_CODE, QTY_CHANGE, ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE,
					TO_CHAR(DATE_OF_ACTIVITY, 'HH24') THE_HOUR, TO_CHAR(DATE_OF_ACTIVITY, 'MI') THE_MINUTE 
				FROM CARGO_ACTIVITY CA
				WHERE CA.PALLET_ID = '".$pallet."'
					AND CA.CUSTOMER_ID = '9722'
				ORDER BY ACTIVITY_NUM DESC";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid) && ociresult($stid, "SERVICE_CODE") == "6"){
			// if the MOST RECENT activity isn't a 6, then this pallet isn't "shipped out".

			$IB_ves = ociresult($stid, "ARRIVAL_NUM");

			$ship_date = ociresult($stid, "THE_DATE");

			$order = ociresult($stid, "ORDER_NUM");
			$ship_qty = ociresult($stid, "QTY_CHANGE");
			$time_out_h = ociresult($stid, "THE_HOUR");
			$time_out_m = ociresult($stid, "THE_MINUTE");

//			$notification = "<font color=\"#FF0000\" size=\"3\"><b>PLEASE NOTE:</b>  If you change the existing Ship-Out date using this screen,<br>all previous activity records will be removed.</b></font>";
		} else {
			$sql = "SELECT ARRIVAL_NUM
					FROM CARGO_TRACKING
					WHERE RECEIVER_ID = '9722'
						AND PALLET_ID = '".$pallet."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$IB_ves = ociresult($stid, "ARRIVAL_NUM");

//			$notification = "<font color=\"#00DD00\" size=\"3\"><b>This will be a New Pallet.</b></font>";
			$notification = "";
		}
?>
<form name="save_data" action="dole9722_manual_act_index.php" method="post">
<input type="hidden" name="IB_ves" value="<? echo $IB_ves; ?>"> 
<input type="hidden" name="pallet" value="<? echo $pallet; ?>">
	<tr>
		<td colspan="2"><? echo $notification; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Outbound Order#:</b></font></td>
		<td><input type="text" name="order" size="10" maxlength="10" value="<? echo $order; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Shipped Out QTY:</b></font></td>
		<td><input type="text" name="ship_qty" size="5" maxlength="5" value="<? echo $ship_qty; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Shipped Date:</b></font></td>
		<td><input type="text" name="ship_date" size="10" maxlength="10" value="<? echo $ship_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.ship_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Shipped Time:</b></font></td>
		<td><input type="text" name="time_out_h" size="2" maxlength="2" value="<? echo $time_out_h; ?>">:
		<input type="text" name="time_out_m" size="2" maxlength="2" value="<? echo $time_out_m; ?>"></font><font size="1" face="Verdana">(24-hour time)</font></td>
	</tr> 
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"></td>
	</tr>
</form>
</table>
<?
	}









function ValidateEntry($pallet, $IB_ves, $ship_date, $order, $ship_qty, $time_out_h, $time_out_m, $rfconn){
	if(strlen($pallet) > 32){
		$error .= "Barcode is required and cannot be longer thatn 32 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $pallet)){
		$error .= "Barcode is required and must be alphanumeric.<br>";
	}

	if(strlen($ship_qty) > 6){
		$error .= "Shipped QTY cannot be longer than 6 characters.<br>";
	}
	if(!ereg("^([0-9])+$", $ship_qty)){
		$error .= "Shipped QTY must be numeric.<br>";
	}

	$sql = "SELECT QTY_RECEIVED 
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$pallet."'
				AND RECEIVER_ID = '9722'
				AND ARRIVAL_NUM = '".$IB_ves."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid) || ociresult($stid, "QTY_RECEIVED") < $ship_qty){
		$error .= "Shipped QTY cannot be higher than the Pallet's Received QTY.<br>";
	}

	if(!ereg("^([0-2]{0,1}[0-9]{1}):([0-5][0-9])$", $time_out_h.":".$time_out_m)) {
		$error .= "Shipped Time is required and must be in a valid HH:MM format (HH 0 through 23, MM 0 through 59).<br>";
	} elseif($time_out_h >= 24){
		$error .= "Received Time is required and must be in a valid HH:MM format (HH 0 through 23, MM 0 through 59).<br>";
	}

	if(strlen($order) > 12){
		$error .= "Order cannot be longer than 12 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $order)){
		$error .= "Order is required and must be alphanumeric.<br>";
	}

	return $error;
}