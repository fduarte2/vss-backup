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

  $Command = $HTTP_POST_VARS['Command'];
  $rate = $HTTP_POST_VARS['rate'];
  $service = $HTTP_POST_VARS['service'];
//  $sick = $HTTP_POST_VARS['sick'];
/*
  if($Command == 'ADD'){
	  $sql = "INSERT INTO VACATION_RATE (BREAK_POINT_YEAR, HOURLY_VACATION_RATE) VALUES ('".$years."', '".$hours."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }
*/
  if($Command == 'EDIT'){
	  $sql = "UPDATE LCS_BASE_RATE 
				SET RATE_AMOUNT = '".$rate."',
					LAST_UPDATE_ON = SYSDATE,
					LAST_UPDATE_BY = '".$user."'
				WHERE SERVICE_ID = '".$service."'";
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
            <font size="5" face="Verdana" color="#0066CC">Edit LCS Rate Table
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
			<td colspan="5" align="center"><font size="2" face="Verdana">Click on a Service Type to change it's rate.</font>
	     <tr>
		 	 <td><b><font size="2" face="Verdana">Service Type</font></b></td>
	         <td><b><font size="2" face="Verdana">Hourly Rate</font></b></td>
	         <td><b><font size="2" face="Verdana">Description</font></b></td>
	         <td><b><font size="2" face="Verdana">Last Update By</font></b></td>
	         <td><b><font size="2" face="Verdana">Last Update On</font></b></td>
         </tr>
<?
  $sql = "SELECT SERVICE_ID, RATE_AMOUNT, TEXT_DESCRIPTION, LAST_UPDATE_BY, TO_CHAR(LAST_UPDATE_ON, 'MM/DD/YYYY HH24:MI') THE_ON
			FROM LCS_BASE_RATE ORDER BY SERVICE_ID";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  $service= $row['SERVICE_ID'];
      $rate = $row['RATE_AMOUNT'];
      $desc = $row['TEXT_DESCRIPTION'];
      $by = $row['LAST_UPDATE_BY'];
      $on = $row['THE_ON'];

?>
		<tr>
		   <td align="left"><font size="2" face="Verdana"><a href="modify_LCSRate.php?service=<? echo $service; ?>"><? echo $service; ?></a></font></td>
		   <td><font size="2" face="Verdana"><? echo $rate ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $desc ?></font>&nbsp;</td>
		   <td><font size="2" face="Verdana"><? echo $by ?></font>&nbsp;</td>
		   <td><font size="2" face="Verdana"><? echo $on ?></font>&nbsp;</td>
		</tr>
<?
  }
?>
		<tr>
			<td colspan="5" align="left"><font size="2" face="Verdana">Notes:<br>1. These are rates for exception situations only. Rates in ADP must be updated as well prior to running payroll.<br>2. The above rates would be applied if an employee performs a duty in one of the above categories even if their base rate were to be a lower rate.<br>3. Also see the other screen <a href="LCSRateTableExcep.php">Modify Service Code Rate Exceptions</a>. If an employee is coded to one of these Service codes, then the rate specified there would be applied as stated in that screen.
</font></td>
		</tr>
      </table>
   </td></tr>
<!--   <tr>
      <td align="center"><font size="2" face="Verdana"><a href="/hr/ATSpages/anniv_add.php">Add a new entry Anniversary table</a></font></td>
   </tr> !-->
</table>

<? include("pow_footer.php"); ?>
