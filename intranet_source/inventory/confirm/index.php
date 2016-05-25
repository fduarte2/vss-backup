<?
  // All POW files need this session file included
//  include("pow_session.php");

  $today = date('m/d/Y');
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor_BNI = ora_open($conn);

/*  //get connect
  include("connect_data_warehouse.php");

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/
  $sql = "select * from utilization_confirm where report_date = TO_DATE('".$today."', 'MM/DD/YYYY')";
//  echo $sql;
	$ora_success = ora_parse($Short_Term_Cursor_BNI, $sql);
	$ora_success = ora_exec($Short_Term_Cursor_BNI, $sql);

/*  
  $result = pg_query($pg_conn, $sql);
  $rows = pg_num_rows($result);
*/
?>

<html>

<script langauge = "javascript">
function clickCancel(){
    document.location = "../index.php"
    return false;
}
</script>

<head>
<title>Port of Wilmington Inventory - Home Page</title>
</head>




<? /*if ($rows == 0){*/
	if(!ora_fetch_into($Short_Term_Cursor_BNI, $short_term_row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<form name="confirm" mothed="post" action="confirm.php">
	Please confirm following daily Warehouse Utilization Report.
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type = "submit" value = " Confirm ">
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type = "submit" value = " Cancel " onclick = "return clickCancel()">
	</form>
	<hr>
<? }else{ ?>
	Following daily Warehouse Utilization Report was confirmed by OPS.<hr>
<? } ?>
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
	   <tr>
		  <td width="1%">&nbsp;</td>
		  <td align="center">
			 <font size="3" face="Verdana">Please check your email for this morning's report.<br>If you did not receive it, please place a request on the Helpdesk to rectify the problem.
			 <hr>
		  </td>
	   </tr>
	</table>
   

</html>
