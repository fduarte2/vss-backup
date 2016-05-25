<?
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			based on ARV# et. al.
*
*		A VERSION OF THIS IS ON EPORT.  IF YOU CHANGE THIS FILE,
*		LOOK IN var/www/html/secure/booking FOR THE OTHER.
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Lookup";
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

//	include("useful_info.php");
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	$timeframe = $HTTP_POST_VARS['timeframe'];
	$cust = $HTTP_POST_VARS['cust'];
	$LR = $HTTP_POST_VARS['LR'];
	if($LR == ""){
		$LR = "all";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Railcar Lookup Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="BookingARVLookup.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Search History:</font>&nbsp;<select name="timeframe">
						<option value="twomonths">Unreceived/Shipped within last 2 months</option>
						<option value="all" <? if($timeframe == "all"){ ?> selected <?}?>>All Time</option>
				</select></td>
		<td align="center"><font size="2" face="Verdana">Customer (Optional):</font>&nbsp;<select name="cust">
						<option value="all" <? if($cust == "all"){ ?> selected <?}?>>All</option>
						<option value="314" <? if($cust == "314"){ ?> selected <?}?>>314</option>
						<option value="338" <? if($cust == "338"){ ?> selected <?}?>>338</option>
						<option value="517" <? if($cust == "517"){ ?> selected <?}?>>517</option>
				</select></td>
		<td align="right"><font size="2" face="Verdana">Arrival # (optional):</font>&nbsp;
			<input type="text" name="LR" size="15" maxlength="15" value="<? echo $LR; ?>"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>

<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT CT.ARRIVAL_NUM, NVL(BAD.BOOKING_NUM, '---NONE---') THE_BOOK, BAD.ORDER_NUM, CT.RECEIVER_ID, BPGC.SSCC_GRADE_CODE,
					COUNT(*) THE_COUNT, BAD.BOL, BAD.SHIPFROMMILL, NVL(TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY'), '---NONE---') THE_REC
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC
				WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE";
		if($cust != "all"){
				$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
		}
		if($timeframe == "twomonths"){
			$sql .= " AND (CT.QTY_IN_HOUSE > 0 OR CT.ARRIVAL_NUM IN
								(SELECT ARRIVAL_NUM FROM CARGO_ACTIVITY
								WHERE SERVICE_CODE = '6'
								AND ACTIVITY_DESCRIPTION IS NULL
								AND DATE_OF_ACTIVITY > SYSDATE - 60))";
		}
		if($LR != "all"){
			$sql .= " AND CT.ARRIVAL_NUM = '".$LR."'";
		}
		$sql .= " GROUP BY CT.ARRIVAL_NUM, CT.RECEIVER_ID, NVL(BAD.BOOKING_NUM, '---NONE---'), BAD.ORDER_NUM, BAD.BOL, BAD.SHIPFROMMILL, BPGC.SSCC_GRADE_CODE";
		$sql .= " ORDER BY CT.ARRIVAL_NUM, CT.RECEIVER_ID, NVL(BAD.BOOKING_NUM, '---NONE---'), BAD.ORDER_NUM, BPGC.SSCC_GRADE_CODE";

		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><b>No Railcars matching selected criteria.</b></font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Railcar</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer#</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking #</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade Code</b></font></td>
		<td><font size="2" face="Verdana"><b>#Rolls Expected</b></font></td>
		<td><font size="2" face="Verdana"><b>BOL</b></font></td>
		<td><font size="2" face="Verdana"><b>Mill</b></font></td>
		<td><font size="2" face="Verdana"><b>First Received</b></font></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><a target="BookingARVLookupPopup.php?timeframe=<? echo $timeframe; ?>&LR=<? echo $row['ARRIVAL_NUM']; ?>&cust=<? echo $row['RECEIVER_ID']; ?>" href="BookingARVLookupPopup.php?timeframe=<? echo $timeframe; ?>&LR=<? echo $row['ARRIVAL_NUM']; ?>&cust=<? echo $row['RECEIVER_ID']; ?>"><? echo $row['ARRIVAL_NUM']; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SSCC_GRADE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SHIPFROMMILL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>