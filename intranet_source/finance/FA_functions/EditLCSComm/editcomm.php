<?
/* Adam Walter, December 2006.  This page added to give Financial Analyst position
*  (Jon Jaffe at time of this writing) the ability to change service codes or
*  commodities in the LCS system for entries previously made by supervisors.
**********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Reports";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }


  $conn = ora_logon("LABOR@LCS", "LABOR");
  if(!$conn){
    echo "Error logging on to the LCS Oracle Server: " . ora_errorcode($conn);
    exit;
  }
  $cursor = ora_open($conn);

  $BNIconn = ora_logon("SAG_OWNER@BNI", "SAG");
  if(!$BNIconn){
    echo "Error logging on to the LCS Oracle Server: " . ora_errorcode($BNIconn);
    exit;
  }
  $BNIcursor = ora_open($BNIconn);




  $vessel = $HTTP_POST_VARS['vessel'];
  $getdate = $HTTP_POST_VARS['getdate'];
  $the_emp = $HTTP_POST_VARS['employee'];
  $the_super = $HTTP_POST_VARS['supervisor'];
  $submit = $HTTP_POST_VARS['submit'];
  $change_comm = $HTTP_POST_VARS['change_comm'];
  $new_comm = $HTTP_POST_VARS['new_comm'];
  $change_service = $HTTP_POST_VARS['change_service'];
  $new_service = $HTTP_POST_VARS['new_service'];
  $row_id = $HTTP_POST_VARS['row_id'];

  if($submit == "Update Records" && $row_id == ""){
	  echo "<font color=\"#FF0000\"> Please choose a (or all) rows to edit via the buttons on the right.<BR></font>";
  }
  if($submit == "Update Records" && $change_comm == "yes" && ($new_comm == "" || $new_comm == "N/A")){
	  echo "<font color=\"#FF0000\"> New commodity code was selected, but none was specified.<BR></font>";
  }
  if($submit == "Update Records" && $change_service == "yes" && ($new_service == "" || $new_service == "N/A")){
	  echo "<font color=\"#FF0000\"> New service code was selected, but none was specified.<BR></font>";
  }



  if($change_comm == "yes" && $new_comm != "" && $row_id != "" && $submit == "Update Records"){
	  $sql = "UPDATE HOURLY_DETAIL SET COMMODITY_CODE = '".$new_comm."' WHERE VESSEL_ID = '".$vessel."' AND EMPLOYEE_ID = '".$the_emp."' AND USER_ID = '".$the_super."' AND HIRE_DATE = to_date('".$getdate."', 'MM/DD/YYYY')";
	  if($row_id != "all"){
		  $sql .= " AND ROW_NUMBER = '".$row_id."'";
	  }
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);		
//	  echo $sql."<BR>";
  }

  if($change_service == "yes" && $new_service != "" && $row_id != "" && $submit == "Update Records"){
	  $sql = "UPDATE HOURLY_DETAIL SET SERVICE_CODE = '".$new_service."' WHERE VESSEL_ID = '".$vessel."' AND EMPLOYEE_ID = '".$the_emp."' AND USER_ID = '".$the_super."' AND HIRE_DATE = to_date('".$getdate."', 'MM/DD/YYYY')";
	  if($row_id != "all"){
		  $sql .= " AND ROW_NUMBER = '".$row_id."'";
	  }
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);		
//	  echo $sql."<BR>";
  }




?>
<script type="text/javascript">
   function change_comm_status() {
	   if (document.update_data.change_comm.checked == true){
		   document.update_data.new_comm.disabled = false
	   } else {
		   document.update_data.new_comm.disabled = true
	   }
   }

   function change_service_status() {
	   if (document.update_data.change_service.checked == true){
		   document.update_data.new_service.disabled = false
	   } else {
		   document.update_data.new_service.disabled = true
	   }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="5" face="Verdana" color="#0066CC">LCS entry modification</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<form name="choose_vessel" method="post" action="editcomm.php">
	<tr>
		<td colspan="6" align="center">
		<select name="vessel" onchange="document.choose_vessel.submit(this.form)">
			<option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LENGTH(LR_NUM) < 7 ORDER BY LR_NUM DESC";
	ora_parse($BNIcursor, $sql);
	ora_exec($BNIcursor);
	while(ora_fetch_into($BNIcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){?> selected <? } ?>><? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
		</select>
		</td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	</form>
<?
	// this part will only show up if a vessel is chosen
	if($vessel != ""){
?>
	<form name="choose_all" method="post" action="editcomm.php">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td colspan="2" width="30%">Date:  <input type="text" name="getdate" size="15" maxlength="10" value="<? if ($getdate != ""){ echo $getdate; } else { echo "MM/DD/YYYY"; } ?>"></td>
		<td colspan="2" width="40%">Employee:  <select name="employee">
						<option value="">&nbsp;</option>
<?
		$sql = "SELECT DISTINCT EM.EMPLOYEE_ID THE_EMP, EM.EMPLOYEE_NAME THE_NAME FROM EMPLOYEE EM, HOURLY_DETAIL HD WHERE EM.EMPLOYEE_ID = HD.EMPLOYEE_ID AND HD.VESSEL_ID = '".$vessel."' ORDER BY THE_EMP";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		// Get employee list that worked this vessel
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['THE_EMP']; ?>"<? if($row['THE_EMP'] == $the_emp){?> selected <?}?>><? echo $row['THE_EMP']." - ".$row['THE_NAME']; ?></option>
<?
		}
?>
						</select></td>
		<td colspan="2" width="30%">Supervisor:  <select name="supervisor">
			<option value="">&nbsp;</option>
<?
		$sql = "SELECT DISTINCT EM.EMPLOYEE_ID THE_EMP, EM.EMPLOYEE_NAME THE_NAME FROM EMPLOYEE EM, HOURLY_DETAIL HD WHERE EM.EMPLOYEE_ID = HD.USER_ID AND HD.VESSEL_ID = '".$vessel."' ORDER BY THE_EMP";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		// Get supervisor list that worked this vessel
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['THE_EMP']; ?>"<? if($row['THE_EMP'] == $the_super){?> selected <?}?>><? echo $row['THE_EMP']." - ".$row['THE_NAME']; ?></option>
<?
		}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Retrieve Records"></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	</form>
<?
		// and this if only gets run if they have completed the first 2 parts of this page.
		if($submit == "Retrieve Records" || $submit == "Update Records"){
			$sql = "SELECT TO_CHAR(START_TIME, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_TIME, 'MM/DD/YYYY HH24:MI') THE_END, EARNING_TYPE_ID, CUSTOMER_ID, SERVICE_CODE, COMMODITY_CODE, ROW_NUMBER FROM HOURLY_DETAIL WHERE VESSEL_ID = '".$vessel."' AND HIRE_DATE = TO_DATE('".$getdate."', 'MM/DD/YYYY') AND EMPLOYEE_ID = '".$the_emp."' AND USER_ID = '".$the_super."' ORDER BY THE_START";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
?>
	<form name="update_data" method="post" action="editcomm.php">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<input type="hidden" name="getdate" value="<? echo $getdate; ?>">
	<input type="hidden" name="employee" value="<? echo $the_emp; ?>">
	<input type="hidden" name="supervisor" value="<? echo $the_super; ?>">
	<tr>
		<td colspan="6" align="center">
		<table border="1" width="100%" cellpadding="3" cellspacing="0">
			<tr align="left">
				<td><font face="Verdana" size="2"><b>Start Time:</b></font></td>
				<td><font face="Verdana" size="2"><b>End Time:</b></font></td>
				<td><font face="Verdana" size="2"><b>Earning Type:</b></font></td>
				<td><font face="Verdana" size="2"><b>Customer Number:</b></font></td>
				<td><font face="Verdana" size="2"><b>Service Code:</b></font></td>
				<td><font face="Verdana" size="2"><b>Commodity Code:</b></font></td>
				<td><font face="Verdana" size="2"><b>LCS Row Number:</b></font></td>
			</tr>

<?
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr align="right">
				<td><font face="Verdana" size="2"><? echo $row['THE_START']; ?></font></td>
				<td><font face="Verdana" size="2"><? echo $row['THE_END']; ?></font></td>
				<td><font face="Verdana" size="2"><? echo $row['EARNING_TYPE_ID']; ?></font></td>
				<td><font face="Verdana" size="2"><? echo $row['CUSTOMER_ID']; ?></font></td>
				<td><font face="Verdana" size="2"><? echo $row['SERVICE_CODE']; ?></font></td>
				<td><font face="Verdana" size="2"><? echo $row['COMMODITY_CODE']; ?></font></td>
				<td><font face="Verdana" size="2"><input type="radio" value="<? echo $row['ROW_NUMBER']; ?>" name="row_id"><? echo $row['ROW_NUMBER']; ?></font></td>
			</tr>
<?
			}
?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td align="right"><font face="Verdana" size="2"><input type="radio" name="row_id" value="all"> All Entries</font></td>
			</tr>
		</table>
		<hr>
		</td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6" align="center">New Commodity:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="change_comm" value="yes" onclick="change_comm_status()">&nbsp;&nbsp;<input type="text" name="new_comm" value="N/A" disabled></td>
	</tr>
	<tr>
		<td colspan="6" align="center">New Service Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="change_service" value="yes" onclick="change_service_status()">&nbsp;&nbsp;<input type="text" name="new_service" value="N/A" disabled></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Update Records"></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	</form>
<?
		}
	}
?>
</table>


<? include("pow_footer.php"); ?>