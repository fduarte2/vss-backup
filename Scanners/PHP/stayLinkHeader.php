#!/usr/lib/php436/bin/php -q
<?php
/*
*
*	Adam Walter, August - XXX 2008
*
*	MOST MASSIVE PROJECT EVER
*
*	This file is executed upon login of the "it" user on Oscar.
*	It holds the header data for the new "stay-linked" scanner program;
*	Which will (in theory) replace all of our scanners one day.
*	It holds the login functions for Supervisor and Checker,
*		As well as the DB-connect/verification functions and
*		One to refresh-and-retitle the screen
*	(which are required for ALL scanner applications);
*	as well as the Main Menu to choose which *type* of scanner
*	This will be used as.
*
*	Depending on your selection, different *.php files will then be loaded.
*
*	MAX CHARACTER SCREEN WIDTH:  20 char
*
**************************************************************************/



// /* Define STDIN in case if it is not already defined by PHP for some reason */
if (!defined("STDIN"))
{
    define("STDIN", fopen('php://stdin', 'r'));
}


global $rf_conn;
global $lcs_conn;
global $print_super;
$print_super = $argv[1];
global $print_checker;

global $inv_and_fina_mail_info_list;
$inv_and_fina_mail_info_list = "ltreut@port.state.de.us,martym@port.state.de.us,sshoemaker@port.state.de.us,scorbin@port.state.de.us,philhower@port.state.de.us,vfarkas@port.state.de.us,bdempsey@port.state.de.us\r\n";
global $inv_mail_list;
$inv_mail_list = "ltreut@port.state.de.us,martym@port.state.de.us,bdempsey@port.state.de.us\r\n";

global $mailHeaders;
$mailHeaders = "From:  Scanner-Notify\r\n";
$mailHeaders .= "Bcc:  hdadmin@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us\r\n";


function get_emp_no($CID){
/*	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);
	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(GEN1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(GEN1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	return $short_term_data_row['THE_EMP']; */

	return $CID;
}

function database_check($oracle_return, $message){
	global $rf_conn;

	if(!$oracle_return){
	    ora_rollback($rf_conn);
		fresh_screen("Database Error.\n".$message."\nPlease contact TS");
		exit;
	}
}

function remove_badchars($string){
	$string = str_replace(".", "", $string);
	$string = str_replace(",", "", $string);
	$string = str_replace("/", "", $string);
	$string = str_replace("'", "`", $string);
	$string = str_replace("\\", "", $string);
	$string = str_replace("\"", "", $string);
	$string = str_replace("[", "", $string);
	$string = str_replace("]", "", $string);
	$string = str_replace(";", "", $string);
	$string = str_replace("?", "", $string);

	return $string;
}

function strip_to_alphanumeric($Barcode){
	$Barcode = str_replace(".", "", $Barcode);
	$Barcode = str_replace(",", "", $Barcode);
	$Barcode = str_replace("/", "", $Barcode);
	$Barcode = str_replace("'", "", $Barcode);
	$Barcode = str_replace("\\", "", $Barcode);
	$Barcode = str_replace("\"", "", $Barcode);
	$Barcode = str_replace("[", "", $Barcode);
	$Barcode = str_replace("]", "", $Barcode);
	$Barcode = str_replace(";", "", $Barcode);
	$Barcode = str_replace("?", "", $Barcode);
	$Barcode = str_replace("`", "", $Barcode);
	$Barcode = str_replace("~", "", $Barcode);
	$Barcode = str_replace("!", "", $Barcode);
	$Barcode = str_replace("@", "", $Barcode);
	$Barcode = str_replace("#", "", $Barcode);
	$Barcode = str_replace("$", "", $Barcode);
	$Barcode = str_replace("%", "", $Barcode);
	$Barcode = str_replace("^", "", $Barcode);
	$Barcode = str_replace("&", "", $Barcode);
	$Barcode = str_replace("*", "", $Barcode);
	$Barcode = str_replace("(", "", $Barcode);
	$Barcode = str_replace(")", "", $Barcode);
	$Barcode = str_replace("=", "", $Barcode);
	$Barcode = str_replace("+", "", $Barcode);
	$Barcode = str_replace("|", "", $Barcode);
	$Barcode = str_replace("<", "", $Barcode);
	$Barcode = str_replace(">", "", $Barcode);
	$Barcode = str_replace("/", "", $Barcode);
	$Barcode = str_replace(":", "", $Barcode);
	$Barcode = str_replace("{", "", $Barcode);
	$Barcode = str_replace("}", "", $Barcode);

	return $Barcode;
}

