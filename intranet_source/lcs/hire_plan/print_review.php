<?

   // All POW files need this session file included
   include("pow_session.php");
   $user = $userdata['username'];
  $user_email = $userdata['user_email'];

   $today = date('m/d/Y');

   $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 1 ,date("Y")));

   $vDate = $HTTP_POST_VARS[vDate];
  

   if ($vDate =="") $vDate = $tomorrow;
   
   $date_display = date('l m/d/y', strtotime($vDate));

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   //get supervisor name
   $sql = "select user_id, user_name from lcs_user where email_address = '$user_email'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
        $sup_id = ora_getcolumn($cursor, 0);
        $user = ora_getcolumn($cursor, 1);
   }



   $arrHeading = array('type'=>'<b>Service Type</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'ftl'=>'<b>FTL</b>', 'ltl'=>'<b>LTL</b>','plt'=>'<b>PLT</b>', 'budget'=>'<b>Budget</b>','prod'=>'<b>Productivity (PLT/Man Hours)</b>','prod_budget'=>'<b>Productivity - Budget</b>', 'flag'=>'Summary');


   $arrCol = array('type'=>array('width'=>130, 'justification'=>'left'),
                   'wing'=>array('width'=>55, 'justification'=>'left'),
                   'comm'=>array('width'=>45, 'justification'=>'center'),
                   'num'=>array('width'=>45, 'justification'=>'center'),
                   'hours'=>array('width'=>45, 'justification'=>'center'),
                   'ftl'=>array('width'=>30, 'justification'=>'center'),
                   'ltl'=>array('width'=>30, 'justification'=>'center'),
                   'plt'=>array('width'=>40, 'justification'=>'center'), 
                   'prod'=>array('width'=>90, 'justification'=>'center'),
                   'budget'=>array('width'=>50, 'justification'=>'center'),
                   'prod_budget'=>array('width'=>70,'justification'=>'center'),
                   'flag' =>array('width'=>80,'justification'=>'center'));

   $heading = array();	
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
   $pdf->ezText("<b>Hire Plan</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i>For $user  $date_display</i></b>", 18, $center);
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

   $rows = 0;
   $data = array();

   $num = 0;
   $hours = 0;
   $plt = 0;
   $tNum =0;
   $tHours = 0;
   $tPlt = 0;
   $tFtl = 0;
   $tLtl = 0;
   $tAPlt = 0;
   $avg_prod = 0;
   $avg_budget = 0;
   $avg_prod_budget = 0;

   $sql = "select type, location, num_of_hire, tot_hours, commodity, plts, budget, productivity, prod_budget, num_of_ftl, num_of_ltl from hire_plan where hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$user' order by type, commodity, location";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   while (ora_fetch($cursor)){
	$type = ora_getcolumn($cursor,0);
	$wing = ora_getcolumn($cursor, 1);
	$num = ora_getcolumn($cursor,2);
       	$hours = ora_getcolumn($cursor,3);
     	$comm = ora_getcolumn($cursor,4);
     	$plt = ora_getcolumn($cursor,5);
      	$budget = ora_getcolumn($cursor,6);
       	$prod = ora_getcolumn($cursor,7);
      	$prod_budget = ora_getcolumn($cursor,8);
	$ftl = ora_getcolumn($cursor, 9);
	$ltl = ora_getcolumn($cursor, 10);

        if ($num == "") $num = 0;
       	if ($hours == "") $hours = 0;
  	if ($plt == "") $plt = 0;
	if ($ftl == "") $ftl = 0;
	if ($ltl == "")	$ltl = 0;
	
	if ($budget > 0 && $prod >= $budget ) {
		$flag = "Looking Good";
	}else if ($budget > 0 &&  $prod < $budget ){
		$flag = "Hire Too Large";
	}else{
		$flag = "";
	}

       	$tNum += $num;
    	$tHours += $hours;	
	$tPlt += $plt;
      	$tBudget += $budget;
     	$tFtl += $ftl;
  	$tLtl += $ltl;
			
		
	array_push($data, array('type'=>$type, 'wing'=>$wing, 'num'=>$num, 'hours'=>$hours, 'comm'=>$comm, 'plt'=>$plt,'budget'=>$budget, 'prod'=>$prod,'prod_budget'=>$prod_budget,'flag'=>$flag, 'ftl'=>$ftl, 'ltl'=>$ltl));		
	}
	$rows = ora_numrows($cursor);
        
	if ($rows > 0){
		if ($tHours > 0){
			$avg_prod = $tPlt / $tHours;
			$avg_budget = $tBudget / $rows;
                	$avg_prod_budget = $avg_prod - $avg_budget;

			if ($avg_prod_budget > 0){
				$flag = "Looking Good";
			}else if ($avg_prod_budget < 0){
				$flag = "Hire Too Large";
			}
		}
		if ($tFtl ==0) $tFtl ="";
		if ($tLtl==0) $tLtl = "";
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
			
		array_push($data, array('type'=>'<b>Tot/Avg</b>', 'num'=>$tNum, 'hours'=>$tHours, 'plt'=>$tPlt, 'prod'=>$avg_prod, 'prod_budget'=>$avg_prod_budget, 'budget'=>$avg_budget, 'flag'=>$flag,'ftl'=>$tFtl, 'ltl'=>$tLtl));

		$pdf->ezSetDy(-15);
                $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
                $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
                $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

		$pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));

		$pdf->ezStream();

	}
	



?> 
