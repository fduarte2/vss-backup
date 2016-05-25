<?
  $hDate = $HTTP_POST_VARS['hdate'];
  $sup = $HTTP_POST_VARS['sup'];

  $tl_wing = $HTTP_POST_VARS['tl_wing'];
  $tl_num = $HTTP_POST_VARS['tl_num'];
  $tl_hours = $HTTP_POST_VARS['tl_hours'];
  $tl_ftl = $HTTP_POST_VARS['tl_ftl'];
  $tl_ltl = $HTTP_POST_VARS['tl_ltl'];
  $tl_comm = $HTTP_POST_VARS['tl_comm'];
  $tl_comment = $HTTP_POST_VARS['tl_comment'];
  $tl_plt = $HTTP_POST_VARS['tl_plt'];
  $tl_prod = $HTTP_POST_VARS['tl_prod'];
  $tl_budget = $HTTP_POST_VARS['tl_budget'];
  $tl_prod_budget = $HTTP_POST_VARS['tl_prod_budget'];

  $bh_wing = $HTTP_POST_VARS['bh_wing'];
  $bh_num = $HTTP_POST_VARS['bh_num'];
  $bh_hours = $HTTP_POST_VARS['bh_hours'];
  $bh_ftl = $HTTP_POST_VARS['bh_ftl'];
  $bh_ltl = $HTTP_POST_VARS['bh_ltl'];
  $bh_comm = $HTTP_POST_VARS['bh_comm'];
  $bh_comment = $HTTP_POST_VARS['bh_comment'];
  $bh_plt = $HTTP_POST_VARS['bh_plt'];
  $bh_prod = $HTTP_POST_VARS['bh_prod'];
  $bh_budget = $HTTP_POST_VARS['bh_budget'];
  $bh_prod_budget = $HTTP_POST_VARS['bh_prod_budget'];

  $ts_wing = $HTTP_POST_VARS['ts_wing']; 
  $ts_num = $HTTP_POST_VARS['ts_num']; 
  $ts_hours = $HTTP_POST_VARS['ts_hours']; 
  $ts_ftl = $HTTP_POST_VARS['ts_ftl']; 
  $ts_ltl = $HTTP_POST_VARS['ts_ltl']; 
  $ts_comm = $HTTP_POST_VARS['ts_comm']; 
  $ts_comment = $HTTP_POST_VARS['ts_comment']; 
  $ts_plt = $HTTP_POST_VARS['ts_plt']; 
  $ts_prod = $HTTP_POST_VARS['ts_prod']; 
  $ts_budget = $HTTP_POST_VARS['ts_budget']; 
  $ts_prod_budget = $HTTP_POST_VARS['ts_prod_budget'];


   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $sql = "select user_id from lcs_user where user_name= '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor))
	$sup_id = ora_getcolumn($cursor, 0);
       
   $sql = "delete from hire_plan where type = 'TRUCKLOADING' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($tl_wing); $i++){	
	if ($tl_num[$i]<>""){
		if ($tl_num[$i]=="") $tl_num[$i]=0;
                if ($tl_ftl[$i]=="") $tl_ftl[$i]=0;
		if ($tl_ltl[$i]=="") $tl_ltl[$i]=0;
                if ($tl_plt[$i]=="") $tl_plt[$i]=0;
                if ($tl_prod[$i]=="") $tl_prod[$i]=0;
                if ($tl_budget[$i]=="") $tl_budget[$i]=0;
		if ($tl_comm[$i] <>""){
			$tl_comm[$i] = substr($tl_comm[$i], 0, 4);
		}       		

		$sql = "insert into hire_plan (type,hire_date, supervisor, sup_id, location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('TRUCKLOADING', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$tl_wing[$i]', $tl_num[$i], $tl_hours[$i], $tl_ftl[$i], $tl_ltl[$i], '$tl_comm[$i]', '$tl_comment[$i]', $tl_plt[$i],$tl_prod[$i], $tl_budget[$i], '$tl_prod_budget[$i]')";

		$statement = ora_parse($cursor, $sql);
   		ora_exec($cursor);
	}
   }

   $sql = "delete from hire_plan where type = 'BACKHAUL' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($bh_wing); $i++){
        if ($bh_num[$i]<>""){
                if ($bh_num[$i]=="") $bh_num[$i]=0;
                if ($bh_ftl[$i]=="") $bh_ftl[$i]=0;
                if ($bh_ltl[$i]=="") $bh_ltl[$i]=0;
                if ($bh_plt[$i]=="") $bh_plt[$i]=0;
                if ($bh_prod[$i]=="") $bh_prod[$i]=0;
                if ($bh_budget[$i]=="") $bh_budget[$i]=0;
                if ($bh_comm[$i] <>""){
                        $bh_comm[$i] = substr($bh_comm[$i], 0, 4);
                }

                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id, location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('BACKHAUL', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$bh_wing[$i]', $bh_num[$i], $bh_hours[$i], $bh_ftl[$i], $bh_ltl[$i], '$bh_comm[$i]', '$bh_comment[$i]', $bh_plt[$i],$bh_prod[$i], $bh_budget[$i], '$bh_prod_budget[$i]')";

                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
        }
   }

   $sql = "delete from hire_plan where type = 'TERMINAL SERVICE' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'"; 
   $statement = ora_parse($cursor, $sql); 
   ora_exec($cursor); 
 
   for($i = 0; $i < count($ts_wing); $i++){ 
        if ($ts_num[$i]<>""){ 
                if ($ts_num[$i]=="") $ts_num[$i]=0; 
                if ($ts_ftl[$i]=="") $ts_ftl[$i]=0; 
                if ($ts_ltl[$i]=="") $ts_ltl[$i]=0; 
                if ($ts_plt[$i]=="") $ts_plt[$i]=0; 
                if ($ts_prod[$i]=="") $ts_prod[$i]=0; 
                if ($ts_budget[$i]=="") $ts_budget[$i]=0; 
                if ($ts_comm[$i] <>""){ 
                        $ts_comm[$i] = substr($ts_comm[$i], 0, 4); 
                } 
 
                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('TERMINAL SERVICE', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$ts_wing[$i]', $ts_num[$i], $ts_hours[$i], $ts_ftl[$i], $ts_ltl[$i], '$ts_comm[$i]', '$ts_comment[$i]', $ts_plt[$i],$ts_prod[$i], $ts_budget[$i], '$ts_prod_budget[$i]')";
 
                $statement = ora_parse($cursor, $sql); 
                ora_exec($cursor); 
        } 
   }
header("Location: index.php");

?>
