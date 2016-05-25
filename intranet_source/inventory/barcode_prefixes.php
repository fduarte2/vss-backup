<?
/*
*	Adam Walter, Feb 2016
*
*	Barcode prefix changer.
*
***********************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];
	$barcode_prefix = $HTTP_POST_VARS['barcode_prefix'];
	$new_prefix = $HTTP_POST_VARS['new_prefix'];
	$received = $HTTP_POST_VARS['received'];

	if($submit != "" && ($barcode_prefix == "" || $vessel == "")){
		echo "<font color=\"#FF0000\" size=\"5\">At least Vessel and Barcode Prefix need to be entered.</font><br>";
		$submit = "";
	}
/*	if($submit == "Update Barcodes" && $new_prefix == ""){
		echo "<font color=\"#FF0000\" size=\"5\">New Prefix must be selected.</font><br>";
		$submit = "Retrieve Barcodes";
	}*/

	if($submit == "Update Barcodes"){
		$sql = "UPDATE CARGO_ACTIVITY
				SET PALLET_ID = '".$new_prefix."' || SUBSTR(PALLET_ID, ".(strlen($new_prefix) + 1).")
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND PALLET_ID LIKE '".$barcode_prefix."%'";
		if($cust != ""){
			$sql .= " AND CUSTOMER_ID = '".$cust."' ";
		}
		if($comm != ""){
			$sql .= " AND (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE COMMODITY_CODE = '".$comm."') ";
		}
		if($received == "Y"){
			$sql .= " AND (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL) ";
		}
		if($received == "N"){
			$sql .= " AND (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NULL) ";
		}
		$update = ociparse($rfconn, $sql);
		if(!ociexecute($update)){
			echo "<font color=\"#FF0000\" size=\"5\">Error:  The above error could be possibly because one or more prefix changes caused duplicate barcodes on this vessel.</font><br>";
		} else {

			$sql = "UPDATE CARGO_TRACKING
					SET PALLET_ID = '".$new_prefix."' || SUBSTR(PALLET_ID, ".(strlen($new_prefix) + 1).")
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND PALLET_ID LIKE '".$barcode_prefix."%'";
			if($cust != ""){
				$sql .= " AND RECEIVER_ID = '".$cust."' ";
			}
			if($comm != ""){
				$sql .= " AND COMMODITY_CODE = '".$comm."' ";
			}
			if($received == "Y"){
				$sql .= " AND DATE_RECEIVED IS NOT NULL ";
			}
			if($received == "N"){
				$sql .= " AND DATE_RECEIVED IS NULL ";
			}
			$update = ociparse($rfconn, $sql);
			if(!ociexecute($update)){
				echo "<font color=\"#FF0000\" size=\"5\">Error:  The above error could be possibly because one or more prefix changes caused duplicate barcodes on this vessel.</font><br>";
			} else {
				echo "<font color=\"#0000FF\" size=\"5\">Pallets Changed.</font><br>";
			}
		}
		$submit = "Retrieve Barcodes";

	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barcode Prefix Adjustment
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="select" action="barcode_prefixes.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Vessel:  </font></td>
		<td><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL 
				FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT')
					OR ARRIVAL_NUM = '9999999'
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer:  </font></td>
		<td><input type="text" name="cust" size="20" maxlength="20" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Commodity:  </font></td>
		<td><input type="text" name="comm" size="20" maxlength="20" value="<? echo $comm; ?>"></td>
	</tr> 
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Received?:  </td>
		<td><input type="radio" name="received" value="" <? if ($received == ""){?> checked <?}?>>All&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="received" value="Y" <? if ($received == "Y"){?> checked <?}?>>Received Only&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="received" value="N" <? if ($received == "N"){?> checked <?}?>>Not Received Only&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr> 
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Old Prefix:  </font></td>
		<td><input type="text" name="barcode_prefix" size="10" maxlength="10" value="<? echo $barcode_prefix; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Barcodes"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
<?
		$sql = "SELECT PALLET_ID, CT.COMMODITY_CODE, COMP.COMMODITY_NAME, CUSP.CUSTOMER_NAME, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NOT REC') THE_DATE 
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = '".$vessel."'
					AND CT.PALLET_ID LIKE '".$barcode_prefix."%'
					AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE";
		if($cust != ""){
			$sql .= " AND RECEIVER_ID = '".$cust."' ";
		}
		if($comm != ""){
			$sql .= " AND COMP.COMMODITY_CODE = '".$comm."' ";
		}
		if($received == "Y"){
			$sql .= " AND DATE_RECEIVED IS NOT NULL ";
		}
		if($received == "N"){
			$sql .= " AND DATE_RECEIVED IS NULL ";
		}
		$sql .= " ORDER BY PALLET_ID";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
?>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana"><b>No Pallets Found.</b></font></td>
	</tr>
<?
		} else {
			$pallet_count = 0;

			$table = "<form name=\"update\" action=\"barcode_prefixes.php\" method=\"post\">
					<input type=\"hidden\" name=\"barcode_prefix\" value=\"".$barcode_prefix."\">
					<input type=\"hidden\" name=\"comm\" value=\"".$comm."\">
					<input type=\"hidden\" name=\"cust\" value=\"".$cust."\">
					<input type=\"hidden\" name=\"vessel\" value=\"".$vessel."\">
					<input type=\"hidden\" name=\"received\" value=\"".$received."\">
					<tr>
						<td colspan=\"4\" align=\"left\">
							<font size=\"2\" face=\"Verdana\"><b>New Prefix:&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"text\" name=\"new_prefix\" size=\"10\" maxlength=\"10\">&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"submit\" name=\"submit\" value=\"Update Barcodes\">&nbsp;&nbsp;&nbsp;&nbsp;(AAAAA Total Pallets)</b></font>
						</td>
					</tr>
					<tr>
						<td><font size=\"2\" face=\"Verdana\"><b>Barcode</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Comm</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Cust</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Date Received</b></font></td>  
					</tr>";
			do {
				$pallet_count++;
				$table .= "<tr>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "PALLET_ID")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "COMMODITY_CODE")."-".ociresult($short_term_data, "COMMODITY_NAME")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "CUSTOMER_NAME")."</font></td>
							<td><font size=\"2\" face=\"Verdana\">".ociresult($short_term_data, "THE_DATE")."</font></td>
						</tr>";
			}while(ocifetch($short_term_data));

			$table = str_replace("AAAAA", $pallet_count, $table);
		}
		echo $table;
?>
</table>
<?
	}
	include("pow_footer.php");