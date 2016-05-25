<?
/*
*		Adam Walter, feb 2011
*
*	Populates a CT-clone table with Start-of-day walmart data
*	So inventory can do daily calculations
*
*	Quite possibly, the shortest code I've ever written (correctly)
*****************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);


	$sql = "INSERT INTO WALMART_CT_START_OF_DAY
			(SELECT CT.*, SYSDATE
			FROM CARGO_TRACKING CT
			WHERE RECEIVER_ID = '3000'
			AND DATE_RECEIVED IS NOT NULL
			AND ARRIVAL_NUM IN
				(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
			AND QTY_IN_HOUSE > 0)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
