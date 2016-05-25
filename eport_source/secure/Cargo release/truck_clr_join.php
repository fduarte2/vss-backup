<?
/*
*		Adam Walter, July 2014.
*
*		Screen to join trucks and their cargo
*
*		
*********************************************************************************/


	$pagename = "truck_clr_join";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$truck_ID = $HTTP_GET_VARS['truck_ID'];
	if($truck_ID == ""){
		$truck_ID = $HTTP_POST_VARS['truck_ID'];
	}
	$clr_ID = $HTTP_GET_VARS['clr_ID'];
	if($clr_ID == ""){
		$clr_ID = $HTTP_POST_VARS['clr_ID'];
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Assign Link"){
		if($clr_ID == "" || $truck_ID == ""){
			echo "<font color=\"#FF0000\">Both entries must have a selection.  Cancelling Save.</font><br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CLR_TRUCK_MAIN_JOIN
					WHERE MAIN_CLR_KEY = '".$clr_ID."'
						AND TRUCK_PORT_ID = '".$truck_ID."'";
			$count_data = ociparse($rfconn, $sql);
			ociexecute($count_data);
			ocifetch($count_data);
			if(ociresult($count_data, "THE_COUNT") >= 1){
				echo "<font color=\"#FF0000\">The selected combination has already been joined.</font><br>";
			} else {
				$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
							(MAIN_CLR_KEY,
							TRUCK_PORT_ID)
						VALUES
							('".$clr_ID."',
							'".$truck_ID."')";
				$insert = ociparse($rfconn, $sql);
				ociexecute($insert);

				echo "<font color=\"#0000FF\">Combination Joined.</font><br>";
			}
		}
	}
	if($submit == "Remove Link"){
		$remove_clr = $HTTP_POST_VARS['remove_clr'];
		$remove_truck = $HTTP_POST_VARS['remove_truck'];

		$sql = "DELETE FROM CLR_TRUCK_MAIN_JOIN
				WHERE TRUCK_PORT_ID = '".$remove_truck."'
					AND MAIN_CLR_KEY = '".$remove_clr."'";
//		echo $sql."<br>";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);

		echo "<font color=\"#0000FF\">Combination Removed.</font><br>";
	}

	$form_counter = 0;

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CLR Assign Cargo
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="ID_select" action="truck_clr_join_index.php" method="post">
	<tr>
		<td>&nbsp;</td>
		<td align="left"><font size="3" face="Verdana" color="#FFFFFF">Cargo ID#:  </font><font size="2" face="Verdana">(Container/Bol)</font></td> 
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Truck ID#:  <select name="truck_ID">
<?
	$sql = "SELECT PORT_ID, NVL(DRIVER_NAME, 'No Name Yet') THE_NAME 
			FROM CLR_TRUCK_LOAD_RELEASE 
			WHERE (GATEPASS_MADE_ON IS NULL OR PORT_ID = '".$truck_ID."')
			ORDER BY PORT_ID DESC";
	$truck_data = ociparse($rfconn, $sql);
	ociexecute($truck_data);
	while(ocifetch($truck_data)){
?>
						<option value="<? echo ociresult($truck_data, "PORT_ID"); ?>"<? if($truck_ID == ociresult($truck_data, "PORT_ID")){?> selected <?}?>>
								<? echo ociresult($truck_data, "PORT_ID")." - ".ociresult($truck_data, "THE_NAME"); ?></option>
<?
	}
?>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left"><font size="3" face="Verdana">Cargo ID#:  <select name="clr_ID">
<?
	$sql = "SELECT CLR_KEY, NVL(CONTAINER_NUM, 'No Container') THE_CONT, NVL(BOL_EQUIV, 'No BoL') THE_BOL, ARRIVAL_NUM 
			FROM CLR_MAIN_DATA";
	if($truck_ID != ""){
		$sql .= " WHERE ARRIVAL_NUM = (SELECT ARRIVAL_NUM FROM CLR_TRUCK_LOAD_RELEASE WHERE PORT_ID = '".$truck_ID."')";
	}
	$sql .= " ORDER BY CLR_KEY DESC";
	$clr_data = ociparse($rfconn, $sql);
	ociexecute($clr_data);
	while(ocifetch($clr_data)){
?>
						<option value="<? echo ociresult($clr_data, "CLR_KEY"); ?>"<? if($clr_ID == ociresult($clr_data, "CLR_KEY")){?> selected <?}?>>
								<? echo ociresult($clr_data, "CLR_KEY")." - ".ociresult($clr_data, "ARRIVAL_NUM")." / ".ociresult($clr_data, "THE_CONT")." / ".ociresult($clr_data, "THE_BOL"); ?></option>
<?
	}
?>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
<?
	if(strpos($security_allowance, "M") !== false){
?>
		<td><input type="submit" name="submit" value="Assign Link"></td>
<?
	} else {
?>
		<td>&nbsp;</td>
<?
	}
