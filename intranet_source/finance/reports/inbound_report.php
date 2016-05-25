<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Delete Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied and (($user <> "fvignuli") and ($user <> "ddonofrio"))){
    printf("Access Denied from Finance system");
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

	$comm = $HTTP_POST_VARS['comm'];
	$cust = $HTTP_POST_VARS['cust'];
	$start = $HTTP_POST_VARS['start'];
	$end = $HTTP_POST_VARS['end'];
	$type = $HTTP_POST_VARS['type'];
	$submit = $HTTP_POST_VARS['submit'];

	if($start == ""){
		$start = date('m/d/Y');
	}
	if($end == ""){
		$end = date('m/d/Y');
	}




?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">RF Inbound Report</font>
         <hr><? //include("../bni_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="inbound_report.php" method="post">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Dates:</b></font>
	</tr>
	<tr>
		<td width="5%">&nbsp;&nbsp;&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Starts on or after:</font></td>
		<td align="left"><input type="text" name="start" value="<? echo $start; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><font size="2" face="Verdana">Ends no later than:</font></td>
		<td><input type="text" name="end" value="<? echo $end; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Commodity:</font></td>
		<td align="left"><select name="comm"><option value="">All</option>
<?
//				WHERE CUSTOMER_STATUS = 'ACTIVE' AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE') 
		$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME 
				FROM COMMODITY_PROFILE
					WHERE COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM CARGO_TRACKING)
				ORDER BY COMMODITY_CODE ASC";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			if(strpos(ociresult($stid, "COMMODITY_NAME"), "-") !== false){
				$commcodedisp = "";
			} else {
				$commcodedisp = ociresult($stid, "COMMODITY_CODE")."-";
			}
?>
						<option value="<? echo ociresult($stid, "COMMODITY_CODE"); ?>"<? if(ociresult($stid, "COMMODITY_CODE") == $comm){ ?> selected <? } ?>>
									<? echo $commcodedisp.ociresult($stid, "COMMODITY_NAME") ?></option>
<?
		}
?>
												<select></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Type:</font></td>
		<td align="left"><select name="type"><option value="">All</option>
					<option value="T"<? if($type == "T"){?> selected <?}?>>Truck-In</option>
					<option value="S"<? if($type == "S"){?> selected <?}?>>Ship</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Customer:</font></td>
		<td align="left"><select name="cust"><option value="">All</option>
<?
//				WHERE CUSTOMER_STATUS = 'ACTIVE' AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE') 
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE
					WHERE CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CARGO_TRACKING)
					AND CUSTOMER_ID != '453'
				ORDER BY CUSTOMER_ID ASC";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>"<? if(ociresult($stid, "CUSTOMER_ID") == $cust){ ?> selected <? } ?>><? echo ociresult($stid, "CUSTOMER_NAME") ?></option>
<?
		}
?>
												<select></font></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
		$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_RECDATE, CT.RECEIVER_ID, CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCKED-IN') THE_VES, 
						CT.COMMODITY_CODE, COMMODITY_NAME,  COUNT(*) THE_PLTS
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
				WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CT.RECEIVER_ID != '453'";
		if($comm != ""){
			$sql .= " AND CT.COMMODITY_CODE = '".$comm."'";
		}
		if($cust != ""){
			$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
		}
		if($type != ""){
			$sql .= " AND CT.RECEIVING_TYPE = '".$type."'";
		}
		if($start != ""){
			$sql .= " AND CT.DATE_RECEIVED >= TO_DATE('".$start."', 'MM/DD/YYYY')";
		}
		if($end != ""){
			$sql .= " AND CT.DATE_RECEIVED < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
		}

		$sql .= " GROUP BY TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), CT.RECEIVER_ID, CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCKED-IN'), CT.COMMODITY_CODE, COMMODITY_NAME
				ORDER BY TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY'), CT.COMMODITY_CODE, CT.RECEIVER_ID, CT.ARRIVAL_NUM";
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center" colspan="6"><font size="3" face="Verdana"><b>Search Results</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Order/Arrival</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Tonnage</b></font></td>
	</tr>
<?
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td align="center" colspan="6"><font size="3" face="Verdana">No Inbound loads match entered criteria.</font></td>
	</tr>
<?
		} else {
			$total_orders = 0;
			$total_plts = 0;
			$total_wt = 0;
			do {
				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				} else {
					$bgcolor = "#FFFFFF";
				}
				if(strpos(ociresult($stid, "COMMODITY_NAME"), "-") !== false){
					$commcodedisp = "";
				} else {
					$commcodedisp = ociresult($stid, "COMMODITY_CODE")."-";
				}

				$wt = GetWt(ociresult($stid, "THE_RECDATE"), ociresult($stid, "COMMODITY_CODE"), ociresult($stid, "RECEIVER_ID"), ociresult($stid, "ARRIVAL_NUM"), $rfconn);
				if(is_numeric($wt)){
					$wt = number_format($wt, 2);
					$total_wt += $wt;
				}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_RECDATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $commcodedisp.ociresult($stid, "COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMER_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ARRIVAL_NUM")."-".ociresult($stid, "THE_VES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_PLTS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $wt; ?></font></td>
	</tr>
<?
					$total_orders++;
					$total_plts += ociresult($stid, "THE_PLTS");

					if(is_numeric($wt)){
						$total_wt += $wt;
					}
				} while(ocifetch($stid));
		}
?>
	<tr bgcolor="#DDEEDD">
		<td><font size="2" face="Verdana"><b>Totals:</b></font></td>
<!--		<td colspan="2">&nbsp;</td> !-->
		<td colspan="3"><font size="2" face="Verdana"><? echo $total_orders; ?> Lines</font></td>
		<td><font size="2" face="Verdana"><? echo $total_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_wt; ?></font></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");







function GetWt($rec_date, $comm, $cust, $arv, $rfconn){
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CT.COMMODITY_CODE = '".$comm."'
				AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$rec_date."'
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = '".$arv."'
				AND CT.WEIGHT_UNIT IS NULL
				AND CP.TONS_PER_PLT IS NULL";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		return "Weight Unavailable";
	}



	$sql = "SELECT COMMODITY_TYPE
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_CODE = '".$comm."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$type = ociresult($stid, "COMMODITY_TYPE");

	if($type == "CLEMENTINES"){
		$select_clause = "WEIGHT * QTY_RECEIVED * CONVERSION_FACTOR";
	} else {
		$select_clause = "DECODE(WEIGHT_UNIT, NULL, TONS_PER_PLT, (WEIGHT * CONVERSION_FACTOR))";
	}


	$sql = "SELECT SUM(".$select_clause.") THE_SUM
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, UNIT_CONVERSION_FROM_BNI UCFB
			WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CT.WEIGHT_UNIT = UCFB.PRIMARY_UOM(+)
				AND UCFB.SECONDARY_UOM(+) = 'TON'
				AND CT.COMMODITY_CODE = '".$comm."'
				AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$rec_date."'
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = '".$arv."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	return ociresult($stid, "THE_SUM");
}