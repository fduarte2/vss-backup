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
  $reqId = $HTTP_POST_VARS[reqId];
  $empId = $HTTP_POST_VARS[empId];
  $ssn = $HTTP_POST_VARS[ssn];
  $bRate = $HTTP_POST_VARS[bRate];
  $eDate = $HTTP_POST_VARS[eDate];
  $hPay = $HTTP_POST_VARS[hPay];
  $holiday = $HTTP_POST_VARS[holiday];
  
  $reload = $HTTP_POST_VARS[reload_lcs];
  $isSaved = false;
  $empId = substr($empId, -4);

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

   if (($reqId <> "") && ($reload <> "Y")){

	$sql = "select employee_name,ssn, pay_rate, to_char(pay_period_end_date,'mm/dd/yy'),holiday_pay,
		to_char(holiday,'mm/dd/yy'), status
          	from unemployment_claim_header where request_id = $reqId";
  	ora_parse($cursor, $sql);
  	ora_exec($cursor);
	if (ora_fetch($cursor)){
		$empName = ora_getcolumn($cursor, 0);
                $ssn = ora_getcolumn($cursor, 1);
                $bRate = ora_getcolumn($cursor, 2);
                $eDate = ora_getcolumn($cursor, 3);
                $hPay = ora_getcolumn($cursor, 4);
                $holiday = ora_getcolumn($cursor, 5);
                $status = ora_getcolumn($cursor, 6);
	
  		$sql = "select to_char(date_worked,'mm/dd/yy'),wage, hours_worked, gratuities, houes_absent
         		from unemployment_claim_body where request_id = $reqId order by date_worked";
  		ora_parse($cursor, $sql);
  		ora_exec($cursor);

  		$i = 0;
  		while (ora_fetch($cursor)){
        		$hDate[$i] = ora_getcolumn($cursor, 0);
        		$pay[$i] = ora_getcolumn($cursor, 1);
        		$hour[$i] = ora_getcolumn($cursor, 2);
                        $gratuities[$i] = ora_getcolumn($cursor, 3);
        		$absent[$i] = ora_getcolumn($cursor, 4);

        		$tot_pay += $pay[$i];
        		$tot_hour += $hour[$i];
			$tot_gratuities += $gratuities[$i];
        		$tot_abHour += $absent[$i];

        		$pay[$i] = number_format($pay[$i], 2,'.',',');
        		$hour[$i] = number_format($hour[$i], 1,'.',',');
                        $gratuities[$i] = number_format($gratuities[$i], 1,'.',',');
        		$absent[$i] = number_format($absent[$i], 1,'.',',');

			array_push($data, array('hDate'=>$hDate[$i], 'pay'=>$pay[$i], 'hour'=>$hour[$i], 'gratuities'=>$gratuities[$i],'absent'=>$absent[$i]));
        		$i ++;
		}	
		$isSaved = true;
  	}
	
   }	

   if ($isSaved == false){

   	$sql = "select employee_name, employee_id from employee where substr(employee_id,4,4) = '$empId'";

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
        	$sql = "select earning_type_id, sum(duration) from hourly_detail
			where employee_id = '$emp_id' and hire_date = to_date('$hDate','mm/dd/yy')  
			group by earning_type_id";

   		$day_hour = 0;
   		$day_pay = 0; 

   		$statement = ora_parse($cursor, $sql);
   		ora_exec($cursor);
   		while (ora_fetch($cursor)){

			$eType = ora_getcolumn($cursor, 0);
			$hour = ora_getcolumn($cursor, 1);
        	
			if ($eType =="DT" ){
				$pay = $hour * $bRate * 2;
			}else if ($eType =="OT" || $eType == "HOL_OT" ||$eType == "BIRTH_OT"){
                        	$pay = $hour * $bRate * 1.5;
			}else{
                        	$pay = $hour * $bRate;
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
                        	where employee_id = '$empId' and eff_date in
                        	(select max(eff_date) from employee_seniority
                        	where eff_date <= to_date('$hDate','mm/dd/yy'))";

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

        	array_push($data, array('hDate'=>$hDate, 'pay'=>number_format($day_pay, 2,'.',','), 'hour'=>number_format($day_hour,1,'.',','), 'absent'=>number_format($abHour,1,'.',',')));

    	}
    }
    array_push($data, array('hDate'=>'TOTALS', 'pay'=>number_format($tot_pay, 2,'.',','), 'hour'=>number_format($tot_hour,1,'.',','),'gratuities'=>$tot_gratuities,'absent'=>number_format($tot_abHour,1,'.',',')));

    if ($status =="Completed"){
	$readonly = "readonly";
	$disabled = "disabled";
    }else{
	$readonly = "";
	$disabled = "";
    }	   

?>
<style>td{font:12px}</style>
<script language="javascript">
function calGratuities()
{
   var grt1 = parseFloat(document.unemployment.elements[10].value);
   var grt2 = parseFloat(document.unemployment.elements[15].value);
   var grt3 = parseFloat(document.unemployment.elements[20].value);
   var grt4 = parseFloat(document.unemployment.elements[25].value);
   var grt5 = parseFloat(document.unemployment.elements[30].value);
   var grt6 = parseFloat(document.unemployment.elements[35].value);
   var grt7 = parseFloat(document.unemployment.elements[40].value);
   
   if (isNaN(grt1))
	grt1 = 0;
   if (isNaN(grt2))
        grt2 = 0;
   if (isNaN(grt3))
        grt3 = 0;
   if (isNaN(grt4))
        grt4 = 0;
   if (isNaN(grt5))
        grt5 = 0;
   if (isNaN(grt6))
        grt6 = 0;
   if (isNaN(grt7))
        grt7 = 0;
   
   var tot = grt1+grt2+grt3+grt4+grt5+grt6+grt7;
   document.unemployment.elements[45].value = tot;	
}

function calAbsent()
{
   var abs1 = parseFloat(document.unemployment.elements[11].value);
   var abs2 = parseFloat(document.unemployment.elements[16].value);
   var abs3 = parseFloat(document.unemployment.elements[21].value);
   var abs4 = parseFloat(document.unemployment.elements[26].value);
   var abs5 = parseFloat(document.unemployment.elements[31].value);
   var abs6 = parseFloat(document.unemployment.elements[36].value);
   var abs7 = parseFloat(document.unemployment.elements[41].value);

   if (isNaN(abs1))
        abs1 = 0;
   if (isNaN(abs2))
        abs2 = 0;
   if (isNaN(abs3))
        abs3 = 0;
   if (isNaN(abs4))
        abs4 = 0;
   if (isNaN(abs5))
        abs5 = 0;
   if (isNaN(abs6))
        abs6 = 0;
   if (isNaN(abs7))
        abs7 = 0;

   var tot = abs1+abs2+abs3+abs4+abs5+abs6+abs7;
   document.unemployment.elements[46].value = tot;
}
function reload_LCS()
{
   document.unemployment.action="";
   document.unemployment.reload_lcs.value="Y";
   document.unemployment.submit();
}
</script>
<form name = "unemployment" action="save.php" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Unemployment Claim Form
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

    <table>
    <tr>
        <td width = "1%">&nbsp;</td>
        <td width = "250" >Request ID:</td>
        <td width = "400"><b><? echo $reqId ?></b></td>
        <input type = "hidden" name = "reqId" value = "<? echo $reqId ?>">
    </tr>
    <tr>
        <td width = "1%">&nbsp;</td>
	<td width = "250" >Employee Name:</td>
	<td width = "400"><b><? echo $empName ?></b></td>
        <input type = "hidden" name = "empName" value = "<? echo $empName ?>">
	<input type = "hidden" name = "empId" value = "<? echo $empId ?>">
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
        <td align="center" width = "155">TOTAL NUMBER OF HOURS ABSENT EACH DAY WHEN WORK WAS AVAILABLE</td>
	</tr>      
<?
    for($i = 0; $i < 8; $i ++){
?>
	<tr>
	<td align="center"><? echo $data[$i]['hDate'] ?></td>
	<input type = "hidden" name = "hDate[]" value = "<? echo $data[$i]['hDate'] ?>">
        <td align="center"><? echo "$".$data[$i]['pay'] ?></td>
        <input type = "hidden" name = "pay[]" value = "<? echo $data[$i]['pay'] ?>">
        <td align="center"><? echo $data[$i]['hour'] ?></td>
        <input type = "hidden" name = "hour[]" value = "<? echo $data[$i]['hour'] ?>">
        <td><input type="text" name = "gratuities[]" size = "13" value ="<? echo $data[$i]['gratuities'] ?>" onchange="javascript:calGratuities()" <?= $readonly?>></td>
        <td><input type="text" name = "absent[]" value ="<? echo $data[$i]['absent'] ?>" size = 24 onchange="javascript:calAbsent()" <?= $readonly?>></td>
	</tr>
<?
    }
?>
   </td></tr>
   </table>
	<p><center>
	<input type="submit" name="finalize" value = " Finalize " <?= $disabled?>>&nbsp;&nbsp;&nbsp;
	<input type="submit" name="print" value = "   Print   ">&nbsp;&nbsp;&nbsp;
	<input type="button" name="reload" value = " Reload " onclick="javascript:reload_LCS()" <?= $disabled?>>
	</center>
   </table>
   <input type="hidden" name=status value="<?= $status?>">
   <input type="hidden" name="reload_lcs" value="">
   <input type="hidden" name="empId" value="<?=$empId?>">
</form>

<? include("pow_footer.php"); ?>
