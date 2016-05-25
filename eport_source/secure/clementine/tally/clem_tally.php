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

	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
		$extra_sql = "AND CUSTOMER_ID = '".$eport_customer_id."'";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementine Outbound Tally
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="clem_print.php" method="post">
	<tr>
		<td width="10%" align="left">Customer:</td>
		<td><select name="cust">
<?
	$sql = "SELECT * FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_LOGIN WHERE USER_TYPE = 'CLEMENTINE' AND CUSTOMER_ID != 0)
			".$extra_sql."
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
		<td width="10%" align="left">Arrival #:</td>
		<td><input type="text" name="vessel" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Tally"></td>
	</tr>
</form>
</table>