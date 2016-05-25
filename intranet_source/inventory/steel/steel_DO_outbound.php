<?
/*
*	Adam Walter, Apr 2013
*
*	Report that gives INVE data on Steel DO's
************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Steel";
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

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Retrieve Data"){
		$DO_num = $HTTP_POST_VARS['DO_num'];
		$DO_num_freeform = $HTTP_POST_VARS['DO_num_freeform'];

		// were neither entered?
		if($DO_num == "" && $DO_num_freeform == ""){
			echo "<font color=\"#FF0000\">Please enter a DO#.</font>";
			$submit = "";
		} else {
			if($DO_num != "" && $DO_num_freeform != ""){
				// do nothing, keep the dropdown-box value
			} elseif($DO_num == "" && $DO_num_freeform != ""){
				$DO_num = $DO_num_freeform;
			}
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Steel Outbound-by-DO# Report</font>
         <hr><? //include("../bni_links.php"); ?>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bills" action="steel_DO_outbound.php" method="post">
	<tr>
		<td align="left" width="10%">Delivery Order#:</td>
		<td><select name="DO_num"><option value="">Select a DO#</option>
<?
	$sql = "SELECT DISTINCT REMARK 
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CP.COMMODITY_TYPE = 'STEEL'
				AND REMARK IS NOT NULL
			ORDER BY REMARK DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "REMARK"); ?>"<? if(ociresult($stid, "REMARK") == $DO_num){?> selected <?}?>><? echo ociresult($stid, "REMARK"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><b>--- OR ---</b></td>
	</tr>
	<tr>
		<td align="left" width="10%">Freeform:</td>
		<td><input name="DO_num_freeform" size="10" maxlength="10" type="text" value="<? echo $DO_num; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"><br><hr><br></td>
	</tr>
</form>
<?
	if($submit != ""){
		$grand_total_pcs = 0;
		$grand_total_wt = 0;

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana"><b>Results for DO# <? echo $DO_num; ?>:</b></font></td>
	</tr>
	<tr>
		<td bgcolor="FFCC99"><font size="2" face="Verdana"><b>PO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Carrier</b></font></td>
		<td><font size="2" face="Verdana"><b>Trailer ID</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>#PCS</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
	<tr>
<?
		$sql = "SELECT DISTINCT CA.ORDER_NUM
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO, COMMODITY_PROFILE CP
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CA.SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = SO.PORT_ORDER_NUM
					AND SO.DONUM = SPDI.DONUM
					AND SO.ORDER_STATUS = 'COMPLETE'
					AND SPDI.CARRIER_ID = SC.CARRIER_ID
					AND CT.REMARK = '".$DO_num."'
					AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
					AND CP.COMMODITY_TYPE = 'STEEL'
				ORDER BY CA.ORDER_NUM";
		$POs = ociparse($rfconn, $sql);
		ociexecute($POs);
		if(!ocifetch($POs)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana"><b>No Outbound Shipments for Do# <? echo $DO_num; ?>:</b></font></td>
	</tr>
<?
		} else {
			do {
				$order_pcs = 0;
				$order_wt = 0;
?>
	<tr>
		<td colspan="9" bgcolor="FFCC99"><font size="2" face="Verdana"><b><? echo ociresult($POs, "ORDER_NUM"); ?></b></font></td>
	</tr>
<?
				$sql = "SELECT SC.NAME, SO.LICENSE_NUM, CT.PALLET_ID, CT.CARGO_DESCRIPTION, SPDI.BOL, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') DATE_SHIP, 
							SUM(CA.QTY_CHANGE) THE_SUM, SUM((CA.QTY_CHANGE / CT.QTY_RECEIVED) * CT.WEIGHT) THE_WEIGHT
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO, COMMODITY_PROFILE CP
						WHERE CA.PALLET_ID = CT.PALLET_ID
							AND CA.CUSTOMER_ID = CT.RECEIVER_ID
							AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
							AND CA.SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
							AND CA.ORDER_NUM = SO.PORT_ORDER_NUM
							AND CA.ORDER_NUM = '".ociresult($POs, "ORDER_NUM")."'
							AND SO.DONUM = SPDI.DONUM
							AND SO.ORDER_STATUS = 'COMPLETE'
							AND SPDI.CARRIER_ID = SC.CARRIER_ID
							AND CT.REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'
						GROUP BY SC.NAME, SO.LICENSE_NUM, CT.PALLET_ID, CT.CARGO_DESCRIPTION, SPDI.BOL, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
						ORDER BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), SPDI.BOL, SC.NAME, CT.PALLET_ID";
//				echo $sql."<br>";
				$PLTs = ociparse($rfconn, $sql);
				ociexecute($PLTs);
				while(ocifetch($PLTs)){
					$order_pcs += ociresult($PLTs, "THE_SUM");
					$order_wt += round(ociresult($PLTs, "THE_WEIGHT"));
					$grand_total_pcs += ociresult($PLTs, "THE_SUM");
					$grand_total_wt += round(ociresult($PLTs, "THE_WEIGHT"));
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "DATE_SHIP"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "BOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "LICENSE_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "CARGO_DESCRIPTION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($PLTs, "THE_SUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo round(ociresult($PLTs, "THE_WEIGHT")); ?></font></td>
	</tr>
<?
				}
?>
	<tr>
		<td colspan="7" bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo ociresult($POs, "ORDER_NUM"); ?> Total:</b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $order_pcs; ?></b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $order_wt; ?></b></font></td>
	</tr>
<?
			} while(ocifetch($POs));
		}
?>
	<tr bgcolor="99CCFF">
		<td colspan="7"><font size="3" face="Verdana"><b>GRAND Total:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $grand_total_pcs; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $grand_total_wt; ?></b></font></td>
	</tr>
<?

	}
?>
</table>
<?
	include("pow_footer.php");


