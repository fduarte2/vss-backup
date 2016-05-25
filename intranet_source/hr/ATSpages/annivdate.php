<?
/* Adam Walter, June 2007.
*  
*	1 time use file.  Uploads a given CSV into the AT_EMPLOYEE table's anniversary field.
**************************************************************************************/


  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);


$handle = fopen("/web/web_pages/hr/ATSpages/annivdate.csv", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle);
        $temp = split(",", trim($buffer));
		$date = $temp[0];
		$emp = $temp[2];

		echo $date."   ".$emp."\n";
		$sql = "UPDATE AT_EMPLOYEE SET ANNIVERSARY_DATE = TO_DATE('".$date."', 'MM/DD/YYYY') WHERE EMPLOYEE_ID = '".$emp."'";
		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);

	}
    fclose($handle);
}