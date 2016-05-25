<?
/*
*	Adam Walter, Feb 2011.
*
*	A program that delves into the ARCHIVE tables to give INV
*	A TOTAL HISTORY of a pallet.
*
*	PLEASE NOTE:  Due to the scan time of the archive tables, this program
*	Takes a metric boatload of time to run.  As such, I make it it's own
*	Popup window so that INV can keep using their main window 
*	For other tasks in the meantime.
*************************************************************************/


 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$plt = $HTTP_POST_VARS['plt'];
	$submit = $HTTP_POST_VARS['submit'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td align="center"><font size="4" face="Verdana" color="#0066CC">DISCLAIMER:  This program will take a long time to finish.</font><br><font size="3" face="Verdana" color="#0066CC">You may continue to use the other screens while it is running.</font></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="CTCA_audit_check.php" method="post">
<!--	<tr>
		<td align="left">
			<font size="3" face="Verdana" color="#0066CC">Please Enter all 3 fields.</font>
		</td>
	</tr> !-->
	<tr>
		<td align="left">
			<font size="2" face="Verdana">Vessel:&nbsp;&nbsp;<input type="text" name="vessel" size="15" maxlength="15" value="<? echo $vessel; ?>">
		</td>
	</tr>
	<tr>
		<td align="left">
			<font size="2" face="Verdana">Cust (optional):&nbsp;&nbsp;<input type="text" name="cust" size="10" value="<? echo $cust; ?>">
		</td>
	</tr>
	<tr>
		<td align="left">
			<font size="2" face="Verdana">Pallet:&nbsp;&nbsp;<input type="text" name="plt" size="32" maxlength="32" value="<? echo $plt; ?>">
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="submit" name="submit" value="Retrieve Full History">
		</td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $vessel != "" && $plt != ""){
		$filename2 = $vessel."-".$plt."-".date('mdYhis').".xls";
		$reference_loc = "/temp/".$filename2;
		$fullpath = "/web/web_pages/".$reference_loc;
		$handle2 = fopen($fullpath, "w");

		if($cust != ""){
			$extra_CT_sql = " AND RECEIVER_ID = '".$cust."'";
			$extra_CA_sql = " AND CUSTOMER_ID = '".$cust."'";
			$cust_select = $cust;
		} else {
			$extra_CT_sql = "";
			$extra_CA_sql = "";
			$cust_select = "Not Specified";
		}

		$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"".$reference_loc."\">Click Here for the Excel File</a></b></font></td>
							</tr>
						</table><br><br>";
		echo $xls_link;

		
		$output_top = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td align=\"left\"><font size=\"3\" face=\"Verdana\"><b>Arrival#:  ".$vessel."</b></font></td>
								<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b>PLT#:  ".$plt."</b></font></td>
								<td align=\"right\"><font size=\"3\" face=\"Verdana\"><b>Cust#:  ".$cust_select."</b></font></td>
							</tr>
						</table><br><br>";


		// CARGO_TRACKING report
		$output_CT = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$CT_tablewidth = 9;

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT REC') DATE_REC, COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED,	
					QTY_DAMAGED, WAREHOUSE_LOCATION, QTY_IN_HOUSE, CARGO_STATUS, RECEIVER_ID
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$plt."'";
					$sql .= $extra_CT_sql;
		$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				ORDER BY RECEIVER_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output_CT .= "<tr><td align=\"center\"><font size=\"2\" face=\"Verdana\">No Current Pallets in CARGO_TRACKING for this criteria</font></td></tr>";
		} else {
			$output_CT .= "<tr>
								<td colspan=\"".$CT_tablewidth."\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>Current Status</b></font></td>
							</tr>
							<tr>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Cust</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Received</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Description</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY Received</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY damaged</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Location</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Status</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>In-House</b></font></td>
							</tr>";
			do {
				$output_CT .= "<tr>
									<td><font size=\"2\" face=\"Verdana\">".$row['RECEIVER_ID']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['DATE_REC']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['COMMODITY_CODE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CARGO_DESCRIPTION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_RECEIVED']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_DAMAGED']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_LOCATION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CARGO_STATUS']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_IN_HOUSE']."&nbsp;</font></td>
								</tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$output_CT .= "</table><br><br>";



		// CARGO_ACTIVITY report
		$output_CA = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$CA_tablewidth = 7;

		$sql = "SELECT NVL(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI'), 'NOT REC') DATE_ACT, ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE,	
					ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID
				FROM CARGO_ACTIVITY
				WHERE PALLET_ID = '".$plt."'";
					$sql .= $extra_CA_sql;
		$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				ORDER BY DATE_OF_ACTIVITY";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output_CA .= "<tr><td align=\"center\"><font size=\"2\" face=\"Verdana\">No Current Records in CARGO_ACTIVITY for this criteria</font></td></tr>";
		} else {
			$output_CA .= "<tr>
								<td colspan=\"".$CA_tablewidth."\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>Recorded Activity Listing</b></font></td>
							</tr>
							<tr>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Activity Date</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Activity Seq#</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Service Code</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY change</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Order#</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Extra Activty Description</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Cust#</b></font></td>
							</tr>";
			do {
				$output_CA .= "<tr>
									<td><font size=\"2\" face=\"Verdana\">".$row['DATE_ACT']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ACTIVITY_NUM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['SERVICE_CODE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_CHANGE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ORDER_NUM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ACTIVITY_DESCRIPTION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CUSTOMER_ID']."&nbsp;</font></td>
								</tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$output_CA .= "</table><br><br>";



		// CARGO_TRACKING_ARCHIVE report
		$output_CT_audit = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$CT_audit_tablewidth = 14;

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT REC') DATE_REC, COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED,	
					QTY_DAMAGED, WAREHOUSE_LOCATION, QTY_IN_HOUSE, CARGO_STATUS, RECEIVER_ID, AUDIT_FLAG,
					AUDIT_USERNAME, AUDIT_TIME, TO_CHAR(AUDIT_TIME, 'MM/DD/YYYY HH24:MI') AUDIT_TM, AUDIT_PROGRAM, AUDIT_HOSTNAME
				FROM (
					SELECT * FROM CARGO_TRACKING_AUDIT
					WHERE PALLET_ID = '".$plt."'";
						$sql .= $extra_CT_sql;
			$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				 UNION 
					SELECT * FROM CARGO_TRACKING_AUDIT_ARCHIVE
					WHERE PALLET_ID = '".$plt."'";
						$sql .= $extra_CT_sql;
			$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				)
				ORDER BY AUDIT_TIME, AUDIT_FLAG DESC";
//			echo $sql;
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output_CT_audit .= "<tr><td align=\"center\"><font size=\"2\" face=\"Verdana\">No Current Pallets in CARGO_TRACKING_AUDIT for this criteria</font></td></tr>";
		} else {
			$output_CT_audit .= "<tr>
								<td colspan=\"".$CT_audit_tablewidth."\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>History of Status Changes</b></font></td>
							</tr>
							<tr>
								<td><font size=\"2\" face=\"Verdana\"><b>Cust</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Received</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Description</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY Received</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY damaged</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Location</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Status</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>In-House</b></font></td>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Audited time</b></font></td>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Audited Action</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering User</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering Program</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering Program Host</b></font></td>
							</tr>";
			do {
				$bgcolor = returnBGColor($row['AUDIT_FLAG']);
				$output_CT_audit .= "<tr bgcolor=\"".$bgcolor."\">
									<td><font size=\"2\" face=\"Verdana\">".$row['RECEIVER_ID']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['DATE_REC']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['COMMODITY_CODE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CARGO_DESCRIPTION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_RECEIVED']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_DAMAGED']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_LOCATION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CARGO_STATUS']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_IN_HOUSE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_TM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_FLAG']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_USERNAME']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_PROGRAM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_HOSTNAME']."&nbsp;</font></td>
								</tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$output_CT_audit .= "</table><br><br>";



		// CARGO_ACTIVITY report
		$output_CA_audit = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$CA_audit_tablewidth = 12;

		$sql = "SELECT NVL(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI'), 'NOT REC') DATE_ACT, ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE,	
					ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, AUDIT_FLAG,
					AUDIT_USERNAME, AUDIT_TIME, TO_CHAR(AUDIT_TIME, 'MM/DD/YYYY HH24:MI') AUDIT_TM, AUDIT_PROGRAM, AUDIT_HOSTNAME
				FROM (
					SELECT * FROM CARGO_ACTIVITY_AUDIT
					WHERE PALLET_ID = '".$plt."'";
						$sql .= $extra_CA_sql;
			$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				 UNION 
					SELECT * FROM CARGO_ACTIVITY_AUDIT_ARCHIVE
					WHERE PALLET_ID = '".$plt."'";
						$sql .= $extra_CA_sql;
			$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				)
				WHERE PALLET_ID = '".$plt."'";
					$sql .= $extra_CA_sql;
		$sql .= " AND ARRIVAL_NUM = '".$vessel."'
				ORDER BY AUDIT_TIME, AUDIT_FLAG DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output_CA_audit .= "<tr><td align=\"center\"><font size=\"2\" face=\"Verdana\">No Current Records in CARGO_ACTIVITY for this criteria</font></td></tr>";
		} else {
			$output_CA_audit .= "<tr>
								<td colspan=\"".$CA_audit_tablewidth."\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>History of Activity Logs</b></font></td>
							</tr>
							<tr>
								<td><font size=\"2\" face=\"Verdana\"><b>Activity Date</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Activity Seq#</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Service Code</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY change</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Order#</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Extra Activty Description</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Cust#</b></font></td>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Audited time</b></font></td>
								<td bgcolor=\"DDDDFF\"><font size=\"2\" face=\"Verdana\"><b>Audited Action</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering User</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering Program</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Altering Program Host</b></font></td>
							</tr>";
			do {
				$bgcolor = returnBGColor($row['AUDIT_FLAG']);
				$output_CA_audit .= "<tr bgcolor=\"".$bgcolor."\">
									<td><font size=\"2\" face=\"Verdana\">".$row['DATE_ACT']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ACTIVITY_NUM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['SERVICE_CODE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['QTY_CHANGE']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ORDER_NUM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['ACTIVITY_DESCRIPTION']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['CUSTOMER_ID']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_TM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_FLAG']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_USERNAME']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_PROGRAM']."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$row['AUDIT_HOSTNAME']."&nbsp;</font></td>
								</tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$output_CA_audit .= "</table><br><br>";


		$the_output = $output_top.$output_CT.$output_CA.$output_CT_audit.$output_CA_audit;

		fwrite($handle2, $the_output);
		echo $the_output;
		fclose($handle2);
	}




function returnBGColor($activity_type){
	if($activity_type == "AFTER UPD"){
		$color = "#FFFF99";
	} elseif($activity_type == "INSERTED") {
		$color = "#CCFFCC";
	} elseif($activity_type == "DELETED") {
		$color = "#FF6666";
	} else {
		$color = "#FFFFFF";
	}

	return $color;
}

?>