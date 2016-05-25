<?
/*
*		Adam Walter, Sep 2014
*
*		Fina's ability to maniuplate a Labor Ticket prior to it being billed.
*
*****************************************************************/

	$bniconn = ocilogon("LABOR", "LABOR", "LCS");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($bniconn));
		exit;
	}

	$show_msg = "";
	$ticket_status = $HTTP_POST_VARS['ticket_status'];
	if($ticket_status == ""){
		$ticket_status = "all";
	}
	$ticket_type = $HTTP_POST_VARS['ticket_type'];
	if($ticket_type == ""){
		$ticket_type = "all";
	}
	$ticket_start = $HTTP_POST_VARS['ticket_start'];
	if($ticket_start == ""){
		$ticket_start = date('m/d/Y');
	}
	$ticket_end = $HTTP_POST_VARS['ticket_end'];
	if($ticket_end == ""){
		$ticket_end = date('m/d/Y');
	}
	$ticket_select = $HTTP_POST_VARS['ticket_select'];

	$submit = $HTTP_POST_VARS['submit'];
/*	
	if($submit == "Review/Edit"){
		$bad_msg = "";
		$maxrows = $HTTP_POST_VARS['maxrows'];

		$ticketnum = $HTTP_POST_VARS['ticketnum'];
//		$ticketact = $HTTP_POST_VARS['ticketact'];
//		$rejectreason = $HTTP_POST_VARS['rejectreason'];
//		print_r($ticketact);

		$ticket_list_for_processing = "(''";

		for($i = 0; $i <= $maxrows; $i++){
			// for each row, see if this ticket needs action...
			if($ticketact[$i] == "U"){
			if($ticketact[$i] == "U"){
				// did they remember to give a reason?
				if($rejectreason[$i] != ""){
					$sql = "UPDATE LABOR.LABOR_TICKET_HEADER
							SET BILL_STATUS = 'U',
								BILL_DESC = '".$rejectreason[$i]."'
							WHERE TICKET_NUM = '".$ticketnum[$i]."'";
					$update = ociparse($bniconn, $sql);
					ociexecute($update);

					SendAnEmail();
				} else {
					$bad_msg .= "Could not make ticket ".$ticketnum[$i]." as Unbillable; no rejection reason was given.<br>";
				}
			} elseif($ticketact[$i] == "Y"){
				$ticket_list_for_processing .= ", '".$ticketnum[$i]."'";
			}
		}

		$ticket_list_for_processing .= ", '".$ticketnum."'";
		$ticket_list_for_processing .= ")";

		if($bad_msg != ""){
			$show_msg .= "<font color=\"#FF0000\">The following tickets had errors within this screen:<br>".$bad_msg."</font><br>";
		} else {

	//		CallLaborV2Routine();
			include("./labor_v2_b_create.php");
	//		include("../../crons/labor_v2_b_create.php");
			$show_msg .= "<font color=\"#0000FF\">".$total_tickets_processed." Labor tickets processed.  Please check the preinvoice print screen.<br><br></font>";
			if($error_msg != ""){ // THIS IS SET IN THE CALLED SCRIPT
				$show_msg .= "<font color=\"#FF0000\">The following Labor Tickets were not billed, due to:<br>".$error_msg."<br><br>
						Please correct the Labor Tickets if you need these bills.<br></font>";
			}
		}

		// this part is generated from the V2 routine
		if($reply_msg != ""){
			$show_msg .= "<font color=\"#FF0000\">The following tickets had errors during PreBill creation:<br>".$reply_msg."</font><br>";
		} else {
			header("view_labor_prebill.php?LTnum=".$ticketnum);
		}


	}
*/


  // Define some vars for the skeleton page
  $title = "Finance System - Labor Bill Review";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
	echo $show_msg;

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Labor Ticket Listing
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_tickets" action="ticket_to_prebills.php" method="post">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Dates:</b></font>
	</tr>
	<tr>
		<td width="5%">&nbsp;&nbsp;&nbsp;</td>
		<td width="20%"><font size="2" face="Verdana">Ticket Date Starts on or after:</font></td>
		<td align="left"><input type="text" name="ticket_start" value="<? echo $ticket_start; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select_tickets.ticket_start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><font size="2" face="Verdana">Ticket Date no later than:</font></td>
		<td><input type="text" name="ticket_end" value="<? echo $ticket_end; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select_tickets.ticket_end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Ticket Status:</b></font></td>
		<td align="left"><select name="ticket_status"><option value="all">All Tickets</option>
											<option value="N"<? if($ticket_status == "N"){?> selected <?}?>>N - To Post</option>
											<option value="Y"<? if($ticket_status == "Y"){?> selected <?}?>>Y - Posted</option>
											<option value="U"<? if($ticket_status == "U"){?> selected <?}?>>U - Rejected; (Review by Supervisor)</option>
											<option value="V"<? if($ticket_status == "V"){?> selected <?}?>>V - Voided</option>
						</select>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Ticket Type:</b></font></td>
		<td align="left"><select name="ticket_type"><option value="all">All Tickets</option>
											<option value="labor"<? if($ticket_type == "labor"){?> selected <?}?>>Labor</option>
											<option value="security"<? if($ticket_type == "security"){?> selected <?}?>>Security</option>
						</select>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Ticket #:</b></font></td>
		<td align="left"><input type="text" name="ticket_select" value="<? echo $ticket_select; ?>" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><input type="submit" name="submit" value="Filter Tickets"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="VErdana"><b>Labor Ticket#</b></font></td>
		<td><font size="2" face="VErdana"><b>Supervisor</b></font></td>
		<td><font size="2" face="VErdana"><b>Ticket Date</b></font></td>
		<td><font size="2" face="VErdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="VErdana"><b>Commodity</b></font></td>
		<td><font size="2" face="VErdana"><b>Customer</b></font></td>
		<td><font size="2" face="VErdana"><b>Service Group</b></font></td>
		<td><font size="2" face="VErdana"><b>Status</b></font></td>
		<td><font size="2" face="VErdana"><b>Action</b></font></td>
	</tr>
