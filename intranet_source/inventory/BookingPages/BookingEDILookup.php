<?
 /*
*			Adam Walter, May 2010
*			This page allows OPS review Booking EDI transmissions
******************************************************************/
 // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Booking EDI viewer";
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
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$short_term_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$pallet = $HTTP_POST_VARS['pallet'];
	$PO = $HTTP_POST_VARS['PO'];
	$BOL = $HTTP_POST_VARS['BOL'];
	$date_from = $HTTP_POST_VARS['date_from'];
	$date_to = $HTTP_POST_VARS['date_to'];
	$status = $HTTP_POST_VARS['status'];
	$filename = $HTTP_POST_VARS['filename'];

	if($submit != ""){
		if($pallet == "" && $PO == "" && $BOL == "" && $date_from == "" && $date_to == "" && $status == "all" && $filename == ""){
			echo "<font color=\"FF0000\" size=\"3\"><b>At least 1 criteria must be chosen</b></font>";
			$submit = "";
		}
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking EDI lookup
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="BookingEDILookup.php" method="post" name="the_upload">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>All Search criteria is optional; but the more fine-tuned the search, the faster the results will be posted.  At least ONE choice is required.</b><br>&nbsp;<br>&nbsp;<br></font></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Pallet#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="pallet" type="text" size="30" maxlength="32" value="<? echo $pallet; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PO (Order)#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="PO" type="text" size="15" maxlength="15" value="<? echo $PO; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>BoL#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="BOL" type="text" size="15" maxlength="15" value="<? echo $BOL; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Date (From)&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="date_from" type="text" size="10" maxlength="10" value="<? echo $date_from; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Date (To)&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="date_to" type="text" size="10" maxlength="10" value="<? echo $date_to; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>EDI Status&nbsp;&nbsp;</b></font></td>
		<td><select name="status"><option value="all">All</option>
					<option value="failed" <? if($status=="failed"){?>selected<?}?>>Failed</option>
					<option value="passed" <? if($status=="passed"){?>selected<?}?>>Passed</option>
			</select></td>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana"><b>EDI - Customer Code&nbsp;&nbsp;</b></font></td>
		<td><select name="raw_cust"><option value="all">All</option> !-->
	<tr>
		<td><font size="2" face="Verdana"><b>Filename (or partial)&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="filename" type="text" size="30" maxlength="60" value="<? echo $filename; ?>"></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve File List"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>File</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Pallet Count</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>PO (order#)</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI received on</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Failed Reason</b></font></td>
	</tr>
<?
		$sql = "SELECT DISTINCT FILENAME, SHORT_REASON,
					CT.RECEIVER_ID, TO_CHAR(DATE_PARSED, 'MM/DD/YYYY HH24:MI:SS') THE_DATE, FILE_RESULT, BAD.BOL, ORDER_NUM
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, EDI_FILE_HISTORY EFH
				WHERE CT.PALLET_ID = BAD.PALLET_ID(+)
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID(+)
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM(+)
				AND CT.SOURCE_NOTE(+) = EFH.FILENAME
				AND EFH.CARGO_SYSTEM = 'BOOKING'";
		if($date_to != ""){
			$sql .= " AND EFH.DATE_PARSED <= TO_DATE('".$date_to."', 'MM/DD/YYYY')";
		}
		if($date_from != ""){
			$sql .= " AND EFH.DATE_PARSED >= TO_DATE('".$date_from."', 'MM/DD/YYYY')";
		}
		if($PO != ""){
			$sql .= " AND BAD.ORDER_NUM = '".$PO."'";
		}
		if($BOL != ""){
			$sql .= " AND BAD.BOL = '".$BOL."'";
		}
		if($status != "all"){
			$sql .= " AND FILE_RESULT = '".$status."'";
		}
		if($filename != ""){
			$sql .= " AND FILENAME LIKE '%".$filename."%'";
		}
		if($pallet != ""){
			$sql .= " AND CT.PALLET_ID = '".$pallet."'";
		}
		
//		$sql .= " GROUP BY FILENAME, SHORT_REASON, CT.RECEIVER_ID, TO_CHAR(DATE_PARSED, 'MM/DD/YYYY HH24:MI:SS'), FILE_RESULT, BAD.BOL, ORDER_NUM";
		$sql .= " ORDER BY FILENAME";
		$ora_success = ora_parse($short_term_cursor, $sql);
		$ora_success = ora_exec($short_term_cursor, $sql);
//		echo $sql."<br>";
		if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="7"><font size="3" face="Verdana"><b>No Files matching Search Criteria</b></font></td>
	</tr>
<?
		} else {
			do {
				if($row['FILE_RESULT'] == "passed"){
					$directory = "complete";
				} else {
					$directory = "failed";
				}
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="../../TS_Program/BarnettEDIv2/<? echo $directory."/".$row['FILENAME']; ?>"><? echo $row['FILENAME']; ?></a></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $row['THE_PLTS']; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['FILE_RESULT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SHORT_REASON']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>