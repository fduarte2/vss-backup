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


  $submit = $HTTP_POST_VARS['submit'];

	if($submit == "Create New Item"){
		$order = $HTTP_POST_VARS['order_prefix'].$HTTP_POST_VARS['order'];
		$PO = $HTTP_POST_VARS['PO'];
		$P_OUT = $HTTP_POST_VARS['P_OUT'];
		$C_OUT = $HTTP_POST_VARS['C_OUT'];
		$D_OUT = $HTTP_POST_VARS['D_OUT'];
		$P_IN = $HTTP_POST_VARS['P_IN'];
		$C_IN = $HTTP_POST_VARS['C_IN'];
		$D_IN = $HTTP_POST_VARS['D_IN'];
		$status = $HTTP_POST_VARS['status'];

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
				$sql = "INSERT INTO WM_EXPECTED_REPACK_ORDER
							(ORDER_NUM,
							PO_NUM,
							PLT_COUNT,
							CASE_COUNT,
							EXPECTED_DATE,
							ENTERED_BY,
							RET_PLT_COUNT,
							RET_CASE_COUNT,
							RET_EXPECTED_DATE,
							STATUS)
						VALUES
							('".$order."',
							'".$PO."',
							'".$P_OUT."',
							'".$C_OUT."',
							TO_DATE('".$D_OUT."', 'MM/DD/YYYY'),
							'".$user."',
							'".$P_IN."',
							'".$C_IN."',
							TO_DATE('".$D_IN."', 'MM/DD/YYYY'),
							'".$status."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				echo "<font color=\"#0000FF\">Order ".$order." - PO ".$PO." added.</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">All fields must be entered to add a new item.</font>";
		}
	}


	if($submit == "Edit Item(s)"){
		$order = $HTTP_POST_VARS['order'];
		$PO = $HTTP_POST_VARS['PO'];
		$P_OUT = $HTTP_POST_VARS['P_OUT'];
		$C_OUT = $HTTP_POST_VARS['C_OUT'];
		$D_OUT = $HTTP_POST_VARS['D_OUT'];
		$P_IN = $HTTP_POST_VARS['P_IN'];
		$C_IN = $HTTP_POST_VARS['C_IN'];
		$D_IN = $HTTP_POST_VARS['D_IN'];
		$status = $HTTP_POST_VARS['status'];

		$pre_order = $HTTP_POST_VARS['pre_order'];
		$pre_PO = $HTTP_POST_VARS['pre_PO'];
		$pre_P_OUT = $HTTP_POST_VARS['pre_P_OUT'];
		$pre_C_OUT = $HTTP_POST_VARS['pre_C_OUT'];
		$pre_D_OUT = $HTTP_POST_VARS['pre_D_OUT'];
		$pre_P_IN = $HTTP_POST_VARS['pre_P_IN'];
		$pre_C_IN = $HTTP_POST_VARS['pre_C_IN'];
		$pre_D_IN = $HTTP_POST_VARS['pre_D_IN'];
		$pre_status = $HTTP_POST_VARS['pre_status'];

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
					$sql = "UPDATE WM_EXPECTED_REPACK_ORDER
							SET ORDER_NUM = '".$order[$i]."',
							PO_NUM = '".$PO[$i]."',
							PLT_COUNT = '".$P_OUT[$i]."',
							CASE_COUNT = '".$C_OUT[$i]."',
							EXPECTED_DATE = TO_DATE('".$D_OUT[$i]."', 'MM/DD/YYYY'),
							RET_PLT_COUNT = '".$P_IN[$i]."',
							RET_CASE_COUNT = '".$C_IN[$i]."',
							RET_EXPECTED_DATE = TO_DATE('".$D_IN[$i]."', 'MM/DD/YYYY'),
							STATUS = '".$status[$i]."'
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
	    <font size="5" face="Verdana" color="#0066CC">Walmart Item Numbers
</font>
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
		<td><font size="2" face="Verdana"><b>Status):</b></font></td>
		<td><select name="status"><option value="ACTIVE">ACTIVE</option>
								<option value="CANCELLED">CANCELLED</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New Item"></td>
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
		<td colspan="9" align="center"><font size="3" face="Verdana"><b>Edit Item #s</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (PLT)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (CTN)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Out (Date)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (PLT)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (CTN)</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected In (Date)</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
	</tr>
