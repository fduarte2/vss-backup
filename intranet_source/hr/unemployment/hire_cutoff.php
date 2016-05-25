<?php
# PHP Calendar (version 2.3), written by Keith Devens
# http://keithdevens.com/software/php_calendar
#  see example at http://keithdevens.com/weblog
# License: http://keithdevens.com/software/license

// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Unemployment";
$area_type = "HRMS";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
}

include("calendar.php");

include("connect.php");
$conn = ora_logon("LABOR@$lcs", "LABOR");

if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
}
$cursor = ora_open($conn);

$month = $HTTP_GET_VARS['month'];
$year = $HTTP_GET_VARS['year'];

if ($month =="")
	$month = date('m');
if ($year =="")
	$year = date('Y');

$first_of_month = mktime(0,0,0,$month,1,$year);
$days_in_month = date('t', $first_of_month);
$cutoff = array();
for($i = 1; $i <=$days_in_month; $i++)
{
	$sql = "select cutoff from employee_hire_cutoff
		where hire_date = to_date('$month/$i/$year','mm/dd/yyyy')";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
	if (ora_fetch($cursor)){
		$cutoff[$i] = ora_getcolumn($cursor, 0);
	}else{
		$cutoff[$i] = "&nbsp;";
	}
} 

$time = time();
$today = date('j',$time);
$days = array($today=>array(NULL,NULL,'<span style="color: red; font-weight: bold; font-size: larger; text-decoration: blink;">'.$today.'</span>'));

for($i = 1; $i <=$days_in_month; $i++)
{
	$days[$i] = array('edit_hire_cutoff.php?date='.$month.'/'.$i.'/'.$year,'linked-day');	
}
if ($month == 1){
	$pre_month = 12;
	$pre_year = $year -1;
        $next_month = $month + 1;
        $next_year = $year;
}else if ($month == 12){
        $pre_month = $month - 1;
        $pre_year = $year;
	$next_month = 1;
	$next_year = $year + 1;
}else{
	$pre_month = $month - 1;
	$pre_year = $year;
	$next_month = $month + 1;
	$next_year = $year;
}

$pn = array('&laquo;'=>'hire_cutoff.php?year='.$pre_year.'&month='.$pre_month, 
	    '&raquo;'=>'hire_cutoff.php?year='.$next_year.'&month='.$next_month);
$calendar =  generate_calendar($cutoff, $year, $month, $days, 3, NULL, 0,$pn);

ora_close($cursor);
ora_logoff($conn);
?>
<form action="hire_cutoff_process.php"  method="post" name="hire">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Daily Hiring Cutoff for B Employees
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

<table width="100%" align="center"  border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="50%">
          <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
             <tr>
	      	<td><?= $calendar ?></td>
	     </tr>	
          </table>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
      </td>
   </tr>
</table>
