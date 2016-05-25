<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisors Applications";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Supervisors Applications
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="2%">&nbsp;</td>
      <td align="left" colspan="2">
	    <font size="2" face="Verdana"><b>From here you can access applications needed by Supervisors.</b></font>
      </td>
	</tr>
	<tr>
	   <td height="1" colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="productivity_report_pages/SUPV_vessel_closing.php"><font size="2" face="Verdana">Vessel Closing</font></a></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="Sprinkler_Report/sprinkler_upload.php"><font size="2" face="Verdana">Sprinkler Scanner Upload</font></a></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="../finance/vessel_arrival_notify.php"><font size="2" face="Verdana">Barge Arrival Notification</font></a></td>
	</tr>
<?
	if((array_search('DIRE', $user_types) !== FALSE && array_search('SUPV', $user_types) !== FALSE) || array_search("ROOT", $user_types) !== FALSE || $user == "ddonofrio") {
?>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="productivity_report_pages/SUPV_vessel_close_override.php"><font size="2" face="Verdana">Vessel Entry Override</font></a></td>
	</tr>
	
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="/director/data_warehouse/reband_report.php"><font size="2" face="Verdana">Reband Report (Argen Juice)</a></font></td>
   </tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="./faulty/faulty_pallet.php"><font size="2" face="Verdana">Faulty Pallet Report</a></font></td>
   </tr>

   <tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="/inventory/transfer_to_CS.php"><font size="2" face="Verdana">Transfer to Cold Storage</a></font></td>
   </tr>
<?
	}
?>
</table>

<? include("pow_footer.php"); ?>
