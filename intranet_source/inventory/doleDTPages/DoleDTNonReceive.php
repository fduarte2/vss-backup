<?php
/* 
*  March 2009
*  Display a list of dock tickets that, as of yet, have NO
*	received rolls on them (but are already EDI-received)
*  
*******************************************************************/

  // include this file to use phpgrid
include("../../functions/phpgrid/include/phpgrid.php");

/*
$hostName = "172.22.15.238:1521";
$userName = "TCONSTANT";
$password = "TCONSTANT";
$dbName  = "RFTEST";
*/
$hostName = "172.22.15.204:1521";
$userName = "SAG_OWNER";
$password = "OWNER";
$dbName  = "RF";

?>
<html>
<head>
<title>Dole Dock Ticket Paper Lookup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
 
$dg -> set_gridpath     ("../../functions/phpgrid/include/");

$dg -> set_sql			("SELECT ARRIVAL_NUM, BOL FROM (SELECT ARRIVAL_NUM, BOL, MAX(NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE')) THE_MAX FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' GROUP BY ARRIVAL_NUM, BOL HAVING MAX(NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE')) = 'NONE') ORDER BY ARRIVAL_NUM, BOL");


//$dg -> set_sql          ("SELECT DISTINCT ARRIVAL_NUM, BOL FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND BOL NOT IN (SELECT BOL FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM') ORDER BY ARRIVAL_NUM, BOL");
$dg -> set_sql_table    ("CT");
$dg -> set_sql_key      ("BOL");


// create master/detail tables
$dg -> set_masterdetail("SELECT ARRIVAL_NUM, BOL, BATCH_ID CODE, PALLET_ID, CARGO_DESCRIPTION, COMMODITY_CODE, DATE_RECEIVED, WEIGHT, MARK BASIS_WEIGHT, QTY_IN_HOUSE, QTY_DAMAGED FROM CARGO_TRACKING","BOL"," order by ARRIVAL_NUM, BOL, PALLET_ID"); 

// make the datagrid editable
//$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
// $dg -> set_action_type ("VE");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_page_size(20);
$dg -> set_theme("bone");
$dg -> display();
?>
</body>
</html>
