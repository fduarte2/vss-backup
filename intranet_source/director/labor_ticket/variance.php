<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $sup = $HTTP_GET_VARS[sup];
   $sDate = $HTTP_GET_VARS[sDate];
   $eDate = $HTTP_GET_VARS[eDate];
   $type = $HTTP_GET_VARS[type];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);
   $cursor2 = ora_open($conn);
   $tHours = 0;

   if ($type =='ST'){
        $earning = " earning_type_id = 'REG' ";
        $rate = " rate_type = 'ST' ";
        $title = "Straight Time Variance";
   }else{
        $earning = " earning_type_id <> 'REG' ";
        $rate = " rate_type <> 'ST' ";
        $title = "OT/DT/MH/DF Variance";
   }


   $sql = "select * from (
	   select distinct commodity_name, h.commodity_code, substr(service_code, 0, 3) as service_group
	   from hourly_detail h, commodity c, lcs_user u
	   where h.user_id = u.user_id and c.commodity_code = h.commodity_code and lower(u.user_name) ='".strtolower($sup)."'
	   and h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy') and $earning
	   union
	   select distinct commodity_name, h.commodity_code, to_char(service_group)
	   from labor_ticket_header h, commodity c, lcs_user u, labor_ticket t
	   where h.user_id = u.user_id and c.commodity_code = h.commodity_code and lower(u.user_name) ='".strtolower($sup)."'
           and h.service_date >= to_date('$sDate','mm/dd/yyyy') and h.service_date <= to_date('$eDate','mm/dd/yyyy') 
	   and t.ticket_num = h.ticket_num and $rate ) a
	   order by commodity_name, service_group";
//echo $sql;

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

?>
<html>

<body onBlur="window.close()">
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
        <td width="1%">&nbsp;</td>
        <td align = center><font size = 5><b><? echo $title ?></font></b></td>
   </tr>


   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Supervisor: <?echo $sup?></b></td>
   </tr>
  
   <tr>
       	<td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="95%" cellpadding="4" cellspacing="0">
	<tr>
	       <td><b>COMMODITY</b></td>
               <td><b>SERVICE TYPE</b></td>
	       <td>&nbsp;</td>
               <td><b>PAID HOURS</b></td>
               <td><b>LABOR TICKET HOURS</b></td>
               <td><b>VARIANCE</b></td>
 	</tr>
<?
	while (ora_fetch($cursor)){
		$comm = ora_getcolumn($cursor, 0);
                $commodity_code = ora_getcolumn($cursor, 1);
                $service = ora_getcolumn($cursor, 2);
		$sql = "select earning_type_id, sum(duration)
           		from hourly_detail h, commodity c, lcs_user u
           		where h.user_id = u.user_id and c.commodity_code = h.commodity_code and 
			lower(u.user_name) ='".strtolower($sup)."' and c.commodity_name = '$comm' and 
           		h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
			group by earning_type_id";
		
    		$sql = "select sum(duration) 
			from hourly_detail h, lcs_user u 
                        where h.user_id = u.user_id and  
                        lower(u.user_name) ='".strtolower($sup)."' and commodity_code = $commodity_code and 
                        h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
			and $earning and substr(service_code, 0, 3) = $service";

		$statement = ora_parse($cursor1, $sql);
   		ora_exec($cursor1);

		if (ora_fetch($cursor1)){
			$pHours = ora_getcolumn($cursor1, 0);
              	}else{
			$pHours = 0;
		}
            	$sql = "select sum(hours) from labor_ticket where $rate  and ticket_num in 
                    	(select distinct h.ticket_num from labor_ticket_header h, labor_ticket t, lcs_user u
                     	where t.ticket_num = h.ticket_num and u.user_id = h.user_id and 
                     	lower(u.user_name) ='".strtolower($sup)."' and h.service_date >= to_date('$sDate','mm/dd/yyyy') and 
                   	h.service_date <= to_date('$eDate','mm/dd/yyyy')
                     	and h.commodity_code = '$commodity_code' and h.service_group = $service) ";
		$statement = ora_parse($cursor2, $sql);
                ora_exec($cursor2);
		if (ora_fetch($cursor2)){
			$lHours = ora_getcolumn($cursor2, 0);
		}else{
			$lHours = 0;
		}

           	$sql = "select service_name from service where service_code like '$service%' order by service_code";
		$statement = ora_parse($cursor2, $sql);
             	ora_exec($cursor2);
		if (ora_fetch($cursor2)){
                    	$service_name = ora_getcolumn($cursor2, 0);
			$pos = strpos($service_name, "Sup");
			if ($pos > 0)  $service_name = substr($service_name, 0, $pos-1);
             	}else{
   	                $service_name = $service;
		}                      

		$tot_pHours += $pHours;
		$tot_lHours += $lHours;
		
?>
           <tr>
                <td><?echo htmlText($comm) ?></td>
		<td><?echo htmlText($service.X) ?></td>
                <td><?echo htmlText($service_name) ?></td>
                <td><?echo htmlText($pHours) ?></td>
                <td><?echo htmlText($lHours) ?></td>
                <td><?echo htmlText($pHours - $lHours) ?></td>

           </tr>

<?	
		
	}?>	
	  <tr>
                <td><b>Total</b></td>
                <td><b>&nbsp;</b></td>
                <td><b>&nbsp;</b></td>
                <td><b><?echo htmlText($tot_pHours) ?></b></td>
                <td><b><?echo htmlText($tot_lHours) ?></b></td>
                <td><b><?echo htmlText($tot_pHours - $tot_lHours) ?></b></td>

           </tr>

        </table>
      </td>
    </tr>
  </table>	
</body>
</html>
