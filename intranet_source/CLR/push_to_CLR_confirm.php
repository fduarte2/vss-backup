<?
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
	$pagename = "push_to_CLR_confirm";
	include("page_security.php");
//	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	$security_allowance = PageSecurityCheck($user, $pagename, "");

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Move Unprocessed Records"){
		$print = ValidateRecords($rfconn);
		if($print == ""){
/*			$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309
					WHERE IGNORE_FOR_CLR IS NULL
						AND PUSH_TO_CLR_ON IS NULL
					ORDER BY DATA_DATE";
			$rows = ociparse($rfconn, $sql);
			ociexecute($rows);
			while(ocifetch($rows)){ // if there are no rows, nothing will get done */
			$sql = "INSERT INTO CLR_MAIN_DATA
						(CLR_KEY,
						LLOYD_NUM,
						VOYAGE_NUM,
						VESNAME,
						BOL_EQUIV,
						CONSIGNEE,
						BROKER,
						CONTAINER_NUM,
						QTY,
						WEIGHT,
						WEIGHT_UNIT,
						COMMODITY,
						PLTCOUNT,
						SHIPLINE,
						LOAD_TYPE,
						CARGO_MODE,
						COMMODITY_CODE,
						CUSTOMER_ID,
						ARRIVAL_NUM,
						ORIGIN_M11,
						BROKER_RELEASE,
						MOST_RECENT_EDIT_DATE,
						MOST_RECENT_EDIT_BY)
					(SELECT CLR_MAIN_DATA_SEQ.NEXTVAL,
						CAD.LLOYD_NUM,
						CAD.VOYAGE_NUM,
						CAD.VESNAME,
						CAD.BOL_EQUIV,
						CAD.CONSIGNEE,
						CAD.BROKER,
						CAD.CONTAINER_NUM,
						CAD.QTY,
						CAD.WEIGHT,
						CAD.WEIGHT_UNIT,
						CAD.COMMODITY,
						CAD.PLTCOUNT,
						CAD.SHIPLINE,
						CAD.LOAD_TYPE,
						CAD.CARGO_MODE,
						CCOMMM.DSPC_COMMODITY_CODE,
						CCUSTM.RECEIVER_ID,
						CLAM.ARRIVAL_NUM,
						CAD.ORIGIN_M11,
						SYSDATE,
						SYSDATE,
						'".$user."'
					FROM CLR_AMSEDI_DETAIL_309 CAD, CLR_LLOYD_ARRIVAL_MAP CLAM, CLR_COMM_MAP CCOMMM, CLR_CUST_MAP CCUSTM
					WHERE CAD.CONSIGNEE = CCUSTM.CONSIGNEE
						AND CAD.COMMODITY = CCOMMM.CLR_COMMODITY_NAME
						AND (CAD.LLOYD_NUM = CLAM.LLOYD_NUM OR (CAD.LLOYD_NUM IS NULL AND CLAM.LLOYD_NUM IS NULL))
						AND (CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM OR (CAD.VOYAGE_NUM IS NULL AND CLAM.VOYAGE_NUM IS NULL))
						AND (CAD.VESNAME = CLAM.SHIP_NAME OR (CAD.VESNAME IS NULL AND CLAM.SHIP_NAME IS NULL))
						AND CAD.LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
						AND CAD.IGNORE_FOR_CLR IS NULL
						AND CAD.PUSH_TO_CLR_ON IS NULL
						AND CLAM.CLR_IGNORE IS NULL
						AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')
					)";
			$inserts = ociparse($rfconn, $sql);
			ociexecute($inserts);

			$sql = "UPDATE CLR_AMSEDI_DETAIL_309
					SET PUSH_TO_CLR_ON = SYSDATE
					WHERE IGNORE_FOR_CLR IS NULL
						AND PUSH_TO_CLR_ON IS NULL
						AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')
						AND LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
						AND COMMODITY IN (SELECT CLR_COMMODITY_NAME FROM CLR_COMM_MAP)
						AND CONSIGNEE IN (SELECT CONSIGNEE FROM CLR_CUST_MAP)
						AND (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) IN
							(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
							FROM CLR_LLOYD_ARRIVAL_MAP
							WHERE CLR_IGNORE IS NULL)";
			$inserts = ociparse($rfconn, $sql);
			ociexecute($inserts);

		} else {
			echo "<font color=\"#FF0000\">The following errors were found with the pending data:<br>".$print."<br><br>";
		}
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Ocean Manifest EDI Push
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="push_to_CLR_confirm.php" method="post">
	<tr>
		<td colspan="18" align="center"><font size="3" face="Verdana"><b>This lists all EDIs that will be processed.  If you do not want an item processed, remove it on <br><a href="push_to_CLR.php">The Review Screen</a> and remember to save the Grid.</b></font></td>
	</tr>
	<tr>
		<td colspan="18" align="center"><font size="2" face="Verdana"><b>Any Entries from the previous screen which cannot be matched to DSPC Commodity, Customer(Consignee), or Vessel numbers will not show up here.  If so, contact TS.</b></font></td>
	</tr>
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td colspan="18" align="center"><input type="submit" name="submit" value="Move Unprocessed Records"></td>
	</tr>
<?
	}
