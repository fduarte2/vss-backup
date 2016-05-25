<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  include("utility.php");
  
  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");
  //$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }

  $cursor = ora_open($conn);

  $filename = trim($HTTP_POST_FILES['file1']['tmp_name']);
  $eDate = $HTTP_POST_VARS['eDate'];


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
                $expected_cols = array('EMPLOYEE ID','SSN');
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
                $ssn = $data['SSN'];

                if ($empId >0){


                	$empId = sprintf("%04d", $empId);
			$ssn=trim($ssn);
                        $sql = "insert into employee_ssn values ('$empId','$ssn')"; 
 			$statement = ora_parse($cursor, $sql);
                        ora_exec($cursor);

			printf("<b>Employee ID:" . $empId . " " . "SSN:" . $ssn . " added<b><br>");

                }
        }

  }


  ora_close($cursor);
  ora_logoff($conn);


?>
<hr>
<a href="display_emp_ssn.php">View Whloe List</a>



