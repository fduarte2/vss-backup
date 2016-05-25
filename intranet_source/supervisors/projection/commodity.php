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
  $sql="select commodity_code, commodity_name from commodity_profile order by commodity_code";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);

?>
<table border="0" align= left width="100%" cellpadding="4" cellspacing="1">
<?	while (ora_fetch($cursor_bni)){
		$comm = ora_getcolumn($cursor_bni, 0);
		$name = ora_getcolumn($cursor_bni, 1);
?>
  <tr>
	<td><input type=checkbox name=comm[] value="<?echo $comm ?>">
	</td>
	<td><?echo $name ?>
	</td>
  </tr>
<? 	} ?>
</table>
