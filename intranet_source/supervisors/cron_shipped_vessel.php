<?

/* Created by Adam Walter, 4/11/2006.
*  This program is run by cron every 10 minutes to automatically scan the table
*  VESSEL_CLOSING for certain values (specifically ones inputted from
*  dspc-s16 -> Supervisors/Enter_Shipped_Vessel.php) and modifying them accordingly
*  Any DB record with SAILED or ALERTED status, but no execution time, is found and 
*  actioned accordingly.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisors Applications";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Supervisors system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_Owner@BNI_BACKUP", "SAG_dev");
  if($conn < 1){
			echo "Error logging on to the Oracle Server: ";
			echo ora_errorcode($conn);
			exit;
  }

  $now = date("m/j/y g:i:s a");
  $temp = strtotime($now) + 21600;
  $six_hours_from_now = date("m/j/y g:i:s a", $temp);
  $Status_cursor = ora_open($conn);
  $Sailed_cursor = ora_open($conn);
  $Alerted_cursor = ora_open($conn);
  $sql = "SELECT * FROM VESSEL_CLOSING WHERE EXECUTION_TIME IS NULL";
  $statement = ora_parse($Status_cursor, $sql);
  ora_exec($Status_cursor);
  echo $sql."\n";
  ora_fetch_into($Status_cursor, $Status_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  echo $Status_row['LR_NUM']."\n";

  do {
	  // first assign the case where Execution time isn't set for SAILED status
	  echo $Status_row['STATUS']."m\n";
	  if (trim($Status_row['STATUS']) == "SAILED"){
		  $sql = "INSERT INTO VESSEL_CLOSING VALUES (".$Status_row['LR_NUM'].", 'ALERTED', to_date('".$six_hours_from_now."', 'MM/DD/YYYY HH:MI:SS AM'), '', to_date('".$now."', 'MM/DD/YYYY HH:MI:SS AM'))";
		  echo $sql."\n";
		  $statement = ora_parse($Sailed_cursor, $sql);
		  ora_exec($Sailed_cursor);

		  $sql = "UPDATE VESSEL_CLOSING SET EXECUTION_TIME = to_date('".$now."', 'MM/DD/YYYY HH:MI:SS AM') WHERE LR_NUM = ".$Status_row['LR_NUM']." AND STATUS = 'SAILED'";
		  $statement = ora_parse($Sailed_cursor, $sql);
		  ora_exec($Sailed_cursor);
	  }
	  // next assign the case where Execution time isn't set for ALERTED status
	  if (trim($Status_row['STATUS']) == "ALERTED"){
		  $sql = "INSERT INTO VESSEL_CLOSING VALUES (".$Status_row['LR_NUM'].", 'PROCESSED', '', '', to_date('".$now."', 'MM/DD/YYYY HH:MI:SS AM'))";
		  $statement = ora_parse($Alerted_cursor, $sql);
		  ora_exec($Alerted_cursor);

		  $sql = "UPDATE VESSEL_CLOSING SET EXECUTION_TIME = to_date('".$now."', 'MM/DD/YYYY HH:MI:SS AM') WHERE LR_NUM = ".$Status_row['LR_NUM']." AND STATUS = 'ALERTED'";
		  $statement = ora_parse($Alerted_cursor, $sql);
		  ora_exec($Alerted_cursor);
	  }
	  // no other cases at this time
  } while (ora_fetch_into($Status_cursor, $Status_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
  ora_close($Sailed_cursor);
  ora_close($Alerted_cursor);
  ora_commit($conn);
  ora_logoff($conn);
?>
<? include("pow_footer.php"); ?>
