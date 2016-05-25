<?

   // Define some vars for the skeleton page
   $title = "Current Temperature Report";
   $area_type = "GATE";

   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);
   $short_term_data_cursor = ora_open($conn);
?>
<html>
<body onload = "setTimeout('window.location.reload()', 30000)">
<center>
<table bgcolor=#ffffff>
   <tr>
	<td width=5%>&nbsp;</td>
	<td align=center><b><font size = 4 color=#000000>NONTWIC Driver Status</font></b></td>
	<td width=5%>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td align=center><i><font size = 3 color=#000000>Page Refreshed At <?= date(' h:i:s A (m/d/Y)')?></font></i></td>
        <td>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td>
	<table border=1 width=1080 cellspacing=1 cellpadding=1 bgcolor=#ffffff>
	    <tr bgcolor=#000000>
	   	<td width=80 height=30><font color="#FFFFFF"><b>Barcode</b></font></td>
                <td width=80><font color="#FFFFFF"><b>Latest Scan</b></font></td>
                <td width=80><font color="#FFFFFF"><b>Last Scanned By</b></font></td>
                <td width=150><font color="#FFFFFF"><b>Trucker Name</b></font></td>
                <td width=80><font color="#FFFFFF"><b>Driver License</b></font></td>
                <td width=80><font color="#FFFFFF"><b>License Plate</b></font></td>
				<td width=200><font color="#FFFFFF"><b>Company</b></font></td>
<!--                <td width=140><b>Request (OPS)</b></td>
                <td width=180><b>Current (Icetec)</b></td>
                <td width=180><b>E&amp;M Target</b></td>
                <td width=80><b>Req#</b></td> !-->

	    </tr>


<?
	// "active" trucks.
	$sql = "SELECT BARCODE, TO_CHAR(MAX(SCAN_TIME), 'MM/DD/YYYY/HH24/MI') THE_TIME, 
			TO_CHAR(MAX(SCAN_TIME), ' HH24:MI AM (MM/DD/YYYY)') DISP_TIME
			FROM NON_TWIC_SCANS WHERE BARCODE IN
			(SELECT BARCODE FROM NON_TWIC_TRUCKER_DETAIL
			WHERE STATUS = 'INPORT')
			AND BARCODE IN
			(SELECT BARCODE FROM NON_TWIC_SCANS
			WHERE USER_ID LIKE 'STGING%')
			GROUP BY BARCODE
			ORDER BY MAX(SCAN_TIME) ASC";

	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$now = mktime();
		$temp = explode("/", $row['THE_TIME']);
		$then = mktime($temp[3], $temp[4], 0, $temp[0], $temp[1], $temp[2]);

		$difference = $now - $then;
		if($difference > 1800){
			$bgcolor = "\"#FF0000\"";
		} elseif($difference > 1200) {
			$bgcolor = "\"#FFFF00\"";
		} else {
			$bgcolor = "\"#33FF00\"";
		}

		$sql = "SELECT USER_ID, FIRST_NAME, LAST_NAME, LISCENSE_NBR, COMPANY_NAME, SCAN_TIME, TRAILER_NBR
				FROM NON_TWIC_TRUCKER_DETAIL NTTD, NON_TWIC_SCANS NTS 
				WHERE NTS.BARCODE = '".$row['BARCODE']."'
				AND NTTD.BARCODE = NTS.BARCODE
				ORDER BY SCAN_TIME DESC";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<tr bgcolor=<? echo $bgcolor; ?>>
			<td><? echo $row['BARCODE']; ?></td>
			<td><? echo $row['DISP_TIME']; ?></td>
			<td><? echo $short_term_row['USER_ID']; ?></td>
			<td><? echo $short_term_row['FIRST_NAME']." ".$short_term_row['LAST_NAME']; ?></td>
			<td><? echo $short_term_row['LISCENSE_NBR']; ?></td>
			<td><? echo $short_term_row['TRAILER_NBR']; ?></td>
			<td><? echo $short_term_row['COMPANY_NAME']; ?></td>
		</tr>
