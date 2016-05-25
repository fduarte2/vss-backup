<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to review steel Carriers.
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


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$ID = $HTTP_POST_VARS['ID'];
	$submit = $HTTP_POST_VARS['submit'];
	$name = $HTTP_POST_VARS['name'];

	if($submit == "Edit Carrier"){
		$sql = "UPDATE STEEL_CARRIERS
				SET NAME = '".$name."'
				WHERE CARRIER_ID = '".$ID."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Row edited.</font>";
	} elseif($submit == "Create Carrier") {
		$sql = "INSERT INTO STEEL_CARRIERS
					(CARRIER_ID,
					NAME,
					CREATED_LOGIN_ID)
				VALUES
					(STEEL_CARRIER_SEQ.NEXTVAL,
					'".$name."',
					'".$user."')";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Row created.</font>";
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Carriers - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" width="50%" cellpadding="4" cellspacing="0">
<form name="new" action="steel_carrier_edit.php" method="post">
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Create Carrier"></td>
	</tr>
</form>
	<tr>
		<td>Name</td>
		<td>&nbsp;</td>
	</tr>
<?
	$sql = "SELECT CARRIER_ID, NAME
			FROM STEEL_CARRIERS
			ORDER BY NAME";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
<form name="edit<? echo ociresult($stid, "CARRIER_ID"); ?>" action="steel_carrier_edit.php" method="post">
<input type="hidden" name="ID" value="<? echo ociresult($stid, "CARRIER_ID"); ?>">
	<tr>
		<td><font size="2"><? echo ociresult($stid, "NAME"); ?></font></td>
		<td><input type="submit" name="submit" value="Edit Carrier"></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");