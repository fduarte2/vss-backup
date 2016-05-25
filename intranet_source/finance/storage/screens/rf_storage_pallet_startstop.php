<?
/*
*	Adam Walter, Oct-Nov 2009
*
*	This is the script to determine the Storage Bills of Cargo
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2009)
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);


	$submit = $HTTP_POST_VARS['submit'];

	$set_start_rec = $HTTP_POST_VARS['start_rec'];
	$set_end_rec = $HTTP_POST_VARS['end_rec'];
	$set_start_bill = $HTTP_POST_VARS['start_bill'];
	$set_end_bill = $HTTP_POST_VARS['end_bill'];
	$set_vessel = $HTTP_POST_VARS['vessel'];
	$set_cust = $HTTP_POST_VARS['cust'];
	$set_comm = $HTTP_POST_VARS['comm'];
	$set_barcode = $HTTP_POST_VARS['barcode'];
	$set_bill = $HTTP_POST_VARS['bill'];


	if($submit == "Set Storage Status"){
		$set_start_rec = $HTTP_POST_VARS['set_start_rec'];
		$set_end_rec = $HTTP_POST_VARS['set_end_rec'];
		$set_start_bill = $HTTP_POST_VARS['set_start_bill'];
		$set_end_bill = $HTTP_POST_VARS['set_end_bill'];
		$set_vessel = $HTTP_POST_VARS['set_vessel'];
		$set_cust = $HTTP_POST_VARS['set_cust'];
		$set_comm = $HTTP_POST_VARS['set_comm'];
		$set_barcode = $HTTP_POST_VARS['set_barcode'];
		$set_bill = $HTTP_POST_VARS['set_bill'];

		$extra_sql = GetExtraSql($set_start_rec, $set_end_rec, $set_start_bill, $set_end_bill, $set_vessel, $set_cust, $set_comm, $set_barcode, $set_bill);

		$new_bill = $HTTP_POST_VARS['new_bill'];
		$new_bill_date = $HTTP_POST_VARS['new_bill_date'];

		$days_added = $HTTP_POST_VARS['days_added'];
		$bill_date = $HTTP_POST_VARS['bill_date'];

		if($new_bill_date == "new_date" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $bill_date)){
			echo "<font color=\"#FF0000\">New Bill Date must be in MM/DD/YYYY Format (was entered as ".$bill_date.")</font>";
			$submit = "Retrieve Pallets";
		} elseif($new_bill_date == "add_days" && (!is_numeric($days_added) || $days_added == 0)){
			echo "<font color=\"#FF0000\">Must choose a non-zero number of days to add to storage Date (was entered as ".$days_added.")</font>";
			$submit = "Retrieve Pallets";
		} else {
			// lets update this, eh?
			$sql = "UPDATE CARGO_TRACKING ";

			switch($new_bill){
				case "Y":
					$sql .= "SET BILL = NULL ";
				break;
				case "N":
					$sql .= "SET BILL = 'N' ";
				break;
			}

			switch($new_bill_date){
				case "as_is":
				break;

				case "add_days":
					$sql .= ",BILLING_STORAGE_DATE = BILLING_STORAGE_DATE + ".$days_added." ";
				break;

				case "new_date":
					$sql .= ",BILLING_STORAGE_DATE = TO_DATE('".$bill_date."', 'MM/DD/YYYY') ";
				break;
			}

			$sql .= "WHERE 1 = 1 ".$extra_sql;
//			echo $sql."<br>";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);

/*			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
					WHERE 1 = 1 ".$extra_sql;
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			ora_fetch_into($cursor_first, $count_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); */
			echo "<font color=\"0000FF\"><b>Updated Storage Bill Status saved.";//.$count_row['THE_COUNT']." Pallets Billing status adjusted.<br></font>";

			$set_start_rec = "";
			$set_end_rec = "";
			$set_start_bill = "";
			$set_end_bill = "";
			$set_vessel = "";
			$set_cust = "";
			$set_comm = "";
			$set_barcode = "";
			$set_bill = "";

		}
	}
