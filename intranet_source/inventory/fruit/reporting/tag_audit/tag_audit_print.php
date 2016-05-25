<?
/*
*     This page is identical to the Finance Tag Audit at /var/www/html/finance/rf/reporting/tag_audit_print.php
*      except that this page allows partial pallet IDs (checks for like values)
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
   
   // Check to see if we have any like values
   $stmt = "select count(*) count from cargo_tracking where pallet_id like '%$pallet_id%'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $count = $count['COUNT'];
   if($count <= 0){
     printf("Invalid Pallet ID- please try again!");
     exit;
   }
   else if($count > 1){
     header("Location: tag_audit.php?pallet_id=$pallet_id");
     exit;
   }

   include("/var/www/html/finance/rf/reporting/common_tag_audit_print.php");
?>
