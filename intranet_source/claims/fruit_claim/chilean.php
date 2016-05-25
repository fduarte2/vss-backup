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

  // make Oracle connection
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  $cursor = ora_open($conn);

//  include("billing_functions.php");
include("../claim_function.php");
//include("./claim_function.php");
//  include("claim_function.php");

  $season = $HTTP_POST_VARS[season];
  $cargo_type = $HTTP_POST_VARS[cargo_type];
  if ($cargo_type =="")
	$cargo_type = "CHILEAN";
  $LR = $HTTP_POST_VARS["LR"];

  if ($HTTP_POST_VARS[type] =="add"){
	$cust = $HTTP_POST_VARS[customer];
	addRFSeasonCustomer($cursor, $season, $cust, $cargo_type, $LR);
  }elseif ($HTTP_POST_VARS[type] =="remove"){
	  if($LR == "truck"){
		  $cust = $HTTP_POST_VARS[tCustomer];
	  } else {
		  $cust = $HTTP_POST_VARS[sCustomer];
	  }
      removeRFSeasonCustomer($cursor, $season, $cust, $cargo_type, $LR);
  }

  $seasons = getRFSeason($cursor);
  if ($season =="")
	$season = $seasons[0];

	
  
  $customer = getClaimCustomerList($cursor);
  $sCustomer = getRFSeasonCustomerList($cursor, $season, $cargo_type, "ship");
  $tCustomer = getRFSeasonCustomerList($cursor, $season, $cargo_type, "truck");

//  print_r($sCustomer);
//  print_r($tCustomer);
?>

<!-- Prebill Update Report - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>
<script language="javascript">
function changeCustomer(form, type){
  form.action = "chilean.php";
  form.type.value=type;
  form.submit();
}

//function viewreport(type){
//  if (type == 1){
//	document.customer.action="report_chilean_breakdown.php";
//  }else if (type == 2){
//    document.customer.action="report_to_psw.php";
//  }else {
//	document.customer.action="report_to_psw_truck.php";
 // }
 // document.customer.submit();
//}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit Claim Summary Report</font><br>
		 <font size="3" face="Verdana" color="#0066CC">Note:  This screen can be used for any fruit commodity.  Simply select the list of customers, and they will display on the summary report.</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="season_form" action="chilean.php" method="post">
<input type="hidden" name="cargo_type" value="<? echo $cargo_type; ?>">
	<tr colspan="2">
		<td>Season:&nbsp;&nbsp;<select name="season" onchange="this.form.submit()"> <? // onchange="changeCustomer('')" ?>
		<?
			for($i=2005;$i<=date("Y", mktime(0,0,0,date("m")+4,date("d"),date("Y")));$i++)
				if($i == $season){
?>
					<option value="<? echo $i; ?>" selected><? echo $i; ?></option>
<?
				} else {
?>
					<option value="<? echo $i; ?>"><? echo $i; ?></option>
<?
				}
?>
				</select></td>
</form>
<form name="cargo_form" action="chilean.php" method="post">
<input type="hidden" name="season" value="<? echo $season; ?>">
		<td>Cargo Type:&nbsp;&nbsp;<select name="cargo_type" onchange="this.form.submit()"> <? // onchange="changeCustomer('')" ?>
		<?
			$sql = "SELECT CLAIM_CARGO_TYPE FROM CLAIM_CARGO_TYPES 
					ORDER BY CLAIM_CARGO_TYPE";
			  ora_parse($cursor, $sql);
			  ora_exec($cursor);
			  while (ora_fetch($cursor)){
?>
					<option value="<? echo ora_getcolumn($cursor, 0); ?>" <? if(ora_getcolumn($cursor, 0) == $cargo_type){?>selected<?}?>><? echo ora_getcolumn($cursor, 0); ?></option>
<?
				}
?>
				</select></td>

</form>
	</tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="0" cellspacing="4">
	    <form method="Post" name="cust_ship" action="report_to_psw.php" method="Post">
	    <input type="hidden" name=type>
	    <input type="hidden" name="LR" value="ship">
	    <input type="hidden" name="season" value="<? echo $season; ?>">
		<input type="hidden" name="cargo_type" value="<? echo $cargo_type; ?>">
	    <tr>
	       <td colspan="5"><b>Vessel-Cargo</b></td>
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
                  while (list($customer_id, $customer_name) = each($customer)) {
			if(array_key_exists($customer_id, $sCustomer) == false){	
		    		printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  	}
		  }
	       ?>
	          </select>
               </td>
	       <td>
		<input type=button name="add" value="  >>  "  onclick="changeCustomer(this.form, 'add')">
		<br />
		<input type=button name="remove" value="  <<  " onclick="changeCustomer(this.form, 'remove')">
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
					<input type="Submit" value="View POW & TSG Report (No Trucks)" onclick="javascript:viewreport(2)">
				 </td>
				  </tr>
		   </table>		
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</form>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="0" cellspacing="4">
	    <form name="cust_truck" action="report_to_psw_truck.php" method="Post">
	    <input type="hidden" name=type>
	    <input type="hidden" name="LR" value="truck">
	    <input type="hidden" name="season" value="<? echo $season; ?>">
		<input type="hidden" name="cargo_type" value="<? echo $cargo_type; ?>">
	    <tr>
	       <td colspan="5"><b>Truck-Cargo</b></td>
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
//					print_r($customer);
//					print_r($tCustomer);
					reset($customer);
                  while (list($customer_id, $customer_name) = each($customer)) {
			if(array_key_exists($customer_id, $tCustomer) == false){	
		    		printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  	}
		  }
	       ?>
	          </select>
               </td>
	       <td>
		<input type=button name="add" value="  >>  "  onclick="changeCustomer(this.form, 'add')">
		<br />
		<input type=button name="remove" value="  <<  " onclick="changeCustomer(this.form, 'remove')">
		</td>
	       <td width="40%" align="left">
                  <select name="tCustomer[]"  multiple=true size=10 style="width:330px">
               <?
                  while (list($customer_id, $customer_name) = each($tCustomer)) {
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
					<input type="Submit" value="View Trucked In Fruit" onclick="javascript:viewreport(3)">
				 </td>
				  </tr>
		   </table>		
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</form>
<?

// close Oracle connection
ora_close($cursor);
ora_logoff($conn);

?>
</table>

<? include("pow_footer.php"); ?>
