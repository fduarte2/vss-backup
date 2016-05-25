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

?>

EMPLOYEE ID, SSN, SENIORITY_PLACE, SP_EFF_DATE, RATE, RATE_EFF_DATE<br>
7290,074-66-0842, 1, 1/26/2006, 56.56, 6/12/2006<br> 
8089,222-52-7921, 2, 1/26/2006, 65.65, 6/12/2006<br> 
