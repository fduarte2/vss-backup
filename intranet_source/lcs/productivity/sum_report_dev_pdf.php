<?

	// All POW files need this session file included
	include("pow_session.php");
	
	// Define some vars for the skeleton page
	$user = $userdata['username'];
	$title = "Director - Weekly Productivity";
	$area_type = "LCS";

	// Provides header / leftnav
	include("pow_header.php");
	if($access_denied)
	{
		printf("Access Denied from LCS system");
		include("pow_footer.php");
		exit;
	}
	
	// Get start and end dates of last week	
	$wday = date('w');
	if ($wday == 0)
	$wday = 7;   
   	$sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 6 - $wday ,date("Y")));
   	$eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $wday ,date("Y")));
	//printf($sDate . " " .  $eDate . "<br>" );	
	
    // open a connection to the database server
   	include("connect.php");

	
	$budget_table=array();
	
	if (getBudgetTable(&$budget_table) > 0 )
	{
		/*
		for ($t=0; $t<count($budget_table)-1; $t++)
		{
			printf($budget_table[$t]['svc']. " " . $budget_table[$t]['com'] . " " . $budget_table[$t]['budget']. "<br>");
		}
		*/
		
		
	}
	
	// Operations with productivity measurements
	$conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
	//$conn_bni = ora_logon("SAG_OWNER@BNI_BACKUP", "SAG_DEV");
   	if($conn_bni < 1)
   	{
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   	}
   
   $cursor_bni = ora_open($conn_bni);
   $svc_clause= "('BACKHAUL', 'TRUCKLOADING', 'RAIL CAR HANDLING', 'CONTAINER HANDLING', 'TERMINAL SERVICE')";
   
   $sql="select SERVICE_TYPE, COMMODITY, COMMODITY_NAME, 
			sum(TONNAGE) Total_Tonnage, sum(QTY) Total_Unit, sum(HOURS) Total_Hours, BUDGET
			from
			( 
			select * 
			from PRODUCTIVITY_HIRE_PLAN a
			where a.COMMODITY NOT like '11%'
			and a.COMMODITY not like '12%'
			and a.COMMODITY not like '2%'
			and a.COMMODITY not like '7%'
			and a.COMMODITY not like '8%'
			)
			where DATE_TIME between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
			and SERVICE_TYPE in $svc_clause
			group by SERVICE_TYPE, COMMODITY, COMMODITY_NAME, BUDGET
			order by SERVICE_TYPE, COMMODITY";

	$statement = ora_parse($cursor_bni, $sql);
	
	// execute the query
	if (! ora_exec($cursor_bni))
	{
		// Roll-back
		ora_rollback($conn_bni);
		// Exit
		exit(ora_error($cursor_bni));
	}
 
 	$data = array();
 	

	// loop throught the recordset
	while (ora_fetch($cursor_bni))
	{
   		$svc_type = ora_getcolumn($cursor_bni,0);
   		$com_code=ora_getcolumn($cursor_bni,1);
   		$com = ora_getcolumn($cursor_bni,2);
   		$tonnage = ora_getcolumn($cursor_bni,3);
   		$unit = ora_getcolumn($cursor_bni,4);
   		$hours = ora_getcolumn($cursor_bni,5);
   		$budget = ora_getcolumn($cursor_bni,6);
 		$var= "N/A";
 		
		$include_flag = false;
		
		if ($svc_type == "TERMINAL SERVICE")
		{			
			$prod="N/A";
			$include_flag = true ;
		}
		elseif ($tonnage != NULL && $hours != NULL && $svc_type != "TERMINAL SERVICE")
		{
			$prod = $tonnage/$hours ;
			$tonnage = $tonnage;
			$include_flag = true;
			

		}
		elseif ($tonnage == NULL && $hours != NULL && $svc_type != "TERMINAL SERVICE")
		{
			$prod=$tonnage/$hours ;
			$include_flag = true;

		}
		elseif ($tonnage != NULL && $hours == NULL && $svc_type != "TERMINAL SERVICE" )
		{
			$hours="!";
			$prod="!";
			$tonnage = $tonnage ;
			$include_flag = true;
			
		}
		elseif ($tonnage == NULL && $hours == NULL && $svc_type != "TERMINAL SERVICE")
		{
		
		}
		
		//printf(substr($com, 0, 2) . " " . $svc_type . "<br>");
		// for commodity=52XX, we only compute productivity for BACKHAUL
		if (substr($com, 0, 2) == "52")
		{
			if ($svc_type == "BACKHAUL")
			{
				//printf($include_flag . "<br>");
				$include_flag = true;
			}
			else
			{
				$include_flag = false;
			}
		}

		
		if ($include_flag)
		{
			array_push($data, array('svc'=> $svc_type,
									'com'=> $com,
									'unit' => $unit,
									'ton'=> $tonnage,						
									'hour'=>  $hours,
									'prod'=> $prod ,
									'budget'=> (($bud=findBudget($budget_table, $svc_type, $com_code))>=0 ? $bud: "N/A"),
									'var' => $var
									)
						);
		}
	}  	

   $my_tons=findTons($data, "BACKHAUL");
 	// Pass address of data..
 	CalTSProd(&$data, $my_tons);
   
   
   	// write to a file
   	$file = fopen("/web/web_pages/lcs/productivity/weekly_productivity_report.xls", "w");
   	if (!$file)
   	{
   		    echo "weekly_productivity_report.csv";
   			exit;
	}

   	fwrite($file, "\n");
   	fwrite($file, $sDate . "-" . $eDate . "\t" . "\n");
	
   	fwrite($file, "\n");
   	fwrite($file, "Service" . "\tCommodity" . "\tUnits/Plts" . "\tTons" . "\tHours" . "\tProductivity(T/H)" . "\tBudget" . "\tVariance" . "\n");
   // Format the numbers
   for ($i=0; $i<=count($data)-1; $i++)
   {   		
   		
   		if (is_numeric($data[$i]['unit']))
		   		{
		   			$data[$i]['unit']=number_format($data[$i]['unit'],0);
   		}
   		
   		if (is_numeric($data[$i]['ton']))
   		{
   			$data[$i]['ton']=number_format($data[$i]['ton'],0);
   		}
   		
   		if (is_numeric($data[$i]['prod']))
   		{
   			$data[$i]['prod']=number_format($data[$i]['prod'],1);
   		}

   		if (is_numeric($data[$i]['prod']) && is_numeric($data[$i]['budget']) )
   		{
   			$v=$data[$i]['prod']-$data[$i]['budget'];
   			$v>0 ? $data[$i]['var']=$v : $data[$i]['var']="(" . $v . ")" ; 
   			//$data[$i]['var']=$data[$i]['prod']-$data[$i]['budget'];
   		}
   		
   		printf($data[$i]['svc'] . "\t" . $data[$i]['com'] . "\t" . $data[$i]['unit'] . "\t" . $data[$i]['ton'] . "\t" . $data[$i]['hour'] . "\t" . $data[$i]['prod'] . "\t" . $data[$i]['budget'] . "\t" . $data[$i]['var'] . "<br>");
   		fwrite($file, $data[$i]['svc'] . "\t" . $data[$i]['com'] . "\t" . $data[$i]['unit'] . "\t" . $data[$i]['ton'] . "\t" . $data[$i]['hour'] . "\t" . $data[$i]['prod'] . "\t" . $data[$i]['budget'] . "\t" . $data[$i]['var'] . "\n");		
   		
   }
      
  	// close the connection
	ora_close($cursor_bni);
	
	
	/*--------------------------------------+
	|	Stand By Operation					|  
	+---------------------------------------*/
	
	$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   $cursor_lcs = ora_open($conn_lcs);
   $sql="select a.SERVICE_CODE, b.SERVICE_NAME, sum(a.DURATION)
		from hourly_detail a, service b
		where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
		and TO_CHAR(a.SERVICE_CODE) like '67%'
		and a.SERVICE_CODE=b.SERVICE_CODE
		group by a.SERVICE_CODE, b.SERVICE_NAME
		order by a.SERVICE_CODE";

   $sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, sum(a.DURATION)
		from hourly_detail a, service b, commodity c
		where a.HIRE_DATE between TO_DATE('07/24/2006', 'mm/dd/yyyy') and TO_DATE('07/30/2006', 'mm/dd/yyyy')
		and TO_CHAR(a.SERVICE_CODE) like '67%'
		and a.SERVICE_CODE=b.SERVICE_CODE
		and a.COMMODITY_CODE=c.COMMODITY_CODE
		group by a.SERVICE_CODE, b.SERVICE_NAME,a.COMMODITY_CODE, c.COMMODITY_NAME
		order by a.SERVICE_CODE";
	$statement = ora_parse($cursor_lcs, $sql);
	
	// execute the query
	if (! ora_exec($cursor_lcs))
	{
		// Roll-back
		ora_rollback($conn_lcs);
		// Exit
		exit(ora_error($cursor_lcs));
	}
 
  	$data_lcs = array();
  	
 
 	// loop throught the recordset
 	while (ora_fetch($cursor_lcs))
 	{
    	
		$svc_name = ora_getcolumn($cursor_lcs,1);
		$com_name = ora_getcolumn($cursor_lcs,3);
		$total_hours = ora_getcolumn($cursor_lcs,4);

		array_push($data_lcs, array('svc'=> "Stand By",
								'svc_name'=> $svc_name,
								'com_name' => $com_name,
								'total_hours'=> $total_hours
								)
					);

	} // while loop 	
	
	// close the connection
	ora_close($cursor_lcs);
	
	/*
	for ($i=0; $i<=count($data_lcs)-1; $i++)
	{
		printf($data_lcs[$i]['svc'] . "\t" . $data_lcs[$i]['svc_name'] . "\t" . $data_lcs[$i]['com_name'] . "\t" . $data_lcs[$i]['total_hours'] . "<br>");
	}
	*/
	
	
	/*--------------------------------------+
	|	Cleaning							|  
	+---------------------------------------*/

	$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   	$cursor_lcs = ora_open($conn_lcs);
   	$sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, SUM(a.DURATION)
				from hourly_detail a, service b, commodity c
				where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
				and ((a.SERVICE_CODE between 7310 and 7319) or (a.SERVICE_CODE=7322))
				and a.SERVICE_CODE=b.SERVICE_CODE
				and a.COMMODITY_CODE=c.COMMODITY_CODE
				group by a.SERVICE_CODE, b.SERVICE_NAME,a.COMMODITY_CODE, c.COMMODITY_NAME
				order by a.SERVICE_CODE";

	$statement = ora_parse($cursor_lcs, $sql);
	// execute the query
	if (! ora_exec($cursor_lcs))
	{
		// Roll-back
		ora_rollback($conn_lcs);
		// Exit
		exit(ora_error($cursor_lcs));
	}
 
  	// loop throught the recordset
 	while (ora_fetch($cursor_lcs))
 	{
    	
		$svc_name = ora_getcolumn($cursor_lcs,1);
		$com_name = ora_getcolumn($cursor_lcs,3);
		$total_hours = ora_getcolumn($cursor_lcs,4);

		array_push($data_lcs, array('svc'=> "Cleaning",
								'svc_name'=> $svc_name,
								'com_name'=> $com_name,
								'total_hours'=> $total_hours
								)
					);

	} // while loop 	
	
	// close the connection
	ora_close($cursor_lcs);

	/*
	for ($i=0; $i<=count($data_lcs)-1; $i++)
	{
		printf($data_lcs[$i]['svc'] . "\t" . $data_lcs[$i]['svc_name'] . "\t" . $data_lcs[$i]['com_name'] . "\t" . $data_lcs[$i]['total_hours'] . "<br>");
	}
	*/

	/*--------------------------------------+
	|	WHSE Preparation					|  
	+---------------------------------------*/

	$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   	$cursor_lcs = ora_open($conn_lcs);
   	$sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, SUM(a.DURATION)
			from hourly_detail a, service b, commodity c
			where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
			and a.SERVICE_CODE=7320
			and b.SERVICE_CODE=7320
			and a.COMMODITY_CODE=c.COMMODITY_CODE
			group by a.SERVICE_CODE, b.SERVICE_NAME,a.COMMODITY_CODE, c.COMMODITY_NAME
			order by a.SERVICE_CODE";

	$statement = ora_parse($cursor_lcs, $sql);
	// execute the query
	if (! ora_exec($cursor_lcs))
	{
		// Roll-back
		ora_rollback($conn_lcs);
		// Exit
		exit(ora_error($cursor_lcs));
	}
 
  	// loop throught the recordset
 	while (ora_fetch($cursor_lcs))
 	{
    	
		$svc_name = ora_getcolumn($cursor_lcs,1);
		$com_name = ora_getcolumn($cursor_lcs,3);
		$total_hours = ora_getcolumn($cursor_lcs,4);

		array_push($data_lcs, array('svc'=> "Warehouse Preparation",
								'svc_name'=> $svc_name,
								'com_name'=> $com_name,
								'total_hours'=> $total_hours
								)
					);

	} // while loop 		
	// close the connection
	ora_close($cursor_lcs);
	
	fwrite($file, "\n");
	fwrite($file, "Service" . "\tService Name" . "\tCommodity" . "\tTotal Hours" . "\n" );
	for ($i=0; $i<=count($data_lcs)-1; $i++)
	{
		//printf($data_lcs[$i]['svc'] . "\t" . $data_lcs[$i]['svc_name'] . "\t" . $data_lcs[$i]['com_name'] . "\t" . $data_lcs[$i]['total_hours'] . "<br>");
		fwrite($file, $data_lcs[$i]['svc'] . "\t" . $data_lcs[$i]['svc_name'] . "\t" . $data_lcs[$i]['com_name'] . "\t" . $data_lcs[$i]['total_hours'] . "\n");	
	}
	
	

	// close the output file
	fclose($file);

	// Create Report in pdf format
	$report = CreateReport($sDate, $eDate, $data, $data_lcs);
	
	// Send Report
	//if (SendReport($report) >0 )
	//{
	//	printf("Report Sent<br>");
	//}

