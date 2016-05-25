<?

/* Adam Walter, June 2007.
*  This used to be a part of wing_g_inv_process.php, but was split off
*  For various reasons (the other filename has since been changed).
*
*
*  Also, I keep a "week" counter going, so as to add blank-lines at
*  The end of every week.
***********************************************************************/

include("connect.php");

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $TrackCursor = ora_open($conn);
  $ActCursor = ora_open($conn);

  $conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn2 < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn2));
      	exit;
  }
  $RFcursor = ora_open($conn2);
  $RFTrackCursor = ora_open($conn2);
  $RFActCursor = ora_open($conn2);


	$eDate = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
	$eTime = strtotime($eDate);
	$printout_date = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));

	$today = date('m/d/y h:i A');


/*
* There has got to be a more elegant, SQL based way of doing this, but as the lone programmer
* At the port, I don't have the luxury of time of figuring it out.  What I am doing here
* Is creating 2 views, 1 from CARGO_TRACKING, and one from CARGO_ACTIVITY, where I will
* Step through the following while loop day by day, and if the current day matches any given date in one
* (or both) of the views, I record that value and step the cursor.
*
* Note that this SQL assumes that both SQL's will have at least 1 row.  If that is untrue,
* Program will crash (only possible if either we don't receive ANYTHING or don't ship ANYTHING
* during the period of the SQL search)
**********************************************************************************************/


	// Not valid SQL, placeholder for future
/*	$sql = "SELECT MAX(THE_TIME) FROM THE_TABLE"; 
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$sDate = $row['THE_TIME'];
*/
//	$sDate = date('m/d/Y', mktime(0,0,0,7,1,2006)); // temporary for testing, July1, 2006 was a saturday
	$sDate = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 9, date('Y')));
	$sTime = strtotime($sDate);

//	echo $sDate;


