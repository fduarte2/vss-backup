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

	if($submit == "Add Order To List"){
		$order_num = $HTTP_POST_VARS['order_num'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_OFFSITE_LOAD
					WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			$sql = "INSERT INTO WDI_OFFSITE_LOAD
						(ORDER_NUM)
					VALUES
						('".$order_num."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}

		echo "<font color=\"#0000FF\">".$order_num." Now in list of Lucca Orders.</font><br>";
	}

	if($submit == "Remove Order From List"){
		$order_num = $HTTP_POST_VARS['order_num'];

		$sql = "DELETE FROM WDI_OFFSITE_LOAD
					WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		echo "<font color=\"#00AA00\">".$order_num." Removed.</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Lucca Valid Expected Order# Entry Screen</font>
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
		<td colspan="2"><input type="submit" name="submit" value="Add Order To List"></td>
	</tr>
</form>
</table>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Unscanned Lucca Order#s Already in System:</b></font></td>
	</tr>
<?
	$i = 0;
	$sql = "SELECT ORDER_NUM 
			FROM WDI_OFFSITE_LOAD
			WHERE ORDER_NUM NOT IN
				(SELECT ORDER_NUM FROM CARGO_ACTIVITY
				WHERE CUSTOMER_ID = '3000'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				)
			ORDER BY ORDER_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<form name="cancel<? echo $i; ?>" action="walmart_lucca_order_entry.php" method="post">
	<input type="hidden" name="order_num" value="<? echo $row['ORDER_NUM']; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><input name="submit" type="submit" value="Remove Order From List"></td>
	</tr>
	</form>
<?
		$i++;
	}

	include("pow_footer.php");
?>