<?
   $user = $HTTP_COOKIE_VARS[lcs_user];
   if($user == ""){
      header("Location: ../../lcs_login.php");
      exit;
   }
   $today = date('m/d/Y');
   $dayOfWeek = date("w");
   $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 1 ,date("Y")));

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $sql = "select distinct location_code from location_category where location_code like 'WING%' order by location_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
    
   $index = 0;
   while (ora_fetch($cursor)){
	$locations[$index] = ora_getcolumn($cursor, 0);
        $index++;
   }
   
   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'TRUCKLOADING' order by commodity";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $tl_budget_index = 0;
   $tl_budget = array();
   while (ora_fetch($cursor)){
        array_push($tl_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)), 
				     'budget'=>trim(ora_getcolumn($cursor, 1)),
				     'ftl'=>trim(ora_getcolumn($cursor, 2)),
	                             'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $tl_budget_index++;
   }

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'BACKHAUL' order by commodity";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $bh_budget_index = 0;
   $bh_budget = array();
   while (ora_fetch($cursor)){
        array_push($bh_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $bh_budget_index++;
   }

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'TERMINAL SERVICE' order by commodity";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $ts_budget_index = 0;
   $ts_budget = array();
   while (ora_fetch($cursor)){
        array_push($ts_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $ts_budget_index++;
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
function updateChange(type, i){

  if(type =="TL"){
  	i = i * 12 + 1
  }else if (type =="BH"){
	i = i * 12 + 70
  }else if (type =="TS"){
	i = i * 12 + 139
  }

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
	

	if (type =="TL")
     		ptls = ftl * qty_ftl + ltl * qty_ltl;
    
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
  updateTot(type);
}

function updateTot(type){		
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
		if (type =="TL"){
           		j = i * 12 + 1;
	   	}else if (type =="BH") {
			j = i * 12 + 70;
		}else if (type =="TS") {
			j = i * 12 + 139;
		}
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

       	if (type =="TL"){
              	j = i * 12 + 1;
   	}else if (type =="BH") {
        	j = i * 12 + 70;
      	}else if (type =="TS") {
        	j = i * 12 + 139;
   	}

        
	if (tot_num == 0) tot_num = "";
	if (tot_hours == 0) tot_hours = "";
        if (tot_ftl == 0) tot_ftl = "";
        if (tot_ltl == 0) tot_ltl = "";
        if (tot_plt == 0) tot_plt = "";
        if (avg_budget == 0) avg_budget = "";
        if (avg_prod == 0) avg_prod = "";
        if (avg_prod_budget == 0) avg_prod_budget = "";

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
	updateGrandTot();
}
function updateGrandTot(){
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

        for (var i = 0; i < 3; i++){
                j = i * 69 + 60
                if (document.hire_request.elements[j+1].value != "")
                        tot_num += parseFloat(document.hire_request.elements[j+1].value);
                if (document.hire_request.elements[j+2].value != "")
                        tot_hours += parseFloat(document.hire_request.elements[j+2].value);
                if (document.hire_request.elements[j+3].value != "")
                        tot_ftl += parseFloat(document.hire_request.elements[j+3].value);
                if (document.hire_request.elements[j+4].value != "")
                        tot_ltl += parseFloat(document.hire_request.elements[j+4].value);
                if (document.hire_request.elements[j+5].value != "")
                        tot_plt += parseFloat(document.hire_request.elements[j+5].value);
                if (document.hire_request.elements[j+7].value != ""){
                        tot_budget += parseFloat(document.hire_request.elements[j+7].value);
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

	j= 208;

        if (tot_num == 0) tot_num = "";
        if (tot_hours == 0) tot_hours = "";
        if (tot_ftl == 0) tot_ftl = "";
        if (tot_ltl == 0) tot_ltl = "";
        if (tot_plt == 0) tot_plt = "";
        if (avg_budget == 0) avg_budget = "";
        if (avg_prod == 0) avg_prod = "";
        if (avg_prod_budget == 0) avg_prod_budget = "";

        document.hire_request.elements[j].value = tot_num;
        document.hire_request.elements[j+1].value = tot_hours;
        document.hire_request.elements[j+2].value = tot_ftl;
        document.hire_request.elements[j+3].value = tot_ltl;
        document.hire_request.elements[j+6].value = tot_plt;
        document.hire_request.elements[j+7].value = avg_prod;
        document.hire_request.elements[j+8].value = avg_budget;
        document.hire_request.elements[j+9].value = avg_prod_budget;
        document.hire_request.elements[j+10].value = flag;

        if (avg_prod < avg_budget){
                document.hire_request.elements[j+9].style.color = "#FF0000";
                document.hire_request.elements[j+10].style.color = "#FF0000";
        }else{
                document.hire_request.elements[j+7].style.color = "#000000";
                document.hire_request.elements[j+8].style.color = "#000000";
        }

}
</script>
<form action="process.php" method="post" name="hire_request">

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 

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
      <td valign = "top"><font size="4" face="Verdana" color="#0066CC"><b>Supervisor: <? echo $user ?> &nbsp;&nbsp;&nbsp;&nbsp;For: <? echo $tomorrow ?> </b></font></td>
      <td valign = "top"><input type="submit" name="save" value = "   Save  "</td>
   </tr>	

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b>TRUCKLOADING</b></td>
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
               <td align="center"><b>PLT</b></td>
               <td align="center"><b>Productivity (PLT/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>		
	   </tr>
	<? 
   	   $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TRUCKLOADING' AND hire_date = to_date('$tomorrow','mm/dd/yyyy') and supervisor = '$user' order by location";
	   $statement = ora_parse($cursor, $sql);
	   ora_exec($cursor);

	   $rows = 0;
	   for ($i = 0; $i < 5; $i++){

 		if ($rows == $i && ora_fetch($cursor)){
			$rows = ora_numrows($cursor);
			$wing = ora_getcolumn($cursor, 0);
			$num = ora_getcolumn($cursor,1);
                        $hours = ora_getcolumn($cursor,2);
                        $ftl = ora_getcolumn($cursor,3);
                        $ltl = ora_getcolumn($cursor,4);
                        $comm = ora_getcolumn($cursor,5);
                        $comment = ora_getcolumn($cursor,6);
                        $plt = ora_getcolumn($cursor,7);
                        $prod = ora_getcolumn($cursor,8);
                        $budget = ora_getcolumn($cursor,9);
                        $prod_budget = ora_getcolumn($cursor,10);
 

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
	       <td><select name="tl_wing[]">
		  <option value=""></option>
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
	       <td><input type="text" name="tl_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('TL',<? echo $i?>)"></td>
	       <td><input type="text" name="tl_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('TL',<? echo $i?>)"></td>	
	       <td><input type="text" name="tl_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('TL',<? echo $i?>)"></td>
	       <td><input type="text" name="tl_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('TL',<? echo $i?>)"></td>
               <td><select name="tl_comm[]" width = "10" onChange ="updateChange('TL',<? echo $i?>)">
		   <option value = ""></option>
	<? for ($j = 0 ; $j < $tl_budget_index; $j++) { 
		if ($comm ==$tl_budget[$j][comm]){
			$strSelected = "selected";
		}else{
			$strSelected = "";
		} 
	?>
		   <option value = "<? echo $tl_budget[$j][comm]."/".$tl_budget[$j][budget]."/".$tl_budget[$j][ftl]."/".$tl_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $tl_budget[$j][comm] ?></option>
    	<? } ?>
		   </select>	
               </td>
               <td><input type="text" name="tl_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="tl_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('TL',<? echo $i?>)"></td>
               <td><input type="text" name="tl_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('TL',<? echo $i?>)"></td>
               <td><input type="text" name="tl_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('TL',<? echo $i?>)"></td>
               <td><input type="text" name="tl_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('TL',<? echo $i?>)"></td>
               <td><input type="text" name="tl_flag[]"  size = "12" onChange ="updateChange('TL',<? echo $i?>)"></td>
	
	   </tr>
 	<? } ?>
	   <tr>
	       <td><b>Tot/Avg</b></td>
               <td><input type="text" name="tl_num[]"  size = "3" disabled></td>
               <td><input type="text" name="tl_hours[]" size="4" disabled></td>
               <td><input type="text" name="tl_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="tl_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>		
               <td>&nbsp;</td>
               <td><input type="text" name="tl_plt[]" size = "4" disabled></td>
               <td><input type="text" name="tl_prod[]" size = "10" disabled></td>
               <td><input type="text" name="tl_budget[]" size = "5" disabled></td>
               <td><input type="text" name="tl_prod_budget[]" size = "10" onChange ="updateTot('TL')"></td>
               <td><input type="text" name="tl_flag[]" size = "12" onChange ="updateTot('TL')"></td>

	   </tr>
        </table>
      </td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2>&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b>BACKHAUL</b></td>
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
               <td align="center"><b>PLT</b></td>
               <td align="center"><b>Productivity (PLT/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'BACKHAUL' AND hire_date = to_date('$tomorrow','mm/dd/yyyy') and supervisor = '$user' order by location";

           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < 5; $i++){

                if ($rows == $i && ora_fetch($cursor)){
                        $rows = ora_numrows($cursor);
                        $wing = ora_getcolumn($cursor, 0);
                        $num = ora_getcolumn($cursor,1);
                        $hours = ora_getcolumn($cursor,2);
                        $ftl = ora_getcolumn($cursor,3);
                        $ltl = ora_getcolumn($cursor,4);
                        $comm = ora_getcolumn($cursor,5);
                        $comment = ora_getcolumn($cursor,6);
                        $plt = ora_getcolumn($cursor,7);
                        $prod = ora_getcolumn($cursor,8);
                        $budget = ora_getcolumn($cursor,9);
                        $prod_budget = ora_getcolumn($cursor,10);

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
               <td><select name="bh_wing[]">
		   <option value=""></option>
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
               <td><input type="text" name="bh_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('BH',<? echo $i?>)" disabled></td>
               <td><input type="text" name="bh_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('BH',<? echo $i?>)" disabled></td>
               <td><select name="bh_comm[]" width = "10" onChange ="updateChange('BH',<? echo $i?>)">
                   <option value = ""></option>
        <? for ($j = 0 ; $j < $bh_budget_index; $j++) {
                if ($comm ==$bh_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $bh_budget[$j][comm]."/".$bh_budget[$j][budget]."/".$bh_budget[$j][ftl]."/".$bh_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $bh_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="bh_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="bh_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('BH',<? echo $i?>)"></td>
               <td><input type="text" name="bh_flag[]"  size = "12" onChange ="updateChange('BH',<? echo $i?>)"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="bh_num[]"  size = "3" disabled></td>
               <td><input type="text" name="bh_hours[]" size="4" disabled></td>
               <td><input type="text" name="bh_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="bh_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="bh_plt[]" size = "4" disabled></td>
               <td><input type="text" name="bh_prod[]" size = "10" disabled></td>
               <td><input type="text" name="bh_budget[]" size = "5" disabled></td>
               <td><input type="text" name="bh_prod_budget[]" size = "10" onChange ="updateTot('BH')"></td>
               <td><input type="text" name="bh_flag[]" size = "12" onChange ="updateTot('BH')"></td>

           </tr>
        </table>
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2>&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b>TERMINAL SERVICE</b></td>
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
               <td align="center"><b>PLT</b></td>
               <td align="center"><b>Productivity (PLT/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TERMINAL SERVICE' AND hire_date = to_date('$tomorrow','mm/dd/yyyy') and supervisor = '$user' order by location";

           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < 5; $i++){

                if ($rows == $i && ora_fetch($cursor)){
                        $rows = ora_numrows($cursor);
                        $wing = ora_getcolumn($cursor, 0);
                        $num = ora_getcolumn($cursor,1);
                        $hours = ora_getcolumn($cursor,2);
                        $ftl = ora_getcolumn($cursor,3);
                        $ltl = ora_getcolumn($cursor,4);
                        $comm = ora_getcolumn($cursor,5);
                        $comment = ora_getcolumn($cursor,6);
                        $plt = ora_getcolumn($cursor,7);
                        $prod = ora_getcolumn($cursor,8);
                        $budget = ora_getcolumn($cursor,9);
                        $prod_budget = ora_getcolumn($cursor,10);

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
               <td><select name="ts_wing[]">
                   <option value=""></option>
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
               <td><input type="text" name="ts_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('TS',<? echo $i?>)" disabled></td>
               <td><input type="text" name="ts_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('TS',<? echo $i?>)" disabled></td>
               <td><select name="ts_comm[]" width = "10" onChange ="updateChange('TS',<? echo $i?>)">
                   <option value = ""></option>
        <? for ($j = 0 ; $j < $ts_budget_index; $j++) {
                if ($comm ==$ts_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $ts_budget[$j][comm]."/".$ts_budget[$j][budget]."/".$ts_budget[$j][ftl]."/".$ts_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $ts_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="ts_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="ts_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('TS',<? echo $i?>)"></td>
               <td><input type="text" name="ts_flag[]" size = "12" onChange ="updateChange('TS',<? echo $i?>)"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="ts_num[]"  size = "3" disabled></td>
               <td><input type="text" name="ts_hours[]" size="4" disabled></td>
               <td><input type="text" name="ts_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="ts_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="ts_plt[]" size = "4" disabled></td>
               <td><input type="text" name="ts_prod[]" size = "10" disabled></td>
               <td><input type="text" name="ts_budget[]" size = "5" disabled></td>
               <td><input type="text" name="ts_prod_budget[]" size = "10" onChange ="updateTot('TS')"></td>
               <td><input type="text" name="ts_flag[]" size = "12" onChange ="updateTot('TS')"></td>

           </tr>
        </table>
      </td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2>&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b>PAGE TOTAL</b></td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2>
        <table border="1" width="90%" cellpadding="0" cellspacing="0">
           <tr>
               <td><b>Tot/Avg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
               <td><input type="text" name="ptnum[]"  size = "3" disabled></td>
               <td><input type="text" name="pt_hours[]" size="4" disabled></td>
               <td><input type="text" name="pt_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="pt_ltl[]" size = "3" disabled></td>
               <td><input type="text" size = "6" disabled></td>
               <td><input type="text" size = "15" disabled></td>
               <td><input type="text" name="pt_plt[]" size = "4" disabled></td>
               <td><input type="text" name="pt_prod[]" size = "10" disabled></td>
               <td><input type="text" name="pt_budget[]" size = "5" disabled></td>
               <td><input type="text" name="pt_prod_budget[]" size = "10" onChange ="updateGrandTot()"></td>
               <td><input type="text" name="pt_flag[]" size = "12" onChange ="updateGrandTot()"></td>

           </tr>
        </table>
      </td>
   </tr>


   <tr><td colspan="3">
	<input type = "hidden" name = "hdate" value = "<? echo $tomorrow?>">
	<input type = "hidden" name = "sup" value = "<? echo $user?>">
	</td>
   </tr>
</table>
</form>
<br />

</body>
<script language="javascript">

<?for($i = 0 ; $i < 5; $i++){ ?>
updateChange('TL',<?echo $i?>);
updateChange('BH',<?echo $i?>);
updateChange('TS',<?echo $i?>);

<? } ?>
</script>
</html>