/*
*	Dole Booking paper portion.  Dock Ticket paper has been removed from BNI, so its lower down.
***************************************************************************************************/
	$current_date = $sDate;
	$current_time = $sTime;
	$day_of_week = date('w', $sTime);
	$strOut = "";

	
	$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM, SUM(CARGO_WEIGHT * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE DATE_RECEIVED < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND RECIPIENT_ID != '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CT.LOT_NUM = CM.CONTAINER_NUM
			AND CT.COMMODITY_CODE IN ('1299')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_in = $row['THE_SUM'];
	$original_weight_in = round($row['THE_WEIGHT'], 0);

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, SUM((QTY_CHANGE * CARGO_WEIGHT / QTY_EXPECTED) * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE DATE_OF_ACTIVITY < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND RECIPIENT_ID != '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CA.SERVICE_CODE = '6200'
			AND CA.LOT_NUM = CM.CONTAINER_NUM
			AND LOT_NUM IN 
			(
				SELECT LOT_NUM FROM CARGO_TRACKING
				WHERE COMMODITY_CODE IN ('1299')
			)";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_out = $row['THE_SUM'];
	$original_weight_out = round($row['THE_WEIGHT'], 0);


	$original_count = $original_in - $original_out;
	$original_weight = $original_weight_in - $original_weight_out;
//	$original_count = 1000; // temporary testing

	$current_count = $original_count;
	$current_weight = $original_weight;


	$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, SUM(QTY_RECEIVED) THE_SUM, SUM(CARGO_WEIGHT * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE CT.COMMODITY_CODE IN ('1299')
			AND RECIPIENT_ID != '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CT.LOT_NUM = CM.CONTAINER_NUM
			AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			GROUP BY TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
	$statement = ora_parse($TrackCursor, $sql);
	ora_exec($TrackCursor);
	ora_fetch_into($TrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, SUM(QTY_CHANGE) THE_SUM, SUM((QTY_CHANGE * CARGO_WEIGHT / QTY_EXPECTED) * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE LOT_NUM IN
			(	  SELECT LOT_NUM
				  FROM CARGO_TRACKING
				  WHERE COMMODITY_CODE IN ('1299')
			)
			AND RECIPIENT_ID != '312'
			AND CA.LOT_NUM = CM.CONTAINER_NUM
			AND CA.SERVICE_CODE = '6200'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND DATE_OF_ACTIVITY >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND SERVICE_CODE = '6200'
			GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
	$statement = ora_parse($ActCursor, $sql);
	ora_exec($ActCursor);
	ora_fetch_into($ActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	while($current_time < $eTime){
//		echo $current_date."\n";

		if($TrackRow['THE_DATE'] == $current_date){
			$inbound = $TrackRow['THE_SUM'];
			$inbound_weight = $TrackRow['THE_WEIGHT'];
			ora_fetch_into($TrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$inbound = "0";
			$inbound_weight = "0";
		}
		if($ActRow['THE_DATE'] == $current_date){
			$outbound = $ActRow['THE_SUM'];
			$outbound_weight = $ActRow['THE_WEIGHT'];
			ora_fetch_into($ActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$outbound = "0";
			$outbound_weight = "0";
		}

		$nextCount = $current_count + $inbound - $outbound;
		$nextWeight = $current_weight + $inbound_weight - $outbound_weight;

		$strOut .= "<tr>
					<td>$current_date</td>
					<td>$current_count</td>
					<td>".round($current_weight, 0)."</td>
					<td>$inbound</td>
					<td>".round($inbound_weight, 0)."</td>
					<td>$outbound</td>
					<td>".round($outbound_weight, 0)."</td>
					<td>$nextCount</td>
					<td>".round($nextWeight, 0)."</td>
					</tr>";

		if($day_of_week == 7){
			$strOut .= "<tr><td colspan=9>&nbsp;</td></tr>";
			$day_of_week = 1;
		} else {
			$day_of_week++;
		}




		$current_count = $nextCount;
		$current_weight = $nextWeight;
		$current_date = date('m/d/Y',mktime(0,0,0,date("m", $current_time),date("d", $current_time)+1 ,date("Y", $current_time)));
		$current_time = strtotime($current_date);
//		echo $current_date."\n";
	}

	$table = "<TABLE border=1 CELLSPACING=1>";
                $table .= "<tr><td colspan=9 align=center><font size = 5><b>Dole Booking Paper Inventory</b></font><br/><b><i>Date: ".$sDate." to ".$printout_date."</i> </b><br \>Printed on: ".$today."</td></tr>";
	$table .= "<tr>
				 <td valign=top rowspan=3 align=center><b>Date</b></td>
				 <td align='center' colspan=8><b>Dole</b></td>
			   </tr>";
	$table .= "<tr>
					<td align=center colspan=2><b>Start</b></td>
					<td align=center colspan=2><b>Received</b></td>
					<td align=center colspan=2><b>Shipped</b></td>
					<td align=center colspan=2><b>End</b></td>				
			   </tr>";
	$table .= "<tr>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
				</tr>";
	$table .= "$strOut";

	$table .= "<tr><td colspan=9>&nbsp;</td></tr>";
	$table .= "<tr><td colspan=9>Disclaimer:  Tonnage is based on the average roll weight per lot, as withdrawals are not made roll by roll.</td></tr>";

	$table .= "</table>";


	$table .= "<br><br>";


/*
*	Dole Dock Ticket Paper.  Found in RF.
*****************************************************************************************************************/

	$current_date = $sDate;
	$current_time = $sTime;
	$day_of_week = date('w', $sTime);
	$strOut = "";

	$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM, SUM((WEIGHT * 2.2) / 2000) THE_TONS
			FROM CARGO_TRACKING CT
			WHERE DATE_RECEIVED < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND REMARK = 'DOLEPAPERSYSTEM'";
	ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_in = $row['THE_SUM'];
	$original_weight_in = round($row['THE_TONS'], 0);

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, SUM(WEIGHT / 2000) THE_TONS
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE DATE_OF_ACTIVITY < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND CA.SERVICE_CODE = '6'
			AND CA.ACTIVITY_DESCRIPTION IS NULL
			AND CA.PALLET_ID = CT.PALLET_ID
			AND CA.CUSTOMER_ID = CT.RECEIVER_ID
			AND CA.BATCH_ID = CT.BOL
			AND CA.PALLET_ID IN 
			(
				SELECT PALLET_ID FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
			)";
	ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_out = $row['THE_SUM'];
	$original_weight_out = round($row['THE_TONS'], 0);


	$original_count = $original_in - $original_out;
	$original_weight = $original_weight_in - $original_weight_out;

	$current_count = $original_count;
	$current_weight = $original_weight;


	$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, SUM(QTY_RECEIVED) THE_SUM, SUM(WEIGHT / 2000) THE_TONS
			FROM CARGO_TRACKING CT
			WHERE REMARK = 'DOLEPAPERSYSTEM'
			AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			GROUP BY TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
	$statement = ora_parse($RFTrackCursor, $sql);
	ora_exec($RFTrackCursor);
	ora_fetch_into($RFTrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, SUM(QTY_CHANGE) THE_SUM, SUM(WEIGHT / 2000) THE_TONS
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CT.PALLET_ID IN
			(	  SELECT PALLET_ID
				  FROM CARGO_TRACKING
				  WHERE REMARK = 'DOLEPAPERSYSTEM'
			)
			AND CA.SERVICE_CODE = '6'
			AND CA.ACTIVITY_DESCRIPTION IS NULL
			AND CA.PALLET_ID = CT.PALLET_ID
			AND CA.CUSTOMER_ID = CT.RECEIVER_ID
			AND CA.BATCH_ID = CT.BOL
			AND DATE_OF_ACTIVITY >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
	$statement = ora_parse($RFActCursor, $sql);
	ora_exec($RFActCursor);
	ora_fetch_into($RFActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	while($current_time < $eTime){
//		echo $current_date."\n";

		if($TrackRow['THE_DATE'] == $current_date){
			$inbound = $TrackRow['THE_SUM'];
			$inbound_weight = $TrackRow['THE_TONS'];
			ora_fetch_into($RFTrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$inbound = "0";
			$inbound_weight = "0";
		}
		if($ActRow['THE_DATE'] == $current_date){
			$outbound = $ActRow['THE_SUM'];
			$outbound_weight = $ActRow['THE_TONS'];
			ora_fetch_into($RFActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$outbound = "0";
			$outbound_weight = "0";
		}

		$nextCount = $current_count + $inbound - $outbound;
		$nextWeight = $current_weight + $inbound_weight - $outbound_weight;

		$strOut .= "<tr>
					<td>$current_date</td>
					<td>$current_count</td>
					<td>".round($current_weight, 0)."</td>
					<td>$inbound</td>
					<td>".round($inbound_weight, 0)."</td>
					<td>$outbound</td>
					<td>".round($outbound_weight, 0)."</td>
					<td>$nextCount</td>
					<td>".round($nextWeight, 0)."</td>
					</tr>";

		if($day_of_week == 7){
			$strOut .= "<tr><td colspan=9>&nbsp;</td></tr>";
			$day_of_week = 1;
		} else {
			$day_of_week++;
		}




		$current_count = $nextCount;
		$current_weight = $nextWeight;
		$current_date = date('m/d/Y',mktime(0,0,0,date("m", $current_time),date("d", $current_time)+1 ,date("Y", $current_time)));
		$current_time = strtotime($current_date);
	}

	$table .= "<TABLE border=1 CELLSPACING=1>";
                $table .= "<tr><td colspan=9 align=center><font size = 5><b>Dole DockTicket Paper Inventory</b></font><br/><b><i>Date: ".$sDate." to ".$printout_date."</i> </b><br \>Printed on: ".$today."</td></tr>";
	$table .= "<tr>
				 <td valign=top rowspan=3 align=center><b>Date</b></td>
				 <td align='center' colspan=8><b>Dole</b></td>
			   </tr>";
	$table .= "<tr>
					<td align=center colspan=2><b>Start</b></td>
					<td align=center colspan=2><b>Received</b></td>
					<td align=center colspan=2><b>Shipped</b></td>
					<td align=center colspan=2><b>End</b></td>				
			   </tr>";
	$table .= "<tr>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
				</tr>";
	$table .= "$strOut";

	$table .= "<tr><td colspan=9>&nbsp;</td></tr>";

	$table .= "</table>";


/*
*	Abitibi Paper from BNI
********************************************************************************************/

	$current_date = $sDate;
	$current_time = $sTime;
	$day_of_week = date('w', $sTime);
	$strOut = "";

	$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM, SUM((WEIGHT * 2.2) / 2000) THE_WEIGHT
			FROM CARGO_TRACKING CT
			WHERE DATE_RECEIVED < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND RECEIVER_ID = '312'";
/*	$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM, SUM(CARGO_WEIGHT * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE DATE_RECEIVED < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND RECIPIENT_ID = '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CT.LOT_NUM = CM.CONTAINER_NUM
			AND CT.COMMODITY_CODE IN ('1272', '1299')"; */
	ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_in = $row['THE_SUM'];
	$original_weight_in = round($row['THE_WEIGHT'], 0);

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, SUM((WEIGHT * 2.2) / 2000) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE DATE_OF_ACTIVITY < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND CA.SERVICE_CODE = '6'
			AND CA.ACTIVITY_DESCRIPTION LIKE 'DMG%'
			AND CA.PALLET_ID = CT.PALLET_ID
			AND CA.CUSTOMER_ID = CT.RECEIVER_ID
			AND DATE_RECEIVED IS NOT NULL
			AND CT.RECEIVER_ID = '312'";
/*	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, SUM((QTY_CHANGE * CARGO_WEIGHT / QTY_EXPECTED) * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE DATE_OF_ACTIVITY < TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND RECIPIENT_ID = '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CA.SERVICE_CODE = '6200'
			AND CA.LOT_NUM = CM.CONTAINER_NUM
			AND LOT_NUM IN 
			(
				SELECT LOT_NUM FROM CARGO_TRACKING
				WHERE COMMODITY_CODE IN ('1272', '1299')
			)"; */
	ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$original_out = $row['THE_SUM'];
	$original_weight_out = round($row['THE_WEIGHT'], 0);


	$original_count = $original_in - $original_out;
	$original_weight = $original_weight_in - $original_weight_out;
//	$original_count = 1000; // temporary testing

	$current_count = $original_count;
	$current_weight = $original_weight;



	$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, SUM(QTY_RECEIVED) THE_SUM, SUM((WEIGHT * 2.2) / 2000) THE_WEIGHT
			FROM CARGO_TRACKING CT
			WHERE RECEIVER_ID = '312'
			AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			GROUP BY TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
/*	$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, SUM(QTY_RECEIVED) THE_SUM, SUM(CARGO_WEIGHT * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE CT.COMMODITY_CODE IN ('1272', '1299')
			AND RECIPIENT_ID = '312'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND CT.LOT_NUM = CM.CONTAINER_NUM
			AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			GROUP BY TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')"; */
	$statement = ora_parse($RFTrackCursor, $sql);
	ora_exec($RFTrackCursor);
	ora_fetch_into($RFTrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, SUM(QTY_CHANGE) THE_SUM, SUM((WEIGHT * 2.2) / 2000) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CA.SERVICE_CODE = '6'
			AND CT.RECEIVER_ID = '312'
			AND CA.ACTIVITY_DESCRIPTION LIKE 'DMG%'
			AND CA.PALLET_ID = CT.PALLET_ID
			AND CA.CUSTOMER_ID = CT.RECEIVER_ID
			AND DATE_OF_ACTIVITY >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND DATE_RECEIVED IS NOT NULL
			GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')";
/*	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, SUM(QTY_CHANGE) THE_SUM, SUM((QTY_CHANGE * CARGO_WEIGHT / QTY_EXPECTED) * CONVERSION_FACTOR) THE_WEIGHT
			FROM CARGO_ACTIVITY CA, CARGO_MANIFEST CM, UNIT_CONVERSION UT
			WHERE LOT_NUM IN
			(	  SELECT LOT_NUM
				  FROM CARGO_TRACKING
				  WHERE COMMODITY_CODE IN ('1272', '1299')
			)
			AND RECIPIENT_ID = '312'
			AND CA.LOT_NUM = CM.CONTAINER_NUM
			AND CA.SERVICE_CODE = '6200'
			AND UT.SECONDARY_UOM = 'TON'
			AND UT.PRIMARY_UOM = CM.CARGO_WEIGHT_UNIT
			AND DATE_OF_ACTIVITY >= TO_DATE('".$sDate."', 'MM/DD/YYYY')
			AND SERVICE_CODE = '6200'
			GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
			ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY')"; */
	$statement = ora_parse($RFActCursor, $sql);
	ora_exec($RFActCursor);
	ora_fetch_into($RFActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	while($current_time < $eTime){
//		echo $current_date."\n";

		if($TrackRow['THE_DATE'] == $current_date){
			$inbound = $TrackRow['THE_SUM'];
			$inbound_weight = $TrackRow['THE_WEIGHT'];
			ora_fetch_into($RFTrackCursor, $TrackRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$inbound = "0";
			$inbound_weight = "0";
		}
		if($ActRow['THE_DATE'] == $current_date){
			$outbound = $ActRow['THE_SUM'];
			$outbound_weight = $ActRow['THE_WEIGHT'];
			ora_fetch_into($RFActCursor, $ActRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		} else {
			$outbound = "0";
			$outbound_weight = "0";
		}

		$nextCount = $current_count + $inbound - $outbound;
		$nextWeight = $current_weight + $inbound_weight - $outbound_weight;

		$strOut .= "<tr>
					<td>$current_date</td>
					<td>$current_count</td>
					<td>".round($current_weight, 0)."</td>
					<td>$inbound</td>
					<td>".round($inbound_weight, 0)."</td>
					<td>$outbound</td>
					<td>".round($outbound_weight, 0)."</td>
					<td>$nextCount</td>
					<td>".round($nextWeight, 0)."</td>
					</tr>";

		if($day_of_week == 7){
			$strOut .= "<tr><td colspan=9>&nbsp;</td></tr>";
			$day_of_week = 1;
		} else {
			$day_of_week++;
		}




		$current_count = $nextCount;
		$current_weight = $nextWeight;
		$current_date = date('m/d/Y',mktime(0,0,0,date("m", $current_time),date("d", $current_time)+1 ,date("Y", $current_time)));
		$current_time = strtotime($current_date);
//		echo $current_date."\n";
	}

	$table2 = "<TABLE border=1 CELLSPACING=1>";
                $table2 .= "<tr><td colspan=9 align=center><font size = 5><b>Abitibi Booking Paper Inventory</b></font><br/><b><i>Date: ".$sDate." to ".$printout_date."</i> </b><br \>Printed on: ".$today."</td></tr>";
	$table2 .= "<tr>
				 <td valign=top rowspan=3 align=center><b>Date</b></td>
				 <td align='center' colspan=8><b>Dole</b></td>
			   </tr>";
	$table2 .= "<tr>
					<td align=center colspan=2><b>Start</b></td>
					<td align=center colspan=2><b>Received</b></td>
					<td align=center colspan=2><b>Shipped</b></td>
					<td align=center colspan=2><b>End</b></td>				
			   </tr>";
	$table2 .= "<tr>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
					<td align=center><b>Count</b></td>
					<td align=center><b>Tons</b></td>
				</tr>";
	$table2 .= "$strOut";

	$table2 .= "<tr><td colspan=9>&nbsp;</td></tr>";
	$table2 .= "<tr><td colspan=9>Disclaimer:  Tonnage is based on the average roll weight per lot, as withdrawals are not made roll by roll.</td></tr>";

	$table2 .= "</table>";











	//export to excel
//	header("Content-Type: application/vnd.ms-excel; name='excel'");
//	header("Content-Disposition: attachment; filename=DoleExport.xls");

      


	// done with logic, now for file sending.
	$File=chunk_split(base64_encode($table));
	$File2=chunk_split(base64_encode($table2));

	$mailTO = "awalter@port.state.de.us";

	$mailTo1 = "tkeefer@port.state.de.us";

	$mailsubject = "Dole Paper Inventory";

	$mailheaders = "From: MailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: sadu@port.state.de.us\r\n";
//		$mailheaders .= "Cc: jharoldson@port.state.de.us\r\n";
//		$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us\r\n";

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
	$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";



	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
//	$Content.= "Disclaimer:  Tonnage is based on the average roll weight per lot, as withdrawals are not made roll by roll.\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"Dole Paper.xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"Abitibi Paper.xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File2;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";



	mail($mailTO, $mailsubject, $Content, $mailheaders);
	//mail($mailTo1, $mailsubject, $Content, $mailheaders);

?>
