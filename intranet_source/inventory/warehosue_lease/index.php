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
  
  $id = $HTTP_GET_VARS[id];
  $whse = $HTTP_GET_VARS[whse];
  $box = $HTTP_GET_VARS[box];
  $sDate = trim($HTTP_GET_VARS[sDate]);
  $eDate = trim($HTTP_GET_VARS[eDate]);
  $eSDate = trim($HTTP_GET_VARS[eSDate]);
  $eEDate = trim($HTTP_GET_VARS[eEDate]);
  $cust = $HTTP_GET_VARS[cust];

//  include("connect_data_warehouse.php");

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$whse_cursor_bni = ora_open($conn);
	$box_cursor_bni = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$insert_cursor_bni = ora_open($conn);
/*  
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/
/*
  //get warehouse
  $sql = "select distinct warehouse from warehouse_box_detail";
  $whs_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $whs_rows = pg_num_rows($whs_result);
*/
//  if ($whse =="") $whse = "A";

  if ($id <>""){
	  // they chose an already-existing lease to modify.  get existing lease info
	$sql = "SELECT ID, WAREHOUSE, BOX, TO_CHAR(START_DATE, 'MM/DD/YYYY') THE_START, TO_CHAR(END_DATE, 'MM/DD/YYYY') THE_END, TO_CHAR(EXP_START_DATE, 'MM/DD/YYYY') THE_EXP_START, TO_CHAR(EXP_END_DATE, 'MM/DD/YYYY') THE_EXP_END, CUSTOMER FROM WAREHOUSE_LEASE WHERE ID = '".$id."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	if(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$whse = $short_term_row['WAREHOUSE'];
		$box = $short_term_row['BOX'];
		$sDate = $short_term_row['THE_START'];
		$eDate = $short_term_row['THE_END'];
		$eSDate = $short_term_row['THE_EXP_START'];
		$eEDate = $short_term_row['THE_EXP_END'];
		$cust = $short_term_row['CUSTOMER'];

		
/*
	$sql = "select id, warehouse, box, start_date, end_date, exp_start_date, exp_end_date, customer from warehouse_lease where id = $id";
	$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
	$rows = pg_num_rows($result);
	if ($rows > 0){
		$row = pg_fetch_row($result, 0);
		
		$whse = $row[1];
		$box = $row[2];
                if ($row[3]<>""){
                        $sDate = date('m/d/Y',strtotime($row[3]));
                }else{
                        $sDate = "";
                }
                if ($row[4]<>""){
                        $eDate = date('m/d/Y',strtotime($row[4]));
                }else{
                        $eDate = "";
                }
                if ($row[5]<>""){
                        $eSDate = date('m/d/Y',strtotime($row[5]));
                }else{
                        $eSDate = "";
                }
                if ($row[6]<>""){
                        $eEDate = date('m/d/Y',strtotime($row[6]));
                }else{
                        $eEDate = "";
                }
		$cust = $row[7];
*/
	}
  }
/*
  //get box
  $sql = "select box from warehouse_box_detail where warehouse = '$whse'  order by box";
  $box_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $box_rows = pg_num_rows($box_result);

  //get lease info
  $sql = "select id, warehouse, box, start_date, end_date, exp_start_date, exp_end_date, customer from warehouse_lease order by id desc";
  $lease_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $lease_rows = pg_num_rows($lease_result);
 */
?>
<html>
<head>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript" src="/functions/date_validation.js"></script>
<script language ="JavaScript">
function changeWhse()
{
        var whse = document.lease.whse.options[document.lease.whse.selectedIndex].value;
        var box = document.lease.box.options[document.lease.box.selectedIndex].value;
        var sDate = document.lease.sDate.value;
        var eDate = document.lease.eDate.value;
        var eSDate = document.lease.eSDate.value;
        var eEDate = document.lease.eEDate.value;
        var cust = document.lease.cust.value;

        document.location = 'index.php?whse='+whse+'&box='+box+'&sDate='+sDate+'&eDate='+eDate+'&eSDate='+eSDate+'&eEDate='+eEDate+'&cuts='+cust;
}
           
