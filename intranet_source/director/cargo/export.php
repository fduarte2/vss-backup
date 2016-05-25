<?php 
  // header("Content-Type: application/vnd.ms-excel; name='excel'");
  // header("Content-Disposition: attachment; filename=Export.xls");
 
   include("pow_session.php");
   $user = $userdata['username'];

   $sDate = $HTTP_GET_VARS[start_date];
   $eDate = $HTTP_GET_VARS[end_date];

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);


$excel="";

   $tot = array();
   $i = 0;
   $j = 0;
   $t = 0;
   while ($j < 9500){
        if ($j<5000){
                $incr=1000;
        } else if ($j < 6000) {
                $incr = 100;
        } else if ($j < 7000) {
                $incr = 1000;
        } else if ($j < 8000) {
                $incr = 100;
        } else {
                $incr = 500;
        }
        $i = $j;
        $j = $i + $incr;


        $sql = "SELECT COMMODITY_NAME, ROUND(SUM(CARGO_WEIGHT)/2000,0) CW, ROUND(SUM(QTY_EXPECTED),0) QTY1, ROUND(SUM(QTY2_EXPECTED),0) QTY2, QTY1_UNIT, QTY2_UNIT FROM CARGO_MANIFEST CM, COMMODITY_PROFILE COM  WHERE CM.COMMODITY_CODE=COM.COMMODITY_CODE AND CM.COMMODITY_CODE >=".$i." AND CM.COMMODITY_CODE <".$j." AND CM.CONTAINER_NUM IN (SELECT CONTAINER_NUM FROM VOYAGE_CARGO WHERE LR_NUM IN (SELECT LR_NUM FROM VOYAGE WHERE DATE_DEPARTED >= TO_DATE('".$sDate."', 'MM/DD/YYYY') AND DATE_DEPARTED <= TO_DATE('".$eDate."', 'MM/DD/YYYY'))) GROUP BY COMMODITY_NAME, QTY1_UNIT, QTY2_UNIT ORDER BY COMMODITY_NAME";


        // Run the sql
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        $sub_tot = array();             // to store running sub_tot total
        while (ora_fetch($cursor)){
                $com = trim(ora_getcolumn($cursor, 0));
                $pos=strpos($com, "-");
                if ($pos > 0){
                $com= strtoupper(substr($com, $pos+1)." (".substr($com,0,$pos).")");
        	}else{
                	$com = strtoupper($com);
        	}
                $weight = ora_getcolumn($cursor, 1);
                $qty1 = ora_getcolumn($cursor, 2);
                $qty2 = ora_getcolumn($cursor, 3);
                $unit1 = trim(ora_getcolumn($cursor, 4));
                $unit2 = trim(ora_getcolumn($cursor, 5));
                $f_weight = number_format($weight, 0, '.',',');
                $f_qty1=number_format($qty1,0,'.',',');
                $f_qty2=number_format($qty2,0,'.',',');
                if ($qty1==0){
                        $f_qty1="";
                        $unit1="";
                }

                if ($qty2==0){
                        $f_qty2="";
                        $unit2="";
                }

		// added to table
		$excel .= "<tr>";
		$excel .= "<td>".$com."</td>";
	 	$excel .= "<td>".$f_weight."</td>";
              	$excel .= "<td>".$f_qty1."</td>";
		$excel .= "<td>".$unit1."</td>";
		$excel .= "<td>".$f_qty2."</td>";
		$excel .= "<td>".$unit2."</td>";
		$excel .= "</tr>";

                //stor running sub_tot
                $sub_tot[0] += $weight;
                $sub_tot[1] += $qty1;
                $sub_tot[2] += $qty2;

	}

	if (count($sub_tot)>0){
                $tot[0] += $sub_tot[0];
                $tot[1] += $sub_tot[1];
                $tot[2] += $sub_tot[2];

                $sub_tot[0]=number_format($sub_tot[0],0,'.',',');
                $sub_tot[1]=number_format($sub_tot[1],0,'.',',');
                $sub_tot[2]=number_format($sub_tot[2],0,'.',',');

                if ($sub_tot[0]==0){$sub_tot[0]="";}
                if ($sub_tot[1]==0){$sub_tot[1]="";}
                if ($sub_tot[2]==0){$sub_tot[2]="";}

		$excel .= "<tr>";
        	$excel .= "<td><b><i>Sub Total</i></b></td>";
        	$excel .= "<td><b><i>".$sub_tot[0]."</i></b></td>";
       	 	$excel .= "<td><b><i>".$sub_tot[1]."</i></b></td>";
        	$excel .= "<td></td>";
        	$excel .= "<td><b><i>".$sub_tot[2]."</i></b></td>";
        	$excel .= "<td></td>";
        	$excel .= "</tr>";

                $excel .= "<tr>";
                $excel .= "<td></td>";
                $excel .= "<td></td>";
                $excel .= "<td></td>";
                $excel .= "<td></td>";
                $excel .= "<td></td>";
                $excel .= "<td></td>";
                $excel .= "</tr>";
	}
  }

   $sql = "SELECT COMMODITY_NAME, ROUND(SUM(CARGO_WEIGHT)/2000,0) CW, ROUND(SUM(QTY_EXPECTED),0) QTY1, ROUND(SUM(QTY2_EXPECTED),0) QTY2, QTY1_UNIT, QTY2_UNIT FROM CARGO_MANIFEST CM, COMMODITY_PROFILE COM, CARGO_TRACKING CT  WHERE CM.COMMODITY_CODE=COM.COMMODITY_CODE AND LR_NUM ='-1'  AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY') AND DATE_RECEIVED <= TO_DATE('".$eDate."', 'MM/DD/YYYY') AND CT.LOT_NUM=CM.CONTAINER_NUM GROUP BY COMMODITY_NAME,QTY1_UNIT, QTY2_UNIT ORDER BY COMMODITY_NAME";


   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();
   $sub_tot = array();             // to store running sub_tot total
   $rows = 0;
   while (ora_fetch($cursor)){
	$rows +=1;

        $com = trim(ora_getcolumn($cursor, 0));
        $pos=strpos($com, "-");
        if ($pos > 0){
                $com= strtoupper(substr($com, $pos+1)." (".substr($com,0,$pos).")");
        }else{
                $com = strtoupper($com);
        }
        $weight = ora_getcolumn($cursor, 1);
        $qty1 = ora_getcolumn($cursor, 2);
        $qty2 = ora_getcolumn($cursor, 3);
        $unit1 = trim(ora_getcolumn($cursor, 4));
        $unit2 = trim(ora_getcolumn($cursor, 5));
        $f_weight = number_format($weight, 0, '.',',');
        $f_qty1=number_format($qty1,0,'.',',');
        $f_qty2=number_format($qty2,0,'.',',');
        if ($qty1==0){
                $f_qty1="";
                $unit1="";
        }

        if ($qty2==0){
                $f_qty2="";
                $unit2="";
        }

        // added to table
	if ($rows == 1) {
		$excel .= "<tr><td><b>Tracked in Cargo</b></td></tr>";
    	}
        $excel .= "<tr>";
        $excel .= "<td>".$com."</td>";
        $excel .= "<td>".$f_weight."</td>";
        $excel .= "<td>".$f_qty1."</td>";
        $excel .= "<td>".$unit1."</td>";
        $excel .= "<td>".$f_qty2."</td>";
        $excel .= "<td>".$unit2."</td>";
        $excel .= "</tr>";

        //stor running sub_tot
        $sub_tot[0] += $weight;
        $sub_tot[1] += $qty1;
        $sub_tot[2] += $qty2;

   }

   if (count($sub_tot)>0){
        $tot[0] += $sub_tot[0];
        $tot[1] += $sub_tot[1];
        $tot[2] += $sub_tot[2];

        $sub_tot[0]=number_format($sub_tot[0],0,'.',',');
        $sub_tot[1]=number_format($sub_tot[1],0,'.',',');
        $sub_tot[2]=number_format($sub_tot[2],0,'.',',');

        if ($sub_tot[0]==0){$sub_tot[0]="";}
        if ($sub_tot[1]==0){$sub_tot[1]="";}
        if ($sub_tot[2]==0){$sub_tot[2]="";}

        $excel .= "<tr>";
        $excel .= "<td><b><i>Sub Total</i></b></td>";
        $excel .= "<td><b><i>".$sub_tot[0]."</i></b></td>";
        $excel .= "<td><b><i>".$sub_tot[1]."</i></b></td>";
        $excel .= "<td></td>";
        $excel .= "<td><b><i>".$sub_tot[2]."</i></b></td>";
        $excel .= "<td></td>";
        $excel .= "</tr>";

        $excel .= "<tr>";
        $excel .= "<td></td>";
        $excel .= "<td></td>";
        $excel .= "<td></td>";
        $excel .= "<td></td>";
        $excel .= "<td></td>";
        $excel .= "<td></td>";
        $excel .= "</tr>";


        $tot[0] = number_format($tot[0],0,'.',',');
        $tot[1] = number_format($tot[1],0,'.',',');
        $tot[2] = number_format($tot[2],0,'.',',');


        $excel .= "<tr>";
        $excel .= "<td><b>Grand Total</b></td>";
        $excel .= "<td><b>".$tot[0]."</b></td>";
        $excel .= "<td><b>".$tot[1]."</b></td>";
        $excel .= "<td></td>";
        $excel .= "<td><b>".$tot[2]."</b></td>";
        $excel .= "<td></td>";
        $excel .= "</tr>";

   }


   $table = "<TABLE border=1 CELLSPACING=1>";
   $table .= "<tr><td colspan=6 align=center><font size = 6><b>Cargo Statistics</b></font><br/><i>From ".$sDate." to ".$eDate."</i></td></tr>";
   $table .= "<tr><td><b>COMMODITY</b></td><td><b>TONNAGE</b></td><td><b>QTY1</b></td><td><b>UNIT1</b></td><td><b>QTY2</b></td><td><b>UNIT2</b></td></tr>";

   $table .= "$excel";

   $table .= "</table>";



   //export to excel
   header("Content-Type: application/vnd.ms-excel; name='excel'");
   header("Content-Disposition: attachment; filename=Export.xls");

   echo ("$table");
?>

