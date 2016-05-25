<?
/*
*	Adam Walter, Jan 2011.
*
*	Report for dole paper, based on any # of inputs... dangit Lisa...
*************************************************************************/


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$cursor_date = ora_open($conn);
	$cursor_cust = ora_open($conn);
	$cursor = ora_open($conn);

	$cust = $HTTP_POST_VARS['cust'];
	$from_date = $HTTP_POST_VARS['from_date'];
	$to_date = $HTTP_POST_VARS['to_date'];
	$PO = $HTTP_POST_VARS['PO'];
	$code = $HTTP_POST_VARS['code'];
	$submit = $HTTP_POST_VARS['submit'];

	if($from_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $from_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>Starting Date must be in MM/DD/YYYY format (was entered as ".$from_date.")</font><br>";
		$submit = "";
	}
	if($to_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $to_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>Ending Date must be in MM/DD/YYYY format (was entered as ".$to_date.")</font><br>";
		$submit = "";
	}

?>
<script language="JavaScript" src="../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Information Lookup
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="dole_pocode_report.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Start Date:</b></font></td>
		<td align="left"><input name="from_date" type="text" size="10" maxlength="10" value="<? echo $from_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.from_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>End Date:</b></font></td>
		<td align="left"><input name="to_date" type="text" size="10" maxlength="10" value="<? echo $to_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.to_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td align="left"><select name="cust"><option value="">All</option>
<?
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN
				(SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS
				WHERE PAPER_TYPE = 'DOCKTICKET')
			ORDER BY CUSTOMER_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['CUSTOMER_ID']; ?>" <? if($row['CUSTOMER_ID'] == $cust){?>selected<?}?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
	}
?>		
		</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>PO:</b></font></td>
		<td align="left"><input type="text" name="PO" size="20" maxlength="20" value="<? echo $PO; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Code:</b></font></td>
		<td align="left"><input type="text" name="code" size="10" maxlength="10" value="<? echo $code; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Records"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	</form>
