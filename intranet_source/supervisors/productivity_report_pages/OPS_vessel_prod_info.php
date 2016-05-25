<?
/* Adam Walter, June 2006.  This page allows Inventory to enter a vessel,
*  Commodity, and tonnage/qty info; Designed so taht SUPV's can later close them and have
*  emails sent out accordingly.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Vessel Information Entry";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Supervisors system");
    include("pow_footer.php");
    exit;
  }

  $today = date('m/d/Y');
  $tomorrow = date('m/d/Y', (time() + 86400));
  $ora_now = date('m/d/Y H:m');

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);

  $conn = ora_logon("SAG_Owner@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $cursor = ora_open($conn);
  $vessel_name_cursor = ora_open($conn);
  $commodity_name_cursor = ora_open($conn);
  $commodity_group_choose_cursor = ora_open($conn);

  $LCSconn = ora_logon("LABOR@LCS", "LABOR");
  if($LCSconn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($LCSconn));
			printf("</body></html>");
			exit;
  }
  $LCS_cursor = ora_open($LCSconn);

  $vessel = $HTTP_POST_VARS['vessel'];
  $commodity1 = $HTTP_POST_VARS['commodity1'];
  $qty1 = $HTTP_POST_VARS['qty1'];
  $measurement1 = $HTTP_POST_VARS['measurement1'];
  $tonnage1 = $HTTP_POST_VARS['tonnage1'];
  $commodity2 = $HTTP_POST_VARS['commodity2'];
  $qty2 = $HTTP_POST_VARS['qty2'];
  $measurement2 = $HTTP_POST_VARS['measurement2'];
  $tonnage2 = $HTTP_POST_VARS['tonnage2'];
  $commodity3 = $HTTP_POST_VARS['commodity3'];
  $qty3 = $HTTP_POST_VARS['qty3'];
  $measurement3 = $HTTP_POST_VARS['measurement3'];
  $tonnage3 = $HTTP_POST_VARS['tonnage3'];
  $commodity4 = $HTTP_POST_VARS['commodity4'];
  $qty4 = $HTTP_POST_VARS['qty4'];
  $measurement4 = $HTTP_POST_VARS['measurement4'];
  $tonnage4 = $HTTP_POST_VARS['tonnage4'];
  $commodity5 = $HTTP_POST_VARS['commodity5'];
  $qty5 = $HTTP_POST_VARS['qty5'];
  $measurement5 = $HTTP_POST_VARS['measurement5'];
  $tonnage5 = $HTTP_POST_VARS['tonnage5'];

  $clear1 = $HTTP_POST_VARS['clear1'];
  $clear2 = $HTTP_POST_VARS['clear2'];
  $clear3 = $HTTP_POST_VARS['clear3'];
  $clear4 = $HTTP_POST_VARS['clear4'];
  $clear5 = $HTTP_POST_VARS['clear5'];

  $submit = $HTTP_POST_VARS['submit'];

  $bad_budget_array = array();

  $error = "";
?>
<script type="text/javascript">
   function change_comm_status() {
	   if (document.update_data.change_comm.checked == true){
		   document.update_data.new_comm.disabled = false
	   } else {
		   document.update_data.new_comm.disabled = true
	   }
   }

   function change_service_status() {
	   if (document.update_data.change_service.checked == true){
		   document.update_data.new_service.disabled = false
	   } else {
		   document.update_data.new_service.disabled = true
	   }
   }
</script>

<?


// a few validity varialbes for easier understanding of code
// basically, an entry is valid if all or none of the portions of said entry are entered, and said entries are numeric.
  $valid1 = ($commodity1 == "" && $qty1 == "" && $tonnage1 == "") || (($commodity1 != "" && is_numeric($commodity1)) && ($qty1 != "" && is_numeric($qty1)) && ($tonnage1 != "" && is_numeric($tonnage1)));
  $valid2 = ($commodity2 == "" && $qty2 == "" && $tonnage2 == "") || (($commodity2 != "" && is_numeric($commodity2)) && ($qty2 != "" && is_numeric($qty2)) && ($tonnage2 != "" && is_numeric($tonnage2)));
  $valid3 = ($commodity3 == "" && $qty3 == "" && $tonnage3 == "") || (($commodity3 != "" && is_numeric($commodity3)) && ($qty3 != "" && is_numeric($qty3)) && ($tonnage3 != "" && is_numeric($tonnage3)));
  $valid4 = ($commodity4 == "" && $qty4 == "" && $tonnage4 == "") || (($commodity4 != "" && is_numeric($commodity4)) && ($qty4 != "" && is_numeric($qty4)) && ($tonnage4 != "" && is_numeric($tonnage4)));
  $valid5 = ($commodity5 == "" && $qty5 == "" && $tonnage5 == "") || (($commodity5 != "" && is_numeric($commodity5)) && ($qty5 != "" && is_numeric($qty5)) && ($tonnage5 != "" && is_numeric($tonnage5)));

// also, a check if the commodities entered are even ones we are supposed to be doing productivity reports for
// do it 5 times, once for each commodity... man my logic needs some work.
  if($commodity1 != ""){
	  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity1."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($row['THE_COUNT'] == 0){
		  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY = '".$commodity1."'";
		  ora_parse($LCS_cursor, $sql);
		  ora_exec($LCS_cursor);
		  ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($row['THE_COUNT'] == 0){
			  array_push($bad_budget_array, $commodity1);
		  }
	  }
  }

  if($commodity2 != ""){
	  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity2."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($row['THE_COUNT'] == 0){
		  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY = '".$commodity2."'";
		  ora_parse($LCS_cursor, $sql);
		  ora_exec($LCS_cursor);
		  ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($row['THE_COUNT'] == 0){
			  array_push($bad_budget_array, $commodity2);
		  }
	  }
  }

  if($commodity3 != ""){
	  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity3."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($row['THE_COUNT'] == 0){
		  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY = '".$commodity3."'";
		  ora_parse($LCS_cursor, $sql);
		  ora_exec($LCS_cursor);
		  ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($row['THE_COUNT'] == 0){
			  array_push($bad_budget_array, $commodity3);
		  }
	  }
  }

  if($commodity4 != ""){
	  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity4."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($row['THE_COUNT'] == 0){
		  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY = '".$commodity4."'";
		  ora_parse($LCS_cursor, $sql);
		  ora_exec($LCS_cursor);
		  ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($row['THE_COUNT'] == 0){
			  array_push($bad_budget_array, $commodity4);
		  }
	  }
  }

  if($commodity5 != ""){
	  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity5."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if($row['THE_COUNT'] == 0){
		  $sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY = '".$commodity5."'";
		  ora_parse($LCS_cursor, $sql);
		  ora_exec($LCS_cursor);
		  ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($row['THE_COUNT'] == 0){
			  array_push($bad_budget_array, $commodity5);
		  }
	  }
  }


  $allvalid = ($valid1 && $valid2 && $valid3 && $valid4 && $valid5 && sizeof($bad_budget_array) == 0);

	if($submit == "Add Entry" && $vessel != "" && $allvalid){
// enter the initial record if submit is pressed, and any CORRECT data is present, and the vessel isn't already in table.
		$sql = "INSERT INTO SUPER_VESSEL_CLOSE (VESSEL, OPS_ENTRY_TIME, PROD_REPORT_RUN) VALUES ('".$vessel."', to_date('".$ora_now."', 'MM/DD/YYYY HH24:MI'), 'N')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}
	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	ora_parse($vessel_name_cursor, $sql);
	ora_exec($vessel_name_cursor);
	ora_fetch_into($vessel_name_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel_name = $vessel_row['VESSEL_NAME'];

	if(($submit == "Add Entry" || $submit == "Update Entry") && $vessel != "" && $allvalid){
		$body = "Entered by ".$user." at ".date('m/d/Y h:i a')."\r\n\r\n";

// now update up to 5 times.  Each entry only made if its record is valid; but before each update, check whether budget is in tons or not.  tons comes from BNI budget table, other comes from LCS budget table, as indicated by the different cursors.
// also, generate each line of the email from here, getting commodity names.

		if($valid1 && $commodity1 != ""){
			$sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE COMMODITY_CODE = '".$commodity1."' AND TYPE = 'BACKHAUL'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY_CODE = '".$commodity1."' AND TYPE = 'BACKHAUL'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget1 = $row['BUDGET'];
				$budget_per1 = 'TONS';
			} else {
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY = '".$commodity1."' AND TYPE = 'BACKHAUL'";
				ora_parse($LCS_cursor, $sql);
				ora_exec($LCS_cursor);
				ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget1 = $row['BUDGET'];
				$budget_per1 = $measurement1;
			}

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity1."'";
			ora_parse($commodity_name_cursor, $sql);
			ora_exec($commodity_name_cursor);
			ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity_name = $commodity_name_row['COMMODITY_NAME'];


			$body .= $tonnage1." TONS (".$qty1." ".$measurement1.") of Commodity ".$commodity_name.".\r\n\tTarget: ".$budget1." TONS/HOUR      Target Hours Allowed: ".round($tonnage1 / $budget1)."\r\n";

//			$body .= "Commodity: ".$commodity_name." --- ".$tonnage1." TONS (".$qty1." ".$measurement1.") TARGET BUDGET: ".$budget1." ".$budget_per1."/HOUR\r\n";

			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY1 = '".$commodity1."', QTY1 = '".$qty1."', MEASUREMENT1 = '".$measurement1."', TONNAGE1 = '".$tonnage1."', BUDGET1 = '".$budget1."', BUDGET_PER1 = '".$budget_per1."' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
		if($valid2 && $commodity2 != ""){
			$sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE COMMODITY_CODE = '".$commodity2."' AND TYPE = 'BACKHAUL'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY_CODE = '".$commodity2."' AND TYPE = 'BACKHAUL'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget2 = $row['BUDGET'];
				$budget_per2 = 'TONS';
			} else {
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY = '".$commodity2."' AND TYPE = 'BACKHAUL'";
				ora_parse($LCS_cursor, $sql);
				ora_exec($LCS_cursor);
				ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget2 = $row['BUDGET'];
				$budget_per2 = $measurement2;
			}

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity2."'";
			ora_parse($commodity_name_cursor, $sql);
			ora_exec($commodity_name_cursor);
			ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity_name = $commodity_name_row['COMMODITY_NAME'];


			$body .= $tonnage2." TONS (".$qty2." ".$measurement2.") of Commodity ".$commodity_name.".\r\n\tTarget: ".$budget2." TONS/HOUR      Target Hours Allowed: ".round($tonnage2 / $budget2)."\r\n";

//			$body .= "Commodity: ".$commodity_name." --- ".$tonnage2." TONS (".$qty2." ".$measurement2.") TARGET BUDGET: ".$budget2." ".$budget_per2."/HOUR\r\n";


			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY2 = '".$commodity2."', QTY2 = '".$qty2."', MEASUREMENT2 = '".$measurement2."', TONNAGE2 = '".$tonnage2."', BUDGET2 = '".$budget2."', BUDGET_PER2 = '".$budget_per2."' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
		if($valid3 && $commodity3 != ""){
			$sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE COMMODITY_CODE = '".$commodity3."' AND TYPE = 'BACKHAUL'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY_CODE = '".$commodity3."' AND TYPE = 'BACKHAUL'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget3 = $row['BUDGET'];
				$budget_per3 = 'TONS';
			} else {
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY = '".$commodity3."' AND TYPE = 'BACKHAUL'";
				ora_parse($LCS_cursor, $sql);
				ora_exec($LCS_cursor);
				ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget3 = $row['BUDGET'];
				$budget_per3 = $measurement3;
			}

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity3."'";
			ora_parse($commodity_name_cursor, $sql);
			ora_exec($commodity_name_cursor);
			ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity_name = $commodity_name_row['COMMODITY_NAME'];


			$body .= $tonnage3." TONS (".$qty3." ".$measurement3.") of Commodity ".$commodity_name.".\r\n\tTarget: ".$budget3." TONS/HOUR      Target Hours Allowed: ".round($tonnage3 / $budget3)."\r\n";

//			$body .= "Commodity: ".$commodity_name." --- ".$tonnage3." TONS (".$qty3." ".$measurement3.") TARGET BUDGET: ".$budget3." ".$budget_per3."/HOUR\r\n";


			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY3 = '".$commodity3."', QTY3 = '".$qty3."', MEASUREMENT3 = '".$measurement3."', TONNAGE3 = '".$tonnage3."', BUDGET3 = '".$budget3."', BUDGET_PER3 = '".$budget_per3."' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
		if($valid4 && $commodity4 != ""){
			$sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE COMMODITY_CODE = '".$commodity4."' AND TYPE = 'BACKHAUL'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY_CODE = '".$commodity4."' AND TYPE = 'BACKHAUL'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget4 = $row['BUDGET'];
				$budget_per4 = 'TONS';
			} else {
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY = '".$commodity4."' AND TYPE = 'BACKHAUL'";
				ora_parse($LCS_cursor, $sql);
				ora_exec($LCS_cursor);
				ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget4 = $row['BUDGET'];
				$budget_per4 = $measurement4;
			}

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity4."'";
			ora_parse($commodity_name_cursor, $sql);
			ora_exec($commodity_name_cursor);
			ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity_name = $commodity_name_row['COMMODITY_NAME'];


			$body .= $tonnage4." TONS (".$qty4." ".$measurement4.") of Commodity ".$commodity_name.".\r\n\tTarget: ".$budget4." TONS/HOUR      Target Hours Allowed: ".round($tonnage4 / $budget4)."\r\n";

//			$body .= "Commodity: ".$commodity_name." --- ".$tonnage4." TONS (".$qty4." ".$measurement4.") TARGET BUDGET: ".$budget4." ".$budget_per4."/HOUR\r\n";


			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY4 = '".$commodity4."', QTY4 = '".$qty4."', MEASUREMENT4 = '".$measurement4."', TONNAGE4 = '".$tonnage4."', BUDGET4 = '".$budget4."', BUDGET_PER4 = '".$budget_per4."' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
		if($valid5 && $commodity5 != ""){
			$sql = "SELECT COUNT(*) THE_COUNT FROM BUDGET WHERE COMMODITY_CODE = '".$commodity5."' AND TYPE = 'BACKHAUL'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY_CODE = '".$commodity5."' AND TYPE = 'BACKHAUL'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget5 = $row['BUDGET'];
				$budget_per5 = 'TONS';
			} else {
				$sql = "SELECT BUDGET FROM BUDGET WHERE COMMODITY = '".$commodity5."' AND TYPE = 'BACKHAUL'";
				ora_parse($LCS_cursor, $sql);
				ora_exec($LCS_cursor);
				ora_fetch_into($LCS_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$budget5 = $row['BUDGET'];
				$budget_per5 = $measurement5;
			}

			$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity5."'";
			ora_parse($commodity_name_cursor, $sql);
			ora_exec($commodity_name_cursor);
			ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity_name = $commodity_name_row['COMMODITY_NAME'];


			$body .= $tonnage5." TONS (".$qty5." ".$measurement5.") of Commodity ".$commodity_name.".\r\n\tTarget: ".$budget5." TONS/HOUR      Target Hours Allowed: ".round($tonnage5 / $budget5)."\r\n";

//			$body .= "Commodity: ".$commodity_name." --- ".$tonnage5." TONS (".$qty5." ".$measurement5.") TARGET BUDGET: ".$budget5." ".$budget_per5."/HOUR\r\n";


			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY5 = '".$commodity5."', QTY5 = '".$qty5."', MEASUREMENT5 = '".$measurement5."', TONNAGE5 = '".$tonnage5."', BUDGET5 = '".$budget5."', BUDGET_PER5 = '".$budget_per5."' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}

// deal with Removal Checkboxes.  While the logic seems to conflict with above statements, as long as the script's order is maintained
// (above, this, prod_report setting) it will function properly.
		
		if($clear1 == "yes"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY1 = NULL, QTY1 = NULL, MEASUREMENT1 = NULL, TONNAGE1 = NULL, BUDGET1 = NULL, BUDGET_PER1 = NULL, SUPER1 = NULL, TIME_COMPLETE1 = NULL, TIME_ENTERED1 = NULL WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}			
		if($clear2 == "yes"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY2 = NULL, QTY2 = NULL, MEASUREMENT2 = NULL, TONNAGE2 = NULL, BUDGET2 = NULL, BUDGET_PER2 = NULL, SUPER2 = NULL, TIME_COMPLETE2 = NULL, TIME_ENTERED2 = NULL WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}			
		if($clear3 == "yes"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY3 = NULL, QTY3 = NULL, MEASUREMENT3 = NULL, TONNAGE3 = NULL, BUDGET3 = NULL, BUDGET_PER3 = NULL, SUPER3 = NULL, TIME_COMPLETE3 = NULL, TIME_ENTERED3 = NULL WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}			
		if($clear4 == "yes"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY4 = NULL, QTY4 = NULL, MEASUREMENT4 = NULL, TONNAGE4 = NULL, BUDGET4 = NULL, BUDGET_PER4 = NULL, SUPER4 = NULL, TIME_COMPLETE4 = NULL, TIME_ENTERED4 = NULL WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}			
		if($clear5 == "yes"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET COMMODITY5 = NULL, QTY5 = NULL, MEASUREMENT5 = NULL, TONNAGE5 = NULL, BUDGET5 = NULL, BUDGET_PER5 = NULL, SUPER5 = NULL, TIME_COMPLETE5 = NULL, TIME_ENTERED5 = NULL WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}			

// also, set the final flag accordingly
		$sql = "SELECT * FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$was_sent = $row['PROD_REPORT_RUN'];  // used later in determining the body of the email
		if(($row['COMMODITY1'] == "" || ($row['SUPER1'] != "")) && ($row['COMMODITY2'] == "" || ($row['SUPER2'] != "")) && ($row['COMMODITY3'] == "" || ($row['SUPER3'] != "")) && ($row['COMMODITY4'] == "" || ($row['SUPER4'] != "")) && ($row['COMMODITY5'] == "" || ($row['SUPER5'] != ""))){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET PROD_REPORT_RUN = 'R' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			$reenter_body = "All commodities for this vessel are closed, and a report will be run at 11AM.\r\n";
		} else {
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			$reenter_body = "This vessel, however, still has commodities that need to be closed, and the report will not be run until they are.\r\n";
		}

// assign the mail headers (and last line of body) and send to recipients
		if($submit == "Add Entry"){
//			$mailto = "ddonofrio@port.state.de.us,Martym@port.state.de.us,ltreut@port.state.de.us,cfoster@port.state.de.us,OPSSupervisors@port.state.de.us,jjaffe@port.state.de.us";
//			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//			$mailheaders .= "CC: " . "wstans@port.state.de.us,fvignuli@port.state.de.us,gbailey@port.state.de.us\r\n";
//			$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,ithomas@port.state.de.us\r\n";
			$mailsubject = $vessel_name." Entered";
			$body .= "\r\n\r\nInformation entered by ".$user." at ".date('m/d/Y h:i a')."\r\n\r\n";
		} elseif($submit == "Update Entry"){
//			$mailto = "ddonofrio@port.state.de.us,Martym@port.state.de.us,ltreut@port.state.de.us,OPSSupervisors@port.state.de.us,jjaffe@port.state.de.us";
//			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//			$mailheaders .= "CC: " . "wstans@port.state.de.us,fvignuli@port.state.de.us,gbailey@port.state.de.us,ithomas@port.state.de.us\r\n";
//			$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us\r\n";
			$mailsubject = $vessel_name." Updated";
			$body .= "\r\n\r\nInformation updated by ".$user." at ".date('m/d/Y h:i a')."\r\n\r\n";
			if($was_sent == 'Y'){
				$body .= "\r\n\r\nNOTE:  Due to the updated information, the vessel productivity report will have to be re-run; please disregard the previous report.";  
			}
			$body .= "\r\n\r\nAll previous closings are still recorded in the system.";
			$body .= "\r\n\r\n".$reenter_body;
		}
		$body_replace = $body;
		$subject_replace = $mailsubject;

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'OPSVSLPRODINFO'";
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
		$mailSubject = str_replace("_0_", $subject_replace, $mailSubject);

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_1_", $body_replace, $body);


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
						'OPSVSLPRODINFO',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
		}

//		mail($mailto, $mailsubject, $body, $mailheaders);


// lastly, if this is an update, clear out the supervisor closing times
// due to user feedback, this is no longer necessary
/*		if($submit == "Update Entry"){
			$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER1 = '', TIME_COMPLETE1 = '', TIME_ENTERED1 = '', SUPER2 = '', TIME_COMPLETE2 = '', TIME_ENTERED2 = '', SUPER3 = '', TIME_COMPLETE3 = '', TIME_ENTERED3 = '', SUPER4 = '', TIME_COMPLETE4 = '', TIME_ENTERED4 = '', SUPER5 = '', TIME_COMPLETE5 = '', TIME_ENTERED5 = '' WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
*/

