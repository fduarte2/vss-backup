<?
/*
*
*	Adam Walter, Feb 2013.
*
*	A screen for inventory to print Inhouse quantities by vessel.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "InHouse Inventory";
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
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Scanned In House Inventory by Vessel
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="new" action="inhouse_RF_by_vessel_pdf.php" method="post">
	<tr>
		<td>Vessel#: <input type="text" name="vessel" size="12" maxlength="12"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");