<?
  // All POW files need this session file included
  include("pow_session.php");

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

  $rc_wing = $HTTP_POST_VARS['rc_wing'];
  $rc_num = $HTTP_POST_VARS['rc_num'];
  $rc_hours = $HTTP_POST_VARS['rc_hours'];
  $rc_ftl = $HTTP_POST_VARS['rc_ftl'];
  $rc_ltl = $HTTP_POST_VARS['rc_ltl'];
  $rc_comm = $HTTP_POST_VARS['rc_comm'];
  $rc_comment = $HTTP_POST_VARS['rc_comment'];
  $rc_plt = $HTTP_POST_VARS['rc_plt'];
  $rc_prod = $HTTP_POST_VARS['rc_prod'];
  $rc_budget = $HTTP_POST_VARS['rc_budget'];
  $rc_prod_budget = $HTTP_POST_VARS['rc_prod_budget'];

  $ct_wing = $HTTP_POST_VARS['ct_wing'];
  $ct_num = $HTTP_POST_VARS['ct_num'];
  $ct_hours = $HTTP_POST_VARS['ct_hours'];
  $ct_ftl = $HTTP_POST_VARS['ct_ftl'];
  $ct_ltl = $HTTP_POST_VARS['ct_ltl'];
  $ct_comm = $HTTP_POST_VARS['ct_comm'];
  $ct_comment = $HTTP_POST_VARS['ct_comment'];
  $ct_plt = $HTTP_POST_VARS['ct_plt'];
  $ct_prod = $HTTP_POST_VARS['ct_prod'];
  $ct_budget = $HTTP_POST_VARS['ct_budget'];
  $ct_prod_budget = $HTTP_POST_VARS['ct_prod_budget'];

  $mt_wing = $HTTP_POST_VARS['mt_wing'];
  $mt_num = $HTTP_POST_VARS['mt_num'];
  $mt_hours = $HTTP_POST_VARS['mt_hours'];
  $mt_ftl = $HTTP_POST_VARS['mt_ftl'];
  $mt_ltl = $HTTP_POST_VARS['mt_ltl'];
  $mt_comm = $HTTP_POST_VARS['mt_comm'];
  $mt_comment = $HTTP_POST_VARS['mt_comment'];
  $mt_plt = $HTTP_POST_VARS['mt_plt'];
  $mt_prod = $HTTP_POST_VARS['mt_prod'];
  $mt_budget = $HTTP_POST_VARS['mt_budget'];
  $mt_prod_budget = $HTTP_POST_VARS['mt_prod_budget'];

  $nr_wing = $HTTP_POST_VARS['nr_wing'];
  $nr_num = $HTTP_POST_VARS['nr_num'];
  $nr_hours = $HTTP_POST_VARS['nr_hours'];
  $nr_ftl = $HTTP_POST_VARS['nr_ftl'];
  $nr_ltl = $HTTP_POST_VARS['nr_ltl'];
  $nr_comm = $HTTP_POST_VARS['nr_comm'];
  $nr_comment = $HTTP_POST_VARS['nr_comment'];
  $nr_plt = $HTTP_POST_VARS['nr_plt'];
  $nr_prod = $HTTP_POST_VARS['nr_prod'];
  $nr_budget = $HTTP_POST_VARS['nr_budget'];
  $nr_prod_budget = $HTTP_POST_VARS['nr_prod_budget'];

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
                        $pos = strpos($tl_comm[$i],"/");
                        if ($pos > 0)
                                $tl_comm[$i] = substr($tl_comm[$i], 0, $pos);

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
                        $pos = strpos($bh_comm[$i],"/");
                        if ($pos > 0)
                                $bh_comm[$i] = substr($bh_comm[$i], 0, $pos);

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
                        $pos = strpos($ts_comm[$i],"/");
                        if ($pos > 0)
                                $ts_comm[$i] = substr($ts_comm[$i], 0, $pos);
 
                } 
 
                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('TERMINAL SERVICE', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$ts_wing[$i]', $ts_num[$i], $ts_hours[$i], $ts_ftl[$i], $ts_ltl[$i], '$ts_comm[$i]', '$ts_comment[$i]', $ts_plt[$i],$ts_prod[$i], $ts_budget[$i], '$ts_prod_budget[$i]')";
 
                $statement = ora_parse($cursor, $sql); 
                ora_exec($cursor); 
        } 
   }

   $sql = "delete from hire_plan where type = 'RAIL CAR HANDLING' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($rc_wing); $i++){
        if ($rc_num[$i]<>""){
                if ($rc_num[$i]=="") $rc_num[$i]=0;
                if ($rc_ftl[$i]=="") $rc_ftl[$i]=0;
                if ($rc_ltl[$i]=="") $rc_ltl[$i]=0;
                if ($rc_plt[$i]=="") $rc_plt[$i]=0;
                if ($rc_prod[$i]=="") $rc_prod[$i]=0;
                if ($rc_budget[$i]=="") $rc_budget[$i]=0;
                if ($rc_comm[$i] <>""){
                        $pos = strpos($rc_comm[$i],"/");
                        if ($pos > 0)
                                $rc_comm[$i] = substr($rc_comm[$i], 0, $pos);
                }

                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('RAIL CAR HANDLING', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$rc_wing[$i]', $rc_num[$i], $rc_hours[$i], $rc_ftl[$i], $rc_ltl[$i], '$rc_comm[$i]', '$rc_comment[$i]', $rc_plt[$i],$rc_prod[$i], $rc_budget[$i], '$rc_prod_budget[$i]')";

                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
        }
   }

   $sql = "delete from hire_plan where type = 'CONTAINER HANDLING' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($ct_wing); $i++){
        if ($ct_num[$i]<>""){
                if ($ct_num[$i]=="") $ct_num[$i]=0;
                if ($ct_ftl[$i]=="") $ct_ftl[$i]=0;
                if ($ct_ltl[$i]=="") $ct_ltl[$i]=0;
                if ($ct_plt[$i]=="") $ct_plt[$i]=0;
                if ($ct_prod[$i]=="") $ct_prod[$i]=0;
                if ($ct_budget[$i]=="") $ct_budget[$i]=0;
                if ($ct_comm[$i] <>""){
                        $pos = strpos($ct_comm[$i],"/");
                        if ($pos > 0)
                                $ct_comm[$i] = substr($ct_comm[$i], 0, $pos);
                }

                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('CONTAINER HANDLING', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$ct_wing[$i]', $ct_num[$i], $ct_hours[$i], $ct_ftl[$i], $ct_ltl[$i], '$ct_comm[$i]', '$ct_comment[$i]', $ct_plt[$i],$ct_prod[$i], $ct_budget[$i], '$ct_prod_budget[$i]')";

                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
        }
   }

   $sql = "delete from hire_plan where type = 'MAINTENANCE' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($mt_wing); $i++){
        if ($mt_num[$i]<>""){
                if ($mt_num[$i]=="") $mt_num[$i]=0;
                if ($mt_ftl[$i]=="") $mt_ftl[$i]=0;
                if ($mt_ltl[$i]=="") $mt_ltl[$i]=0;
                if ($mt_plt[$i]=="") $mt_plt[$i]=0;
                if ($mt_prod[$i]=="") $mt_prod[$i]=0;
                if ($mt_budget[$i]=="") $mt_budget[$i]=0;
                if ($mt_comm[$i] <>""){
                        $pos = strpos($mt_comm[$i],"/");
                        if ($pos > 0)
                                $mt_comm[$i] = substr($mt_comm[$i], 0, $pos);
                }

                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('MAINTENANCE', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$mt_wing[$i]', $mt_num[$i], $mt_hours[$i], $mt_ftl[$i], $mt_ltl[$i], '$mt_comm[$i]', '$mt_comment[$i]', $mt_plt[$i],$mt_prod[$i], $mt_budget[$i], '$mt_prod_budget[$i]')";

                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
        }
   }

   $sql = "delete from hire_plan where type = 'NON REVENUE' AND hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($nr_wing); $i++){
        if ($nr_num[$i]<>""){
                if ($nr_num[$i]=="") $nr_num[$i]=0;
                if ($nr_ftl[$i]=="") $nr_ftl[$i]=0;
                if ($nr_ltl[$i]=="") $nr_ltl[$i]=0;
                if ($nr_plt[$i]=="") $nr_plt[$i]=0;
                if ($nr_prod[$i]=="") $nr_prod[$i]=0;
                if ($nr_budget[$i]=="") $nr_budget[$i]=0;
                if ($nr_comm[$i] <>""){
                        $pos = strpos($nr_comm[$i],"/");
                        if ($pos > 0)
                                $nr_comm[$i] = substr($nr_comm[$i], 0, $pos);
                }

                $sql = "insert into hire_plan (type,hire_date, supervisor, sup_id,  location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('NON REVENUE', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$nr_wing[$i]', $nr_num[$i], $nr_hours[$i], $nr_ftl[$i], $nr_ltl[$i], '$nr_comm[$i]', '$nr_comment[$i]', $nr_plt[$i],$nr_prod[$i], $nr_budget[$i], '$nr_prod_budget[$i]')";

                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
        }
   }


header("Location: index.php?sup=$sup&hdate=$hDate");

?>
