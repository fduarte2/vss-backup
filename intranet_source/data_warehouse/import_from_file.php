<?
  $path = "/web/web_pages/upload/";	 
  $basefile[0] = "emp_rate.csv"; 

  
  //get connect
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
        printf("Error logging on to the lcs Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   ora_commitoff($conn);
   $cursor = ora_open($conn);

$j = 0;
for($i = 0; $i < count($basefile); $i++){
  //parse file
  if ($handle = fopen ($path.$basefile[$i], "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		list($sen, $id,$ssn, $rate) = split(",",$fLine);
		
		$sql = "insert into employee_rate (employee_id, pay_rate, eff_date)
			 values ('$id',$rate, to_date('05/01/2005','mm/dd/yyyy'))";
              	$statement = ora_parse($cursor, $sql);
               	ora_exec($cursor);
/*
                $sql = "insert into employee_seniority (employee_id, seniority)
                         values ('$id','$sen')";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
*/
	}

  }
}

?>

