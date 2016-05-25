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

	
   $sql = "select EMPLOYEE_ID from employee_ssn order by EMPLOYEE_ID";

   $statement = ora_parse($cursor, $sql);
   
   ora_exec($cursor);
   
   
   
   $selected_id = $HTTP_POST_VARS['empID'];
   
   //printf("selected_id:" . $selected_id);
   
   
   if ($selected_id == " ") {
   	$selected_id = "1";
   }

?>   
   
   
   <br><br>
   <form name="edit_emp_ssn"  action="edit_emp_ssn.php" method="post">
   <select name="empID" >
   <option value="1" <?if ($selected_id =="1") echo "selected"?>>-Select an Employee ID-
   
   <?
   
      while (ora_fetch($cursor)){
   	$emp_id = ora_getcolumn($cursor,0);

 
   ?>
   
   <option value="<?=$emp_id ?>" <?if ($selected_id == $emp_id) echo "selected"?> ><?=$emp_id ?>
   

     
   
   
<?  } ?>
   

   </select>
   <input type="submit" value="Find SSN">
   </form>
   
   
  <?
  	
  	
  	if (strlen($selected_id) > 1 )
  	{
  		$sql="select SSN from employee_ssn where EMPLOYEE_ID=" . "'" . trim($selected_id) . "'";
  		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
	      	while (ora_fetch($cursor)){
   			$emp_ssn = ora_getcolumn($cursor,0);
		}
  	
  ?>	
  	
  	
  	<b>SSN on file: <?=$emp_ssn ?></b>
  	<form name="edit_emp_ssn_process" action="edit_emp_ssn_process.php" method="post">
  	<input type="hidden" name="sel_emp_id" value="<?=$selected_id ?>">
  	<input type="hidden" name="old_ssn" value="<?=$emp_ssn ?>">
  	<b>Change SSN to:&nbsp&nbsp<input type="text" name="SSN"></b>
  	<input type="submit" value="Update SSN">
  	</form>
  
  
  
  <?
  	}
  
  
  ?>
   
   
   