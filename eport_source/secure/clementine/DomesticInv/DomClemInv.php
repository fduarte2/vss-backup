<?
/*
*	Adam Walter, October 2007.
*	This page gives a list of domestic clementine containers;
*	Either only those taht still ahve cargo present, or all of
*	Them, depending on user selection.
*
*	Modified Feb 21, 2008, to account for new customer.
*	I basically hardcoded 441 into the selection box, but luckily,
*	Thats the extent of the hard coding.
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$Outer_Cursor = ora_open($conn);
	$Inner_Cursor = ora_open($conn);

	$current_option = $HTTP_POST_VARS['current_option'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}

	// $eport_customer_id comes from index.php
	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
		$extra_sql = "AND CUSTOMER_ID = '".$eport_customer_id."'";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="data_pick" action="index.php" method="post">
	<tr>
		<td width="50%" align="center"><select name="current_option">
					<option value="current">Current Containers</option>
					<option value="all" <? if($current_option == "all"){?> selected <?}?>>All Containers</option>
			</select></td>
		<td width="50%" align="center"><font size="3" face="Verdana">Customer:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="cust">
<?
	$sql = "SELECT * FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_LOGIN WHERE USER_TYPE = 'CLEMENTINE' AND CUSTOMER_ID != 0)
			".$extra_sql."
			ORDER BY CUSTOMER_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// we gotta do something strange for oppy-439, so...
		if($Short_Term_row['CUSTOMER_ID'] != '439'){
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?></option>
<?
		} else {
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($raw_cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - NO OPPY</option>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>O" <? if($raw_cust == "439O"){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - OPPY ONLY</option>
<?
		}
	}
?>
			</select></td>
<!--		<td width="50%" align="center"><select name="cust">
					<option value="440">440 - Oppenheimer</option>
					<option value="441" <? if($cust == "441"){?> selected <?}?>>441 - CostCo</option>
					<option value="442" <? if($cust == "442"){?> selected <?}?>>442 - LGS</option>
			</select></td> !-->
	</tr>
	<tr>
		<td width="100%" align="center" colspan="2"><input type="submit" name="submit" value="Generate List"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<HR>&nbsp;</td>
	</tr>
</form>
</table>
<?
	if($current_option != ""){
		$current_container == "";
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Case Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipped on (if applicable)</b></font></td>
		<td><font size="2" face="Verdana"><b>Outgoing Order (if applicable)</b></font></td>
	</tr>
<?
//											AND SUBSTR(ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
		$sql = "SELECT CT.CONTAINER_ID THE_CONT, CT.PALLET_ID THE_PALLET, TO_NUMBER(CT.CARGO_SIZE) THE_SIZE, DC_CARGO_DESC DISP_SIZE, QTY_RECEIVED, 
				TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC, NVL(CT.CARGO_STATUS, 'GOOD') THE_STATUS, CTE.MIN_DATE DATE_ORDERED_BY,
				CA.DATE_ACT ACTIVITY_DATE, CA.ORDER_NUM THE_ORDER
				FROM DC_CARGO_TRACKING CT, (SELECT PALLET_ID, ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_ACT, ORDER_NUM
											FROM CARGO_ACTIVITY CA
											WHERE SERVICE_CODE = '6'
											AND ACTIVITY_DESCRIPTION IS NULL
											AND CUSTOMER_ID = '".$cust."') CA,
										(SELECT CONTAINER_ID, MIN(DATE_RECEIVED) MIN_DATE
											FROM CARGO_TRACKING
											WHERE RECEIVER_ID = '".$cust."'".$sub_sql."
											AND DATE_RECEIVED > '01-oct-2007' ";
		if($current_option == "current"){
			$sql .= "AND CONTAINER_ID IN (SELECT CONTAINER_ID FROM CARGO_TRACKING WHERE MARK != 'SHIPPED' OR MARK IS NULL) ";
		}

		$sql .=								"GROUP BY CONTAINER_ID
											ORDER BY MIN(DATE_RECEIVED)) CTE
				WHERE CT.CONTAINER_ID = CTE.CONTAINER_ID
				AND CT.RECEIVER_ID = '".$cust."'".$sub_sql."
				AND CT.DATE_RECEIVED > '01-oct-2007'
				AND CT.PALLET_ID = CA.PALLET_ID (+)
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM (+)
				ORDER BY DATE_ORDERED_BY, THE_SIZE, DISP_SIZE, DATE_REC";
//		echo $sql."<br>";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($current_container != "" && $current_container != $row['THE_CONT']){
?>
	<tr>
		<td colspan="8">&nbsp;</td>
	</tr>
<?
			}
			$current_container = $row['THE_CONT'];
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="DomClemInvPrint.php?container=<? echo $row['THE_CONT']; ?>&cust=<? echo $raw_cust; ?>" target="DomClemInvPrint.php?container=<? echo $row['THE_CONT']; ?>&cust=<? echo $cust; ?>" ><? echo $row['THE_CONT']; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DISP_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_STATUS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ACTIVITY_DATE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ORDER']; ?>&nbsp;</font></td>
	</tr>
<?
		}
	}
?>