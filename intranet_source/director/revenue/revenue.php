<?
   include("pow_session.php");
   $user = $userdata['username'];
	
   $sDate = $HTTP_POST_VARS[start_date];
   $eDate = $HTTP_POST_VARS[end_date];

   $rep_type = $HTTP_POST_VARS[type];

   if($rep_type =="vessel"){
        $sql = "SELECT V.VESSEL_NAME, TO_CHAR(Y.DATE_ARRIVED, 'MM/DD/YYYY'), TO_CHAR(Y.DATE_DEPARTED,'MM/DD/YYYY'), B.BILLING_TYPE, SUM(SERVICE_AMOUNT) FROM VESSEL_PROFILE V, VOYAGE Y, BILLING B WHERE B.LR_NUM = V.LR_NUM AND V.LR_NUM = Y.LR_NUM AND SERVICE_STATUS = 'INVOICED' AND Y.DATE_ARRIVED >=TO_DATE('".$sDate."','MM/DD/YYYY') AND  Y.DATE_ARRIVED <=TO_DATE('".$eDate."','MM/DD/YYYY') GROUP BY  V.VESSEL_NAME, Y.DATE_ARRIVED, Y.DATE_DEPARTED, B.BILLING_TYPE ORDER BY Y.DATE_ARRIVED, V.VESSEL_NAME, B.BILLING_TYPE ";

        $arrCol = array('ves'=>'VESSEL','arr_date'=>'ARRIVAL','dep_date'=>'DEPARTURE', 'type'=>'SERVICE ','amt'=>'REVENUE  ');
        $heading1 = "Vessel Total";
        $heading2 = "";
	$title = "REVENUE BY VESSEL";
	$sub_title = "Vessel arrivals in the period:";
   }else if($rep_type == "revenue"){
        $sql = "SELECT V.VESSEL_NAME, TO_CHAR(Y.DATE_ARRIVED, 'MM/DD/YYYY'), TO_CHAR(Y.DATE_DEPARTED,'MM/DD/YYYY'), B.BILLING_TYPE, SUM(SERVICE_AMOUNT) FROM VESSEL_PROFILE V, VOYAGE Y, BILLING B WHERE  B.LR_NUM = V.LR_NUM AND V.LR_NUM = Y.LR_NUM AND SERVICE_STATUS = 'INVOICED' AND INVOICE_DATE >=TO_DATE('".$sDate."','MM/DD/YYYY') AND  INVOICE_DATE <=TO_DATE('".$eDate."','MM/DD/YYYY') GROUP BY B.BILLING_TYPE, V.VESSEL_NAME, Y.DATE_ARRIVED, Y.DATE_DEPARTED ORDER BY B.BILLING_TYPE,V.VESSEL_NAME";
        $arrCol = array('type'=>'SERVICE','ves'=>'VESSEL','arr_date'=>'ARRIVAL', 'dep_date'=>'DEPARTURE','amt'=>'REVENUE  ');
        $heading1 = "";
        $heading2 = "Sub Total";
	$title = "REVENUE BY SERVICE";
        $sub_title = "Invoices dated:";
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

   $h_data = array();
   array_push($h_data, array('ves'=>'VESSEL', 'arr_date'=>'ARRIVAL', 'dep_date'=>'DEPARTURE','type'=>'SERVICE','amt'=>'REVENUE'));

   $pdf->ezSetMargins(55,40,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $pdf->ezStartPageNumbers(300, 20, 8, '','',1);
   // Write out the intro.
   // Print Receiving Header
   //$pdf->ezSetDy(-10);

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
   $pdf->addObject($all,'add');
  
   $heading = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
 // $pdf->line(45,30,555,30);
   $pdf->addText(490,760,8, $format);
   $pdf->ezSetDy(15);
   $pdf->ezTable($h_data, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>0,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($heading,'next');

   $pdf->ezSetDy(15);
   $pdf->ezText("<b>$title</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<i>$sub_title $sDate to $eDate</i>", 12, $center);
   $pdf->ezSetDy(-15);


   $tot = 0;
   $t = 0;

   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
	
   $data = array();
   $sub_tot = 0;	// to store running sub_tot total
   $pre_ves = "";
   $pre_type="";
   while (ora_fetch($cursor)){
      	$ves = trim(ora_getcolumn($cursor, 0));

        $pos=strpos($ves, "-");
	if ($pos == 0){
		$f_ves = "CARGO TRUCKED IN/MIXED VESSEL";
	}else if ($pos > 0){
                $f_ves= strtoupper(substr($ves, $pos+1)." (".substr($ves,0,$pos).")");
        }else{
                $f_ves = strtoupper($ves);
        }
      
	$arr_date = ora_getcolumn($cursor, 1);
      	$dep_date = ora_getcolumn($cursor, 2);
      	$type = trim(ora_getcolumn($cursor, 3));
	$amt = ora_getcolumn($cursor, 4);
	$f_amt = number_format($amt, 0, '.',',');

	if ($type == "LEASE") {$f_ves ="";}

	if ($rep_type=="vessel" && $ves == $pre_ves) {
		// added to $data for printing
		array_push($data, array('ves'=>'', 'arr_date'=>'', 'dep_date'=>'','type'=>$type,'amt'=>$f_amt));
                //stor running sub_tot
                $sub_tot += $amt;
	}else if ($rep_type=="revenue" && $type==$pre_type ) {
      		// added to $data for printing
		array_push($data, array('ves'=>$f_ves, 'arr_date'=>$arr_date, 'dep_date'=>$dep_date,'type'=>'','amt'=>$f_amt));
		//stor running sub_tot
		$sub_tot += $amt;
	}else{
		//print
		if (count($data)>0){
                	$t += 1;
                	if ($t ==1){
                        	$h = 1;
                	}else{
                        	$h = 0;
                	}
                	$tot += $sub_tot;

                	$sub_tot=number_format($sub_tot,0,'.',',');

                	if ($sub_tot==0){$sub_tot="";}


                	$sub = array();

			array_push($sub, array('ves'=>$heading1, 'arr_date'=>'', 'dep_date'=>'','type'=>$heading2,'amt'=>$sub_tot));
                	$pdf->ezTable($data, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>$h,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));
                	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
			$pdf->ezTable($sub, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>0,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));

                	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
                	$pdf->ezSetDy(-20);

		}
		if($rep_type == "vessel") { 
			$pre_ves = $ves;
		}else if($rep_type =="revenue") {
			$pre_type = $type;
		}
   
		$data=array();
   		$sub_tot = 0;	
		if ($rep_type =="vessel"){	
                	// added to $data for printing
                	array_push($data, array('ves'=>$f_ves, 'arr_date'=>$arr_date, 'dep_date'=>$dep_date,'type'=>'','amt'=>''));
			array_push($data, array('ves'=>'', 'arr_date'=>'', 'dep_date'=>'','type'=>$type,'amt'=>$f_amt));
                }else if ($rep_type=="revenue"){
                        // added to $data for printing
                        array_push($data, array('ves'=>'', 'arr_date'=>'', 'dep_date'=>'','type'=>$type,'amt'=>''));
                        array_push($data, array('ves'=>$f_ves, 'arr_date'=>$arr_date, 'dep_date'=>$dep_date,'type'=>'','amt'=>$f_amt));
		}
		//stor running sub_tot
                $sub_tot += $amt;

	}
   }
   if (count($data)>0){
  	$t += 1;
        if ($t ==1){
        	$h = 1;
        }else{
                $h = 0;
        }
        $tot += $sub_tot;

      	$sub_tot=number_format($sub_tot,0,'.',',');

        if ($sub_tot==0){$sub_tot="";}


        $sub = array();

        array_push($sub, array('ves'=>$heading1, 'arr_date'=>'', 'dep_date'=>'','type'=>$heading2,'amt'=>$sub_tot));
        
        $pdf->ezTable($data, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>$h,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
        $pdf->ezTable($sub, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>0,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
        $pdf->ezSetDy(-20);

   }
/*
   $sql = "SELECT V.VESSEL_NAME, TO_CHAR(Y.DATE_ARRIVED, 'MM/DD/YYYY'), TO_CHAR(Y.DATE_DEPARTED,'MM/DD/YYYY'), B.BILLING_TYPE, SUM(SERVICE_AMOUNT) FROM VESSEL_PROFILE V, VOYAGE Y, BILLING B WHERE B.LR_NUM =-1 AND  B.LR_NUM = V.LR_NUM AND V.LR_NUM = Y.LR_NUM AND SERVICE_STATUS = 'INVOICED' AND INVOICE_DATE >=TO_DATE('".$sDate."','MM/DD/YYYY') AND  INVOICE_DATE <=TO_DATE('".$eDate."','MM/DD/YYYY') ".$strGroup;

   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();
   $sub_tot = 0;        // to store running sub_tot total
   $pre_ves = "";
   while (ora_fetch($cursor)){
        $ves = "CARGO TRUCKED IN/MIXED VESSEL";

        $pos=strpos($com, "-");
        if ($pos > 0){
                $com= strtoupper(substr($com, $pos+1)." (".substr($com,0,$pos).")");
        }else{
                $com = strtoupper($com);
        }

        $arr_date = ora_getcolumn($cursor, 1);
        $dep_date = ora_getcolumn($cursor, 2);
        $type = ora_getcolumn($cursor, 3);
        $amt = ora_getcolumn($cursor, 4);
        $f_amt = number_format($amt, 2, '.',',');

        array_push($data, array('ves'=>$ves, 'arr_date'=>$arr_date, 'dep_date'=>$dep_date,'type'=>$type,'amt'=>$f_amt));

        //stor running sub_tot
        $sub_tot += $amt;
   }
   if (count($data)>0){
        $t += 1;
        if ($t ==1){
                $h = 1;
        }else{
                $h = 0;
        }
        $tot += $sub_tot;

        $sub_tot=number_format($sub_tot,2,'.',',');

        if ($sub_tot==0){$sub_tot="";}


        $sub = array();

        array_push($sub, array('ves'=>$heading1, 'arr_date'=>'', 'dep_date'=>'','type'=>$heading2,'amt'=>$sub_tot));

        $pdf->ezTable($data, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>$h,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
        $pdf->ezTable($sub, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>0,'cols'=>array('ves'=>array('width'=>240), 'arr_date'=>array('width'=>75), 'dep_date'=>array('width'=>75),'type'=>array('width'=>90), 'amt'=>array('width'=>70, 'justification'=>'right'))));

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
        $pdf->ezSetDy(-20);
    }
*/
   if ($tot>0){
        $tot = number_format($tot,0,'.',',');

        $tot_data = array();
	array_push($tot_data, array('ves'=>'Grand Total', 'arr_date'=>'', 'dep_date'=>'','type'=>'','amt'=>$tot));
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');
        $pdf->ezTable($tot_data, $arrCol, '', array('width'=>550, 'shaded'=>0,'showHeadings'=>0,'showLines'=>0, 'fontSize'=>14,'cols'=>array('ves'=>array('width'=>100), 'arr_date'=>array('width'=>300), 'dep_date'=>array('width'=>1),'type'=>array('width'=>1), 'amt'=>array('width'=>150, 'justification'=>'right'))));
   }

   $pdf->ezStream();

   ora_close($cursor);

?>
