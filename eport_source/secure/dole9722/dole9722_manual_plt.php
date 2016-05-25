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

	$rec_date = $HTTP_POST_VARS['rec_date'];
	if($rec_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $rec_date)){
		echo "<font color=\"#FF0000\">Receiving Date must be in MM/DD/YYYY format</font><br>";
		$rec_date = "";
		$submit = "";
	}
/*	$ship_date = $HTTP_POST_VARS['ship_date'];
	if($ship_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $ship_date)){
		echo "<font color=\"#FF0000\">Ship Date must be in MM/DD/YYYY format</font><br>";
		$ship_date = "";
		$submit = "";
	}
*/
	if($HTTP_POST_VARS['pallet'] != ""){
		$pallet = strtoupper($HTTP_POST_VARS['pallet']);
	} else {
		$pallet = strtoupper($HTTP_GET_VARS['pallet']);
	}
/*	if($HTTP_POST_VARS['row'] != ""){
		$row = $HTTP_POST_VARS['row'];
	} else {
		$row = $HTTP_GET_VARS['row'];
	}
*/
	if($submit == "Save"){
		$pool = $HTTP_POST_VARS['pool'];
//		$pallet = $HTTP_POST_VARS['pallet'];
		$rec_qty = $HTTP_POST_VARS['rec_qty'];
		$time_in_h = $HTTP_POST_VARS['time_in_h'];
		$time_in_m = $HTTP_POST_VARS['time_in_m'];
		$ves_type = $HTTP_POST_VARS['ves_type'];
		$IB_ves = $HTTP_POST_VARS['IB_ves'];
		$old_ves = $HTTP_POST_VARS['old_ves'];
		if($ves_type == "T"){
			$IB_ves = $pool;
		}
		$comm = $HTTP_POST_VARS['comm'];
		$var = $HTTP_POST_VARS['var'];

/*
		$order = $HTTP_POST_VARS['order'];
		$ship_qty = $HTTP_POST_VARS['ship_qty'];
		$time_out_h = $HTTP_POST_VARS['time_out_h'];
		$time_out_m = $HTTP_POST_VARS['time_out_m'];
*/
//		$result = ValidateEntry($rec_date, $ship_date, $pool, $pallet, $rec_qty, $time_in_h, $time_in_m, $IB_ves, $comm, $var, $order, $ship_qty, $time_out_h, $time_out_m, $ves_type, $rfconn);
		$result = ValidateEntry($rec_date, $ship_date, $pool, $pallet, $rec_qty, $time_in_h, $time_in_m, $IB_ves, $comm, $var, $ves_type, $rfconn);
		if($result != ""){
			echo "<font color=\"#FF0000\">Cannot Accept pallet:<br>".$result."<br>Please Correct and Retry.</font>";
			if($ves_type == "T"){
				$IB_ves = "";
			}
		} else {
			// do upload
			$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
					WHERE DOLE_COMM = '".$comm."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$comm = ociresult($stid, "PORT_COMM");

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$pallet."'
						AND ARRIVAL_NUM = '".$old_ves."'
						AND RECEIVER_ID = '9722'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$email_count = ociresult($stid, "THE_COUNT");


			$sql = "DELETE FROM CARGO_ACTIVITY
					WHERE PALLET_ID = '".$pallet."'
						AND ARRIVAL_NUM = '".$old_ves."'
						AND CUSTOMER_ID = '9722'";
			$clear = ociparse($rfconn, $sql);
			ociexecute($clear);
			$sql = "DELETE FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$pallet."'
						AND ARRIVAL_NUM = '".$old_ves."'
						AND RECEIVER_ID = '9722'";
			$clear = ociparse($rfconn, $sql);
			ociexecute($clear);

			$sql = "INSERT INTO CARGO_TRACKING
						(ARRIVAL_NUM,
						RECEIVER_ID,
						PALLET_ID,
						COMMODITY_CODE,
						VARIETY,
						QTY_RECEIVED,
						QTY_IN_HOUSE,
						WEIGHT,
						WEIGHT_UNIT,
						RECEIVING_TYPE)
					VALUES
						('".$IB_ves."',
						'9722',
						'".$pallet."',
						'".$comm."',
						'".$var."',
						'".$rec_qty."',
						'".$rec_qty."',
						'1950',
						'LB',
						'".$ves_type."')";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
					SET DOLE_POOL = '".$pool."'
					WHERE PALLET_ID = '".$pallet."'
						AND RECEIVER_ID = '9722'
						AND ARRIVAL_NUM = '".$IB_ves."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			ReceivePallet($pallet, 9722, $IB_ves, $rec_date, $time_in_h.":".$time_in_m, $rfconn);
/*
			$sql = "UPDATE CARGO_TRACKING
					SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$ship_qty."
					WHERE PALLET_ID = '".$pallet."' 
						AND RECEIVER_ID = '9722' 
						AND ARRIVAL_NUM = '".$IB_ves."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

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

			if($HTTP_POST_VARS['row'] != "" && $HTTP_POST_VARS['ID'] != ""){
				$sql = "UPDATE DOLE_9722_DETAIL
						SET ERROR_MOVE_TO_DB = NULL
						WHERE FILE_ID = '".$HTTP_POST_VARS['ID']."'
							AND ROW_NUM = '".$HTTP_POST_VARS['row']."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
			}

			echo "<font color=\"#0000FF\">Pallet ".$pallet." Imported and Shipped.</font>";
*/
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEFRIBPALLET'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);
		/*
			$mailTO = ociresult($email, "TO");
			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		*/
			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "lstewart@port.state.de.us";
		//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
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

			if($email_count >= 1){
				$email_text = "Modified";
			} else {
				$email_text = "Added";
			}

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", $pallet, $mailSubject);
			$mailSubject = str_replace("_1_", $email_text, $mailSubject);

			$body = ociresult($email, "NARRATIVE");
			$body = str_replace("_1_", $email_text, $body);

//			if(mail($mailTO, $mailSubject, $body, $mailheaders)){
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
							'DOLEFRIBPALLET',
							SYSDATE,
							'PENDING',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."',
							'".$pallet.";".$email_text.";".$pool.";".$rec_date." ".$time_in_h.":".$time_in_m."')";
//				echo $sql."<br>";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);
//			}


			echo "<font color=\"#0000FF\">Pallet ".$pallet." Receive Information Saved.</font>";

			$pool = "";
			$rec_qty = "";
			$time_in_h = "";
			$time_in_m = "";
			$IB_ves = "";
			$comm = "";
			$ves_type = "";
			$var = "";
			$rec_date = "";
/*
			$ship_date = "";
			$order = "";
			$ship_qty = "";
			$time_out_h = "";
			$time_out_m = "";
*/		
		}
	}

