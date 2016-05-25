<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail("awalter@port.state.de.us", "barnett tally 'sploded", "barnett access failure", $mailheaders);
    exit;
  }

  $select_cursor1 = ora_open($conn);         
  $select_cursor2 = ora_open($conn);         
  $Short_Term_Cursor = ora_open($conn);

  $body = "";

	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
//	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));

//	$mailTo = "awalter@port.state.de.us";
	$mailTo = "TPenot@nortonlilly.com,Hector.Garcia@Dole.com";

//	$mailheaders = "From: " . "NoReplies@POWAutoTally.com\r\n";
	$mailheaders = "From: " . "PoWNoReplies@POWAutoTally.com\r\n";
//	$mailheaders .= "Cc: " . "Ithomas@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "martym@port.state.de.us,ltreut@port.state.de.us\r\n";
//	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,hdadmin@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$sql = "SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING 
			WHERE TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date."' 
			AND REMARK = 'BOOKINGSYSTEM'
			ORDER BY ARRIVAL_NUM";
	ora_parse($select_cursor1, $sql);
	ora_exec($select_cursor1);
	if(!ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		mail($mailTO, $mailsubject, "No Inbound Arrivals Today", $mailHeaders);
	} else {
		do {
			$total_rolls = 0;
			$mailsubject = "Barnett Inbound Tally ".$date." from Arrival: ".$select_row1['ARRIVAL_NUM']."\r\n";
			$xls_file = "<TABLE border=0 CELLSPACING=1>";
	
			$xls_file .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=8>PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Inbound)</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '314'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$xls_file .= "<tr><td></td><td colspan=10>".$short_term_row['CUSTOMER_NAME']."</td><td></td></tr>";
/*
			$sql = "SELECT NVL(CONTAINER_ID, 'NOT SPECIFIED') THE_CONT FROM BOOKING_ORDERS WHERE ORDER_NUM = '".$select_row1['ORDER_NUM']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$container_id = $short_term_row['THE_CONT'];
*/
			$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH:MI AM') START_TIME, 
					TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY HH:MI AM') END_TIME
					FROM CARGO_TRACKING 
					WHERE ARRIVAL_NUM = '".$select_row1['ARRIVAL_NUM']."'
					AND REMARK = 'BOOKINGSYSTEM'
					AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$StartTime = $short_term_row['START_TIME'];
			$EndTime = $short_term_row['END_TIME'];

			$xls_file .= "<tr><td>&nbsp;</td><td colspan=6>Printed On ".$date."</td><td colspan=4>START TIME: ".$StartTime."</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			$xls_file .= "<tr><td>&nbsp;</td><td colspan=6>Arrival ".$select_row1['ARRIVAL_NUM']."</td><td colspan=4>END TIME: ".$EndTime."</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			$xls_file .= "<tr><td colspan=12>&nbsp;</td></tr>";
			$xls_file .= "<tr><td><b>BARCODE</b></td>
							<td><b>QTY</b></td>
							<td><b>Size(cm)</b></td>
							<td><b>Dia(cm)</b></td>
							<td><b>PRODUCT</b></td>
							<td><b>BOOKING #</b></td>
							<td><b>ORDER</b></td>
							<td><b>Linear Meas</b></td>
							<td><b>Rec. Manifest</b></td>
							<td><b>LB</b></td>
							<td><b>KG</b></td>
							<td><b>Damage</b></td></tr>";

			$sql = "SELECT CT.PALLET_ID, Round(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH,
						ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA, NVL(BPGC.SSCC_GRADE_CODE, 'NONE') THE_CODE, BAD.ORDER_NUM,
						BAD.LENGTH, BAD.LENGTH_MEAS, BAD.BOL, DECODE(CT.QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN,
						NVL(BOOKING_NUM, '--NONE--') THE_BOOK,
						ROUND(CT.WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB, 
						ROUND(CT.WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG
					FROM BOOKING_ADDITIONAL_DATA BAD, CARGO_TRACKING CT, BOOKING_PAPER_GRADE_CODE BPGC, 
						UNIT_CONVERSION_FROM_BNI UC1, 
						UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4
					WHERE CT.ARRIVAL_NUM = '".$select_row1['ARRIVAL_NUM']."' 
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'
					AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'
					AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') = '".$date."'
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
					ORDER BY CT.PALLET_ID, BAD.ORDER_NUM";
			ora_parse($select_cursor2, $sql);
			ora_exec($select_cursor2);

			while(ora_fetch_into($select_cursor2, $select_row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$xls_file .= "<tr><td>".$select_row2['PALLET_ID']."</td>
								<td>1</td>
								<td>".$select_row2['THE_WIDTH']."</td>
								<td>".$select_row2['THE_DIA']."</td>
								<td>".$select_row2['THE_CODE']."</td>
								<td>".$select_row2['THE_BOOK']."</td>
								<td>".$select_row2['ORDER_NUM']."</td>
								<td>".$select_row2['LENGTH']." ".$select_row2['LENGTH_MEAS']."</td>
								<td>".$select_row2['BOL']."</td>
								<td>".$select_row2['WEIGHT_LB']."</td>
								<td>".$select_row2['WEIGHT_KG']."</td>
								<td>".$select_row2['DAMAGE_YN']."</td></tr>";
				$total_rolls++;
			}
	
			$xls_file .= "<tr><td>Total:</td><td>".$total_rolls."</td><td colspan=\"10\">&nbsp;</td></tr>";
			$xls_file .= "</table>";
			$xls_attach=chunk_split(base64_encode($xls_file));
			$body = " \r\n\r\n";


			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"BarnettTallyIn".date('mdy').$select_row1['ARRIVAL_NUM'].".xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$xls_attach;
			$Content.="\r\n";
			$Content.="--MIME_BOUNDRY--\n";

			mail($mailTo, $mailsubject, $Content, $mailheaders);
		
		} while(ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

	}
?>