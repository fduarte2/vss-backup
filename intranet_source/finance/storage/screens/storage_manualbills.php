<?
/*
*	Adam Walter, Oct-Nov 2009
*
*	This is the script to determine the Storage Bills of Cargo
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2009)
*****************************************************************************/

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  ora_commitoff($bni_conn); // we dont want this going through unless it totally works

  $bni_cursor = ora_open($bni_conn);
  $rate_cursor = ora_open($bni_conn);
  $update_bni_cursor = ora_open($bni_conn);
  $Short_Term_Cursor = ora_open($bni_conn);


  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$submit = $HTTP_POST_VARS['submit'];
	$date_start = $HTTP_POST_VARS['date_start'];
	$date_end = $HTTP_POST_VARS['date_end'];
 


	if($submit != ""){
		if (!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $date_start) || !ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $date_end)) {
			echo "<font color=\"#FF0000\">Dates must be in MM/DD/YYYY Format</font>";
		} elseif ($date_start == "" || $date_end == ""){
			echo "<font color=\"#FF0000\">Both dates must be entered</font>";
		} elseif(Which_Date_Is_First($date_start, date('m/d/Y')) >= 0 || Which_Date_Is_First($date_end, date('m/d/Y')) >= 0) {
			echo "<font color=\"#FF0000\">Both dates must be before today's date.</font>";
		} else {
			// ok, dates check out.  time for some fun...
			// we will essentially run the autobill program for each date between the range, with the only difference being
			// that I need to recalculate "qty_in_house" as of each day, rather than just take the DB value.

			// NOTE:  this will commit after EACH DAY, so if it breaks mid-run, all bills up to the day of the break are taken.
			// This is necessary in case cargo gets billed more than once during the chosen cycle.
			$temp_start = explode("/", $date_start);
			$time_start = mktime(0,0,0,$temp_start[0],$temp_start[1],$temp_start[2]);
//			echo $time_start."<br>";

			$temp_end = explode("/", $date_end);
			$time_end = mktime(0,0,0,$temp_end[0],$temp_end[1],$temp_end[2]);
//			echo $time_end."<br>";

			$current_time = $time_start;

			while($current_time <= $time_end){
				$this_run_date = date('m/d/Y', $current_time);
//				echo $this_run_date."<br>";
				$lot_num = "default";
//				$email_bni_bad_details = "<br>";
				$email_bni_stopbill_details = "<br>";
				$email_bni_okbill_notify_details = "<br>";
				$mail_body_summary = "\r\n";


				// AND CUSTOMERID != 312 is because abitibi is billed from RF right now.  HOPEFULLY, that will change.
				$sql = "SELECT CM.COMMODITY_CODE THE_COMM, CM.LR_NUM THE_VES, CT.OWNER_ID THE_CUST, ORIGINAL_CONTAINER_NUM, WAREHOUSE_LOCATION, CONTAINER_NUM, NVL(GREATEST(((CT.STORAGE_END - CT.FREE_TIME_END) + 1), 0), 0) DAYS_STORED_SO_FAR, QTY_RECEIVED, QTY1_UNIT, QTY2_UNIT, CARGO_WEIGHT_UNIT, QTY_EXPECTED, QTY2_EXPECTED, CARGO_WEIGHT, QTY_IN_HOUSE, STORAGE_CUST_ID, CARGO_MARK, CARGO_BOL 
						FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE
						CT.LOT_NUM = CM.CONTAINER_NUM AND
						CT.DATE_RECEIVED IS NOT NULL AND
						CT.OWNER_ID != '312' AND
						TO_CHAR(CT.STORAGE_END + 1, 'MM/DD/YYYY') = '".$this_run_date."'";
//				echo $sql."<br>";
				ora_parse($bni_cursor, $sql);
				ora_exec($bni_cursor);
				while(ora_fetch_into($bni_cursor, $bni_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$make_billing_record_for_this_lot = true;
					$comm = $bni_row['THE_COMM'];
					$vessel = $bni_row['THE_VES'];
					$cust = $bni_row['THE_CUST'];
					$whse = substr($bni_row['WAREHOUSE_LOCATION'], 0, 1);
					$box = substr($bni_row['WAREHOUSE_LOCATION'], 1, 1);
					$orig_cont = $bni_row['ORIGINAL_CONTAINER_NUM'];
					$cont = $bni_row['CONTAINER_NUM'];
					$days_so_far = $bni_row['DAYS_STORED_SO_FAR'];
					$mark = $bni_row['CARGO_MARK'];
					$bol = $bni_row['CARGO_BOL'];

					$sql = "SELECT * FROM RATE WHERE
							(CUSTOMERID = '".$cust."' OR CUSTOMERID IS NULL) AND
							(COMMODITYCODE = '".$comm."' OR COMMODITYCODE IS NULL) AND
							(ARRIVALNUMBER = '".$vessel."' OR ARRIVALNUMBER IS NULL) AND
							(WAREHOUSE = '".$whse."' OR WAREHOUSE IS NULL) AND
							(BOX = '".$box."' OR BOX IS NULL) AND
								(('".$orig_cont."' != '' AND '".$orig_cont."' != '".$cont."' AND ARRIVALTYPE = 'X') OR
								(".$vessel." > 20 AND 
									".$vessel." IN (SELECT LR_NUM FROM VOYAGE) AND
									ARRIVALTYPE = 'V') OR
								('".$vessel."' IN ('3', '4', '7') AND ARRIVALTYPE = 'R') OR
								(".$vessel." <= 20 AND '".$vessel."' NOT IN ('3', '4', '7') AND ARRIVALTYPE = 'T') OR
								ARRIVALTYPE = 'A')
							AND RATESTARTDATE <= ".$days_so_far."
							AND SCANNEDORUNSCANNED = 'UNSCANNED'
							ORDER BY RATEPRIORITY ASC, RATESTARTDATE DESC";
					ora_parse($rate_cursor, $sql);
					ora_exec($rate_cursor);
//					echo $sql."<br>";
					if(!ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//						$email_bni_bad_details .= "Lot ".$bni_row['CONTAINER_NUM']." (cust ".$cust." comm ".$comm." vsl ".$vessel.") has no valid rate in the rate table.  Cannot bill.<br>";
						$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']." bol ".$bol." mark ".$mark." (cust ".$cust." comm ".$comm." vsl ".$vessel.") has no valid rate in the rate table.  Cannot bill.<br>";
						$make_billing_record_for_this_lot = false;
					} else {
//						echo "lot".$bni_row['CONTAINER_NUM']."<br>";
						$rate = $rate_row['RATE'];
//						echo "rate".$rate."<br>";
						if($rate_row['BILLTOCUSTOMER'] != ""){
							$bill_to_cust = $rate_row['BILLTOCUSTOMER'];
						} else {
							$bill_to_cust = $bni_row['STORAGE_CUST_ID'];
						}
						// THIS NEXT LINE VARIES FROM AUTOSTORAGE
						if($bni_row['QTY_EXPECTED'] == 0){
							$ratio_in_house_to_bill = 0;
//							$email_bni_bad_details .= "Lot ".$cont." Cust ".$cust." Comm ".$comm." has had it's contents 100% transferred since the last bill; autocalculation for this bill has been halted.<br>";
							$email_bni_stopbill_details .= "Lot ".$cont." Cust ".$cust." Comm ".$comm." bol ".$bol." mark ".$mark." has had it`s contents 100% transferred since the last bill; autocalculation for this bill has been halted.<br>";
						} else {
							$ratio_in_house_to_bill = Qty_as_of_date($cont, $this_run_date, $bni_conn) / $bni_row['QTY_EXPECTED'];
						}
//						echo "ratio_in_house_to_bill".$ratio_in_house_to_bill."<br>";

						$bill_duration = $rate_row['BILLDURATION'];
//						echo "bill_duration".$bill_duration."<br>";
						$bill_duration_unit = $rate_row['BILLDURATIONUNIT'];
//						echo "bill_duration_unit".$bill_duration_unit."<br>";
						$bill_unit = $rate_row['UNIT'];
//						echo "bill_unit".$bill_unit."<br>";

						// figure which UOM to bill off of
						if($bill_unit == $bni_row['QTY1_UNIT']){
							$original_receive = $bni_row['QTY_EXPECTED'];
//							echo "original_receive1".$original_receive."<br>";
						} elseif($bill_unit == $bni_row['QTY2_UNIT']){
							$original_receive = $bni_row['QTY2_EXPECTED'];
//							echo "original_receive2".$original_receive."<br>";
						} else { // check to see if it matches to a Weight
							$sql = "SELECT CONVERSION_FACTOR FROM UNIT_CONVERSION WHERE PRIMARY_UOM = '".$bni_row['CARGO_WEIGHT_UNIT']."' AND SECONDARY_UOM = '".$bill_unit."'";
			//				echo $sql."\n";
							ora_parse($Short_Term_Cursor, $sql);
							ora_exec($Short_Term_Cursor);
							if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//								$email_bni_bad_details .= "Lot ".$bni_row['CONTAINER_NUM']." is measured in ".$bni_row['QTY1_UNIT'].", ".$bni_row['QTY2_UNIT'].", and ".$bni_row['CARGO_WEIGHT_UNIT'].".  The rate table wants to bill it by ".$bill_unit.".  Cannot bill.<br>";
								$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']." bol ".$bol." mark ".$mark." is measured in ".$bni_row['QTY1_UNIT'].", ".$bni_row['QTY2_UNIT'].", and ".$bni_row['CARGO_WEIGHT_UNIT'].".  The rate table wants to bill it by ".$bill_unit.".  Cannot bill.<br>";
								$make_billing_record_for_this_lot = false;
							} else {
								$original_receive = $bni_row['CARGO_WEIGHT'] * $short_term_row['CONVERSION_FACTOR'];
//								echo "original_receivewt".$original_receive."<br>";
							}
						}

						$bill_quantity = $original_receive * $ratio_in_house_to_bill;
						if($bill_unit != $bni_row['QTY1_UNIT']){
							$qty1_for_printing = round($bni_row['QTY_EXPECTED'] * $ratio_in_house_to_bill, 2);
							$qty1_for_printing = "(". $qty1_for_printing . $bni_row['QTY1_UNIT'].")";
						} else {
							$qty1_for_printing = "";
						}
//						echo "orig ".$original_receive." ratio ".$ratio_in_house_to_bill." qty ".$bill_quantity."<br>";
						$bill_amount = $bill_quantity * $rate;

//						echo "amount ".$bill_amount."<br>";
						if($bill_amount <= 0){
							$make_billing_record_for_this_lot = false;
						}

						// get service code from LOCATION_CATEGORY table, based on warehouse location
						$sql = "SELECT STORAGE_SERVICE_CODE FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = UPPER('".$bni_row['WAREHOUSE_LOCATION']."')";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
			//			echo $sql."\n";
						if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//							$email_bni_bad_details .= "Lot ".$bni_row['CONTAINER_NUM']."'s warehouse location (".$bni_row['WAREHOUSE_LOCATION'].") has no entry in Location Category Table.  Cannot bill.<br>";
							$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']."`s warehouse location (".$bni_row['WAREHOUSE_LOCATION'].") has no entry in Location Category Table.  Cannot bill.<br>";
							$make_billing_record_for_this_lot = false;
						} else {
							$service_code = $short_term_row['STORAGE_SERVICE_CODE'];
						}

						// get next BILLING_NUM for billing table
						$sql = "SELECT MAX(BILLING_NUM) THE_MAX FROM BILLING";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			//			echo $sql."\n";
						$billing_num = $short_term_row['THE_MAX'] + 1;

						// Get the Oracle Asset Code
						// Note that if one doesnt exist, we update the email, but do NOT prevent record from writing.
						$sql = "SELECT ASSET_CODE FROM ASSET_PROFILE WHERE SERVICE_LOCATION_CODE = UPPER('".$bni_row['WAREHOUSE_LOCATION']."')";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
			//			echo $sql."\n";
						if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//							$email_bni_bad_details .= "Lot ".$bni_row['CONTAINER_NUM']." (".$bni_row['WAREHOUSE_LOCATION'].") has no valid Asset Code (This will not stop bill from generating).<br>";
							$email_bni_okbill_notify_details .= "Lot ".$bni_row['CONTAINER_NUM']." (".$bni_row['WAREHOUSE_LOCATION'].") bol ".$bol." mark ".$mark." has no valid Asset Code (This will not stop bill from generating).<br>";
							$asset_code = "";
						} else {
							$asset_code = $short_term_row['ASSET_CODE'];
						}

						// ok, we gots the data... time to tell the DB all about it.
						if($make_billing_record_for_this_lot){
							$sql = "INSERT INTO BILLING (
									CUSTOMER_ID,
									SERVICE_CODE,
									LOT_NUM,
									ACTIVITY_NUM,
									BILLING_NUM,
									EMPLOYEE_ID,
									SERVICE_START,
									SERVICE_STOP,
									SERVICE_AMOUNT,
									SERVICE_STATUS,
									SERVICE_DESCRIPTION,
									SERVICE_RATE,
									LR_NUM,
									ARRIVAL_NUM,
									COMMODITY_CODE,
									INVOICE_NUM,
									SERVICE_DATE,
									SERVICE_QTY,
									SERVICE_NUM,
									THRESHOLD_QTY,
									LEASE_NUM,
									SERVICE_UNIT,
									LABOR_RATE_TYPE,
									LABOR_TYPE,
									PAGE_NUM,
									CARE_OF,
									BILLING_TYPE,
									MEMO_NUM,
									ASSET_CODE)
								SELECT
									'".$bill_to_cust."',
									'".$service_code."',
									'".$cont."',
									'1',
									'".$billing_num."',
									'4',
									STORAGE_END + 1, ";

							if($bill_duration_unit == "DAY"){
								$sql .= "STORAGE_END + ".$bill_duration.", ";
							} else {
								// no one has any idea
								$sql .= "'', ";
							}

							$sql .= "'".round($bill_amount, 2)."',
									'PREINVOICE',
									'STORAGE CHARGE',
									'".$rate."',
									'".$vessel."',
									'1',
									'".$comm."',
									'0',
									STORAGE_END + 1,
									'".round($bill_quantity, 2)."',
									'1',
									'0',
									'0',
									'".$bill_unit."',
									'',
									'',
									'1',
									'Y',
									'STORAGE',
									'".$qty1_for_printing."',
									'".$asset_code."'
									FROM CARGO_TRACKING
									WHERE LOT_NUM = '".$cont."'";
			//				echo $sql."\n";
							ora_parse($update_bni_cursor, $sql);
							ora_exec($update_bni_cursor);

							$mail_body_summary .= $cont." bol ".$bol." mark ".$mark." - cust ".$bill_to_cust." - ".$bill_duration." days, $".round($bill_amount, 2)."\r\n";

							$sql = "UPDATE CARGO_TRACKING SET STORAGE_END = ";
							if($bill_duration_unit == "DAY"){
								$sql .= "STORAGE_END + ".$bill_duration." ";
							} else {
								// no one has any idea, defaulting to days
								$sql .= "STORAGE_END + ".$bill_duration." ";
							}
							$sql .= " WHERE LOT_NUM = '".$cont."'";
							ora_parse($update_bni_cursor, $sql);
							ora_exec($update_bni_cursor);

						}
					}
				}

				ora_commit($bni_conn);

				$current_time += (60*60*24);
			}

			if($email_bni_stopbill_details == "" && $email_bni_okbill_notify_details == ""){
				echo "<font color=\"#0000FF\">BNI Bills Generated; no error/warning messages to report</font>";
			} else {
				if($email_bni_stopbill_details == ""){
					$email_bni_stopbill_details_disp = "None.";
				} else {
					$email_bni_stopbill_details_disp = $email_bni_stopbill_details;
				}
				if($email_bni_okbill_notify_details == ""){
					$email_bni_okbill_notify_details_disp = "None.";
				} else {
					$email_bni_okbill_notify_details_disp = $email_bni_okbill_notify_details;
				}
				echo "<font color=\"#FF0000\">BNI Bills Generated; however, the following unbillable lots were generated: <br>".$email_bni_stopbill_details_disp."</font><BR><font color=\"#99CC33\">And the following notices were generated: <br>".$email_bni_okbill_notify_details_disp."</font>";
			}

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'BNIMANUALRUN'";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
			ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$mailTO = $email_row['TO'];
			$mailheaders = "From: ".$email_row['FROM']."\r\n";

			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}

			$mailSubject = $email_row['SUBJECT'];
			$mailSubject = str_replace("_0_", $date_start, $mailSubject);
			$mailSubject = str_replace("_1_", $date_end, $mailSubject);

			$email_bni_stopbill_details = str_replace("<br>", "\r\n", $email_bni_stopbill_details);
			$email_bni_okbill_notify_details = str_replace("<br>", "\r\n", $email_bni_okbill_notify_details);

			$body = $email_row['NARRATIVE'];
			$body = str_replace("_2_", $mail_body_summary."\r\n", $body);
			$body = str_replace("_3_", $email_bni_stopbill_details.$email_bni_okbill_notify_details, $body);

			if(mail($mailTO, $mailSubject, $body, $mailheaders)){
				$sql = "INSERT INTO JOB_QUEUE
							(JOB_ID,
							SUBMITTER_ID,
							SUBMISSION_DATETIME,
							JOB_TYPE,
							JOB_DESCRIPTION,
							DATE_JOB_COMPLETED,
							COMPLETION_STATUS,
							JOB_EMAIL_TO,
							JOB_EMAIL_CC,
							JOB_EMAIL_BCC,
							JOB_BODY)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'".$user."',
							SYSDATE,
							'EMAIL',
							'BNIMANUALRUN',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($ED_cursor, $sql);
				ora_exec($ED_cursor);
//				echo $sql;
			}


		}

	}
		// end of the BNI storage bills.