?>
		<td><input type="submit" name="submit" value="Review Detailed Info"></td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3"><hr></td>
	</tr>
	<tr>
		<td width="48%"><font size="3" face="Verdana"><b>Trucker Data:</b><br>
<?
	if($truck_ID != ""){
?>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><b>Driver Info</b></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Driver Name</b></td>
					<td><font size="2" face="Verdana"><b>Driver License Number</b></td>
					<td><font size="2" face="Verdana"><b>Trucking Company</b></td>
				</tr>
<?
		$sql = "SELECT * FROM CLR_TRUCK_LOAD_RELEASE
				WHERE PORT_ID = '".$truck_ID."'";
		$truck_data = ociparse($rfconn, $sql);
		ociexecute($truck_data);
		ocifetch($truck_data);
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "DRIVER_NAME"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "DRIVER_LIC_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "TRUCKING_COMPANY"); ?></td>
				</tr>
			</table>
				<br><br>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="5" align="center"><b>Cargo Assigned To Truck# <? echo $truck_ID; ?></b></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Cargo ID#</b></td>
					<td><font size="2" face="Verdana"><b>Arrival#</b></td>
					<td><font size="2" face="Verdana"><b>Container #</b></td>
					<td><font size="2" face="Verdana"><b>BoL</b></td>
					<td><font size="2" face="Verdana">&nbsp;</td>
				</tr>
<?
		$sql = "SELECT * FROM CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
				WHERE CTMJ.TRUCK_PORT_ID = '".$truck_ID."'
					AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY
				ORDER BY CLR_KEY";
		$clr_data = ociparse($rfconn, $sql);
		ociexecute($clr_data);
		if(!ocifetch($clr_data)){
?>
				<tr>
					<td colspan="5" align="center"><font size="2" face="Verdana"><b>No Curent Assigned Cargo.</b></td>
				</tr>
<?
		} else {
			do {
				$form_counter++;
?>
				<form name="remove_truck<? echo $form_counter; ?>" action="truck_clr_join_index.php" method="post">
				<input type="hidden" name="remove_clr" value="<? echo ociresult($clr_data, "CLR_KEY"); ?>">
				<input type="hidden" name="remove_truck" value="<? echo $truck_ID; ?>">
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "CLR_KEY"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "ARRIVAL_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "CONTAINER_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "BOL_EQUIV"); ?></td>
<?
				if(strpos($security_allowance, "M") !== false){
?>
					<td><input name="submit" type="submit" value="Remove Link"></td>
<? 
				} else {
?>
					<td>&nbsp;</td>
<?
				}
?>
				</tr>
				</form>
<?
			} while(ocifetch($clr_data));
		}
?>
			</table>
<?
	} else {
?>
			&nbsp;
<?
	}
?>
		</td>
		<td width="4%">&nbsp;</td>
		<td width="48%"><font size="3" face="Verdana"><b>Cargo Data:</b><br>
<?
	if($clr_ID != ""){
?>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><b>Cargo Info</b></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Arrival#</b></td>
					<td><font size="2" face="Verdana"><b>Container #</b></td>
					<td><font size="2" face="Verdana"><b>BoL</b></td>
				</tr>
<?
		$sql = "SELECT * FROM CLR_MAIN_DATA
				WHERE CLR_KEY = '".$clr_ID."'";
		$clr_data = ociparse($rfconn, $sql);
		ociexecute($clr_data);
		ocifetch($clr_data);
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "ARRIVAL_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "CONTAINER_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($clr_data, "BOL_EQUIV"); ?></td>
				</tr>
			</table>
				<br><br>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="5" align="center"><b>Trucks Assigned to Cargo ID# <? echo $clr_ID; ?></b></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Truck ID#</b></td>
					<td><font size="2" face="Verdana"><b>Driver Name</b></td>
					<td><font size="2" face="Verdana"><b>Driver License Number</b></td>
					<td><font size="2" face="Verdana"><b>Trucking Company</b></td>
					<td><font size="2" face="Verdana">&nbsp;</td>
				</tr>
<?
		$sql = "SELECT * FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_TRUCK_MAIN_JOIN CTMJ
				WHERE CTMJ.MAIN_CLR_KEY = '".$clr_ID."'
					AND CTMJ.TRUCK_PORT_ID = CTLR.PORT_ID
				ORDER BY PORT_ID";
		$truck_data = ociparse($rfconn, $sql);
		ociexecute($truck_data);
		if(!ocifetch($truck_data)){
?>
				<tr>
					<td colspan="5" align="center"><font size="2" face="Verdana"><b>No Curent Attached Trucks.</b></td>
				</tr>
<?
		} else {
			do {
?>
				<form name="remove_truck<? echo $form_counter; ?>" action="truck_clr_join_index.php" method="post">
				<input type="hidden" name="remove_clr" value="<? echo $clr_ID; ?>">
				<input type="hidden" name="remove_truck" value="<? echo ociresult($truck_data, "PORT_ID"); ?>">
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "PORT_ID"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "DRIVER_NAME"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "DRIVER_LIC_NUM"); ?></td>
					<td><font size="2" face="Verdana"><? echo ociresult($truck_data, "TRUCKING_COMPANY"); ?></td>
<?
				if(strpos($security_allowance, "M") !== false){
?>
					<td><input name="submit" type="submit" value="Remove Link"></td>
<? 
				} else {
?>
					<td>&nbsp;</td>
<?
				}
?>
				</tr>
				</form>
<?
			} while(ocifetch($truck_data));
		}
