<?
/*
*		Adam Walter, July/Aug 2014.
*
*		Convert voyage/lloyd to our LR nums
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "CLR preload";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CLR system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
	$pagename = "lloyd_to_LR";
	include("page_security.php");
//	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	$security_allowance = PageSecurityCheck($user, $pagename, "");

	$submit = $HTTP_POST_VARS['submit'];
	$filter_lloyd = $HTTP_POST_VARS['filter_lloyd'];
	$filter_voyage = $HTTP_POST_VARS['filter_voyage'];
	$filter_shipname = $HTTP_POST_VARS['filter_shipname'];
	$filter_arrival = $HTTP_POST_VARS['filter_arrival'];
	$filter_ignore = $HTTP_POST_VARS['filter_ignore'];
	$filter_saved = $HTTP_POST_VARS['filter_saved'];

	$extra_sql_in_table = "";
	$extra_sql_still_needed = "";

	if($filter_lloyd != ""){
		$extra_sql_in_table .= " AND LLOYD_NUM = '".$filter_lloyd."'";
		$extra_sql_still_needed .= "AND LLOYD_NUM = '".$filter_lloyd."'";
	}
	if($filter_voyage != ""){
		$extra_sql_in_table .= " AND VOYAGE_NUM = '".$filter_voyage."'";
		$extra_sql_still_needed .= "AND VOYAGE_NUM = '".$filter_voyage."'";
	}
	if($filter_shipname != ""){
		$extra_sql_in_table .= " AND SHIP_NAME = '".$filter_shipname."'";
		$extra_sql_still_needed .= "AND VESNAME = '".$filter_shipname."'";
	}
	if($filter_arrival != ""){
		$extra_sql_in_table .= " AND ARRIVAL_NUM = '".$filter_arrival."'";
		$extra_sql_still_needed .= "AND 1 = 2";
	}
	if($filter_ignore == "ignored"){
		$extra_sql_in_table .= " AND CLR_IGNORE = 'Y'";
		$extra_sql_still_needed .= "AND 1 = 2";
	}
	if($filter_ignore == "accepted"){
		$extra_sql_in_table .= " AND CLR_IGNORE IS NULL";
	}
	if($filter_saved == "saved"){
		$extra_sql_still_needed .= "AND 1 = 2";
	}
	if($filter_saved == "unsaved"){
		$extra_sql_in_table .= " AND 1 = 2";
	}

	if($submit == "Save"){
		$maxrows = $HTTP_POST_VARS['maxrows'];
		$lloyd = $HTTP_POST_VARS['lloyd'];
		$voyage = $HTTP_POST_VARS['voyage'];
		$shipname = $HTTP_POST_VARS['shipname'];
		$LR = $HTTP_POST_VARS['LR'];
		$ignore = $HTTP_POST_VARS['ignore'];
		$ins_upd = $HTTP_POST_VARS['ins_upd'];
		$cur_lr = $HTTP_POST_VARS['cur_lr'];
		$cur_ignore = $HTTP_POST_VARS['cur_ignore'];

		for($i = 0; $i <= $maxrows; $i++){
			if($ins_upd[$i] == "INS"){ // first time this entry gets to the CLR_LLOYD_ARRIVAL_MAP table
				if($LR[$i] != "" || $ignore[$i] != ""){ // if they didn't fill anything in, we don't want to place it in the reference table (would screw up the eventual push to CLR later)
					$sql = "INSERT INTO CLR_LLOYD_ARRIVAL_MAP
								(ARRIVAL_NUM,
								LLOYD_NUM,
								VOYAGE_NUM,
								SHIP_NAME,
								CLR_IGNORE,
								SET_BY,
								SET_ON)
							VALUES
								('".$LR[$i]."',
								'".$lloyd[$i]."',
								'".$voyage[$i]."',
								'".$shipname[$i]."',
								'".$ignore[$i]."',
								'".$user."',
								SYSDATE)";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);

					$sql = "UPDATE VESSEL_PROFILE
							SET LLOYD_NUM = '".$lloyd[$i]."'
							WHERE TO_CHAR(LR_NUM) = '".$LR[$i]."'";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);

/*					$sql = "UPDATE VOYAGE
							SET VOYAGE_NUM = '".$voyage[$i]."'
							WHERE TO_CHAR(LR_NUM) = '".$LR[$i]."'";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod); */
				}
			}

			if($ins_upd[$i] == "UPD"){ // updating a previous entry...
				if($LR[$i] != $cur_lr[$i] || $ignore[$i] != $cur_ignore[$i]){ // if they didn't change anything, we don't want to update it in the reference table (would screw up the audit trail)
					$sql = "UPDATE CLR_LLOYD_ARRIVAL_MAP
							SET IGNORE_FOR_CLR = '".$ignore[$i]."',
								ARRIVAL_NUM = '".$LR[$i]."'
							WHERE LLOYD_NUM = '".$lloyd[$i]."'
								AND VOYAGE_NUM = '".$voyage[$i]."'
								AND SHIP_NAME = '".$shipname[$i]."'";
					$mod = ociparse($rfconn, $sql);
					ociexecute($mod);

/*					// this will be handled at "push" step.
					if($ignore[$i] != ""){ // make sure the EDI's know to be ignored.  Note the lack of a "reverse" of this; even if they undo the check, we do NOT remove it from CLR_AMSEDI_DETAIL_309
						$sql = "UPDATE CLR_AMSEDI_DETAIL_309
								SET IGNORE_FOR_CLR = 'Y'
								WHERE IGNORE_FOR_CLR IS NULL
									AND PUSH_TO_CLR_ON IS NULL
									AND LLOYD_NUM = '".$lloyd[$i]."'
									AND VOYAGE_NUM = '".$voyage[$i]."'
									AND VESNAME = '".$shipname[$i]."'";
						$mod = ociparse($rfconn, $sql);
						ociexecute($mod);
					}
*/
				}
			}
		}
	}



