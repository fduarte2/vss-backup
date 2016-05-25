<?
/*
*	Adam Walter, May 2013
*
*	A page to modify unreceived DT-based rolls
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "SUPV System";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
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

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Changes"){
		$plt = $HTTP_POST_VARS['plt'];
		$cust = $HTTP_POST_VARS['cust'];
		$arv = $HTTP_POST_VARS['arv'];
		$PRF = trim(str_replace("'", "`", $HTTP_POST_VARS['PRF']));
		$batch_id = trim(str_replace("'", "`", $HTTP_POST_VARS['batch_id']));
		$BM = trim(str_replace("'", "`", $HTTP_POST_VARS['BM']));
		$weight = trim(str_replace("'", "`", $HTTP_POST_VARS['weight']));
		$linearfeet = trim(str_replace("'", "`", $HTTP_POST_VARS['linearfeet']));

		// validity checks
		$error_msg = "";

		$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_EDI_IMPORT_CODES
				WHERE PAPER_CODE = '".$batch_id."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$error_msg .= "Entered Paper Code (".$batch_id.") not found in system.<br>&nbsp;&nbsp;&nbsp;If this code is valid, please add it before changing this roll's code.<br>";
		}

		if(!is_numeric($weight) || $weight <= 0){
			$error_msg .= "Entered Weight (".$weight.") not valid.<br>";
		}

		if(!is_numeric($linearfeet) || $linearfeet <= 0){
			$error_msg .= "Entered Linear Feet (".$linearfeet.") not valid.<br>";
		}

		// did it pass?
		if($error_msg != ""){
			echo "<font color=\"#FF0000\">".$error_msg."<br><b>Please use your browser's Back Button to return to the previous screen and fix the errors.<b></font>";
		} else {
			// alright, lets update!
			$cargo_desc = $PRF." ".$batch_id." ".$BM;

			$sql = "SELECT MAX(BOL) THE_MAX FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$max_dock_ticket = ociresult($short_term_data, "THE_MAX");

			$sql = "SELECT CARGO_DESCRIPTION FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$arv."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "CARGO_DESCRIPTION") != $cargo_desc){
				$DT_change = true;
			} else {
				$DT_change = false;
			}

			$sql = "SELECT BOL FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION = '".$cargo_desc."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$dock_ticket = $max_dock_ticket + 1;
			} else {
				$dock_ticket = ociresult($short_term_data, "BOL");
			}
			

			$sql = "UPDATE CARGO_TRACKING
					SET CARGO_DESCRIPTION = '".$cargo_desc."',
						WEIGHT = '".$weight."',
						VARIETY = '".$linearfeet."',
						BATCH_ID = '".$batch_id."',
						MARK = (SELECT BASIS_WEIGHT FROM DOLEPAPER_EDI_IMPORT_CODES WHERE PAPER_CODE = '".$batch_id."'),
						CARGO_SIZE = (SELECT PAPER_WIDTH FROM DOLEPAPER_EDI_IMPORT_CODES WHERE PAPER_CODE = '".$batch_id."'),
						BOL = '".$dock_ticket."'
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$arv."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$success_msg = "<b>Roll ".$plt." Updated.</b><br>";
			if($DT_change == true){
				$success_msg .= "Note:  Due to a change to the Cargo Description, the Dock Ticket# for this roll has been auto-adjusted to ".$dock_ticket.".<br>";
			} else {
				$success_msg .= "There was no change to the Cargo Description, so the Dock Ticket# remained ".$dock_ticket.".<br>";
			}
			echo "<font color=\"#0000FF\">".$success_msg."</font>";
		}
		$submit = "";
		$arv = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">DT Modification Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="DT_list_edit.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Arrival#:  </font></td>
		<td><input type="text" name="arv" size="15" maxlength="10" value="<? echo $arv; ?>"></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">DT#:  </font></td>
		<td><input type="text" name="DT" size="15" maxlength="10" value="<? echo $DT; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Cargo Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight of Roll</b></font></td>
		<td><font size="2" face="Verdana"><b>Basis Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Linear Feet</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
		$sql = "SELECT * FROM CARGO_TRACKING
				WHERE REMARK = 'DOLEPAPERSYSTEM'
					AND ARRIVAL_NUM = '".$arv."'
					AND BOL = '".$DT."'
				ORDER BY PALLET_ID";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana">No Pallets matching Search Criteria.</font></td>
	</tr>
<?		
		} else {
			$counter = 0;
			do {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
							AND CUSTOMER_ID = '".ociresult($stid, "RECEIVER_ID")."'
							AND ARRIVAL_NUM = '".$arv."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);

?>
<form name="alter<? echo $counter++; ?>" action="DT_edit_pallet.php" method="post">
<input type="hidden" name="plt" value="<? echo ociresult($stid, "PALLET_ID"); ?>">
<input type="hidden" name="cust" value="<? echo ociresult($stid, "RECEIVER_ID"); ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "BATCH_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CARGO_DESCRIPTION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMODITY_CODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "WEIGHT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "MARK"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "QTY_IN_HOUSE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "VARIETY"); ?></font></td>
<?
				if(ociresult($short_term_data, "THE_COUNT") <= 0){
?>
		<td><input type="submit" name="submit" value="Edit Roll"></td>
<?
				} else {
?>
		<td><font size="2" face="Verdana" color="#FF0000">Activity Detected, Cannot Modify</font></td>
<?
				}
?>
	</tr>
</form>
<?
			} while(ocifetch($stid));
		}
?>
</table>
<?
	}
	include("pow_footer.php");