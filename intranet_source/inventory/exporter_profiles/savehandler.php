<?php

require("/web/web_pages/functions/Grid_2.6/ebaxml.php");

// This file is used as a Save Handler for the Grid control. When the user clicks
// the save button in default.asp a datagram is sent to this script.
// The script in turn looks at each update in the datagram and processes them accordingly.

// We have provided all the necessary functionality in the EBASaveHandler_ functions to extract any of the update instructions.
// For more details please have a look at our shipped online help under Reference/Server

// We need this small function in order to handle NULL values for number columns in the database
function GetNullOrNumber($number)
{
	if (gettype($number) == NULL || $number == "")
	{
		return "NULL";
	}
	else
	{
		return $number;
	}
}

$saveHandler = new EBASaveHandler();

$saveHandler->ProcessRecords();

// This block of code is ADO connection code used only for demonstration purposes
// objConn is an ADODB object we use here for demonstration purposes.
include("connect.php");
$conn = ora_logon("SAG_OWNER@BNI", "sag");
if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
}
$cursor = ora_open($conn);

// ********************************************************** //
// Begin by processing our inserts
// ********************************************************** //
$insertCount = $saveHandler->ReturnInsertCount();
if ($insertCount > 0)
{
	// Yes there are INSERTs to perform...
	for ($currentRecord = 0; $currentRecord < $insertCount; $currentRecord++)
	{
		$sql = "INSERT INTO EXPORTER_PROFILE (EXPORTER_ID, EXPORTER_NAME) VALUES (
                         ".  GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord, "EXPORTER_ID"))  . "," . 
                        "'" . $saveHandler->ReturnInsertField($currentRecord,"EXPORTER_NAME") . "'" .
	                   	") ";

		// Now we execute this query
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		
	}
}

// ********************************************************** //
// Continue by processing our updates
// ********************************************************** //
$updateCount = $saveHandler->ReturnUpdateCount();
if ($updateCount > 0)
{
	// Yes there are UPDATEs to perform...
	for ($currentRecord = 0; $currentRecord < $updateCount; $currentRecord++)
	{
		$sql = "UPDATE EXPORTER_PROFILE SET 
			EXPORTER_NAME = '".  $saveHandler->ReturnUpdateField($currentRecord,"EXPORTER_NAME") ."', 
			WHERE EXPORTER_ID = '" . GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord, "EXPORTER_ID")) . "'";

		

		// Now we execute this query
                ora_parse($cursor, $sql);
                ora_exec($cursor);
	}
}

// ********************************************************** //
// Finish by processing our deletes
// ********************************************************** //
$deleteCount = $saveHandler->ReturnDeleteCount();
if ($deleteCount > 0)
{
	// Yes there are DELETEs to perform...
	for ($currentRecord = 0; $currentRecord < $deleteCount; $currentRecord++)
	{
		$sql = "DELETE FROM EXPORTER_PROFILE WHERE EXPORTER_ID = " . $saveHandler->ReturnDeleteField($currentRecord);

		// Now we execute this query
		ora_parse($cursor, $sql);
                ora_exec($cursor);

	}
}

$saveHandler->CompleteSave();
ora_close($cursor);
ora_logoff($conn);

?>

