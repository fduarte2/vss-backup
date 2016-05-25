<?php
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to pribnt Steel picklists.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel Picklist";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
?>


<h1>SSAB Steel Create Picklist</h1>
<a href="index_steel.php">Return to Main Steel Page</a>


<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="new" action="steel_picklist_print.php" method="post">
	<tr>
		<td>Port Order#: <input type="text" name="PORT_num" size="12" maxlength="12"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Generate Picklist"></td>
	</tr>
</form>
</table>
<?php
	include("pow_footer.php");
?>