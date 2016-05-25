<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
  
  $vesId = $HTTP_POST_VARS[vessel];
  $custId = $HTTP_POST_VARS[customer];

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
        printf("Error logging on to the Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  ora_commitoff($conn);
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);
  //TABLES
/*
  $cargo_manifest = "cargo_manifest_backup";
  $cargo_manifest_changes = "cargo_manifest_changes_backup";
  $cargo_tracking = "cargo_tracking_backup";
  $cargo_tracking_changes = "cargo_tracking_changes_backup";
*/
  $cargo_manifest = "cargo_manifest";
  $cargo_manifest_changes = "cargo_manifest_changes";
  $cargo_tracking = "cargo_tracking";
  $cargo_tracking_changes = "cargo_tracking_changes";


  $sql = "select lr_num, vessel_name from vessel_profile order by lr_num";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $arrVessel = array();
  $ves_count = 0;
  while (ora_fetch($cursor)){
	$ves_count ++;
	array_push($arrVessel, array('lr_num'=>ora_getcolumn($cursor, 0), 'vName'=>ora_getcolumn($cursor, 1)));
  }

  $sql = "select customer_id, customer_name from customer_profile order by customer_id";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $arrCustomer = array();
  $cust_count = 0;
  while (ora_fetch($cursor)){
	$cust_count ++;
	array_push($arrCustomer, array('cId'=>ora_getcolumn($cursor, 0), 'cName'=>ora_getcolumn($cursor, 1)));
  }

  $sql = "select location_type from location_category order by location_type";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $arrLocation = array();
  $loc_count = 0;
  while (ora_fetch($cursor)){
        $loc_count ++;
        array_push($arrLocation, ora_getcolumn($cursor, 0));
  }

  if(isset($HTTP_POST_VARS[save])){
	$arrBol = $HTTP_POST_VARS[bol];
        $arrMark = $HTTP_POST_VARS[mark];
        $arrCurrLocation = $HTTP_POST_VARS[curr_location];
        $arrNewLocation = $HTTP_POST_VARS[new_location];
	for ($i = 0; $i < count($arrBol); $i ++){
		//location changed
		if($arrCurrLocation[$i] <> $arrNewLocation[$i]){
			//update cargo_manifest
			$sql = "update $cargo_manifest set cargo_location = '$arrNewLocation[$i]' where lr_num = '$vesId' and recipient_id = '$custId' and cargo_bol = '$arrBol[$i]' and ltrim(rtrim(cargo_mark)) = '$arrMark[$i]'";

		  	$statement = ora_parse($cursor, $sql);
        		if (!ora_exec($cursor)){
//echo "error";
				ora_rollback($conn);
				continue;
			}
			//add to cargo_manifest_changes
			$sql = "select Max(change_num) from $cargo_manifest_changes where container_num in (select container_num from $cargo_manifest where lr_num = '$vesId' and recipient_id = '$custId' and cargo_bol = '$arrBol[$i]' and ltrim(rtrim(cargo_mark)) = '$arrMark[$i]')";
			$statement = ora_parse($cursor, $sql);
                        ora_exec($cursor);
			if (ora_fetch($cursor)){
				$max_mani_ch_nbr = ora_getcolumn($cursor, 0) +1;
			}else{
				$max_mani_ch_nbr = 1;
			}

			$sql = "insert into $cargo_manifest_changes (change_num, cargo_location, date_of_change, initials, reason, cargo_weight, qty_expected, qty2_expected, lr_num, arrival_num, container_num, commodity_code, recipient_id, cargo_bol, cargo_mark, exporter_id, cargo_weight_unit, qty1_unit, qty2_unit, impex, manifest_status) select $max_mani_ch_nbr, '$arrNewLocation[$i]', round(sysdate,'DD'), '$user', 'CHANGE LOCATION', 0,0,0, lr_num, arrival_num, container_num, commodity_code, recipient_id, cargo_bol, cargo_mark, exporter_id, cargo_weight_unit, qty1_unit, qty2_unit, impex, manifest_status from $cargo_manifest where lr_num = '$vesId' and recipient_id = '$custId' and cargo_bol = '$arrBol[$i]' and ltrim(rtrim(cargo_mark)) = '$arrMark[$i]'";

			$statement = ora_parse($cursor, $sql);
			if (!ora_exec($cursor)){
			  ora_rollback($conn);
			  continue;
                        }
			
			
			$sql = "select lot_num from $cargo_tracking where lot_num in (select container_num from $cargo_manifest where lr_num = '$vesId' and recipient_id = '$custId' and cargo_bol = '$arrBol[$i]' and ltrim(rtrim(cargo_mark)) = '$arrMark[$i]')";
			$statement = ora_parse($cursor2, $sql);
                        ora_exec($cursor2);
			//if in cargo_tracking
			while (ora_fetch($cursor2)){
				$lot_num = ora_getcolumn($cursor2, 0);
				//update cargo_tracking
				$sql = "update $cargo_tracking set warehouse_location ='$arrNewLocation[$i]' where lot_num = '$lot_num'";

				$statement = ora_parse($cursor, $sql);
                        	if (!ora_exec($cursor)){
//echo "error3";
                                	ora_rollback($conn);
                                	continue;
                        	}

				//add to cargo_tracking_change
				$sql = "select Max(change_num) from $cargo_tracking_changes where lot_num ='$lot_num'";
                        	$statement = ora_parse($cursor, $sql);
                        	ora_exec($cursor);
                        	if (ora_fetch($cursor)){
                                	$max_trk_ch_nbr = ora_getcolumn($cursor, 0) +1;
                        	}else{
                                	$max_trk_ch_nbr = 1;
                        	}
				$sql = "insert into $cargo_tracking_changes (change_num, warehouse_location, date_of_change, initials, reason, qty_in_house, qty_received, date_received, receiver_id, lot_num, commodity_code, owner_id, whse_rcpt_num, qty_unit) select $max_trk_ch_nbr, '$arrNewLocation[$i]', round(sysdate,'DD'), '$user', 'CHANGE LOCATION', 0, 0, round(sysdate,'DD'), 4, lot_num, commodity_code, owner_id, whse_rcpt_num, qty_unit from $cargo_tracking where lot_num = '$lot_num'";
				$statement = ora_parse($cursor, $sql);
                                if (!ora_exec($cursor)){
//echo "error4";
                                        ora_rollback($conn);
                                        continue;
                                }
			}

			ora_commit($conn);
		}
	}
  }
  $sql = "select cargo_bol, cargo_mark, cargo_location, lr_num, recipient_id from $cargo_manifest where lr_num = '$vesId' and recipient_id = '$custId' group by cargo_bol, cargo_mark, cargo_location, lr_num, recipient_id order by cargo_bol, cargo_mark, cargo_location";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);  


