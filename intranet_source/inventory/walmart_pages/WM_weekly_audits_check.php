<?
/*
*	Adam Walter, Dec 2011.
*
*	This page is so that Inventory can add a "I verified this" type of 
*	check, per week, to use on a Walmart Accuracy report.
*************************************************************************/



  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Walmart Validation";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);




	$day = date('d');
	$month = date('m');
	$year = date('Y');
	switch($day){
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
			$week = 1;
//			$start = date('m/d/Y', mktime(date('m'),1,date('Y'),0,0,0));
//			$end = date('m/d/Y', mktime(date('m'),7,date('Y'),0,0,0));
//			$LY_start = date('m/d/Y', mktime(date('m'),1,date('Y') - 1,0,0,0));
//			$LY_end = date('m/d/Y', mktime(date('m'),7,date('Y') - 1,0,0,0));
		break;

		case 8:
		case 9:
		case 10:
		case 11:
		case 12:
		case 13:
		case 14:
			$week = 2;
//			$start = date('m/d/Y', mktime(date('m'),8,date('Y'),0,0,0));
//			$end = date('m/d/Y', mktime(date('m'),14,date('Y'),0,0,0));
//			$LY_start = date('m/d/Y', mktime(date('m'),8,date('Y') - 1,0,0,0));
//			$LY_end = date('m/d/Y', mktime(date('m'),14,date('Y') - 1,0,0,0));
		break;

		case 15:
		case 16:
		case 17:
		case 18:
		case 19:
		case 20:
		case 21:
			$week = 3;
//			$start = date('m/d/Y', mktime(date('m'),15,date('Y'),0,0,0));
//			$end = date('m/d/Y', mktime(date('m'),21,date('Y'),0,0,0));
//			$LY_start = date('m/d/Y', mktime(date('m'),15,date('Y') - 1,0,0,0));
//			$LY_end = date('m/d/Y', mktime(date('m'),21,date('Y') - 1,0,0,0));
		break;
		
		case 22:
		case 23:
		case 24:
		case 25:
		case 26:
		case 27:
		case 28:
		case 29:
		case 30:
		case 31:
			$week = 4;
//			$start = date('m/d/Y', mktime(date('m'),22,date('Y'),0,0,0));
//			$end = date('m/d/Y', mktime(date('m'),date('Y'),0,0,0));
//			$LY_start = date('m/d/Y', mktime(date('m'),22,date('Y') - 1,0,0,0));
//			$LY_end = date('m/d/Y', mktime(date('m'),date('Y') - 1,0,0,0));
		break;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Enter Warehouse Data"){
		$WH_checked = $HTTP_POST_VARS['WH_checked'];
		$WH_correct = $HTTP_POST_VARS['WH_correct'];
		if(!is_numeric($WH_checked) || !is_numeric($WH_correct)){
			echo "<font color=\"#FF0000\">All Warehouse entery fields must be numeric.  Could not save.";
		} else {

			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT
					FROM WM_INVENTORY_AUDIT
					WHERE REPORT_YEAR = '".$year."'
						AND REPORT_MONTH = '".$month."'
						AND REPORT_WEEK = '".$week."'";
//			echo $sql."<br>";
			$ora_success = ora_parse($Short_Term_Cursor, $sql);
			$ora_success = ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_Row['THE_COUNT'] <= 0){
				$sql = "INSERT INTO WM_INVENTORY_AUDIT
							(REPORT_YEAR,
							REPORT_MONTH,
							REPORT_WEEK,
							WH_ENTERED_BY,
							WH_TEST_DATE,
							WH_PALLETS_CHECKED,
							WH_PALLETS_CORRECT)
						VALUES
							('".$year."',
							'".$month."',
							'".$week."',
							'".$user."',
							SYSDATE,
							'".$WH_checked."',
							'".$WH_correct."')";
			} else {
				$sql = "UPDATE WM_INVENTORY_AUDIT
						SET WH_ENTERED_BY = '".$user."',
							WH_TEST_DATE = SYSDATE,
							WH_PALLETS_CHECKED = '".$WH_checked."',
							WH_PALLETS_CORRECT = '".$WH_correct."'
						WHERE REPORT_YEAR = '".$year."'
							AND REPORT_MONTH = '".$month."'
							AND REPORT_WEEK = '".$week."'";
			}
			$ora_success = ora_parse($mod_cursor, $sql);
			$ora_success = ora_exec($mod_cursor);
		}
	} elseif($submit == "Enter Truck Data"){
		$TR_checked = $HTTP_POST_VARS['TR_checked'];
		$TR_correct = $HTTP_POST_VARS['TR_correct'];
		$TR_load_num = $HTTP_POST_VARS['TR_load_num'];
		if(!is_numeric($TR_checked) || !is_numeric($TR_correct) || $TR_load_num == ""){
			echo "<font color=\"#FF0000\">All pallet counts must be numeric, and Truck# is required.  Could not save.";
		} else {
			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT
					FROM WM_INVENTORY_AUDIT
					WHERE REPORT_YEAR = '".$year."'
						AND REPORT_MONTH = '".$month."'
						AND REPORT_WEEK = '".$week."'";
			$ora_success = ora_parse($Short_Term_Cursor, $sql);
			$ora_success = ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_Row['THE_COUNT'] <= 0){
				$sql = "INSERT INTO WM_INVENTORY_AUDIT
							(REPORT_YEAR,
							REPORT_MONTH,
							REPORT_WEEK,
							TR_ENTERED_BY,
							TR_TEST_DATE,
							TR_PALLETS_EXPECTED,
							TR_PALLETS_CORRECT,
							TR_LOAD_NUM)
						VALUES
							('".$year."',
							'".$month."',
							'".$week."',
							'".$user."',
							SYSDATE,
							'".$TR_checked."',
							'".$TR_correct."',
							'".$TR_load_num."')";
			} else {
				$sql = "UPDATE WM_INVENTORY_AUDIT
						SET TR_ENTERED_BY = '".$user."',
							TR_TEST_DATE = SYSDATE,
							TR_PALLETS_EXPECTED = '".$TR_checked."',
							TR_PALLETS_CORRECT = '".$TR_correct."',
							TR_LOAD_NUM = '".$TR_load_num."'
						WHERE REPORT_YEAR = '".$year."'
							AND REPORT_MONTH = '".$month."'
							AND REPORT_WEEK = '".$week."'";
			}
			$ora_success = ora_parse($mod_cursor, $sql);
			$ora_success = ora_exec($mod_cursor);
		}
	}



	$sql = "SELECT TO_CHAR(WH_TEST_DATE, 'MM/DD/YYYY HH24:MI:SS') WH_DATE,
				TO_CHAR(TR_TEST_DATE, 'MM/DD/YYYY HH24:MI:SS') TR_DATE,
				WH_PALLETS_CHECKED, WH_PALLETS_CORRECT, TR_PALLETS_EXPECTED, TR_PALLETS_CORRECT, TR_LOAD_NUM
			FROM WM_INVENTORY_AUDIT
			WHERE REPORT_YEAR = '".$year."'
				AND REPORT_MONTH = '".$month."'
				AND REPORT_WEEK = '".$week."'";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$WH_date = "";
		$WH_checked = 0;
		$WH_correct = 0;
		$TR_date = "";
		$TR_checked = 0;
		$TR_load_num = "";
		$TR_correct = 0;
	} else {
		$WH_date = $Short_Term_Row['WH_DATE'];
		$WH_checked = (0 + $Short_Term_Row['WH_PALLETS_CHECKED']);
		$WH_correct = (0 + $Short_Term_Row['WH_PALLETS_CORRECT']);
		$TR_date = $Short_Term_Row['TR_DATE'];
		$TR_checked = (0 + $Short_Term_Row['TR_PALLETS_EXPECTED']);
		$TR_load_num = $Short_Term_Row['TR_LOAD_NUM'];
		$TR_correct = (0 + $Short_Term_Row['TR_PALLETS_CORRECT']);
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Weekly Accuracy Check</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Current User:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $user; ?></b></font></td>
	</tr>
	<tr>
		<td width="50%">
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
			<form name="WM_audit" action="WM_weekly_audits_check.php" method="post">
			<input name="user_submit" type="hidden" value="<? echo $user; ?>">
				<tr>
					<td colspan="2" align="center"><font size="2" face="Verdana"><b>Warehouse Cycle Spot Check</b></font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Audit Week:&nbsp;&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $week; ?></font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Last Entered:&nbsp;&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $WH_date; ?>&nbsp;</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Pallets Checked:&nbsp;&nbsp;</font></td>
					<td><input name="WH_checked" type="text" value="<? echo $WH_checked; ?>"></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Pallets Correct:&nbsp;&nbsp;</font></td>
					<td><input name="WH_correct" type="text" value="<? echo $WH_correct; ?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Enter Warehouse Data"></td>
				</tr>
			</form>
			</table>
		</td>
		<td width="50%">
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
			<form name="TR_audit" action="WM_weekly_audits_check.php" method="post">
			<input name="user_submit" type="hidden" value="<? echo $user; ?>">
				<tr>
					<td colspan="2" align="center"><font size="2" face="Verdana"><b>Truck-Out Spot Check</b></font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Audit Week:&nbsp;&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $week; ?></font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Last Entered:&nbsp;&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $TR_date; ?>&nbsp;</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Pallets Checked:&nbsp;&nbsp;</font></td>
					<td><input name="TR_checked" type="text" value="<? echo $TR_checked; ?>"></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Pallets Correct:&nbsp;&nbsp;</font></td>
					<td><input name="TR_correct" type="text" value="<? echo $TR_correct; ?>"></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana">Load#:&nbsp;&nbsp;</font></td>
					<td><input name="TR_load_num" type="text" value="<? echo $TR_load_num; ?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Enter Truck Data"></td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
</table>
<?
	include("pow_footer.php");
?>