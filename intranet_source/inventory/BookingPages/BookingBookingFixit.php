<?
/*
*			Adam Walter, Jul 2010
*			This page allows OPS to fix a Booking# for specific criteria
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Booking Fixit";
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
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$arv = $HTTP_POST_VARS['arv'];
	$booking = $HTTP_POST_VARS['booking'];
	$newbook = strtoupper($HTTP_POST_VARS['newbook']);

	if($submit == "CHANGE Booking Info"){
		$sql = "UPDATE BOOKING_ADDITIONAL_DATA
				SET BOOKING_NUM = '".$newbook."'
				WHERE BOOKING_NUM = '".$booking."'
					AND ARRIVAL_NUM = '".$arv."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);

		$submit = "";

		echo "<font color=\"#0000FF\">$booking has been changed to $newbook for $arv</font>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Number FIXIT Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="getbook" action="BookingBookingFixit.php" method="post">
	<tr>
		<td width="15%"><font size="3" face="Verdana"><b>Booking#:&nbsp;</b></font></td>
		<td><input name="booking" type="text" value="<? echo $booking; ?>" size="20"></td>
	</tr>
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="Get Booking Info"></td>
	</tr>
</form>
<?
	if($submit != "") {
?>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
<form name="getarv" action="BookingBookingFixit.php" method="post">
<input type="hidden" name="booking" value="<? echo $booking; ?>">
	<tr>
		<td><font size="3" face="Verdana"><b>ARV#:&nbsp;</b></font></td>
		<td><select name="arv">
<?
		$sql = "SELECT DISTINCT ARRIVAL_NUM FROM BOOKING_ADDITIONAL_DATA WHERE BOOKING_NUM = '".$booking."' ORDER BY ARRIVAL_NUM";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="">No ARV# matches entered booking#.</option>
<?
		} else {
?>
					<option value="">Select an Arrival#</option>
<?
			do {
?>
					<option value="<? echo $row['ARRIVAL_NUM']; ?>"<? if($row['ARRIVAL_NUM'] == $arv){?> selected <?}?>><? echo $row['ARRIVAL_NUM']; ?></option>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="Get Arrival Info"></td>
	</tr>
</form>
<?
		if($submit != "" && $booking != "" && $arv != "") {
?>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
<form name="changebook" action="BookingBookingFixit.php" method="post">
<input type="hidden" name="booking" value="<? echo $booking; ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
	<tr>
		<td colspan="2" align="center">
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="5" align="center"><font size="3" face="Verdana"><b>Booking - <? echo $booking; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Arrival - <? echo $arv; ?></b></font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Roll#</b></font></td>
					<td><font size="2" face="Verdana"><b>Customer</b></font></td>
					<td><font size="2" face="Verdana"><b>Paper Code</b></font></td>
					<td><font size="2" face="Verdana"><b>BoL</b></font></td>
					<td><font size="2" face="Verdana"><b>P.O.</b></font></td>
				</tr>

<?
			$sql = "SELECT PALLET_ID, RECEIVER_ID, NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN') THE_CODE, BOL, ORDER_NUM
					FROM BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC
					WHERE BOOKING_NUM = '".$booking."'
						AND ARRIVAL_NUM = '".$arv."'
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<tr>
					<td><? echo $row['PALLET_ID']; ?></td>
					<td><? echo $row['RECEIVER_ID']; ?></td>
					<td><? echo $row['THE_CODE']; ?></td>
					<td><? echo $row['BOL']; ?></td>
					<td><? echo $row['ORDER_NUM']; ?></td>
				</tr>
<?
			}
?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><b>NEW Booking#:&nbsp;</b></font></td>
		<td><input name="newbook" type="text" value="" size="20"></td>
	</tr>
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="CHANGE Booking Info"></td>
	</tr>
</form>
<?
		}
	}
?>
</table>
<?
	include("pow_footer.php");
?>
