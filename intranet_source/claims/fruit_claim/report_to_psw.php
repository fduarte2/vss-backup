<?
  // All POW files need this session file included
  include("pow_session.php");

$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

include("../claim_function.php");
//include("./claim_function.php");
// include the date comparing function
//include("compareDate.php");

// To be used to eliminate trailing zeros
$trans = array(".00"=>"");

// make Oracle connection
$conn = ora_logon("SAG_OWNER@$bni", "SAG");
//$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
if($conn < 1){
	printf("Error logging on to the DB Server: ");
	printf(ora_errorcode($conn));
	exit;
}
$cursor = ora_open($conn);

$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
if($conn2 < 1){
	printf("Error logging on to the DB Server: ");
	printf(ora_errorcode($conn2));
	exit;
}
$RFcursor = ora_open($conn2);

$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$season = $HTTP_POST_VARS["season"];
if(date('Y', mktime(0,0,0,date('m') + 2, date('d'), date('Y'))) == $season){
	$CT_table = "CARGO_TRACKING";
} else {
	$CT_table = "CARGO_TRACKING_".$season;
}
$cargo_type = $HTTP_POST_VARS["cargo_type"];
// $customer = $HTTP_POST_VARS["customer"];   ---NOT USED---


// HD ??
// Astrid wants reports from non-chilean cargo types to appear totally different than the current method.
// So, here we have a wonderful redirect for that case.
if($cargo_type != "CHILEAN"){
	NonChileanReport($conn, $conn2, $season, $CT_table, $cargo_type);
	exit;
}

$system = "RF";
$data = array();

//get customer name
$cust = getRFSeasonCustomerList($cursor, $season, $cargo_type);

$total_submission = 0;
$total_pow_100_payment = 0;
$total_pow_100_denial = 0;
$total_powpsw_resp = 0;
$total_acceptance = 0;
$total_68 = 0;
$total_cartons = 0;
$total_cartons_claimed = 0;
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3">
		<table border="2" width="100%" cellpadding="0" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td align="center"><font size="4" face="Verdana"><b><? echo $season; ?> POW and Trans Global Claim Summary Report (No Trucks) -- CHILEAN</b></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">
		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td align="center"><font size="3" face="Verdana"><b>Customer</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Claims received</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Cartons Rec'd</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Cartons Claimed</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>% claimed</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>POW Payment @ 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>POW Denial @ 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>TGS Claim Responsibility 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Claim Acceptance</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>POW 68% Responsibility</b></font></td>
			</tr>
<?

while(list($cust_id, $cust_name)=each($cust)){
	$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM FROM ".$CT_table." 
			WHERE RECEIVER_ID = '".$cust_id."' 
			AND DATE_RECEIVED IS NOT NULL
			AND RECEIVING_TYPE != 'T'
			AND COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = '".$cargo_type."')";
	$statement = ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$cartons = $row['THE_SUM'];
	$total_cartons += $cartons;


	$sql = "select customer_id, sum(claim_amt) as submission, sum(claim_qty) as CASES 
			from claim_header_rf h, claim_body_rf b 
			where h.claim_id = b.claim_id 
			and customer_id = $cust_id 
			and h.season='$season' 
			and b.status <> 'D'
			and h.claim_cargo_type='CHILEAN'
			and vessel is not null
			and vessel != 'TRUCK'
			group by customer_id";
	$statement = ora_parse($cursor, $sql);
