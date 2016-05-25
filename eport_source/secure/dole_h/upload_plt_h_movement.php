<?


	$start_date = $HTTP_POST_VARS['start_date'];
	if($start_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $start_date)){
		echo "<font color=\"#FF0000\">Start Date must be in MM/DD/YYYY format</font>";
		$start_date = "";
	}
	$end_date = $HTTP_POST_VARS['end_date'];
	if($end_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $end_date)){
		echo "<font color=\"#FF0000\">End Date must be in MM/DD/YYYY format</font>";
		$end_date = "";
	}
	$plts = $HTTP_POST_VARS['plts'];
	if($plts != "" && (!is_numeric($plts) || $plts <= 0 || $plts != round($plts))) {
		echo "<font color=\"#FF0000\">Pallet Count must be a Positive Whole Number </font>";
		$plts = "";
	}
	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $start_date != "" && $end_date != "" && $plts != ""){
		$impfilename = date(mdYhis).".".basename($HTTP_POST_FILES['import_file']['name']);
		$target_path_import = "./acceptednew_files/".$impfilename;

		// entered values check out, file was successfully uploaded, insert into the DB.
		$sql = "SELECT RATE FROM TERMINAL_RATE
				WHERE COMMODITY_CODE = '1221'
					AND SERVICE_CODE = '6221'
					AND CUSTOMER_ID = '313'
					AND UNIT = 'PLT'";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			echo "<font color=\"#FF0000\">Could not find Rate in P.o.W. system.  Please call P.o.W.</font>";
			exit;
		}
		$rate = ociresult($stid, "RATE");

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		$sql = "SELECT MAX(BILLING_NUM) THE_MAX 
				FROM BILLING ";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$next_num = ociresult($stid, "THE_MAX") + 1;

		$sql = "INSERT INTO BILLING
					(CUSTOMER_ID,
					SERVICE_CODE,
					BILLING_NUM,
					EMPLOYEE_ID,
					SERVICE_START,
					SERVICE_STOP,
					SERVICE_AMOUNT,
					SERVICE_STATUS,
					SERVICE_DESCRIPTION,
					SERVICE_QTY,
					SERVICE_RATE,
					LR_NUM,
					ARRIVAL_NUM,
					COMMODITY_CODE,
					INVOICE_NUM,
					SERVICE_DATE,
					BILLING_TYPE,
					ASSET_CODE,
					GL_CODE)
				VALUES
					('313',
					'6221',
					'".$next_num."',
					'4',
					TO_DATE('".$end_date."', 'MM/DD/YYYY'),
					TO_DATE('".$end_date."', 'MM/DD/YYYY'),
					(".$rate." * ".$plts."),
					'PREINVOICE',
					'Warehouse H Truckloading Charges ".$start_date." - ".$end_date."   ".number_format($plts)." plts @ $".number_format($rate, 2)."/plt',
					'".$plts."',
					'".$rate."',
					'-1',
					'1',
					'1221',
					'0',
					TO_DATE('".$end_date."', 'MM/DD/YYYY'),
					'MISC',
					'WH00',
					'3074')";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);

		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					RUN_DELAY_MINUTES,
					COMPLETION_STATUS,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'WAREHOUSEHPLTCT',
					'10',
					'PENDING',
					'".$start_date.";".$end_date.";".$plts.";".$impfilename."')";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		echo "<font color=\"0000FF\" size=\"3\">Upload Complete.</font><br>";
	}

?>
<script type="text/javascript" src="/functions/calendar.js"></script>



<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Upload Weekly Data
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="upload_plt_h_movement_index.php" method="post">
	<tr>
		<td colspan="2"><a href="./acceptednew_files/?C=M;O=D">View Past Uploads</a><br><br></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Start Date:</b></td>
		<td><input type="text" name="start_date" size="10" maxlength="10" value="<? echo $start_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>End Date:</b></td>
		<td><input type="text" name="end_date" size="10" maxlength="10" value="<? echo $end_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font></td>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Grand Total of Pallets:</font></td>
		<td><input type="text" name="plts" size="5" maxlength="5" value="<? echo $plts; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
