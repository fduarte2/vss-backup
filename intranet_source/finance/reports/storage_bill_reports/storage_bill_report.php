<?
/*
*	Adam Walter, Jan 2011.
*
*	Report detailing storage bills for Jon
*	Capable of reading from both BNI and RF (despite the inhereant
*	non-similarities between the systems)
*******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Directors - Storage Bill Report";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }


	$connRF = ora_logon("SAG_OWNER@RF", "OWNER");
//	$connRF = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($connRF < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($connRF));
		exit;
	}
	$Short_Term_Cursor_RF = ora_open($connRF);
	$cursor_RF = ora_open($connRF);

	$connBNI = ora_logon("SAG_OWNER@BNI", "SAG");
//	$connBNI = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($connBNI < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($connBNI));
		exit;
	}
	$Short_Term_Cursor_BNI = ora_open($connBNI);
	$cursor_BNI = ora_open($connBNI);

	$from_date = $HTTP_POST_VARS['from_date'];
	$to_date = $HTTP_POST_VARS['to_date'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];

?>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

<script language="JavaScript" src="../../../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Storage Information Lookup
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_date" action="storage_bill_report.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Start Date:</b></font></td>
		<td align="left"><input name="from_date" type="text" size="10" maxlength="10" value="<? echo $from_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_date.from_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>End Date:</b></font></td>
		<td align="left"><input name="to_date" type="text" size="10" maxlength="10" value="<? echo $to_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_date.to_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td align="left"><select name="cust"><option value="all">All</option>
<?
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			ORDER BY CUSTOMER_ID";
	ora_parse($Short_Term_Cursor_RF, $sql);
	ora_exec($Short_Term_Cursor_RF);
	while(ora_fetch_into($Short_Term_Cursor_RF, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['CUSTOMER_ID']; ?>" <? if($row['CUSTOMER_ID'] == $cust){?>selected<?}?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
	}
?>		
		</select></td>
	</tr>
<?
	$button_name = "Retrieve Commodities";
	if($submit != "" && $from_date != "" && $to_date != ""){
		$button_name = "Retrieve Bills";
?>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Commodity Type:</b></font></td>
		<td align="left"><select name="comm">
<?
				FillCommDropdown($from_date, $to_date, $cust, $comm, $connRF, $connBNI);
?>
				</select></td>
	</tr>
<?
	}
?>		
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="<? echo $button_name; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	</form>
</table>

<?
	if($comm != ""){
		$filename = "STR".date('mdYhis').".xls";
		$handle = fopen($filename, "w");
?>
<font size="3" face="Verdana"><a href="<? echo $filename; ?>">Click Here for the tab-delimited .XLS file</a></font><br><br><br>
<table border="1" cellpadding="4" cellspacing="0"> <!-- width="100%"  !-->
<?
		if($cust != "all"){
			$extra_sql = "AND CP.CUSTOMER_ID = '".$cust."'";
		} else {
			$extra_sql = "";
		}

		
		$temp = split("-", $comm);

		$sql_comm = $temp[0];
		$system = $temp[1];
?>
		<? fwrite($handle, $from_date." - ".$to_date."            Commodity: ".$comm."\n"); ?>
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana"><b><? echo $from_date." - ".$to_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Commodity:&nbsp;<? echo $comm; ?></b></font></td>
	</tr>
<?
		if($system == "SCANNED"){
			$sql = "SELECT CUSTOMER_NAME, SUM(SERVICE_AMOUNT) THE_SUM
					FROM RF_BILLING RB, CUSTOMER_PROFILE CP
					WHERE SERVICE_START >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
						AND SERVICE_START <= TO_DATE('".$to_date."', 'MM/DD/YYYY')
						AND BILLING_TYPE = 'PLT-STRG'
						AND SERVICE_STATUS = 'INVOICED'
						".$extra_sql."
						AND DECODE(COMMODITY_CODE, 5101, 8060, COMMODITY_CODE) IN
							(SELECT COMMODITY_CODE
							FROM COMMODITY_PROFILE
							WHERE COMMODITY_TYPE = '".$sql_comm."'
							)
						AND RB.CUSTOMER_ID = CP.CUSTOMER_ID
					GROUP BY CUSTOMER_NAME
					ORDER BY CUSTOMER_NAME";

			$report_cursor = ora_open($connRF);
		} else {
			$sql = "SELECT CUSTOMER_NAME, SUM(SERVICE_AMOUNT) THE_SUM
					FROM BILLING BIL, CUSTOMER_PROFILE CP
					WHERE SERVICE_START >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
						AND SERVICE_START <= TO_DATE('".$to_date."', 'MM/DD/YYYY')
						AND BILLING_TYPE = 'STORAGE'
						AND SERVICE_STATUS = 'INVOICED'
						".$extra_sql."
						AND COMMODITY_CODE IN
							(SELECT COMMODITY_CODE
							FROM COMMODITY_PROFILE
							WHERE COMMODITY_TYPE = '".$sql_comm."'
							)
						AND BIL.CUSTOMER_ID = CP.CUSTOMER_ID
					GROUP BY CUSTOMER_NAME
					ORDER BY CUSTOMER_NAME";

			$report_cursor = ora_open($connBNI);
		}
		ora_parse($report_cursor, $sql);
		ora_exec($report_cursor);
		if(!ora_fetch_into($report_cursor, $report_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<? fwrite($handle, "No Billed Records for Given Selections\n"); ?>
	<tr>
		<td align="center">No Billed Records for Given Selections</td>
	</tr>
<?
		} else {
?>
			<? fwrite($handle, "Customer\tAmount\n"); ?>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
	</tr>
<?
			do {
?>
			<? fwrite($handle, $report_row['CUSTOMER_NAME']."\t".$report_row['THE_SUM']."\n"); ?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $report_row['CUSTOMER_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $report_row['THE_SUM']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($report_cursor, $report_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		fclose($handle);
	}
	

	include("pow_footer.php");

















function FillCommDropdown($from_date, $to_date, $cust, $comm, $connRF, $connBNI){
	$Short_Term_Cursor_RF = ora_open($connRF);
	$Short_Term_Cursor_BNI = ora_open($connBNI);

	if($cust != "all"){
		$extra_sql = "AND CUSTOMER_ID = '".$cust."'";
	} else {
		$extra_sql = "";
	}

	$output = "";

	// from RF first, since it has the lion's share of storage bills
	// DECODE statement is because "5101" doesn't exist in RF-COMMODITY_PROFILE, but represents CHILEAN
	$sql = "SELECT DISTINCT COMMODITY_TYPE
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_CODE IN
				(SELECT DECODE(COMMODITY_CODE, 5101, 8060, COMMODITY_CODE)
				FROM RF_BILLING
				WHERE SERVICE_START >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
				AND SERVICE_START <= TO_DATE('".$to_date."', 'MM/DD/YYYY')
				AND BILLING_TYPE = 'PLT-STRG'
				AND SERVICE_STATUS = 'INVOICED'
				".$extra_sql."
				)
			ORDER BY COMMODITY_TYPE";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor_RF, $sql);
	ora_exec($Short_Term_Cursor_RF);
	while(@ora_fetch_into($Short_Term_Cursor_RF, $row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output = $row_RF['COMMODITY_TYPE']."-SCANNED";
?>
				<option value="<? echo $output; ?>"<? if($output == $comm){?> selected <?}?>><? echo $output; ?></option>
<?
	}



	// and BNI
	$sql = "SELECT DISTINCT COMMODITY_TYPE
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_CODE IN
				(SELECT COMMODITY_CODE
				FROM BILLING
				WHERE SERVICE_START >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
				AND SERVICE_START <= TO_DATE('".$to_date."', 'MM/DD/YYYY')
				AND BILLING_TYPE = 'STORAGE'
				AND SERVICE_STATUS = 'INVOICED'
				".$extra_sql."
				)
			ORDER BY COMMODITY_TYPE";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor_BNI, $sql);
	ora_exec($Short_Term_Cursor_BNI);
	while(@ora_fetch_into($Short_Term_Cursor_BNI, $row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output = $row_BNI['COMMODITY_TYPE']."-UNSCANNED";
?>
				<option value="<? echo $output; ?>"<? if($output == $comm){?> selected <?}?>><? echo $output; ?></option>
<?
	}

	if($output == ""){
		// no results at ALL
?>
				<option value="">No Billed Commodities found</option>
<?
	}
}