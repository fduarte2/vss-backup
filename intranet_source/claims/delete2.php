<?
  // Seth Morecraft 19-OCT-03
  // All POW files need this session file included
  include("pow_session.php");

   $user = $userdata['username'];

   $claim_id = $HTTP_POST_VARS['claim_select'];
   $line_id = $HTTP_POST_VARS['line_id'];

   // free-form takes the lead
   $claim_id_free = $HTTP_POST_VARS['claim_free'];
   if($claim_id_free != "")
     $claim_id = $claim_id_free;

   if($claim_id == ""){
     header("Location: delete.php?input=1");
     exit;
   }

   include("connect.php");

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if (!$bni_conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
  }
//  $cursor = ora_open($bni_conn);
  $ex_postgres_cursor = ora_open($bni_conn);   

/*   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   if (!$connection){
     die("Could not open connection to database server");
   }*/
   $sql = "select * from claim_log_oracle where claim_id = '$claim_id'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	if(!ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//   $result = pg_query($connection, $stmt) or die("Error in query: $stmt. " . pg_last_error($connection));
   // Find out if we need a unique line ID
//   $rows = pg_num_rows($result);

   // If the wrong claim ID was entered, go back and try again
//   if($rows <= 0){
     header("Location: delete.php?input=2");
     exit;
   }
   else{
     if($line_id == ""){
       // We need to go back to the previous page and select a line id
       header("Location: delete.php?select_line=$claim_id");
       exit;
     }
     else{
       $sql = "delete from claim_log_oracle where claim_id = '$claim_id' and line_id = '$line_id'";
//       $result = pg_query($connection, $stmt) or die("Error in query: $stmt. " . pg_last_error($connection));
		ora_parse($ex_postgres_cursor, $sql);
		ora_exec($ex_postgres_cursor);
       header("Location: delete.php?edit_claim_id=$claim_id");
       exit;
     }
   }
?>