?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh - Add or Update Inbound Pallet Information</font><br>
		<font size="4" face="Verdana" color="#0066CC">Use this screen to add a pallet, or to update a previously entered pallet.</font><br><br>
		<font size="4" face="Verdana" color="#FF0000">Note:  If pallet info is updated for a pallet that has been shipped out, you have to re-enter the ship-out information using the link that will be provided.</font>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="dole9722_manual_plt_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet:</b></font></td>
		<td><input type="text" name="pallet" size="32" maxlength="32" value="<? echo $pallet; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Pallet Info"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
<?
	if($pallet != ""){
		$sql = "SELECT DOLE_POOL, CT.QTY_RECEIVED, CT.RECEIVING_TYPE, CT.ARRIVAL_NUM, DOLE_COMM, VARIETY, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE,
					TO_CHAR(DATE_RECEIVED, 'HH24') THE_HOUR, TO_CHAR(DATE_RECEIVED, 'MI') THE_MINUTE 
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, DOLE_COMM_CONV DCC
				WHERE CT.PALLET_ID = '".$pallet."'
					AND CT.RECEIVER_ID = '9722'
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = DCC.PORT_COMM";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){

			$pool = ociresult($stid, "DOLE_POOL");
			$rec_qty = ociresult($stid, "QTY_RECEIVED");
			$rec_date = ociresult($stid, "THE_DATE");
			$time_in_h = ociresult($stid, "THE_HOUR");
			$time_in_m = ociresult($stid, "THE_MINUTE");
			$ves_type = ociresult($stid, "RECEIVING_TYPE");
			$old_ves = ociresult($stid, "ARRIVAL_NUM");
			if($ves_type == "S"){
				$IB_ves = ociresult($stid, "ARRIVAL_NUM");
			} else {
				$IB_ves = "";
			}
			$comm = ociresult($stid, "DOLE_COMM");
			$var = ociresult($stid, "VARIETY");

/*			$ship_date = ociresult($stid, "THE_SHIPOUT");

			$order = ociresult($stid, "THE_ORDER");
			$ship_qty = ociresult($stid, "QTY");
			$temp = explode(":", ociresult($stid, "THE_TIME"));
			$time_out_h = $temp[0];
			$time_out_m = $temp[1];
*/
//			$notification = "<font color=\"#FF0000\" size=\"3\"><b>PLEASE NOTE:</b>  If you make a change to this existing Pallet, all outgoing records for said pallet will be removed, and need to be re-entered.</b></font>";
			$ship_link = "<a href=\"dole9722_manual_act_index.php?pallet=".$pallet."\">Click Here to Ship-Out this Pallet.</a>";
		} else {
//			$notification = "<font color=\"#00DD00\" size=\"3\"><b>This will be a New Pallet.</b></font>";
			$ship_link = "";
		}
