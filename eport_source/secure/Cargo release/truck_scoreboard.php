<?
/*
*	Adam Walter, Sep 2013.
*
*	splash page showing release status of containers.
*************************************************************************/

	$pagename = "truck_main_board";  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$status = $HTTP_POST_VARS['status'];
	if($status == ""){
		$status = "atpow";
	}
	$driver = $HTTP_POST_VARS['driver'];
	if($driver == ""){
		$driver = "atpow";
	}

	$truck_ID = $HTTP_POST_VARS['truck_ID'];
	if($truck_ID == ""){
		$truck_ID = $HTTP_GET_VARS['truck_ID'];
	}
	$gated_date = $HTTP_POST_VARS['gated_date'];

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Delete" && $truck_ID != ""){
		$sql = "DELETE FROM CLR_TRUCK_MAIN_JOIN
				WHERE TRUCK_PORT_ID = '".$truck_ID."'";
		$delete = ociparse($rfconn, $sql);
		ociexecute($delete);

		$sql = "DELETE FROM CLR_TRUCK_LOAD_RELEASE
				WHERE PORT_ID = '".$truck_ID."'";
		$delete = ociparse($rfconn, $sql);
		ociexecute($delete);

		$truck_ID = "";
	}

?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">CLR Trucker Main Board</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?> 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="truck_scoreboard_index.php" method="post">
	<tr>
		<td><input type="radio" name="driver" value="atpow"<? if($driver == "atpow"){?> checked <?}?>>
				&nbsp;&nbsp;<font size="2" face="Verdana">Driver Checked In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="driver" value="all"<? if($driver == "all"){?> checked <?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All</td>
	</tr>
	<tr>
		<td><input type="radio" name="status" value="atpow"<? if($status == "atpow"){?> checked <?}?>><font size="2" face="Verdana">&nbsp;&nbsp;Gate Pass Not Yet Issued</font>
				<input type="radio" name="status" value="gated"<? if($status == "gated"){?> checked <?}?>><font size="2" face="Verdana">&nbsp;&nbsp;Gated On:</font>&nbsp;
						<input type="text" name="gated_date" size="10" maxlength="10" value="<? echo $gated_date;?>"><font size="1" face="Verdana">(MM/DD/YYYY Format)</font>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="status" value="all"<? if($status == "all"){?> checked <?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All</font>
			</td>
	</tr>
	<tr>
		<td colspan="11"><input type="submit" name="submit" value="Retrieve / Refresh"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>TRUCK ID#</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>CARGO ON TRUCK</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>DRIVER NAME</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>DRIVER LICENSE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>TRUCK COMPANY</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>TRUCK LICENSE / STATE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>REEFER</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>TRUCK-T&E#</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>GATE PASS MADE ON</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>SEAL #</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>SEAL TIME</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>ATTACHED CARGO RELEASE STATUS</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>PICKUP TYPE</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>ASSIGN CARGO</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>FAX</b></font></td> 
		<td align="center"><font size="2" face="Verdana"><b>ACTIONS</b></font></td> 
	</tr>
<?
	if($status == "atpow"){
		$sql_filter = " AND GATEPASS_PDF_DATE IS NULL ";
	} elseif($status == "gated") {
		if(ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $gated_date)){
			$sql_filter = " AND TO_CHAR(GATEPASS_PDF_DATE, 'MM/DD/YYYY') = '".$gated_date."' ";
		}
	} else {
		$sql_filter = " ";
	}
	if($driver == "atpow"){
		$sql_filter .= " AND DRIVER_NAME IS NOT NULL ";
	} else {
		$sql_filter .= " ";
	}
	if($truck_ID != ""){
		$sql_filter .= " AND PORT_ID = '".$truck_ID."' ";
	}
//			ORDER BY PORT_ID";
	$sql = "SELECT PORT_ID, DRIVER_NAME, DRIVER_LIC_NUM, DRIVER_LIC_STATE, TRUCKING_COMPANY, TRUCK_LIC_NUM, TRUCK_LIC_STATE, TRAIL_REEF_PLATE_NUM, TRAIL_REEF_PLATE_STATE, PICKUP_TYPE, TRUCK_TE_NUM,
				DECODE(GATEPASS_MADE_ON, NULL, 'NOT GATED', TO_CHAR(GATEPASS_MADE_ON, 'MM/DD/YYYY HH:MI AM')) THE_GATEPASS,
				SEAL_NUM, DECODE(SEAL_TIME, NULL, 'NOT SCAN-SEALED', TO_CHAR(SEAL_TIME, 'MM/DD/YYYY HH:MI AM')) THE_SEALTIME,				
				DECODE(CBP_FAX, NULL, 'NOT CBP-FAXED', TO_CHAR(CBP_FAX, 'MM/DD/YYYY HH:MI AM')) THE_FAXTIME
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE 1 = 1
				".$sql_filter."
			ORDER BY GATEPASS_MADE_ON DESC NULLS FIRST,
				SEAL_TIME NULLS LAST";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$plt_count = 0;
?>
	<tr>
		<td><nobr><font size="2" face="Verdana"><a href="truck_release_index.php?truck_ID=<? echo ociresult($short_term_data, "PORT_ID"); ?>"><? echo ociresult($short_term_data, "PORT_ID"); ?></a>&nbsp;&nbsp;
				<font size="2" face="Verdana"><a href="truck_checkout_index.php?truck_ID=<? echo ociresult($short_term_data, "PORT_ID"); ?>">(checkout)</a></font></nobr></td> 
		<td><? DisplayCargoOnTruck(ociresult($short_term_data, "PORT_ID"), $plt_count, $rfconn); ?></td> 
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_NAME"); ?></font></td> 
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_LIC_NUM")."&nbsp;".ociresult($short_term_data, "DRIVER_LIC_STATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCKING_COMPANY"); ?></font></td> 
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCK_LIC_NUM")."&nbsp;".ociresult($short_term_data, "TRUCK_LIC_STATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRAIL_REEF_PLATE_NUM")."&nbsp;".ociresult($short_term_data, "TRAIL_REEF_PLATE_STATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCK_TE_NUM"); ?></font></td> 
		<? DisplayGatePassLink(ociresult($short_term_data, "PORT_ID"), $rfconn); ?>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "SEAL_NUM"); ?></font></td> !-->
		<? DisplaySeal(ociresult($short_term_data, "THE_SEALTIME"), ociresult($short_term_data, "SEAL_NUM")); ?>
