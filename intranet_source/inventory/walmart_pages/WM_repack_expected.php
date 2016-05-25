<?
/*
*	Adam Walter, Mar 2011.
*
*	Allows INV to enter expected Walmart Repack-loads (as well
*	As their return dates)
*************************************************************************/


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
 
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);


  $forcerun = $HTTP_GET_VARS['forcerun'];
  if($forcerun == "yes"){
//	  echo "Hi<br>";
	  system("../../TS_Program/walmartcrons/WM_reject_order_notifications.sh");
//	  system("../TS_Testing/WM_reject_order_notifications.sh");
  }
  $submit = $HTTP_POST_VARS['submit'];

	if($submit == "Create Repack/Reject Order"){
		$order = $HTTP_POST_VARS['order_prefix'].$HTTP_POST_VARS['order'];
		$PO = $HTTP_POST_VARS['PO'];
		$P_OUT = $HTTP_POST_VARS['P_OUT'];
		$C_OUT = $HTTP_POST_VARS['C_OUT'];
		$D_OUT = $HTTP_POST_VARS['D_OUT'];
		$P_IN = $HTTP_POST_VARS['P_IN'];
		$C_IN = $HTTP_POST_VARS['C_IN'];
		$D_IN = $HTTP_POST_VARS['D_IN'];
		$status = $HTTP_POST_VARS['status'];
		$rej_date = $HTTP_POST_VARS['rej_date'];
		$ves_name = $HTTP_POST_VARS['ves_name'];
		$sup_name = $HTTP_POST_VARS['sup_name'];
		$item_desc = $HTTP_POST_VARS['item_desc'];
		$rej_reason = $HTTP_POST_VARS['rej_reason'];
		$prod_dispo = $HTTP_POST_VARS['prod_dispo'];

		if($order != "" && $PO != ""){
			// needed fields entered.  Validate checks.
			$insert = true;
			
			if($insert == true){
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM WM_EXPECTED_REPACK_ORDER
						WHERE ORDER_NUM = '".$order."'
						AND PO_NUM = '".$PO."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] >= 1){
					echo "<font color=\"#FF0000\">The Order/PO combination is already in system</font>";
					$insert = false;
				}
			}

			if($insert == true){
				$error = ValidateLine($order, $PO, $P_OUT, $C_OUT, $D_OUT, $status, $rej_date, $ves_name, $sup_name, $item_desc, $rej_reason, $prod_dispo);
				if($error != ""){
					echo "<font color=\"#FF0000\">".$error."</font>";
					$insert = false;
				}
			}

			if($insert == true){
				$sql = "SELECT MAX(NVL(REPORT_ROW_NUM, 1)) THE_MAX
						FROM WM_EXPECTED_REPACK_ORDER";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$next_row = "1";
				} else {
					$next_row = $short_term_row['THE_MAX'] + 1;
				}

				$sql = "INSERT INTO WM_EXPECTED_REPACK_ORDER
							(REPORT_ROW_NUM,
							ORDER_NUM,
							PO_NUM,
							PLT_COUNT,
							CASE_COUNT,
							EXPECTED_DATE,
							ENTERED_BY,
							RET_PLT_COUNT,
							RET_CASE_COUNT,
							RET_EXPECTED_DATE,
							STATUS,
							DATE_REJECTION,
							SUPPLIER_NAME,
							ITEM_DESCRIPTION,
							REJECT_REASON,
							DISPOSITION,
							VESSEL_NAME)
						VALUES
							('".$next_row."',
							'".$order."',
							'".$PO."',
							'".$P_OUT."',
							'".$C_OUT."',
							TO_DATE('".$D_OUT."', 'MM/DD/YYYY'),
							'".$user."',
							'".$P_IN."',
							'".$C_IN."',
							TO_DATE('".$D_IN."', 'MM/DD/YYYY'),
							'".$status."',
							TO_DATE('".$rej_date."', 'MM/DD/YYYY'),
							'".$status."',
							'".$item_desc."',
							'".$rej_reason."',
							'".$prod_dispo."',
							'".$ves_name."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				echo "<font color=\"#0000FF\">Order ".$order." - PO ".$PO." added.</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">Order and PO fields must be entered to add a new item.</font>";
		}
	}


	if($submit == "Save"){
		$order = $HTTP_POST_VARS['order'];
		$PO = $HTTP_POST_VARS['PO'];
		$P_OUT = $HTTP_POST_VARS['P_OUT'];
		$C_OUT = $HTTP_POST_VARS['C_OUT'];
		$D_OUT = $HTTP_POST_VARS['D_OUT'];
		$P_IN = $HTTP_POST_VARS['P_IN'];
		$C_IN = $HTTP_POST_VARS['C_IN'];
		$D_IN = $HTTP_POST_VARS['D_IN'];
		$status = $HTTP_POST_VARS['status'];
		$rej_date = $HTTP_POST_VARS['rej_date'];
		$ves_name = $HTTP_POST_VARS['ves_name'];
		$sup_name = $HTTP_POST_VARS['sup_name'];
		$item_desc = $HTTP_POST_VARS['item_desc'];
		$rej_reason = $HTTP_POST_VARS['rej_reason'];
		$prod_dispo = $HTTP_POST_VARS['prod_dispo'];

		$pre_order = $HTTP_POST_VARS['pre_order'];
		$pre_PO = $HTTP_POST_VARS['pre_PO'];
		$pre_P_OUT = $HTTP_POST_VARS['pre_P_OUT'];
		$pre_C_OUT = $HTTP_POST_VARS['pre_C_OUT'];
		$pre_D_OUT = $HTTP_POST_VARS['pre_D_OUT'];
		$pre_P_IN = $HTTP_POST_VARS['pre_P_IN'];
		$pre_C_IN = $HTTP_POST_VARS['pre_C_IN'];
		$pre_D_IN = $HTTP_POST_VARS['pre_D_IN'];
		$pre_status = $HTTP_POST_VARS['pre_status'];
		$pre_rej_date = $HTTP_POST_VARS['pre_rej_date'];
		$pre_ves_name = $HTTP_POST_VARS['pre_ves_name'];
		$pre_sup_name = $HTTP_POST_VARS['pre_sup_name'];
		$pre_item_desc = $HTTP_POST_VARS['pre_item_desc'];
		$pre_rej_reason = $HTTP_POST_VARS['pre_rej_reason'];
		$pre_prod_dispo = $HTTP_POST_VARS['pre_prod_dispo'];

		$i = 0;
		$updated = 0;

		while($order[$i] != "" && $PO[$i] != ""){

			
			if($order[$i] != $pre_order[$i] || $PO[$i] != $pre_PO[$i] || 
				$P_OUT[$i] != $pre_P_OUT[$i] || 
				$C_OUT[$i] != $pre_C_OUT[$i] || 
				$D_OUT[$i] != $pre_D_OUT[$i] || 
				$P_IN[$i] != $pre_P_IN[$i] || 
				$C_IN[$i] != $pre_C_IN[$i] || 
				$D_IN[$i] != $pre_D_IN[$i] || 
				$rej_date[$i] != $pre_rej_date[$i] || 
				$ves_name[$i] != $pre_ves_name[$i] || 
				$sup_name[$i] != $pre_sup_name[$i] || 
				$item_desc[$i] != $pre_item_desc[$i] || 
				$rej_reason[$i] != $pre_rej_reason[$i] || 
				$prod_dispo[$i] != $pre_prod_dispo[$i] || 
				$status[$i] != $pre_status[$i]){

				$update = true;
				
				if($update == true
					&& ($order[$i] != $pre_order[$i] || $PO[$i] != $pre_PO[$i])){

					$sql = "SELECT COUNT(*) THE_COUNT 
							FROM WM_EXPECTED_REPACK_ORDER
							WHERE ORDER_NUM = '".$order[$i]."'
							AND PO_NUM = '".$PO[$i]."'";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
					ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_row['THE_COUNT'] >= 1){
						echo "<font color=\"#FF0000\">The Order/PO combination change made to row ".($i + 1)." is already in system.</font><br>";
						$update = false;
					}
				}

				if($update == true){
					$error = ValidateLine($order[$i], $PO[$i], $P_OUT[$i], $C_OUT[$i], $D_OUT[$i], $status[$i], $rej_date[$i], $ves_name[$i], $sup_name[$i], $item_desc[$i], $rej_reason[$i], $prod_dispo[$i]);
					if($error != ""){
						echo "<font color=\"#FF0000\">Line ".$i." was skipped because:<br>".$error."</font>";
						$update = false;
					}
				}

				if($update == true){
					$sql = "UPDATE WM_EXPECTED_REPACK_ORDER
							SET ORDER_NUM = '".$order[$i]."',
								PO_NUM = '".$PO[$i]."',
								PLT_COUNT = '".$P_OUT[$i]."',
								CASE_COUNT = '".$C_OUT[$i]."',
								EXPECTED_DATE = TO_DATE('".$D_OUT[$i]."', 'MM/DD/YYYY'),
								RET_PLT_COUNT = '".$P_IN[$i]."',
								RET_CASE_COUNT = '".$C_IN[$i]."',
								RET_EXPECTED_DATE = TO_DATE('".$D_IN[$i]."', 'MM/DD/YYYY'),
								STATUS = '".$status[$i]."',
								DATE_REJECTION = TO_DATE('".$rej_date[$i]."', 'MM/DD/YYYY'),
								SUPPLIER_NAME = '".$sup_name[$i]."',
								ITEM_DESCRIPTION = '".$item_desc[$i]."',
								REJECT_REASON = '".$rej_reason[$i]."',
								DISPOSITION = '".$prod_dispo[$i]."',
								VESSEL_NAME = '".$ves_name[$i]."'
							WHERE ORDER_NUM = '".$pre_order[$i]."'
								AND PO_NUM = '".$pre_PO[$i]."'";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
//					echo $sql."<br>";

					$updated++;
				}
			}

			$i++;
		}

		echo "<font color=\"#0000FF\">".$updated." items updated.</font>";
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Repack/Reject Orders</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font><br><font size="3" face="Verdana"><a href="WM_repack_expected.php?forcerun=yes">Click Here</a> to force-run the Walmart Email Report.
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="create" action="WM_repack_expected.php" method="post">
<? $order_prefix = "R"; ?>
<input type="hidden" name="order_prefix" value="<? echo $order_prefix; ?>">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>New Entry</b></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana"><b>Order#:</b></font></td>
		<td><b><? echo $order_prefix; ?></b><input type="text" name="order" size="15" maxlength="12"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PO:</b></font></td>
		<td><input type="text" name="PO" size="15" maxlength="10"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Rejected Date:</b></font></td>
		<td><input type="text" name="rej_date" size="10" maxlength="10">&nbsp;&nbsp;<a href="javascript:show_calendar('create.rej_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel Name:</b></font></td>
		<td><select name="ves_name"><option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME 
			FROM VESSEL_PROFILE 
			WHERE SHIP_PREFIX IN ('ARG FRUIT', 'CHILEAN')
				AND LENGTH(LR_NUM) <= 5
			ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['VESSEL_NAME']." (".$row['LR_NUM'].")"; ?>"><? echo $row['VESSEL_NAME']." (".$row['LR_NUM'].")"; ?></option>
