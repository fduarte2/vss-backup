<?
/*
*		Adam Walter, June 2014.
*
*		Moderate the list of Lease Bills for the V2 Bill System (7/2014)
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Print Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI"); echo "<font color=\"#000000\" size=\"1\">BNI LIVE DB</font><br>";
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST"); echo "<font color=\"#FF0000\" size=\"5\">BNI TEST DB</font><br>";
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$valid = "";
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Add New Entry"){
		$cust_select = $HTTP_POST_VARS['cust_select'];
		$active_select = $HTTP_POST_VARS['active_select'];

		$cust = $HTTP_POST_VARS['cust'];
		$lease = $HTTP_POST_VARS['lease'];
		if($lease[0] == ""){
			// no entry means they want an autoassigned one
			$sql = "SELECT MAX(TO_NUMBER(CONTRACTID)) THE_MAX
					FROM NONSTORAGE_RATE";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$lease[0] = ociresult($short_term_data, "THE_MAX") + 1;
		}
		$detail = $HTTP_POST_VARS['detail'];
		$active = $HTTP_POST_VARS['active'];
		$rate = $HTTP_POST_VARS['rate'];
		$unit = $HTTP_POST_VARS['unit'];
		$asset = $HTTP_POST_VARS['asset'];
		$asset = str_replace("'", "`", $asset);
		$asset = str_replace("\\", "", $asset);
		$comm = $HTTP_POST_VARS['comm'];
		$serv = $HTTP_POST_VARS['serv'];
		$freeform = $HTTP_POST_VARS['freeform'];
		$freeform = str_replace("'", "`", $freeform);
		$freeform = str_replace("\\", "", $freeform);

		$maxrows = "none";

		$valid = ValidityCheck($bniconn, "", $cust, $lease, $detail, $active, $rate, $unit, $asset, $comm, $serv, $freeform, '', $maxrows);

		if($valid != ""){
			echo "<font color=\"#FF0000\">Could not save new Lease:<br>".$valid."Please correct and Resubmit.<br></font>";
		} else {
			$sql = "INSERT INTO NONSTORAGE_RATE
						(ROW_NUM,
						CONTRACTID,
						BILL_ORDER,
						DATEMOSTRECENT,
						USERMOSTRECENT,
						CUSTOMERID,
						COMMODITYCODE,
						BNI_SERVICE_CODE,
						RATE,
						UNIT,
						ASSET_CODE,
						ACTIVE_LEASE,
						RATEPRIORITY,
						SCANNEDORUNSCANNED,
						BILL_TYPE,
						FREEFORM_DESCRIPTION)
					(SELECT MAX(ROW_NUM) + 1,
						'".$lease[0]."',
						'".$detail[0]."',
						SYSDATE,
						'".$user."',
						'".$cust[0]."',
						'".$comm[0]."',
						'".$serv[0]."',
						'".$rate[0]."',
						'".$unit[0]."',
						'".$asset[0]."',
						'".$active[0]."',
						'500',
						'UNSCANNED',
						'LEASE',
						'".$freeform[0]."'
					FROM NONSTORAGE_RATE)";
//			echo $sql."<br>";
			$mod = ociparse($bniconn, $sql);
			ociexecute($mod);

			echo "<font color=\"#0000FF\">New Lease Saved.<br></font>";
		}
	}
		


	if($submit == "Save Grid"){
		$cust_select = $HTTP_POST_VARS['cust_select'];
		$active_select = $HTTP_POST_VARS['active_select'];

		$row = $HTTP_POST_VARS['row'];
		$cust = $HTTP_POST_VARS['cust'];
		$lease = $HTTP_POST_VARS['lease'];
		$detail = $HTTP_POST_VARS['detail'];
		$active = $HTTP_POST_VARS['active'];
		$rate = $HTTP_POST_VARS['rate'];
		$unit = $HTTP_POST_VARS['unit'];
		$asset = $HTTP_POST_VARS['asset'];
		$asset = str_replace("'", "`", $asset);
		$asset = str_replace("\\", "", $asset);
		$comm = $HTTP_POST_VARS['comm'];
		$serv = $HTTP_POST_VARS['serv'];
		$freeform = $HTTP_POST_VARS['freeform'];
		$freeform = str_replace("'", "`", $freeform);
		$freeform = str_replace("\\", "", $freeform);
		$delete = $HTTP_POST_VARS['delete'];

		$maxrows = $HTTP_POST_VARS['maxrows'];

		$valid = ValidityCheck($bniconn, $row, $cust, $lease, $detail, $active, $rate, $unit, $asset, $comm, $serv, $freeform, $delete, $maxrows);

		if($valid != ""){
			echo "<font color=\"#FF0000\">Could not save new Lease:<br>".$valid."Please correct and Resubmit.<br></font>";
		} else {
			for($i = 0; $i <= $maxrows; $i++){
				if($delete[$i] == "Yes"){
					$sql = "DELETE FROM NONSTORAGE_RATE
							WHERE ROW_NUM = '".$row[$i]."'";
					$mod = ociparse($bniconn, $sql);
					ociexecute($mod);
				} else {
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM NONSTORAGE_RATE
							WHERE CONTRACTID = '".$lease[$i]."'
								AND BILL_ORDER = '".$detail[$i]."'
								AND CUSTOMERID = '".$cust[$i]."'
								AND COMMODITYCODE = '".$comm[$i]."'
								AND BNI_SERVICE_CODE = '".$serv[$i]."'
								AND RATE = '".$rate[$i]."'
								AND UNIT = '".$unit[$i]."'
								AND ASSET_CODE = '".$asset[$i]."'
								AND ACTIVE_LEASE = '".$active[$i]."'
								AND FREEFORM_DESCRIPTION = '".$freeform[$i]."'
								AND ROW_NUM = '".$row[$i]."'";
//					echo $sql."<br>";
					$short_term_data = ociparse($bniconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);
					if(ociresult($short_term_data, "THE_COUNT") < 1){ // only do this if ANYTHING has changed
						$sql = "UPDATE NONSTORAGE_RATE
								SET CONTRACTID = '".$lease[$i]."',
									BILL_ORDER = '".$detail[$i]."',
									DATEMOSTRECENT = SYSDATE,
									USERMOSTRECENT = '".$user."',
									CUSTOMERID = '".$cust[$i]."',
									COMMODITYCODE = '".$comm[$i]."',
									BNI_SERVICE_CODE = '".$serv[$i]."',
									RATE = '".$rate[$i]."',
									UNIT = '".$unit[$i]."',
									ASSET_CODE = '".$asset[$i]."',
									ACTIVE_LEASE = '".$active[$i]."',
									FREEFORM_DESCRIPTION = '".$freeform[$i]."'
								WHERE ROW_NUM = '".$row[$i]."'";
//						echo $sql."<br>";
						$mod = ociparse($bniconn, $sql);
						ociexecute($mod);
					}
				}
			}

			echo "<font color=\"#0000FF\">Data Grid Updated.<br></font>";
		}
	}
			

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Lease Maintain V2
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="lease_maintain_v2.php" method="post">
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">All criteria are optional.</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer:  </font></td>
		<td><input type="text" name="cust_select" size="20" maxlength="20" value="<? echo $cust_select; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Active:  </font></td>
		<td><input type="radio" name="active_select" value="ALL" <? if($active_select == "ALL" || $active_select == ""){?> checked <?}?>>&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">All</font>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active_select" value="Y" <? if($active_select == "Y"){?> checked <?}?>>&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">Active</font>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active_select" value="N" <? if($active_select == "N"){?> checked <?}?>>&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">In-Active</font>
		</td>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add" action="lease_maintain_v2.php" method="post">
<input type="hidden" name="active_select" value="<? echo $active_select; ?>">
<input type="hidden" name="cust_select" value="<? echo $cust_select; ?>">
	<tr>
		<td colspan="6"><br><hr><br></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana">New Lease Entry.</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customer:</font></td>
		<td width="20%"><input type="text" name="cust[0]" size="6" maxlength="6" value="<? echo $cust_select;?>"></td>
		<td width="10%"><font size="2" face="Verdana">Lease ID:</font></td>
		<td width="20%"><input type="text" name="lease[0]" size="10" maxlength="10" value="">&nbsp;&nbsp;<font size="1" face="Verdana">(Optional)</font></td>
		<td width="10%"><font size="2" face="Verdana">Lease Detail Line:</font></td>
		<td><input type="text" name="detail[0]" size="2" maxlength="2" value=""></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Rate:</font></td>
		<td width="20%"><input type="text" name="rate[0]" size="6" maxlength="6" value=""></td>
		<td width="10%"><font size="2" face="Verdana">Frequency:</font></td>
		<td width="20%"><select name="unit[0]"><option value="MONTH">MONTH</option><option value="MONTH">YEAR</option></select></td>
		<td width="10%"><font size="2" face="Verdana">Active?:</font></td>
		<td><select name="active[0]"><option value="Y">Y</option><option value="N">N</option></select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Commodity:</font></td>
		<td width="20%"><input type="text" name="comm[0]" size="6" maxlength="6" value=""></td>
		<td width="10%"><font size="2" face="Verdana">Service:</font></td>
		<td width="20%"><input type="text" name="serv[0]" size="10" maxlength="10" value=""></td>
		<td width="10%"><font size="2" face="Verdana">Freeform Description:</font></td>
		<td><input type="text" name="freeform[0]" size="30" maxlength="100" value=""></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Asset:</font></td>
		<td width="20%"><input type="text" name="asset[0]" size="6" maxlength="6" value=""></td>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6"><input type="submit" name="submit" value="Add New Entry"></td>
	</tr>
	<tr>
		<td colspan="6"><br><hr><br></td>
	</tr>
</form>
</table>



<form name="edit" action="lease_maintain_v2.php" method="post">
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font>
		<td><font size="2" face="Verdana"><b>Lease ID</b></font>
		<td><font size="2" face="Verdana"><b>Detail Line</b></font>
		<td><font size="2" face="Verdana"><b>Active?</b></font>
		<td><font size="2" face="Verdana"><b>Rate</b></font>
		<td><font size="2" face="Verdana"><b>Period</b></font>
		<td><font size="2" face="Verdana"><b>Asset Code</b></font>
		<td><font size="2" face="Verdana"><b>Commodity</b></font>
		<td><font size="2" face="Verdana"><b>Service Code</b></font>
		<td><font size="2" face="Verdana"><b>Freeform Description</b></font>
		<td><font size="2" face="Verdana"><b>Delete This Row?</b></font>
		<td><font size="2" face="Verdana"><b>Last Changed By</b></font>
		<td><font size="2" face="Verdana"><b>Last Changed On</b></font>
	</tr>
<?
		$cust_select = $HTTP_POST_VARS['cust_select'];
		$active_select = $HTTP_POST_VARS['active_select'];

		if($valid == "" || $maxrows == "none"){ // screen with data from DB

			if($cust_select != ""){
				$more_where_clause = " AND CUSTOMERID = '".$cust_select."'";
			}
			if($active_select != "ALL"){
				$more_where_clause = " AND ACTIVE_LEASE = '".$active_select."'";
			}

			$sql = "SELECT NR.*, TO_CHAR(DATEMOSTRECENT, 'MM/DD/YYYY HH24:MI:SS') DATE_RECENT
					FROM NONSTORAGE_RATE NR
					WHERE SCANNEDORUNSCANNED = 'UNSCANNED'
						AND BILL_TYPE = 'LEASE'
						".$more_where_clause."
					ORDER BY CUSTOMERID, TO_NUMBER(CONTRACTID), BILL_ORDER";
			$rows = ociparse($bniconn, $sql);
			ociexecute($rows);
			if(!ocifetch($rows)){
?>
	<tr>
		<td colspan="13" align="center"><font size="2" face="Verdana">No Lease rates found for selected criteria.</font></td>
	</tr>
<?
			} else {
				$row = -1;
?>
<input type="hidden" name="active_select" value="<? echo $active_select; ?>">
<input type="hidden" name="cust_select" value="<? echo $cust_select; ?>">
<?
				do {
					$row++;
?>
	<tr>
		<td><input type="text" name="cust[<? echo $row; ?>]" size="6" maxlength="6" value="<? echo ociresult($rows, "CUSTOMERID"); ?>"><input type="hidden" name="row[<? echo $row; ?>]" value="<? echo ociresult($rows, "ROW_NUM"); ?>"></td>
		<td><input type="text" name="lease[<? echo $row; ?>]" size="10" maxlength="10" value="<? echo ociresult($rows, "CONTRACTID"); ?>"></td>
		<td><input type="text" name="detail[<? echo $row; ?>]" size="2" maxlength="2" value="<? echo ociresult($rows, "BILL_ORDER"); ?>"></td>
		<td><select name="active[<? echo $row; ?>]"><option value="Y">Y</option><option value="N"<? if(ociresult($rows, "ACTIVE_LEASE") == "N"){?> selected <?}?>>N</option></select></td>
		<td><input type="text" name="rate[<? echo $row; ?>]" size="9" maxlength="9" value="<? echo ociresult($rows, "RATE"); ?>"></td>
		<td><select name="unit[<? echo $row; ?>]"><option value="MONTH">MONTH</option>
													<option value="YEAR" <? if(ociresult($rows, "UNIT") == "YEAR"){?> selected <?}?>>YEAR</option>
						</select></td>
		<td><input type="text" name="asset[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($rows, "ASSET_CODE"); ?>"></td>
		<td><input type="text" name="comm[<? echo $row; ?>]" size="4" maxlength="4" value="<? echo ociresult($rows, "COMMODITYCODE"); ?>"></td>
		<td><input type="text" name="serv[<? echo $row; ?>]" size="5" maxlength="5" value="<? echo ociresult($rows, "BNI_SERVICE_CODE"); ?>"></td>
		<td><input type="text" name="freeform[<? echo $row; ?>]" size="40" maxlength="100" value="<? echo ociresult($rows, "FREEFORM_DESCRIPTION"); ?>"></td>
		<td><input type="checkbox" name="delete[<? echo $row; ?>]" value="Yes"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "USERMOSTRECENT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "DATE_RECENT"); ?></font></td>
	</tr>
<?
				} while(ocifetch($rows));
			}
?>
	<tr>
		<td colspan="13" align="center"><input type="submit" name="submit" value="Save Grid"></td>
	</tr>
<input name="maxrows" type="hidden" value="<? echo $row; ?>">
<?
		} else { // an ineffective editbox save was processed, refresh previous data
?>
<input type="hidden" name="active_select" value="<? echo $active_select; ?>">
<input type="hidden" name="cust_select" value="<? echo $cust_select; ?>">
<?

			for($i = 0; $i <= $maxrows; $i++){
?>
	<tr>
		<td><input type="text" name="cust[<? echo $i; ?>]" size="6" maxlength="6" value="<? echo $cust[$i]; ?>"><input type="hidden" name="row[<? echo $i; ?>]" value="<? echo $row[$i]; ?>"></td>
		<td><input type="text" name="lease[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $lease[$i]; ?>"></td>
		<td><input type="text" name="detail[<? echo $i; ?>]" size="2" maxlength="2" value="<? echo $detail[$i]; ?>"></td>
		<td><select name="active[<? echo $i; ?>]"><option value="Y">Y</option><option value="N"<? if($active[$i] == "N"){?> selected <?}?>>N</option></select></td>
		<td><input type="text" name="rate[<? echo $i; ?>]" size="9" maxlength="9" value="<? echo $rate[$i]; ?>"></td>
		<td><select name="unit[<? echo $i; ?>]"><option value="MONTH">MONTH</option>
													<option value="YEAR" <? if($unit[$i] == "YEAR"){?> selected <?}?>>YEAR</option>
						</select></td>
		<td><input type="text" name="asset[<? echo $i; ?>]" size="4" maxlength="4" value="<? echo $asset[$i]; ?>"></td>
		<td><input type="text" name="comm[<? echo $i; ?>]" size="4" maxlength="4" value="<? echo $comm[$i]; ?>"></td>
		<td><input type="text" name="serv[<? echo $i; ?>]" size="5" maxlength="5" value="<? echo $serv[$i]; ?>"></td>
		<td><input type="text" name="freeform[<? echo $i; ?>]" size="40" maxlength="100" value="<? echo $freeform[$i]; ?>"></td>
		<td><input type="checkbox" name="delete[<? echo $i; ?>]" value="Yes" <? if($delete[$i] == "Yes"){?> checked <?}?>></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
<?
			}
?>
	<tr>
		<td colspan="13" align="center"><input type="submit" name="submit" value="Save Grid"></td>
	</tr>
<input name="maxrows" type="hidden" value="<? echo ($i - 1); ?>">
<?
		}
?>
</table>
</form>
<?
	}
	include("pow_footer.php");













function ValidityCheck($bniconn, $row, $cust, $lease, $detail, $active, $rate, $unit, $asset, $comm, $serv, $freeform, $is_delete, $maxrows){
	$return = "";

	if($maxrows == "none"){
		$maxrows = 0;
		$type = "new";
		$extra_error = " (On New Entry)";
	} else {
		$type = "table";
		$extra_error = " (On Line XXX)";
	}

	for($i = 0; $i <= $maxrows; $i++){
		//character-based checks
		if($lease[$i] == ""){
			$return .= "Lease ID is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($lease[$i])){
			$return .= "Lease ID must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif($lease[$i] <= 0 || $lease[$i] != round($lease[$i])){
			$return .= "Lease ID must be greater than 0 and be a whole number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}

		if($cust[$i] == ""){
			$return .= "Customer ID is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($cust[$i])){
			$return .= "Customer ID must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}

		if($detail[$i] == ""){
			$return .= "Detail ID is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($detail[$i])){
			$return .= "Detail ID must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif($detail[$i] <= 0 || $detail[$i] != round($detail[$i])){
			$return .= "Detail ID must be greater than 0 and be a whole number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}

		if($rate[$i] == ""){
			$return .= "Rate is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($rate[$i])){
			$return .= "Rate must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif($rate[$i] <= 0 || $rate[$i] != round($rate[$i], 2)){
			$return .= "Rate must be greater than 0 and be no more than 2 decimal places".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}

		if($asset[$i] == ""){
			$return .= "Asset Code is required".str_replace("XXX", $maxrows, $extra_error).".<br>";
		}

		if($comm[$i] == ""){
			$return .= "Commodity Code is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($comm[$i])){
			$return .= "Commodity Code must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif($comm[$i] < 0 || $comm[$i] != round($comm[$i])){
			$return .= "Commodity Code must be greater than or equal to 0 and be a whole number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}

		if($serv[$i] == ""){
			$return .= "Service Code is required".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif(!is_numeric($serv[$i])){
			$return .= "Service Code must be a number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		} elseif($serv[$i] < 0 || $serv[$i] != round($serv[$i])){
			$return .= "Service Code must be greater than or equal to 0 and be a whole number".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}



		// referential checks
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM COMMODITY_PROFILE
				WHERE COMMODITY_CODE = '".$comm[$i]."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$return .= "Commodity Code ".$comm[$i]." not recognized in system".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM SERVICE_CATEGORY
				WHERE SERVICE_CODE = '".$serv[$i]."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$return .= "Service Code ".$serv[$i]." not recognized in system".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ASSET_PROFILE
				WHERE ASSET_CODE = '".$asset[$i]."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$return .= "Asset Code ".$asset[$i]." not recognized in system".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID = '".$cust[$i]."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$return .= "Customer Code ".$cust[$i]." not recognized in system".str_replace("XXX", ($i + 1), $extra_error).".<br>";
		}



		// consistency checks.
/*		for($j = 0; $j < $maxrows; $j++){ 
			if($j != $i) { // we dont care about the same line
				if($lease[$i] == $lease[$j] && $detail[$i] == $detail[$j] && $is_delete[$i] != "Yes" && $is_delete[$j] != "Yes"){ // we also dont care if either line will end up being deleted
					$return .= "Line ".str_replace("XXX", ($i + 1), $extra_error)." is trying to duplicate an existing row (".($j + 1).")<br>";
				}
			}
		}*/
		// make sure each lease-detail is unique
		if($type == "new"){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM NONSTORAGE_RATE
					WHERE CONTRACTID = '".$lease[$i]."'
						AND BILL_ORDER = '".$detail[$i]."'
						AND ACTIVE_LEASE = 'Y'";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){
				$return .= "Line ".str_replace("XXX", ($i + 1), $extra_error).", Lease/Detail number ".$lease[$i]."/".$detail[$i].", is trying to duplicate an existing entry.  Please delete, or render inactive, the entry for ".$lease[$i]."/.".$detail[$i]." first.<br>";
			}
		} else {
			for($j = 0; $j < $maxrows; $j++){ 
				if($j != $i) { // we dont care about the same line
					if($lease[$i] == $lease[$j] && $detail[$i] == $detail[$j] && $is_delete[$i] != "Yes" && $is_delete[$j] != "Yes"){ // we also dont care if either line will end up being deleted
						$return .= "Line ".str_replace("XXX", ($i + 1), $extra_error)." is trying to duplicate an existing row (".($j + 1).")<br>";
					}
				}
			}
		}


		// make sure a contract doesnt split to multiple customers
		if($type == "new"){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM NONSTORAGE_RATE
					WHERE CONTRACTID = '".$lease[$i]."'
						AND ACTIVE_LEASE = 'Y'
						AND CUSTOMERID != '".$cust[$i]."'";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){
				$return .= str_replace("XXX", ($i + 1), $extra_error).", Attempted to add a line to a contract with a customer that doesn't match the current, active contract.<br>";
			}
		} else { // they edited the grid wrong...
			for($j = 0; $j <= $maxrows; $j++){
				if($j != $i){ // of any line OTHER than the one being checked...
					if($lease[$i] == $lease[$j] && $cust[$i] != $cust[$j] && $is_delete[$i] != "Yes" && $is_delete[$j] != "Yes"){
						$return .= str_replace("XXX", ($i + 1), $extra_error).", Lease/Detail number ".$lease[$i]."/".$detail[$i].", is trying to be saved to customer ".$cust[$i].", which mismatches the customer for this contract on line ".($j + 1).".<br>";
					}
				}
			}
		}



		if($detail[$i] > 1){ // if a detail line >1, we want to make sure there exists a detail line for the SAME LEASE-ID with detail# -1
			$check = false;

			for($j = 0; $j <= $maxrows; $j++){
				if($lease[$i] == $lease[$j] && ($detail[$i] - 1) == $detail[$j] && $is_delete[$i] != "Yes" && $is_delete[$j] != "Yes"){
					$check = true;
				}
			}
			if($type == "new"){
				// if this is a new entry, we need to check the DB instead
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM NONSTORAGE_RATE
						WHERE CONTRACTID = '".$lease[$i]."'
							AND BILL_ORDER = '".($detail[$i] - 1)."'
							AND ACTIVE_LEASE = 'Y'";
				$short_term_data = ociparse($bniconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$check = true;
				}
			}

			if($check == false){
				$return .= str_replace("XXX", ($i + 1), $extra_error).", Lease/Detail number ".$lease[$i]."/".$detail[$i].", does not have an active entry of ".$lease[$i]."/".($detail[$i] - 1)." to follow.<br>";
			}
		}

		if($is_delete[$i] == "Yes"){ // this can only happen in the "grid" method

			if($detail[$i] == "1"){ // for the "primary" bill, make sure there are NO other detail lines for this lease
				for($j = 0; $j <= $maxrows; $j++){
					if($j != $i){ // of any line OTHER than the one being checked...
						if($lease[$i] == $lease[$j] && $is_delete[$j] != "Yes"){ // yup, there was another one.  stop the transaction.
							$return .= str_replace("XXX", ($i + 1), $extra_error).", Lease/Detail number ".$lease[$i]."/".$detail[$i].", has follow-up detail lines.  They need to be delete as well to remove the \"main\" bill.<br>";
						}
					}
				}
			} else {
				for($j = 0; $j <= $maxrows; $j++){
					if($j != $i){ // of any line OTHER than the one being checked...
						if($lease[$i] == $lease[$j] && ($detail[$i] + 1) == $detail[$j]){ // yup, there was another one.  stop the transaction.
							$return .= str_replace("XXX", ($i + 1), $extra_error).", Lease/Detail number ".$lease[$i]."/".$detail[$i].", has a follow-up detail line.  This need to be deleted as well to remove the \"main\" bill.<br>";
						}
					}
				}
			}
		}
	}

	return $return;
}