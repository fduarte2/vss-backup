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
  include("utility.php");
include("../claim_function.php");
//include("./claim_function.php");

  // Begin Claims Code
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf";

  $claim_id = $HTTP_GET_VARS['claim_id'];
  $retrieve = true;

  $customer_id = $HTTP_POST_VARS['customer_id'];
  $cargo_type = $HTTP_POST_VARS['cargo_type'];
  $invoice_num = $HTTP_POST_VARS['invoice_num'];
  $invoice_date = $HTTP_POST_VARS['invoice_date'];
  $received_date = $HTTP_POST_VARS['received_date'];
  $check_num = $HTTP_POST_VARS['check_num'];
  $check_date = $HTTP_POST_VARS['check_date'];
  $amt_paid = $HTTP_POST_VARS['amt_paid'];
  $status =  $HTTP_POST_VARS['status'];
  $season = $HTTP_POST_VARS['season'];
 
 
  if ($customer_id <>"" && $invoice_num <>""){
	$sql = "select claim_id from $claim_header 
		where customer_id = $customer_id and invoice_num = '$invoice_num'  and 
		(status is null or status <>'D')";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                $claim_id = ora_getcolumn($cursor, 0);
        }else{
		$claim_id = $HTTP_POST_VARS['claim_id'];
		$retrieve = false;
	}
  }


  if ($claim_id <>"" && $retrieve == true){
	$sql = "select customer_id, invoice_num, invoice_date, receive_date, check_num, check_date,
			amt_paid,status,ispercentage,claim_cargo_type 
		from $claim_header where claim_id = $claim_id and (status is null or status <>'D')";
	$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
	if (ora_fetch($cursor)){
		$customer_id = ora_getcolumn($cursor, 0);
		$invoice_num = ora_getcolumn($cursor, 1);
		$invoice_date = ora_getcolumn($cursor, 2);
		$receive_date = ora_getcolumn($cursor, 3);
		$check_num = ora_getcolumn($cursor, 4);
		$check_date = ora_getcolumn($cursor, 5);
		$amt_paid = ora_getcolumn($cursor, 6);
		$status = ora_getcolumn($cursor, 7);
        $ispct = ora_getcolumn($cursor, 8);
		$cargo_type = ora_getcolumn($cursor, 9);

		if ($check_date <>"")
			$check_date = date('m/d/Y', strtotime($check_date));
	}else{
		$claim_id = "";
	}
  }
  
  if ($status =="C"){
	$save_disabled = "disabled";
        $delete_disabled = "disabled";
        $add_disabled = "disabled";
  }else if ($claim_id==""){
	$delete_disabled = "disabled";
	$add_disabled = "disabled";
	$print_disabled = "disabled";
  }

  if ($claim_id <>""){
	$pow_claim_id = "F".$claim_id;
  }

  if($cargo_type == ""){
	$cargo_type = "CHILEAN";
  }

