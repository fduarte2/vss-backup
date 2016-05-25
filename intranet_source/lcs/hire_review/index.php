<?
// Modified Adam Walter, July 2006, to account for the case where people
// show up in the hire plan, but no longer work here, and therefore
// should not be shown in the dropdown box

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $title = "Director - HIRE REVIEW";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

  $user_occ = $userdata['user_occ'];
//  if (stristr($user_occ,"Supervisor") <>false){
  if (trim($user_occ) == "Supervisor"){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }

   $today = date('m/d/Y');
   $dayOfWeek = date("w");

   if($dayOfWeek == 5){
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 3 ,date("Y")));
   }else{
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 1 ,date("Y")));
   }

   $vDate = $HTTP_GET_VARS[hdate];
   $sup = $HTTP_GET_VARS[sup];

   if ($vDate =="") $vDate = $tomorrow;
   



   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $sql = "select * from productivity_lock where lock_type = 'HIRE_PLAN' and hire_date = to_date('$vDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
	$lock_disable="disabled";
   }else{
	$lock_disable = "";
   }

   $sql = "select distinct location_code from location_category where location_code like 'WING%' order by location_code";
   $sql = "select distinct location_id from location_category order by location_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
    
   $index = 0;
   while (ora_fetch($cursor)){
	$locations[$index] = ora_getcolumn($cursor, 0);
        $index++;
   }
   
   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'TRUCKLOADING' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'TRUCKLOADING' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

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
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'BACKHAUL' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

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
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'TERMINAL SERVICE' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

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

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'RAIL CAR HANDLING' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'RAIL CAR HANDLING' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $rc_budget_index = 0;
   $rc_budget = array();
   while (ora_fetch($cursor)){
        array_push($rc_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $rc_budget_index++;
   }

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'CONTAINER HANDLING' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'CONTAINER HANDLING' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $ct_budget_index = 0;
   $ct_budget = array();
   while (ora_fetch($cursor)){
        array_push($ct_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $ct_budget_index++;
   }

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'MAINTENANCE' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'MAINTENANCE' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $mt_budget_index = 0;
   $mt_budget = array();
   while (ora_fetch($cursor)){
        array_push($mt_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $mt_budget_index++;
   }

   $sql = "select commodity, budget, qty_ftl, qty_ltl from budget where type = 'NON REVENUE' order by commodity";
   $sql = "select commodity_code, budget, qty_ftl, qty_ltl from commodity a, (select * from budget where type = 'NON REVENUE' ) b where a.commodity_code = b.commodity(+) order by commodity_code";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $nr_budget_index = 0;
   $nr_budget = array();
   while (ora_fetch($cursor)){
        array_push($nr_budget, array('comm'=>trim(ora_getcolumn($cursor, 0)),
                                     'budget'=>trim(ora_getcolumn($cursor, 1)),
                                     'ftl'=>trim(ora_getcolumn($cursor, 2)),
                                     'ltl'=>trim(ora_getcolumn($cursor, 3))));
        $nr_budget_index++;
   }



  if ($sup <> "") {
	$tl_sql = "select count(*) from hire_plan where type = 'TRUCKLOADING' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $bh_sql = "select count(*) from hire_plan where type = 'BACKHAUL' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $ts_sql = "select count(*) from hire_plan where type = 'TERMINAL SERVICE' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $rc_sql = "select count(*) from hire_plan where type = 'RAIL CAR HANDLING' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $ct_sql = "select count(*) from hire_plan where type = 'CONTAINER HANDLING' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $mt_sql = "select count(*) from hire_plan where type = 'MAINTENANCE' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
        $nr_sql = "select count(*) from hire_plan where type = 'NON REVENUE' and hire_date = to_date('$vDate', 'mm/dd/yyyy') and supervisor = '$sup'";
  }else{
        $tl_sql = "select count(*) from hire_plan where type = 'TRUCKLOADING' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $bh_sql = "select count(*) from hire_plan where type = 'BACKHAUL' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $ts_sql = "select count(*) from hire_plan where type = 'TERMINAL SERVICE' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $rc_sql = "select count(*) from hire_plan where type = 'RAIL CAR HANDLING' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $ct_sql = "select count(*) from hire_plan where type = 'CONTAINER HANDLING' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $mt_sql = "select count(*) from hire_plan where type = 'MAINTENANCE' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
        $nr_sql = "select count(*) from hire_plan where type = 'NON REVENUE' and hire_date = to_date('$vDate', 'mm/dd/yyyy')";
  } 
   $statement = ora_parse($cursor, $tl_sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))  $tl_cnt = ora_getcolumn($cursor, 0);

   $statement = ora_parse($cursor, $bh_sql);
   ora_exec($cursor); 
   if (ora_fetch($cursor))  $bh_cnt = ora_getcolumn($cursor, 0);

   $statement = ora_parse($cursor, $ts_sql);
   ora_exec($cursor); 
   if (ora_fetch($cursor))  $ts_cnt = ora_getcolumn($cursor, 0);
   $statement = ora_parse($cursor, $rc_sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))  $rc_cnt = ora_getcolumn($cursor, 0);
   $statement = ora_parse($cursor, $ct_sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))  $ct_cnt = ora_getcolumn($cursor, 0);
   $statement = ora_parse($cursor, $mt_sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))  $mt_cnt = ora_getcolumn($cursor, 0);
   $statement = ora_parse($cursor, $nr_sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))  $nr_cnt = ora_getcolumn($cursor, 0);

   
   if ($tl_cnt < 5) $tl_cnt = 5;
   if ($bh_cnt < 5) $bh_cnt = 5;
   if ($ts_cnt < 5) $ts_cnt = 5;
   if ($rc_cnt < 5) $rc_cnt = 5;
   if ($ct_cnt < 5) $ct_cnt = 5;
   if ($mt_cnt < 5) $mt_cnt = 5;
   if ($nr_cnt < 5) $nr_cnt = 5;

