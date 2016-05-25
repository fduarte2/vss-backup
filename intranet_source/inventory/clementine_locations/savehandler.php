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
$conn = ora_logon("SAG_OWNER@RF", "owner");
if($conn < 1){
    printf("Error logging on to the RF_NEW Oracle Server: ");
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
		$sql = "INSERT INTO CARGO_TRACKING (PALLET_ID, CARGO_SIZE, EXPORTER_CODE, WAREHOUSE_LOCATION, COMMODITY_CODE) VALUES (
                         '".  $saveHandler->ReturnInsertField($currentRecord, "PALLET_ID")  . "'," . 
                        "'" . $saveHandler->ReturnInsertField($currentRecord,"CARGO_SIZE") . "'," .
                       	"'" . $saveHandler->ReturnInsertField($currentRecord,"EXPORTER_CODE") . "'," .
                        "'" . $saveHandler->ReturnInsertField($currentRecord,"WAREHOUSE_LOCATION") . "'," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"COMMODITY_CODE")) . "" .
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
		$sql = "UPDATE CARGO_TRACKING SET 
			CARGO_SIZE = '" . $saveHandler->ReturnUpdateField($currentRecord,"CARGO_SIZE") . "', 
			EXPORTER_CODE = '".  $saveHandler->ReturnUpdateField($currentRecord,"EXPORTER_CODE") ."', 
			WAREHOUSE_LOCATION = '".  $saveHandler->ReturnUpdateField($currentRecord,"WAREHOUSE_LOCATION") ."', 
                        COMMODITY_CODE = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"COMMODITY_CODE")) ." 
			WHERE PALLET_ID = '" . $saveHandler->ReturnUpdateField($currentRecord, "PALLET_ID") . "'";

		

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
		$sql = "DELETE FROM PRODUCT_TEMP WHERE PALLET_ID = " . $saveHandler->ReturnDeleteField($currentRecord);

		// Now we execute this query
		ora_parse($cursor, $sql);
                ora_exec($cursor);

	}
}

$saveHandler->CompleteSave();
ora_close($cursor);
ora_logoff($conn);

?>

