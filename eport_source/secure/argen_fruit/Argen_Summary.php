<?
/*
*	Adam Walter, Mar 2014.
*
*	intermediary page for argen fruit details
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$var = $HTTP_GET_VARS['var'];
	$category = $HTTP_GET_VARS['category'];
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}

	$page_header = "";
	$sql = makeSQL($vessel, $cust, $comm, $var, $category, $page_header);
//	echo $sql;

	$pallet_count = 0;
	$case_count = 0;
?>
<table border="1" width="100%" cellpadding="2" cellspacing="0"> 
	<tr>
		<td colspan="6" align="center"><font size="4" face="Verdana" color="#0000C0"><? echo $page_header; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Voucher</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Grade</b></font></td>
<!--		<td align="center"><font size="2" face="Verdana"><b>Code</b></font></td> !-->
		<td align="center"><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
	</tr>
<?
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pallet_count += $Short_Term_row['PAL_TOTAL'];
		$case_count += $Short_Term_row['CASE_TOTAL'];
		if ($Short_Term_row['THE_GRADE'] == "E") {
			$grade = "ELE";
		}
		else { 
			$grade = $Short_Term_row['THE_GRADE'];
		}
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_VAR']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_VOUCH']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SIZE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $grade; ?></b></font></td>
<!--		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_CODE']; ?></b></font></td> !-->
		<td align="center"><font size="2" face="Verdana"><b><a href="Argen_Drill_index.php?cust=<? echo $cust; ?>&grade=<? echo $grade; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&var=<? echo $Short_Term_row['THE_VAR']; ?>&vouch=<? echo urlencode($Short_Term_row['THE_VOUCH']); ?>&category=<? echo $category; ?>"><? echo $Short_Term_row['PAL_TOTAL']; ?></a></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="Argen_Drill_index.php?cust=<? echo $cust; ?>&grade=<? echo $grade; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&var=<? echo $Short_Term_row['THE_VAR']; ?>&vouch=<? echo urlencode($Short_Term_row['THE_VOUCH']); ?>&category=<? echo $category; ?>"><? echo $Short_Term_row['CASE_TOTAL']; ?></a></b></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6"><font size="2" face="Verdana"><b>Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Argen_Drill_index.php?cust=<? echo $cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=all&grade=all&var=<? echo $var; ?>&vouch=all&category=<? echo $category; ?>"><? echo "Pallets:  ".number_format($pallet_count); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Cartons:  ".number_format($case_count); ?></b></font></td>
	</tr>
</table>



















<?
function makeSQL($vessel, $cust, $comm, $var, $category, &$page_header){
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}

	switch($category){
		case "expected":
			$page_header = "PoW Expected Cargo";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						SUBSTR(REMARK, 0, 1) THE_GRADE,
						NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, 
						NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						".$extra_sql."
					GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)
					ORDER BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)";
			return $sql;
		break;
		case "awaiting":
			$page_header = "PoW Cargo Awaiting Receipt";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						SUBSTR(REMARK, 0, 1) THE_GRADE,
						NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, 
						NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND DATE_RECEIVED IS NULL 
						".$extra_sql."
					GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)
					ORDER BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)";
			return $sql;
		break;
		case "received":
			$page_header = "PoW Received Cargo";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						SUBSTR(REMARK, 0, 1) THE_GRADE,
						NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, 
						NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND DATE_RECEIVED IS NOT NULL
						".$extra_sql."
					GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)
					ORDER BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)";
			return $sql;
		break;
		case "shipped":
			$page_header = "PoW Cargo Already Shipped Out";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						SUBSTR(REMARK, 0, 1) THE_GRADE,
						NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, 
						NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND QTY_IN_HOUSE = 0 
						".$extra_sql."
					GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)
					ORDER BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)";
			return $sql;
		break;
		case "atpow":
			$page_header = "PoW Cargo Currently In House";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						SUBSTR(REMARK, 0, 1) THE_GRADE,
						NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, 
						NVL(SUM(QTY_IN_HOUSE), 0) CASE_TOTAL
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND QTY_IN_HOUSE > 0 
						AND DATE_RECEIVED IS NOT NULL
						".$extra_sql."
					GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)
					ORDER BY VARIETY, BATCH_ID, CARGO_SIZE, SUBSTR(REMARK, 0, 1)";
			return $sql;
		break;
	}
}