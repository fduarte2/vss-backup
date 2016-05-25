<?
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

   $type[0] = "TRUCKLOADING";
   $type[1] = "BACKHAUL";
   $type[2] = "TERMINAL SERVICE";
   $type[3] = "RAIL CAR HANDLING";
   $type[4] = "CONTAINER HANDLING";
   $type[5] = "MAINTENANCE";
   $type[6] = "NON REVENUE";
   $type[7] = "STAND BY";
   $type[8] = "OTHERS";

   $service_code_in[0] = " and service_code between 6220 and 6229 ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
   $service_code_in[2] = " and service_code between 6520 and 6619 ";
   $service_code_in[3] = " and service_code between 6310 and 6319 ";
   $service_code_in[4] = " and service_code between 6410 and 6419 ";
   $service_code_in[5] = " and service_code between 7200 and 7277 ";
   $service_code_in[6] = " and service_code between 7300 and 7399 ";
   $service_code_in[7] = " and service_code like '67__' ";
   $service_code_in[8] = " and service_code not between 6220 and 6229 
			   and service_code not between 6110 and 6119 
			   and service_code not between 6130 and 6149
			   and service_code not between 6520 and 6619 
			   and service_code not between 6310 and 6319 
			   and service_code not between 6410 and 6419 
			   and service_code not between 7200 and 7277 
			   and service_code not between 7300 and 7399 
			   and service_code not like '67__' ";

   $sDate = $HTTP_SERVER_VARS["argv"][1];
   $sDate = "01/01/2004";
   $eDate = date('m/d/Y');

   $sql = "delete from lcs_b_vs_nb_hours
           where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $sql = "delete from paid_hours_vs_labor_ticket
           where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   while ($sTime <= $eTime){
        $vDate = date('m/d/Y', $sTime);
echo $vDate."\r\n";
        //next day
        $sTime += 24*60*60;

        //check if is business day

        $dayOfWeek = date('w', strtotime($vDate));
        if ($dayOfWeek == 0 && $dayOfWeek = 6){
                $isBDay = false;
        }else{
                $sql = "select * from holiday_list where holiday_date = to_date('$vDate', 'mm/dd/yyyy')";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $isBDay = false;
                }else{
                        $isBDay = true;
                }
        }

	for ($i = 0; $i < count($type); $i++){
        	if ($isBDay){
		        $sql = "select user_id, customer_id, commodity_code, sum(bHours), sum(duration) from (
                        	select user_id, customer_id, commodity_code, service_code,
                        	((greatest(least(end_time, to_date('$vDate 12:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        	least(greatest(start_time, to_date('$vDate 08:00 AM','mm/dd/yyyy hh12:mi am')), end_time)) +
                        	(greatest(least(end_time, to_date('$vDate 05:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        	least(greatest(start_time, to_date('$vDate 01:00 PM','mm/dd/yyyy hh12:mi am')), end_time))) * 				      24 as bHours, duration
                        	from hourly_detail
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') and start_time <= end_time
                        	union all
                        	select user_id, customer_id, commodity_code, service_code,
                        	((greatest(least(end_time+1,to_date('$vDate 12:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        	least(greatest(start_time, to_date('$vDate 08:00 AM','mm/dd/yyyy hh12:mi am')), end_time+1)) +
                        	(greatest(least(end_time+1, to_date('$vDate 05:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        	least(greatest(start_time, to_date('$vDate 01:00 PM','mm/dd/yyyy hh12:mi am')), end_time+1))) 				      * 24, duration
                        	from hourly_detail
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') and start_time > end_time
                        	) a where 1 = 1 $service_code_in[$i]  
				group by user_id,  customer_id, commodity_code ";
        	}else{
                	$sql = "select user_id, customer_id, commodity_code, 0, sum(duration) from hourly_detail
                        	where  hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i]
                        	group by user_id, customer_id, commodity_code";
		}
		$statement = ora_parse($cursor, $sql);
        	ora_exec($cursor);
        	while (ora_fetch($cursor)){
                	$user_id = ora_getcolumn($cursor, 0);
                        $customer = ora_getcolumn($cursor,1);
                        $commodity = ora_getcolumn($cursor, 2);
                	$bPaid = ora_getcolumn($cursor,3);
                	$tPaid = ora_getcolumn($cursor, 4);
                	$nbPaid = $tPaid - $bPaid;

                	$sql = "insert into lcs_b_vs_nb_hours (hire_date, service_type, customer, commodity, 
				user_id, bPaid, nbPaid) values
                        	(to_date('$vDate','mm/dd/yyyy'), '$type[$i]', '$customer','$commodity',
				'$user_id', $bPaid, $nbPaid)";
                	$statement = ora_parse($cursor2, $sql);
                	ora_exec($cursor2);

        	}
        }

   	$sql = "insert into paid_hours_vs_labor_ticket (hire_date, user_id, bPaid, nbPaid)
           	select hire_date, user_id, sum(bPaid), sum(nbPaid) from lcs_b_vs_nb_hours
           	where hire_date =to_date('$vDate','mm/dd/yyyy') 
           	group by hire_date, user_id";
   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);

        if ($isBDay){
                $sql = "select user_id, sum(bHours), sum(hours) from (
                        select user_id,
                        ((greatest(least(end_time, to_date('$vDate 12:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        least(greatest(start_time, to_date('$vDate 08:00 AM','mm/dd/yyyy hh12:mi am')), end_time)) +
                        (greatest(least(end_time, to_date('$vDate 05:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        least(greatest(start_time, to_date('$vDate 01:00 PM','mm/dd/yyyy hh12:mi am')), end_time)))
                        * qty * 24
                        as bHours, hours * qty as hours
                        from labor_ticket_header h, labor_ticket t
                        where t.ticket_num = h.ticket_num and service_date = to_date('$vDate','mm/dd/yyyy') and
                        start_time <= end_time
                        union all
                        select user_id,
                        ((greatest(least(end_time + 1, to_date('$vDate 12:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        least(greatest(start_time, to_date('$vDate 08:00 AM','mm/dd/yyyy hh12:mi am')), end_time + 1)) +
                        (greatest(least(end_time + 1, to_date('$vDate 05:00 PM','mm/dd/yyyy hh12:mi am')), start_time) -
                        least(greatest(start_time, to_date('$vDate 01:00 PM','mm/dd/yyyy hh12:mi am')), end_time + 1)))
                        * qty * 24
                        as bHours, hours * qty as hours
                        from labor_ticket_header h, labor_ticket t
                        where t.ticket_num = h.ticket_num and service_date = to_date('$vDate','mm/dd/yyyy') and
                        start_time > end_time
                        ) a group by user_id";
        }else{
                $sql = "select user_id, 0, sum(hours * qty) from labor_ticket_header h, labor_ticket t
                        where t.ticket_num = h.ticket_num and service_date = to_date('$vDate','mm/dd/yyyy')
                        group by user_id";
        }

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        while (ora_fetch($cursor)){
                $user_id = ora_getcolumn($cursor, 0);
                $bTicketed = ora_getcolumn($cursor,1);
                $tTicketed = ora_getcolumn($cursor, 2);
                $nbTicketed = $tTicketed - $bTicketed;

                $sql = "select * from paid_hours_vs_labor_ticket
                        where user_id = '$user_id' and hire_date = to_date('$vDate','mm/dd/yyyy')";
                $statement = ora_parse($cursor2, $sql);
                ora_exec($cursor2);
                if (ora_fetch($cursor2)){
                        $sql = "update paid_hours_vs_labor_ticket set bTicketed = $bTicketed, nbTicketed = $nbTicketed
                                where user_id = '$user_id' and hire_date = to_date('$vDate','mm/dd/yyyy')";
                }else{
                        $sql = "insert into paid_hours_vs_labor_ticket (hire_date, user_id, bTicketed, nbTicketed) values
                                (to_date('$vDate','mm/dd/yyyy'), '$user_id', $bTicketed, $nbTicketed)";
                }
                $statement = ora_parse($cursor2, $sql);
                ora_exec($cursor2);

        }

   }
   
?>
