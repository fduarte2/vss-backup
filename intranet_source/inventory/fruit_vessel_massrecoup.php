<?
/*
*
*	Adam Walter, Apr 2014.
*
*	A screen for inventory to mass-recoup a vessel.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel modification";
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

	$lr_num = $HTTP_POST_VARS['lr_num'];
	if($lr_num == ""){
		$lr_num = $HTTP_POST_VARS['lr_num_truck'];
	}
	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $lr_num == ""){
		echo "<font color=\"#FF0000\">Please select a Vessel.</font><br>";
		$submit = "";
	}

	if($submit == "Update"){
		$edit_pallet = $HTTP_POST_VARS['edit_pallet'];
		$cust_list = $HTTP_POST_VARS['cust_list'];
		$counter = $HTTP_POST_VARS['counter'];
		$pallets_updated = 0;

		for($i = 0; $i <= $counter; $i++){
			if($edit_pallet[$i] != ""){
				if(IsInHouse($edit_pallet[$i], $cust_list[$i], $lr_num, $rfconn)){
					$next_num = GetMaxAct($edit_pallet[$i], $cust_list[$i], $lr_num, $rfconn) + 1;

					$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, 
								SERVICE_CODE, 
								QTY_CHANGE,
								ACTIVITY_ID, 
								CUSTOMER_ID, 
								DATE_OF_ACTIVITY, 
								PALLET_ID, 
								ARRIVAL_NUM,
								QTY_LEFT) 
							(SELECT
								'".$next_num."', 
								'9', 
								(-1 * QTY_IN_HOUSE), 
								'0', 
								RECEIVER_ID, 
								SYSDATE, 
								PALLET_ID, 
								ARRIVAL_NUM,
								0
							FROM CARGO_TRACKING
							WHERE PALLET_ID = '".$edit_pallet[$i]."'
								AND RECEIVER_ID = '".$cust_list[$i]."'
								AND ARRIVAL_NUM = '".$lr_num."'
							)";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$sql = "UPDATE CARGO_TRACKING
							SET QTY_IN_HOUSE = 0
							WHERE PALLET_ID = '".$edit_pallet[$i]."'
								AND RECEIVER_ID = '".$cust_list[$i]."'
								AND ARRIVAL_NUM = '".$lr_num."'";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);

					$pallets_updated++;
				}
			}
		}

		echo "<font color=\"0000FF\">".$pallets_updated." Pallets have been Recouped to 0.</font>";
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Fruit Mass-Recoup Screen
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="fruit_vessel_massrecoup.php" method="post">
	<tr>
		<td align="left">Ship:&nbsp;&nbsp;&nbsp;<select name="lr_num"><option value="">Use Truck Value Instead</option>
<?
//Changed QTY_IN_HOUSE from <5 to <11 per Help Desk 10369 - LFS 12/23/2015. Changed in 3 places.
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE SHIP_PREFIX IN ('ARG FRUIT', 'CHILEAN', 'CLEMENTINES')
				AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING 
															WHERE QTY_IN_HOUSE > 0
																AND DATE_RECEIVED IS NOT NULL
																AND QTY_IN_HOUSE < 11
																AND QTY_IN_HOUSE != QTY_RECEIVED
																AND RECEIVER_ID != '9722')
			ORDER BY LR_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if($lr_num == ociresult($stid, "LR_NUM")){?> selected <?}?>><? echo ociresult($stid, "LR_NUM")."-".ociresult($stid, "VESSEL_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td align="left">Truck:&nbsp;&nbsp;&nbsp;<select name="lr_num_truck"><option value="">Use Ship Value Instead</option>
<?

	$sql = "SELECT DISTINCT ARRIVAL_NUM
			FROM CARGO_TRACKING
			WHERE QTY_IN_HOUSE > 0
				AND DATE_RECEIVED IS NOT NULL
				AND QTY_IN_HOUSE < 11
				AND QTY_IN_HOUSE != QTY_RECEIVED
				AND RECEIVER_ID != '9722'
				AND COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IN ('ARG FRUIT', 'CHILEAN', 'CLEMENTINES', 'PERUVIAN', 'BRAZILIAN'))
				AND RECEIVING_TYPE = 'T'
			ORDER BY ARRIVAL_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>"<? if($lr_num == ociresult($stid, "ARRIVAL_NUM")){?> selected <?}?>><? echo ociresult($stid, "ARRIVAL_NUM"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td>Edit All?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pre_check" value="yes" checked>&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pre_check" value="no">&nbsp;No</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Select"><br><hr><br></td>
	</tr>
</form>
<?
	if($submit == "Select"){
		// the next sections only display if "Select" is pressed.
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="update" action="fruit_vessel_massrecoup.php" method="post">
<input type="hidden" name="lr_num" value="<? echo $lr_num; ?>">
	<tr>
		<td align="center" colspan="6"><font size="3" face="Verdana"><b>Pending Recoups for ARRIVAL # <? echo $lr_num; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Receiver</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Received</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Recoup?</b></font></td>
	</tr>
<?
		$total_plt = 0;
		$total_cs = 0;
		$counter = 0;

		$sql = "SELECT PALLET_ID, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC, QTY_IN_HOUSE, QTY_RECEIVED, RECEIVER_ID
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$lr_num."'
					AND QTY_IN_HOUSE > 0
					AND QTY_IN_HOUSE < 11
					AND DATE_RECEIVED IS NOT NULL
					AND QTY_IN_HOUSE != QTY_RECEIVED
					AND RECEIVER_ID != '9722'
				ORDER BY PALLET_ID";
		$pallet_list = ociparse($rfconn, $sql);
		ociexecute($pallet_list);
		while(ocifetch($pallet_list)){
?>
	<input type="hidden" name="cust_list[<? echo $counter; ?>]" value="<? echo ociresult($pallet_list, "RECEIVER_ID"); ?>">
	<tr>
		<td><? echo ociresult($pallet_list, "PALLET_ID"); ?></td>
		<td><? echo ociresult($pallet_list, "RECEIVER_ID"); ?></td>
		<td><? echo ociresult($pallet_list, "DATE_REC"); ?></td>
		<td><? echo ociresult($pallet_list, "QTY_RECEIVED"); ?></td>
		<td><? echo ociresult($pallet_list, "QTY_IN_HOUSE"); ?></td>
		<td><input type="checkbox" name="edit_pallet[<? echo $counter; ?>]" 
					value="<? echo ociresult($pallet_list, "PALLET_ID"); ?>" <? if($pre_check == "yes"){?> checked <?}?>></td>
	</tr>
<?
			$total_cs += ociresult($pallet_list, "QTY_IN_HOUSE");
			$total_plt++;
			$counter++;
		}
?>
	<tr>
		<td colspan="3" align="right"><b>TOTALS:</b></td>
		<td><b>Pallets: <? echo $total_plt; ?></b></td>
		<td><b>Cartons: <? echo $total_cs; ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="6"><input type="submit" name="submit" value="Update"></td>
	</tr>
<input type="hidden" name="counter" value="<? echo $counter; ?>">
</form>
</table>
<?
	}
	include("pow_footer.php");















function IsInHouse($pallet, $cust, $lr_num, $rfconn) {
	$sql = "SELECT NVL(QTY_IN_HOUSE, 0) THE_IH
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$pallet."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$lr_num."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
//changed from 5 to 11 LFS 
	if(ociresult($stid, "THE_IH") > 0 && ociresult($stid, "THE_IH") < 11){
		return true;
	}

	return false;
}


function GetMaxAct($pallet, $cust, $lr_num, $rfconn){
	$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX
			FROM CARGO_ACTIVITY
			WHERE CUSTOMER_ID = '".$cust."'
				AND PALLET_ID = '".$pallet."'
				AND ARRIVAL_NUM = '".$lr_num."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	return ociresult($stid, "THE_MAX");
}



?>