<?
  include("pow_session.php");
 
  $user = $userdata['username'];

   include("connect.php");


  // Connect to Rf Database
   $conn_rf = ora_logon("SAG_OWNER@$rf", "OWNER");
   if($conn_rf < 1){

      printf("Error logging on to the RF Oracle Server: ");
      printf(ora_errorcode($conn_rf));
      printf("Please try later!");
      exit;
   }

   //Open Cursor
   $cursor = ora_open($conn_rf);
 
   // Get all of the forms changes
  $lot_id = $HTTP_POST_VARS["lot_id"];
  $arrival = $HTTP_POST_VARS["arrival"];
  $po_num = $HTTP_POST_VARS["po_num"];
  $mark = $HTTP_POST_VARS["mark"];
  $receiver = $HTTP_POST_VARS["receiver"];
  $ccode = $HTTP_POST_VARS["ccode"];
  $color = $HTTP_POST_VARS["color"];
  $loc = $HTTP_POST_VARS["loc"];
  $hatch = $HTTP_POST_VARS["hatch"];
  $qty_expect = $HTTP_POST_VARS["qty_expect"];
  $qty_receive = $HTTP_POST_VARS["qty_receive"];
  $qty_house = $HTTP_POST_VARS["qty_house"];
  $pallet_expect = $HTTP_POST_VARS["pallet_expect"];
  $pallet_receive = $HTTP_POST_VARS["pallet_receive"];
  $pallet_house = $HTTP_POST_VARS["pallet_house"];

  
  $input = $HTTP_POST_VARS["input"];

  // Assign Default values

  if ($color == "")
   $color = "GRAY";

  if ($loc == "")
   $loc = "TBA";

  if ($hatch == "")
   $hatch = "N/A";

  if ($qty_expect == "")
   $qty_expect = 0;
  
  if ($qty_receive == "")
   $qty_receive = 0;

  if ($qty_house == "")
   $qty_house = 0;

  if ($pallet_expect == "")
   $pallet_expect = 0;

  if ($pallet_receive == "")
   $pallet_receive = 0;

  if ($pallet_house == "")
   $pallet_house = 0;

  if ($po_num == "")
   $po_num = "";

  if ($input == "add")
  {
    // To add

     // Check the Lot Id existence
     $sql = "select * from ccd_cargo_tracking where lot_id = '$lot_id'
             and arrival_num = '$arrival' and mark = '$mark'";
     $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_close($cursor);
        exit;
     }

     ora_fetch($cursor);
     $rows = ora_numrows($cursor);

     if ($rows == 0) {
       // insert into ccd_cargo_tracking

        $sql = "insert into CCD_CARGO_TRACKING
               (LOT_ID, PO_NUM, ARRIVAL_NUM, MARK, RECEIVER_ID, COMMODITY_CODE, COLOR, CARGO_LOCATION,
                QTY_EXPECTED, QTY_RECEIVED, QTY_IN_HOUSE, PALLET_COUNT_EXPECTED, PALLET_COUNT_RECEIVED,
                PALLET_COUNT_IN_HOUSE, DATE_RECEIVED, MANIFESTED)
                values
                ('$lot_id', '$po_num', '$arrival',  '$mark', $receiver, $ccode, '$color',
                '$loc', $qty_expect, $qty_receive, $qty_house, $pallet_expect,
                 $pallet_receive, $pallet_house, sysdate, 'N')";
         $stmt = ora_parse($cursor,$sql);

         if(!ora_exec($cursor))
         {
           printf("Error in query:$sql");
           ora_rollback($conn_rf);
           ora_close($cursor);
           exit;
         }
         ora_commit($conn_rf);



      // Insert into ccd_cargo_hatch 

       $sql = "insert into CCD_CARGO_HATCH
                (LOT_ID, ARRIVAL_NUM, MARK, HATCH, QTY_EXPECTED, PALLET_COUNT_EXPECTED)
                values
                ('$lot_id', '$arrival', '$mark', '$hatch', $qty_expect, $pallet_expect)";

       $stmt = ora_parse($cursor,$sql);

         if(!ora_exec($cursor))
         {
           printf("Error in query:$sql");
           ora_rollback($conn_rf);
           ora_close($cursor);
           exit;
         }
         ora_commit($conn_rf);

     $msg = "Successfully added a lot!";
     header("Location: index.php?input=$msg");
     }
     else
     {
       // update into ccd_cargo_tracking

       $sql = "update CCD_CARGO_TRACKING
               set QTY_EXPECTED = QTY_EXPECTED + $qty_expect,
               QTY_RECEIVED = QTY_RECEIVED + $qty_receive,
               QTY_IN_HOUSE = QTY_IN_HOUSE + $qty_house,
               PALLET_COUNT_EXPECTED = PALLET_COUNT_EXPECTED + $pallet_expect,
               PALLET_COUNT_RECEIVED = PALLET_COUNT_RECEIVED + $pallet_receive,
               PALLET_COUNT_IN_HOUSE = PALLET_COUNT_IN_HOUSE + $pallet_house
               where LOT_ID = '$lot_id' AND ARRIVAL_NUM = '$arrival' AND MARK = '$mark'";

        $stmt = ora_parse($cursor,$sql);

        if(!ora_exec($cursor))
        {
          printf("Error in query:$sql");
          ora_rollback($conn_rf);
          ora_close($cursor);
          exit;
        }
        ora_commit($conn_rf);


      // update into ccd_cargo_hatch

       $sql = "update CCD_CARGO_HATCH
               set QTY_EXPECTED = QTY_EXPECTED + $qty_expect,
               PALLET_COUNT_EXPECTED = PALLET_COUNT_EXPECTED + $pallet_expect
               where LOT_ID = '$lot_id' and ARRIVAL_NUM = '$arrival'
               and MARK = '$mark' and HATCH = '$hatch'";
       $stmt = ora_parse($cursor,$sql);

        if(!ora_exec($cursor))
        {
          printf("Error in query:$sql");
          ora_rollback($conn_rf);
          ora_close($cursor);
          exit;
        }
        ora_commit($conn_rf);

     $msg = "Successfully updated a lot!";
     header("Location: index.php?input=$msg");
     }
 }
