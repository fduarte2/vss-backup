<?php
/* Created April 9, 2008 by Inigo Thomas
*  
*  This is an example of the phpgrid used to bring up a single table
*  
*******************************************************************/

  // include this file to use phpgrid
include("include/phpgrid.php");

$hostName = "172.22.15.4:1521";
$userName = "sag_owner";
$password = "sag";
$dbName  = "ORCL";

?>
<html>
<head>
<title>Monthly Misc Vessel Calls</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<?php
$dg = new C_DataGrid($hostName, $userName, $password, $dbName, "oracle");
 
$dg -> set_gridpath     ("include/");
$dg -> set_sql          ("SELECT * FROM MISC_VESSEL ORDER BY YEAR_SAILED, MONTH_SAILED, VESSEL_TYPE");
$dg -> set_sql_table    ("MISC_VESSEL");
$dg -> set_sql_key      ("ENTRY_ID");

// make the datagrid editable
$dg -> set_allow_actions(true);
$dg -> set_action_type ("VED");

$dg -> add_control("VESSEL_TYPE",                    
		CHECKBOX, 
		array("PETROLEUM"=>"PETROLEUM", 
		      "BARGE"=>"BARGE",  
		      "OTHER"=>"OTHER"), 
		      ",");

$dg -> set_col_hidden   ("ENTRY_ID");

$dg -> display();
?>

</body>
</html>
