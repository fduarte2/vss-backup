<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to enter DO-destination info.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel destinations";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
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

	$DO_num = $HTTP_POST_VARS['DO_num'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Retrieve"){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE REMARK = '".$DO_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\">DO# not in system.  Please retype, or send file to TS for import first.</font>";
			$submit = "";
		}
	}

	if($submit == "Save"){
		$cust = $HTTP_POST_VARS['cust'];
		$vessel = $HTTP_POST_VARS['vessel'];
		$comm = $HTTP_POST_VARS['comm'];
		$bol = $HTTP_POST_VARS['bol'];
		$shipto = $HTTP_POST_VARS['ship_to'];
		$carrier = $HTTP_POST_VARS['carrier'];
		$remark1 = str_replace("'", "`", $HTTP_POST_VARS['remark1']);
		$remark2 = str_replace("'", "`", $HTTP_POST_VARS['remark2']);
		$remark_pl = str_replace("'", "`", $HTTP_POST_VARS['remark_pl']);
		$hold = $HTTP_POST_VARS['hold'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM STEEL_PRELOAD_DO_INFORMATION
				WHERE DONUM = '".$DO_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			// nw record.  insert.
			$sql = "INSERT INTO STEEL_PRELOAD_DO_INFORMATION
						(DONUM,
						CUSTOMER_ID,
						LR_NUM,
						COMMODITY_CODE,
						BOL,
						SHIP_TO_ID,
						CARRIER_ID,
						REMARK_1,
						REMARK_2,
						REMARK_PICKLIST,
						CREATED_LOGIN_ID,
						HOLD_STATUS)
					VALUES
						('".$DO_num."',
						'".$cust."',
						'".$vessel."',
						'".$comm."',
						'".$bol."',
						'".$shipto."',
						'".$carrier."',
						'".$remark1."',
						'".$remark2."',
						'".$remark_pl."',
						'".$user."',
						'".$hold."')";
		} else {
			$sql = "UPDATE STEEL_PRELOAD_DO_INFORMATION SET
						CUSTOMER_ID = '".$cust."',
						LR_NUM = '".$vessel."',
						COMMODITY_CODE = '".$comm."',
						BOL = '".$bol."',
						SHIP_TO_ID = '".$shipto."',
						CARRIER_ID = '".$carrier."',
						REMARK_1 = '".$remark1."',
						REMARK_2 = '".$remark2."',
						REMARK_PICKLIST = '".$remark_pl."',
						HOLD_STATUS = '".$hold."'
					WHERE DONUM = '".$DO_num."'";
		}
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);						

		echo "<font color=\"#0000FF\">Save successful.  New Information Displayed.</font>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Preload DO Information - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="steel_DO_dest.php" method="post">
	<tr>
		<td align="left" width="10%">Delivery Order:</td>
		<td><input name="DO_num" size="10" maxlength="10" type="text" value="<? echo $DO_num; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"><br><hr><br></td>
	</tr>
