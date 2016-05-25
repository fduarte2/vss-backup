<?
/*
*		Adam Walter, June 2014.
*
*		Page for Finance to "activate" bills using the new (as of 6/2014) system
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Print Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Generate Prebill"){
		if($HTTP_POST_VARS['billing_type'] == "TRANSFER"){
			include("./transfer_rf_prebills.php");
//			include("../../crons/transfer_rf_prebills.php");
			echo "<font color=\"#0000FF\">".$HTTP_POST_VARS['billing_type']." bills generated.  Please check the preinvoice print screen.<br><br></font>";
		}
		if($HTTP_POST_VARS['billing_type'] == "LEASE" || ($HTTP_POST_VARS['billing_type'] == "LEASE_SINGLE" && $HTTP_POST_VARS['lease_id'] != "")){
			include("./lease_prebills.php");
//			include("../../crons/lease_prebills.php");
			echo "<font color=\"#0000FF\">".$HTTP_POST_VARS['billing_type']." bills generated.  Please check the preinvoice print screen.<br><br></font>";
		}
		if($HTTP_POST_VARS['billing_type'] == "LABOR"){
			include("./labor_make_preinvoice_v2.php");
//			include("../../crons/labor_make_preinvoice_v2.php");
			echo "<font color=\"#0000FF\">".$HTTP_POST_VARS['billing_type']." bills generated.  Please check the preinvoice print screen.<br><br></font>";
			if($reply_msg != ""){ // THIS IS SET IN THE CALLED SCRIPT
				echo "<font color=\"#FF0000\">The following Labor Tickets were not billed, due to the reason given:<br>".$reply_msg."<br><br>
						Please have the Supervisors correct the Labor Tickets if you need these bills.<br></font>";
			}
		}
//		echo "<font color=\"#0000FF\"><a href=\"print_prebills.php\">Click Here</a> To go to the Print Prebills page.<br><br></font>";
		echo "<font color=\"#0000FF\"><a href=\"./invoice_testing/print_prebill.php\">Click Here</a> To go to the Print Prebills page.<br><br></font>";
	}

	$cust = $HTTP_POST_VARS['cust'];
	$vessel = $HTTP_POST_VARS['vessel'];



?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Generate Prebills V2
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="forcerun_preinvoices.php" method="post">
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">For all "optional" fields, leaving the entry blank will pick ALL applicable values.</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Vessel (optional):  </font></td>
		<td><input type="text" name="vessel" size="20" maxlength="20" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer (optional):  </font></td>
		<td><input type="text" name="cust" size="20" maxlength="20" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>Choose Bill Type:</b></font></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
		<table bgcolor="#f0f0f0" width="95%" border="0" cellpadding="4" cellspacing="0">
			<tr>
				<td width="2%">&nbsp;</td>
				<td width="6%"><input type="radio" name="billing_type" value="TRANSFER"></td>
				<td width="50%"><font size="2" face="Verdana">Transfer (Scanned)&nbsp;&nbsp;(<? PrePopXferCount($rfconn); ?> Orders Pending)</font></td>
				<td width="6%"><input type="radio" name="billing_type" value="LEASE"></td>
				<td><font size="2" face="Verdana">Lease (All Active)</font></td>
			</tr>
			<tr>
				<td width="2%">&nbsp;</td>
				<td width="6%">&nbsp;<!--<input type="radio" name="billing_type" value="LABOR">!--></td>
				<td width="50%">&nbsp;<!--<font size="2" face="Verdana">Labor</font>!--></td>
				<td width="6%"><input type="radio" name="billing_type" value="LEASE_SINGLE"></td>
				<td><font size="2" face="Verdana">Lease ID#:&nbsp;<input type="text" name="lease_id" size="4" maxlength="4"></td>
			</tr>
<!--			<tr>
				<td width="2%">&nbsp;</td>
				<td width="6%"><input type="radio" name="billing_type" value="LABOR"></td>
				<td width="50%"><font size="2" face="Verdana">Labor</font></td>
				<td width="6%">&nbsp;</td>
				<td>&nbsp;</td>
			</tr> !-->
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Prebill"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");





function PrePopXferCount($rfconn){
	$sql = "SELECT COUNT(DISTINCT ORDER_NUM) THE_ORDS
			FROM CARGO_ACTIVITY
			WHERE (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND SERVICE_CODE = '11'
				AND ACTIVITY_NUM != '1'
				AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')";
	$orders = ociparse($rfconn, $sql);
	ociexecute($orders);
	ocifetch($orders);

	echo ociresult($orders, "THE_ORDS");
}
?>