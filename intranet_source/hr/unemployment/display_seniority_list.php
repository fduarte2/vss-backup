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
  //$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
   }
  
  $cursor = ora_open($conn);

	
   $sql = "select distinct a.EFF_DATE from employee_seniority a order by a.EFF_DATE desc";

   $statement = ora_parse($cursor, $sql);
   
   ora_exec($cursor);
   
   
   
   $sel_eff_date = $HTTP_POST_VARS['eff_date'];
   $no_sel_date=-10;
   
   //printf("selected date:" . $sel_eff_date . "strlen($sel_eff_date):" . strlen($sel_eff_date) . "<br>" );
   
   
   if ($sel_eff_date == " ") 
   {
   	$no_sel_date = 1;
   }
   else
   {
   	if ($sel_eff_date == "1") 
   	{
   		$no_sel_date = 1;
   	}
   	else
   	{
   		$no_sel_date = -1;
   	}
   }
   
   //printf("'$no_sel_date:'" . $no_sel_date . "<br>");

?>   
   
   
   <br><br>
   <form name="display_seniority_list"  action="display_seniority_list.php" method="post">
   <select name="eff_date" >
   <option value="1" <?if ($no_sel_date > 0 ) echo "selected"?>>-Select an Effective Date-
   
   <?
   
      while (ora_fetch($cursor)){
   	$eff_date = ora_getcolumn($cursor,0);

 
   ?>
   
   <option value="<?=$eff_date ?>" <?if ($sel_eff_date == $eff_date) echo "selected"?> ><?=$eff_date ?>
   

     
   
   
<?  } ?>
   

   </select>
   <input type="submit" value="Display Seniority List">
   </form><hr><br>
   
  <?
  	
  	 
  	if (strlen($sel_eff_date) > 1 )
  	{
  		
  		//printf($sel_eff_date . "<br>");
  		
  		$sql="select b.SENIORITY, b.EMPLOYEE_ID, a.EMPLOYEE_NAME, TO_CHAR(b.EFF_DATE, 'mm/dd/yyyy')
			from employee a, employee_seniority b
			where b.EFF_DATE='$sel_eff_date'
			and substr(a.EMPLOYEE_ID, 4) =b.EMPLOYEE_ID
			order by b.SENIORITY";
  		//printf($sql . "<br>");
  		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
	      	
?>

	<table border="1">
	<tr><th>Seniority</th><th>Employee Name</th><th>Employee ID</th><th>Effective Date</th></tr>

<?
	      		      	
	      	while (ora_fetch($cursor)){
   			$emp_sp = ora_getcolumn($cursor,0);
   			$emp_id = ora_getcolumn($cursor,1);
   			$emp_name = ora_getcolumn($cursor,2);
   			$eff_date = ora_getcolumn($cursor,3);
?>  		
  			<tr><td><?=$emp_sp ?></td><td><?=$emp_name ?></td><td><?=$emp_id ?></td><td><?=$eff_date ?></td></tr>

<?
		}  			
?>	
 
  	</table>
  	<br>
  	<hr>
  	<br>
  	Back To <a href="display_index.php">View Employee Information Main Page</a>  
  
  <?
  	}    
  ?>
   
   
   