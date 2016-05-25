<?
/*
*	Adam Walter, Jun 2011.
*
*	Report Page to "pre-transfer cargo" fro Argen Juice.
*
*************************************************************************/


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
	$modify_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Argen Juice Scanner-Cargo Transfer List
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="filter_data" action="Argen_Juice_XFER_list.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">LR#: </td>
		<td align="left"><select name="vessel">
						<option value="">All</option>
<?
		$sql = "SELECT DISTINCT CT.ARRIVAL_NUM, NVL(LR_NUM, 0) THE_VES, DECODE(VESSEL_NAME, NULL, 'N/A', LR_NUM || '-' || VESSEL_NAME) THE_VESSEL 
				FROM VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
					AND CT.ARRIVAL_NUM IN
						(SELECT ARRIVAL_NUM FROM ARGJUICE_TRANSFERS)
				ORDER BY NVL(LR_NUM, 0) DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['ARRIVAL_NUM']; ?>"<? if($row['ARRIVAL_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Customer (from):</td>
		<td><select name="cust_from">
						<option value="">All</option>
<?
		$sql = "SELECT DISTINCT CUSP.CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE CUSP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
					AND CUSP.CUSTOMER_ID IN
						(SELECT CUSTOMER_FROM FROM ARGJUICE_TRANSFERS)
				ORDER BY CUSTOMER_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust_from){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Customer (to):</td>
		<td><select name="cust_to">
						<option value="">All</option>
<?
		$sql = "SELECT DISTINCT CUSP.CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE CUSP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
					AND CUSP.CUSTOMER_ID IN
						(SELECT CUSTOMER_TO FROM ARGJUICE_TRANSFERS)
				ORDER BY CUSTOMER_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust_from){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Commodity:</td>
		<td><select name="comm">
						<option value="">All</option>
<?
		$sql = "SELECT DISTINCT COMP.COMMODITY_CODE, COMMODITY_NAME 
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP
				WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND COMMODITY_TYPE = 'ARG JUICE'
					AND CT.QTY_IN_HOUSE > 0
					AND COMP.COMMODITY_CODE IN
						(SELECT COMMODITY_CODE FROM ARGJUICE_TRANSFERS)
				ORDER BY COMMODITY_CODE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['COMMODITY_CODE']; ?>"<? if($row['COMMODITY_CODE'] == $comm){ ?> selected <? } ?>><? echo $row['COMMODITY_CODE']." - ".$row['COMMODITY_NAME']; ?></option>
<?
		}
?>
			</select></font></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Status:</td>
		<td><select name="status">
						<option value="">All</option>
						<option value="still_full">BNI-transferred, but no scanned-transfers done yet</option>
						<option value="quantity_remaining" selected>Still has any quantity that can be scan-transferred on it</option>
						<option value="finished">All allotted barcodes already scan-transferred</option>
			</select></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Retrieve Available Cargo"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Available Cargo"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cust_from = $HTTP_POST_VARS['cust_from'];
		$cust_to = $HTTP_POST_VARS['cust_to'];
		$comm = $HTTP_POST_VARS['comm'];
		$status = $HTTP_POST_VARS['status'];

		$where_clause = GetWhereClause($vessel, $cust_from, $cust_to, $comm, $status);

		$sql = "SELECT ARRIVAL_NUM, COMMODITY_CODE, CUSTOMER_FROM, CUSTOMER_TO, TRANSFER_NUM, BOL, QTY_TO_TRANS, QTY_LEFT_TO_TRANS
				FROM ARGJUICE_TRANSFERS
				WHERE BOL IS NOT NULL
				".$where_clause."
				ORDER BY TRANSFER_NUM, BOL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">No Juice Transfers matching selected criteria.</font>";
			include("pow_footer.php");
			exit;
		} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font face="Verdana" size="2"><b>LR</b></font></td>
		<td><font face="Verdana" size="2"><b>Customer From</b></font></td>
		<td><font face="Verdana" size="2"><b>Customer To</b></font></td>
		<td><font face="Verdana" size="2"><b>Commodity Code</b></font></td>
		<td><font face="Verdana" size="2"><b>Transfer#</b></font></td>
		<td><font face="Verdana" size="2"><b>Original Transfer QTY</b></font></td>
		<td><font face="Verdana" size="2"><b>QTY remaining to scan-transfer</b></font></td>
	</tr>
<?
			do{
?>
	<tr>
		<td><font face="Verdana" size="2"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['CUSTOMER_FROM']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['CUSTOMER_TO']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['COMMODITY_CODE']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['TRANSFER_NUM']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['QTY_TO_TRANS']; ?></font></td>
		<td><font face="Verdana" size="2"><? echo $row['QTY_LEFT_TO_TRANS']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
	include("pow_footer.php");















function GetWhereClause($vessel, $cust_from, $cust_to, $comm, $status){
	$return = "";

	switch($vessel){
		case "":
			break;
		default:
			$return .= " AND ARRIVAL_NUM = '".$vessel."'";
		break;
	}

	switch($cust_from){
		case "":
			break;
		default:
			$return .= " AND CUSTOMER_FROM = '".$cust_from."'";
		break;
	}
	switch($cust_to){
		case "":
			break;
		default:
			$return .= " AND CUSTOMER_TO = '".$cust_to."'";
		break;
	}
	switch($comm){
		case "":
			break;
		default:
			$return .= " AND COMMODITY_CODE = '".$comm."'";
		break;
	}
	switch($status){
		case "":
		break;
		case "still_full":
			$return .= " AND QTY_LEFT_TO_TRANS = QTY_TO_TRANS";
		break;
		case "quantity_remaining":
			$return .= " AND QTY_LEFT_TO_TRANS > 0";
		break;
		case "finished":
			$return .= " AND QTY_LEFT_TO_TRANS = 0";
		break;
	}

	return $return;
}