<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Walmart";
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
	$cursor = ora_open($conn);
	$cursor_inner = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($vessel != "all"){
		$vessel_sql = "AND CT.ARRIVAL_NUM = '".$vessel."'";
	} else {
		$vessel_sql = "";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Out-Of-Walmart Transfers</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="eh" action="walmart_trans_report.php" method="post">
	<tr>
		<td>Vessel:</td>
		<td><select name="vessel"><option value="all">All</option>
<?
	$sql = "SELECT VESSEL_NAME, LR_NUM
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) IN
				(SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '3000'
				)
			ORDER BY LR_NUM DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $short_term_row['LR_NUM']; ?>"<? if($vessel == $short_term_row['LR_NUM']){?> selected <?}?>><? echo $short_term_row['LR_NUM']." - ".$short_term_row['VESSEL_NAME']; ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Transfers"></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><br><hr><br></td>
	</tr>
</form>
</table>

<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Transfer Date</b></font></td>
		<td><font size="2" face="Verdana"><b>PO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Transferred To</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT</b></font></td>
		<td><font size="2" face="Verdana"><b>CTN</b></font></td>
	</tr>
<?
		$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, MARK, COUNT(DISTINCT CT.PALLET_ID) PLTS, SUM(QTY_CHANGE) CTNS
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND SERVICE_CODE = '11'
					AND ACTIVITY_NUM > 1
					AND RECEIVER_ID = '3000'
					".$vessel_sql."
				GROUP BY ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), MARK
				ORDER BY ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), MARK";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT MIN(CUSTOMER_ID) THE_CUST 
					FROM CARGO_ACTIVITY
					WHERE ACTIVITY_NUM = '1'
						AND SERVICE_CODE = '11'
						AND ORDER_NUM = '".$row['ORDER_NUM']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$receiver = $short_term_row['THE_CUST'];

?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['MARK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $receiver; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PLTS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CTNS']; ?></font></td>
	</tr>
<?
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>