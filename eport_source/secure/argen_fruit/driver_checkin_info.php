<?

























?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Driver Checkin Info</b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="num_select_1" action="driver_checkin_info_index.php" method="post">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">ID:</td>
		<td align="left"><select name="driver_num" onchange="document.num_select_1.submit(this.form)"><option value="">Select a CheckIn ID:</option>
<?

	$sql = "SELECT CHECKIN_ID, '-' || NVL(DRIVER_NAME, 'ENTER NAME') THE_DRIVER
			FROM ARGENFRUIT_CHECKIN_ID
			WHERE (CHECK_OUT IS NULL OR CHECKIN_ID = '".$driver_num."')
			ORDER BY CHECKIN_ID DESC";
	echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "CHECKIN_ID"); ?>"<? if($driver_num == ociresult($stid, "CHECKIN_ID")){?> selected <?}?>><? echo ociresult($stid, "CHECKIN_ID").ociresult($stid, "THE_DRIVER"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b><font size="2" face="Verdana" color="#330000">--- OR ---</font></b></td>
	</tr>
<form name="num_select_2" action="driver_checkin_info_index.php" method="post">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New CheckInID"><hr></td>
	</tr>
</form>
</table>
<?
	if($driver_num != ""){
		$sql = "SELECT ACI.*, TO_CHAR(CHECK_IN, 'MM/DD/YYYY HH24:MI') THE_DATE_IN, TO_CHAR(CHECK_OUT, 'MM/DD/YYYY HH24:MI') THE_DATE_OUT
				FROM ARGENFRUIT_CHECKIN_ID ACI
				WHERE CHECKIN_ID = '".$driver_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$driver_name = ociresult($stid, "DRIVER_NAME");
		$temp_rec = ociresult($stid, "TEMP_RECORDER");
		$driver_phone = ociresult($stid, "DRIVER_PHONE");
		$in_time = ociresult($stid, "THE_DATE_IN");
		$out_time = ociresult($stid, "THE_DATE_OUT");
		$signature = ociresult($stid, "SIGNATURE");
		$lic = ociresult($stid, "TRUCK_LIC_AND_STATE");
		$trailer_lic = ociresult($stid, "TRAILER_LIC_AND_STATE");
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="checkin_edit" action="driver_checkin_info_index.php" method="post">
<input type="hidden" name="driver_num" value="<? echo $driver_num; ?>">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana" color="#330000">Driver Name:</font></td>
		<td><input type="text" name="driver_name" size="20" maxlength="20" value="<? echo $driver_name; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Temperature Recorder:</font></td>
		<td><input type="text" name="temp_rec" size="30" maxlength="30" value="<? echo $temp_rec; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Driver Phone:</font></td>
		<td><input type="text" name="driver_phone" size="20" maxlength="20" value="<? echo $driver_phone; ?>"></td>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana" color="#330000">CheckIn Time:</font></td>
		<td><input type="text" name="in_time" size="5" maxlength="5" value="<? echo $in_time; ?>"></td>
	</tr>!-->
	<tr>
		<td><font size="2" face="Verdana" color="#330000">CheckIn Time:</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo $in_time; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">CheckOut Time:</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo $out_time; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Signature:</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo $signature; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Truck License (and State):</font></td>
		<td><input type="text" name="lic" size="20" maxlength="20" value="<? echo $lic; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana" color="#330000">Trailer License (and State):</font></td>
		<td><input type="text" name="trailer_lic" size="20" maxlength="20" value="<? echo $trailer_lic; ?>"></td>
	</tr>
<?
		if($auth_port == "WRITE"){
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save CheckIn Details"><hr></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">(Only the Port can edit these lines)</font><hr></td>
	</tr>
<?
		}
?>
</form>
</table>
<?
	}
?>
