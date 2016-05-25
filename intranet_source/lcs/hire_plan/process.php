<?

  // All POW files need this session file included
  include("pow_session.php");

  $hDate = $HTTP_POST_VARS['hdate'];
//  $sup = $HTTP_POST_VARS['sup'];
  $sup = $userdata['user_email'];
  $type = $HTTP_POST_VARS['type'];

  $wing = $HTTP_POST_VARS['wing'];
  $num = $HTTP_POST_VARS['num'];
  $hours = $HTTP_POST_VARS['hours'];
  $ftl = $HTTP_POST_VARS['ftl'];
  $ltl = $HTTP_POST_VARS['ltl'];
  $comm = $HTTP_POST_VARS['comm'];
  $comment = $HTTP_POST_VARS['comment'];
  $plt = $HTTP_POST_VARS['plt'];
  $productivity = $HTTP_POST_VARS['prod'];
  $budget = $HTTP_POST_VARS['budget'];
  $prod_budget = $HTTP_POST_VARS['prod_budget'];


  include("connect.php");
   $conn = ora_logon("LABOR@$lcs", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

   $sql = "select * from productivity_lock where lock_type='HIRE_PLAN' and hire_date = to_date('$hDate','mm/dd/yyyy')";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
	header("Location: index.php");
   }

   //get supervisor id
//   $sql = "select user_id from lcs_user where user_name= '$sup'";
   $sql = "select user_id, user_name from lcs_user where email_address = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
	$sup_id = ora_getcolumn($cursor, 0);
        $sup = ora_getcolumn($cursor, 1);
   }
       
   //delete from hire_plan
   $sql = "delete from hire_plan where type = '$type' and hire_date = to_date('$hDate','mm/dd/yyyy') and supervisor = '$sup'";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   for($i = 0; $i < count($num); $i++){	
	if ($num[$i]<>""){
		if ($num[$i]=="") $num[$i]=0;
                if ($ftl[$i]=="") $ftl[$i]=0;
		if ($ltl[$i]=="") $ltl[$i]=0;
                if ($plt[$i]=="") $plt[$i]=0;
                if ($productivity[$i]=="") $productivity[$i]=0;
                if ($budget[$i]=="") $budget[$i]=0;
		if ($comm[$i] <>""){
			$pos = strpos($comm[$i],"/");
			if ($pos > 0)
				$comm[$i] = substr($comm[$i], 0, $pos);
		}       		
		$sql = "insert into hire_plan (type,hire_date, supervisor, sup_id, location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity, comments, plts, productivity, budget, prod_budget) values ('$type', to_date('$hDate','mm/dd/yyyy'),'$sup','$sup_id','$wing[$i]', $num[$i], $hours[$i], $ftl[$i], $ltl[$i], '$comm[$i]', '$comment[$i]', $plt[$i],$productivity[$i], $budget[$i], '$prod_budget[$i]')";

		$statement = ora_parse($cursor, $sql);
   		ora_exec($cursor);
	}
   }

header("Location: index.php");

?>
