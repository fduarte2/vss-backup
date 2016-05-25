<?php

  //connect to BNI
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

  $cust_id = $HTTP_POST_VARS[cust_id];
  if ($cust_id == "") $cust_id = 0;

  $sql="select max(amount) from 
	(select invoice_num, sum(service_amount) as amount from billing 
	where customer_id = $cust_id and service_status = 'INVOICED' 
	group by invoice_num ) a";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

?>
<html>
<body>
<P>Retrieve largest invoice amount
<table>
<form action = "" method=post>
<tr>
	<td>Customer ID:<input type=textbox name=cust_id value="<?echo $cust_id?>"
	</td>
	<td><input type=submit name=retrieve value = "Retrieve">
	</td>	
</tr>

<?
  while(ora_fetch($cursor)){
	$invoice = ora_getcolumn($cursor, 0);
?>
<tr>
	<td>Invoice amount:<?echo $invoice?></td>
</tr>
<?
  }

?>
</table>
</form>
</body>
</html>