function S5_validity_check($string){
	if(!ereg("^([0-9a-zA-Z])+$", $string)) {
		return false;
	} else {
		return true;
	}
}


function fresh_screen($display, $result="good")
{
	global $print_checker;
	global $print_super;

	system("clear");
	if($result == "bad"){
		for ($temp = 0; $temp < 100; $temp++){}
		echo "\x07";
		for ($temp = 0; $temp < 100; $temp++){}
		echo "\x07";
		for ($temp = 0; $temp < 100; $temp++){}
		echo "\x07";
		for ($temp = 0; $temp < 100; $temp++){}
	}
    system("clear");

	$temp = explode("\n", $display, 2);
//	$temp[0] = substr($temp[0], 0, 10)." (".substr($print_super, 0, 6)."/".substr($print_checker, 0, 6).")\n";
	$temp[0] = substr($temp[0], 0, 5)." (".substr($print_super, 0, 6)."/".substr($print_checker, 0, 11).")\n";

	$display = $temp[0].$temp[1];
	echo $display."\n";
}

function scanner_display($text)
{
	echo substr(rtrim($text), 0, 25)."\n";
}

function reconcile_leading_zeroes($Barcode, $Arrival){
	global $rf_conn;
	$user_cursor = ora_open($rf_conn);

	if(substr($Barcode, 0, 1) !== "0"){
		// this pallet never started with a 0, function unneeded
		return;
	} else {
		$sql = "SELECT COUNT(DISTINCT RECEIVER_ID) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($user_cursor, $sql);
		database_check($ora_success, "Leading Zero\nCheck Failed\n(SZ1a)");
		$ora_success = ora_exec($user_cursor, $sql);
		database_check($ora_success, "Leading Zero\nCheck Failed\n(SZ1b)");
		ora_fetch_into($user_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] >= 1){
			// this exact-matched a record.  exit.
			return;
		}

		// now we check for strips... remove the zeroes...
		$stripped_BC = $Barcode;
		while(substr($stripped_BC, 0, 1) === "0"){
			$stripped_BC = substr($stripped_BC, 1);
		}
		// and check THAT against CT
		$sql = "SELECT COUNT(DISTINCT RECEIVER_ID) THE_COUNT
				FROM CARGO_TRACKING_ADDITIONAL_DATA
				WHERE PLT_ID_NO_LEAD_ZERO = '".$stripped_BC."'
					AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($user_cursor, $sql);
		database_check($ora_success, "Leading Zero\nCheck Failed\n(SZ1a)");
		$ora_success = ora_exec($user_cursor, $sql);
		database_check($ora_success, "Leading Zero\nCheck Failed\n(SZ1b)");
		ora_fetch_into($user_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == "1"){
			// only if there is exactly 1 pallet match, we change it
			$sql = "UPDATE CARGO_TRACKING
					SET PALLET_ID = '".$Barcode."'
					WHERE PALLET_ID = '".$stripped_BC."'
						AND ARRIVAL_NUM = '".$Arrival."'
						AND DATE_RECEIVED IS NULL";
			$ora_success = ora_parse($user_cursor, $sql);
			database_check($ora_success, "Leading Zero\nUpdate Failed\n(SZ2a)");
			$ora_success = ora_exec($user_cursor, $sql);
			database_check($ora_success, "Leading Zero\nUpdate Failed\n(SZ2b)");
			ora_commit($rf_conn);

		}
		return;
	}
}




