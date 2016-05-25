<?
  // All POW files need this session file included
  include("pow_session.php");
  $user =  $userdata['username'];

  // Define some vars for the skeleton page
  $title = "Temperature Change Request";
  $area_type = "SUPV";

  $rId = $HTTP_POST_VARS[rId];
  $set_point = $HTTP_POST_VARS[set_point];

  if ($HTTP_POST_VARS['cancel']<>""){
        header("location: current.php");
        exit;
  }


  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@LCS", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $sql = "update temperature_req set actual_set_point = '$set_point' where req_id = $rId";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  $sql = "insert into set_temp_log (req_id, set_point, set_time, user_name) values 
	  ($rId, '$set_point', sysdate, '$user')";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  ora_close($cursor);
  ora_logoff($conn);

  header("location: current.php");
  exit;

?>
