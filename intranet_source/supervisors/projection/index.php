<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Preferences Commodity";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  $web_user = $userdata['username'];
  $mail = $userdata['user_email'];

  $date = $HTTP_GET_VARS['date'];
  if ($date == "")
  	$date = date("m/d/Y");

  $conn_lcs = ora_logon("LABOR@LCS", "LABOR");
  if($conn_lcs < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_lcs = ora_open($conn_lcs);

  $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn_bni < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_bni = ora_open($conn_bni);

  //get user id
  $sql = "select user_id from lcs_user where email_address = '$mail'";
  $statement = ora_parse($cursor_lcs, $sql);
  ora_exec($cursor_lcs);

  if (ora_fetch($cursor_lcs)){
	$user_id = ora_getcolumn($cursor_lcs, 0);
  }else{
	$msg = "Invalid user!";
	echo $msg;
	exit;
  }

  $pComm = array();

  $type = $HTTP_GET_VARS['type'];
  if ($type<>""){
        $sql="select p.commodity_code, c.commodity_name, p.backhaul, p.truckloading, p.container_handling,
              p.rail_car_handling, p.inspection
              from pref_commodity p, commodity_profile c
              where user_id = '$user_id' and working_date is null and
              p.commodity_code = c.commodity_code
              order by p.commodity_code";

  }else{
  	$sql="select p.commodity_code, c.commodity_name, p.backhaul, p.truckloading, p.container_handling,
              p.rail_car_handling, p.inspection
              from pref_commodity p, commodity_profile c
              where user_id = '$user_id' and working_date = to_date('$date','mm/dd/yyyy') and 
	      p.commodity_code = c.commodity_code
              order by p.commodity_code";
  }
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);
?>
<style>
td{font:11px};
</style>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
function changeDate(){
   var date = document.commodity.date.value;
   document.location = "index.php?date="+date;
}
function get_default(){
   var date = document.commodity.date.value;
   document.location = "index.php?date="+date+"&type=getDefault";;
}
function set_default(){
   var date = document.commodity.date.value;
   document.location = "index.php?date="+date+"&type=setDefault";
}
function selectAll(position, index){
   for (var i=0; i<index; i++){
	document.commodity.elements[position+i*7].checked = true;
		
   }
}
</script>
<form name = "commodity"  method="Post" action="process.php">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
 <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Commodity Preferences</font> 
         <hr>
      </td>
   </tr>
</table>

<table border="0" align= left width="100%" cellpadding="4" cellspacing="1">
   <tr>
      <td width="1%">&nbsp;</td>
      <td width = 400><nobr>

<? if ($type=="setDefault"){ ?>
	<b><?echo strtoupper($web_user) ?></b> Default Commodities:
	<input type=hidden  name="date" value="<?echo $date ?>">
	
<? }else{  ?>
        <b><?echo strtoupper($web_user) ?></b> Commodities for:
        <input type="textbox" name="date" value="<?echo $date ?>" size = 10 onChange="changeDate()" readonly>
        <a href="javascript:show_calendar('commodity.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="show-calendar.gif" width=24 height=22 border=0></a>
<? }  ?>          
     	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="setDefault" value="Set Default" onclick="set_default()">
        &nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" name="getDefault" value="Get Default" onclick="get_default()">
        &nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="submit" name="save" value=" Save "></nobr>
      </td>
      
      <td width="1%">&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
<table border="1" align= left width="100%" cellpadding="0" cellspacing="0">
   <tr>
	<td width=330><b>Commodity</b></td>
	<td align=center><b>Backhaul</b></td>
        <td align=center><b>Truckloading</b></td>
        <td align=center><b>Container</b></td>
        <td align=center><b>Rail Car</b></td>
        <td align=center><b>Inspection</b></td>
        <td align=center><b>Remove</b></td>

   </tr>	
