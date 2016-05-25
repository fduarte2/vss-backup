<?
   include("pow_session.php");
   $user = $userdata['username'];
	
   $sDate = $HTTP_POST_VARS[start_date];
   $eDate = $HTTP_POST_VARS[end_date];


   if (isset($HTTP_POST_VARS[export])){
      header("Location: export.php?start_date=$sDate&end_date=$eDate");
   }

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $today = date('F j, Y');
   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);

   $pdf->ezSetMargins(40,40,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $pdf->ezStartPageNumbers(300, 20, 8, '','',1);
   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Cargo Statistics</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<i>From $sDate to $eDate</i>", 12, $center);
   $pdf->ezSetDy(-15);

   $today = date('m/j/y');
   $format = "Printed On: " . $today;
 //  $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
 // $pdf->line(45,30,555,30);
   $pdf->addText(490,760,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');


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



   	
   	$data = array();
   	$sub_tot = array();		// to store running sub_tot total
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



      		// added to $data for printing
      		array_push($data, array('comm'=>$com, 'weight'=>$f_weight, 'count1'=>$f_qty1,'unit1'=>$unit1,'count2'=>$f_qty2, 'unit2'=>$unit2));
		
		//stor running sub_tot
		$sub_tot[0] += $weight;
		$sub_tot[1] += $qty1;
		$sub_tot[2] += $qty2;
      
 
	}
   	// Data array created
	if (count($data)>0){
		$t += 1;
		if ($t ==1){
			$h = 1;
		}else{
			$h = 0;
		}
		$tot[0] += $sub_tot[0];
                $tot[1] += $sub_tot[1];
                $tot[2] += $sub_tot[2];
		
		$sub_tot[0]=number_format($sub_tot[0],0,'.',',');
		$sub_tot[1]=number_format($sub_tot[1],0,'.',',');
		$sub_tot[2]=number_format($sub_tot[2],0,'.',',');

	        if ($sub_tot[0]==0){$sub_tot[0]="";}
        	if ($sub_tot[1]==0){$sub_tot[1]="";}
        	if ($sub_tot[2]==0){$sub_tot[2]="";}

		$sub = array();
		array_push($sub, array('comm'=>'Sub Total', 'weight'=>$sub_tot[0], 'count1'=>$sub_tot[1],'unit1'=>'','count2'=>$sub_tot[2], 'unit2'=>''));
	   	$pdf->ezTable($data, array('comm'=>'COMMODITY', 'weight'=>'TONNAGE', 'count1'=>'QTY1', 'unit1'=>'UNIT1','count2'=>'QTY2','unit2'=>'UNIT2'), '', array('width'=>530, 'shaded'=>0,'showHeadings'=>$h,'cols'=>array('comm'=>array('width'=>200), 'weight'=>array('width'=>85, 'justification'=>'right'), 'count1'=>array('width'=>85, 'justification'=>'right'),'unit1'=>array('width'=>40), 'count2'=>array('width'=>90, 'justification'=>'right'),'unit2'=>array('width'=>40))));
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
		$pdf->ezTable($sub, array('comm'=>'COMMODITY NAME', 'weight'=>'WEIGHT (IN TONS)', 'count1'=>'COUNT1', 'unit1'=>'UNIT1', 'count2'=>'COUNT2','unit2'=>'UNIT2'), '', array('width'=>530, 'shaded'=>0,'showHeadings'=>0, 'showLines'=>1,'cols'=>array('comm'=>array('width'=>200), 'weight'=>array('width'=>85, 'justification'=>'right'), 'count1'=>array('width'=>85, 'justification'=>'right'),'unit1'=>array('width'=>40), 'count2'=>array('width'=>90, 'justification'=>'right'),'unit2'=>array('width'=>40))));
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$pdf->ezSetDy(-20);
	}
   }

   $sql = "SELECT COMMODITY_NAME, ROUND(SUM(CARGO_WEIGHT)/2000,0) CW, ROUND(SUM(QTY_EXPECTED),0) QTY1, ROUND(SUM(QTY2_EXPECTED),0) QTY2, QTY1_UNIT, QTY2_UNIT FROM CARGO_MANIFEST CM, COMMODITY_PROFILE COM, CARGO_TRACKING CT  WHERE CM.COMMODITY_CODE=COM.COMMODITY_CODE AND LR_NUM ='-1'  AND DATE_RECEIVED >= TO_DATE('".$sDate."', 'MM/DD/YYYY') AND DATE_RECEIVED <= TO_DATE('".$eDate."', 'MM/DD/YYYY') AND CT.LOT_NUM=CM.CONTAINER_NUM GROUP BY COMMODITY_NAME,QTY1_UNIT, QTY2_UNIT ORDER BY COMMODITY_NAME";


   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();
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


        // added to $data for printing
       	array_push($data, array('comm'=>$com, 'weight'=>$f_weight, 'count1'=>$f_qty1,'unit1'=>$unit1,'count2'=>$f_qty2, 'unit2'=>$unit2));

        //stor running sub_tot
        $sub_tot[0] += $weight;
        $sub_tot[1] += $qty1;
        $sub_tot[2] += $qty2;


   }
   // Data array created
   if (count($data)>0){
        $t += 1;
        if ($t ==1){
                $h = 1;
        }else{
                $h = 0;
        }

        $tot[0] += $sub_tot[0];
        $tot[1] += $sub_tot[1];
        $tot[2] += $sub_tot[2];	

        $sub_tot[0]=number_format($sub_tot[0],0,'.',',');
        $sub_tot[1]=number_format($sub_tot[1],0,'.',',');
        $sub_tot[2]=number_format($sub_tot[2],0,'.',',');
	if ($sub_tot[0]==0){$sub_tot[0]="";}
        if ($sub_tot[1]==0){$sub_tot[1]="";}
        if ($sub_tot[2]==0){$sub_tot[2]="";}


	$sub=array();

        $pdf->ezSetDy(-10);
	$pdf->ezText("<b>     Tracked in Cargo:</b>");
	$pdf->ezSetDy(-5);

	array_push($sub, array('comm'=>'Sub Total', 'weight'=>$sub_tot[0], 'count1'=>$sub_tot[1],'unit1'=>'','count2'=>$sub_tot[2], 'unit2'=>''));

        $pdf->ezTable($data, array('comm'=>'COMMODITY', 'weight'=>'TONNAGE', 'count1'=>'QTY1', 'unit1'=>'UNIT1','count2'=>'QTY2','unit2'=>'UNIT2'), '', array('width'=>530, 'shaded'=>0,'showHeadings'=>$h,'cols'=>array('comm'=>array('width'=>200), 'weight'=>array('width'=>85, 'justification'=>'right'), 'count1'=>array('width'=>85, 'justification'=>'right'),'unit1'=>array('width'=>40), 'count2'=>array('width'=>90, 'justification'=>'right'),'unit2'=>array('width'=>40))));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
        $pdf->ezTable($sub, array('comm'=>'COMMODITY NAME', 'weight'=>'WEIGHT (IN TONS)', 'count1'=>'COUNT1', 'unit1'=>'UNIT1', 'count2'=>'COUNT2','unit2'=>'UNIT2'), '', array('width'=>530, 'shaded'=>0,'showHeadings'=>0, 'showLines'=>1,'cols'=>array('comm'=>array('width'=>200), 'weight'=>array('width'=>85, 'justification'=>'right'), 'count1'=>array('width'=>85, 'justification'=>'right'),'unit1'=>array('width'=>40), 'count2'=>array('width'=>90, 'justification'=>'right'),'unit2'=>array('width'=>40))));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$pdf->ezSetDy(-10);
   }
   if (count($tot)>0){
	$tot[0] = number_format($tot[0],0,'.',',');
        $tot[1] = number_format($tot[1],0,'.',',');
        $tot[2] = number_format($tot[2],0,'.',',');

	$tot_data = array();

	array_push($tot_data, array('comm'=>'Grand Total', 'weight'=>$tot[0], 'count1'=>$tot[1],'unit1'=>'','count2'=>$tot[2], 'unit2'=>''));
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
	$pdf->ezTable($tot_data, array('comm'=>'COMMODITY NAME', 'weight'=>'WEIGHT (IN TONS)', 'count1'=>'COUNT1', 'unit1'=>'UNIT1', 'count2'=>'COUNT2','unit2'=>'UNIT2'), '', array('width'=>530, 'shaded'=>0,'showHeadings'=>0, 'showLines'=>0,'fontSize'=>14,'cols'=>array('comm'=>array('width'=>200), 'weight'=>array('width'=>85, 'justification'=>'right'), 'count1'=>array('width'=>85, 'justification'=>'right'),'unit1'=>array('width'=>40), 'count2'=>array('width'=>90, 'justification'=>'right'),'unit2'=>array('width'=>40))));

   }

   $pdf->ezStream();

   ora_close($cursor);

?>
