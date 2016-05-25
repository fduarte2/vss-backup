<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $user_id = $HTTP_GET_VARS[uId];
   $sup = $HTTP_GET_VARS[sup];
   $sDate = $HTTP_GET_VARS[sDate];
   $eDate = $HTTP_GET_VARS[eDate];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }

   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);
   $tHours = 0;

   $sql = "select distinct commodity_name
	   from hourly_detail h, commodity c, lcs_user u
	   where h.user_id = u.user_id and c.commodity_code = h.commodity_code and lower(u.user_name) ='".strtolower($sup)."'
	   and h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')";

   $sql = "select commodity_name, service_name, customer_name, to_char(start_time, 'hh12:mi PM'), 
	   to_char(end_time, 'hh12:mi PM'), duration, employee_name, to_char(hire_date, 'mm/dd/yy')
	   from hourly_detail h, commodity c, employee e, customer m,
	   (select * from service where (service_code like '6___' and status ='N') or service_code not like '6___') s
	   where h.commodity_code = c.commodity_code and h.service_code = s.service_code and h.employee_id = e.employee_id
	   and h.customer_id = m.customer_id(+) and user_id = '$user_id' and
	   h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
	   order by hire_date, service_name, commodity_name";
 
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $sql = "select to_char(service_date, 'mm/dd/yy'), h.ticket_num, customer_name, commodity_name, service_group, 
	   labor_type, to_char(start_time, 'hh12:mi PM'), to_char(end_time, 'hh12:mi PM'), qty, qty*hours
           from labor_ticket_header h, labor_ticket t, commodity c, customer m
	   where h.ticket_num = t.ticket_num and h.commodity_code = c.commodity_code and h.customer_id = m.customer_id and
	   h.user_id = '$user_id' and service_date >= to_date('$sDate','mm/dd/yyyy') and 
    	   service_date <= to_date('$eDate','mm/dd/yyyy')
	   order by service_date, h.ticket_num";
   $statement = ora_parse($cursor1, $sql);
   ora_exec($cursor1);

?>
<html>

<body onBlur="window.close()">
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
        <td width="1%">&nbsp;</td>
        <td><b><?echo $sup?></b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Paid Hours:</b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="900" cellpadding="4" cellspacing="0">
	<tr>
		<td><b>Date</b></td>
	       	<td><b>Service</b></td>	
	       	<td><b>Commodity</b></td>
               	<td><b>Customer</b></td>
               	<td><b>Employee</b></td>
               	<td width = 60><b>Start</b></td>
               	<td width = 60><b>End</b></td>
               	<td><b>Hours</b></td>
 	</tr>
<?
	while (ora_fetch($cursor)){
		$commodity = ora_getcolumn($cursor, 0);
		$service = ora_getcolumn($cursor, 1);
		$customer = ora_getcolumn($cursor, 2);
                $start = ora_getcolumn($cursor, 3);
                $end= ora_getcolumn($cursor, 4);
                $hours = ora_getcolumn($cursor, 5);
                $emp = ora_getcolumn($cursor, 6);
		$hDate = ora_getcolumn($cursor, 7);
  		$tot_hours +=$hours;
		
?>
           <tr>
		<td><?echo htmlText($hDate) ?></td>
                <td><?echo htmlText($service) ?></td>
                <td><?echo htmlText($commodity) ?></td>
                <td><?echo htmlText($customer) ?></td>
                <td><?echo htmlText($emp) ?></td>
                <td><?echo htmlText($start) ?></td>
                <td><?echo htmlText($end) ?></td>
                <td><?echo htmlText($hours) ?></td>

           </tr>

	
<?	}?>	
	  <tr>
                <td><b>Total</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b><?echo htmlText($tot_hours) ?></b></td>

           </tr>

        </table>
      </td>
   </tr>
</table>	
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
        <td width="1%">&nbsp;</td>
        <td></td>
   </tr>

   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Labor Ticket:</b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="900" cellpadding="4" cellspacing="0">
        <tr>
                <td><b>Date</b></td>
                <td><b>Ticket#</b></td>
		<td><b>Service</b></td>
                <td><b>Commodity</b></td>
                <td><b>Customer</b></td>
                <td><b>Labor Type</b></td>
		<td><b>Qty</b></td>
                <td width = 60><b>Start</b></td>
                <td width = 60><b>End</b></td>
                <td><b>Hours</b></td>
        </tr>
<?
        while (ora_fetch($cursor1)){
		$date = ora_getcolumn($cursor1, 0);
		$ticket_num = ora_getcolumn($cursor1, 1);
                $customer = ora_getcolumn($cursor1, 2);
                $commodity = ora_getcolumn($cursor1, 3);
                $service = ora_getcolumn($cursor1, 4);
                $lType = ora_getcolumn($cursor1, 5);
                $start = ora_getcolumn($cursor1, 6);
                $end= ora_getcolumn($cursor1, 7);
                $qty = ora_getcolumn($cursor1, 8);
                $hours = ora_getcolumn($cursor1, 9);
   
                $t_hours +=$hours;

                $sql = "select service_name from service where service_code like '$service%' order by service_code";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $service_name = ora_getcolumn($cursor, 0);
                        $pos = strpos($service_name, "Sup");
                        if ($pos > 0)  $service_name = substr($service_name, 0, $pos-1);
			$pos = strpos($service_name, "-");
			if ($pos > 0)  $service_name = substr($service_name, $pos+1);
                }else{
                        $service_name = $service;
                }

?>
           <tr>
                <td><?echo htmlText($date) ?></td>
                <td><?echo htmlText($ticket_num) ?></td>
                <td><?echo htmlText($service_name) ?></td>
                <td><?echo htmlText($commodity) ?></td>
                <td><?echo htmlText($customer) ?></td>
                <td><?echo htmlText($lType) ?></td>
                <td><?echo htmlText($qty) ?></td>
                <td><?echo htmlText($start) ?></td>
                <td><?echo htmlText($end) ?></td>
                <td><?echo htmlText($hours) ?></td>

           </tr>


<?      }?>
          <tr>
                <td><b>Total</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b><?echo htmlText($t_hours) ?></b></td>

           </tr>

        </table>
      </td>
   </tr>
</table>



</body>
</html>
