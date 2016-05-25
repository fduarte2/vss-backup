<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  $date = $HTTP_POST_VARS['date'];
  $cutoff = $HTTP_POST_VARS['cutoff'];

  $year = date('Y', strtotime($date));
  $month = date('m',strtotime($date));

  if ($HTTP_POST_VARS['cancel']<>""){
	header("location: hire_cutoff.php?year=$year&month=$month");	
	exit;
  }


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


  if ($cutoff == ""){
        $sql = "delete from employee_hire_cutoff where hire_date = to_date('$date','mm/dd/yyyy')";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
  }else if (is_numeric($cutoff)){
  	$sql = "delete from employee_hire_cutoff where hire_date = to_date('$date','mm/dd/yyyy')";
  	$statement = ora_parse($cursor, $sql);
  	ora_exec($cursor);

  	$sql = "insert into employee_hire_cutoff (hire_date, cutoff) values (to_date('$date','mm/dd/yyyy'), $cutoff)";
  	$statement = ora_parse($cursor, $sql);
  	ora_exec($cursor);
  }
  ora_close($cursor);
  ora_logoff($conn);

  header("location: hire_cutoff.php?year=$year&month=$month");

?>
