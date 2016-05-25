<?
/*
*	Adam Walter, Oct 16, 2009.
*
*	This page is designed to be a yearly recap of RF cargo, based
*	On selections of type
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

/*	$FY = $HTTP_POST_VARS['FY'];
	$arv = $HTTP_POST_VARS['arv'];
	$comm = $HTTP_POST_VARS['comm'];
	$submit = $HTTP_POST_VARS['submit'];
*/
?>
<script language="javascript">

function viewreport(){
  if (document.get_data.arv.value == "Vessel"){
	document.get_data.action="Space.php";
  }else{
        document.get_data.action="Space_OTR.php";
  }
  document.get_data.submit();
}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">RF Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="Space_report.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">FY:  <select name="FY">
<?
	for($i = 2005; $i <= date("Y", mktime(0,0,0,date("m") + 6, date("d"), date("Y"))); $i++){
?>						
						<option value="<? echo $i; ?>"><? echo $i; ?></option>
<?
	}
?>
			</select></td>
		<td align="center"><font size="2" face="Verdana">Arrival Via:  <select name="arv">
						<option value="Vessel"<? if($arv == "Vessel"){ ?> selected <? } ?>>Vessel</option>
						<option value="OTR"<? if($arv == "OTR"){ ?> selected <? } ?>>OTR</option>
			</select></td>
		<td align="right"><font size="2" face="Verdana">Customer:  <select name="cust">
															<option value="all">All</option>
<?
		$sql = "SELECT DISTINCT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
		}
?>
												<select></font></td>
		<td align="right"><font size="2" face="Verdana">Commodity:  <select name="comm">
<?
		$sql = "SELECT DISTINCT COMMODITY_TYPE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IS NOT NULL ORDER BY COMMODITY_TYPE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['COMMODITY_TYPE']; ?>"<? if($row['COMMODITY_TYPE'] == $comm){ ?> selected <? } ?>><? echo $row['COMMODITY_TYPE'] ?></option>
<?
		}
?>
												<select></font></td>
	<tr>
		<td colspan="4" align="center"><input type="submit" name="submit" value="Generate Report" onclick="javascript:viewreport()"><hr></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>