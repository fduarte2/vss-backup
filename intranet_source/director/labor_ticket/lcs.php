<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

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
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
?>
<html>

<body onBlur="window.close()">
<table border="0" width="80%" cellpadding="4" cellspacing="0">
   <tr>
        <td width="1%">&nbsp;</td>
        <td><b>Supervisor: <?echo $sup?></b></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td>

        <table border="1" width="95%" cellpadding="4" cellspacing="0">
	<tr>
	       <td><b>Commodity</b></td>
               <td><b>REG</b></td>
               <td><b>OT</b></td>
               <td><b>DT</b></td>
               <td><b>OTHER</b></td>

 	</tr>
<?
	while (ora_fetch($cursor)){
		$comm = ora_getcolumn($cursor, 0);
		$sql = "select earning_type_id, sum(duration)
           		from hourly_detail h, commodity c, lcs_user u
           		where h.user_id = u.user_id and c.commodity_code = h.commodity_code and 
			lower(u.user_name) ='".strtolower($sup)."' and c.commodity_name = '$comm' and 
           		h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
			group by earning_type_id";

		$statement = ora_parse($cursor1, $sql);
   		ora_exec($cursor1);

               	$ST = 0;
          	$OT = 0;
           	$DT = 0;
            	$Other = 0;
     

		while (ora_fetch($cursor1)){

			$rType = ora_getcolumn($cursor1, 0);
			$lHours = ora_getcolumn($cursor1, 1);
			switch ($rType){
                        case "REG":
                                $ST = $lHours;
				$tST += $ST;
                                break;
                        case "OT":
                                $OT = $lHours;
				$tOT += $OT;
                                break;
                        case "DT":
                                $DT = $lHours;
				$tDT += $DT;
                                break;
                        case "MH":
                                $lMH = $lHours;
				$tMH += $lMH;
                                break;
                        default:
                                $Other = $lHours;
                                $tOther += $Other;
                	}
		}
?>
           <tr>
                <td><?echo htmlText($comm) ?></td>
                <td><?echo htmlText($ST) ?></td>
                <td><?echo htmlText($OT) ?></td>
                <td><?echo htmlText($DT) ?></td>
                <td><?echo htmlText($Other) ?></td>

           </tr>

	
<?	}?>	
	  <tr>
                <td><b>Total</b></td>
                <td><b><?echo htmlText($tST) ?></b></td>
                <td><b><?echo htmlText($tOT) ?></b></td>
                <td><b><?echo htmlText($tDT) ?></b></td>
                <td><b><?echo htmlText($tOhter) ?></b></td>

           </tr>

        </table>
      </td>
    </tr>
  </table>	
</body>
</html>
