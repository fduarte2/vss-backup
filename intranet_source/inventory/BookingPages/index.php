<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
?>


<h1>Booking Paper Inventory</h1>

<ul>
	<li><a href="transfer_to_port_booking.php">Transfer to Port Account</a>
	<li><a href="sell_book_to_newcust.php">BK Customer-to-Customer Transfer</a>
	<li><a href="booking_code_alter.php">Booking Add/Edit Grade Code</a>
	<li><a href="BookingBooking.php">Booking# Assignment</a>
	<li><a href="delete_booking.php">Delete Unreceived Rolls</a>
	<li><a href="BookingBookingFixit.php">Booking# Individual Vessel Fix/Modification</a>
	<li><a href="BarnettManualUpload/BookingManualUpload.php">Manual Roll Upload</a>
	<li><a href="BookingContainerChange.php">Change Container#</a>
	<li><a href="BookingARVLookup.php">Arrival # Lookup</a>
	<li><a href="../paper_orders_active.php">Paper Orders Open/Completed Today</a>
	<li><a href="BK_list_edit.php">Pre-Received Paper Alterations</a>
	<li><a href="BookingEDILookup.php">EDI Lookup</a>
	<li><a href="BookingGradeCodeEntry.php">EDI Grade Code Conversion Entry</a>
	<li><a href="dole_book_in.php">Inbound Report</a>
	<li><a href="dole_book_out.php">Outbound Report</a>
	<li><a href="adjust.php">Roll Correction</a>
</ul>


<? include("pow_footer.php"); ?>
