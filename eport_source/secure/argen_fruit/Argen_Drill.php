<?

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);


	$extra_sql = "";

	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$category = $HTTP_GET_VARS['category'];

	$var = $HTTP_GET_VARS['var'];
	$size = $HTTP_GET_VARS['size'];
	$vouch = $HTTP_GET_VARS['vouch'];
	$grade = $HTTP_GET_VARS['grade'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Argentine Fruit Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade</b></font></td>
		<td><font size="2" face="Verdana"><b>Voucher#</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Pall No.</b></font></td>
		<td><font size="2" face="Verdana"><b>Received On</b></font></td>
	</tr>
<?
	$sql = makeSQL($vessel, $cust, $comm, $var, $size, $vouch, $grade, $category, $page_header);
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$temp = explode("-", $Short_Term_row['REMARK']);
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_QTY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_VAR']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[0]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_VOUCH']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[1]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_PAL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_REC']; ?></font></td>
	</tr>
<?
	}
?>
</table>







<?
function makeSQL($vessel, $cust, $comm, $var, $size, $vouch, $grade, $category, $page_header){
	$extra_sql = "";
	if($var != "all"){
		$extra_sql .= " AND VARIETY = '".$var."' ";
	} else {
		$extra_sql .= "";
	}
	if($size != "all"){
		$extra_sql .= " AND CARGO_SIZE = '".$size."' ";
	} else {
		$extra_sql .= "";
	}
	if($vouch != "all"){
		$extra_sql .= " AND BATCH_ID = '".$vouch."' ";
	} else {
		$extra_sql .= "";
	}
	if($grade != "all"){
		$extra_sql .= " AND REMARK LIKE '".$grade."%' ";
	} else {
		$extra_sql .= "";
	}

	switch($category){
		case "expected":
			$page_header = "PoW Expected Cargo";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						PALLET_ID THE_PAL, 
						QTY_IN_HOUSE THE_QTY,
						REMARK,
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						".$extra_sql."
					ORDER BY VARIETY, CARGO_SIZE, REMARK";
			return $sql;
		break;
		case "awaiting":
			$page_header = "PoW Cargo Awaiting Receipt";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						PALLET_ID THE_PAL, 
						QTY_IN_HOUSE THE_QTY,
						REMARK,
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND DATE_RECEIVED IS NULL 
						".$extra_sql."
					ORDER BY VARIETY, CARGO_SIZE, REMARK";
			return $sql;
		break;
		case "received":
			$page_header = "PoW Received Cargo";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						PALLET_ID THE_PAL, 
						QTY_IN_HOUSE THE_QTY,
						REMARK,
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND DATE_RECEIVED IS NOT NULL
						".$extra_sql."
					ORDER BY VARIETY, CARGO_SIZE, REMARK";
			return $sql;
		break;
		case "shipped":
			$page_header = "PoW Cargo Already Shipped Out";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						PALLET_ID THE_PAL, 
						QTY_IN_HOUSE THE_QTY,
						REMARK,
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND QTY_IN_HOUSE = 0 
						".$extra_sql."
					ORDER BY VARIETY, CARGO_SIZE, REMARK";
			return $sql;
		break;
		case "atpow":
			$page_header = "PoW Cargo Currently In House";
			$sql = "SELECT VARIETY THE_VAR,
						BATCH_ID THE_VOUCH,
						CARGO_SIZE THE_SIZE,
						PALLET_ID THE_PAL, 
						QTY_IN_HOUSE THE_QTY,
						REMARK,
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."' 
						AND QTY_IN_HOUSE > 0 
						AND DATE_RECEIVED IS NOT NULL
						".$extra_sql."
					ORDER BY VARIETY, CARGO_SIZE, REMARK";
			return $sql;
		break;
	}
}