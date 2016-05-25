<?
/* Adam Walter, March 2008
*
*
*
*	This program doesn't do anything port-related; However, I have found in my travels,
*	That I often wonder exactly WHAT a DB is returning in a specific DB call,
*	And how PHP interprets the result.  So this is a small script, designed to be
*	Run from a Linux command line, that displays the result of an SQL
*	Statement to the screen.
*
*	Just choose your DB cursor, statement, and you're good to go.
*************************************************************************************/

//   $conn_bni = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);
   $cursor_bni2 = ora_open($conn_bni);
   $cursor_bni3 = ora_open($conn_bni);

   $conn_rf = ora_logon("SAG_OWNER@RF.TEST", "RFOWNER");
//   $conn_rf = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn_rf < 1){
        printf("Error logging on to the RF Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   $cursor_rf = ora_open($conn_rf);
   $cursor_rf2 = ora_open($conn_rf);
   $cursor_rf3 = ora_open($conn_rf);



	$sql = "SELECT * FROM RF_BILLING_EXCEPTIONS";
	$ora_success = ora_parse($cursor3, $sql);
	$ora_success = ora_exec($cursor3);
	while(ora_fetch_into($cursor3, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '0300240021'
				AND RECEIVER_ID = '175'
				AND ARRIVAL_NUM = '10051'
				AND COMMODITY_CODE = '8060'
				AND ".$row['WHERE_CLAUSE'];
		echo $sql;
		$ora_success = ora_parse($cursor4, $sql);
		$ora_success = ora_exec($cursor4);
		if(ora_fetch_into($cursor4, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "TRUE";
		} else {
			echo "FALSE";
		}
	}
	print_r($row);

ora_close($cursor_bni);
ora_close($cursor_bni2);
ora_close($cursor_bni3);
ora_close($cursor_rf);
ora_close($cursor_rf2);
ora_close($cursor_rf3);