/*--------------------------------------+
|										|
|	function CreateReport				|  
|										|
+---------------------------------------*/
function CreateReport($start_date, $end_date, $prod_data, $hour_data)
{
	include 'class.ezpdf.php';
   	
   	$arrHeading = array('svc'=>'<b>Service</b>', 'com'=>'<b>Commodity</b>', 'unit'=>'<b>Units/Plts</b>', 'ton'=>'<b>Tons</b>','hour'=>'<b>Hours</b>','prod'=>'<b>Productivity(T/H)</b>','budget'=>'<b>Budget</b>', 'var'=>'<b>Variance</b>');
	$heading = array();
   	array_push($heading, $arrHeading);
   	
   	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(25,60,10,10);
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);
	$format = "Printed On: " . date('m/d/y g:i A');
	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	$pdf->addText(650, 600,8, $format);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

   	// Write out the intro.
   	// Print Receiving Header
   	$pdf->ezSetDy(-5);
   	$pdf->ezText("<b>Weekly Productivity</b>", 24, $center);
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<b><i>" . $start_date . "-" . $end_date. "</i></b>", 14, $center);
   	
   	// $pdf->ezSetDy(-15);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
	//   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
	$pdf->ezSetDy(-10);
   	//$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   	$pdf->ezTable($prod_data, $arrHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>700,'cols'=>$arrCol));
	$pdf->ezSetDy(-1);
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Backhaul service codes are 611x, 613x</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	$pdf->ezSetDy(-1);
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Truckloading service codes are 622x</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	$pdf->ezSetDy(-1);
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Rail Car Handling service codes are 631x</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	$pdf->ezSetDy(-1);
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Container Handling service codes are 641x</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));


	// Start of Section for Hours
	$pdf->ezSetDy(-20);
   	//$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   	$arrHeading = array('svc'=>'<b>Service</b>', 'svc_name' => '<b>Service Name</b>', 'com_name' => '<b>Commodity</b>','total_hours' => '<b>Total Hours</b>');
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   	$pdf->ezTable($hour_data, $arrHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>700,'cols'=>$arrCol));
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Stand by service codes are 67xx</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Cleaning service codes are between 7310 and 7319 or servce code is 7322</i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	$t1=Array();
	array_push($t1,array('my_text'=>"<b><i>Warehouse preparation servce code is 7320 </i></b>") );
	$pdf->ezTable($t1, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>700,'cols'=>$arrCol));
	
	// output
	$pdfcode = $pdf->ezOutput();
	$file = chunk_split(base64_encode($pdfcode));
	
	return ($file);	

}

