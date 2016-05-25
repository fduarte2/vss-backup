<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
// $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
//    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  if($user_cust_num != 3000){
	  echo "user not authorized to use this page.  Please use your browser's back button, or choose another link to continue";
	  exit;
  }


  $cursor = ora_open($conn);         // general purpose
  $short_term_cursor = ora_open($conn);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart File Upload History
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<?
	if($submit != "" || true){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Upload #</b></font></td>
		<td><font size="2" face="Verdana"><b>File Uploaded on</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>File</b></font></td>
		<td><font size="2" face="Verdana"><b>Rows in file</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets in file</b></font></td>
	</tr>
<?
		$sql = "SELECT WCUH.UPLOAD_NUMBER, STATUS, TO_CHAR(UPLOAD_DATE, 'MM/DD/YYYY HH24:MI:SS') THE_DATE, FILENAME, FILENAME_APPENDED, UPLOAD_DATE, COUNT(*) ROW_COUNT
				FROM WM_UPLOAD_HISTORY WCUH, WALMART_CARGO_RAW_DUMP WCRD
				WHERE WCUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER";
		if($upload != ""){
			$sql .= " AND WCUH.UPLOAD_NUMBER = '".$upload."'";
		}
		if($date_to != ""){
			$sql .= " AND UPLOAD_DATE <= TO_DATE('".$date_to."', 'MM/DD/YYYY')";
		}
		if($date_from != ""){
			$sql .= " AND UPLOAD_DATE >= TO_DATE('".$date_from."', 'MM/DD/YYYY')";
		}
		if($PO != ""){
			$sql .= " AND WM_PO_NUM = '".$PO."'";
		}
		if($BOL != ""){
			$sql .= " AND BOL = '".$BOL."'";
		}
		if($status != "all" && $status != ""){
			$sql .= " AND STATUS = '".$status."'";
		}
		if($filename != ""){
			$sql .= " AND FILENAME LIKE '%".$filename."%'";
		}
		if($pallet != ""){
			$sql .= " AND PALLET_ID = '".$pallet."'";
		}
		
		$sql .= " GROUP BY WCUH.UPLOAD_NUMBER, STATUS, TO_CHAR(UPLOAD_DATE, 'MM/DD/YYYY HH24:MI:SS'), FILENAME, FILENAME_APPENDED, UPLOAD_DATE";
		$sql .= " ORDER BY UPLOAD_DATE DESC";
//		echo $sql."<br>";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="6"><font size="3" face="Verdana"><b>No Files matching Search Criteria</b></font></td>
	</tr>
<?
		} else {
			do {
				$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$row['UPLOAD_NUMBER']."'";
				$ora_success = ora_parse($short_term_cursor, $sql);
				$ora_success = ora_exec($short_term_cursor, $sql);
				ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['UPLOAD_NUMBER']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['STATUS']; ?></font></td>
		<td><font size="2" face="Verdana"><a href="./WMuploadedfiles/<? echo $row['FILENAME_APPENDED']; ?>"><? echo $row['FILENAME']; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ROW_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['THE_COUNT']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="walmart_cargo_lookup_index.php" method="post" name="the_upload">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b><br>&nbsp;<br>&nbsp;<br>Narrow search down by:</b></font></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Upload #&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="upload" type="text" size="15" maxlength="15" value="<? echo $upload; ?>"></font></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Pallet #&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="pallet" type="text" size="30" maxlength="32" value="<? echo $pallet; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PO#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="PO" type="text" size="15" maxlength="15" value="<? echo $PO; ?>"></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>BoL#&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="BOL" type="text" size="20" maxlength="20" value="<? echo $BOL; ?>"></font></td>
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
<?
	$sql = "SELECT DISTINCT STATUS FROM WM_UPLOAD_HISTORY
			ORDER BY STATUS";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $short_term_row['STATUS']; ?>" <? if($status==$short_term_row['STATUS']){?>selected<?}?>><? echo $short_term_row['STATUS']; ?></option>
<?
	}
?>
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
	include("pow_footer.php");
?>