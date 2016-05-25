<?
 function delete($conn, $cursor, $id){
        $sql = "delete from gl_allocation_header where id = $id";
        ora_parse($cursor, $sql);
        if (!ora_exec($cursor)){
                ora_rollback($conn);
		return "Delete failed";
        }

        $sql = "delete from gl_allocation_body where header_id = $id";
        ora_parse($cursor, $sql);
        if (!ora_exec($cursor)){
                ora_rollback($conn);
                return "Delete failed";
        }
	ora_commit($conn);
	return "";
 }

 function save($conn, $cursor, $id, $i_customer, $i_gl_code, $i_service_code, $i_comm_code, $i_amount, $i_desc,
	       $d_gl_code, $d_service_code, $d_comm_code, $d_amount){
        if ($id == 0){
                $sql = "select max(id) from gl_allocation_header";
                ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $id = ora_getcolumn($cursor, 0) + 1;
                }else{
                        $id = 1;
                }
                $isNew = true;
        }

        if ($i_amount ==""){
                return "Please enter amount";
        }

        for($i = 0; $i < count($d_amount); $i++){
                $tot_d_amt += $d_amount[$i];
        }
  
	if (number_format($i_amount,4) <> number_format($tot_d_amt,4)){
        	return "Allocation amount is not balanced"; 
	}

        $sql = "delete from gl_allocation_header where id = $id";
        ora_parse($cursor, $sql);
        ora_exec($cursor);

        $sql = "insert into gl_allocation_header
                (id, customer_id, gl_code, service_code, commodity_code, description, amount) values
                ($id, $i_customer, '$i_gl_code','$i_service_code','$i_comm_code','$i_desc',$i_amount)";

        ora_parse($cursor, $sql);
        if (!ora_exec($cursor)){
                ora_rollback($conn);
                return "Save failed";
        }

        $sql = "delete from gl_allocation_body where header_id = $id";
        ora_parse($cursor, $sql);
        if (!ora_exec($cursor)){
                ora_rollback($conn);
                return "Save failed";
        }
        for ($i=0; $i<count($d_gl_code); $i++){
                if ($d_amount[$i] <> "" && $d_amount[$i]<>0){
                        $sql = "insert into gl_allocation_body
                        (header_id, gl_code, service_code, commodity_code, amount) values
                        ($id,'$d_gl_code[$i]','$d_service_code[$i]','$d_comm_code[$i]',$d_amount[$i])";

                        ora_parse($cursor, $sql);
                        if (!ora_exec($cursor)){
                                ora_rollback($conn);
                                return "Save failed";
                        }
                }
        }
	ora_commit($conn);
	return "";
 }

 $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 ora_commitoff($conn);
 $cursor = ora_open($conn);

 $prod_conn = ora_logon("APPS@PROD", "APPS");
 if($prod_conn < 1){
    printf("Error logging on to the PROD Oracle Server: ");
    printf(ora_errorcode($prod_conn));
    printf("Please try later!");
    exit;
 }

 $prod_cursor = ora_open($prod_conn);

 $id = $HTTP_GET_VARS[id];
 if ($id ==""){
        $id = 0;
 }

 $cId = $HTTP_GET_VARS[cId];

 if($HTTP_GET_VARS[delete]<>""){//delete
        $msg = delete($conn, $cursor, $id);
	if ($msg ==""){
                $id = 0;
?>
<script language="javascript">
opener.window.gl.submit();
self.close();
</script>
<?
        }
 }

 if($HTTP_GET_VARS[save]<>""){
        $i_customer = $HTTP_GET_VARS[i_cust];
        $i_gl_code = $HTTP_GET_VARS[i_gl];
        $i_service_code = $HTTP_GET_VARS[i_service];
        $i_comm_code = $HTTP_GET_VARS[i_comm];
        $i_amount = $HTTP_GET_VARS[i_amt];
        $i_desc = $HTTP_GET_VARS[desc];
        $i_desc = str_replace("\'","''",$i_desc);

        $d_gl_code = $HTTP_GET_VARS[d_gl];
        $d_service_code = $HTTP_GET_VARS[d_service];
        $d_comm_code = $HTTP_GET_VARS[d_comm];
        $d_amount = $HTTP_GET_VARS[d_amt];

        $msg = save($conn, $cursor, $id, $i_customer, $i_gl_code, $i_service_code, $i_comm_code, $i_amount, $i_desc,
               $d_gl_code, $d_service_code, $d_comm_code, $d_amount);
	if ($msg ==""){
?>
<script language="javascript">
opener.window.gl.submit();
self.close();
</script>

<?
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

 if ($msg ==""){
  $sql = "select customer_id, gl_code, service_code, commodity_code, description, amount from gl_allocation_header
	 where id = $id";
 ora_parse($cursor, $sql);
 ora_exec($cursor);
 if(ora_fetch($cursor)){
	$i_cust = ora_getcolumn($cursor, 0);
        $i_gl = ora_getcolumn($cursor, 1);
        $i_service = ora_getcolumn($cursor, 2);
        $i_comm = ora_getcolumn($cursor, 3);
        $i_desc = ora_getcolumn($cursor, 4);
        $i_amt = ora_getcolumn($cursor, 5);
 }

 if ($i_cust =="")
	$i_cust = $cId;

 $sql = "select gl_code, service_code, commodity_code, amount from gl_allocation_body
         where header_id = $id";
 ora_parse($cursor, $sql);
 ora_exec($cursor);
 $i = 0;
 while(ora_fetch($cursor)){
        $d_gl[$i] = ora_getcolumn($cursor, 0);
        $d_service[$i] = ora_getcolumn($cursor, 1);
        $d_comm[$i] = ora_getcolumn($cursor, 2);
        //$d_asset[$i] = ora_getcolumn($cursor, 3);
        $d_amt[$i] = ora_getcolumn($cursor, 3);
	$i ++;
 }
}
?>
<html>
<head>
<style>
td{font:12px};
</style>
</head>
<body>
<form>
<input type=hidden name=id value="<?echo $id?>">
<input type=hidden name=cId value="<?echo $cId?>">
<table>
	<tr>
		<td width="1%">&nbsp;</td>
		<td align=center colspan=3><b><font size=4>GL Allocation</font></b></td>
	</tr>
	<tr>
           <td width="1%">&nbsp;</td>
	      <td colspan=3><table>
		<tr>	
                <td><b>Customer:</b></td>
		<td><select name=i_cust style="width:340px">
            	<?      for($i=0; $i<count($customer); $i++){
				if (strpos($customer[$i][name], $customer[$i][id]) === FALSE)
					$customer[$i][name] = $customer[$i][id]."-".$customer[$i][name];
    	                	if ($customer[$i][id] == $i_cust){
                               		$strSelected = "selected";
                               	}else{
                      	                $strSelected = "";
                           	}
             	?>
                  	<option value="<?echo $customer[$i][id]?>" <?echo $strSelected?>>
                           	<?echo $customer[$i][name]?></option>
               	<?      }       ?>
                    </select>
               	</td>
       		</tr>
        	<tr>
                <td  valign=top><b>Description:</b></td>
		<td><textarea name=desc cols=40 rows=3><?echo $i_desc?></textarea>
                </td>
        	</tr>
	     </table></td>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><b>Account (GL-Service-Commodity)</b></td>
		<td width="1%"><b>&nbsp;</b></td>	   
		<td><b>Amount</b></td>
	</tr>
	<tr>	  
		<td width="1%">&nbsp;</td>
		<td><nobr><select name=i_gl>
	<?	for($i=0; $i<count($gl); $i++){	
			if ($gl[$i][gl] == $i_gl){
				$strSelected = "selected";
		}else{
				$strSelected = "";
		}
	?>
		<option value="<?echo $gl[$i][gl]?>" <?echo $strSelected?>>
			<?echo $gl[$i][gl]?></option>
	<?	}	?>
	       </select>-
	       <select name=i_service>
      	<?      for($i=0; $i<count($service); $i++){
                  	if ($service[$i][service] == $i_service){
                       		$strSelected = "selected";
                       	}else{
                             	$strSelected = "";
                     	}
     	?>
              	<option value="<?echo $service[$i][service]?>" <?echo $strSelected?>>
                 	<?echo $service[$i][service]?></option>
      	<?      }       ?>
               	</select>-
		<select name=i_comm>
        <?      for($i=0; $i<count($comm); $i++){
                    	if ($comm[$i][comm] == $i_comm){
                         	$strSelected = "selected";
                       	}else{
                             	$strSelected = "";
              	        }
      	?>
            	<option value="<?echo $comm[$i][comm]?>" <?echo $strSelected?>>
                    	<?echo $comm[$i][comm]?></option>
       	<?      }       ?>
               	</select></nobr>
<!--
		<select name=i_asset>
      	<?      for($i=0; $i<count($asset); $i++){
                   	if ($asset[$i][asset] == $i_asset){
                           	$strSelected = "selected";
                       	}else{
                             	$strSelected = "";
                   	}
     	?>
            	<option value="<?echo $asset[$i][asset]?>" <?echo $strSelected?>>
                   	<?echo $asset[$i][asset]?></option>
   	<?      }       ?>
           	</select></nobr>
-->
		</td>
		<td>&nbsp;</td>
		<td><input type=textbox name=i_amt value="<?echo $i_amt?>" size=10></td>
	</tr>
	<tr>
		<td colspan=4>
			<hr>
		</td>
	</tr>
	
        <tr>
                <td width="1%">&nbsp;</td>
                <td><b>Allocation Account (GL-Service-Commodity)</b></td>
                <td width="1%"><b>&nbsp;</b></td>
                <td><b>Amount</b></td>
        </tr>
<? for($j=0; $j<10; $j++){	?>
        <tr>
                <td width="1%">&nbsp;</td>
                <td><nobr><select name=d_gl[]>
        <?      for($i=0; $i<count($gl); $i++){
                        if ($gl[$i][gl] == $d_gl[$j]){
                                $strSelected = "selected";
                }else{
                                $strSelected = "";
                }
        ?>
                <option value="<?echo $gl[$i][gl]?>" <?echo $strSelected?>>
                        <?echo $gl[$i][gl]?></option>
        <?      }       ?>
               </select>-
               <select name=d_service[]>
        <?      for($i=0; $i<count($service); $i++){
                        if ($service[$i][service] == $d_service[$j]){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
        ?>
                <option value="<?echo $service[$i][service]?>" <?echo $strSelected?>>
                        <?echo $service[$i][service]?></option>
        <?      }       ?>
               </select>-
               <select name=d_comm[]>
        <?      for($i=0; $i<count($comm); $i++){
                        if ($comm[$i][comm] == $d_comm[$j]){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
        ?>
                <option value="<?echo $comm[$i][comm]?>" <?echo $strSelected?>>
                        <?echo $comm[$i][comm]?></option>
        <?      }       ?>
                </select>
<!--
                <select name=d_asset[]>
        <?      for($i=0; $i<count($asset); $i++){
                        if ($asset[$i][asset] == $d_asset[$j]){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
        ?>
                <option value="<?echo $asset[$i][asset]?>" <?echo $strSelected?>>
                        <?echo $asset[$i][asset]?></option>
        <?      }       ?>
                </select></nobr>
-->
                </td>
                <td>&nbsp;</td>
                <td><input type=textbox name=d_amt[] value="<?echo $d_amt[$j]?>" size=10></td>
        </tr>
<?}?>
        <tr>
		<td width="1%">&nbsp;</td>
		<td colspan=3 align=center><input type=submit name="save" value=" Save ">&nbsp;&nbsp;&nbsp;&nbsp;
			      <input type=submit name="delete" value="Delete"> 
		</td>
	</tr>
        <tr>
                <td width="1%">&nbsp;</td>
	</tr>
	<tr>
                <td width="1%">&nbsp;</td>
                <td colspan=3 align=center><font color=red><?echo $msg?></font></td>
 	</tr>
</table>
</form>
</body>
</html>
