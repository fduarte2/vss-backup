<?
   // File: set_bill.php
   //
   // This page set cookie for selected billing number.
   include("pow_session.php");

   $bill_free = trim($HTTP_POST_VARS["bill_free"]);
   $bill_select = $HTTP_POST_VARS["bill_select"];
   $previous_page = $HTTP_POST_VARS["page_filename"];
   $page = $HTTP_POST_VARS["page"];

   if ($bill_select != "") {
      $billing_num = $bill_select;
   } else {
      // make database connection
      include("connect.php");

      $conn = ora_logon("SAG_OWNER@$bni", "SAG");
      if (!$conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
      }

      $cursor = ora_open($conn);
      
      // generate and execute a query
      if ($page == "un-delete") {
	 $stmt = "select * from billing where billing_num = $bill_free and service_status = 'DELETED'";
      } else {
	 $stmt = "select * from billing where billing_num = $bill_free and service_status = 'PREINVOICE'";
      }

      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);
      
      ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor);

      if ($rows <= 0) {	// not prebill with this billing # is found
	 if ($page == "un-delete") {
            die("Cannot find a deleted prebill in the system that has billing number: $bill_free.
	         \nPlease go back to re-enter or select the billing number.");
	 } else {
            die("Cannot find a prebill in the system that has the entered billing number: $bill_free.
	         \nPlease go back to re-enter or select the billing number.");
	 }
      } else {
         $billing_num = $bill_free;
      }

      ora_close($cursor);
      ora_logoff($conn);
   }

   setcookie("billing_num", $billing_num, time() + 7200);
   header("Location: $previous_page");
?>