//	echo $sql;
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$submission = $row['SUBMISSION'];
		$cartons_claimed = $row['CASES'];
		$total_submission += $submission;
		$total_cartons_claimed += $cartons_claimed;
	} else {
		$submission = "0";
		$cartons_claimed = "0";
	}
	
	if($cartons == 0){
		$carton_percent = 0;
	} else {
		$carton_percent = round(($cartons_claimed / $cartons) * 100, 2);
	}

	$sql = "select customer_id, sum(claim_amt) as acceptance 
			from claim_header_rf h, claim_body_rf b 
			where h.claim_id = b.claim_id 
			and customer_id = $cust_id 
			and h.season='$season' 
			and b.status <>'D' 
			and h.ispercentage = 'Y' 
			and h.claim_cargo_type='CHILEAN'
			and vessel is not null
			and vessel != 'TRUCK'
			group by customer_id";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$acceptance = $row['ACCEPTANCE'];
		$sixty_eight = round($acceptance * 0.68, 2);
		$total_acceptance += $acceptance;
		$total_68 += $sixty_eight;
	} else {
		$acceptance = "0";
		$sixty_eight = "0";
	}

	$sql = "select customer_name from customer_profile where customer_id = $cust_id";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$temp = split("-", $row['CUSTOMER_NAME'], 2);
	$customer_name = $temp[1];

	$sql = "select customer_name, sum(port_amt) as sumport, sum(denied_amt) as sumdenied, sum(ship_line_amt) as sumsline 
			from claim_header_rf h, claim_body_rf b, customer_profile c 
			where h.claim_id = b.claim_id 
			and h.customer_id = c.customer_id 
			and (h.status is null or h.status <>'D') 
			and (b.status is null or b.status <>'D') 
			and h.season = $season 
			and h.claim_cargo_type='CHILEAN'
			and h.customer_id = $cust_id 
			and (h.ispercentage is null or h.ispercentage <>'Y') 
			and vessel is not null
			and vessel != 'TRUCK'
			group by customer_name";
//	echo $sql;
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pow_100_payment = $row['SUMPORT'];
		$pow_100_denial = $row['SUMDENIED'];
		$powpsw_resp = $row['SUMSLINE'];
		$total_pow_100_payment += $pow_100_payment;
		$total_pow_100_denial += $pow_100_denial;
		$total_powpsw_resp += $powpsw_resp;
	} else {
		$pow_100_payment = "0";
		$pow_100_denial = "0";
		$powpsw_resp = "0";
	}

?>
			<tr>
				<td align="left"><font size="2" face="Verdana"><a href="report_detail.php?season=<? echo $season; ?>&customer=<? echo $cust_id; ?>&LR=ship&cargo_type=CHILEAN"><? echo $customer_name; ?></a></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($submission,2,'.',','); ?></font></td>

				<td align="right"><font size="2" face="Verdana"><? echo number_format($cartons,0,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana"><? echo number_format($cartons_claimed,0,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana"><? echo $carton_percent; ?>%</font></td>
				
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($pow_100_payment,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($pow_100_denial,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($powpsw_resp,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($acceptance,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($sixty_eight,2,'.',','); ?></font></td>
			</tr>
<?
}
	if($total_submission == "" || $total_submission == 0){
		$total_submission = "0";
	}
	if($total_pow_100_payment == "" || $total_pow_100_payment == 0){
		$total_pow_100_payment = "0";
	}
	if($total_pow_100_denial == "" || $total_pow_100_denial == 0){
		$total_pow_100_denial = "0";
	}
	if($total_powpsw_resp == "" || $total_powpsw_resp == 0){
		$total_powpsw_resp = "0";
	}
	if($total_acceptance == "" || $total_acceptance == 0){
		$total_acceptance = "0";
	}
	if($total_68 == "" || $total_68 == 0){
		$total_68 = "0";
	}

	if($total_cartons == 0){
		$total_carton_percent = 0;
	} else {
		$total_carton_percent = round(($total_cartons_claimed / $total_cartons) * 100, 2);
	}
	
?>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b><? echo "Total"; ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_submission,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo number_format($total_cartons,0,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo number_format($total_cartons_claimed,0,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo $total_carton_percent; ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_pow_100_payment,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_pow_100_denial,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_powpsw_resp,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_acceptance,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_68,2,'.',','); ?></b></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td width="40%">
		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td colspan="2" align="center"><font size="3" face="Verdana"><b>POW Payment Summary:</b></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW payment to customer</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_pow_100_payment,2,'.',','); ?></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW 68% payment to TGS</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_68,2,'.',','); ?></font></td>
			</tr>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b>Total POW payments</b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_68 + $total_pow_100_payment,2,'.',','); ?></b></font></td>
			</tr>
		</table>
		</td>
		<td width="20%">&nbsp;</td>
		<td width="40%">
		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td colspan="2" align="center"><font size="3" face="Verdana"><b>TGS Payment Summary:</b></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW & TGS (claim split)</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_powpsw_resp,2,'.',','); ?></font></td>
			</tr>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b>Total TGS payments</b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_powpsw_resp,2,'.',','); ?></b></font></td>
			</tr>
		</table>
		</td>
	</tr>
</table>























