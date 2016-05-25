<?
/* Adam Walter, October 2006
*  This massively huge file generates a whole bunch of numbers for marketing
*  Based on a variety of sources, and sticks them all in a nice PDF package
*  For easy viewing.  Unless, of course, you cannot view PDFs, at which point..
*  What are you doing at the port?
*  This is quite possibly the largest, most in-depth set of cross-department
*  calculations I have ever had to deal with.  Sorry for the
*  EXCURTIATINGLY LONG CODE, to anyone who has to edit this.
*******************************************************************************/
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $cursor1 = ora_open($conn);
  $cursor2 = ora_open($conn);
  $cursor3 = ora_open($conn);

/*  $RFconn = ora_logon("SAG_OWNER@RF", "OWNER");
  if(!$RFconn){
    echo "Error logging on to the RF Oracle Server: ".ora_errorcode($conn);
    exit;
  }
  $RFcursor = ora_open($RFconn); */

  $customer_id = $HTTP_GET_VARS['customer_id'];

  $sql = "SELECT * FROM MARKETING_CUSTOMER_PROFILE WHERE MARKETING_ID = '".$customer_id."'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $customer_name = $row['CUSTOMER_NAME'];
//  $corporate_name = $row['CORPORATE_NAME'];
  $address_one = $row['ADDRESS_ONE'];
  $address_two = $row['ADDRESS_TWO'];
  $city = $row['CITY'];
  $state = $row['STATE'];
  $zip = $row['ZIP'];
  $contact_one = $row['CONTACT_ONE'];
  $contact_two = $row['CONTACT_TWO'];
  $contact_three = $row['CONTACT_THREE'];
  $contact_four = $row['CONTACT_FOUR'];
  $contract_eff = $row['CONTRACT_EFF'];
  $termination = $row['TERMINATION'];
  $notice_period = $row['NOTICE_PERIOD'];
  $date_relation_started = $row['DATE_RELATION_STARTED'];
  $contractual_rates = $row['CONTRACTUAL_RATES'];
  $terminal_agreement_extract = $row['TERMINAL_AGREEMENT_EXTRACT'];
  $terminal_agreement = $row['TERMINAL_AGREEMENT'];
  $commodity = $row['COMMODITY'];
  $special_one = $row['SPECIAL_ONE'];
  $special_two = $row['SPECIAL_TWO'];
  $special_three = $row['SPECIAL_THREE'];
  $comments = $row['COMMENTS'];
  $country = $row['COUNTRY'];
  $contact_2nd_one = $row['SECONDARY_CONTACT_ONE'];
  $contact_2nd_two = $row['SECONDARY_CONTACT_TWO'];
  $contact_2nd_three = $row['SECONDARY_CONTACT_THREE'];
  $contact_2nd_four = $row['SECONDARY_CONTACT_FOUR'];

  $sql = "SELECT to_char(CONTRACT_EFF, 'MM/DD/YYYY') EFF, to_char(TERMINATION, 'MM/DD/YYYY') TERM, to_char(NOTICE_PERIOD, 'MM/DD/YYYY') NOTICE, to_char(DATE_RELATION_STARTED, 'MM/DD/YYYY') STARTED FROM MARKETING_CUSTOMER_PROFILE WHERE MARKETING_ID = '".$customer_id."'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $contract_eff = $row['EFF'];
  $termination = $row['TERM'];
  $notice_period = $row['NOTICE'];
  $date_relation_started = $row['STARTED'];

  if(date("m") > 7){
	  $this_year = date('y', mktime(0, 0, 0, 1, 2, date("Y") + 1));
	  $last_year = date('y', mktime(0, 0, 0, 1, 2, date("Y")));
	  $two_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 1));
	  $three_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 2));
	  $four_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 3));
  } else {
	  $this_year = date('y', mktime(0, 0, 0, 1, 2, date("Y")));
	  $last_year = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 1));
	  $two_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 2));
	  $three_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 3));
	  $four_years_ago = date('y', mktime(0, 0, 0, 1, 2, date("Y") - 4));
  }
  $right_now = date('m/d/Y h:i a');

	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','portrait');
	$pdf->ezSetMargins(40,40,50,40);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	$pdf->ezText("<i>Confidential</i>", 20, $center);

	$pdf->ezSetDy(-10);
	$pdf->ezText("<b>Customer Information</b>", 20, $center);
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<i>As of $right_now</i>", 10, $center);
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<i>Customer Grouping:  $customer_name</i>", 10, $left);
   	$pdf->ezText("<i>Address (1):  $address_one</i>", 10, $left);
   	$pdf->ezText("<i>Address (2):  $address_two</i>", 10, $left);
   	$pdf->ezText("<i>City, State, Zip:  $city, $state  $zip</i>", 10, $left);
	$pdf->ezText("<i>Country:  $country</i>", 10, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Contact (Name):  $contact_one</i>", 10, $left);
   	$pdf->ezText("<i>Contact (Phone):  $contact_two</i>", 10, $left);
   	$pdf->ezText("<i>Contact (Email):  $contact_three</i>", 10, $left);
   	$pdf->ezText("<i>Contact (Other):  $contact_four</i>", 10, $left);

	$pdf->ezSetDy(-10);

	$pdf->ezText("<i>2nd Contact (Name):  $contact_2nd_one</i>", 10, $left);
   	$pdf->ezText("<i>2nd Contact (Phone):  $contact_2nd_two</i>", 10, $left);
   	$pdf->ezText("<i>2nd Contact (Email):  $contact_2nd_three</i>", 10, $left);
   	$pdf->ezText("<i>2nd Contact (Other):  $contact_2nd_four</i>", 10, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Contract Effective Date:  $contract_eff</i>", 10, $left);
   	$pdf->ezText("<i>Termination Date:  $termination</i>", 10, $left);
   	$pdf->ezText("<i>Notice Period:  $notice_period</i>", 10, $left);
   	$pdf->ezText("<i>Date Commercial Relationship Started:  $date_relation_started</i>", 10, $left);

	$pdf->ezSetDy(-10);

   	$pdf->ezText("<i>Commodity:  $commodity</i>", 10, $left);
   	$pdf->ezText("<i>Remarks (1):  $special_one</i>", 10, $left);
   	$pdf->ezText("<i>Remarks (2):  $special_two</i>", 10, $left);
   	$pdf->ezText("<i>Remarks (3):  $special_three</i>", 10, $left);

	$pdf->ezSetDy(-10);

	$pdf->ezText("<i>Comments:  $comments</i>", 10, $left);

	$pdf->ezSetDy(-20);

	$colHeading = array('one'=>'', 'two'=>"FY ".$this_year, 'three'=>"FY ".$this_year, 'four'=>"FY ".$this_year, 'five'=>"FY ".$last_year, 'six'=>"FY ".$last_year, 'seven'=>"FY ".$last_year, 'eight'=>"FY ".$two_years_ago, 'nine'=>"FY ".$three_years_ago, 'ten'=>"FY ".$four_years_ago);
	$row = array();
//	array_push($row, $colHeading);
/*
	$colOptions = array('one'=>array('width'=>55, 'justification'=>'left'),
						'two'=>array('width'=>55, 'justification'=>'center'),
						'three'=>array('width'=>55, 'justification'=>'center'),
						'four'=>array('width'=>55, 'justification'=>'center'),
						'five'=>array('width'=>55, 'justification'=>'center'),
						'six'=>array('width'=>55, 'justification'=>'center'),
						'seven'=>array('width'=>55, 'justification'=>'center'),
						'eight'=>array('width'=>55, 'justification'=>'center'),
						'nine'=>array('width'=>55, 'justification'=>'center'),
						'ten'=>array('width'=>55, 'justification'=>'center'));
*/
	$colOptions = array('one'=>array('justification'=>'left'),
						'two'=>array('justification'=>'center'),
						'three'=>array('justification'=>'center'),
						'four'=>array('justification'=>'center'),
						'five'=>array('justification'=>'center'),
						'six'=>array('justification'=>'center'),
						'seven'=>array('justification'=>'center'),
						'eight'=>array('justification'=>'center'),
						'nine'=>array('justification'=>'center'),
						'ten'=>array('justification'=>'center'));

	
//	$pdf->ezTable($row, $colHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>550,'fontSize'=>12, 'cols'=>$colOptions));

//  the TITLE OF EACH ROW for the output table
	array_push($row, array('one'=>'', 'two'=>'YTD', 'three'=>'Budget', 'four'=>'Variance', 'five'=>'YTD', 'six'=>'Variance', 'seven'=>'Total', 'eight'=>'Total', 'nine'=>'Total', 'ten'=>'Total'));

	$current_invoice_total = 0;
	$current_payback_total = 0;
	$current_invoice_lasttimeframe = 0;
	$current_payback_lasttimeframe = 0;
	$prior_1_invoice = 0;
	$prior_1_payback = 0;
	$prior_2_invoice = 0;
	$prior_2_payback = 0;
	$prior_3_invoice = 0;
	$prior_3_payback = 0;
	$prior_4_invoice = 0;
	$prior_4_payback = 0;
	// Start_date for the current YTD is July 1st, end date the last day of the month prior to current
	// this ugly if statement is because I have to account for years that may not have yet happened, but
	// the date feature of PHP returns the year based on Jan-DEC, not Jul-Jul
	if(date("m") > 7){
		$start_date = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y")));
		$end_date = date('m/d/Y', mktime(0,0,0, date("m"), 0, date("Y")));
		$last_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 1));
		$last_end = date('m/d/Y', mktime(0,0,0, date("m"), 0, date("Y") - 1));
		$prior_1_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 1));
		$prior_1_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y")));
		$prior_2_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 2));
		$prior_2_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 1));
		$prior_3_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 3));
		$prior_3_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 2));
		$prior_4_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 4));
		$prior_4_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 3));
	} else {
		$start_date = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 1));
		$end_date = date('m/d/Y', mktime(0,0,0, date("m"), 0, date("Y")));
		$last_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 2));
		$last_end = date('m/d/Y', mktime(0,0,0, date("m"), 0, date("Y") - 1));
		$prior_1_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 2));
		$prior_1_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 1));
		$prior_2_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 3));
		$prior_2_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 2));
		$prior_3_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 4));
		$prior_3_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 3));
		$prior_4_start = date('m/d/Y', mktime(0,0,0, 7, 1, date("Y") - 5));
		$prior_4_end = date('m/d/Y', mktime(0,0,0, 6, 30, date("Y") - 4));
	}

	$sql = "SELECT * FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID = '".$customer_id."'";
    ora_parse($cursor1, $sql);
    ora_exec($cursor1);
	while(ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// first 2 statements, current YTD
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_invoice_total += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_payback_total += $row2['GIVEN_BACK'];

		// next 2 statements, Last Year YTD comparison
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_invoice_lasttimeframe += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_payback_lasttimeframe += $row2['GIVEN_BACK'];

		// these 2, prior year 1
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_1_invoice += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_1_payback += $row2['GIVEN_BACK'];

		// these 2, prior year 2
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_2_invoice += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_2_payback += $row2['GIVEN_BACK'];

		// these 2, prior year 3
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_3_invoice += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_3_payback += $row2['GIVEN_BACK'];

		// And, finally, these 2, prior year 4
		$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_4_invoice += $row2['PAID'];

		$sql = "SELECT SUM(SERVICE_AMOUNT) GIVEN_BACK FROM BILLING WHERE CUSTOMER_ID = '".$row1['CUSTOMER_NUMBER']."' AND SERVICE_STATUS = 'CREDITMEMO' AND SERVICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY')";
	    ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$prior_4_payback += $row2['GIVEN_BACK'];

	}

	$current_total = round($current_invoice_total - $current_payback_total, 0);
	$current_lasttimeframe = round($current_invoice_lasttimeframe - $current_payback_lasttimeframe, 0);
	$prior_1_total = round($prior_1_invoice - $prior_1_payback, 0);
	$prior_2_total = round($prior_2_invoice - $prior_2_payback, 0);
	$prior_3_total = round($prior_3_invoice - $prior_3_payback, 0);
	$prior_4_total = round($prior_4_invoice - $prior_4_payback, 0);

