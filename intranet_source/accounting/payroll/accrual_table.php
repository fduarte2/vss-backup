<?
/* Created by Adam Walter, Feb 2007.
*  The payroll accrual script that was in use from 2003-2007 was designed hardcoded, and
*  required regular changing.  This was... well, bad.  As such, I am rewriting it to 
*  pull values from a database.
*  This file is the one that Accounting can sue to maintain the table of values.
********************************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Payroll Accrual";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }

   // Connect to BNI
   $ora_connBNI = ora_logon("SAG_OWNER@BNI", "SAG");
   if (!$ora_connBNI) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_connBNI));
      exit;
   }
   $BNIcursor = ora_open($ora_connBNI);
   if (!$BNIcursor) {
      printf("Error opening a cursor on Oracle Server: ");
      printf(ora_errorcode($BNIcursor));
      exit;
   }

   $emp_type = $HTTP_POST_VARS['emp_type'];
   $pay_code = $HTTP_POST_VARS['pay_code'];
   $gl_code = $HTTP_POST_VARS['gl_code'];
   $avg_wage = $HTTP_POST_VARS['avg_wage'];
   $multiplier = $HTTP_POST_VARS['multiplier'];
   $Command = $HTTP_POST_VARS['Command'];

//   echo $emp_type." ".$pay_code." ".$gl_code." ".$avg_wage." ".$multiplier." ".$Command."<BR>";

	if($Command == "ADD"){
		$sql = "INSERT INTO PARTIAL_WEEK_ACCRUAL_MAP (EMP_TYPE, PAY_CODE, GL_CODE, MULTIPLIER, AVERAGE_WAGE) VALUES ('".$emp_type."', '".$pay_code."', ".$gl_code.", ".$multiplier.", ".$avg_wage.")";
	    $statement = @ora_parse($BNIcursor, $sql);
		if(!@ora_exec($BNIcursor)){
			echo "There was a problem with your entry:<BR>";
			echo ora_error($BNIcursor)."<BR>";
			echo $sql."<BR>";
			echo "<font color=\"FF0000\">No data was recorded.</font>  If you feel this was in error, please contact TS.<BR>";
		}
	}

	if($Command == "EDIT"){
		$sql = "UPDATE PARTIAL_WEEK_ACCRUAL_MAP SET GL_CODE = '".$gl_code."', MULTIPLIER = '".$multiplier."', AVERAGE_WAGE = '".$avg_wage."' WHERE EMP_TYPE = '".$emp_type."' AND PAY_CODE = '".$pay_code."'";
	    $statement = @ora_parse($BNIcursor, $sql);
		if(!@ora_exec($BNIcursor)){
			echo "There was a problem with your entry:<BR>";
			echo ora_error($BNIcursor)."<BR>";
			echo $sql."<BR>";
			echo "<font color=\"FF0000\">No data was recorded.</font>  If you feel this was in error, please contact TS.<BR>";
		}
	}

	if($Command == "DELETE"){
		$sql = "DELETE FROM PARTIAL_WEEK_ACCRUAL_MAP WHERE EMP_TYPE = '".$emp_type."' AND PAY_CODE = '".$pay_code."'";
	    $statement = @ora_parse($BNIcursor, $sql);
		if(!@ora_exec($BNIcursor)){
			echo "There was a problem with your entry:<BR>";
			echo ora_error($BNIcursor)."<BR>";
			echo $sql."<BR>";
			echo "<font color=\"FF0000\">No data was recorded.</font>  If you feel this was in error, please contact TS.<BR>";
		}
	}

	$bgcolor = "#FFFFFF";

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Accrual Values Table
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="center"><font size="3" face="Verdana"><a href="/accounting/payroll/add_to_accrual.php">Add a new entry to the GL Code table.</a></font></td>
   </tr>
   <tr><td>
   	  <table border="1" width="100%" cellpadding="4" cellspacing="0">
	     <tr bgcolor=<? echo $bgcolor; ?>>
		 	 <td width="18%"><b><font size="2" face="Verdana"><nobr>Employee Type</nobr></font></b></td>
	         <td width="18%"><b><font size="2" face="Verdana"><nobr>Pay Code</nobr></font></b></td>
	         <td width="18%"><b><font size="2" face="Verdana">GL Code</font></b></td>
			 <td width="18%"><b><font size="2" face="Verdana">Rate Multiplier</font></b></td>
			 <td width="18%"><b><font size="2" face="Verdana">Avg Wage</font></b></td>
			 <td width="18%"><b><font size="2" face="Verdana">&nbsp;</font></b></td>
		 </tr>
<?
  $sql = "SELECT * FROM PARTIAL_WEEK_ACCRUAL_MAP ORDER BY EMP_TYPE, PAY_CODE";
  $statement = ora_parse($BNIcursor, $sql);
  ora_exec($BNIcursor);
  while (ora_fetch_into($BNIcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  if($bgcolor == "#FFFFFF"){
		  $bgcolor = "#F0F0F0";
	  } else {
		  $bgcolor = "#FFFFFF";
	  }

	  $emp_type = $row['EMP_TYPE'];
	  $pay_code = $row['PAY_CODE'];
	  $GL_code= $row['GL_CODE'];
	  $multiplier = $row['MULTIPLIER'];
	  $avg_wage = $row['AVERAGE_WAGE'];

?>
		<tr bgcolor=<? echo $bgcolor; ?>>
		   <td><font size="2" face="Verdana"><? echo $emp_type ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $pay_code ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $GL_code ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $multiplier ?></font></td>
		   <td><font size="2" face="Verdana"><? echo $avg_wage ?></font></td>
           <td><font size="2" face="Verdana"><a href="/accounting/payroll/modify_accrual.php?emp_type=<? echo $emp_type; ?>&pay_code=<? echo $pay_code; ?>">Edit</a></font></td>
		</tr>

<?
  }
?>
      </table>
   </td></tr>
</table>

<? include("pow_footer.php"); ?>