</table>
<?
	if($submit != "" && ($from_date != "" || $to_date != "" || $PO != "" || $code != "" || $cust != "")) {
		if($to_date == ""){
			$to_date = date('m/d/Y');
		}
?>
<table border="2" width="100%" cellpadding="2" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="Verdana"><b>Manifest</b></font></td>
		<td><font size="2" face="Verdana"><b>PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Dock Ticket</b></font></td>
		<td><font size="2" face="Verdana"><b>#Rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Lbs</b></font></td>
		<td><font size="2" face="Verdana"><b>Tons</b></font></td>
		<td><font size="2" face="Verdana"><b>MSF</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Left as of<br><? echo $to_date; ?> E.O.D.</b></font></td>
	</tr>
	<tr>
<?
		$grand_total_rolls = 0;
		$grand_total_lbs = 0;
		$grand_total_tons = 0;
		$grand_total_msf = 0;

		$where_clause = "";
		if($from_date != ""){
			$where_clause .= " AND DATE_RECEIVED >= TO_DATE('".$from_date."', 'MM/DD/YYYY')";
		}
		if($to_date != ""){
			$where_clause .= " AND DATE_RECEIVED <= TO_DATE('".$to_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		}
		if($cust != ""){
			$where_clause .= " AND RECEIVER_ID = '".$cust."'";
		}
		if($code != ""){
			$where_clause .= " AND BATCH_ID = '".$code."'";
		}
		if($PO != ""){
			$where_clause .= " AND CARGO_DESCRIPTION LIKE '%".$PO."%'";
		}

		$sql = "SELECT DISTINCT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE
				FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'";
		$sql .= $where_clause;
		$sql .= " ORDER BY TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
		ora_parse($cursor_date, $sql);
		ora_exec($cursor_date);
		if(!ora_fetch_into($cursor_date, $row_date, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="12" align="center"><font size="2" face="Verdana"><b>No In-House rolls for the given search criteria.</b></font></td>
	</tr>
<?
		} else {
			$date_total_rolls = 0;
			$date_total_lbs = 0;
			$date_total_tons = 0;
			$date_total_msf = 0;
			do {
?>
	<tr bgcolor="#FFCC99">
		<td colspan="12"><font size="2" face="Verdana"><b><? echo $row_date['THE_DATE']; ?></b></font></td>
	</tr>
<?
				$sql = "SELECT DISTINCT CT.RECEIVER_ID, CUSTOMER_NAME
						FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CP
						WHERE REMARK = 'DOLEPAPERSYSTEM'
							AND CT.RECEIVER_ID = CP.CUSTOMER_ID
							AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$row_date['THE_DATE']."'";
				$sql .= $where_clause;
				$sql .= " ORDER BY CT.RECEIVER_ID";
				ora_parse($cursor_cust, $sql);
				ora_exec($cursor_cust);
				if(!ora_fetch_into($cursor_cust, $row_cust, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// this wont happen, but for the sake of the loop, i left it in
				} else {
					$cust_total_rolls = 0;
					$cust_total_lbs = 0;
					$cust_total_tons = 0;
					$cust_total_msf = 0;
					do {
?>
	<tr bgcolor="#CCFF99">
		<td>&nbsp;</td>
		<td colspan="11"><font size="2" face="Verdana"><b><? echo $row_cust['CUSTOMER_NAME']; ?></b></font></td>
	</tr>
<?

						// in case you are wondering what's up with the "variety sum" clause, it's because manual EDI
						// entries are somehow slipping carriage-return line-feed combos into the field.
						$sql = "SELECT ARRIVAL_NUM, CARGO_DESCRIPTION,
									BATCH_ID, BOL, SUM(QTY_RECEIVED) THE_ROLLS, SUM(WEIGHT) THE_LBS, 
									SUM(DECODE(VARIETY, NULL, '0', REPLACE(VARIETY, CHR(13) || CHR(10))) * (CARGO_SIZE / 12) / 1000) THE_MSF
								FROM CARGO_TRACKING
								WHERE REMARK = 'DOLEPAPERSYSTEM'";
						$sql .= $where_clause;
						$sql .= " GROUP BY ARRIVAL_NUM, CARGO_DESCRIPTION, BATCH_ID, BOL";
						$sql .= " ORDER BY ARRIVAL_NUM, CARGO_DESCRIPTION, BATCH_ID";
						ora_parse($cursor, $sql);
						ora_exec($cursor);
						if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							// this wont happen, but for the sake of the loop, i left it in
						} else {
							do {
								$grand_total_rolls += $row['THE_ROLLS'];
								$grand_total_lbs += $row['THE_LBS'];
								$grand_total_tons += round($row['THE_LBS'] / 2000, 2);
								$grand_total_msf += $row['THE_MSF'];
								$date_total_rolls += $row['THE_ROLLS'];
								$date_total_lbs += $row['THE_LBS'];
								$date_total_tons += round($row['THE_LBS'] / 2000, 2);
								$date_total_msf += $row['THE_MSF'];
								$cust_total_rolls += $row['THE_ROLLS'];
								$cust_total_lbs += $row['THE_LBS'];
								$cust_total_tons += round($row['THE_LBS'] / 2000, 2);
								$cust_total_msf += $row['THE_MSF'];

								$temp = split(" ", $row['CARGO_DESCRIPTION']);
								$manifest = $temp[2];
								$PO = $temp[0];
								// we need to figure out how many of these rolls were shipped out for the "current qty field"
								$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM FROM CARGO_ACTIVITY
										WHERE SERVICE_CODE = '6'
										AND ACTIVITY_DESCRIPTION IS NULL
										AND DATE_OF_ACTIVITY <= TO_DATE('".$to_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
										AND PALLET_ID IN
											(SELECT PALLET_ID FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'
											AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$row['REC_DATE']."'
											AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'
											AND RECEIVER_Id = '".$row['RECEIVER_ID']."'
											AND BATCH_ID = '".$row['BATCH_ID']."'
											AND BOL = '".$row['BOL']."'
											AND CARGO_DESCRIPTION LIKE '".$PO."%".$manifest."')";
								ora_parse($Short_Term_Cursor, $sql);
								ora_exec($Short_Term_Cursor);
								ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$qty_shipped = $short_term_row['THE_SUM'];

?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $manifest; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $PO; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BATCH_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ROLLS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_LBS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo round($row['THE_LBS'] / 2000, 2); ?></font></td>
		<td><font size="2" face="Verdana"><? echo round($row['THE_MSF'], 2); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ($row['THE_ROLLS'] - $qty_shipped); ?></font></td>
	</tr>
<?
								} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
							}
?>
	<tr bgcolor="#99FF66">
		<td>&nbsp;</td>
		<td colspan="6"><font size="2" face="Verdana"><b>Totals for  <? echo $row_cust['CUSTOMER_NAME']; ?>:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $cust_total_rolls; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $cust_total_lbs; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $cust_total_tons; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo round($cust_total_msf, 2); ?></b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
					} while(ora_fetch_into($cursor_cust, $row_cust, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
?>
	<tr bgcolor="#FF9999">
		<td colspan="7"><font size="2" face="Verdana"><b>Totals for  <? echo $row_date['THE_DATE']; ?>:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $date_total_rolls; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $date_total_lbs; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $date_total_tons; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo round($date_total_msf, 2); ?></b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
			} while(ora_fetch_into($cursor_date, $row_date, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
	<tr bgcolor="#CCFFFF">
		<td colspan="7"><font size="3" face="Verdana"><b>GRAND Totals:</b></font></td>
		<td><b><? echo $grand_total_rolls; ?></b></td>
		<td><b><? echo $grand_total_lbs; ?></b></td>
		<td><b><? echo $grand_total_tons; ?></b></td>
		<td><b><? echo round($grand_total_msf, 2); ?></b></td>
		<td>&nbsp;</td>
	</tr>	
<?
	}
?>
</table>