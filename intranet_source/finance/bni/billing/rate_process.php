<?
 include("utility.php");

 $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $cursor = ora_open($conn);

 $id = $HTTP_POST_VARS['id'];
 $type = $HTTP_POST_VARS['type'];
 $desc = $HTTP_POST_VARS['desc'];
 $cust = $HTTP_POST_VARS['cust'];
 $gl = $HTTP_POST_VARS['gl'];
 $service = $HTTP_POST_VARS['service'];
 $comm = $HTTP_POST_VARS['comm'];
 $rate = $HTTP_POST_VARS['rate'];
 $unit= $HTTP_POST_VARS['unit'];

 $type = OraSafeString($type);
 $desc = OraSafeString($desc);
 
 $status = $HTTP_POST_VARS['status'];
 if ($status == "save"){
//echo "save";
	if ($id == ""){
		$sql = "select max(id) from billing_rate";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if (ora_fetch($cursor)){
			$id = ora_getcolumn($cursor, 0) + 1;
		}else{
			$id = 1;
		}
		$sql = "insert into billing_rate (id, billing_type, bill_to, gl_code, service_code, commodity_code, rate, unit, description, ship_type) values ($id, '$type',$cust,'$gl','$service','$comm',$rate, '$unit','$desc','Star Vessel')"; 
	}else{
		$sql = "update billing_rate set billing_type='$type',
						bill_to = $cust,
						gl_code = '$gl',
						service_code = '$service',
						commodity_code = '$comm',
						rate = $rate,
						unit = '$unit',
						description = '$desc'
			where id = $id";
	}	
	ora_parse($cursor, $sql);
	ora_exec($cursor);

 }
 if ($status == "delete"){
	if ($id <>""){
		$sql = "delete from billing_rate where id = $id";
        	ora_parse($cursor, $sql);
	        ora_exec($cursor);
	}
 } 
 header("location: rate.php");
?>