// closing the big if statement, this happens if that data entered by Marty is bad.
// 2 possibilities:  either he put in a comm code with no budget, or didnt totally fill out a column.
// Jan18:  technically, the first case should no longer happen with me restricting Marty's inputs, but...
	} elseif (($submit == "Add Entry" || $submit == "Update Entry") && $vessel != "" && !$allvalid){
		if(sizeof($bad_budget_array) == 0){
			echo "<font color=#ff0000>You cannot enter partial information in a column.  Each column either needs to be completed, or empty.<BR>Also, all fields must be numeric.<BR></font>";
		} else {
			echo "<font color=#ff0000>The following commodity(s): ";
			for($i = 0; $i < sizeof($bad_budget_array); $i++){
				echo $bad_budget_array[$i]." ";
			}
			echo "have no budgeted value.  If this is in error, please contact the Financial Analyst.<BR></font>";
		}
	}




// ok, now that all the "new" or "updated" routines are done, get the pertinent info for pre-populating
// the boxes with information of already-entered vessels.
	$sql = "SELECT * FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $prep_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Operations Vessel Productivity Information Page
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="vessel_change" action="OPS_vessel_prod_info.php" method="post">
		<td colspan="6" align="center">Vessel:  <select name="vessel" onchange="document.vessel_change.submit(this.form)">
								<option value="">Select a Vessel</option>
