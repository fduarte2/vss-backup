<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Fruit Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

  // Get database connection
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);
 
  $rf_conn = ora_logon("SAG_OWNER@$rf", "OWNER");
  if (!$rf_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
     printf("Please report to TS!");
     exit;
  }
  $rf_cursor = ora_open($rf_conn);
 
  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf";

  $claim_id = $HTTP_POST_VARS['claim_id'];
  $claim_body_id = $HTTP_POST_VARS['claim_body_id'];
  $pallet_id = $HTTP_POST_VARS['pallet_id'];
  $season = $HTTP_POST_VARS['season'];

  if ($claim_id <>""){
	$sql = "select status from $claim_header where claim_id = $claim_id";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	if (ora_fetch($cursor)){
		$h_status  = ora_getcolumn($cursor, 0);
	}
  }
  if ($h_status == "C"){
        $disabled = "disabled";
  }else{
	$disabled = "";
  }

?>
<style>td{font:11px}</style>
<script language="JavaScript">
 var popWindow;

function changePalletId()
{
    document.detail.action="";
    document.detail.submit();
}
function populateNotes()
{
        var notes = document.detail.notes.value;
        var int_notes = document.detail.internal_notes.value;
        if (int_notes =="")
        {
                document.detail.internal_notes.value = notes;
        }
}
function update_amount() {
     x = document.detail;

     claim_qty = x.claim_qty.value;
     claim_price = x.claim_price.value;
     claim_amt = x.claim_amt.value;
  
     port_qty = x.port_qty.value;
     port_amt = x.port_amt.value;

     denied_qty = x.denied_qty.value;
     denied_amt = x.denied_amt.value;
  
     ship_line_qty = x.ship_line_qty.value;
     ship_line_amt = x.ship_line_amt.value;

     claim_amt = claim_qty * claim_price;
     port_amt = port_qty * claim_price;
     denied_amt = denied_qty * claim_price;
     ship_line_amt = ship_line_qty * claim_price;

     // Round some stuff off
     claim_amt = Math.round(claim_amt * 100) / 100;
     port_amt = Math.round(port_amt * 100) / 100;
     denied_amt = Math.round(denied_amt * 100) / 100;
     ship_line_amt = Math.round(ship_line_amt * 100) / 100;

     x.claim_amt.value = claim_amt;
     x.port_amt.value = port_amt;
     x.denied_amt.value = denied_amt;
     x.ship_line_amt.value = ship_line_amt;
   }
function editNotes()
{
    var url = "final_notes.php?claim_id=<?= $claim_id?>&claim_body_id=<?= $claim_body_id?>";

    popWindow =	window.open(url, "Notes", "height=150, width=620");
    popWindow.focus();
}

function palletLookup()
{
    var pallet_id = document.detail.pallet_id.value;
    var season = document.detail.season.value;
    var url = "select_pallet.php?pId="+pallet_id+"&season="+season;
    popWindow = window.open(url, "Notes", "height=400, width=620, scrollbars=Yes");
    popWindow.focus();
}

function closePopup()
{
   if(popWindow !=null)
	popWindow.close();  
}
function confirmSubmit()
{
var agree=confirm("Are you sure you want to DELETE?");
if (agree)
	return true ;
else
	return false ;
}
</script>
<body onFocus="javascript:closePopup()">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit Claim</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<?
     // Make sure this is not a duplicate!
     $sql = "select invoice_num from $claim_header h, $claim_body b
	     where h.claim_id = b.claim_id and pallet_id = '$pallet_id' and 
	     (h.status is null or h.status <>'D') and (b.status is null or b.status <>'D') ";
     if ($claim_body_id <>"")
	$sql .= " and b.claim_body_id <> $claim_body_id ";

     $statement = ora_parse($cursor, $sql);
     ora_exec($cursor);
     if (ora_fetch($cursor)){
	$i_invoice = ora_getcolumn($cursor, 0);
	
	$msg = "Caution! You already made a claim ($i_invoice) for Pallet Id: $pallet_id.<br>";
     }
               
