<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Walmart";
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
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$order_num = strtoupper($HTTP_POST_VARS['order_num']);
	$dest = $HTTP_POST_VARS['dest'];

	if(($order_num == "" && $dest == "")){
		$submit = "";
	}
	
	if(($order_num == "" xor $dest == "")){
		echo "<font color=\"#FF0000\">Both Order# and Destination must be chosen.</font>";
		$submit = "";
		$order_num = "";
		$dest = "";
	}

	if($submit == "Add Order To List"){

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_OFFSITE_LOAD
					WHERE ORDER_NUM = '".$order_num."'
					AND OFFSITE_DESTINATION = '".$dest."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			$sql = "INSERT INTO WDI_OFFSITE_LOAD
						(ORDER_NUM,
						OFFSITE_DESTINATION)
					VALUES
						('".$order_num."',
						'".$dest."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}

		echo "<font color=\"#0000FF\">".$order_num." Now in list of ".$dest." Orders.</font><br>";
	}

	if($submit == "Remove Order From List"){

		$sql = "DELETE FROM WDI_OFFSITE_LOAD
					WHERE ORDER_NUM = '".$order_num."'
					AND OFFSITE_DESTINATION = '".$dest."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		echo "<font color=\"#00AA00\">".$order_num." -- ".$dest." Removed.</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Crossdock Valid Expected Order# Entry Screen</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="add_order" action="walmart_lucca_order_entry.php" method="post">
	<tr>
		<td>Enter Order #:</td>
		<td><input type="text" name="order_num" size="12" maxlength="12"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="radio" name="dest" value="LUCCA">&nbsp;LUCCA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="dest" value="HOLT">&nbsp;HOLT</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add Order To List"></td>
	</tr>
</form>
</table>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Unscanned Crossdock Order#s Already in System:</b></font></td>
	</tr>
<?
	$i = 0;
	$sql = "SELECT ORDER_NUM, OFFSITE_DESTINATION 
			FROM WDI_OFFSITE_LOAD
			WHERE ORDER_NUM NOT IN
				(SELECT ORDER_NUM FROM CARGO_ACTIVITY
				WHERE CUSTOMER_ID = '3000'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				)
			ORDER BY OFFSITE_DESTINATION, ORDER_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<form name="cancel<? echo $i; ?>" action="walmart_lucca_order_entry.php" method="post">
	<input type="hidden" name="order_num" value="<? echo $row['ORDER_NUM']; ?>">
	<input type="hidden" name="dest" value="<? echo $row['OFFSITE_DESTINATION']; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['OFFSITE_DESTINATION']; ?></font></td>
		<td><input name="submit" type="submit" value="Remove Order From List"></td>
	</tr>
	</form>
<?
		$i++;
	}

	include("pow_footer.php");
?>