<?
	}
?>

		</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Supplier Name:</b></font></td>
		<td><input type="text" name="sup_name" size="25" maxlength="25"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Item Description:</b></font></td>
		<td><input type="text" name="item_desc" size="25" maxlength="25"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Reject Reason:</b></font></td>
		<td><input type="text" name="rej_reason" size="50" maxlength="50"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Disposition of Product:</b></font></td>
		<td><input type="text" name="prod_dispo" size="50" maxlength="50"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected Out (PLT):</b></font></td>
		<td><input type="text" name="P_OUT" size="10" maxlength="6"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected Out (CTN):</b></font></td>
		<td><input type="text" name="C_OUT" size="10" maxlength="6"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected Out (Date):</b></font></td>
		<td><input type="text" name="D_OUT" size="10" maxlength="10">&nbsp;&nbsp;<a href="javascript:show_calendar('create.D_OUT');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected In (PLT):</b></font></td>
		<td><input type="text" name="P_IN" size="10" maxlength="6"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected In (CTN):</b></font></td>
		<td><input type="text" name="C_IN" size="10" maxlength="6"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Expected In (Date):</b></font></td>
		<td><input type="text" name="D_IN" size="10" maxlength="10">&nbsp;&nbsp;<a href="javascript:show_calendar('create.D_IN');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Status:</b></font></td>
		<td><select name="status"><option value="ACTIVE">ACTIVE</option>
								<option value="CANCELLED">CANCELLED</option>
								<option value="COMPLETE">COMPLETE</option>
								<option value="DAILY ACTIVITY">DAILY ACTIVITY</option>
								<option value="SENT TO REPACK">SENT TO REPACK</option>
								<option value="BACK FROM REPACK">BACK FROM REPACK</option>
								<option value="IDC WHSE DAMAGE">IDC WHSE DAMAGE</option>
								<option value="OCEAN CARRIER DAMAGE">OCEAN CARRIER DAMAGE</option>
								<option value="IDC PULL PER BUYER">IDC PULL PER BUYER</option>
								<option value="IDC PULL PER IDC">IDC PULL PER IDC</option>
								<option value="RFS PULL">RFS PULL</option>
								<option value="PULL PER DISPOSED FOR SUPPLIER PICKUP">PULL PER DISPOSED FOR SUPPLIER PICKUP</option>
								<option value="PULL PER SOLD TO SALVAGE">PULL PER SOLD TO SALVAGE</option>
								<option value="HOLD PER FDA">HOLD PER FDA</option>
								<option value="RETURNED FROM FDA HOLD">RETURNED FROM FDA HOLD</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create Repack/Reject Order"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>

