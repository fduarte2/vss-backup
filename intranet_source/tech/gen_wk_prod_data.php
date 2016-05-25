<?

	 //All POW files need this session file included
	include("pow_session.php");
	
	//Define some vars for the skeleton page
	$user = $userdata['username'];
	$title = "TS - Weekly Productivity Data";
	$area_type = "TECH";

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
	$conn_bni = ora_logon("SAG_OWNER@bni", "SAG");

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
   		    echo "weekly_productivity_report.xls";
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
   		
   		//printf($data[$i]['svc'] . "\t" . $data[$i]['com'] . "\t" . $data[$i]['unit'] . "\t" . $data[$i]['ton'] . "\t" . $data[$i]['hour'] . "\t" . $data[$i]['prod'] . "\t" . $data[$i]['budget'] . "\t" . $data[$i]['var'] . "<br>");
   		fwrite($file, $data[$i]['svc'] . "\t" . $data[$i]['com'] . "\t" . $data[$i]['unit'] . "\t" . $data[$i]['ton'] . "\t" . $data[$i]['hour'] . "\t" . $data[$i]['prod'] . "\t" . $data[$i]['budget'] . "\t" . $data[$i]['var'] . "\n");		
   		
   }
      
  	// close the connection
	ora_close($cursor_bni);
	
	
	/*--------------------------------------+
	|	Stand By Operation					|  
	+---------------------------------------*/
	
	$conn_lcs = ora_logon("LABOR@lcs", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   $cursor_lcs = ora_open($conn_lcs);
   
   /*
   $sql="select a.SERVICE_CODE, b.SERVICE_NAME, sum(a.DURATION)
		from hourly_detail a, service b
		where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
		and TO_CHAR(a.SERVICE_CODE) like '67%'
		and a.SERVICE_CODE=b.SERVICE_CODE
		group by a.SERVICE_CODE, b.SERVICE_NAME
		order by a.SERVICE_CODE";
	*/
	
   $sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, sum(a.DURATION)
		from hourly_detail a, service b, commodity c, employee d
		where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
		and TO_CHAR(a.SERVICE_CODE) like '67%'
		and a.SERVICE_CODE=b.SERVICE_CODE
		and a.COMMODITY_CODE=c.COMMODITY_CODE
		and a.EMPLOYEE_ID=d.EMPLOYEE_ID
		and d.EMPLOYEE_TYPE_ID not in  ('SUPV')
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

	$conn_lcs = ora_logon("LABOR@lcs", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   	$cursor_lcs = ora_open($conn_lcs);
   	$sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, SUM(a.DURATION)
				from hourly_detail a, service b, commodity c, employee d
				where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
				and ((a.SERVICE_CODE between 7310 and 7319) or (a.SERVICE_CODE=7322))
				and a.SERVICE_CODE=b.SERVICE_CODE
				and a.COMMODITY_CODE=c.COMMODITY_CODE
				and a.EMPLOYEE_ID=d.EMPLOYEE_ID
				and d.EMPLOYEE_TYPE_ID not in  ('SUPV')
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

	$conn_lcs = ora_logon("LABOR@lcs", "LABOR");
   	if($conn_lcs < 1)
   	{
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   	}
   
   	$cursor_lcs = ora_open($conn_lcs);
   	$sql="select a.SERVICE_CODE, b.SERVICE_NAME, a.COMMODITY_CODE, c.COMMODITY_NAME, SUM(a.DURATION)
			from hourly_detail a, service b, commodity c, employee d
			where a.HIRE_DATE between TO_DATE('$sDate', 'mm/dd/yyyy') and TO_DATE('$eDate', 'mm/dd/yyyy')
			and a.SERVICE_CODE=7320
			and b.SERVICE_CODE=7320
			and a.COMMODITY_CODE=c.COMMODITY_CODE
			and a.EMPLOYEE_ID=d.EMPLOYEE_ID
			and d.EMPLOYEE_TYPE_ID not in  ('SUPV')
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

	$writtenFile = "/web/web_pages/lcs/productivity/weekly_productivity_report.xls";
	$body = "As Attached.";
	$cmd = "echo ".$body." | mutt -s \"Weekly Productivity Data\" -a ".$writtenFile." -b hdadmin@port.state.de.us -b ithomas@port.state.de.us jjaffe@port.state.de.us";
	
	system($cmd, $stat);

	printf("Weekly productivity data for " . $sDate . "-" .  $eDate . " has beened emailed to Jon Jaffe, Inigo and Paul<br>" );

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
	
	$conn_bni_budget = ora_logon("SAG_OWNER@bni", "SAG");
	
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
