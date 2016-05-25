<?
  	// All POW files need this session file included
	include("pow_session.php");

  	// Define some vars for the skeleton page
  	$title = "Unemployment";
  	$area_type = "HRMS";

  	// Provides header / leftnav
  	include("pow_header.php");
	
	if($access_denied)
	{
		printf("Access Denied from HRMS system");
	    	include("pow_footer.php");
	    	exit;
	}

  	include("utility.php");
  
  	//get DB connection
  	include("connect.php");
  	$conn = ora_logon("LABOR@$lcs", "LABOR");
  	//$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

  	if($conn < 1)
  	{
      	printf("Error logging on to the LCS Oracle Server: ");
        	printf(ora_errorcode($conn));
        	printf("Please try later!");
        	exit;
  	}

  	// open a curosr
  	$cursor = ora_open($conn);

  	$filename = trim($HTTP_POST_FILES['file1']['tmp_name']);
  	//$eDate = $HTTP_POST_VARS['eDate'];
	//printf("filename:$filename<br>");
	//$filename = $_GET['filename'];
  	
  	if (($filename != 'none') && ($filename != '' )) // if level 1
  	{        		
        	// open the uploaded file for read
        	if ($handle = fopen("$filename", "rb"))
        	{
			$line = fgets($handle);
			$column = split(",", $line);
			$num_cols = count($column);

		    	for ($i=0; $i<$num_cols; $i++)	// strip whitespace and make column names uppercase
			{
				$column[$i] = strtoupper(trim($column[$i]));
			}

			    // check if we got all the required columns
			    $expected_cols = array('EMPLOYEE ID','SSN', 'SENIORITY_PLACE', 'SP_EFF_DATE', 'RATE', 'RATE_EFF_DATE');
			    $existing_cols = 0;

			    foreach ($column as $value)
			    {
					if (in_array($value, $expected_cols))
						  $existing_cols++;
			    }

			    if ($existing_cols != count($expected_cols))
			    {
				     printf("Has only %s columns names exist in the transfer while %s columns names are expected
						 in the first line of the file<br />", $existing_cols,count($expected_cols));
				     printf("");
				     printf("Please modify the file and go back to <a href=\"add_emp_ssn.php\">
						 Update the Pay Rates of ILA employees</a> to update it again <br />");
				     exit;
			    }
		}
	      
		// Archieve Employee_SNN before truncate
		$user = $userdata['username'];
		$timestamp = date('Y-m-d H:i:s');
		if (ArchEmpSSN($user, $timestamp)>0)
		{
			//printf("archieved completed<br>");
		}

		// Truncate employee_ssn table
		$sql = "delete from employee_ssn";
		$statement = ora_parse($cursor, $sql);
		if (!ora_exec($cursor))
		{
			// Roll-back
			ora_rollback($conn);
			// Exit
			exit(ora_error($cursor));
		 }

		// Flags to indicate the deletion of seniority list
		$seniority_del_flag = -1;
		// Flags to indicate the deletion of rate
		$rate_del_flag = -1;

		while (!feof($handle))
		{
			$data = array();
			$fLine = fgets($handle);
			$temp = split(",", $fLine);

			for ($j=0; $j<$num_cols; $j++)
			{
				$data[$column[$j]] = trim($temp[$j]);
			}


			// Assign values to variables
			$empId = $data['EMPLOYEE ID'];
			$ssn = $data['SSN'];
			$sp = $data['SENIORITY_PLACE'];
			$sp_eff_date=$data['SP_EFF_DATE'];
			$rate = $data['RATE'];
			$rate_eff_date=$data['RATE_EFF_DATE'];

			if ($empId >0) // if level 2
			{
				$empId = sprintf("%04d", $empId);
				$ssn=trim($ssn);
				$sp=trim($sp);
				$sp_eff_date=trim($sp_eff_date);
				$rate=trim($rate);
				$rate_eff_date=trim($rate_eff_date);

				// insert into employee_ssn table
				$sql = "insert into employee_ssn values ('$empId','$ssn')"; 
				//printf($sql . "<br>");
				$statement = ora_parse($cursor, $sql);
				if (! ora_exec($cursor))
				{
					// Roll-back
					ora_rollback($conn);
					// Exit
					exit(ora_error($cursor));
				}

				// delete seniority list operation only needs to happen ONCE
				if ($seniority_del_flag < 0 )
				{
					// Delete records based on the given seniority effective date
					$sql="delete from employee_seniority where eff_date=To_Date('$sp_eff_date', 'mm/dd/yyyy')";
					//printf("Delete from senority list " . $sql . "<br>");
					$statement = ora_parse($cursor, $sql);
					if (! ora_exec($cursor))
					{
						// Roll-back
						ora_rollback($conn);
						// Exit
						exit(ora_error($cursor));
					}
					$seniority_del_flag = 1 ;
				}

				// insert into employee_seniority table
				$sql="insert into employee_seniority values ($sp, '$empId', TO_DATE('$sp_eff_date','mm/dd/yyyy'))";
				//printf($sql . "<br>");
				$statement = ora_parse($cursor, $sql);
				if (! ora_exec($cursor))
				{
					// Roll-back
					ora_rollback($conn);
					// Exit
					exit(ora_error($cursor));
				}

				// delete rate operation only needs to happen ONCE
				if ($rate_del_flag < 0)
				{
					// Delete records based on the given rate effective date
					$sql="delete from employee_rate where eff_date=To_Date('$rate_eff_date', 'mm/dd/yyyy')";
					//printf("Delete from rate " . $sql . "<br>");
					$statement = ora_parse($cursor, $sql);
					if (! ora_exec($cursor))
					{
						// Roll-back
						ora_rollback($conn);
						// Exit
						exit(ora_error($cursor));
					}
					// set the flag to indicate 'delete' took place
					$rate_del_flag = 1 ;
				}

				// insert into employee_rate table
				$sql="insert into employee_rate values ('$empId', $rate, TO_DATE('$rate_eff_date','mm/dd/yyyy'))";
				//printf($sql . "<br>");
				$statement = ora_parse($cursor, $sql);
				if (! ora_exec($cursor))
				{
					// Roll-back
					ora_rollback($conn);
					// Exit
					exit(ora_error($cursor));
				}			
			} // if level 2
		} // while loop        
		
		// Ccmmit transactions
		ora_commit($conn);        
  	} // if level 1
  
	ora_close($cursor);
  	ora_logoff($conn);

  	printf("Update Completed<br>");	
?>

<?

function ArchEmpSSN ($user, $time_stamp)
{  	
	// Make connection
  	$conn = ora_logon("LABOR@lcs", "LABOR");
  	//$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

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
	
	//printf("READING EXISTING TABLE<br>");
	
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
		//printf("$sql<br>");
	}
		
	return 1 ;

}

?>

<hr>
<br>
  	Back To <a href="display_index.php">View Employee Information Main Page</a>  


