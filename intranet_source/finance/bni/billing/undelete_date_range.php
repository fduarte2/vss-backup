<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Delete Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");  echo "<font color=\"#000000\" size=\"1\">BNI LIVE DB</font><br>";
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");  echo "<font color=\"#FF0000\" size=\"5\">BNI TEST DB</font><br>";
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}
	
	$submit = $HTTP_POST_VARS['submit'];
	$set_start_date = $HTTP_POST_VARS['start_date'];
	$set_end_date = $HTTP_POST_VARS['end_date'];

	if($submit == "Process Checkboxes"){
		$bill_set = $HTTP_POST_VARS['bill_set'];
		$pass_set = $HTTP_POST_VARS['pass_set'];

		$updates = 0;
		$i = 0;

		while($bill_set[$i] != ""){
			if($pass_set[$i] == "save"){

				$sql = "UPDATE BILLING
						SET SERVICE_STATUS = 'PREINVOICE'
						WHERE BILLING_NUM = '".$bill_set[$i]."'";
				$upd_data = ociparse($bniconn, $sql);
				ociexecute($upd_data);

				$updates++;
			}
			$i++;
		}

		echo "<font color=\"0000FF\">Bills Updated.  ".$updates." bill(s) marked PREINVOICE</font></br>";
	}

?>

<!-- Delete Prebills - Main page -->
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Un-Delete (non-storage) V1 Pre-Invoices by Date</font>
         <hr><? //include("../bni_links.php"); ?></td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="undelete_date_range.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date (From):  </font></td>
		<td><input type="text" name="start_date" size="10" maxlength="10" value="<? echo $set_start_date; ?>"><a href="javascript:show_calendar('meh.start_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date (To):  </font></td>
		<td><input type="text" name="end_date" size="10" maxlength="10" value="<? echo $set_end_date; ?>"><a href="javascript:show_calendar('meh.end_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $set_start_date != "" && $set_end_date != ""){
?>
<form name="save" action="undelete_date_range.php" method="post">
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Process Checkboxes"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><b>Billing#</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<!-- <td><font size="2" face="Verdana"><b>QTY</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Rate</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
	</tr>
<?
		$rownum = -1;

		$sql = "select * 
				from billing 
				where service_date >= TO_DATE('".$set_start_date."', 'MM/DD/YYYY') 
					and service_date <= TO_DATE('".$set_end_date."', 'MM/DD/YYYY') 
					and service_status = 'DELETED' 
					and billing_type != 'STORAGE'
				order by lr_num, billing_type, customer_id";
		$bills = ociparse($bniconn, $sql);
		ociexecute($bills);
		while(ocifetch($bills)){
			$rownum++;

			$desc = "&nbsp;";
			if (ociresult($bills, "SERVICE_DESCRIPTION") != "") {
				$desc = ociresult($bills, "SERVICE_DESCRIPTION");
			}
			if (trim(ociresult($bills, "BILLING_TYPE")) == "LABOR") {
				if (ociresult($bills, "LABOR_TICKET_NUM") != "") {
					$desc .= " (LABOR TICKET #: " . ociresult($bills, "LABOR_TICKET_NUM") . ")";
				} else {
					$desc .= " (LABOR TICKET #: NONE)";
				}
			}

			$service_date = date('m/d/y', strtotime(ociresult($bills, "SERVICE_START")));
?>
	<tr>
		<td><input type="hidden" name="bill_set[<? echo $rownum; ?>]" value="<? echo ociresult($bills, "BILLING_NUM"); ?>"><input type="checkbox" name="pass_set[<? echo $rownum; ?>]" value="save"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "BILLING_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $desc; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "LR_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "BILLING_TYPE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "CUSTOMER_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $service_date; ?></font></td>
		<!-- <td><font size="2" face="Verdana"><? echo ociresult($bills, "SERVICE_QTY")." ".ociresult($bills, "SERVICE_UNIT"); ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "SERVICE_RATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($bills, "SERVICE_AMOUNT"); ?></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="9" align="center"><input type="submit" name="submit" value="Process Checkboxes"></td>
	</tr>
</table>
</form>
<?
	}
	include("pow_footer.php");
