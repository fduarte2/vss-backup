<?
/*
*
*	Adam Walter, May 2008.
*
*	This report shows invoiced bills for a given customer/fiscal year combination,
*	As passed to this page from "rf_storage_billed_select.php"
*
*	This is the "maiden voyage" of the PHPGrid.
********************************************************************************/

	if($HTTP_POST_VARS['customer'] != ""){
		$customer = $HTTP_POST_VARS['customer'];
	} else {
		$customer = $HTTP_COOKIE_VARS['customer'];
	}
	if($HTTP_POST_VARS['year'] != ""){
		$year = $HTTP_POST_VARS['year'];
	} else {
		$year = $HTTP_COOKIE_VARS['year'];
	}

	setcookie("customer", $customer, time()+3600);
	setcookie("year", $year, time()+3600);

	include("include/phpgrid.php");
	$hostName = "172.22.15.204:1521";
	//$hostName = "172.22.15.238:1521"; // RF.TEST
	$userName = "sag_owner";
	$password = "owner";
	$dbName  = "RF";

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");

/*
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
*/

  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$sql = "SELECT CUSTOMER_ID, SERVICE_CODE, INVOICE_NUM, SERVICE_AMOUNT, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, ARRIVAL_NUM, COMMODITY_CODE, SERVICE_QTY, SERVICE_RATE, SERVICE_QTY2, BILLING_NUM FROM RF_BILLING WHERE SERVICE_STATUS = 'INVOICED' AND TO_CHAR(ADD_MONTHS(SERVICE_DATE, 1), 'YYYY') = '".$year."'";
	if($customer != "ALL"){
		$sql .= " AND CUSTOMER_ID = '".$customer."'";
	}
//	echo $sql;

	$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
	
	$dg -> set_gridpath     ("../../functions/phpgrid/include/");
//	$dg -> set_gridpath     ("/web/web_pages/functions/phpgrid/include/");
	$dg -> set_sql          ($sql);
	$dg -> set_sql_table    ("RF_BILLING");
	$dg -> set_sql_key      ("BILLING_NUM");
	$dg -> set_page_size	(40);
	$dg -> set_col_title    ("CUSTOMER_ID", "CUSTOMER");
	$dg -> set_col_title    ("SERVICE_CODE", "SERVICE");
	$dg -> set_col_title    ("INVOICE_NUM", "INVOICE");
	$dg -> set_col_title    ("SERVICE_AMOUNT", "AMOUNT");
	$dg -> set_col_title    ("THE_START", "SERVICE START");
	$dg -> set_col_title    ("THE_END", "SERVICE END");
	$dg -> set_col_title    ("ARRIVAL_NUM", "VESSEL");
	$dg -> set_col_title    ("COMMODITY_CODE", "COMMODITY");
	$dg -> set_col_title    ("SERVICE_QTY", "CASES");
	$dg -> set_col_title    ("SERVICE_RATE", "RATE");
	$dg -> set_col_title    ("SERVICE_QTY2", "PALLETS");
	$dg -> set_col_hidden   ("BILLING_NUM");

	$dg -> set_masterdetail("SELECT PALLET_ID PALLET, SERVICE_RATE RATE, SERVICE_QTY CASES, SERVICE_AMOUNT AMOUNT FROM RF_BILLING_DETAIL", "SUM_BILL_NUM", " order by PALLET_ID"); 

	$dg -> set_allow_actions(false);
//	$dg -> set_action_type ("V");   
	$dg -> set_theme("adam-bone");
	$dg -> display();

include("pow_footer.php");
