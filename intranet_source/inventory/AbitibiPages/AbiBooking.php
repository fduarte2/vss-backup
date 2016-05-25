<?
/*
*			Adam Walter, Oct 2008
*			This page allows OPS to add a Booking#
*			To previously EDI-received
*			Abitibi paper receipts.
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Abitibi Booking";
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

	if($submit != ""){
		$sales = $HTTP_POST_VARS['sales'];
		$vehicle = $HTTP_POST_VARS['vehicle'];
		$booking = strtoupper(str_replace(" ", "", $HTTP_POST_VARS['booking']));

		if($booking != ""){
			$sql = "UPDATE CARGO_TRACKING SET CARGO_DESCRIPTION = '".$booking." ' || CARGO_DESCRIPTION, BATCH_ID = '".ereg_replace("[a-zA-Z]", "", $booking)."' || BATCH_ID WHERE CONTAINER_ID = '".$vehicle."' AND CARGO_DESCRIPTION LIKE '%".$sales."' AND ARRIVAL_NUM = '4' AND SUBSTR(BATCH_ID, 0, 1) = '-'";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);
		}
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Abitibi Booking Number Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	$sql = "SELECT DISTINCT CONTAINER_ID, SUBSTR(CARGO_DESCRIPTION, INSTR(CARGO_DESCRIPTION, ' ') + 1) THE_SALES FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '4' AND SUBSTR(BATCH_ID, 0, 1) = '-' ORDER BY THE_SALES, CONTAINER_ID";
//	echo $sql;
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana">No Paper currently awaiting Order Numbers</font></td>
	</tr>
<?
	}else{
		$form_number = 0;
?>
	<tr>
		<td width="25%" align="center"><font size="3" face="Verdana">Sales Order</font></td>
		<td width="25%" align="center"><font size="3" face="Verdana">Vehicle</font></td>
		<td width="25%" align="center"><font size="3" face="Verdana">Booking Order</font></td>
		<td width="25%" align="center">&nbsp;</td>
	</td>
<?
		do{
			$form_number++;
?>
	<form name="upload_data<? echo $form_number; ?>" action="AbiBooking.php" method="post">
	<tr>
		<input type="hidden" name="sales" value="<? echo $row['THE_SALES']; ?>">
		<input type="hidden" name="vehicle" value="<? echo $row['CONTAINER_ID']; ?>">
		<td align="center"><font size="2" face="Verdana"><? echo $row['THE_SALES']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $row['CONTAINER_ID']; ?></font></td>
		<td align="center"><input type="text" name="booking" size="20"></td>
		<td><input type="submit" name="submit" value="Attach Booking No."></td>
	</tr>
	</form>
<?
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>
<?
	include("pow_footer.php");
?>