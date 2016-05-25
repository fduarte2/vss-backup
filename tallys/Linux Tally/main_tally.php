#!/usr/lib/php436/bin/php -q
<?php
/*
*
*	IT September, 2013
*	Project to move to php based warehouse tally generation
**************************************************************************/

// /* Define STDIN in case if it is not already defined by PHP for some reason */
if (!defined("STDIN"))
{
    define("STDIN", fopen('php://stdin', 'r'));
}



function database_check($oracle_return, $message){
	global $rf_conn;

	if(!$oracle_return){
	    ora_rollback($rf_conn);
		echo "Database Error.\n".$message."\nPlease contact TS";
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

function get_customer_id(){
	echo "Enter Customer ID:";
	fscanf(STDIN, "%s\n\n\n", $Customer_Id);
	if (valid_customer_id ($Customer_Id)) {return $Customer_Id;};
	echo "  Invalid Entry.  2nd Try - Re-enter Customer ID:";
	fscanf(STDIN, "%s\n", $Customer_Id);
	if (valid_customer_id ($Customer_Id)) {return $Customer_Id;};
	echo "    Invalid Entry.  3rd Try - Re-enter Customer ID:";
	fscanf(STDIN, "%s\n", $Customer_Id);
	if (valid_customer_id ($Customer_Id)) {return $Customer_Id;};
	return "X";
}

function get_arrival_num(){
	echo "\nEnter Arrival Num:";
	fscanf(STDIN, "%s\n", $Arrival_Num);
	if (valid_arrival_num ($Arrival_Num)) {return $Arrival_Num;};
	echo "  Invalid Entry.  2nd Try - Re-enter Arrival Num:";
	fscanf(STDIN, "%s\n", $Arrival_Num);
	if (valid_arrival_num ($Arrival_Num)) {return $Arrival_Num;};
	echo "    Invalid Entry.  3rd Try - Re-enter Arrival Num:";
	fscanf(STDIN, "%s\n", $Arrival_Num);
	if (valid_arrival_num ($Arrival_Num)) {return $Arrival_Num;};
	return "X";
}

function get_order_num($Customer_Id, $Arrival_Num){
	echo "\nEnter Order Num:";
	fscanf(STDIN, "%s\n", $Order_Num);
	if (valid_order_num ($Customer_Id, $Arrival_Num, $Order_Num)) {return $Order_Num;};
	echo "  Invalid Entry.  2nd Try - Re-enter Order Num:";
	fscanf(STDIN, "%s\n", $Order_Num);
	if (valid_order_num ($Customer_Id, $Arrival_Num, $Order_Num)) {return $Order_Num;};
	echo "    Invalid Entry.  3rd Try - Re-nter Order Num:";
	fscanf(STDIN, "%s\n", $Order_Num);
	if (valid_order_num ($Customer_Id, $Arrival_Num, $Order_Num)) {return $Order_Num;};
	return "X";
}


global $rf_conn;
global $lcs_conn;
global $kiosk_name;
$kiosk_name = $argv[1];
global $print_checker;
global $kiosk_ip;
$kiosk_ip = $argv[2];

//echo $kiosk_ip;

include("db_definition.php");

//while (TRUE) {

// this include defines RF database.  should be live for user IT, test for user ITTEST
print chr(27)."[H".chr(27)."[2J";
echo "\n              TALLY PRINTER MENU\n\n\n";
echo str_pad("   1: Dockticket INBOUND", 30).str_pad("2: Dockticket OUTBOUND", 30)."\n\n";
echo str_pad("   3: Booking Paper INBOUND", 30).str_pad("4: Booking Paper OUTBOUND", 30)."\n\n";
echo str_pad("   5: Chilean Fruit", 30).str_pad("6: WalMart-Chilean", 30)."\n\n";
echo str_pad("   7: Clementine", 30).str_pad("8: Argen Fruit", 30)."\n\n";
echo str_pad("   9: Argen Juice", 30).str_pad("10: Steel", 30)."\n\n";
echo "    ENTER (1-10):";
fscanf(STDIN, "%s\n", $CHOICE);

	switch ($CHOICE) {
		case 1: 
			include_once("DT_IB_tally.php");	// Inbound Dockticket scans
			break;
		case 2: 
			include_once("DT_OB_tally.php");	
			break;
		case 3: 
			include_once("BK_IB_tally.php");	
			break;
		case 4: 
			include_once("BK_OB_tally.php");	
			break;
		case 7:
			include_once("clem_tally.php");	// clementine scanner functions
		break;
		default:
		break;
	}
//}
