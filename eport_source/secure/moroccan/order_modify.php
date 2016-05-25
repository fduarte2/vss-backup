<?
/*
*
*	Adam Walter, Aug-Sep 2013.
*
*	Main Listing screen for Moroccan Clementine Orders.
*
***********************************************************************************/

/*
//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
*/
	include("db_def.php");


//	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");

	$ordernum = $HTTP_POST_VARS['ordernum'];
	if($ordernum == "" && $HTTP_GET_VARS['ordernum'] != ""){
		$ordernum = $HTTP_GET_VARS['ordernum'];
		$sql = "UPDATE MOR_ORDER
				SET ORDERSTATUSID = '".$HTTP_GET_VARS['setstat']."',
					SYNC_TO_CLR = NULL
				WHERE ORDERNUM = '".$ordernum."'
					AND CUST = '".$cust."'";
		$update = ociparse($conn, $sql);
		ociexecute($update);
	}
//	echo $ordernum."<br>";
//	$action_type = $HTTP_GET_VARS['action_type'];
	$submit = $HTTP_POST_VARS['submit'];

	$submitted_values_top = array();
	$submitted_values_bottom = array();

	$submitted_values_top['ordernum_add'] = strtoupper(sql_safe($HTTP_POST_VARS['ordernum_add']));
	$submitted_values_top['vessel'] = sql_safe($HTTP_POST_VARS['vessel']);
	$submitted_values_top['comm'] = sql_safe($HTTP_POST_VARS['comm']);
	$submitted_values_top['status'] = sql_safe($HTTP_POST_VARS['status']);
	$submitted_values_top['mor_cust'] = sql_safe($HTTP_POST_VARS['mor_cust']);
	$submitted_values_top['direct_order'] = sql_safe($HTTP_POST_VARS['direct_order']);
	$submitted_values_top['consignee'] = sql_safe($HTTP_POST_VARS['consignee']);
	$submitted_values_top['TE_status'] = sql_safe($HTTP_POST_VARS['TE_status']);
	$submitted_values_top['cust_PO'] = sql_safe($HTTP_POST_VARS['cust_PO']);
	$submitted_values_top['SNMG'] = sql_safe($HTTP_POST_VARS['SNMG']);
	$submitted_values_top['pickup'] = sql_safe($HTTP_POST_VARS['pickup']);
	$submitted_values_top['load_type'] = sql_safe($HTTP_POST_VARS['load_type']);
	$submitted_values_top['delivery'] = sql_safe($HTTP_POST_VARS['delivery']);
	$submitted_values_top['comments'] = sql_safe($HTTP_POST_VARS['comments']);
	$submitted_values_top['transporter'] = sql_safe($HTTP_POST_VARS['transporter']);
	$submitted_values_top['trans_charge'] = sql_safe($HTTP_POST_VARS['trans_charge']);
	$submitted_values_top['driver_name'] = sql_safe($HTTP_POST_VARS['driver_name']);
	$submitted_values_top['trailernum'] = sql_safe($HTTP_POST_VARS['trailernum']);
	$submitted_values_top['trucktag'] = sql_safe($HTTP_POST_VARS['trucktag']);
	$submitted_values_top['sealnum'] = sql_safe($HTTP_POST_VARS['sealnum']);
	$submitted_values_top['bol_comment'] = sql_safe($HTTP_POST_VARS['bol_comment']);
	$submitted_values_top['temp_recorder'] = sql_safe($HTTP_POST_VARS['temp_recorder']);
	$submitted_values_top['commentcancel'] = sql_safe($HTTP_POST_VARS['commentcancel']);
	$submitted_values_top['t_and_e'] = sql_safe($HTTP_POST_VARS['t_and_e']);

	$orderdet_ctns = $HTTP_POST_VARS['orderdet_ctns'];
	$orderdet_price = $HTTP_POST_VARS['orderdet_price'];
	$orderdet_sizelow = $HTTP_POST_VARS['orderdet_sizelow'];
	$orderdet_sizehigh = $HTTP_POST_VARS['orderdet_sizehigh'];
	$orderdet_weightkg = $HTTP_POST_VARS['orderdet_weightkg'];
	$orderdet_comments = $HTTP_POST_VARS['orderdet_comments'];
	$order_det_id = $HTTP_POST_VARS['order_det_id'];
	$order_det_size = $HTTP_POST_VARS['order_det_size'];


	for($i = 0; $i < 10; $i++){
		$submitted_values_bottom[$i]['ctns'] = $orderdet_ctns[$i];
		$submitted_values_bottom[$i]['price'] = $orderdet_price[$i];
		if($submitted_values_bottom[$i]['price'] == 0){
			$submitted_values_bottom[$i]['price'] = "";
		}
		$submitted_values_bottom[$i]['sizelow'] = $orderdet_sizelow[$i];
		$submitted_values_bottom[$i]['sizehigh'] = $orderdet_sizehigh[$i];
		$submitted_values_bottom[$i]['weightkg'] = $orderdet_weightkg[$i];
		$submitted_values_bottom[$i]['comments'] = $orderdet_comments[$i];
		$submitted_values_bottom[$i]['id'] = $order_det_id[$i];
		$submitted_values_bottom[$i]['size'] = $order_det_size[$i];
	}

	if($submit == "Save"){
		// whee!
		$save_message = validate_entries($submitted_values_top, $submitted_values_bottom, $action_type, $ordernum, $cust, $conn);
		if($save_message == ""){
			// checks passed.  save.

			if($action_type == "ADD"){
				$sql = "INSERT INTO MOR_ORDER
							(ORDERNUM,
							COMMENTS,
							COMMENTSCANCEL,
							COMMODITYCODE,
							CONSIGNEEID,
							CUSTOMERID,
							CUSTOMERPO,
							DELIVERYDATE,
							DIRECTORDER,
							DRIVERNAME,
							LASTUPDATEUSER,
							LASTUPDATEDATETIME,
							LOADTYPE,
							ORDERSTATUSID,
							PICKUPDATE,
							TESTATUS,
							TRANSPORTCHARGES,
							TRANSPORTERID,
							TRAILERNUM,
							TRUCKTAG,
							VESSELID,
							SEALNUM,
							SNMGNUM,
							ZUSER1,
							ZUSER2,
							T_AND_E,
							CUST)
						VALUES
							('".$submitted_values_top['ordernum_add']."',
							'".$submitted_values_top['comments']."',
							'".$submitted_values_top['commentcancel']."',
							'".$submitted_values_top['comm']."',
							'".$submitted_values_top['consignee']."',
							'".$submitted_values_top['mor_cust']."',
							'".$submitted_values_top['cust_PO']."',
							TO_DATE('".$submitted_values_top['delivery']."', 'MM/DD/YYYY'),
							'".$submitted_values_top['direct_order']."',
							'".$submitted_values_top['driver_name']."',
							'".$user."',
							SYSDATE,
							'".$submitted_values_top['load_type']."',
							'".$submitted_values_top['status']."',
							TO_DATE('".$submitted_values_top['pickup']."', 'MM/DD/YYYY'),
							'".$submitted_values_top['TE_status']."',
							'".$submitted_values_top['trans_charge']."',
							'".$submitted_values_top['transporter']."',
							'".$submitted_values_top['trailernum']."',
							'".$submitted_values_top['trucktag']."',
							'".$submitted_values_top['vessel']."',
							'".$submitted_values_top['sealnum']."',
							'".$submitted_values_top['SNMG']."',
							'".$submitted_values_top['bol_comment']."',
							'".$submitted_values_top['temp_recorder']."',
							'".$submitted_values_top['t_and_e']."',
							'".$cust."')";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);

				for($i = 0; $i < 10; $i++){
					if($submitted_values_bottom[$i]['size'] != ""){
						$sql = "INSERT INTO MOR_ORDERDETAIL
									(ORDERNUM,
									ORDERDETAILID,
									COMMENTS,
									ORDERQTY,
									ORDERSIZEID,
									PRICE,
									SIZEHIGH,
									SIZELOW,
									WEIGHTKG,
									CUST)
								VALUES
									('".$submitted_values_top['ordernum_add']."',
									MOR_ORDERDETAIL_SEQ.NEXTVAL,
									'".$submitted_values_bottom[$i]['comments']."',
									'".$submitted_values_bottom[$i]['ctns']."',
									'".$submitted_values_bottom[$i]['size']."',
									'".$submitted_values_bottom[$i]['price']."',
									NVL('".$submitted_values_bottom[$i]['sizehigh']."', (SELECT SIZEHIGH FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									NVL('".$submitted_values_bottom[$i]['sizelow']."', (SELECT SIZELOW FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									NVL('".$submitted_values_bottom[$i]['weightkg']."', (SELECT WEIGHTKG FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									'".$cust."')";
						$update = ociparse($conn, $sql);
						ociexecute($update, OCI_NO_AUTO_COMMIT);
					}
				}

				$sql = "UPDATE MOR_ORDER
						SET TOTALCOUNT = (SELECT SUM(ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."' AND CUST = '".$cust."')
						WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
				$sql = "UPDATE MOR_ORDER
						SET TOTALQUANTITYKG = (SELECT SUM(WEIGHTKG * ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."' AND CUST = '".$cust."')
						WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM MOR_ORDERDETAIL
						WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."'
							AND PRICE IS NULL";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$sql = "UPDATE MOR_ORDER
							SET TOTALPRICE = NULL
							WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."'";
					$update = ociparse($conn, $sql);
					ociexecute($update, OCI_NO_AUTO_COMMIT);
				} else {
					$sql = "UPDATE MOR_ORDER
							SET TOTALPRICE = (SELECT SUM(PRICE * ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."' AND CUST = '".$cust."')
							WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."'";
					$update = ociparse($conn, $sql);
					ociexecute($update, OCI_NO_AUTO_COMMIT);
				}

				$ordernum = $submitted_values_top['ordernum_add']; // to make sure the page now knows it's not "adding" anymore.
				echo "<font color=\"#0000FF\">Order ".$submitted_values_top['ordernum_add']." Saved.</font><br><br>";
			} else {
				$sql = "UPDATE MOR_ORDER
						SET 
							COMMENTS = '".$submitted_values_top['comments']."',
							COMMENTSCANCEL = '".$submitted_values_top['commentcancel']."',
							COMMODITYCODE = '".$submitted_values_top['comm']."',
							CONSIGNEEID = '".$submitted_values_top['consignee']."',
							CUSTOMERID = '".$submitted_values_top['mor_cust']."',
							CUSTOMERPO = '".$submitted_values_top['cust_PO']."',
							DELIVERYDATE = TO_DATE('".$submitted_values_top['delivery']."', 'MM/DD/YYYY'),
							DIRECTORDER = '".$submitted_values_top['direct_order']."',
							DRIVERNAME = '".$submitted_values_top['driver_name']."',
							LASTUPDATEUSER = '".$user."',
							LASTUPDATEDATETIME = SYSDATE,
							LOADTYPE = '".$submitted_values_top['load_type']."',
							ORDERSTATUSID = '".$submitted_values_top['status']."',
							PICKUPDATE = TO_DATE('".$submitted_values_top['pickup']."', 'MM/DD/YYYY'),
							TESTATUS = '".$submitted_values_top['TE_status']."',
							TRANSPORTCHARGES = '".$submitted_values_top['trans_charge']."',
							TRANSPORTERID = '".$submitted_values_top['transporter']."',
							TRAILERNUM = '".$submitted_values_top['trailernum']."',
							TRUCKTAG = '".$submitted_values_top['trucktag']."',
							VESSELID = '".$submitted_values_top['vessel']."',
							SEALNUM = '".$submitted_values_top['sealnum']."',
							ZUSER1 = '".$submitted_values_top['bol_comment']."',
							ZUSER2 = '".$submitted_values_top['temp_recorder']."',
							T_AND_E = '".$submitted_values_top['t_and_e']."',
							SYNC_TO_CLR = NULL,
							SNMGNUM = '".$submitted_values_top['SNMG']."'
						WHERE ORDERNUM = '".$ordernum."'
							AND CUST = '".$cust."'";
//					echo $sql."<br>";
					$update = ociparse($conn, $sql);
					ociexecute($update, OCI_NO_AUTO_COMMIT);

					if($submitted_values_top['status'] == 9){
						PrepareMail($ordernum, $conn);
					}

				for($i = 0; $i < 10; $i++){
					if($submitted_values_bottom[$i]['id'] == "" && $submitted_values_bottom[$i]['size'] == ""){
						// case A:  the row isnt chosen, AND didnt exist.
						// do nothing
						$submitted_values_bottom[$i]['comments'] = "";
						$submitted_values_bottom[$i]['ctns'] = "";
						$submitted_values_bottom[$i]['size'] = "";
						$submitted_values_bottom[$i]['price'] = "";
						$submitted_values_bottom[$i]['sizehigh'] = "";
						$submitted_values_bottom[$i]['sizelow'] = "";
						$submitted_values_bottom[$i]['weightkg'] = "";
						$submitted_values_bottom[$i]['id'] = "";
					} elseif($submitted_values_bottom[$i]['id'] == "" && $submitted_values_bottom[$i]['size'] != ""){
						// case B:  they just added this row
						$sql = "INSERT INTO MOR_ORDERDETAIL
									(ORDERNUM,
									ORDERDETAILID,
									COMMENTS,
									ORDERQTY,
									ORDERSIZEID,
									PRICE,
									SIZEHIGH,
									SIZELOW,
									WEIGHTKG,
									CUST)
								VALUES
									('".$ordernum."',
									MOR_ORDERDETAIL_SEQ.NEXTVAL,
									'".$submitted_values_bottom[$i]['comments']."',
									'".$submitted_values_bottom[$i]['ctns']."',
									'".$submitted_values_bottom[$i]['size']."',
									'".$submitted_values_bottom[$i]['price']."',
									NVL('".$submitted_values_bottom[$i]['sizehigh']."', (SELECT SIZEHIGH FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									NVL('".$submitted_values_bottom[$i]['sizelow']."', (SELECT SIZELOW FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									NVL('".$submitted_values_bottom[$i]['weightkg']."', (SELECT WEIGHTKG FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									'".$cust."')";
						$update = ociparse($conn, $sql);
						ociexecute($update, OCI_NO_AUTO_COMMIT);
					} elseif($submitted_values_bottom[$i]['id'] != "" && $submitted_values_bottom[$i]['size'] == ""){
						// case C:  this row used to exist, but was removed by user
						// also, clear the row of data.
						$sql = "DELETE FROM MOR_ORDERDETAIL
								WHERE ORDERDETAILID = '".$submitted_values_bottom[$i]['id']."'
									AND ORDERNUM = '".$ordernum."'
									AND CUST = '".$cust."'";
						$update = ociparse($conn, $sql);
						ociexecute($update, OCI_NO_AUTO_COMMIT);
						$submitted_values_bottom[$i]['comments'] = "";
						$submitted_values_bottom[$i]['ctns'] = "";
						$submitted_values_bottom[$i]['size'] = "";
						$submitted_values_bottom[$i]['price'] = "";
						$submitted_values_bottom[$i]['sizehigh'] = "";
						$submitted_values_bottom[$i]['sizelow'] = "";
						$submitted_values_bottom[$i]['weightkg'] = "";
						$submitted_values_bottom[$i]['id'] = "";
					} elseif($submitted_values_bottom[$i]['id'] != "" && $submitted_values_bottom[$i]['size'] != ""){
						// case D:  they changed an existing row
						$sql = "UPDATE MOR_ORDERDETAIL
								SET 
									COMMENTS = '".$submitted_values_bottom[$i]['comments']."',
									ORDERQTY = '".$submitted_values_bottom[$i]['ctns']."',
									ORDERSIZEID = '".$submitted_values_bottom[$i]['size']."',
									PRICE = '".$submitted_values_bottom[$i]['price']."',
									SIZEHIGH = NVL('".$submitted_values_bottom[$i]['sizehigh']."', (SELECT SIZEHIGH FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									SIZELOW = NVL('".$submitted_values_bottom[$i]['sizelow']."', (SELECT SIZELOW FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."')),
									WEIGHTKG = NVL('".$submitted_values_bottom[$i]['weightkg']."', (SELECT WEIGHTKG FROM MOR_COMMODITYSIZE WHERE SIZEID = '".$submitted_values_bottom[$i]['size']."'))
								WHERE ORDERNUM = '".$ordernum."'
									AND ORDERDETAILID = '".$submitted_values_bottom[$i]['id']."'
									AND CUST = '".$cust."'";
						$update = ociparse($conn, $sql);
						ociexecute($update, OCI_NO_AUTO_COMMIT);
					} else {
						// there are no other cases.  yay!
					}
				}

				$sql = "UPDATE MOR_ORDER
						SET TOTALCOUNT = (SELECT SUM(ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."')
						WHERE ORDERNUM = '".$ordernum."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
				$sql = "UPDATE MOR_ORDER
						SET TOTALQUANTITYKG = (SELECT SUM(WEIGHTKG * ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."')
						WHERE ORDERNUM = '".$ordernum."'";
				$update = ociparse($conn, $sql);
				ociexecute($update, OCI_NO_AUTO_COMMIT);
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM MOR_ORDERDETAIL
						WHERE ORDERNUM = '".$ordernum."'
							AND PRICE IS NULL";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$sql = "UPDATE MOR_ORDER
							SET TOTALPRICE = NULL
							WHERE ORDERNUM = '".$ordernum."'";
					$update = ociparse($conn, $sql);
					ociexecute($update, OCI_NO_AUTO_COMMIT);
				} else {
					$sql = "UPDATE MOR_ORDER
							SET TOTALPRICE = (SELECT SUM(PRICE * ORDERQTY) FROM MOR_ORDERDETAIL WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."')
							WHERE ORDERNUM = '".$ordernum."'";
					$update = ociparse($conn, $sql);
					ociexecute($update, OCI_NO_AUTO_COMMIT);
				}
				echo "<font color=\"#0000FF\">Order ".$ordernum." Saved.</font><br><br>";
			}

			ocicommit($conn);
			$success = true;
			
		} else {
			// echo error to screen.
			echo "<font color=\"#FF0000\"><b>Changes were not saved for the following reasons:<br><br>".$save_message."<br>Please resubmit after correction.</b></font>";
			$success = false;
		}
	}




	if($ordernum == ""){
		$action_type = "ADD";
		$new_disabled = "disabled";
		$not_new_disabled = "";
	} else {
		$action_type = "UPDATE";
		$new_disabled = "";
		$not_new_disabled = "disabled";
	}








	$display_order_info = array();
	$display_orderdet_info = array();
	get_display_values($display_order_info, $display_orderdet_info, $submitted_values_top, $submitted_values_bottom, $submit, $ordernum, $cust, $success, $conn);
?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Order Modification</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="4" face="Verdana"><b>Order # <? echo $ordernum; ?></b><? if($display_order_info['status'] >= 3){?>
				<a href="picklist_print.php?ordernum=<? echo $ordernum; ?>&cust=<? echo $cust; ?>" target="picklist_print.php?ordernum=<? echo $ordernum; ?>&cust=<? echo $cust; ?>">Print Picklist</a><?}?></font></td>
	</tr>
</table>
<?
/*
*	And, the big part.
*********************/
	$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CTNS, SUM(BATCH_ID) THE_DMG
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$ordernum."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_DESCRIPTION IS NULL
				AND SERVICE_CODE = '6'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$plts_out = ociresult($stid, "THE_PLTS");
	$ctns_out = ociresult($stid, "THE_CTNS");
	$dmg_out = ociresult($stid, "THE_DMG");


?>
<form name="order_info" action="order_modify_index.php" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><input type="submit" name="submit" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Reload Current Values"></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<input type="hidden" name="ordernum" value="<? echo $ordernum; ?>">
<input type="hidden" name="action_type" value="<? echo $action_type; ?>">
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Vessel:</font></td>
		<td width="40%" align="left"><select name="vessel"><option value="">Please Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) IN
				(SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 0)
			ORDER BY LR_NUM";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if(ociresult($stid, "LR_NUM") == $display_order_info['vessel']){?> selected <?}?>><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></option>

<?
	}
?>
				</select></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Order#:</font></td>
		<td width="40%" align="left"><input type="text" name="ordernum_add" size="20" maxlength="20" value="<? echo $ordernum; ?>"<? echo $not_new_disabled; ?>></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Commodity Code:</font></td>
		<td width="40%" align="left"><select name="comm"><option value="">Please Select a Commodity</option>
<?
	$sql = "SELECT PORT_COMMODITY_CODE, DC_COMMODITY_NAME
			FROM MOR_COMMODITY
			WHERE CUST = '".$cust."'
			ORDER BY PORT_COMMODITY_CODE";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "PORT_COMMODITY_CODE"); ?>"<? if(ociresult($stid, "PORT_COMMODITY_CODE") == $display_order_info['comm']){?> selected <?}?>><? echo ociresult($stid, "DC_COMMODITY_NAME"); ?></option>

<?
	}
?>
				</select></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Order Status:</font></td>
		<td width="40%" align="left"><select name="status">
<?
	$sql = "SELECT ORDERSTATUSID, DESCR
			FROM MOR_ORDERSTATUS
			ORDER BY ORDERSTATUSID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "ORDERSTATUSID"); ?>"<? if(ociresult($stid, "ORDERSTATUSID") == $display_order_info['status']){?> selected <?}?>><? echo ociresult($stid, "ORDERSTATUSID")." - ".ociresult($stid, "DESCR"); ?></option>

<?
	}
?>
				</select>
				<? if($display_order_info['status'] =="1"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="order_modify_index?order_num=<? echo $ordernum; ?>&setstat=2">Set Status To: Submitted</a><?}?>
				<? if($display_order_info['status'] =="3"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="order_modify_index?order_num=<? echo $ordernum; ?>&setstat=4">Set Status To: Truckloading</a><?}?>
				<? if(($display_order_info['status'] =="7" || $display_order_info['status'] =="4") && $plts_out > 0){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="order_modify_index?order_num=<? echo $ordernum; ?>&setstat=8">Set Status To: Order Confirmed</a><?}?>
				<? if($display_order_info['status'] =="8"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="order_modify_index?order_num=<? echo $ordernum; ?>&setstat=9">Set Status To: Order Complete</a><?}?></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Customer:</font></td>
		<td width="40%" align="left"><select name="mor_cust"><option value="">Please Select a Customer</option>
<?
	$sql = "SELECT CUSTOMERID, CUSTOMERNAME
			FROM MOR_CUSTOMER
			WHERE CUST = '".$cust."'
			ORDER BY CUSTOMERID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "CUSTOMERID"); ?>"<? if(ociresult($stid, "CUSTOMERID") == $display_order_info['mor_cust']){?> selected <?}?>><? echo ociresult($stid, "CUSTOMERNAME"); ?></option>

<?
	}
?>
				</select></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Direct Order:</font></td>
		<td width="40%" align="left"><select name="direct_order"><option value="DIRECT">DIRECT</option>
				</select></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Consignee:</font></td>
		<td width="40%" align="left"><select name="consignee"><option value="">Please Select a Consignee</option>
<?
	$sql = "SELECT CONSIGNEEID, CONSIGNEENAME
			FROM MOR_CONSIGNEE
			WHERE CUST = '".$cust."'
			ORDER BY CONSIGNEEID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "CONSIGNEEID"); ?>"<? if(ociresult($stid, "CONSIGNEEID") == $display_order_info['consignee']){?> selected <?}?>><? echo ociresult($stid, "CONSIGNEENAME"); ?></option>

<?
	}
?>
				</select></td>
		<td width="10%" align="right"><font size="2" face="Verdana">TE Status:</font></td>
		<td width="40%" align="left"><select name="TE_status">
					<option value="RECEIVED"<? if("RECEIVED" == $display_order_info['TE_status']){?> selected <?}?>><? echo "RECEIVED"; ?></option>
					<option value="PENDING"<? if("PENDING" == $display_order_info['TE_status']){?> selected <?}?>><? echo "PENDING"; ?></option>
				</select></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Customer PO:</font></td>
		<td width="40%" align="left"><input type="text" name="cust_PO" size="20" maxlength="20" value="<? echo $display_order_info['cust_PO']; ?>"></td>
		<td width="10%" align="right"><font size="2" face="Verdana">SNMG:</font></td>
		<td width="40%" align="left"><input type="text" name="SNMG" size="30" maxlength="50" value="<? echo $display_order_info['SNMG']; ?>"></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">PickUp Date:</font></td>
		<td width="40%" align="left"><input type="text" name="pickup" size="10" maxlength="10" value="<? echo $display_order_info['pickup']; ?>"><a href="javascript:show_calendar('order_info.pickup');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Load Type:</font></td>
		<td width="40%" align="left"><select name="load_type">
					<option value="CUSTOMER LOAD"<? if("CUSTOMER LOAD" == $display_order_info['load_type']){?> selected <?}?>><? echo "CUSTOMER LOAD"; ?></option>
					<option value="REGRADE LOAD"<? if("REGRADE LOAD" == $display_order_info['load_type']){?> selected <?}?>><? echo "REGRADE LOAD"; ?></option>
					<option value="HOSPITAL LOAD"<? if("HOSPITAL LOAD" == $display_order_info['load_type']){?> selected <?}?>><? echo "HOSPITAL LOAD"; ?></option>
				</select></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Delivery Date:</font></td>
		<td width="40%" align="left"><input type="text" name="delivery" size="10" maxlength="10" value="<? echo $display_order_info['delivery']; ?>"><a href="javascript:show_calendar('order_info.delivery');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Comments:</font></td>
		<td width="40%" align="left"><input type="text" name="comments" size="50" maxlength="50" value="<? echo $display_order_info['comments']; ?>"></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Transporter:</font></td>
		<td width="40%" align="left"><select name="transporter"><option value="">Please Select a Transporter</option>
<?
	$sql = "SELECT TRANSPORTID, CARRIERNAME
			FROM MOR_TRANSPORTER
			WHERE CUST = '".$cust."'
			ORDER BY TRANSPORTID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "TRANSPORTID"); ?>"<? if(ociresult($stid, "TRANSPORTID") == $display_order_info['transporter']){?> selected <?}?>><? echo ociresult($stid, "TRANSPORTID")." - ".ociresult($stid, "CARRIERNAME"); ?></option>

<?
	}
?>
				</select></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Border Crossing:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $display_order_info['bordercross']; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Transport Charges:</font></td>
		<td width="40%" align="left"><input type="text" name="trans_charge" size="10" maxlength="10" value="<? echo $display_order_info['trans_charge']; ?>"></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Customs Broker:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $display_order_info['customs_broker']; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Fixed Freight (Per Load):</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo "$3,150.00"; ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Enter or Scan T&E:</font></td>
		<td width="40%" align="left"><input type="text" name="t_and_e" size="20" maxlength="20" value="<? echo $display_order_info['t_and_e']; ?>"></td>
	</tr>
<?
	if($display_order_info['status'] == "8" || $display_order_info['status'] == "9"){
?>
	<tr>
		<td colspan="2" align="left"><a href="moroccan_straight_bol_pdf.php?order=<? echo $ordernum;?>">Print BoL</a></td>
		<td colspan="2" align="right"><a href="moroccan_clem_tally.php?order=<? echo $ordernum;?>">Print Tally</a></td>
	</tr>
<?
	}

?>
	<tr>
		<td colspan="4">&nbsp;<hr></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Driver Name:</font></td>
		<td width="40%" align="left"><input type="text" name="driver_name" size="30" maxlength="30" value="<? echo $display_order_info['driver_name']; ?>"<? echo $new_disabled; ?>></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Total Carton Scanned:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $ctns_out; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Trailer Number:</font></td>
		<td width="40%" align="left"><input type="text" name="trailernum" size="15" maxlength="15" value="<? echo $display_order_info['trailernum']; ?>"<? echo $new_disabled; ?>></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Total Pallets Scanned:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $plts_out; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Truck Tag:</font></td>
		<td width="40%" align="left"><input type="text" name="trucktag" size="15" maxlength="15" value="<? echo $display_order_info['trucktag']; ?>"<? echo $new_disabled; ?>></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Total Weight (KG):</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $display_order_info['total_weight']; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Seal #:</font></td>
		<td width="40%" align="left"><input type="text" name="sealnum" size="15" maxlength="15" value="<? echo $display_order_info['sealnum']; ?>"<? echo $new_disabled; ?>></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Total Box Damaged:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $dmg_out; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">BoL Comments:</font></td>
		<td width="40%" align="left"><input type="text" name="bol_comment" size="15" maxlength="50" value="<? echo $display_order_info['bol_comment']; ?>"<? echo $new_disabled; ?>></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Total Price:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo $display_order_info['total_price']; ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Temp Recorder#:</font></td>
		<td width="40%" align="left"><input type="text" name="temp_recorder" size="15" maxlength="50" value="<? echo $display_order_info['temp_recorder']; ?>"<? echo $new_disabled; ?>></td>
		<td colspan="2" rowspan="2"><font size="3" face="Verdana">Signature:     X <? echo $display_order_info['signature']; ?></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Cancel Comments:</font></td>
		<td width="40%" align="left"><input type="text" name="commentcancel" size="15" maxlength="15" value="<? echo $display_order_info['commentcancel']; ?>"<? echo $new_disabled; ?>></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;<hr></td>
	</tr>
</table>
<?
/*
* DETAILS
***********/
	$counter = 1;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Order Size</font></td>
		<td><font size="2" face="Verdana">Order QTY (Cartons)</font></td>
		<td><font size="2" face="Verdana">Price</font></td>
		<td><font size="2" face="Verdana">Size (Low)<br>(optional)</font></td>
		<td><font size="2" face="Verdana">Size (High)<br>(optional)</font></td>
		<td><font size="2" face="Verdana">Weight (KG/Carton)<br>(optional)</font></td>
		<td><font size="2" face="Verdana">Comments</font></td>
	</tr>
<?
	for($i = 0; $i < 10; $i++){
?>
	<input type="hidden" name="order_det_id[<? echo $i; ?>]" value="<? echo $display_orderdet_info[$i]['id']; ?>">
	<tr>
		<td><select name="order_det_size[<? echo $i; ?>]"><option value="">---</option>
<?
		$sql = "SELECT SIZEID, DESCR, SIZEHIGH, SIZELOW, WEIGHTKG
				FROM MOR_COMMODITYSIZE
				WHERE CUST = '".$cust."'
				ORDER BY SIZEID";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "SIZEID"); ?>"<? if(ociresult($stid, "SIZEID") == $display_orderdet_info[$i]['size']){?> selected <?}?>><? echo ociresult($stid, "DESCR"). "  (defaults - low:".ociresult($stid, "SIZELOW")." high:".ociresult($stid, "SIZEHIGH")." kg:".ociresult($stid, "WEIGHTKG").")"; ?></option>

<?
		}
?>
				</select></td>
		<td><font size="2" face="Verdana"><input type="text" name="orderdet_ctns[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $display_orderdet_info[$i]['ctns']; ?>"></font></td>
		<td><font size="2" face="Verdana">$<input type="text" name="orderdet_price[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo number_format($display_orderdet_info[$i]['price'], 2); ?>"></font></td>
		<td><font size="2" face="Verdana"><input type="text" name="orderdet_sizelow[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $display_orderdet_info[$i]['sizelow']; ?>"></font></td>
		<td><font size="2" face="Verdana"><input type="text" name="orderdet_sizehigh[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $display_orderdet_info[$i]['sizehigh']; ?>"></font></td>
		<td><font size="2" face="Verdana"><input type="text" name="orderdet_weightkg[<? echo $i; ?>]" size="10" maxlength="10" value="<? echo $display_orderdet_info[$i]['weightkg']; ?>"></font></td>
		<td><font size="2" face="Verdana"><input type="text" name="orderdet_comments[<? echo $i; ?>]" size="50" maxlength="50" value="<? echo $display_orderdet_info[$i]['comments']; ?>"></font></td>
	</tr>
<?
	}
?>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><input type="submit" name="submit" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Reload Current Values"></td>
	</tr>
</table>
</form>







































<?
function get_display_values(&$display_order_info, &$display_orderdet_info, $submitted_values_top, $submitted_values_bottom, $submit, $ordernum, $cust, $success, $conn){

	// order data
	if($submit == "Save" && !$success){
		$display_order_info['vessel'] = $submitted_values_top['vessel'];
		$display_order_info['comm'] = $submitted_values_top['comm'];
		$display_order_info['status'] = $submitted_values_top['status'];
		$display_order_info['mor_cust'] = $submitted_values_top['mor_cust'];
		$display_order_info['direct_order'] = $submitted_values_top['direct_order'];
		$display_order_info['consignee'] = $submitted_values_top['consignee'];
		$display_order_info['TE_status'] = $submitted_values_top['TE_status'];
		$display_order_info['cust_PO'] = $submitted_values_top['cust_PO'];
		$display_order_info['SNMG'] = $submitted_values_top['SNMG'];
		$display_order_info['pickup'] = $submitted_values_top['pickup'];
		$display_order_info['load_type'] = $submitted_values_top['load_type'];
		$display_order_info['delivery'] = $submitted_values_top['delivery'];
		$display_order_info['comments'] = $submitted_values_top['comments'];
		$display_order_info['transporter'] = $submitted_values_top['transporter'];
		$display_order_info['trans_charge'] = $submitted_values_top['trans_charge'];
		$display_order_info['driver_name'] = $submitted_values_top['driver_name'];
		$display_order_info['trailernum'] = $submitted_values_top['trailernum'];
		$display_order_info['trucktag'] = $submitted_values_top['trucktag'];
		$display_order_info['sealnum'] = $submitted_values_top['sealnum'];
		$display_order_info['bol_comment'] = $submitted_values_top['bol_comment'];
		$display_order_info['temp_recorder'] = $submitted_values_top['temp_recorder'];
		$display_order_info['commentcancel'] = $submitted_values_top['commentcancel'];
	} else {
		$sql = "SELECT VESSELID, COMMODITYCODE, ORDERSTATUSID, CUSTOMERID, DIRECTORDER, CONSIGNEEID, TESTATUS, CUSTOMERPO, SNMGNUM, LOADTYPE, COMMENTS,
						TRANSPORTERID, TRANSPORTCHARGES, DRIVERNAME, TRAILERNUM, TRUCKTAG, SEALNUM, COMMENTSCANCEL, ZUSER1, ZUSER2,
						TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICK, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') THE_DELV
				FROM MOR_ORDER
				WHERE ORDERNUM = '".$ordernum."'
					AND CUST = '".$cust."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);

		$display_order_info['vessel'] = ociresult($stid, "VESSELID");
		$display_order_info['comm'] = ociresult($stid, "COMMODITYCODE");
		$display_order_info['status'] = ociresult($stid, "ORDERSTATUSID");
		$display_order_info['mor_cust'] = ociresult($stid, "CUSTOMERID");
		$display_order_info['direct_order'] = ociresult($stid, "DIRECTORDER");
		$display_order_info['consignee'] = ociresult($stid, "CONSIGNEEID");
		$display_order_info['TE_status'] = ociresult($stid, "TESTATUS");
		$display_order_info['cust_PO'] = ociresult($stid, "CUSTOMERPO");
		$display_order_info['SNMG'] = ociresult($stid, "SNMGNUM");
		$display_order_info['pickup'] = ociresult($stid, "THE_PICK");
		$display_order_info['load_type'] = ociresult($stid, "LOADTYPE");
		$display_order_info['delivery'] = ociresult($stid, "THE_DELV");
		$display_order_info['comments'] = ociresult($stid, "COMMENTS");
		$display_order_info['transporter'] = ociresult($stid, "TRANSPORTERID");
		$display_order_info['trans_charge'] = ociresult($stid, "TRANSPORTCHARGES");
		$display_order_info['driver_name'] = ociresult($stid, "DRIVERNAME");
		$display_order_info['trailernum'] = ociresult($stid, "TRAILERNUM");
		$display_order_info['trucktag'] = ociresult($stid, "TRUCKTAG");
		$display_order_info['sealnum'] = ociresult($stid, "SEALNUM");
		$display_order_info['bol_comment'] = ociresult($stid, "ZUSER1");
		$display_order_info['temp_recorder'] = ociresult($stid, "ZUSER2");
		$display_order_info['commentcancel'] = ociresult($stid, "COMMENTSCANCEL");
	}

	$sql = "SELECT TOTALBOXDAMAGED, TOTALCOUNT, TOTALPALLETCOUNT, TOTALPRICE, TOTALQUANTITYKG, CONSIGNEEID
			FROM MOR_ORDER
			WHERE ORDERNUM = '".$ordernum."'
				AND CUST = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$display_order_info['total_carton'] = ociresult($stid, "TOTALCOUNT");
	$display_order_info['total_pallet'] = ociresult($stid, "TOTALPALLETCOUNT");
	$display_order_info['total_weight'] = ociresult($stid, "TOTALQUANTITYKG");
	$display_order_info['total_boxdmg'] = ociresult($stid, "TOTALBOXDAMAGED");
	if(ociresult($stid, "TOTALPRICE") != ""){
		$display_order_info['total_price'] = "$".number_format(ociresult($stid, "TOTALPRICE"), 2);
	} else {
		$display_order_info['total_price'] = "";
	}

	$sql = "SELECT CUSTOMSBROKEROFFICEID
			FROM MOR_CONSIGNEE
			WHERE CONSIGNEEID = '".$display_order_info['consignee']."'";
	$cons = ociparse($conn, $sql);
	ociexecute($cons);
	ocifetch($cons);

	$sql = "SELECT BORDERCROSSING, CUSTOMSBROKER
			FROM MOR_BROKER
			WHERE BROKERID = '".ociresult($cons, "CUSTOMSBROKEROFFICEID")."'";
	$bc = ociparse($conn, $sql);
	ociexecute($bc);
	ocifetch($bc);
	$display_order_info['bordercross'] = ociresult($bc, "BORDERCROSSING");
	$display_order_info['customs_broker'] = ociresult($bc, "CUSTOMSBROKER");




	//order details
	if($submit == "Save" && !$success){
		for($i = 0; $i < 10; $i++){
			$display_orderdet_info[$i]['ctns'] = $submitted_values_bottom[$i]['ctns'];
			$display_orderdet_info[$i]['price'] = $submitted_values_bottom[$i]['price'];
			$display_orderdet_info[$i]['sizelow'] = $submitted_values_bottom[$i]['sizelow'];
			$display_orderdet_info[$i]['sizehigh'] = $submitted_values_bottom[$i]['sizehigh'];
			$display_orderdet_info[$i]['weightkg'] = $submitted_values_bottom[$i]['weightkg'];
			$display_orderdet_info[$i]['comments'] = $submitted_values_bottom[$i]['comments'];
			$display_orderdet_info[$i]['id'] = $submitted_values_bottom[$i]['id'];
			$display_orderdet_info[$i]['size'] = $submitted_values_bottom[$i]['size'];
			// next three statements are to populate bottom if "default" data was saved
/*			if($submitted_values_bottom[$i]['size'] != "" && $display_orderdet_info[$i]['sizelow'] == ""){
				$sql = "SELECT SIZELOW FROM MOR_ORDERDETAIL
						WHERE ORDERNUM = '".$ordernum."'
							AND CUST = '".$cust."'
							AND ORDERDETAILID = '".$display_orderdet_info[$i]['id']."'";
				echo $sql."<br>";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$display_orderdet_info[$i]['sizelow'] = ociresult($short_term_data, "SIZELOW");
			}
			if($submitted_values_bottom[$i]['size'] != "" && $display_orderdet_info[$i]['sizehigh'] == ""){
				$sql = "SELECT SIZEHIGH FROM MOR_ORDERDETAIL
						WHERE ORDERNUM = '".$ordernum."'
							AND CUST = '".$cust."'
							AND ORDERDETAILID = '".$display_orderdet_info[$i]['id']."'";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$display_orderdet_info[$i]['sizehigh'] = ociresult($short_term_data, "SIZEHIGH");
			}
			if($submitted_values_bottom[$i]['size'] != "" && $display_orderdet_info[$i]['weightkg'] == ""){
				$sql = "SELECT WEIGHTKG FROM MOR_ORDERDETAIL
						WHERE ORDERNUM = '".$ordernum."'
							AND CUST = '".$cust."'
							AND ORDERDETAILID = '".$display_orderdet_info[$i]['id']."'";
				$short_term_data = ociparse($conn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$display_orderdet_info[$i]['weightkg'] = ociresult($short_term_data, "WEIGHTKG");
			}*/
		}
	}else {
		$i = 0;

		$sql = "SELECT ORDERDETAILID, COMMENTS, ORDERQTY, ORDERSIZEID, PRICE, SIZEHIGH, SIZELOW, WEIGHTKG
				FROM MOR_ORDERDETAIL
				WHERE ORDERNUM = '".$ordernum."'
				AND CUST = '".$cust."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			$display_orderdet_info[$i]['ctns'] = ociresult($stid, "ORDERQTY");
			$display_orderdet_info[$i]['price'] = ociresult($stid, "PRICE");
			$display_orderdet_info[$i]['sizelow'] = ociresult($stid, "SIZELOW");
			$display_orderdet_info[$i]['sizehigh'] = ociresult($stid, "SIZEHIGH");
			$display_orderdet_info[$i]['weightkg'] = ociresult($stid, "WEIGHTKG");
			$display_orderdet_info[$i]['comments'] = ociresult($stid, "COMMENTS");
			$display_orderdet_info[$i]['id'] = ociresult($stid, "ORDERDETAILID");
			$display_orderdet_info[$i]['size'] = ociresult($stid, "ORDERSIZEID");

			$i++;
		}
	}

}

function NVL($possible_null, $default_value){
	if($possible_null == ""){
		return $default_value;
	} else {
		return $possible_null;
	}

	// should never happen, but...
	return "";
}

function sql_safe($variable){
	$variable = str_replace("'", "`", $variable);
	$variable = str_replace("\\", "", $variable);
	$variable = str_replace("\"", "", $variable);
	$variable = str_replace("#", "", $variable);
	$variable = str_replace("@", "", $variable);
	$variable = str_replace("&", "", $variable);
	$variable = trim($variable);

	return $variable;
}

function validate_entries($submitted_values_top, $submitted_values_bottom, $action_type, $ordernum, $cust, $conn){
	// check #1:  if this is a new order, make sure the order# doesn't already exist
	$error = "";

	if($action_type == "ADD"){
		if($submitted_values_top['ordernum_add'] == ""){
			$error .= "Must enter an Order#.<br>";
		} else {

			$sql = "SELECT COUNT(*) THE_COUNT FROM MOR_ORDER WHERE ORDERNUM = '".$submitted_values_top['ordernum_add']."' AND CUST = '".$cust."'";
			$stid = ociparse($conn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_COUNT") > 0){
				$error .= "Order# ".$submitted_values_top['ordernum_add']." has already been used.<br>";
			}
		}
	}

	if($action_type == "UPDATE"){
		$sql = "SELECT ORDERSTATUSID FROM MOR_ORDER WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "ORDERSTATUSID") == "10"){
			$error .= "Order# ".$ordernum." cannot be changed; it's already cancelled.<br>";
		}
	}
/*
	$sql = "SELECT ORDERSTATUSID FROM MOR_ORDER WHERE ORDERNUM = '".$ordernum."' AND CUST = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "ORDERSTATUSID") != "1" && $submitted_values_top['status'] == "2"){
		$error .= "Orders can only be Submitted from Draft status.<br>";
	}
*/
	if($submitted_values_top['vessel'] == ""){
		$error .= "A Vessel must be chosen.<br>";
	}
	if($submitted_values_top['comm'] == ""){
		$error .= "A Commodity must be chosen.<br>";
	}
	if($submitted_values_top['mor_cust'] == ""){
		$error .= "A Customer must be chosen.<br>";
	}
	if($submitted_values_top['consignee'] == ""){
		$error .= "A Consignee must be chosen.<br>";
	}
	if($submitted_values_top['vessel'] == ""){
		$error .= "A Vessel must be chosen.<br>";
	}
	if($submitted_values_top['transporter'] == ""){
		$error .= "A Transporter must be chosen.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['cust_PO'])){
		$error .= "Customer PO (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['SNMG'])){
		$error .= "SNMG# (if entered) must be AlphaNumeric.<br>";
	}
	if($submitted_values_top['pickup'] != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $submitted_values_top['pickup'])){
		$error .= "Pickup Date (if entered) must be in MM/DD/YYYY format.<br>";
	}
	if($submitted_values_top['delivery'] != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $submitted_values_top['delivery'])){
		$error .= "Delivery Date (if entered) must be in MM/DD/YYYY format.<br>";
	}
/*	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['comments'])){
		$error .= "Comments (if entered) must be AlphaNumeric.<br>";
	}*/ 
	if($submitted_values_top['trans_charge'] != "" && !is_numeric($submitted_values_top['trans_charge'])){
		$error .= "Transport Charge (if entered) must be Numeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['driver_name'])){
		$error .= "Driver Name (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['trailernum'])){
		$error .= "Trailer# (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['trucktag'])){
		$error .= "Truck Tag (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['sealnum'])){
		$error .= "Seal# (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9])*$", $submitted_values_top['t_and_e'])){
		$error .= "T&E (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9`?\/ ])*$", $submitted_values_top['bol_comment'])){
		$error .= "BOL-comment (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9`? ])*$", $submitted_values_top['temp_recorder'])){
		$error .= "Temperature Recorder (if entered) must be AlphaNumeric.<br>";
	}
	if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_top['commentcancel'])){
		$error .= "Cancellation Comments (if entered) must be AlphaNumeric.<br>";
	}


	for($i = 0; $i < 10; $i++){
		if($submitted_values_bottom[$i]['size'] != ""){
			// we only care about a line they have actually chosen to enter

			if($submitted_values_bottom[$i]['ctns'] == "" || !is_numeric($submitted_values_bottom[$i]['ctns'])){
				$error .= "Cartons (on line ".($i + 1).") is required, and must be Numeric.<br>";
			}
			if($submitted_values_bottom[$i]['price'] != "" && (!is_numeric($submitted_values_bottom[$i]['price']) || $submitted_values_bottom[$i]['price'] <= 0)){
				$error .= "Price (on line ".($i + 1)."), if entered, must be more than zero, and must be Numeric.<br>";
			}
			if($submitted_values_bottom[$i]['sizelow'] != "" && !is_numeric($submitted_values_bottom[$i]['sizelow'])){
				$error .= "Low-Size (on line ".($i + 1).") must be Numeric if entered.<br>";
			}
			if($submitted_values_bottom[$i]['sizehigh'] != "" && !is_numeric($submitted_values_bottom[$i]['sizehigh'])){
				$error .= "High-Size (on line ".($i + 1).") must be Numeric if entered.<br>";
			}
			if($submitted_values_bottom[$i]['weightkg'] != "" && !is_numeric($submitted_values_bottom[$i]['weightkg'])){
				$error .= "Weight-KG (on line ".($i + 1).") must be Numeric if entered.<br>";
			}
			if(!ereg("^([a-zA-Z0-9` ])*$", $submitted_values_bottom[$i]['comments'])){
				$error .= "Comments (if entered) must be AlphaNumeric.<br>";
			}
		}
	}

	return $error;
	
}



function PrepareMail($ordernum, $rf_conn){
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CLEMCBPWEEXPED'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}

	$mailSubject = ociresult($email, "SUBJECT");

	$body = ociresult($email, "NARRATIVE");

	$sql = "INSERT INTO JOB_QUEUE
				(JOB_ID,
				SUBMITTER_ID,
				SUBMISSION_DATETIME,
				JOB_TYPE,
				JOB_DESCRIPTION,
				COMPLETION_STATUS,
				JOB_EMAIL_TO,
				JOB_EMAIL_CC,
				JOB_EMAIL_BCC,
				JOB_BODY,
				VARIABLE_LIST)
			VALUES
				(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
				'INSTANTCRON',
				SYSDATE,
				'EMAIL',
				'CLEMCBPWEEXPED',
				'PENDING',
				'".$mailTO."',
				'".ociresult($email, "CC")."',
				'".ociresult($email, "BCC")."',
				'".substr($body, 0, 2000)."',
				'".$ordernum."')";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
}
