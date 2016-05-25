<?
/*
*		Adam Walter, Dec 2014
*
*	cront hat attempts to auto-join trucks to their cargo in CLR
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT PORT_ID
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID NOT IN
				(SELECT TRUCK_PORT_ID FROM CLR_TRUCK_MAIN_JOIN)
				AND SEAL_TIME IS NOT NULL";
//	echo $sql."\n";
	$trucks = ociparse($rfconn, $sql);
	ociexecute($trucks);
	while(ocifetch($trucks)){
		$truck_ID = ociresult($trucks, "PORT_ID");
		$sql = "SELECT DISTINCT CT.BOL, NVL(CONTAINER_ID, 'NC') THE_CONT, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CLR_TRUCK_LOAD_RELEASE CTLR
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CA.SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = TRIM(CTLR.CLEM_ORDER_NUM)
					AND CTLR.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CTLR.CUSTOMER_ID = CA.CUSTOMER_ID
					AND CTLR.PORT_ID = '".$truck_ID."'
				ORDER BY CT.ARRIVAL_NUM, BOL, NVL(CONTAINER_ID, 'NC')";
//	echo $sql."\n";
		$bol_data = ociparse($rfconn, $sql);
		ociexecute($bol_data);
		while(ocifetch($bol_data)){
			$sql = "SELECT CLR_KEY
					FROM CLR_MAIN_DATA
					WHERE BOL_EQUIV = '".ociresult($bol_data, "BOL")."'
						AND ARRIVAL_NUM = '".ociresult($bol_data, "ARRIVAL_NUM")."'
						AND CONTAINER_NUM = '".ociresult($bol_data, "THE_CONT")."'";
//	echo $sql."\n";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$CLR_pass_value = ociresult($short_term_data, "CLR_KEY");

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CLR_TRUCK_MAIN_JOIN
					WHERE TRUCK_PORT_ID = '".$truck_ID."'
						AND MAIN_CLR_KEY = '".$CLR_pass_value."'";
//	echo $sql."\n";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0 && $CLR_pass_value != ""){
				$make_join = true;
			} else {
				$make_join = false;
			}

			if($make_join){
				$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
							(MAIN_CLR_KEY,
							TRUCK_PORT_ID)
						VALUES
							('".$CLR_pass_value."',
							'".$truck_ID."')";
//	echo $sql."\n";
				$insert = ociparse($rfconn, $sql);
				ociexecute($insert);
			}
		}
	}