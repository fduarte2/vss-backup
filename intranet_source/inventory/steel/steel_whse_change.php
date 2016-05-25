<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to modify steel DOs RF.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel modification";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$whse = $HTTP_POST_VARS['whse'];
	$DO_num = $HTTP_POST_VARS['DO_num'];
	$pre_check = $HTTP_POST_VARS['pre_check'];
	$submit = $HTTP_POST_VARS['submit'];

	$new_whse_1 = $HTTP_POST_VARS['new_whse_1'];
	$new_whse_2 = $HTTP_POST_VARS['new_whse_2'];
	if($new_whse_1 != "" || $new_whse_2 != ""){
		if($new_whse_1 != "" && $new_whse_2 != "" && $new_whse_1 != $new_whse_2){
			echo "<font color=\"#FF0000\">Please don't put different new whse's in both boxes :)</font>";
			$submit = "Select";
		} else {
			$submit = "Update";
			if($new_whse_1 != ""){
				$new_whse = $new_whse_1;
			} else {
				$new_whse = $new_whse_2;
			}
		}
	}

	if($submit == "Update"){
		$barcode_list = "Barcodes:\r\n";
		$edit_pallet_whse = $HTTP_POST_VARS['edit_pallet_whse'];
		$counter = $HTTP_POST_VARS['counter'];
		$vessel = $HTTP_POST_VARS['vessel'];
		$cust = $HTTP_POST_VARS['cust'];
		$pallets_updated = 0;

		for($i = 0; $i <= $counter; $i++){
			if($edit_pallet_whse[$i] != ""){
//AND REMARK = '".$whse_num."'
				$sql = "UPDATE CARGO_TRACKING
						SET WAREHOUSE_LOCATION = UPPER('".$new_whse."')
						WHERE PALLET_ID = '".$edit_pallet_whse[$i]."'
							AND ARRIVAL_NUM = '".$vessel."'
							AND RECEIVER_ID = '".$cust."'";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				$pallets_updated++;
				$barcode_list .= $edit_pallet_whse[$i]."\r\n";
			}
		}

		echo "<font color=\"0000FF\">".$pallets_updated." Pallets Updated to whse ".$new_whse.".</font>";

		// SEND DA EMAIL!
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'STEELLOCCHG'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailTO = ociresult($email, "TO");
		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}

		$mailSubject = ociresult($email, "SUBJECT");

		$body = ociresult($email, "NARRATIVE");

		$sql = "SELECT DISTINCT VP.LR_NUM, VESSEL_NAME, CUSTOMER_NAME, COMMODITY_NAME
				FROM CARGO_TRACKING CT, VESSEL_PROFILE VP, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.REMARK = '".$DO_num."'
					AND WAREHOUSE_LOCATION = '".$new_whse."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);

		$main_content = "\r\n\r\nVessel: ".ociresult($stid, "LR_NUM")."-".ociresult($stid, "VESSEL_NAME")."\r\n";
		$main_content .= "Customer: ".ociresult($stid, "CUSTOMER_NAME")."\r\n";
		$main_content .= "Commodity: ".ociresult($stid, "COMMODITY_NAME")."\r\n";
		$main_content .= "DO#: ".$DO_num."\r\n\r\n";
		$main_content .= $barcode_list;

		$body = str_replace("_0_", $pallets_updated, $body);
		$body = str_replace("_1_", $new_whse, $body);
		$body = str_replace("_2_", $main_content, $body);
/*
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		if($combo_counter_csv > 0){
			$attach=chunk_split(base64_encode($combo_csv));

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"AllBills.csv\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";
		}

		$Content.="--MIME_BOUNDRY--\n";
*/
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
						'WEEKLYCRON',
						SYSDATE,
						'EMAIL',
						'STEELLOCCHG',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}

	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Warehouse - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="steel_whse_change.php" method="post">
	<tr>
		<td align="left"><select name="DO_num"><option value="">Select a DO:</option>
