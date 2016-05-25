<?

	$submit = $HTTP_POST_VARS['submit'];
	if($auth_exp == "WRITE" && $submit == "Create New Load"){
		$load_num = $HTTP_POST_VARS['load_num'];
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGEN_LOAD_HEADER
				WHERE LOAD_NUM = '".$load_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if($load_num == ""){
			echo "<font color=\"#FF0000\">A Load # must be entered to create a new load.</font><br>";
		} elseif(ociresult($stid, "THE_COUNT") >= 1){
			echo "<font color=\"#FF0000\">Entered Load# (".$load_num.") could not be created as new,<br>Because it is already in the system.</font><br>";
		} else {

			$sql = "INSERT INTO ARGEN_LOAD_HEADER
						(LOAD_NUM)
					VALUES
						('".$load_num."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);
		}
	}

	if($auth_exp == "WRITE" && $submit == "Save Load Info"){
		$bad_message = "";

		$date = str_replace("'", "`", $HTTP_POST_VARS['date']);
		$date = str_replace("\\", "", $date);
		$trans_id = str_replace("'", "`", $HTTP_POST_VARS['trans_id']);
		$trans_id = str_replace("\\", "", $trans_id);

		if(!ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $date)) {
			$bad_message .= "Date wasn't in MM/DD/YYYY format.<br>";
		}

		if($bad_message != ""){
			echo "<font color=\"#FF0000\">Save could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		} else {
			$sql = "UPDATE ARGEN_LOAD_HEADER
					SET EXPECTED_DATE = TO_DATE('".$date."', 'MM/DD/YYYY'),
						TRANSPORT_ID = '".$trans_id."'
					WHERE LOAD_NUM = '".$load_num."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
			echo "<font color=\"#0000FF\">Save Complete.</font><br>";
		}
	}

	if($auth_port == "WRITE" && $submit == "Save Load Details"){
		$bad_message = "";

		$driver_name = str_replace("'", "`", $HTTP_POST_VARS['driver_name']);
		$driver_name = str_replace("\\", "", $driver_name);
		$driver_phone = str_replace("'", "`", $HTTP_POST_VARS['driver_phone']);
		$driver_phone = str_replace("\\", "", $driver_phone);
		$in_time = str_replace("'", "`", $HTTP_POST_VARS['in_time']);
		$in_time = str_replace("\\", "", $in_time);
		$out_time = str_replace("'", "`", $HTTP_POST_VARS['out_time']);
		$out_time = str_replace("\\", "", $out_time);
		$signature = str_replace("'", "`", $HTTP_POST_VARS['signature']);
		$signature = str_replace("\\", "", $signature);
		$lic = str_replace("'", "`", $HTTP_POST_VARS['lic']);
		$lic = str_replace("\\", "", $lic);

		if($in_time != "" && !ereg("^([0-9]{1,2}):([0-9]{1,2})$", $in_time)) {
			$bad_message .= "Checkin time wasn't in HH:MM format.<br>";
		}
		if($out_time != "" && !ereg("^([0-9]{1,2}):([0-9]{1,2})$", $out_time)) {
			$bad_message .= "Checkout time wasn't in HH:MM format.<br>";
		}
		
		if($bad_message != ""){
			echo "<font color=\"#FF0000\">Save could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		} else {
			$sql = "UPDATE ARGEN_LOAD_HEADER
					SET DRIVER_NAME = '".$driver_name."',
						DRIVER_PHONE = '".$driver_phone."',
						CHECK_IN = '".$in_time."',
						CHECK_OUT = '".$out_time."',
						SIGNATURE = '".$signature."',
						TRUCK_LIC_AND_STATE = '".$lic."'
					WHERE LOAD_NUM = '".$load_num."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
			echo "<font color=\"#0000FF\">Save Complete.</font><br>";
		}
	}














?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Load Details Page</b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="loadnum_select_1" action="loadnum_edit_index.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Load:</td>
		<td align="left"><select name="load_num" onchange="document.loadnum_select_1.submit(this.form)"><option value="">Select a Load:</option>
