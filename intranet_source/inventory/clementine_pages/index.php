<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine Function Area";
  $area_type = "INVE"; /// ??? no marketing area :(

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementine Main Area</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="3%">&nbsp;</td>
		<td width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
		<td><font size="3" face="Verdana"><a href="clementine_manual_entry.php">Manual Order Entry</a></font></td>
   </tr>
	<tr>
		<td width="3%">&nbsp;</td>
		<td width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
		<td><font size="3" face="Verdana"><a href="../clementine_locations/index.php">Clementine Locations</a></font></td>
   </tr>
   <tr>
		<td width="3%">&nbsp;</td>
		<td width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
		<td><font size="3" face="Verdana"><a href="ClemNours.php">Hatch-Deck Comparison</a></font></td>
   </tr>
   <tr>
		<td width="3%">&nbsp;</td>
		<td width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
		<td><font size="3" face="Verdana"><a href="clem_report_choose.php">Clementine Inventory Display</a></font></td>
   </tr>
</table>

<?
	include("pow_footer.php");
?>