function Login_Super($optional_name="none", $type="unspec")
{
	global $rf_conn;
	global $print_super;
	$user_cursor = ora_open($rf_conn);

	$SuperID = "";
	$password = "";

	$valid = "";

    do
    {
        $SuperID = "";
		if($valid == ""){
	        fresh_screen("SUPER");
		} else {
	        fresh_screen("INVALID\nSUPER");
			echo $valid."\n\n";
		}

		if($optional_name == "none"){
			echo "SUPER ID:\n";
			fscanf(STDIN, "%s\n", $SuperID);
			$SuperID = trim(strtoupper($SuperID));
		} else {
			$SuperID = trim(strtoupper($optional_name));
			$optional_name = "none";
		}

//        echo "PASSWORD:\n";
//        fscanf(STDIN, "%s\n", $password);

		if($SuperID != ""){
			$sql = "SELECT SCANNER_NAME FROM SCANNER_SUPERVISOR WHERE SCANNER_NAME = '".$SuperID."'";
	//        echo $sql."\n";
	//        fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($user_cursor, $sql);
			database_check($ora_success, "User ID not in\nPersonnel\n(LS1a)");
			$ora_success = ora_exec($user_cursor, $sql);
			database_check($ora_success, "User ID not in\nPersonnel\n(LS1b)");
			if(!ora_fetch_into($user_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				$valid = "User ".$SuperID." not\nIn SUPV System.";
			} else {
				$valid = "";
			}
		}
	
	} while ($SuperID == "" or $valid != ""); 
	$print_super = strtoupper($short_term_data_row['SCANNER_NAME']);
	$sql = "INSERT INTO STAYLINK_SCANNER_LOGIN
				(LOGIN_ID,
				LOGIN_TIME,
				SCANNER_TYPE)
			VALUES
				('".$print_super."',
				SYSDATE,
				'".$type."')";
	$ora_success = ora_parse($user_cursor, $sql);
	database_check($ora_success, "Cannot Update\nLogin");
	$ora_success = ora_exec($user_cursor, $sql);
	database_check($ora_success, "Cannot Update\nLogin");

	ora_commit($rf_conn);

	return strtoupper($SuperID);

}

function Login_Checker($type="unspec")
{
	global $rf_conn;
	global $print_checker;
	$user_cursor = ora_open($rf_conn);

	$CheckerID = "";
	$password = "";

	$valid = "";

	do
    {
        $CheckerID = "";
		if($valid == ""){
	        fresh_screen("CHECKER");
		} else {
	        fresh_screen("INVALID LOGIN\nCHECKER");
			echo $valid."\n\n";
		}

        echo "CHECKER ID:\n";
        fscanf(STDIN, "%s\n", $CheckerID);
		$CheckerID = trim(strtoupper($CheckerID));
//        echo "PASSWORD:\n";
//        fscanf(STDIN, "%s\n", $password);

//		$sql = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".strtoupper($CheckerID)."'";
		$sql = "SELECT COUNT(*) THE_COUNT FROM EMPLOYEE 
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($CheckerID).") = '".$CheckerID."'
				AND EMPLOYEE_TYPE_ID != 'INACTE'";
		$ora_success = ora_parse($user_cursor, $sql);
		database_check($ora_success, "User ID not in\nPersonnel\n(LC1a)");
		$ora_success = ora_exec($user_cursor, $sql);
		database_check($ora_success, "User ID not in\nPersonnel\n(LC1b)");
		if(strlen($CheckerID) < 4 || strlen($CheckerID) > 4){
//			$valid = "Login must be 4,\n5, or 6 digits";
			$valid = "Login must be 4 digits";
		} elseif(!is_numeric($CheckerID)){
			$valid = "Login with\nemployee# please.";
		} else {
			ora_fetch_into($user_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				$valid = "User ".$CheckerID." not\nIn CHKR System.";
			} elseif($short_term_data_row['THE_COUNT'] >= 2) {
				$valid = $CheckerID." has more than\n1 matching employee.\nPlease use your full\nEmployee# to log in.";
			} else {
				$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 4) THE_EMP FROM EMPLOYEE 
						WHERE SUBSTR(EMPLOYEE_ID, -".strlen($CheckerID).") = '".$CheckerID."'
						AND EMPLOYEE_TYPE_ID != 'INACTE'";
				$ora_success = ora_parse($user_cursor, $sql);
				database_check($ora_success, "User ID not in\nPersonnel\n(LC2a)");
				$ora_success = ora_exec($user_cursor, $sql);
				database_check($ora_success, "User ID not in\nPersonnel\n(LC2b)");
				ora_fetch_into($user_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$print_checker = $CheckerID."-".$short_term_data_row['THE_EMP'];
				$valid = "";
			}
		}
		
	// or $CheckerID != $password
    } while ($CheckerID == "" or $valid != "");     
//	$print_checker = strtoupper($CheckerID);
	$sql = "INSERT INTO STAYLINK_SCANNER_LOGIN
				(LOGIN_ID,
				LOGIN_TIME,
				SCANNER_TYPE)
			VALUES
				('".$CheckerID."',
				SYSDATE,
				'".$type."')";
	$ora_success = ora_parse($user_cursor, $sql);
	database_check($ora_success, "Cannot Update\nLogin");
	$ora_success = ora_exec($user_cursor, $sql);
	database_check($ora_success, "Cannot Update\nLogin");

	ora_commit($rf_conn);
//	ora_close($user_cursor);

	return strtoupper($CheckerID);

}

function validate_customer_to_scannertype($cust, $scanner_type, $scanner_function="cheezburger"){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT COUNT(*) THE_COUNT FROM SCANNER_ACCESS WHERE RECEIVER_ID = '".$cust."'
			AND VALID_SCANNER = '".$scanner_type."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcts1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcts1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['THE_COUNT'] > 0){
		return true;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM SCANNER_ACCESS_FUNCTION_EXCEP WHERE RECEIVER_ID = '".$cust."'
			AND VALID_SCANNER = '".$scanner_type."'
			AND UPPER(FUNCTION_NAME) = UPPER('".$scanner_function."')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcts2a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcts2b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['THE_COUNT'] > 0){
		return true;
	}

	return false;
}