<!--		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_SEALTIME"); ?></font></td> !-->
		<? DisplaySealTime(ociresult($short_term_data, "THE_SEALTIME")); ?>
		<? DisplayCargoLink(ociresult($short_term_data, "PORT_ID"), $rfconn); ?>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PICKUP_TYPE"); ?></font></td> 
		<td><? DisplayAssignLink(ociresult($short_term_data, "PORT_ID"), $rfconn); ?></td> 
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_FAXTIME"); ?></font></td> 
		<? echo DisplayActionButtons(ociresult($short_term_data, "PORT_ID"), $plt_count, $security_allowance, $status, $driver, $gated_date, 
						ociresult($short_term_data, "THE_SEALTIME"), $rfconn); ?> 
	</tr>
<?
	}
?>
</table>





<?

function DisplayCargoLink($truck_ID, $rfconn){
	
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_TRUCK_MAIN_JOIN
			WHERE TRUCK_PORT_ID = '".$truck_ID."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") <= 0){
		$return_string = "<td><font size=\"2\" face=\"Verdana\">No CLR-Cargo Currently Attached.</font></td>";
	} else {
		$bgcolor="#CCFFCC";

		$return_string = "<font size=\"2\" face=\"Verdana\"><a href=\"cargo_scoreboard_index.php?truck_ID=".$truck_ID."\">Truck ID ".$truck_ID."</a>";

		$sql = "SELECT * 
				FROM CLR_TRUCK_MAIN_JOIN CTMJ, CLR_MAIN_DATA CMD
				WHERE TRUCK_PORT_ID = '".$truck_ID."'
					AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY";
		$joins = ociparse($rfconn, $sql);
		ociexecute($joins);
		while(ocifetch($joins)){
			$return_string .= "<br><nobr>(".ociresult($joins, "MAIN_CLR_KEY")." ".ociresult($joins, "DESTINATION").")</nobr>";

			$sql = "SELECT NVL(TO_CHAR(GREATEST(AMS_RELEASE, LINE_RELEASE, BROKER_RELEASE), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL
					FROM CLR_MAIN_DATA
					WHERE CLR_KEY = '".ociresult($joins, "MAIN_CLR_KEY")."'";
//			echo $sql."<br>";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_FINAL") == "ON HOLD"){
				$bgcolor="#FFCCCC";
			}

		}

		$return_string = trim($return_string)."</font>";

		$return_string = "<td bgcolor=\"".$bgcolor."\">".$return_string."</td>";
	}

	echo $return_string;
}

		