?>
<style>td {font:11px}</style>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
function addDetail()
{
	var claim_id=document.mod_form.claim_id.value;
	document.location="detail.php?claim_id="+claim_id;
}
function loadClaim()
{
	document.mod_form.action="rf_claim.php";
	document.mod_form.submit();
}
function validation()
{
	var cust = document.mod_form.customer_id.options[document.mod_form.customer_id.options.selectedIndex].value;
	var invoice = document.mod_form.invoice_num.value;

	if (cust == "")
	{
		alert("Please select a customer!");
		return false;
	}
	if (invoice == "")
	{
		alert("Please enter invoice #!");
		return false;
	}
	return true;
}
function createLetter()
{
	document.location = "create_letter.php?claim_id=<?= $claim_id?>";
	//window.open("http://dspc-s16/claims/meat_claim/create_letter.php?claim_id=<?= $claim_id?>","Letter");
}
function confirmSubmit()
{
var agree=confirm("Are you sure you want to DELETE?");
if (agree)
        return true ;
else
        return false ;
}
function viewDetail(claim_body_id)
{
   document.mod_form.claim_body_id.value=claim_body_id;
   document.mod_form.action = "detail.php";
   document.mod_form.submit();
}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
 	<tr>
           	<td width="1%">&nbsp;</td>
               	<td>
                  	<font size="5" face="Verdana" color="#0066CC">Fruit Claim
                  	</font>
                  	<hr>
               	</td>
      	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<tr>
      	<td width="1%">&nbsp;</td>
       	<td valign="top">
   	<form name="mod_form"  action="rf_claim_process.php" method="Post">
		<input type=hidden name=claim_body_id value="">
   	<table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
                <tr>
                        <td>&nbsp;</td>
                        <td align="right">
                                <font size="2" face="Verdana">POW Claim#:</font></td>
                        <td align="left">
                                <input type="textbox" name="pow_claim_id" size="20" maxlength="20" value="<?= $pow_claim_id?>" readonly>
				<a href="rf_claim.php">New Claim</a>
				<input type="hidden" name="claim_id" value="<?= $claim_id?>">
                        </td>
					<td>&nbsp;</td>
				<td align="right">
						<font size="2" face="Verdana">Claim Type:</font></td>
				<td align="left">
						<select name="cargo_type">
<?
		$sql = "SELECT CLAIM_CARGO_TYPE FROM CLAIM_CARGO_TYPES 
				ORDER BY CLAIM_CARGO_TYPE";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
		  while (ora_fetch($cursor)){
?>
							<option value="<? echo ora_getcolumn($cursor, 0); ?>" <? if(ora_getcolumn($cursor, 0) == $cargo_type){?>selected<?}?>><? echo ora_getcolumn($cursor, 0); ?></option>
<?
		  }
?>
						</select></td>
				<td>&nbsp;</td>
		</tr>
     		<tr>
         		<td>&nbsp;</td>
         		<td align="right" valign="top">
            			<font size="2" face="Verdana">Customer:</font></td>
          		<td>
            			<select name="customer_id" onchange="javascript:loadClaim()">
<?
         $customer = getClaimCustomerList($cursor, "RF");
         while (list($ora_customer_id, $customer_name) = each($customer)) {
           if($ora_customer_id == $customer_id){
             printf("<option value=\"%s\" SELECTED>%s - %s</option>\n", $ora_customer_id, $customer_name,$ora_customer_id);
             $cust_in = true;
           } else {
             printf("<option value=\"%s\">%s - %s</option>\n", $ora_customer_id, $customer_name,$ora_customer_id);
           }
         }
         if(!$cust_in){
           printf("<option value=\"\" SELECTED>Select A Customer</option>");
         }

?>
                  		</select>
         		</td>
         		<td width ='1%'>&nbsp;</td>
         		<td align="right">
            			<font size="2" face="Verdana">Invoice #:</font></td>
         		<td align="left">
            			<input type="textbox" name="invoice_num" size="20" maxlength="50" value="<?= $invoice_num ?>"					onchange="javascript:loadClaim()">
         		</td>
         		<td>&nbsp;</td>
      		</tr>
                <tr>
                        <td>&nbsp;</td>
                        <td align="right">
                                <font size="2" face="Verdana">Season:</font></td>
                        <td align="left">
                                <select name="season">
<?
								for($i=date("Y", mktime(0,0,0,date("m")+2,date("d"),date("Y"))); $i > 2005; $i--){
?>
                                    <option value="<? echo $i; ?>" <? if($i == date("Y")) {?> selected <? } ?>><? echo $i; ?></option>
<?
								}
