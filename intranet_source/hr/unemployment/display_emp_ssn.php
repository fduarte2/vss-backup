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
  //$conn = ora_logon("LABOR@bni_backup", "LABOR_DEV");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
   }

   $cursor = ora_open($conn);

   //$sql = "select * from EMPLOYEE_SSN ORDER BY EMPLOYEE_ID";
   $sql="select b.EMPLOYEE_ID, a.EMPLOYEE_NAME, b.ssn from employee a, employee_ssn b where substr(a.EMPLOYEE_ID, 4) =b.EMPLOYEE_ID order by b.EMPLOYEE_ID";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

?>

 <b>Employee ID&nbsp-&nbspSSN&nbsp List (Order by Employee ID)</b>
 <br><br>


  <table border="1">
  	<tr><th>Employee ID</th><th>Employee Name</th><th>SSN</th></tr>

<?

   while (ora_fetch($cursor)){
		$emp_id = ora_getcolumn($cursor,0);
		$emp_name = ora_getcolumn($cursor,1);
		$emp_ssn = ora_getcolumn($cursor,2);
?>

    <tr><td><?=$emp_id ?></td><td><?=$emp_name ?></td><td><?=$emp_ssn ?></td></tr>


<?  } ?>


</table>
<?
	$row=ora_numrows($cursor);
?>
	<br><b>Total&nbspnumber&nbspof&nbspemployees&nbspin&nbspthe&nbsplist: <?=$row ?></b>
	 <br>
	  <hr>
	 <br>
  	Back To <a href="display_index.php">View Employee Information Main Page</a>
<?
  ora_close($cursor);
  ora_logoff($conn);


?>


