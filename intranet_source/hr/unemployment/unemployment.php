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

  //get parmeters
  $empId = $HTTP_POST_VARS[empId];
  $ssn = $HTTP_POST_VARS[ssn];
  $bRate = $HTTP_POST_VARS[rate];
  $eDate = $HTTP_POST_VARS[end_date];
  $hPay = $HTTP_POST_VARS[hPay];
  $holiday = $HTTP_POST_VARS[holiday];
  
  list($m, $d, $y) = split("/", $eDate);
  
  $arrHeading2 = array('hDate'=>'<b>DATES</b>','pay'=>'<b>GROSS WAGES EARNED EACH DAY</b>','hour'=>'<b>TOTAL NUMBER OF HOURS WORKED EACH DAY</b>','gratuities'=>'<b>GRATUITIES</b>','absent'=>'<b>TOTAL NUMBER OF HOURS ABSENT EACH DAY WHEN WORK WAS AVAILABLE</b>');
  $arrCol2 = array('hDate'=>array('width'=>100, 'justification'=>'center'),
                   'pay'=>array('width'=>100, 'justification'=>'center'),
                   'hour'=>array('width'=>100, 'justification'=>'center'),
                   'gratuities'=>array('width'=>100, 'justification'=>'center'),
                   'absent'=>array('width'=>110, 'justification'=>'center'));
  $data = array();
 

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

   $sql = "select employee_name, employee_id from employee where substr(employee_id,4,4) = '".substr($empId, -4)."'";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
   	$empName = ora_getcolumn($cursor, 0);
	$emp_id = ora_getcolumn($cursor, 1);
   }

   $tot_hour = 0;
   $tot_pay = 0;

  list($m, $d, $y) = split("/", $eDate);
  $eDate = date('m/d/y', mktime(0,0,0,$m,$d,$y));

   for($i = 0; $i < 7; $i++)
   {
	$hDate = date('m/d/y',mktime(0,0,0,$m,$d - 6 + $i ,$y));

   	$sql = "select ceridian_pay_hour, ceridian_pay_code, ceridian_rate_code,ceridian_pay_rate from ceridian_pay_detail  where employee_id = '$emp_id' and hire_date = to_date('$hDate','mm/dd/yy')  "; 

   	$day_hour = 0;
   	$day_pay = 0; 

   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);
   	while (ora_fetch($cursor)){

		$hour = ora_getcolumn($cursor, 0);
        	$pay_code = ora_getcolumn($cursor, 1);
       		$rate_code = ora_getcolumn($cursor, 2);
  		$pay_rate = ora_getcolumn($cursor, 3);


		if ($hour =="") $hour = 0;

		if ($rate_code =="R" || $rate_code =="P" )
		{
			$rate = $pay_rate;
		} 
		else
		{
			$rate = $bRate;
		}
		
        	if ($rate_code =="E")
		{
			$pay =  $pay_rate;
		}
		else if (substr($pay_code, 0, 1) == "5")
		{
			$pay = $hour * $rate * 1.5;
		}
		else if (substr($pay_code, 0, 1) == "6")
		{
			$pay = $hour * $rate * 2.0;
		}
		else
		{
			$pay = $hour * $rate;
		}
	
		$day_hour += $hour;
		$day_pay += $pay;
    	}
	$tot_hour += $day_hour;
	$tot_pay += $day_pay;

        //get number of hours absent each day when work was available
	$avHour = 0;
	$abHour = 0;
        $sql = "select cutoff from employee_hire_cutoff where hire_date = to_date('$hDate','mm/dd/yy')";
   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);
   	if (ora_fetch($cursor)){
		$cutoff = ora_getcolumn($cursor, 0);
	}else{
		$cutoff = ""; 
	}

	if ($cutoff <>""){
		$sql = "select seniority from employee_seniority 
			where employee_id = '$emp_id' and eff_date in
			(select max(eff_date) from employee_seniority 
			where eff_date <= to_date('$hDate','mm/dd/yy')";
		$statement = ora_parse($cursor, $sql);
        	ora_exec($cursor);
        	if (ora_fetch($cursor)){
			$seniority = ora_getcolumn($cursor, 0);
			if ($cutoff >= $seniority){
				$avHour = 8;
			}
		}
	}

	if ($avHour > 0){
		$abHour = $avHour - $day_hour;
		if ($abHour < 0)
			$abHour = 0;
	}	
	$tot_abHour += $abHour;

  	array_push($data, array('hDate'=>$hDate, 'pay'=>'$'.number_format($day_pay, 2,'.',','), 'hour'=>number_format($day_hour,1,'.',','), 'absent'=>number_format($abHour,1,'.',',')));
  	
    }	
    array_push($data, array('hDate'=>'TOTALS', 'pay'=>'$'.number_format($tot_pay, 2,'.',','), 'hour'=>number_format($tot_hour,1,'.',','),'absent'=>number_format($tot_abHour,1,'.',',')));

