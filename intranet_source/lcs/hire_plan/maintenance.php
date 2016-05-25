<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Director - HIRE PLAN";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

   $sType = "MAINTENANCE";
   $dType = "MAINTENANCE (Service code 7200 to 7277)";

   $vDate = $HTTP_GET_VARS[vDate];

   $today = date('m/d/Y');
   $dayOfWeek = date("w");
   if($dayOfWeek == 5){
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 3 ,date("Y")));
   }else{
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 1 ,date("Y")));
   }

   if ($vDate <> "") $tomorrow = $vDate;

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   //get supervisor name
   $sql = "select user_id, user_name from lcs_user where email_address = '$user_email'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
        $sup_id = ora_getcolumn($cursor, 0);
        $user = ora_getcolumn($cursor, 1);
   }

   $sql = "select * from productivity_lock where lock_type = 'HIRE_PLAN' and hire_date = to_date('$tomorrow','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
        $lock_hire = "Y";
   }else{
        $lock_hire = "N";
   }

   $sql = "select distinct location_id from location_category order by location_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   $index = 0;
   while (ora_fetch($cursor)){
	$locations[$index] = ora_getcolumn($cursor, 0);
        $index++;
   }
   
   $sql = "select type from service_type order by id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   $service_index = 0;
   while (ora_fetch($cursor)){
        $service_type[$service_index] = ora_getcolumn($cursor, 0);
        $service_index++;
   }


   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = '$sType' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = '$sType') b where a.commodity_code = b.commodity(+) order by commodity_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $budget_index = 0;
   $Budget = array();
   while (ora_fetch($cursor)){
        array_push($Budget, array('comm'=>trim(ora_getcolumn($cursor, 0)), 
				     'budget'=>trim(ora_getcolumn($cursor, 1)),
				     'ftl'=>trim(ora_getcolumn($cursor, 2)),
	                             'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $budget_index++;
   }

?>

<script language="JaveScript">
function exportExcel()
{
	
        alert('export to excel');
}
</script>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
  var lock = "<? echo $lock_hire ?>";

function checkDate(){
  var vDate = document.hire_request.hdate.value;
  var mm = vDate.substring(0, 2);
  var dd = vDate.substring(3, 5);
  var yy = vDate.substring(6, 10);

  var hDate = new Date(yy, mm-1 , dd, 0, 0, 0);
  var now = new Date();

  if (hDate > now ){
//        return true;
  }else{
        alert("Can't save hire plan for " +vDate );
        return false;
  }
  if (lock == "N"){
        return  true;
  }else{
        alert("Can't save locked hire plan.");
        return false;
  }
}

function changeDate(){
  var vDate = document.hire_request.hdate.value;

  document.location = 'maintenance.php?vDate='+vDate;
}

function updateChange(i){
  i = i * 12 + 3;

  document.hire_request.elements[i+10].style.color = "#000000";
  document.hire_request.elements[i+11].style.color = "#000000";

  var num = document.hire_request.elements[i+1].value;
  var ftl = document.hire_request.elements[i+3].value;
  var ltl = document.hire_request.elements[i+4].value;
  var ptls = document.hire_request.elements[i+7].value 

  var index = document.hire_request.elements[i+5].selectedIndex;

  var hours = num * 8;
  if (hours == 0)
  {
        document.hire_request.elements[i+2].value = "";
        document.hire_request.elements[i+8].value = "";
        document.hire_request.elements[i+10].value = "";
        document.hire_request.elements[i+11].value = "";
  }else{

        document.hire_request.elements[i+2].value = hours;
  }

  if (index > 0)
  {
  	var comm_value = document.hire_request.elements[i+5].options[index].value;
	var index1 = comm_value.indexOf("/");
        var comm = comm_value.substring(0,index1)
	var index2 = comm_value.indexOf("/", index1+1);
        var budget = comm_value.substring(index1+1, index2);
        var index3 = comm_value.indexOf("/", index2+1);
        var qty_ftl = comm_value.substring(index2+1, index3);
        var qty_ltl = comm_value.substring(index3+1);
	

//	if (type =="TRUCKLOADING")
//     		ptls = ftl * qty_ftl + ltl * qty_ltl;
    
        if (ptls > 0) 
	{
		document.hire_request.elements[i+7].value = ptls;
	}else{
		document.hire_request.elements[i+7].value = "";
	}
	
	if (hours > 0) var prod = ptls / hours;
	
	prod = Math.round(prod * 100) / 100;

        if (prod > 0){
		document.hire_request.elements[i+8].value = prod;
	}else{
		document.hire_request.elements[i+8].value = "";
	}
        document.hire_request.elements[i+9].value = budget;

	if (prod > 0)
	{	
		var p_d = prod - budget;
        	p_d = Math.round(p_d * 100) / 100;
        
        	if (p_d < 0)
		{
			document.hire_request.elements[i+10].value = "(" + -p_d + ")";
			document.hire_request.elements[i+11].value = "Hire Too Large";
		}else{
			document.hire_request.elements[i+10].value = p_d;
                        document.hire_request.elements[i+11].value = "Looking Good"; 
		}

        	if (p_d < 0)
		{
		 	document.hire_request.elements[i+10].style.color = "#FF0000";
		 	document.hire_request.elements[i+11].style.color = "#FF0000";
		}
	}
  }else{
  	document.hire_request.elements[i+9].value = "";
        document.hire_request.elements[i+10].value = "";
        document.hire_request.elements[i+11].value = "";
       
  }
  if (budget =="")
       document.hire_request.elements[i+11].value = "No Budget";

  updateTot();
}

function updateTot(){		
        var tot_num = 0;
        var tot_hours = 0;
        var tot_ftl = 0;
        var tot_ltl = 0;
        var tot_plt = 0;
        var tot_budget = 0;
        var bgt_count = 0;
	var avg_budget = 0;
	var avg_prod = 0;
        var avg_prod_budget = 0;
       	var flag = "";

	for (var i = 0; i < 5; i++){
           	j = i * 12 + 3;
		if (document.hire_request.elements[j+1].value != "")
	   		tot_num += parseFloat(document.hire_request.elements[j+1].value); 
           	if (document.hire_request.elements[j+2].value != "")
                	tot_hours += parseFloat(document.hire_request.elements[j+2].value);
           	if (document.hire_request.elements[j+3].value != "")
                	tot_ftl += parseFloat(document.hire_request.elements[j+3].value);
           	if (document.hire_request.elements[j+4].value != "")
                	tot_ltl += parseFloat(document.hire_request.elements[j+4].value);
           	if (document.hire_request.elements[j+7].value != "")
                	tot_plt += parseFloat(document.hire_request.elements[j+7].value);
	   	if (document.hire_request.elements[j+9].value != ""){
                	tot_budget += parseFloat(document.hire_request.elements[j+9].value);
			bgt_count ++;
           	}
	}
	if (bgt_count > 0 ) {
		avg_budget = tot_budget / bgt_count;
		avg_budget = Math.round(avg_budget * 100) / 100;
	}
	if (tot_hours > 0) avg_prod = tot_plt / tot_hours;
        
	avg_prod = Math.round(avg_prod * 100) / 100;

	if (avg_prod > 0 && avg_budget > 0 ){
		avg_prod_budget = avg_prod - avg_budget;
		avg_prod_budget = Math.round(avg_prod_budget * 100) / 100;
                
		if (avg_prod_budget < 0) {
			avg_prod_budget = "(" + -avg_prod_budget + ")";
			flag = "Hire Too Large";
		}else{
			flag = "Looking Good";
		}
	}

        
	if (tot_num == 0) tot_num = "";
	if (tot_hours == 0) tot_hours = "";
        if (tot_ftl == 0) tot_ftl = "";
        if (tot_ltl == 0) tot_ltl = "";
        if (tot_plt == 0) tot_plt = "";
        if (avg_budget == 0) avg_budget = "";
        if (avg_prod == 0) avg_prod = "";
        if (avg_prod_budget == 0) avg_prod_budget = "";

	j = 63;
	document.hire_request.elements[j].value = tot_num;
        document.hire_request.elements[j+1].value = tot_hours;
        document.hire_request.elements[j+2].value = tot_ftl;
        document.hire_request.elements[j+3].value = tot_ltl;
        document.hire_request.elements[j+4].value = tot_plt;
	document.hire_request.elements[j+5].value = avg_prod;
	document.hire_request.elements[j+6].value = avg_budget;
	document.hire_request.elements[j+7].value = avg_prod_budget;
	document.hire_request.elements[j+8].value = flag;
   
        if (avg_prod < avg_budget){
		document.hire_request.elements[j+7].style.color = "#FF0000";
		document.hire_request.elements[j+8].style.color = "#FF0000";
	}else{
		document.hire_request.elements[j+7].style.color = "#000000";
                document.hire_request.elements[j+8].style.color = "#000000";
	}
}

</script>
<form action="process.php" method="post" name="hire_request">

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2">
            <font size="5" face="Verdana" color="#0066CC">DAILY HIRE PLAN
            </font>
            <hr>
      </td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td valign = "top"><font size="4" face="Verdana" color="#0066CC"><b>Supervisor: <? echo $user ?> &nbsp;&nbsp;&nbsp;&nbsp;For: 
<input type="textbox" name="hdate" size=10 value="<? echo $tomorrow; ?>">
      <a href="javascript:show_calendar('hire_request.hdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
      <td valign = "top"><input type="button" name="view" value = "   View  " onClick = "changeDate()">&nbsp;&nbsp;&nbsp;
      			<input type="submit" name="save" value = "   Save  " onClick = "return checkDate()"></td>
   </tr>	
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b><? echo $dType?></b></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2>
	<table border="1" width="90%" cellpadding="0" cellspacing="0">
	   <tr>
	     
	       <td align="center"><b>Warehouse</b></td>
               <td align="center"><b>Hire</b></td>
               <td align="center"><b>Labor Hours</b></td>
               <td align="center"><b>FTL</b></td>
               <td align="center"><b>LTL</b></td>
               <td align="center"><b>Comm</b></td>
               <td align="center"><b>Comment</b></td>
               <td align="center"><b>Unit (*)</b></td>
               <td align="center"><b>Productivity <nobr>(Units/Man Hour)</nobr></b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>		
	   </tr>
	<? 
   	   $sql = "select type, location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = '$sType' and hire_date = to_date('$tomorrow','mm/dd/yyyy') and supervisor = '$user' order by location";
	   $statement = ora_parse($cursor, $sql);
	   ora_exec($cursor);

	   $rows = 0;
	   for ($i = 0; $i < 5; $i++){

 		if ($rows == $i && ora_fetch($cursor)){
			$rows = ora_numrows($cursor);
			$type = ora_getcolumn($cursor,0);
			$wing = ora_getcolumn($cursor, 1);
			$num = ora_getcolumn($cursor,2);
                        $hours = ora_getcolumn($cursor,3);
                        $ftl = ora_getcolumn($cursor,4);
                        $ltl = ora_getcolumn($cursor,5);
                        $comm = ora_getcolumn($cursor,6);
                        $comment = ora_getcolumn($cursor,7);
                        $plt = ora_getcolumn($cursor,8);
                        $prod = ora_getcolumn($cursor,9);
                        $budget = ora_getcolumn($cursor,10);
                        $prod_budget = ora_getcolumn($cursor,11);
 

			if ($num ==0) $num ="";
                        if ($hours ==0) $hours ="";
                        if ($ftl ==0) $ftl ="";
                        if ($ltl ==0) $ltl ="";
                        if ($plt ==0) $plt ="";
                        if ($prod ==0) $prod ="";
                        if ($budget ==0) $budget ="";
                       
		}else{
                        $wing = "";
                        $num = "";
                        $hours = "";
                        $ftl = "";
                        $ltl = "";
                        $comm = "";
                        $comment = "";
                        $plt = "";
                        $prod = "";
                        $budget = "";
                        $prod_budget = "";
 

		}
	?>
		
	   <tr>
	       <td><select name="wing[]">
		  <option value="">--Select--</option>
	<? for($j = 0; $j < $index; $j++){ 
       		if ($wing == $locations[$j]){
			$strSelected = "selected";
		}else{
			$strSelected = "";
		}
 	?>
		   <option value="<? echo $locations[$j]?>" <? echo $strSelected?>><? echo $locations[$j]?></option>
        <? } ?>		   
		   </select></td>
	       <td><input type="text" name="num[]" value = "<?echo $num?>" size = "2" onChange ="updateChange(<? echo $i?>)"></td>
	       <td><input type="text" name="hours[]" value = "<?echo $hours?>" size="3" onChange ="updateChange(<? echo $i?>)"></td>	
	       <td><input type="text" name="ftl[]" value = "<?echo $ftl?>" size = "2" onChange ="updateChange(<? echo $i?>)" disabled></td>
	       <td><input type="text" name="ltl[]" value = "<?echo $ltl?>" size = "2" onChange ="updateChange(<? echo $i?>)" disabled></td>
               <td><select name="comm[]" width = "10" onChange ="updateChange(<? echo $i?>)">

		   <option value = ""></option>
	<? for ($j = 0 ; $j < $budget_index; $j++) { 
		if ($comm ==$Budget[$j][comm]){
			$strSelected = "selected";
		}else{
			$strSelected = "";
		} 
	?>
		   <option value = "<? echo $Budget[$j][comm]."/".$Budget[$j][budget]."/".$Budget[$j][ftl]."/".$Budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $Budget[$j][comm] ?></option>
    	<? } ?>
		   </select>	
               </td>
               <td><input type="text" name="comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="plt[]" value = "<?echo $plt?>" size = "3" onChange ="updateChange(<? echo $i?>)"></td>
               <td><input type="text" name="prod[]" value = "<?echo $prod?>" size = "12" onChange ="updateChange(<? echo $i?>)"></td>
               <td><input type="text" name="budget[]" value = "<?echo $budget?>" size = "4" onChange ="updateChange(<? echo $i?>)"></td>
               <td><input type="text" name="prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange(<? echo $i?>)"></td>
               <td><input type="text" name="flag[]"  size = "12" onChange ="updateChange(<? echo $i?>)"></td>
	
	   </tr>
 	<? } ?>
	   <tr>
	       <td><b>Tot/Avg</b></tdp;</td>
               <td><input type="text" name="num[]"  size = "2" disabled></td>
               <td><input type="text" name="hours[]" size="3" disabled></td>
               <td><input type="text" name="ftl[]"  size = "2" disabled></td>
               <td><input type="text" name="ltl[]" size = "2" disabled></td>
               <td>&nbsp;</td>		
               <td>&nbsp;</td>
               <td><input type="text" name="plt[]" size = "3" disabled></td>
               <td><input type="text" name="prod[]" size = "12" disabled></td>
               <td><input type="text" name="budget[]" size = "4" disabled></td>
               <td><input type="text" name="prod_budget[]" size = "10" onChange ="updateTot()"></td>
               <td><input type="text" name="flag[]" size = "12" onChange ="updateTot()"></td>

	   </tr>
        </table>
      </td>
   </tr>

   <tr><td colspan="3">
	<input type = "hidden" name = "sup" value = "<? echo $user?>">
	<input type = "hidden" name = "type" value = "<? echo $sType ?>">
	</td>
   </tr>
    <tr>
            <td width="1%">&nbsp;</td>
            <td colspan = 2><p><font color="Red"><b>*&nbspPossible Units are: Pallet, Bundle, Piece, etc</b></font></p></td>
   </tr>
</table>
</form>
<br />

<? include("pow_footer.php"); ?>

<script language="javascript">

<?for($i = 0 ; $i < 5; $i++){ ?>
updateChange(<?echo $i?>);
//updateChange('BH',<?echo $i?>);
//updateChange('TS',<?echo $i?>);

<? } ?>
</script>

