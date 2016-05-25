<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Fruit Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $claim_id = $HTTP_GET_VARS['claim_id'];

  $sql = "select id, signature from claim_letter_signature order by id desc";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">

function cancelPrint()
{
   document.location = "ccd_claim.php?claim_id=<?=$claim_id?>"
}
</script>
<form name=print action="print_letter.php" method=post>
<input type=hidden name=claim_id value="<?= $claim_id?>">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
        <tr>
                <td width="1%">&nbsp;</td>
                <td colspan=3>
                        <font size="5" face="Verdana" color="#0066CC">Fruit Claim Letter
                        </font>
                        <hr>
                </td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="30%" align=right>
			Signature: 
		</td>
		<td width="30%" align=left><select name="signature_id">
		<?	
			while (ora_fetch($cursor)){	
				$id = ora_getcolumn($cursor,  0);
				$signature = ora_getcolumn($cursor, 1);
                       		printf("<option value=\"%s\">%s</option>\n",$id, $signature);
                	}
		?>
                         </select>
		</td>
		<td>&nbsp;</td>
        <tr>
                <td width="1%">&nbsp;</td>
                <td align=right>
                        Date:
                </td>
                <td align=left>
			<input type="textbox" name="date" size="10" maxlength="20" value="<?= date('m/d/Y') ?>"><a href="javascript:show_calendar('print.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
                <td width="1%">&nbsp;</td>
		<td align=center colspan=2>
			<input type=submit name=print value="   Print   " >
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type=button name=cancel value=" Cancel " onclick="cancelPrint()">

		</td>
		<td>&nbsp;</td>
        </tr>
</table>
</form>
<?
ora_close($cursor);
ora_logoff($conn);
include("pow_footer.php");
?>
