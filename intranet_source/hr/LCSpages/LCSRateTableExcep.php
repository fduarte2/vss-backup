<?
/* Adam Walter, June 2007.
*  
*	This webpage ties in with the ATS system; it allows Sylvia to
*	Manage a table by which "anniversary dates" are kept.  Said
*	Table is used in a cron job to send email alerts to indicate
*	Whenever people are entitled to "raises" in their vacation times.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HR - LCD RATES";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HR system");
    include("pow_footer.php");
    exit;
  }

  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
  $user_occ = $userdata['user_occ'];


  include("connect.php");
  $conn = ora_logon("LABOR@LCS", "LABOR");
//  $conn = ora_logon("LABOR@BNITEST", "LABOR");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);
  $short_term_cursor = ora_open($conn);

  $Command = $HTTP_POST_VARS['Command'];
  $rate = $HTTP_POST_VARS['rate'];
  $service = $HTTP_POST_VARS['service'];
  $type = $HTTP_POST_VARS['type'];
//  $sick = $HTTP_POST_VARS['sick'];
/*
  if($Command == 'ADD'){
	  $sql = "INSERT INTO VACATION_RATE (BREAK_POINT_YEAR, HOURLY_VACATION_RATE) VALUES ('".$years."', '".$hours."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }
*/
  if($Command == 'EDIT'){
	  $sql = "UPDATE LCS_SERVICE_CODE_EXCEPTIONS 
				SET NEW_RATE = '".$rate."',
					LAST_UPDATE_ON = SYSDATE,
					LAST_UPDATE_BY = '".$user."'
				WHERE SERVICE_CODE = '".$service."'
					AND RATE_TYPE = '".$type."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }
/*
  if($Command == 'DELETE'){
	  $sql = "DELETE FROM VACATION_RATE WHERE BREAK_POINT_YEAR = '".$years."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }
*/
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Edit LCS Special Rate Table
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr><td align="center">
   	  <table border="1" cellpadding="4" cellspacing="0">
		<tr>
			<td colspan="5" align="center"><font size="2" face="Verdana">Click on a Service Code to change it's rate.</font>
	     <tr>
		 	 <td><b><font size="2" face="Verdana">Service Code</font></b></td>
	         <td><b><font size="2" face="Verdana">Add/Replace</font></b></td>
	         <td><b><font size="2" face="Verdana">Hourly Rate</font></b></td>
	         <td><b><font size="2" face="Verdana">Last Update By</font></b></td>
	         <td><b><font size="2" face="Verdana">Last Update On</font></b></td>
         </tr>
<?
  $sql = "SELECT SERVICE_CODE, NEW_RATE, RATE_TYPE, LAST_UPDATE_BY, TO_CHAR(LAST_UPDATE_ON, 'MM/DD/YYYY HH24:MI') THE_ON
			FROM LCS_SERVICE_CODE_EXCEPTIONS ORDER BY SERVICE_CODE, RATE_TYPE";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  $service= $row['SERVICE_CODE'];
      $rate = $row['NEW_RATE'];
      $type = $row['RATE_TYPE'];
      $by = $row['LAST_UPDATE_BY'];
      $on = $row['THE_ON'];

	$sql = "SELECT SERVICE_NAME FROM SERVICE
			WHERE SERVICE_CODE = '".$service."'";
	$statement = ora_parse($short_term_cursor, $sql);
	ora_exec($short_term_cursor);
	ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$servname = $short_term_row['SERVICE_NAME'];

?>
		<tr>
		   <td align="left"><font size="2" face="Verdana"><a href="modify_LCSRateExcep.php?service=<? echo $service; ?>&type=<? echo $type; ?>"><? echo $servname; ?></a></font></td>
		   <td><font size="2" face="Verdana"><? echo $type ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $rate ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $by ?></font>&nbsp;</td>
		   <td><font size="2" face="Verdana"><? echo $on ?></font>&nbsp;</td>
		</tr>
<?
  }
?>
		<tr>
			<td colspan="5" align="left"><font size="2" face="Verdana">Notes:<br>1.	These are special rates only.  Rates in ADP need to be updated as well prior to running payroll.<br>2.	 If an employee hours are coded to one of the above Service codes, then the rate specified there would be applied as stated (add or replace). This rate will add to or replace any other prior rate rules.
</font></td>
		</tr>
      </table>
   </td></tr>
<!--   <tr>
      <td align="center"><font size="2" face="Verdana"><a href="/hr/ATSpages/anniv_add.php">Add a new entry Anniversary table</a></font></td>
   </tr> !-->
</table>

<? include("pow_footer.php"); ?>