?>
                                </select>
                        </td>
                        <td>&nbsp;</td>
                	<td align="right">
                        	<font size="2" face="Verdana">Is Chilean %:</font></td>
                	<td align="left">
                        	<input type=checkbox name=ispct value="Y" <?if ($ispct=="Y") echo "checked"?>>
                	<td>&nbsp;</td>
		</tr>
      		<tr>
         		<td>&nbsp;</td>
         		<td align="right">
            			<font size="2" face="Verdana">Invoice Date:</font></td>
         		<td align="left">
            			<input type="textbox" name="invoice_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($invoice_date)) ?>"><a href="javascript:show_calendar('mod_form.invoice_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
         		</td>
			<td>&nbsp;</td>
         		<td align="right">
            			<font size="2" face="Verdana">Date Received:</font></td>
         		<td align="left">
            			<input type="textbox" name="received_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($receive_date)) ?>"><a href="javascript:show_calendar('mod_form.received_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
         		</td>
         		<td>&nbsp;</td>
      		</tr>
                <tr>
                        <td>&nbsp;</td>
                        <td align="right">
                                <font size="2" face="Verdana">Check#:</font></td>
                        <td align="left">
				<input type="textbox" name="check_num" size="20" maxlength="20" value="<?= $check_num?>">
                        </td>
                        <td>&nbsp;</td>
                        <td align="right">
                                <font size="2" face="Verdana">Check Date:</font></td>
                        <td align="left">
                                <input type="textbox" name="check_date" size="10" maxlength="20" value="<?= $check_date ?>"><a href="javascript:show_calendar('mod_form.check_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
                        </td>
                        <td>&nbsp;</td>
		</tr>
		<tr>
                        <td>&nbsp;</td>
                        <td align="right">
                                <font size="2" face="Verdana">Amount Paid:</font></td>
                        <td align="left">
                                <input type="textbox" name="amt_paid" size="20" maxlength="20" value="<?= $amt_paid?>">
                        </td>
                        <td>&nbsp;</td>

                        <td align="right">
                                <font size="2" face="Verdana">Status:</font></td>
                        <td align="left">&nbsp;
				<select name=status style="color:red">
					<option value="O" <?if ($status=="O") echo "selected"?>>Open</option>
                                        <option value="C" <?if ($status=="C") echo "selected"?>>Closed</option>
				</select>
                        </td>
                        <td>&nbsp;</td>

                <tr>
                        <td>&nbsp;</td>
                        <td colspan=5 align="center">
				<input type=submit name=save value="     Save     " onclick = "return validation()" <?= $save_disabled?>>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type=submit name=delete value="   Delete   " onClick="return confirmSubmit()" <?= $delete_disabled?>>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type=button name=detail value="Add Detail" onclick="javascript:viewDetail('')" <?= $add_disabled?>>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type=button name=letter value="Print Letter" onclick="javascript:createLetter()" <?= $print_disabled?>>
                        </td>

                        </td>
                        <td>&nbsp;</td>
                </tr>

	</table>
	</td>