?>
<script language ="JavaScript">
function IsValidVes(){
	var valid_vessel = false;
	var vId = trim(document.location.vessel.value);
 	var sel = document.location.sel_ves;
	var len = sel.options.length;
	for (var i = 0; i < len; ++i){
		if (sel.options[i].value == vId){
			sel.options[i].selected = true;
			valid_vessel = true;
			break;
		}else{
			sel.options[i].selected = false;
		}
	}
	if (!valid_vessel){
		alert("Please enter a valid vessel!");
		document.location.vessel.focus();
		document.location.vessel.select();
		return false;
	}else{
		return true;
	}
}


function selectVes(){
	var vId = document.location.sel_ves.options[document.location.sel_ves.selectedIndex].value;
	document.location.vessel.value = vId;
}

function IsValidCust() {
        var valid_customer = false;
        var cId = trim(document.location.customer.value);
        var sel = document.location.sel_cust;
        var len = sel.options.length;
        for (var i = 0; i < len; ++i){
                if (sel.options[i].value == cId){
                        sel.options[i].selected = true;
                        valid_customer = true;
                        break;
                }else{
                        sel.options[i].selected = false;
                }
        }
        if (!valid_customer){
                alert("Please enter a valid customer id!");
		document.location.customer.focus();
		document.location.customer.select();
	}
}

function selectCust(){
        var cId = document.location.sel_cust.options[document.location.sel_cust.selectedIndex].value;
        document.location.customer.value = cId;
}

