<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Upload Ship Schedule";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
    	 <font size="5" face="Verdana" color="#0066CC">Ship Schedule Import</font>
	 <hr>
      </td>
   </tr>
</table>

<table border="0" align= left width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <? include("uploading.php"); ?>
	 <? include("pow_footer.php"); ?>
