<?php
/* Created April 9, 2008 by Inigo Thomas
*  
*  This is an example of the phpgrid used to bring up a single table
*  
*******************************************************************/

  // include this file to use phpgrid
include("include/phpgrid.php");

$hostName = "172.22.15.238:1521";
$userName = "sag_owner";
$password = "rftest238";
$dbName  = "RFTEST";
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Vessel Information Entry";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from PHPGrid testing page.  Really, if you are looking at this page, and you aren't in IT, you must be bored.");
    include("pow_footer.php");
    exit;
  }

$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
 
$dg -> set_gridpath     ("include/");
//$dg -> set_sql          ("SELECT * FROM CUSTOMER_PROFILE");
//$dg -> set_sql_table    ("CUSTOMER_PROFILE");
//$dg -> set_sql_key      ("CUSTOMER_ID");

$dg -> set_sql          ("SELECT * FROM CARGO_TRACKING WHERE DATE_RECEIVED > TO_DATE('03/01/2008', 'MM/DD/YYYY') AND ARRIVAL_NUM = '10050'");
$dg -> set_sql_table    ("CARGO_TRACKING");
//$dg -> set_sql_key      ("LR_NUM");

// create master/detail tables
//$dg -> set_masterdetail("SELECT * FROM CARGO_TRACKING", "ARRIVAL_NUM", " order by PALLET_ID"); 

// make the datagrid editable
$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
 $dg -> set_action_type ("VE");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_col_hidden   ("FREE_TIME_END");
$dg -> set_col_hidden   ("BILLING_STORAGE_DATE");
$dg -> set_col_hidden   ("FROM_SHIPPING_LINE");
$dg -> set_col_hidden   ("SHIPPING_LINE");
$dg -> set_col_hidden   ("BILL");
$dg -> set_col_hidden   ("QTY_IN_STORAGE");
$dg -> set_col_hidden   ("FUMIGATION_CODE");
$dg -> set_col_hidden   ("STORAGE_TERMINATION");
$dg -> set_col_hidden   ("EXPORTER_CODE");
$dg -> set_col_hidden   ("VARIETY");
$dg -> set_col_hidden   ("CHEP");
$dg -> set_col_hidden   ("STACKING");
$dg -> set_col_hidden   ("SUB_CUSTID");
$dg -> set_col_hidden   ("REMARK");
$dg -> set_col_hidden   ("CARGO_TYPE_ID");
$dg -> set_col_hidden   ("SOURCE_NOTE");
$dg -> set_col_hidden   ("SOURCE_USER");
$dg -> set_col_hidden   ("DECK");
$dg -> set_col_hidden   ("HATCH");

$dg -> set_alt_bgcolor("#ffcc99, #ffccdd");
$dg -> set_page_size(20);
$dg -> set_theme("adam-bone");
$dg -> display();


include("pow_footer.php");
?>
