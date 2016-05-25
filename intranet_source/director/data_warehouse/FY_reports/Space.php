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
//	$submit = $HTTP_POST_VARS['submit'];

	if($comm == "ARG JUICE"){
		$days_to_show = 299;
	} else {
		$days_to_show = 99;
	}

	$filename = "RFFY".date('mdYhi')."vessel.xls";

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">RF Vessel Report
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
		$vessel_counter = 0;
		$vessel_info = array();
		$vessel_sql_list = "('999999'";

		GetTableNames($cargo_tracking, $cargo_activity, $comm, $FY);

		if($cust == "all"){
			$sql_cust = "!= '453'";
		} else {
			$sql_cust = "= '".$cust."'";
		}
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, VP.LR_NUM, TO_CHAR(VP.FREE_TIME_END, 'MM/DD/YYYY') THE_FREE_END, TO_CHAR(VOY.DATE_DEPARTED, 'MM/DD/YYYY') THE_DEP, NVL(VP.FREE_TIME_END - DATE_DEPARTED, 0) THE_FREE_DAYS 
				FROM VESSEL_PROFILE VP, VOYAGE VOY, ".$cargo_tracking." CT, COMMODITY_PROFILE CP 
				WHERE VOY.LR_NUM = VP.LR_NUM 
				AND TO_CHAR(VP.LR_NUM) = CT.ARRIVAL_NUM 
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE 
				AND CP.COMMODITY_TYPE = '".$comm."' 
				AND CT.RECEIVER_ID ".$sql_cust." 
				AND CT.ARRIVAL_NUM != '4321' 
				AND CT.DATE_RECEIVED IS NOT NULL 
				AND CT.DATE_RECEIVED >= TO_DATE('07/01/".($FY - 1)."', 'MM/DD/YYYY') 
				AND CT.DATE_RECEIVED < TO_DATE('07/01/".$FY."', 'MM/DD/YYYY') 
				GROUP BY VP.LR_NUM, TO_CHAR(VP.FREE_TIME_END, 'MM/DD/YYYY'), TO_CHAR(VOY.DATE_DEPARTED, 'MM/DD/YYYY'), NVL(VP.FREE_TIME_END - DATE_DEPARTED, 0) ORDER BY VP.LR_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$vessel_info[$vessel_counter]["ship"] = $row["LR_NUM"];
			$vessel_info[$vessel_counter]["free_end"] = $row["THE_FREE_END"];
			$vessel_info[$vessel_counter]["date_dep"] = $row["THE_DEP"];
			$vessel_info[$vessel_counter]["plt_count"] = $row["THE_COUNT"];
			$vessel_info[$vessel_counter]["free_days"] = $row["THE_FREE_DAYS"];

			$vessel_sql_list .= ", '".$row["LR_NUM"]."'";

			$vessel_counter++;
		}

		$vessel_sql_list .= ")";

		// heading:  vessels
		fwrite($fp, "\tLR#\t");
		for($i = 0; $i < $vessel_counter; $i++){
			fwrite($fp, $vessel_info[$i]["ship"]."\t");
		}
		//fwrite($fp, "Totals\n");

		// heading: Free Time End
		//fwrite($fp, "\tFree Time End\t");
		for($i = 0; $i < $vessel_counter; $i++){
			//fwrite($fp, $vessel_info[$i]["free_end"]."\t");
		}
		//fwrite($fp, "\n");

		// heading:  Date Departed
		fwrite($fp, "\tDeparted\t");
		for($i = 0; $i < $vessel_counter; $i++){
			fwrite($fp, $vessel_info[$i]["date_dep"]."\t");
		}
		fwrite($fp, "\n");

		// heading:  Received
//		fwrite($fp, "\tReceived\t");
		$total_rec = 0;
		for($i = 0; $i < $vessel_counter; $i++){
			fwrite($fp, $vessel_info[$i]["date_dep"]."\t");
			fwrite($fp, "0"."\t");
			fwrite($fp, $vessel_info[$i]["ship"]."\t");
			fwrite($fp, $vessel_info[$i]["plt_count"]."\n");
			$total_rec += $vessel_info[$i]["plt_count"];
		}
//		fwrite($fp, $total_rec."\n");
//		fwrite($fp, "Days After Departure\n1\t\t");

		// get all outgoing pallets BEFORE FINANCE DATE DEPARTED
		$current_vessel = 0;
		$day_one_total = 0;
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, CT.ARRIVAL_NUM 
				FROM ".$cargo_activity." CA, VOYAGE VOY, ".$cargo_tracking." CT, COMMODITY_PROFILE COMP 
				WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE 
				AND COMP.COMMODITY_TYPE = '".$comm."' 
				AND CA.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM) 
				AND CA.ARRIVAL_NUM IN ".$vessel_sql_list." 
				AND CA.SERVICE_CODE IN ('6', '11') 
				AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) 
				AND CA.PALLET_ID = CT.PALLET_ID 
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM 
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
				AND CA.QTY_CHANGE >= (0.75 * CT.QTY_RECEIVED) 
				AND CA.DATE_OF_ACTIVITY < VOY.DATE_DEPARTED + 1 
				AND CT.RECEIVER_ID ".$sql_cust." 
				GROUP BY CT.ARRIVAL_NUM ORDER BY CT.ARRIVAL_NUM";
