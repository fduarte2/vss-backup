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
  $title = "Steel orderss";
  $area_type = "TELR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TELR system");
    include("pow_footer.php");
    exit;
  }

	if ((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){ // this comes from pow_header.php
		$display_steel_links = true;
	} else {
		$display_steel_links = false;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$DO_num = $HTTP_POST_VARS['DO_num'];
	$PORT_num = $HTTP_POST_VARS['PORT_num'];
	$submit = $HTTP_POST_VARS['submit'];
	$cancel = $HTTP_POST_VARS['cancel'];

	if($submit == "Retrieve DO"){
		$sql = "SELECT COUNT(*) THE_COUNT FROM STEEL_PRELOAD_DO_INFORMATION
				WHERE DONUM = '".$DO_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\">INVALID DO#.  Please retype, or send file to TS for import first.</font>";
			$submit = "";
		} else {
		
			$sql = "SELECT SUM(QTY_IN_HOUSE) THE_SUM 
					FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
					WHERE DATE_RECEIVED IS NOT NULL					
							AND REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_SUM") <= 0){
				echo "<font color=\"#FF0000\">NO INVENTORY AVAILABLE FOR THIS DO#.  Do Not create a Port Order#.</font>";
				$submit = "";
			}
		}
	}

	if($submit == "Retrieve Port Order"){
		$sql = "SELECT COUNT(*) THE_COUNT FROM STEEL_ORDERS
				WHERE PORT_ORDER_NUM = '".$PORT_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			echo "<font color=\"#FF0000\">NO CARGO ASSIGNED TO THIS PO</font>";
			$submit = "";
		} else {

			$sql = "SELECT DONUM FROM STEEL_ORDERS
					WHERE PORT_ORDER_NUM = '".$PORT_num."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$DO_num = ociresult($stid, "DONUM");
		}
	}

	if($submit == "Create Port Order" || $submit == "Save Changes"){
		$driver = str_replace("'", "`", $HTTP_POST_VARS['driver']);
		$lic_state = $HTTP_POST_VARS['lic_state'];
		$lic_num = $HTTP_POST_VARS['lic_num'];
		$truck_company = str_replace("'", "`", $HTTP_POST_VARS['truck_company']);
		$clerk_init = $HTTP_POST_VARS['clerk_init'];
	
		if($driver == "" || $lic_state == "" || $lic_num == "" || $truck_company == "" || $clerk_init == ""){
			echo "<font color=\"#FF0000\">All fields must be entered.  Save cancelled.</font>";
		} else {
			if($submit == "Save Changes"){
				$sql = "UPDATE STEEL_ORDERS
						SET DRIVER_NAME = '".$driver."',
							LICENSE_NUM = '".$lic_num."',
							LICENSE_STATE = '".$lic_state."',
							TRUCKING_COMPANY = '".$truck_company."',
							CLERK_INITIALS = '".$clerk_init."'
						WHERE PORT_ORDER_NUM = '".$PORT_num."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "UPDATE TLS_TRUCK_LOG
						SET CHECKED_IN_BY = '".$clerk_init."',
							CHECKED_OUT_BY = '".$clerk_init."',
							TRUCKING_COMPANY = '".$truck_company."',
							DRIVER_NAME = '".$driver."'
						WHERE BOL = '".$PORT_num."'";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);
							
			} else {
				// yay.
				// get new order# first
				$sql = "SELECT PORT_ORDER_NUM FROM STEEL_ORDERS
						WHERE DONUM = '".$DO_num."'
						ORDER BY CREATED_DATE DESC, PORT_ORDER_NUM DESC";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				if(!ocifetch($stid)){
					$PORT_num = $DO_num."-001";
				} else {
					$temp = explode("-", ociresult($stid, "PORT_ORDER_NUM"));
					$next_num = $temp[1] + 1;
					$PORT_num = $DO_num."-";
					for($temp = strlen($next_num); $temp < 3; $temp++){
						$PORT_num = $PORT_num."0";
					}
					$PORT_num = $PORT_num.$next_num;
				}

				$sql = "INSERT INTO STEEL_ORDERS
							(PORT_ORDER_NUM,
							DONUM,
							DRIVER_NAME,
							LICENSE_NUM,
							LICENSE_STATE,
							TRUCKING_COMPANY,
							CLERK_INITIALS,
							CREATED_LOGIN_ID)
						VALUES
							('".$PORT_num."',
							'".$DO_num."',
							'".$driver."',
							'".$lic_num."',
							'".$lic_state."',
							'".$truck_company."',
							'".$clerk_init."',
							'".$user."')";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "SELECT MAX(DAILY_ROW_NUM) THE_MAX
						FROM TLS_TRUCK_LOG
						WHERE TO_CHAR(TIME_IN, 'mm/dd/yyyy') = '".date('m/d/Y')."'";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);
				if(!ocifetch($stid)){
					$row_num = 1;
				} else {
					$row_num =  ociresult($stid, "THE_MAX") + 1;
				}

				$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID, CP.COMMODITY_CODE
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
						WHERE REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$cust = ociresult($stid, "RECEIVER_ID");
				$vessel = ociresult($stid, "ARRIVAL_NUM");
				$comm = ociresult($stid, "COMMODITY_CODE");

				$sql = "INSERT INTO TLS_TRUCK_LOG
							(RECORD_ID,
							DAILY_ROW_NUM,
							TIME_IN,
							TIME_OUT,
							CHECKED_IN_BY,
							CHECKED_OUT_BY,
							TRUCKING_COMPANY,
							DRIVER_NAME,
							COMMODITY_CODE,
							CUSTOMER_CODE,
							LR_NUM,
							BOL,
							SEAL_NUM,
							WAREHOUSE)
						VALUES
							(TLS_TRUCK_LOG_SEQ.NextVal,
							'".$row_num."',
							SYSDATE,
							SYSDATE,
							'".$clerk_init."',
							'".$clerk_init."',
							'".$truck_company."',
							'".$driver."',
							'".$comm."',
							'".$cust."',
							'".$vessel."',
							'".$PORT_num."',
							'NA',
							'YARD')";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);

				$sql = "SELECT MAX(RECORD_ID) THE_MAX FROM TLS_TRUCK_LOG
						WHERE BOL = '".$PORT_num."'";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$row_id = ociresult($stid, "THE_MAX");

				$sql = "UPDATE STEEL_ORDERS
						SET TLS_ROW_ID = '".$row_id."'
						WHERE PORT_ORDER_NUM = '".$PORT_num."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);			
			}

			echo "<font color=\"#0000FF\">Changes Saved.  New information displayed.</font><br>";
			echo "<font><a href=\"steel_picklist_print.php?PORT_num=".$PORT_num."\">Click Here</a> for the picklist.</font><br><br>";
		}
	}

	if($cancel == "CANCEL ORDER"){
		$sql = "SELECT TLS_ROW_ID
				FROM STEEL_ORDERS
				WHERE PORT_ORDER_NUM = '".$PORT_num."'";
//		echo $sql;
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$tls_row = ociresult($stid, "TLS_ROW_ID");

//		$sql = "DELETE FROM TLS_TRUCK_LOG
//				WHERE RECORD_ID = '".$tls_row."'";
		$sql = "UPDATE TLS_TRUCK_LOG
				SET SEAL_NUM = 'CANCELLED'
				WHERE RECORD_ID = '".$tls_row."'";
//		echo $sql;
		$update = ociparse($bniconn, $sql);
		ociexecute($update);

		$sql = "UPDATE STEEL_ORDERS
				SET ORDER_STATUS = 'CANCELLED'
				WHERE PORT_ORDER_NUM = '".$PORT_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Order Cancelled.</font><br>";
	}




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Truck Check In<? if($display_steel_links){?> - </font><a href="index_steel.php">Return to Main Steel Page</a><?}?>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="steel_PO_create.php" method="post">
	<tr>
		<td align="left" width="10%">Delivery Order#:</td>
		<td><input name="DO_num" size="10" maxlength="10" type="text" value="<? echo $DO_num; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve DO"><br></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b>--- OR ---</b></td>
	</tr>