/*--------------------------------------+
|										|
|	function SendReport					|  
|										|
+---------------------------------------*/
function SendReport($rpt)
{
	// mailto
	$mailTo = "hdadmin@port.state.de.us";
	//$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
	//$mailheaders .= "Content-Type: text/html\r\n";
	
	// Header
	$mailheaders = "From: MailServer@port.state.de.us\r\n";
	//$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n";
	//$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
	$mailheaders  .= "This is a multi-part Contentin MIME format.\r\n";
	
	// Body
	//$body = "TEST" ;
	$body="--MIME_BOUNDRY\r\n";
	$body.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$body.="Content-Transfer-Encoding: quoted-printable\r\n";
	$body.="\r\n";
	//$Content.="http://dspc-s16/lcs/productivity/index2.php?vDate=".$vDate."\r\n";
	$body.="\r\n";
	$body.="--MIME_BOUNDRY\r\n";
	//$body.="Content-Type: application/pdf; name=\"Productivity Summary.pdf\"\r\n";
	$body.="Content-Type: application/csv; name=\"weekly_productivity_report.csv\"\r\n";
	$body.="Content-disposition: attachment\r\n";
	$body.="Content-Transfer-Encoding: base64\r\n";
	$body.="\r\n";
	$body.=$rpt;
	$body.="\r\n";
    $body.="--MIME_BOUNDRY--\n";
	
	// Subject
	$mailsubject = "Weekly Productivity Report Raw Data";

	if ((mail($mailTo, $mailsubject, $body, $mailheaders)))
	{
		return 1;
	}
	else
	{
		return -1;
	}
}

