<?

	if($submit != ""){
		$bad_message = "";
		$status = $HTTP_POST_VARS['status'];
		$start_date = $HTTP_POST_VARS['start_date'];
		$end_date = $HTTP_POST_VARS['end_date'];
		$cust = $HTTP_POST_VARS['cust'];

		if($start_date != "" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $start_date)){
			$bad_order_message .= "Start Date must be in MM/DD/YYYY format.<br>";
		}
		if($end_date != "" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $end_date)){
			$bad_order_message .= "End Date must be in MM/DD/YYYY format.<br>";
		}
		if($bad_order_message != ""){
			echo "<font color=\"#FF0000\">Order List could not be generated for the following reasons:<br><br>".$bad_order_message."<br>Please try again.</font><br>";
		} else {
			$sql_where = "";
			if($status != "default"){
				$sql_where .= " AND AOH.STATUS = '".$status."' ";
			} else {
				$sql_where .= " AND AOH.STATUS <= 3 ";
			}
			if($start_date != ""){
				$sql_where .= " AND AOH.EXPECTED_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY') ";
			}
			if($end_date != ""){
				$sql_where .= " AND AOH.EXPECTED_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY') ";
			}
			if($cust != "All"){
				$sql_where .= " AND AOH.CUSTOMER_ID = '".$cust."' ";
			}
		}
	}












?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Order List Page<? if($order_num != ""){?> ORDER#: <? echo $order_num;}?><b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_select" action="order_list_index.php" method="post">
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Status:</font></td>
		<td><select name="status"><option value="default">Default</option>
					<? PopulateStatusBox($auth_exp, $auth_port, $status, $rfconn); ?></select>&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana" color="#330000">(Default shows Draft, Submitted, and Truckloading Orders)</font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#330000">Pickup Date:</font></td>
		<td><font size="2" face="Verdana" color="#330000">Start:&nbsp;<input type="text" name="start_date" size="10" maxlength="10" value="<? echo $start_date; ?>">&nbsp;&nbsp;&nbsp;&nbsp;
					End:&nbsp;<input type="text" name="end_date" size="10" maxlength="10" value="<? echo $end_date; ?>">(MM/DD/YYYY)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana" color="#330000"><b>Customer #:</b></td>
		<td><select name="cust"><option value="All">All</option>
<?
	$sql = "SELECT CUSTOMER_CODE, CUSTOMER_CODE || '-' || CUSTOMER_NAME THE_NAME
			FROM EXP_CUSTOMER
			ORDER BY CUSTOMER_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "CUSTOMER_CODE"); ?>"<? if(ociresult($stid, "CUSTOMER_CODE") == $cust){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>			
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Orders"></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $bad_message == ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0" class="sortable">
	<thead>
	<tr bgcolor="#00AAFF">
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>Order Num</b></font></th>
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>Customer</b></font></th>
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>Consignee</b></font></th>
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>Status</b></font></th>
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>PO</b></font></th>
		<th class="sorttable_alpha"><font size="2" face="Verdana" color="#330000"><b>Transport</b></font></th>
		<th><font size="2" face="Verdana" color="#330000"><b>Pickup Date</b></font></th>
		<th><font size="2" face="Verdana" color="#330000"><b>Check-In Time</b></font></th>
		<th><font size="2" face="Verdana" color="#330000"><b>Check-Out Time</b></font></th>
	</tr>
	</thead>
	<tbody>
<?
		$sql = "SELECT AOH.ORDER_NUM, ECUST.CUSTOMER_NAME, ECON.CONSIGNEE_NAME, AOH.CUSTOMER_PO, TO_CHAR(ACI.CHECK_IN, 'MM/DD/YYYY HH24:MI') THE_IN, TO_CHAR(ACI.CHECK_OUT, 'MM/DD/YYYY HH24:MI') THE_OUT,
					AT.COMPANY_NAME, AST.DESCR, TO_CHAR(AOH.EXPECTED_DATE, 'MM/DD/YYYY') THE_DATE, TO_CHAR(AOH.EXPECTED_DATE, 'YYYYMMDD') SORT_DATE 
				FROM EXP_CUSTOMER ECUST, EXP_CONSIGNEE ECON, ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_TRANSPORT AT, ARGFRUIT_STATUS AST, ARGENFRUIT_CHECKIN_ID ACI
				WHERE AOH.CUSTOMER_ID = ECUST.CUSTOMER_CODE(+)
					AND AOH.CONSIGNEE_ID = ECON.CONSIGNEE_CODE(+)
					AND AOH.STATUS = AST.STATUS
					AND AOH.CHECKIN_ID = ACI.CHECKIN_ID(+)
					AND AOH.TRANSPORT_ID = AT.TRANSPORT_ID(+)".$sql_where."
				ORDER BY ACI.CHECK_OUT DESC NULLS FIRST";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
	<tr>
		<td><font size="2" face="Verdana" color="#330000"><a href="order_edit_index.php?order_num=<? echo ociresult($stid, "ORDER_NUM"); ?>"><? echo ociresult($stid, "ORDER_NUM"); ?></a>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "CUSTOMER_NAME"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "CONSIGNEE_NAME"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "DESCR"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "CUSTOMER_PO"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "COMPANY_NAME"); ?>&nbsp;</font></td>
		<td sorttable_customkey="<? echo ociresult($stid, "SORT_DATE"); ?>"><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "THE_DATE"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "THE_IN"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana" color="#330000"><? echo ociresult($stid, "THE_OUT"); ?>&nbsp;</font></td>
	</tr>
<?
		}
?>
	</tbody>
</table>
<?
	}
?>






























<?

function PopulateStatusBox($auth_exp, $auth_port, $status, $rfconn){
	$sql = "SELECT STATUS, DESCR
			FROM ARGFRUIT_STATUS
			ORDER BY STATUS";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
		<option value="<? echo ociresult($stid, "STATUS"); ?>"<? if(ociresult($stid, "STATUS") == $status){?> selected <?}?>><? echo ociresult($stid, "STATUS")."-".ociresult($stid, "DESCR"); ?></option>
<?
	}
}
