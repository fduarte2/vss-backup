<?php
/*
*		Adam Walter, July/Aug 2014.
*
*		Holding "page" for AMS-EDI 309's, awaiting to be inserted
*		into the CLR table
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "CLR preload";
  $area_type = "CLR";
  
  $url_this_page = 'push_to_CLR.php';
  // $url_this_page = 'push_to_CLR_test.php';

  // Provides header / leftnav
  include("pow_header.php");
  if ($access_denied) {
    printf("Access Denied from CLR system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo '<div class="alert" id="alert">Currently using the RF.TEST database!</div>';
	if ($rfconn < 1) {
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
	$pagename = "push_to_CLR";
	include("page_security.php");
	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	// $security_allowance = PageSecurityCheck($user, $pagename, "");

	$active_select = "pending"; // for default purposes
	$submit = $_POST['submit'];
	if ($submit != "" || $_GET['ves_select'] != "") {
		$extra_where = "";

		$ves_select = $_POST['ves_select'];
		if ($ves_select == "") {
			$ves_select = $_GET['ves_select'];
		}
		$arv_select = $_POST['arv_select'];
		$active_select = $_POST['active_select'];
		$shipline_select = $_POST['shipline_select'];
		$bol_select = $_POST['bol_select'];
		$cont_select = $_POST['cont_select'];
		$consignee_select = $_POST['consignee_select'];
		$broker_select = $_POST['broker_select'];

		if ($ves_select != "") {
			$extra_where .= " AND CAD.LLOYD_NUM = '".$ves_select."' ";
		}
		if ($arv_select != "") {
			$extra_where .= " AND ARRIVAL_NUM = '".$arv_select."' ";
		}
		if ($active_select == "pending") {
			$extra_where .= " AND IGNORE_FOR_CLR = 'Y' ";
		}
		if ($active_select == "stopped") {
			$extra_where .= " AND IGNORE_FOR_CLR IS NULL ";
		}
		if ($shipline_select != "") {
			$extra_where .= " AND SHIPLINE = '".$shipline_select."' ";
		}
		if ($bol_select != "") {
			$extra_where .= " AND BOL_EQUIV = '".$bol_select."' ";
		}
		if ($cont_select != "") {
			$extra_where .= " AND CONTAINER_NUM = '".$cont_select."' ";
		}
		if ($consignee_select != "") {
			$extra_where .= " AND CONSIGNEE = '".$consignee_select."' ";
		}
		if ($broker_select != "") {
			$extra_where .= " AND BROKER = '".$broker_select."' ";
		}
	}
	if ($submit == "Save Grid") {
		$lloyd = RemoveQuoteBackslash($_POST['lloyd']);
		$voyage = RemoveQuoteBackslash($_POST['voyage']);
		$vesname = RemoveQuoteBackslash($_POST['vesname']);
		$shipline = RemoveQuoteBackslash($_POST['shipline']);
		$bol = RemoveQuoteBackslash($_POST['bol']);
		$consignee = RemoveQuoteBackslash($_POST['consignee']);
		$container = RemoveQuoteBackslash($_POST['container']);
		$pltcount = RemoveQuoteBackslash($_POST['pltcount']);
		$qty = RemoveQuoteBackslash($_POST['qty']);
		$commodity = RemoveQuoteBackslash($_POST['commodity']);
//		$desc = RemoveQuoteBackslash($_POST['desc']);
		$weight = RemoveQuoteBackslash($_POST['weight']);
		$wt_unt = RemoveQuoteBackslash($_POST['wt_unt']);
		$broker = RemoveQuoteBackslash($_POST['broker']);
		$loadtype = RemoveQuoteBackslash($_POST['loadtype']);
		$mode = RemoveQuoteBackslash($_POST['mode']);
		$amendtype = RemoveQuoteBackslash($_POST['amendtype']);
		$amendcode = RemoveQuoteBackslash($_POST['amendcode']);
		$destination = RemoveQuoteBackslash($_POST['destination']);
//		$origin = RemoveQuoteBackslash($_POST['origin']);
		$ignore = $_POST['ignore'];
		$maxrows = $_POST['maxrows'];
		$key = $_POST['key'];
		$entry = $_POST['entry'];

//		$validate = ValidateRows($lloyd, $voyage, $vesname, $shipline, $bol, $consignee, $container, $pltcount, $qty, $commodity, $desc, $broker, $loadtype, $mode, $amendtype, $amendcode, $maxrows, $key, $entry, $rfconn);
		$validate = "";

		if ($validate != "") {
			echo "<font class='alert'><strong>DATA NOT SAVED.</strong><br/><br/>Reason(s):<br/>".$validate."Please correct and Resubmit.<br/></font>";
			$badsave = "bad";
		} else {
			$badsave = "";

			for($i = 0; $i <= $maxrows; $i++) {
//				if ($ignore[$i] == "Y") {
//					$sql = "UPDATE CLR_AMSEDI_DETAIL_309
//							SET IGNORE_FOR_CLR = 'Y'
//							WHERE KEY_ID = '".$key[$i]."'
//								AND ENTRY_NUM = '".$entry[$i]."'
//								AND IGNORE_FOR_CLR != 'Y'";
//					$mod = ociparse($rfconn, $sql);
//					ociexecute($mod);
//				} else {
//								AND ORIGIN_M11 = '".$origin[$i]."'
//								AND DESCR = '".$desc[$i]."'
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM CLR_AMSEDI_DETAIL_309
							WHERE LLOYD_NUM = '".$lloyd[$i]."'
								AND VOYAGE_NUM = '".$voyage[$i]."'
								AND VESNAME = '".$vesname[$i]."'
								AND SHIPLINE = '".$shipline[$i]."'
								AND BOL_EQUIV = '".$bol[$i]."'
								AND CONSIGNEE = '".$consignee[$i]."'
								AND CONTAINER_NUM = '".$container[$i]."'
								AND PLTCOUNT = '".$pltcount[$i]."'
								AND QTY = '".$qty[$i]."'
								AND WEIGHT = '".$weight[$i]."'
								AND WEIGHT_UNIT = '".$wt_unt[$i]."'
								AND COMMODITY = '".$commodity[$i]."'
								AND BROKER = '".$broker[$i]."'
								AND LOAD_TYPE = '".$loadtype[$i]."'
								AND CARGO_MODE = '".$mode[$i]."'
								AND AMEND_TYPE = '".$amendtype[$i]."'
								AND AMEND_CODE = '".$amendcode[$i]."'
								AND KEY_ID = '".$key[$i]."'
								AND ENTRY_NUM = '".$entry[$i]."'
								AND DESTINATION = '".$destination[$i]."'
								AND IGNORE_FOR_CLR = '".$ignore[$i]."'";
//					echo $sql."<br/>";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);
					if (ociresult($short_term_data, "THE_COUNT") < 1 && $ignore[$i] != "D") { // only do this if ANYTHING has changed
//									ORIGIN_M11 = '".$origin[$i]."'
//									DESCR = '".$desc[$i]."',
						$sql = "UPDATE CLR_AMSEDI_DETAIL_309
								SET LLOYD_NUM = '".$lloyd[$i]."',
									VOYAGE_NUM = '".$voyage[$i]."',
									VESNAME = '".$vesname[$i]."',
									SHIPLINE = '".$shipline[$i]."',
									BOL_EQUIV = '".$bol[$i]."',
									CONSIGNEE = '".$consignee[$i]."',
									CONTAINER_NUM = '".$container[$i]."',
									PLTCOUNT = '".$pltcount[$i]."',
									QTY = '".$qty[$i]."',
									WEIGHT = '".$weight[$i]."',
									WEIGHT_UNIT = '".$wt_unt[$i]."',
									COMMODITY = '".$commodity[$i]."',
									BROKER = '".$broker[$i]."',
									LOAD_TYPE = '".$loadtype[$i]."',
									CARGO_MODE = '".$mode[$i]."',
									AMEND_TYPE = '".$amendtype[$i]."',
									AMEND_CODE = '".$amendcode[$i]."',
									DESTINATION = '".$destination[$i]."',
									IGNORE_FOR_CLR = '".$ignore[$i]."'
								WHERE KEY_ID = '".$key[$i]."'
									AND ENTRY_NUM = '".$entry[$i]."'";
//						echo $sql."<br/>";
						$mod = ociparse($rfconn, $sql);
						ociexecute($mod);
					} elseif (ociresult($short_term_data, "THE_COUNT") < 1 && $ignore[$i] == "D") {
						$sql = "DELETE FROM CLR_AMSEDI_DETAIL_309
								WHERE KEY_ID = '".$key[$i]."'
									AND ENTRY_NUM = '".$entry[$i]."'";
						$mod = ociparse($rfconn, $sql);
						ociexecute($mod);
					}
//				}
			}

			echo "<font color=\"#0000FF\">Data Grid Updated.<br/></font>";
		}
	}
?>

<h1>Ocean Manifest EDI Review/Edit</h1>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="<?php echo $url_this_page; ?>" method="post">
	<tr>
		<td colspan="2" align="left"><font size="3">All criteria are optional.</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="arv_select">DSPC Arrival#:</label></td>
		<td><input type="text" id="arv_select" name="arv_select" size="20" maxlength="20" value="<?php echo $arv_select; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="shipline_select">Ship Line#:</label></td>
		<td><select id="shipline_select" name="shipline_select"><option value="">All</option>
<?php
	$sql = "SELECT DISTINCT SHIPLINE
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
			ORDER BY SHIPLINE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)) {
?>
				<option value="<?php echo ociresult($short_term_data, "SHIPLINE"); ?>"<?php if (ociresult($short_term_data, "SHIPLINE") == $shipline_select) { ?> selected <?php } ?>>
				<?php echo ociresult($short_term_data, "SHIPLINE"); ?></option>
<?php
	}
?>
						</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="cont_select">Container:</label></td>
		<td><select id="cont_select" name="cont_select"><option value="">All</option>
<?php
	$sql = "SELECT DISTINCT CONTAINER_NUM
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
			ORDER BY CONTAINER_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)) {
?>
				<option value="<?php echo ociresult($short_term_data, "CONTAINER_NUM"); ?>"<?php if (ociresult($short_term_data, "CONTAINER_NUM") == $cont_select) { ?> selected <?php } ?>>
				<?php echo ociresult($short_term_data, "CONTAINER_NUM"); ?></option>
<?php
	}
?>
						</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="bol_select">BoL:</label></td>
		<td><select id="bol_select" name="bol_select"><option value="">All</option>
<?php
	$sql = "SELECT DISTINCT BOL_EQUIV
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
			ORDER BY BOL_EQUIV";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)) {
?>
				<option value="<?php echo ociresult($short_term_data, "BOL_EQUIV"); ?>"<?php if (ociresult($short_term_data, "BOL_EQUIV") == $bol_select) { ?> selected <?php } ?>>
				<?php echo ociresult($short_term_data, "BOL_EQUIV"); ?></option>
<?php
	}
?>
						</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="consignee_select">Consignee:</label></td>
		<td><select id="consignee_select" name="consignee_select"><option value="">All</option>
<?php
	$sql = "SELECT DISTINCT CONSIGNEE
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
			ORDER BY CONSIGNEE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)) {
?>
				<option value="<?php echo ociresult($short_term_data, "CONSIGNEE"); ?>"<?php if (ociresult($short_term_data, "CONSIGNEE") == $consignee_select) { ?> selected <?php } ?>>
				<?php echo ociresult($short_term_data, "CONSIGNEE"); ?></option>
<?php
	}
?>
						</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="broker_select">Broker:</label></td>
		<td><select id="broker_select" name="broker_select"><option value="">All</option>
<?php
	$sql = "SELECT DISTINCT BROKER
			FROM CLR_AMSEDI_DETAIL_309
			WHERE PUSH_TO_CLR_ON IS NULL
			ORDER BY BROKER";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)) {
?>
				<option value="<?php echo ociresult($short_term_data, "BROKER"); ?>"<?php if (ociresult($short_term_data, "BROKER") == $broker_select) {?> selected <?php } ?>>
				<?php echo ociresult($short_term_data, "BROKER"); ?></option>
<?php
	}
?>
						</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label for="ves_select">LLoyd#:</label></td>
		<td><input type="text" id="ves_select" name="ves_select" size="20" maxlength="20" value="<?php echo $ves_select; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><label>Push to CLR?:</label></td>
		<td>
			<input type="radio" name="active_select" value="all" <?php if ($active_select == "all" || $active_select == "") { ?> checked <?php } ?>><font size="2" face="Verdana"><b>All Unresolved EDIs</b></font>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active_select" value="pending" <?php if ($active_select == "pending") { ?> checked <?php } ?>><font size="2" face="Verdana"><b>Pending EDIs</b></font>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active_select" value="stopped" <?php if ($active_select == "stopped") { ?> checked <?php } ?>><font size="2" face="Verdana"><b>Ready to Push EDIs</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>



<?php
	if ($submit != "") {
		$xls_filename = date('mdYhis').".xls";
		$handle = fopen("./safe_to_delete_tempfiles/".$xls_filename, "w");
		if (!$handle) {
			echo "File ".$xls_filename." could not be opened, please contact TS.\n";
			exit;
		}
		fwrite($handle, "<table>");

?>
<table class="aTable fullWidthTable">
<form name="update" action="push_to_CLR.php" method="post">
<input type="hidden" name="active_select" value="<?php echo $active_select; ?>">
<input type="hidden" name="ves_select" value="<?php echo $ves_select; ?>">
<input type="hidden" name="arv_select" value="<?php echo $arv_select; ?>">
<input type="hidden" name="shipline_select" value="<?php echo $shipline_select; ?>">
<input type="hidden" name="bol_select" value="<?php echo $bol_select; ?>">
<input type="hidden" name="consignee_select" value="<?php echo $consignee_select; ?>">
<input type="hidden" name="broker_select" value="<?php echo $broker_select; ?>">

	<thead>
<?php
		if (strpos($security_allowance, "M") !== false) {
?>
		<tr>
			<td colspan="22" align="center"><input type="submit" name="submit" value="Save Grid"></td>
		</tr>
<?php
		}
?>
		<tr>
			<td colspan="22" align="center">
				<font size="3" face="Verdana"><a href="<?php echo "./safe_to_delete_tempfiles/".$xls_filename; ?>">Click Here</a> For an Excel File.<br/></font>
				Please note:  To be used on the Manual Upload screen, you will need to replace the Customer and Commodity fields with PoW customer and Commodity numbers.
			</td>
		</tr>
		<tr>
			<th>Add to Push</th>
			<th>EDI ID#</th>
			<th>Matched Arrival #</th>
			<th>Lloyd#</th>
			<th>Voyage#</th>
			<th>Vessel Name</th>
			<th>Ship Line</th>
			<th>BoL</th>
			<th>Consignee</th>
			<th>Container#</th>
			<th>Pallet Count</th>
			<th>Case Count</th>
			<th>Commodity</th>
			<th>Weight</th>
			<th>Wt Unit</th>
			<th>Broker</th>
			<th>Load Type</th>
			<th>Mode</th>
			<th>Amend Type</th>
			<th>Amend Code</th>
			<th>Destination</th>
			<th>Freeform N10 Desc</th>
<!--		<th>Originates From</th> !-->
		</tr>
	</thead>
	<tbody>
<?php
		$output = "<tr>
						<td>EDI ID#</td>
						<td>MATCHED ARRIVAL #</td>
						<td>LLOYD#</td>
						<td>VOYAGE#</td>
						<td>VESSEL NAME</td>
						<td>SHIP LINE</td>
						<td>BOL</td>
						<td>CONSIGNEE CODE</td>
						<td>CONSIGNEE</td>
						<td>CONTAINER#</td>
						<td>PALLET COUNT</td>
						<td>CASE COUNT</td>
						<td>COMMODITY CODE</td>
						<td>COMMODITY</td>
						<td>WEIGHT</td>
						<td>WT UNIT</td>
						<td>WEIGHT (LBS)</td>
						<td>LBS/EACH</td>
						<td>BROKER</td>
						<td>LOAD TYPE</td>
						<td>MODE</td>
						<td>AMEND TYPE</td>
						<td>AMEND CODE</td>
						<td>DESTINATION</td>
						<td>ACTION</td>
					</tr>";
		fwrite($handle, $output);

		$sql = "SELECT
					CAD.*,
					NVL(CLAM.ARRIVAL_NUM, 'NO ARV# SET') THE_ARV,
					DECODE(CLR_IGNORE, 'Y', '<br/>Lloyd/Voyage Ignored', '') THE_IGNORE,
					NVL(AMEND_CODE, 'A') AMEND_FOR_XLS
				FROM
					CLR_AMSEDI_DETAIL_309 CAD
				LEFT JOIN CLR_LLOYD_ARRIVAL_MAP CLAM
					ON CAD.LLOYD_NUM = CLAM.LLOYD_NUM
						AND CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM
						AND CAD.VESNAME = CLAM.SHIP_NAME
				WHERE
					PUSH_TO_CLR_ON IS NULL
					AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')
					AND CAD.LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
					".$extra_where."
				ORDER BY
					BOL_EQUIV,
					KEY_ID";
		// echo "<pre>$sql</pre>";
		$rows = ociparse($rfconn, $sql);
		ociexecute($rows);
		if (!ocifetch($rows)) {
?>
		<tr>
			<td colspan="22" align="center"><b>No EDIs for the given filters.</b></td>
		</tr>
<?php
		} else {
			$line = -1;
			do {
				$line++;
				$sql = "SELECT (NVL('".GetBadSaveValue(ociresult($rows, "WEIGHT"), $weight[$line], $badsave)."', 0) * CONVERSION_FACTOR) CONV_WEIGHT
						FROM UNIT_CONVERSION_FROM_BNI UCFB 
						WHERE PRIMARY_UOM = '".GetBadSaveValue(ociresult($rows, "WEIGHT_UNIT"), $wt_unt[$line], $badsave)."'
							AND SECONDARY_UOM = 'LB'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data); 
				if (!ocifetch($short_term_data)) {
					$lbs_equiv = 0;
				} else {
					$lbs_equiv = ociresult($short_term_data, "CONV_WEIGHT");
				}
	/*			$sql = "SELECT ARRIVAL_NUM, DECODE(CLR_IGNORE, 'Y', '<br/>Ignored at Vessel Level', '') THE_IGNORE
						FROM CLR_LLOYD_ARRIVAL_MAP CLAM
						WHERE LLOYD_NUM = '".ociresult($rows, "LLOYD_NUM")."'
							AND VOYAGE_NUM = '".ociresult($rows, "VOYAGE_NUM")."'
							AND SHIP_NAME = '".ociresult($rows, "VESNAME")."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data); */
				$display_ves = ociresult($rows, "THE_ARV").ociresult($rows, "THE_IGNORE");
	/*			if (ociresult($rows, "ARRIVAL_NUM")) {
					$display_ves = "Not Yet Matched";
				} else {
					$display_ves = ociresult($short_term_data, "ARRIVAL_NUM").ociresult($short_term_data, "THE_IGNORE");
				} */
				
				//Get filename of EDI file
				$sql = "select SPLIT_FILENAME
						from CANADIAN_AMSEDI_HEADER
						where KEY_ID = '" . ociresult($rows, "KEY_ID") . "'";
				// echo "<pre>$sql</pre>";
				$filename = ociparse($rfconn, $sql);
				ociexecute($filename);
				ocifetch($filename);
				$filename = ociresult($filename, 'SPLIT_FILENAME');
				$url_edi = "http://intranet/TS_Program/can_AMSEDI_filesplit/split_files/EDI309/processed/$filename";
							//http://intranet/TS_Program/can_AMSEDI_filesplit/split_files/EDI309/processed/ICERANGER-05262015154501-2-72877-72877
				
				
