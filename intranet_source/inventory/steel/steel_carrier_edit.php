<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to edit steel Carriers.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel orderss";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$ID = $HTTP_POST_VARS['ID'];
	$submit = $HTTP_POST_VARS['submit'];
///	echo $submit."aaa<br>";

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
	
	$sql = "SELECT NAME
			FROM STEEL_CARRIERS
			WHERE CARRIER_ID = '".$ID."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$name = ociresult($stid, "NAME");

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Steel Carrier Edit Page - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" width="65%" cellpadding="4" cellspacing="0">
<form name="save" action="steel_carrier_view.php" method="post">
<input type="hidden" name="ID" value="<? echo $ID; ?>">
	<tr>
		<td>Name:</td>
		<td><input type="text" name="name" size="35" maxlength="35" value="<? echo $name; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="<? echo $submit; ?>"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>