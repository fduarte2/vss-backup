<?
/*
*	Adam Walter, Feb 2009
*
*	This page is a report for in-house rolls of Dole
*	Paper, and current orders against them.
*
*	Displays As-of the time report is generated.
********************************************************/

//	include 'class.ezpdf.php';
//	include("useful_info.php");
//	$short_term_data_cursor = ora_open($conn);
//	$dockticket_cursor = ora_open($conn);

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$xls_filename = "IH_Summary.xls";
	$handle = fopen("./".$xls_filename, "w");
	if (!$handle) {
		echo "File ".$xls_filename." could not be opened, please contact TS.\n";
		exit;
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dockticket Paper REAL TIME Inventory Summary
					<br>
						<a href="./<? echo $xls_filename; ?>">Click Here</a> For an Excel File.<br/></font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<?
	
	$output = "<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\">
				<tr>
					<td colspan=\"4\" align=\"center\"><font size=\"3\" face=\"Verdana\">Inventory For Dockticket Paper As Of:  ".date('m/d/Y h:i:s')."</font></td>
				</tr>
				<tr>
					<td><font size=\"2\" face=\"Verdana\"><b>P.O.</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Code</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Rolls</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>ST</b></font></td>
				</tr>";
	$total_rolls = 0;
	$total_st = 0;
	$sql = "SELECT SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) THE_PO, BATCH_ID THE_CODE, SUM(QTY_IN_HOUSE) THE_IH, SUM(WEIGHT / 2000) THE_ST
			FROM CARGO_TRACKING
			WHERE COMMODITY_CODE = '1272'
				AND QTY_IN_HOUSE > 0
				AND DATE_RECEIVED IS NOT NULL
			GROUP BY SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')), BATCH_ID";
	$dt_data = ociparse($rfconn, $sql);
	ociexecute($dt_data);
	if(!ocifetch($dt_data)){
		$output .= "<tr><td colspan=\"4\" align=\"center\"><b>No Shippable In-House Rolls to display</b></td></tr>";
	} else {
		do { // do this for each dock ticket
/*			$sql = "SELECT SUM(QTY_DAMAGED) THE_DMG, SUM(WEIGHT) THE_WEIGHT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE > 0 AND BOL = '".ociresult($dt_data, "BOL")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$DMG_Inhouse = ociresult($short_term_data, "THE_DMG");
			$WT_Inhouse = ociresult($short_term_data, "THE_WEIGHT"); */

		$total_rolls += ociresult($dt_data, "THE_IH");
		$total_st += round(ociresult($dt_data, "THE_ST"));

	$output .= "<tr>
					<td><font size=\"2\" face=\"Verdana\">".ociresult($dt_data, "THE_PO")."</font></td>
					<td><font size=\"2\" face=\"Verdana\">".ociresult($dt_data, "THE_CODE")."</font></td>
					<td><font size=\"2\" face=\"Verdana\">".ociresult($dt_data, "THE_IH")."</font></td>
					<td><font size=\"2\" face=\"Verdana\">".round(ociresult($dt_data, "THE_ST"))."</font></td>
				</tr>";
		} while(ocifetch($dt_data));
	$output .= "<tr>
					<td colspan=\"2\" align=\"right\"><font size=\"2\" face=\"Verdana\"><b>TOTALS</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$total_rolls."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$total_st."</b></font></td>
				</tr>";
	}
	$output .= "</table>";

	fwrite($handle, $output);
	fclose($handle);

	echo $output;