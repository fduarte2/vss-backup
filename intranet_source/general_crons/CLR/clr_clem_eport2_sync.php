<?
/*
*		Adam Walter, Oct 2014
*
*		Generation of Labor preinvoices from BNI system
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rfconn));
		exit;
	}
	
	$sql = "SELECT DISTINCT ORDERNUM, ORDERSTATUSID
			FROM DC_ORDER
				WHERE ZUSER4 IS NULL
			ORDER BY ORDERNUM";
	$orders = ociparse($rfconn, $sql);
	ociexecute($orders);
	while(ocifetch($orders)){ // if there are no orders, script is done already.
		$sql = "SELECT BORDERCROSSING_ID
				FROM CLR_BORDERCROSSING CLRB, DC_ORDER DCO
				WHERE DCO.ORDERNUM = '".ociresult($orders, "ORDERNUM")."'
					AND DCO.PARSPORTOFENTRYNUM = CLRB.PARSPORTOFENTRYNUM";
		$border_check = ociparse($rfconn, $sql);
		ociexecute($border_check);
		if(ocifetch($border_check)){
			$bordernum = ociresult($border_check, "BORDERCROSSING_ID");
		} else {
			$bordernum = "";
		}






		// for CLR_TRUCK_LOAD_RELEASE is an insert needed?
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'";
		$upd_check = ociparse($rfconn, $sql);
		ociexecute($upd_check);
		ocifetch($upd_check);
		if(ociresult($upd_check, "THE_COUNT") >= 1 || ociresult($orders, "ORDERSTATUSID") == "1" || ociresult($orders, "ORDERSTATUSID") == "2" || 
				ociresult($orders, "ORDERSTATUSID") == "3" || ociresult($orders, "ORDERSTATUSID") == "10"){
			// do nothing... yet
		} else {
			$sql = "INSERT INTO CLR_TRUCK_LOAD_RELEASE
						(PORT_ID,
						DRIVER_LIC_NUM,
						DRIVER_LIC_STATE,
						PICKUP_TYPE,
						TRUCK_LIC_STATE,
						TRAIL_REEF_PLATE_STATE,
						CLEM_ORDER_NUM)
					(SELECT NVL(MAX(PORT_ID), 0) + 1,
						'NA',
						'',
						'BREAKBULK',
						'',
						'',
						'".ociresult($orders, "ORDERNUM")."'
					FROM CLR_TRUCK_LOAD_RELEASE)";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);
		}








		// from EPORT2, the T_E NUMBER is in the "Trailer#" field.  ARGH
		$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE CTLR
				SET (DRIVER_NAME, TRUCKING_COMPANY, TRAIL_REEF_PLATE_NUM, TRUCK_TE_NUM, BORDER_CROSSING, SEAL_NUM, LAST_CHANGED_BY, LAST_CHANGED_ON, CHECKOUT, ARRIVAL_NUM, CUSTOMER_ID) = 
					(SELECT NVL(DRIVERNAME, 'From Eport2'), TRANSPORTERID, TRUCKTAG, TRAILERNUM, '".$bordernum."', UPPER(SEALNUM), 'cron', SYSDATE, DRIVERCHECKOUTDATETIME, VESSELID, '835'
					FROM DC_ORDER
					WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."')
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);

		$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
				SET CHECKIN = (SELECT DRIVERCHECKINDATETIME FROM DC_ORDER WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."')
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'
					AND CHECKIN IS NULL";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);

		$sql = "UPDATE DC_ORDER
				SET ZUSER4 = 'F'
				WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."'";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);
	}





// with the DC-orders out of the way, now we (try ;p) to sync up the MOR-orders
	$sql = "SELECT DISTINCT ORDERNUM, ORDERSTATUSID, CUST
			FROM MOR_ORDER
				WHERE SYNC_TO_CLR IS NULL
			ORDER BY ORDERNUM";
	$orders = ociparse($rfconn, $sql);
	ociexecute($orders);
	while(ocifetch($orders)){ // if there are no orders, script is done already.
		$sql = "SELECT BORDERCROSSING_ID
				FROM CLR_BORDERCROSSING CLRB, MOR_ORDER DCO, MOR_CONSIGNEE DCC
				WHERE DCO.ORDERNUM = '".ociresult($orders, "ORDERNUM")."'
					AND DCO.CUST = '".ociresult($orders, "CUST")."'
					AND DCO.CUST = DCC.CUST
					AND DCC.CONSIGNEEID = DCO.CONSIGNEEID
					AND DCC.CUSTOMSBROKEROFFICEID = CLRB.BORDERCROSSING_ID";
		$border_check = ociparse($rfconn, $sql);
		ociexecute($border_check);
		if(ocifetch($border_check)){
			$bordernum = ociresult($border_check, "BORDERCROSSING_ID");
		} else {
			$bordernum = "";
		}

		// for CLR_TRUCK_LOAD_RELEASE is an insert needed?
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'";
		$upd_check = ociparse($rfconn, $sql);
		ociexecute($upd_check);
		ocifetch($upd_check);
		if(ociresult($upd_check, "THE_COUNT") >= 1 || ociresult($orders, "ORDERSTATUSID") == "1" || ociresult($orders, "ORDERSTATUSID") == "2" || 
				ociresult($orders, "ORDERSTATUSID") == "3" || ociresult($orders, "ORDERSTATUSID") == "10"){
			// do nothing... yet
		} else {
			$sql = "INSERT INTO CLR_TRUCK_LOAD_RELEASE
						(PORT_ID,
						DRIVER_LIC_NUM,
						DRIVER_LIC_STATE,
						PICKUP_TYPE,
						TRUCK_LIC_STATE,
						TRAIL_REEF_PLATE_STATE,
						CUSTOMER_ID,
						CLEM_ORDER_NUM)
					(SELECT NVL(MAX(PORT_ID), 0) + 1,
						'NA',
						'',
						'BREAKBULK',
						'',
						'',
						'".ociresult($orders, "CUST")."',
						'".ociresult($orders, "ORDERNUM")."'
					FROM CLR_TRUCK_LOAD_RELEASE)";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);
		}


		$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE CTLR
				SET (DRIVER_NAME, TRUCKING_COMPANY, TRAIL_REEF_PLATE_NUM, TRUCK_TE_NUM, BORDER_CROSSING, SEAL_NUM, LAST_CHANGED_BY, LAST_CHANGED_ON, CHECKOUT, ARRIVAL_NUM, CUSTOMER_ID) = 
					(SELECT NVL(DRIVERNAME, 'From Eport2'), TRANSPORTERID, TRUCKTAG, T_AND_E, '".$bordernum."', UPPER(SEALNUM), 'cron', SYSDATE, DRIVERCHECKOUTDATETIME, VESSELID, CUST
					FROM MOR_ORDER
					WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."'
						AND CUST = '".ociresult($orders, "CUST")."')
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'
					AND CUSTOMER_ID = '".ociresult($orders, "CUST")."'";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);

		$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
				SET CHECKIN = (SELECT DRIVERCHECKINDATETIME FROM MOR_ORDER WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."' AND CUST = '".ociresult($orders, "CUST")."')
				WHERE CLEM_ORDER_NUM = '".ociresult($orders, "ORDERNUM")."'
					AND CUSTOMER_ID = '".ociresult($orders, "CUST")."'
					AND CHECKIN IS NULL";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);

		$sql = "UPDATE MOR_ORDER
				SET SYNC_TO_CLR = 'F'
				WHERE ORDERNUM = '".ociresult($orders, "ORDERNUM")."'
					AND CUST = '".ociresult($orders, "CUST")."'";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);
	}
