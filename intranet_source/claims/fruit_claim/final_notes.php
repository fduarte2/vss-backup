<?
  // All POW files need this session file included
  include("pow_session.php");
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf";

  $claim_id = $HTTP_GET_VARS['claim_id'];
  $claim_body_id = $HTTP_GET_VARS['claim_body_id'];

  $sql = "select post_closed_notes from $claim_body where claim_id = $claim_id and claim_body_id = $claim_body_id";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
        $notes = ora_getcolumn($cursor, 0);
  }
  
  ora_close($cursor);
  ora_logoff($conn);
?>
<style>td{font:11px}</style>
<form name=notes action = "final_notes_process.php" method=post>
<input type=hidden name=claim_id value=<?= $claim_id?>>
<input type=hidden name=claim_body_id value=<?= $claim_body_id?>>

<table>
    <tr>
	<td>&nbsp;</td>
	<td align=center><font size="3" face="Verdana" ><b>Final Notes:</b></font></td>
	<td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><textarea name=notes cols="70" rows="3" maxlength="300"><?= $notes ?></textarea></td>
	<td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align=center><input type=submit name=save value = "  Save  ">
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type=submit name=cancel value = " Cancel ">
	</td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>
