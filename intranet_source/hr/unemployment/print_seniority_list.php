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

  $date = $HTTP_POST_VARS['date'];

  $path = "/web/web_pages/upload/ADP_Files/";
  $fName = "seniority.csv";

  $fp = fopen($path.$fName, 'w');
  fwrite($fp, $header);

  $line = "SENIORITY, EMPLOYEE ID, EMPLOYEE NAME\r\n";
  fwrite($fp, $line);

  $sql = "select s.seniority, s.employee_id, employee_name from employee_seniority s, employee e
	  where s.employee_id = substr(e.employee_id, 4) and s.eff_date = to_date('$date', 'mm/dd/yyyy')
	  order by s.seniority";

  ora_parse($cursor, $sql);
  ora_exec($cursor);

  while (ora_fetch($cursor)){
	$seniority = ora_getcolumn($cursor, 0);
        $employee_id = ora_getcolumn($cursor, 1);
        $employee_name = ora_getcolumn($cursor, 2);
 
        list($last_name, $first_name) = split(",", $employee_name);

	if ($first_name == ""){
		$name = trim($employee_name);
	}else{
		$name = trim($first_name)." ".trim($last_name);
	}

	$line = $seniority.",".$employee_id.",".$name."\r\n";
        fwrite($fp, $line);
  }
  fclose($fp);

  ora_close($cursor);
  ora_logoff($conn);

  //  header("Location: /upload/ADP_Files/$fName");

  $filename = $path.$fName;
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Disposition: attachment; filename=$fName");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($filename));

  readfile("$filename");
  exit();

?>
