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
                $expected_cols = array('EMPLOYEE ID','CURRENT PAY RATE');
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
                       printf("Please modify the file and go back to <a href=\"update_payrate.php\">
                               Update the Pay Rates of ILA employees</a> to update it again <br />");
                       exit;
                }

        }

        $sql = "delete from employee_rate where eff_date = to_date('$eDate','mm/dd/yyyy')";
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
                $payrate = $data['CURRENT PAY RATE'];

             
                if (strstr($payrate,'$') == TRUE)
                {
                  $payrate = substr($payrate, 1);  
                }

                   if ($empId >0){
                    $empId = sprintf("%04d", $empId);
                    
                        $sql = "insert into employee_rate (employee_id, pay_rate, eff_date)
                                values ('$empId',$payrate, to_date('$eDate','mm/dd/yyyy'))";
                        $statement = ora_parse($cursor, $sql);
                        ora_exec($cursor);
                }
        }

  }


  ora_close($cursor);
  ora_logoff($conn);

  header("location: update_payrate.php");

?>