<?
	$i = 0;

	$sql = "SELECT ORDER_NUM, PO_NUM, PLT_COUNT, CASE_COUNT, EXPECTED_DATE, RET_PLT_COUNT, RET_CASE_COUNT, RET_EXPECTED_DATE,
				TO_CHAR(EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_OUT, TO_CHAR(RET_EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_IN, STATUS
			FROM WM_EXPECTED_REPACK_ORDER 
			ORDER BY EXPECTED_DATE DESC, RET_EXPECTED_DATE DESC, ORDER_NUM, PO_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<input type="hidden" name="pre_order[<? echo $i; ?>]" value="<? echo $short_term_row['ORDER_NUM']; ?>">
	<input type="hidden" name="pre_PO[<? echo $i; ?>]" value="<? echo $short_term_row['PO_NUM']; ?>">
	<input type="hidden" name="pre_P_OUT[<? echo $i; ?>]" value="<? echo $short_term_row['PLT_COUNT']; ?>">
	<input type="hidden" name="pre_C_OUT[<? echo $i; ?>]" value="<? echo $short_term_row['CASE_COUNT']; ?>">
	<input type="hidden" name="pre_D_OUT[<? echo $i; ?>]" value="<? echo $short_term_row['EXPEC_OUT']; ?>">
	<input type="hidden" name="pre_P_IN[<? echo $i; ?>]" value="<? echo $short_term_row['RET_PLT_COUNT']; ?>">
	<input type="hidden" name="pre_C_IN[<? echo $i; ?>]" value="<? echo $short_term_row['RET_CASE_COUNT']; ?>">
	<input type="hidden" name="pre_D_IN[<? echo $i; ?>]" value="<? echo $short_term_row['EXPEC_IN']; ?>">
	<input type="hidden" name="pre_status[<? echo $i; ?>]" value="<? echo $short_term_row['STATUS']; ?>">
	<tr>
		<td><input type="text" name="order[<? echo $i; ?>]" size="15" maxlength="12" value="<? echo $short_term_row['ORDER_NUM']; ?>"></td>
		<td><input type="text" name="PO[<? echo $i; ?>]" size="15" maxlength="10" value="<? echo $short_term_row['PO_NUM']; ?>"></td>
		<td><input type="text" name="P_OUT[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $short_term_row['PLT_COUNT']; ?>"></td>
		<td><input type="text" name="C_OUT[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $short_term_row['CASE_COUNT']; ?>"></td>
		<td><input type="text" name="D_OUT[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $short_term_row['EXPEC_OUT']; ?>"></td>
		<td><input type="text" name="P_IN[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $short_term_row['RET_PLT_COUNT']; ?>"></td>
		<td><input type="text" name="C_IN[<? echo $i; ?>]" size="10" maxlength="6" value="<? echo $short_term_row['RET_CASE_COUNT']; ?>"></td>
		<td><input type="text" name="D_IN[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $short_term_row['EXPEC_IN']; ?>"></td>
		<td><select name="status[<? echo $i; ?>]">
				<option value="ACTIVE"<? if($short_term_row['STATUS'] == "ACTIVE"){?> selected <?}?>>ACTIVE</option>
				<option value="CANCELLED"<? if($short_term_row['STATUS'] == "CANCELLED"){?> selected <?}?>>CANCELLED</option>
			</select></td>
	</tr>
<?
		$i++;
	}
	if($i > 0){
?>
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Edit Item(s)"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="9" align="center"><font color="#FF0000">No Known Orders In System</font></td>
	</tr>
<?
	}
?>
</form>