</tr>
</table>
<?
  if ($claim_id <>""){
        $sql = "select count(*) from $claim_body
                where claim_id = $claim_id and (status is null or status <>'D')";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                $count = ora_getcolumn($cursor, 0);
        }
  }

  if ($count > 0){
        $sql = "select  claim_body_id, vessel ||' '||voyage, pallet_id, exporter,
                        claim_qty, claim_unit, claim_price, claim_amt, 
			port_qty, port_amt, denied_qty, denied_amt, ship_line_qty, ship_line_amt,
			claim_type 
                from $claim_body
                where claim_id = $claim_id and (status is null or status <>'D')
		order by claim_body_id desc";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<tr>
        <td width="2">&nbsp;</td>
        <td valign="top">	
	<table width="99%"  bgcolor="#f0f0f0"  border="0" cellpadding="0" cellspacing="0">
       	    <tr>
          	<td>&nbsp;</td>
		<td> <table border=1 width="99%" cellpadding="0" cellspacing="0">
		    <tr bgcolor="#BFBFBF">
			<td rowspan=2 align=center><b>Vessel</b></td>
                        <td rowspan=2 align=center><b>Pallet ID</b></td>
                        <td rowspan=2 align=center><b>Exporter</b></td>
			<td colspan=3 align=center><b>Claim</b></td>
			<td colspan=2 align=center><b>POW</b></td>
                        <td colspan=2 align=center><b>Denied</b></td>
                        <td colspan=2 align=center><b>Ship Line</b></td>
			<td rowspan=2 align=center width=40><b>Claim Type</b></td>
                    </tr>
                    <tr bgcolor="#BFBFBF">
                        <td><b>Qty</b></td>
                        <td align=center><b>$</b></td>
                        <td align=center><b>AMT</b></td>
			<td><b>QTY</b></td>
			<td align=center><b>AMT</b></td>
                        <td><b>QTY</b></td>
                        <td align=center><b>AMT</b></td>
                        <td><b>QTY</b></td>
                        <td align=center><b>AMT</b></td>
     		    </tr>
<?	while(ora_fetch($cursor)){
		$claim_body_id = ora_getcolumn($cursor, 0);
                $ves = ora_getcolumn($cursor, 1);
                $pallet_id = ora_getcolumn($cursor, 2);
                $exporter = ora_getcolumn($cursor, 3);
                $claim_qty = ora_getcolumn($cursor, 4);
                $claim_unit = ora_getcolumn($cursor, 5);
                $claim_price = ora_getcolumn($cursor, 6);
                $claim_amt = ora_getcolumn($cursor, 7);
                $port_qty = ora_getcolumn($cursor, 8);
		$port_amt = ora_getcolumn($cursor, 9);
                $denied_qty = ora_getcolumn($cursor, 10);
                $denied_amt = ora_getcolumn($cursor, 11);
                $ship_line_qty = ora_getcolumn($cursor, 12);
                $ship_line_amt = ora_getcolumn($cursor, 13);
                $claim_type = ora_getcolumn($cursor, 14);
              
		if ($claim_type =="Warehouse Damage"){
			$claim_type="W-Damage";
		}else if ($claim_type =="Mis-Shipment"){
			$claim_type = "M-Ship";
		}

                $tot_claim_qty += $claim_qty;
                $tot_claim_amt += $claim_amt;
                $tot_port_qty += $port_qty;

                $tot_port_amt += $port_amt;
                $tot_denied_qty += $denied_qty;
                $tot_denied_amt += $denied_amt;
                $tot_ship_line_qty += $ship_line_qty;
                $tot_ship_line_amt += $ship_line_amt;
	
?>
                    <tr>
                        <td><?= html($ves)?></td>
                        <td><a href="javascript:viewDetail('<?=$claim_body_id?>')"><?= html($pallet_id)?></a></td>
                        <td><?= html($exporter)?></td>
                        <td align=right><?= html($claim_qty)?></td>
                        <td align=right><?= html($claim_price)?></td>
                        <td align=right><?= html('$'.$claim_amt)?></td>
                        <td align=right><?= html($port_qty)?></td>
                        <td align=right><?= html('$'.$port_amt)?></td>
                        <td align=right><?= html($denied_qty)?></td>
                        <td align=right><?= html('$'.$denied_amt)?></td>
                        <td align=right><?= html($ship_line_qty)?></td>
                        <td align=right><?= html('$'.$ship_line_amt)?></td>
                        <td><?= html($claim_type)?></td>

		    </tr>
<?      }
?>
                    <tr>
                        <td><b>Total</b></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align=right><b><?= html($tot_claim_qty)?></b></td>
                        <td align=right>&nbsp;</td>
                        <td align=right><b><?= '$'.$tot_claim_amt?></b></td>
                        <td align=right><b><?= $tot_port_qty?></b></td>
                        <td align=right><b><?= '$'.$tot_port_amt?></b></td>
                        <td align=right><b><?= $tot_denied_qty?></b></td>
                        <td align=right><b><?= '$'.$tot_denied_amt?></b></td>
                        <td align=right><b><?= $tot_ship_line_qty?></b></td>
                        <td align=right><b><?= '$'.$tot_ship_line_amt?></b></td>
                        <td align=center>&nbsp;</td>

                    </tr>
		</table></td>
<?}?>
	    </tr>
	</table>
	</td>
</tr>
</table>

<script language="JavaScript">
document.mod_form.invoice_num.focus();
</script>
<?
ora_close($cursor);
ora_logoff($conn);
include("pow_footer.php");
?>
