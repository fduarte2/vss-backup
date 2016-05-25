<?
// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Inventory System - Walmart";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
printf("Access Denied from INVE system");
include("pow_footer.php");
exit;
}

$conn = ora_logon("SAG_OWNER@RF", "OWNER"); 
// $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";

if($conn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	printf(ora_errorcode($conn));
	exit;
}
$cursor = ora_open($conn);
$cursor_inner = ora_open($conn);
$Short_Term_Cursor = ora_open($conn);

$submit = $HTTP_POST_VARS['submit'];

if($submit == "Retrieve"){
	// if they are retrieving data, make sure it exists...
	$dcpo = $HTTP_POST_VARS['dcpo'];

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM WDI_LOAD_DCPO
			WHERE DCPO_NUM = '".$dcpo."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] <= 0){
		echo "<font color=\"#FF0000\">DCPO# ".$dcpo." Not Valid.<br></font>";
		$submit = "";
	}
}

if($submit == "Submit Override"){
	$ovr_reason = $HTTP_POST_VARS['ovr_reason'];
	$dcpo = $HTTP_POST_VARS['dcpo'];

	if($ovr_reason == ""){
		echo "<font color=\"#FF0000\">Submitted Overrides must have the Reason field entered.<br></font>";
	} else {
		$sql = "UPDATE WDI_LOAD_DCPO
				SET FIFO_OVERRIDE_BY = '".$user."',
					FIFO_OVERRIDE_DATE = SYSDATE,
					FIFO_OVERRIDE_REASON = '".$ovr_reason."',
					LAST_CHANGED_BY = '".$user."',
					LAST_CHANGED_DATE = SYSDATE
				WHERE DCPO_NUM = '".$dcpo."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		echo "<font color=\"#0000FF\">DCPO# ".$dcpo." Override Saved.<br></font>";
	}
}

if($submit == "Remove Override"){
	$dcpo = $HTTP_POST_VARS['dcpo'];
	
	$sql = "update WDI_LOAD_DCPO
			set FIFO_OVERRIDE_BY = Null,
				FIFO_OVERRIDE_DATE = Null,
				FIFO_OVERRIDE_REASON = Null,
				LAST_CHANGED_BY = '".$user."',
				LAST_CHANGED_DATE = sysdate
			where DCPO_NUM = '".$dcpo."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	echo "<font color=\"#0000FF\">FIFO Override removed.</font>";
}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">WALMART FIFO-Override Entry</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select_bills" action="WM_FIFO_override.php" method="post">
	<tr>
		<td>Enter DCPO #:</td>
		<td><input type="text" name="dcpo" size="15" maxlength="15" value="<? echo $dcpo; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><br><hr><br></td>
	</tr>
</form>
</table>
<?
if($submit != ""){
?>
<table border="0" cellpadding="4" cellspacing="0">
	<form name="dcpo_edit" action="WM_FIFO_override.php" method="post">
		<input type="hidden" name="dcpo" value="<? echo $dcpo; ?>">
<?
	$sql = "SELECT FIFO_OVERRIDE_BY, TO_CHAR(FIFO_OVERRIDE_DATE, 'MM/DD/YYYY HH:MI AM') THE_DATE, FIFO_OVERRIDE_REASON
			FROM WDI_LOAD_DCPO
			WHERE DCPO_NUM = '".$dcpo."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	
	$ovr_person = $short_term_row['FIFO_OVERRIDE_BY'];
	$ovr_date = $short_term_row['THE_DATE'];
	$ovr_reason = $short_term_row['FIFO_OVERRIDE_REASON'];

?>
		<tr>
			<td colspan="2"><font size="3" face="Verdana"><b>DCPO <? echo $dcpo; ?></b></font></td>
		</tr>
		<tr>
			<td colspan="2"><br></td>
		</tr>
<?
	if($ovr_person == "") {
?>
		<tr>
			<td colspan="2"><font size="2" face="Verdana"><b>No FIFO Override Currently Recorded.</b></font></td>
		</tr>
<?
	} else {
?>
		<tr>
			<td><font size="2" face="Verdana"><b>FIFO Override Submitted By:</b></font></td>
			<td><font size="2" face="Verdana"><b><? echo $ovr_person; ?></b></font></td>
		</tr>
		<tr>
			<td><font size="2" face="Verdana"><b>FIFO Override On:</b></font></td>
			<td><font size="2" face="Verdana"><b><? echo $ovr_date; ?></b></font></td>
		</tr>
<?
	}
?>
		<tr>
			<td><font size="2" face="Verdana"><b>Reason for Override:</b></font></td>
			<td><input type="text" name="ovr_reason" size="30" maxlength="50" value="<? echo $ovr_reason; ?>"></font></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit" value="Submit Override"></td>
		</tr>
	</form>
</table>
<?php
	if($ovr_person != "") {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><br><hr><br></td>
	</tr>
</table>

<table>
	<form name="dcpo_remove" action="WM_FIFO_override.php" method="post">
		<!--Use a hidden control to pass the DCPO# to the next page via POST-->
		<input type="hidden" name="dcpo" value="<? echo $dcpo; ?>">
		<tr>
			<td>To remove this override, click the button below:</td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Remove Override"></td>
		</tr>
	</form>
</table>
<?php
	}
}
include("pow_footer.php");
?>