function clearUp(){
	document.location = 'index.php';
	return false;
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

<table border = "0" width = "100%" cellpadding = "4" cellspacing = "0">
   <tr>
	<td width = "1%">&nbsp;</td>
	<td>
	   <p align = "left">
		<font size = "5 " face = "Verdana" color = "##0066cc">Warehouse Lease
	   	</font>
	   	<hr>
	   </p>
	</td>
   </tr>
</table>

<table border="0" width="100%"  cellpadding="4" cellspacing="0">
<form action = "process.php" method = "post" name = "lease">
<input type = "hidden" name = "id" value = "<? echo $id ?>">
   <tr>
      <td width="1%">&nbsp;</td>
   	<td>
 	Warehouse:
	<select name="whse">
<?
	$sql = "SELECT DISTINCT WAREHOUSE FROM WAREHOUSE_BOX_DETAIL ORDER BY WAREHOUSE";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $short_term_row['WAREHOUSE']; ?>"<? if($short_term_row['WAREHOUSE'] == $whse){?> selected <?}?>><? echo $short_term_row['WAREHOUSE']; ?></option>
<?
	}
?>
			</select>
<?
/*		
		for($i = 0; $i < $whs_rows; $i++){
                $row = pg_fetch_row($whs_result, $i);
                $whs = $row[0];
                if ($whse ==$whs){
                        $strSelected = "SELECTED";
                }else{
                        $strSelected = "";
                }
                printf("<option value = '$whs' $strSelected>$whs</option>");
        }
*/
?>
   	&nbsp;&nbsp;&nbsp;&nbsp;
	Box:
	<select name="box">
<?
	$sql = "SELECT DISTINCT BOX FROM WAREHOUSE_BOX_DETAIL ORDER BY BOX";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $short_term_row['BOX']; ?>"<? if($short_term_row['BOX'] == $box){?> selected <?}?>><? echo $short_term_row['BOX']; ?></option>
<?
	}
?>
			</select>
