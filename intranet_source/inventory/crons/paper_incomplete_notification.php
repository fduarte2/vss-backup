<?
/*
*	Adam Walter, Jul 2010
*
*	This sends an email (to be run daily, early-morning) that reports to
*	Finance-based individuals any BNI cargo that are awaiting billing
*	Should be cronned AFTER the nightly storage run.
*****************************************************************************/

  $bni_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $bni_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }

  $cursor = ora_open($bni_conn);
  $Short_Term_Cursor = ora_open($bni_conn);

  $date = date('m/d/Y');


	$mailTO = "Hector.Garcia@Dole.com\r\n";
//	$mailTO = "awalter@port.state.de.us\r\n";
	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "ltreut@port.state.de.us,scorbin@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	

	

	// PART ONE:  BOOKING
	$sql = "SELECT ORDER_NUM, NVL(CONTAINER_ID, 'NONE') THE_CONT, BO.STATUS || '-' || DS.ST_DESCRIPTION THE_STATUS
			FROM BOOKING_ORDERS BO, DOLEPAPER_STATUSES DS
			WHERE BO.STATUS IN ('2', '3', '4', '5')
			AND BO.STATUS = DS.STATUS
			AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$date."'
			ORDER BY ORDER_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$body = "";
		$total_orders = 0;

		do {
			$body .= "Order - ".$row['ORDER_NUM']."      Container - ".$row['THE_CONT']."      Current Status - ".$row['THE_STATUS']."\r\n";
			$total_orders++;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$mailsubject = "Booking Paper Orders Incomplete:  ".$total_orders;

		mail($mailTO, $mailsubject, $body, $mailheaders);
	}



	// PART TWO:  Docktickets
	$sql = "SELECT ORDER_NUM, NVL(CONTAINER_ID, 'NONE') THE_CONT, DO.STATUS || '-' || DS.ST_DESCRIPTION THE_STATUS
			FROM DOLEPAPER_ORDER DO, DOLEPAPER_STATUSES DS
			WHERE DO.STATUS IN ('2', '3', '4', '5')
			AND DO.STATUS = DS.STATUS
			AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$date."'
			ORDER BY ORDER_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$body = "";
		$total_orders = 0;

		do {
			$body .= "Order - ".$row['ORDER_NUM']."      Container - ".$row['THE_CONT']."      Current Status - ".$row['THE_STATUS']."\r\n";
			$total_orders++;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$mailsubject = "DockTicket Paper Orders Incomplete:  ".$total_orders;

		mail($mailTO, $mailsubject, $body, $mailheaders);
	}
