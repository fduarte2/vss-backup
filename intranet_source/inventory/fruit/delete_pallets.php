<?
   include("connect.php");

   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);

   // Check to see if we have any like values
   $stmt = "select count(*) count from cargo_tracking where warehouse_location = 'Delete'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $count = $count['COUNT'];

   if($count > 0){
     $stmt = "delete from cargo_tracking where warehouse_location = 'Delete'";
     $ora_success = ora_parse($cursor, $stmt);
     $ora_success = ora_exec($cursor);
     ora_fetch_into($cursor, $count, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
     printf("Deleted $count pallets\n");
   }
   exit;
?>