//		echo $sql;
		ora_parse($select_cursor, $sql);
		ora_exec($select_cursor);
		ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		while($current_vessel < $vessel_counter){
			if($select_row['ARRIVAL_NUM'] == $vessel_info[$current_vessel]["ship"]){
				$vessel_info[$current_vessel]["plt_count"] -= $select_row['THE_COUNT'];
				ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			}
			
			$day_one_total += $vessel_info[$current_vessel]["plt_count"];
			fwrite($fp, $vessel_info[$current_vessel]["date_dep"]."\t");
			fwrite($fp, "1"."\t");
			fwrite($fp, $vessel_info[$current_vessel]["ship"]."\t");
			fwrite($fp, $vessel_info[$current_vessel]["plt_count"]."\n");  // IF YOU WANT TO INDICATE FREE DAYS, EDIT THIS LINE
			$current_vessel++;
		}

		fwrite($fp, $day_one_total."\n");

		// loop each day to continue to decrease pallets.
		$date_offset = 1;
		$continue_loop = true;
		while($continue_loop){
			$continue_loop = false; // to prevent infinite looping
			$current_vessel = 0;
			$day_total = 0;
			$display_total_flag = true;
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, CT.ARRIVAL_NUM 
					FROM ".$cargo_activity." CA, VOYAGE VOY, ".$cargo_tracking." CT, COMMODITY_PROFILE COMP 
					WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE 
					AND COMP.COMMODITY_TYPE = '".$comm."' 
					AND CA.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM) 
					AND CA.ARRIVAL_NUM IN ".$vessel_sql_list." 
					AND CA.SERVICE_CODE IN ('6', '11') 
					AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) 
					AND CA.PALLET_ID = CT.PALLET_ID 
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM 
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
					AND CA.QTY_CHANGE >= (0.75 * CT.QTY_RECEIVED) 
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = TO_CHAR((VOY.DATE_DEPARTED + ".$date_offset."), 'MM/DD/YYYY') 
					AND CT.RECEIVER_ID ".$sql_cust." 
					GROUP BY CT.ARRIVAL_NUM ORDER BY CT.ARRIVAL_NUM";

			//fwrite($fp, ($date_offset + 1)."\n");

			ora_parse($select_cursor, $sql);
			ora_exec($select_cursor);
			if(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				while($current_vessel < $vessel_counter){
					if($select_row['ARRIVAL_NUM'] == $vessel_info[$current_vessel]["ship"]){
						$vessel_info[$current_vessel]["plt_count"] -= $select_row['THE_COUNT'];
						@ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					}
					
					if($vessel_info[$current_vessel]["plt_count"] > 0){
						$continue_loop = true; // if there is a non-0 entry, continue the loop
					}
					if($date_offset >= $days_to_show){
						$continue_loop = false; // break loop if we have already 100 days on it
					}

					$day_total += $vessel_info[$current_vessel]["plt_count"];
					
					$temp = explode("/", $vessel_info[$current_vessel]["date_dep"]);
					$cur_vessel_plus_offset = mktime(0,0,0,$temp[0],$temp[1] + $date_offset,$temp[2]);
					if($cur_vessel_plus_offset > $today){
						fwrite($fp, "N/A\t");
						$display_total_flag = false;
//					if($vessel_info[$current_vessel]["free_days"] >= $date_offset){
//						fwrite($fp, $vessel_info[$current_vessel]["plt_count"]."\t");  // IF YOU WANT TO INDICATE FREE DAYS, EDIT THIS LINE
					} else {
						fwrite($fp, $vessel_info[$current_vessel]["date_dep"]."\t");
						fwrite($fp, 1+$date_offset."\t");
						fwrite($fp, $vessel_info[$current_vessel]["ship"]."\t");
						fwrite($fp, $vessel_info[$current_vessel]["plt_count"]."\n");
					}
					$current_vessel++;
				}
			} else {
				while($current_vessel < $vessel_counter){
					if($vessel_info[$current_vessel]["plt_count"] > 0){
						$continue_loop = true; // if there is a non-0 entry, continue the loop
					}
					if($date_offset >= $days_to_show){
						$continue_loop = false; // break loop if we have already 100 days on it
					}

					$day_total += $vessel_info[$current_vessel]["plt_count"];

					$temp = explode("/", $vessel_info[$current_vessel]["date_dep"]);
					$cur_vessel_plus_offset = mktime(0,0,0,$temp[0],$temp[1] + $date_offset,$temp[2]);
					if($cur_vessel_plus_offset > $today){
						fwrite($fp, "N/A\t");
						$display_total_flag = false;
//					if($vessel_info[$current_vessel]["free_days"] >= $date_offset){
//						fwrite($fp, $vessel_info[$current_vessel]["plt_count"]."\t");  // IF YOU WANT TO INDICATE FREE DAYS, EDIT THIS LINE
					} else {
						fwrite($fp, $vessel_info[$current_vessel]["date_dep"]."\t");
						fwrite($fp, 1+$date_offset."\t");
						fwrite($fp, $vessel_info[$current_vessel]["ship"]."\t");
						fwrite($fp, $vessel_info[$current_vessel]["plt_count"]."\n");
					}
					$current_vessel++;
				}
			}

			if($display_total_flag){
				//fwrite($fp, $day_total."\n");
			} else {
				//fwrite($fp, "N/A\n");
			}

			$date_offset++;
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