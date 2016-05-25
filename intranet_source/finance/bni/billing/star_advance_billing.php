<?
  // File: holmen_vessel.php
  //
  // Lynn F. Wang, 8/16/04
  // Interface to let user process Holmen Vessel billing

  // All POW files need this session file included
  include("pow_session.php");

  include("utility.php");
  // Define some vars for the skeleton page
  $title = "Finance - BNI -Star Vessel - Advance Vessel Billing";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  // make database connection
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  $cursor = ora_open($conn);

  $msg = $HTTP_GET_VARS['msg'];
  $disabled = "disabled";
  $lr_num = $HTTP_POST_VARS['lr_num'];
if ($lr_num <>""){
  $sql = "SELECT * FROM BILLING 
	  WHERE LR_NUM = '$lr_num' AND BILLING_TYPE = 'Star Ves' AND SERVICE_STATUS <> 'DELETED'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$msg="Advance Vessel Bills For Vessel $lr_num  Have Already Been Generated!";
  }else{
	$sql= "select * from cargo_manifest where lr_num = $lr_num and commodity_code in (4592, 4384,4472,4361)";
        ora_parse($cursor, $sql);
        ora_exec($cursor);
        
        if (ora_fetch($cursor)){
		$sql = "select commodity_code, cargo_bol, cargo_weight_unit, sum(cargo_weight) 
			from cargo_manifest
			where lr_num = $lr_num and cargo_mark not like 'TR*%' 
			group by commodity_code, cargo_bol, cargo_weight_unit
			order by commodity_code, cargo_bol, cargo_weight_unit";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
	}else{
		$msg = "No Results";
	}
  }
}
?>

<script type="text/javascript">
function invoice(){
   document.billing.action="star_advance_billing_process.php";
   document.billing.submit();
}  
function exit(){
   document.location = "run_prebill.php";
}
</script>
<style>td{font:12px}</style>
<form name=billing action="" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
    <tr>
	<td width="1%">&nbsp;</td>
      	<td>
	    <font size="5" face="Verdana" color="#0066CC">Star Vessel - Advance Vessel/Wharfage Billing</font>
	    <hr>
	    </font>
    	</td>
    </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
    <tr>
        <td width="1%">&nbsp;</td>
        <td>
	<table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td align=center  colspan=2><font size="2" face="Verdana">Vessel#:</font>
			&nbsp;&nbsp;
			<input type=textbox name=lr_num value="<?= $lr_num ?>" onchange="document.billing.submit()"></td>
		<td width="1%">&nbsp;</td>
	    <tr>
            <tr>
		<td colspan=4>&nbsp;</td>
	    </tr>
<? if ($msg <>""){	?>
            <tr>
                <td width="1%">&nbsp;</td>
		<td colspan=2 align=center><font size="2" face="Verdana" color=red><?= $msg ?></td>
		<td width="1%">&nbsp;</td>
	    </tr>
            <tr>
                <td colspan=4>&nbsp;</td>
            </tr>

<? }else if ($lr_num <>""){	?>
            <tr>
                <td width="1%">&nbsp;</td>
		<td colspan=2 align=center>
		<table width=90%>
		    <tr>
			<td><b>Commodity</b></td>
                        <td><b>BOL</b></td>
                	<td><b>Weight</b></td>
                	<td><b>Unit</b></td>
		    </tr>
	    
<?
	while(ora_fetch($cursor)){	
		$disabled = "";
		$comm = ora_getcolumn($cursor, 0);
                $bol = ora_getcolumn($cursor, 1);
                $unit = ora_getcolumn($cursor, 2);
		$weight = ora_getcolumn($cursor,3);

?>
            	    <tr>
                    	<td><?= html($comm)?></td>
                        <td><?= html($bol)?></td>
                	<td><?= html($weight)?></td>
                	<td><?= html($unit)?></td>
            	    </tr>
<?
	}
?>
		</table>
		</td>
		<td width="1%">&nbsp;</td>
	    </tr>	
<?
   }
?>

	    <tr>
		<td width="1%">&nbsp;</td>
                <td colspan=2 align=center>
			<input type=button name=create value="  Invoice  " onclick="javascript:invoice()" <?= $disabled ?>>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type=button name=cancel value="     Exit    " onclick="javascript:exit()">
	    </tr>

        </table>
        </td>
    </tr>
</table>

<?
  ora_close($cursor);
  ora_logoff($conn);
  include("pow_footer.php");
?>