function validate_comm_to_scannertype($comm, $scanner_type){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	// this bit is because "brazillian and peruvian cargo is exactly the same as chilean"
	// I shall not name who said that.
	// this is temporary until a new "commodity definition is reach"... or until I have to elaborate the function.
	if($scanner_type == "BRAZILIAN" || $scanner_type == "PERUVIAN" || $scanner_type == "CHILEAN"){
		$extra_sql = "('CHILEAN', 'BRAZILIAN', 'PERUVIAN')";
	} else {
		$extra_sql = "('".$scanner_type."')";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'
			AND COMMODITY_TYPE IN ".$extra_sql;
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcots1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Validate\nAccess Rights\n(vcots1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['THE_COUNT'] > 0){
		return true;
	}

	return false;
}

function get_max_activity_num($cust, $barcode, $vessel){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX
			FROM CARGO_ACTIVITY
			WHERE CUSTOMER_ID = '".$cust."'
				AND PALLET_ID = '".$barcode."'
				AND ARRIVAL_NUM = '".$vessel."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(gman1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(gman1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	return $short_term_data_row['THE_MAX'];
}


function is_max_activity_num($act_num, $cust, $barcode, $vessel){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX
			FROM CARGO_ACTIVITY
			WHERE CUSTOMER_ID = '".$cust."'
				AND PALLET_ID = '".$barcode."'
				AND ARRIVAL_NUM = '".$vessel."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(iman1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(iman1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($short_term_data_row['THE_MAX'] != $act_num){
		return false;
	} else {
		return true;
	}
}

function is_released_350($cust, $barcode, $vessel){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT BOL, NVL(CONTAINER_ID, 'NC') THE_CONT
			FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '".$cust."'
				AND PALLET_ID = '".$barcode."'
				AND ARRIVAL_NUM = '".$vessel."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(ir1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(ir1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($short_term_data_row['BOL'] == ""){
		return "No BoL found\nFor Pallet.\nCannot determine\nRelease Status.";
	} else {
		$bol = $short_term_data_row['BOL'];
		$cont = $short_term_data_row['THE_CONT'];
	}

	$sql = "SELECT LINE_RELEASE, AMS_RELEASE, BROKER_RELEASE
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND BOL_EQUIV = '".$bol."'
				AND CONTAINER_NUM = '".$cont."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(ir2a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(ir2b)");
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $SuperID);
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($short_term_data_row['LINE_RELEASE'] == "" || $short_term_data_row['AMS_RELEASE'] == "" || $short_term_data_row['BROKER_RELEASE'] == ""){
		return "Pallet has not\nBeen Released.\nContact Office.";
	}

	// there may also be several bols per container, so check that as well.
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND (LINE_RELEASE IS NULL OR AMS_RELEASE IS NULL OR BROKER_RELEASE IS NULL)";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCONT check\n(ir3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCONT check\n(ir3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($short_term_data_row['THE_COUNT'] > 0){
		return "Full Container\nHas Not Been\nReleased.\nContact Office.";
	}
	
}