?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Lloyd# Match to PoW Arrival#
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="filter" action="lloyd_to_LR.php" method="post">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>Filtering Criteria:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Lloyd's #:</b></font></td>
		<td><input type="text" name="filter_lloyd" size="20" maxlength="20" value="<? echo $filter_lloyd; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Voyage#:</b></font></td>
		<td><input type="text" name="filter_voyage" size="20" maxlength="20" value="<? echo $filter_voyage; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>EDI Ship Name:</b></font></td>
		<td><input type="text" name="filter_shipname" size="20" maxlength="50" value="<? echo $filter_shipname; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>DSPC Arrival#:</b></font></td>
		<td><input type="text" name="filter_arrival" size="20" maxlength="20" value="<? echo $filter_arrival; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Ignored EDIs:</b></font></td>
		<td><select name="filter_ignore">
							<option value="">Show All</option>
							<option value="ignored"<? if($filter_ignore == "ignored"){?> selected<?}?>>Only Ignored Entries</option>
							<option value="accepted"<? if($filter_ignore == "accepted"){?> selected<?}?>>Only Accepted Entries</option>
				</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Entry Type:</b></font></td>
		<td><select name="filter_saved">
							<option value="">Show All</option>
							<option value="saved"<? if($filter_saved == "saved"){?> selected<?}?>>Prior Entries Only</option>
							<option value="unsaved"<? if($filter_saved == "unsaved"){?> selected<?}?>>New Entries Only</option>
				</select></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Filter"></td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr></td>
	</tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
<form name="yup" action="lloyd_to_LR.php" method="post">
<input type="hidden" name="filter_lloyd" value="<? echo $filter_lloyd; ?>">
<input type="hidden" name="filter_voyage" value="<? echo $filter_voyage; ?>">
<input type="hidden" name="filter_shipname" value="<? echo $filter_shipname; ?>">
<input type="hidden" name="filter_arrival" value="<? echo $filter_arrival; ?>">
<input type="hidden" name="filter_ignore" value="<? echo $filter_ignore; ?>">
<input type="hidden" name="filter_saved" value="<? echo $filter_saved; ?>">
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td colspan="11" align="center"><input type="submit" name="submit" value="Save"></td>
	</tr>
<?
	}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>LLoyd's #</b></font>
		<td><font size="2" face="Verdana"><b>Custom's Voyage#</b></font>
		<td><font size="2" face="Verdana"><b>EDI Ship Name</b></font>
		<td><font size="2" face="Verdana"><b>DSPC Arrival# - Name</b></font>
		<td><font size="2" face="Verdana"><b>Ignore EDIs?</b></font>
		<td><font size="2" face="Verdana"><b>Set By</b></font>
		<td><font size="2" face="Verdana"><b>Set On</b></font>
		<td><font size="2" face="Verdana"><b>Pushed EDI Lines</b></font>
		<td><font size="2" face="Verdana"><b>Ignored EDI Lines</b></font>
		<td><font size="2" face="Verdana"><b>Awaiting Push</b></font>
		<td><font size="2" face="Verdana"><b>Total EDI Lines</b></font>
	</tr>
