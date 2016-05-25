<?
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

  //get commodity
  $sql="select p.commodity_code, c.commodity_name, p.backhaul, p.truckloading, p.container_handling,
	p.rail_car_handling, p.inspection
	from pref_commodity p, commodity_profile c
 	where user_id = '$user_id' and working_date = to_date('$date','mm/dd/yyyy') and p.commodity_code = c.commodity_code
	order by p.commodity_code";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);

?>
<table border="0" align= left width="100%" cellpadding="4" cellspacing="1">
<?	while (ora_fetch($cursor_bni)){
		$comm = ora_getcolumn($cursor_bni, 0);
		$name = ora_getcolumn($cursor_bni, 1);
		$backhaul = ora_getcolumn($cursor_bni, 2);
                $truckloading = ora_getcolumn($cursor_bni, 3);
                $container_handling = ora_getcolumn($cursor_bni, 4);
                $rail_car_handling = ora_getcolumn($cursor_bni, 5);
                $inspection = ora_getcolumn($cursor_bni, 6);

?>
  <tr>
	<td><input type=checkbox name="pref_comm[]" value="<?echo $comm ?>">
	</td>
	<td><?echo $name ?>
	</td>
<?      if ($backhaul =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="backhaul[]" <?echo $strChecked?>>
        </td>
        </td>
<?      if ($truckloading =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="truckloading[]" <?echo $strChecked?>>
        </td>
        </td>
<?      if ($container_handling =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="container_handling[]" <?echo $strChecked?>>
        </td>
        </td>
<?      if ($rail_car_handling =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="rail_car_handling[]" <?echo $strChecked?>>
        </td>
        </td>
<?      if ($inspection =="Y"){
                $strChecked = "checked";
        }else{
                $strChecked = "";
        }
?>
        <input type=checkbox name="inspection[]" <?echo $strChecked?>>
        </td>
  </tr>
<? 	} ?>
</table>
