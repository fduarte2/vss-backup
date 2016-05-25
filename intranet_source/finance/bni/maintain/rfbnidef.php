<?
/*  Adam Walter, June 2007.
*
*  This page allows Antonia to change the rates of items, so that
*  RF - BNI Misc Billing runs smoothly
*  
*  VERY IMPORTANT:
*  I had a grand plan on how to modify this script later to accomodate
*  The possibility that customers, commodities, and service codes
*  could be differentiated... but Inigo has made an executive decision
*  ragerding table layouts that compeltely negates my idea, so I will
*  just make this code very simple to read, so that in the future
*  *IF* I have to modify it, I can come up with something new at that time.
*
*
*	BIG UPDATE March 2013:
*	As of right now, the "multiple commoidity" concept from above is now in action.
*	Also, "multiple service codes" is (at this point in time) redundant
*	So the only future modification to this page, should it ever be necessary,
*	Would be customer-specific... and man do I hope that doesn't come to pass.
*	...
*	Also Antonia has long since retired, but since I'm not big on changing
*	Previous comments, it and the typo of "regarding" shall remain ;p
**************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - MiscBill Rate Edit";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }


	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if (!$conn) {
	  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	  printf("Please report to TS!\n");
	  exit;
	}
	$cursor = ora_open($conn);

	$def_serv = $HTTP_POST_VARS['def_serv'];
	$def_comm = $HTTP_POST_VARS['def_comm'];
	$rate = $HTTP_POST_VARS['rate'];
	$unit = $HTTP_POST_VARS['unit'];
	$rf_service = $HTTP_POST_VARS['rf_service'];
	$submit = $HTTP_POST_VARS['submit'];
	$comm = $HTTP_POST_VARS['comm'];

	if($submit == "Select Commodity"){
		$sql = "SELECT COUNT(*) THE_COUNT FROM RFBNI_MISC_BILL_RATE WHERE BNI_COMMODITY_DEF = '".$comm."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			echo "<font color=\"#FF0000\">Commodity not in MISCBILL system.  If you need it added, please contact TS.</font>";
			$comm = "";
			$submit = "";
		}
	}

	if($submit == "Update"){
		$sql = "UPDATE RFBNI_MISC_BILL_RATE 
				SET RATE = '".$rate."', PER_UNIT = '".$unit."' 
				WHERE RF_SERVICE_CODE = '".$rf_service."'
					AND BNI_COMMODITY_DEF = '".$comm."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		echo "<font color=\"#0000FF\">Update Saved.</font>";
	}






?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">RF/BNI Miscbill Default Rates
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="commchoose" action="rfbnidef.php" method="post">
	<tr>
		<td colspan="3"><font size="2" face="Verdana">Commodity Code: <input type="text" name="comm" value="<? echo $comm; ?>" size="10" maxlength="6"></font></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Select Commodity"><hr><hr></td>
	</tr>
</form>
<?
	if($submit != ""){
		$sql = "SELECT RMBR.RF_SERVICE_CODE, RMBR.BNI_COMMODITY_DEF, RMBR.RATE, RMBR.PER_UNIT, RMBH.BNI_SERVICE_DEF, RMBH.DESCRIPTION 
				FROM RFBNI_MISC_BILL_RATE RMBR, RFBNI_MISC_BILL_HEADINGS RMBH
				WHERE RMBR.RF_SERVICE_CODE = RMBH.RF_SERVICE_CODE
					AND RMBR.BNI_COMMODITY_DEF = '".$comm."'
					AND RMBR.RF_SERVICE_CODE != '11'
				ORDER BY RMBR.RF_SERVICE_CODE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<form name="Update<? echo $row['RF_SERVICE_CODE']; ?>" action="rfbnidef.php" method="post">
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana"><? echo $row['DESCRIPTION']; ?><? if($row['DESCRIPTION'] == "Inbound Loading Fee"){?>  (Except For Dayka-Hackett Peruvian Grapes)<? } ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Service Code: <? echo $row['BNI_SERVICE_DEF']; ?><? if($row['DESCRIPTION'] == "Inbound Loading Fee"){?>  (6227 For All Peruvian Grapes and "OTR Dayka")<? } ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Commodity Code: <? echo $comm; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana">Rate: <input type="text" name="rate" value="<? echo $row['RATE']; ?>"></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana">Unit: <input type="text" name="unit" value="<? echo $row['PER_UNIT']; ?>"></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana"><input type="submit" name="submit" value="Update"></font><hr></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
<input type="hidden" name="rf_service" value="<? echo $row['RF_SERVICE_CODE']; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
</form>
<?
		}
?>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Transfers of Ownership</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Service Code: 6120</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Commodity Code: <? echo $comm; ?></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Transfer Bills use Billing v2 logic.  Rates are stored in a complex table.  Please contact TS if you need them adjusted<br>The current set of transfer-related rates is as follows:</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2">
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td><font size="2" face="Verdana"><b>Bill Entry</b></font></td>
					<td><font size="2" face="Verdana"><b>(UnScanned) Commodity</b></font></td>
					<td><font size="2" face="Verdana"><b>Customer</b></font></td>
					<td><font size="2" face="Verdana"><b>Priority</b></font></td>
					<td><font size="2" face="Verdana"><b>Rate</b></font></td>
					<td><font size="2" face="Verdana"><b>Rate Unit</b></font></td>
				</tr>
<?
		$sql = "SELECT * FROM NONSTORAGE_RATE
				WHERE (BILL_TYPE IS NULL OR BILL_TYPE = 'TRANSFER')
					AND SCANNEDORUNSCANNED = 'SCANNED'
					AND (COMMODITYCODE IS NULL OR COMMODITYCODE = '".$comm."')
				ORDER BY BILL_ORDER, RATEPRIORITY, COMMODITYCODE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo $row['BILL_ORDER']; ?></font></td>
					<td><font size="2" face="Verdana"><? echo $row['COMMODITYCODE']; ?>&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $row['CUSTOMERID']; ?>&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo $row['RATEPRIORITY']; ?></font></td>
					<td><font size="2" face="Verdana">$<? echo number_format($row['RATE'], 2); ?></font></td>
					<td><font size="2" face="Verdana"><? echo $row['UNIT']; ?></font></td>
				</tr>
<?
		}
?>
			</table>
		</td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>





