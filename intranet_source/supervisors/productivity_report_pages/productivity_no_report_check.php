<?
/* Adam Walter, January 2007
*  This is a VERY SIMPLE (compared to most of my work) script designed to send
*  an email out regarding productivity reports (or, lack thereof).
*  Actual frequency of this email-check can be set via the cron job
*  that runs this script.
*
*  Edit Feb1, 2007.  Added an additional check to see if a single backhaul hour
*  has been worked against a vessel before including it in the email
*
*  Regretably, due to continued input, this program is getting less simple.  Editing
*  Feb 23, 2007, to include lists of unclosed commodities.
*******************************************************************************/
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);

  $conn2 = ora_logon("labor@LCS", "labor");
  if($conn2 < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn2));
			printf("</body></html>");
			exit;
  }
  $LCScursor = ora_open($conn2);

  $body = "";

  $sql = "SELECT to_char(SVC.OPS_ENTRY_TIME, 'MM/DD/YYYY') THE_TIME, VP.VESSEL_NAME, SVC.VESSEL VESSEL, SVC.* FROM SUPER_VESSEL_CLOSE SVC, VESSEL_PROFILE VP WHERE SVC.VESSEL = VP.LR_NUM AND SVC.PROD_REPORT_RUN = 'N'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
// choosing an if loop here, so that nothing is sent if nothing is present
  if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  $body = "Do you have more information to add to any of the vessels listed below?  i.e. would you like to close them?  If so, please use the vessel closing page and close the commodities that have finished working:\r\n\r\n";
	  do{
		  $sql = "SELECT COUNT(*) THE_COUNT FROM HOURLY_DETAIL WHERE VESSEL_ID = '".$row['VESSEL']."' AND (SERVICE_CODE LIKE '61%' OR SERVICE_CODE LIKE '641%')";
		  ora_parse($LCScursor, $sql);
		  ora_exec($LCScursor);
		  ora_fetch_into($LCScursor, $LCSrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if($LCSrow['THE_COUNT'] > 0){
			  $body .= "\r\n\r\n".$row['VESSEL_NAME'];

			  if($row['COMMODITY1'] != "" && $row['SUPER1'] == ""){
				  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row['COMMODITY1']."'";
				  ora_parse($cursor2, $sql);
				  ora_exec($cursor2);
				  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				  $body .= "\r\n\tAwaiting Closure:  Commodity ".$row2['COMMODITY_NAME'];
			  }
			  if($row['COMMODITY2'] != "" && $row['SUPER2'] == ""){
				  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row['COMMODITY2']."'";
				  ora_parse($cursor2, $sql);
				  ora_exec($cursor2);
				  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				  $body .= "\r\n\tAwaiting Closure:  Commodity ".$row2['COMMODITY_NAME'];
			  }
			  if($row['COMMODITY3'] != "" && $row['SUPER3'] == ""){
				  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row['COMMODITY3']."'";
				  ora_parse($cursor2, $sql);
				  ora_exec($cursor2);
				  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				  $body .= "\r\n\tAwaiting Closure:  Commodity ".$row2['COMMODITY_NAME'];
			  }
			  if($row['COMMODITY4'] != "" && $row['SUPER4'] == ""){
				  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row['COMMODITY4']."'";
				  ora_parse($cursor2, $sql);
				  ora_exec($cursor2);
				  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				  $body .= "\r\n\tAwaiting Closure:  Commodity ".$row2['COMMODITY_NAME'];
			  }
			  if($row['COMMODITY5'] != "" && $row['SUPER5'] == ""){
				  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row['COMMODITY5']."'";
				  ora_parse($cursor2, $sql);
				  ora_exec($cursor2);
				  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				  $body .= "\r\n\tAwaiting Closure:  Commodity ".$row2['COMMODITY_NAME'];
			  }
		  }
	  } while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

//	  $body .= "\r\nIf you've finished unloading, please close immediately.  If a vessel has appeared on this list incorrectly inform TS immediately.";

//	  $mailTo = "awalter@port.state.de.us";
	  $mailTo = "ddonofrio@port.state.de.us,wstans@port.state.de.us,OPSSupervisors@port.state.de.us";
	  $mailSubject = "Have these vessels finished?";
	  $mailHeaders = "From: " . "MailServer@port.state.de.us\r\n";
	  $mailHeaders .= "CC: " . "Martym@port.state.de.us,ltreut@port.state.de.us,fvignuli@port.state.de.us,jjaffe@port.state.de.us,ithomas@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,parul@port.state.de.us\r\n";
//	  $mailHeaders .= "CC: " . "fvignuli@port.state.de.us\r\n";
//	  $mailHeaders .= "BCC: " . "\r\n";
	  
	  if($body != "Do you have more information to add to any of the vessels listed below?  i.e. would you like to close them?  If so, please use the vessel closing page and close the commodities that have finished working:\r\n\r\n"){
		  // I.E. if there are any actual ships to report
		  mail($mailTo, $mailSubject, $body, $mailHeaders);
	  }
  }
?>