function DisplayAssignLink($truck_ID, $rfconn){
	echo "<font size=\"2\" face=\"Verdana\"><a href=\"truck_clr_join_index.php?truck_ID=".$truck_ID."\">".$truck_ID."</a><br>";
/*	$sql = "SELECT *
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID = '".$truck_ID."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data) || ociresult($short_term_data, "PICKUP_TYPE") == ""){
		$display = "Not Set";
	} else {
		$display = ociresult($short_term_data, "PICKUP_TYPE");
	}
	echo "(".$display.")</font>";*/
}

function DisplayGatePassLink($truck_ID, $rfconn){
	$sql = "SELECT CTLR.*, TO_CHAR(CTLR.GATEPASS_MADE_ON, 'MM/DD/YYYY HH:MI AM') GATE_DATE
            FROM CLR_TRUCK_LOAD_RELEASE CTLR
 			WHERE PORT_ID = '".$truck_ID."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "GATEPASS_NUM") == ""){
		echo "<td bgcolor=\"#FFCCCC\"><font size=\"2\" face=\"Verdana\">Not Gated</font></td>";
	} else {
		echo "<td bgcolor=\"#CCFFCC\"><font size=\"2\" face=\"Verdana\"><a href=\"cargo_gatepass.php?truck_id=".$truck_ID."\">".ociresult($short_term_data, "GATEPASS_NUM")."</a><br>".ociresult($short_term_data, "GATE_DATE")."</font></td>";
	}
}

function DisplayCargoOnTruck($truck_ID, &$plt_count, $rfconn){
	// THIS FUNCTION ONLY WORKS FOR CLEMENTINE ORDERS
	$default_return = "&nbsp;";

	$sql = "SELECT CLEM_ORDER_NUM 
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID = '".$truck_ID."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "CLEM_ORDER_NUM") == ""){
		echo $default_return;
		return;
	}

	$order_num = trim(ociresult($short_term_data, "CLEM_ORDER_NUM"));
	$cust = '835';

	$sql = "SELECT VESSELID
			FROM DC_ORDER
			WHERE ORDERNUM = '".$order_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		echo $order_num;
		return;
	}
	if(ociresult($short_term_data, "VESSELID") == ""){
		echo $order_num;
		return;
	}

	$vessel = trim(ociresult($short_term_data, "VESSELID"));

	$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, NVL(SUM(QTY_CHANGE), 0) THE_CTNS
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$order_num."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'
				AND SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
//	echo $sql."<br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_PLTS") == 0){
		echo $order_num;
		return;
	}

	$plt_count = ociresult($short_term_data, "THE_PLTS");
	echo $order_num."<br><nobr>".ociresult($short_term_data, "THE_PLTS")."plts   ".ociresult($short_term_data, "THE_CTNS")."ctns</nobr>";
}

function DisplaySealTime($time){
	if($time == "NOT SCAN-SEALED"){
		$bg = "#FFCCCC";
	} else {
		$bg = "#CCFFCC";
	}

	echo "<td bgcolor=\"".$bg."\"><font size=\"2\" face=\"Verdana\">".$time."</font></td>";
}

function DisplaySeal($time, $num){
	if($time == "NOT SCAN-SEALED"){
		$bg = "#FFCCCC";
	} else {
		$bg = "#CCFFCC";
	}

	echo "<td bgcolor=\"".$bg."\"><font size=\"2\" face=\"Verdana\">".$num."</font></td>";
}

function DisplayActionButtons($key_id, $plt_count, $security_allowance, $status, $driver, $gated_date, $sealtime, $rfconn){
	$return = "<td>&nbsp;";
/*	if(strpos($security_allowance, "M") !== false){
		$return .= "<form name=\"mod\" action=\"mod_clr_index.php\" method=\"post\"><input type=\"hidden\" name=\"clr_key\" value=\"".$key_id."\">
									<input type=\"submit\" name=\"submit\" value=\"Modify\"></form>";
	}*/
	if(strpos($security_allowance, "D") !== false && $plt_count <= 0 && $sealtime == "NOT SCAN-SEALED"){
		$return .= "<form name=\"del\" action=\"truck_scoreboard_index.php\" method=\"post\">
						<input type=\"hidden\" name=\"truck_ID\" value=\"".$key_id."\">
						<input type=\"hidden\" name=\"status\" value=\"".$status."\">
						<input type=\"hidden\" name=\"driver\" value=\"".$driver."\">
						<input type=\"hidden\" name=\"gated_date\" value=\"".$gated_date."\">
									<input type=\"submit\" name=\"submit\" value=\"Delete\"></form>";
	}

	$return .= "</td>";

	return $return;
}
