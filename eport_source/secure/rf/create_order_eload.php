<?
/*
*	Adam Walter, Dec 2008.
*
*	A script to create a fixed-width file from a 
*	CARGO_ACTIVITY order number.
*
*
**************************************************************************/

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $cursor = ora_open($conn);

	$order_num = $HTTP_POST_VARS['order_num'];
	$submit = $HTTP_POST_VARS['submit'];


	if($submit != "Generate File" || $order_num == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Eloads
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp</td>
	  <td>
		<table border="0" width="100%" cellpadding="4" cellspacing="0">
		    <tr>
		      <td width="20%">&nbsp;</td>
			  <td width="80%">&nbsp;</td>
		    </tr>
		    <tr>
		      <form name="the_form" action="eloads_index.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Order #:</font></td>
		      <td align="left">
			     <input type="textbox" name="order_num" size="20"></td>
		    </tr>
			<tr>
			</tr>
			<tr>
				<td align="left">
					<input type="submit" name="submit" value="Generate File"></td>
			</tr>
			</form>
		</table>
	  </td>
	</tr>	  
</table>
<?
	} else {
		$filename = "eloadsReports/".$order_num.".txt";
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "File ".$filename." could not be opened, please contact the Port of Wilmington.\n";
			exit;
		} else {

			$sql = "SELECT PALLET_ID, QTY_CHANGE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, TO_CHAR(DATE_OF_ACTIVITY, 'HH24:MI:SS') THE_TIME FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '175' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID') ORDER BY PALLET_ID";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$order_padding = 18 - strlen($order_num);
				$pallet_padding = 35 - strlen($row['PALLET_ID']);
//				$change_padding = 33 - strlen($row['QTY_CHANGE']);
				$pre_change_padding = 3 - strlen($row['QTY_CHANGE']);
				$change_padding = 35 - 3;
				$date_padding = 2;
				$time_padding = 3;

				fwrite($handle, $order_num);
				for($i = 0; $i < $order_padding; $i++){
					fwrite($handle, " ");
				}
				fwrite($handle, $row['PALLET_ID']);
				for($i = 0; $i < $pallet_padding; $i++){
					fwrite($handle, " ");
				}

				for($i = 0; $i < $pre_change_padding; $i++){
					fwrite($handle, "0");
				}
				fwrite($handle, $row['QTY_CHANGE']);
				for($i = 0; $i < $change_padding; $i++){
					fwrite($handle, " ");
				}

				fwrite($handle, $row['THE_DATE']);
				for($i = 0; $i < $date_padding; $i++){
					fwrite($handle, " ");
				}
				fwrite($handle, $row['THE_TIME']);
				for($i = 0; $i < $time_padding; $i++){
					fwrite($handle, " ");
				}
				
				fwrite($handle, "\r\n");
			}

			fclose($handle);
		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>&nbsp;<br></td>
	</tr>
	<tr>
	<td><font size="3" face = "Verdana" color="#0066CC">File Generated.</font></td>
	</tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana">Click <a href="<? echo $filename; ?>">here</a> to view the file.<br>Or, right-click and choose "Save As" to save the file to your system.
</font>
	 </p>
      </td>
   </tr>
</table>
<?
	}
?>