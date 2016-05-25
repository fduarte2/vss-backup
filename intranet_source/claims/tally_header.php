<?
   // Print Receiving Header
   if ($_SERVER['PHP_SELF'] == '/ccds/expediting/shipping_tally_print.php') {
      $pdf->ezText("<b>Shipping   Tally</b>", 18, $center);
   } elseif ($_SERVER['PHP_SELF'] == '/ccds/expediting/bol_print.php') {
      $pdf->ezText("<b>Bill  Of  Lading</b>", 18, $center);
   } elseif ($_SERVER['PHP_SELF'] == '/ccds/expediting/order_confirm_print.php') {
      $pdf->ezText("<b>Confirmation  Of  Transfer</b>", 18, $center);
   } elseif ($_SERVER['PHP_SELF'] == '/ccds/expediting/track_report_print.php') {
      $pdf->ezText("<b>Tracker   Report</b>", 18, $center);
   }

   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>Cold Chain Distribution Services</b>", 12, $left);
   $pdf->ezText("1 Hausel Road                                                                                                      Phone (302) 472-5631", 12, $left);
   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                           Fax (302) 472-5635", 12, $left);
?>
