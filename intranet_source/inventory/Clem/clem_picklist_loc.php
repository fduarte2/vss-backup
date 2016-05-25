<?
/*
*	Adam Walter, October 2007
*
*	This page takes as input a Clementine Order number (2007 season format)
*	And returns a list of packinghouses on the order, and locations of
*	Pallets that fit the criteria
********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
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
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$order_present = true;
	$special_lines = 0;
	$total_lines = 0;
	if($submit == "Retrieve"){
		$prefix = which_table_prefix($order_num, $conn);
//		if($prefix == "MOR"){
//			$right_bardode = "270";
//		} else {
			$right_bardode = "835";
//		}

		$sql = "SELECT * FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
//		echo $sql."<br>";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order_present = false;
		}
/*
		$sql = "SELECT * FROM ".$prefix."_PICKLIST WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(@ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if(strtoupper(substr($short_term_row['COMMENTS'], 0, 3)) == "1.8"){
				$linestart = strtoupper(substr($short_term_row['COMMENTS'], 0, 3));
			} else {
				$linestart = strtoupper(substr($short_term_row['COMMENTS'], 0, 1));
			}

			if($linestart == "B" ||
				$linestart == "C" ||
				$linestart == "H" ||
				$linestart == "R" ||
				$linestart == "D" ||
				$linestart == "1.8"){
					$most_recent_special = $linestart;
					$special_lines++;
			}

			$total_lines++;
		}
//		echo $special_lines." ".$total_lines."<br>";

		if($special_lines == 0 || $special_lines == 1 || $special_lines == $total_lines){
			$valid_picklist = true;
			$special_order = $most_recent_special; // ONLY TO BE USED FOR $special_lines == 1
		} else {
			$valid_picklist = false;
		}
*/
//		echo $valid_picklist."<br>";
		$valid_picklist = true;
	
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementine Picklist Locations
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_pick" action="clem_picklist_loc.php" method="post">
	<tr>
		<td align="center"><font size="2" face="Verdana">Order #:</font></td>
	</tr>
	<tr>
		<td align="center"><input type="text" name="order_num" size="10" maxlength="12" value="<? echo $order_num; ?>"></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td>&nbsp;<HR>&nbsp;</td>
	</tr>
<?
	if($submit == "Retrieve" && !$order_present){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">Order not present in System.  Please call TS if this is in error.</font></td>
	</tr>
<?
	} elseif($submit == "Retrieve" && !$valid_picklist){
?>
	<tr>
		<!-- diction supplied by Inigo Thomas !-->
		<td align="center"><font size="3" face="Verdana" color="#FF0000">The picklist for this order is very complicated with multiple instructions for picking.  Either the picklist has to be changed or you need to contact Ann Rizzo or Dominion directly.</font></td>
	</tr>
<?
	}
?>
</form>
</table>
<?
	if($order_num != "" && $order_present && $valid_picklist){
		$sql = "SELECT ORDERSTATUSID FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		switch($row['ORDERSTATUSID']){
			case "3":
				$status = "3 - Pick List Complete";
				break;
			case "4":
				$status = "4 - Loading";
				break;
			case "7":
				$status = "7 - Revised Pick List Complete";
				break;
			case "8":
				$status = "8 - Confirmed";
				break;
			case "9":
				$status = "9 - Order Complete";
				break;
		}

		$sql = "SELECT DCO.*, TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP FROM ".$prefix."_ORDER DCO WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$special_ins_order = $row['COMMENTS'];
		$commodity = $row['COMMODITYCODE'];
		$vessel = $row['VESSELID'];
		$load_type = $row['LOADTYPE'];
		$cust_ID = $row['CUSTOMERID'];
		$consign_ID = $row['CONSIGNEEID'];
		$pickup_date = $row['THE_PICKUP'];
		$seal = $row['SEALNUM'];