<?
function NonChileanReport($conn, $conn2, $season, $CT_table, $cargo_type){
	$cursor = ora_open($conn);
	$RFcursor = ora_open($conn2);


	$system = "RF";
	$data = array();

	//get customer name
	$cust = getRFSeasonCustomerList($cursor, $season, $cargo_type);

	$total_submission = 0;
	$total_pow_100_payment = 0;
	$total_pow_100_denial = 0;
	$total_powpsw_resp = 0;
	$total_acceptance = 0;
	$total_68 = 0;
	$total_cartons = 0;
	$total_cartons_claimed = 0;




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3">
		<table border="2" width="100%" cellpadding="0" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td align="center"><font size="4" face="Verdana"><b><? echo $season; ?> POW Claim Summary Report (No Trucks) -- <? echo $cargo_type; ?></b></font></td>
			</tr>
		</table>
		</td>
	</tr> 
	<tr>
		<td colspan="3">&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">
		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td align="center"><font size="3" face="Verdana"><b>Customer</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Claims Submission</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>POW Payment @ 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>POW Denial @ 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>ShipLine Claim Responsibility 100%</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Cartons Rec'd</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>Cartons Claimed</b></font></td>
				<td align="center"><font size="3" face="Verdana"><b>% claimed</b></font></td>
			</tr>


<?
	while(list($cust_id, $cust_name)=each($cust)){
		$sql = "SELECT SUM(QTY_RECEIVED) THE_SUM FROM ".$CT_table." 
				WHERE RECEIVER_ID = '".$cust_id."' 
				AND DATE_RECEIVED IS NOT NULL
				AND RECEIVING_TYPE != 'T'
				AND COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = '".$cargo_type."')";
		$statement = ora_parse($RFcursor, $sql);
		ora_exec($RFcursor);
		ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$cartons = $row['THE_SUM'];
		$total_cartons += $cartons;


		$sql = "select customer_id, sum(claim_amt) as submission, sum(claim_qty) as CASES 
				from claim_header_rf h, claim_body_rf b 
				where h.claim_id = b.claim_id 
				and customer_id = $cust_id 
				and h.season='$season' 
				and h.claim_cargo_type='$cargo_type'
				and b.status <> 'D'
				and (	(vessel is not null
						and vessel != 'TRUCK')
					or
						((vessel is null or vessel != 'TRUCK')
						and '".$cargo_type."' = 'CLEMENTINES')
					)
				group by customer_id";
		$statement = ora_parse($cursor, $sql);
	//	echo $sql;
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$submission = $row['SUBMISSION'];
			$cartons_claimed = $row['CASES'];
			$total_submission += $submission;
			$total_cartons_claimed += $cartons_claimed;
		} else {
			$submission = "0";
			$cartons_claimed = "0";
		}
		
		if($cartons == 0){
			$carton_percent = 0;
		} else {
			$carton_percent = round(($cartons_claimed / $cartons) * 100, 2);
		}

		$sql = "select customer_id, sum(claim_amt) as acceptance 
				from claim_header_rf h, claim_body_rf b 
				where h.claim_id = b.claim_id 
				and customer_id = $cust_id 
				and h.season='$season' 
				and h.claim_cargo_type='$cargo_type'
				and b.status <>'D' 
				and h.ispercentage = 'Y' 
				and (	(vessel is not null
						and vessel != 'TRUCK')
					or
						((vessel is null or vessel != 'TRUCK')
						and '".$cargo_type."' = 'CLEMENTINES')
					)
				group by customer_id";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$acceptance = $row['ACCEPTANCE'];
			$sixty_eight = round($acceptance * 0.68, 2);
			$total_acceptance += $acceptance;
			$total_68 += $sixty_eight;
		} else {
			$acceptance = "0";
			$sixty_eight = "0";
		}

		$sql = "select customer_name from customer_profile where customer_id = $cust_id";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$temp = split("-", $row['CUSTOMER_NAME'], 2);
		$customer_name = $temp[1];

		$sql = "select customer_name, sum(port_amt) as sumport, sum(denied_amt) as sumdenied, sum(ship_line_amt) as sumsline 
				from claim_header_rf h, claim_body_rf b, customer_profile c 
				where h.claim_id = b.claim_id 
				and h.customer_id = c.customer_id 
				and (h.status is null or h.status <>'D') 
				and (b.status is null or b.status <>'D') 
				and h.season = $season 
				and h.customer_id = $cust_id 
				and h.claim_cargo_type='$cargo_type'
				and (h.ispercentage is null or h.ispercentage <>'Y') 
				and (	(vessel is not null
						and vessel != 'TRUCK')
					or
						((vessel is null or vessel != 'TRUCK')
						and '".$cargo_type."' = 'CLEMENTINES')
					)
				group by customer_name";
	//	echo $sql;
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$pow_100_payment = $row['SUMPORT'];
			$pow_100_denial = $row['SUMDENIED'];
			$powpsw_resp = $row['SUMSLINE'];
			$total_pow_100_payment += $pow_100_payment;
			$total_pow_100_denial += $pow_100_denial;
			$total_powpsw_resp += $powpsw_resp;
		} else {
			$pow_100_payment = "0";
			$pow_100_denial = "0";
			$powpsw_resp = "0";
		}

