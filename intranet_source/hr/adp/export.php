<?
  include("pow_session.php");
  
  //get parmeters
  $sDate = $HTTP_POST_VARS[start_date];
  $eDate = $HTTP_POST_VARS[end_date];

  $co_code = "5ZE";
  list($m, $d, $y) = split("/", $sDate);
  $batch_id = $m.$d.substr($y,2,2);

//  $header = "Co Code,Batch ID,File #,Pay #,Tax Frequency,Temp Cost Number,Temp Dept,Reg Hours,Temp Rate,O/T Hours,Hours 3 Code,Hours 3 Amount,Reg Earnings,Earnings 3 Code, Earnings 3 Amount\r\n";
  $header = "Co Code,Batch ID,File #,Pay #,Tax Frequency,Temp Cost Number,Reg Hours,Temp Rate,O/T Hours,Hours 3 Code,Hours 3 Amount,Reg Earnings,Earnings 3 Code, Earnings 3 Amount\r\n";

  //get DB connection
  $conn = ora_logon("LABOR@LCS", "LABOR");

  if($conn < 1){
    	printf("Error logging on to the LCS Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $export_cursor = ora_open($conn);

   $path = "/web/web_pages/upload/ADP_Files/";
//   $path = "/web/web_pages/TS_Testing/";
//   $fName = "PR5ZEEPI.CSV";  // for single file imports
   $fName = "EPI5ZELC.CSV";  // for importing in tandem with ATS system

   $fp = fopen($path.$fName, 'w');
   fwrite($fp, $header);

//				--and --EMP_TYPE-- != 'SUPV' -----OR--- entry in this -approved- table for the week--
   $sql = "select c.employee_id, c.ceridian_pay_hour, c.ceridian_rate_code, c.ceridian_pay_rate, c.ceridian_service_code, a.adp_pay_code 
			from ceridian_pay_detail c, adp_pay_code_conversion a 
			where c.ceridian_pay_code = a.ceridian_pay_code(+) 
				and hire_date >=to_date('$sDate','mm/dd/yyyy') 
				and hire_date <=to_date('$eDate','mm/dd/yyyy')
			order by hire_date, employee_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
	$earning_type = "";
	$earning_amt = "";
	$reg_earning = "";

	$emp_id = ora_getcolumn($cursor, 0);
        $pay_hour = ora_getcolumn($cursor, 1);
       	$rate_code = ora_getcolumn($cursor, 2);
  	$pay_rate = ora_getcolumn($cursor, 3);
	$service_code = ora_getcolumn($cursor, 4);
	$pay_code = ora_getcolumn($cursor, 5);

	$file_nbr = sprintf("%04d", substr($emp_id, -4));

       	$job_cost = "00".substr($service_code, 0,4)."-".substr($service_code, 4,4)."-".substr($service_code, 8,4)."-".substr($service_code, 12, 2);
//        $dept = "00".substr($service_code, 0,4);
//        $dept = substr($service_code, 0,4);
//		for($i = strlen($dept); $i < 6; $i++){
//			$dept = "0".$dept;
//		}

//	if ($rate_code =="#") $rate_code = "";

        if ($pay_code =="REGULAR") {
		$reg_hours = $pay_hour;
		$ot_hours = "";
		$pay_hour = "";
		$pay_code = "";
	}elseif ($pay_code =="OVERTIME"){
		$reg_hours = "";
		$ot_hours = $pay_hour;
		$pay_hour = "";
		$pay_code = "";
	}else{
		$reg_hours = "";
		$ot_hours = "";
	}

	if ($rate_code =="E"){
		if ($pay_code =="H"){//Hazard pay
			$earning_type = $pay_code;
			$earning_amt = $pay_rate;
			$pay_code = "";
			$pay_rate = "";
		}else if ( substr($service_code, 12, 2) == "01" || substr($service_code, 12, 2) == "02" || substr($service_code, 12, 2) == "03"){//shift diffence
			$earning_type = "Q";
			$earning_amt = $pay_rate;
			$pay_rate = "";
		}else if (substr($service_code, 12, 2) == "50"){ // freezer pay
			$earning_type = "Z";
                        $earning_amt = $pay_rate;
                        $pay_rate = "";			
		}else{
			$reg_earning = $pay_rate;
			$pay_rate = "";
		}
	}
	$line = $co_code.",".$batch_id.",".$file_nbr.",1,,".$job_cost.",".$reg_hours.",".$pay_rate.",".$ot_hours.",".$pay_code.",".$pay_hour.",".$reg_earning.",".$earning_type.",".$earning_amt."\r\n";
//	$line = $co_code.",".$batch_id.",".$file_nbr.",1,,".$job_cost.",".$dept.",".$reg_hours.",".$pay_rate.",".$ot_hours.",".$pay_code.",".$pay_hour.",".$reg_earning.",".$earning_type.",".$earning_amt."\r\n";

	// 1/9/2015.  SUPV now needs their time "approved" by tazzy before getting into this file.
	$write_line = false;
	$sql = "SELECT EMPLOYEE_TYPE_ID
			FROM EMPLOYEE
			WHERE EMPLOYEE_ID = '".$emp_id."'";
	$statement = ora_parse($export_cursor, $sql);
	ora_exec($export_cursor);
	ora_fetch($export_cursor);
	if(ora_getcolumn($export_cursor, 0) != "SUPV"){
		$write_line = true;
	} else {
		$sql = "SELECT ACTION_STATUS, DATE_OF_ACTION, ACTION_BY, TO_CHAR(DATE_OF_ACTION, 'MM/DD/YYYY HH24:MI:SS') THE_ACT, REJECT_REASON
				FROM HR_SUPV_APPROVES
				WHERE SUPV_ID = '".$emp_id."'
					AND TO_CHAR(WEEK_END_OF_HIRE, 'MM/DD/YYYY') = '".$eDate."'
				ORDER BY DATE_OF_ACTION DESC";
		$statement = ora_parse($export_cursor, $sql);
		ora_exec($export_cursor);
		if(ora_fetch($export_cursor) && ora_getcolumn($export_cursor, 0) == "Approved"){
			$write_line = true;
		}
	}

	if($write_line){
		fwrite($fp, $line);
	}
   }
   fclose($fp);
   

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