?>
		<input type="hidden" name="key[<?php echo $line; ?>]" value="<?php echo ociresult($rows, "KEY_ID"); ?>">
		<input type="hidden" name="entry[<?php echo $line; ?>]" value="<?php echo ociresult($rows, "ENTRY_NUM"); ?>">
		<tr>
			<td><input type="radio" value="Y" name="ignore[<?php echo $line; ?>]"<?php if (GetBadSaveValue(ociresult($rows, "IGNORE_FOR_CLR"), $ignore[$line], $badsave) == "Y") { ?> checked <?php } ?>>Ignore<br/>
				<input type="radio" value="" name="ignore[<?php echo $line; ?>]"<?php if (GetBadSaveValue(ociresult($rows, "IGNORE_FOR_CLR"), $ignore[$line], $badsave) == "") { ?> checked <?php } ?>>Push<br/>
				<input type="radio" value="D" name="ignore[<?php echo $line; ?>]"<?php if (GetBadSaveValue(ociresult($rows, "IGNORE_FOR_CLR"), $ignore[$line], $badsave) == "D") { ?> checked <?php } ?>>Delete</td>
			<td><b><?php echo '<a href="'.$url_edi.'" target="_blank">' . ociresult($rows, "KEY_ID") . '&#8209;' . ociresult($rows, "ENTRY_NUM") . '</a>'; ?></td>
			<td><?php echo $display_ves; ?></td>
			<td><input type="text" name="lloyd[<?php echo $line; ?>]" size="10" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "LLOYD_NUM"), $lloyd[$line], $badsave); ?>"></td>
			<td><input type="text" name="voyage[<?php echo $line; ?>]" size="10" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "VOYAGE_NUM"), $voyage[$line], $badsave); ?>"></td>
			<td><input type="text" name="vesname[<?php echo $line; ?>]" size="20" maxlength="50" value="<?php echo GetBadSaveValue(ociresult($rows, "VESNAME"), $vesname[$line], $badsave); ?>"></td>
			<td><input type="text" name="shipline[<?php echo $line; ?>]" size="5" maxlength="10" value="<?php echo GetBadSaveValue(ociresult($rows, "SHIPLINE"), $shipline[$line], $badsave); ?>"></td>
			<td><input type="text" name="bol[<?php echo $line; ?>]" size="20" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "BOL_EQUIV"), $bol[$line], $badsave); ?>"></td>
			<td><input type="text" name="consignee[<?php echo $line; ?>]" size="20" maxlength="60" value="<?php echo GetBadSaveValue(ociresult($rows, "CONSIGNEE"), $consignee[$line], $badsave); ?>"></td>
			<td><input type="text" name="container[<?php echo $line; ?>]" size="20" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "CONTAINER_NUM"), $container[$line], $badsave); ?>"></td>
			<td><input type="text" name="pltcount[<?php echo $line; ?>]" size="5" maxlength="10" value="<?php echo GetBadSaveValue(ociresult($rows, "PLTCOUNT"), $pltcount[$line], $badsave); ?>"></td>
			<td><input type="text" name="qty[<?php echo $line; ?>]" size="6" maxlength="6" value="<?php echo GetBadSaveValue(ociresult($rows, "QTY"), $qty[$line], $badsave); ?>"></td>
			<td><input type="text" name="commodity[<?php echo $line; ?>]" size="20" maxlength="45" value="<?php echo GetBadSaveValue(ociresult($rows, "COMMODITY"), $commodity[$line], $badsave); ?>"></td>
	<!--		<td><input type="text" name="desc[<?php echo $line; ?>]" size="20" maxlength="40" value="<?php echo GetBadSaveValue(ociresult($rows, "DESCR"), $desc[$line], $badsave); ?>"></td> !-->
			<td><input type="text" name="weight[<?php echo $line; ?>]" size="7" maxlength="10" value="<?php echo GetBadSaveValue(ociresult($rows, "WEIGHT"), $weight[$line], $badsave); ?>">
								<br/>(<?php echo round($lbs_equiv); ?>&nbsp;lbs)
								<br/>(<?php echo round($lbs_equiv / GetBadSaveValue(ociresult($rows, "QTY"), $qty[$line], $badsave), 2); ?>&nbsp;lbs/qty)</td> 
			<td><input type="text" name="wt_unt[<?php echo $line; ?>]" size="4" maxlength="2" value="<?php echo GetBadSaveValue(ociresult($rows, "WEIGHT_UNIT"), $wt_unt[$line], $badsave); ?>"></td> 
			<td><input type="text" name="broker[<?php echo $line; ?>]" size="20" maxlength="60" value="<?php echo GetBadSaveValue(ociresult($rows, "BROKER"), $broker[$line], $badsave); ?>"></td>
			<td><input type="text" name="loadtype[<?php echo $line; ?>]" size="10" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "LOAD_TYPE"), $loadtype[$line], $badsave); ?>"></td>
			<td><input type="text" name="mode[<?php echo $line; ?>]" size="10" maxlength="20" value="<?php echo GetBadSaveValue(ociresult($rows, "CARGO_MODE"), $mode[$line], $badsave); ?>"></td>
			<td><input type="text" name="amendtype[<?php echo $line; ?>]" size="4" maxlength="4" value="<?php echo GetBadSaveValue(ociresult($rows, "AMEND_TYPE"), $amendtype[$line], $badsave); ?>"></td>
			<td><input type="text" name="amendcode[<?php echo $line; ?>]" size="4" maxlength="4" value="<?php echo GetBadSaveValue(ociresult($rows, "AMEND_CODE"), $amendcode[$line], $badsave); ?>"></td>
			<td><select name="destination[<?php echo $line; ?>]">
					<option value="">Select Destination</option>
					<option value="DOMESTIC"<?php if (GetBadSaveValue(ociresult($rows, "DESTINATION"), $destination[$line], $badsave) == "DOMESTIC") {?> selected <?php } ?>>DOMESTIC</option>
					<option value="CANADIAN"<?php if (GetBadSaveValue(ociresult($rows, "DESTINATION"), $destination[$line], $badsave) == "CANADIAN") {?> selected <?php } ?>>CANADIAN</option>
				</select></td>
			<td><font size="2" face="Verdana"><?php echo ociresult($rows, "QTY")." ".ociresult($rows, "FULL_N10_LINE"); ?></font></td>
	<!--		<td><input type="text" name="origin[<?php echo $line; ?>]" size="4" maxlength="4" value="<?php echo GetBadSaveValue(ociresult($rows, "ORIGIN_M11"), $origin[$line], $badsave); ?>"></td> !-->
		</tr>
