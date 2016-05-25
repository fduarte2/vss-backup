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

   $sDate = "07/01/2002";
   $eDate = "09/27/2004";
//$eDate = "07/01/2002";
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   while ($sTime <= $eTime){
        $vDate = date('m/d/Y', $sTime);
echo $productivity.$vDate."\r\n";

        //next day
        $sTime += 24*60*60;

	$sql = "insert into revenue_cost (date_time, itg)
		select distinct to_date('$vDate','mm/dd/yyyy'), itg from commodity_itg";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
 
	$sql = "select itg, sum(service_amount) from commodity_itg i, billing b 
		where i.commodity_code = to_char(b.commodity_code) and service_status = 'INVOICED' and 
		invoice_date = to_date('$vDate','mm/dd/yyyy')
		group by itg";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	while(ora_fetch($cursor_bni)){
		$itg = ora_getcolumn($cursor_bni, 0);
		$revenue = ora_getcolumn($cursor_bni, 1);
		
		$sql = "update revenue_cost set revenue = $revenue 
			where itg = '$itg' and date_time = to_date('$vDate','mm/dd/yyyy')";
        	$statement = ora_parse($cursor_bni2, $sql);
        	ora_exec($cursor_bni2);
	}
	
	$sql = "select itg, sum(hours) from commodity_itg i, productivity p
		where i.commodity_code = p.commodity and service_type in ('BACKHAUL') and
		date_time =  to_date('$vDate','mm/dd/yyyy')
		group by itg having sum(hours) > 0";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        while(ora_fetch($cursor_bni)){
                $itg = ora_getcolumn($cursor_bni, 0);
                $hours = ora_getcolumn($cursor_bni, 1);

                $sql = "update revenue_cost set unloading_hours = $hours
                        where itg = '$itg' and date_time = to_date('$vDate','mm/dd/yyyy')";

                $statement = ora_parse($cursor_bni2, $sql);
                ora_exec($cursor_bni2);
        }
 
        $sql = "select itg, sum(hours) from commodity_itg i, productivity p
                where i.commodity_code = p.commodity and 
		service_type in ('TRUCKLOADING', 'RAIL CAR HANDLING', 'CONTAINER HANDLING') and
                date_time =  to_date('$vDate','mm/dd/yyyy')
                group by itg having sum(hours) > 0";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        while(ora_fetch($cursor_bni)){
                $itg = ora_getcolumn($cursor_bni, 0);
                $hours = ora_getcolumn($cursor_bni, 1);

                $sql = "update revenue_cost set loading_hours = $hours
                        where itg = '$itg' and date_time = to_date('$vDate','mm/dd/yyyy')";
                $statement = ora_parse($cursor_bni2, $sql);
                ora_exec($cursor_bni2);
        }

        $sql = "select itg, sum(hours) from commodity_itg i, productivity p
                where i.commodity_code = p.commodity and 
		service_type NOT in ('BACKHAUL','TRUCKLOADING', 'RAIL CAR HANDLING', 'CONTAINER HANDLING') and
                date_time =  to_date('$vDate','mm/dd/yyyy')
                group by itg having sum(hours) > 0";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        while(ora_fetch($cursor_bni)){
                $itg = ora_getcolumn($cursor_bni, 0);
                $hours = ora_getcolumn($cursor_bni, 1);

                $sql = "update revenue_cost set other_hours = $hours
                        where itg = '$itg' and date_time = to_date('$vDate','mm/dd/yyyy')";
                $statement = ora_parse($cursor_bni2, $sql);
                ora_exec($cursor_bni2);
        }

	$sql = "insert into revenue_cost (date_time, itg, weight, weight_unit) 
		select to_date('$vDate','mm/dd/yyyy'), itg, 
		decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000, 
		'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2,sum(cargo_weight)), 
		decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF','TONS',upper(cargo_weight_unit))
                from cargo_manifest m, commodity_itg i
                where to_char(m.commodity_code) = i.commodity_code(+) and lr_num > 1000  and lr_num in
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate')
                group by itg, upper(cargo_weight_unit)";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	$sql = "insert into revenue_cost (date_time, itg, weight, weight_unit)
		select to_date('$vDate','mm/dd/yyyy'), itg, 
		decode(upper(cargo_weight_unit), 'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000, 
		'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)),
                decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF', TONS',upper(cargo_weight_unit))
                from cargo_tracking t, cargo_manifest m, commodity_itg i
                where to_char(m.commodity_code) = i.commodity_code(+) and lr_num < 10 and  
		t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                t.commodity_code <>'1272'
                group by itg, upper(m.cargo_weight_unit)";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);


  }
?>
