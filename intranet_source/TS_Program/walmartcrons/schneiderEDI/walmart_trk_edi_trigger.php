<?
/*
*	Adam Walter, Jan 2011.
*
*	see the file "dole_schneider_trk_out_edi.php" (which is in the
*	folder TS_Program/walmartcrons/schneiderEDI as of this writing)
*	for the script that takes these entries and does stuff with them.
*
*	More or less, because inventory doesn't have enoug to do already...
*
*	Now if Walmart ever sends us trucked in cargo, they expect
*	EDI-confrimation of receipt, and inventory gets to trigger said EDI
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
//	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);

	$bol = str_replace("\'","`", $HTTP_POST_VARS['bol']);
	$cont = str_replace("\'","`", $HTTP_POST_VARS['cont']);
	$seal_1 = str_replace("\'","`", $HTTP_POST_VARS['seal_1']);
	$seal_2 = str_replace("\'","`", $HTTP_POST_VARS['seal_2']);
	$seal_3 = str_replace("\'","`", $HTTP_POST_VARS['seal_3']);
	$seal_4 = str_replace("\'","`", $HTTP_POST_VARS['seal_4']);
	$submit = $HTTP_POST_VARS['submit'];

	// a bit of validation
	if($submit != "" && $bol == ""){
		echo "<font color=\"#FF0000\">BOL is a required field, could not save</font><br>";
		$submit = "";
	}
	if($submit != "" && $cont == ""){
		echo "<font color=\"#FF0000\">Container is a required field, could not save</font><br>";
		$submit = "";
	}
	if($submit != "" && $seal_1 == ""){
		echo "<font color=\"#FF0000\">The first seal# is a required field, could not save</font><br>";
		$submit = "";
	}

	// alright, if it passed, then do the save, and CLEAR THE FIELDS, to prevent a duplicate send 
	// (if someone gets antsy with the save button)
	if($submit != ""){
		$variable_string = $bol.";".$cont.";".$seal_1;

		if($seal_2 != ""){
			$variable_string .= ";".$seal_2;
		}
		if($seal_3 != ""){
			$variable_string .= ";".$seal_3;
		}
		if($seal_4 != ""){
			$variable_string .= ";".$seal_4;
		}

		$sql = "INSERT INTO JOB_QUEUE
				(JOB_ID,
				SUBMITTER_ID,
				SUBMISSION_DATETIME,
				JOB_TYPE,
				JOB_DESCRIPTION,
				VARIABLE_LIST,
				COMPLETION_STATUS)
			VALUES
				(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
				'".$user."',
				SYSDATE,
				'FTP',
				'WMSCHTRKOEDI',
				'".$variable_string."',
				'PENDING')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		$display = "BoL:  ".$bol."<br>Container:  ".$cont."<br>Seal#1:  ".$seal_1;
		if($seal_2 != ""){
			$display .= "<br>Seal#2:  ".$seal_2;
		}
		if($seal_3 != ""){
			$display .= "<br>Seal#3:  ".$seal_3;
		}
		if($seal_4 != ""){
			$display .= "<br>Seal#4:  ".$seal_4;
		}

		echo "<font color=\"#0000FF\">Truck Recorded; EDI will be sent shortly.<br><br>".$display."<br></font>";

		$bol = "";
		$cont = "";
		$seal_1 = "";
		$seal_2 = "";
		$seal_3 = "";
		$seal_4 = "";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Inbound Truck EDI entry
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<form name="save_data" action="walmart_trk_edi_trigger.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>BoL:</b></font></td>
		<td align="left"><input name="bol" type="text" size="20" maxlength="20" value="<? echo $bol; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(required)</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Container:</b></font></td>
		<td align="left"><input name="cont" type="text" size="20" maxlength="20" value="<? echo $cont; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(required)</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Seal#1:</b></font></td>
		<td align="left"><input name="seal_1" type="text" size="20" maxlength="20" value="<? echo $seal_1; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(required)</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Seal#2:</b></font></td>
		<td align="left"><input name="seal_2" type="text" size="20" maxlength="20" value="<? echo $seal_2; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(optional)</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Seal#3:</b></font></td>
		<td align="left"><input name="seal_3" type="text" size="20" maxlength="20" value="<? echo $seal_3; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(optional)</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Seal#4:</b></font></td>
		<td align="left"><input name="seal_4" type="text" size="20" maxlength="20" value="<? echo $seal_4; ?>">&nbsp;&nbsp;<font size="2" face="Verdana">(optional)</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Record Truck Arrival"></td>
	</tr>
	</form>
</table>
<?
	include("pow_footer.php");
?>