//		if($special_ins_order == ""){
//			$special_ins_order = "---NONE---";
//		}
		$sql = "SELECT TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI:SS') THE_DATE
				FROM DUAL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$create_time = $row['THE_DATE'];
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $vessel." - ".$row['VESSEL_NAME'];
		$sql = "SELECT CUSTOMERNAME FROM ".$prefix."_CUSTOMER WHERE TO_CHAR(CUSTOMERID) = '".$cust_ID."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$cust_name = $cust_ID." - ".$row['CUSTOMERNAME'];
		$sql = "SELECT CONSIGNEENAME FROM ".$prefix."_CONSIGNEE WHERE TO_CHAR(CONSIGNEEID) = '".$consign_ID."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$consign_name = $consign_ID." - ".$row['CONSIGNEENAME'];

?>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<!--	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana">PickList Locations</font></td>
	</tr> !-->
	<tr>
		<td align="left" width="35%"><font size="4" face="Verdana"><b>Port Customer:  <? echo $right_bardode; ?></b></font>
			<img src="/functions/barcode.php?codetype=code39&size=30&text=<?php echo $right_bardode; ?>" alt="<?php echo $right_bardode; ?>" />
		<td align="center"><font size="4" face="Verdana"><b>Seal#:  <? echo $seal; ?></b></font></td>
		<td align="right" width="35%"><font size="4" face="Verdana"><b>Order Number:  <? echo $order_num; ?></b></font>
			<img src="/functions/barcode.php?codetype=code39&size=30&text=<?php echo $order_num; ?>" alt="<?php echo $order_num; ?>" /></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">This picklist information is as of the date and time shown below.  If you run this at another time the results may be different depending on what was already shipped out after the date and time the picklist was created.</font></td>
	</tr>
<!--	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Order Number:  <? echo $order_num; ?></b></font>
			<img src="/functions/barcode.php?codetype=code39&size=30&text=<?php echo $order_num; ?>" alt="<?php echo $order_num; ?>" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Port Customer:  <? echo $right_bardode; ?></b></font>
			<img src="/functions/barcode.php?codetype=code39&size=30&text=<?php echo $right_bardode; ?>" alt="<?php echo $right_bardode; ?>" />
	</tr> !-->
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Picklist Created On:  <? echo $create_time; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Load Type:  <? echo $load_type; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Status:  <? echo $status; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Vessel:  <? echo $vessel_name; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Customer:  <? echo $cust_name; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Consignee:  <? echo $consign_name; ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Pick Up Date:  <? echo $pickup_date; ?></font></td>
		<td>&nbsp;</td>
	</tr>
<!--	<tr>
		<td align="left">Order:<br><img src="/functions/barcode.php?codetype=code39&size=50&text=<?php echo $order_num; ?>" alt="<?php echo $order_num; ?>" /></td>
		<td align="right">Customer:<br><img src="/functions/barcode.php?codetype=code39&size=50&text=<?php echo $right_bardode; ?>" alt="<?php echo $right_bardode; ?>" /></td>
	</tr> !-->
	<tr>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana"><b>Comments/Special Instructions (if any):  <? echo $special_ins_order; ?></b></font></td>
		<td>&nbsp;</td>
	</tr>
</table>
<?
		//VESSELID, COMMODITYCODE,
		$sql = "SELECT DCOD.SIZELOW, DCOD.SIZEHIGH, DCOD.ORDERDETAILID THE_DET, CONSIGNEEID, DCOD.WEIGHTKG, DCOD.COMMENTS, DCOD.ORDERQTY, DESCR 
				FROM ".$prefix."_ORDER DCO, ".$prefix."_ORDERDETAIL DCOD, ".$prefix."_COMMODITYSIZE DCC 
				WHERE DCO.ORDERNUM = DCOD.ORDERNUM 
					AND DCO.ORDERNUM = '".$order_num."'
					AND DCOD.ORDERSIZEID = DCC.SIZEID
				ORDER BY DCOD.ORDERDETAILID";
