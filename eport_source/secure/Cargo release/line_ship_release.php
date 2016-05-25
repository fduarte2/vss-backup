<?
/*
*		Adam Walter, Oct 2014.
*
*		Line Release for a whole vessel at a time.
*********************************************************************************/


  
	$pagename = "line_release_ship";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}
	$shipline_sql = ShipLineV2UserCheck($user, "SHIPLINE", $rfconn);

	$vessel = $HTTP_GET_VARS['vessel'];
	if($vessel == ""){
		$vessel = $HTTP_POST_VARS['vessel'];
	}
	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Set Line Release Time and Save Comments For ENTIRE Vessel"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
//		$key = $HTTP_POST_VARS['key'];
		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\\", "", $comments);

		if($comments == ""){
			$comments = "None (Full Vessel Release)";
		}

		$sql = "INSERT INTO CLR_LINE_RELEASE
					(USERNAME,
					DATE_TIME,
					COMMENTS,
					ACTION_TYPE,
					CLR_KEY)
				(SELECT '".$user."', SYSDATE, '".$comments."', 'RELEASE SET (Ship) (Manual)', CLR_KEY
					FROM CLR_MAIN_DATA
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND LINE_RELEASE IS NULL)";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

		$sql = "UPDATE CLR_MAIN_DATA
				SET LINE_RELEASE = SYSDATE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND LINE_RELEASE IS NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

		echo "<font color=\"0000FF\">Line release set for Vessel ".$vessel.".</font></br>";
	}

	if($submit == "Set Releases"){
		$bol_set = $HTTP_POST_VARS['bol_set'];
		$cont_set = $HTTP_POST_VARS['cont_set'];
		$rel_set = $HTTP_POST_VARS['rel_set'];

		$i = 0;
		$saved = 0;
		while($cont_set[$i] != ""){
			if($rel_set[$i] == "save"){
				$sql = "INSERT INTO CLR_LINE_RELEASE
							(USERNAME,
							DATE_TIME,
							COMMENTS,
							ACTION_TYPE,
							CLR_KEY)
						(SELECT '".$user."', SYSDATE, '', 'RELEASE SET (Ship) (Manual)', CLR_KEY
							FROM CLR_MAIN_DATA
							WHERE ARRIVAL_NUM = '".$vessel."'
								AND BOL_EQUIV = '".$bol_set[$i]."'
								AND CONTAINER_NUM = '".$cont_set[$i]."'
								AND LINE_RELEASE IS NULL)";
				$upd_data = ociparse($rfconn, $sql);
				ociexecute($upd_data);

				$sql = "UPDATE CLR_MAIN_DATA
						SET LINE_RELEASE = SYSDATE
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND BOL_EQUIV = '".$bol_set[$i]."'
							AND CONTAINER_NUM = '".$cont_set[$i]."'
							AND LINE_RELEASE IS NULL";
				$upd_data = ociparse($rfconn, $sql);
				ociexecute($upd_data);

				$saved++;
			}
			$i++;
		}

		echo "<font color=\"0000FF\">".$saved." releases set for Vessel ".$vessel.".</font></br>";
	}

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Batch Line-Release
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<!--<table border="0" cellpadding="4" cellspacing="0">
<form name="ID_select" action="line_ship_release_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>Line-Release For Entire Vessel</font></td></td>
	</tr>
<?
	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".$vessel."'";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	ocifetch($ves_data);
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $vessel." - ".ociresult($ves_data, "VESSEL_NAME"); ?></font></td>
	</tr>
<?
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND LINE_RELEASE IS NULL";
	$rel_data = ociparse($rfconn, $sql);
	ociexecute($rel_data);
	ocifetch($rel_data);
?>
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Cargo Entries Needing Release:</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rel_data, "THE_COUNT"); ?></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left"><input type="text" name="comments" size="50" maxlength="50"></td>
	</tr>

<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Set Line Release Time and Save Comments For ENTIRE Vessel"><BR></td>
	</tr>
<?
	}
?>
</form>
</table> !-->
<table border="0" cellpadding="4" cellspacing="0" width="100%">
<form name="detail_retrieve" action="line_ship_release_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<!--	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>Line-Release For Selected Entries</font></td></td>
	</tr> !-->
<?
	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".$vessel."'";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	ocifetch($ves_data);
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $vessel." - ".ociresult($ves_data, "VESSEL_NAME"); ?></font></td>
	</tr>
	<tr>
		<td width="10%">BoL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><select name="bol"><option value="All">All</option>
