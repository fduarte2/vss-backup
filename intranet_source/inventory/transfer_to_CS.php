<?
/* Adam Walter, Feb 2007
*
*	This page is for Ben, used to list (by vessel and customer)
*	Service code 16 pallets from Cargo_Activity in RF
*********************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Transfer to Cold Storage";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
 /* if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
*/
   // Connect to RF
   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_conn));
      exit;
   }
   $cursor = ora_open($ora_conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$pallet_number = 0;

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>
			<font size="5" face="Verdana" color="#0066CC">Transfer to Cold Storage
			<hr>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="input" action="transfer_to_CS.php" method="post">
	<tr>
		<td width="50%" align="center"><font size="3" face="Verdana">Customer:<BR></font><select name="cust"><option value="">Select Customer</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0) ORDER BY CUSTOMER_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust) {?> selected <? } ?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>				
			</select></td>
		<td width="50%" align="center"><font size="3" face="Verdana">Vessel:<BR></font><select name="vessel"><option value="">Select Vessel</option>
<?
		$sql = "SELECT LR_NUM THE_LR, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) IN (SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL) ORDER BY THE_VESSEL DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['THE_LR']; ?>"<? if($row['THE_LR'] == $vessel) {?> selected <? } ?>><? echo $row['THE_VESSEL']; ?></option>
<?
		}
?>				
			</select></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><a href="CS_customers_by_vessel.php">List of customers by vessel</a></font><BR><HR></td>
	</tr>
</form>
</table>

<?
	if($cust != "" && $vessel != "") {
		$sql = "SELECT CA.PALLET_ID THE_PALLET, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_DATETIME, QTY_CHANGE, ACTIVITY_DESCRIPTION, VARIETY || ' - ' || CARGO_SIZE CARGO_DESCRIPTION, REMARK FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CA.ARRIVAL_NUM = '".$vessel."' AND CA.CUSTOMER_ID = '".$cust."' AND CA.SERVICE_CODE = '16' AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID ORDER BY THE_PALLET, DATE_OF_ACTIVITY";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#F00000">No Cargo transferred to Cold Storage for this customer/vessel combination.</font></td>
	</tr>
</table>
<?
		} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>#</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Cargo Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Remark</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Date/Time</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>QTY Change</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Activity Description</b></font></td>
	</tr>
<?
			do {
				if($row['THE_PALLET'] != $last_displayed_pallet){
					$pallet_number++;
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $pallet_number; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['REMARK']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATETIME']; ?>&nbsp;</font></td>
<!--		<td><font size="2" face="Verdana"><? echo $row['QTY_CHANGE']; ?>&nbsp;</font></td> !-->
		<td><font size="2" face="Verdana"><? echo $row['ACTIVITY_DESCRIPTION']; ?>&nbsp;</font></td>
	</tr>
<?
					$last_displayed_pallet = $row['THE_PALLET'];
				}
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
</table>
<?
		}
	}



include("pow_footer.php"); ?>