else
{
//To edit

 // update into ccd_cargo_tracking

       $sql = "update CCD_CARGO_TRACKING
               set PO_NUM = '$po_num',RECEIVER_ID = $receiver,COMMODITY_CODE = $ccode,
               COLOR = '$color',CARGO_LOCATION = '$loc',
               QTY_EXPECTED = $qty_expect,QTY_RECEIVED = $qty_receive,QTY_IN_HOUSE = $qty_house,
               PALLET_COUNT_EXPECTED = $pallet_expect,PALLET_COUNT_RECEIVED = $pallet_receive,
               PALLET_COUNT_IN_HOUSE = $pallet_house
               where LOT_ID = '$lot_id' AND ARRIVAL_NUM = '$arrival' AND MARK = '$mark'";

        $stmt = ora_parse($cursor,$sql);

        if(!ora_exec($cursor))
        {
          printf("Error in query:$sql");
          ora_rollback($conn_rf);
          ora_close($cursor);
          exit;
        }
        ora_commit($conn_rf);


       // update into ccd_cargo_hatch

       $sql = "update CCD_CARGO_HATCH
               set QTY_EXPECTED = $qty_expect,
               PALLET_COUNT_EXPECTED = $pallet_expect
               where LOT_ID = '$lot_id' and ARRIVAL_NUM = '$arrival'
               and MARK = '$mark' and HATCH = '$hatch'";
       $stmt = ora_parse($cursor,$sql);

        if(!ora_exec($cursor))
        {
          printf("Error in query:$sql");
          ora_rollback($conn_rf);
          ora_close($cursor);
          exit;
        }
        ora_commit($conn_rf);

     $msg = "Successfully modified a lot!";
     header("Location: index.php?input=$msg");
} 
?>