<?

	$sql = "SELECT DISTINCT REMARK
			FROM CARGO_TRACKING
			WHERE COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'STEEL')
				AND QTY_IN_HOUSE > 0
				AND REMARK IS NOT NULL
				AND REMARK != 'NO DO'
			ORDER BY REMARK desc";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "REMARK"); ?>"<? if($DO_num == ociresult($stid, "REMARK")){?> selected <?}?>><? echo ociresult($stid, "REMARK"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td>Edit All?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pre_check" value="yes" checked>&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pre_check" value="no">&nbsp;No</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Select"><br><hr><br></td>
	</tr>
</form>
<?
	if($submit == "Select"){
		$total_pcs = 0;
		$total_weight = 0;
		// the next sections only display if "Select" is pressed.

		$sql = "SELECT COMMODITY_NAME, CUSTOMER_NAME, LR_NUM, VESSEL_NAME, CT.RECEIVER_ID, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
				WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
					AND CT.REMARK = '".$DO_num."'
					AND COMP.COMMODITY_TYPE = 'STEEL'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%">Customer:</td>
		<td><? echo ociresult($stid, "CUSTOMER_NAME"); ?></td>
	</tr>
	<tr>
		<td>Vessel:</td>
		<td><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></td>
	</tr>
	<tr>
		<td>Commodity:</td>
		<td><? echo ociresult($stid, "COMMODITY_NAME"); ?><br></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="update_DO" action="steel_whse_change.php" method="post">
<input type="hidden" name="vessel" value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>">
<input type="hidden" name="cust" value="<? echo ociresult($stid, "RECEIVER_ID"); ?>">
<input type="hidden" name="DO_num" value="<? echo $DO_num; ?>">
	<tr>
		<td align="center" colspan="6">New Whse&nbsp;&nbsp;&nbsp;<input name="new_whse_1" size="10" maxlength="10" type="text">
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="save Whse"></td>
	</tr>
	<tr>
		<td>Plate/Coil#</td>
		<td>Mark</td>
		<td>Pcs (In-House)</td>
		<td>Weight</td>
		<td>Current Location</td>
		<td>Edit?</td>
	</tr>
<?
		$counter = 0;
		$sql = "SELECT PALLET_ID, CARGO_DESCRIPTION, QTY_IN_HOUSE, WEIGHT, WAREHOUSE_LOCATION
				FROM CARGO_TRACKING
				WHERE REMARK = '".$DO_num."'
					AND QTY_IN_HOUSE > 0
					AND RECEIVER_ID = '".ociresult($stid, "RECEIVER_ID")."'
					AND ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'
				ORDER BY PALLET_ID";
		$pallet_list = ociparse($rfconn, $sql);
		ociexecute($pallet_list);
		while(ocifetch($pallet_list)){
?>
	<tr>
		<td><? echo ociresult($pallet_list, "PALLET_ID"); ?></td>
		<td><? echo ociresult($pallet_list, "CARGO_DESCRIPTION"); ?></td>
		<td><? echo ociresult($pallet_list, "QTY_IN_HOUSE"); ?></td>
		<td><? echo ociresult($pallet_list, "WEIGHT"); ?></td>
		<td><? echo ociresult($pallet_list, "WAREHOUSE_LOCATION"); ?>&nbsp;</td>
		<td><input type="checkbox" name="edit_pallet_whse[<? echo $counter; ?>]" 
					value="<? echo ociresult($pallet_list, "PALLET_ID"); ?>" <? if($pre_check == "yes"){?> checked <?}?>></td>
	</tr>
<?
			$total_pcs += ociresult($pallet_list, "QTY_IN_HOUSE");
			$total_weight += ociresult($pallet_list, "WEIGHT");
			$counter++;
		}
?>
	<tr>
		<td colspan="2" align="right"><b>TOTALS:</b></td>
		<td><b><? echo $total_pcs; ?></b></td>
		<td><b><? echo $total_weight; ?></b></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="6">New Whse&nbsp;&nbsp;&nbsp;<input name="new_whse_2" size="10" maxlength="10" type="text">
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="save Whse"></td>
	</tr>
<input type="hidden" name="counter" value="<? echo $counter; ?>">
</form>
</table>
<?
	}
	include("pow_footer.php");
?>