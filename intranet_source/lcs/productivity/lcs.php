<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

  // All POW files need this session file included
  include("pow_session.php");

   $i = $HTTP_GET_VARS[i];
   $sup_id = $HTTP_GET_VARS[sup_id];
   $location = $HTTP_GET_VARS[location];
   $comm = $HTTP_GET_VARS[comm];
   $hDate = $HTTP_GET_VARS[hDate];
   $sup = $HTTP_GET_VARS[sup];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $tHours = 0;


   $type[0] = "TRUCKLOADING";
   $type[1] = "BACKHAUL";
   $type[2] = "TERMINAL SERVICE";
   $type[3] = "RAIL CAR HANDLING";
   $type[4] = "CONTAINER HANDLING";
   $type[5] = "MAINTENANCE";
   $type[6] = "NON REVENUE";

   $service_code_in[0] = " and (service_code between 6220 and 6229 or service_code between 6720 and 6729) ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6139 or service_code between 6140 and 6149)";
   $service_code_in[2] = " and service_code between 6520 and 6619 ";
   $service_code_in[3] = " and service_code between 6310 and 6319 ";
   $service_code_in[4] = " and service_code between 6410 and 6419 ";
   $service_code_in[5] = " and service_code between 7200 and 7277 ";
   $service_code_in[6] = " and service_code between 7300 and 7399 ";
   
?>
<html>
<body onBlur="window.close()">
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">LCS Summary</font>
            <hr>
         </p>
      </td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td colspan = 5><font size = 4><b>Date: <?echo $hDate?></b></font></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td colspan = 5><font size = 4><b>Supervisor: <?echo $sup?></b></font></td>
   </tr>
<?
   if ($i > 6 ){
        $sql = "select p.service_type, s.service_code, commodity_code, location, num_of_hire, tot_hours, plts from productivity p, service_type s where p.service_type = s.type and sup_id = '$sup_id' and hire_date = to_date('$hDate', 'mm/dd/yyyy') and (service_type is not null or service_type <> '')";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
?>
   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Hire Plan</b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="95%" cellpadding="4" cellspacing="0">

           <tr>
                <td><b>Type</b></td>
                <td><b>Service Code</b></td>
                <td><b>Commodity</b></td>
                <td><b>Location</b></td>
                <td><b>Hire</b></td>
                <td><b>Hours</b></td>
                <td><b>Plts</b></td>

           </tr>

<?
	while (ora_fetch($cursor)){
		$tHire += ora_getcolumn($cursor, 4);
		$tHours += ora_getcolumn($cursor, 5);
		$tPlts += ora_getcolumn($cursor, 6);
		

?>
           <tr>
                <td><?echo htmlText(ora_getcolumn($cursor, 0)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 1)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 2)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 3)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 4)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 5)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 6)) ?></td>
           </tr>

	
<?	}?>
           <tr>
                <td><b>Total</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?echo htmlText($tHire) ?></td>
                <td><?echo htmlText($tHours) ?></td>
                <td><?echo htmlText($tPlts) ?></td>


           </tr>
        </table>
      </td>
    </tr>


<?  }

   if ($i <= 6 ){
        $sql = "select commodity_code, service_code, location_id, employee_id, sum(duration) from prod_hourly_detail where user_id ='$sup_id' and employee_id <> user_id and location_id = '$location' and commodity_code = '$comm' and hire_date = to_date('$hDate', 'mm/dd/yyyy') $service_code_in[$i] group by commodity_code, service_code, location_id, employee_id  order by service_code, employee_id";
   }else{
        $sql = "select commodity_code, service_code, location_id, employee_id, sum(duration) from prod_hourly_detail where user_id ='$sup_id' and employee_id <> user_id and hire_date = to_date('$hDate', 'mm/dd/yyyy') and isplan = 'N' group by commodity_code, service_code, location_id, employee_id order by commodity_code, service_code, employee_id";
   }
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
?>
   <tr>
        <td width="1%">&nbsp;</td>
        <td></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>

<?  if ($i <=6){
	$title = "Paid in Hire Plan";
    }else{
        $title = "Paid Not in Hire Plan";
    } ?>
	<td><b><? echo $title?></b></td>
   </tr>

   <tr>
        <td width="1%">&nbsp;</td>
        <td>

	<table border="1" width="95%" cellpadding="4" cellspacing="0">

	   <tr>
                <td><b>Service Code</b></td>
                <td><b>Commodity</b></td>
                <td><b>Location</b></td>
                <td><b>Employee Id</b></td>
                <td><b>Hours</b></td>
	   </tr>
<?
   	while (ora_fetch($cursor)){
		$tHour += ora_getcolumn($cursor, 4);
?>
	
	   <tr>
                <td><?echo htmlText(ora_getcolumn($cursor, 1)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 0)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 2)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 3)) ?></td>
                <td><?echo htmlText(ora_getcolumn($cursor, 4)) ?></td>

	   </tr>
	
<? 	} ?>
	   <tr>
                <td><b>Total</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?echo htmlText($tHour) ?></td>

           </tr>
	</table>
      </td>
    </tr>
  </table>	
</body>
</html>
