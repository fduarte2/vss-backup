<?
/*
*		Adam Walter, Sep 2014
*
*		Takes a pre-split AMS edi file, and populates the 
*		data to RF for use in canadian releases
*
*
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	include("cron_alert.php");
	SendCronBreakNotify("AMS_EDI_parse.php", "CBP EDI", $rfconn);

	// for each EDI-record that is not yet pushed to the CLR pending/history table, we want to see if it can be.
	$sql = "SELECT CAE.*, TO_CHAR(EDI_DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_EDI_TIME 
			FROM CLR_AMS_350_EDI CAE
			WHERE MOVE_TO_CLR_HIST IS NULL
				AND LLOYD_NUM != 'UNKNOWN'
				AND LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
			ORDER BY AMS350_UNIQ_ID";
	$EDI_Data = ociparse($rfconn, $sql);
	ociexecute($EDI_Data);
	while(ocifetch($EDI_Data)){

		// if the EDI was sent without a container#, we want to treat it as a wildcard.
		if(ociresult($EDI_Data, "CONTAINER_NUM") != ""){
			$container_where = " AND CONTAINER_NUM = '".ociresult($EDI_Data, "CONTAINER_NUM")."' ";
		} else {
			$container_where = " ";
		}

		// if there is no match in the arrival # conversion table at this time, we want to skip this line (for now)
		$sql = "SELECT ARRIVAL_NUM
				FROM CLR_LLOYD_ARRIVAL_MAP
				WHERE LLOYD_NUM = '".ociresult($EDI_Data, "LLOYD_NUM")."'
					AND VOYAGE_NUM = '".ociresult($EDI_Data, "VOYAGE_NUM")."'
					AND ARRIVAL_NUM IS NOT NULL";
		$ARV_Data = ociparse($rfconn, $sql);
		ociexecute($ARV_Data);
		if(!ocifetch($ARV_Data)){
			$ARV_where = " AND 1 = 2 ";
		} else {
			$ARV_where = " AND ARRIVAL_NUM = '".ociresult($ARV_Data, "ARRIVAL_NUM")."' ";
		}

		// set the action code...
		$action_code = "";
		$code_removed = "";
		GetActionType(ociresult($EDI_Data, "EDI_CODE"), $action_code, $code_removed, $rfconn);

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_MAIN_DATA
				WHERE BOL_EQUIV = '".ociresult($EDI_Data, "BOL_EQUIV")."'
					AND LLOYD_NUM = '".ociresult($EDI_Data, "LLOYD_NUM")."'
					AND VOYAGE_NUM = '".ociresult($EDI_Data, "VOYAGE_NUM")."'
					".$container_where."
					".$ARV_where;
		$any_entries_Data = ociparse($rfconn, $sql);
		ociexecute($any_entries_Data);
		ocifetch($any_entries_Data);
		if(ociresult($any_entries_Data, "THE_COUNT") >= 1){
			// we only get into this loop if there exists entries in the CLR table
			$sql = "SELECT * 
					FROM CLR_MAIN_DATA
					WHERE BOL_EQUIV = '".ociresult($EDI_Data, "BOL_EQUIV")."'
						AND LLOYD_NUM = '".ociresult($EDI_Data, "LLOYD_NUM")."'
						AND VOYAGE_NUM = '".ociresult($EDI_Data, "VOYAGE_NUM")."'
						".$container_where."
						".$ARV_where;
			$CLR_Data = ociparse($rfconn, $sql);
			ociexecute($CLR_Data);
			while(ocifetch($CLR_Data)){
				// insert each line...
				$sql = "INSERT INTO CLR_AMS_RELEASE
							(USERNAME,
							COMMENTS,
							ACTION_TYPE,
							ARRIVAL_NUM,
							CUSTOMER_ID,
							BOL_EQUIV,
							EDI_CODE,
							EDI_CODE_TYPE,
							EDI_DATE_TIME,
							CONTAINER_NUM,
							QTY,
							KEY_ID,
							CLR_KEY,
							CLR_UNIQ_AMSHIST_KEY)
						VALUES
							('EDI',
							'".ociresult($EDI_Data, "COMMENTS")."',
							'".$action_code."',
							'".ociresult($CLR_Data, "ARRIVAL_NUM")."',
							'".ociresult($CLR_Data, "CUSTOMER_ID")."',
							'".ociresult($CLR_Data, "BOL_EQUIV")."',
							'".ociresult($EDI_Data, "EDI_CODE")."',
							'".$action_code."',
							TO_DATE('".ociresult($EDI_Data, "THE_EDI_TIME")."', 'MM/DD/YYYY HH24:MI:SS'),
							'".ociresult($CLR_Data, "CONTAINER_NUM")."',
							'".ociresult($EDI_Data, "QTY")."',
							'".ociresult($EDI_Data, "AMS350_UNIQ_ID")."',
							'".ociresult($CLR_Data, "CLR_KEY")."',
							CLR_UNIQ_AMSHIST_SEQ.NEXTVAL)";
				$insert_release = ociparse($rfconn, $sql);
				ociexecute($insert_release);
			}

			$sql = "UPDATE CLR_AMS_350_EDI
					SET MOVE_TO_CLR_HIST = SYSDATE
					WHERE AMS350_UNIQ_ID = '".ociresult($EDI_Data, "AMS350_UNIQ_ID")."'";
			$clear_edi = ociparse($rfconn, $sql);
			ociexecute($clear_edi);
		}
	}
	UpdateCronBreakNotify("push_CLREDI_to_hist_table.php", "CBP EDI", $rfconn);









function GetActionType($edi_code, &$action_code, &$code_removed, $rfconn){
	// function to determine, and return, what exactly this line of the EDI does
	$sql = "SELECT * FROM CANADIAN_AMSEDI_CODES
			WHERE EDI_CODE = '".$edi_code."'";
//	if($edi_code == "7I"){
//		echo $sql."\n";
//	}
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$action_code = ociresult($short_term_data, "ACTION");
		$code_removed = ociresult($short_term_data, "CODE_REMOVED");
//		if($edi_code == "7I"){
//			echo "Code 7I - code removed DB: ".ociresult($short_term_data, "CODE_REMOVED")."    code removed var: ".$code_removed."\n";
//		}
	} else {
		// treat code as ignore until further notice is given
		$action_code = "TO BE RESOLVED";
		$code_removed = "";
		$sql = "INSERT INTO CANADIAN_AMSEDI_CODES
					(EDI_CODE,
					ACTION,
					USERNAME)
				VALUES
					('".$edi_code."',
					'TO BE RESOLVED',
					'cron')";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,archive@port.state.de.us";
		$mailheaders = "FROM:PoW@NoReplies.com";
		mail($mailTO, "AMS-EDI new code (".$edi_code.") found", "", $mailheaders);
	
	}
}
