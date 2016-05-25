<?
   // File: set_invoice.php
   //
   // This page set cookie for selected invoice number.
   include("pow_session.php");

   $invoice_free = trim($HTTP_POST_VARS["invoice_free"]);
   $invoice_select = $HTTP_POST_VARS["invoice_select"];

   if ($invoice_select != "") {
      $invoice_num = $invoice_select;
   } else {
      // check whether it is a valid invoice # for a invoice that is not imported to Oracle Financial
      include("connect.php");

      $conn = ora_logon("SAG_OWNER@$bni", "SAG");
      if (!$conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
      }
      $cursor = ora_open($conn);
      
      $stmt = "select * from billing where invoice_num = $invoice_free and service_status = 'INVOICED' and tosolomon is null";

      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);
      
      ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor);

      if ($rows <= 0) {	// not invoice with this billing # is found
	die("Invoice # $invoice_free either does not exist or it is imported into Oracle Financial.
	     \nPlease go back to re-enter or select an invoice number.");
      } else {
         $invoice_num = $invoice_free;
      }

      ora_close($cursor);
      ora_logoff($conn);
   }

   setcookie("invoice_num", $invoice_num, time() + 7200);
   header("Location: update_invoice.php");
?>
