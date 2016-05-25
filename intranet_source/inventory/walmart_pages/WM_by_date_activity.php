<?
/*
*	Adam Walter, May 2011
*
*	Since 28 (to date) Walmart reports arent enough, here's another
*	One for inventory.
*
*	It gives a "by past date" report of daily walmart activities.
*	This report is VERY specific; if someone changes walmart
*	pallets post-facto (using Pallet Correction, POWNS, or whatever)
*	It can cause this report to go wonky.
*
*	This type of sensitivity is why I hate making "post-facto" reports
*	In the first place, but whatareyagonnado.
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
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$cursor = ora_open($conn);
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$date = $HTTP_POST_VARS['date'];
	if($date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)){
		echo "<font color=\"#FF0000\">Date (".$date.") must be in MM/DD/YYYY format</font><br>";
		$date = "";
	}
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Activity Report</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="WM_by_date_activity.php" method="post">
	<tr>
		<td align="left" width="10%"><font size="2" face="Verdana"><b>Date:</b></td>
		<td align="left"><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($date != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Start-Of-Day</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipped Out</b></font></td>
		<td><font size="2" face="Verdana"><b>Transferred Out</b></font></td>
		<td><font size="2" face="Verdana"><b><span title="To be 'Received', cargo must be both scanned in AND QC-released; whichever of those 2 dates is greater is the 'Receive' Date"><font color="#6633CC">Received</font></span></b></font></td>
		<td><font size="2" face="Verdana"><b><span title="Does not figure into E.O.D. calculation"><font color="#6633CC">Rejected/Held</font></span></b></font></td>
		<td><font size="2" face="Verdana"><b>Returned Cargo</b></font></td>
		<td><font size="2" face="Verdana"><b>Repack-Returned Cargo</b></font></td>
		<td><font size="2" face="Verdana"><b>Other</b></font></td>
		<td><font size="2" face="Verdana"><b>E.O.D. Amount</b></font></td>
	</tr>
<?
		$sql = "SELECT * FROM WM_ITEM_COMM_MAP
				ORDER BY ITEM_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$itemnum = $row['ITEM_NUM'];
			$itemname = $row['WM_COMMODITY_NAME'];

			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLT, SUM(QTY_RECEIVED - OUTBOUND_TOTAL) THE_CTN
					FROM
						(SELECT PALLET_ID, 
								QTY_RECEIVED, NVL(
												 (SELECT SUM(DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE)) 
												 FROM CARGO_ACTIVITY CA
												 WHERE CT.PALLET_ID = CA.PALLET_ID
													   AND CT.RECEIVER_ID = CA.CUSTOMER_ID
													   AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
													   AND SERVICE_CODE NOT IN (1, 2, 8, 12, 14, 16, 18, 19, 21, 22, 23)
													   AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
													   AND ACTIVITY_NUM != '1'
													   AND DATE_OF_ACTIVITY <= TO_DATE('".$date."', 'MM/DD/YYYY')
												)
											  , 0) OUTBOUND_TOTAL
						FROM CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR
						WHERE RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
						AND CT.BOL = '".$itemnum."'
						AND GREATEST(DATE_RECEIVED, RELEASE_TIME) <= TO_DATE('".$date."', 'MM/DD/YYYY')
						)
					WHERE (QTY_RECEIVED - OUTBOUND_TOTAL) > 0";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$SOD_plt = (0 + $Short_Term_Row['THE_PLT']);
			$SOD_ctn = (0 + $Short_Term_Row['THE_CTN']);
/*
			$sql = "SELECT WM_COMMODITY_NAME FROM WM_ITEM_COMM_MAP
					WHERE ITEM_NUM = '".$itemnum."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
*/
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_CTN
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE = '6'";
//			echo $sql."<br>";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$out_plt = (0 + $Short_Term_Row['THE_PLT']);
			$out_ctn = (0 + $Short_Term_Row['THE_CTN']);

			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_CTN
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE = '11'
						AND ACTIVITY_NUM != '1'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$trans_plt = (0 + $Short_Term_Row['THE_PLT']);
			$trans_ctn = (0 + $Short_Term_Row['THE_CTN']);

			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_CTN
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, WDI_VESSEL_RELEASE WVR
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND ACTIVITY_NUM = '1'
						AND SERVICE_CODE IN ('1', '8')
						AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
						AND DATE_RECEIVED IS NOT NULL
						AND TO_CHAR(GREATEST(DATE_RECEIVED, RELEASE_TIME), 'MM/DD/YYYY') = '".$date."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$in_plt = (0 + $Short_Term_Row['THE_PLT']);
			$in_ctn = (0 + $Short_Term_Row['THE_CTN']);