?>
<form name="save_data" action="dole9722_manual_plt_index.php" method="post">
<input type="hidden" name="old_ves" value="<? echo $old_ves; ?>"> <!-- this value may be empty.  that is fine.  !-->
<input type="hidden" name="pallet" value="<? echo $pallet; ?>">
	<tr>
		<td colspan="2"><? echo $notification; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet:</b></font></td>
<!--		<td><input type="text" name="pallet" size="32" maxlength="32" value="<? echo $pallet; ?>"></td> !-->
		<td><font size="2" face="Verdana"><b><? echo $pallet; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $ship_link; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Pool:</b></font></td>
		<td><input type="text" name="pool" size="10" maxlength="10" value="<? echo $pool; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Inbound Type:</b></font></td>
		<td><input type="radio" name="ves_type" value="T"<? if($ves_type == "T"){?> checked <?}?>><font size="2" face="Verdana">Truck</font>
						<font size="1" face="Verdana">(Will use Pool#)</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="ves_type" value="S"<? if($ves_type == "S"){?> checked <?}?>><font size="2" face="Verdana">Ship</font>
			<input type="text" name="IB_ves" size="10" maxlength="10" value="<? echo $IB_ves; ?>">
						&nbsp;<font size="1" face="Verdana">(Port of Wilmington Arrival#)</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Receive Date:</b></font></td>
		<td><input type="text" name="rec_date" size="10" maxlength="10" value="<? echo $rec_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.rec_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Received Time:</b></font></td>
		<td><input type="text" name="time_in_h" size="2" maxlength="2" value="<? echo $time_in_h; ?>">:
		<input type="text" name="time_in_m" size="2" maxlength="2" value="<? echo $time_in_m; ?>"></font><font size="1" face="Verdana">(24-hour time)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Received Cases:</b></font></td>
		<td><input type="text" name="rec_qty" size="5" maxlength="5" value="<? echo $rec_qty; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Comm:</b></font></td>
		<td><input type="text" name="comm" size="2" maxlength="2" value="<? echo $comm; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Var:</b></font></td>
		<td><input type="text" name="var" size="20" maxlength="20" value="<? echo $var; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<!--
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
	</tr> !-->
	<tr>
		<td><input type="submit" name="submit" value="Save"></td>
	</tr>
</form>
</table>
<?
	}
	

























function ValidateEntry($rec_date, $ship_date, $pool, $pallet, $rec_qty, $time_in_h, $time_in_m, $IB_ves, $comm, $var, $ves_type, $rfconn){
	$error = "";

	if(strlen($pool) > 10){
		$error .= "Pool cannot be longer than 10 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9])+$", $pool)){
		$error .= "Pool is required and must be alphanumeric.<br>";
	}

	if(strlen($pallet) > 32){
		$error .= "Barcode is required and cannot be longer thatn 32 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $pallet)){
		$error .= "Barcode is required and must be alphanumeric.<br>";
	}

	if(strlen($rec_qty) > 6){
		$error .= "Received QTY cannot be longer than 6 characters.<br>";
	}
	if(!ereg("^([0-9])+$", $rec_qty)){
		$error .= "Received QTY must be numeric.<br>";
	}
/*
	if(strlen($ship_qty) > 6){
		$error .= "Shipped QTY cannot be longer than 6 characters.<br>";
	}
	if(!ereg("^([0-9])+$", $ship_qty)){
		$error .= "Shipped QTY must be numeric.<br>";
	}
	if($ship_qty > $rec_qty){
		$error .= "Shipped QTY cannot be larger than Received QTY.<br>";
	}
*/
	if(!ereg("^([0-2]{0,1}[0-9]{1}):([0-5][0-9])$", $time_in_h.":".$time_in_m)) {
		$error .= "Received Time is required and must be in a valid HH:MM format (HH 0 through 23, MM 0 through 59).<br>";
	} elseif($time_in_h >= 24){
		$error .= "Received Time is required and must be in a valid HH:MM format (HH 0 through 23, MM 0 through 59).<br>";
	}
/*
	if(!ereg("^([0-2]{0,1}[0-9]{1}):([0-5][0-9])$", $time_out_h.":".$time_out_m)) {
		$error .= "Shipped Time is required and must be in a valid HH:MM format (HH 0 through 23, MM 0 through 59).<br>";
	}
*/
/*
	if(strlen($order) > 12){
		$error .= "Order cannot be longer than 12 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $order)){
		$error .= "Order is required and must be alphanumeric.<br>";
	}
*/
	if(strlen($comm) > 2){
		$error .= "Commodity cannot be longer than 2 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $comm)){
		$error .= "Commodity is required and must be alphanumeric.<br>";
	}
	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM DOLE_COMM_CONV
			WHERE DOLE_COMM = '".$comm."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") < 1){
		$error .= "Commodity ".$comm." Not found in PoW conversion table.<br>";
	}

	if(strlen($var) > 20){
		$error .= "Variety cannot be longer than 20 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9 _-])+$", $var)){
		$error .= "Variety is required and must be alphanumeric.<br>";
	}

	if($ves_type == ""){
		$error .= "Please choose an Inbound Shipment Type.<br>";
	}

	if(strlen($IB_ves) > 12){
		$error .= "Inbound Vessel/Order# cannot be longer than 12 characters.<br>";
	}
	if(!ereg("^([a-zA-Z0-9-])+$", $IB_ves)){
		$error .= "Inbound Vessel/Order# is required and must be alphanumeric.<br>";
	}

	if($ves_type == "S"){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM VESSEL_PROFILE
				WHERE TO_CHAR(LR_NUM) = '".$IB_ves."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") < 1){
			$error .= "Selected Ship ".$IB_ves." not found in PoW system.<br>";
		}
	}