<?
	$sql = "SELECT VY.LR_NUM VESSEL, VP.VESSEL_NAME VESSEL_NAME
			FROM VOYAGE VY, VESSEL_PROFILE VP
			WHERE VY.LR_NUM = VP.LR_NUM
			AND LENGTH(VY.LR_NUM) > 2
			AND LENGTH(VY.LR_NUM) < 6
			ORDER BY VESSEL DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['VESSEL']; ?>" <? if($row['VESSEL'] == $vessel){ ?> selected <? } ?>><? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
								</select></td>
	</form>
	</tr>
	<tr>
		<td colspan="6">&nbsp;<br>&nbsp;</td>
	</tr>
<?
// this section is if a vessel is selected.  If vessel is changed, this updates automatically.
	if($vessel != ""){
?>
	<tr>
	<form name="add_info" action="OPS_vessel_prod_info.php" method="post">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
		<td width="16%">&nbsp;</td>
		<td width="16%" align="left">Commodity 1</td>
		<td width="16%" align="left">Commodity 2</td>
		<td width="16%" align="left">Commodity 3</td>
		<td width="16%" align="left">Commodity 4</td>
		<td width="16%" align="left">Commodity 5</td>
	</tr>
	<tr>
		<td width="16%" align="left"><a href="budget_group_codes.php" target="budget_group_codes.php">Commodity #:</a>&nbsp;</td>
		<td width="16%" align="left"><select name="commodity1">
						<option value="">---</option>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($commodity_group_choose_cursor, $sql);
	ora_exec($commodity_group_choose_cursor);
	while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $comm_row['GROUP_CODE']; ?>" <? if($comm_row['GROUP_CODE'] == $prep_row['COMMODITY1']){ ?> selected <? } ?>><? echo $comm_row['GROUP_CODE']; ?></option>
<?
	}
