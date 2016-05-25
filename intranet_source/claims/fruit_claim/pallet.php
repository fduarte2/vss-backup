<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Fruit Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit System (RF)</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="80%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <p><br /><font size="2" face="Verdana">Enter a Pallet ID to start creating a claim for fruit.</font><br /><br />
      </td>
   </tr>
</table>

<?
   include("select_pallet.php");
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <table border="0" width="95%" cellpadding="4" cellspacing="0">
<? 
   $pallet_id = $HTTP_COOKIE_VARS["pallet_id"];
   $schema = $HTTP_COOKIE_VARS["schema"];
   if ($pallet_id != "") {
?>
	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2">You selected Pallet # <?= $pallet_id ?> from the <?= $schema ?> database.</font>
		  <br /><br />
	       </td>
	    </tr>
<?
   if($input == 1){
     
?>
	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2">Pallet <?= $pallet_id ?> not found in the system- please re-enter and try again!</font>
		  <br /><br />
	       </td>
	    </tr>
<?
  }
  if($input == 2){
     
?>
	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2" color="red">Caution- you already made a claim (ID # <?= $claim ?>) for Pallet <?= $pallet_id ?>!</font>
		  <br /><br />
	       </td>
	    </tr>
<?
  }
?>
	    <tr>
	       <td width="5%" valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td width="45%" valign="middle">
		   <font face="Verdana" size="2" color="#000080">
		   <a href="add_main.php?system=RF">Proceed</a></font>
	       </td>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">
		  <a href="reports/tag_audit.php?pallet_id=<?= $pallet_id ?>&schema=<?= $schema ?>">Tag Audit</a></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
   </tr>
</table>
<?
  }
	 include("pow_footer.php");
?>
