<?
function htmlText($text){
	if ($text =="")
		$text = "&nbsp;";
	return $text;
}

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $title = "Director - HIRE PLAN";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

   include("connect.php");
   $conn = ora_logon("LABOR@$lcs", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $today = date('m/d/Y');

   $wDay = date('D');

   $yesterday = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));

   $friday = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 3 ,date("Y")));

   $vDate = $HTTP_POST_VARS[vDate];
  
   $sql = "select * from prod_report_date where report_date = to_date('$vDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   if (!ora_fetch($cursor)){

   }

   if ($vDate =="") $vDate = $yesterday;

   if ($wDay =="Mon" && $HTTP_SERVER_VARS["argv"][1]=="email" )
	$vDate = $friday; 

   $date_display = date('l m/d/y', strtotime($vDate));

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

   $type1[0] = "TRUCKLOADING (Service code 6220 to 6229, 6720 to 6729)";
   $type1[1] = "BACKHAUL (Service code 6110 to 6119, 6130 to 6149)";
   $type1[2] = "TERMINAL SERVICE (Service code 6520 to 6619)";
   $type1[3] = "RAIL CAR HANDLING (Service code 6310 to 6319) ";
   $type1[4] = "CONTAINER HANDLING (Service code 6410 to 6419)";
   $type1[5] = "MAINTENANCE (Service code 7200 to 7277)";
   $type1[6] = "NON REVENUE (Service code 7300 to 7399)";


?>
<form action=process.php method=post>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Productivity Report
            </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <input type=submit value="  Print  ">
            <hr>
         </p>
      </td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td><font size = 4><b>Date: <? echo $vDate ?></b></font></td>
   </tr>	
   <tr>
        <td width="1%">&nbsp;</td>
        <td></td>
   </tr>

</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <input type="hidden" name="vDate" value="<? echo $vDate ?>">



