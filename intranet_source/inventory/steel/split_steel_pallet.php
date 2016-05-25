<?
/*
*
*	Adam Walter, May 2015.
*
*	A screen for inventory to split Steel pallets.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel BoL";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
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
	$Barcode = $HTTP_POST_VARS['barcode'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$main_qty = $HTTP_POST_VARS['main_qty'];
	$qty_A = $HTTP_POST_VARS['qty_A'];
	$qty_B = $HTTP_POST_VARS['qty_B'];
	$qty_C = $HTTP_POST_VARS['qty_C'];


	if($submit == "Commit" || $submit == "Calculate Weights"){
		if(!is_numeric($main_qty) || !is_numeric($qty_A) || !is_numeric($qty_B) || !is_numeric($qty_C)){
			echo "<font color=\"#FF0000\">All entered values must be numeric.</font>";
			$submit = "Retrieve Pallet";
		} elseif($main_qty != round($main_qty) || $qty_A != round($qty_A) || $qty_B != round($qty_B) || $qty_C != round($qty_C)){
			echo "<font color=\"#FF0000\">Cannot save a quantity with a Decimal value.</font>";
			$submit = "Retrieve Pallet";
		} elseif($main_qty < 0 || $qty_A < 0 || $qty_B < 0 || $qty_C < 0){
			echo "<font color=\"#FF0000\">Cannot have negative quantity values.</font>";
			$submit = "Retrieve Pallet";
		} else { 
			$sql = "SELECT QTY_IN_HOUSE
					FROM CARGO_TRACKING CT
					WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$qty_in_house = ociresult($short_term_data, "QTY_IN_HOUSE");
			if($main_qty + $qty_A + $qty_B + $qty_C != $qty_in_house){
				echo "<font color=\"#FF0000\">The sum of the new quantites must equal the current QTY-IH.</font>";
				$submit = "Retrieve Pallet";
			}
		}
	}

	if($submit == "Commit"){
		// checks above, now we save

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NR') THE_REC, QTY_IN_HOUSE, (WEIGHT / QTY_RECEIVED) WT_PER
				FROM CARGO_TRACKING CT
				WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$cust."'
					AND COMMODITY_CODE = '".$comm."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_REC") == "NR"){
			$make_act = false;
		} else {
			$make_act = true;
		}
		
		$wt_per = ociresult($short_term_data, "WT_PER");
		$cur_IH = ociresult($short_term_data, "QTY_IN_HOUSE");
		$IH_diff = $cur_IH - $main_qty;

		$sql = "UPDATE CARGO_TRACKING
				SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$IH_diff.",
					QTY_RECEIVED = QTY_RECEIVED - ".$IH_diff.",
					WEIGHT = (QTY_RECEIVED - ".$IH_diff.") * ".$wt_per."
				WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$cust."'
					AND COMMODITY_CODE = '".$comm."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		// if not received, this will update nothing
		$sql = "UPDATE CARGO_ACTIVITY
				SET QTY_CHANGE = QTY_CHANGE  - ".$IH_diff."
				WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND CUSTOMER_ID = '".$cust."'
					AND ACTIVITY_NUM = '1'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		echo "<font color=\"#0000FF\">".$Barcode." Save Completed.</font><br>";

		if($qty_A != 0){
			$sql = "INSERT INTO CARGO_TRACKING
						(PALLET_ID, 
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						QTY_RECEIVED,
						QTY_IN_HOUSE,
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						WEIGHT,
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						SOURCE_NOTE)
					(SELECT
						'".$Barcode."A',
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						'".$qty_A."',
						'".$qty_A."',
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						'".($qty_A * $wt_per)."',
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						'".$user.": Steel-Split pallet page'
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);

			if($make_act){
				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							CUSTOMER_ID,
							PALLET_ID,
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY)
						(SELECT
							'1',
							SERVICE_CODE,
							'".$qty_A."',
							'4',
							CUSTOMER_ID,
							'".$Barcode."A',
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
							AND ARRIVAL_NUM = '".$vessel."'
							AND CUSTOMER_ID = '".$cust."'
							AND ACTIVITY_NUM = '1')";
				$insert = ociparse($rfconn, $sql);
				ociexecute($insert);
			}

			echo "<font color=\"#0000FF\">".$Barcode."A Save Completed.</font><br>";
		}

		if($qty_B != 0){
			$sql = "INSERT INTO CARGO_TRACKING
						(PALLET_ID, 
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						QTY_RECEIVED,
						QTY_IN_HOUSE,
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						WEIGHT,
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						SOURCE_NOTE)
					(SELECT
						'".$Barcode."B',
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						'".$qty_B."',
						'".$qty_B."',
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						'".($qty_B * $wt_per)."',
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						'".$user.": Steel-Split pallet page'
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);

			if($make_act){
				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							CUSTOMER_ID,
							PALLET_ID,
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY)
						(SELECT
							'1',
							SERVICE_CODE,
							'".$qty_B."',
							'4',
							CUSTOMER_ID,
							'".$Barcode."B',
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
							AND ARRIVAL_NUM = '".$vessel."'
							AND CUSTOMER_ID = '".$cust."'
							AND ACTIVITY_NUM = '1')";
				$insert = ociparse($rfconn, $sql);
				ociexecute($insert);
			}

			echo "<font color=\"#0000FF\">".$Barcode."B Save Completed.</font><br>";
		}

		if($qty_C != 0){
			$sql = "INSERT INTO CARGO_TRACKING
						(PALLET_ID, 
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						QTY_RECEIVED,
						QTY_IN_HOUSE,
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						WEIGHT,
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						SOURCE_NOTE)
					(SELECT
						'".$Barcode."C',
						RECEIVER_ID, 
						ARRIVAL_NUM,
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED,
						'".$qty_C."',
						'".$qty_C."',
						QTY_UNIT,
						WAREHOUSE_LOCATION,
						BATCH_ID,
						RECEIVING_TYPE,
						'".($qty_C * $wt_per)."',
						WEIGHT_UNIT,
						REMARK,
						CONTAINER_ID,
						'".$user.": Steel-Split pallet page'
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust."'
						AND COMMODITY_CODE = '".$comm."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);

			if($make_act){
				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							CUSTOMER_ID,
							PALLET_ID,
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY)
						(SELECT
							'1',
							SERVICE_CODE,
							'".$qty_C."',
							'4',
							CUSTOMER_ID,
							'".$Barcode."C',
							ARRIVAL_NUM,
							DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
							AND ARRIVAL_NUM = '".$vessel."'
							AND CUSTOMER_ID = '".$cust."'
							AND ACTIVITY_NUM = '1')";
				$insert = ociparse($rfconn, $sql);
				ociexecute($insert);
			}

			echo "<font color=\"#0000FF\">".$Barcode."C Save Completed.</font><br>";
		}


	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Steel Split Pallet
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get" action="split_steel_pallet.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL 
				FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('STEEL')
					AND ARRIVAL_NUM IN
						(SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Customer:</b>&nbsp;&nbsp;</td>
		<td><select name="cust"><option value="">Please Select a Customer</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID IN
					(SELECT RECEIVER_ID FROM CARGO_TRACKING WHERE COMMODITY_CODE LIKE '3%')
				ORDER BY CUSTOMER_ID";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"<? if($cust == ociresult($short_term_data, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Commodity:</b>&nbsp;&nbsp;</td>
		<td><select name="comm"><option value="">Please Select a Commodity</option>
<?
		$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
				FROM COMMODITY_PROFILE
				WHERE COMMODITY_TYPE = 'STEEL'
				ORDER BY COMMODITY_CODE";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>"<? if($comm == ociresult($short_term_data, "COMMODITY_CODE")){?> selected <?}?>><? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Barcode:</td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $Barcode; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" width="100%"><input type="submit" name="submit" value="Retrieve Pallet"><hr></td>
	</tr>
</form>
</table>
<?
	if(($submit == "Retrieve Pallet" || $submit == "Calculate Weights") && $Barcode != ""){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT
				WHERE PALLET_ID LIKE '".$Barcode."%'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$cust."'
					AND COMMODITY_CODE = '".$comm."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\">Selected Pallet not found in system.</font>";
			exit;
		} elseif(ociresult($short_term_data, "THE_COUNT") > 1){
			echo "<font color=\"#FF0000\">Pallet Already Split.  Please Delete the pallet and start over if you need a different set of Splits.</font>";
			exit;
		}

		$sql = "SELECT CT.*, (WEIGHT / QTY_RECEIVED) WT_PER, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC
				FROM CARGO_TRACKING CT
				WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$cust."'
					AND COMMODITY_CODE = '".$comm."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			echo "<font color=\"#FF0000\">Selected Pallet not found in system.</font>";
			exit;
		} elseif(ociresult($short_term_data, "QTY_IN_HOUSE") <= 0){
			echo "<font color=\"#FF0000\">No QTY left In House.  Cannot Split</font>";
			exit;
		}

?>
<form name="update" action="split_steel_pallet.php" method="post">
<input type="hidden" name="barcode" value="<? echo $Barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Original QTY:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "QTY_RECEIVED"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Original Weight:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WT_PER") * ociresult($short_term_data, "QTY_RECEIVED"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Date Received:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_REC"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>In-House QTY:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "QTY_IN_HOUSE"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Weight/PC:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WT_PER")." ".ociresult($short_term_data, "WEIGHT_UNIT"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Total IH Weight:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WT_PER") * ociresult($short_term_data, "QTY_IN_HOUSE"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Code:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WAREHOUSE_LOCATION"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Mark:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CARGO_DESCRIPTION"); ?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>PO#:</b></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "REMARK"); ?></td>
	</tr>
</table>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>Post-Split Weight</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Barcode; ?></font></td>
		<td><input type="text" name="main_qty" size="3" maxlength="3" value="<? if($main_qty != ""){ echo $main_qty; } else { echo ociresult($short_term_data, "QTY_IN_HOUSE"); } ?>"></td>
		<td><font size="2" face="Verdana"><? echo (min($main_qty, ociresult($short_term_data, "QTY_IN_HOUSE")) * ociresult($short_term_data, "WT_PER")); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Barcode; ?>A</font></td>
		<td><input type="text" name="qty_A" size="3" maxlength="3" value="<? echo (0 + $qty_A); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ((0 + $qty_A) * ociresult($short_term_data, "WT_PER")); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Barcode; ?>B</font></td>
		<td><input type="text" name="qty_B" size="3" maxlength="3" value="<? echo (0 + $qty_B); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ((0 + $qty_B) * ociresult($short_term_data, "WT_PER")); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Barcode; ?>C</font></td>
		<td><input type="text" name="qty_C" size="3" maxlength="3" value="<? echo (0 + $qty_C); ?>"></td>
		<td><font size="2" face="Verdana"><? echo ((0 + $qty_C) * ociresult($short_term_data, "WT_PER")); ?></font></td>
	</tr>
	<tr>
		<td colspan="3">
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
				<tr>
					<td align="left"><input type="submit" name="submit" value="Calculate Weights"></td>
					<td align="right"><input type="submit" name="submit" value="Commit"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?
	}
	include("pow_footer.php");