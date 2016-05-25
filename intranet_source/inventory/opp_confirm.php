<?
/* Adam Walter, 8/27/07
*
*  Page for inventory area; generates email, sent to Oppenheimer,
*  Requesting order verification for kiwis (as of right now).
*
*  Modified Oct 2nd to account for pallets that were on an order,
*  Voided from the order, and then placed back ont he same order
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "INVE - Oppenheimer Confirmation";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

//   $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);

	$success_message = "";

	$submit = $HTTP_POST_VARS['submit'];
	$customer = $HTTP_POST_VARS['customer'];
	$order = $HTTP_POST_VARS['order'];

	$cur_pallet = "";

	if($customer == ""){
		$customer = 1512;
	}

	if($submit == "Request Confirmation"){
		$mailsubject = $order;
//		$mailTo = "awalter@port.state.de.us";
//		$mailTo = "ithomas@port.state.de.us";
//		$mailTo = "lstewart@port.state.de.us";
		$mailTo = "ecops@oppy.com,invec@oppy.com";
		$mailheaders = "From:  PortOfWilmingtonInventory\r\n";
		$mailheaders .= "CC:  ltreut@port.state.de.us,martym@port.state.de.us,smcguire@port.state.de.us,lsanchez@port.state.de.us,bdempsey@port.state.de.us,mslowe@port.state.de.us,scorbin@port.state.de.us,mbillin@port.state.de.us,harli@oppy.com,SteRo@oppy.com\r\n";
//		$mailheaders .= "Bcc:  awalter@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us\r\n";

		$mailheaders  .= "Reply-to: ".$user."@port.state.de.us\r\n";

		$sql = "SELECT CA.ACTIVITY_NUM THE_ACT, CA.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION || ' ' || CT.VARIETY THE_VARIETY, CA.QTY_CHANGE THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.CUSTOMER_ID = '".$customer."' AND CA.ORDER_NUM = '".$order."' AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) ORDER BY CA.PALLET_ID, THE_ACT DESC";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// no order yet exists, don't email
			$success_message = "<font color=\"#FF0000\" face=\"Verdana\">No pallets have been scanned to this order; email not sent.</font>";
		} else {
			// order exists, proceed with program
			$body = "";
			$count = 0;
			do {
				if(substr($row['THE_PALLET'], -8) != substr($cur_pallet, -8)){
					$body .= $row['THE_PALLET']."\t\t".$row['THE_VARIETY']."\t\t".$row['THE_COUNT']."\r\n";
					$count++;
					$cur_pallet = $row['THE_PALLET'];
				}
			}while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$body .= ">>Total:  ".$count."\r\n";

			$body .= ">>This is a request for confirmation. Subject line is the order number and email text lists pallet ids.  Please confirm OK via reply to all.";

			mail($mailTo, $mailsubject, $body, $mailheaders);
			$success_message = "<font color=\"#000080\" face=\"Verdana\">Request sent.  Please check your Inbox for your copy.</font>";
		}
	$cur_pallet = "";
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Request Oppenheimer Confirmation</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="display" action="opp_confirm.php" method="post">
<?
	if($submit == "Request Confirmation"){
?>
	<tr>
		<td colspan="2" align="center"><? echo $success_message; ?></td>
	</tr>
<?
	}
?>
	<tr>
		<td width="50%" align="center"><font size="3" face="Verdana">Customer #:  </font><input type="text" name="customer" size="10" maxlength="6" value="<? echo $customer; ?>"></td>
		<td width="50%" align="center"><font size="3" face="Verdana">Order #:  </font><input type="text" name="order" size="15" maxlength="15" value="<? echo $order; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Display Pallets"></td>
	</tr>
</form>
<?
	if($submit == "Display Pallets"){
?>
<form name="request" action="opp_confirm.php" method="post">
<input type="hidden" name="customer" value="<? echo $customer; ?>">
<input type="hidden" name="order" value="<? echo $order; ?>">
	<tr>
		<td colspan="2">&nbsp;<hr></td>
	</tr>
<?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$customer."' AND ORDER_NUM = '".$order."' ORDER BY PALLET_ID";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 0){
?>
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana" color="#FF0000">No pallets on this order number for this customer.</font></td>
	</tr>
<?
		} else {
			$count = 0;
?>
	<tr>
		<td colspan="2">
		<table border="1" cellpadding="4" cellspacing="0">
			<tr>
				<td><font size="2" face="Verdana">Pallet(s):</font></td>
				<td><font size="2" face="Verdana">Variety:</font></td>
				<td><font size="2" face="Verdana">Case Count:</font></td>
			</tr>
<?
			$sql = "SELECT CA.ACTIVITY_NUM THE_ACT, CA.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION || ' ' || CT.VARIETY THE_VARIETY, CA.QTY_CHANGE THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.CUSTOMER_ID = '".$customer."' AND CA.ORDER_NUM = '".$order."' AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) ORDER BY CA.PALLET_ID ASC, THE_ACT DESC";
			$statement = ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if(substr($row['THE_PALLET'], -8) != substr($cur_pallet, -8)){
					$count++;
					$cur_pallet = $row['THE_PALLET'];
?>
			<tr>
				<td><font size="2" face="Verdana"><? echo $row['THE_PALLET'] ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['THE_VARIETY'] ?>&nbsp;</font></td>
				<td><font size="2" face="Verdana"><? echo $row['THE_COUNT'] ?>&nbsp;</font></td>
			</tr>
<?
				}
			}
?>
			<tr>
				<td colspan="3"><font size="2" face="Verdana"><br>Total:  <? echo $count; ?> Pallets</font></td>
			</tr>
		</table></td>
		
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Request Confirmation"></td>
	</tr>
<?
		}
?>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>