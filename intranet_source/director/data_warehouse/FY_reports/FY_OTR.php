<?
/*
*	Adam Walter, Oct 16, 2009.
*
*	This page is designed to be a yearly recap of RF cargo, based
*	On selections of type
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$select_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$FY = $HTTP_POST_VARS['FY'];
	$arv = $HTTP_POST_VARS['arv'];
	$comm = $HTTP_POST_VARS['comm'];
	$cust = $HTTP_POST_VARS['cust'];
	$vessel = $HTTP_POST_VARS['vessel'];
//	$submit = $HTTP_POST_VARS['submit'];

	$filename = "RFFY".date('mdYhi')."OTR.xls";

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">RF OTR Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	// create the XLS file
	$fp = fopen($filename, "w");
	if(!$fp){
		echo "error opening file.  Please contact TS";
		include("pow_footer.php");
		exit;
	} else {
		$comm_counter = 0;
		$comm_info = array();
		$comm_sql_list = "('999999'";

		GetTableNames($cargo_tracking, $cargo_activity, $comm, $FY);

		if($cust == "all"){
			$sql_cust = "!= '453'";
		} else {
			$sql_cust = "= '".$cust."'";
		}
		if($vessel == "all"){
			$sql_vessel = "!= '4321'";
		} else {
			$sql_vessel = "= '".$vessel."'";
		}
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, CP.COMMODITY_NAME THE_COMM 
		FROM ".$cargo_tracking." CT, COMMODITY_PROFILE CP 
		WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE 
		AND CP.COMMODITY_TYPE = '".$comm."' 
		AND CT.RECEIVER_ID ".$sql_cust." 
		AND CT.ARRIVAL_NUM ".$sql_vessel." 
		AND	CT.DATE_RECEIVED IS NOT NULL 
		AND CT.DATE_RECEIVED >= TO_DATE('07/01/".($FY - 1)."', 'MM/DD/YYYY') 
		AND CT.DATE_RECEIVED < TO_DATE('07/01/".$FY."', 'MM/DD/YYYY') 
		AND CT.RECEIVING_TYPE = 'T' 
		GROUP BY CP.COMMODITY_NAME ORDER BY CP.COMMODITY_NAME";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$comm_info[$comm_counter]["comm"] = $row["THE_COMM"];
			$comm_info[$comm_counter]["plt_count"] = $row["THE_COUNT"];

			$comm_sql_list .= ", '".$row["THE_COMM"]."'";

			$comm_counter++;
		}

		$comm_sql_list .= ")";

		// heading:  vessels
		fwrite($fp, "\tComm\t");
		for($i = 0; $i < $comm_counter; $i++){
			fwrite($fp, $comm_info[$i]["comm"]."\t");
		}
		fwrite($fp, "Totals\n");

		// heading:  Received
		fwrite($fp, "\tReceived\t");
		$total_rec = 0;
		for($i = 0; $i < $comm_counter; $i++){
			fwrite($fp, $comm_info[$i]["plt_count"]."\t");
			$total_rec += $comm_info[$i]["plt_count"];
		}
		fwrite($fp, $total_rec."\n");

		fwrite($fp, "Whse-day\n1\t\t");
		for($i = 0; $i < $comm_counter; $i++){
			fwrite($fp, $comm_info[$i]["plt_count"]."\t");
		}
		fwrite($fp, $total_rec."\n");


		$comm_chart = array();
		$sql = "SELECT COUNT(*) THE_COUNT, CP.COMMODITY_NAME THE_COMM, (TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY') - TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')) + 1 DAYS_HERE 
				FROM ".$cargo_tracking." CT, ".$cargo_activity." CA, COMMODITY_PROFILE CP 
				WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE 
				AND CP.COMMODITY_TYPE = '".$comm."' 
				AND CT.RECEIVER_ID ".$sql_cust." 
				AND CT.ARRIVAL_NUM ".$sql_vessel." 
				AND CT.DATE_RECEIVED IS NOT NULL 
				AND CT.DATE_RECEIVED >= TO_DATE('07/01/".($FY - 1)."', 'MM/DD/YYYY') 
				AND CT.DATE_RECEIVED < TO_DATE('07/01/".$FY."', 'MM/DD/YYYY') 
				AND CT.RECEIVING_TYPE = 'T' 
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CT.PALLET_ID = CA.PALLET_ID 
				AND CA.QTY_CHANGE >= (0.75 * CT.QTY_RECEIVED) 
				AND CA.SERVICE_CODE IN ('6', '11') 
				GROUP BY CP.COMMODITY_NAME, (TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY') - TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')) + 1 ORDER BY (TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY') - TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')) + 1, CP.COMMODITY_NAME";
		ora_parse($select_cursor, $sql);
		ora_exec($select_cursor);
		while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$max_days = $select_row['DAYS_HERE']; // by the time we get to the last row, this will be the maximum number of days here.
			$comm_chart[$select_row['THE_COMM']][$select_row['DAYS_HERE']] = $select_row['THE_COUNT'];
		}

		$day_number = 2;

		while($day_number < $max_days){
			$comm_this_row = 0;
			fwrite($fp, $day_number."\t\t");
			while($comm_this_row < $comm_counter){
				// for each day, we want to subtract the number of pallets that went out the day *before*, hence -1 on day number
				$comm_info[$comm_this_row]["plt_count"] -= $comm_chart[$comm_info[$comm_this_row]["comm"]][$day_number - 1];
				$total_rec -= $comm_chart[$comm_info[$comm_this_row]["comm"]][$day_number - 1];
				fwrite($fp, $comm_info[$comm_this_row]["plt_count"]."\t");
				$comm_this_row++;
			}
			fwrite($fp, $total_rec."\n");
			$day_number++;
		}
		fclose($fp);
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>Click <a href="<? echo $filename; ?>">Here</a> for the file.</td>
	</tr>
</table>
<?
	include("pow_footer.php");
?>





<?



function GetTableNames(&$cargo_tracking, &$cargo_activity, $comm, $FY){
	
	if($comm != "CHILEAN" && $comm != "CLEMENTINE"){
		$cargo_tracking = "CARGO_TRACKING";
		$cargo_activity = "CARGO_ACTIVITY";
		return;
	} else {
		if($FY == date("Y", mktime(0,0,0,date("m") + 6, date("d"), date("Y")))){
			$cargo_tracking = "CARGO_TRACKING";
			$cargo_activity = "CARGO_ACTIVITY";
			return;
		} else {
			if($comm == "CHILEAN"){
				$cargo_tracking = "CARGO_TRACKING_".$FY;
				$cargo_activity = "CARGO_ACTIVITY_".$FY;
				return;
			} else { // $comm = "CLEMENTINE"
				$table_suffix = date("y", mktime(0,0,0,date("m") + 6, date("d"), $FY - 1)).date("y", mktime(0,0,0,date("m") + 3, date("d"), $FY));
				$cargo_tracking = "CT_ARCHIVE_CLEM_".$table_suffix;
				$cargo_activity = "CA_ARCHIVE_CLEM_".$table_suffix;
				return;
			}				
		}
	}
}



?>