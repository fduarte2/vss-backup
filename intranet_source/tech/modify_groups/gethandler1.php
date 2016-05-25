<?php

require("/web/web_pages/functions/Grid_2.7/bin/ebaxml.php");

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
$conn = ora_logon("SAG_OWNER@BNI", "sag");
if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
}


$cursor = ora_open($conn);


$sql = "select GROUP_ID, DESCRIPTION from SURCHARGE_EXCEPTION_GROUP" ;
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

$getHandler->DefineField("GROUP_ID");
$getHandler->DefineField("DESCRIPTION");


// *******************************************************************
// Lets loop through our data and send it to the grid
// *******************************************************************

for($i = 0; $i < count($results); $i++)
{
	$getHandler->CreateNewRecord($results[$i]['GROUP_ID'] . $results[$i]['DESCRIPTION']);
	$getHandler->DefineRecordFieldValue("GROUP_ID", $results[$i]['GROUP_ID']);	// set ID
        $getHandler->DefineRecordFieldValue("DESCRIPTION", $results[$i]['DESCRIPTION']);      // customer_ID
	$getHandler->SaveRecord();

}

$getHandler->CompleteGet();
ora_close($cursor);
ora_logoff($conn);

?>
