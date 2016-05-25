<?php

include_once("JSON.php");
$json = new Services_JSON();

$container_id = strtoupper($_GET["value1"]);

$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
 
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }

  $short_term_data_cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);
  $short_term_data_cursor_2 = ora_open($conn);
  $short_term_data_cursor_3 = ora_open($conn);

   // check for any containers that are not gated out i.e. gatepass_issued is null
	$sql = "SELECT COUNT(*) THE_IN_PORT_COUNT
			FROM CANADIAN_LOAD_RELEASE
			WHERE GATEPASS_ISSUED IS NULL AND UPPER(REPLACE(CONTAINER_NUM, ' ')) = '".$container_id."'";
	//echo $sql;
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	// There are no containers not gated out i.e all containers are gated out or there are no containers with this number. 
	if($short_term_data_row['THE_IN_PORT_COUNT'] == 0){
			$message = "NOT IN SYSTEM OR GATED OUT – DO NOT OPEN";
	}
	if($short_term_data_row['THE_IN_PORT_COUNT'] > 1){
		// ensure that the multiple rows are not multiple rows on the same vessel
		$sql_2 = "SELECT COUNT( DISTINCT ARRIVAL_NUM) THE_ARRIVAL_NUMS 
				  FROM CANADIAN_LOAD_RELEASE
				  WHERE GATEPASS_ISSUED IS NULL AND UPPER(REPLACE(CONTAINER_NUM, ' ')) = '".$container_id."'";
		$ora_success = ora_parse($short_term_data_cursor_2, $sql_2);
		$ora_success = ora_exec($short_term_data_cursor_2, $sql_2);
		if ($short_term_data_row_2['THE_ARRIVAL_NUMS'] > 1) {
			$message = "MULTIPLE VESSELS – DO NOT OPEN";
		}
		else {
			$sql_3 = "SELECT COUNT(*) IF_RELEASED
					FROM CANADIAN_LOAD_RELEASE
					WHERE GATEPASS_ISSUED IS NULL AND UPPER(REPLACE(CONTAINER_NUM, ' ')) = '".$container_id."'
					AND (AMS_STATUS IS NULL OR LINE_STATUS IS NULL OR BROKER_STATUS IS NULL)";
					// echo $sql_3;
			$ora_success = ora_parse($short_term_data_cursor_3, $sql_3);
			$ora_success = ora_exec($short_term_data_cursor_3, $sql_3);
			ora_fetch_into($short_term_data_cursor_3, $short_term_data_row_3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row_3['IF_RELEASED'] == 0){
				$message = "RELEASED - OK TO PROCEED";
			} else {
				$message = "ON HOLD – DO NOT OPEN";
			}
		}
	}
	if($short_term_data_row['THE_IN_PORT_COUNT'] == 1){	

		$sql = "SELECT COUNT(*) IF_RELEASED
				FROM CANADIAN_LOAD_RELEASE
				WHERE GATEPASS_ISSUED IS NULL AND UPPER(REPLACE(CONTAINER_NUM, ' ')) = '".$container_id."'
						AND AMS_STATUS IS NOT NULL AND LINE_STATUS IS NOT NULL AND BROKER_STATUS IS NOT NULL";
		//echo $sql;
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['IF_RELEASED'] == 1){
			$message = "RELEASED - OK TO PROCEED";
		} else {
			$message = "ON HOLD – DO NOT OPEN";
		}
	}


	$sql = "INSERT INTO CANADIAN_RELEASE_CHECKED
				(CONTAINER_ID,
				CHECKER,
				DEVICE,
				TIME_CHECKED,
				RESPONSE_GIVEN)
			VALUES
				('".$container_id."',
				'IPHONE',
				'IPHONE',
				SYSDATE,
				'".$message."')";
	$ora_success = ora_parse($modify_cursor, $sql);
	$ora_success = ora_exec($modify_cursor, $sql);

	echo $json->encode($message);


 
 ?>