<?
/*
        for($i = 0; $i < $box_rows; $i++){
                $row = pg_fetch_row($box_result, $i);
                $bx = $row[0];
		
		if ($box == $bx){
			$strSelected = "SELECTED";
		}else{
			$strSelected = "";
		}
		
                printf("<option value = '$bx' $strSelected>$bx</option>");
        }
*/
?>
	&nbsp;&nbsp;&nbsp;&nbsp;
        Customer: <input type="text" size ="30" name = "cust" value = "<? echo $cust ?>">
      	</td>
    </tr>
    <tr>
 	<td width="1%">&nbsp;</td>
	<td>
	<nobr>
	Expect Start Date: <input type = "text" size = "12" name = "eSDate" value ="<? echo $eSDate ?>" onBlur="ValidateDate(this)"><a href="javascript:show_calendar('lease.eSDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
        Expect End Date: <input type = "text" size = "12" name = "eEDate" value ="<? echo $eEDate ?>" onBlur="ValidateDate(this)"><a href="javascript:show_calendar('lease.eEDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
        </nobr>
	</td>
    </tr>
    <tr>
 	<td width="1%">&nbsp;</td>
	<td>
	<nobr>
        Actual Start Date: &nbsp;<input type = "text" size = "12" name = "sDate" value ="<? echo $sDate ?>" onBlur="ValidateDate(this)"><a href="javascript:show_calendar('lease.sDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        Actual End Date: <input type = "text" size = "12" name = "eDate" value ="<? echo $eDate ?>" onBlur="ValidateDate(this)"><a href="javascript:show_calendar('lease.eDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
        </nobr>
	</td>
    </tr>
 
   <tr>
      <td width="1%">&nbsp;</td>
      	<td colspan = "2" align = "center">
		<input type = "submit" name = "save" value = "  Save  ">
                &nbsp;&nbsp;
                &nbsp;&nbsp;
                &nbsp;&nbsp;
                &nbsp;&nbsp;
		<input type = "Submit" name = "clear" value = "  Clear  " onClick = "return clearUp()">
                &nbsp;&nbsp;
                &nbsp;&nbsp;
                &nbsp;&nbsp;
                &nbsp;&nbsp;
                <input type = "Submit" name = "delete" value = "  Delete " onClick = "return confirm('Are you sure you want to delete this record?')">

 	</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = "2">
        <table border="1"  cellpadding="4" cellspacing="0">
           <tr>
                <td width = "10"><b>ID</b></td>
		<td width = "15"><b>WHS</b></td>
		<td width = "200"><b>Box</b></td>
		<td width = "100"><b>Customer</b></td>
		<td width = "100"><nobr><b>Actual Start Date</b></nobr></td>
                <td width = "100"><nobr><b>Actual End Date</b></nobr></td>
                <td width = "100"><nobr><b>Expect Start Date</b></nobr></td>
                <td width = "100"><nobr><b>Expect End Date</b></nobr></td>

           </tr>
<?
	$sql = "SELECT ID, WAREHOUSE, BOX, TO_CHAR(START_DATE, 'MM/DD/YYYY') THE_START, TO_CHAR(END_DATE, 'MM/DD/YYYY') THE_END, TO_CHAR(EXP_START_DATE, 'MM/DD/YYYY') THE_EXP_START, TO_CHAR(EXP_END_DATE, 'MM/DD/YYYY') THE_EXP_END, CUSTOMER FROM WAREHOUSE_LEASE ORDER BY ID DESC";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><a href="index.php?id=<? echo $short_term_row['ID']; ?>"><? echo $short_term_row['ID']; ?></a></td>
		<td><? echo $short_term_row['WAREHOUSE']; ?> </td>
		<td><? echo $short_term_row['BOX']; ?> </td>
		<td><? echo $short_term_row['CUSTOMER']; ?> </td>
		<td>&nbsp<? echo $short_term_row['THE_START']; ?> </td>
		<td>&nbsp<? echo $short_term_row['THE_END']; ?> </td>
		<td>&nbsp<? echo $short_term_row['THE_EXP_START']; ?> </td>
		<td>&nbsp<? echo $short_term_row['THE_EXP_END']; ?> </td>
	</tr>
		

<?
/*	$index = 0;
	   for($i = 0; $i < $lease_rows; $i++){
                $row = pg_fetch_row($lease_result, $i);

		$_id = trim($row[0]);
		$_whse = trim($row[1]);
                $_box = trim($row[2]);
	
                if ($row[3]<>""){
                	$_sDate = date('m/d/Y',strtotime($row[3]));
		}else{
			$_sDate = "&nbsp";
		}
                if ($row[4]<>""){
                        $_eDate = date('m/d/Y',strtotime($row[4]));
                }else{
                        $_eDate = "&nbsp";
                }
                if ($row[5]<>""){
                        $_eSDate = date('m/d/Y',strtotime($row[5]));
                }else{
                        $_eSDate = "&nbsp";
                }
                if ($row[6]<>""){
                        $_eEDate = date('m/d/Y',strtotime($row[6]));
                }else{
                        $_eEDate = "&nbsp";
                }
		if ($row[7] <>""){
                	$_cust = trim($row[7]);
		}else{
			$_cust = "&nbsp";
		}
*/	?>
<!--	   <tr>
		<td><a href="index.php?id=<?echo $_id?>"><? echo $_id ?></a></td>
		<td><? echo $_whse ?> </td>
                <td><? echo $_box ?> </td>
                <td><? echo $_cust ?> </td>
                <td><? echo $_sDate ?> </td>
                <td><? echo $_eDate ?> </td>
                <td><? echo $_eSDate ?> </td>
                <td><? echo $_eEDate ?> </td> !-->
<?	/* $index ++; 
	  */
	  } 
?>
   </td>
  </tr>
</table>
</td>
</tr>
</table>
<?
          include("pow_footer.php");
	?>
