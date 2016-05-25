<?
/*
*	Adam Walter, May 2012
*
*	Script to "obliterate" the free tinme for a set of pallets; said free time
*	Will then be reset by the overnight "set free time" script.
*
*	10/17/2012.
*	Note:  For RF storage, this will NOT reset any bill specifically turned
*	Off by finance (Bill = 'N').  They need to "un-N" it first.
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
//	$rf_cursor = ora_open($conn);
//	$Short_Term_Cursor = ora_open($conn);

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
//  $bni_cursor = ora_open($bni_conn);
//  $update_bni_cursor = ora_open($bni_conn);
//  $Short_Term_Cursor = ora_open($bni_conn);




	$submit = $HTTP_POST_VARS['submit'];
	$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

	$system = $HTTP_POST_VARS['system'];
	if($system == "UnScanned"){
		$cursor = ora_open($bni_conn);
		$Short_Term_Cursor = ora_open($bni_conn);
	} elseif($system == "Scanned") {
		$cursor = ora_open($conn);
		$Short_Term_Cursor = ora_open($conn);
	}

	$set_start_rec = $HTTP_POST_VARS['start_rec'];
	$set_end_rec = $HTTP_POST_VARS['end_rec'];
	$set_vessel = $HTTP_POST_VARS['vessel'];
	$set_cust = $HTTP_POST_VARS['cust'];
	$set_comm = $HTTP_POST_VARS['comm'];
	$set_barcode = $HTTP_POST_VARS['barcode'];

	if($submit == "Clear Free Time"){
		if($system == "UnScanned"){
			$update_sql = "UPDATE CARGO_TRACKING SET START_FREE_TIME = NULL, FREE_DAYS = NULL, STORAGE_CUST_ID = NULL, FREE_TIME_END = NULL, STORAGE_END = NULL ";
		} else {
			$update_sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = NULL, SOURCE_USER = '".$user."', SOURCE_NOTE = '".$ip." - FreeTimeReset' ";
		}

		$update_sql .= GetWhereSql($set_start_rec, $set_end_rec, $set_vessel, $set_cust, $set_comm, $set_barcode, $system);
//		echo $update_sql;
		ora_parse($cursor, $update_sql);
		ora_exec($cursor);

		echo "<font color=\"0000FF\"><b>Selected ".$system." Free time Reset.</b></font>";
		$set_start_rec = "";
		$set_end_rec = "";
		$set_vessel = "";
		$set_cust = "";
		$set_comm = "";
		$set_barcode = "";
		$system = "";
	}

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Storage Free Time Reset
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="reset_free_time.php" method="post">
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Please enter at least 1 data value.</b></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">System:  </font></td>
		<td><input type="radio" name="system" value="UnScanned" <? if($system != "Scanned"){?>checked<?}?>>&nbsp;<font size="2" face="Verdana">Un-Scanned&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="system" value="Scanned" <? if($system == "Scanned"){?>checked<?}?>>Scanned</td>
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
		<td width="15%" align="left"><font size="2" face="Verdana">Vessel:  </font></td>
		<td><input type="text" name="vessel" size="20" maxlength="20" value="<? echo $set_vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer:  </font></td>
		<td><input type="text" name="cust" size="20" maxlength="20" value="<? echo $set_cust; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Commodity:  </font></td>
		<td><input type="text" name="comm" size="20" maxlength="20" value="<? echo $set_comm; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Barcode (Scanned)<br>---OR---<br>Lot# (Unscanned):  </font></td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $set_barcode; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
		$lines = 0;
		$select_sql = GetSelectSql($set_start_rec, $set_end_rec, $set_vessel, $set_cust, $set_comm, $set_barcode, $system);
//		echo $select_sql;
//		break;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
<form name="set" action="reset_free_time.php" method="post">
<input type="hidden" name="system" value="<? echo $system; ?>">
<input type="hidden" name="start_rec" value="<? echo $set_start_rec; ?>">
<input type="hidden" name="end_rec" value="<? echo $set_end_rec; ?>">
<input type="hidden" name="vessel" value="<? echo $set_vessel; ?>">
<input type="hidden" name="cust" value="<? echo $set_cust; ?>">
<input type="hidden" name="comm" value="<? echo $set_comm; ?>">
<input type="hidden" name="barcode" value="<? echo $set_barcode; ?>">
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana"><b>Results:</b></font><br><font color="#9933FF" size="2" face="Verdana"><b>(Note:  Cargo is only included if the vessel has had it's free time set, and (for Scanned cargo) has not been manually stopped from billing.<br>If cargo HAS been billed, it is Finance's responsibility to delete prebills, issue CreditMemos, etc.)<br>If you are using this screen to redo bills because the vessel's free time start date has changed, you must first enter the new free time start date in the Vessel Entry Screen.<br>Currently, for UnScanned cargo, this screen can only be used if there are NO Invoices/Preinvoices for the selected cargo.</b></font></td>
	</tr>
<?
		if($select_sql == false){
?>
	<tr>
		<td colspan="6"><font color="#FF0000">Could not perform search.</font></td>
	</tr>
<?
		} else {
			ora_parse($cursor, $select_sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="6"><font color="#FF0000">No pallets match entered criteria</font></td>
	</tr>
<?
			} else {
?>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Clear Free Time"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode/Lot#</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>System</b></font></td>
	</tr>
<?
				do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['THE_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_CUST']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ShowComm($row['THE_COMM'], $system, $Short_Term_Cursor); ?></font></td>
		<td><font size="2" face="Verdana"><b><? echo $system; ?></b></font></td>
	</tr>
<?
					$lines++;
				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana"><b>Records:  <? echo $lines; ?></b></font></td>
	</tr>
<?
			}
		}
?>
</table>
</form>
<?
	}
	include("pow_footer.php");










function GetSelectSql($start_rec, $end_rec, $vessel, $cust, $comm, $barcode, $system){
	if($system == "UnScanned"){
		$return = "SELECT LOT_NUM THE_ID, CM.COMMODITY_CODE THE_COMM, CT.OWNER_ID THE_CUST, CM.LR_NUM THE_VES, 
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_REC
					FROM CARGO_TRACKING CT, CARGO_MANIFEST CM";
	} else {
		$return = "SELECT COMMODITY_CODE THE_COMM, RECEIVER_ID THE_CUST, ARRIVAL_NUM THE_VES, PALLET_ID THE_ID, 
						NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NONE') THE_REC
					FROM CARGO_TRACKING";
	}

	$where = GetWhereSql($start_rec, $end_rec, $vessel, $cust, $comm, $barcode, $system);

	if($where == false){
		return false;
	} else {
		$return .= $where;
		if($system == "UnScanned"){
			return $return .= " AND CT.LOT_NUM = CM.CONTAINER_NUM";
		} else {
			return $return;
		}
	}
}


function GetWhereSql($start_rec, $end_rec, $vessel, $cust, $comm, $barcode, $system){
	$error = "";

	if($system == "UnScanned"){
		$return = " WHERE TO_DATE(TO_CHAR(FREE_TIME_END, 'MM/DD/YYYY'), 'MM/DD/YYYY') - 1 = TO_DATE(TO_CHAR(STORAGE_END, 'MM/DD/YYYY'), 'MM/DD/YYYY') ";
		if($vessel != ""){
			$return .= "AND LOT_NUM IN (SELECT CONTAINER_NUM FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."') ";
		}

		if($cust != ""){
			$return .= "AND OWNER_ID = '".$cust."' ";
		}

		if($comm != ""){
			$return .= "AND LOT_NUM IN (SELECT CONTAINER_NUM FROM CARGO_MANIFEST WHERE COMMODITY_CODE = '".$comm."') ";
		}

		if($barcode != ""){
			$return .= "AND LOT_NUM = '".$barcode."' ";
		}
	} else {
		//TO_DATE(TO_CHAR(FREE_TIME_END, 'MM/DD/YYYY'), 'MM/DD/YYYY') = TO_DATE(TO_CHAR(BILLING_STORAGE_DATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')
		$return = " WHERE (BILL IS NULL OR BILL != 'N') ";
		if($vessel != ""){
			$return .= "AND ARRIVAL_NUM = '".$vessel."' ";
		}

		if($cust != ""){
			$return .= "AND RECEIVER_ID = '".$cust."' ";
		}

		if($comm != ""){
			$return .= "AND COMMODITY_CODE IN (SELECT RF_COMM FROM RF_TO_BNI_COMM WHERE BNI_COMM =  '".$comm."') ";
		}

		if($barcode != ""){
			$return .= "AND PALLET_ID = '".$barcode."' ";
		}
	}

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

	if($comm != "" && !is_numeric($comm)){
		$error .= "Commodity Code must be Numeric.<br>";
	}

	if($cust != "" && !is_numeric($cust)){
		$error .= "Customer must be Numeric.<br>";
	}

	if($error != ""){
		echo "<font color=\"#FF0000\">The following Errors were found in the search criteria:<br>".$error."</font>";
		return false;
	} else {
		return $return;
	}

}

function ShowComm($comm, $system, $Short_Term_Cursor){
	if($system == "UnScanned"){
		return $comm;
	} else {
		$sql = "SELECT BNI_COMM FROM RF_TO_BNI_COMM WHERE RF_COMM = '".$comm."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		return $row['BNI_COMM']." (".$comm." Scanned)";
	}
}
