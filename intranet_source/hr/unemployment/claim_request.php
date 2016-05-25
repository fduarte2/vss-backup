<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  include("utility.php");
  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
   }
   $cursor = ora_open($conn);

   $status = $HTTP_POST_VARS['status'];
   if ($status == ""){
	$status = "Open";
   }

   if ($status <> "All"){
      	$strStatus = " status = '$status' ";
   }else{
	$strStatus = " 1=1 ";
   }
   $eDate = $HTTP_POST_VARS['eDate'];
   if ($eDate ==""){
	$dayOfWeek = date('w');
        if ($dayOfWeek == 0)
		$dayOfWeek = 7;
	$eDate = date('m/d/Y', mktime(0,0,0,date('m'), date('d')-$dayOfWeek-7, date('Y')));
   }

   $sql = "select request_id, r.employee_id, e.employee_name, to_char(pay_period_end_date,'mm/dd/yyyy'), 
	   to_char(request_date,'mm/dd/yy hh24:mi'), status
	   from unemployment_claim_request r, employee e 
	   where r.employee_id = e.employee_id and $strStatus and pay_period_end_date = to_date('$eDate','mm/dd/yyyy')
	   order by request_id desc";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   
?>
<style>td{font:12px}</style>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
function claim_form(reqId, empId, end_date)
{
   document.claim_request.reqId.value = reqId;
   document.claim_request.empId.value = empId;
   document.claim_request.end_date.value = end_date;
   document.claim_request.action = "claim.php";
   document.claim_request.submit();
}
function changeStatus()
{
   document.claim_request.submit();
}
</script>
<form name = "claim_request" action="" method="post">
<input type="hidden" name="reqId">
<input type="hidden" name="empId">
<input type="hidden" name="end_date">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Request For Unemployment Form
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <b>Status:</b>
	 <select name="status" onchange="javascript:changeStatus()">
	 	<option value="Open" <?if ($status=="Open") echo "selected"?>>Open</option>
		<option value="Processed" <?if ($status=="Processed") echo "selected"?>>Processed</option>
                <option value="Completed" <?if ($status=="Completed") echo "selected"?>>Completed</option>
		<option value="All" <?if ($status=="All") echo "selected"?>>All</option>
	 </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <b>Pay Period End Date:</b>
	 <input type="textbox" name="eDate" size=10 value="<? echo $eDate; ?>" onchange="changeStatus()"><a href="javascript:show_calendar('claim_request.eDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a> 
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <b>REQUEST LIST:</b>
         <table border="1" cellpadding="0" cellspacing="0">
            <tr>
		<td width='12%'><b>Req ID</b></td>
		<td width='12%'><b>EmpID</b></td>
                <td width='30%'><b>Emp Name</b></td>
                <td width='20%'><b>Pay Period End Date</b></td>
                <td width='20%'><b>Request Date</b></td>
		<td width='10%'><b>Status</b></td>
	    </tr>
<?	while (ora_fetch($cursor)){
		$req_id = ora_getcolumn($cursor,0);
                $emp_id = ora_getcolumn($cursor,1);
                $emp_name = ora_getcolumn($cursor,2);
                $end_date = ora_getcolumn($cursor,3);
                $req_date = ora_getcolumn($cursor,4);
                $status = ora_getcolumn($cursor,5);

?>
            <tr>
         	<td><a href="javascript:claim_form('<?=$req_id?>','<?=$emp_id?>','<?=$end_date?>')"><?=$req_id?></a></td>
		<td><?=$emp_id?></td>
                <td><?=$emp_name?></td>
                <td><?=$end_date?></td>
                <td><?=$req_date?></td>
                <td><?= html($status)?></td>
	    </tr>
<?	}	?> 
	 </table>
      </td>
   </tr>
</table>
</form>				    
<?
	include("pow_footer.php");
?>