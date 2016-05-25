<?
/*
*
*	Adam Walter, Aug 2013.
*
*	A screen for inventory to view information about a steel barcode.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel Barcode History";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$barcode = $HTTP_POST_VARS['barcode'];
//	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
/*	$status = $HTTP_POST_VARS['status'];
	if($status == "NORMAL"){
		$extra_sql = " AND ORDER_STATUS IS NULL ";
	} elseif($status == "All"){
		$extra_sql = "";
	} else {
		$extra_sql = " AND ORDER_STATUS = '".$status."'";
	}*/
//	echo $submit."aaa<br>";

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Steel Pallet-Reconciliation Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="steel_pallet_recon.php" method="post">
<!--	<tr>
		<td align="left" colspan="2"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'STEEL' ORDER BY LR_NUM DESC";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if(ociresult($stid, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($stid, "THE_VESSEL") ?></option>
<?
		}
?>
					</select></font></td>
	</tr> !-->
	<tr>
		<td colspan="2">Barcode: <input type="text" name="barcode" size="12" maxlength="12" value="<? echo $barcode; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="View Barcode Info"></td>
	</tr>
</form>
</form>
</table>
<?
	if($barcode != ""){
//		$cur_qty = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#CC3333">
<!--		<td><font size="2" face="Verdana"><b>Customer</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>DO#</b></font></td>
		<td><font size="2" face="Verdana"><b>P.O. Assigned On</b></font></td>
		<td><font size="2" face="Verdana"><b>PO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Left InHouse after Shipment</b></font></td>
		<td><font size="2" face="Verdana"><b>Ship To Name</b></font></td>
	</tr>
<?
//					AND CT.ARRIVAL_NUM = '".$vessel."'";
		$sql = "SELECT CT.REMARK, TO_CHAR(SPDI.CREATED_DATE, 'MM/DD/YYYY') THE_CREATE, CT.QTY_RECEIVED, SSI.NAME, CT.RECEIVER_ID, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_SHIPPING_TABLE SSI
				WHERE CT.REMARK = SPDI.DONUM
					AND SPDI.SHIP_TO_ID = SSI.SHIP_TO_ID
					AND CT.PALLET_ID = '".$barcode."'";
		$main_info = ociparse($rfconn, $sql);
		ociexecute($main_info);
		if(!ocifetch($main_info)){
?>
	<tr>
		<td colspan="7"><font size="2" face="Verdana">Pallet/Arrival# combination not found in system.</font></td>
	</tr>
<?
		} else {
			do {

				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".ociresult($main_info, "ARRIVAL_NUM")."'";
				$ves_name = ociparse($rfconn, $sql);
				ociexecute($ves_name);
				ocifetch($ves_name);
				$vessel_name = ociresult($main_info, "ARRIVAL_NUM")." - ".ociresult($ves_name, "VESSEL_NAME");

				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".ociresult($main_info, "RECEIVER_ID")."'";
				$cust_name = ociparse($rfconn, $sql);
				ociexecute($cust_name);
				ocifetch($cust_name);
				$customer_name = ociresult($cust_name, "CUSTOMER_NAME");

?>
	<tr bgcolor="#9999FF">
		<td colspan="7"><font size="2" face="Verdana"><b>Customer#&nbsp;&nbsp;&nbsp;<? echo $customer_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LR#&nbsp;&nbsp;&nbsp;<? echo $vessel_name; ?></b></font></td>
	</tr>
<?
				$qty_ih = ociresult($main_info, "QTY_RECEIVED");

				$sql = "SELECT NVL(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI'), 'NONE') DATE_SHIP, NVL(QTY_CHANGE, 0) THE_CHG, PORT_ORDER_NUM
						FROM CARGO_ACTIVITY CA, STEEL_ORDERS SO
							WHERE CA.ORDER_NUM(+) = SO.PORT_ORDER_NUM
							AND SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
							AND SO.DONUM = '".ociresult($main_info, "REMARK")."'
							AND CA.ARRIVAL_NUM = '".ociresult($main_info, "ARRIVAL_NUM")."'
							AND CA.PALLET_ID = '".$barcode."'
							AND CA.CUSTOMER_ID = '".ociresult($main_info, "RECEIVER_ID")."'
						ORDER BY ACTIVITY_NUM";
				$loop_1 = ociparse($rfconn, $sql);
				ociexecute($loop_1);
				if(!ocifetch($loop_1)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "REMARK"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "THE_CREATE"); ?></font></td>
		<td colspan="5"><font size="2" face="Verdana">No Outbound PO for This Barcode Found.</font></td>
	</tr>
<?
				} elseif(ociresult($loop_1, "DATE_SHIP") == "NONE"){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "REMARK"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "THE_CREATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($loop_1, "PORT_ORDER_NUM"); ?></font></td>
		<td colspan="4"><font size="2" face="Verdana">No Outbound Scanned Activity</font></td>
	</tr>
<?
				} else {
					do {
						$qty_ih -= ociresult($loop_1, "THE_CHG");
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "REMARK"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "THE_CREATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($loop_1, "PORT_ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($loop_1, "DATE_SHIP"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($loop_1, "THE_CHG"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $qty_ih; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($main_info, "NAME"); ?></font></td>
	</tr>
<?
					} while(ocifetch($loop_1));
				}
			} while(ocifetch($main_info));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>