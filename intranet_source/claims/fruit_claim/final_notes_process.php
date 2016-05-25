<?
  // All POW files need this session file included
  include("pow_session.php");
  include("utility.php");
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

  $claim_id = $HTTP_POST_VARS['claim_id'];
  $claim_body_id = $HTTP_POST_VARS['claim_body_id'];
  $notes = $HTTP_POST_VARS['notes'];
  $notes = OraSafeString($notes);

  $reload = false;
  if ($HTTP_POST_VARS['save']<>""){
        $sql = "update $claim_body set post_closed_notes = '$notes'
                where claim_id = $claim_id and claim_body_id = $claim_body_id";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
	$reload = true;
  }
  
  ora_close($cursor);
  ora_logoff($conn);
?>
<html>
<head>
<script language="javascript">
<? if ($reload==true) {?>
opener.document.detail.pallet_id.value = "";
opener.document.detail.action="";
opener.document.detail.submit();
<? } ?>
self.close();
</script>
</head>
</html>