?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
var tl_cnt = parseInt(<? echo $tl_cnt ?>);
var bh_cnt = parseInt(<? echo $bh_cnt ?>);
var ts_cnt = parseInt(<? echo $ts_cnt ?>);
var rc_cnt = parseInt(<? echo $rc_cnt ?>);
var ct_cnt = parseInt(<? echo $ct_cnt ?>);
var mt_cnt = parseInt(<? echo $mt_cnt ?>);
var nr_cnt = parseInt(<? echo $nr_cnt ?>);

function lockHire(){
  var hdate = document.hire_request.hdate.value;
  document.location = 'lock.php?hdate='+hdate;
}
function changeSupDate(){
  var sup = document.hire_request.sup.value;
  var hdate = document.hire_request.hdate.value;
  document.location = 'index.php?sup='+sup+'&hdate='+hdate;

}
function setupSave(){
  var sup = document.hire_request.sup.value;
  if (sup ==""){
	document.hire_request.save.disabled = true;
  }else{
        document.hire_request.save.disabled = false;
  }
}
function updateChange(type, i, isFTL){

  if(type =="TL"){
  	i = i*12 + 5
  }else if (type =="BH"){
	i = i * 12 + tl_cnt * 12 + 14;
  }else if (type =="TS"){
	i = i * 12 + tl_cnt * 12 + bh_cnt * 12 + 23; 
  }else if (type =="RC"){
	i = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + 32;
  }else if (type =="CT"){
        i = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + 41;
  }else if (type =="MT"){
        i = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + 50;
  }else if (type =="NR"){
        i = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + mt_cnt * 12 + 59;
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
	
        if (type =="TL" && isFTL =="Y")  
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
  if (budget =="")
       document.hire_request.elements[i+11].value = "No Budget";

  updateTot(type);
}

function updateTot(type){
        var cnt;		
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

 	if (type == "TL"){
		cnt = tl_cnt;
	}else if (type == "BH"){
		cnt = bh_cnt;
	}else if (type == "TS"){
		cnt = ts_cnt;
	}else if (type == "RC"){
                cnt = rc_cnt;
        }else if (type == "CT"){
                cnt = ct_cnt;
        }else if (type == "MT"){
                cnt = mt_cnt;
        }else if (type == "NR"){
                cnt = nr_cnt;
        }


	for (var i = 0; i < cnt; i++){
		if (type =="TL"){
           		j = i * 12 + 5;
	   	}else if (type =="BH") {
			j = i * 12 + tl_cnt * 12 + 14;
		}else if (type =="TS") {
			j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + 23;
		}else if (type =="RC"){
        		j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + 32;
  		}else if (type =="CT"){
        		j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + 41;
  		}else if (type =="MT"){
        		j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + 50;
  		}else if (type =="NR"){
        		j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + mt_cnt * 12 + 59;
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
              	j = i * 12 + 5;
   	}else if (type =="BH") {
           	j = i * 12 + tl_cnt * 12 + 14; 
       	}else if (type =="TS") { 
          	j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + 23;
    	}else if (type =="RC"){
            	j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + 32;
     	}else if (type =="CT"){
           	j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + 41;
   	}else if (type =="MT"){
            	j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + 50;
   	}else if (type =="NR"){
            	j = i * 12 + tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + mt_cnt * 12 + 59;
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

        for (var i = 0; i < 7; i++){
              //  j = i * 69 + 62
		if (i == 0) {
			j = tl_cnt * 12 + 4;
                }else if (i == 1) {
			j = (tl_cnt * 12) + (bh_cnt * 12) + 13;
                }else if (i == 2) {
			j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + 22; 
		}else if (i == 3) {
                        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + 31;
                }else if (i == 4) {
                        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 + 40;
                }else if (i == 5) {
                        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 +  mt_cnt * 12 + 49;
                }else if (i == 6) {
                        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 +  mt_cnt * 12 + nr_cnt * 12 + 58;
                }

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

//	j= 210;
//        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + 31;
        j = tl_cnt * 12 + bh_cnt * 12 + ts_cnt * 12 + rc_cnt * 12 + ct_cnt * 12 +  mt_cnt * 12 + nr_cnt * 12 + 68;

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

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2">
            <font size="5" face="Verdana" color="#0066CC">DAILY HIRE REVIEW
            </font>
            <hr>
      </td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td><font size="4" face="Verdana" color="#0066CC"><b>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font>
                <input type="textbox" name="hdate" size=10 value="<? echo $vDate; ?>">
                <a href="javascript:show_calendar('hire_request.hdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
      <td valign = "top">
	<input type="button" name="retrieve" value = " Retrieve " onClick="changeSupDate()">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="save" value="    Save    ">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="lock" value="    Lock    "  onClick ="lockHire()" <?echo $lock_disable?>> 
      </td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td valign = "top"><font size="4" face="Verdana" color="#0066CC"><b>Supervisor:</b></font><select name="sup" onChange="changeSupDate()">
            	<option value="">All</option>
   <?   
   // $sql = "select distinct supervisor from hire_plan where hire_date = to_date('$vDate', 'mm/dd/yyyy') order by supervisor";
	// $sql = "select user_name from lcs_user where status = 'A'  order by user_name";
	// $sql = "select distinct supervisor from hire_plan order by supervisor" ;
	$sql = "SELECT DISTINCT supervisor FROM HIRE_PLAN WHERE SUPERVISOR IN (SELECT user_name FROM LCS_USER WHERE status = 'A') ORDER BY supervisor";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        while (ora_fetch($cursor)){
                $supervisor = ora_getcolumn($cursor,0 );
                if ($supervisor == $sup){
			$strSelected = "selected";
		}else{
			$strSelected = "";
		}

   ?>
          	<option value = "<?echo $supervisor?>" <? echo $strSelected ?>><?echo $supervisor?></option>
   <? } ?>
        </select></td>
      <td></td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = 2><b>TRUCKLOADING (Service code 6220 to 6229, 6720 to 6729)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity <nobr>(Unit/Man Hour)</nobr></b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>		
	   </tr>
	<? 
           if ($sup <> "") {
   	   	$sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TRUCKLOADING' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
	   }else{
		$sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TRUCKLOADING' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
	   }
	   $statement = ora_parse($cursor, $sql);
	   ora_exec($cursor);

	   $rows = 0;
	   for ($i = 0; $i < $tl_cnt; $i++){

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
	       <td><input type="text" name="tl_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
	       <td><input type="text" name="tl_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>	
	       <td><input type="text" name="tl_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('TL',<? echo $i?>,'Y')"></td>
	       <td><input type="text" name="tl_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('TL',<? echo $i?>,'Y')"></td>
               <td><select name="tl_comm[]" width = "10" onChange ="updateChange('TL',<? echo $i?>,'N')">
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
               <td><input type="text" name="tl_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
               <td><input type="text" name="tl_prod[]" value = "<?echo $prod?>" size = "12" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
               <td><input type="text" name="tl_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
               <td><input type="text" name="tl_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
               <td><input type="text" name="tl_flag[]"  size = "12" onChange ="updateChange('TL',<? echo $i?>,'N')"></td>
	
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
               <td><input type="text" name="tl_prod[]" size = "12" disabled></td>
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
      <td colspan = 2><b>BACKHAUL (Service code 6110 to 6119, 6130 to 6149)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           if ($sup <> ""){
           	$sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'BACKHAUL' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
	   }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'BACKHAUL' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
	   }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $bh_cnt; $i++){

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
               <td><input type="text" name="bh_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('BH',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="bh_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('BH',<? echo $i?>,'N')" disabled></td>
               <td><select name="bh_comm[]" width = "10" onChange ="updateChange('BH',<? echo $i?>,'N')">
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
               <td><input type="text" name="bh_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>
               <td><input type="text" name="bh_flag[]"  size = "12" onChange ="updateChange('BH',<? echo $i?>,'N')"></td>

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
      <td colspan = 2><b>TERMINAL SERVICE (Service code 6520 to 6619)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
	   if ($sup <> "") {
           	$sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TERMINAL SERVICE' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
	   }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'TERMINAL SERVICE' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
           }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $ts_cnt; $i++){

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
               <td><input type="text" name="ts_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('TS',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="ts_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('TS',<? echo $i?>,'N')" disabled></td>
               <td><select name="ts_comm[]" width = "10" onChange ="updateChange('TS',<? echo $i?>,'N')">
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
               <td><input type="text" name="ts_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ts_flag[]" size = "12" onChange ="updateChange('TS',<? echo $i?>,'N')"></td>

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
      <td colspan = 2><b>RAIL CAR HANDLING (Service code 6310 to 6319)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           if ($sup <> "") {
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'RAIL CAR HANDLING' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
           }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'RAIL CAR HANDLING' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
           }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $rc_cnt; $i++){

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
               <td><select name="rc_wing[]">
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
               <td><input type="text" name="rc_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('RC',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="rc_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('RC',<? echo $i?>,'N')" disabled></td>
               <td><select name="rc_comm[]" width = "10" onChange ="updateChange('RC',<? echo $i?>,'N')">
                   <option value = ""></option>
        <? for ($j = 0 ; $j < $rc_budget_index; $j++) {
                if ($comm ==$rc_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $rc_budget[$j][comm]."/".$rc_budget[$j][budget]."/".$rc_budget[$j][ftl]."/".$rc_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $rc_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="rc_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="rc_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>
               <td><input type="text" name="rc_flag[]" size = "12" onChange ="updateChange('RC',<? echo $i?>,'N')"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="rc_num[]"  size = "3" disabled></td>
               <td><input type="text" name="rc_hours[]" size="4" disabled></td>
               <td><input type="text" name="rc_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="rc_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="rc_plt[]" size = "4" disabled></td>
               <td><input type="text" name="rc_prod[]" size = "10" disabled></td>
               <td><input type="text" name="rc_budget[]" size = "5" disabled></td>
               <td><input type="text" name="rc_prod_budget[]" size = "10" onChange ="updateTot('RC')"></td>
               <td><input type="text" name="rc_flag[]" size = "12" onChange ="updateTot('RC')"></td>

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
      <td colspan = 2><b>CONTAINER HANDLING (Service code 6410 to 6419)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           if ($sup <> "") {
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'CONTAINER HANDLING' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
           }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'CONTAINER HANDLING' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
           }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $ct_cnt; $i++){

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
               <td><select name="ct_wing[]">
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
               <td><input type="text" name="ct_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('CT',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="ct_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('CT',<? echo $i?>,'N')" disabled></td>
               <td><select name="ct_comm[]" width = "10" onChange ="updateChange('CT',<? echo $i?>,'N')">
                   <option value = ""></option>
        <? for ($j = 0 ; $j < $ct_budget_index; $j++) {
                if ($comm ==$ct_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $ct_budget[$j][comm]."/".$ct_budget[$j][budget]."/".$ct_budget[$j][ftl]."/".$ct_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $ct_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="ct_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="ct_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="ct_flag[]" size = "12" onChange ="updateChange('CT',<? echo $i?>,'N')"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="ct_num[]"  size = "3" disabled></td>
               <td><input type="text" name="ct_hours[]" size="4" disabled></td>
               <td><input type="text" name="ct_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="ct_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="ct_plt[]" size = "4" disabled></td>
               <td><input type="text" name="ct_prod[]" size = "10" disabled></td>
               <td><input type="text" name="ct_budget[]" size = "5" disabled></td>
               <td><input type="text" name="ct_prod_budget[]" size = "10" onChange ="updateTot('CT')"></td>
               <td><input type="text" name="ct_flag[]" size = "12" onChange ="updateTot('CT')"></td>

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
      <td colspan = 2><b>MAINTENANCE (Service code 7200 to 7277)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           if ($sup <> "") {
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'MAINTENANCE' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
           }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'MAINTENANCE' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
           }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $mt_cnt; $i++){

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
               <td><select name="mt_wing[]">
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
               <td><input type="text" name="mt_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('MT',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="mt_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('MT',<? echo $i?>,'N')" disabled></td>
               <td><select name="mt_comm[]" width = "10" onChange ="updateChange('MT',<? echo $i?>,'N')">
                   <option value = "">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        <? for ($j = 0 ; $j < $mt_budget_index; $j++) {
                if ($comm ==$mt_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $mt_budget[$j][comm]."/".$mt_budget[$j][budget]."/".$mt_budget[$j][ftl]."/".$mt_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $mt_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="mt_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="mt_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>
               <td><input type="text" name="mt_flag[]" size = "12" onChange ="updateChange('MT',<? echo $i?>,'N')"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="mt_num[]"  size = "3" disabled></td>
               <td><input type="text" name="mt_hours[]" size="4" disabled></td>
               <td><input type="text" name="mt_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="mt_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="mt_plt[]" size = "4" disabled></td>
               <td><input type="text" name="mt_prod[]" size = "10" disabled></td>
               <td><input type="text" name="mt_budget[]" size = "5" disabled></td>
               <td><input type="text" name="mt_prod_budget[]" size = "10" onChange ="updateTot('MT')"></td>
               <td><input type="text" name="mt_flag[]" size = "12" onChange ="updateTot('MT')"></td>

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
      <td colspan = 2><b>NON REVENUE (Service code 7300 to 7399)</b></td>
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
               <td align="center"><b>Unit</b></td>
               <td align="center"><b>Productivity (Units/Hours)</b></td>
               <td align="center"><b>Budget</b></td>
               <td align="center"><b>Productivity - Budget</b></td>
               <td align="center"><b>&nbsp;</b></td>
           </tr>
        <?
           if ($sup <> "") {
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'NON REVENUE' AND hire_date = to_date('$vDate','mm/dd/yyyy') and supervisor = '$sup' order by location";
           }else{
                $sql = "select location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget from hire_plan where type = 'NON REVENUE' AND hire_date = to_date('$vDate','mm/dd/yyyy') order by location";
           }
           $statement = ora_parse($cursor, $sql);
           ora_exec($cursor);

           $rows = 0;
           for ($i = 0; $i < $nr_cnt; $i++){

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
               <td><select name="nr_wing[]">
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
               <td><input type="text" name="nr_num[]" value = "<?echo $num?>" size = "3" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_hours[]" value = "<?echo $hours?>" size="4" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_ftl[]" value = "<?echo $ftl?>" size = "3" onChange ="updateChange('NR',<? echo $i?>,'N')" disabled></td>
               <td><input type="text" name="nr_ltl[]" value = "<?echo $ltl?>" size = "3" onChange ="updateChange('NR',<? echo $i?>,'N')" disabled></td>
               <td><select name="nr_comm[]" width = "10" onChange ="updateChange('NR',<? echo $i?>,'N')">
                   <option value = "">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        <? for ($j = 0 ; $j < $nr_budget_index; $j++) {
                if ($comm ==$nr_budget[$j][comm]){
                        $strSelected = "selected";
                }else{
                        $strSelected = "";
                }
        ?>
                   <option value = "<? echo $nr_budget[$j][comm]."/".$nr_budget[$j][budget]."/".$nr_budget[$j][ftl]."/".$nr_budget[$j][ltl] ?>" <?echo $strSelected?>><? echo $nr_budget[$j][comm] ?></option>
        <? } ?>
                   </select>
               </td>
               <td><input type="text" name="nr_comment[]" value = "<?echo $comment?>" size = "15"></td>
               <td><input type="text" name="nr_plt[]" value = "<?echo $plt?>" size = "4" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_prod[]" value = "<?echo $prod?>" size = "10" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_budget[]" value = "<?echo $budget?>" size = "5" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_prod_budget[]" value = "<?echo $prod_budget?>" size = "10" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>
               <td><input type="text" name="nr_flag[]" size = "12" onChange ="updateChange('NR',<? echo $i?>,'N')"></td>

           </tr>
        <? } ?>
           <tr>
               <td><b>Tot/Avg</b></td>
               <td><input type="text" name="nr_num[]"  size = "3" disabled></td>
               <td><input type="text" name="nr_hours[]" size="4" disabled></td>
               <td><input type="text" name="nr_ftl[]"  size = "3" disabled></td>
               <td><input type="text" name="nr_ltl[]" size = "3" disabled></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td><input type="text" name="nr_plt[]" size = "4" disabled></td>
               <td><input type="text" name="nr_prod[]" size = "10" disabled></td>
               <td><input type="text" name="nr_budget[]" size = "5" disabled></td>
               <td><input type="text" name="nr_prod_budget[]" size = "10" onChange ="updateTot('NR')"></td>
               <td><input type="text" name="nr_flag[]" size = "12" onChange ="updateTot('NR')"></td>

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
               <td><b>Tot/Avg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
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
</table>
</form>
<br />

<? include("pow_footer.php"); ?>

<script language="javascript">

<?for($i = 0 ; $i < $tl_cnt; $i++){ ?>
	updateChange('TL',<?echo $i?>,'N');
<?}
  for($i = 0 ; $i < $bh_cnt; $i++){ ?>
	updateChange('BH',<?echo $i?>,'N');
<?} 
  for($i = 0 ; $i < $ts_cnt; $i++){ ?> 
	updateChange('TS',<?echo $i?>,'N');
<? } ?>
<?for($i = 0 ; $i < $rc_cnt; $i++){ ?>
        updateChange('RC',<?echo $i?>,'N');
<?}
  for($i = 0 ; $i < $ct_cnt; $i++){ ?>
        updateChange('CT',<?echo $i?>,'N');
<?}
  for($i = 0 ; $i < $mt_cnt; $i++){ ?>
        updateChange('MT',<?echo $i?>,'N');
<?}
  for($i = 0 ; $i < $nr_cnt; $i++){ ?>
        updateChange('NR',<?echo $i?>,'N');
<? } ?>

setupSave();
	
</script>

