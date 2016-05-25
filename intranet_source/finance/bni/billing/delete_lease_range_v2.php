<?
/*
*		Adam Walter, June 2014.
*
*		Page for Finance to "delete" bills using the new (as of 6/2014) system
*********************************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}
/*
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}
*/
  
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


	$submit = $HTTP_POST_VARS['submit'];
	$start_prebill = $HTTP_POST_VARS['start_prebill'];
	$end_prebill = $HTTP_POST_VARS['end_prebill'];

	if($submit == "Delete Lease Prebills"){
		$sql = "UPDATE BILL_HEADER
				SET SERVICE_STATUS = 'DELETED'
				WHERE SERVICE_STATUS = 'PREINVOICE'
					AND BILLING_TYPE = 'LEASE'
					AND BILLING_NUM BETWEEN '".$start_prebill."' AND '".$end_prebill."'";
		$deleted_bills = ociparse($bniconn, $sql);
		ociexecute($deleted_bills);

		echo "<font color=\"#0000FF\">Lease Preinvoices Deleted.</font><br>";
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Delete Lease Prebills by Range<br></font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="delete_lease_range_v2.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Starting Prebill#:  </font></td>
		<td><input type="text" name="start_prebill" size="20" maxlength="20" value="<? echo $start_prebill; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Ending Prebill#:  </font></td>
		<td><input type="text" name="end_prebill" size="20" maxlength="20" value="<? echo $end_prebill; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Prebills"></td>
	</tr>
</form>
<? 
	if ($submit == "Retrieve Prebills" && $start_prebill != "" && $end_prebill != ""){
?>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<form name="select" action="delete_lease_range_v2.php" method="post">
<input type="hidden" name="start_prebill" value="<? echo $start_prebill; ?>">
<input type="hidden" name="end_prebill" value="<? echo $end_prebill; ?>">
<?
		$sql = "SELECT COUNT(DISTINCT BH.BILLING_NUM) THE_BILLS, SUM(SERVICE_AMOUNT) THE_AMOUNT
				FROM BILL_HEADER BH, BILL_DETAIL BD
				WHERE BH.BILLING_NUM = BD.BILLING_NUM
					AND BH.BILLING_TYPE = 'LEASE'
					AND SERVICE_STATUS = 'PREINVOICE'
					AND BH.BILLING_NUM BETWEEN '".$start_prebill."' AND '".$end_prebill."'";
		$amt_data = ociparse($bniconn, $sql);
		ociexecute($amt_data);
		ocifetch($amt_data);
		$num_bills = ociresult($amt_data, "THE_BILLS");
		$total_amt = ociresult($amt_data, "THE_AMOUNT");
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">(Currently <? echo $num_bills; ?> Lease Preinvoices at $<? echo number_format($total_amt, 2); ?> between billing #s <? echo $start_prebill; ?> and <? echo $end_prebill; ?> )</font><br><br></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Delete Lease Prebills"></td>
	</tr>
</form>
<?
	}
?>
</table>

<?
	include("pow_footer.php");