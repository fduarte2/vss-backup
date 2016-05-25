<?
   include("pow_session.php");
   $user = $userdata['username'];

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $today = date('F j, Y');
   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);

   // Sql Statement Per Inigo
   $sql = "SELECT WAREHOUSE_LOCATION, COMMODITY_NAME, QTY_UNIT, SUM(QTY_IN_HOUSE) 
	   FROM CARGO_TRACKING A, CARGO_MANIFEST C, COMMODITY_PROFILE B 
	   WHERE QTY_IN_HOUSE > 0 AND A.COMMODITY_CODE = B.COMMODITY_CODE AND A.LOT_NUM = C.CONTAINER_NUM 
	   GROUP BY WAREHOUSE_LOCATION, COMMODITY_NAME, QTY_UNIT
	   ORDER BY WAREHOUSE_LOCATION, COMMODITY_NAME, QTY_UNIT";

   // Run the sql
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>DSPC Inventory Summary By Location / Commodity</b>", 14, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>As of $today</b>", 12, $center);
   $pdf->ezSetDy(-15);

   // Append BNI Data to the array - All Commodities except for Meat
   $data = array();
   $loc_unit = array();		// to store running total of quantity for each Location/Unit pair
   while (ora_fetch($cursor)){
      $location = strtoupper(trim(ora_getcolumn($cursor, 0)));
      $commodity = trim(ora_getcolumn($cursor, 1));
      $quantity = ora_getcolumn($cursor, 3);
      $unit = strtoupper(trim(ora_getcolumn($cursor, 2)));

      if ($location == "")
	 $location = "UNKNOWN";

      // added to $data for printing
      array_push($data, array('Location'=>$location, 'Commodity'=>$commodity, 'Quantity'=>$quantity, 
			      'Unit'=>$unit));

      // check if the location/unit pair is already in $loc_unit
      $is_new_pair = TRUE;
      for ($i=0; $i<count($loc_unit); $i++) {
	 // if there is a match, update the running total for quantity
	 if ($location == $loc_unit[$i]['Location'] && $unit == $loc_unit[$i]['Unit']) {
	    $is_new_pair = FALSE;
	    $loc_unit[$i]['Quantity'] +=  $quantity;
	    break;		// break the for loop
         }
      }

      // if it is a new location/unit pair, add a row (an array) to $loc_unit
      if ($is_new_pair)
         array_push($loc_unit, array('Location'=>$location, 'Quantity'=>$quantity, 'Unit'=>$unit));
   }

/*   // Done with other commodities - time for Meat
   $sql6 = "select location, longproductname, commodity, sum(palnum) 
	    from isd_inventory A, isdproduct B where A.xprod = B.product and A.palnum is not null 
	    group by location, longproductname, commodity order by location";
   $statement = ora_parse($cursor, $sql6);
   ora_exec($cursor);

   // Here is the 'Meat Loop'
   $unit = "PLT";
   $temp = array();		// to store running total of quantity for each location/commodity pair
   while (ora_fetch($cursor)){
      $location = trim(ora_getcolumn($cursor, 0));
      $prod = trim(ora_getcolumn($cursor, 1));
      $comm = trim(ora_getcolumn($cursor, 2));
      $quantity = ora_getcolumn($cursor, 3);
      $commodity = $comm . "-" . $prod;

      // clean up locations to WHING A, B, C, D, E, F, G, or UNKNOWN
      $first_two = strtoupper(substr(trim($location), 0, 2));
      $first = strtoupper(substr(trim($location), 0, 1));

      if ($first_two == 'BX') {	// assume it is Warehouse D
         $location = 'WING D';
      } else {
         switch ($first) {
	    case 'A':
	       $location = 'WING A';
	       break;
	    case 'B':
	       $location = 'WING B';
	       break;
	    case 'C':
	       $location = 'WING C';
	       break;
	    case 'D':
	       $location = 'WING D';
	       break;
	    case 'E':
	       $location = 'WING E';
	       break;
	    case 'F':
	       $location = 'WING F';
	       break;
	    case 'G':
	       $location = 'WING G';
	       break;
	    default:
	       $location = 'UNKNOWN';
	       break;
         }
      }

      // check if the location/commodity pair is already in $temp
      $is_new_pair = TRUE;
      for ($i=0; $i<count($temp); $i++) {
	 // if there is a match, update the running total for quantity
	 if ($location == $temp[$i]['Location'] && $commodity == $temp[$i]['Commodity']) {
	    $is_new_pair = FALSE;
	    $temp[$i]['Quantity'] +=  $quantity;
	    break;	// break the for loop
         }
      }

      // if it is a new location/commodity pair, add a row (an array) to $temp
      if ($is_new_pair)
         array_push($temp, array('Location'=>$location, 'Commodity'=>$commodity, 
		    		 'Quantity'=>$quantity, 'Unit'=>$unit));

      // check if the location/unit pair is already in $loc_unit
      $is_new_pair = TRUE;
      for ($i=0; $i<count($loc_unit); $i++) {
	 // if there is a match, update the running total for quantity
	 if ($location == $loc_unit[$i]['Location'] && $unit == $loc_unit[$i]['Unit']) {
	    $is_new_pair = FALSE;
	    $loc_unit[$i]['Quantity'] +=  $quantity;
	    break;	// break the for loop
         }
      }

      // if it is a new location/commodity pair, add a row (an array) to $loc_unit
      if ($is_new_pair)
         array_push($loc_unit, array('Location'=>$location, 'Quantity'=>$quantity, 'Unit'=>$unit));

   }
   
   // append rows (arrays) of $temp to the end of $data for printing
   foreach ($temp as $value) {
      array_push($data, $value);
   }
 
   ora_close($cursor);
*/

   // Data array created
   $pdf->ezTable($data, array('Location'=>'Cargo Location', 'Commodity'=>'Commodity', 'Quantity'=>'Quantity', 'Unit'=>'Unit'), '', array('width'=>500, 'shaded'=>1));

   // generate the table of inventory information by warehouse
   $pdf->ezSetDy(-35);
   $pdf->ezText("<b>DSPC Inventory Summary By Location</b>", 14, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>As of $today</b>", 12, $center);
   $pdf->ezSetDy(-15);

   $data = array();

   // append rows (arrays) of $temp to the end of $data for printing
   foreach ($loc_unit as $value) {
      array_push($data, $value);
   }

   // Data array created
   $pdf->ezTable($data, array('Location'=>'Cargo Location', 'Quantity'=>'Quantity', 'Unit'=>'Unit'), 
		 '', array('width'=>500, 'shaded'=>1));

   $today = date('m/j/y');
   $format = "Port of Wilmington, " . $today . " Printed by " . $user;
   $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->line(20,822,578,822);
   $pdf->addText(50,34,6, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   $pdf->ezStream();
?>
