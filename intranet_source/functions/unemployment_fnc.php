<?



  // Start a POW session
//include("pow_session.php");
//$user = $userdata['username'];
//$timestmp = date('Y-m-d H:i:s');
//printf("user:" . $user . " " . "time:" . $timestmp);

//include("connect.php");


function ArchEmpSSN ($user, $time_stamp)
{
  
	
	
	// Make connection
  	//$conn = ora_logon("LABOR@$lcs", "LABOR");
  	$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

  	if($conn < 1){
  		printf("Error logging on to the LCS Oracle Server: ");
      	printf(ora_errorcode($conn));
      	return -1 ;
  	}

	$cursor = ora_open($conn);
	$cursor1 = ora_open($conn);
  	
  	
  	// Select all entries in EMPLOYEE_SSN
	$sql = "SELECT * FROM EMPLOYEE_SSN";
	$statement = ora_parse($cursor, $sql);
	if (! ora_exec($cursor))
	{
		// Roll-back
		ora_rollback($conn);
		// Exit
		return -1 ;
	}
	
	
	// Loop through the recodset and insert records into archieve table
	while (ora_fetch($cursor))
	{
		$emp_id = ora_getcolumn($cursor,0);
		$emp_ssn = ora_getcolumn($cursor,1);
		
		$sql="insert into EMPLOYEE_SSN_DELETED values (EMPLOYEE_SSN_DELETE_SEQ.NextVal, '$emp_id', '$emp_ssn', '$user',TO_DATE('$time_stamp','YYYY-MM-DD HH24:MI:SS'))";
		//printf("$sql<br>");
		$statement = ora_parse($cursor1, $sql);
		if (! ora_exec($cursor1))
		{
			// Roll-back
			ora_rollback($conn);
			// Exit
			return -1;
		}	
	}
	
	
	return 1 ;
	
	

}

?>