<br><br>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="WM_repack_expected.php" method="post">
	<tr>
		<td colspan="18" align="center"><font size="3" face="Verdana"><b>Edit Item #s</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Uniq Row#</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Reject Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Supplier Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Item Desc</b></font></td>
		<td><font size="2" face="Verdana"><b>Reject Reason</b></font></td>
		<td><font size="2" face="Verdana"><b>Product Disp.</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (PLT)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (CTN)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (Date)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (PLT)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (CTN)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (Date)</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTUAL SCANNED OUT:</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTUAL SCANNED IN:</b></font></td>
	</tr>
<?
	$i = 0;

	$sql = "SELECT ORDER_NUM, PO_NUM, PLT_COUNT, CASE_COUNT, EXPECTED_DATE, RET_PLT_COUNT, RET_CASE_COUNT, RET_EXPECTED_DATE,
				TO_CHAR(EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_OUT, TO_CHAR(RET_EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_IN, TO_CHAR(DATE_REJECTION, 'MM/DD/YYYY') REJ_DATE, 
				STATUS, SUPPLIER_NAME, ITEM_DESCRIPTION, REJECT_REASON, DISPOSITION, VESSEL_NAME, REPORT_ROW_NUM
			FROM WM_EXPECTED_REPACK_ORDER 
			WHERE (EXPECTED_DATE IS NULL OR EXPECTED_DATE >= SYSDATE - 540)
			ORDER BY REPORT_ROW_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<input type="hidden" name="pre_order[<? echo $i; ?>]" value="<? echo $row['ORDER_NUM']; ?>">
	<input type="hidden" name="pre_PO[<? echo $i; ?>]" value="<? echo $row['PO_NUM']; ?>">
	<input type="hidden" name="pre_P_OUT[<? echo $i; ?>]" value="<? echo $row['PLT_COUNT']; ?>">
	<input type="hidden" name="pre_C_OUT[<? echo $i; ?>]" value="<? echo $row['CASE_COUNT']; ?>">
	<input type="hidden" name="pre_D_OUT[<? echo $i; ?>]" value="<? echo $row['EXPEC_OUT']; ?>">
	<input type="hidden" name="pre_P_IN[<? echo $i; ?>]" value="<? echo $row['RET_PLT_COUNT']; ?>">
	<input type="hidden" name="pre_C_IN[<? echo $i; ?>]" value="<? echo $row['RET_CASE_COUNT']; ?>">
	<input type="hidden" name="pre_D_IN[<? echo $i; ?>]" value="<? echo $row['EXPEC_IN']; ?>">
	<input type="hidden" name="pre_rej_date[<? echo $i; ?>]" value="<? echo $row['REJ_DATE']; ?>">
	<input type="hidden" name="pre_ves_name[<? echo $i; ?>]" value="<? echo $row['VESSEL_NAME']; ?>">
	<input type="hidden" name="pre_sup_name[<? echo $i; ?>]" value="<? echo $row['SUPPLIER_NAME']; ?>">
	<input type="hidden" name="pre_item_desc[<? echo $i; ?>]" value="<? echo $row['ITEM_DESCRIPTION']; ?>">
	<input type="hidden" name="pre_rej_reason[<? echo $i; ?>]" value="<? echo $row['REJECT_REASON']; ?>">
	<input type="hidden" name="pre_prod_dispo[<? echo $i; ?>]" value="<? echo $row['DISPOSITION']; ?>">
	<tr>
		<td><font size="2" face="Verdana">&nbsp;<? echo $row['REPORT_ROW_NUM']; ?></font></td>
		<td><input type="text" name="order[<? echo $i; ?>]" size="15" maxlength="12" value="<? echo $row['ORDER_NUM']; ?>"></td>
		<td><input type="text" name="PO[<? echo $i; ?>]" size="15" maxlength="10" value="<? echo $row['PO_NUM']; ?>"></td>
		<td><input type="text" name="rej_date[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $row['REJ_DATE']; ?>"></td>
		<td><input type="text" name="ves_name[<? echo $i; ?>]" size="15" maxlength="40" value="<? echo $row['VESSEL_NAME']; ?>"></td>
		<td><input type="text" name="sup_name[<? echo $i; ?>]" size="15" maxlength="25" value="<? echo $row['SUPPLIER_NAME']; ?>"></td>
		<td><input type="text" name="item_desc[<? echo $i; ?>]" size="15" maxlength="25" value="<? echo $row['ITEM_DESCRIPTION']; ?>"></td>
		<td><input type="text" name="rej_reason[<? echo $i; ?>]" size="15" maxlength="50" value="<? echo $row['REJECT_REASON']; ?>"></td>
		<td><input type="text" name="prod_dispo[<? echo $i; ?>]" size="15" maxlength="50" value="<? echo $row['DISPOSITION']; ?>"></td>
		<td><input type="text" name="P_OUT[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $row['PLT_COUNT']; ?>"></td>
		<td><input type="text" name="C_OUT[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $row['CASE_COUNT']; ?>"></td>
		<td><input type="text" name="D_OUT[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $row['EXPEC_OUT']; ?>"></td>
		<td><input type="text" name="P_IN[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $row['RET_PLT_COUNT']; ?>"></td>
		<td><input type="text" name="C_IN[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $row['RET_CASE_COUNT']; ?>"></td>
		<td><input type="text" name="D_IN[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $row['EXPEC_IN']; ?>"></td>
		<td><select name="status[<? echo $i; ?>]">
								<option value="ACTIVE"<? if($row['STATUS'] == "ACTIVE"){?> selected <?}?>>ACTIVE</option>
								<option value="CANCELLED"<? if($row['STATUS'] == "CANCELLED"){?> selected <?}?>>CANCELLED</option>
								<option value="COMPLETE"<? if($row['STATUS'] == "COMPLETE"){?> selected <?}?>>COMPLETE</option>
								<option value="DAILY ACTIVITY"<? if($row['STATUS'] == "DAILY ACTIVITY"){?> selected <?}?>>DAILY ACTIVITY</option>
								<option value="SENT TO REPACK"<? if($row['STATUS'] == "SENT TO REPACK"){?> selected <?}?>>SENT TO REPACK</option>
								<option value="BACK FROM REPACK"<? if($row['STATUS'] == "BACK FROM REPACK"){?> selected <?}?>>BACK FROM REPACK</option>
								<option value="IDC WHSE DAMAGE"<? if($row['STATUS'] == "IDC WHSE DAMAGE"){?> selected <?}?>>IDC WHSE DAMAGE</option>
								<option value="OCEAN CARRIER DAMAGE"<? if($row['STATUS'] == "OCEAN CARRIER DAMAGE"){?> selected <?}?>>OCEAN CARRIER DAMAGE</option>
								<option value="IDC PULL PER BUYER"<? if($row['STATUS'] == "IDC PULL PER BUYER"){?> selected <?}?>>IDC PULL PER BUYER</option>
								<option value="IDC PULL PER IDC"<? if($row['STATUS'] == "IDC PULL PER IDC"){?> selected <?}?>>IDC PULL PER IDC</option>
								<option value="RFS PULL"<? if($row['STATUS'] == "RFS PULL"){?> selected <?}?>>RFS PULL</option>
								<option value="PULL PER DISPOSED FOR SUPPLIER PICKUP"<? if($row['STATUS'] == "PULL PER DISPOSED FOR SUPPLIER PICKUP"){?> selected <?}?>>PULL PER DISPOSED FOR SUPPLIER PICKUP</option>
								<option value="PULL PER SOLD TO SALVAGE"<? if($row['STATUS'] == "PULL PER SOLD TO SALVAGE"){?> selected <?}?>>PULL PER SOLD TO SALVAGE</option>
								<option value="HOLD PER FDA"<? if($row['STATUS'] == "HOLD PER FDA"){?> selected <?}?>>HOLD PER FDA</option>
								<option value="RETURNED FROM FDA HOLD"<? if($row['STATUS'] == "RETURNED FROM FDA HOLD"){?> selected <?}?>>RETURNED FROM FDA HOLD</option>
			</select></td>
<?
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) PLTS, SUM(QTY_CHANGE) CTNS, CT.BATCH_ID, BOL
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND SERVICE_CODE = 6
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = '".$row['ORDER_NUM']."'
					AND CT.MARK = '".$row['PO_NUM']."'
				GROUP BY CT.BATCH_ID, BOL
				ORDER BY CT.BATCH_ID, BOL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<td>--NONE--</td>
<?
		} else {
?>
		<td>
			<table border="1" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="35%" align="left"><font face="Verdana" size="2"><b>WM#</b></font></td>
					<td width="35%" align="left"><font face="Verdana" size="2"><b>Sup#</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Plt</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Ctn</b></font></td>
				</tr>
<?
			do {
?>
				<tr>
					<td align="left"><font face="Verdana" size="2"><? echo $Short_Term_Row['BOL']; ?></font></td>
					<td align="left"><font face="Verdana" size="2"><? echo $Short_Term_Row['BATCH_ID']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $Short_Term_Row['PLTS']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $Short_Term_Row['CTNS']; ?></font></td>
				</tr>
<?
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
			</table>
<?
		}
?>
			
		</td>
<?
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) PLTS, SUM(QTY_CHANGE) CTNS, CT.BATCH_ID, BOL
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND SERVICE_CODE = 20
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = '".$row['ORDER_NUM']."'
					AND CT.MARK = '".$row['PO_NUM']."'
				GROUP BY CT.BATCH_ID, BOL
				ORDER BY CT.BATCH_ID, BOL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<td>--NONE--</td>
