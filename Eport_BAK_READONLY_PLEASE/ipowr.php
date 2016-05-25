<?php

include_once("JSON.php");
$json = new Services_JSON();

$container_num = strtoupper($_GET["value1"]);

    $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
 
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }

  $Short_Term_Cursor = ora_open($conn);

   // if records present
	$sql = "SELECT NVL(to_char(AMS_STATUS, 'DD-MON-YYYY HH24:MI:SS'), 'HOLD') RELSTAT  FROM CANADIAN_LOAD_RELEASE where replace(container_num, ' ') = '".$container_num."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
   if (!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	 echo $json->encode("UNKNOWN CONTAINER DO NOT OPEN DOOR");
	   echo $row['RELSTAT'];
	} else if (strcmp($row['RELSTAT'], "HOLD") == 0) {
		echo $json->encode("ON HOLD - DO NOT OPEN DOOR");
	}
	else {
		 echo $json->encode("RELEASED - OK TO PROCEED");
      }
 ?>

