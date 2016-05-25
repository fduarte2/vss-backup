<?
/*
*	Adam Walter, Feb 2013
*
*	Allows INVE to change the commodity code of a batch cargo at once
*
***********************************************************************************************/

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


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$order_num = $HTTP_POST_VARS['order_num'];
//	$cust_num = $HTTP_POST_VARS['cust_num'];
	$date_check = $HTTP_POST_VARS['date_check'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save New Commodity"){
		$new_comm_1 = $HTTP_POST_VARS['new_comm_1'];
		
		$sql = "UPDATE CARGO_TRACKING
				SET COMMODITY_CODE = '".$new_comm_1."'
				WHERE ARRIVAL_NUM = '".$order_num."'
					AND RECEIVING_TYPE = 'T'
					AND COMMODITY_CODE IN
						(SELECT COMMODITY_CODE 
						FROM COMMODITY_PROFILE 
						WHERE COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT')
						)";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Order# ".$order_num." Updated to ".$new_comm_1.".</font>";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Fruit Commodity Changer
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	if($submit != "Select" || $date_check == ""){
?>
<form name="select" action="inve_comm_change.php" method="post">
	<tr>
		<td align="left">Order#:&nbsp;&nbsp;<input type="text" name="order_num" size="15" maxlength="15" value="<? echo $order_num; ?>"></td>
	</tr>
<!--	<tr>
		<td align="left">Customer:</td>
		<td align="left"><select name="cust_num"><option value="">Any/All</option>
<?

	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			ORDER BY CUSTOMER_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>"<? if($cust_num == ociresult($stid, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($stid, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr> !-->
	<tr>
		<td align="left"><b>--- OR ---</b></td>
	</tr>
	<tr>
		<td align="left">Date (MM/DD/YYYY):&nbsp;&nbsp;<input type="text" name="date_check" size="10" maxlength="10" value="<? echo $date_check; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Select"><br><hr><br></td>
	</tr>
</form>
<?
	} else {
?>
<form name="select" action="inve_comm_change.php" method="post">
	<tr>
		<td>Orders for date <? echo $date_check; ?>:</td>
	</tr>
	<tr>
		<td align="left">Order#:&nbsp;&nbsp;<select name="order_num">
<?

		$sql = "SELECT DISTINCT ARRIVAL_NUM
				FROM CARGO_TRACKING
				WHERE TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date_check."'
					AND COMMODITY_CODE IN
						(SELECT COMMODITY_CODE 
						FROM COMMODITY_PROFILE 
						WHERE COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT')
						)
					AND RECEIVING_TYPE = 'T'
				ORDER BY ARRIVAL_NUM";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>"><? echo ociresult($stid, "ARRIVAL_NUM"); ?></option>
<?
		}
?>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Select"><br><hr><br></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	if($submit == "Select" && $date_check == ""){
		// the next sections only display if "Select" is pressed.
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="update_comm" action="inve_comm_change.php" method="post">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
	<tr>
		<td align="center" colspan="5">New Commodity:&nbsp;&nbsp;&nbsp;<select name="new_comm_1">
<?

	$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT')
			ORDER BY COMMODITY_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "COMMODITY_CODE"); ?>"><? echo ociresult($stid, "COMMODITY_CODE")." - ".ociresult($stid, "COMMODITY_NAME"); ?></option>
<?
	}
?>
					</select><input type="submit" name="submit" value="Save New Commodity"></td>
	</tr>
	<tr>
		<td>Date Received</td>
		<td>Owner</td>
		<td>Current Commodity</td>
		<td>Barcode</td>
	</tr>
<?
		$total_plts = 0;

		$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC, PALLET_ID, CT.COMMODITY_CODE, COMMODITY_NAME, CUSTOMER_NAME
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = '".$order_num."'
					AND COMP.COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT')
					AND RECEIVING_TYPE = 'T'";
		$pallet_list = ociparse($rfconn, $sql);
		ociexecute($pallet_list);
		if(!ocifetch($pallet_list)){
?>
	<tr>
		<td colspan="4">No pallets for given Order.</td>
	</tr>
<?
		} else {
			do {
				$total_plts++;
?>
	<tr>
		<td><? echo ociresult($pallet_list, "DATE_REC"); ?></td>
		<td><? echo ociresult($pallet_list, "CUSTOMER_NAME"); ?></td>
		<td><? echo ociresult($pallet_list, "COMMODITY_CODE")." - ".ociresult($pallet_list, "COMMODITY_NAME"); ?></td>
		<td><? echo ociresult($pallet_list, "PALLET_ID"); ?></td>
	</tr>
<?
			} while(ocifetch($pallet_list));
		}
?>
</form>
</table>
<?
	}
	include("pow_footer.php");