<?php
					$output = "<tr>
								<td>".ociresult($rows, "KEY_ID")."-".ociresult($rows, "ENTRY_NUM")."</td>
								<td>".$display_ves."</td>
								<td>".GetBadSaveValue(ociresult($rows, "LLOYD_NUM"), $lloyd[$line], $badsave)."</td>
								<td>\0".GetBadSaveValue(ociresult($rows, "VOYAGE_NUM"), $voyage[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "VESNAME"), $vesname[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "SHIPLINE"), $shipline[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "BOL_EQUIV"), $bol[$line], $badsave)."</td>
								<td>".PreResolveCust(GetBadSaveValue(ociresult($rows, "CONSIGNEE"), $consignee[$line], $badsave), $rfconn)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "CONSIGNEE"), $consignee[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "CONTAINER_NUM"), $container[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "PLTCOUNT"), $pltcount[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "QTY"), $qty[$line], $badsave)."</td>
								<td>".PreResolveComm(GetBadSaveValue(ociresult($rows, "COMMODITY"), $commodity[$line], $badsave), $rfconn)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "COMMODITY"), $commodity[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "WEIGHT"), $weight[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "WEIGHT_UNIT"), $wt_unt[$line], $badsave)."</td>
								<td>".round($lbs_equiv)."</td>
								<td>".round($lbs_equiv / GetBadSaveValue(ociresult($rows, "QTY"), $qty[$line], $badsave), 2)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "BROKER"), $broker[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "LOAD_TYPE"), $loadtype[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "CARGO_MODE"), $mode[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "AMEND_TYPE"), $amendtype[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "AMEND_CODE"), $amendcode[$line], $badsave)."</td>
								<td>".GetBadSaveValue(ociresult($rows, "DESTINATION"), $destination[$line], $badsave)."</td>
								<td>A</td>
							</tr>";
					fwrite($handle, $output);
				} while(ocifetch($rows));

			if (strpos($security_allowance, "M") !== false) {
?>
		<tr>
			<td colspan="22" align="center"><input type="submit" name="submit" value="Save Grid"></td>
		</tr>
<?php
			}