//, SUM(QTY_CHANGE) THE_CTN
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE IN ('18', '21', '22')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$held_plt = (0 + $Short_Term_Row['THE_PLT']);
//			$held_ctn = (0 + $Short_Term_Row['THE_CTN']);

			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_CTN
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE IN ('7', '13')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$ret_plt = (0 + $Short_Term_Row['THE_PLT']);
			$ret_ctn = (-1 * (0 + $Short_Term_Row['THE_CTN']));

			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_CTN
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.RECEIVER_ID = '3000'
						AND CT.BOL = '".$itemnum."'
						AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE = '20'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$rep_plt = (0 + $Short_Term_Row['THE_PLT']);
			$rep_ctn = (-1 * (0 + $Short_Term_Row['THE_CTN']));

			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLT, SUM(QTY_RECEIVED - OUTBOUND_TOTAL) THE_CTN
					FROM
						(SELECT PALLET_ID, 
								QTY_RECEIVED, NVL(
												 (SELECT SUM(DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE)) 
												 FROM CARGO_ACTIVITY CA
												 WHERE CT.PALLET_ID = CA.PALLET_ID
													   AND CT.RECEIVER_ID = CA.CUSTOMER_ID
													   AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
													   AND SERVICE_CODE NOT IN (1, 2, 8, 12, 14, 16, 18, 19, 21, 22, 23)
													   AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
													   AND ACTIVITY_NUM != '1'
													   AND DATE_OF_ACTIVITY <= TO_DATE('".$date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
												)
											  , 0) OUTBOUND_TOTAL
						FROM CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR
						WHERE RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
						AND CT.BOL = '".$itemnum."'
						AND GREATEST(DATE_RECEIVED, RELEASE_TIME) <= (TO_DATE('".$date."', 'MM/DD/YYYY') + 1)
						)
					WHERE (QTY_RECEIVED - OUTBOUND_TOTAL) > 0";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$EOD_plt = (0 + $Short_Term_Row['THE_PLT']);
			$EOD_ctn = (0 + $Short_Term_Row['THE_CTN']);

//			$plt_calc = ($SOD_plt - $out_plt - $trans_plt + $in_plt + $ret_plt + $rep_plt);
			$ctn_calc = ($SOD_ctn - $out_ctn - $trans_ctn + $in_ctn + $ret_ctn + $rep_ctn);

//			$other_plt = $EOD_plt - $plt_calc;
			$other_ctn = (0 + $EOD_ctn - $ctn_calc);

			// we have our data.  If we need to print the line, do so.
			if($SOD_ctn != 0 || $out_ctn != 0 || $trans_ctn != 0 || $in_ctn != 0 || $ret_ctn != 0 || $rep_ctn != 0 || $EOD_ctn != 0){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $itemnum; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $itemname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $SOD_ctn." ctns<br>(".$SOD_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $out_ctn." ctns<br>(".$out_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $trans_ctn." ctns<br>(".$trans_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $in_ctn." ctns<br>(".$in_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo "(".$held_plt." unique plts)"; ?></nobr></font></td>
		<td><font size="2" face="Verdana"><? echo $ret_ctn." ctns<br>(".$ret_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $rep_ctn." ctns<br>(".$rep_plt." unique plts)"; ?></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo $other_ctn." ctns"; ?></nobr></font></td>
		<td><font size="2" face="Verdana"><? echo $EOD_ctn." ctns<br>(".$EOD_plt." unique plts)"; ?></font></td>
	</tr>
<?
			}
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>