function loadout_CLR_sealscan($cust, $order, $seal){
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	// first, check if this already matches an entry.
	$sql = "SELECT SEAL_NUM
			FROM CLR_ORDER_SEAL_JOIN
			WHERE ORDER_NUM = '".$order."'
				AND CUSTOMER_ID = '".$cust."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc1b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// this is a new entry.  check if it's valid.
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
				WHERE CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY
					AND CTMJ.TRUCK_PORT_ID = CTLR.PORT_ID
					AND CTLR.GATEPASS_NUM IS NULL
					AND CMD.CUSTOMER_ID = '".$cust."'
					AND CTLR.SEAL_NUM = '".$seal."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] <= 0){
			return "Seal# is not valid.\nContact Inventory";
		} else {
			// this is a new entry with a valid seal.
			$sql = "INSERT INTO CLR_ORDER_SEAL_JOIN
						(ORDER_NUM,
						CUSTOMER_ID,
						SEAL_NUM)
					VALUES
						('".$order."',
						'".$cust."',
						'".$seal."')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPrior Activity\n(Lc3b)");
			ora_commit($rf_conn);

			return "";
		}
	} else {
		// if there was a row in the table, does it match?
		if($short_term_data_row['SEAL_NUM'] == $seal){
			// they are just restarting an existing order.  cleared.
			return "";
		} else {
			// restarting an order with the wrong seal#.
			return "Seal# does not\nmatch previous";
		}
	}
}




// Main Logic begins here
if($argv[2] >= 20){
	// to prevent an infinite stack overflow, if they try and "reload" the scanner mroe than 20 times, kill it to restart.
	exit;
}



// this include defines RF database.  should be live for user IT, test for user ITTEST
include("db_definition.php");

do {
	fresh_screen(" Select Scanner Type");
//	echo "*** TEST SCANNER1 ***\n";
//	echo "getenv".getenv('SUPERID')."\n";
//	echo "the_ENV".$_ENV['SUPERID']."\n";
//	echo "argv1".$argv[1]."\n";
//	echo "argv2".$argv[2]."\n";
	echo " 1: Argen Fruit\n";
	echo " 2: Dole Paper\n";
	echo " 3: Chilean Fruit\n";
	echo " 4: WalMart-Chilean\n";
	echo " 5: Booking Paper\n";
	echo " 6: Clementine\n";
	echo " 7: Argen Juice\n";
//	echo " 8: Argen Fruit\n";
	echo " 8: Steel\n";
	echo " 9: TS\n";
//	echo " 11: Supervisor (DEMO)\n";
	echo " ENTER (1-9):\n";
//	echo "Scan Restarts: (".$argv[2].")\n";
	fscanf(STDIN, "%s\n", $CHOICE);
} while ($CHOICE < 1 or $CHOICE > 9);

switch ($CHOICE) {
	case 1:
		include_once("./argfruit_scanner.php");	// abitibi-bowater scanner functions
	break;
	case 2:
		include_once("./dole_scanner.php");	// Dole Paper scanner functions
	break;
	case 3:
		include_once("./fruit_scanner.php"); // Chilean Fruit scanner functions
	break;
	case 4:
		include_once("./walmart_fruit_scanner.php"); // WALMART SPECIFIC scanner functions
	break;
	case 5:
		include_once("./booking_scanner.php");	// Booking Paper fruit scanner functions
	break;
	case 6:
		include_once("./clem_scanner.php");	// clementine scanner functions
	break;
	case 7:
		include_once("./juice_scanner.php");	// argentine Juice functions
	break;
//	case 8:
//		include_once("./argfruit_scanner.php");	// argentine Fruit functions
//	break;
	case 8:
		include_once("./steel_scanner.php");	// steel rolls/coils
	break;
	case 9:
		include_once("./TS_scanner.php");	// anything TS needs internally
	break;
//	case 11:
//		include_once("./super_scanner.php");	// commodity-independant Supervisory functions
//	break;

	default:
}

//	system("./stayLinkHeader.php \"".$print_super."\" ".($argv[2] + 1)."");
// AT THIS POINT, program should terminate and re-execute due to the shell script engaging it.