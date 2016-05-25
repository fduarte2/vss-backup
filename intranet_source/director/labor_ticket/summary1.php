<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $user_id = $HTTP_GET_VARS[uId];
   $type = $HTTP_GET_VARS[type];
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
   
   $tot_bHours = 0;
   $tot_nbHours = 0;

   if ($user_id <>""){
	$uId = " and h.user_id = '$user_id' ";
   }else{
	$uId = "";
   }
 
   $sql = "select to_char(hire_date,'mm/dd/yyyy'),u.user_name,service_type,customer_name,commodity_name,sum(bPaid),sum(nbPaid)
	   from lcs_b_vs_nb_hours h,  commodity c, customer m,  lcs_user u, supervisor_type s
	   where  h.commodity = c.commodity_code and  h.user_id = u.user_id(+) and 
	   h.user_id = s.user_id and s.type = '$type'
           and h.customer = m.customer_id(+)  $uId and
	   h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
           group by to_char(hire_date,'mm/dd/yyyy'), u.user_name, service_type, customer_name, commodity_name, hire_date 
           order by hire_date, u.user_name, service_type, customer_name, commodity_name";       	   
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

?>
<html>

<!--<body onBlur="window.close()">-->
<body>
<table border="0" width="80%" cellpadding="4" cellspacing="0">
<form action = "summary_print.php" method="post">
<input type=hidden name="uId" value = "<?echo $user_id?>">
<input type=hidden name="type" value = "<?echo $type?>">
<input type=hidden name="sDate" value = "<?echo $sDate?>">
<input type=hidden name="eDate" value = "<?echo $eDate?>">

   <tr>
        <td width="1%">&nbsp;</td>
        <td><b><?echo $sup?></b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>

        <td align =left><input type = submit value = " Print "></td>
   </tr>

   <tr>
        <td width="1%">&nbsp;</td>
        
        <td align = center><font size = 5><b>Paid Hours</b></font></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="900" cellpadding="4" cellspacing="0">
	<tr>
		<td><b>Date</b></td>
                <td><b>Superisor</b></td>
	       	<td><b>Service</b></td>	
	       	<td><b>Customer</b></td>
               	<td><b>Commodity</b></td>
               	<td><b>Business Hours</b></td>
               	<td><b>Non Business Hours</b></td>
             
 	</tr>
<?
	while (ora_fetch($cursor)){
		$hDate = ora_getcolumn($cursor, 0);
		$supervisor = ora_getcolumn($cursor, 1);
                $service = ora_getcolumn($cursor, 2);
		$customer = ora_getcolumn($cursor, 3);
                $commodity = ora_getcolumn($cursor, 4);
                $bHours= ora_getcolumn($cursor, 5);
                $nbHours = ora_getcolumn($cursor, 6);
                $tot_bHours += $bHours;
		$tot_nbHours += $nbHours;
		
?>
           <tr>
		<td><?echo htmlText($hDate) ?></td>
		<td><?echo htmlText($supervisor) ?></td>
                <td><?echo htmlText($service) ?></td>
                <td><?echo htmlText($customer) ?></td>
                <td><?echo htmlText($commodity) ?></td>
                <td><?echo htmlText($bHours) ?></td>
                <td><?echo htmlText($nbHours) ?></td>
           </tr>

	
<?	}?>	
	  <tr>
                <td><b>Total</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b><?echo htmlText($tot_bHours) ?></b></td>
                <td><b><?echo htmlText($tot_nbHours) ?></b></td>

           </tr>

        </table>
      </td>
   </tr>
</form>
</table>	

</body>
</html>
