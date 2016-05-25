<?
/*
*	Adam Walter, May 2013
*
*	A page to modify unreceived DT-based rolls
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "SUPV System";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$plt = $HTTP_POST_VARS['plt'];
	$cust = $HTTP_POST_VARS['cust'];
	$arv = $HTTP_POST_VARS['arv'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">BK Modification Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="BK_list_edit.php" method="post">
<input type="hidden" name="plt" value="<? echo $plt; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
<?
	// we know this exists, otherwise we wouldn't be at this point
	$sql = "SELECT BAD.* FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
			WHERE CT.PALLET_ID = '".$plt."'
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = '".$arv."'
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.PALLET_ID = BAD.PALLET_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
?>
	<tr>
		<td colspan="3">Customer#  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td colspan="3">LR#  <? echo $arv; ?></td>
	</tr>
	<tr>
		<td colspan="3">Barcode  <? echo $plt; ?><br><hr><br></td>
	</tr>
	<tr>
		<td width="15%">&nbsp;</td>
		<td width="15%"><b><font size="2" face="Verdana">Current</font></b></td>
		<td><font size="2" face="Verdana"><b>New</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BOOKING_NUM"); ?></font></td>
		<td><input type="text" name="BK" size="15" "maxlength="15" value="<? echo ociresult($short_term_data, "BOOKING_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Paper Code</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PRODUCT_CODE"); ?></font></td>
		<td><input type="text" name="code" size="15" "maxlength="15" value="<? echo ociresult($short_term_data, "PRODUCT_CODE"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>BoL</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BOL"); ?></font></td>
		<td><input type="text" name="BOL" size="15" "maxlength="15" value="<? echo ociresult($short_term_data, "BOL"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>PO#</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "ORDER_NUM"); ?></font></td>
		<td><input type="text" name="PO" size="15" "maxlength="15" value="<? echo ociresult($short_term_data, "ORDER_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Diameter</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DIAMETER").ociresult($short_term_data, "DIAMETER_MEAS"); ?></font></td>
		<td><input type="text" name="dia" size="10" "maxlength="10" value="<? echo ociresult($short_term_data, "DIAMETER"); ?>"><? echo ociresult($short_term_data, "DIAMETER_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Width</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WIDTH").ociresult($short_term_data, "WIDTH_MEAS"); ?></font></td>
		<td><input type="text" name="width" size="10" "maxlength="10" value="<? echo ociresult($short_term_data, "WIDTH"); ?>"><? echo ociresult($short_term_data, "WIDTH_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Length</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "LENGTH").ociresult($short_term_data, "LENGTH_MEAS"); ?></font></td>
		<td><input type="text" name="length" size="10" "maxlength="10" value="<? echo ociresult($short_term_data, "LENGTH"); ?>"><? echo ociresult($short_term_data, "LENGTH_MEAS"); ?></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Changes"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");