<?

	$sql = "SELECT LOAD_NUM
			FROM ARGEN_LOAD_HEADER 
			ORDER BY LOAD_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "LOAD_NUM"); ?>"<? if($load_num == ociresult($stid, "LOAD_NUM")){?> selected <?}?>><? echo ociresult($stid, "LOAD_NUM"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b><font size="2" face="Verdana" color="#330000">--- OR ---</font></b></td>
	</tr>
<form name="loadnum_select_2" action="loadnum_edit_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana" color="#330000">New Load:</font></td>
		<td><input type="text" name="load_num" size="20" maxlength="20" value=""><input type="submit" name="submit" value="Create New Load"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>

<?
	if($load_num != ""){
		$sql = "SELECT TO_CHAR(EXPECTED_DATE, 'MM/DD/YYYY') THE_DATE, TRANSPORT_ID
				FROM ARGEN_LOAD_HEADER
				WHERE LOAD_NUM = '".$load_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){
			$date = ociresult($stid, "THE_DATE");
			$trans_id = ociresult($stid, "TRANSPORT_ID");
		}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="load_edit_bottom_1" action="loadnum_edit_index.php" method="post">
<input type="hidden" name="load_num" value="<? echo $load_num; ?>">
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Load #:</b></td>
		<td><font size="2" face="Verdana"><? echo $load_num; ?></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana" color="#330000">Transporter ID:</font></td>
		<td><select name="trans_id"><option value="">Select a #</option>
<?
		$sql = "SELECT TRANSPORT_ID, COMPANY_NAME 
				FROM ARGENFRUIT_TRANSPORT
				WHERE COMPANY_NAME IS NOT NULL
					AND ADDRESS IS NOT NULL
					AND PHONE IS NOT NULL
				ORDER BY TRANSPORT_ID";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "TRANSPORT_ID"); ?>"<? if(ociresult($stid, "TRANSPORT_ID") == $trans_id){?> selected <?}?>><? echo ociresult($stid, "TRANSPORT_ID")."-".ociresult($stid, "COMPANY_NAME"); ?></option>
<?
		}
?>			
			</select><? if($trans_id == ""){?><font size="2" face="Verdana" color="#FF0000">&nbsp;&nbsp;If this value is not entered, this Load ID cannot be chosen on the Order Page.</font><?}?></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Load Date:</font></td>
		<td><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>"><? if($date == ""){?><font size="2" face="Verdana" color="#FF0000">&nbsp;&nbsp;If this value is not entered, this Load ID cannot be chosen on the Order Page.</font><?}?></td>
	</tr>
<?
	if($auth_exp == "WRITE"){
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Load Info"><hr></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">(Only the Customer can edit these lines)</font><hr></td>
	</tr>
<?
	}
?>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="load_edit_bottom_2" action="loadnum_edit_index.php" method="post">
<input type="hidden" name="load_num" value="<? echo $load_num; ?>">
	<tr>
		<td colspan="2"><font size="2" face="Verdana" color="#330000"><b>Display Only.  Port enters below info when truck checks in.</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana" color="#330000">Driver Name:</font></td>
		<td><input type="text" name="driver_name" size="20" maxlength="20" value="<? echo $driver_name; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Driver Phone:</font></td>
		<td><input type="text" name="driver_phone" size="20" maxlength="20" value="<? echo $driver_phone; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">CheckIn Time:</font></td>
		<td><input type="text" name="in_time" size="5" maxlength="5" value="<? echo $in_time; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">CheckOut Time:</font></td>
		<td><input type="text" name="out_time" size="5" maxlength="5" value="<? echo $out_time; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Signature:</font></td>
		<td><input type="text" name="signature" size="20" maxlength="20" value="<? echo $signature; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Truck License (and State):</font></td>
		<td><input type="text" name="lic" size="20" maxlength="20" value="<? echo $lic; ?>"></td>
	</tr>
<?
	if($auth_port == "WRITE"){
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Load Details"><hr></td>
	</tr>
<?
	}
?>
</form>
</table>
<?
	}
?>