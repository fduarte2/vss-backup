<?
  include("pow_session.php");
  include("connect.php"); 

  // Finish off a claim
  $claim_id = $HTTP_POST_VARS['claim_id'];
  $internal_notes = $HTTP_POST_VARS['internal_notes'];
  $internal_notes = addslashes($internal_notes);
  // open a connection to the database server
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn) {
     die("Could not open connection to database server");
  }
  $stmt = "update claim_log set internal_notes = '$internal_notes' where claim_id = '$claim_id'";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  header("Location: final_notes.php?input=$claim_id");
  exit;
?>
