<?
   include("pow_session.php");

   // The form processor
   $mark_free = trim($HTTP_POST_VARS["mark_free"]);
   $mark_select = trim($HTTP_POST_VARS["mark_select"]);

   if ($mark_select != "") {	// user select a mark
      setcookie("mark", $mark_select, time() + 28800);
   } else {
      // make database connection
      include("connect.php");
      $conn = pg_connect ("host=$host dbname=$db user=$dbuser");

      if (!$conn) {
         die("Could not open connection to database server");
      }

      $stmt = "select * from ccd_received where mark like '%$mark_free%' and verified = true";
      $result = pg_query($conn, $stmt) or 
                die("Error in query: $stmt. " .  pg_last_error($conn));
      $rows = pg_num_rows($result);

      if ($rows <= 0) {	// not ccd_lot_id is found
         die("Cannot find an verified lot with mark similar to : '$mark_free'. Please go back to re-enter 
	      or select a mark.");
      } else {
	 $mark = $mark_free;
   	 setcookie("mark", $mark_free, time() + 28800);
      }

      // release database resource
      pg_free_result($result);
      pg_close($conn);
   }

   header("Location: mark_lookup.php");
?>