<?
	}

	// "Currently In the Dole Yard" trucks
	$sql = "SELECT BARCODE, TO_CHAR(MAX(SCAN_TIME), 'MM/DD/YYYY/HH24/MI') THE_TIME, 
			TO_CHAR(MAX(SCAN_TIME), ' HH24:MI AM (MM/DD/YYYY)') DISP_TIME
			FROM NON_TWIC_SCANS WHERE BARCODE IN
			(SELECT BARCODE FROM NON_TWIC_TRUCKER_DETAIL
			WHERE STATUS = 'INDOLE')
			GROUP BY BARCODE
			ORDER BY MAX(SCAN_TIME) ASC";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
/*		$now = mktime();
		$temp = explode("/", $row['THE_TIME']);
		$then = mktime($temp[3], $temp[4], 0, $temp[0], $temp[1], $temp[2]);

		$difference = $now - $then;
*/
		$bgcolor = "\"#66AAFC\"";

		$sql = "SELECT USER_ID, FIRST_NAME, LAST_NAME, LISCENSE_NBR, COMPANY_NAME, SCAN_TIME, TRAILER_NBR
				FROM NON_TWIC_TRUCKER_DETAIL NTTD, NON_TWIC_SCANS NTS 
				WHERE NTS.BARCODE = '".$row['BARCODE']."'
				AND NTTD.BARCODE = NTS.BARCODE
				ORDER BY SCAN_TIME DESC";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<tr bgcolor=<? echo $bgcolor; ?>>
			<td><? echo $row['BARCODE']; ?></td>
			<td><? echo $row['DISP_TIME']; ?></td>
			<td><? echo $short_term_row['USER_ID']; ?></td>
			<td><? echo $short_term_row['FIRST_NAME']." ".$short_term_row['LAST_NAME']; ?></td>
			<td><? echo $short_term_row['LISCENSE_NBR']; ?></td>
			<td><? echo $short_term_row['TRAILER_NBR']; ?></td>
			<td><? echo $short_term_row['COMPANY_NAME']; ?></td>
		</tr>
<?
	}
	// "passive" trucks.
	$sql = "SELECT BARCODE, TO_CHAR(MAX(SCAN_TIME), 'MM/DD/YYYY/HH24/MI') THE_TIME, 
			TO_CHAR(MAX(SCAN_TIME), ' HH24:MI AM (MM/DD/YYYY)') DISP_TIME
			FROM NON_TWIC_SCANS WHERE BARCODE IN
			(SELECT BARCODE FROM NON_TWIC_TRUCKER_DETAIL
			WHERE STATUS = 'INPORT')
			AND BARCODE NOT IN
			(SELECT BARCODE FROM NON_TWIC_SCANS
			WHERE USER_ID LIKE 'STGING%')
			GROUP BY BARCODE
			ORDER BY MAX(SCAN_TIME) ASC";

	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$now = mktime();
		$temp = explode("/", $row['THE_TIME']);
		$then = mktime($temp[3], $temp[4], 0, $temp[0], $temp[1], $temp[2]);

		$difference = $now - $then;
/*		if($difference > 1800){
			$bgcolor = "\"#FF0000\"";
		} elseif($difference > 1200) {
			$bgcolor = "\"#FFFF00\"";
		} else {
			$bgcolor = "\"#33FF00\"";
		}*/
		$bgcolor = "\"#FFFFFF\"";

		$sql = "SELECT USER_ID, FIRST_NAME, LAST_NAME, LISCENSE_NBR, COMPANY_NAME, SCAN_TIME, TRAILER_NBR
				FROM NON_TWIC_TRUCKER_DETAIL NTTD, NON_TWIC_SCANS NTS 
				WHERE NTS.BARCODE = '".$row['BARCODE']."'
				AND NTTD.BARCODE = NTS.BARCODE
				ORDER BY SCAN_TIME DESC";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<tr bgcolor=<? echo $bgcolor; ?>>
			<td><? echo $row['BARCODE']; ?></td>
			<td><? echo $row['DISP_TIME']; ?></td>
			<td><? echo $short_term_row['USER_ID']; ?></td>
			<td><? echo $short_term_row['FIRST_NAME']." ".$short_term_row['LAST_NAME']; ?></td>
			<td><? echo $short_term_row['LISCENSE_NBR']; ?></td>
			<td><? echo $short_term_row['TRAILER_NBR']; ?></td>
			<td><? echo $short_term_row['COMPANY_NAME']; ?></td>
		</tr>
<?
	}
?>
	</table>
	</td>
        <td width=2%>&nbsp;</td>
   </tr>
</table>
</body>
</html>