<?
	// the wierd "NVL-Q" logic in the where clause is because the NOT IN statement acts wierd with NVL data
	$row = -1;

	$sql = "SELECT CLAM.*, TO_CHAR(SET_ON, 'MM/DD/YYYY HH24:MI:SS') THE_SET
			FROM CLR_LLOYD_ARRIVAL_MAP CLAM
				WHERE 1 = 1
				AND LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
				".$extra_sql_in_table."
			ORDER BY LLOYD_NUM, VOYAGE_NUM, SHIP_NAME";
	$data = ociparse($rfconn, $sql);
	ociexecute($data);
	if(!ocifetch($data)){
		// do nothing
	} else {
		do {
			$row++;
?>
<input type="hidden" name="lloyd[<? echo $row; ?>]" value="<? echo ociresult($data, "LLOYD_NUM"); ?>">
<input type="hidden" name="voyage[<? echo $row; ?>]" value="<? echo ociresult($data, "VOYAGE_NUM"); ?>">
<input type="hidden" name="shipname[<? echo $row; ?>]" value="<? echo ociresult($data, "SHIP_NAME"); ?>">
<input type="hidden" name="cur_lr[<? echo $row; ?>]" value="<? echo ociresult($data, "ARRIVAL_NUM"); ?>">
<input type="hidden" name="cur_ignore[<? echo $row; ?>]" value="<? echo ociresult($data, "CLR_IGNORE"); ?>">
<input type="hidden" name="ins_upd[<? echo $row; ?>]" value="UPD">
	<tr>
		<td><font size="2" face="Verdana"><? echo PushLink(ociresult($data, "LLOYD_NUM")); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "VOYAGE_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "SHIP_NAME"); ?></font></td>
		<td><select name="LR[<? echo $row; ?>]"><option value=""></option>
<?
			$sql = "SELECT LR_NUM, VESSEL_NAME
					FROM VESSEL_PROFILE
					WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT', 'ARG JUICE', 'DOLE', 'CLEMENTINES', 'STEEL')
					ORDER BY LR_NUM DESC";
			$vessels = ociparse($rfconn, $sql);
			ociexecute($vessels);
			while(ocifetch($vessels)){
?>
								<option value="<? echo ociresult($vessels, "LR_NUM"); ?>"<? if(ociresult($vessels, "LR_NUM") == ociresult($data, "ARRIVAL_NUM")){?> selected <?}?>>
										<? echo ociresult($vessels, "LR_NUM")." - ".ociresult($vessels, "VESSEL_NAME"); ?></option>
<?
			}
?>
				</select></td>
		<td><input type="checkbox" value="Y" name="ignore[<? echo $row; ?>]"<? if(ociresult($data, "CLR_IGNORE") == "Y"){?> checked <?}?>></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "SET_BY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "THE_SET"); ?></font></td>
		<? DisplayEDIReport(ociresult($data, "LLOYD_NUM"), ociresult($data, "VOYAGE_NUM"), ociresult($data, "SHIP_NAME"), $rfconn); ?>
	</tr>
<?
		} while(ocifetch($data));
	} // this finishes in-table data, now for in-EDI needs

	// the "nvl-Q" logic in here is because oracle is rather touchy with null result sets when using a NOT-IN clause
	$sql = "SELECT DISTINCT LLOYD_NUM, VOYAGE_NUM, VESNAME
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
				AND LLOYD_NUM NOT IN (SELECT NVL(LLOYD_NUM, 'Q') FROM CLR_IGNORE_LLOYD)
				AND (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) NOT IN
					(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
					FROM CLR_LLOYD_ARRIVAL_MAP)
				".$extra_sql_still_needed."
			ORDER BY LLOYD_NUM, VOYAGE_NUM, VESNAME";
	$data = ociparse($rfconn, $sql);
	ociexecute($data);
	if(!ocifetch($data)){
		// do nothing
	} else {
		do {
			$row++;
?>
<input type="hidden" name="lloyd[<? echo $row; ?>]" value="<? echo ociresult($data, "LLOYD_NUM"); ?>">
<input type="hidden" name="voyage[<? echo $row; ?>]" value="<? echo ociresult($data, "VOYAGE_NUM"); ?>">
<input type="hidden" name="shipname[<? echo $row; ?>]" value="<? echo ociresult($data, "VESNAME"); ?>">
<input type="hidden" name="ins_upd[<? echo $row; ?>]" value="INS">
	<tr>
		<td><font size="2" face="Verdana"><? echo PushLink(ociresult($data, "LLOYD_NUM")); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "VOYAGE_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($data, "VESNAME"); ?></font></td>
		<td><select name="LR[<? echo $row; ?>]"><option value=""></option>
<?
			$sql = "SELECT LR_NUM, VESSEL_NAME
					FROM VESSEL_PROFILE
					WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT', 'ARG JUICE', 'DOLE', 'CLEMENTINES', 'STEEL')
					ORDER BY LR_NUM DESC";
			$vessels = ociparse($rfconn, $sql);
			ociexecute($vessels);
			while(ocifetch($vessels)){
?>
								<option value="<? echo ociresult($vessels, "LR_NUM"); ?>"<? if(ociresult($vessels, "LR_NUM") == ociresult($data, "ARRIVAL_NUM")){?> selected <?}?>>
										<? echo ociresult($vessels, "LR_NUM")." - ".ociresult($vessels, "VESSEL_NAME"); ?></option>
<?
			}
?>
				</select></td>
		<td><input type="checkbox" value="Y" name="ignore[<? echo $row; ?>]"<? if(ociresult($data, "CLR_IGNORE") == "Y"){?> checked <?}?>></td>
		<td colspan="2"><font size="2" face="Verdana">Not Yet Set</font></td>
		<? DisplayEDIReport(ociresult($data, "LLOYD_NUM"), ociresult($data, "VOYAGE_NUM"), ociresult($data, "VESNAME"), $rfconn); ?>
	</tr>
<?
		} while(ocifetch($data));
	} // this finishes in-EDI data

	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td colspan="11" align="center"><input type="submit" name="submit" value="Save"></td>
	</tr>
