<?
  // Start a POW session
  include("pow_session.php");
  $username = $userdata['username'];

  $timestamp = date('Y-m-d');

  include("connect.php");
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }

  // Retrieve the POST Vars
  $incident_date = date('Y-m-d', strtotime($HTTP_POST_VARS["incident_date"]));
  $location = $HTTP_POST_VARS["location"];
  $classification = $HTTP_POST_VARS["classification"];
  $medical_code = $HTTP_POST_VARS["medical_code"];
  $supervisor = $HTTP_POST_VARS["supervisor"];
  $employee_classification = $HTTP_POST_VARS["employee_classification"];
  $notes = $HTTP_POST_VARS["notes"];

  // Get the next incident ID
  $stmt = "select nextval('saftey_id')";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
  $row = pg_fetch_row($result, 0);
  $incident_id = $row[0];

  $stmt = "insert into saftey_stats (incident_id, incident_date, location, classification, medical_code, supervisor, employee_classification, entered_date, notes) values ('$incident_id', '$incident_date', '$location', '$classification', '$medical_code', '$supervisor', '$employee_classification', '$timestamp', '$notes')";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
  header("Location: index.php?input=1");
  exit;
?>