<?
		} else {
?>
		<td>
			<table border="1" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="35%" align="left"><font face="Verdana" size="2"><b>WM#</b></font></td>
					<td width="35%" align="left"><font face="Verdana" size="2"><b>Sup#</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Plt</b></font></td>
					<td width="15%" align="right"><font face="Verdana" size="2"><b>Ctn</b></font></td>
				</tr>
<?
			do {
?>
				<tr>
					<td align="left"><font face="Verdana" size="2"><? echo $Short_Term_Row['BOL']; ?></font></td>
					<td align="left"><font face="Verdana" size="2"><? echo $Short_Term_Row['BATCH_ID']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo $Short_Term_Row['PLTS']; ?></font></td>
					<td align="right"><font face="Verdana" size="2"><? echo (-1 * $Short_Term_Row['CTNS']); ?></font></td>
				</tr>
<?
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
			</table>
<?
		}
?>
		</td>
	</tr>
<?
		$i++;
	}
	if($i > 0){
?>
	<tr>
		<td colspan="18" align="center"><input type="submit" name="submit" value="Save"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="18" align="center"><font color="#FF0000">No Known Orders In System</font></td>
	</tr>
<?
	}
?>
</form>
<?
	include("pow_footer.php");










function ValidateLine($order, $PO, $P_OUT, $C_OUT, $D_OUT, $status, $rej_date, $ves_name, $sup_name, $item_desc, $rej_reason, $prod_dispo){
	$return = "";
	if($order == ""){
		$return .= "Order# cannot be empty.<br>";
	}
	if($PO == ""){
		$return .= "PO# cannot be empty.<br>";
	}
	if($P_OUT == ""){
		$return .= "Outbound Pallet Count cannot be empty.<br>";
	}
	if($C_OUT == ""){
		$return .= "Outbound Carton Count cannot be empty.<br>";
	}
	if($D_OUT == ""){
		$return .= "Outbound Date cannot be empty.<br>";
	}
	if($status == ""){
		$return .= "Status cannot be empty.<br>";
	}
	if($rej_date == ""){
		$return .= "Date of Reject cannot be empty.<br>";
	}
	if($ves_name == ""){
		$return .= "Vessel Name cannot be empty.<br>";
	}
	if($sup_name == ""){
		$return .= "Supplier Name cannot be empty.<br>";
	}
	if($item_desc == ""){
		$return .= "Item Description cannot be empty.<br>";
	}
	if($rej_reason == ""){
		$return .= "Reason for Reject cannot be empty.<br>";
	}
	if($prod_dispo == ""){
		$return .= "Product Disposition cannot be empty.<br>";
	}

	return $return;
}