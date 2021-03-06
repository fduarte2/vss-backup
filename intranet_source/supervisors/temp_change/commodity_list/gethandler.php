<?php

require("/web/web_pages/functions/Grid_2.6/ebaxml.php");

// This file is used as a Get Handler for the Grid control. When the grid is initialized,
// the get handler script (this page) is called and expected to return a properly formatted
// xml stream. We have provided all the necessary functionality to do this without actually
// requiring you to construct XML. Simply interface with your datasource and use the provided
// function calls to create the necessary output.

// Gethandlers must be able to output xml when called without any parameters.

// We have provided all the necessary functionality in the EBASaveHandler_ functions to extract any of the update instructions.
// For more details please have a look at our shipped online help under Reference/Server



// *******************************************************************
// This code block just sets up the database and tries to figure out what index number
// our data starts at. This code has nothing to do with the grid itself so think of it as
// business logic.
// *******************************************************************

include("connect.php");
$conn = ora_logon("LABOR@$lcs", "LABOR");
if($conn < 1){
    printf("Error logging on to the LCS Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
}
$cursor = ora_open($conn);

$sql = "select * from product_temp";
ora_parse($cursor, $sql);
ora_exec($cursor);

$results = array();
while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        array_push($results, $row);
}


// *******************************************************************
// Lets Set up the Output
// *******************************************************************
$getHandler = new EBAGetHandler();
$getHandler->ProcessRecords();

// First we define how many columns we are sending in each record, and name each field.
// We will do this by using the EBAGetHandler_DefineField function. We will name each
// field of data after its column name in the database.

$getHandler->DefineField("ID");
$getHandler->DefineField("PRODUCT");
$getHandler->DefineField("LOW_TEMP");
$getHandler->DefineField("HIGH_TEMP");
$getHandler->DefineField("LOW_ALERT");
$getHandler->DefineField("HIGH_ALERT");
$getHandler->DefineField("LOW_WARNING");
$getHandler->DefineField("HIGH_WARNING");
$getHandler->DefineField("DISPLAY_PRODUCT");

// *******************************************************************
// Lets loop through our data and send it to the grid
// *******************************************************************

for($i = 0; $i < count($results); $i++)
{
	$getHandler->CreateNewRecord($results[$i]['ID']);
	$getHandler->DefineRecordFieldValue("ID", $results[$i]['ID']);	// set ID
	$getHandler->DefineRecordFieldValue("PRODUCT", $results[$i]['PRODUCT']);	// set product
	$getHandler->DefineRecordFieldValue("LOW_TEMP", $results[$i]['LOW_TEMP']);	// set low temp
	$getHandler->DefineRecordFieldValue("HIGH_TEMP", $results[$i]['HIGH_TEMP']);	// set high temp
        $getHandler->DefineRecordFieldValue("LOW_ALERT", $results[$i]['LOW_ALERT']);      // set low alert
        $getHandler->DefineRecordFieldValue("HIGH_ALERT", $results[$i]['HIGH_ALERT']);   // set high alert
        $getHandler->DefineRecordFieldValue("LOW_WARNING", $results[$i]['LOW_WARNING']);      // set low warning
        $getHandler->DefineRecordFieldValue("HIGH_WARNING", $results[$i]['HIGH_WARNING']);   // set high warning
	$getHandler->DefineRecordFieldValue("DISPLAY_PRODUCT",$results[$i]['DISPLAY_PRODUCT']);	// set display name
	$getHandler->SaveRecord();

}

$getHandler->CompleteGet();
ora_close($cursor);
ora_logoff($conn);

?>