?>
			<tr>
				<td align="left"><font size="2" face="Verdana"><a href="report_detail.php?season=<? echo $season; ?>&customer=<? echo $cust_id; ?>&LR=ship&cargo_type=<? echo $cargo_type; ?>"><? echo $customer_name; ?></a></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($submission,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($pow_100_payment,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($pow_100_denial,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($powpsw_resp,2,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana"><? echo number_format($cartons,0,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana"><? echo number_format($cartons_claimed,0,'.',','); ?></font></td>
				<td align="right"><font size="2" face="Verdana"><? echo $carton_percent; ?>%</font></td>
			</tr>
<?
	}

	if($total_submission == "" || $total_submission == 0){
		$total_submission = "0";
	}
	if($total_pow_100_payment == "" || $total_pow_100_payment == 0){
		$total_pow_100_payment = "0";
	}
	if($total_pow_100_denial == "" || $total_pow_100_denial == 0){
		$total_pow_100_denial = "0";
	}
	if($total_powpsw_resp == "" || $total_powpsw_resp == 0){
		$total_powpsw_resp = "0";
	}
	if($total_acceptance == "" || $total_acceptance == 0){
		$total_acceptance = "0";
	}
	if($total_68 == "" || $total_68 == 0){
		$total_68 = "0";
	}

	if($total_cartons == 0){
		$total_carton_percent = 0;
	} else {
		$total_carton_percent = round(($total_cartons_claimed / $total_cartons) * 100, 2);
	}

?>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b><? echo "Total"; ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_submission,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_pow_100_payment,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_pow_100_denial,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_powpsw_resp,2,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo number_format($total_cartons,0,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo number_format($total_cartons_claimed,0,'.',','); ?></b></font></td>
				<td align="right"><font size="2" face="Verdana"><b><? echo $total_carton_percent; ?></b></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td width="40%">
		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td colspan="2" align="center"><font size="3" face="Verdana"><b>POW Payment Summary:</b></font></td>
			</tr>
<!--			<tr>
				<td align="left"><font size="2" face="Verdana">POW payment to customer</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_pow_100_payment,2,'.',','); ?></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW 68% payment to PacSea</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_68,2,'.',','); ?></font></td>
			</tr>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b>Total POW payments</b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_68 + $total_pow_100_payment,2,'.',','); ?></b></font></td>
			</tr> !-->
			<tr>
				<td align="left"><font size="2" face="Verdana">Claim Submission</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_submission,2,'.',','); ?></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW Payment @100%</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_pow_100_payment,2,'.',','); ?></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW Denial @100%</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_pow_100_denial,2,'.',','); ?></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">Ship Line Responsibility</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_powpsw_resp,2,'.',','); ?></font></td>
			</tr>

		</table>
		</td>
		<td width="20%">&nbsp;</td>
		<td width="40%">&nbsp;
<!--		<table border="1" width="100%" cellpadding="1" cellspacing="0">
			<tr bgcolor="F0F0F0">
				<td colspan="2" align="center"><font size="3" face="Verdana"><b>PacSea Payment Summary:</b></font></td>
			</tr>
			<tr>
				<td align="left"><font size="2" face="Verdana">POW & PacSea (claim split)</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo number_format($total_powpsw_resp,2,'.',','); ?></font></td>
			</tr>
			<tr bgcolor="F0F0F0">
				<td align="left"><font size="2" face="Verdana"><b>Total PacSea payments</b></font></td>
				<td align="right"><font size="2" face="Verdana"><b>$<? echo number_format($total_powpsw_resp,2,'.',','); ?></b></font></td>
			</tr>
		</table> !-->		
		</td>
	</tr>
</table>
<?
}
?>