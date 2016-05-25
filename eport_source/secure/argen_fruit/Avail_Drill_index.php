<?
	include("v2_valid.php");
	include("argen_fruit_db_def.php");
	include("set_values.php");
	$user = $HTTP_COOKIE_VARS["eport_user_v2"];
	if($user == ""){
      header("Location: ../argen_fruit_login.php");
      exit;
   }

	// NOTE:  these variables are also used in the child page.
	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$var = $HTTP_GET_VARS['var'];
//	$category = $HTTP_GET_VARS['category'];
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}

	$auth_exp = PageUserCheck($user, "ARGEN_FRUIT_EXPED", $rfconn);
	$auth_port = PageUserCheck($user, "ARGEN_FRUIT_PORT", $rfconn);

	// these values come from the child page.  The logic is being done here because I need to page-redirect afterwards,
	// which I can't do if the page output has already been passed.
	$submit = $HTTP_POST_VARS['submit'];
	if($auth_exp == "WRITE" && $submit == "Commit"){
		$order_num = strtoupper($HTTP_POST_VARS['order_num']);
		$order_num = str_replace(" ", "", $order_num);
		$drop_order_num = $HTTP_POST_VARS['drop_order_num'];
//		echo "drop:".$drop_order_num."<br>";
		$patacust = $HTTP_POST_VARS['patacust'];
		$maxline = $HTTP_POST_VARS['maxline'];
		if($drop_order_num != ""){
			$order_num = $drop_order_num;
		}

		$commodity = $HTTP_POST_VARS['commodity'];
		$voucher = str_replace("'", "`", $HTTP_POST_VARS['voucher']);
		$voucher = str_replace("\\", "", $voucher);
		$variety = str_replace("'", "`", $HTTP_POST_VARS['variety']);
		$variety = str_replace("\\", "", $variety);
		$import_code = str_replace("'", "`", $HTTP_POST_VARS['import_code']);
		$import_code = str_replace("\\", "", $import_code);
		$import_size = str_replace("'", "`", $HTTP_POST_VARS['import_size']);
		$import_size = str_replace("\\", "", $import_size);
		$cartons = str_replace("'", "`", $HTTP_POST_VARS['cartons']);
		$cartons = str_replace("\\", "", $cartons);
		$qty_entered = str_replace("'", "`", $HTTP_POST_VARS['qty_entered']);
		$qty_entered = str_replace("\\", "", $qty_entered);
		$include = $HTTP_POST_VARS['include'];

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGENFRUIT_ORDER_HEADER
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);

		// make sure they've entered something
		$bad_message = "";
		if($order_num == ""){
			$bad_message .= "An Order# must be entered.<br>";
		}
		if($patacust == "" && ociresult($stid, "THE_COUNT") <= 0){
			$bad_message .= "A Customer must be selected for New Orders.<br>";
		} elseif(ociresult($stid, "THE_COUNT") >= 1){
			$sql = "SELECT CUSTOMER_ID
					FROM ARGENFRUIT_ORDER_HEADER
					WHERE ORDER_NUM = '".$order_num."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$patacust = ociresult($stid, "CUSTOMER_ID");
		}


		$any_select_flag = false;
		for($i = 0; $i <= $maxline; $i++){
			if($include[$i] == "Y"){
				$any_select_flag = true;
			}
		}
		if($any_select_flag == false){
			$bad_message .= "At least 1 line must be selected to Create or Add to an order.<br>";
		}

		$sql = "SELECT STATUS
				FROM ARGENFRUIT_ORDER_HEADER
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid) && (ociresult($stid, "STATUS") != "1" && ociresult($stid, "STATUS") != "2")){
			$bad_message .= "This screen can only edit orders in Submitted or Draft status.<br>";
		}

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGENFRUIT_ORDER_DETAIL
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid) && (ociresult($stid, "THE_COUNT") >= "10")) {
			$bad_message .= "The selected order already had 10 entries.  Cannot add another.<br>";
		}

		// assuming the aobve checks passed, determine if this order exists...

		if($bad_message == ""){
/*			$sql = "SELECT COUNT(*) THE_COUNT
					FROM ARGENFRUIT_ORDER_HEADER
					WHERE ORDER_NUM = '".$order_num."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_COUNT") >= 1){*/
				$bad_message .= ValidateAndMaybeSave($order_num, $patacust, $maxline, $commodity, $voucher, $variety, $import_code, $import_size, $cartons, $qty_entered, $include, $user, $rfconn);
/*			} else {
				$bad_message .= ValidateAndMaybeSaveNew($order_num, $cust, $maxline, $comm, $voucher, $variety, $import_code, $import_size, $cartons, $include, $rfconn);
			}*/
		}

		if($bad_message != ""){
			//show this farther in
//			echo "<font color=\"#FF0000\">Entry could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		} else {
			header("Location: ./order_edit_index.php?order_num=".$order_num);
		}
	}