?>
<!--
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
         <table border="0" width="95%" cellpadding="4" cellspacing="0">

	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2" color="red">Caution! You already made a claim (<?= ora_getcolumn($cursor, 0) ?>) for Pallet Id: <?= $pallet_id ?></font>
		  <br /><br />
	       </td>
	    </tr>
         </table>
      </td>
   </tr>
-->
<?
if ($pallet_id <>""){
    $current = date('Y', mktime(0, 0, 0, date('m')+4, date('d'), date('Y')));
    if ($season == "" || $season == $current){
      	$tablename = "CARGO_TRACKING";
    }else{
    	$tablename = "CARGO_TRACKING_".$season;
    }
    $sql = "select count(*) from $tablename where pallet_id='$pallet_id'";
    $statement = ora_parse($rf_cursor, $sql);
    ora_exec($rf_cursor);
    if (ora_fetch($rf_cursor)){
	$count = ora_getcolumn($rf_cursor, 0);
    }
    if ($count >1 ){
	$msg .= "Caution! There are more than one record in system. Please select one from Pallet Lookup!";
    }else if ($count == 1){
	$sql = "select  NVL(v.vessel_name, 'TRUCK'), c.commodity_name, t.arrival_num, t.bol, t.exporter_code, voy.voyage_num, t.receiving_type
                from $tablename t, vessel_profile v, commodity_profile c, voyage voy
                where t.arrival_num = to_char(v.lr_num(+))
				and t.arrival_num = to_char(voy.lr_num(+))
                and t.commodity_code = c.commodity_code and t.pallet_id = '$pallet_id'";
        $statement = ora_parse($rf_cursor, $sql);
    	ora_exec($rf_cursor);
    	if (ora_fetch($rf_cursor)){
				$vessel = ora_getcolumn($rf_cursor, 0);
                $product = ora_getcolumn($rf_cursor, 1);
                $arrival_num = ora_getcolumn($rf_cursor, 2);
                $bol = ora_getcolumn($rf_cursor, 3);
				$exporter = ora_getcolumn($rf_cursor, 4);
				$voyage = ora_getcolumn($rf_cursor, 5);
				$rec_type = ora_getcolumn($rf_cursor, 6);
				if($rec_type == "T"){
					$vessel = "TRUCK";
				}
		}
    }
}else if ($pallet_id =="" && $claim_body_id <>""){
    $sql = "select pallet_id, exporter, vessel, voyage, bol, prod, claim_qty, claim_unit,
                   claim_price, claim_amt, port_qty, port_amt, denied_qty, denied_amt,
                   ship_line_qty, ship_line_amt, claim_type, 
		   notes, internal_notes, status, post_closed_notes,arrival_num, season
	    from $claim_body where claim_body_id = $claim_body_id";

    $statement = ora_parse($cursor, $sql);
    ora_exec($cursor);
    if (ora_fetch($cursor)){
	$pallet_id = ora_getcolumn($cursor, 0);
        $exporter = ora_getcolumn($cursor, 1);
        $vessel = ora_getcolumn($cursor, 2);
        $voyage = ora_getcolumn($cursor, 3);
        $bol = ora_getcolumn($cursor, 4);
        $product = ora_getcolumn($cursor, 5);
        $claim_qty = ora_getcolumn($cursor, 6);
        $claim_unit = ora_getcolumn($cursor, 7);
        $claim_price = ora_getcolumn($cursor, 8);
        $claim_amt = ora_getcolumn($cursor, 9);
        $port_qty = ora_getcolumn($cursor, 10);
        $port_amt = ora_getcolumn($cursor, 11);
        $denied_qty = ora_getcolumn($cursor, 12);
        $denied_amt = ora_getcolumn($cursor, 13);
        $ship_line_qty = ora_getcolumn($cursor, 14);
        $ship_line_amt = ora_getcolumn($cursor, 15);
        $claim_type = ora_getcolumn($cursor, 16);
        $notes = ora_getcolumn($cursor, 17);
        $internal_notes = ora_getcolumn($cursor, 18);
    	$status = ora_getcolumn($cursor, 19);
	$post_notes = ora_getcolumn($cursor, 20);
  	$arrival_num = ora_getcolumn($cursor, 21);
	$season = ora_getcolumn($cursor, 22);
	
        if ($status == "C" && $post_notes <>""){
		if ($internal_notes <>""){
			$internal_notes .= "\n".$post_notes;
		}else{
			$internal_notes = $post_notes;
		}
	}
    }
}
   if ($msg <>""){   
?>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
         <table border="0" width="95%" cellpadding="4" cellspacing="0">

            <tr>
               <td colspan="4">
                  <font face="Verdana" size="2" color="red"><?= $msg ?></font>
                  <br /><br />
               </td>
            </tr>
         </table>
      </td>
   </tr>

<? } ?>
<form name=detail action="detail_process.php" method=post>
<input type="hidden" name="claim_id" value="<? echo $claim_id; ?>">
<input type="hidden" name="claim_body_id" value="<? echo $claim_body_id; ?>">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="0">
            <tr>
         	<td width="1%">&nbsp;</td>
                <td align="right" valign="top">
                        <font size="2" face="Verdana">Pallet ID: </font></td>
                <td align="left">
                    	<nobr><input type="textbox" name="pallet_id" size="25" value="<?echo "$pallet_id";?>" onChange="javascript:changePalletId()">&nbsp;
			<a href="javascript:palletLookup()">Pallet Lookup</a>
			<input type=hidden name="season" value="<?= $season ?>">
			<input type=hidden name="arrival_num" value="<?= $arrival_num ?>">
                </td>
                <td>&nbsp;</td>
                <td align="right" valign="top">
                        <font size="2" face="Verdana">Commodity:</font></td>
                <td align="left">
                        <input type="textbox" name="product" size="20" maxlength="20" value="<?= $product?>">
                </td>
                <td width='5%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	    </tr>	
            <tr>
                <td width="1%">&nbsp;</td>
                <td align="right" valign="top">
                        <font size="2" face="Verdana">Vessel:</font></td>
                <td align="left">
			<input type="textbox" name="vessel" value="<? echo $vessel;?>" size = "25" >
                        <input type="hidden" name="status" value="O"><font size="2" face="Verdana">("TRUCK" for trucked-cargo)
                        </font>
                </td>
                <td>&nbsp;</td>
         	<td align="right" valign="top">
                        <font size="2" face="Verdana">Voyage:</font></td>
                <td align="left">
                        <input type="textbox" name="voyage" size="20" maxlength="20" value="<?= $voyage ?>">
                </td>
                <td>&nbsp;</td>
	    </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="right">
                        <font size="2" face="Verdana">Exporter:</font></td>
                <td align="left">
                        <input type="textbox" name="exporter" size="20" maxlength="20" value="<?= $exporter ?>">
		<td>&nbsp;</td>
                <td align="right" valign="top">
                        <font size="2" face="Verdana">BOL:</font></td>
                <td align="left">
                        <input type="textbox" name="bol" size="20" maxlength="20" value="<?= $bol ?>">
                </td>
                <td>&nbsp;</td>
      	    <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Quantity:</font></td>
         	<td align="left">
            		<nobr><input type="textbox" name="claim_qty" onchange="update_amount()" size="10" maxlength="20" value="<?=$claim_qty ?>"> 
			
			<select name="claim_unit">
				<option value="">Select Unit</option>
				<option value="Case" <?if ($claim_unit == "Case") echo "selected" ?>>Case(s)</option>
				<option value="Pallet" <?if ($claim_unit == "Pallet") echo "selected" ?>>Pallet(s)</option>
				<option value="Each" <?if ($claim_unit == "Each") echo "selected" ?>>Each</option>
			</select></nobr>
         	</td>
         	<td colspan=4>&nbsp;</td>
	    </tr>
      	    <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Market Price:</font></td>
         	<td align="left">
            		<input type="textbox" name="claim_price" onchange="update_amount()" size="10" maxlength="20" value="<?= $claim_price?>">
		</td>
		<td>&nbsp;</td>
                <td align="right">
            		<font size="2" face="Verdana">Amount:</font>
		</td>
                <td align="left">            
			<input type="textbox" name="claim_amt" onchange="update_amount()" size="10" maxlength="20" value="<?= $claim_amt?>" >
         	</td>
         	<td>&nbsp;</td>
	    </tr>
      	    <tr>
           	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Quantity to Port:</font></td>
         	<td width="25%" align="left">
            		<input type="textbox" name="port_qty" onchange="update_amount()"  size="10" maxlength="20" value="<?=  $port_qty?>">
		</td>
                
                <td align="right" colspan=2>            
			<font size="2" face="Verdana">Amount to Port:</font>
            	</td>
                <td align="left">
			<input type="textbox" name="port_amt" size="10" maxlength="20" value="<?=  $port_amt?>">
         	</td>
         	<td>&nbsp;</td>
      	   </tr>
           <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Quantity Denied:</font></td>
         	<td align="left">
            		<input type="textbox" name="denied_qty" onchange="update_amount()" size="10" maxlength="20" value="<?= $denied_qty?>">
            	</td>
                
                <td align="right" colspan=2>
			<font size="2" face="Verdana">Amount Denied:</font>
            	</td>
                <td align="left">
			<input type="textbox" name="denied_amt" size="10" maxlength="20" value="<?= $denied_amt?>">
         	</td>
         	<td>&nbsp;</td>
      	   </tr>
           <tr>
    	        <td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana"><nobr>Quantity to Ship Line:</nobr></font></td>
         	<td align="left">
            		<input type="textbox" name="ship_line_qty" onchange="update_amount()" size="10" maxlength="20" value="<?= $ship_line_qty?>">
            	</td>
                
                <td align="right" colspan=2>
			<font size="2" face="Verdana"><nobr>Amount to Ship Line:</nobr></font>
            	</td>
                <td align="left">
			<input type="textbox" name="ship_line_amt" size="10" maxlength="20" value="<?= $ship_line_amt?>">
         	</td>
         	<td>&nbsp;</td>
      	    </tr>
      	    <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Claim Type:</font></td>
         	<td align="left">
            		<select name="claim_type">
              		<option value="Damage" <?if ($claim_type=="Damage") echo "selected" ?>>Damage</option>
                        <option value="Warehouse Damage" <?if ($claim_type=="Warehouse Damage") echo "selected" ?>>Warehouse Damage</option>
              		<option value="Mis-Shipment" <?if ($claim_type=="Mis-Shipment") echo "selected" ?>>Mis-Shipment</option>
              		<option value="Missing" <?if ($claim_type=="Missing") echo "selected" ?>>Missing</option>
            		</select>
         	</td>
                <td colspan=6>&nbsp;</td>
      	    </tr>
      	    <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Letter Notes:</font></td>
         	<td colspan=5 align="left">
           		 <textarea name="notes" cols="70" rows="3" maxlength="300" onchange="javascript:populateNotes()"><?= $notes ?></textarea>
         	</td>
         	
      	    </tr>
      	    <tr>
         	<td>&nbsp;</td>
         	<td align="right">
            		<font size="2" face="Verdana">Internal Notes:</font></td>
         	<td colspan=5 align="left">
            		<textarea name="internal_notes" cols="70" rows="3" maxlength="300"><?= $internal_notes?></textarea>

         	</td>
         	
      	    </tr>	
	    <tr>
		<td>&nbsp;</td>
		<td colspan = 5 align=center>
<?
		if ($h_status =="C"){
?>

                        <input type=button name=""  value=" Add Notes " onclick="editNotes()">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type=submit name=cancel value="    Cancel   ">
<?
		}else{
?>
			<input type=submit name=save value="  Save  " <?= $disabled?>>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type=submit name=delete value=" Delete " onClick="return confirmSubmit()" <?= $disabled?> >
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type=submit name=cancel value=" Cancel ">
<?
		}
?>

		</td>
		<td>&nbsp;</td>
	    </tr>
	 </form>
	 </table>
<? if($pallet_id <>""){ ?>
	 <table>
            <tr>
               <td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="tag_audit.php?pallet_id=<?= $pallet_id?>&season=<?= $season?>" target="_blank">Tag Audit</a></font>
               </td>
            </tr>

	 </table>
<?  }	?>
      </td>
   </tr> 
   <tr>
      <td colspan="3">&nbsp;</td>
   </tr>
</table>

<?
ora_close($rf_cursor);
ora_logoff($rf_conn);
ora_close($cursor);
ora_logoff($conn);

include("pow_footer.php");
?>
