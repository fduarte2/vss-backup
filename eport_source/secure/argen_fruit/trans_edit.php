<?

	$submit = $HTTP_POST_VARS['submit'];
	$trans_num = $HTTP_POST_VARS['trans_num'];
//	echo "submit: ".$HTTP_POST_VARS['submit']."  transnum: ".$trans_num."<br>";
	if($auth_exp == "WRITE" && $submit == "Create New Transporter"){
		$sql = "SELECT ARGEN_TRANS_ID_SEQ.NEXTVAL THE_NEXT FROM DUAL";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$trans_num = ociresult($stid, "THE_NEXT");

		$sql = "INSERT INTO ARGENFRUIT_TRANSPORT
					(TRANSPORT_ID)
				VALUES
					('".$trans_num."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);
	}

	if($auth_exp == "WRITE" && $submit == "Save Transporter Info"){
		$trans_num = $HTTP_POST_VARS['trans_num'];
		$name = str_replace("'", "`", $HTTP_POST_VARS['name']);
		$name = str_replace("\\", "", $name);
		$addr = str_replace("'", "`", $HTTP_POST_VARS['addr']);
		$addr = str_replace("\\", "", $addr);
		$phone = str_replace("'", "`", $HTTP_POST_VARS['phone']);
		$phone = str_replace("\\", "", $phone);

		$sql = "UPDATE ARGENFRUIT_TRANSPORT
				SET COMPANY_NAME = '".$name."',
					ADDRESS = '".$addr."',
					PHONE = '".$phone."'
				WHERE TRANSPORT_ID = '".$trans_num."'";
//		echo $sql."<br>";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}






?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Transporter Details Page</b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="trans_select_1" action="trans_edit_index.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Transporter:</td>
		<td align="left"><select name="trans_num" onchange="document.trans_select_1.submit(this.form)"><option value="">Select an ID:</option>
<?

	$sql = "SELECT TRANSPORT_ID, TRANSPORT_ID || ' ' || COMPANY_NAME THE_NAME
			FROM ARGENFRUIT_TRANSPORT 
			ORDER BY TRANSPORT_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "TRANSPORT_ID"); ?>"<? if($trans_num == ociresult($stid, "TRANSPORT_ID")){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b><font size="2" face="Verdana" color="#330000">--- OR ---</font></b></td>
	</tr>
<form name="trans_select_2" action="trans_edit_index.php" method="post">
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New Transporter"><hr></td>
	</tr>
</form>
</table>
<?
	if($trans_num != ""){
		$sql = "SELECT COMPANY_NAME, ADDRESS, PHONE
				FROM ARGENFRUIT_TRANSPORT
				WHERE TRANSPORT_ID = '".$trans_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$name = ociresult($stid, "COMPANY_NAME");
		$addr = ociresult($stid, "ADDRESS");
		$phone = ociresult($stid, "PHONE");
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="trans_edit" action="trans_edit_index.php" method="post">
<input type="hidden" name="trans_num" value="<? echo $trans_num; ?>">
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Transporter #:</b></td>
		<td><font size="2" face="Verdana"><? echo $trans_num; ?></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Company Name:</b></td>
		<td><input type="text" name="name" size="40" maxlength="40" value="<? echo $name; ?>"><? if($name == ""){?><font size="2" face="Verdana" color="#FF0000">&nbsp;&nbsp;If this value is not entered, this Transporter ID cannot be chosen on the Load Page.</font><?}?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Address:</b></td>
		<td><input type="text" name="addr" size="50" maxlength="50" value="<? echo $addr; ?>"><? if($addr == ""){?><font size="2" face="Verdana" color="#FF0000">&nbsp;&nbsp;If this value is not entered, this Transporter ID cannot be chosen on the Load Page.</font><?}?></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Phone #:</b></td>
		<td><input type="text" name="phone" size="12" maxlength="12" value="<? echo $phone; ?>"><? if($phone == ""){?><font size="2" face="Verdana" color="#FF0000">&nbsp;&nbsp;If this value is not entered, this Transporter ID cannot be chosen on the Load Page.</font><?}?></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Transporter Info"></td>
	</tr>
</form>
</table>
<?
	}
?>