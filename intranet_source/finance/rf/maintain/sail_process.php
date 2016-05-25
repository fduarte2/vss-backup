<?
  include("pow_session.php");
  // Seth Morecraft  11-NOV-03
  // Code to Actually create an entry in crontab

  $user = $userdata['username'];

  // Get all of the forms changes
  $free_date = $HTTP_POST_VARS["free_date"];
  $vessel = $HTTP_POST_VARS["vessel"];

  if($free_date == ""){
    header("Location: sail.php?success=2");
    exit;
  }

  // connect
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $cursor = ora_open($conn);

  // Get the number of Free Days
  $ora_sql = "select distinct shipping_line as shipping_line from cargo_tracking where arrival_num = '$vessel' and shipping_line is not null";
  $statement = ora_parse($cursor, $ora_sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $shipping_line = $row['SHIPPING_LINE'];
  if($shipping_line == ""){
    header("Location: sail.php?reason=ShippingLine&success=2");
    exit;
  }
  //echo "Got $shipping_line as Shipping Line<br />";

  $ora_sql = "select freetime from rf_storage_rate where shipping_line = '$shipping_line'";
  $statement = ora_parse($cursor, $ora_sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $free_days = $row['FREETIME'];
  if($free_days == ""){
    header("Location: sail.php?reason=FreeTime&success=2");
    exit;
  }
  //echo "Got $free_days as Free Days<br />";
  
  // Add the date
  $storage_start = date('m/d/Y', mktime(0,0,0, date("m", strtotime($free_date)), (date("d", strtotime($free_date)) + $free_days), date("y", strtotime($free_date))));
  //echo "Converted $free_date into $storage_start<br />";

  // Vessel Profile
  $ora_sql = "update vessel_profile set free_time_end = to_date('$storage_start', 'MM/DD/YYYY'), free_time_set = '$user' where lr_num = '$vessel'";
  //echo "$ora_sql<br />";
  $statement = ora_parse($cursor, $ora_sql);
  ora_exec($cursor);
  
  // Cargo Tracking
  $ora_sql = "update cargo_tracking set free_time_end = to_date('$storage_start', 'MM/DD/YYYY'), billing_storage_date = to_date('$storage_start', 'MM/DD/YYYY') where arrival_num = '$vessel'";
  $statement = ora_parse($cursor, $ora_sql);
  ora_exec($cursor);
  //echo "$ora_sql<br />";

  // SPECIAL EXCEPTION
  // pallets in A2 from customer 1608 get a special billing time... based off of Warehouse Location, which is a variable
  // the existing RF_STORAGE_RATE table does not carry.  the plan is to chagne all RF billing to contract-driven later,
  // till then, this exception goes here.
  $ora_sql = "update cargo_tracking set free_time_end = to_date('$free_date', 'MM/DD/YYYY'), billing_storage_date = to_date('$free_date', 'MM/DD/YYYY') where arrival_num = '$vessel' and commodity_code like '8%' and warehouse_location = 'A2'";
  $statement = ora_parse($cursor, $ora_sql);
  ora_exec($cursor);


  ora_close($cursor);
  header("Location: sail.php?success=1");
  exit;
?>