<?
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'<b>Plan</b>', 'actual'=>'<b>Actual</b>', 'aProd'=>'','budget'=>'','aProd_budget'=>'<b>Productivity</b>', 'flag'=>'');
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'Plan', 'actual'=>'Actual', 'aProd'=>'','budget'=>'','aProd_budget'=>'Productivity', 'flag'=>'');
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'Plan', 'actual'=>'Actual','aProd_budget'=>'Productivity');

   $arrHeading = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>PLT</b>', 'lab'=>'<b>Labor</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>PLT</b>', 'aProd'=>'<b>Productivity</b>', 'budget'=>'<b>Budget</b>','aProd_budget'=>'<b>- Budget</b>', 'flag'=>'Summary');
   $arrHeading = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>PLT</b>', 'lab'=>'<b>Hire</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>PLT</b>', 'aProd'=>'<b>Actual</b>', 'budget'=>'<b>Budget</b>','aProd_budget'=>'<b>Variance</b>');

   $arrCol1 = array('sup'=>array('width'=>100, 'justification'=>'left'),
                   'wing'=>array('width'=>55, 'justification'=>'left'),
                   'comm'=>array('width'=>45, 'justification'=>'center'),
                   'plan'=>array('width'=>135, 'justification'=>'center'),
                   'actual'=>array('width'=>135, 'justification'=>'center'),
                   'aProd_budget'=>array('width'=>190,'justification'=>'center'));

   $arrCol = array('sup'=>array('width'=>100, 'justification'=>'left'),
                   'wing'=>array('width'=>55, 'justification'=>'left'),
                   'comm'=>array('width'=>45, 'justification'=>'center'),
                   'num'=>array('width'=>45, 'justification'=>'center'),
                   'hours'=>array('width'=>45, 'justification'=>'center'),
                   'plt'=>array('width'=>45, 'justification'=>'center'),
		   'lab'=>array('width'=>45, 'justification'=>'center'),
                   'aHours'=>array('width'=>45, 'justification'=>'center'),
                   'aPlt'=>array('width'=>45, 'justification'=>'center'),
                   'aProd'=>array('width'=>70, 'justification'=>'center'),
                   'budget'=>array('width'=>50, 'justification'=>'center'),
                   'aProd_budget'=>array('width'=>70,'justification'=>'center')  );

   $heading1 = array();
   $heading = array();	
   array_push($heading1, $arrHeading1);
   array_push($heading, $arrHeading);

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(40,40,50,40);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $format = "Printed On: " . date('m/d/y g:i A');

   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->addText(650, 580,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Productivity Report</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i>For $date_display</i></b>", 18, $center);
   $pdf->ezSetDy(-25);

  // $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');

   $tot_rows = 0;
   $tot_num = 0;
   $tot_Hours = 0;
   $tot_Plt = 0;
   $tot_aHours = 0;
   $tot_aPlt = 0;
   $tot_budget = 0;
   $tot_avg_prod = 0;

   $sql = "select * from productivity where isplan is null and hire_date = to_date('$vDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   if (!ora_fetch($cursor)){
        $pdf->ezSetDy(-25);
        $pdf->setColor(1,0,0);
        $pdf->ezText("<b>THERE WAS NO HIRE PLAN ENTERED</b>", 14, $center);
	$pdf->ezSetDy(-25);

   }else{
     for ($i = 0; $i < 7; $i++){
	$rows = 0;
	$data = array();

        $num = 0;
	$hours = 0;
  	$plt = 0;
        $tNum =0;
        $tHours = 0;
        $tPlt = 0;
        $tBudget = 0;
        $tAHours = 0;
        $tAPlt = 0;
	$avg_prod = 0;
	$avg_budget = 0;
	$avg_prod_budget = 0;

	$sql = "select supervisor, location, num_of_hire, tot_hours, commodity_code, plts, budget, actual_hours, actual_plts, num_of_lab, sup_id from productivity where isplan is null and service_type='$type[$i]' and hire_date = to_date('$vDate','mm/dd/yyyy')  order by supervisor, commodity_code, location";
   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);

   	while (ora_fetch($cursor)){
		$sup = ora_getcolumn($cursor,0);
		$wing = ora_getcolumn($cursor, 1);
		$num = ora_getcolumn($cursor,2);
       		$hours = ora_getcolumn($cursor,3);
     		$comm = ora_getcolumn($cursor,4);
     		$plt = ora_getcolumn($cursor,5);
      		$budget = ora_getcolumn($cursor,6);
       		$aHours = ora_getcolumn($cursor,7);
      		$aPlt = ora_getcolumn($cursor,8);
		$labs = ora_getcolumn($cursor,9);
		$sup_id = ora_getcolumn($cursor,10);

		if ($aHours > 0){
			$labs = number_format($aHours / 8, 1, ".",",");
		}else{
			$labs = "";
		}
                if ($num == "") $num = 0;
                if ($hours == "") $hours = 0;
                if ($plt == "") $plt = 0;
		
		if ($aPlt == 0 || $aHours == 0){
			$aProd = 0;
		}else{
			$aProd = $aPlt / $aHours;
		}

		if ($aHours > 0 && $budget > 0){
     			$aProd_Budget = $aProd - $budget;
			if ($aProd_Budget >= 0) {
				$flag = "Looking Good";
			}else if ($aProd_Budget < 0){
				$flag = "Hire Too Large";
			}else{
				$flag = "";
			}
			
			$aProd = number_format($aProd, 2, ".",",");
		}else{
			$aProd_Budget = "";
			if ($budget ==0){
				$budget = "";
				$flag = "No Budget";
			}else{
				$flag ="";
			}
		}

		if ($aProd_Budget > 0){
			$aProd_Budget = number_format($aProd_Budget,  2, ".",",");
		}else if ($aProd_Budget < 0) {
			$aProd_Budget = "(".number_format(-$aProd_Budget,  2, ".",",").")";	
		}
		if ($aProd <> 0){
			$aProd = number_format($aProd, 2, ".",",");
		}else{
			$aProd = "";
		}
		$tNum += $num;
		$tHours += $hours;
		$tPlt += $plt;
		$tBudget += $budget;
		$tAHours += $aHours;
		$tAPlt += $aPlt;
		
		array_push($data, array('sup'=>$sup, 'wing'=>$wing, 'num'=>$num, 'hours'=>$hours, 'comm'=>$comm, 'plt'=>$plt,'budget'=>$budget, 'lab'=>$labs,'aHours'=>$aHours, 'aPlt'=>$aPlt, 'aProd'=>$aProd, 'aProd_budget'=>$aProd_Budget, 'sup_id'=>$sup_id));		
	}
	$rows = ora_numrows($cursor);
        
	if ($rows > 0){
		if ($tAHours > 0){
			$avg_prod = $tAPlt / $tAHours;
			$avg_budget = $tBudget / $rows;
                	$avg_prod_budget = $avg_prod - $avg_budget;

			if ($avg_prod_budget > 0){
				$flag = "Looking Good";
			}else if ($avg_prod_budget < 0){
				$flag = "Hire Too Large";
			}
		}
		if ($tAHours ==0) $tAHours ="";
		if ($tAPlt ==0) $tAPlt = "";
		if ($avg_prod ==0) {
			$avg_prod = "";
		}else{
			$avg_prod = number_format($avg_prod, 2, '.',',');
		}
		if ($avg_budget ==0) {
			$avg_budget ="";
		}else{
			$avg_budget = number_format($avg_budget, 2, '.',',');
		}
		if ($avg_prod_budget == 0) {
			$avg_prod_budget ="";
			$flag = "";
		}else if ($avg_prod_budget > 0) {
			$avg_prod_budget = number_format($avg_prod_budget, 2, '.',',');
		}else{
                        $avg_prod_budget = "(".number_format(-$avg_prod_budget, 2, '.',',').")";
		}
/*			
		$sql = "select count(distinct employee_id) from prod_hourly_detail where isplan is null and hire_date =to_date('$vDate','mm/dd/yyyy') $service_code_in[$i]";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $tot_labs = ora_getcolumn($cursor, 0);
                }else{
                        $tot_labs = "";
                }
*/
		if ($tAHours >0){
			$tot_labs = number_format($tAHours / 8, 2, ".",",");
		}else{
			$tot_labs = "";
		}
		
		array_push($data, array('sup'=>'<b>Sub Total</b>', 'num'=>$tNum, 'hours'=>$tHours, 'plt'=>$tPlt, 'lab'=>$tot_labs,'aHours'=>$tAHours, 'aPlt'=>$tAPlt) );
		if (count($data) > 0){ 
		
?>
<tr>
   <td width="1%">&nbsp;</td>
   <td><b><?echo $type1[$i]?></b></td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td>
      <table border="1" cellpadding="0" cellspacing="0" width=740>
        <tr>
                <td width=110 rowspan=2><b>Supervisor</b></td>
                <td width=50 rowspan=2><b>Comm</b></td>
                <td width=100 rowspan=2><b>Location</b></td>
                <td align=center colspan=3><b>Plan</b></td>
                <td align=center colspan=3><b>Actual</b></td>
                <td align=center colspan=3><b>Productivity (P/H)</b></td>
        </tr>

      	<tr>
                <td width=50 align=center><b>Hire</b></td>
                <td width=50 align=center><b>Hours</b></td>
                <td width=50 align=center><b>Plts</b></td>
                <td width=50 align=center><b>Hire</b></td>
                <td width=50 align=center><b>Hours</b></td>
                <td width=50 align=center><b>Plts</b></td>
                <td width=60 align=center><b>Actual</b></td>
                <td width=60 align=center><b>Budget</b></td>
                <td width=60 align=center><b>Variance</b></td>
	</tr>
<? for($j = 0; $j <count($data); $j++){ ?>
	<tr>
		<td><?echo htmlText($data[$j][sup])?></td>
                <td align=center><?echo htmlText($data[$j][comm])?></td>
                <td><?echo htmlText($data[$j][wing])?></td>
                <td align=right><?echo htmlText($data[$j][num])?></td>
                <td align=right><?echo htmlText($data[$j][hours])?></td>
                <td align=right><?echo htmlText($data[$j][plt])?></td>
                <td align=right><?echo htmlText($data[$j][lab])?></td>
<?if ($data[$j][aHours] <>"" && $data[$j][sup_id] <>""){ ?>
                <td align=right><a href ="lcs.php?i=<?echo $i?>&sup_id=<?echo $data[$j][sup_id]?>&comm=<?echo $data[$j][comm]?>&location=<?echo $data[$j][wing]?>&hDate=<?echo $vDate?>&sup=<?echo $data[$j][sup]?>" target="blank"><?echo htmlText($data[$j][aHours])?></a></td>
<? }else{ ?>
		<td align=right><?echo htmlText($data[$j][aHours])?></td>
<? } ?>
                <td align=right><?echo htmlText($data[$j][aPlt])?></td>
                <td align=right><?echo htmlText($data[$j][aProd])?></td>
                <td align=right><?echo htmlText($data[$j][budget])?></td>
                <td align=right><?echo htmlText($data[$j][aProd_budget])?></td>
         </tr>
<? } ?>
     </table>
   </td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td></td>
</tr>

<?
		}
/*
		$pdf->ezSetDy(-15);
		$pdf->ezText("<b>$type[$i]</b>", 14, $center);
		$pdf->ezSetDy(-15);
                $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
                $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
                $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
                $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

		$pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
*/
		$tot_rows +=$rows;
		$tot_num +=$tNum;
		$tot_Hours +=$tHours;
		$tot_Plt += $tPlt;
		$tot_aHours += $tAHours;
		$tot_aPlt += $tAPlt;
		$tot_budget += $tBudget;

	}
	}
        if ($tot_num > 0){
                $data = array();

                if ($tot_aHours > 0){
                        $tot_avg_prod = $tot_aPlt / $tot_aHours;
                        $tot_avg_budget = $tot_budget / $tot_rows;
                        $tot_avg_prod_budget = $tot_avg_prod - $tot_avg_budget;

                        if ($tot_avg_prod_budget >= 0){
                                $flag = "Looking Good";
                                $tot_avg_prod_budget = number_format($tot_avg_prod_budget, 2, '.',',');
                        }else{
                                $flag = "Hire Too Large";
                                $tot_avg_prod_budget = "(".number_format(-$tot_avg_prod_budget, 2, '.',',').")";
                        }
                }
                if ($tot_aHours ==0) $tot_aHours ="";
                if ($tot_aPlt ==0) $tot_aPlt ="";
                if ($tot_avg_prod ==0) {
                        $tot_avg_prod = "";
                }else{
                        $tot_avg_prod = number_format($tot_avg_prod, 2, ".", ",");
                }
/*
		$sql = "select count(distinct employee_id) from prod_hourly_detail where isplan is null and hire_date =to_date('$vDate','mm/dd/yyyy')";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $tot_labs = ora_getcolumn($cursor, 0);
                }else{
                        $tot_labs = "";
                }
                if ($tot_labs == 0) $tot_labs = "";
*/
		if ($tot_aHours > 0){
			$tot_labs = number_format($tot_aHours / 8,1,".",",");
		}else{
			$tot_labs = "";
		}

                array_push($data, array('sup'=>'<b>Grand Total</b>', 'num'=>$tot_num, 'hours'=>$tot_Hours, 'plt'=>$tot_Plt, 'lab'=>$tot_labs, 'aHours'=>$tot_aHours, 'aPlt'=>$tot_aPlt ));
/*
                $pdf->ezSetDy(-20);
                $pdf->ezText("<b>Grand Total</b>", 14, $center);
                $pdf->ezSetDy(-15);
                $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
*/
?>
<tr>
   <td width="1%">&nbsp;</td>
   <td><b>Grand Total</b></td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td>
      <table border="1" cellpadding="0" cellspacing="0" width=740>
        <tr>
                <td width = 110><?echo htmlText($data[0][sup])?></td>
                <td width = 50 align=center><?echo htmlText($data[0][comm])?></td>
                <td width = 100><?echo htmlText($data[0][wing])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][num])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][hours])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][plt])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][lab])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][aHours])?></td>
                <td width = 50 align=right><?echo htmlText($data[0][aPlt])?></td>
                <td width = 60 align=right><?echo htmlText($data[0][aProd])?></td>
                <td width = 60 align=right><?echo htmlText($data[0][budget])?></td>
                <td width = 60 align=right><?echo htmlText($data[0][aProd_budget])?></td>
         </tr>
       </table>
    </td>
