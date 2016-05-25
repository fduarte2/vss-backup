<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
	printf("Access Denied from INVE system");
	include("pow_footer.php");
	exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
			<p align="left">
				<font size="5" face="Verdana" color="#0066CC">
					Inventory
				</font>
				<hr>
			</p>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td width="1%" rowspan="150">&nbsp;</td>
		<td colspan="2" valign="top">
			<font size="2" face="Verdana"><b>From here you can access the various functions needed for inventory management.</b></font>
		</td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font face="Verdana" size="2"><a href="../TS_Testing/index_invetest.php"><b>Client Testing</b></a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="rf_order_tobill_v2.php">Review Transfers Orders For Finance</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/TS_Testing/truck_notify_oppy.php">OPPY Picklist - Temporary</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/TS_Testing/oppy_request_confirm.php">OPPY Request Confirmation - Temporary</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/TS_Testing/oppy_order_print.php">OPPY BOL Print - Temporary</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/TS_Testing/oppy_manual_override.php">OPPY BOL Manual Override - Temporary</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/finance/vessel_arrival_notify.php">Barge Arrival Notification</a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="delete_DT.php">Dole DT Unreceived Roll (EDI) Deletion</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="dole_DT_in_out.php">Dole DT Inbound/Outbound Report</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a target="DoleDTNonReceive.php" href="DoleDTNonReceive.php">Dole DT non-received Dock Tickets</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a target="Dolerailcarlookup.php" href="Dolerailcarlookup.php">Dole DT Rail Car Lookup</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="DoleEDImanualupload/DoleDTupload.php">Dole DT Barcode Manual-Upload</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="dole_book_in.php">Dole Booking Inbound Report</a></font></td>
	</tr>
		<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="dole_book_out.php">Dole Booking Outbound Report</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="abitibi_in_out.php">Abitibi Inbound/Outbound Report</a></font></td>
	</tr>  !--> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="barcode_prefixes.php">Barcode Prefix Adjustment</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="rapidcool_report.php">Rapid Cool Report</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="CTCA_audit_check.php" target="CTCA_audit_check.php">Full Historical Audit (ANY RF Cargo)</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="rf_pallet_edit.php">Update RF Juice Pallet Information</a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="Argen_Trans.php">Juice BNI-to-Scanner Manual Transfer</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="Argen_Juice_XFER_list.php">Pending Juice BNI-to-Scanner Transfer Report</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="/director/data_warehouse/reband_report.php">Reband Report (Argen Juice)</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="Dummy_num_check.php">Dummy Number Lookup</a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="ArgenJuice/powBCcorrection.php">Argen Juice POW relabel upload</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./Clem/index_clem.php"><b>Clementine Pages</b></a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="transfer_to_CS.php">Cold Storage Transfer Report</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a target="CargoInHouse.php" href="CargoInHouse.php">Chilean Cargo In House (by Vessel)</a></font></td>
	</tr> 
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="chilean_recon.php">Chilean Reconciliation</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="sort_a_WIP.php">Chilean WIP-sort</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="inhouse_RF_by_vessel.php">Scanned In-House Inventory By Vessel</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="argen_recon.php">Argen Juice Reconciliation</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="release_cargo/release_cargo.php">Kiwi - Release by HatchDeck</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="BarCodeCorrection.php">General-Purpose Barcode Correction Screen</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="swingload_alter.php">Swingload Changes</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="ArgenFruit/argen_fruit_picklist.php">Argen Fruit Picklist Screen</a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="railcarlookup.php">Abitibi Rail Car Lookup</a></font></td>
	</tr>!-->
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana">Booking # Set:&nbsp;&nbsp;<font size="2" face="Verdana"><a	href="AbiBooking.php">Abitibi</a></font>&nbsp;&nbsp;<font size="2" 
		face="Verdana"></font></td>	  </tr> !-->
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a target="abi_pallet_popup.php" href="abi_pallet_popup.php">Abitibi Pallet Popup</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./BookingPages/index.php"><b>Booking Paper Applications</b></a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./doleDTPages/index_dole.php"><b>Dockticket Paper Applications</b></a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./AbitibiPages/index_abitibi.php"><b>Abitibi Paper Applications</b></a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./steel/index_steel.php"><b>SSAB Steel Applications</b></a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="DoleEDILookup.php">Dole EDI Lookup</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="berth_util.php">Berth Utilization Report</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="NONTWICentry.php">NONTWIC Barcode Info</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="temp_monitor_box_monitor/">Temperature Monitor Count Display</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./walmart_pages/index_WM.php"><b>WALMART Applications</b></a></font></td>
	</tr>
<!--	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="walmart_pages/WM_split_alter_or_recoup.php">Walmart Split Pallets - Adjust QTY-Received / Recoup</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="walmart_pages/WM_order_auto.php">Walmart Projected Shipments File Upload</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="walmart_pages/walmart_proj.php">Walmart Projected Shipments Manual Entry/Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="walmart_pages/walmart_proj_item_lookup.php">(Lookup By Item)</a></font></td>
	</tr> 
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="walmart_lucca_order_entry.php">Add Lucca Order#</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="WM_lucca_tally.php">Wal-Mart Lucca Tally Printout</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="WM_repack_expected.php">Walmart Repack Order Screen</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="walmart_pages/WM_FIFO_override.php">WALMART FIFO-Override Entry</a></font></td>
	</tr> !-->
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="sort_a_WIP.php">WIP-Sorting Application</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="inve_comm_change.php">Fruit Commodity Change</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="add_or_update.php">Add or Update Location</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./fruit/reporting/tag_audit/tag_audit.php">Tag Audit</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="./fruit_vessel_massrecoup.php">Vessel Recoup</a></font></td>
	</tr>
<?
	if ($user == "martym" || $user == "awalter" || $user == "ltreut" || $user == "lstewart") {
?>
  <tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="import_vessel_removal.php">Remove Imported Vessel</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="../claims/fruit_claim/OSD.php">Claim's OS&D Report</a></font></td>
	</tr>
	<tr>
		<td valign="middle" width="1%"><img src="images/yellowbulletsmall.gif"></td>
		<td><font size="2" face="Verdana"><a href="../claims/fruit_claim/missing_pallet.php">Claim's Missing Pallet Report</a></font></td>
	</tr>
<?
	}
?>
			  
	 
</table>

<? include("pow_footer.php"); ?>
