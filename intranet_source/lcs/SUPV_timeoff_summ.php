<?
/*
*		Adam Walter, Oct 2014.
*
*		Page for Finance to "delete" bills using the new (as of 6/2014) system
*********************************************************************************/

	$conn = ocilogon("LABOR", "LABOR", "LCS");
//	$conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($conn < 1){
		printf("Error logging on to the LCS Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisor time off";
  $area_type = "LCS";

//strtotime('last monday -7 days')

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied || ($user != 'fvignuli' && $user != 'tstest' && $user != 'wstans' && $user != 'ddonofrio' && $user != 'skennard' && $user != 'tscott')){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }
	
//	$wednesday = date('m/d/Y', strtotime("last Wednesday +1 days"));
//	$tuesday = date('m/d/Y', strtotime("last Tuesday +1 days"));
	$top_monday = date('m/d/Y', strtotime("last Monday +1 days"));
	$top_sunday = date('m/d/Y', strtotime("last Sunday +1 days"));

//	echo $wednesday."<br>".$tuesday."<br>".$monday."<br>".$sunday."<br>";

	$supv = $HTTP_POST_VARS['supv'];

	if($supv != ""){
		$more_sql = " AND EMPLOYEE_ID = '".$supv."' ";
	} else {
		$more_sql = " ";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Supervisor Paid Time Off</font>
         <hr>
      </td>
   </tr>
</table>
<br />


<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="SUPV_timeoff_summ.php" method="post">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Filters:</b></font>
	</tr>
	<tr>
		<td width="2%">&nbsp;&nbsp;&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">Supervisor:</font></td>
		<td align="left"><select name="supv"><option value="">All</option>
<?
	$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, INITIAL_VACATION, INITIAL_PERSONAL, INITIAL_SICK, VACATION_WEEKLY_RATE, SICK_WEEKLY_RATE
			FROM AT_EMPLOYEE
			WHERE EMPLOYMENT_STATUS = 'SUPV'
			ORDER BY EMPLOYEE_ID";
	$top_emps = ociparse($conn, $sql);
	ociexecute($top_emps);
	while(ocifetch($top_emps)){
?>
							<option value="<? echo ociresult($top_emps, "EMPLOYEE_ID"); ?>"<? if(ociresult($top_emps, "EMPLOYEE_ID") == $supv){?> selected <?}?>><? echo ociresult($top_emps, "EMPLOYEE_ID")." - ".ociresult($top_emps, "FIRST_NAME").",".ociresult($top_emps, "LAST_NAME"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><input type="submit" name="submit" value="View Report"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="13" align="center"><font size="3" face="Verdana"><b>As of Sunday, <? echo $top_sunday; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Starting values are as of Monday July 21, 2014</b></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><b>Employee:</b></font></td>
		<td colspan="4" align="center" bgcolor="#99FF66"><font size="3" face="Verdana"><b>Vacation Hours</b></font></td>
		<td colspan="3" align="center" bgcolor="#CCCCCC"><font size="3" face="Verdana"><b>Personal Hours</b></font></td>
		<td colspan="4" align="center" bgcolor="#FFCCCC"><font size="3" face="Verdana"><b>Sick Hours</b></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><b>START</b></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><b>ACCRUED</b></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><b>USED</b></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><b>REMAINING</b></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><b>START</b></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><b>USED</b></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><b>REMAINING</b></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><b>START</b></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><b>ACCRUED</b></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><b>USED</b></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><b>REMAINING</b></font></td>
	</tr>
<?
	$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, INITIAL_VACATION, INITIAL_PERSONAL, INITIAL_SICK, VACATION_WEEKLY_RATE, SICK_WEEKLY_RATE
			FROM AT_EMPLOYEE
			WHERE EMPLOYMENT_STATUS = 'SUPV'
				".$more_sql."
			ORDER BY EMPLOYEE_ID";
	$top_emps = ociparse($conn, $sql);
	ociexecute($top_emps);
	while(ocifetch($top_emps)){
		$start_vac = ociresult($top_emps, "INITIAL_VACATION");
		$start_pers = ociresult($top_emps, "INITIAL_PERSONAL");
		$start_sick = ociresult($top_emps, "INITIAL_SICK");

		$vac_rate = ociresult($top_emps, "VACATION_WEEKLY_RATE");
		$sick_rate = ociresult($top_emps, "SICK_WEEKLY_RATE");

		$sql = "SELECT SUM(DURATION) THE_DUR
				FROM LABOR.SF_HOURLY_DETAIL SFD, LABOR.LCS_USER LU
				WHERE SFD.USER_ID = LU.USER_ID
					AND SFD.EMPLOYEE_ID = '".ociresult($top_emps, "EMPLOYEE_ID")."'
					AND SFD.EARNING_TYPE_ID = 'PERS'
					AND HIRE_DATE >= TO_DATE('07/21/2014', 'MM/DD/YYYY')
					AND HIRE_DATE <= TO_DATE('".$top_monday."', 'MM/DD/YYYY')";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$pers_used = ociresult($short_term_data, "THE_DUR");
		$sql = "SELECT SUM(DURATION) THE_DUR
				FROM LABOR.SF_HOURLY_DETAIL SFD, LABOR.LCS_USER LU
				WHERE SFD.USER_ID = LU.USER_ID
					AND SFD.EMPLOYEE_ID = '".ociresult($top_emps, "EMPLOYEE_ID")."'
					AND SFD.EARNING_TYPE_ID = 'SICK'
					AND HIRE_DATE >= TO_DATE('07/21/2014', 'MM/DD/YYYY')
					AND HIRE_DATE <= TO_DATE('".$top_monday."', 'MM/DD/YYYY')";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$sick_used = ociresult($short_term_data, "THE_DUR");
		$sql = "SELECT SUM(DURATION) THE_DUR
				FROM LABOR.SF_HOURLY_DETAIL SFD, LABOR.LCS_USER LU
				WHERE SFD.USER_ID = LU.USER_ID
					AND SFD.EMPLOYEE_ID = '".ociresult($top_emps, "EMPLOYEE_ID")."'
					AND SFD.EARNING_TYPE_ID = 'VAC'
					AND HIRE_DATE >= TO_DATE('07/21/2014', 'MM/DD/YYYY')
					AND HIRE_DATE <= TO_DATE('".$top_monday."', 'MM/DD/YYYY')";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$vac_used = ociresult($short_term_data, "THE_DUR");

		$sql = "SELECT (SYSDATE - TO_DATE('07/21/2014', 'MM/DD/YYYY')) / 7 NUM_WEEKS
				FROM DUAL";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$num_weeks = floor(ociresult($short_term_data, "NUM_WEEKS"));

?>
	<tr>
		<td><font size="2" face="Verdana"><a href="view_SUPV_timeoff.php?emp=<? echo ociresult($top_emps, "EMPLOYEE_ID"); ?>&type=all"><? echo ociresult($top_emps, "EMPLOYEE_ID")." - ".ociresult($top_emps, "FIRST_NAME").",".ociresult($top_emps, "LAST_NAME"); ?></a></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><? echo (0 + $start_vac); ?></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><? echo (0 + $vac_accrued); ?></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><a href="view_SUPV_timeoff.php?emp=<? echo ociresult($top_emps, "EMPLOYEE_ID"); ?>&type=VAC"><? echo (0 + $vac_used); ?></a></font></td>
		<td bgcolor="#99FF66"><font size="2" face="Verdana"><? echo ($start_vac + $vac_accrued - $vac_used); ?></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><? echo (0 + $start_pers); ?></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><a href="view_SUPV_timeoff.php?emp=<? echo ociresult($top_emps, "EMPLOYEE_ID"); ?>&type=PERS"><? echo (0 + $pers_used); ?></a></font></td>
		<td bgcolor="#CCCCCC"><font size="2" face="Verdana"><? echo ($start_pers - $pers_used); ?></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><? echo (0 + $start_sick); ?></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><? echo (0 + $sick_accrued); ?></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><a href="view_SUPV_timeoff.php?emp=<? echo ociresult($top_emps, "EMPLOYEE_ID"); ?>&type=SICK"><? echo (0 + $sick_used); ?></a></font></td>
		<td bgcolor="#FFCCCC"><font size="2" face="Verdana"><? echo ($start_sick + $sick_accrued - $sick_used); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");