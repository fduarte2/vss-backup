<?

	$submit = $HTTP_POST_VARS['submit'];
	$cust_code = $HTTP_POST_VARS['cust_code'];

	if($auth_exp == "WRITE" && $submit == "Create New Customer"){
		$cust_code = $HTTP_POST_VARS['cust_code'];
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM EXP_CUSTOMER
				WHERE CUSTOMER_CODE = '".$cust_code."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if($cust_code == ""){
			echo "<font color=\"#FF0000\">A Customer ID must be entered to create a new entry.</font><br>";
		} elseif(ociresult($stid, "THE_COUNT") >= 1){
			echo "<font color=\"#FF0000\">Entered Customer (".$cust_code.") could not be created as new,<br>Because it is already in the system.</font><br>";
		} else {

			$sql = "INSERT INTO EXP_CUSTOMER
						(CUSTOMER_CODE)
					VALUES
						('".$cust_code."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);
		}
	}

	if($auth_exp == "WRITE" && $submit == "Save Customer Info"){
		$cust_code = $HTTP_POST_VARS['cust_code'];
		$name = str_replace("'", "`", $HTTP_POST_VARS['name']);
		$name = str_replace("\\", "", $name);
		$addr = str_replace("'", "`", $HTTP_POST_VARS['addr']);
		$addr = str_replace("\\", "", $addr);

		$sql = "UPDATE EXP_CUSTOMER
				SET CUSTOMER_NAME = '".$name."',
					CUSTOMER_ADDRESS = '".$addr."'
				WHERE CUSTOMER_CODE = '".$cust_code."'";
//		echo $sql."<br>";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}





























?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#330000"><b>Customer Details Page</b>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_1" action="cust_edit_index.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana" color="#330000">Load:</td>
		<td align="left"><select name="cust_code" onchange="document.select_1.submit(this.form)"><option value="">Select a Customer:</option>
<?

	$sql = "SELECT CUSTOMER_CODE, CUSTOMER_NAME
			FROM EXP_CUSTOMER 
			ORDER BY CUSTOMER_CODE DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "CUSTOMER_CODE"); ?>"<? if($cust_code == ociresult($stid, "CUSTOMER_CODE")){?> selected <?}?>><? echo ociresult($stid, "CUSTOMER_CODE")."-".ociresult($stid, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b><font size="2" face="Verdana" color="#330000">--- OR ---</font></b></td>
	</tr>
<form name="loadnum_select_2" action="cust_edit_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana" color="#330000">New Customer:</font></td>
		<td><input type="text" name="cust_code" size="20" maxlength="20" value=""><input type="submit" name="submit" value="Create New Customer"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($cust_code != ""){
		$sql = "SELECT *
				FROM EXP_CUSTOMER
				WHERE CUSTOMER_CODE = '".$cust_code."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$name = ociresult($stid, "CUSTOMER_NAME");
		$addr = ociresult($stid, "CUSTOMER_ADDRESS");
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="trans_edit" action="cust_edit_index.php" method="post">
<input type="hidden" name="cust_code" value="<? echo $cust_code; ?>">
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Customer Code:</b></td>
		<td><font size="2" face="Verdana"><? echo $cust_code; ?></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Customer Name:</b></td>
		<td><input type="text" name="name" size="30" maxlength="30" value="<? echo $name; ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana" color="#330000"><b>Address:</b></td>
		<td><input type="text" name="addr" size="50" maxlength="200" value="<? echo $addr; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Customer Info"></td>
	</tr>
</form>
</table>
<?
	}
?>