?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Start or Stop Scanned Storage Billing
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="rf_storage_pallet_startstop.php" method="post">
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Please enter at least 1 data value.</b></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date Received (From):  </font></td>
		<td><input type="text" name="start_rec" size="15" maxlength="10" value="<? echo $set_start_rec; ?>"><a href="javascript:show_calendar('meh.start_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date Received (To):  </font></td>
		<td><input type="text" name="end_rec" size="15" maxlength="10" value="<? echo $set_end_rec; ?>"><a href="javascript:show_calendar('meh.end_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Next Bill Date (From):  </font></td>
		<td><input type="text" name="start_bill" size="15" maxlength="10" value="<? echo $set_start_bill; ?>"><a href="javascript:show_calendar('meh.start_bill');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Next Bill Date (To):  </font></td>
		<td><input type="text" name="end_bill" size="15" maxlength="10" value="<? echo $set_end_bill; ?>"><a href="javascript:show_calendar('meh.end_bill');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Vessel:  </font></td>
		<td><input type="text" name="vessel" size="20" maxlength="20" value="<? echo $set_vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer:  </font></td>
		<td><input type="text" name="cust" size="20" maxlength="20" value="<? echo $set_cust; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">(Scanned) Commodity:  </font></td>
		<td><input type="text" name="comm" size="20" maxlength="20" value="<? echo $set_comm; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Barcode:  </font></td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $set_barcode; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Billing Status:  </font></td>
		<td>	<input type="radio" name="bill" value="any" <? if($set_bill == "any"){?> checked<?}?>>All<br>
				<input type="radio" name="bill" value="" <? if($set_bill == ""){?> checked<?}?>>Currently Billing<br>
				<input type="radio" name="bill" value="N" <? if($set_bill == "N"){?> checked<?}?>>Stopped by Finance<br>
				<input type="radio" name="bill" value="X" <? if($set_bill == "X"){?> checked<?}?>>Marked Unbillable by Autostorage<br>
				<input type="radio" name="bill" value="F" <? if($set_bill == "F"){?> checked<?}?>>No More Bills to Generate<br>
			</td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Pallets"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Pallets"){

		$extra_sql = GetExtraSql($set_start_rec, $set_end_rec, $set_start_bill, $set_end_bill, $set_vessel, $set_cust, $set_comm, $set_barcode, $set_bill);
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
<form name="set" action="rf_storage_pallet_startstop.php" method="post">
<input type="hidden" name="set_start_rec" value="<? echo $set_start_rec; ?>">
<input type="hidden" name="set_end_rec" value="<? echo $set_end_rec; ?>">
<input type="hidden" name="set_start_bill" value="<? echo $set_start_bill; ?>">
<input type="hidden" name="set_end_bill" value="<? echo $set_end_bill; ?>">
<input type="hidden" name="set_vessel" value="<? echo $set_vessel; ?>">
<input type="hidden" name="set_cust" value="<? echo $set_cust; ?>">
<input type="hidden" name="set_comm" value="<? echo $set_comm; ?>">
<input type="hidden" name="set_barcode" value="<? echo $set_barcode; ?>">
<input type="hidden" name="set_bill" value="<? echo $set_bill; ?>">
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>Selected Pallets:</b></font><br><font color="#9933FF" size="2" face="Verdana"><b>(Note:  Only pallets that have had their free time successfully set will be available here)</b></font></td>
	</tr>
<?
		if($extra_sql == false){
?>
	<tr>
		<td colspan="7"><font color="#FF0000">Could not perform search.</font></td>
	</tr>
<?
		} else {

			$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NR') DATE_REC,
							NVL(TO_CHAR(BILLING_STORAGE_DATE, 'MM/DD/YYYY'), 'NB') DATE_BILL,
							PALLET_ID,
							RECEIVER_ID,
							ARRIVAL_NUM,
							DECODE(BILL, 'N', 'BILLING TURNED OFF', 'X', 'MARKED UNBILLABLE', 'F', 'NO FURTHER BILLS TO GENERATE', 'BILLING NORMALLY') THE_BILL,
							CT.COMMODITY_CODE THE_RF_COMM,
							NVL(TO_CHAR(BNI_COMM), '--NO UNSCANNED COMM FOUND--') THE_BNI_COMM
					FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
					WHERE CT.COMMODITY_CODE = RTBC.RF_COMM(+) ".$extra_sql." 
					ORDER BY PALLET_ID, RECEIVER_ID, ARRIVAL_NUM";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="7"><font color="#FF0000">No pallets match entered criteria</font></td>
	</tr>
<?
			} else {
?>
	<tr>
		<td colspan="3" align="left">
			<font size="3" face="Verdana"><b>Mark Pallets As:</b></font><br>
			<input type="radio" name="new_bill" value="Y" checked>&nbsp;<font size="2" face="Verdana"><b>Bill</b></font><br>
			<input type="radio" name="new_bill" value="N">&nbsp;<font size="2" face="Verdana"><b>Stop Billing</b></font>
		</td>
		<td colspan="4" align="left">
			<font size="3" face="Verdana"><b>If Billing Storage:</b></font><br>
			<input type="radio" name="new_bill_date" value="as_is" checked>&nbsp;<font size="2" face="Verdana"><b>Keep Existing Next Storage Dates</b></font><br>
			<input type="radio" name="new_bill_date" value="add_days">&nbsp;<input type="text" name="days_added" size="3" maxlength="3" value="0">&nbsp;<font size="2" face="Verdana"><b>Added days to existing Next Bill Dates</b></font><br>
			<input type="radio" name="new_bill_date" value="new_date">&nbsp;<input type="text" name="bill_date" size="10" maxlength="10" value="">&nbsp;<font size="2" face="Verdana"><b>Set Specific Next Bill Date (MM/DD/YYYY)</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Set Storage Status"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Next Bill Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity(s)</b></font></td>
		<td><font size="2" face="Verdana"><b>Billing Status</b></font></td>
	</tr>
<?
				do {
					if($row['THE_BILL'] == "BILLING NORMALLY"){
						$bgcolor= "#FFFFFF";
					} elseif($row['THE_BILL'] == "MARKED UNBILLABLE"){
						$bgcolor= "#FF0000";
					} elseif($row['THE_BILL'] == "BILLING TURNED OFF"){
						$bgcolor= "#00FFFF";
					} elseif($row['THE_BILL'] == "NO FURTHER BILLS TO GENERATE"){
						$bgcolor= "#66CC33";
					}

?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_BILL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana">Scanned:&nbsp;<? echo $row['THE_RF_COMM']; ?><br>Unscanned:&nbsp;<? echo $row['THE_BNI_COMM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BILL']; ?></font></td>
	</tr>
<?
				} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}
		}
