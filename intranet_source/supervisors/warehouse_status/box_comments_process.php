<?
  include("pow_session.php");

  $user = $userdata['username'];

  include("utility.php");
  include("defines.php");
  include("connect.php");
 
// Connect to Lcs Database
   $conn_lcs = ora_logon("LABOR@$lcs", "labor");
   if($conn_lcs < 1){

      printf("Error logging on to the LCS Oracle Server: ");
      printf(ora_errorcode($conn_lcs));
      printf("Please try later!");
      exit;
   }


   //Open Cursor
  $cursor = ora_open($conn_lcs);

  $wing_comm = $HTTP_POST_VARS["wing"];

  list($wing,$comm) = split(":",$wing_comm);

  $comments = $HTTP_POST_VARS["comments"];

  $comments = OraSafeString($comments);

   
 // Update to Warehouse_status table

    $sql = "update warehouse_status set comments = '$comments' where warehouse_box = '$wing'";
    $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_lcs);
        ora_close($cursor);
        exit;
     }
     ora_commit($conn_lcs);

   // Insert into Warehouse_Status_Log table
     $sql = "insert into warehouse_status_log(warehouse_box,comments,user_name,update_time) 
             values('$wing','$comments','$user',sysdate)";
     $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_lcs);
        ora_close($cursor);
        exit;
     }
     ora_commit($conn_lcs); 

  
ora_close($cursor);
ora_logoff($conn_lcs);

 
header("Location:box_comments.php");

?>