<form name="top_retrieve" action="steel_PO_create.php" method="post">
	<tr>
		<td align="left" width="10%">Port Order:</td>
		<td><input name="PORT_num" size="10" maxlength="10" type="text" value="<? echo $PORT_num; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Port Order"><br><hr><br></td>
	</tr>
</form>
<?
	if($submit != ""){
?>
<form name="save" action="steel_PO_create.php" method="post">
<?
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

		$sql = "SELECT SC.NAME CAR_NAME, SST.NAME SHIP_NAME, SST.ADDRESS_1, SST.ADDRESS_2, SST.CITY, SST.STATE, SST.ZIP
				FROM STEEL_SHIPPING_TABLE SST, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI
				WHERE SPDI.SHIP_TO_ID = SST.SHIP_TO_ID
					AND SPDI.CARRIER_ID = SC.CARRIER_ID
					AND SPDI.DONUM = '".$DO_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$carrier = ociresult($stid, "CAR_NAME");
		$shipto = ociresult($stid, "SHIP_NAME");
		$add1 = ociresult($stid, "ADDRESS_1");
		$add2 = ociresult($stid, "ADDRESS_2");
		$city = ociresult($stid, "CITY");
		$state = ociresult($stid, "STATE");
		$zip = ociresult($stid, "ZIP");

		$sql = "SELECT DRIVER_NAME, LICENSE_NUM, LICENSE_STATE, TRUCKING_COMPANY, CLERK_INITIALS, NVL(ORDER_STATUS, 'OPEN') THE_STAT
				FROM STEEL_ORDERS
				WHERE PORT_ORDER_NUM = '".$PORT_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){
			$driver = ociresult($stid, "DRIVER_NAME");
			$lic_num = ociresult($stid, "LICENSE_NUM");
			$lic_state = ociresult($stid, "LICENSE_STATE");
			$truck_company = ociresult($stid, "TRUCKING_COMPANY");
			$clerk_init = ociresult($stid, "CLERK_INITIALS");
			$ord_stat = ociresult($stid, "THE_STAT");
			$button_name = "Save Changes";
		} else {
			$driver = "";
			$lic_num = "";
			$lic_state = "";
			$truck_company = "";
			$clerk_init = "";
			$ord_stat = "";
			$button_name = "Create Port Order";
		}

		if($ord_stat != "OPEN" && $ord_stat != ""){
			$button_stat = "(Order marked as ".$ord_stat.", no changes allowed)";
			$active = "disabled";
		} else {
			$active = "";
		}

		// one another check...
		if($active == ""){
			$sql = "SELECT NVL(HOLD_STATUS, 'NORMAL') THE_STAT
					FROM STEEL_PRELOAD_DO_INFORMATION
					WHERE DONUM = '".$DO_num."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_STAT") == "Y"){
				$button_stat = "ORDER IS ON HOLD -- do not check this driver in!";
				$active = "disabled";
			}
		}

		// another check...
		if($active == ""){
			$sql = "SELECT COUNT (DISTINCT ARRIVAL_NUM) THE_VES, COUNT(DISTINCT RECEIVER_ID) THE_CUST, COUNT(DISTINCT CP.COMMODITY_CODE) THE_COMM
					FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
					WHERE REMARK = '".$DO_num."'
						AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
                        AND CP.COMMODITY_TYPE = 'STEEL'";
			$error = ociparse($rfconn, $sql);
			ociexecute($error);
			ocifetch($error);
			if(ociresult($error, "THE_VES") > 1){
				$button_stat .= "More than 1 vessel detected for this DO#.  Order cannot be saved until this is fixed. ( ";
				$sql = "SELECT DISTINCT ARRIVAL_NUM 
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
						WHERE REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					$button_stat .= ociresult($stid, "ARRIVAL_NUM")." ";
				}
				$button_stat .= ")<br>";
				$active = "disabled";
				$vessel = "<font color=\"#FF0000\">Multiple</font>";
				$vesname = "<font color=\"#FF0000\">Multiple</font>";
			}
			if(ociresult($error, "THE_CUST") > 1){
				$button_stat .= "More than 1 customer detected for this DO#.  Order cannot be saved until this is fixed. ( ";
				$sql = "SELECT DISTINCT RECEIVER_ID 
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
						WHERE REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					$button_stat .= ociresult($stid, "RECEIVER_ID")." ";
				}
				$button_stat .= ")<br>";
				$active = "disabled";
				$custname = "<font color=\"#FF0000\">Multiple</font>";
			}
			if(ociresult($error, "THE_COMM") > 1){
				$button_stat .= "More than 1 commodity detected for this DO#.  Order cannot be saved until this is fixed. ( ";
				$sql = "SELECT DISTINCT CP.COMMODITY_CODE 
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
						WHERE REMARK = '".$DO_num."'
							AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CP.COMMODITY_TYPE = 'STEEL'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					$button_stat .= ociresult($stid, "COMMODITY_CODE")." ";
				}
				$button_stat .= ")<br>";
				$active = "disabled";
				$commname = "<font color=\"#FF0000\">Multiple</font>";
			}
		}