//		echo $sql."<br>";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$consignee = $first_row['CONSIGNEEID'];
			$sizelow = $first_row['SIZELOW'];
			$sizehigh = $first_row['SIZEHIGH'];
//			$commodity = $first_row['COMMODITYCODE'];
//			$vessel = $first_row['VESSELID'];
			$detail_id = $first_row['THE_DET'];
			$weight = $first_row['WEIGHTKG'];
			$comments_detail = $first_row['COMMENTS'];
			$order_det_qty = $first_row['ORDERQTY'];
			$order_det_descrip = $first_row['DESCR'];
?>
<font size="3" face="Verdana">Order Detail:</font>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><table border="1" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td><font size="2" face="Verdana">Order Size:</font></td>
				<td><font size="2" face="Verdana">Size (Low):</font></td>
				<td><font size="2" face="Verdana">Size (High):</font></td>
				<td><font size="2" face="Verdana">Weight-KG:</font></td>
				<td><font size="2" face="Verdana">Comments (if any):</font></td>
				<td><font size="2" face="Verdana">Order Quantity:</font></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana"><? echo $order_det_descrip; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $sizelow; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $sizehigh; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $weight; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $comments_detail; ?>&nbsp;</font></td>
				<td><font size="2" face="Verdana"><? echo $order_det_qty; ?></font></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>	
		<td width="10%">&nbsp;</td>
		<td><font size="3" face="Verdana">Picklist Locations for Order Detail:</font><br>
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td><font size="2" face="Verdana">Pack House</font></td>
					<td><font size="2" face="Verdana">Picklist Size</font></td>
					<td><font size="2" face="Verdana">Plt Qty</font></td>
					<td><font size="2" face="Verdana">Plt(s) Available</font></td>
					<td><font size="2" face="Verdana">Location(s)</font></td>
				</tr>
<?
			$sql = "SELECT * FROM ".$prefix."_PICKLIST DCP, ".$prefix."_PACK_HOUSE DCPH
					WHERE ORDERNUM = '".$order_num."' 
						AND ORDERDETAILID = '".$detail_id."'
						AND TRIM(DCP.PACKINGHOUSE) = DCPH.PACK_HOUSE_ID
						ORDER BY PICKLISTID";
//			echo $sql."<BR>";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			while(ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$packhousename = $second_row['PACK_HOUSE_NAME'];
				$full_picklist_comments = $second_row['COMMENTS'];
//				if(strtoupper(substr($second_row['COMMENTS'], 0, 3)) == "1.8"){
//					$linestart = strtoupper(substr($second_row['COMMENTS'], 0, 3));
//				} else {
					$linestart = strtoupper(substr($second_row['COMMENTS'], 0, 1));
//				}
//				if($special_lines == 1){
//					$specific_picklist = $special_order;
//				} else {
					$specific_picklist = $linestart;
//				}
/*			if((($linestart == "B" ||
				$linestart == "C" ||
				$linestart == "H" ||
				$linestart == "R" ||
				$linestart == "D" ||
				$linestart == "1.8")
*/

				switch($specific_picklist){
					case "B":
						$addon_sql = "AND UPPER(REMARK) = 'BURNAC'";
					break;
					case "C":
						$addon_sql = "AND DC_CARGO_DESC LIKE '%: C'";
					break;
					case "H":
						$addon_sql = "AND CARGO_STATUS IN ('H', 'B')";
					break;
					case "R":
						$addon_sql = "AND CARGO_STATUS IN ('R', 'B')";
					break;
//					case "1.8":
//						$addon_sql = "AND WEIGHT = '1.8'";
//					break;
					default:
//										AND WEIGHT != '1.8'";
						$addon_sql = "AND (UPPER(REMARK) != 'BURNAC' OR REMARK IS NULL)
										AND DC_CARGO_DESC NOT LIKE '%: C'
										AND CARGO_STATUS IS NULL";
					break;
				}


