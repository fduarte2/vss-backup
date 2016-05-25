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

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Reports
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<!--	<tr>
      <td width="2%">&nbsp;</td>
      <td align="left" colspan="2">
	    <font size="2" face="Verdana"><b>Supervisor Reports:</b></font>
      </td>
	</tr> !-->
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="truck_activity.php"><font size="2" face="Verdana">Outbound Shipment Counts</font></a></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../../images/yellowbulletsmall.gif"></td>
		<td align="left"><a target="../../inventory/CargoInHouse.php" href="../../inventory/CargoInHouse.php"><font size="2" face="Verdana">Chilean Cargo In House (by Vessel)</font></a></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="sort_upload_summary_v2.php"><font size="2" face="Verdana">Sort File Summary</font></a></td>
	</tr>
<?
	if($user == "fvignuli" || $user == "ddonofrio"){
?>
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="4%" align="left"><img src="../../images/yellowbulletsmall.gif"></td>
		<td align="left"><a href="../../finance/reports/inbound_report.php"><font size="2" face="Verdana">Inbound Report</font></a></td>
	</tr>
<?
	}
?>

</table>

<?
	include("pow_footer.php");
?>