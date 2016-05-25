<?
/*
*			Adam Walter, Jul 2010
*			This page allows OPS to 
*			edit container numbers on Booking Paper orders
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Container";
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
	$ord = $HTTP_POST_VARS['ord'];
	$newcont = $HTTP_POST_VARS['newcont'];
	$newcont = str_replace("'", "`", $newcont);
	$newcont = str_replace(" ", "", $newcont);
	$newcont = str_replace("\"", "", $newcont);
	$newcont = str_replace("\\", "", $newcont);

	if($submit == "Set Container" && $newcont != ""){
		$sql = "UPDATE BOOKING_ORDERS
				SET CONTAINER_ID = '".$newcont."'
				WHERE ORDER_NUM = '".$ord."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);

		$submit = "";

		echo "<font color=\"#0000FF\">$ord has had it's Container# changed to $newcont</font>";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barnett Container# FIXIT Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="getord" action="BookingContainerChange.php" method="post">
	<tr>
		<td width="15%"><font size="3" face="Verdana"><b>Order#:&nbsp;</b></font></td>
		<td><input name="ord" type="text" value="<? echo $ord; ?>" size="10"></td>
	</tr>
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="Get Container No."></td>
	</tr>
</form>
<?
	if($submit != "" && $ord != "") {
		$sql = "SELECT CONTAINER_ID FROM BOOKING_ORDERS WHERE ORDER_NUM = '".$ord."'";
//echo $sql."<BR>";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>No order with that # in the Booking Paper System.</b></font></td>
	</tr>

<?
		} else {
?>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
<form name="setcont" action="BookingContainerChange.php" method="post">
<input type="hidden" name="ord" value="<? echo $ord; ?>">
	<tr>
		<td><font size="2" face="Verdana">Current Container:</font></td>
		<td><font size="2" face="Verdana"><? echo $row['CONTAINER_ID']; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">New Container:</font></td>
		<td><input name="newcont" type="text" value="" size="20"></td>
	</tr>
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="Set Container"></td>
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