?>

<html>
<head>
<title>Eport of Wilmington - Argentine Fruit Inventory</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("/var/www/secure/argen_fruit/header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "650">
		  <? include("/var/www/secure/argen_fruit/leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
		<? if($bad_message != ""){ echo "<font color=\"#FF0000\">Entry could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>"; } ?>
<? 
	if($auth_exp !== false || $auth_port !== false){
		include("Avail_Drill.php");
	} else {
		echo "<font color=\"#FF0000\"><b>User not authorized to view this page.</b></font>";
	}
?>
	 <? include("/var/www/secure/argen_fruit/footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>









<?

function ValidateAndMaybeSave($order_num, $cust, $maxline, $comm, $voucher, $variety, $import_code, $import_size, $cartons, $qty_entered, $include, $user, $rfconn){
	// note:  this function only gets called if none of the header checks bombed.
	// so the SQL statements don't have to worry about invalid execution, as all checks are done before we get to them.
	$return = "";
	for($i = 0; $i <= $maxline; $i++){
/*		echo "check<br>";
		echo "$i: ".$i."<br>";
		echo "order: ".$order_num."<br>";
		echo "cust: ".$cust."<br>";
		echo "maxline: ".$maxline."<br>";
		echo "comm: ".$comm[$i]."<br>";
		echo "voucher: ".$voucher[$i]."<br>";
		echo "variety: ".$variety[$i]."<br>";
		echo "import_code: ".$import_code[$i]."<br>";
		echo "import_size: ".$import_size[$i]."<br>";
		echo "cartons: ".$cartons[$i]."<br>";
		echo "include: ".$include[$i]."<br>";*/
		if($include[$i] == "Y"){
			$available = GetAvailableCartons($voucher[$i], $variety[$i], $import_code[$i], $import_size[$i], $order_num, $user, $rfconn);
			if($available < $qty_entered[$i]){
				$return .= "On line ".$i.", the amount requested (".$qty_entered[$i].") is more than the amount available (".$available.")<br>";
			}
		}
	}

	if($return != ""){
		// there was any error.  return message, stop process.
		return $return;
	} else {
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGENFRUIT_ORDER_HEADER
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			// order didn't exist yet
			$sql = "INSERT INTO ARGENFRUIT_ORDER_HEADER
						(STATUS,
						ORDER_NUM,
						CUSTOMER_ID)
					VALUES
						('1',
						'".$order_num."',
						'".$cust."')";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
		} else {
			// order existed, do nothing
		}

		//
		for($i = 0; $i <= $maxline; $i++){
/*			echo "upd<br>";
			echo "$i: ".$i."<br>";
			echo "order: ".$order_num."<br>";
			echo "cust: ".$cust."<br>";
			echo "maxline: ".$maxline."<br>";
			echo "comm: ".$comm[$i]."<br>";
			echo "voucher: ".$voucher[$i]."<br>";
			echo "variety: ".$variety[$i]."<br>";
			echo "import_code: ".$import_code[$i]."<br>";
			echo "import_size: ".$import_size[$i]."<br>";
			echo "cartons: ".$cartons[$i]."<br>";
			echo "include: ".$include[$i]."<br>"; */
			if($include[$i] == "Y"){
				$sql = "SELECT ORDER_DETAIL
						FROM ARGENFRUIT_ORDER_DETAIL
						WHERE ORDER_NUM = '".$order_num."'
							AND VOUCHER_NUM = '".$voucher[$i]."'
							AND VARIETY = '".$variety[$i]."'
							AND COMMODITY_CODE = '".$comm[$i]."'
							AND (IMPORT_CODE IS NULL OR IMPORT_CODE = '".$import_code[$i]."')
							AND (IMPORT_SIZE IS NULL OR IMPORT_SIZE = '".$import_size[$i]."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				if(ocifetch($short_term_data)){
					// this already had an entry, update it
//					$available = GetAvailableCartons($voucher[$i], $variety[$i], $import_code[$i], $import_size[$i], $order_num, $user, $rfconn);

					$sql = "UPDATE ARGENFRUIT_ORDER_DETAIL
							SET CARTONS = CARTONS + ".$qty_entered[$i]."
							WHERE ORDER_NUM = '".$order_num."'
								AND ORDER_DETAIL = '".ociresult($short_term_data, "ORDER_DETAIL")."'";
//					echo $sql."<br>";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				} else {
					$sql = "SELECT NVL(MAX(ORDER_DETAIL), -1) THE_NUM
							FROM ARGENFRUIT_ORDER_DETAIL
							WHERE ORDER_NUM = '".$order_num."'";
					$short_term_data2 = ociparse($rfconn, $sql);
					ociexecute($short_term_data2);
					ocifetch($short_term_data2);
					$next_num = ociresult($short_term_data2, "THE_NUM") + 1;

					$sql = "INSERT INTO ARGENFRUIT_ORDER_DETAIL
								(ORDER_NUM,
								ORDER_DETAIL,
								VARIETY,
								IMPORT_CODE,
								IMPORT_SIZE,
								VOUCHER_NUM,
								COMMODITY_CODE,
								CARTONS)
							VALUES
								('".$order_num."',
								'".$next_num."',
								'".$variety[$i]."',
								'".$import_code[$i]."',
								'".$import_size[$i]."',
								'".$voucher[$i]."',
								'".$comm[$i]."',
								'".$qty_entered[$i]."')";
//					echo $sql."<br><br>";
					$update = ociparse($rfconn, $sql);
					ociexecute($update);
				}
			}
		}
	}
}


function GetAvailableCartons($voucher, $variety, $import_code, $import_size, $order_num, $user, $rfconn){
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
	$sql = "SELECT SUM(QTY_IN_HOUSE) THE_SUM
			FROM CARGO_TRACKING
			WHERE BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$IH = ociresult($short_term_data, "THE_SUM");

	// the amount of pallets already reerved on other orders...
//				AND (AOD.IMPORT_CODE = '".$import_code."' OR AOD.IMPORT_SIZE = '".$import_size."')
//				AOD.ORDER_NUM != '".$order_num."'
	$sql = "SELECT SUM(CARTONS) THE_SUM
			FROM ARGENFRUIT_ORDER_DETAIL AOD, ARGENFRUIT_ORDER_HEADER AOH
			WHERE AOD.VOUCHER_NUM = '".$voucher."'
				AND AOD.VARIETY = '".$variety."'
				AND AOH.STATUS IN ('1', '2', '3')
				AND AOD.ORDER_NUM = AOH.ORDER_NUM
				AND (IMPORT_CODE IS NULL OR IMPORT_CODE = '".$import_code."')
				AND (IMPORT_SIZE IS NULL OR IMPORT_SIZE = '".$import_size."')";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$reserved = ociresult($short_term_data, "THE_SUM");

	// this one is a bit trickier... we want all pallets scanned-out on orders that aren't yet done.
	// we need this value to "add back into" the values above above, since said pallet, while not being "in house",
	// DOES cancel 1 of the "reserved" pallets.
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
//				AND ORDER_NUM != '".$order_num."'
	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')
				AND CT.BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CT.CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR CT.BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL)
				AND CA.ORDER_NUM IN
					(SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER
					WHERE STATUS IN ('1', '2', '3')
					)";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$scanned_on_reserve = ociresult($short_term_data, "THE_SUM");

	$total_available = ($IH + $scanned_on_reserve) - $reserved;

	return $total_available;
}

?>