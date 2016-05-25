<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisors Applications";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Supervisors system");
    include("pow_footer.php");
    exit;
  }

  $today = date('m/d/Y');
  $conn = ora_logon("SAG_Owner@BNI_BACKUP", "SAG_dev");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $Voyage_cursor = ora_open($conn);
  $sql = "SELECT * FROM Voyage WHERE DATE_DEPARTED IS NULL ORDER BY LR_NUM DESC";
  // echo $sql;
  $statement = ora_parse($Voyage_cursor, $sql);
  ora_exec($Voyage_cursor);
  ora_fetch_into($Voyage_cursor, $Voyage_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $Vessel_name_cursor = ora_open($conn);

  $Vessel_num = $HTTP_POST_VARS[Vessel_num];
  $now = date("m/j/y g:i:s a");
  if ($HTTP_POST_VARS[shipped_time] != "") {
	  $Entry_time = strtotime($HTTP_POST_VARS[shipped_time]);
	  $Next_Action_time = $Entry_time;
	  // $Next_Action_time = $Entry_time + 21600;
  }


//  $Shipped_time = date("m/j/y g:i a", $HTTP_POST_VARS[shipped_date].$HTTP_POST_VARS[shipped_hour].$HTTP_POST_VARS[shipped_minute].$HTTP_POST_VARS[shipped_AmorPm]);

?>


<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Sailed Ship Entry Page
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana"><b></b></font>
	  </td>
	</tr>
	<tr>
	<td>
            <form action="Enter_Shipped_Vessel.php" method="post" name="scan">
			Vessel: <select name="Vessel_num">
			<?
				do {
					$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM =" . $Voyage_row['LR_NUM'];
					$statement = ora_parse($Vessel_name_cursor, $sql);
					ora_exec($Vessel_name_cursor);
					ora_fetch_into($Vessel_name_cursor, $Vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

					printf("<option value=\"%s\">%s</option>", $Voyage_row['LR_NUM'], $Vessel_name_row['VESSEL_NAME']);
				} while (ora_fetch_into($Voyage_cursor, $Voyage_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			?>
	 </td>
	 </tr>
	 <tr>
	 <td>
		<font size="2">Enter Departed Date & Time In</font>
	 </td>
	 </tr>
	 <tr>
	 <td>
		<font size="2">MM/DD/YYYY HH:MI 24-hour format</font>
	 </td>
	 </tr>
	 <tr>
	 <td>
			Departed: <input type="textbox" name="shipped_time" size=20 value="">
	 <?
	   //    Shipped at: <input type="textbox" name="shipped_date" size=10 value=""><a href="javascript:show_calendar('scan.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0>&nbsp;&nbsp;&nbsp;&nbsp;<input type="textbox" name="shipped_hour" size=2 value="">&nbsp;&nbsp;<input type="textbox" name="shipped_minute" size=2 value="">&nbsp;&nbsp;<select name="shipped_AmorPm"><option value="AM">AM</option><option value="PM">PM</option></select>
	 ?>
	 </td>
	 </tr>
	 <tr>
	 <td>
	 <input type="submit" value="Submit">
	 </form>
	 </td>
	 </tr>
	 <tr>
	 </tr>
	 <tr>
	 </tr>
	 <tr>
	 </tr>

<?
	if ($Vessel_num != "" && $Entry_time != "") {
		$Insert_cursor = ora_open($conn);
		$temp_Entry_time = date("m/j/y g:i:s a", $Entry_time);
		$temp_Next_action_time = date("m/j/y g:i:s a", $Next_Action_time);
//		$sql = "INSERT INTO VESSEL_CLOSING VALUES ($Vessel_num, 'SHIPPED', $temp_Next_action_time,'',$temp_Entry_time)";
		$sql = "INSERT INTO VESSEL_CLOSING VALUES ($Vessel_num, 'SAILED',
			to_date('".$temp_Next_action_time."', 'MM/DD/YYYY HH:MI:SS AM'),'',
			to_date('".$temp_Entry_time."', 'MM/DD/YYYY HH:MI:SS AM'))";
		// echo $sql;
		$statement = ora_parse($Insert_cursor, $sql);
		ora_exec($Insert_cursor);

		$Update_cursor = ora_open($conn);
		$sql = "UPDATE VOYAGE SET DATE_DEPARTED = to_date('".$temp_Entry_time."', 'MM/DD/YYYY HH:MI:SS AM') WHERE LR_NUM = $Vessel_num";
		// echo $sql;
		$statement = ora_parse($Update_cursor, $sql);
		ora_exec($Update_cursor);

		ora_close($Update_cursor);
		ora_close($Insert_cursor);
		ora_commit($conn);
		ora_logoff($conn);

?>
	<tr>
	<td>
		<font size=2><b>Entry made for ship # <? echo $Vessel_num; ?></b></font>
	</td>
	</tr>
<?
	}
?>
<? include("pow_footer.php"); ?>
