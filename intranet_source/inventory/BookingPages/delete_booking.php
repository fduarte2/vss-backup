<?
/*
*	Adam Walter, Apr 2013.
*
*	Dole Paper's DT edi-cancellations do NOT match with the expectation
*	That were in place when the "receive cancellations" script was written.
*	
*	That said, Inventory sometimes just wants to delete (unreceived) rolls
*	From inventory, so rather than alter the EDI-cencel routine, we are
*	Giving Inventory a screen by which they can delete (unreceived) rolls.
***************************************************************************/

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
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$BOL = $HTTP_POST_VARS['BOL'];
	$book_num = $HTTP_POST_VARS['book_num'];
	$barcode = $HTTP_POST_VARS['barcode'];
	$submit = $HTTP_POST_VARS['submit'];

	$display_error = "";

	if($submit == "Delete Rolls"){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND DATE_RECEIVED IS NOT NULL";
		if($BOL != ""){
			$sql .= " AND BAD.BOL = '".$BOL."'";
		}
		if($book_num != ""){
			$sql .= " AND BAD.BOOKING_NUM = '".$book_num."'";
		}
		if($barcode != ""){
			$sql .= " AND CT.PALLET_ID = '".$barcode."'";
		}
		$sql .= " ORDER BY CT.PALLET_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$received = $short_term_row['THE_COUNT'];

		if($received > 0){
			$display_error .= "The entered criteria contains already-received rolls.  Please narrow the search down so that only Un-Received rolls are returned.<br>";
		}

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND DATE_RECEIVED IS NULL";
		if($BOL != ""){
			$sql .= " AND BAD.BOL = '".$BOL."'";
		}
		if($book_num != ""){
			$sql .= " AND BAD.BOOKING_NUM = '".$book_num."'";
		}
		if($barcode != ""){
			$sql .= " AND CT.PALLET_ID = '".$barcode."'";
		}
		$sql .= " ORDER BY CT.PALLET_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$un_received = $short_term_row['THE_COUNT'];

		if($un_received <= 0){
			$display_error .= "No Unreceived Rolls found for entered criteria.<br>";
		}

		if($display_error != ""){
			echo "<font color=\"#FF0000\">The search criteria turned up the following issues:<br>".$display_error."Please Resubmit Search";
			$submit = "";
		}
	}

	if($submit == "Delete Rolls"){
		$sql = "SELECT CT.ARRIVAL_NUM, CT.PALLET_ID, CT.RECEIVER_ID
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND DATE_RECEIVED IS NULL";
		if($BOL != ""){
			$sql .= " AND BAD.BOL = '".$BOL."'";
		}
		if($book_num != ""){
			$sql .= " AND BAD.BOOKING_NUM = '".$book_num."'";
		}
		if($barcode != ""){
			$sql .= " AND CT.PALLET_ID = '".$barcode."'";
		}
		$sql .= " ORDER BY CT.PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "DELETE FROM BOOKING_ADDITIONAL_DATA
					WHERE PALLET_ID = '".$row['PALLET_ID']."'
						AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'
						AND RECEIVER_ID = '".$row['RECEIVER_ID']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
//			echo $sql."<br>";

			$sql = "DELETE FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$row['PALLET_ID']."'
						AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'
						AND RECEIVER_ID = '".$row['RECEIVER_ID']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
//			echo $sql."<br>";
		}

		echo "<font color=\"#0000FF\">Deletion Complete.</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Non-Received Deletion Program
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="delete_booking.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">ARV#:&nbsp;&nbsp;&nbsp;</td>
		<td><select name="vessel">
<?
		$sql = "SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE REMARK = 'BOOKINGSYSTEM'
				AND DATE_RECEIVED IS NULL
				ORDER BY ARRIVAL_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['ARRIVAL_NUM']; ?>"<? if($row['ARRIVAL_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['ARRIVAL_NUM'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Booking#:&nbsp;&nbsp;&nbsp;</td>
		<td><select name="BOL"><option value=""> </option>
<?
		$sql = "SELECT DISTINCT BAD.BOOKING_NUM 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND DATE_RECEIVED IS NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				ORDER BY BAD.BOOKING_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['BOOKING_NUM']; ?>"<? if($row['BOOKING_NUM'] == $book_num){ ?> selected <? } ?>><? echo $row['BOOKING_NUM'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">BoL#:&nbsp;&nbsp;&nbsp;</td>
		<td><select name="BOL"><option value=""> </option>
<?
		$sql = "SELECT DISTINCT BAD.BOL 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND DATE_RECEIVED IS NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				ORDER BY BAD.BOL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['BOL']; ?>"<? if($row['BOL'] == $BOL){ ?> selected <? } ?>><? echo $row['BOL'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Barcode:&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="barcode" value="<? echo $barcode; ?>" size="25" maxlength="25"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
		$total = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="del_data" action="delete_booking.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>"> 
<input type="hidden" name="BOL" value="<? echo $BOL; ?>"> 
<input type="hidden" name="book_num" value="<? echo $book_num; ?>"> 
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
	<tr>
		<td colspan="5" align="center"><font size="3" face="Verdana" color="#FF0000"><b>Once Deleted, TS will not be able to reverse it.  The item will have to be re-EDI-received, or entered manually.  Please Review the following roll(s):</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>ARV#</b></font></td>
		<td><font size="2" face="Verdana"><b>Cust</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>BK#</b></font></td>
	</tr>
<?
		// we know there is at least 1 row, since the checks at the top of the page
		// would break if we didn't
		$sql = "SELECT CT.ARRIVAL_NUM, CT.PALLET_ID, CT.RECEIVER_ID, BAD.BOL, BAD.BOOKING_NUM
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND DATE_RECEIVED IS NULL";
		if($BOL != ""){
			$sql .= " AND BAD.BOL = '".$BOL."'";
		}
		if($book_num != ""){
			$sql .= " AND BAD.BOOKING_NUM = '".$book_num."'";
		}
		if($barcode != ""){
			$sql .= " AND CT.PALLET_ID = '".$barcode."'";
		}
		$sql .= " ORDER BY CT.PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total++;
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOOKING_NUM']; ?></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Total Rolls:</b></font></td>
		<td colspan="4" align="right"><font size="2" face="Verdana"><b><? echo $total; ?></b></font></td>
	</tr>
	<tr><td colspan="5" align="center"><font size="3" face="Verdana" color="#FF0000"><b>Are you SURE you wish to delete these roll(s)?</b></font><br><input type="submit" name="submit" value="Delete Rolls"></td></tr>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>