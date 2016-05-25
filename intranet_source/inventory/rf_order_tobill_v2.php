<?
/*
*		Adam Walter, JAN 2015.
*
*		RF-transfer orders pre-prepped for finance
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance Pre-Review";
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
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$date = $HTTP_POST_VARS['date'];
	$act_select = $HTTP_POST_VARS['act_select'];


	if($submit == "Process Checkboxes"){
		$cust_set = $HTTP_POST_VARS['cust_set'];
		$arv_set = $HTTP_POST_VARS['arv_set'];
		$order_set = $HTTP_POST_VARS['order_set'];
		$pass_set = $HTTP_POST_VARS['pass_set'];

		$i = 0;
		$to_bill = 0;
		$dont_bill = 0;
		while($cust_set[$i] != ""){
			if($pass_set[$i] == "save"){

				$sql = "UPDATE CARGO_ACTIVITY
						SET TO_MISCBILL = 'B'
						WHERE ARRIVAL_NUM = '".$arv_set[$i]."'
							AND CUSTOMER_ID = '".$cust_set[$i]."'
							AND ORDER_NUM = '".$order_set[$i]."'
							AND SERVICE_CODE = '".$act_select."'
							AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
				$upd_data = ociparse($rfconn, $sql);
				ociexecute($upd_data);

				$to_bill++;
			} else {
				$sql = "UPDATE CARGO_ACTIVITY
						SET TO_MISCBILL = NULL
						WHERE ARRIVAL_NUM = '".$arv_set[$i]."'
							AND CUSTOMER_ID = '".$cust_set[$i]."'
							AND ORDER_NUM = '".$order_set[$i]."'
							AND SERVICE_CODE = '".$act_select."'
							AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
				$upd_data = ociparse($rfconn, $sql);
				ociexecute($upd_data);

				$dont_bill++;
			}
			$i++;
		}

		echo "<font color=\"0000FF\">Orders Updated.  ".$to_bill." order(s) marked to be billed by finance; ".$dont_bill." order(s) ignored by finance (for now).</font></br>";
	}


?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Review Orders For Finance
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="rf_order_tobill_v2.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date:  </font></td>
		<td><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>"><a href="javascript:show_calendar('select.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Activity Type:  </font></td>
		<td><select name="act_select"><option value="">Select Activity Type</option>
										<option value="11"<? if($act_select == "11" || $act_select == ""){?> selected <?}?>>Transfers</option>
					</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Pre-Check Unbilled Rows?</font></td>
		<td><input type="radio" name="pre_check" value="no"<? if($pre_check == "no" || $pre_check == ""){?> checked <?}?>>&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="pre_check" value="yes"<? if($pre_check == "yes"){?> checked <?}?>>&nbsp;Yes</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve" && ($act_select == "" || $date == "")){
		echo "<font color=\"#FF0000\">Please select both an Activity Type and a Date.</font>";
		$submit = "";
	}

	if($submit == "Retrieve" && $act_select != "" && $date != ""){
		$pre_check = $HTTP_POST_VARS['pre_check'];

//		$sql = "SELECT";
?>
<form name="partial_save" action="rf_order_tobill_v2.php" method="post">
<input type="hidden" name="date" value="<? echo $date; ?>">
<input type="hidden" name="act_select" value="<? echo $act_select; ?>">
<input type="hidden" name="pre_check" value="<? echo $pre_check; ?>">
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="10" align="center"><font size="3" face="Verdana"><b>Date:&nbsp;&nbsp;<? echo $date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Service Code:&nbsp;&nbsp;<? echo $act_select; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="Verdana"><b>Original Owner</b></font></td>
		<td><font size="2" face="Verdana"><b>New Owner</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Billed Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Current Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Send to Finance?</b></font></td>
	</tr>
<?
		$rownum = -1;

		$sql = GetSql($date, $act_select, $rfconn);
		$line_data = ociparse($rfconn, $sql);
		ociexecute($line_data);
		if(!ocifetch($line_data)){
?>
	<tr>
		<td colspan="10" align="center"><font size="2" face="Verdana"><b>No Items to Review</font></td>
	</tr>
<?
		} else {
			do {
				$rownum++;
?>
<input type="hidden" name="cust_set[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "CUSTOMER_ID"); ?>">
<input type="hidden" name="arv_set[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "ARRIVAL_NUM"); ?>">
<input type="hidden" name="order_set[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "ORDER_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "ARRIVAL_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "CUSTOMER_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo NewOwnerDisp(ociresult($line_data, "CUSTOMER_ID"), ociresult($line_data, "ORDER_NUM"), $date, $act_select, $rfconn); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "PLTCOUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "CASES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "COMMODITY_CODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "BNI_COMM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($line_data, "THE_STAT"); ?></font></td>
		<td><input type="checkbox" name="pass_set[<? echo $rownum; ?>]" value="save"<? if($pre_check == "yes" || ociresult($line_data, "THE_STAT") == "Already Cleared"){?> checked <?}?>></td>
	</tr>
<?
		} while(ocifetch($line_data));
?>
	<tr>
		<td colspan="10"><input type="submit" name="submit" value="Process Checkboxes">
	</tr>
<?
	}
?>
</form>
</table>

<?
	}
	include("pow_footer.php");





function GetSql($date, $act_select, $rfconn){
	if($act_select == "11"){
        $sql = "SELECT COUNT(*) PLTCOUNT, SUM(QTY_CHANGE) CASES, CUSTOMER_ID, CA.ARRIVAL_NUM, ORDER_NUM, RECEIVING_TYPE, NVL(BNI_COMM, CP.COMMODITY_CODE) BNI_COMM, 
					CP.COMMODITY_CODE, DECODE(TO_MISCBILL, 'B', 'Already Cleared', 'Awaiting Clearance') THE_STAT
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, COMMODITY_PROFILE CP, RF_TO_BNI_COMM RTBC 
				WHERE DATE_OF_ACTIVITY >= TO_DATE('".$date."' ,'MM/DD/YYYY') 
					AND DATE_OF_ACTIVITY < TO_DATE('".$date."' ,'MM/DD/YYYY')+1 
					AND CUSTOMER_ID != 1 
					AND SERVICE_CODE = '11' 
					AND ((TO_MISCBILL IS NULL AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID'))
						OR
						TO_MISCBILL = 'B')
					AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM 
					AND CT.COMMODITY_CODE=CP.COMMODITY_CODE 
					AND CT.RECEIVER_ID=CA.CUSTOMER_ID 
					AND CT.PALLET_ID=CA.PALLET_ID
					AND CT.COMMODITY_CODE=RTBC.RF_COMM
					AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) 
					AND CT.RECEIVER_ID NOT IN ('9722')
					AND CA.ACTIVITY_NUM != '1'
                GROUP BY CUSTOMER_ID, ORDER_NUM, CA.ARRIVAL_NUM,
					NVL(BNI_COMM, CP.COMMODITY_CODE), CP.COMMODITY_CODE, RECEIVING_TYPE, DECODE(TO_MISCBILL, 'B', 'Already Cleared', 'Awaiting Clearance')
                ORDER BY CUSTOMER_ID, ORDER_NUM, NVL(BNI_COMM, CP.COMMODITY_CODE), CP.COMMODITY_CODE";
	}

	return $sql;
}

function NewOwnerDisp($cust, $ord, $date, $act_select, $rfconn){
	if($act_select != "11"){
		return $cust;
	}

	$sql = "SELECT CUSTOMER_ID 
			FROM CARGO_ACTIVITY 
			WHERE ARRIVAL_NUM = '".$ord."'
				AND DATE_OF_ACTIVITY >= TO_DATE('".$date."' ,'MM/DD/YYYY') 
				AND DATE_OF_ACTIVITY < TO_DATE('".$date."' ,'MM/DD/YYYY')+1 
				AND CUSTOMER_ID != '".$cust."'
				AND ORDER_NUM = ARRIVAL_NUM
				AND ACTIVITY_NUM = '1'
				AND SERVICE_CODE = '11'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		return -1;
	} else {
		return ociresult($short_term_data, "CUSTOMER_ID");
	}
}