//				$sizeactual = $second_row['PALLET_LOCATION'];
				$PKG = trim($second_row['PACKINGHOUSE']);
//				$requested_pal = $second_row['PALLET_QTY'];
				$sizeactual = $second_row['PICKLISTSIZE'];
//				$PKG = trim($second_row['PACKHOUSEID']);
				$requested_pal = $second_row['PALLETQTY'];
//				echo $sizeactual."PL<BR>".$PKG."PK<BR>".$requested_pal."RP<BR>";

/*				if(strtoupper(substr($order_num, 0, 3)) == "BUR"){
					$extra_clause = "AND UPPER(REMARK) = 'BURNAC' ";
				} else {
					$extra_clause = "AND (UPPER(REMARK) != 'BURNAC' OR REMARK IS NULL)";
				}
*/
?>
			<tr>
				<td><font size="2" face="Verdana"><? echo $PKG." (".$packhousename.")"; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $sizeactual; if($sizeactual == 0){?>(Any)<?}?></font></td>
				<td><font size="2" face="Verdana"><? echo $requested_pal; ?></font></td>
<?			
				// not 439 anymore, now 835
				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(WAREHOUSE_LOCATION, 'UNSPECIFIED') THE_LOC 
						FROM DC_CARGO_TRACKING 
						WHERE COMMODITY_CODE = '".$commodity."'
							AND DATE_RECEIVED IS NOT NULL
							AND ARRIVAL_NUM = '".$vessel."' 
							AND (MARK IS NULL OR MARK != 'SHIPPED') 
							AND (CARGO_SIZE = '".$sizeactual."' 
								OR ('".$sizeactual."' = '0' 
									AND TO_NUMBER(CARGO_SIZE) >= '".$sizelow."' 
									AND TO_NUMBER(CARGO_SIZE) <= '".$sizehigh."'
									)
								)
							AND EXPORTER_CODE = '".$PKG."'
							AND WEIGHT = '".$weight."'
							AND SUBSTR(PALLET_ID, 10, 4) != '4901' 
							AND RECEIVER_ID NOT IN (SELECT DISTINCT CUST FROM MOR_ORDER) 
							".$addon_sql." 
						GROUP BY WAREHOUSE_LOCATION 
						ORDER BY WAREHOUSE_LOCATION";
//				echo $sql."<BR>";
				ora_parse($cursor_third, $sql);
				ora_exec($cursor_third);
				if(!ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<td colspan="2" align="center"><font size="2" face="Verdana"><b>No Pallets left in house.</b></font></td>
			</tr>

<?
				} else {
?>
				<td colspan="2" align="center"><font size="2" face="Verdana">&nbsp;</font></td>
			</tr>
<?
					do {
						if(($consignee == "10" || $consignee == "22") && $sizeactual == "36"){
						// band-aid:  Costco boxes of size 36 are different from all others
?>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td colspan="2"><font size="2" face="Verdana">*** CostCo Cardboard Containers ***</font></td>
			</tr>
<?
						} else {
?>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $third_row['THE_COUNT']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $third_row['THE_LOC']; ?></font></td>
			</tr>
<?
						}
					} while(ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
			}
?>
		</table></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center" width="100%"><font size="2" face="Verdana">&nbsp;<br><b>Picklist Comments (if any):   </b><? echo $full_picklist_comments; ?><hr></td>
	</tr>
</table>
<?
		}
	}
	include("pow_footer.php");


function which_table_prefix($order, $conn){
//	$Short_Term_Cursor = ora_open($conn);
	
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM MOR_ORDER
			WHERE ORDERNUM = '".$order."'";
//	echo $sql."<br>";
//	ora_parse($Short_Term_Cursor, $sql);
//	ora_exec($Short_Term_Cursor);
//	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//	if($row['THE_COUNT'] >= 1){
//		return "MOR";
//	} else {
		return "DC";
//	}
}

?>