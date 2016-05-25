<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);


  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Customer / Season Invoice Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp</td>
	  <td>
		<table border="0" width="100%" cellpadding="4" cellspacing="0">
		    <tr>
		      <td width="20%">&nbsp;</td>
			  <td width="80%">&nbsp;</td>
		    </tr>
		    <tr>
		      <form action="rf_storage_billed.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Customer number:</font></td>
		      <td align="left">
			     <select name="customer"><option value="ALL">ALL</option>
<?
					$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID >= 100 AND CUSTOMER_ID IN (SELECT DISTINCT CUSTOMER_ID FROM RF_BILLING WHERE INVOICE_NUM LIKE '2408%') ORDER BY CUSTOMER_ID";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"><? echo $row['CUSTOMER_NAME'] ?></option>
<?
					}
?>
					</select></td>
		    </tr>
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Fiscal Year:</font></td>
		      <td align="left">
			     <select name="year">
<?
					$sql = "SELECT DISTINCT TO_CHAR(ADD_MONTHS(SERVICE_DATE, 1), 'YYYY') THE_YEAR FROM RF_BILLING WHERE SERVICE_STATUS = 'INVOICED' ORDER BY TO_CHAR(ADD_MONTHS(SERVICE_DATE, 1), 'YYYY') DESC";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['THE_YEAR']; ?>"><? echo $row['THE_YEAR'] ?></option>
<?
					}
?>
					</select></td>
			<tr>
			</tr>
			<tr>
				<td align="left">
					<input type="submit" value="View Bills"></td>
			</tr>
			</form>
		</table>
			  
	 
</table>

<? include("pow_footer.php"); ?>
