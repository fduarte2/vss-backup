<?
   include("pow_session.php");
   // The form processor
   $lr_num = $HTTP_POST_VARS["lr_num"];
   $lot_id = $HTTP_POST_VARS["lot_id"];
   $lot_free = $HTTP_POST_VARS["lot_free"];
   $mark_free = $HTTP_POST_VARS["mark_free"];
   $ccd_lot_id = $HTTP_POST_VARS["ccd_lot_id"];
   $ccd_lot_free = $HTTP_POST_VARS["ccd_lot_free"];
   $previous_page = $HTTP_POST_VARS["page_filename"];

   if ($lr_num == "") {
      $lr_num = $HTTP_COOKIE_VARS["lrnum"];
   }

   // make database connection
   include("connect.php");
   $conn = pg_connect ("host=$host dbname=$db user=$dbuser");

   if (!$conn) {
      $error_msg = "Could not open connection to database server";
      header("Location: error_page.php?error_msg=$error_msg");
      exit;
   }

   if ($ccd_lot_id == "" && $ccd_lot_free == "Tracking Number") {	// user select lot from manifest

      if($lot_id == ""){
         $lot_id = trim($lot_free);
         $mark = trim($mark_free);
      }else{
         list($slot_id, $mark) = split("[, ]", $lot_id, 2);
         $lot_id = trim($slot_id);
         $mark = trim($mark);
      }

      if ($lot_id == "" || $lot_id == "Lot") {
         $stmt = "select ccd_lot_id from ccd_received where lr_num = '$lr_num' and mark = '$mark' and 
		  verified = true";
      } else {
         $stmt = "select ccd_lot_id from ccd_received where lr_num = '$lr_num' and lot_id = '$lot_id'
	          and mark = '$mark' and verified = true";
      }

      $result = pg_query($conn, $stmt);
      $rows = pg_num_rows($result);

      if ($rows < 0) {		// database error
         $error_msg = "Error in database query: $stmt." . pg_last_error($conn) . 
		      " Please report to Technology Solutions Department!";
         header("Location: error_page.php?error_msg=$error_msg");
	 exit;
      } elseif ($rows == 0) {	// no info is found
	 $error_msg = "Cargo with vessel No: $lr_num, lot_id: $lot_id and mark: $mark is not ready for " . 
		      "Expediting. Either it does not exist, or it is not verified inspection production " . 
		      "or it is not released non-inspection product. Please go back to Receiving to check, " . 
		      "and verify or release it.";
         header("Location: error_page.php?error_msg=$error_msg");
	 exit;
      } else {
         $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
         $ccd_lot_id = $row['ccd_lot_id'];
      }

   } else {			// user select lot using ccd_lot_id

      if ($ccd_lot_id != "") {
	 $stmt = "select lr_num from ccd_received where ccd_lot_id = '$ccd_lot_id'";
	 $result = pg_query($conn, $stmt);
         $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	 $lr_num =  $row['lr_num'];
      } else {
	 $stmt = "select lr_num from ccd_received where ccd_lot_id = '$ccd_lot_free' and verified = true";
	 $result = pg_query($conn, $stmt);
	 $rows = pg_num_rows($result);

         if ($rows < 0) {		// database error
            $error_msg = "Error in database query: $stmt." . pg_last_error($conn) . 
		         " Please report to Technology Solutions Department!";
    	    header("Location: error_page.php?error_msg=$error_msg");
	    exit;
	 } elseif ($rows == 0) {	// no info is found for the entered ccd_lot_id
	    $error_msg = "Cargo with tracking No. $ccd_lot_free is not ready for expediting. " .
			 "Either it does not exist, or it is not verified inspection production " . 
		      	 "or it is not released non-inspection product. Please go back to Receiving " . 
			 "to check, and verify or release it.";
    	    header("Location: error_page.php?error_msg=$error_msg");
	    exit;
         } else {
            $ccd_lot_id = $ccd_lot_free;
            $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	    $lr_num =  $row['lr_num'];
         }
      }
   }      

   // release database resource
   if ($result) {
      pg_free_result($result);
   }
   pg_close($conn);

   // set the cookie for ccd lot id and lr_num
   setcookie("ccd_lot_id", $ccd_lot_id, time() + 28800);
   setcookie("lrnum", $lr_num, time() + 28800);

   // delete the cookies for order_num
   setcookie("order_num", "");

   header("Location: $previous_page");
?>
