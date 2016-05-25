<?
/*
*
*	Adam Walter, Jul 2015.
*
*	Report on any open paper orders
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Paper";
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
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}


?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Paper Orders Open / Completed Today</font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="12" align="center"><font size="3" face="Verdana">REVIEW BOOKING ORDER PROGRESS</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Cust #</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking #</b></font></td>
		<td><font size="2" face="Verdana"><b>PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Width</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter</b></font></td>
		<td><font size="2" face="Verdana"><b>SSCC</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY To Ship</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Scanned</b></font></td>
		<td><font size="2" face="Verdana"><b>Order Complete</b></font></td>
		<td><font size="2" face="Verdana"><b>Order Status</b></font></td>
	</tr>
<?//
	$sql = "SELECT BO.ORDER_NUM, BO.STATUS, DS.ST_DESCRIPTION, BO.CUSTOMER_ID, BOD.BOOKING_NUM, BOD.P_O, BOD.WIDTH, BOD.DIA, BOD.QTY_TO_SHIP, BOD.SSCC_GRADE_CODE,
				ROUND(BOD.WIDTH * UC.CONVERSION_FACTOR) THE_WIDTH_IN, 
				ROUND(BOD.DIA * UC.CONVERSION_FACTOR) THE_DIA_IN,
				DECODE(BO.STATUS, 1, 20, BO.STATUS) STATUS_SORT
			FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD, DOLEPAPER_STATUSES DS, UNIT_CONVERSION_FROM_BNI UC
			WHERE (BO.STATUS IN ('1', '2', '3', '4', '5') OR TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."')
				AND BO.STATUS = DS.STATUS
				AND BO.ORDER_NUM = BOD.ORDER_NUM
				AND UC.PRIMARY_UOM = 'CM' AND UC.SECONDARY_UOM = 'IN' 
			ORDER BY STATUS_SORT, BO.ORDER_NUM";
	$order_lines = ociparse($rfconn, $sql);
	ociexecute($order_lines);
	while(ocifetch($order_lines)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "CUSTOMER_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "BOOKING_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "P_O"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "WIDTH")."CM/".ociresult($order_lines, "THE_WIDTH_IN"); ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "DIA")."CM/".ociresult($order_lines, "THE_DIA_IN"); ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "SSCC_GRADE_CODE"); ?></font></td>
		<td><font size="2" face="Verdana">
			<? echo get_avg_bk_weight(ociresult($order_lines, "CUSTOMER_ID"), ociresult($order_lines, "BOOKING_NUM"), 
					ociresult($order_lines, "P_O"), ociresult($order_lines, "WIDTH"), ociresult($order_lines, "DIA"), ociresult($order_lines, "SSCC_GRADE_CODE"), $rfconn); ?>
			</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "QTY_TO_SHIP"); ?></font></td>
		<td><font size="2" face="Verdana">
			<? echo get_qty_bk_shipped(ociresult($order_lines, "ORDER_NUM"), ociresult($order_lines, "CUSTOMER_ID"), ociresult($order_lines, "BOOKING_NUM"), 
					ociresult($order_lines, "P_O"), ociresult($order_lines, "WIDTH"), ociresult($order_lines, "DIA"), ociresult($order_lines, "SSCC_GRADE_CODE"), $rfconn); ?>
			</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "STATUS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "ST_DESCRIPTION"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%"><br><hr><br></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="8" align="center"><font size="3" face="Verdana">REVIEW DOCK TICKET ORDER PROGRESS</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Cust #</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Dock Ticket #</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY To Ship</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Scanned</b></font></td>
		<td><font size="2" face="Verdana"><b>Order Complete</b></font></td>
		<td><font size="2" face="Verdana"><b>Order Status</b></font></td>
	</tr>
<?
	$sql = "SELECT DO.ORDER_NUM, DO.STATUS, DS.ST_DESCRIPTION, DD.DOCK_TICKET, DD.P_O, DD.QTY_SHIP,
				DECODE(DO.STATUS, 1, 20, DO.STATUS) STATUS_SORT
			FROM DOLEPAPER_ORDER DO, DOLEPAPER_DOCKTICKET DD, DOLEPAPER_STATUSES DS
			WHERE (DO.STATUS IN ('1', '2', '3', '4', '5') OR TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."')
				AND DO.STATUS = DS.STATUS
				AND DO.ORDER_NUM = DD.ORDER_NUM
			ORDER BY STATUS_SORT, DO.ORDER_NUM";
	$order_lines = ociparse($rfconn, $sql);
	ociexecute($order_lines);
	while(ocifetch($order_lines)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo get_cust_for_dt(ociresult($order_lines, "DOCK_TICKET"), $rfconn); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "DOCK_TICKET"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "P_O"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "QTY_SHIP"); ?></font></td>
		<td><font size="2" face="Verdana">
			<? echo get_qty_dt_shipped(ociresult($order_lines, "ORDER_NUM"), ociresult($order_lines, "DOCK_TICKET"), $rfconn); ?>
			</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "STATUS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($order_lines, "ST_DESCRIPTION"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");



















function get_cust_for_dt($DT, $rfconn){
	$sql = "SELECT DISTINCT RECEIVER_ID
			FROM CARGO_TRACKING
			WHERE BOL = '".$DT."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		return "&nbsp;";
	} else {
		$return = ociresult($short_term_data, "RECEIVER_ID");
		if(!ocifetch($short_term_data)){
			return $return;
		} else {
			return "MULTIPLE";
		}
	}
}

function get_avg_bk_weight($cust, $booking, $PO, $width, $dia, $SSCC, $rfconn){
	if($cust == "" || $booking == "" || $PO == "" || $width == "" || $dia == "" || $SSCC == ""){
		return "&nbsp;";
	}

	$sql = "SELECT ROUND(AVG(WEIGHT * UC3.CONVERSION_FACTOR)) THE_WEIGHT 
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, 
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3
			WHERE REMARK = 'BOOKINGSYSTEM'
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$PO."'
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'";
//	echo $sql;
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_WEIGHT");
}

function get_qty_bk_shipped($order_num, $cust, $booking, $PO, $width, $dia, $SSCC, $rfconn){
	if($cust == "" || $booking == "" || $PO == "" || $width == "" || $dia == "" || $SSCC == ""){
		return "&nbsp;";
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, CARGO_ACTIVITY CA,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
			WHERE CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CT.PALLET_ID = BAD.PALLET_ID 
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID 
				AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' 
				AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'
				AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
				AND BAD.ORDER_NUM = '".$PO."'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND CA.SERVICE_CODE = '6'
				AND CA.ORDER_NUM = '".$order_num."'
				AND CA.ACTIVITY_DESCRIPTION IS NULL";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_COUNT");
}

function get_qty_dt_shipped($order_num, $DT, $rfconn){
	if($order_num == "" || $DT == ""){
		return "&nbsp;";
	}

	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT 
			WHERE CA.ORDER_NUM = '".$order_num."' 
				AND CT.BOL = '".$DT."' 
				AND CA.SERVICE_CODE = '6' 
				AND CA.ACTIVITY_DESCRIPTION IS NULL 
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM 
				AND CA.PALLET_ID = CT.PALLET_ID AND 
				CA.CUSTOMER_ID = CT.RECEIVER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_COUNT");
}
