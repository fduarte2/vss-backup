<?
/*
*     This page is identical to the Inventory Tag Audit at /var/www/html/inventory/fruit/reporting/tag_audit/tag_audit_print.php
*      except that this page does not allow partial pallet IDs
*
*
******************************************************************/
   include("pow_session.php");
   // File: track_report_print.php
   //
   // This page generate a PDF file of the tracker report

   // form processor
   if(!$pallet_id){
     printf("Missing Pallet ID- please go back and try again (Should not get here).");
     exit;
   }
   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);
   $loop_cursor = ora_open($ora_conn);
    
   include("common_tag_audit_print.php");
?>