?>
<style>td{font:12px}</style>
<form name = "unemployment" action="print.php" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Unemployment Claim
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

    <table>
    <tr>
        <td width = "1%">&nbsp;</td>
	<td width = "250" >Employee Name:</td>
	<td width = "400"><b><? echo $empName ?></b></td>
        <input type = "hidden" name = "empName" value = "<? echo $empName ?>">
    </tr>  
    <tr>  
        <td width = "1%">&nbsp;</td>
        <td>Social Security Number:</td>
        <td><b><? echo $ssn ?></b></td> 
        <input type = "hidden" name = "ssn" value = "<? echo $ssn ?>">
    </tr>
    <tr>  
        <td width = "1%">&nbsp;</td>
        <td>Employee Gross Hourly Rate:</td>
        <td><b><? echo '$'.number_format($bRate, 2, '.',',') ?></b></td>
        <input type = "hidden" name = "bRate" value = "<? echo $bRate ?>"> 
    </tr>
    <tr>  
        <td width = "1%">&nbsp;</td>
        <td>Pay Period Ending:</td>
        <td><b><? echo $eDate ?></b></td> 
        <input type = "hidden" name = "eDate" value = "<? echo $eDate ?>">
    </tr>
    <tr>
        <td width = "1%">&nbsp;</td>
        <td>Holiday Pay During Pay Period:</td> 
        <td><b><? echo '$'.number_format($hPay,2,'.',',') ?></b></td> 
        <input type = "hidden" name = "hPay" value = "<? echo $hPay ?>"> 
    </tr>
    <tr>
        <td width = "1%">&nbsp;</td>
        <td>Holiday Date:</td>
        <td><b><? echo $holiday ?></b></td>
        <input type = "hidden" name = "holiday" value = "<? echo $holiday ?>">
    </tr>
  

    <tr> <td width = "1%">&nbsp;</td><td colspan = 2>
    <table border="1" cellpadding="0" cellspacing="0">	
        <tr>
	<td align="center" width = "80">DATES</td>
        <td align="center" width = "120">GROSS WAGES EARNED EACH DAY</td>
        <td align="center" width = "120">TOTAL NUMBER OF HOURS WORKED EACH DAY</td>
        <td align="center" width = "95">GRATUITIES</td>
        <td align="center" width = "155">TOTAL NUMBER OF HOURS ABSENT EACH DAY WHEN WORK WAS AVAILABL</td>
	</tr>      
<?
    for($i = 0; $i < 8; $i ++){
?>
	<tr>
	<td align="center"><? echo $data[$i]['hDate'] ?></td>
	<input type = "hidden" name = "hDate[]" value = "<? echo $data[$i]['hDate'] ?>">
        <td align="center"><? echo $data[$i]['pay'] ?></td>
        <input type = "hidden" name = "pay[]" value = "<? echo $data[$i]['pay'] ?>">
        <td align="center"><? echo $data[$i]['hour'] ?></td>
        <input type = "hidden" name = "hour[]" value = "<? echo $data[$i]['hour'] ?>">
        <td><input type="text" name = "gratuities[]" size = "13" value ="<? echo $data[$i]['gratuities'] ?>"></td>
        <td><input type="text" name = "absent[]" value ="<? echo $data[$i]['absent'] ?>" size = 24></td>
	</tr>
<?
    }
?>
   </td></tr>
   </table>
	<p><center><input type="submit" value = "   Print   "></center>
   </table>
</form>

<? include("pow_footer.php"); ?>
