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
  
  $system = $HTTP_GET_VARS['system'];
  $view = $HTTP_GET_VARS['view'];
  $view_sup = $HTTP_GET_VARS['view_sup'];

  $sDate = "01/31/2005";

  if ($system =="")
	$system = "BNI";
  if ($view =="")
	$view = "0";
  if ($view == "2" && $view_sup =="")
	$view_sup = "E407474";

 // open a connection to the database server
  $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
  if($lcs_conn < 1){
        printf("Error logging on to the Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $lcs_cursor = ora_open($lcs_conn);

  if ($system =="CCDS"){
  	include("connect.php");
  	$ccd_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  	if(!$ccd_conn){
        	die("Could not open connection to PostgreSQL database server");
  	}
  }else if ($system =="BNI"){
  	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
  	if($conn < 1){
        	printf("Error logging on to the Oracle Server: ");
        	printf(ora_errorcode($conn));
        	printf("Please try later!");
        	exit;
  	}
  	$cursor = ora_open($conn);
  }else if ($system =="RF"){
  	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
  	if($conn < 1){
        	printf("Error logging on to the Oracle Server: ");
        	printf(ora_errorcode($conn));
        	printf("Please try later!");
        	exit;
  	}
  	$cursor = ora_open($conn);
  }else if ($system =="PAPER"){
  	$conn = ora_logon("PAPINET@RF", "OWNER");
  	if($conn < 1){
        	printf("Error logging on to the Oracle Server: ");
        	printf(ora_errorcode($conn));
        	printf("Please try later!");
        	exit;
  	}
  	$cursor = ora_open($conn);
  }
  $sql = "select user_name, user_id from lcs_user where status = 'A' and group_id = 4 order by user_name";
  $statement = ora_parse($lcs_cursor, $sql);
  ora_exec($lcs_cursor);

  $supervisor = array();
  while (ora_fetch($lcs_cursor)){
	array_push($supervisor, array('uName'=>ora_getcolumn($lcs_cursor, 0), 'uId'=>ora_getcolumn($lcs_cursor, 1)));
  }
  $sup_count = count($supervisor);

  if ($system =="BNI"){
	$sql = "select to_char(a.date_of_activity,'mm/dd/yyyy'), a.order_num, b.user_id from
		(select distinct delivery_num as order_num, date_of_activity from cargo_activity a, cargo_delivery d
		where a.lot_num = d.lot_num and a.activity_num = d.activity_num and a.service_code = d.service_code and 
		date_of_activity >= to_date('$sDate','mm/dd/yyyy') and d.service_code = 6200)a,
		order_supervisor b
 		where a.order_num = b.order_num(+) and a.date_of_activity = b.date_of_activity(+)";
  }else if ($system =="RF"){
	$sql = "select to_char(a.date_of_activity,'mm/dd/yyyy'), a.order_num, b.user_id from
                (select distinct order_num, trunc(date_of_activity, 'dd') as date_of_activity from cargo_activity
                where date_of_activity >= to_date('$sDate','mm/dd/yyyy') and 
		service_code = 6 and activity_description is null) a,
                order_supervisor b
                where a.order_num = b.order_num(+) and a.date_of_activity = b.date_of_activity(+)";
  }else if ($system =="PAPER"){
        $sql = "select to_char(a.date_of_activity,'mm/dd/yyyy'), a.order_num, b.user_id from
                (select distinct order_num, trunc(activity_date, 'dd') as date_of_activity from cargo_activity
                where activity_date >= to_date('$sDate','mm/dd/yyyy') and
                service_code = 2 and (activity_status is null or activity_status <>'VOID')) a,
                order_supervisor b
                where a.order_num = b.order_num(+) and a.date_of_activity = b.date_of_activity(+)";
  }else if ($system =="CCDS"){
	$sql = "select to_char(a.date_of_activity,'mm/dd/yyyy'), a.order_num, b.user_id from
                (select distinct order_num, execution_date as date_of_activity from ccd_activity
                where execution_date  >= '$sDate' and transaction_type ='SHIPPED')a left outer join
                order_supervisor b on a.order_num = b.order_num and a.date_of_activity = b.date_of_activity
		where 1 = 1 ";
  }

  if ($view =="0"){
	$sql .= " and b.user_id is null ";
  }else if ($view =="1"){
	$sql .= " and b.user_id is not null ";
  }else if ($view =="2"){
	$sql .= " and b.user_id = '$view_sup' ";
  }

  $sql .= "order by a.date_of_activity, order_num";

  $data = array();
  if ($system =="CCDS"){
        $result = pg_query($ccd_conn, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
        $rows = pg_num_rows($result);
	for ($i=0; $i<$rows; $i++){
		$row = pg_fetch_row($result, $i);
		array_push($data, array('date'=>$row[0],'order'=>$row[1], 'sId'=>$row[2]));
	}	
  }else{

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
	while (ora_fetch($cursor)){
		array_push($data, array('date'=>ora_getcolumn($cursor, 0),
					'order'=>ora_getcolumn($cursor, 1), 
					'sId'=>ora_getcolumn($cursor, 2)));
	}
  }
  $data_count = count($data);

?>
<style>
td{font:11px};
</style>  
<script language="JavaScript" src="/functions/calendar.js"></script>  
<script language ="JavaScript">
function changeVal()
{
//	var sys = document.order.system.options[document.order.system.selectedIndex].value;
	var view_sup ="";
	for (var i = 0; i < 4; i ++){
		if(document.order.system[i].checked)
			var sys = document.order.system[i].value;
	}
        for (var i = 0; i < 3; i ++){
                if(document.order.view[i].checked)
                        var view = document.order.view[i].value;
        }
	if (document.order.view_sup != null)
		view_sup = document.order.view_sup.options[document.order.view_sup.selectedIndex].value;
	location = "index.php?system="+sys+"&view="+view+"&view_sup="+view_sup;
}

</script>

<form action = "process.php" method = "post" name = "order">
<table border = "0" width = "100%" cellpadding = "4" cellspacing = "0">
   <tr>
	<td width = "1%">&nbsp;</td>
	<td>
	   <p align = "left">
		<font size = "5 " face = "Verdana" color = "##0066cc">Assign Supervisor to Order
	   	</font>
	   	<hr>
	   </p>
	</td>
   </tr>
</table>

<table border="0" width="100%"  cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td><font size="4" face="Verdana" color="#0066CC">System:</font>
<!--	   <select name = "system" onChange = "changeVal()">
	   <option value = "BNI" <?if ($system=="BNI") echo "selected"?>>BNI</option>
           <option value = "RF" <?if ($system=="RF") echo "selected"?>>RF</option>
           <option value = "CCDS" <?if ($system=="CCDS") echo "selected"?>>CCDS</option>
           <option value = "PAPER"  <?if ($system=="PAPER") echo "selected"?>>Holmen</option>
	   </select>
-->
	   <input type=radio name=system value="BNI" <?if ($system=="BNI") echo "checked"?> onclick="changeVal()">BNI
		&nbsp;&nbsp;&nbsp;&nbsp;
           <input type=radio name=system value="RF" <?if ($system=="RF") echo "checked"?> onclick="changeVal()">RF
                &nbsp;&nbsp;&nbsp;&nbsp;
           <input type=radio name=system value="CCDS" <?if ($system=="CCDS") echo "checked"?> onclick="changeVal()">CCDS
                &nbsp;&nbsp;&nbsp;&nbsp;
           <input type=radio name=system value="PAPER" <?if ($system=="PAPER") echo "checked"?> onclick="changeVal()">Holmen
                &nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      <td width="2%">&nbsp;</td>
      <td width = '50%'>
	<input type='button' name='refresh' value="Refresh" onclick="changeVal()">
        <input type='submit' name='save' value="  Save  ">
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="4"><font size="4" face="Verdana" color="#0066CC">View:</font>
	<input type=radio name=view value="0" <?if ($view=="0") echo "checked"?> onclick="changeVal()">Unassigned 
                &nbsp;&nbsp;&nbsp;&nbsp;
        <input type=radio name=view value="1" <?if ($view=="1") echo "checked"?> onclick="changeVal()">All assigned
                &nbsp;&nbsp;&nbsp;&nbsp;
        <input type=radio name=view value="2" <?if ($view=="2") echo "checked"?> onclick="changeVal()">Select Supervisor
<?
	if ($view =="2"){
?>     
	  <select name='view_sup' onchange="changeVal()">
<?
                for($i=0; $i<$sup_count; $i++){
                        if ($supervisor[$i]['uId'] == $view_sup){
                                $strSelected = "selected";
                        }else{
                                $strSelected = "";
                        }
?>
                        <option value="<?echo $supervisor[$i]['uId']?>" <?echo $strSelected?>>
                                <?echo $supervisor[$i]['uName']?></option>
<?              }       ?>
         </select>
<?	} ?>
      </td>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = "4">
        <table border="1"  cellpadding="4" cellspacing="0">
           <tr>
                <td width = "100"><b>Date</b></td>
<?	if ($system == "BNI"){
		$title = "Withdrawal#";
	}else if ($system == "RF"){
		$title = "Order#";
	}else{
		$title = "BOL";
	}
?>
		<td width = "200"><b><?echo $title?></b></td>
		<td width = "150"><b>Supervisor</b></td>
           </tr>
<?
	for($j=0; $j<$data_count; $j++){
		$date = $data[$j]['date'];
		$order = $data[$j]['order'];
		$sId = $data[$j]['sId'];
?>	
	   <tr>
		<input type=hidden name="date[]" value="<?echo $date ?>">
		<input type=hidden name="order_num[]" value="<?echo $order ?>">
		<input type=hidden name="orig_sup[]" value="<?echo $sId ?>">
                <td><?echo $date ?></td>
		<td><?echo $order ?></td>
		<td><select name='sup[]'>
			<option value=""></option>
<?
		for($i=0; $i<$sup_count; $i++){
			if ($supervisor[$i]['uId'] == $sId){
				$strSelected = "selected";
			}else{
				$strSelected = "";
			}
?>
			<option value="<?echo $supervisor[$i]['uId']?>" <?echo $strSelected?>>
				<?echo $supervisor[$i]['uName']?></option>
<?		}	?>
		   </select>
		</td>
  	   </tr>
<?				
	}
?>
	
	</table>
      </td>
  </tr>
</table>
<? 
   if ($system <>"CCDS"){
   	ora_close($cursor);
   	ora_logoff($conn);
   }

   include("pow_footer.php");
?>
