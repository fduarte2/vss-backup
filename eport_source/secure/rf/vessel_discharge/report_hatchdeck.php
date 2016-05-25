<?
/*
*		Adam Walter, Sep 2014.
*
*****************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
	list($lr_num, $vessel_name) = split("-", $ship, 2);
	$user = $HTTP_COOKIE_VARS["eport_user"];

	$today = date("F j, Y, g:i A");


	$hatch = $HTTP_POST_VARS['hatch'];
//	echo "hatch: ".$HTTP_POST_VARS['hatch']."<br>";
	$hatch_spec = $HTTP_POST_VARS['hatch_spec'];
//	echo "hatch_spec: ".$HTTP_POST_VARS['hatch_spec']."<br>";
	$discharge = $HTTP_POST_VARS['discharge'];
//	$ship = $HTTP_POST_VARS['ship'];
	$pallet_id = $HTTP_POST_VARS['pallet_id'];


	$more_sql = "";
	if ($eport_customer_id != 0){
//		$more_sql .= " and T.receiver_id = '".$eport_customer_id."' ";
		if($eport_customer_id == "9999"){
			$more_sql .= "AND T.RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
		} else {
			$more_sql .= "AND T.RECEIVER_ID = '".$eport_customer_id."' ";
		}
	}
	if($hatch != ""){
		$more_sql .= " and T.hatch='".$hatch_spec."' ";
	}
	if($discharge == "tbd"){
		$more_sql .= " and T.date_received is null ";
	}
	if($discharge == "only"){
		$more_sql .= " and T.date_received is not null ";
	}
	if($pallet_id != ""){
		$more_sql .= " and T.pallet_id like '%".$pallet_id."%' ";
	}

	$sql = "select pallet_id, CM.commodity_name commodity, C.customer_name customer, NVL(to_char(date_received, 'MM/DD/YY HH24:MI:SS'), 'Not Received') date_received,
				qty_received, qty_damaged, manifested, batch_id, exporter_code,
				qty_in_house, hatch, deck, warehouse_location, cargo_description, mark, cargo_size
			from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM
			where arrival_num = '".$lr_num."' 
				and T.receiver_id = C.customer_id
				and T.commodity_code = CM.commodity_code
				".$more_sql."
			order by customer_name, hatch, commodity, pallet_id asc";
//	echo $sql."<br>";

	$filename2 = date('mdYhis').".xls";
	$reference_loc = "temp/".$filename2;
	$fullpath = "/var/www/secure/rf/vessel_discharge/".$reference_loc;
	$handle2 = fopen($fullpath, "w");

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Vessel Discharge by Hatchdeck
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	// initialize running totals
	$qty_expected_total = 0;
	$qty_received_total = 0;
	$qty_damaged_total = 0;
	$pallets_total = 0;
	$pallets_scanned = 0;

	$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
						<tr>
							<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"".$reference_loc."\">Click Here for the Excel File</a></b></font></td>
						</tr>
					</table><br><br>";
	echo $xls_link;

	$pallet_list = ociparse($rfconn, $sql);
	ociexecute($pallet_list);
	if(!ocifetch($pallet_list)){
		$output = "No pallets found for selected criteria";
	} else {
		$output = "<table border=\"1\" width=\"100%\" bgcolor=\"#FFFFFF\" cellpadding=\"4\" cellspacing=\"0\">
							<tr bgcolor=\"#FFFFFF\">
								<td align=\"center\" colspan=\"7\"><font size=\"3\" face=\"Verdana\"><b>Vessel:  ".$lr_num." - ".$vessel_name."</b></font></td>
							</tr>
							<tr bgcolor=\"#FFFFFF\">
								<td align=\"center\" colspan=\"7\"><font size=\"2\" face=\"Verdana\"><b>As Of:  ".$today."</b></font></td>
							</tr>
							<tr bgcolor=\"#FFFFFF\">
								<td align=\"center\" colspan=\"7\"><font size=\"3\" face=\"Verdana\"><b>Customer:  ".ociresult($pallet_list, "CUSTOMER")."</b></font></td>
							</tr>
							<tr bgcolor=\"#FFFFFF\">
								<td align=\"center\" colspan=\"7\">&nbsp;</font></td>
							</tr>
							<tr bgcolor=\"#FFFFFF\">
								<td><font size=\"2\" face=\"Verdana\"><b>Customer</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Deck/Hatch</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Pallet ID</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Expected</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Received</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Time Scanned</b></font></td>
							</tr>";
		do {
			$pallets_total++;
			$qty_expected = 0;
			$qty_received = ociresult($pallet_list, "QTY_RECEIVED");
			if(ociresult($pallet_list, "MANIFESTED") == "Y"){
				$qty_expected_total += ociresult($pallet_list, "QTY_RECEIVED"); 
				$qty_expected = ociresult($pallet_list, "QTY_RECEIVED");
			}
			if(ociresult($pallet_list, "DATE_RECEIVED") == "Not Received"){
				$qty_received = 0;
			}else{
				$qty_received_total += ociresult($pallet_list, "QTY_RECEIVED");
				$pallets_scanned++;
			}
			$qty_damaged_total += ociresult($pallet_list, "QTY_DAMAGED");
			if($qty_received > $qty_expected){
				$qty_over = $qty_received - $qty_expected;
				$qty_over_total += $qty_over;
			}
			if($qty_received < $qty_expected){
				$qty_short = $qty_expected - $qty_received;
				$qty_short_total += $qty_short;
			}

			$output .= "<tr>
							<td><font size=\"1\" face=\"Verdana\">".ociresult($pallet_list, "CUSTOMER")."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".ociresult($pallet_list, "HATCH")."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".ociresult($pallet_list, "COMMODITY")."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".ociresult($pallet_list, "PALLET_ID")."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".$qty_expected."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".$qty_received."</font></td>
							<td><font size=\"1\" face=\"Verdana\">".ociresult($pallet_list, "DATE_RECEIVED")."</font></td>
						</tr>";
		} while(ocifetch($pallet_list));

		$output .= "<tr>
						<td><font size=\"2\" face=\"Verdana\">Totals</font></td>
						<td colspan=\"3\">&nbsp;</td>
						<td><font size = \"2\" face=\"Verdana\" color=\"#0066CC\">Expected Total<br />".$qty_expected_total."</font></td>
						<td><font size = \"2\" face=\"Verdana\" color=\"#0066CC\">Received Total<br />".$qty_received_total."</font></td>
						<td>&nbsp;</td>
					</tr>
					</table>";	
	}


	fwrite($handle2, $output);
	echo $output;
	fclose($handle2);

?>
	