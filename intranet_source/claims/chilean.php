<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Open Claim Report";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
  include("connect.php");

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
  }

  // make Oracle connection
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  $cursor = ora_open($conn);

  include("billing_functions.php");
  include("claim_function.php");

  $season = $HTTP_POST_VARS[season];

  if ($HTTP_POST_VARS[type] =="add"){
	$cust = $HTTP_POST_VARS[customer];
	addSeasonCustomer($pg_conn, $season, $cust);
  }else if ($HTTP_POST_VARS[type] =="remove" ){
        $sCust = $HTTP_POST_VARS[sCustomer];
        removeSeasonCustomer($pg_conn, $season, $sCust);
  }

  $seasons = getSeason($pg_conn);
  if ($season =="")
	$season = $seasons[0];
  
  $customer = getCustomerList($cursor, "Y");
  $sCustomer = getSeasonCustomerList($pg_conn, $season);
?>

<!-- Prebill Update Report - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>
<script language="javascript">
function changeCustomer(type){
  document.customer.action = "chilean.php";
  document.customer.type.value=type;
  document.customer.submit();
}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Chilean Fruit Claim Summary Report</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="0" cellspacing="4">
	    <form method="Post" name="customer" action="reports/report_chilean_breakdown.php" method="Post">
	    <input type=hidden name=type>
	    <tr>
	       <td colspan="5">&nbsp;</td>
	    </tr>
	    <tr>
	      <td width="5%">&nbsp;</td>
	      <td colspan=4>Season:
		 <select name="season" onchange="changeCustomer('')">
		<?	
			for($i=0; $i<count($seasons); $i++){
				if ($seasons[$i] == $season){
					$strSel = "selected";
				}else{
					$strSel = "";
				}
				printf("<option value=\"%s\" %s>%s</option>",$seasons[$i],$strSel, $seasons[$i]);
			}
		?>	
		 </select> 	
	      </td>	 
            </tr>
            <tr>
               <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td width="5%">&nbsp;</td>
	       <td>Un-selected Customer</td>
	       <td></td>
	       <td>Selected Customer</td>	
            </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       
	       <td width="40%" align="left"> 
	          <select name="customer[]"  multiple=true size=10 style="width:330px">
	       <?
                  while (list($customer_name, $customer_id) = each($customer)) {
			if(array_key_exists($customer_id, $sCustomer) == false){	
		    		printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  	}
		  }
	       ?>
	          </select>
               </td>
	       <td>
		<input type=button name="add" value="  >>  "  onclick="changeCustomer('add')">
		<br />
		<input type=button name="remove" value="  <<  " onclick="changeCustomer('remove')">
		</td>
	       <td width="40%" align="left">
                  <select name="sCustomer[]"  multiple=true size=10 style="width:330px">
               <?
                  while (list($customer_id, $customer_name) = each($sCustomer)) {
                    printf("<option value=\"%s\">%s</option>", $customer_id, $customer_name);
                  }
               ?>

		  </select>
	       </td> 		
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="6"></td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="center" colspan="3">
		   <table>
		      <tr>
			 <td align="center"> 
			    <input type="Submit" value="View Report">
			 </td>
    		      </tr>
		   </table>		
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
<?

// close Oracle connection
ora_close($cursor);
ora_logoff($conn);

?>
</table>

<? include("pow_footer.php"); ?>
