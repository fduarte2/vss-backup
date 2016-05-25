<?
/* Adam Walter, October 2006
*  This program returns a screen which breaks a vessel down by commodities
*  5603 and 5602, based on Hatch/Deck of a ship that it came from.
**************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine / NOURS display";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);

  $vessel = $HTTP_POST_VARS['vessel'];
  $bgcolor = "#F0F0F0";


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hatch - Deck Vessel Report</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="choose_vessel" action="ClemNours.php" method="post">
	<tr>
		<td colspan="3" align="center"><select name="vessel" onchange="document.choose_vessel.submit(this.form)">
				<option value="">Select a Vessel:</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LENGTH(LR_NUM) < 6 ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['LR_NUM']; ?>"><? echo $row['LR_NUM']; ?> - <? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	</form>
</table>

<?
	if($vessel != ""){
		$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
<table border="1" width="100%" cellpadding="2" cellspacing="0">
	<tr bgcolor="#CC6666">
		<td align="center" colspan="20"><font size="4" face="Verdana"><? echo $row['LR_NUM']; ?> - <? echo $row['VESSEL_NAME']; ?></font></td>
	</tr>
	<tr bgcolor="#66CC00">
		<td width="10%" align="center"><font size="2" face="Verdana">Hatch / Deck</font></td>
		<td width="12%" align="center" colspan="3"><font size="2" face="Verdana">5601 - Salutianas</font></td>
		<td width="12%" align="center" colspan="3"><font size="2" face="Verdana">5602 - Clementine</font></td>
		<td width="12%" align="center" colspan="3"><font size="2" face="Verdana">5603 - Nours</font></td>
		<td width="12%" align="center" colspan="3"><font size="2" face="Verdana">5604 - Naval Oranges</font></td>
		<td width="12%" align="center" colspan="3"><font size="2" face="Verdana">5605 - Moroccan Lemons</font></td>
		<td width="30%" align="center" colspan="4"><font size="2" face="Verdana">Totals</font></td>
	</tr>
	<tr bgcolor="#B0B0F0">
		<td width="10%" align="center"><font size="2" face="Verdana">&nbsp;</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="4%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td width="7%" align="center"><font size="2" face="Verdana">Expected</font></td>
		<td width="7%" align="center"><font size="2" face="Verdana">Received</font></td>
		<td width="7%" align="center"><font size="2" face="Verdana">Not Received</font></td>
		<td align="center"><font size="2" face="Verdana">% received</font></td>
	</tr>
<?
		// wierd logic here, but in essence, we will get a record, palce the row header in the HTML page,
		// and determine if there are 1 or both columns for that record that need to be printed.
		$sql = "SELECT DISTINCT HATCH || DECK HDOUT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE IN ('5601', '5602', '5603', '5604', '5605') ORDER BY HATCH || DECK";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$Total5601Exp = 0;
		$Total5601Rec = 0;
		$Total5601NotRec = 0;
		$Total5602Exp = 0;
		$Total5602Rec = 0;
		$Total5602NotRec = 0;
		$Total5603Exp = 0;
		$Total5603Rec = 0;
		$Total5603NotRec = 0;
		$Total5604Exp = 0;
		$Total5604Rec = 0;
		$Total5604NotRec = 0;
		$Total5605Exp = 0;
		$Total5605Rec = 0;
		$Total5605NotRec = 0;
		$TotalTotalExp = 0;
		$TotalTotalRec = 0;
		$TotalTotalNotRec = 0;

		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$lineExp = 0;
			$lineRec = 0;
			$lineNotRec = 0;

			$hatchDeck = $row['HDOUT'];

			if($bgcolor == "#F0F0F0"){
				$bgcolor = "#FFFFFF";
			} else {
				$bgcolor = "#F0F0F0";
			}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td width="10%"><font size="2" face="Verdana"><? echo $hatchDeck; ?></font></td>
<?
			$sql2 = "SELECT COMMODITY_CODE, SUM(CASE WHEN DATE_RECEIVED IS NULL THEN 1 ELSE 1 END) AS TOTALS, SUM(CASE WHEN DATE_RECEIVED IS NULL THEN 1 ELSE 0 END) AS NOTREC, SUM(CASE WHEN DATE_RECEIVED IS NOT NULL THEN 1 ELSE 0 END) AS REC FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE IN ('5601', '5602', '5603', '5604', '5605') AND HATCH || DECK = '".$hatchDeck."' GROUP BY COMMODITY_CODE ORDER BY COMMODITY_CODE";
			ora_parse($cursor2, $sql2);
			ora_exec($cursor2);
			ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row2['COMMODITY_CODE'] == '5601'){
				$lineExp += $row2['TOTALS'];
				$lineRec += $row2['REC'];
				$lineNotRec += $row2['NOTREC'];
				$Total5601Exp += $row2['TOTALS'];
				$TotalTotalExp += $row2['TOTALS'];
				$Total5601Rec += $row2['REC'];
				$TotalTotalRec += $row2['REC'];
				$Total5601NotRec += $row2['NOTREC'];
				$TotalTotalNotRec += $row2['NOTREC'];
?>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['TOTALS']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC']; ?></font></td>
<?
				ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
?>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
<?
			}

			if($row2['COMMODITY_CODE'] == '5602'){
				$lineExp += $row2['TOTALS'];
				$lineRec += $row2['REC'];
				$lineNotRec += $row2['NOTREC'];
				$Total5602Exp += $row2['TOTALS'];
				$TotalTotalExp += $row2['TOTALS'];
				$Total5602Rec += $row2['REC'];
				$TotalTotalRec += $row2['REC'];
				$Total5602NotRec += $row2['NOTREC'];
				$TotalTotalNotRec += $row2['NOTREC'];
?>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['TOTALS']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC']; ?></font></td>
<?
				ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
?>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
<?
			}

			if($row2['COMMODITY_CODE'] == '5603'){
				$lineExp += $row2['TOTALS'];
				$lineRec += $row2['REC'];
				$lineNotRec += $row2['NOTREC'];
				$Total5603Exp += $row2['TOTALS'];
				$TotalTotalExp += $row2['TOTALS'];
				$Total5603Rec += $row2['REC'];
				$TotalTotalRec += $row2['REC'];
				$Total5603NotRec += $row2['NOTREC'];
				$TotalTotalNotRec += $row2['NOTREC'];
?>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC'] + $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC']; ?></font></td>
<?
				ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
?>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
<?
			}

			if($row2['COMMODITY_CODE'] == '5604'){
				$lineExp += $row2['TOTALS'];
				$lineRec += $row2['REC'];
				$lineNotRec += $row2['NOTREC'];
				$Total5604Exp += $row2['TOTALS'];
				$TotalTotalExp += $row2['TOTALS'];
				$Total5604Rec += $row2['REC'];
				$TotalTotalRec += $row2['REC'];
				$Total5604NotRec += $row2['NOTREC'];
				$TotalTotalNotRec += $row2['NOTREC'];
?>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['TOTALS']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC']; ?></font></td>
<?
			} else {
?>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
<?
			}

			if($row2['COMMODITY_CODE'] == '5605'){
				$lineExp += $row2['TOTALS'];
				$lineRec += $row2['REC'];
				$lineNotRec += $row2['NOTREC'];
				$Total5605Exp += $row2['TOTALS'];
				$TotalTotalExp += $row2['TOTALS'];
				$Total5605Rec += $row2['REC'];
				$TotalTotalRec += $row2['REC'];
				$Total5605NotRec += $row2['NOTREC'];
				$TotalTotalNotRec += $row2['NOTREC'];
?>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC'] + $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['REC']; ?></font></td>
			<td width="4%"><font size="2" face="Verdana"><? echo $row2['NOTREC']; ?></font></td>
<?
				ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
?>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
			<td width="4%"><font size="2" face="Verdana">0</font></td>
<?
			}
?>
			<td width="7%"><font size="2" face="Verdana"><? if($lineExp > 0){ echo $lineExp; } else { echo '0'; }?></font></td>
			<td width="7%"><font size="2" face="Verdana"><? if($lineRec > 0){ echo $lineRec; } else { echo '0'; }?></font></td>
			<td width="7%"><font size="2" face="Verdana"><? if($lineNotRec > 0){ echo $lineNotRec; } else { echo '0'; }?></font></td>
			<td bgcolor="FFFF99"><font size="2" face="Verdana"><? if($lineExp > 0){ echo round($lineRec / $lineExp, 2) * 100; } else { echo '0'; }?></font></td>
	</tr>
<?
		}	
?>
	<tr bgcolor="CC9900">
		<td width="4%"><font size="2" face="Verdana">TOTALS</font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5601Exp > 0){ echo $Total5601Exp; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5601Rec > 0){ echo $Total5601Rec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5601NotRec > 0){ echo $Total5601NotRec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5602Exp > 0){ echo $Total5602Exp; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5602Rec > 0){ echo $Total5602Rec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5602NotRec > 0){ echo $Total5602NotRec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5603Exp > 0){ echo $Total5603Exp; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5603Rec > 0){ echo $Total5603Rec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5603NotRec > 0){ echo $Total5603NotRec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5604Exp > 0){ echo $Total5604Exp; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5604Rec > 0){ echo $Total5604Rec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5604NotRec > 0){ echo $Total5604NotRec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5605Exp > 0){ echo $Total5605Exp; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5605Rec > 0){ echo $Total5605Rec; } else { echo '0'; }?></font></td>
		<td width="4%"><font size="2" face="Verdana"><? if($Total5605NotRec > 0){ echo $Total5605NotRec; } else { echo '0'; }?></font></td>
		<td width="7%"><font size="2" face="Verdana"><? if($TotalTotalExp > 0){ echo $TotalTotalExp; } else { echo '0'; }?></font></td>
		<td width="7%"><font size="2" face="Verdana"><? if($TotalTotalRec > 0){ echo $TotalTotalRec; } else { echo '0'; }?></font></td>
		<td width="7%"><font size="2" face="Verdana"><? if($TotalTotalNotRec > 0){ echo $TotalTotalNotRec; } else { echo '0'; }?></font></td>
		<td><font size="2" face="Verdana"><? if($TotalTotalExp > 0){ echo round($TotalTotalRec / $TotalTotalExp, 2) * 100;} else { echo '0'; }?></font></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>