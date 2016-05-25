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
$conn = ora_logon("LABOR@$lcs", "LABOR");
if($conn < 1){
    printf("Error logging on to the LCS Oracle Server: ");
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
		$sql = "INSERT INTO PRODUCT_TEMP (ID, PRODUCT, LOW_TEMP, HIGH_TEMP, LOW_ALERT, HIGH_ALERT, 
			LOW_WARNING, HIGH_WARNING,DISPLAY_PRODUCT) VALUES (".
                     	GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord, "ID"))  . "," .
                    	"'" . $saveHandler->ReturnInsertField($currentRecord,"PRODUCT") . "'," .
                       	"" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"LOW_TEMP")) . "," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"HIGH_TEMP")) . "," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"LOW_ALERT")) . "," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"HIGH_ALERT")) . "," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"LOW_WARNING")) . "," .
                        "" . GetNullOrNumber($saveHandler->ReturnInsertField($currentRecord,"HIGH_WARNING")) . "," .
                        "'" . $saveHandler->ReturnInsertField($currentRecord,"DISPLAY_PRODUCT") . "'" .
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
		$sql = "UPDATE PRODUCT_TEMP SET 
			PRODUCT = '" . $saveHandler->ReturnUpdateField($currentRecord,"PRODUCT") . "', 
			LOW_TEMP = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"LOW_TEMP")) ." ,
			HIGH_TEMP = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"HIGH_TEMP")) ." ,
                        LOW_ALERT = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"LOW_ALERT")) ." ,
                        HIGH_ALERT = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"HIGH_ALERT")) ." ,
                        LOW_WARNING = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"LOW_WARNING")) ." ,
                        HIGH_WARNING = ".  GetNullOrNumber($saveHandler->ReturnUpdateField($currentRecord,"HIGH_WARNING")).",
			DISPLAY_PRODUCT = '" . $saveHandler->ReturnUpdateField($currentRecord,"DISPLAY_PRODUCT") . "'
			WHERE ID = " . $saveHandler->ReturnUpdateField($currentRecord, "ID");

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
		$sql = "DELETE FROM PRODUCT_TEMP WHERE ID = " . $saveHandler->ReturnDeleteField($currentRecord);

		// Now we execute this query
		ora_parse($cursor, $sql);
                ora_exec($cursor);

	}
}

$saveHandler->CompleteSave();
ora_close($cursor);
ora_logoff($conn);

?>

