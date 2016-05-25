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

   $today = date('m/d/Y');
   $dayOfWeek = date("w");
   $last_mon = date('m/d/Y', mktime(0,0,0,date("m"), date("d") - $dayOfWeek - 6, date("Y")));
   $last_sun = date('m/d/Y', mktime(0,0,0,date("m"), date("d") - $dayOfWeek, date("Y"))); 
   $yesterday = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));

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

//  $reqId = $HTTP_POST_VARS[reqId];
  $empId = $HTTP_POST_VARS[empId];
  $eDate = $HTTP_POST_VARS[eDate];

  if ($eDate =="")
	$eDate = $last_sun;

  $sql = "select request_id from unemployment_claim_request 
	  where substr(employee_id,4,4) = '".substr($empId, -4)."' and pay_period_end_date = to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$reqId = ora_getcolumn($cursor, 0);
  }else{
	$reqId = "";
  }

  $sql = "select ssn from employee_ssn where employee_id = '".substr($empId, -4)."'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$ssn = ora_getcolumn($cursor, 0);
  }else{
	$ssn = "";
  }

  $sql = "select pay_rate from employee_rate where employee_id = '".substr($empId, -4)."' and 
	  eff_date <=to_date('$eDate','mm/dd/yyyy') order by eff_date desc";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
        $rate = ora_getcolumn($cursor, 0);
  }else{
	$rate = "";
  }


?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<script language="JavaScript">
function retrieve()
{
   var id = document.unemployment.empId.value;
   document.unemployment.action="";
   document.unemployment.submit();
}

function clickReset()
{
   document.location = "index.php";
}
function exportToExcel()
{
        //alert('export to excel');
}
</script>
<style>td{font:12px}</style>
<!--<form action="unemployment.php" method="post" name="unemployment">-->
<form action="unemp.php" method="post" name="unemployment">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Unemployment Claim Form
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
<!--
	    <font size="2" face="Verdana"><b>Please enter Employee ID, SSN, pay rate and pay period ending date.</b></font><br /><br /><br />
-->
         <table border="0" width="90%" cellpadding="4" cellspacing="0">
         <tr>
                <td width = "140"><b> Request ID:</b></td>
                <td><input type = "textbox" name = "reqId" size ="11" value="<?= $reqId?>" readonly></td>
	 </tr>
         <tr>
		<td width = "140"><b> Employee ID:</b></td>
	        <td><input type = "textbox" name = "empId" size ="11" value="<?= $empId?>" onchange="javascript:retrieve()"></td>
 	 </tr>
	 <tr>
              	<td><b> SSN:</b> </td><td><input type = "textbox" name = "ssn" value="<?= $ssn?>" size = 11></td>
	 </tr>
	 <tr>         
     		<td><b> Pay Rate:</b> </td><td><input type = "textbox" name = "bRate" size = 11 value="<?= $rate?>"> </td>
 	 </tr>
	 <tr>
              	<td><b> Pay Period Ending:</b></td><td><input type="textbox" name="eDate" size=10 value="<? echo $eDate; ?>" onchange="retrieve()"><a href="javascript:show_calendar('unemployment.eDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a> </td>
         </tr>
         <tr>
                <td><b> Holiday Pay:</b> </td><td><input type = "textbox" name = "hPay" size = 11></td>
         </tr>

         <tr>
                <td><b> Holiday Date:</b></td><td><input type="textbox" name="holiday" size=10 ><a href="javascript:show_calendar('unemployment.holiday');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a> </td>
         </tr>

         <tr>
 		<td colspan= 2>&nbsp;</td>
         </tr>
	 <tr>
	      <td colspan= 2> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;<input type="button" value=" Reset " onclick = "javascript:clickReset()">
		</td>
       	 </tr>

            </form>
         </p>
         </table>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
