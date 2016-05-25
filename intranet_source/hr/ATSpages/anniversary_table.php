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
  $title = "HR - ATS Anniversary Amounts";
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
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $Command = $HTTP_POST_VARS['Command'];
  $years = $HTTP_POST_VARS['years'];
  $hours = $HTTP_POST_VARS['hours'];
//  $sick = $HTTP_POST_VARS['sick'];

  if($Command == 'ADD'){
	  $sql = "INSERT INTO VACATION_RATE (BREAK_POINT_YEAR, HOURLY_VACATION_RATE) VALUES ('".$years."', '".$hours."')";
	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'EDIT'){
	  $sql = "UPDATE VACATION_RATE SET HOURLY_VACATION_RATE = '".$hours."' WHERE BREAK_POINT_YEAR = '".$years."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($Command == 'DELETE'){
	  $sql = "DELETE FROM VACATION_RATE WHERE BREAK_POINT_YEAR = '".$years."'";
  	  $statement = ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Edit Anniversary Notification Table
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
		 	 <td><b><font size="2" face="Verdana">Years of service</font></b></td>
	         <td><b><font size="2" face="Verdana">Hourly Vacation Accrual</font></b></td>
         </tr>
<?
  $sql = "SELECT * FROM VACATION_RATE ORDER BY BREAK_POINT_YEAR";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  $y_o_s= $row['BREAK_POINT_YEAR'];
      $h_w = $row['HOURLY_VACATION_RATE'];

?>
		<tr>
		   <td align="left"><font size="2" face="Verdana"><a href="/hr/ATSpages/modify_anniv.php?years=<? echo $y_o_s; ?>"><? echo $y_o_s; ?></a></font></td>
		   <td align="right"><font size="2" face="Verdana"><? echo $h_w ?></font></td>
		</tr>
<?
  }
?>
      </table>
   </td></tr>
   <tr>
      <td align="center"><font size="2" face="Verdana"><a href="/hr/ATSpages/anniv_add.php">Add a new entry Anniversary table</a></font></td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
