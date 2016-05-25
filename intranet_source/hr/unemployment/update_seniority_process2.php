<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $filename = trim($HTTP_POST_FILES['file1']['tmp_name']); 
  $sDate = $HTTP_POST_VARS['sDate'];

  $sql = "select max(start_date) from employee_seniority";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$start_date = ora_getcolumn($cursor, 0);
       
	if ( $start_date <>"" && strtotime($start_date) > strtotime($sDate))
	{
		$start_date = date('m/d/Y', strtotime($start_date));
             	printf("The latest effective date is $start_date. 
			The new update can not update seniority before the latest effective date.");
             	printf("");
          	printf("Please go back to <a href=\"update_seniority.php\">Update the Seniority of B employees</a> 
			reenty the effective date and to update it again <br />");
         	exit;

	}
  }

  if (($filename != 'none') && ($filename != '' ))
  {
      	// open the uploaded file for read
      	if ($handle = fopen("$filename", "rb")){
		$line = fgets($handle);
      		$column = split(",", $line);           
      		$num_cols = count($column);

      		for ($i=0; $i<$num_cols; $i++)            // strip whitespace and make column names uppercase
      		{
        		$column[$i] = strtoupper(trim($column[$i]));
      		}
      		// check if we got all the required columns
     		$expected_cols = array('SENIORITY', 'EMPLOYEE ID');

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
         		printf("Please modify the file and go back to <a href=\"update_seniority.php\">
				Update the Seniority of B employees</a> to update it again <br />");
         		exit;
      		}

	}

	$sql = "delete from employee_seniority where start_date = to_date('$sDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

	$sql = "update employee_seniority set end_date = to_date('$sDate','mm/dd/yyyy') - 1 
		where end_date is null";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);

	while (!feof($handle))
    	{
		$data = array();
		$fLine = fgets($handle);
		$temp = split(",", $fLine);

		for ($j=0; $j<$num_cols; $j++)
        	{
	    		$data[$column[$j]] = trim($temp[$j]);
		}
		$empId = $data['EMPLOYEE ID'];
		$seniority = $data['SENIORITY'];


		if ($empId >0){
			$empId = sprintf("%04d", $empId);
			$sql = "select seniority from employee_seniority
				where employee_id = '$empId' and end_date = to_date('$sDate','mm/dd/yyyy') - 1";	
			$statement = ora_parse($cursor, $sql);
                        ora_exec($cursor);
			if (ora_fetch($cursor)){
				$sen = ora_getcolumn($cursor, 0);
			}else{
				$sen = 0;
			}
			
			if ($sen <> $seniority){
                		$sql = "insert into employee_seniority (employee_id, seniority, start_date)
                        		values ('$empId','$seniority', to_date('$sDate','mm/dd/yyyy'))";
                	}else{
				$sql = "update employee_seniority set end_date = null
                			where employee_id = '$empId' and end_date = to_date('$sDate','mm/dd/yyyy') - 1";
			}
			$statement = ora_parse($cursor, $sql);
               		ora_exec($cursor);
			
		}
	}

  }


  ora_close($cursor);
  ora_logoff($conn);

  header("location: update_seniority.php");

?>
