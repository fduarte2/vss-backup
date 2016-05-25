 <?
 $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("<br />Please try later!</body></html>");
    exit;
 }
 $cursor = ora_open($conn);

 // drop table
 $sql2 = "drop table isd_inventory";
 $statement = ora_parse($cursor, $sql2);
 ora_exec($cursor);

 // Create temp table
 $sql3 = "create table isd_inventory as (select xlot, xmark, xto, xfrom, xprod, xves, xvoy, location, palnum, xqty, shiptype, xdate, anivdate, xcontain, xbrand, xcut, xestab, xusda, xgwt, xnwt, xpo, insdate from isdxfile where xsale = 'RECEIVED' and xdate > '01-JAN-01')";
 $statement = ora_parse($cursor, $sql3);
 ora_exec($cursor);

 $test = "select count(*) from isd_inventory";
 $statement = ora_parse($cursor, $test);
 ora_exec($cursor);
 $initial = ora_getcolumn($cursor, 0);
 echo "Started off with $initial elements\n";

 // Grab all sent items
 $sql4 = "select xlot, xmark from isdxfile where xsale = 'SHIPPED' or xsale = 'T-SHIPPED' and xdate > '01-JAN-01'";
 $statement = ora_parse($cursor, $sql4);
 ora_exec($cursor);

 $i = 0;
 while(ora_fetch($cursor)){
   $lot_array[$i] = ora_getcolumn($cursor, 0);
   $mark_array[$i] = ora_getcolumn($cursor, 1);
   $i++;
 }

 for($x = 0; $x < $i; $x++){
   $sql5 = "delete from isd_inventory where xlot = '" . $lot_array[$x] . "' and xmark = '" . $mark_array[$x] . "'";
//   echo "Here: $sql5\n";
   $statement = ora_parse($cursor, $sql5);
   ora_exec($cursor);
 }
 $test = "select count(*) from isd_inventory";
 $statement = ora_parse($cursor, $test);
 ora_exec($cursor);
 $final = ora_getcolumn($cursor, 0);
 echo "Ended up with $final elements\n";

 ora_close($cursor);
?>
