<?
/*
*		Adam Walter, June 2014.
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
	
	$emp = $HTTP_GET_VARS['emp'];
	$type = $HTTP_GET_VARS['type'];
	if($type == "all"){
		$more_sql = " AND EARNING_TYPE_ID IN ('PERS', 'VAC', 'SICK', 'HOL') ";
	} else {
		$more_sql = " AND EARNING_TYPE_ID = '".$type."' ";
	}

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

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

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Employee</b></font></td>
		<td><font size="2" face="Verdana"><b>Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Hours</b></font></td>
		<td><font size="2" face="Verdana"><b>Entered By</b></font></td>
	</tr>
<?
	$sql = "SELECT DISTINCT EMPLOYEE_ID
			FROM LABOR.SF_HOURLY_DETAIL
			WHERE EMPLOYEE_ID = '".$emp."'
				".$more_sql."
			ORDER BY EMPLOYEE_ID";
	$emps = ociparse($conn, $sql);
	ociexecute($emps);
	if(!ocifetch($emps)){
?>
	<tr>
		<td colspan="5" align="center"><font size="2" face="Verdana"><b>No Entries found.</b></font></td>
	</tr>
<?
	} else {
		do {
			$emp_total = 0;

			$sql = "SELECT EMPLOYEE_NAME
					FROM LABOR.EMPLOYEE
					WHERE EMPLOYEE_ID = '".ociresult($emps, "EMPLOYEE_ID")."'";
			$Short_Term_Data = ociparse($conn, $sql);
			ociexecute($Short_Term_Data);
			ocifetch($Short_Term_Data);
?>
	<tr bgcolor="#99FF99">
		<td colspan="5"><font size="2" face="Verdana"><b><? echo ociresult($emps, "EMPLOYEE_ID")." - ".ociresult($Short_Term_Data, "EMPLOYEE_NAME"); ?></b></font></td>
	</tr>
<?
			$sql = "SELECT DISTINCT EARNING_TYPE_ID
					FROM LABOR.SF_HOURLY_DETAIL
					WHERE EMPLOYEE_ID = '".ociresult($emps, "EMPLOYEE_ID")."'
						".$more_sql."
					ORDER BY EARNING_TYPE_ID";
			$types = ociparse($conn, $sql);
			ociexecute($types);
			while(ocifetch($types)){
				$type_total = 0;
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="4" bgcolor="#66CCFF"><font size="2" face="Verdana"><b><? echo ociresult($types, "EARNING_TYPE_ID"); ?></b></font></td>
	</tr>
<?
				$sql = "SELECT TO_CHAR(HIRE_DATE, 'MM/DD/YYYY') THE_DATE, HIRE_DATE, DURATION, SFD.USER_ID, LU.USER_NAME
						FROM LABOR.SF_HOURLY_DETAIL SFD, LABOR.LCS_USER LU
						WHERE SFD.USER_ID = LU.USER_ID
							AND SFD.EMPLOYEE_ID = '".ociresult($emps, "EMPLOYEE_ID")."'
							AND SFD.EARNING_TYPE_ID = '".ociresult($types, "EARNING_TYPE_ID")."'
						ORDER BY HIRE_DATE";
				$hours = ociparse($conn, $sql);
				ociexecute($hours);
				while(ocifetch($hours)){
					$type_total += ociresult($hours, "DURATION");
					$emp_total += ociresult($hours, "DURATION");
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo ociresult($hours, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($hours, "DURATION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($hours, "USER_ID")." - ".ociresult($hours, "USER_NAME"); ?></font></td>
	</tr>
<?
					}
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="4" bgcolor="#99CCFF"><font size="2" face="Verdana"><b>TOTAL - <? echo ociresult($types, "EARNING_TYPE_ID"); ?> - <? echo $type_total; ?></b></font></td>
	</tr>
<?
				}
?>
	<tr>
		<td colspan="5" bgcolor="#CCFFCC"><font size="2" face="Verdana"><b>TOTAL - <? echo ociresult($emps, "EMPLOYEE_ID")." - ".ociresult($Short_Term_Data, "EMPLOYEE_NAME"); ?> - <? echo $emp_total; ?></b></font></td>
	</tr>
<?
		} while(ocifetch($emps));
	}
?>
</table>
<?
	include("pow_footer.php");
?>