?>
</form>
	<tr>
		<td colspan="18" align="center"><font size="3" face="Verdana">The following records will be run against the Cargo Load Release System:</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>EDI ID#</b></font>
		<td><font size="2" face="Verdana"><b>Lloyd#</b></font>
		<td><font size="2" face="Verdana"><b>Voyage#</b></font>
		<td><font size="2" face="Verdana"><b>Vessel Name</b></font>
		<td><font size="2" face="Verdana"><b>Ship Line</b></font>
		<td><font size="2" face="Verdana"><b>BoL</b></font>
		<td><font size="2" face="Verdana"><b>Consignee</b></font>
		<td><font size="2" face="Verdana"><b>Container#</b></font>
		<td><font size="2" face="Verdana"><b>Pallet Count</b></font>
		<td><font size="2" face="Verdana"><b>Case Count</b></font>
		<td><font size="2" face="Verdana"><b>Commodity</b></font>
		<td><font size="2" face="Verdana"><b>Weight</b></font>
		<td><font size="2" face="Verdana"><b>Wt Unit</b></font>
		<td><font size="2" face="Verdana"><b>Broker</b></font>
		<td><font size="2" face="Verdana"><b>Load Type</b></font>
		<td><font size="2" face="Verdana"><b>Mode</b></font>
		<td><font size="2" face="Verdana"><b>Amend Type</b></font>
		<td><font size="2" face="Verdana"><b>Amend Code</b></font>
<!--		<td><font size="2" face="Verdana"><b>Originates From</b></font> !-->
<!--		<td><font size="2" face="Verdana"><b>Remove from Push List?</b></font> !-->
	</tr>
<?
	$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309 CAD, CLR_LLOYD_ARRIVAL_MAP CLAM
			WHERE CAD.IGNORE_FOR_CLR IS NULL
				AND CAD.PUSH_TO_CLR_ON IS NULL
				AND CAD.LLOYD_NUM = CLAM.LLOYD_NUM
				AND CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM
				AND CAD.VESNAME = CLAM.SHIP_NAME
				AND CLAM.CLR_IGNORE IS NULL
				AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')
				AND COMMODITY IN (SELECT CLR_COMMODITY_NAME FROM CLR_COMM_MAP)
				AND CONSIGNEE IN (SELECT CONSIGNEE FROM CLR_CUST_MAP)
				AND CAD.LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
			ORDER BY KEY_ID";
	$rows = ociparse($rfconn, $sql);
	ociexecute($rows);
	if(!ocifetch($rows)){
?>
	<tr>
		<td colspan="18" align="center"><font size="2" face="Verdana"><b>No Pushable EDIs found.</b></font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><nobr><font size="2" face="Verdana"><b><? echo ociresult($rows, "KEY_ID")."-".ociresult($rows, "ENTRY_NUM"); ?></nobr></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "LLOYD_NUM"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "VOYAGE_NUM"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "VESNAME"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "SHIPLINE"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "BOL_EQUIV"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "CONSIGNEE"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "CONTAINER_NUM"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "PLTCOUNT"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "QTY"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "COMMODITY"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "WEIGHT"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "WEIGHT_UNIT"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "BROKER"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "LOAD_TYPE"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "CARGO_MODE"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "AMEND_TYPE"); ?>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "AMEND_CODE"); ?>&nbsp;</td>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($rows, "ORIGIN_M11"); ?>&nbsp;</td> !-->
	</tr>
<?
		} while(ocifetch($rows));
	}
?>
</table>
<?
	include("pow_footer.php");





function ValidateRecords($rfconn){
	$errors = "";

	$perpetual_where = " AND IGNORE_FOR_CLR IS NULL AND PUSH_TO_CLR_ON IS NULL AND LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD) AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')";
/*
	$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309
			WHERE AMEND_CODE != 'A'".$perpetual_where."
				AND (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) IN
				(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
						FROM CLR_LLOYD_ARRIVAL_MAP
						WHERE CLR_IGNORE IS NULL)
			ORDER BY KEY_ID, ENTRY_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$errors .= "EDI line ".ociresult($short_term_data, "KEY_ID")."-".ociresult($short_term_data, "ENTRY_NUM")." Is not listed as Amend Code A (other types not yet supported)<br>";
	}
*/
	$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309
			WHERE CONSIGNEE NOT IN (SELECT CONSIGNEE FROM CLR_CUST_MAP)
			".$perpetual_where."
				AND (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) IN
				(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
						FROM CLR_LLOYD_ARRIVAL_MAP
						WHERE CLR_IGNORE IS NULL)
			ORDER BY KEY_ID, ENTRY_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$errors .= "EDI line ".ociresult($short_term_data, "KEY_ID")."-".ociresult($short_term_data, "ENTRY_NUM").", ".ociresult($short_term_data, "CONSIGNEE")." could not be found in the CLR-customer-code table.<br>";
	}

	$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309
			WHERE COMMODITY NOT IN (SELECT CLR_COMMODITY_NAME FROM CLR_COMM_MAP)
			".$perpetual_where."
				AND (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) IN
				(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
						FROM CLR_LLOYD_ARRIVAL_MAP
						WHERE CLR_IGNORE IS NULL)
			ORDER BY KEY_ID, ENTRY_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$errors .= "EDI line ".ociresult($short_term_data, "KEY_ID")."-".ociresult($short_term_data, "ENTRY_NUM").", ".ociresult($short_term_data, "COMMODITY")." could not be found in the CLR-commodity-code table.<br>";
	}

	$sql = "SELECT * FROM CLR_AMSEDI_DETAIL_309
			WHERE (NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(VESNAME, 'Q')) NOT IN
						(SELECT NVL(LLOYD_NUM, 'Q'), NVL(VOYAGE_NUM, 'Q'), NVL(SHIP_NAME, 'Q')
						FROM CLR_LLOYD_ARRIVAL_MAP)
			".$perpetual_where."
			ORDER BY KEY_ID, ENTRY_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$errors .= "EDI line ".ociresult($short_term_data, "KEY_ID")."-".ociresult($short_term_data, "ENTRY_NUM")." Could not match to the DSPC-Vessel Conversion Table<br>";
	}

	return $errors;
}