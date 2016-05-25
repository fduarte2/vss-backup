<?
/*
*	Adam Walter, Apr 2013.
*
*	A page that enteres two "dummy" values into our billing tables,
*	Which has the effect of "forwarding" the billing#s by 1 FY
***************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");  echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");  echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");  
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");  echo "<font color=\"#000000\" size=\"1\">BNI LIVE DB</font><br>";
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");  echo "<font color=\"#FF0000\" size=\"5\">BNI TEST DB</font><br>";
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Commit"){
		$next_FY = date('y') + 1;
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM RF_BILLING
				WHERE BILLING_NUM = '24".$next_FY."0000'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			echo "<font color=\"#FF0000\">FY '".$next_FY." has already been started.  No change made.</font>";
			$submit = "";
		}
	}

	if($submit == "Commit"){
		// well, it passed the above test.  Let's do it...
		$sql = "INSERT INTO BILLING
				(INVOICE_NUM, BILLING_NUM, SERVICE_CODE, SERVICE_DESCRIPTION, SERVICE_STATUS, CUSTOMER_ID, SERVICE_DATE)
				(SELECT '98".$next_FY."00000',
					MAX(BILLING_NUM + 1),
					'0000',
					'Entry made to start next Fiscal Year Bill Numbering; done by ".$user."',
					'DELETED',
					'1',
					SYSDATE
				FROM BILLING)";
//		echo $sql."<br>";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);

		$sql = "INSERT INTO RF_BILLING
			(BILLING_NUM, INVOICE_NUM, SERVICE_DESCRIPTION, SERVICE_STATUS, CUSTOMER_ID, SERVICE_DATE)
			VALUES
			('24".$next_FY."0000',
			'24".$next_FY."0000', 
			'Entry made to start next Fiscal Year Bill Numbering; done by ".$user."',
			'DELETED',
			'1',
			SYSDATE)";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		$sql = "INSERT INTO BILL_HEADER
					(CUSTOMER_ID,
					COMMODITY_CODE,
					SERVICE_CODE,
					BILLING_NUM,
					INVOICE_NUM,
					CREATED_BY,
					SERVICE_STATUS,
					ARRIVAL_NUM,
					SERVICE_DATE,
					BILLING_TYPE)
				VALUES
					('0',
					'0',
					'0',
					'30".$next_FY."00000', 
					'30".$next_FY."00000', 
					'cron',
					'DELETED',
					'0',
					SYSDATE,
					'YEAR-SET')";
//		echo $sql."<br>";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);

		echo "<font color=\"#0000FF\">FY '".$next_FY.": Update Successful.</font>";
		$submit = "";

}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">START NEXT FISCAL YEAR BILL NUMBERING
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="next_FY_set.php" method="post">
	<tr>
		<td align="left"><font size="3" face="Verdana" color="#FF0000"><b>PLEASE NOTE:  Once Bill Numbering is started for the next fiscal year (<? echo (date('Y') + 1); ?>) this step cannot be reversed.</b></font>
		<br>
		<br><font size="2" face="Verdana">Next FY Scanned Cargo Invoice Number:  <b><? echo "24".(date('y') + 1)."0001"; ?></b>
		<br>Next FY UnScanned Cargo Invoice Number:  <b><? echo "98".(date('y') + 1)."00001"; ?></b>
		<br>Next FY Billing-v2 Cargo Invoice Number:  <b><? echo "30".(date('y') + 1)."00001"; ?></b>
		<br>
		<br>Would you like to complete the <? echo date('Y'); ?> Fiscal Year and begin billng #s for FY <? echo (date('Y') + 1); ?>?</font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Commit"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");