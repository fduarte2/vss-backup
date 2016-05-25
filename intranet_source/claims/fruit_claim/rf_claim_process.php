<?
  // All POW files need this session file included
  include("pow_session.php");

  include("utility.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Claim";
  $area_type = "CLAI";

  $user = $userdata['username'];

  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf";

  $claim_id = $HTTP_POST_VARS['claim_id'];
  $cargo_type = $HTTP_POST_VARS['cargo_type'];
  $season = $HTTP_POST_VARS['season'];
  $customer_id = $HTTP_POST_VARS['customer_id'];
  $invoice_num = $HTTP_POST_VARS['invoice_num'];
  $invoice_date = $HTTP_POST_VARS['invoice_date'];
  $received_date = $HTTP_POST_VARS['received_date'];
  $check_num = $HTTP_POST_VARS['check_num'];
  $check_date = $HTTP_POST_VARS['check_date'];
  $amt_paid = $HTTP_POST_VARS['amt_paid'];
  $status = $HTTP_POST_VARS['status'];
  $ispct = $HTTP_POST_VARS['ispct'];

  if ($invoice_date==""){
        $invoice_date = "null";
  }else{
        $invoice_date = date('m/d/Y', strtotime($invoice_date));
        $invoice_date = "to_date('$invoice_date','mm/dd/yyyy')";
  }

  if ($received_date==""){
        $received_date = "null";
  }else{
        $received_date = date('m/d/Y', strtotime($received_date));
        $received_date = "to_date('$received_date','mm/dd/yyyy')";
  }
 
  if ($check_date==""){
	$check_date = "null";
  }else{
	$check_date = date('m/d/Y', strtotime($check_date));
	$check_date = "to_date('$check_date','mm/dd/yyyy')";
  }

  if ($amt_paid ==""){
        $amt_paid = "null";
  }

  if ($HTTP_POST_VARS['save'] <>""){
        if ($claim_id <>""){
                $sql = "update $claim_header set season = '$season', 
						customer_id = $customer_id,
												claim_cargo_type = '$cargo_type',
                                                invoice_num = '$invoice_num',
                                                invoice_date = $invoice_date,
                                                receive_date = $received_date,
                                                check_num = '$check_num',
                                                check_date = $check_date,
                                                amt_paid = $amt_paid,
						status = '$status',
						ispercentage = '$ispct'
                        where claim_id = $claim_id";
        }else{
                $sql = "select max(claim_id) from $claim_header";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $claim_id = ora_getcolumn($cursor, 0);
			if ($claim_id <>""){
				$claim_id ++;
			}else{
				$claim_id = 10000;
			}
                }else{
			$claim_id = 10000;
		}
                $sql = "insert into $claim_header
                        (claim_id, season, customer_id, invoice_num,invoice_date,receive_date,check_num, check_date,amt_paid,
			 status, entry_date, entry_by, ispercentage, claim_cargo_type) values
                        ($claim_id, '$season', $customer_id, '$invoice_num', $invoice_date,
			 $received_date,'$check_num', $check_date, $amt_paid, '$status',
			sysdate, '$user','$ispct','$cargo_type')";
        }

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        if ($status=="C"){
		$sql = "update $claim_body set status = '$status' 
			where claim_id = $claim_id and (status is null or status <>'D')";
		$statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
	}
  }

  if ($HTTP_POST_VARS['delete'] <>""){
        if ($claim_id <>""){
                $sql = "update $claim_header set status = 'D' where claim_id = $claim_id";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);

                $sql = "update $claim_body set status = 'D' where claim_id = $claim_id";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);

        }
        $system = "";
        $claim_id = "";
        $customer_id = "";
        $invoice_num = "";
        $invoice_date = "";
        $received_date = "";
        $check_num = "";
        $amt_paid = "";
  }

  ora_close($cursor);
  ora_logoff($conn);
  header ("location: rf_claim.php?claim_id=$claim_id");

?>
