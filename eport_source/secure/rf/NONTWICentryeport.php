<?

	$user = $HTTP_COOKIE_VARS["eport_user"];

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);

	if(strtoupper($user) == "DOLEIN"){
		$color = "#0000FF";
		$bottom_message = "By recording a Barcode you are stating that the NONTWIC driver is now entering the Dole Yard.";
		$title_words = "CHECK IN";
	} else {
		$color = "#FF0000";
		$bottom_message = "By recording a Barcode you are stating that the NONTWIC driver is now exiting the Dole Yard.";
		$title_words = "CHECK OUT";
	}
	$top_message = "";

	$submit = $HTTP_POST_VARS['submit'];
	$Barcode = strtoupper($HTTP_POST_VARS['Barcode']);

	if($submit == "Record Barcode" && $Barcode != ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			echo "<font color=\"#FF0000\">Invalid Barcode; Barcode not scanned at front gate.  Cannot proceed until Gate verifys (scans).</font>";
		} else {
			$sql = "INSERT INTO NON_TWIC_SCANS (BARCODE, SCAN_TIME, USER_ID)
					VALUES
					('".$Barcode."',
					SYSDATE,
					'".$user."')";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);

			$sql = "SELECT NEW_STATUS FROM NON_TWIC_STATUS_CHANGES WHERE USER_LOGIN = '".$user."' AND NEW_STATUS IS NOT NULL";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// do nothing
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = '".$row['NEW_STATUS']."' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($cursor, $sql);
				$ora_success = ora_exec($cursor, $sql);
				
				$sql = "SELECT FIRST_NAME, LAST_NAME, LISCENSE_NBR FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($cursor, $sql);
				$ora_success = ora_exec($cursor, $sql);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if(strtoupper($user) == "DOLEIN"){
					$top_message = "Barcode # ".$Barcode."<br>(Name:  ".$row['LAST_NAME'].", ".$row['FIRST_NAME']."   Licence# ".$row['LISCENSE_NBR'].")<br>Is now in the Dole Yard, and no longer having it's TWIC status monitored.<br>";
				} else {
					$top_message = "Barcode # ".$Barcode."<br>(Name:  ".$row['LAST_NAME'].", ".$row['FIRST_NAME']."   Licence# ".$row['LISCENSE_NBR'].")<br>Is now exiting Dole Yard, and has resumed TWIC monitoring.<br>";
				}
			}
/*
			if($user == "DOLEIN"){
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'OFFPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($cursor, $sql);
				$ora_success = ora_exec($cursor, $sql);
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'INPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($cursor, $sql);
				$ora_success = ora_exec($cursor, $sql);
			}
*/

		}
	}


/*
	if($submit == "Save Information"){
		$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET
				ENTERED_BY = '".$user."',
				ENTRY_DATETIME = SYSDATE,
				FIRST_NAME = '".$HTTP_POST_VARS['firstname']."',
				LAST_NAME = '".$HTTP_POST_VARS['lastname']."',
				LISCENSE_NBR = '".$HTTP_POST_VARS['License']."',
				STATE_CODE = '".$HTTP_POST_VARS['state']."',
				COMPANY_NAME = '".$HTTP_POST_VARS['company']."',
				TRAILER_NBR = '".$HTTP_POST_VARS['trailer']."',
				WHOLE_DAY_PASS = '".strtoupper($HTTP_POST_VARS['daypass'])."'
				WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
	}
*/

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color=<? echo $color; ?>>NONTWIC Trucker <? echo $title_words; ?>
</font>
	    <hr>
	 </p>
      </td>
	</tr>
	<tr>
      <td width="1%">&nbsp;</td>
	  <td><font size="5" face="Verdana" color="<? echo $color; ?>"><? echo $top_message; ?></td>
	 </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="get_BC" method="post" action="NONTWICentryeport_index.php">
	<tr>
		<td><font face="Verdana" size="3">Barcode:  </font></td>
		<td><input type="text" name="Barcode" size="30" maxlength="30">  <input type="submit" name="submit" value="Record Barcode"></td>
	</tr>
	<tr>
		<td colspan="2"><br><br><font face="Verdana" size="3" color="<? echo $color; ?>">You are logged in as <? echo $user; ?>.
				<? echo $bottom_message; ?></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($Barcode != ""){
		$sql = "SELECT FIRST_NAME, LAST_NAME, LISCENSE_NBR, STATE_CODE, COMPANY_NAME, TRAILER_NBR, WHOLE_DAY_PASS FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		$row = array();
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">Invalid Barcode; Barcode not scanned at front gate.  Cannot proceed until Gate verifys (scans).</font>";
		} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<!-- <form name="save_BC" method="post" action="NONTWICentryeport_index.php"> !-->
<!-- <input type="hidden" name="Barcode" value="<? echo $Barcode; ?>"> !-->
	<tr>
		<td width="20%"><font size="2" face="Verdana">First Name:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['FIRST_NAME']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Last Name:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['LAST_NAME']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">License:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['LISCENSE_NBR']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">State:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['STATE_CODE']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Company:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMPANY_NAME']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Trailer #:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['TRAILER_NBR']; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Whole day Pass?:  </font></td>
		<td><font size="2" face="Verdana"><? echo $row['WHOLE_DAY_PASS']; ?></td>
	</tr>
<!--	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Information"></td>
	</tr> !-->
<!-- </form> !-->
</table>
<?
		}
	}
?>