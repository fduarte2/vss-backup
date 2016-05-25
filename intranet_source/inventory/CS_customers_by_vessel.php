<?
/* Adam Walter, Feb 2007
*
*	This page is for Ben, used to list customers by vessel
*	with Cold Storage transfers
*********************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Transfer to Cold Storage";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

   // Connect to RF
   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_conn));
      exit;
   }
   $cursor = ora_open($ora_conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>
			<font size="5" face="Verdana" color="#0066CC">Transfer to Cold Storage, Customer List
			<hr>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="input" action="CS_customers_by_vessel.php" method="post">
	<tr>
		<td align="center"><font size="3" face="Verdana">Vessel:<BR></font><select name="vessel"><option value="">Select Vessel</option>
<?
		$sql = "SELECT LR_NUM THE_LR, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) IN (SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL) ORDER BY THE_VESSEL DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['THE_LR']; ?>"<? if($row['THE_LR'] == $vessel) {?> selected <? } ?>><? echo $row['THE_VESSEL']; ?></option>
<?
		}
?>				
			</select></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Retrieve"><BR><HR></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, CUSTOMER_NAME FROM CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP WHERE ARRIVAL_NUM = '".$vessel."' AND CA.CUSTOMER_ID = CP.CUSTOMER_ID AND SERVICE_CODE = '16' GROUP BY CUSTOMER_NAME ORDER BY CUSTOMER_NAME";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td>
			<font size="3" face="Verdana" color="#CC0000">No Cold Storage transfers for this vessel.</font>
		</td>
	</tr>
</table>
<?
		} else {
?>
<table border="1" width="50%" cellpadding="4" cellspacing="0" align="center">
	<tr>
		<td width="80%" align="left"><font size="2" face="Verdana">Customer</font></td>
		<td align="right"><font size="2" face="Verdana"># Transfers</font></td>
	</tr>
<?
			do {
?>
	<tr>
		<td width="80%" align="left"><font size="2" face="Verdana"><? echo $row['CUSTOMER_NAME']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo $row['THE_COUNT']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}

include("pow_footer.php"); ?>