?>
			</table>
<?
	} else {
?>
			&nbsp;
<?
	}
?>
		</td>
	</tr>	
	<tr>
		<td colspan="3"><hr><br><br><br><br></td>
	</tr>
</table>


<?
	$cust_sql = CustV2UserCheck($user, "CA.CUSTOMER_ID", $rfconn);
	$sql = "SELECT CT.PALLET_ID, VP.VESSEL_NAME, CT.ARRIVAL_NUM, CP.CUSTOMER_NAME, CT.BOL, NVL(CONTAINER_ID, 'NC') THE_CONT, CA.ORDER_NUM
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP, VESSEL_PROFILE VP, CLR_TRUCK_LOAD_RELEASE CTLR
			WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.RECEIVER_ID = CP.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
				".$cust_sql."
				AND CA.SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND CA.ORDER_NUM = TRIM(CTLR.CLEM_ORDER_NUM)
				AND CTLR.PORT_ID = '".$truck_ID."'
			ORDER BY CA.ORDER_NUM, CP.CUSTOMER_ID, CT.ARRIVAL_NUM, BOL, CONTAINER_ID, CT.PALLET_ID";
//	echo $sql;
	$order_data = ociparse($rfconn, $sql);
	ociexecute($order_data);
	if(!ocifetch($order_data)){
		// do noting
	} else {
?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td align="left" colspan="6"><font size="3" face="Verdana"><b>Distinct Vessel/Container/BoL(s) found on Truck:
<?
		$sql = "SELECT DISTINCT CT.BOL, NVL(CONTAINER_ID, 'NC') THE_CONT, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CLR_TRUCK_LOAD_RELEASE CTLR
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					".$cust_sql."
					AND CA.SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = TRIM(CTLR.CLEM_ORDER_NUM)
					AND CTLR.PORT_ID = '".$truck_ID."'
				ORDER BY CT.ARRIVAL_NUM, BOL, NVL(CONTAINER_ID, 'NC')";
		$bol_data = ociparse($rfconn, $sql);
		ociexecute($bol_data);
		$counter = -1;
		while(ocifetch($bol_data)){
			$counter++;
			$sql = "SELECT CLR_KEY
					FROM CLR_MAIN_DATA
					WHERE BOL_EQUIV = '".ociresult($bol_data, "BOL")."'
						AND ARRIVAL_NUM = '".ociresult($bol_data, "ARRIVAL_NUM")."'
						AND CONTAINER_NUM = '".ociresult($bol_data, "THE_CONT")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$CLR_pass_value = ociresult($short_term_data, "CLR_KEY");

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CLR_TRUCK_MAIN_JOIN
					WHERE TRUCK_PORT_ID = '".$truck_ID."'
						AND MAIN_CLR_KEY = '".$CLR_pass_value."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0 && $CLR_pass_value != ""){
				$show_button = true;
			} else {
				$show_button = false;
			}

			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP, VESSEL_PROFILE VP, CLR_TRUCK_LOAD_RELEASE CTLR
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.RECEIVER_ID = CP.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
						".$cust_sql."
						AND CA.SERVICE_CODE = '6'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND CA.ORDER_NUM = TRIM(CTLR.CLEM_ORDER_NUM)
						AND CTLR.PORT_ID = '".$truck_ID."'";
		//	echo $sql;
			$total_data = ociparse($rfconn, $sql);
			ociexecute($total_data);
			ocifetch($total_data);


?>
					<form name="inlineupdate<? echo $counter; ?>" action="truck_clr_join_index.php" method="post">
					<input type="hidden" name="truck_ID" value="<? echo $truck_ID; ?>">
					<input type="hidden" name="clr_ID" value="<? echo $CLR_pass_value; ?>">
					<? echo ociresult($bol_data, "ARRIVAL_NUM")." / ".ociresult($bol_data, "THE_CONT")." / ".ociresult($bol_data, "BOL")."    Pallets: ".ociresult($total_data, "THE_PLTS")."    Cases: ".ociresult($total_data, "THE_CTNS"); ?>&nbsp;&nbsp;&nbsp;
					<? if($show_button){?><input type="submit" name="submit" value="Assign Link"><?}?>
					</form>
<?
		}

?>
		</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel#</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet</b></font></td>
	</tr>
<?
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "CUSTOMER_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "ARRIVAL_NUM")."-".ociresult($order_data, "VESSEL_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "BOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "THE_CONT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_data, "PALLET_ID"); ?></font></td>
	</tr>
<?
		} while(ocifetch($order_data));
?>
</table>
<?
	}
?>
