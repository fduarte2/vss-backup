<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Customer Profile Page (Marketing)";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

  $submit = $HTTP_POST_VARS['submit'];
  $new_super = $HTTP_POST_VARS['new_super'];
  $old_super = $HTTP_POST_VARS['old_super'];
  $renamed_super = $HTTP_POST_VARS['renamed_super'];



  $sql = "SELECT MAX(SUPERCUSTOMER_ID) THE_MAX FROM MARKETING_SUPERCUSTOMERS";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $max = $row['THE_MAX'];

  if($max < 0 || $max == ""){
	  $max = 0;
  }
  $max++;

  if($new_super != "" && $submit == "Add Supercustomer"){
	  $sql = "INSERT INTO MARKETING_SUPERCUSTOMERS (SUPERCUSTOMER_ID, SUPERCUSTOMER) VALUES ('".$max."', '".$new_super."')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($submit == "Modify Supercustomer" && $renamed_super != "" && $old_super != ""){
	  $sql = "UPDATE MARKETING_SUPERCUSTOMERS SET SUPERCUSTOMER = '".$renamed_super."' WHERE SUPERCUSTOMER_ID = '".$old_super."'";
  	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">"Supercustomer" Creation page.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="super_add" action="marketing_supercustomer.php" method="post">
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Add new Supercustomer:</font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><input type="text" name="new_super" size="20" maxlength="50"></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><input type="submit" name="submit" value="Add Supercustomer"></td>
	</form>
	</tr>
	<tr>
		<td colspan="3" align="center">&nbsp;<br><b>--OR--</b><br>&nbsp;</td>
	</tr>
	<tr>
	<form name="super_change" action="marketing_supercustomer.php" method="post">
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Rename Supercustomer:</font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana">From:  <select name="old_super">
												<option value=""> </option>
<?
	$sql = "SELECT * FROM MARKETING_SUPERCUSTOMERS ORDER BY SUPERCUSTOMER";
    ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['SUPERCUSTOMER_ID']; ?>"><? echo $row['SUPERCUSTOMER']; ?></option>
<?
	}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><font size="2" face="Verdana">To:  <input type="text" name="renamed_super" size="20" maxlength="50"></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><input type="submit" name="submit" value="Modify Supercustomer"></td>
	</form>
	</tr>
</table>

<? include("pow_footer.php"); ?>

