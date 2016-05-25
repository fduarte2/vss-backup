<?
  // All POW files need this session file included
  include("pow_session.php");

   $hDate = $HTTP_GET_VARS['hdate'];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);

  $sql = "insert into productivity_lock (lock_type, lock_date, hire_date) values ('HIRE_PLAN', sysdate, to_date('$hDate','mm/dd/yyyy'))";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  

  header("Location: index.php?hdate=$hDate");


?>