<?
	}
?>
<input name="maxrows" type="hidden" value="<? echo $row; ?>">
</form>	
</table>
<?
	include("pow_footer.php");
	












function DisplayEDIReport($lloyd, $voyage, $shipname, $rfconn){
//	$string = "";

	if($lloyd == ""){
		$lloyd_where = "IS NULL";
	} else {
		$lloyd_where = "= '".$lloyd."'";
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_AMSEDI_DETAIL_309
			WHERE LLOYD_NUM ".$lloyd_where."
				AND VOYAGE_NUM = '".$voyage."'
				AND VESNAME = '".$shipname."'";
	$data = ociparse($rfconn, $sql);
	ociexecute($data);
	ocifetch($data);
	$total = ociresult($data, "THE_COUNT");
//	$string .= "Total EDI Lines: ".$total;

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_AMSEDI_DETAIL_309
			WHERE LLOYD_NUM ".$lloyd_where."
				AND VOYAGE_NUM = '".$voyage."'
				AND VESNAME = '".$shipname."'
				AND PUSH_TO_CLR_ON IS NOT NULL";
	$data = ociparse($rfconn, $sql);
	ociexecute($data);
	ocifetch($data);
	$pushed = ociresult($data, "THE_COUNT");
//	$string .= "<br>Already Pushed to Scoreboard: ".$pushed;

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_AMSEDI_DETAIL_309
			WHERE LLOYD_NUM ".$lloyd_where."
				AND VOYAGE_NUM = '".$voyage."'
				AND VESNAME = '".$shipname."'
				AND IGNORE_FOR_CLR IS NOT NULL";
	$data = ociparse($rfconn, $sql);
	ociexecute($data);
	ocifetch($data);
	$ignored = ociresult($data, "THE_COUNT");
//	$string .= "<br>Lines Set to Ignore (from Other Screen): ".$ignored;

	echo "<td><font size=\"2\" face=\"Verdana\">".$pushed."</td>";
	echo "<td><font size=\"2\" face=\"Verdana\">".$ignored."</td>";
	echo "<td><font size=\"2\" face=\"Verdana\">".($total - ($pushed + $ignored))."</td>";
	echo "<td><font size=\"2\" face=\"Verdana\">".$total."</td>";

//	echo "<td><font size=\"2\" face=\"Verdana\">".$string."</td>";
}

function PushLink($lloyds){
	$return = "<a href=\"push_to_CLR.php?ves_select=".$lloyds."\">".$lloyds."</a>";

	return $return;
}