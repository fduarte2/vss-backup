<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $cursor = ora_open($conn);
 $cursor2 = ora_open($conn);

 $cId = $HTTP_GET_VARS[customer_id];
 
 if ($cId <>""){
	$where = " where customer_id = $cId ";
 }else{
	$where = "";
 }
 $sql = "select h.customer_id, h.gl_code, h.service_code, h.commodity_code, h.amount, h.id
	 from gl_allocation_header h $where 
	 order by h.customer_id, h.gl_code, h.service_code, h.commodity_code";

 ora_parse($cursor, $sql);
 ora_exec($cursor);

?>
<script language="javascript">
function gl_dist(id)
{
	var cId = "<?echo $cId?>";
	var height = 500;
	var width = 500;
	var url = "/finance/gl/gl_allocation.php?id="+id+"&cId="+cId;
	popWindow = window.open(url, "popup", "height="+height+",width="+width+",scrollbars=yes");	
	popWindow.focus();
}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">GL Allocation
          </font>
	  <hr>
      </td>
   </tr>
</table>
<form name="gl">
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<input type=button value = "Add New" onclick="gl_dist('0')">
      </td>
   </tr>
   <tr>	
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<table border="1" width="95%" cellpadding="0" cellspacing="2">
	   <tr>
		<td><b>Customer</b></td>
		<td><b>Account</b></td>
		<td><b>GL Allocation</b></td>
		<td><b>Amount</b></td>
	   </tr>
<?	while (ora_fetch($cursor)) 
	{
		$cust = ora_getcolumn($cursor, 0);
		$gl = ora_getcolumn($cursor, 1);
                $service = ora_getcolumn($cursor, 2);
                $comm = ora_getcolumn($cursor, 3);
               // $asset = ora_getcolumn($cursor, 4);
                $tot_amt = ora_getcolumn($cursor, 4);
                $id = ora_getcolumn($cursor, 5);

		$acct = $gl."-".$service."-".$comm;
?>
	   <tr>
		<td><?echo $cust?></td>
                <td><a href="javascript:gl_dist(<?echo $id?>)"><?echo $acct?></a></td>
                <td>&nbsp;</td>
                <td><?echo $tot_amt?></td>
	   </tr>
<?
	 	$sql = "select gl_code, service_code, commodity_code,  amount
			from gl_allocation_body
			where header_id = $id
			order by gl_code, service_code, commodity_code";
		ora_parse($cursor2, $sql);
	 	ora_exec($cursor2);

		while(ora_fetch($cursor2))
		{
			$gl = ora_getcolumn($cursor2, 0);
                	$service = ora_getcolumn($cursor2, 1);
               	 	$comm = ora_getcolumn($cursor2, 2);
                	//$asset = ora_getcolumn($cursor2, 3);
               		$amt = ora_getcolumn($cursor2, 3);

			$acct = $gl."-".$service."-".$comm;
?>
           <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?echo $acct?></td>
                <td><?echo $amt?></td>
           </tr>
<?
		}
	}
?>
	<input type=hidden name=customer_id value="<?echo $cId?>">
	</table>
      </td>
   </tr>	
</table>
</form> 
<script language="javascript">
<? include("pow_footer.php"); 
ora_close($cursor);
ora_logoff($conn);

?>
