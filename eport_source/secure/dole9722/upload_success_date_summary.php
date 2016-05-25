<?
/*
*		Adam Walter, Jan 2015.
*
*		Dole9722 review board
*********************************************************************************/


	$pagename = "dole9722_upload_report";
	include("dole9722_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$start = $HTTP_POST_VARS['start'];
	$end = $HTTP_POST_VARS['end'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $start == ""){
		echo "<font color=\"#FF0000\">Start date is required.</font>";
		$submit = "";
	}

?>


<script type="text/javascript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Daily Activity
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="upload_success_date_summary_index.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Starting Date:</font></td>
		<td align="left"><input type="text" name="start" value="<? echo $start; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Ending Date:</font></td>
		<td><input type="text" name="end" value="<? echo $end; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Results"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Results"){
		$total_ves = 0;
		$total_truck = 0;
		$total_in = 0;
		$total_ship = 0;
		$total_unship = 0;
		$total_out = 0;
		$total_return = 0;
//		$total_total = 0;

//					SUM(DECODE(SERVICE_CODE, 7, 1, 0)) RETURNS,
//					SUM(DECODE(SERVICE_CODE, 6, 1, 0)) TRUCK_OUTS,
/*		$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, 
					SUM(DECODE(SERVICE_CODE, 1, 1, 0)) SHIP_INS,
					SUM(DECODE(SERVICE_CODE, 8, 1, 0)) TRUCK_INS,
					SUM(DECODE(SERVICE_CODE, 13, 1, 0)) VOIDS_OUTS,
					COUNT(*) JUST_IN_CASE
				FROM CARGO_ACTIVITY 
				WHERE CUSTOMER_ID = 9722";
		if($start != ""){
			$sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY')";
		}
		if($end != ""){
			$sql .= " AND DATE_OF_ACTIVITY < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
		}
		$sql .= "GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') 
				ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
*/
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_ACTIVITY
				WHERE CUSTOMER_ID = 9722";
		if($start != ""){
			$sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY')";
		}
		if($end != ""){
			$sql .= " AND DATE_OF_ACTIVITY < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
		}
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid) || ociresult($stid, "THE_COUNT") === 0){
			echo "<font color=\"#FF0000\">No Entries match searched criteria</font><br>";
		} else {

?>
<table border="1" align="center" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EECCDD">
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>Pallet Counts, End-Of-Day</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Came In and Stayed</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipped Out - Here Last Night</b></font></td>
		<td><font size="2" face="Verdana"><b>Came In but Shipped Out</b></font></td>
	</tr>
<?
			$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE
					FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '9722'";
			if($start != ""){
				$sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY')";
			}
			if($end != ""){
				$sql .= " AND DATE_OF_ACTIVITY < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
			}
			$sql .= "ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
			$dates = ociparse($rfconn, $sql);
			ociexecute($dates);
			while(ocifetch($dates)){
				$ins = 0;
				$outs = 0;
				$both = 0;

				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				} else {
					$bgcolor = "#FFFFFF";
				}

				$sql = "SELECT PALLET_ID, SUM(DECODE(SERVICE_CODE, 6, 1, 0)) THE_OUTS, SUM(DECODE(SERVICE_CODE, 6, 0, 1)) THE_NOT_OUTS
						FROM CARGO_ACTIVITY
						WHERE CUSTOMER_ID = '9722'
							AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".ociresult($dates, "THE_DATE")."'
						GROUP BY PALLET_ID";
//				echo $sql."<br>";
				$plts = ociparse($rfconn, $sql);
				ociexecute($plts);
				while(ocifetch($plts)){
					if(ociresult($plts, "THE_OUTS") < ociresult($plts, "THE_NOT_OUTS")){
						$ins++;
					} elseif(ociresult($plts, "THE_OUTS") > ociresult($plts, "THE_NOT_OUTS")){
						$outs++;
					} else {
						$both++;
					}
				}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($dates, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode(ociresult($dates, "THE_DATE")); ?>&type=in"><? echo $ins; ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode(ociresult($dates, "THE_DATE")); ?>&type=out"><? echo $outs; ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode(ociresult($dates, "THE_DATE")); ?>&type=even"><? echo $both; ?></a></font></td> 
<!--		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode(ociresult($dates, "THE_DATE")); ?>"><? echo ociresult($dates, "THE_DATE"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $ins; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $outs; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $both; ?></font></td> !-->
	</tr>
<?
			}
?>
</table>
<!--<hr>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EECCDD">
		<td colspan="11" align="center"><font size="3" face="Verdana"><b>Individual Pallet Activities</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td rowspan="2"><font size="2" face="Verdana"><b>Activity Date</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Truck-Ins</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Ship-Ins</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Returns</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Unships</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Ship-Outs</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Total Activities</b></font></td>
		<td><font size="2" face="Verdana"><b>Distinct Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Activities</b></font></td>
		<td><font size="2" face="Verdana"><b>Distinct Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Activities</b></font></td>
		<td><font size="2" face="Verdana"><b>Distinct Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Activities</b></font></td>
		<td><font size="2" face="Verdana"><b>Distinct Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Activities</b></font></td>
		<td><font size="2" face="Verdana"><b>Distinct Pallets</b></font></td>
	</tr>
<?
			$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE
					FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '9722'";
			if($start != ""){
				$sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY')";
			}
			if($end != ""){
				$sql .= " AND DATE_OF_ACTIVITY < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
			}
			$dates = ociparse($rfconn, $sql);
			ociexecute($dates);
			while(ocifetch($dates)){
				$truckins_total = 0;
				$truckins_plts = 0;
				$shipins_total = 0;
				$shipins_plts = 0;
				$returns_total = 0;
				$returns_plts = 0;
				$unships_total = 0;
				$unships_plts = 0;
				$truckouts_total = 0;
				$truckouts_plts = 0;

				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				} else {
					$bgcolor = "#FFFFFF";
				}

				$sql = "SELECT SERVICE_CODE, COUNT(*) THE_ACTS, COUNT(DISTINCT PALLET_ID) THE_PLTS
						FROM CARGO_ACTIVITY 
						WHERE TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".ociresult($dates, "THE_DATE")."'
							AND CUSTOMER_ID = '9722'
						GROUP BY SERVICE_CODE";
				$counts = ociparse($rfconn, $sql);
				ociexecute($counts);
				while(ocifetch($counts)){
					if(ociresult($counts, "SERVICE_CODE") == "1"){
						$shipins_total = ociresult($counts, "THE_ACTS");
						$shipins_plts = ociresult($counts, "THE_PLTS");
					} elseif(ociresult($counts, "SERVICE_CODE") == "6"){
						$truckouts_total = ociresult($counts, "THE_ACTS");
						$truckouts_plts = ociresult($counts, "THE_PLTS");
					} elseif(ociresult($counts, "SERVICE_CODE") == "7"){
						$returns_total = ociresult($counts, "THE_ACTS");
						$returns_plts = ociresult($counts, "THE_PLTS");
					} elseif(ociresult($counts, "SERVICE_CODE") == "8"){
						$truckins_total = ociresult($counts, "THE_ACTS");
						$truckins_plts = ociresult($counts, "THE_PLTS");
					} elseif(ociresult($counts, "SERVICE_CODE") == "13"){
						$unships_total = ociresult($counts, "THE_ACTS");
						$unships_plts = ociresult($counts, "THE_PLTS");
					}
				}


?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($dates, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $truckins_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $truckins_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $shipins_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $shipins_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $returns_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $returns_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $unships_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $unships_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $truckouts_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $truckouts_plts; ?></font></td>
	</tr>
<?
/*				$total_ves += ociresult($stid, "SHIP_INS");
				$total_truck += ociresult($stid, "TRUCK_INS");
				$total_return_diffday += $returns_different_day;
				$total_in += $ins;
				$total_out += $outs;
				$total_ship += $plt_id_outs;
				$total_unship += ociresult($stid, "VOIDS_OUTS");
				$total_return_sameday += $returns_same_day;
//				$total_total += $line_total;*/
			} 
?>
</table> !-->
<?
		}
	}
?>