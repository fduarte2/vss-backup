<?
/*
*			Adam Walter, Sep 2010
*
*			A page to list all in-house chilean pallets, chosen by vessel,
*			Broken down into subheadings (customer 1st, commodity 2nd)
********************************************************************************/

/*  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Cargo In House Report";
  $area_type = "INVE";
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

*/
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$select_cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];

?>
<?
	if($hide != "hide" || $vessel == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Cargo In House (By Vessel) Report
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="CargoInHouse.php" method="post" name="the_upload">
	<tr>
		<td>Select a Vessel:&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
			$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' ORDER BY LR_NUM DESC";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			while(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
			}
?>
					</select></font></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td align="left"><input type="checkbox" value="hide" name="hide" checked>&nbsp;&nbsp;Check here to hide the search boxes (useful for printing)</td>
	</tr>
</form>
	<tr>
		<td>&nbsp;<hr>&nbsp;</td>
	</tr>
</table>
<?
	}
	if($vessel != ""){
		$current_cust = "butterfingers";
		$current_comm = "everquest";
		$comm_subtotal_ctn_ih = 0;
		$comm_subtotal_ctn_rec = 0;
		$comm_subtotal_plt = 0;

		$cust_subtotal_ctn_ih = 0;
		$cust_subtotal_ctn_rec = 0;
		$cust_subtotal_plt = 0;

		$grand_total_ctn_ih = 0;
		$grand_total_ctn_rec = 0;
		$grand_total_plt = 0;

		$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VES FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $row['THE_VES'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td align="center"><font size="4" face="Verdana"><b>CARGO IN HOUSE REPORT</b></font></td></tr>
	<tr><td align="center"><font size="2" face="Verdana">Generated On:  <? echo date('m/d/y h:i:s'); ?></font></td></tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td bgcolor="#00EE00"><font size="3" face="Verdana"><b>Customer</b></td>
		<td bgcolor="#00FFFF"><font size="3" face="Verdana"><b>Commodity</b></td>
		<td><font size="3" face="Verdana"><b>Pallet ID</b></td>
		<td><font size="3" face="Verdana"><b>CTN RCV'D</b></td>
		<td><font size="3" face="Verdana"><b>CTN In-House</b></td>
	</tr>
	<tr>
		<td colspan="5" align="center" bgcolor="#FF6600"><font size="3" face="Verdana"><? echo $vessel_name; ?></font></td>
	</tr>
<?
		$sql = "SELECT RECEIVER_ID, COMP.COMMODITY_CODE, CUSTOMER_NAME, COMMODITY_NAME, PALLET_ID, QTY_RECEIVED, QTY_IN_HOUSE
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM = '".$vessel."'
				AND DATE_RECEIVED IS NOT NULL
				AND COMP.COMMODITY_TYPE = 'CHILEAN'
				AND RECEIVER_ID != '453'
				ORDER BY RECEIVER_ID, COMMODITY_CODE";
		ora_parse($select_cursor, $sql);
		ora_exec($select_cursor);
		while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

			if(($select_row['COMMODITY_NAME'] != $current_comm || $select_row['CUSTOMER_NAME'] != $current_cust) && $comm_subtotal_plt > 0){
				// total commodity, or customer break
?>
	<tr>
		<td colspan="1">&nbsp;</td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_plt; ?></b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_ctn_rec; ?></b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_ctn_ih; ?></b></font></td>
	</tr>
<?
				$comm_subtotal_ctn_ih = 0;
				$comm_subtotal_ctn_rec = 0;
				$comm_subtotal_plt = 0;
			}

			if($select_row['CUSTOMER_NAME'] != $current_cust && $cust_subtotal_plt > 0){
				// total customer
?>
	<tr>
		<td colspan="2" bgcolor="#99FF66"><font size="2" face="Verdana"><b>Total:</font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_plt; ?></b></font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_ctn_rec; ?></b></font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_ctn_ih; ?></b></font></td>
	</tr>
<?
				$cust_subtotal_ctn_ih = 0;
				$cust_subtotal_ctn_rec = 0;
				$cust_subtotal_plt = 0;
			}

			if($select_row['CUSTOMER_NAME'] != $current_cust){
				// new customer header
				$force_print_comm = true;
				$current_cust = $select_row['CUSTOMER_NAME'];
?>
	<tr>
		<td colspan="5"	bgcolor="#00EE00"><font size="2" face="Verdana"><b><? echo $current_cust; ?></b></font></td>
	</tr>
<?
			} else {
				$force_print_comm = false;
			}

			if(($select_row['COMMODITY_NAME'] != $current_comm) || $force_print_comm){
				// new commodity header
				$current_comm = $select_row['COMMODITY_NAME'];
?>
	<tr>
		<td colspan="1">&nbsp;</td>
		<td colspan="4"	bgcolor="#00FFFF"><font size="2" face="Verdana"><b><? echo $current_comm; ?></b></font></td>
	</tr>
<?
			}
?>

	<tr>
		<td colspan="2">&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $select_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $select_row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $select_row['QTY_IN_HOUSE']; ?></font></td>
	</tr>
<?

			$comm_subtotal_ctn_ih += $select_row['QTY_IN_HOUSE'];
			$comm_subtotal_ctn_rec += $select_row['QTY_RECEIVED'];
			$comm_subtotal_plt++;

			$cust_subtotal_ctn_ih += $select_row['QTY_IN_HOUSE'];
			$cust_subtotal_ctn_rec += $select_row['QTY_RECEIVED'];
			$cust_subtotal_plt++;

			$grand_total_ctn_ih += $select_row['QTY_IN_HOUSE'];
			$grand_total_ctn_rec += $select_row['QTY_RECEIVED'];
			$grand_total_plt++;
		}
?>
	<tr>
		<td colspan="1">&nbsp;</td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_plt; ?></b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_ctn_rec; ?></b></font></td>
		<td colspan="1" bgcolor="#99FFCC"><font size="2" face="Verdana"><b><? echo $comm_subtotal_ctn_ih; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="#99FF66"><font size="2" face="Verdana"><b>Total:</font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_plt; ?></b></font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_ctn_rec; ?></b></font></td>
		<td colspan="1" bgcolor="#99FF66"><font size="2" face="Verdana"><b><? echo $cust_subtotal_ctn_ih; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="#FFCC66"><font size="3" face="Verdana"><b>GRAND Total:</font></td>
		<td colspan="1" bgcolor="#FFCC66"><font size="3" face="Verdana"><b><? echo $grand_total_plt; ?></b></font></td>
		<td colspan="1" bgcolor="#FFCC66"><font size="3" face="Verdana"><b><? echo $grand_total_ctn_rec; ?></b></font></td>
		<td colspan="1" bgcolor="#FFCC66"><font size="3" face="Verdana"><b><? echo $grand_total_ctn_ih; ?></b></font></td>
	</tr>
<?
	}
?>