<?
	$more_sql = "";
	if($ticket_status == "N"){
		$more_sql .= " AND (BILL_STATUS IS NULL OR BILL_STATUS = '".$ticket_status."')";
	} elseif($ticket_status != "all"){
		$more_sql .= " AND BILL_STATUS = '".$ticket_status."'";
	}
	if($ticket_start != ""){
		$more_sql .= " AND SERVICE_DATE >= TO_DATE('".$ticket_start."', 'MM/DD/YYYY')";
	}
	if($ticket_end != ""){
		$more_sql .= " AND SERVICE_DATE <= TO_DATE('".$ticket_end."', 'MM/DD/YYYY')";
	}
	if($ticket_select != ""){
		$more_sql .= " AND TICKET_NUM = '".$ticket_select."' ";
	}
	if($ticket_type == "labor"){
		$more_sql .= " AND (JOB_DESCRIPTION IS NULL OR JOB_DESCRIPTION != 'SECURITY') ";
	}
	if($ticket_type == "security"){
		$more_sql .= " AND JOB_DESCRIPTION = 'SECURITY' ";
	}
	$sql = "SELECT LTH.*, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, DECODE(BILL_STATUS, NULL, 'N', BILL_STATUS) THE_STAT, LTH.USER_ID, USER_NAME
			FROM LABOR.LABOR_TICKET_HEADER LTH, LABOR.LCS_USER LCS
			WHERE 1 = 1
				AND LTH.USER_ID = LCS.USER_ID
				".$more_sql."
			ORDER BY TICKET_NUM";
	$tickets = ociparse($bniconn, $sql);
	ociexecute($tickets);
	if(!ocifetch($tickets)){
?>
	<tr>
		<td align="center" colspan="9"><font size="2" face="VErdana"><b>No tickets found for selected criteria</b></font></td>
	</tr>
<?
	} else {
		$row_num = -1;
		do {
?>
<form name="update_tickets<? echo $row_num; ?>" action="view_labor_prebill.php" method="post">
<input type="hidden" name="ticket_end" value="<? echo $ticket_end; ?>">
<input type="hidden" name="ticket_start" value="<? echo $ticket_start; ?>">
<input type="hidden" name="ticket_status" value="<? echo $ticket_status; ?>">
<?
			$row_num++;

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
					WHERE LR_NUM = '".ociresult($tickets, "VESSEL_ID")."'";
			$ves = ociparse($bniconn, $sql);
			ociexecute($ves);
			ocifetch($ves);
			$vesname = ociresult($ves, "VESSEL_NAME");

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
					WHERE COMMODITY_CODE = '".ociresult($tickets, "COMMODITY_CODE")."'";
			$comm = ociparse($bniconn, $sql);
			ociexecute($comm);
			ocifetch($comm);
			$commname = ociresult($comm, "COMMODITY_NAME"); //ociresult($tickets, "COMMODITY_CODE")."-".

			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
					WHERE CUSTOMER_ID = '".ociresult($tickets, "CUSTOMER_ID")."'";
			$cust = ociparse($bniconn, $sql);
			ociexecute($cust);
			ocifetch($cust);
			$custname = ociresult($cust, "CUSTOMER_NAME");

			if(ociresult($tickets, "JOB_DESCRIPTION") == "SECURITY"){
				$more_info = " (Security)";
			} else {
				$more_info = " (Labor)";
			}

			switch(ociresult($tickets, "THE_STAT")){
				case "N":
					$status = "To Post";
					$extra_desc = "";
				break;
				case "Y":
					$status = "Posted";
					$extra_desc = "";
				break;
				case "U":
					$status = "Rejected";
					$extra_desc = "<br>(Reason Given:  ".ociresult($tickets, "BILL_DESC").")";
				break;
				case "V":
					$status = "Voided";
					$extra_desc = "";
				break;
			}


?>
<!--	<input type="hidden" name="ticketnum[<? echo $row_num;?>]" value="<? echo ociresult($tickets, "TICKET_NUM"); ?>"> !-->
	<input type="hidden" name="LTnum" value="<? echo ociresult($tickets, "TICKET_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($tickets, "TICKET_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($tickets, "USER_ID")." - ".ociresult($tickets, "USER_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($tickets, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $commname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $custname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($tickets, "SERVICE_GROUP"); ?><? echo $more_info; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $status; ?></font></td>
		<td><font size="2" face="Verdana"><? echo DisplayActionOption($bniconn, $row_num, ociresult($tickets, "TICKET_NUM")).$extra_desc; ?></font></td>
	</tr>
</form>
<?
		} while(ocifetch($tickets));
?>
	<!--<tr><td colspan="8"><input type="submit" name="submit" value="Process Tickets"></td></tr> !-->
<?
	}
?>
<!--<input type="hidden" name="maxrows" value="<? echo $row_num; ?>"> !-->
</table>
<?
	include("pow_footer.php");










function DisplayActionOption($bniconn, $row_num, $ticket_num){
	$sql = "SELECT *
			FROM LABOR.LABOR_TICKET_HEADER LTH
			WHERE TICKET_NUM = '".$ticket_num."'";
	$tickets = ociparse($bniconn, $sql);
	ociexecute($tickets);
	ocifetch($tickets);	
	if(ociresult($tickets, "BILL_STATUS") == "Y"){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM FINA_LT_HEADER
				WHERE LABOR_TICKET_NUM = '".$ticket_num."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);	
		if(ociresult($short_term_data, "THE_COUNT") >= 1){
			$return = "<a href=\"view_labor_prebill.php?LTnum=".$ticket_num."\">Ticket is already processed</a>";
		} else {
			$return = "Ticket was handled in the old v1 Billing system.";
		}
	}elseif(ociresult($tickets, "BILL_STATUS") == "U"){
		$return = "Ticket is Rejected, and in the SUPV hands.";
	}elseif(ociresult($tickets, "BILL_STATUS") == "V"){
		$return = "Ticket is Voided.";
	}else {
		$return = "<input type=\"submit\" name=\"submit\" value=\"Review/Edit\">";
	}
/*
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM BILL_HEADER BH, BILL_DETAIL BD
			WHERE BH.BILLING_NUM = BD.BILLING_NUM
				AND BD.LABOR_TICKET_NUM = '".$ticket_num."'
				AND SERVICE_STATUS = 'PREINVOICE'";
	$bill = ociparse($bniconn, $sql);
	ociexecute($bill);
	ocifetch($bill);
	if(ociresult($bill, "THE_COUNT") >= 1){
		$sql = "SELECT BH.BILLING_NUM
				FROM BILL_HEADER BH, BILL_DETAIL BD
				WHERE BH.BILLING_NUM = BD.BILLING_NUM
					AND BD.LABOR_TICKET_NUM = '".$ticket_num."'
					AND SERVICE_STATUS = 'PREINVOICE'";
		$billnum = ociparse($bniconn, $sql);
		ociexecute($billnum);
		ocifetch($billnum);
		return "Ticket Already on Step 2";//.ociresult($billnum, "BILLING_NUM");
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM BILL_HEADER BH, BILL_DETAIL BD
			WHERE BH.BILLING_NUM = BD.BILLING_NUM
				AND BD.LABOR_TICKET_NUM = '".$ticket_num."'
				AND SERVICE_STATUS = 'PREINVOICE'";
	$bill = ociparse($bniconn, $sql);
	ociexecute($bill);
	ocifetch($bill);
	if(ociresult($bill, "THE_COUNT") >= 1){
		$sql = "SELECT INVOICE_NUM
				FROM BILL_HEADER BH, BILL_DETAIL BD
				WHERE BH.BILLING_NUM = BD.BILLING_NUM
					AND BD.LABOR_TICKET_NUM = '".$ticket_num."'
					AND SERVICE_STATUS = 'INVOICED'";
		$billnum = ociparse($bniconn, $sql);
		ociexecute($billnum);
		ocifetch($billnum);
		return "Ticket Already on Invoice# ".ociresult($billnum, "INVOICE_NUM");
	}
*/
	// so, if we get here, the ticket can be billed.  set up the form
//	$return .= "<input type=\"radio\" name=\"ticketact[".$row_num."]\" value=\"\" checked>No Action<br>
//				<input type=\"radio\" name=\"ticketact[".$row_num."]\" value=\"Y\">Proceed with Ticket<br>
//				<input type=\"radio\" name=\"ticketact[".$row_num."]\" value=\"U\">Reject Ticket (must give reason)<br>
//				<input type=\"text\" name=\"rejectreason[".$row_num."]\" size=\"20\" maxlength=\"100\">";

	return $return;
}

function SendAnEmail(){
	// results of a run
}
