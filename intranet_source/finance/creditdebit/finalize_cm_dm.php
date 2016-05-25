<?
/*
*	Adam Walter, Aug 2013
*
*	Finance page to finalize credit and debit memos
*****************************************************************************/

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

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}



?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Finalize Credit/Debit Memos
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="finalize_cm_dm_completed.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Memo Date:&nbsp;&nbsp;</font></td>
		<td align="left"><font size="2" face="Verdana"><input type="text" name="the_date" size="10" maxlength="10" value="<? echo date('m/d/Y'); ?>"><!--<a href="javascript:show_calendar('meh.the_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a> !--></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Finalize Memos"><br><br></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="2" face="Verdana"><b>Memo Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Memo#</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
		<td><font size="2" face="Verdana"><b># of Memo Lines</b></font></td>
		<td><font size="2" face="Verdana"><b>Service Date</b></font></td>
	</tr>
<?
	$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SUM(SERVICE_AMOUNT) THE_AMT, COUNT(*) THE_LINES, NVL(TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY'), 'SET BY MEMO DATE') THE_START, 
				MEMO_NUM, SERVICE_STATUS
			FROM BILLING
			WHERE SERVICE_STATUS LIKE '%PRE%'
				AND BILLING_TYPE IN ('CM', 'DM')
			GROUP BY CUSTOMER_ID, LR_NUM, COMMODITY_CODE, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY'), MEMO_NUM, SERVICE_STATUS
			ORDER BY MEMO_NUM, CUSTOMER_ID, LR_NUM, COMMODITY_CODE";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
?>
	<tr>
		<td colspan="7" align="center"><font size="2" face="Verdana">No Currently-Existing Pre-Memos.</font></td>
	</tr>
<?
	} else {
		do{
?>
	<tr>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "SERVICE_STATUS"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "MEMO_NUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "LR_NUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "THE_AMT"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "THE_LINES"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($short_term_data, "THE_START"); ?></font></td>
	</tr>
<?
		} while(ocifetch($short_term_data));
	}
?>
</table>

<? include("pow_footer.php"); ?>