function setColor(ob,location){
	if (ob.options[ob.selectedIndex].value == location){
		ob.style.color = 'black';
	}else{
		ob.style.color = 'red';
	}
}
function trim(s) {
  while (s.substring(0,1) == ' ') {
    s = s.substring(1,s.length);
  }
  while (s.substring(s.length-1,s.length) == ' ') {
    s = s.substring(0,s.length-1);
  }
  return s;
}

</script>

<form action = "index.php" method = "post" name = "location">
<table border = "0" width = "100%" cellpadding = "4" cellspacing = "0">
   <tr>
	<td width = "1%">&nbsp;</td>
	<td>
	   <p align = "left">
		<font size = "5 " face = "Verdana" color = "##0066cc">Change Manifest Location
	   	</font>
	   	<hr>
	   </p>
	</td>
   </tr>
</table>

<table border="0" width="100%"  cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td ><b>Vessel:</b></td>
      <td><input type = "text" size = "4" name = "vessel" value = "<? echo $vesId ?>"  onBlur = "IsValidVes()">
	   <select name = "sel_ves" onChange = "selectVes()">
	   <option value = ""></option>
        <? for ( $i = 0; $i < $ves_count; $i++){
                $lr_num = $arrVessel[$i][lr_num];
		$vName = $arrVessel[$i][vName];
		if ($lr_num == $vesId){
			$strSelect = "selected";
		}else{
			$strSelect = "";
		}
        ?>
		<option value="<? echo $lr_num ?>" <? echo $strSelect ?>><? echo $vName ?></option>
        <? } ?>
	
	  </select></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td><b>Customer:</b></td>
      <td><input type = "text" size = "4" name = "customer" value = "<? echo $custId ?>" onBlur = "IsValidCust()">
	  <select name = "sel_cust" onChange = "selectCust()">
	  <option value = ""></option>
        <? for ( $i = 0; $i < $cust_count; $i++){
                $cId = $arrCustomer[$i][cId];
                $cName = $arrCustomer[$i][cName];
		if ($cId == $custId){
			$strSelect = "selected";
		}else{
			$strSelect = "";
		}
        ?>
                <option value="<? echo $cId ?>" <? echo $strSelect ?>><? echo $cName ?></option>
        <? } ?>

          </select></td>

   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      	<td colspan = "2" align = "center">
      		<input type ="submit" name = "search" value="Search">&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;

		<input type = "submit" name = "save" value = " Save ">
 	</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = "2">
        <table border="1"  cellpadding="4" cellspacing="0">
           <tr>
		<td width = "100"><b>BOL</b></td>
		<td width = "200"><b>Mark</b></td>
		<td width = "100"><b>Location</b></td>
		<td width = "100"><nobr><b>New Location</b></nobr></td>
           </tr>
	<? $index = 0;
	   while (ora_fetch($cursor)){
		$bol = trim(ora_getcolumn($cursor, 0));
		$mark = trim(ora_getcolumn($cursor, 1));
		$loc = trim(ora_getcolumn($cursor,2));

	?>
	   <tr>
		<td><? echo $bol ?></td><input type = "hidden" name = "bol[]" value = "<? echo $bol ?>">
		<td><? echo $mark ?></td><input type = "hidden" name = "mark[]" value = "<? echo $mark ?>">
		<td><? echo $loc ?></td><input type = "hidden" name = "curr_location[]" value = "<? echo $loc ?>">
		<td><select name ="new_location[]" onChange = "setColor(this, '<? echo $loc ?>')">
		     <?	for($i = 0; $i < $loc_count; $i++){
				if ($loc == $arrLocation[$i]){
					$strSelect = "selected";
				}else{
					$strSelect = "";
				}
		     ?>
		 	<option value = "<? echo $arrLocation[$i] ?>"  <? echo $strSelect ?>><? echo $arrLocation[$i] ?></option>
		     <? } ?>
			</select>
           </tr>
	
	<?	
		$index ++; 
	  } 
	?>
   </td>
  </tr>
</table>
   </td>
  </tr>
</table>
<? 
   ora_close($cursor);
   ora_close($cursor2);
   ora_logoff($conn);
   include("pow_footer.php");
?>