?>
<input type="hidden" name="PORT_num" value="<? echo $PORT_num; ?>">
<input type="hidden" name="DO_num" value="<? echo $DO_num; ?>">
	<tr>
		<td width="10%"><b>ORDER STATUS:</b></td>
		<td><? echo $ord_stat; ?></td>
	</tr>
	<tr>
		<td>Customer:</td>
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
		<td>Carrier:</td>
		<td><? echo $carrier; ?></td>
	</tr>
	<tr>
		<td>Ship To:</td>
		<td><? echo $shipto; ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><? echo $add1." ".$add2; ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><? echo $city." ".$state." ".$zip; ?></td>
	</tr>
	<tr>
		<td>Driver Name:</td>
		<td><input type="text" name="driver" size="30" maxlength="30" value="<? echo $driver; ?>"></td>
	</tr>
	<tr>
		<td>License State and Number:</td>
		<td><input type="text" name="lic_state" size="5" maxlength="20" value="<? echo $lic_state; ?>">&nbsp;&nbsp;&nbsp;<input type="text" name="lic_num" size="15" maxlength="15" value="<? echo $lic_num; ?>"></td>
	</tr>
	<tr>
		<td>Trucking Company:</td>
		<td><input type="text" name="truck_company" size="100" maxlength="100" value="<? echo $truck_company; ?>"></td>
	</tr>
	<tr>
		<td>Clerk Initials:</td>
		<td><input type="text" name="clerk_init" size="10" maxlength="10" value="<? echo $clerk_init; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="<? echo $button_name; ?>" <? echo $active; ?>></td>
		<td>
			<table border="0" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center"><font size="2" color="#FF0000"><? echo $button_stat; ?></font>
					<td align="right"><input type="submit" name="cancel" value="CANCEL ORDER" <? echo $active; ?>></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>