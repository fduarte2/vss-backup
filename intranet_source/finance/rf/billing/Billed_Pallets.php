<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF Reports";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  $Start_Date = $HTTP_POST_VARS[start_date];
  $today = date('m/d/Y');
?>


<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Distinct Pallets in RF Storage
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana"><b>Please select start date.</b></font>
	  </td>
	</tr>
	<tr>
	<td>
            <form action="Billed_Pallets.php" method="post" name="scan">
	       Start Date: <input type="textbox" name="start_date" size=10 value=""><a href="javascript:show_calendar('scan.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0>
	 </td>
	 </tr>
	 <tr>
	 <td>
	 <input type="submit" value="Submit">
	 </td>
	 </tr>
	 <tr>
	 </tr>
	 <tr>
	 </tr>
	 <tr>
	 </tr>

<?
	if($Start_Date != ""){
		$conn = ora_logon("SAG_Owner@RF", "OWNER");
		if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
		}
  
		$cursor = ora_open($conn);
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) FROM RF_BILLING_DETAIL WHERE SERVICE_DATE >= to_date('".$Start_Date."', 'MM/DD/YYYY')";
		// echo $sql;
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch($cursor);
		$output = ora_getcolumn($cursor, 0);

?>
	<tr>

	<font size="2" face="Verdana">Billed Pallet Count from <? echo $Start_Date; ?> to <? echo $today; ?> Is:</font>

	</tr>
	<font size="3" face="Verdana"><b><? echo $output; ?></b></font>

	</tr>
	<br>
	<br>

<?
	}
?>

<? include("pow_footer.php"); ?>
