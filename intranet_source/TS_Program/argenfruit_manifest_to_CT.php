<?php
/*
*	I. Thomas, 2/24/2014 using code written by Adam Walter, Dec 2009 for Chilean Fruit
*
*	This page takes a previously-uploaded Argentine Fruit Manifest
*	And moves it to CT, provided it's "valid".
*************************************************************************/



// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Argentine Fruit Manifest Conversion";
$area_type = "TECH";

$url_this_page = 'argenfruit_manifest_to_CT.php';

// Provides header / leftnav
include("pow_header.php");
if ($access_denied) {
	printf("Access Denied from TECH system");
	include("pow_footer.php");
	exit;
}
  
$user = $userdata['username'];

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo '<div class="alert" id="alert">Currently using the RF.TEST database!</div>';
if ($rfconn < 1) {
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

$trans = $_POST['trans'];
$submit = $_POST['submit'];

// echo $submit ." ".$trans."<br>";

if ($submit != "" && $trans != "") {
	$vessel = GetVessel($rfconn, $trans);
	$pushCT = GetPushedToCtDetails($rfconn, $trans);	
	if(ociresult($pushCT, 'PUSHED_TO_CT') == 'Y') {
		echo '<div class="alert">Manifest records for vessel' . " $vessel have already been moved to Cargo Tracking at " . ociresult($pushCT, 'PUSH_TO_CT_DATETIME') . " by ".  ociresult($pushCT, 'PUSH_TO_CT_USER') . ".</div>";
	} else {
		$manifest_details = GetManifestDetails($rfconn, $trans);
		
		while (ocifetch($manifest_details)) {
			$AMD_record = array('COMMODITY'=>ociresult($manifest_details, 'COMMODITY'),
									'NUM_OF_PARTIALS'=>ociresult($manifest_details, 'NUM_OF_PARTIALS'),
									'VARIETY'=>ociresult($manifest_details, 'VARIETY'),
									'INVOICE'=>ociresult($manifest_details, 'INVOICE'),
									'PALLET_NO'=>ociresult($manifest_details, 'PALLET_NO'),
									'UNIT_PALL'=>ociresult($manifest_details, 'UNIT_PALL'),
									'IMPORT_CODE'=>ociresult($manifest_details, 'IMPORT_CODE'),
									'GROWER_CODE'=>ociresult($manifest_details, 'GROWER_CODE'),
									'CONTAINER_NO'=>ociresult($manifest_details, 'CONTAINER_NO'),
									'PACK_TYPE'=>ociresult($manifest_details, 'PACK_TYPE'),
									'PACK_STYLE'=>ociresult($manifest_details, 'PACK_STYLE'),
									'STATUS_CD'=>substr(ociresult($manifest_details, 'STATUS_CD'), 0, 4),
									'BRAND'=>ociresult($manifest_details, 'BRAND'),
									'CARGO_SIZE'=>ociresult($manifest_details, 'CARGO_SIZE'),
									'PALL_MARK'=>ociresult($manifest_details, 'PALL_MARK'),
									'VOUCHER_NUM'=>ociresult($manifest_details, 'VOUCHER_NUM'),
									'TOTAL_CRTNS'=>ociresult($manifest_details, 'TOTAL_CRTNS'),
									'filename'=>GetFileName($rfconn, $trans),
									'cust'=>'1626', //Patagonia is the receiver
									'shipline'=>'8061',
									'vessel'=>$vessel
									);
			// echo "<pre>"; var_dump($AMD_record); echo "</pre><br/>"; //for bug-testing
			
			write_to_ct($rfconn, $AMD_record, $user);
		}
		
		ocicommit($rfconn); //Finalise all updates and insertions
		SetPushToCtUser($rfconn, $trans, $user);
		
		$the_count = GetCount($rfconn, $vessel);
		echo '<div class="message">' . "$the_count records now present in Cargo Tracking for vessel $vessel.</div>";
	}

}	
?>

<h1>Argenfruit Manifest to Cargo Tracking Application</h1>

<form name="get_data" action="<?php echo $url_this_page; ?>" method="post">
	<div class="field">
		<label for="trans">File:</label>
		<select id="trans" name="trans">
			<option value="">Please Select an Original File</option>
<?php
$all_files = GetFiles($rfconn);
while (ocifetch($all_files)) {
?>
			<option value="<?php echo ociresult($all_files, "TRANSACTION_ID"); ?>">
				<?php echo ociresult($all_files, "LR_NUM") . " - Transaction " . ociresult($all_files, "TRANSACTION_ID") . " (" . ociresult($all_files, "FILENAME") . ") uploaded at " . ociresult($all_files, "THE_UPLOAD"); ?>
			</option>
<?php
} ?>		
		</select>
	</div>
	<input class="submitButton" type="submit" name="submit" value="Move Manifest">
</form>

<?php
include("pow_footer.php");
exit;


//FUNCTIONS

function GetPushedToCtDetails($rfconn, $trans_id)
{
	$sql = "select PUSHED_TO_CT,
				to_char(PUSH_TO_CT_DATETIME, 'MM/DD/YYYY HH:MI:SS AM') as PUSH_TO_CT_DATETIME,
				PUSH_TO_CT_USER
			from ARGENFRUIT_MANIFEST_HEADER
			where TRANSACTION_ID = '$trans_id'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	return $stid;
}

function GetFiles($rfconn)
{
	$sql = "SELECT TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY HH24:MI') THE_UPLOAD,
				FILENAME,
				LR_NUM,
				TRANSACTION_ID
			FROM ARGENFRUIT_MANIFEST_HEADER
			ORDER BY LR_NUM DESC,
				TRANSACTION_ID DESC";
	
	$all_files = ociparse($rfconn, $sql);
	ociexecute($all_files);
	return $all_files;
}

function GetVessel($rfconn, $trans)
{
	$sql = "SELECT LR_NUM
			FROM ARGENFRUIT_MANIFEST_HEADER
			WHERE TRANSACTION_ID = '".$trans."'"; 
	$ar_num = ociparse($rfconn, $sql);
	ociexecute($ar_num);
	ocifetch($ar_num);
	return ociresult($ar_num, "LR_NUM");
}

function GetManifestDetails($rfconn, $trans_id)
{
	$sql = "select case when (NUM_OF_PARTIALS > 1) then substr('MIX' || '-' || VARIETY, 1, 20)
						 else substr(VARIETY, 1, 20)
				    end as VARIETY,
				   B.*
			from (select
						sum(crtns) over (partition by PALLET_NO) as TOTAL_CRTNS,
						max(CRTNS) over (partition by PALLET_NO) as MOST_CRTNS,
						count(PALLET_NO) over (partition by PALLET_NO) as NUM_OF_PARTIALS,
						AMD.*
					from ARGENFRUIT_MANIFEST_DETAILS AMD
					where AMD.TRANSACTION_ID = '$trans_id') B
			where CRTNS = MOST_CRTNS
			order by NUM_OF_PARTIALS desc";
	// echo "<pre>$sql</pre>";
	$manifest_details = ociparse($rfconn, $sql);
	ociexecute($manifest_details);
	return $manifest_details;
}

function GetFileName($rfconn, $trans_id)
{
	$sql = "SELECT FILENAME
			FROM ARGENFRUIT_MANIFEST_HEADER
			WHERE TRANSACTION_ID = '$trans_id'";
	$filename = ociparse($rfconn, $sql);
	ociexecute($filename);
	ocifetch($filename);
	return ociresult($filename, 'FILENAME');
}

function write_to_ct($rfconn, $AMD_record, $user)
{	
	if (strpos($AMD_record['COMMODITY'], 'PEAR') === false) {
		$comm = "7081";
	} else {
		$comm = "7082";
	}
	
	if (is_null($AMD_record['INVOICE'])) {
		$invoice_num = 0;
	} else {
		$invoice_num = $AMD_record['INVOICE'];
	}

	$sql = "INSERT INTO CARGO_TRACKING
				(PALLET_ID,
				ARRIVAL_NUM,
				QTY_RECEIVED,
				BOL,
				COMMODITY_CODE,
				VARIETY,
				RECEIVER_ID,
				EXPORTER_CODE,
				WEIGHT,
				WEIGHT_UNIT,
				DECK,
				HATCH,
				CONTAINER_ID,
				FUMIGATION_CODE,
				CARGO_DESCRIPTION,
				REMARK,
				CARGO_SIZE,
				MARK,
				BATCH_ID,
				FROM_SHIPPING_LINE,
				SHIPPING_LINE,
				QTY_IN_HOUSE,
				SUB_CUSTID,
				CARGO_TYPE_ID,
				RECEIVING_TYPE,
				MANIFESTED,
				SOURCE_NOTE,
				SOURCE_USER)
			VALUES
				('".strtoupper(trim($AMD_record['PALLET_NO']))."',
				'".$AMD_record['vessel']."',
				".$AMD_record['TOTAL_CRTNS'].",
				'".$AMD_record['IMPORT_CODE']."',
				".$comm.",
				SUBSTR('".$AMD_record['STATUS_CD']."-".$AMD_record['VARIETY']."', 0, 20),
				".$AMD_record['cust'].",
				'".substr($AMD_record['GROWER_CODE'], 0, 20)."',
				0,
				'KG',
				'',
				'".substr($AMD_record['CONTAINER_NO'], 0, 5)."',
				'".substr($AMD_record['CONTAINER_NO'], 0, 20)."',
				'',
				'".substr(($AMD_record['PACK_TYPE']."-".$AMD_record['PACK_STYLE']),0,20)."',
				'".$AMD_record['BRAND']."',
				'".$AMD_record['CARGO_SIZE']."',
				'".$AMD_record['PALL_MARK']."',
				'".$AMD_record['VOUCHER_NUM']."',
				'".$AMD_record['shipline']."',
				'".$AMD_record['shipline']."',
				".$AMD_record['TOTAL_CRTNS'].",
				'".$invoice_num."',
				'1',
				'S',
				'Y',
				'".$AMD_record['filename']."',
				'".$user."')";
//	echo "<pre>$sql</pre>";
	$new_record = ociparse($rfconn, $sql);
	ociexecute($new_record, OCI_DEFAULT); // Do not commit changes (When we upgrade to PHPv5.3 or greater, use 'OCI_NO_AUTO_COMMIT' instead)
}

function GetCount($rfconn, $vessel)
{
	$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '$vessel'";
		
	$the_count = ociparse($rfconn, $sql);
	ociexecute($the_count);
	ocifetch($the_count);
	return ociresult($the_count, 'THE_COUNT');
}

function SetPushToCtUser($rfconn, $trans_id, $user)
{
	$sql = "UPDATE ARGENFRUIT_MANIFEST_HEADER
			SET
				PUSH_TO_CT_USER = '$user',
				PUSH_TO_CT_DATETIME = SYSDATE,
				PUSHED_TO_CT = 'Y'
			WHERE TRANSACTION_ID = '$trans_id'"; 
	
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
}
?>