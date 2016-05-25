<?
   // open a connection to the database server
   include("connect.php");


   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);
   $cursor_bni2 = ora_open($conn_bni);

   $conn_lcs = ora_logon("LABOR@LCS", "LABOR");
   if($conn_lcs < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   }
   $cursor_lcs = ora_open($conn_lcs);

   $conn_prod = ora_logon("APPS@PROD", "APPS");
   if($conn_prod < 1){
    	printf("Error logging on to the PROD Oracle Server: ");
    	printf(ora_errorcode($conn_prod));
    	printf("Please try later!");
    	exit;
   }
   $cursor_prod = ora_open($conn_prod);

   $sDate = "07/01/2003";
   $eDate = "10/01/2004";
//$eDate = "07/10/2003";
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   while ($sTime <= $eTime){
        $vDate = date('m/d/Y', $sTime);
echo $productivity.$vDate."\r\n";

        //next day
        $sTime += 24*60*60;

	$sql = "select c.segment10 as service_code, c.segment15 as commodity_code, 
		sum((decode(accounted_cr, null, 0, accounted_cr) - decode(accounted_dr, null, 0, accounted_dr))) as amount   
		from gl_je_lines j, gl_code_combinations c
		where j.code_combination_id = c.code_combination_id and j.status = 'P' and 
		c.segment5 between 3000 and 3999 and effective_date = to_date('$vDate','mm/dd/yyyy')
		group by c.segment10, c.segment15";
        $statement = ora_parse($cursor_prod, $sql);
        ora_exec($cursor_prod);

        while(ora_fetch($cursor_prod)){
		$service = ora_getcolumn($cursor_prod, 0);
		$commodity = ora_getcolumn($cursor_prod, 1);
		$revenue = ora_getcolumn($cursor_prod, 2);

		$sql = "insert into revenue_cost_detail (date_time, service, commodity, revenue) values
			(to_date('$vDate','mm/dd/yyyy'), $service, $commodity, $revenue)";
        	$statement = ora_parse($cursor_bni, $sql);
        	ora_exec($cursor_bni);
	}

	$sql = "select service_code, commodity_code, sum(duration) 
		from hourly_detail 
		where hire_date = to_date('$vDate','mm/dd/yyyy')
		group by service_code, commodity_code";
	$statement = ora_parse($cursor_lcs, $sql);
        ora_exec($cursor_lcs);
	
	while(ora_fetch($cursor_lcs)){
		$service = ora_getcolumn($cursor_lcs, 0);
                $commodity = ora_getcolumn($cursor_lcs, 1);
		$hours = ora_getcolumn($cursor_lcs, 2);

		if ($service =="")
			$service = 0;
		if ($commodity == "")
			$commodity = 0;
		
		$sql = "select count(*) from revenue_cost_detail 
			where date_time = to_date('$vDate','mm/dd/yyyy') and service = $service and commodity = $commodity";
		$statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
		$count = 0;
		if (ora_fetch($cursor_bni)){
			$count = ora_getcolumn($cursor_bni, 0);	
		}
		if ($count == 0){
			$sql = "insert into revenue_cost_detail (date_time, service, commodity, hours) values
                        	(to_date('$vDate','mm/dd/yyyy'), $service, $commodity, $hours)";
		}else if ($count ==1){
			$sql = "update revenue_cost_detail set hours = $hours
				where date_time = to_date('$vDate','mm/dd/yyyy') and service = $service and 
				commodity = $commodity";
		}
		$statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
	}


	$sql = "insert into revenue_cost_detail (date_time, commodity, weight, weight_unit) 
		select to_date('$vDate','mm/dd/yyyy'), commodity_code, 
		decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000, 
		'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2,sum(cargo_weight)), 
		decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF','TONS',upper(cargo_weight_unit))
                from cargo_manifest m
                where lr_num > 1000  and lr_num in
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate')
                group by commodity_code, upper(cargo_weight_unit)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	$sql = "insert into revenue_cost_detail (date_time, commodity, weight, weight_unit)
		select to_date('$vDate','mm/dd/yyyy'), m.commodity_code, 
		decode(upper(cargo_weight_unit), 'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000, 
		'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)),
                decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF', 'TONS',upper(cargo_weight_unit))
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and  
		t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                m.commodity_code <>'1272'
                group by m.commodity_code, upper(m.cargo_weight_unit)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	$sql = "update revenue_cost_detail set itg = 
		(select itg from commodity_itg where commodity_code = to_char(commodity))
		where date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	$sql = "update revenue_cost_detail set type = 
		(select type from service_code_type where service_from <= service and service_to >= service)
		where date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
 
	$sql = "update revenue_cost_detail set commodity_name = 
		(select commodity_name from commodity_profile where commodity_code = commodity)
		where date_time = to_date('$vDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

  }

  
?>
