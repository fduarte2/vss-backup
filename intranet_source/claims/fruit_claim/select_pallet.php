<?
  // Begin Claims Code
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$rf", "OWNER");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  // seasons go from Nov 'X-1 to Aug X, so to get the year conjoining with th season, add
  // 4 months to the current months (I.E. the 4 months from the end of august to the end of calendar year)
  $current = date('Y', mktime(0, 0, 0, (date('m')+4), date('d'), date('Y')));

  $pId = $HTTP_POST_VARS['pId'];
  if ($pId == "")
	$pId = $HTTP_GET_VARS['pId'];

  $season = $HTTP_POST_VARS['season'];
  if ($season =="")
	$season = $HTTP_GET_VARS['season'];
  if ($season =="")  
	$season = $current;

  if ($season == $current){
        $table_name = "CARGO_TRACKING";
  }else{
	$table_name = "CARGO_TRACKING_".$season;
  }

  if ($pId <>""){
	$display = true;
	$sql = "select NVL(v.vessel_name, 'TRUCK'), r.customer_name, c.commodity_name, 
				t.receiving_type, t.arrival_num, t.receiver_id, t.pallet_id, t.bol,
				t.exporter_code, voy.voyage_num
			from $table_name t, vessel_profile v, customer_profile r, commodity_profile c, voyage voy
			where t.arrival_num = to_char(v.lr_num(+)) 
			and t.arrival_num = to_char(voy.lr_num(+)) 
			and t.receiver_id = r.customer_id(+) 
			and t.commodity_code = c.commodity_code 
			and t.pallet_id like '%$pId'
			order by t.pallet_id, t.receiving_type";

	$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);		  
  }


?>
<style>td {font:12px}</style>
<script language="javascript">
function check(value){
   document.pallet_form.index.value = value;
}
function selectPLT(){
   var index = document.pallet_form.index.value;
   if (index != ""){
  	document.pallet_form.action = "select_pallet_process.php";
   	document.pallet_form.submit();
   }else{
	alert("Please select pallet first!");
	return false;
   }
}

</script>
<body bgcolor="#f0f0f0">
<form action="" method="POST" name="pallet_form">
<center>
<p><b><font size=4> Pallet Lookup</b></p>
<table  border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
       <td align="right" valign="top" width=35%>
    	     <font size="2" face="Verdana">Pallet ID:</font></td>
       <td align="left" valign="middle" width=20%>
    	     <input type="textbox" name="pId" size="20" maxlength="30" value="<?= $pId ?>">
       </td>
       <td align="left" valign="middle" width=45%>
             <select name="season">
             <?
	       for ($i = $current; $i >=2001; $i--)
    	       {
		if ($season == $i){
			$strSelected = "selected";
		}else{
			$strSelected = "";
		}
		 printf("<option value=\"$i\" $strSelected>$i</option>");
               }
             ?>
             </select>
       </td>
    <tr>
	<td colspan="3" align="center">
		<input type="Submit" name="search" value=" Search ">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="Reset" value="  Reset  ">		
	</td>
    </tr>
    <tr>
	<td colspan="3" height="8"></td>
    </tr>

<?
  if ($display == true){
?>
	<tr>
        <td colspan=3 align="center">
			<a href="tag_audit.php?pallet_id=<?= $pId?>&season=<?= $season?>" target="_blank">Tag Audit</a></font>
		</td>
	</tr>
    <tr>
        <td colspan = 3>
	<hr>
	</td>
    </tr>
    <tr>
    	<td colspan = 3>
	   <table width=100%>
		<tr>
		    <td>&nbsp;</td>
                    <td><b>PALLET ID</b></td>
		    <td><b>VESSEL</b></td>
                    <td><b>CUSTOMER</b></td>
                    <td><b>COMMODITY</b></td>
		    <input type=hidden name=index>
		</tr>		
<?
	$i = 0;
 	while(ora_fetch($cursor)){
		$ves = ora_getcolumn($cursor, 0);
		$cust = ora_getcolumn($cursor, 1);
		$comm = ora_getcolumn($cursor, 2);
		$type = ora_getcolumn($cursor, 3);
		$lr_num = ora_getcolumn($cursor, 4);
		$cust_id = ora_getcolumn($cursor, 5);
		$pallet_id = ora_getcolumn($cursor, 6);
		$bol = ora_getcolumn($cursor, 7);
		$exporter = ora_getcolumn($cursor, 8);
		$voyage = ora_getcolumn($cursor, 9);

		if ($ves ==""){
			if ($type =="F"){
				$ves = "Transfer";
			}/*else if ($type =="T"){
				$ves = "Trucked IN";
			}*/
		}
?>
		<tr>
			<td><input type="radio" name="pallet" value="<?= $i?>" onclick="check(this.value)"></td>
				<input type=hidden name=bol[] value="<?= $bol?>">
				<input type=hidden name=vessel[] value="<?= $ves?>">
				<input type=hidden name=pallet_id[] value="<?= $pallet_id?>">
				<input type=hidden name=prod[] value="<?= $comm?>">
				<input type=hidden name=arrival_num[] value="<?= $lr_num?>">
				<input type=hidden name=exporter[] value="<?= $exporter?>">
				<input type=hidden name=voyage[] value="<?= $voyage?>">
			<td><?= $pallet_id?></td>
			<td><?= $ves?></td>
			<td><?= $cust?></td>
			<td><?= $comm?></td>
		</tr>
<?
		$i++;
	}
?>
 	   </table>
	</td>
    </tr>
<?
	if ($i > 0 ){
?>
    <tr>
        <td colspan="3" align="center">
                <input type="button" name="select" value="  Select  " onclick="javascript:selectPLT()">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="cancel" value="  Cancel  " onclick="self.close()">
        </td>
    </tr>
<?	}else{	?>
    <tr>
        <td colspan="3" align="center">
		<font color=red>No Pallet found!</b>
        </td>
    </tr>


<?
	}
  }
  ora_close($cursor);
  ora_logoff($conn);

?>
</table>



