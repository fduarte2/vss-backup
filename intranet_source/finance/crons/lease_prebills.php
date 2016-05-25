<?
/*
*		Adam Walter, Feb 2014
*
*		Auto-generation of Transfer bills from RF system
*
*****************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$user = $HTTP_POST_VARS['user'];
	if($user == ""){
		$user = "cron";
	}
	if($HTTP_POST_VARS['cust'] != ""){
		$extra_where_clause .= " AND CUSTOMERID = '".$HTTP_POST_VARS['cust']."'";
	}
	if($HTTP_POST_VARS['lease_id'] != ""){
		$extra_where_clause .= " AND CONTRACTID = '".$HTTP_POST_VARS['lease_id']."'";
	}
//	if($HTTP_POST_VARS['lr_num'] != ""){
//		$extra_where_clause .= " AND CA.ARRIVAL_NUM = '".$HTTP_POST_VARS['lr_num']."'";
//	}

//	$billed_array = array();

	$sql = "SELECT NVL(MAX(BILLING_NUM), 301500000) THE_MAX
			FROM BILL_HEADER";
	$billnum = ociparse($bniconn, $sql);
	ociexecute($billnum);
	ocifetch($billnum);
	$billing_num = ociresult($billnum, "THE_MAX");

	$sql = "SELECT * FROM NONSTORAGE_RATE
			WHERE (BILL_TYPE IS NULL OR BILL_TYPE = 'LEASE')
				AND ACTIVE_LEASE = 'Y'
				AND BILL_ORDER = '1'
				AND SCANNEDORUNSCANNED = 'UNSCANNED'
				".$extra_where_clause."
			ORDER BY CONTRACTID, ASSET_CODE";
	$rates = ociparse($bniconn, $sql);
	ociexecute($rates, OCI_NO_AUTO_COMMIT);
	while(ocifetch($rates)){
		if(ociresult($rates, "RATE") != 0){
			$billing_num++;
//			array_push($billed_array, $billing_num);
			$billing_det = 1;

			if(ociresult($rates, "UNIT") == "MONTH"){
				$desc_db = "LEASE BILL FOR MONTH OF ".strtoupper(date('F Y')).", ".ociresult($rates, "FREEFORM_DESCRIPTION");
			} else {
				$desc_db = "LEASE BILL FOR YEAR OF ".date('Y').", ".ociresult($rates, "FREEFORM_DESCRIPTION");
			}
			
			$sql = "INSERT INTO BILL_HEADER
						(CUSTOMER_ID,
						COMMODITY_CODE,
						SERVICE_CODE,
						BILLING_NUM,
						CREATED_BY,
						SERVICE_STATUS,
						ARRIVAL_NUM,
						SERVICE_DATE,
						BILLING_TYPE,
						BILLED_FROM_SYSTEM)
					VALUES
						('".ociresult($rates, "CUSTOMERID")."',
						'".ociresult($rates, "COMMODITYCODE")."',
						'".ociresult($rates, "BNI_SERVICE_CODE")."',
						'".$billing_num."',
						'".$user."',
						'PREINVOICE',
						'0',
						TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'),1,date('Y')))."', 'MM/DD/YYYY'),
						'LEASE',
						'BNI')";
			$insert_lease = ociparse($bniconn, $sql);
			ociexecute($insert_lease, OCI_NO_AUTO_COMMIT);

			$sql = "INSERT INTO BILL_DETAIL
						(BILLING_NUM,
						DETAIL_LINE,
						SERVICE_AMOUNT,
						SERVICE_QTY,
						SERVICE_UNIT,
						SERVICE_RATE,
						SERVICE_RATE_UNIT,
						SERVICE_DESCRIPTION,
						SERVICE_DATE_DETAIL,
						ASSET_CODE)
					VALUES
						('".$billing_num."',
						'".$billing_det."',
						'".ociresult($rates, "RATE")."',
						'1',
						'".ociresult($rates, "UNIT")."',
						'".ociresult($rates, "RATE")."',
						'".ociresult($rates, "UNIT")."',
						'".$desc_db."',
						TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'),1,date('Y')))."', 'MM/DD/YYYY'),
						'".ociresult($rates, "ASSET_CODE")."')";
//			echo $sql."<br>";
			$insert_lease = ociparse($bniconn, $sql);
			ociexecute($insert_lease, OCI_NO_AUTO_COMMIT);

			$multibill = true;
			while($multibill){
				$billing_det++;
//							AND ACTIVE_LEASE = 'Y'
				$sql = "SELECT * FROM NONSTORAGE_RATE
						WHERE (BILL_TYPE IS NULL OR BILL_TYPE = 'LEASE')
							AND BILL_ORDER = '".$billing_det."'
							AND SCANNEDORUNSCANNED = 'UNSCANNED'
							AND CONTRACTID = '".ociresult($rates, "CONTRACTID")."'
						ORDER BY RATEPRIORITY";
				$loop_rates = ociparse($bniconn, $sql);
				ociexecute($loop_rates, OCI_NO_AUTO_COMMIT);
				if(!ocifetch($loop_rates)){
					// last line was reached.  bill over.
					$multibill = false;
				} else {
					if(ociresult($loop_rates, "ACTIVE_LEASE") == "Y"){
						$sql = "INSERT INTO BILL_DETAIL
									(BILLING_NUM,
									DETAIL_LINE,
									SERVICE_AMOUNT,
									SERVICE_QTY,
									SERVICE_UNIT,
									SERVICE_RATE,
									SERVICE_RATE_UNIT,
									SERVICE_DATE_DETAIL,
									SERVICE_DESCRIPTION)
								VALUES
									('".$billing_num."',
									'".$billing_det."',
									'".ociresult($loop_rates, "RATE")."',
									'1',
									'".ociresult($loop_rates, "UNIT")."',
									'".ociresult($loop_rates, "RATE")."',
									'".ociresult($loop_rates, "UNIT")."',
									TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'),1,date('Y')))."', 'MM/DD/YYYY'),
									'".$desc_db."')";
						$insert_lease = ociparse($bniconn, $sql);
						ociexecute($insert_lease, OCI_NO_AUTO_COMMIT);
					} else { // line marked as inactive
						// do nothing, proceed to next line
					}
				}
			}
		} else {
			// for leases, there is nothing to "do" for a rate of zero
		}
	}
	ocicommit($bniconn);
