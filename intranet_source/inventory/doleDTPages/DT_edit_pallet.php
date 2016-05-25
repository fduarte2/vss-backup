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
	    <font size="5" face="Verdana" color="#0066CC">DT Modification Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="DT_list_edit.php" method="post">
<input type="hidden" name="plt" value="<? echo $plt; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
<?
	// we know this exists, otherwise we wouldn't be at this point
	$sql = "SELECT * FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$plt."'
				AND RECEIVER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$arv."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$cargo_desc = explode(" ", ociresult($short_term_data, "CARGO_DESCRIPTION"));
?>
	<tr>
		<td colspan="3">Customer#  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td colspan="3">LR#  <? echo $arv; ?></td>
	</tr>
	<tr>
		<td colspan="3">Barcode  <? echo $plt; ?></td>
	</tr>
	<tr>
		<td colspan="3">Dock Ticket#  <? echo ociresult($short_term_data, "BOL"); ?><br><hr><br></td>
	</tr>
	<tr>
		<td width="15%">&nbsp;</td>
		<td width="15%"><b><font size="2" face="Verdana">Current</font></b></td>
		<td><font size="2" face="Verdana"><b>New</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Current Cargo Description</b></font></td>
		<td colspan="2"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "CARGO_DESCRIPTION"); ?></b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Reference Order (PRF)</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo $cargo_desc[0]; ?></font></td>
		<td><input type="text" name="PRF" size="15" "maxlength="15" value="<? echo $cargo_desc[0]; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Code</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo $cargo_desc[1]; ?></font></td>
		<td><input type="text" name="batch_id" size="10" "maxlength="6" value="<? echo $cargo_desc[1]; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Reference Order (BM)</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo $cargo_desc[2]; ?></font></td>
		<td><input type="text" name="BM" size="15" "maxlength="15" value="<? echo $cargo_desc[2]; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Commodity Code</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "COMMODITY_CODE"); ?></font></td>
		<td><font size="2" face="Verdana">(cannot change)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Roll Weight</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WEIGHT"); ?></font></td>
		<td><input type="text" name="weight" size="10" "maxlength="10" value="<? echo ociresult($short_term_data, "WEIGHT"); ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Basis Weight</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "MARK"); ?></font></td>
		<td><font size="2" face="Verdana">Basis Weight changes handled by Code line</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>QTY In House</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "QTY_IN_HOUSE"); ?></font></td>
		<td><font size="2" face="Verdana">(cannot change)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Linear Feet</b></td>
		<td width="15%"><font size="2" face="Verdana"><? echo ociresult($short_term_data, "VARIETY"); ?></font></td>
		<td><input type="text" name="linearfeet" size="10" "maxlength="10" value="<? echo ociresult($short_term_data, "VARIETY"); ?>"></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Changes"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");