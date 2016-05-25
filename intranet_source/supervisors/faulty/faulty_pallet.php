<?
/*
*	Adam Walter, Apr 2014
*
*	This page is a one-stop shop for argen Juice.
*	Displays pallets, BoL, Mark (all),
*	Date received (only for those received),
*	And date and quantity shipped (only for those with activity records)
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisor System";
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

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Faulty Pallet Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="faulty_pallet.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX IN ('ARG JUICE', 'CHILEAN') ORDER BY LR_NUM DESC";
		$vessels = ociparse($rfconn, $sql);
		ociexecute($vessels);
		while(ocifetch($vessels)){
?>
						<option value="<? echo ociresult($vessels, "LR_NUM"); ?>"<? if(ociresult($vessels, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($vessels, "THE_VESSEL") ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td align="left"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($vessel != "" && $submit != ""){
		$filename = "faulty_".$vessel."_".date('mdyhis').".xls";
		$fp = fopen($filename, "w");
		if(!$fp){
			echo "can not open file for writing, please contact TS";
			exit;
		}

		$output = "<tr>
						<td><b>Receiver</b></td>
						<td><b>Commodity</b></td>
						<td><b>Pallet</b></td>
						<td><b>Warhouse Location</b></td>
						<td><b>QTY Received</b></td>
						<td><b>Scanned</b></td>
						<td><b>Checker</b></td>
					</tr>";
		
		$sql = "SELECT CUSTOMER_NAME, COMMODITY_NAME, CT.PALLET_ID, CT.WAREHOUSE_LOCATION, QTY_RECEIVED, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC, ACTIVITY_ID, CT.RECEIVER_ID
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND CTAD.PLT_FAULT = 'Y'
					AND CA.ACTIVITY_NUM = 1
				ORDER BY CT.RECEIVER_ID";
		$pallets = ociparse($rfconn, $sql);
		ociexecute($pallets);
		if(!ocifetch($pallets)){
			$output .= "<tr><td colspan=\"7\">No Faulty Pallets found.</td></tr>";
		} else {
			$cust_plt = 0;
			$cust_ctns = 0;
			$total_plt = 0;
			$total_ctn = 0;
			$current_cust = ociresult($pallets, "RECEIVER_ID");
			$output .= "<tr bgcolor=\"#CCFF99\"><td colspan=\"7\">".ociresult($pallets, "CUSTOMER_NAME")."</td></tr>";
			do {
				if(ociresult($pallets, "RECEIVER_ID") != $current_cust){
					$output .= "<tr bgcolor=\"#CCCC99\">
									<td colspan=\"2\">Customer Totals</td>
									<td>Pallets:  ".$cust_plt."</td>
									<td>&nbsp</td>
									<td>Cartons:  ".$cust_ctns."</td>
									<td colspan=\"2\">&nbsp</td>
								</tr>";
					$output .= "<tr bgcolor=\"#CCFF99\"><td colspan=\"7\">".ociresult($pallets, "CUSTOMER_NAME")."</td></tr>";
					$current_cust = ociresult($pallets, "RECEIVER_ID");
					$cust_plt = 0;
					$cust_ctns = 0;
				}

				$output .= "<tr>
								<td>&nbsp;</td>
								<td>".ociresult($pallets, "COMMODITY_NAME")."</td>
								<td>".ociresult($pallets, "PALLET_ID")."</td>
								<td>".ociresult($pallets, "WAREHOUSE_LOCATION")."&nbsp;</td>
								<td>".ociresult($pallets, "QTY_RECEIVED")."</td>
								<td>".ociresult($pallets, "DATE_REC")."</td>
								<td>".get_employee_for_print(ociresult($pallets, "PALLET_ID"), $vessel, ociresult($pallets, "RECEIVER_ID"), 1, $rfconn)."</td>
							</tr>";
				$cust_plt++;
				$cust_ctns += ociresult($pallets, "QTY_RECEIVED");
				$total_plt++;
				$total_ctns += ociresult($pallets, "QTY_RECEIVED");
			} while(ocifetch($pallets));

			$output .= "<tr bgcolor=\"#CCCC99\">
							<td colspan=\"2\">Customer Totals</td>
							<td>Pallets:  ".$cust_plt."</td>
							<td>&nbsp</td>
							<td>Cartons:  ".$cust_ctns."</td>
							<td colspan=\"2\">&nbsp</td>
						</tr>";
			$output .= "<tr bgcolor=\"#CCCC99\">
							<td colspan=\"2\">Grand Totals</td>
							<td>Pallets:  ".$total_plt."</td>
							<td>&nbsp</td>
							<td>Cartons:  ".$total_ctns."</td>
							<td colspan=\"2\">&nbsp</td>
						</tr>";
		}
		fwrite($fp, "<table>".$output."</table>");
		fclose($fp);

?>
<table border="1" width="100%"><tr><td colspan="7" align="center"><a href="<? echo $filename; ?>">Click Here</a> for an XLS version of this report.</a></td></tr><? echo $output; ?></table>
<?
	}















































function get_employee_for_print($Barcode, $LR, $cust, $act_num, $rfconn){

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$date_act = ociparse($rfconn, $sql);
	ociexecute($date_act);
	ocifetch($date_act);

	$date = ociresult($date_act, "THE_DATE");
	$emp_no = ociresult($date_act, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$max_date = ociparse($rfconn, $sql);
	ociexecute($max_date);
	ocifetch($max_date);
	if(ociresult($max_date, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($emp_no).") = '".$emp_no."'"; 
	}
//	echo $sql."\n";
	$emp_name = ociparse($rfconn, $sql);
	ociexecute($emp_name);
	ocifetch($emp_name);

	return ociresult($emp_name, "THE_EMP");
}
