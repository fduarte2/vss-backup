<?
/* Adam Walter, June 2007.
*  
*  This one-time use script will place values into the 3 newly create "initial" fields
* in the AT_EMPLOYEE table of BNI.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

   //get DB connection
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");

  if($conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $OuterCursor = ora_open($conn);
   $InnerCursor = ora_open($conn);
   $ChangeCursor = ora_open($conn);
   $Short_Term_Data = ora_open($conn);



	$current_employee = "NONE";

	$sql = "SELECT* FROM TIME_SUBMISSION ORDER BY EMPLOYEE_ID, WEEK_START_MONDAY";
	ora_parse($OuterCursor, $sql);
	ora_exec($OuterCursor);
	while(ora_fetch_into($OuterCursor, $OuterRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// for every row in TIME_SUBMISSION

		if($OuterRow['EMPLOYEE_ID'] == $current_employee){
			// this employee has already had it's minimum week updated, skip
		} else {
			$sql = "UPDATE AT_EMPLOYEE SET INITIAL_VACATION = '".$OuterRow['YTD_WEEK_START_VACATION_BAL']."', INITIAL_SICK = '".$OuterRow['YTD_WEEK_START_SICK_BAL']."', INITIAL_PERSONAL = '".$OuterRow['YTD_WEEK_START_PERSONAL_BAL']."' WHERE EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."'";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);

			$current_employee = $OuterRow['EMPLOYEE_ID'];
		}
	}