</form>
<?
	if($submit != ""){
?>
<form name="save" action="steel_DO_dest.php" method="post">
<?
		// the next sections only display if "Select" is pressed.

		$sql = "SELECT * FROM STEEL_PRELOAD_DO_INFORMATION WHERE DONUM = '".$DO_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){
			$cust = ociresult($stid, "CUSTOMER_ID");
			$vessel = ociresult($stid, "LR_NUM");
			$comm = ociresult($stid, "COMMODITY_CODE");
			$bol = ociresult($stid, "BOL");
			$shipto = ociresult($stid, "SHIP_TO_ID");
			$carrier = ociresult($stid, "CARRIER_ID");
			$remark1 = ociresult($stid, "REMARK_1");
			$remark2 = ociresult($stid, "REMARK_2");
			$hold = ociresult($stid, "HOLD_STATUS");

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			if(ocifetch($stid)){
				$vesname = ociresult($stid, "VESSEL_NAME");
			} else {
				$vesname = "- N/A";
			}
			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			if(ocifetch($stid)){
				$commname = ociresult($stid, "COMMODITY_NAME");
			} else {
				$commname = "- N/A";
			}
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			if(ocifetch($stid)){
				$custname = ociresult($stid, "CUSTOMER_NAME");
			} else {
				$custname = "- N/A";
			}




		} else {
			$sql = "SELECT COMMODITY_NAME, CUSTOMER_NAME, LR_NUM, VESSEL_NAME, CT.RECEIVER_ID, CT.ARRIVAL_NUM, CT.COMMODITY_CODE
					FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
					WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
						AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
						AND CT.REMARK = '".$DO_num."'
						AND COMP.COMMODITY_TYPE = 'STEEL'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$cust = ociresult($stid, "RECEIVER_ID");
			$vessel = ociresult($stid, "LR_NUM");
			$comm = ociresult($stid, "COMMODITY_CODE");
			$vesname = ociresult($stid, "VESSEL_NAME");
			$commname = ociresult($stid, "COMMODITY_NAME");
			$custname = ociresult($stid, "CUSTOMER_NAME");

			$bol = "";
			$shipto = "";
			$carrier = "";
			$remark1 = "";
			$remark2 = "";
		}
?>
<input type="hidden" name="DO_num" value="<? echo $DO_num; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="comm" value="<? echo $comm; ?>">
	<tr>
		<td width="10%"><b>HOLD STATUS:</b></td>
		<td><select name="hold"><option value="">Normal</option>
								<option value="Y" <? if($hold == "Y"){?> selected <?}?>>ON HOLD</option>
			</select></td>
	</tr>
	</tr>
	<tr>
		<td width="10%">Customer:</td>
		<td><? echo $custname; ?></td>
	</tr>
	<tr>
		<td>Vessel:</td>
		<td><? echo $vessel." - ".$vesname; ?></td>
	</tr>
	<tr>
		<td>Commodity:</td>
		<td><? echo $commname; ?></td>
	</tr>
	<tr>
		<td>Manifst BOL:</td>
		<td><input type="text" name="bol" size="15" maxlength="15" value="<? echo $bol; ?>"></td>
	</tr>
	<tr>
		<td>Ship To Address:</td>
		<td><select name="ship_to"><option value="">Select a Destination</option>
<?

	$sql = "SELECT *
			FROM STEEL_SHIPPING_TABLE
			WHERE STEEL_TYPE IN
				(SELECT STEEL_TYPE FROM STEEL_COMM_TO_TYPE_MAP WHERE COMMODITY_CODE = '".$comm."')
			ORDER BY NAME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "SHIP_TO_ID"); ?>"<? if($shipto == ociresult($stid, "SHIP_TO_ID")){?> selected <?}?>><? echo ociresult($stid, "NAME")." ".ociresult($stid, "ADDRESS_1")." ".ociresult($stid, "ADDRESS_2")."  ".ociresult($stid, "CITY")." ".ociresult($stid, "STATE")." ".ociresult($stid, "ZIP"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td>Carrier:</td>
		<td><select name="carrier"><option value="">Select a Carrier</option>
<?

	$sql = "SELECT CARRIER_ID, NAME
			FROM STEEL_CARRIERS
			ORDER BY NAME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "CARRIER_ID"); ?>"<? if($carrier == ociresult($stid, "CARRIER_ID")){?> selected <?}?>><? echo ociresult($stid, "NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td>Remarks:</td>
		<td><input type="text" name="remark1" size="60" maxlength="60" value="<? echo $remark1; ?>"> (BoL)</td>
	</tr>
	<tr>
		<td>Remarks:</td>
		<td><input type="text" name="remark2" size="60" maxlength="60" value="<? echo $remark2; ?>"> (BoL)</td>
	</tr>
	<tr>
		<td>Remarks:</td>
		<td><input type="text" name="remark_pl" size="60" maxlength="60" value="<? echo $remark_pl; ?>"> (Picklist)</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"><br><hr><br></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>