?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">BNI Storage After-The-Fact
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="storage_manualbills.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Starting Date:  </font></td>
		<td><input type="text" name="date_start" size="15" maxlength="10"><a href="javascript:show_calendar('meh.date_start');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Ending Date:  </font></td>
		<td><input type="text" name="date_end" size="15" maxlength="10"><a href="javascript:show_calendar('meh.date_end');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Storage Bills"></td>
	</tr>
</form>
</table>

<? include("pow_footer.php");



function Qty_as_of_date($cont, $this_run_date, $bni_conn){
	// NOTE:  this function assumes that $cont is a valid LOT_NUM from CARGO_TRACKING.
	// if it is not (which means the calling routine has a bug), the return value will be "", which equates to 0 for mathematical purposes.
	$bni_cursor = ora_open($bni_conn);
	$Short_Term_Cursor = ora_open($bni_conn);

	$sql = "SELECT QTY_IN_HOUSE FROM CARGO_TRACKING WHERE LOT_NUM = '".$cont."'";
	ora_parse($bni_cursor, $sql);
	ora_exec($bni_cursor);
	ora_fetch_into($bni_cursor, $bni_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_IH = $bni_row['QTY_IN_HOUSE'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY WHERE LOT_NUM = '".$cont."' AND DATE_OF_ACTIVITY >= TO_DATE('".$this_run_date."', 'MM/DD/YYYY')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$aggregate_activities = $Short_Term_row['THE_CHANGE'];

	return ($current_IH + $aggregate_activities);
}

function Which_Date_Is_First($a, $b){
	// returns a negative # if $a is earlier than $b, a positive # if $b is earlier than $a, and 0 if they are equal
	if($a == $b){
		return 0;
	}

	// they aren't equal; figure the difference
	$a_temp = explode("/", $a);
	$b_temp = explode("/", $b);
//	print_r($a_temp)."<br>";
//	print_r($b_temp)."<br>";

	// year check
	if($a_temp[2] < $b_temp[2]){
		return -1;
	} elseif($a_temp[2] > $b_temp[2]){
		return 1;
	}

	//month check
	if($a_temp[0] < $b_temp[0]){
		return -1;
	} elseif($a_temp[0] > $b_temp[0]){
		return 1;
	}

	// day check
	if($a_temp[1] < $b_temp[1]){
		return -1;
	} elseif($a_temp[1] > $b_temp[1]){
		return 1;
	}

	return 0; // this only happens on an error, so as not to crash the program.
}
