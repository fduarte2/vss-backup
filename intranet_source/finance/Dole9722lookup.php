<?
 /*
*			Adam Walter, May 2014
*			This page allows retrieval of a raw Dole9722 EDI dump
******************************************************************/
 // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "DT EDI viewer";
  $area_type = "FINA";
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
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
//	$DT = $HTTP_POST_VARS['DT'];
	$date_from = $HTTP_POST_VARS['date_from'];
	$date_to = $HTTP_POST_VARS['date_to'];
	$status = $HTTP_POST_VARS['status'];
	$filename = $HTTP_POST_VARS['filename'];

	if($submit != ""){
		if($pallet == "" && $date_from == "" && $date_to == "" && $status == "all" && $filename == ""){ //$DT == "" &&
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
	    <font size="5" face="Verdana" color="#0066CC">Dole-9722 EDI lookup
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="Dole9722lookup.php" method="post" name="the_upload">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>All Search criteria is optional; but the more fine-tuned the search, the faster the results will be posted.  At least ONE choice is required.</b><br>&nbsp;<br>&nbsp;<br></font></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Pallet#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="pallet" type="text" size="30" maxlength="32" value="<? echo $pallet; ?>">(only works with successful EDIs)</font></td>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana"><b>DockTicket#</b></font></td>
		<td><font size="2" face="Verdana"><input name="DT" type="text" size="15" maxlength="15" value="<? echo $DT; ?>">(only works with successful EDIs)</font></td>
	</tr> !-->
	<tr>
		<td><font size="2" face="Verdana"><b>Move To Database Date (From)&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="date_from" type="text" size="10" maxlength="10" value="<? echo $date_from; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Move To Database Date (To)&nbsp;&nbsp;</b></font></td>
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
<!--		<td><font size="2" face="Verdana"><b>Pallet#</b></font></td> !-->
<!--		<td><font size="2" face="Verdana"><b>DockTicket</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>EDI received on</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Fail Reason</b></font></td>
	</tr>
<?
		$sql = "SELECT DISTINCT FILENAME, FINAL_FILENAME, NVL(TO_CHAR(PUSH_TO_CTCA_ON), 'MM/DD/YYYY HH24:MI:SS') THE_PUSH, PUSH_TO_CTCA_ON,
					DECODE(FILE_FAIL, NULL, 'PASSED', 'FAILED') THE_STATUS, FILE_FAIL, DRFH.FILE_ID
				FROM DOLE9722_RAW_FILE_HEADER DRFH, DOLE9722_RAW_FILE_DETAIL DRFD
				WHERE DRFD.FILE_ID = DRFH.FILE_ID";
		if($date_to != ""){
			$sql .= " AND PUSH_TO_CTCA_ON <= TO_DATE('".$date_to."', 'MM/DD/YYYY')";
		}
		if($date_from != ""){
			$sql .= " AND PUSH_TO_CTCA_ON >= TO_DATE('".$date_from."', 'MM/DD/YYYY')";
		}
//		if($DT != ""){
//			$sql .= " AND BOL(+) = '".$DT."'";
//		}
		if($status == "failed"){
			$sql .= " AND FILE_FAIL IS NOT NULL";
		} elseif($status == "passed"){
			$sql .= " AND FILE_FAIL IS NULL";
		}
		if($filename != ""){
			$sql .= " AND FILENAME LIKE '%".$filename."%'";
		}
		if($pallet != ""){
			$sql .= " AND PALLET_NBR = '".$pallet."'";
		}
		
		$sql .= " ORDER BY PUSH_TO_CTCA_ON DESC";
//echo $sql."<BR";
		$ora_success = ora_parse($short_term_cursor, $sql);
		$ora_success = ora_exec($short_term_cursor, $sql);
		if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="6"><font size="3" face="Verdana"><b>No Files matching Search Criteria.  This retrieval screen was created on 5/14/2014; any EDIs prior to that cannot be retrieved from this screen.</b></font></td>
	</tr>
<?
		} else {
			do {
				if($row['FILE_RESULT'] == ""){
					$directory = "processed";
				} else {
					$directory = "failed";
				}
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="../../TS_Program/DoleEDI9722/<? echo $directory."/".$row['FINAL_FILENAME']; ?>"><? echo $row['FILENAME']; ?></a></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?></font></td> !-->
<!--		<td><font size="2" face="Verdana"><? echo $row['THE_BATCH']; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo $row['THE_PUSH']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STATUS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['FILE_FAILED']; ?>&nbsp;</font></td>
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