<?
/*
*	Adam Walter, Sep 30, 2010.
*
*	A summary screen which, by vessel, reports chilean
*	Client sort file uploads
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Chilean Sort";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$cursor = ora_open($conn);
	$cursor_outer = ora_open($conn);
	$cursor_inner = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Chilean Sort File Pallet Summary
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="sort_upload_summary_v2.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($vessel == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($vessel == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select>
		
	&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Retrieve Sort Info"></td>
	</tr>
</form>
	<tr>
		<td><hr></td>
	</tr>
</table>

<?
	if($submit != "" && $vessel != ""){
		$total_carle = 0;
		$total_match = 0;
		$total_nomatch = 0;
		$total_unknown = 0;
		$total_X_deny = 0;
		$total_X_grant = 0;
		$total_total = 0;


?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "	SELECT RECEIVER_ID
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$vessel."'
				UNION
					SELECT RECEIVER_ID
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND OMH.PUSHED_TO_CT = 'Y'
				UNION
					SELECT CUSTOMER_ID RECEIVER_ID
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'";
		ora_parse($cursor_outer, $sql);
		ora_exec($cursor_outer);
		if(!ora_fetch_into($cursor_outer, $row_outer, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">No Values for Selected Vessel</font>";
			exit;
		} else {
?>
	<tr>
<!--		<td><font size="3" face="Verdana"><b>Customer</b></font></td> !-->
		<td><font size="3" face="Verdana"><b>Name</b></font></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="From Carle File(s)"><font size="3" face="Verdana" color="#CC6600">Carle</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Customer PLT in Carle"><font size="3" face="Verdana" color="#CC6600">Match</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Carle File minus Match"><font size="3" face="Verdana" color="#CC6600">Unmatch</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Customer File PLT IDs not in Entire Carle File"><font size="3" face="Verdana" color="#CC6600">Unknown</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Transfer requested but Denied"><font size="3" face="Verdana" color="#CC6600">Xfer Denied</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Transfer Requested and Granted"><font size="3" face="Verdana" color="#CC6600">Xfer Granted</font></a></b></td>
		<td><b><a href="#" style="text-decoration:none" TITLE="Total in Cargo Tracking"><font size="3" face="Verdana" color="#CC6600">Total Current</font></a></b></td>
	</tr>
<?
			do {
			$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 20) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$row_outer['RECEIVER_ID']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$cust_name = $short_term_row['THE_CUST'];

			$sql = "SELECT COUNT(DISTINCT PALLET) PLT_COUNT
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND CCM.RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
					AND OMH.PUSHED_TO_CT = 'Y'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$carle_count = $short_term_row['PLT_COUNT'];
			$total_carle += $carle_count;

			$sql = "SELECT COUNT(DISTINCT PALLET) PLT_COUNT
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND CCM.RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
					AND OMH.PUSHED_TO_CT = 'Y'
					AND PALLET IN
						(SELECT PALLET_ID
						FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
						WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
						AND CPCH.ARRIVAL_NUM = '".$vessel."'
						AND CPCH.CUSTOMER_ID = '".$row_outer['RECEIVER_ID']."'
						AND DATE_CONFIRMED IS NOT NULL
						AND PALLET_TO_DB_COMPARE != 'NOTINFILE'
						)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$match_count = $short_term_row['PLT_COUNT'];
			$total_match += $match_count;

			$sql = "SELECT COUNT(DISTINCT PALLET) PLT_COUNT
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND CCM.RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
					AND OMH.PUSHED_TO_CT = 'Y'
					AND PALLET NOT IN
						(SELECT PALLET_ID
						FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
						WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
						AND CPCH.ARRIVAL_NUM = '".$vessel."'
						AND CPCH.CUSTOMER_ID = '".$row_outer['RECEIVER_ID']."'
						AND DATE_CONFIRMED IS NOT NULL
						AND PALLET_TO_DB_COMPARE != 'NOTINFILE'
						)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$no_match_count = $short_term_row['PLT_COUNT'];
			$total_nomatch += $no_match_count;
			
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) PLT_COUNT 
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'
					AND CPCH.CUSTOMER_ID = '".$row_outer['RECEIVER_ID']."'
					AND DATE_CONFIRMED IS NOT NULL
					AND PALLET_ID NOT IN
						(SELECT PALLET
						FROM ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
						WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
						AND OMH.LR_NUM = '".$vessel."'
						AND OMH.PUSHED_TO_CT = 'Y'
						)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$noDB_count = $short_term_row['PLT_COUNT'];
			$total_unknown += $noDB_count;

			$sql = "SELECT COUNT(DISTINCT PALLET) PLT_COUNT
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND CCM.RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
					AND OMH.PUSHED_TO_CT = 'Y'
					AND PALLET IN
						(SELECT PALLET_ID
						FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
						WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
						AND CPCH.ARRIVAL_NUM = '".$vessel."'
						AND CPCH.CUSTOMER_ID != '".$row_outer['RECEIVER_ID']."'
						AND DATE_CONFIRMED IS NOT NULL
						)
					AND PALLET IN
						(SELECT PALLET_ID
						FROM CARGO_TRACKING
						WHERE RECEIVER_ID != '".$row_outer['RECEIVER_ID']."'
						)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$trans_count = $short_term_row['PLT_COUNT'];
			$total_X_grant += $trans_count;

			$sql = "SELECT COUNT(DISTINCT PALLET) PLT_COUNT
					FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
					AND OMH.LR_NUM = '".$vessel."'
					AND CCM.RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
					AND OMH.PUSHED_TO_CT = 'Y'
					AND PALLET IN
						(SELECT PALLET_ID
						FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
						WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
						AND CPCH.ARRIVAL_NUM = '".$vessel."'
						AND CPCH.CUSTOMER_ID != '".$row_outer['RECEIVER_ID']."'
						AND DATE_CONFIRMED IS NOT NULL
						)
					AND PALLET IN
						(SELECT PALLET_ID
						FROM CARGO_TRACKING
						WHERE RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'
						AND ARRIVAL_NUM = '".$vessel."'
						)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$cant_trans_count = $short_term_row['PLT_COUNT'];
			$total_X_deny += $cant_trans_count;

			$sql = "SELECT COUNT(DISTINCT PALLET_ID) PLT_COUNT
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$row_outer['RECEIVER_ID']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$in_name_right_now = $short_term_row['PLT_COUNT'];
			$total_total += $in_name_right_now;
?>
	<tr>
<!--		<td><font size="2" face="Verdana"><b><? echo $row_outer['RECEIVER_ID']; ?></b></font></td> !-->
		<td><font size="2" face="Verdana"><b><? echo $cust_name; ?></b></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=CARLE" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=CARLE"><? echo $carle_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=MATCH" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=MATCH"><? echo $match_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOMATCH" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOMATCH"><? echo $no_match_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NODB" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NODB"><? echo $noDB_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOTRANS" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOTRANS"><? echo $cant_trans_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=YESTRANS" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=YESTRANS"><? echo $trans_count; ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOW" target="sort_popup.php?vessel=<? echo $vessel; ?>&cust=<? echo $row_outer['RECEIVER_ID']; ?>&type=NOW"><? echo $in_name_right_now; ?></a></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor_outer, $row_outer, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
	<tr>
		<td><font size="3" face="Verdana"><b>TOTALS:</b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_carle; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_match; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_nomatch; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_unknown; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_X_deny; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_X_grant; ?></b></font></td>
		<td><font size="3" face="Verdana"><b><? echo $total_total; ?></b></font></td>
	</tr>
</table>
<?
	include("pow_footer.php");
?>