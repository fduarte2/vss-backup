<?
/*
*	Adam Walter, Feb 2010.
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
	$DT = $HTTP_POST_VARS['DT'];
	$manifest = $HTTP_POST_VARS['manifest'];
	$barcode = $HTTP_POST_VARS['barcode'];
	$submit = $HTTP_POST_VARS['submit'];

	$display_error = "";

	if($submit == "Check Barcodes"){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND DATE_RECEIVED IS NOT NULL
				AND ARRIVAL_NUM = '".$vessel."'";
		if($DT != ""){
			$sql .= " AND BOL = '".$DT."'";
		}
		if($manifest != ""){
			$sql .= " AND CARGO_DESCRIPTION LIKE '%".$manifest."%'";
		}
		if($barcode != ""){
			$sql .= " AND PALLET_ID = '".$barcode."'";
		}
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$received = $short_term_row['THE_COUNT'];

		if($received > 0){
			$display_error .= "The entered criteria contains already-received rolls.  Please narrow the search down so that only Un-Received rolls are returned.<br>";
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND DATE_RECEIVED IS NULL
				AND ARRIVAL_NUM = '".$vessel."'";
		if($DT != ""){
			$sql .= " AND BOL = '".$DT."'";
		}
		if($manifest != ""){
			$sql .= " AND CARGO_DESCRIPTION LIKE '%".$manifest."%'";
		}
		if($barcode != ""){
			$sql .= " AND PALLET_ID = '".$barcode."'";
		}
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
		$sql = "DELETE FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND DATE_RECEIVED IS NULL
				AND ARRIVAL_NUM = '".$vessel."'";
		if($DT != ""){
			$sql .= " AND BOL = '".$DT."'";
		}
		if($manifest != ""){
			$sql .= " AND CARGO_DESCRIPTION LIKE '%".$manifest."%'";
		}
		if($barcode != ""){
			$sql .= " AND PALLET_ID = '".$barcode."'";
		}
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		echo "<font color=\"#0000FF\">Deletion Compelte</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole DT Deletion DProgram
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="delete_DT.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">ARV#:&nbsp;&nbsp;&nbsp;</td>
		<td><select name="vessel">
<?
		$sql = "SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
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
		<td align="left"><font size="2" face="Verdana">DT#:&nbsp;&nbsp;&nbsp;</td>
		<td><select name="DT"><option value=""> </option>
<?
		$sql = "SELECT DISTINCT BOL FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND DATE_RECEIVED IS NULL
				ORDER BY BOL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['BOL']; ?>"<? if($row['BOL'] == $DT){ ?> selected <? } ?>><? echo $row['BOL'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Manifest#:&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="manifest" value="<? echo $manifest; ?>" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Barcode:&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="barcode" value="<? echo $barcode; ?>" size="25" maxlength="25"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Check Barcodes"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Check Barcodes"){
		$total = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="del_data" action="delete_DT.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>"> 
<input type="hidden" name="DT" value="<? echo $DT; ?>"> 
<input type="hidden" name="manifest" value="<? echo $manifest; ?>"> 
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
	<tr>
		<td colspan="5" align="center"><font size="3" face="Verdana" color="#FF0000"><b>Once Deleted, TS will not be able to reverse it.  The item will have to be re-EDI-received, or entered manually.  Please Review the following roll(s):</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>ARV#</b></font></td>
		<td><font size="2" face="Verdana"><b>Cust</b></font></td>
		<td><font size="2" face="Verdana"><b>DT</b></font></td>
		<td><font size="2" face="Verdana"><b>Desc</b></font></td>
	</tr>
<?
		// we know there is at least 1 row, since the checks at the top of the page
		// would break if we didn't
		$sql = "SELECT ARRIVAL_NUM, PALLET_ID, RECEIVER_ID, BOL, CARGO_DESCRIPTION
				FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND ARRIVAL_NUM = '".$vessel."'
				AND DATE_RECEIVED IS NULL";
		if($DT != ""){
			$sql .= " AND BOL = '".$DT."'";
		}
		if($manifest != ""){
			$sql .= " AND CARGO_DESCRIPTION LIKE '%".$manifest."%'";
		}
		if($barcode != ""){
			$sql .= " AND PALLET_ID = '".$barcode."'";
		}
		$sql .= " ORDER BY PALLET_ID";
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
		<td><font size="2" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?></font></td>
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