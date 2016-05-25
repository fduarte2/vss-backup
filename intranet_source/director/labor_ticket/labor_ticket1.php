<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $sup = $HTTP_GET_VARS[sup];
   $date = $HTTP_GET_VARS[date];
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
   $tHours = 0;

   $sql = "select distinct h.ticket_num from labor_ticket_header h, labor_ticket t, lcs_user u
           where t.ticket_num = h.ticket_num and u.user_id = h.user_id and rate_type = '$type' and
           u.user_name ='$sup' and h.service_date >= to_date('$sDate','mm/dd/yyyy') and
           h.service_date <= to_date('$eDate','mm/dd/yyyy')";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
?>
<html>
<body onBlur="window.close()">
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Labor Ticket</b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="95%" cellpadding="4" cellspacing="0">
	<tr>
	       <td><b>Ticket#</b></td>
               <td><b>ST</b></td>
               <td><b>OT</b></td>
               <td><b>DT</b></td>
               <td><b>MH</b></td>
               <td><b>DF</b></td>
 	</tr>
<?
	while (ora_fetch($cursor)){
		$ticket_num = ora_getcolumn($cursor, 0);
		$sql = "select rate_type, sum(hours) from labor_ticket where ticket_num = '$ticket_num' group by rate_type";
		$statement = ora_parse($cursor1, $sql);
   		ora_exec($cursor1);

               	$lST = 0;
          	$lOT = 0;
           	$lDT = 0;
            	$lMH = 0;
              	$lDF = 0;

		while (ora_fetch($cursor1)){

			$rType = ora_getcolumn($cursor1, 0);
			$lHours = ora_getcolumn($cursor1, 1);
			switch ($rType){
                        case "ST":
                                $lST = $lHours;
				$tST += $lST;
                                break;
                        case "OT":
                                $lOT = $lHours;
				$tOT += $lOT;
                                break;
                        case "DT":
                                $lDT = $lHours;
				$tDT += $lDT;
                                break;
                        case "MH":
                                $lMH = $lHours;
				$tMH += $lMH;
                                break;
                        case "DF":
                                $lDF = $lHours;
				$tDF += $lDF;
                	}
		}
?>
           <tr>
                <td><?echo htmlText($ticket_num) ?></td>
                <td><?echo htmlText($lST) ?></td>
                <td><?echo htmlText($lOT) ?></td>
                <td><?echo htmlText($lDT) ?></td>
                <td><?echo htmlText($lMH) ?></td>
                <td><?echo htmlText($lDF) ?></td>

           </tr>

	
<?	}?>	
	  <tr>
                <td><b>Total</b></td>
                <td><b><?echo htmlText($tST) ?></b></td>
                <td><b><?echo htmlText($tOT) ?></b></td>
                <td><b><?echo htmlText($tDT) ?></b></td>
                <td><b><?echo htmlText($tMH) ?></b></td>
                <td><b><?echo htmlText($tDF) ?></b></td>

           </tr>

        </table>
      </td>
    </tr>
  </table>	
</body>
</html>