/*--------------------------------------+
|										|
|	function findTons					|  
|										|
+---------------------------------------*/
function findTons($my_data, $my_svc_type)
{	
	$my_array=array();
	for ($i=0; $i<=count($my_data)-1; $i++)
	{
		if ($my_data[$i]['svc'] == $my_svc_type )
		{			
			array_push($my_array, array('com'=>$my_data[$i]['com'], 'ton'=>$my_data[$i]['ton']));
		}
	}	
	return $my_array;	
}

/*--------------------------------------+
|										|
|	function CalTSProd					|  
|										|
+---------------------------------------*/
function CalTSProd(&$big, $small)
{

	for ($i=0; $i<=(count($big))-1; $i++)
	{
		if ($big[$i]['svc'] == "TERMINAL SERVICE" )
		{
			for ($j=0; $j<=(count($small))-1; $j++)
			{
				if ($big[$i]['com'] == $small[$j]['com'])
				{					
					$big[$i]['ton'] = $small[$j]['ton'];
					//printf($big[$i]['ton'] . " " . $big[$i]['com'] . " " . $big[$i]['hour'] . " " . $big[$i]['ton']/$big[$i]['hour'] . "<br>");
					if ($big[$i]['hour'] != NULL)
					{
						$big[$i]['prod']=$big[$i]['ton']/$big[$i]['hour'];
					}
				}
			}
		}
	} 
}