//  Grab the supercustomer's budget.
//  NOTE TO ANYONE READING THIS:  It was agreed that, since we don't have "budget" values for customers as fine as
//  Marketings description of a customer, we'd take the budget from the grouping that this customer belongs to,
//  Figure the ratio of how much this customer has of all customers in said group, and multiply that ratio to the budget
	$sql = "SELECT SUM(BUDGET_VALUE) THE_BUDGET FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$end_date."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
//	echo $sql."<BR>";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$supercustomer_budget = $row2['THE_BUDGET'];

//  Now figure the ratio of the current marketing customer to all marketing customers in the same supercustomer group
//  This sql is ugly, since I have to get the supercustomerID from the marketing group, and then get all marketing groups
//  Associated with said supercustomerID, so it's a tri-nested SQL
	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN (SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID IN (SELECT MARKETING_ID FROM MARKETING_CUSTOMER_GROUPS WHERE SUPERCUSTOMER_ID IN (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'))) AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
//	echo $sql."<BR>";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$all_customer_paid = $row2['PAID'];
	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN (SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID IN (SELECT MARKETING_ID FROM MARKETING_CUSTOMER_GROUPS WHERE SUPERCUSTOMER_ID IN (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'))) AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
//	echo $sql."<BR>";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$all_customer_reimbursed = $row2['PAID'];
	$all_customer_total = $all_customer_paid - $all_customer_reimbursed;

//	Put it all together for...
	$current_budget = round(($current_total / $all_customer_total) * $supercustomer_budget, 0);

	$variance_to_budget = round($current_total - $current_budget, 0);
	$variance_to_last = round($current_total - $current_lasttimeframe, 0);







//  First line of the table data gets printed.
//	array_push($row, array('one'=>'Revenue', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));
	array_push($row, array('one'=>'Revenue ($)', 'two'=>number_format($current_total), 'three'=>number_format($current_budget), 'four'=>number_format($variance_to_budget), 'five'=>number_format($current_lasttimeframe), 'six'=>number_format($variance_to_last), 'seven'=>number_format($prior_1_total), 'eight'=>number_format($prior_2_total), 'nine'=>number_format($prior_3_total), 'ten'=>number_format($prior_4_total)));


//  Now, we start dealing with the second line.  Oh, the math, it hurts...
// We already have the R(M) values, I'm adding them here in comment form for ease of viewing
/*
	$current_total
	$current_lasttimeframe
	$prior_1_total
	$prior_2_total
	$prior_3_total
	$prior_4_total
*/

// R(S) variables
	$current_invoice_total_contrib_RS = 0;
	$current_payback_total_contrib_RS = 0;
	$current_invoice_lasttimeframe_contrib_RS = 0;
	$current_payback_lasttimeframe_contrib_RS = 0;
	$prior_1_invoice_contrib_RS = 0;
	$prior_1_payback_contrib_RS = 0;
	$prior_2_invoice_contrib_RS = 0;
	$prior_2_payback_contrib_RS = 0;
	$prior_3_invoice_contrib_RS = 0;
	$prior_3_payback_contrib_RS = 0;
	$prior_4_invoice_contrib_RS = 0;
	$prior_4_payback_contrib_RS = 0;

// S(Contrib) variables

	$Scontrib_current = 0;
	$Scontrib_lasttimeframe = 0;
	$Scontrib_prior_1 = 0;
	$Scontrib_prior_2 = 0;
	$Scontrib_prior_3 = 0;
	$Scontrib_prior_4 = 0;

// populate S(Contrib) values
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$end_date."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_current = $row2['THE_CONTRIB'];
	
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$last_end."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_lasttimeframe = $row2['THE_CONTRIB'];
	
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_prior_1 = $row2['THE_CONTRIB'];
	
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_prior_2 = $row2['THE_CONTRIB'];
	
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_prior_3 = $row2['THE_CONTRIB'];
	
	$sql = "SELECT SUM(CONTRIBUTION_VALUE) THE_CONTRIB FROM MARKETING_SUPERCUSTOMER_INFO WHERE FOR_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND FOR_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY') AND SUPERCUSTOMER_ID = (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Scontrib_prior_4 = $row2['THE_CONTRIB'];
	
// next up, generate a list of customers, all part of the current supercustomer grouping, to be passed into the R(M) sql statement
// note:  $customer_RS_listing also gets used later on, so be careful modifying this area.
	$sql = "SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID IN (SELECT MARKETING_ID FROM MARKETING_CUSTOMER_GROUPS WHERE SUPERCUSTOMER_ID IN (SELECT SUPERCUSTOMER_ID FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'))"; 
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	if(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$customer_RS_listing = "('".$row2['CUSTOMER_NUMBER']."'";
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$customer_RS_listing .= ",'".$row2['CUSTOMER_NUMBER']."'";
		}
		$customer_RS_listing .= ")";
	} else {
		$customer_RS_listing = "('999999999')"; // a customer we *should* never have, and will be used later to check for existance
	}

// with the list generated, proceed to get the amounts paid / returned.
	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_invoice_total_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_payback_total_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_invoice_lasttimeframe_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_payback_lasttimeframe_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_1_invoice_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_1_payback_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_2_invoice_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_2_payback_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_3_invoice_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_3_payback_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('INVOICED', 'DEBITMEMO') AND SERVICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_4_invoice_contrib_RS = $row2['PAID'];

	$sql = "SELECT SUM(SERVICE_AMOUNT) PAID FROM BILLING WHERE CUSTOMER_ID IN ".$customer_RS_listing." AND SERVICE_STATUS IN ('CREDITMEMO') AND SERVICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND SERVICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_4_payback_contrib_RS = $row2['PAID'];

//  subtract as necessary
	$current_contrib_RS_total = $current_invoice_total_contrib_RS - $current_payback_total_contrib_RS;
	$lasttimeframe_contrib_RS_total = $current_invoice_lasttimeframe_contrib_RS - $current_payback_lasttimeframe_contrib_RS;
	$prior_1_contrib_RS_total = $prior_1_invoice_contrib_RS - $prior_1_payback_contrib_RS;
	$prior_2_contrib_RS_total = $prior_2_invoice_contrib_RS - $prior_2_payback_contrib_RS;
	$prior_3_contrib_RS_total = $prior_3_invoice_contrib_RS - $prior_3_payback_contrib_RS;
	$prior_4_contrib_RS_total = $prior_4_invoice_contrib_RS - $prior_4_payback_contrib_RS;

// final calculations
	if($current_contrib_RS_total != 0){
		$current_contrib = round(($current_total / $current_contrib_RS_total) * $Scontrib_current, 0);
		$current_contrib_percent = round(($current_total / $current_contrib_RS_total) * 100, 2);
	} else {
		$current_contrib = "Bad Data";
	}
	if($lasttimeframe_contrib_RS_total != 0){
		$lasttimeframe_contrib = round(($current_lasttimeframe / $lasttimeframe_contrib_RS_total) * $Scontrib_lasttimeframe, 0);
		$lasttimeframe_contrib_percent = round(($current_lasttimeframe / $lasttimeframe_contrib_RS_total) * 100, 2);
	} else {
		$lasttimeframe_contrib = "Bad Data";
	}
	if($prior_1_contrib_RS_total != 0){
		$prior_1_contrib = round(($prior_1_total / $prior_1_contrib_RS_total) * $Scontrib_prior_1, 0);
		$prior_1_contrib_percent = round(($prior_1_total / $prior_1_contrib_RS_total) * 100, 2);
	} else {
		$prior_1_contrib = "Bad Data";
	}
	if($prior_2_contrib_RS_total != 0){
		$prior_2_contrib = round(($prior_2_total / $prior_2_contrib_RS_total) * $Scontrib_prior_2, 0);
		$prior_2_contrib_percent = round(($prior_2_total / $prior_2_contrib_RS_total) * 100, 2);
	} else {
		$prior_2_contrib = "Bad Data";
	}
	if($prior_3_contrib_RS_total != 0){
		$prior_3_contrib = round(($prior_3_total / $prior_3_contrib_RS_total) * $Scontrib_prior_3, 0);
		$prior_3_contrib_percent = round(($prior_3_total / $prior_3_contrib_RS_total) * 100, 2);
	} else {
		$prior_3_contrib = "Bad Data";
	}
	if($prior_4_contrib_RS_total != 0){
		$prior_4_contrib = round(($prior_4_total / $prior_4_contrib_RS_total) * $Scontrib_prior_4, 0);
		$prior_4_contrib_percent = round(($prior_4_total / $prior_4_contrib_RS_total) * 100, 2);
	} else {
		$prior_4_contrib = "Bad Data";
	}

	$contrib_variance_to_last_year = round($current_contrib - $lasttimeframe_contrib, 0);

// and write out row 2 (yay?  this is taking way too long...)
	array_push($row, array('one'=>'Contrib ($)', 'two'=>number_format($current_contrib)."\n".$current_contrib_percent."%", 'three'=>'', 'four'=>'', 'five'=>number_format($lasttimeframe_contrib)."\n".$lasttimeframe_contrib_percent."%", 'six'=>number_format($contrib_variance_to_last_year), 'seven'=>number_format($prior_1_contrib)."\n".$prior_1_contrib_percent."%", 'eight'=>number_format($prior_2_contrib)."\n".$prior_2_contrib_percent."%", 'nine'=>number_format($prior_3_contrib)."\n".$prior_3_contrib_percent."%", 'ten'=>number_format($prior_4_contrib)."\n".$prior_4_contrib_percent."%"));



// time to start on line 3, Quantity...
// Special case here:  Jon specifies whether this customer is measured in Tons, or by some other unit of measurement
// It "other", there may be a case where units of measurement differ within the same customer, in which case, I will print
// out each type.

// define a couple variables:
//	$measurement_types = array();  // this variable gets used, but its definition is dependant on a factor later on
	$array_of_values_for_types = array();
	$oracle_customer_set = "";

// First up, get the list of customers to scan cargo for
	$sql = "SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID = '".$customer_id."'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$oracle_customer_set = "('".$row2['CUSTOMER_NUMBER']."'";
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$oracle_customer_set .= ",'".$row2['CUSTOMER_NUMBER']."'";
	}
	$oracle_customer_set .= ")";

//  Second, determine how to measure them
	$sql = "SELECT BY_MEASUREMENT FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row2['BY_MEASUREMENT'] == 'TONS'){
		$measurement_types = "TONS";
	} else {
		$sql = "SELECT DISTINCT QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE QTY1_UNIT IS NOT NULL AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND CT.DATE_RECEIVED > to_date('".$prior_4_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		$measurement_types = array();
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($measurement_types, $row2['QTY1_UNIT']);
		}
	}

// Now get the values for printing (different SQL if its by weight or measurements).  More long arithmetic.
// There exists a table in the DB for numerical conversions, but as these are universal constant values, AND I notice quite a few bad
// DB entires, I will hardcode these, and cut 'N paste a bunch of lines.  Again.
// All weights converted to pounds for calculation purposes.
	if($measurement_types == "TONS"){
		$total_weight_current = 0;
		$total_weight_lasttimeframe = 0;
		$prior_1_weight = 0;
		$prior_2_weight = 0;
		$prior_3_weight = 0;
		$prior_4_weight = 0;

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$start_date."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$end_date."', 'MM/DD/YYYY')";
//		echo $sql."\n";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$total_weight_current += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$total_weight_current += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$total_weight_current += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$total_weight_current += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$last_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$last_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$total_weight_lasttimeframe += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$total_weight_lasttimeframe += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$total_weight_lasttimeframe += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$total_weight_lasttimeframe += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_1_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$prior_1_weight += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$prior_1_weight += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$prior_1_weight += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$prior_1_weight += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_2_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$prior_2_weight += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$prior_2_weight += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$prior_2_weight += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$prior_2_weight += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_3_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$prior_3_weight += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$prior_3_weight += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$prior_3_weight += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$prior_3_weight += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

		$sql = "SELECT CARGO_WEIGHT, CARGO_WEIGHT_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_4_end."', 'MM/DD/YYYY')";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$weight_unit = $row2['CARGO_WEIGHT_UNIT'];
			switch($weight_unit){
				case 'LB':
				case 'LBS':
					$prior_4_weight += $row2['CARGO_WEIGHT'];
				break;

				case 'KG':
					$prior_4_weight += ($row2['CARGO_WEIGHT'] * 2.205);
				break;

				case 'TON':
				case 'TONS':
					$prior_4_weight += ($row2['CARGO_WEIGHT'] * 2000);
				break;

				case 'MT':
					$prior_4_weight += ($row2['CARGO_WEIGHT'] * 2.205 * 1000);
				break;

				default:
				break;
			}
		}

// now convert all of the totals into tons for viewing purposes.
		$total_weight_current /= 2000;
		$total_weight_lasttimeframe /= 2000;
		$prior_1_weight /= 2000;
		$prior_2_weight /= 2000;
		$prior_3_weight /= 2000;
		$prior_4_weight /= 2000;

		$variance_to_last_year = $total_weight_lasttimeframe - $total_weight_current;

// This is the output of the Third line of the table IF AND ONLY IF this commodity is measured in "tons"
		array_push($row, array('one'=>'Volume', 'two'=>number_format($total_weight_current)." TONS", 'three'=>'', 'four'=>'', 'five'=>number_format($total_weight_lasttimeframe)." TONS", 'six'=>number_format($variance_to_last_year)." TONS", 'seven'=>number_format($prior_1_weight)." TONS", 'eight'=>number_format($prior_2_weight)." TONS", 'nine'=>number_format($prior_3_weight)." TONS", 'ten'=>number_format($prior_4_weight)." TONS"));
	} else {

// this happens if the measurement for commodities is NOT by weight.  6 more cut 'N pastes.
		$totals_for_current = "";
		$totals_for_lasttimeframe = "";
		$prior_1_totals = "";
		$prior_2_totals = "";
		$prior_3_totals = "";
		$prior_4_totals = "";


		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$start_date."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$end_date."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$totals_for_current .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$last_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$last_end."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$totals_for_lasttimeframe .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_1_end."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$prior_1_totals .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_2_end."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$prior_2_totals .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_3_end."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$prior_3_totals .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$sql = "SELECT SUM(QTY1) THE_SUM, QTY1_UNIT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CM.CONTAINER_NUM = CT.LOT_NUM AND CM.RECIPIENT_ID IN ".$oracle_customer_set." AND DATE_RECEIVED > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND DATE_RECEIVED < to_date('".$prior_4_end."', 'MM/DD/YYYY') AND QTY1_UNIT IS NOT NULL GROUP BY QTY1_UNIT";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$prior_4_totals .= $row2['THE_SUM']." ".$row2['QTY1_UNIT']." \r\n";
		}

		$variance_to_last_year = $totals_for_current - $totals_for_lasttimeframe;


// and write out the third line of output (only applies if measurement is NOT by weight)
		array_push($row, array('one'=>'Volume', 'two'=>"$totals_for_current", 'three'=>'', 'four'=>'', 'five'=>"$totals_for_lasttimeframe", 'six'=>"$variance_to_last_year", 'seven'=>"$prior_1_totals", 'eight'=>"$prior_2_totals", 'nine'=>"$prior_3_totals", 'ten'=>"$prior_4_totals"));
	}






// Line 4.  Contribution per unit.
// There are 2 possibilities here:  either there are multiple measurement types, or there aren't.
// if there are, this section is SKIPPED.  If there is only 1, then we proceed to the arithmetic.

/* Variable used earlier, referenced here for ease of viewing
	$measurement_types
*/

	if(sizeof($measurement_types) > 1){
		array_push($row, array('one'=>'Contrib Per Unit', 'two'=>'N/A', 'three'=>'', 'four'=>'', 'five'=>'N/A', 'six'=>'N/A', 'seven'=>'N/A', 'eight'=>'N/A', 'nine'=>'N/A', 'ten'=>'N/A'));
	} else {
		if(is_array($measurement_types)){
			$the_measurement = $measurement_types[0];
		} else {
			$the_measurement = $measurement_types;
		}

		if($the_measurement != "TONS"){
// if some unit of measure besides tons...
			if($totals_for_current != 0) { 
				$C_P_U_current = round($current_contrib / $totals_for_current, 2);
			} else {
				$C_P_U_current = 0;
			}
			if($totals_for_lasttimeframe != 0){
				$C_P_U_lasttimeframe = round($lasttimeframe_contrib / $totals_for_lasttimeframe, 2);
			} else {
				$C_P_U_lasttimeframe = 0;
			}
			if($prior_1_totals != 0){
				$C_P_U_prior_1 = round($prior_1_contrib / $prior_1_totals, 2);
			} else {
				$C_P_U_prior_1 = 0;
			}
			if($prior_2_totals != 0){ 
				$C_P_U_prior_2 = round($prior_2_contrib / $prior_2_totals, 2);
			} else {
				$C_P_U_prior_2 = 0;
			}
			if($prior_3_totals != 0){ 
				$C_P_U_prior_3 = round($prior_3_contrib / $prior_3_totals, 2);
			} else {
				$C_P_U_prior_3 = 0;
			}
			if($prior_4_totals != 0){ 
				$C_P_U_prior_4 = round($prior_4_contrib / $prior_4_totals, 2);
			} else {
				$C_P_U_prior_4 = 0;
			}

			$tagline = "($ / ".$the_measurement.")";
		} else {
// if unit of measure is tons...
			if($total_weight_current != 0){
				$C_P_U_current = round($current_contrib / $total_weight_current, 2);
			} else {
				$C_P_U_current = 0;
			}
			if($total_weight_lasttimeframe != 0){
				$C_P_U_lasttimeframe = round($lasttimeframe_contrib / $total_weight_lasttimeframe, 2);
			} else {
				$C_P_U_lasttimeframe = 0;
			}
			if($prior_1_weight != 0){
				$C_P_U_prior_1 = round($prior_1_contrib / $prior_1_weight, 2);
			} else {
				$C_P_U_prior_1 = 0;
			}
			if($prior_2_weight != 0){
				$C_P_U_prior_2 = round($prior_2_contrib / $prior_2_weight, 2);
			} else {
				$C_P_U_prior_2 = 0;
			}
			if($prior_3_weight != 0){
				$C_P_U_prior_3 = round($prior_3_contrib / $prior_3_weight, 2);
			} else {
				$C_P_U_prior_3 = 0;
			}
			if($prior_4_weight != 0){
				$C_P_U_prior_4 = round($prior_4_contrib / $prior_4_weight, 2);
			} else {
				$C_P_U_prior_4 = 0;
			}

			$tagline = "($ / TON)";
		}

		$C_P_U_variance_last = $C_P_U_current - $C_P_U_lasttimeframe;


// Simple enough, no?  Now print them...
		array_push($row, array('one'=>'Contrib Per Unit', 'two'=>"$C_P_U_current\r\n$tagline", 'three'=>'', 'four'=>'', 'five'=>"$C_P_U_lasttimeframe\r\n$tagline", 'six'=>"$C_P_U_variance_last\r\n$tagline", 'seven'=>"$C_P_U_prior_1\r\n$tagline", 'eight'=>"$C_P_U_prior_2\r\n$tagline", 'nine'=>"$C_P_U_prior_3\r\n$tagline", 'ten'=>"$C_P_U_prior_4\r\n$tagline"));
	}
	



// removed to to un-necessity, and sheer impossibleness
//	array_push($row, array('one'=>'Productivity', 'two'=>'', 'three'=>'', 'four'=>'', 'five'=>'', 'six'=>'', 'seven'=>'', 'eight'=>'', 'nine'=>'', 'ten'=>''));



// Line 5:  Unique ship calls
// This will probably be not only the simplest section of this code, but the simplest section of any code I write ever.

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$start_date."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$end_date."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_shipcalls = $row2['THE_COUNT'];

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$last_start."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$last_end."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$lasttimeframe_shipcalls = $row2['THE_COUNT'];

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$prior_1_start."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$prior_1_end."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_1_shipcalls = $row2['THE_COUNT'];

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$prior_2_start."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$prior_2_end."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_2_shipcalls = $row2['THE_COUNT'];

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$prior_3_start."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$prior_3_end."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_3_shipcalls = $row2['THE_COUNT'];

	$sql = "SELECT COUNT(DISTINCT LR_NUM) THE_COUNT FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE RECIPIENT_ID IN ".$oracle_customer_set." AND CM.CONTAINER_NUM = CT.LOT_NUM AND CT.DATE_RECEIVED > TO_DATE('".$prior_4_start."', 'MM/DD/YYYY') AND CT.DATE_RECEIVED < TO_DATE('".$prior_4_end."', 'MM/DD/YYYY') AND LR_NUM > 0";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prior_4_shipcalls = $row2['THE_COUNT'];


	$shipcall_variance = $current_shipcalls - $lasttimeframe_shipcalls;


// write the shipcall line
	array_push($row, array('one'=>'Ship Calls', 'two'=>"$current_shipcalls", 'three'=>'', 'four'=>'', 'five'=>"$lasttimeframe_shipcalls", 'six'=>"$shipcall_variance", 'seven'=>"$prior_1_shipcalls", 'eight'=>"$prior_2_shipcalls", 'nine'=>"$prior_3_shipcalls", 'ten'=>"$prior_4_shipcalls"));
	
	
	
	
	
	



// THE FINAL LINE:  Claims.
// There are 2 claims tables in BNI; that said, they are MUTUALLY EXCLUSIVE as of the time of this program's creation,
// So I can scan both without fear of duplication.
// Marketing is aware that not all claims are in the DB's (some are in Excel on Astrid's computer), and will not
// expect those to show on the report.

	$claim_current = 0;
	$claim_lasttimeframe = 0;
	$claim_prior_1 = 0;
	$claim_prior_2 = 0;
	$claim_prior_3 = 0;
	$claim_prior_4 = 0;

	$claim_variance = 0;


	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_current += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_current += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$start_date."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$end_date."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_current += $row2['AMT_PAID'];
	}

	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_lasttimeframe += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_lasttimeframe += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$last_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$last_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_lasttimeframe += $row2['AMT_PAID'];
	}

	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_prior_1 += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_prior_1 += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_1_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_1_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_prior_1 += $row2['AMT_PAID'];
	}

	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_prior_2 += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_prior_2 += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_2_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_2_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_prior_2 += $row2['AMT_PAID'];
	}

	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_prior_3 += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_prior_3 += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_3_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_3_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_prior_3 += $row2['AMT_PAID'];
	}

	$sql = "SELECT * FROM CLAIM_HEADER_RF WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row2['ISPERCENTAGE'] == 'Y'){
			$claim_prior_4 += ($row2['AMT_PAID'] * .68);
		} else {
			$claim_prior_4 += $row2['AMT_PAID'];
		}
	}
	$sql = "SELECT * FROM CLAIM_HEADER WHERE STATUS IN ('O', 'C') AND INVOICE_DATE > to_date('".$prior_4_start."', 'MM/DD/YYYY') AND INVOICE_DATE < to_date('".$prior_4_end."', 'MM/DD/YYYY') AND CUSTOMER_ID IN ".$oracle_customer_set;
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$claim_prior_4 += $row2['AMT_PAID'];
	}

	$claim_variance = round($claim_current - $claim_lasttimeframe, 0);
	$claim_current = round($claim_current, 0);
	$claim_lasttimeframe = round($claim_lasttimeframe, 0);
	$claim_prior_1 = round($claim_prior_1, 0);
	$claim_prior_2 = round($claim_prior_2, 0);
	$claim_prior_3 = round($claim_prior_3, 0);
	$claim_prior_4 = round($claim_prior_4, 0);


	array_push($row, array('one'=>'Claims / Expense ($)', 'two'=>number_format($claim_current), 'three'=>'', 'four'=>'', 'five'=>number_format($claim_lasttimeframe), 'six'=>number_format($claim_variance), 'seven'=>number_format($claim_prior_1), 'eight'=>number_format($claim_prior_2), 'nine'=>number_format($claim_prior_3), 'ten'=>number_format($claim_prior_4)));

	$pdf->ezTable($row, $colHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>550,'fontSize'=>8, 'cols'=>$colOptions));

	$pdf->ezNewPage();

   	$pdf->ezText("<i>Customers Numbers associated with group:  $customer_name</i>", 12, $left);

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID IN (SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID = '".$customer_id."')";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<i>".$row2['CUSTOMER_NAME']."</i>", 12, $left);
	}
