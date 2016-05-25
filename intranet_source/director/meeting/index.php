<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Meeting Main Page";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Meeting Main Page
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana"><a href="meeting.php">Meeting Agenda</font></td>
	</tr>
	<tr>
		<td width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana"><a href="hot_topic.php">Hot List</font></td>
	</tr>
</table>
<br />

<? include("pow_footer.php"); ?>