?>
				</select></td>		
		<td width="16%" align="left"><select name="commodity2">
						<option value="">---</option>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($commodity_group_choose_cursor, $sql);
	ora_exec($commodity_group_choose_cursor);
	while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $comm_row['GROUP_CODE']; ?>" <? if($comm_row['GROUP_CODE'] == $prep_row['COMMODITY2']){ ?> selected <? } ?>><? echo $comm_row['GROUP_CODE']; ?></option>
<?
	}
?>
				</select></td>		
		<td width="16%" align="left"><select name="commodity3">
						<option value="">---</option>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($commodity_group_choose_cursor, $sql);
	ora_exec($commodity_group_choose_cursor);
	while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $comm_row['GROUP_CODE']; ?>" <? if($comm_row['GROUP_CODE'] == $prep_row['COMMODITY3']){ ?> selected <? } ?>><? echo $comm_row['GROUP_CODE']; ?></option>
<?
	}
?>
				</select></td>		
		<td width="16%" align="left"><select name="commodity4">
						<option value="">---</option>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($commodity_group_choose_cursor, $sql);
	ora_exec($commodity_group_choose_cursor);
	while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $comm_row['GROUP_CODE']; ?>" <? if($comm_row['GROUP_CODE'] == $prep_row['COMMODITY4']){ ?> selected <? } ?>><? echo $comm_row['GROUP_CODE']; ?></option>
