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

	if($submit == "Save Changes"){
		$plt = $HTTP_POST_VARS['plt'];
		$cust = $HTTP_POST_VARS['cust'];
		$arv = $HTTP_POST_VARS['arv'];
		$BK = trim(str_replace("'", "`", $HTTP_POST_VARS['BK']));
		$code = trim(str_replace("'", "`", $HTTP_POST_VARS['code']));
		$BOL = trim(str_replace("'", "`", $HTTP_POST_VARS['BOL']));
		$PO = trim(str_replace("'", "`", $HTTP_POST_VARS['PO']));
		$dia = trim(str_replace("'", "`", $HTTP_POST_VARS['dia']));
		$width = trim(str_replace("'", "`", $HTTP_POST_VARS['width']));
		$length = trim(str_replace("'", "`", $HTTP_POST_VARS['length']));

		// validity checks
		$error_msg = "";

		$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_PAPER_GRADE_CODE
				WHERE PRODUCT_CODE = '".$code."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$error_msg .= "Entered Paper Code (".$code.") not found in system.<br>&nbsp;&nbsp;&nbsp;If this code is valid, please add it before changing this roll's code.<br>";
		}

		if(!is_numeric($dia) || $dia <= 0){
			$error_msg .= "Entered Diameter (".$dia.") not valid.<br>";
		}

		if(!is_numeric($width) || $width <= 0){
			$error_msg .= "Entered Width (".$width.") not valid.<br>";
		}

		if(!is_numeric($length) || $length <= 0){
			$error_msg .= "Entered Length (".$length.") not valid.<br>";
		}

		// did it pass?
		if($error_msg != ""){
			echo "<font color=\"#FF0000\">".$error_msg."<br><b>Please use your browser's Back Button to return to the previous screen and fix the errors.<b></font>";
		} else {
			// alright, lets update!
			

			$sql = "UPDATE BOOKING_ADDITIONAL_DATA
					SET BOOKING_NUM = '".$BK."',
						DIAMETER = '".$dia."',
						WIDTH = '".$width."',
						LENGTH = '".$length."',
						ORDER_NUM = '".$PO."',
						PRODUCT_CODE = '".$code."',
						BOL = '".$BOL."'
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$arv."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$success_msg = "<b>Roll ".$plt." Updated.</b><br>";
			echo "<font color=\"#0000FF\">".$success_msg."</font>";
		}
		$submit = "";
		$BK = "";
	}

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
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Arrival#:  </font></td>
		<td><input type="text" name="arv" size="15" maxlength="10" value="<? echo $arv; ?>"></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Booking#:  </font></td>
		<td><input type="text" name="BK" size="15" maxlength="15" value="<? echo $BK; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Paper Code</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>PO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter</b></font></td>
		<td><font size="2" face="Verdana"><b>Width</b></font></td>
		<td><font size="2" face="Verdana"><b>Length</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
		$sql = "SELECT BAD.* FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE REMARK = 'BOOKINGSYSTEM'
					AND CT.ARRIVAL_NUM = '".$arv."'
					AND BAD.BOOKING_NUM = '".$BK."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.PALLET_ID = BAD.PALLET_ID
				ORDER BY CT.PALLET_ID";
		$stid = ociparse($rfconn, $sql);
//		echo $sql."<br>";
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana">No Pallets matching Search Criteria.</font></td>
	</tr>
<?		
		} else {
			$counter = 0;
			do {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
							AND CUSTOMER_ID = '".ociresult($stid, "RECEIVER_ID")."'
							AND ARRIVAL_NUM = '".$arv."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);

?>
<form name="alter<? echo $counter++; ?>" action="BK_edit_pallet.php" method="post">
<input type="hidden" name="plt" value="<? echo ociresult($stid, "PALLET_ID"); ?>">
<input type="hidden" name="cust" value="<? echo ociresult($stid, "RECEIVER_ID"); ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PRODUCT_CODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "BOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DIAMETER").ociresult($stid, "DIAMETER_MEAS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "WIDTH").ociresult($stid, "WIDTH_MEAS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "LENGTH").ociresult($stid, "LENGTH_MEAS"); ?></font></td>
<?
				if(ociresult($short_term_data, "THE_COUNT") <= 0){
?>
		<td><input type="submit" name="submit" value="Edit Roll"></td>
<?
				} else {
?>
		<td><font size="2" face="Verdana" color="#FF0000">Activity Detected, Cannot Modify</font></td>
<?
				}
?>
	</tr>
</form>
<?
			} while(ocifetch($stid));
		}
?>
</table>
<?
	}
	include("pow_footer.php");