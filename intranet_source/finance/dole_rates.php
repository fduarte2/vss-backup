<?
/*
*	Adam Walter, Feb 2013
*
*	Giving Finance the ability to modify their own "Dole Paper Rates"
************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Changes"){
		$max_count = $HTTP_POST_VARS['rows'];
		$cust = $HTTP_POST_VARS['cust'];
		$new_rate = $HTTP_POST_VARS['new_rate'];

		$changes = 0;
		for($i = 0; $i < $max_count; $i++){
			if($new_rate[$i] != "" && is_numeric($new_rate[$i])){
				$sql = "UPDATE LU_DOLEPAPER_TRUCKLOADING_RATE
						SET RATE = '".$new_rate[$i]."'
						WHERE CUSTOMER_ID = '".$cust[$i]."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				$changes++;
			}
		}
		echo "<font color=\"#0000FF\">".$changes." rows have beed saved.</font>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Paper Rate Adjustments
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
<form name="get_data" action="dole_rates.php" method="post">
	<tr>
		<td><b>Customer Name</b></td>
		<td><b>Rate</b></td>
		<td>&nbsp;</td>
	</tr>
<?
	$counter = 0;
	$sql = "SELECT CP.CUSTOMER_ID, CUSTOMER_NAME, RATE
			FROM CUSTOMER_PROFILE CP, LU_DOLEPAPER_TRUCKLOADING_RATE LDTR
			WHERE CP.CUSTOMER_ID = LDTR.CUSTOMER_ID
			ORDER BY CUSTOMER_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
	<input type="hidden" name="cust[<? echo $counter; ?>]" value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>">
	<tr>
		<td><? echo ociresult($stid, "CUSTOMER_NAME"); ?></td>
		<td><? echo ociresult($stid, "RATE"); ?></td>
		<td><input type="text" name="new_rate[<? echo $counter; ?>]" size="10"></td>
	<tr>
<?
		$counter++;
	}
?>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Save Changes"></td>
	</tr>
	<input type="hidden" name="rows" value="<? echo $counter; ?>">
</form>
</table>
<?
	include("pow_footer.php");