</tr>	

<?
	}
   }
   $sql = "select * from productivity where isplan = 'N' and hire_date = to_date('$vDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   if (ora_fetch($cursor)){
        $pdf->ezSetDy(-25);
	$pdf->setColor(1,0,0);
        $pdf->ezText("<b>WORK DONE THAT WAS NOT IN THE HIRE PLAN</b>", 14, $center);

			
   
        $rows = 0;
        $data = array();

        $num = 0;
        $hours = 0;
        $plt = 0;
        $tNum =0;
        $tHours = 0;
        $tPlt = 0;
        $tBudget = 0;
        $tAHours = 0;
        $tAPlt = 0;
        $avg_prod = 0;
        $avg_budget = 0;
        $avg_prod_budget = 0;
	$wing = "";
	$comm = "";
	$budget="";
	$aPlt = "";
//        $sql = "select supervisor, location, num_of_hire, tot_hours, commodity_code, plts, budget, sum(actual_hours), sum(actual_plts) from productivity where isplan = 'N'  and hire_date = to_date('$vDate','mm/dd/yyyy') group by supervisor, commodity_code, location,num_of_hire, tot_hours, plts, budget  order by supervisor, commodity_code, location";
        $sql = "select supervisor, num_of_lab, actual_hours, sup_id from productivity where isplan = 'N'  and hire_date = to_date('$vDate','mm/dd/yyyy') order by supervisor";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        while (ora_fetch($cursor)){
//                $sup = ora_getcolumn($cursor,0);
//                $wing = ora_getcolumn($cursor, 1);
//                $num = ora_getcolumn($cursor,2);
//                $hours = ora_getcolumn($cursor,3);
//                $comm = ora_getcolumn($cursor,4);
//                $plt = ora_getcolumn($cursor,5);
//                $budget = ora_getcolumn($cursor,6);
//                $aHours = ora_getcolumn($cursor,7);
//                $aPlt = ora_getcolumn($cursor,8);
		$sup = ora_getcolumn($cursor,0);
		$labs = ora_getcolumn($cursor,1);
                $aHours = ora_getcolumn($cursor,2);
                $sup_id = ora_getcolumn($cursor,3);

		if($aHours > 0){
			$labs = number_format($aHours / 8, 2, ".",",");
		}else{
			$labs = "";
		}

		if ($num == "") $num = 0;
		if ($hours == "") $hours = 0;
		if ($plt == "") $plt = 0;

                if ($aPlt == 0 || $aHours == 0){
                        $aProd = 0;
                }else{
                        $aProd = $aPlt / $aHours;
                }

                if ($aHours > 0 && $budget > 0){
                        $aProd_Budget = $aProd - $budget;
                        if ($aProd_Budget >= 0) {
                                $flag = "Looking Good";
                       	}else if ($aProd_Budget < 0) {
                                $flag = "Hire Too Large";
                        }else{
				$flag = "";
			}

                        $aProd = number_format($aProd, 2, ".",",");
                }else{
			$flag = "";
                        $aProd_Budget = "";
                }

		if ($aProd_Budget > 0) {
			$aProd_Budget = number_format($aProd_Budget,  2, ".",",");
		}else if ($aProd_Budget < 0){
			$aProd_Budget = "(".number_format(-$aProd_Budget,  2, ".",",").")";
		}

                if ($aProd <> 0){
                        $aProd = number_format($aProd, 2, ".",",");
                }else{
                        $aProd = "";
                }
                $tNum += $num;
                $tHours += $hours;
                $tPlt += $plt;
                $tBudget += $budget;
                $tAHours += $aHours;
                $tAPlt += $aPlt;


                array_push($data, array('sup'=>$sup, 'wing'=>$wing, 'num'=>$num, 'hours'=>$hours, 'comm'=>$comm, 'plt'=>$plt,'budget'=>$budget, 'lab'=>$labs,'aHours'=>$aHours, 'aPlt'=>$aPlt, 'aProd'=>$aProd, 'aProd_budget'=>$aProd_Budget, 'sup_id'=>$sup_id));   
        }
        $rows = ora_numrows($cursor);
        if ($rows > 0){
                if ($tAHours > 0){
                        $avg_prod = $tAPlt / $tAHours;
                        $avg_budget = $tBudget / $rows;
                        $avg_prod_budget = $avg_prod - $avg_budget;

                        if ($avg_prod_budget >= 0){
                                $avg_prod_budget = number_format($avg_prod_budget, 2, '.',',');
                        }else{
                                $avg_prod_budget = "(".number_format(-$avg_prod_budget, 2, '.',',').")";
                        }
                }
                if ($tAHours ==0) $tAHours ="";
                if ($tAPlt ==0) $tAPlt = "";
                if ($avg_prod ==0) {
                        $avg_prod = "";
                }else{
                        $avg_prod = number_format($avg_prod, 2, '.',',');
                }
                if ($avg_budget ==0) {
                        $avg_budget ="";
                }else{
                        $avg_budget = number_format($avg_budget, 2, '.',',');
                }
                if ($avg_prod_budget == 0) {
                        $avg_prod_budget ="";
                }else{
                        $avg_prod_budget = number_format($avg_prod_budget, 2, '.',',');
                }
		$avg_prod_budget="";

/*
		$sql = "select count(distinct employee_id) from prod_hourly_detail where isplan = 'N' and hire_date = to_date('$vDate','mm/dd/yyyy')";
		$statement = ora_parse($cursor, $sql);
        	ora_exec($cursor);
        	if (ora_fetch($cursor)){
			$tot_labs = ora_getcolumn($cursor, 0);
		}else{
			$tot_labs = "";
		}
*/
		if ($tAHours > 0){
			$tot_labs = number_format($tAHours / 8,1,".",",");
		}else{
			$tot_labs = "";
		}
                array_push($data, array('sup'=>'<b>Tot/Avg</b>', 'num'=>$tNum, 'hours'=>$tHours, 'plt'=>$tPlt, 'lab'=>$tot_labs,'aHours'=>$tAHours, 'aPlt'=>$tAPlt, 'aProd'=>$avg_prod, 'aProd_budget'=>$avg_prod_budget, 'budget'=>$avg_budget, 'flag'=>$flag));
?>
<tr>
   <td width="1%">&nbsp;</td>
   <td>&nbsp;</td>
</tr>

<tr>
   <td width="1%">&nbsp;</td>
   <td align = center><b><font color="ff000">WORK DONE THAT WAS NOT IN THE HIRE PLAN</font></b></td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td>
      <table border="1" cellpadding="0" cellspacing="0" width=740>
        <tr>
                <td width=110 rowspan=2><b>Supervisor</b></td>
                <td width=50 rowspan=2><b>Comm</b></td>
                <td width=100 rowspan=2><b>Location</b></td>
                <td align=center colspan=3><b>Plan</b></td>
                <td align=center colspan=3><b>Actual</b></td>
                <td align=center colspan=3><b>Productivity</b></td>
        </tr>

        <tr>
                <td width=50 align=center><b>Hire</b></td>
                <td width=50 align=center><b>Hours</b></td>
                <td width=50 align=center><b>Plts</b></td>
                <td width=50 align=center><b>Hire</b></td>
                <td width=50 align=center><b>Hours</b></td>
                <td width=50 align=center><b>Plts</b></td>
                <td width=60 align=center><b>Actual</b></td>
                <td width=60 align=center><b>Budget</b></td>
                <td width=60 align=center><b>Variance</b></td>
        </tr>
<? for($j = 0; $j <count($data); $j++){ ?>
        <tr>
                <td><?echo htmlText($data[$j][sup])?></td>
                <td align=center><?echo htmlText($data[$j][comm])?></td>
                <td><?echo htmlText($data[$j][wing])?></td>
                <td align=right><?echo htmlText($data[$j][num])?></td>
                <td align=right><?echo htmlText($data[$j][hours])?></td>
                <td align=right><?echo htmlText($data[$j][plt])?></td>
                <td align=right><?echo htmlText($data[$j][lab])?></td>
<?if ($data[$j][aHours] <>""  && $data[$j][sup_id] <>""){ ?>
                <td align=right><a href ="lcs.php?i=<?echo $i?>&sup_id=<?echo $data[$j][sup_id]?>&comm=<?echo $data[$j][comm]?>&location=<?echo $data[$j][wing]?>&hDate=<?echo $vDate?>&sup=<?echo $data[$j][sup]?>" target="blank"><?echo htmlText($data[$j][aHours])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$j][aHours])?></td>
<? } ?>


                <td align=right><?echo htmlText($data[$j][aPlt])?></td>
                <td align=right><?echo htmlText($data[$j][aProd])?></td>
                <td align=right><?echo htmlText($data[$j][budget])?></td>
                <td align=right><?echo htmlText($data[$j][aProd_budget])?></td>
         </tr>
<? } ?>
     </table>
   </td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td></td>
</tr>

</form>
</table>
<?


                $tot_rows +=$rows;
                $tot_num +=$tNum;
                $tot_Hours +=$tHours;
                $tot_Plt += $tPlt;
                $tot_aHours += $tAHours;
                $tot_aPlt += $tAPlt;
                $tot_budget += $tBudget;
	}
   }
	

   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
     //        $pdf->ezStream();
   }else{

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";

	$mailTo = "gbailey@port.state.de.us,";
	$mailTo .= "ithomas@port.state.de.us,";
//        $mailTo .= "ffitzgerald@port.state.de.us,";
	$mailTo .="parul@port.state.de.us";

        $mailsubject = "Productivity Report";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: wstans@port.state.de.us,jjaffe@port.state.de.us,ddonofrio@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,rwang@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        $mailheaders .= "X-Mailer: PHP4\r\n";
        $mailheaders .= "X-Priority: 3\r\n";
        $maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        $maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"Productivity Report.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";

        mail($mailTo, $mailsubject, $Content, $mailheaders);

}

include("pow_footer.php");

?> 
