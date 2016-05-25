<?
  // All POW files need this session file included
  include("pow_session.php");

  include("utility.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

 $prod_conn = ora_logon("APPS@PROD", "APPS");
 if($prod_conn < 1){
    printf("Error logging on to the PROD Oracle Server: ");
    printf(ora_errorcode($prod_conn));
    printf("Please try later!");
    exit;
 }

 $prod_cursor = ora_open($prod_conn);

 $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $cursor = ora_open($conn);


 $status = $HTTP_POST_VARS['status'];

 $id = $HTTP_POST_VARS[id];
 
 if ($id <>""){
    $sql = "select billing_type, bill_to, gl_code, service_code, commodity_code, rate, unit, description, id
            from billing_rate where id = $id";
    ora_parse($cursor, $sql);
    ora_exec($cursor);
    if (ora_fetch($cursor)){
 	$iType = ora_getcolumn($cursor,0);
        $iCust = ora_getcolumn($cursor,1);
        $iGl = ora_getcolumn($cursor,2);
        $iService = ora_getcolumn($cursor,3);
        $iComm = ora_getcolumn($cursor,4);
        $iRate = ora_getcolumn($cursor,5);
        $iUnit = ora_getcolumn($cursor,6);
        $iDesc = ora_getcolumn($cursor,7);
    }
 }

 $customer = array();
 $gl = array();
 $comm = array();
 $service = array();

 //get customer
 $sql = "select customer_id, customer_name from customer_profile order by customer_id";
 ora_parse($cursor, $sql);
 ora_exec($cursor);

 while(ora_fetch($cursor)){
        array_push($customer, array('id'=>ora_getcolumn($cursor, 0), 'name'=>ora_getcolumn($cursor, 1)));
 }
 //get gl code
 $sql = "select flex_value from fnd_flex_values where flex_value_set_id ='1005835' order by flex_value ";
 ora_parse($prod_cursor, $sql);
 ora_exec($prod_cursor);
 while(ora_fetch($prod_cursor)){
        array_push($gl, array('gl'=>ora_getcolumn($prod_cursor, 0)));
 }
 //get service code
 $sql = "select flex_value from fnd_flex_values where flex_value_set_id ='1005836' order by flex_value ";
 ora_parse($prod_cursor, $sql);
 ora_exec($prod_cursor);
 while(ora_fetch($prod_cursor)){
        array_push($service, array('service'=>ora_getcolumn($prod_cursor, 0)));
 }
 //get commodity code
 $sql = "select flex_value from fnd_flex_values where flex_value_set_id ='1005837' order by flex_value ";
 ora_parse($prod_cursor, $sql);
 ora_exec($prod_cursor);
 while(ora_fetch($prod_cursor)){
        array_push($comm, array('comm'=>ora_getcolumn($prod_cursor, 0)));
 }

 $sql = "select billing_type, bill_to, gl_code, service_code, commodity_code, rate, unit, description, id
         from billing_rate 
	 where ship_type = 'Star Vessel' order by commodity_code, billing_type";
 ora_parse($cursor, $sql);
 ora_exec($cursor);

?>
<style>td{font:11px}</style>
<script language="javascript">
function addNew(isNew)
{
   if (isNew =="Y")
   	document.rate.status.value = "Add";
   document.rate.id.value = "";
   document.rate.submit();
}
function edit(id)
{
   document.rate.id.value = id;
   document.rate.status.value = "Edit";
   document.rate.submit();
}
function rate_process(status)
{
   document.rate.status.value = status;
   document.rate.action="rate_process.php";
   document.rate.submit();
}
function gl_dist(cust, gl, service, comm)
{
        var height = 500;
        var width = 500;
        var url = "gl_allocation.php?cust="+cust+"&gl="+gl+"&service="+service+"&comm="+comm;
        popWindow = window.open(url, "popup", "height="+height+",width="+width+",scrollbars=yes");
        popWindow.focus();
}
</script>
<form name=rate action="" method=post>
<input type=hidden name=id value="<?= $id ?>">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Star Vessel Billing Rate
          </font>
	  <hr>
      </td>
   </tr>
</table>
<? if ($status <>""){?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <table border="0" width="95%" cellpadding="4" cellspacing="2">
	    <tr>
		<td align=left>Type:</td>
		<td colspan=2 align=left><input type=textbox name=type value=<?= $iType?>><td>
		<td colspan=2 align=right>Description:</td>
		<td colspan=5><input type=textbox name=desc value="<?= $iDesc?>" size=40><td>
	    </tr>
	    <tr>	
                <td><nobr>Bill To:</nobr></td>
		<td colspan=9><select name=cust style="width:400px">
                <?      for($i=0; $i<count($customer); $i++){
                                if (strpos($customer[$i][name], $customer[$i][id]) === FALSE)
                                        $customer[$i][name] = $customer[$i][id]."-".$customer[$i][name];
                                if ($customer[$i][id] == $iCust){
                                        $strSelected = "selected";
                                }else{
                                        $strSelected = "";
                                }
                ?>
                        <option value="<?echo $customer[$i][id]?>" <?echo $strSelected?>>
                                <?echo $customer[$i][name]?></option>
                <?      }       ?>
                    </select>
		<td>		
	    </tr>
	    <tr>
		<td>GL:</td>
		<td><select name=gl>
		<option value=""></option>
        <?      for($i=0; $i<count($gl); $i++){
                        if ($gl[$i][gl] == $iGl){
                                $strSelected = "selected";
                }else{
                                $strSelected = "";
                }
        ?>
                <option value="<?echo $gl[$i][gl]?>" <?echo $strSelected?>>
                        <?echo $gl[$i][gl]?></option>
        <?      }       ?>
               	</select>
		</td>
		<td>Service:</td>
		<td><select name=service>
        <?      for($i=0; $i<count($service); $i++){
                        if ($service[$i][service] == $iService){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
        ?>
                <option value="<?echo $service[$i][service]?>" <?echo $strSelected?>>
                        <?echo $service[$i][service]?></option>
        <?      }       ?>
                </select>
		</td>
		<td>Commodity:</td>
		<td><select name=comm>
        <?      for($i=0; $i<count($comm); $i++){
                        if ($comm[$i][comm] == $iComm){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
        ?>
                <option value="<?echo $comm[$i][comm]?>" <?echo $strSelected?>>
                        <?echo $comm[$i][comm]?></option>
        <?      }       ?>
                </select>
		</td>
		<td>Rate:</td>
		<td><input type=textbox name=rate value="<?= $iRate?>" size=10></td>
		<td>Unit:</td>
                <td><input type=textbox name=unit value="<?= $iUnit?>" size=10></td>
	    </tr>
	    <tr>
		<td align=center colspan=10><input type=button name=save value="   Save  " onclick="javaseript:rate_process('save')">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
			<input type=button name=delete value="  Delete " onclick="javaseript:rate_process('delete')">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type=button name=cancel value="  Cancel " onclick="javascript:addNew('N')">
		
		</td>			
	    </tr>
	</table>
	<hr>
      </td>
   </tr>
<table>	
<? } ?>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
<? if ($status ==""){?>
	<input type=button value = "Add New" onclick="javascript:addNew('Y')">
<? }  ?>
	<input type=hidden name=status>
      </td>
   </tr>
   <tr>	
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<table border="1" width="95%" cellpadding="0" cellspacing="2">
	   <tr>
		<td><b>Type</b></td>
                <td><b>Description</b></td>
		<td><b>Bill To</b></td>
		<td><b>GL </b></td>
		<td><b>Service</b></td>
                <td><b>Commodity</b></td>
                <td><b>Rate</b></td>
                <td><b>Unit</b></td>
                <td>&nbsp;</td>

	   </tr>
<?	while (ora_fetch($cursor)) 
	{
		$type = ora_getcolumn($cursor, 0);
		$cust = ora_getcolumn($cursor, 1);
                $gl = ora_getcolumn($cursor, 2);
                $service = ora_getcolumn($cursor, 3);
                $comm = ora_getcolumn($cursor, 4);
                $rate = ora_getcolumn($cursor, 5);
                $unit = ora_getcolumn($cursor, 6);
                $desc = ora_getcolumn($cursor, 7);
                $id = ora_getcolumn($cursor, 8);


?>
	   <tr>
		<td><?echo html($type)?></td>
		<td><?echo html($desc)?></td>
                <td><?echo html($cust)?></td>
                <td><?echo html($gl)?></td>
                <td><?echo html($service)?></td>
                <td><a href="javascript:edit(<?= $id?>)"><?echo html($comm)?></a></td>
                <td align=right><?echo '$'.number_format($rate,2,'.',',')?></td>
                <td><?echo html($unit)?></td>
                <td><input type=button name=gl value="GL Allocation" onclick="javascript:gl_dist('<?= $cust?>','<?= $gl?>','<?= $service?>','<?= $comm?>')">
		</td>
             
	   </tr>
<?	} ?>
	<input type=hidden name=customer_id value="<?echo $cId?>">
	</table>
      </td>
   </tr>	
</table>
</form> 
<script language="javascript">
<? include("pow_footer.php"); 
ora_close($cursor);
ora_logoff($conn);

?>
