<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to modify steel DOs RF.
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

	$DO_num = $HTTP_POST_VARS['DO_num'];
	$pre_check = $HTTP_POST_VARS['pre_check'];
	$submit = $HTTP_POST_VARS['submit'];

	$new_DO_1 = $HTTP_POST_VARS['new_DO_1'];
	$new_DO_2 = $HTTP_POST_VARS['new_DO_2'];
	if($new_DO_1 != "" || $new_DO_2 != ""){
		if($new_DO_1 != "" && $new_DO_2 != "" && $new_DO_1 != $new_DO_2){
			echo "<font color=\"#FF0000\">Please don't put different new DO#s in both boxes :)</font>";
			$submit = "Select";
		} else {
			$submit = "Update";
			if($new_DO_1 != ""){
				$new_DO = $new_DO_1;
			} else {
				$new_DO = $new_DO_2;
			}
		}
	}

	if($submit == "Update"){
		$edit_pallet_DO = $HTTP_POST_VARS['edit_pallet_DO'];
		$counter = $HTTP_POST_VARS['counter'];
		$vessel = $HTTP_POST_VARS['vessel'];
		$cust = $HTTP_POST_VARS['cust'];
		$pallets_updated = 0;

		for($i = 0; $i <= $counter; $i++){
			if($edit_pallet_DO[$i] != ""){
				$sql = "UPDATE CARGO_TRACKING
						SET REMARK = '".$new_DO."'
						WHERE PALLET_ID = '".$edit_pallet_DO[$i]."'
							AND ARRIVAL_NUM = '".$vessel."'
							AND RECEIVER_ID = '".$cust."'
							AND REMARK = '".$DO_num."'";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				$pallets_updated++;
			}
		}

		echo "<font color=\"0000FF\">".$pallets_updated." Pallets Updated to DO# ".$new_DO.".</font>";
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Change DO Assignments - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="steel_DO_change.php" method="post">
	<tr>
		<td align="left"><select name="DO_num"><option value="">Select a DO:</option>
<?

	$sql = "SELECT DISTINCT REMARK
			FROM CARGO_TRACKING
			WHERE COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'STEEL')
				AND QTY_IN_HOUSE > 0
				AND REMARK IS NOT NULL
				AND REMARK != 'NO DO'
			ORDER BY REMARK DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "REMARK"); ?>"<? if($DO_num == ociresult($stid, "REMARK")){?> selected <?}?>><? echo ociresult($stid, "REMARK"); ?></option>
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
		$total_pcs = 0;
		$total_weight = 0;
		// the next sections only display if "Select" is pressed.

		$sql = "SELECT COMMODITY_NAME, CUSTOMER_NAME, LR_NUM, VESSEL_NAME, CT.RECEIVER_ID, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.REMARK = '".$DO_num."'
					AND COMP.COMMODITY_TYPE = 'STEEL'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%">Customer:</td>
		<td><? echo ociresult($stid, "CUSTOMER_NAME"); ?></td>
	</tr>
	<tr>
		<td>Vessel:</td>
		<td><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></td>
	</tr>
	<tr>
		<td>Commodity:</td>
		<td><? echo ociresult($stid, "COMMODITY_NAME"); ?><br></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="update_DO" action="steel_DO_change.php" method="post">
<input type="hidden" name="vessel" value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>">
<input type="hidden" name="cust" value="<? echo ociresult($stid, "RECEIVER_ID"); ?>">
<input type="hidden" name="DO_num" value="<? echo $DO_num; ?>">
	<tr>
		<td align="center" colspan="6">New DO#&nbsp;&nbsp;&nbsp;<input name="new_DO_1" size="10" maxlength="10" type="text">
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="save DO"></td>
	</tr>
	<tr>
		<td>Plate/Coil#</td>
		<td>Mark</td>
		<td>Code</td>
		<td>Pcs (In-House)</td>
		<td>Weight</td>
		<td>Edit?</td>
	</tr>
<?
		$counter = 0;
		$sql = "SELECT PALLET_ID, CARGO_DESCRIPTION, QTY_IN_HOUSE, WEIGHT, QTY_RECEIVED, WAREHOUSE_LOCATION
				FROM CARGO_TRACKING
				WHERE REMARK = '".$DO_num."'
					AND QTY_IN_HOUSE > 0
					AND RECEIVER_ID = '".ociresult($stid, "RECEIVER_ID")."'
					AND ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'
				ORDER BY PALLET_ID";
		$pallet_list = ociparse($rfconn, $sql);
		ociexecute($pallet_list);
		while(ocifetch($pallet_list)){
			if(ociresult($pallet_list, "QTY_RECEIVED") == 0){
				$ratio = 0;
			} else {
				$ratio = ociresult($pallet_list, "QTY_IN_HOUSE") / ociresult($pallet_list, "QTY_RECEIVED");
			}
?>
	<tr>
		<td><? echo ociresult($pallet_list, "PALLET_ID"); ?></td>
		<td><? echo ociresult($pallet_list, "CARGO_DESCRIPTION"); ?></td>
		<td><? echo ociresult($pallet_list, "WAREHOUSE_LOCATION"); ?></td>
		<td><? echo ociresult($pallet_list, "QTY_IN_HOUSE"); ?></td>
		<td><? echo round(ociresult($pallet_list, "WEIGHT") * $ratio); ?></td>
		<td><input type="checkbox" name="edit_pallet_DO[<? echo $counter; ?>]" 
					value="<? echo ociresult($pallet_list, "PALLET_ID"); ?>" <? if($pre_check == "yes"){?> checked <?}?>></td>
	</tr>
<?
			$total_pcs += ociresult($pallet_list, "QTY_IN_HOUSE");
			$total_weight += round(ociresult($pallet_list, "WEIGHT") * $ratio);
			$counter++;
		}
?>
	<tr>
		<td colspan="3" align="right"><b>TOTALS:</b></td>
		<td><b><? echo $total_pcs; ?></b></td>
		<td><b><? echo $total_weight; ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="6">New DO#&nbsp;&nbsp;&nbsp;<input name="new_DO_2" size="10" maxlength="10" type="text">
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="save DO"></td>
	</tr>
<input type="hidden" name="counter" value="<? echo $counter; ?>">
</form>
</table>
<?
	}
	include("pow_footer.php");
?>