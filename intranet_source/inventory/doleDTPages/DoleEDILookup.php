<?
 /*
*			Adam Walter, May 2010
*			This page allows OPS review DOCKTICKET EDI transmissions
******************************************************************/
 // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "DT EDI viewer";
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
	$DT = $HTTP_POST_VARS['DT'];
	$date_from = $HTTP_POST_VARS['date_from'];
	$date_to = $HTTP_POST_VARS['date_to'];
	$status = $HTTP_POST_VARS['status'];
	$filename = $HTTP_POST_VARS['filename'];

	if($submit != ""){
		if($pallet == "" && $DT == "" && $date_from == "" && $date_to == "" && $status == "all" && $filename == ""){
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
	    <font size="5" face="Verdana" color="#0066CC">DockTicket EDI lookup
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="DoleEDILookup.php" method="post" name="the_upload">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>All Search criteria is optional; but the more fine-tuned the search, the faster the results will be posted.  At least ONE choice is required.</b><br>&nbsp;<br>&nbsp;<br></font></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Pallet#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="pallet" type="text" size="30" maxlength="32" value="<? echo $pallet; ?>">(only works with successful EDIs)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>DockTicket#</b></font></td>
		<td><font size="2" face="Verdana"><input name="DT" type="text" size="15" maxlength="15" value="<? echo $DT; ?>">(only works with successful EDIs)</font></td>
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
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>DockTicket</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI received on</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Fail Reason</b></font></td>
	</tr>
<?
		$sql = "SELECT DISTINCT FILENAME, NVL(TO_CHAR(CT.RECEIVER_ID), 'UKN') THE_REC, TO_CHAR(DATE_PARSED, 'MM/DD/YYYY HH24:MI:SS') THE_DATE, 
					FILE_RESULT, NVL(BATCH_ID, 'UKN') THE_BATCH, SHORT_REASON
				FROM CARGO_TRACKING CT, EDI_FILE_HISTORY EFH
				WHERE EFH.FILENAME LIKE '%' || SUBSTR(SOURCE_NOTE(+), INSTR(SOURCE_NOTE(+), ' ', 1, 2) + 1) || '%'
				AND EFH.CARGO_SYSTEM = 'DOCKTICKET'
				AND CT.REMARK(+) = 'DOLEPAPERSYSTEM'";
		if($date_to != ""){
			$sql .= " AND EFH.DATE_PARSED <= TO_DATE('".$date_to."', 'MM/DD/YYYY')";
		}
		if($date_from != ""){
			$sql .= " AND EFH.DATE_PARSED >= TO_DATE('".$date_from."', 'MM/DD/YYYY')";
		}
		if($DT != ""){
			$sql .= " AND BOL(+) = '".$DT."'";
		}
		if($status != "all"){
			$sql .= " AND FILE_RESULT = '".$status."'";
		}
		if($filename != ""){
			$sql .= " AND FILENAME LIKE '%".$filename."%'";
		}
		if($pallet != ""){
			$sql .= " AND CT.PALLET_ID(+) = '".$pallet."'";
		}
		
		$sql .= " ORDER BY FILENAME";
//echo $sql."<BR";
		$ora_success = ora_parse($short_term_cursor, $sql);
		$ora_success = ora_exec($short_term_cursor, $sql);
		if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="6"><font size="3" face="Verdana"><b>No Files matching Search Criteria</b></font></td>
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
		<td><font size="2" face="Verdana"><a href="../../TS_Program/DoleEDI/<? echo $directory."/".$row['FILENAME']; ?>"><? echo $row['FILENAME']; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BATCH']; ?></font></td>
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