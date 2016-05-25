<?
  	/*
  	
  	
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
  
  	*/
  	
  	/*
  	//get DB connection
  	include("connect.php");
  	//$conn = ora_logon("LABOR@$lcs", "LABOR");
  	$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

  	if($conn < 1)
  	{
      	printf("Error logging on to the LCS Oracle Server: ");
        	printf(ora_errorcode($conn));
        	printf("Please try later!");
        	exit;
  	}

  	// open a curosr
  	$cursor = ora_open($conn);
	*/
	//printf("validate<br>");
	
  	$filename = trim($HTTP_POST_FILES['file1']['tmp_name']);
  	//printf($filename);

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
	      
	      // loop through input file line by line
		$line_no=1;
		// Seniority effective date counter
		$s_eff_date_counter=0;
		// Rate effective date counter
		$r_eff_date_counter=0;
		// Prev seniority effective date
		$prv_s_eff_date="";
		// Prev rate effective date
		$prv_r_eff_date="";
		
		while (!feof($handle))
		{
			$line_no ++;	
			
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

			// Employee ID
			// SSN
			if (! ereg ("^[0-9]{3}-[0-9]{2}-[0-9]{4}$", $ssn)) 
			{
				$color="red";
				printf("<p><font color=$color>Invalid SSN '$ssn'. Format incorrect at line number $line_no in input file</font></p><br>" );
				exit;
   			}
   			
			// Seniority List effective date
			if (! ereg ("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $sp_eff_date)) 
			{
				$color="red";
				printf("<p><font color=$color>Invalid seniority effective date '$sp_eff_date' at line number $line_no in input file. Format required: mm/dd/yyyy</font></p><br>" );
				exit;
   			}
   			
   			if ($prv_s_eff_date != $sp_eff_date )
   			{
   				$s_eff_date_counter ++;
   				$prv_s_eff_date = $sp_eff_date;
   			}
   			
   			// Rate
   			if (! is_numeric($rate))
   			{
   				$color="red";
   				printf("<p><font color=$color>Invalid rate '$rate'. A number is required at line number $line_no in input file</font></p><br>" );
				exit;
   			}
   			
   			// Rate effective date
   			if (! ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $rate_eff_date))
			{
				$color="red";
				printf("<p><font color=$color>Invalid rate effective date '$rate_eff_date' at line number $line_no in input file. Format required: mm/dd/yyyy</font></p><br>" );
				exit;
   			}
			
			if ($prv_r_eff_date != $rate_eff_date )
			{
				$r_eff_date_counter ++;
				$prv_r_eff_date = $rate_eff_date;
			}

		} // while loop        
		
		// only one seniority effective date is allowed
		if ($s_eff_date_counter > 1)
		{
			$color="red";
			printf("<p><font color=$color>Muliple seniority effective dates found in input file</font></p><br>" );
			exit;
		}
		
		// only one rate effective date is allowed
		if ($r_eff_date_counter > 1)
		{
			$color="red";
			printf("<p><font color=$color>Muliple rate effective dates found in input file</font></p><br>" );
			exit;
		}
		
		/*
		// Ccmmit transactions
		ora_commit($conn);
		*/
		
  	} // if level 1
  
	/*
	ora_close($cursor);
  	ora_logoff($conn);

  	printf("Update Completed<br>");	
	*/
	
	printf("Validate completed");
	//printf($HTTP_POST_FILES['file']['name']);
	//copy($HTTP_POST_FILES['file']['tmp_name'], $HTTP_POST_FILES['file']['name']);

	//header("location: update_emp_info_process2.php");
?>


