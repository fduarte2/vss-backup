<?
/*
*			Adam Walter, Oct 2008
*			This page allows OPS to add a Booking#
*			To previously EDI-received
*			Abitibi paper receipts.
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "NONTWIC";
  $area_type = "NPSA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from NPSA system");
    include("pow_footer.php");
    exit;
  }


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$short_term_data_cursor = ora_open($conn);
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$Barcode = strtoupper($HTTP_POST_VARS['Barcode']);

	if($Barcode != ""){
		if(substr($Barcode, 0, 4) != "2010" || strlen($Barcode) != 10 || !is_numeric($Barcode)){
			echo "<font color=\"#FF0000\">All Barcodes must start with 2010, be only numbers, and have a length of 10; cannot retrieve / create</font>";
			$Barcode = "";
		}
	}

	if($submit == "Save Information"){
		// save trucker info
		$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET
				ENTERED_BY = '".$user."',
				ENTRY_DATETIME = SYSDATE,
				FIRST_NAME = '".$HTTP_POST_VARS['firstname']."',
				LAST_NAME = '".$HTTP_POST_VARS['lastname']."',
				LISCENSE_NBR = '".$HTTP_POST_VARS['License']."',
				STATE_CODE = '".$HTTP_POST_VARS['state']."',
				COMPANY_NAME = '".$HTTP_POST_VARS['company']."',
				TRAILER_NBR = '".$HTTP_POST_VARS['trailer']."',
				WHOLE_DAY_PASS = '".strtoupper($HTTP_POST_VARS['daypass'])."'
				WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);

		// send chastising email if INVE took in the wrong payment
		$sql = "SELECT COUNT(*) THE_COUNT FROM NON_TWIC_TRUCKER_DETAIL
				WHERE BARCODE = '".$Barcode."'
				AND PAID_AMOUNT IS NULL";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 0 && $HTTP_POST_VARS['payment_amt'] != "" && $HTTP_POST_VARS['payment_amt'] != 30){
			$mailTO = $user."@port.state.de.us,ltreut@port.state.de.us,fvignuli@port.state.de.us\r\n";

			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
			$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,awalter@port.state.de.us\r\n";

			$mailsubject = $user." has accepted a NONTWIC payment of ".$HTTP_POST_VARS['payment_amt']." (instead of $30)\r\n";

			mail($mailTO, $mailsubject, "", $mailheaders);
		}

		// save the payment (if applicable)
		$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET
				PAYMENT_RECEIVED_BY = '".$user."',
				PAID_AMOUNT = '".$HTTP_POST_VARS['payment_amt']."'
				WHERE BARCODE = '".$Barcode."'
				AND PAID_AMOUNT IS NULL";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
	} elseif($submit == "VOID Barcode"){
		$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET
				STATUS = 'VOID'
				WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
	}




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="center">
	    <font size="3" face="Verdana" color="#0066CC">PORT OF WILMINGTON DELAWARE<br></font><font size="3" face="Verdana" color="#0066CC"><b>NON TWIC HOLDER</b><br></font><font size="5" face="Verdana" color="#0066CC"><b>DAY PASS</b><br></font><font size="3" face="Verdana" color="#0066CC"><b>($30.00 Cash Only)</b>
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_BC" method="post" action="NONTWICentry.php">
	<tr>
		<td><font face="Verdana" size="3">Barcode:  </font></td>
		<td><input type="text" name="Barcode" size="30" maxlength="30" value="<? echo $Barcode; ?>">  <input type="submit" name="submit" value="Check / Create Barcode"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($Barcode != ""){
		$sql = "SELECT FIRST_NAME, LAST_NAME, LISCENSE_NBR, STATE_CODE, COMPANY_NAME, TRAILER_NBR, PAYMENT_RECEIVED_BY, PAID_AMOUNT, WHOLE_DAY_PASS, STATUS FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		$row = array();
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "INSERT INTO NON_TWIC_TRUCKER_DETAIL (BARCODE, CURRENT_LOCATION, STATUS) VALUES ('".$Barcode."', '', 'INPORT')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			$ora_success = ora_exec($short_term_data_cursor, $sql);

//			echo "<font color=\"#FF0000\">Invalid Barcode; Barcode not scanned at front gate.  Cannot proceed until Gate verifys (scans).</font>";
//			include("pow_footer.php");
//			exit;
		} elseif ($row['STATUS'] != 'VOID'){
			$sql = "INSERT INTO NON_TWIC_SCANS (BARCODE, SCAN_TIME, USER_ID) VALUES ('".$Barcode."', SYSDATE, '".$user."')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			$ora_success = ora_exec($short_term_data_cursor, $sql);
		}

		$sql = "SELECT NVL(TO_CHAR(ENTRY_DATETIME, 'MM/DD/YYYY'), 'NONE') THE_DATE FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="save_BC" method="post" action="NONTWICentry.php">
<input type="hidden" name="Barcode" value="<? echo $Barcode; ?>">
<?
	if($row['STATUS'] == 'VOID'){
?>
	<tr>
		<td colspan="2" align="center"><font face="Verdana" size="5" color="#FF0000">---VOID---</font></td>
	</tr>
<?
	}
?>
	<tr>
		<td width="20%"><font size="2" face="Verdana">Last Updated On:  </font></td>
		<td><font size="2" face="Verdana"><b><? echo $short_term_data_row['THE_DATE']; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">First Name:  </font></td>
		<td><input name="firstname" type="text" size="20" maxlength="20" value="<? echo $row['FIRST_NAME']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Last Name:  </font></td>
		<td><input name="lastname" type="text" size="20" maxlength="20" value="<? echo $row['LAST_NAME']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Driver License #:  </font></td>
		<td><input name="License" type="text" size="30" maxlength="30" value="<? echo $row['LISCENSE_NBR']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">State:  </font></td>
		<td><input name="state" type="text" size="5" maxlength="5" value="<? echo $row['STATE_CODE']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Company:  </font></td>
		<td><input name="company" type="text" size="30" maxlength="30" value="<? echo $row['COMPANY_NAME']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">License Plate:  </font></td>
		<td><input name="trailer" type="text" size="20" maxlength="20" value="<? echo $row['TRAILER_NBR']; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Whole day Pass?:  </font></td>
		<td><input name="daypass" type="text" size="1" maxlength="1" value="<? if($row['WHOLE_DAY_PASS'] != ""){ echo $row['WHOLE_DAY_PASS']; } else { echo "Y"; } ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Payment Rec By:  </b></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PAYMENT_RECEIVED_BY']; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Payment Amt:  </b></font></td>
		<td><input name="payment_amt" type="text" size="6" maxlength="6" <? if($row['PAYMENT_RECEIVED_BY'] == ""){ ?> value="30.00" <? } else { ?> value=<? echo $row['PAID_AMOUNT']; ?> disabled <? } ?>></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Save Information"></td>
		<td align="right"><input type="submit" name="submit" value="VOID Barcode"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Date:  </font><hr></td>
		<td><font size="2" face="Verdana">Signature:  </font><hr></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana">Paid to: Diamond State Port Corp<br>1 Hausel Rd., Wilmington, DE 19801<br>(302)472-7678    EIN 51-0368431</td>
	</tr>
</form>
</table>
<?
		$sql = "SELECT PAID_AMOUNT FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."' AND PAID_AMOUNT IS NOT NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){		
			// do nothing
		} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3"><br><br></td>
	</tr>
	<tr>
		<td width="200"><font size="5" face="Verdana"><b>Paid Amount:</b></font></td>
		<td align="left"><font size="7" face="Verdana"><b>$<? echo $short_term_data_row['PAID_AMOUNT']; ?></b></font>
	</tr>
</table>
<?
		}
	}
//	include("pow_footer.php");
?>