?>
		<input name="maxrows" type="hidden" value="<?php echo $line; ?>">
	</tbody>
</form>
<?php
		}
	
		fwrite($handle, "</table>");
		fclose($handle);
?>
</table>
<?php
	}
	
include("pow_footer.php");
exit;








//FUNCTIONS

function GetBadSaveValue($DB_val, $saved_val, $save_check) {
	if ($save_check != "bad") {
		return $DB_val;
	} else {
		return $saved_val;
	}

	return $DB_val;
}

function RemoveQuoteBackslash($value) {
	$value = str_replace("'", "`", $value);
	$value = str_replace("\\", "", $value);

	return $value;
}

function ValidateRows($lloyd, $voyage, $vesname, $shipline, $bol, $consignee, $container, $pltcount, $qty, $commodity, $desc, $broker, $loadtype, $mode, $amendtype, $amendcode, $maxrows, $key, $entry, $rfconn) {
	$return = "";

	for($i = 0; $i <= $maxrows; $i++) {
		if (!is_numeric($qty[$i]) || $qty[$i] < 0) {
			$return .= "QTY must be a positive number.<br/>";
		}
		if ($pltcount[$i] != "" && $pltcount[$i] < 0) {
			$return .= "Pallet Count, if entered, must be a positive number.<br/>";
		}
		if ($amendcode[$i] != "" && $amendcode[$i] != "A") {
			$return .= "Amend Code must either be Empty or A<br/>";
		}
	}

	return $return;
}

function PreResolveCust($custname, $rfconn) {
	$sql = "SELECT RECEIVER_ID
			FROM CLR_CUST_MAP
			WHERE CONSIGNEE = '".$custname."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data); 
	if (!ocifetch($short_term_data)) {
		return "";
	} else {
		return ociresult($short_term_data, "RECEIVER_ID");
	}
}

function PreResolveComm($commname, $rfconn) {
	$sql = "SELECT DSPC_COMMODITY_CODE
			FROM CLR_COMM_MAP
			WHERE CLR_COMMODITY_NAME = '".$commname."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data); 
	if (!ocifetch($short_term_data)) {
		return "";
	} else {
		return ociresult($short_term_data, "DSPC_COMMODITY_CODE");
	}
}