?>
</table>
</form>
<?
	}
	include("pow_footer.php");














function GetExtraSql($start_rec, $end_rec, $start_bill, $end_bill, $vessel, $cust, $comm, $barcode, $bill){
	$return = "AND 1 = 1 ";
	$error = "";

	if($start_rec != ""){
		if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $start_rec)){
			$error .= "Date Received (Start) not in MM/DD/YYYY format.<br>";
		} else {
			$return .= "AND DATE_RECEIVED >= TO_DATE('".$start_rec."', 'MM/DD/YYYY') ";
		}
	}

	if($end_rec != ""){
		if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $end_rec)){
			$error .= "Date Received (End) not in MM/DD/YYYY format.<br>";
		} else {
			$return .= "AND DATE_RECEIVED <= TO_DATE('".$end_rec." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
		}
	}

	if($start_bill != ""){
		if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $start_bill)){
			$error .= "Next Bill Date (Start) not in MM/DD/YYYY format.<br>";
		} else {
			$return .= "AND BILLING_STORAGE_DATE >= TO_DATE('".$start_bill."', 'MM/DD/YYYY') ";
		}
	}

	if($end_bill != ""){
		if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $end_bill)){
			$error .= "Next Bill Date (End) not in MM/DD/YYYY format.<br>";
		} else {
			$return .= "AND BILLING_STORAGE_DATE <= TO_DATE('".$end_bill." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
		}
	}

	if($vessel != ""){
		$return .= "AND ARRIVAL_NUM = '".$vessel."' ";
	}

	if($cust != ""){
		$return .= "AND RECEIVER_ID = '".$cust."' ";
	}

	if($comm != ""){
		$return .= "AND COMMODITY_CODE = '".$comm."' ";
	}

	if($barcode != ""){
		$return .= "AND PALLET_ID = '".$barcode."' ";
	}

	if($bill == "any" && $return == "" && $error == ""){
		echo "<font color=\"#FF0000\">Please enter at least 1 Search Criteria</font>";
		return false;
	} else {
		switch($bill){
			case "":
				$return .= "AND BILL IS NULL ";
			break;

			case "N":
				$return .= "AND BILL = 'N' ";
			break;

			case "X":
				$return .= "AND BILL = 'X' ";
			break;

			case "F":
				$return .= "AND BILL = 'F' ";
			break;
		}
	}

	if($error != ""){
		echo "<font color=\"#FF0000\">The following Errors were found in the search criteria:<br>".$error."</font>";
		return false;
	} else {
		return $return;
	}
}

?>