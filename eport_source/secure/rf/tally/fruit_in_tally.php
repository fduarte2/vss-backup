<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Inbound Tally
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="fruit_in_print.php" method="post">
	<tr>
		<td width="10%" align="left">Customer:</td>
		<td><select name="cust">
<?
	$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$user_cust_num."'
		ORDER BY CUSTOMER_ID";
//	echo $sql."<br>";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	while(ocifetch($Short_term_data)){
?>
				<option value="<? echo ociresult($Short_term_data, "CUSTOMER_ID"); ?>"<? if($view_cust == ociresult($Short_term_data, "CUSTOMER_ID")){?> selected <?}?>><? echo ociresult($Short_term_data, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td width="10%" align="left">Order#:</td>
		<td><input type="text" name="order" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Tally"></td>
	</tr>
</form>
</table>