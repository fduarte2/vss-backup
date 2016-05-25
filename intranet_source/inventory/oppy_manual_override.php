<?
/*
*	May, 2009.
*
*	Manual override for Oppy EDI request.  Maybe they weren't home.
*
************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Oppenheim Truck Notification";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Oppy system");
    include("pow_footer.php");
    exit;
  }

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$load = $HTTP_POST_VARS['load'];

	if($submit == "Submit Override" && $load != ""){
		$sql = "SELECT TO_CHAR(VALID_REC_ON, 'MM/DD/YYYY HH24:MI') THE_DATE FROM OPPY_TRUCK_INFO WHERE LOAD_NUM = '".$load."' ORDER BY TO_CHAR(VALID_REC_ON, 'MM/DD/YYYY HH24:MI') NULLS FIRST";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">No Attempt has been made to contact Oppenheimer via EDI yet for this load.  Override Cancelled.</font>";
		} elseif ($row['THE_DATE'] != ""){
			echo "<font color=\"#FF0000\">EDI confirmation was already received for this order on ".$short_term_row['THE_DATE'].".  Override Cancelled.</font>";
		} else {
			// ok, valid to be overridden.
			$order_list = "";
			$sql = "SELECT ORDER_NUM FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."' AND VERIFY_REC_BY IS NULL";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$order_list .= $row['ORDER_NUM']."\r\n";
			}

//			$mailTo = "awalter@port.state.de.us\r\n";
			$mailTo = "ltreut@port.state.de.us,draczkowski@port.state.de.us,bdempsey@port.state.de.us,schapman@port.state.de.us,martym@port.state.de.us";
			$mailTo .= ",".$user."@port.state.de.us\r\n";

			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
			$mailheaders .= "Cc: " . "lstewart@port.state.de.us,awalter@port.state.de.us\r\n";

			$mailsubject = "OPPENHEIMER:  LOAD ".$load." MANUAL OVERRIDE ENACTED";

			$body = "User ".$user." has Manually Overridden Load Number ".$load.".  You may now print the BoL for all previously unconfirmed orders on this load:\r\n\r\n";
			$body .= $order_list;

			$sql = "UPDATE OPPY_PER_ORDER SET VERIFY_REC_BY = '".$user."', VERIFY_RECEIVED = SYSDATE WHERE LOAD_NUM = '".$load."' AND VERIFY_REC_BY IS NULL";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			mail($mailTo, $mailsubject, $body, $mailheaders);

			echo "<font color=\"#0000FF\">Manual Override successful.  Email notification sent.</font>";
		}
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Oppenheimer Manual Confirmation Override</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="the_form" action="oppy_manual_override.php" method="post">
<input name="load" value="<? echo $load; ?>" type="hidden">
<input name="order_p" value="<? echo $order; ?>" type="hidden">
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana"><b>*** IMPORTANT: ***<br>This screen only to be used AFTER submitting a request,<br> but receiving either no response, or a "verified but some pallets have problems" one,<br>That needs to be shipped, regardless of pallet problems.</b></font></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Load #&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><select name="load"><option value="">Select a Load:</option>
<?
	$sql = "SELECT DISTINCT LOAD_NUM FROM OPPY_PER_ORDER WHERE VERIFY_REC_BY IS NULL ORDER BY LOAD_NUM"; 
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LOAD_NUM']; ?>"><? echo $row['LOAD_NUM']; ?></option>
<?
	}
?>		
		
		</select></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Submit Override"></td>
		<td>&nbsp;</td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>