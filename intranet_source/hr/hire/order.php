<?php
/* Created April 9, 2008 by Inigo Thomas
*  
*  This is an example of the phpgrid used to bring up a single table
*  
*******************************************************************/

  // include this file to use phpgrid
include("../../functions/phpgrid/include/phpgrid.php");

$hostName = "172.22.15.4";
$userName = "labor";
$password = "labor";
$dbName  = "ORCL";

?>
<html>
<head>
<title>phpGrid - Example 2k</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
$dg -> set_gridpath     ("../../functions/phpgrid/include/");
$dg -> set_sql          ("SELECT * FROM HIRE_SUMMARY WHERE HIRE_DATE > TO_DATE('01-JAN-2008', 'DD-MON-YYYY') ORDER BY HIRE_DATE DESC, EMPLOYEE_TYPE_ID");
$dg -> set_sql_table    ("HIRE_SUMMARY");
$dg -> set_sql_key      ("HIRE_DATE");

// make the datagrid editable
//$dg -> set_allow_actions(true);

// set data administrative level
// "V" means View, "E" means Edit, and "D" means Delete.
$dg -> set_action_type ("V");   // view and edit only

// turn on inline-editing with Ajax enabled
//$dg -> set_inlineedit_enabled(true, true);

$dg -> set_page_size(25);
$dg -> set_theme("bone");
$dg -> display();
?>

</body>
</html>