//   	$pdf->ezText("<i>121 - Test Customer Number 1</i>", 12, $left);
//   	$pdf->ezText("<i>1940 - Test Customer Number 2</i>", 12, $left);

	$pdf->ezSetDy(-10);
	$pdf->ezText("<i>Customers Numbers used to determine Supercustomer Budget and Contribution Values:</i>", 12, $left);

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID IN ".$customer_RS_listing." ORDER BY CUSTOMER_NAME";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<i>".$row2['CUSTOMER_NAME']."</i>", 12, $left);
	}


//   	$pdf->line(50,40,560,40);
   	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	$pdf->addText(60,34,6, $msg);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

	$pdfcode = $pdf->ezOutput();

   $dir = '/var/www/html/upload/pdf_files';
   if (!file_exists($dir)) {
     mkdir ($dir, 0775);
   }

   $fname = tempnam($dir . '/', 'PDF_') . '.pdf';
   $fp = fopen($fname, 'w');
   fwrite($fp, $pdfcode);
   fclose($fp);

   list($junk1, $junk2, $junk3, $junk4, $junk5, $junk, $filename) = split("/", $fname);
   header("Location: /upload/pdf_files/$filename");

  // clean out files older than 5 minutes; code taken from redirect_pdf.php
   if ($d = @opendir($dir)) {
      while (($file = readdir($d)) !== false) {
	 if (substr($file,0,4)=="PDF_"){
	    // then check to see if this one is too old
	    $ftime = filemtime($dir.'/'.$file);
	    if (time() - $ftime > 300){
	      unlink($dir.'/'.$file);
	    }
	 }
      }  
      
      closedir($d);
   }



?>