/*--------------------------------------+
|										|
|	function getBudgetTable				|  
|										|
+---------------------------------------*/
function getBudgetTable(&$data)
{
	
	$conn_bni_budget = ora_logon("SAG_OWNER@BNI", "SAG");
	
   	if($conn_bni_budget < 1)
   	{
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni_budget));
        printf("Please try later!");
        return (-1);
   	}
   
   $cursor_bni_budget = ora_open($conn_bni_budget);
   $sql="select * from budget order by type, commodity_code";

	$statement = ora_parse($cursor_bni_budget, $sql);
	
	// execute the query
	if (! ora_exec($cursor_bni_budget))
	{
		// Roll-back
		ora_rollback($conn_bni_budget);
		// Exit
		printf(ora_error($cursor_bni_budget));
		return (-1);
	}
	
	//$budget_data = array();
	 	
	// loop throught the recordset
	while (ora_fetch($cursor_bni_budget))
	{
		$svc = ora_getcolumn($cursor_bni_budget,0);
	   	$com = ora_getcolumn($cursor_bni_budget,1);
   		$budget = ora_getcolumn($cursor_bni_budget,2);
	
		array_push($data, array('svc'=> $svc,
								'com'=> $com,
								'budget'=> $budget,
								)
					);
	} //while
	
	ora_close($cursor_bni_budget);
	
	return 1;
}

/*--------------------------------------+
|										|
|	function findBudget					|  
|										|
+---------------------------------------*/
function findBudget($budget_table, $svc_type, $commodity)
{
	$ret_val=-1;
	
	//printf("passed-in:" . $svc_type . $commodity . "<br>");
	
	for ($t=0; $t<count($budget_table)-1; $t++)
	{
		//printf("table:" . $budget_table[$t]['svc']. " " . $budget_table[$t]['com'] . " " . $budget_table[$t]['budget']. "<br>");
		
		if (($budget_table[$t]['svc'] == $svc_type) && ($budget_table[$t]['com'] == $commodity) )
		{

			$ret_val=$budget_table[$t]['budget'];
			//printf($ret_val . "<br>");
			return $ret_val;
			
		}
				
	}
	
	return $ret_val;

}

?>
