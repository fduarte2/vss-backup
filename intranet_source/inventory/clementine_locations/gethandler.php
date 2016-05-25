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
$conn = ora_logon("SAG_OWNER@RF", "owner");
if($conn < 1){
    printf("Error logging on to the RF_NEW Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
}


$cursor = ora_open($conn);

$sql = "select PALLET_ID, CARGO_SIZE, EXPORTER_CODE, WAREHOUSE_LOCATION, COMMODITY_CODE from CARGO_TRACKING where ARRIVAL_NUM = '9112'";
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

$getHandler->DefineField("PALLET_ID");
$getHandler->DefineField("CARGO_SIZE");
$getHandler->DefineField("EXPORTER_CODE");
$getHandler->DefineField("WAREHOUSE_LOCATION");
$getHandler->DefineField("COMMODITY_CODE");


// *******************************************************************
// Lets loop through our data and send it to the grid
// *******************************************************************

for($i = 0; $i < count($results); $i++)
{
	$getHandler->CreateNewRecord($results[$i]['PALLET_ID']);
	$getHandler->DefineRecordFieldValue("PALLET_ID", $results[$i]['PALLET_ID']);	// set ID
	$getHandler->DefineRecordFieldValue("CARGO_SIZE", $results[$i]['CARGO_SIZE']);	// set cargo size
	$getHandler->DefineRecordFieldValue("EXPORTER_CODE", $results[$i]['EXPORTER_CODE']);	// set exporter code
	$getHandler->DefineRecordFieldValue("WAREHOUSE_LOCATION", $results[$i]['WAREHOUSE_LOCATION']);	// set warehouse location
        $getHandler->DefineRecordFieldValue("COMMODITY_CODE", $results[$i]['COMMODITY_CODE']);      // set commodity code
	$getHandler->SaveRecord();

}

$getHandler->CompleteGet();
ora_close($cursor);
ora_logoff($conn);

?>