/*
	$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED, TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') DATE_REC
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$pallet."'
				AND CTAD.DOLE_POOL = '".$pool."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND CA.ACTIVITY_NUM = '1'";
//			echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1){
		$error .= "Pallet already received previously at ".ociresult($stid, "DATE_REC").".<br>";
	} 
*/
	$temprec = explode("/", $rec_date);
//	$tempship = explode("/", $ship_date);
/*
	if(mktime($time_in_h, $time_in_m, 0, $temprec[0], $temprec[1], $temprec[2]) > mktime($time_out_h, $time_out_m, 0, $tempship[0], $tempship[1], $tempship[2])){
		$error .= "Ship Out time cannot be earlier than received time.<br>";
	} 
*/

	return $error;
}

function ReceivePallet($plt, $cust, $ves, $date, $time, $rfconn){
			$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						ORDER_NUM,
						QTY_CHANGE,
						ACTIVITY_ID,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
					(SELECT
						'1',
						DECODE(RECEIVING_TYPE, 'T', '8', '1'),
						DECODE(RECEIVING_TYPE, 'T', ARRIVAL_NUM, NULL),
						QTY_RECEIVED,
						'8761',
						RECEIVER_ID,
						TO_DATE('".$date." ".$time.":00', 'MM/DD/YYYY HH24:MI:SS'),
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_RECEIVED
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$ves."'
					)";
			echo $sql;
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "UPDATE CARGO_TRACKING
					SET DATE_RECEIVED = 
						(SELECT DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".$plt."' 
							AND ARRIVAL_NUM = '".$ves."' 
							AND CUSTOMER_ID = '".$cust."' 
							AND ACTIVITY_NUM = '1')
					WHERE PALLET_ID = '".$plt."' 
						AND ARRIVAL_NUM = '".$ves."' 
						AND RECEIVER_ID = '".$cust."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
}

function GetBadSaveValue($DB_val, $saved_val, $save_check){
	if($save_check != "bad"){
		return $DB_val;
	} else {
		return $saved_val;
	}

	return $DB_val;
}
