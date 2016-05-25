<?
/*
*	Fernando Duarte, Nov. 2014. 
*
*	Vessel Summary page
*************************************************************************/


// Include for database connection file //
	include("oraconn.inc");

// Include for the Test Database Connection file //
//	include("oraconntest.inc");

// Start of the Main Program //

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];

//	if($user != "martym" && $user != "susan" && $user != "portc"){
//		echo "<font color=\"#FF0000\">User not authorized for this page.</font>";
//		exit;
//	}

// If statement for the location for the file opening //
	if($submit != "" && $vessel != ""){
		$filename = $vessel."-".date('mdYhis').".xls";
		$reference_loc = "/clementine/activity_XLS/".$filename;
		$fullpath = "/var/www/secure/".$reference_loc;
		$handle = fopen($fullpath, "w");

		// Main Program for Excel File //
		
		$output = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$output .= "<tr><td colspan=\"20\"><b>* Customer PO's of \"NO PO\" indicate that order(s) were not expedited by DSPC</b></td></tr>";
		$output .= "<tr>
							<td><font size=\"2\" face=\"Verdana\"><b>Vessel</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Hatch/Deck #</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Importer</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Pallet #</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Warehouse Ship/Stored</b></font></td>
		          			<td><font size=\"2\" face=\"Verdana\"><b>Date Received</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Label</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Pack</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Size</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Producer</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>PKG HSE</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Boxes received</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Boxes shipped</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Boxes storage</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Order#</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Customer PO*</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Ship Date</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Customer</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>DMG Cartons</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>HOSP/REGRADE</b></font></td></tr>";
		// step 1:  main SQL with all pallets for DC-clems.
		//NVL(wm_concat(DISTINCT CA.ORDER_NUM), 'NOT SHIPPED') THE_ORD
		$sql = "SELECT VP.VESSEL_NAME, CT.HATCH THE_HATCH, CT.DECK THE_DECK, SUBSTR(CT.PALLET_ID, 18, 6) THE_SUBPALLET, 
					NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NOT RECEIVED') THE_REC, CT.CARGO_SIZE, CT.EXPORTER_CODE, CT.QTY_RECEIVED,
					SUM(CA.QTY_CHANGE) THE_SUM, CT.QTY_IN_HOUSE, MAX(CA.ORDER_NUM) THE_ORD, DECODE(COUNT(DISTINCT CA.ORDER_NUM), 0, '', 1, '', '*') ORD_COUNT_ASTERISK,
					TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_MIN, GREATEST(NVL(QTY_DAMAGED, 0), NVL(CA.BATCH_ID, 0)) THE_DAMAGE,
					DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', ' ') THE_STATUS, CT.DATE_RECEIVED, CP.CUSTOMER_NAME
				FROM CARGO_TRACKING CT, (SELECT * FROM CARGO_ACTIVITY ORDER BY ORDER_NUM) CA, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP		
				WHERE CT.PALLET_ID = CA.PALLET_ID(+)
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID(+)
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM(+)
					AND CA.SERVICE_CODE(+) = '6'
					AND CA.ACTIVITY_DESCRIPTION(+) IS NULL
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.RECEIVER_ID = CP.CUSTOMER_ID
					AND (CT.RECEIVER_ID = ".$eport_customer_id."
						OR
						".$eport_customer_id." = 0
						)
					AND CT.ARRIVAL_NUM = '".$vessel."'
				GROUP BY VP.VESSEL_NAME, CT.HATCH, CT.DECK, SUBSTR(CT.PALLET_ID, 18, 6), NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NOT RECEIVED'), 
					CT.CARGO_SIZE, CT.EXPORTER_CODE, CT.QTY_RECEIVED, CT.QTY_IN_HOUSE, GREATEST(NVL(QTY_DAMAGED, 0), NVL(CA.BATCH_ID, 0)), 
					DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', ' '), CT.DATE_RECEIVED, CP.CUSTOMER_NAME
				ORDER BY CT.HATCH, CT.DECK";
			
//			ORDER BY DATE_RECEIVED NULLS LAST, SUBSTR(CT.PALLET_ID, 14, 10)";
//		echo $sql."<br>";
		$short_term_data = ociparse($rfconn, $sql);
		
		ociexecute($short_term_data);
		
		if(!ocifetch($short_term_data)){
			echo "<font color=\"#FF0000\">Vessel Has no cargo in system yet.</font>";
			exit;
		} else {
			$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
								<tr>
								<td align=\"left\"><font size=\"3\" face=\"Verdana\"><b>Vessel Summary Detail V2</font></td>
								<td><font size=\"3\" face=\"Verdana\"><b><a href=\"".$reference_loc."\">Click Here for the Excel File</a></b></font></td>
								</tr>
							</table><br><br>";
			echo $xls_link;
			do {
				$vessel_name = ociresult($short_term_data, "VESSEL_NAME");
				$importer = ociresult($short_term_data, "CUSTOMER_NAME");
				$pallet = ociresult($short_term_data, "THE_SUBPALLET");
                $hatchdeck = ociresult($short_term_data, "THE_HATCH").ociresult($short_term_data, "THE_DECK");
//				$rec = str_replace(" ", "<br>", ociresult($short_term_data, "THE_REC"));
				$rec = ociresult($short_term_data, "THE_REC");
				$size = ociresult($short_term_data, "CARGO_SIZE");
				$pkg = ociresult($short_term_data, "EXPORTER_CODE");
				$qty_rec = ociresult($short_term_data, "QTY_RECEIVED");
				$qty_IH = ociresult($short_term_data, "QTY_IN_HOUSE");
				$qty_chng = (0 + ociresult($short_term_data, "THE_SUM"));
				$order_display = ociresult($short_term_data, "THE_ORD").ociresult($short_term_data, "ORD_COUNT_ASTERISK");
				$order_subsql = "('".str_replace(",", "','", ociresult($short_term_data, "THE_ORD"))."')";
				$dmg = ociresult($short_term_data, "THE_DAMAGE");
				$status = ociresult($short_term_data, "THE_STATUS");
				
				if($order_display == "NOT SHIPPED"){
					$cust_PO = "";
					$delv_date = "";
					$consignees = "";
				} else {
//								AND DCO.ORDERNUM IN ".$order_subsql;
//								NVL(wm_concat(DCO.CUSTOMERPO), 'NO PO') THE_CUST, wm_concat(TO_CHAR(DCO.DELIVERYDATE, 'MM/DD/YYYY')) THE_DATE, wm_concat(DCC.CONSIGNEENAME) THE_CONS
					$sql = "SELECT NVL(DCO.CUSTOMERPO, 'NO PO') THE_CUST, TO_CHAR(DCO.DELIVERYDATE, 'MM/DD/YYYY') THE_DATE, DCC.CONSIGNEENAME THE_CONS
							FROM (SELECT * FROM DC_ORDER ORDER BY ORDERNUM) DCO, DC_CONSIGNEE DCC
							WHERE DCO.CONSIGNEEID = DCC.CONSIGNEEID
								AND DCO.ORDERNUM = '".ociresult($short_term_data, "THE_ORD")."'";
//					echo $sql."<br>";
					$short_term_data2 = ociparse($rfconn, $sql);
					ociexecute($short_term_data2);
					ocifetch($short_term_data2);
//					$cust_PO = str_replace(",", "<br>", ociresult($short_term_data2, "THE_CUST"));
//					$consignees = str_replace(",", "<br>", ociresult($short_term_data2, "THE_CONS"));
					$cust_PO = ociresult($short_term_data2, "THE_CUST");
					$consignees = ociresult($short_term_data2, "THE_CONS");
					if(ociresult($short_term_data2, "THE_DATE") == ""){
						$delv_date = ociresult($short_term_data, "THE_MIN");
					} else {
//						$delv_date = str_replace(",", "<br>", ociresult($short_term_data2, "THE_DATE"));
						$delv_date = ociresult($short_term_data2, "THE_DATE");
					}
				}
			
				$output .= "<tr>
									<td><font size=\"2\" face=\"Verdana\">".$vessel." - ".$vessel_name."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$hatchdeck."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$importer."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$pallet."</font></td>
									<td><font size=\"2\" face=\"Verdana\">P.O.W.</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$rec."</font></td>
									<td><font size=\"2\" face=\"Verdana\">MAROC</font></td>
									<td><font size=\"2\" face=\"Verdana\">N/A</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$size."</font></td>
									<td><font size=\"2\" face=\"Verdana\">N/A</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$pkg."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$qty_rec."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$qty_chng."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$qty_IH."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$order_display."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$cust_PO."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$delv_date."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$consignees."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$dmg."</font></td>
									<td><font size=\"2\" face=\"Verdana\">&nbsp;".$status."</font></td>
								</tr>";
				
				} while(ocifetch($short_term_data));
				// Connection to SQL for Total Pallet Count //
	
			$sql2 = "select count(distinct CT.PALLET_ID) TOTAL_PLTS from cargo_tracking ct, cargo_activity ca, customer_profile cp, vessel_profile vp
			WHERE CT.PALLET_ID = CA.PALLET_ID(+)
			AND CT.RECEIVER_ID = CA.CUSTOMER_ID(+)
			AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM(+)
			AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
			AND CT.RECEIVER_ID = CP.CUSTOMER_ID(+)
			AND (CT.RECEIVER_ID = ".$eport_customer_id." OR ".$eport_customer_id." = 0)
			AND CA.SERVICE_CODE(+) = '6'
			AND CA.ACTIVITY_DESCRIPTION(+) IS NULL
			AND CT.ARRIVAL_NUM = '".$vessel."'";			
		
		// Parse
		$short_term_data3 = ociparse($rfconn, $sql2);
		// Execute
		ociexecute($short_term_data3);
		// Fetch
		ocifetch($short_term_data3);
		//total pallets display
		
		$totalpallets = ociresult($short_term_data3, "TOTAL_PLTS");
		
		$output .= "<tr><td colspan=\"20\"><b>Total Pallet Count: ".$totalpallets." </b></td></tr>";
		
		$sql3 = "select sum(qty_received) TOTAL_CRTNS from cargo_tracking ct, cargo_activity ca, customer_profile cp, vessel_profile vp
			where ct.arrival_num = to_char(vp.lr_num)
			and ct.receiver_id = cp.customer_id(+)
			and ct.arrival_num = ca.arrival_num(+)
			and ct.pallet_id = ca.pallet_id(+)
			AND (CT.RECEIVER_ID = ".$eport_customer_id." OR ".$eport_customer_id." = 0)
			AND CA.SERVICE_CODE(+) = '6'
			AND CA.ACTIVITY_DESCRIPTION(+) IS NULL
			AND CT.ARRIVAL_NUM = '".$vessel."'";
		
		$short_term_data4 = ociparse($rfconn, $sql3);
		
		ociexecute($short_term_data4);
		
		ocifetch($short_term_data4);
		
		$totalcartons = ociresult($short_term_data4, "TOTAL_CRTNS");
		$output .= "<tr><td colspan=\"20\"><b>Total Carton Count: ".$totalcartons."</b></td></tr>";
		$output .= "</table>";
			
			fwrite($handle, $output);
			echo $output;
			fclose($handle);
			exit;
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">Vessel Summary
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<!-- Selecting a Vessel Screen -->

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="dc_outbound_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT DISTINCT
				   VP.ARRIVAL_NUM THE_LR,
				   LR_NUM || '-' || VESSEL_NAME THE_VESSEL
			FROM VESSEL_PROFILE VP
			INNER JOIN CARGO_TRACKING CT
				  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
			WHERE
				VP.SHIP_PREFIX = 'CLEMENTINES'
			ORDER BY VP.ARRIVAL_NUM";

		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "THE_LR"); ?>"<?
if(ociresult($short_term_data, "THE_LR") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL")
?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