<?
	$sql = "SELECT DISTINCT BOL_EQUIV
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND LINE_RELEASE IS NULL
				".$shipline_sql."
			ORDER BY BOL_EQUIV";
	$bol_data = ociparse($rfconn, $sql);
	ociexecute($bol_data);
	while(ocifetch($bol_data)){
?>
							<option value="<? echo ociresult($bol_data, "BOL_EQUIV"); ?>"<? if(ociresult($bol_data, "BOL_EQUIV") == $bol){?> selected <?}?>><? echo ociresult($bol_data, "BOL_EQUIV"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td>Container:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><select name="cont"><option value="All">All</option>
<?
	$sql = "SELECT DISTINCT CONTAINER_NUM
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND LINE_RELEASE IS NULL
				".$shipline_sql."
			ORDER BY CONTAINER_NUM";
	$cont_data = ociparse($rfconn, $sql);
	ociexecute($cont_data);
	while(ocifetch($cont_data)){
?>
							<option value="<? echo ociresult($cont_data, "CONTAINER_NUM"); ?>""<? if(ociresult($cont_data, "CONTAINER_NUM") == $cont){?> selected <?}?>><? echo ociresult($cont_data, "CONTAINER_NUM"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td>Sort By:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><select name="order"><option value="Container">Container</option>
								<option value="BoL"<? if($order == "BoL"){?> Selected <?}?>>BoL</option>
				</select></td>
	</tr>
	<tr>
		<td>Pre-Check All Rows?</td>
		<td><input type="radio" name="pre_check" value="no"<? if($pre_check == "no" || $pre_check == ""){?> checked <?}?>>&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="pre_check" value="yes"<? if($pre_check == "yes"){?> checked <?}?>>&nbsp;Yes</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Non-Released Cargo"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Non-Released Cargo"){
		$submit = $HTTP_POST_VARS['submit'];
		$bol = $HTTP_POST_VARS['bol'];
		$cont = $HTTP_POST_VARS['cont'];
		$pre_check = $HTTP_POST_VARS['pre_check'];
		$order = $HTTP_POST_VARS['order'];
?>
<form name="partial_save" action="line_ship_release_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="order" value="<? echo $order; ?>">
<input type="hidden" name="pre_check" value="<? echo $pre_check; ?>">
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="10" align="center"><font size="3" face="Verdana"><b>BoL:&nbsp;&nbsp;<? echo $bol; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Container:&nbsp;&nbsp;<? echo $cont; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Ship Line</b></font></td>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Consignee</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Mode</b></font></td>
		<td><font size="2" face="Verdana"><b>Load Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Release?</b></font></td>
	</tr>
<?
		$rownum = -1;
//		$bol_set = array();
//		$cont_set = array();
//		$checkbox = array();

		if($bol != "All"){
			$bol_sql = "AND BOL_EQUIV = '".$bol."' ";
		} else {
			$bol_sql = "";
		}
		if($cont != "All"){
			$cont_sql = "AND CONTAINER_NUM = '".$cont."' ";
		} else {
			$cont_sql = "";
		}
		if($order == "Container"){
			$order_sql = "ORDER BY CONTAINER_NUM, BOL_EQUIV";
		} else {
			$order_sql = "ORDER BY BOL_EQUIV, CONTAINER_NUM";
		}

		$sql = "SELECT *
				FROM CLR_MAIN_DATA
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND LINE_RELEASE IS NULL
					".$bol_sql."
					".$cont_sql."
					".$shipline_sql."
				".$order_sql;
		$line_data = ociparse($rfconn, $sql);
		ociexecute($line_data);
		while(ocifetch($line_data)){
			$rownum++;
?>
<input type="hidden" name="bol_set[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "BOL_EQUIV"); ?>">
<input type="hidden" name="cont_set[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "CONTAINER_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "SHIPLINE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "CONTAINER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "BOL_EQUIV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "CONSIGNEE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "PLTCOUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "QTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "COMMODITY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "CARGO_MODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "LOAD_TYPE"); ?></font></td>
		<td><input type="checkbox" name="rel_set[<? echo $rownum; ?>]" value="save"<? if($pre_check == "yes"){?> checked <?}?>></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="10"><input type="submit" name="submit" value="Set Releases">
	</tr>
</form>
</table>
<?
	}
?>