<?
	}
?>
				</select></td>		
		<td width="16%" align="left"><select name="commodity5">
						<option value="">---</option>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($commodity_group_choose_cursor, $sql);
	ora_exec($commodity_group_choose_cursor);
	while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $comm_row['GROUP_CODE']; ?>" <? if($comm_row['GROUP_CODE'] == $prep_row['COMMODITY5']){ ?> selected <? } ?>><? echo $comm_row['GROUP_CODE']; ?></option>
<?
	}
?>
				</select></td>		
	</tr>
	<tr>
		<td width="16%" align="left">Tonnage:&nbsp;</td>
		<td width="16%" align="left"><input type="text" name="tonnage1" size="10" maxlength="10" value="<? echo $prep_row['TONNAGE1']; ?>"></td>
		<td width="16%" align="left"><input type="text" name="tonnage2" size="10" maxlength="10" value="<? echo $prep_row['TONNAGE2']; ?>"></td>
		<td width="16%" align="left"><input type="text" name="tonnage3" size="10" maxlength="10" value="<? echo $prep_row['TONNAGE3']; ?>"></td>
		<td width="16%" align="left"><input type="text" name="tonnage4" size="10" maxlength="10" value="<? echo $prep_row['TONNAGE4']; ?>"></td>
		<td width="16%" align="left"><input type="text" name="tonnage5" size="10" maxlength="10" value="<? echo $prep_row['TONNAGE5']; ?>"></td>
	</tr>
	<tr>
		<td width="16%" align="left">QTY:&nbsp;</td>
		<td width="16%" align="left"><input type="text" name="qty1" size="6" maxlength="10" value="<? echo $prep_row['QTY1']; ?>">&nbsp;&nbsp;<select name="measurement1">
						<option value="PLTS" <? if($prep_row['MEASUREMENT1'] == "PLTS"){ ?> selected <? } ?>>PALLETS</option>
						<option value="BDLS" <? if($prep_row['MEASUREMENT1'] == "BDLS"){ ?> selected <? } ?>>BUNDLES</option>
						<option value="PCS" <? if($prep_row['MEASUREMENT1'] == "PCS"){ ?> selected <? } ?>>PIECES</option>
						<option value="OTHER" <? if($prep_row['MEASUREMENT1'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
						</select></td>
		<td width="16%" align="left"><input type="text" name="qty2" size="6" maxlength="10" value="<? echo $prep_row['QTY2']; ?>">&nbsp;&nbsp;<select name="measurement2">
						<option value="PLTS" <? if($prep_row['MEASUREMENT2'] == "PLTS"){ ?> selected <? } ?>>PALLETS</option>
						<option value="BDLS" <? if($prep_row['MEASUREMENT2'] == "BDLS"){ ?> selected <? } ?>>BUNDLES</option>
						<option value="PCS" <? if($prep_row['MEASUREMENT2'] == "PCS"){ ?> selected <? } ?>>PIECES</option>
						<option value="OTHER" <? if($prep_row['MEASUREMENT2'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
						</select></td>
		<td width="16%" align="left"><input type="text" name="qty3" size="6" maxlength="10" value="<? echo $prep_row['QTY3']; ?>">&nbsp;&nbsp;<select name="measurement3">
						<option value="PLTS" <? if($prep_row['MEASUREMENT3'] == "PLTS"){ ?> selected <? } ?>>PALLETS</option>
						<option value="BDLS" <? if($prep_row['MEASUREMENT3'] == "BDLS"){ ?> selected <? } ?>>BUNDLES</option>
						<option value="PCS" <? if($prep_row['MEASUREMENT3'] == "PCS"){ ?> selected <? } ?>>PIECES</option>
						<option value="OTHER" <? if($prep_row['MEASUREMENT3'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
						</select></td>
		<td width="16%" align="left"><input type="text" name="qty4" size="6" maxlength="10" value="<? echo $prep_row['QTY4']; ?>">&nbsp;&nbsp;<select name="measurement4">
						<option value="PLTS" <? if($prep_row['MEASUREMENT4'] == "PLTS"){ ?> selected <? } ?>>PALLETS</option>
						<option value="BDLS" <? if($prep_row['MEASUREMENT4'] == "BDLS"){ ?> selected <? } ?>>BUNDLES</option>
						<option value="PCS" <? if($prep_row['MEASUREMENT4'] == "PCS"){ ?> selected <? } ?>>PIECES</option>
						<option value="OTHER" <? if($prep_row['MEASUREMENT4'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
						</select></td>
		<td width="16%" align="left"><input type="text" name="qty5" size="6" maxlength="10" value="<? echo $prep_row['QTY5']; ?>">&nbsp;&nbsp;<select name="measurement5">
						<option value="PLTS" <? if($prep_row['MEASUREMENT5'] == "PLTS"){ ?> selected <? } ?>>PALLETS</option>
						<option value="BDLS" <? if($prep_row['MEASUREMENT5'] == "BDLS"){ ?> selected <? } ?>>BUNDLES</option>
						<option value="PCS" <? if($prep_row['MEASUREMENT5'] == "PCS"){ ?> selected <? } ?>>PIECES</option>
						<option value="OTHER" <? if($prep_row['MEASUREMENT5'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
						</select></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana" color="#EE3300">Remove?</font></td>
		<td><input type="checkbox" name="clear1" value="yes"></td>
		<td><input type="checkbox" name="clear2" value="yes"></td>
		<td><input type="checkbox" name="clear3" value="yes"></td>
		<td><input type="checkbox" name="clear4" value="yes"></td>
		<td><input type="checkbox" name="clear5" value="yes"></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;<br>&nbsp;</td>
	</tr>

<?
	// this next segment is a mess, but it basically says this:
	// if any commodity group is chosen, place under that segment of the table the commodity codes involved.
	if($prep_row['COMMODITY1'] != "" || $prep_row['COMMODITY2'] != "" || $prep_row['COMMODITY3'] != "" || $prep_row['COMMODITY4'] != "" || $prep_row['COMMODITY5'] != ""){
?>
	<tr>
		<td><font size="3" face="Verdana" color="#993300">Included Commodities:</font></td>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
<?
		if($prep_row['COMMODITY1'] != ""){
			$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$prep_row['COMMODITY1']."' ORDER BY COMMODITY_CODE";
			ora_parse($commodity_group_choose_cursor, $sql);
			ora_exec($commodity_group_choose_cursor);
?>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
			while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<tr>
			<td><? echo $comm_row['COMMODITY_CODE']; ?></td>
		</tr>
<?
			}
?>
		</table></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
<?
		if($prep_row['COMMODITY2'] != ""){
			$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$prep_row['COMMODITY2']."' ORDER BY COMMODITY_CODE";
			ora_parse($commodity_group_choose_cursor, $sql);
			ora_exec($commodity_group_choose_cursor);
?>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
			while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<tr>
			<td><? echo $comm_row['COMMODITY_CODE']; ?></td>
		</tr>
<?
			}
?>
		</table></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
<?
		if($prep_row['COMMODITY3'] != ""){
			$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$prep_row['COMMODITY3']."' ORDER BY COMMODITY_CODE";
			ora_parse($commodity_group_choose_cursor, $sql);
			ora_exec($commodity_group_choose_cursor);
?>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
			while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<tr>
			<td><? echo $comm_row['COMMODITY_CODE']; ?></td>
		</tr>
<?
			}
?>
		</table></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
<?
		if($prep_row['COMMODITY4'] != ""){
			$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$prep_row['COMMODITY4']."' ORDER BY COMMODITY_CODE";
			ora_parse($commodity_group_choose_cursor, $sql);
			ora_exec($commodity_group_choose_cursor);
?>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
			while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<tr>
			<td><? echo $comm_row['COMMODITY_CODE']; ?></td>
		</tr>
<?
			}
?>
		</table></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
<?
		if($prep_row['COMMODITY5'] != ""){
			$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$prep_row['COMMODITY5']."' ORDER BY COMMODITY_CODE";
			ora_parse($commodity_group_choose_cursor, $sql);
			ora_exec($commodity_group_choose_cursor);
?>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
			while(ora_fetch_into($commodity_group_choose_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<tr>
			<td><? echo $comm_row['COMMODITY_CODE']; ?></td>
		</tr>
<?
			}
?>
		</table></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="<? if($prep_row['VESSEL'] == ""){ echo "Add Entry"; } else { echo "Update Entry"; } ?>"></td>
	</tr>
	</form>
<?
	}
?>
</table>

<? include("pow_footer.php"); ?>
