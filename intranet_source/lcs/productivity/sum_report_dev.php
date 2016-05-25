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
	printf($sDate . " " .  $eDate . "<br>" );	
	
    // open a connection to the database server
   	include("connect.php");

	$conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   	if($conn_bni < 1)
   	{
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   	}
   
   $cursor_bni = ora_open($conn_bni);
   
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
			and SERVICE_TYPE NOT in ('MAINTENANCE')
			group by SERVICE_TYPE, COMMODITY, COMMODITY_NAME, BUDGET
			order by SERVICE_TYPE, COMMODITY";

	$statement = ora_parse($cursor_bni, $sql);
	
	// execute the query
	if (! ora_exec($cursor_bni))
	{
		// Roll-back
		ora_rollback($conn);
		// Exit
		exit(ora_error($cursor));
	}

?>
	<p align="center"><font size="4"><b>Weekly Productivity Report</b></font><br>
	<p align="center"><font size="2"><b><? echo $sDate . "~" . "$eDate" ?></b></font><br>
	<table border="1">
	<tr>
	<th>SERVICE</th>
	<th>COMMODITY</th>
	<th>TONNAGE</th>
	<th>UNIT</th>
	<th>HOURS</th>
	<th>PRODUCTIVITY(T/H)</th>
	<th>BUDGET</th>
	<th>VARIANCE</th>
	<th>WEEK TO DATE</th>
	<th>MONTH TO DATE</th>
	</tr>


<?
	// loop throught the recordset
	while (ora_fetch($cursor_bni))
	{
   		$svc_type = ora_getcolumn($cursor_bni,0);
   		$com = ora_getcolumn($cursor_bni,2);
   		$tonnage = ora_getcolumn($cursor_bni,3);
   		$unit = ora_getcolumn($cursor_bni,4);
   		$hours = ora_getcolumn($cursor_bni,5);
   		$budget = ora_getcolumn($cursor_bni,6);
   	
   		if (($tonnage+$unit+$hours)> 0)
   		{
   		
?>   		
		<tr>
		<td align="left"><font size="2"><? echo $svc_type ?></font></td>
		<td align="left"><font size="2"><? echo $com ?></font></td>
		<td align="center"><font size="2">
			<? 	if ($tonnage > 0)
				{
					echo (number_format($tonnage, 0));
				}
				else
				{
					echo ("&nbsp");
				}	
			?>
		</td>
		<td align="center"><font size="2">
		<? if ($unit >0)
				{
					echo ($unit);
				}
				else
				{
					echo("&nbsp");
				}
			
			?>
		</font></td>
		<td align="center"><font size="2">
			<? if ( $hours > 0)
				{
					echo ($hours);
				}
				else
				{
					echo ("&nbsp");
				}
			?>
		</font></td>
		<td align="center"><font size="2">
		<? if (($tonnage > 0) && ($hours > 0))
				{
					$prod=number_format($tonnage/$hours, 1);
					echo ($prod);
				}
				elseif ($tonnage > 0 )
				{
					echo "<b>No Hours</b>";
				}
				elseif ($hours > 0 )
				{
					echo "<b>No Tons</b>";
				}
				else
				{
					echo "&nbsp";
				}
			?>
		</font></td>
		<td align="center"><font size="2">
		<? if ($budget == 9999)
				{
					echo ("<b>No Budget</b>");
				}
				else
				{
					echo $budget;
				} 
			?>
		</font></td>
		<td align="center"><font size="2">
			<?	if (($prod >0) && ($budget > 0 ) && ($budget != 9999 ))
				{
					$variance=(number_format((($prod-$budget)/$budget),2))*100 . "%";
					echo ($variance);
				}
				else
				{
					echo ("---");
				}			
			?>
		</font></td>
		<td align="center"><font size="2">WEEK TO DATE</td></font>
		<td align="center"><font size="2">MONTH TO DATE</td></font>
		</tr>
<?		
		}
	}
?>
	</table>
	<br>
	<p align="left"></p>
	<font size="2" align="left"><b>Note:	</b></font><br>
	<font size="2"><b>Commodity codes '11xx', '12xx', '2xxx', '7xxx' and '8xxx' are excluded.<b></font><br>
	<font size="2"><b>Commodity codes '52xx', only Backhaul operation is included.</b></font><br>
	
	<hr>
<?	// close the connection
	ora_close($cursor_bni);
?>
