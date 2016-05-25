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

  $emp_id = $HTTP_POST_VARS['sel_emp_id'];
  $old_ssn=$HTTP_POST_VARS['old_ssn'];
  $ssn=$HTTP_POST_VARS['SSN'];
  
  $sql = "update employee_ssn set SSN='$ssn' where employee_id='$emp_id'";
  //printf($sql . "<br>");
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  
  printf("<b>SSN was updated to " . $ssn . " from " . $old_ssn . " for Employee ID:" . $emp_id . "</b><br>");
  ora_close($cursor);
  ora_logoff($conn);
  

  //header("location: update_payrate2.php");

?>
<hr>
<a href="display_emp_ssn.php">View Whloe List</a>