<?      $i = 0;
   	while (ora_fetch($cursor_bni)){
                $comm = ora_getcolumn($cursor_bni, 0);
                $name = ora_getcolumn($cursor_bni, 1);
                $backhaul = ora_getcolumn($cursor_bni, 2);
                $truckloading = ora_getcolumn($cursor_bni, 3);
                $container_handling = ora_getcolumn($cursor_bni, 4);
                $rail_car_handling = ora_getcolumn($cursor_bni, 5);
                $inspection = ora_getcolumn($cursor_bni, 6);

		$pComm[$i] = $comm;
		$i++;
?>
  <tr>
        <td><?echo $name ?>
        </td>
<?      if ($backhaul =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <td align=center><input type=checkbox name="backhaul[]" value="<?echo $comm?>" <?echo $strChecked?>>
        </td>
        <td align=center>

<?      if ($truckloading =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="truckloading[]" value="<?echo $comm?>" <?echo $strChecked?>>
        </td>
        <td align=center>
<?      if ($container_handling =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="container_handling[]" value="<?echo $comm?>" <?echo $strChecked?>>
        </td>
        <td align=center>
<?      if ($rail_car_handling =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="rail_car_handling[]" value="<?echo $comm?>" <?echo $strChecked?>>
        </td>
        <td align=center>
<?      if ($inspection =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="inspection[]" value="<?echo $comm?>" <?echo $strChecked?>>
        </td>
        <td align=center><input type=checkbox name="pref_comm[]" value="<?echo $comm ?>">
        </td>
	<input type=hidden name=commodity[] value="<?echo $comm ?>">
  </tr>
<?      } 
	if ($i ==0){
?>
  <tr>
	<td colspan=7><font color=red>No Commodity is selected</font></td>
  </tr>
<?	}else{ ?>
  <tr>
	<td>&nbsp;</td>
	<td><input type=button name=bSelect value="Select All" onclick="javascript:selectAll(4,'<?echo $i?>')"></td>
        <td><input type=button name=tSelect value="Select All" onclick="javascript:selectAll(5,'<?echo $i?>')"></td>
        <td><input type=button name=cSelect value="Select All" onclick="javascript:selectAll(6,'<?echo $i?>')"></td>
        <td><input type=button name=rSelect value="Select All" onclick="javascript:selectAll(7,'<?echo $i?>')"></td>
        <td><input type=button name=iSelect value="Select All" onclick="javascript:selectAll(8,'<?echo $i?>')"></td>
        <td><input type=button name=rmSelect value="Select All" onclick="javascript:selectAll(9,'<?echo $i?>')"></td>
  </tr>	
<?	} ?>
</table>
</td>	
<!--
	<iframe marginHeight=0 marginWidth=0 frameborder=1 width=100% height=100%  name=Pref_Commodity noResize 
	src='/supervisors/projection/pref_commodity.php'></iframe>
-->
      </td>		
      <td width="1%">&nbsp;</td>
   <tr>
      <td width="1%">&nbsp;</td>
      <td align = center>
	  <input type=submit name="add" value="    Add    ">
	  <input type=submit name="remove" value=" Remove "> 
      </td>
      <td width="1%">&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	<select name=comm[] size=10 multiple style="width:780px">
<? 
  //get commodity
  $sql="select commodity_code, commodity_name from commodity_profile order by commodity_code";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);

      	while (ora_fetch($cursor_bni)){
                $comm = ora_getcolumn($cursor_bni, 0);
                $name = ora_getcolumn($cursor_bni, 1);
		
		$display = true;
		for ($i = 0; $i<count($pComm); $i++){
			if ($pComm[$i] == $comm){
				$display = false;
			}
		}
		if ($display ==true){
?>
	<option value="<?echo $comm?>"><?echo $name?></option>
<?		}
	}	?>
<!--	
        <iframe marginHeight=0 marginWidth=0 frameborder=1 width=100% height=100%  name=Commodity noResize
        src='/supervisors/projection/commodity.php'></iframe>
-->
      </td>
      <td width="1%">&nbsp;</td>
   <tr>

</table>
<?
  printf("<input type=\"hidden\" name=\"user\" value=\"%s\">", $web_user);
  printf("<input type=\"hidden\" name=\"mail\" value=\"%s\">", $mail);
  printf("<input type=\"hidden\" name=\"user_id\" value=\"%s\">", $user_id);
  printf("<input type=\"hidden\" name=\"type\" value=\"%s\">", $type);
?>
</form>
<? include("pow_footer.php"); ?>
