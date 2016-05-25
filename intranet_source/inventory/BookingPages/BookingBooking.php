<?php

$title = "Booking Booking";
$area_type = "INVE";
$useHtml5 = true;
include "pow_header.php";

if ($access_denied) {
	printf("Access Denied from INVE system");
	include "pow_footer.php";
	exit;
}

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
if ($rfconn < 1) {
	echo "Error logging on to the RF Oracle Server.";
	exit;
}

if ($_POST['submit'] == 'save') {
	$orderNum = $_POST['orderNum'];
	$vehicle = $_POST['vehicle'];
	$bookingNum = strtoupper(str_replace(" ", "", $_POST['booking']));
	
	if ($bookingNum !== '') {
		$results = saveNewBookingNum($orderNum, $vehicle, $bookingNum);
	}
}
elseif ($_POST['submit'] == 'modify') {
	$old = strtoupper(str_replace(" ", "", $_POST['oldBookingNo']));
	$new = strtoupper(str_replace(" ", "", $_POST['newBookingNo']));
	if ($old != "" && $new != "") {
		modifyExistingBookingNum($old, $new);
	}
}

$awaitingRolls = getAwaitingPaper();
$savedBookingNums = getSavedBookingNums();

?>

<h1>Barnett Booking Numbers</h1>

<div class="module">
	<h2>Attach</h2>
	<?php if ($results == 'CONFLICT') { ?>
	<p class="error">Update cancelled. Booking number <strong><?php echo $bookingNum; ?></strong> has already been used on a P.O. number other than <?php echo $orderNum; ?>.
	<?php } ?>
	<table class="aTable">
		<thead>
			<tr>
				<th>Order No.</th>
				<th>Vehicle</th>
				<th>Enter New Booking Order</th>
			</td>
		</thead>
		<tbody>
		<?php if ($awaitingRolls) {?>
			<?php foreach ($awaitingRolls as $roll) { ?>
			<tr>
				<td><?php echo $roll['orderNum']; ?></td>
				<td><?php echo $roll['vehicle']; ?></td>
				<td>
					<form action="" method="post" name="<?php echo $roll['orderNum'] . '-' . $roll['orderNum']; ?>">
						<input type="hidden" name="orderNum" value="<?php echo $roll['orderNum']; ?>">
						<input type="hidden" name="vehicle" value="<?php echo $roll['vehicle']; ?>">
						<input type="text" name="booking">
						<button name="submit" value="save" type="submit">Attach</button>
					</form>
				</td>
			</tr>
			<?php } ?>
		<?php } else { ?>
			<tr><td colspan="3">No paper rolls currently awaiting order numbers.</td></tr>
		<?php } ?>
		</tbody>
	</table>
</div>

<div class="module">
	<h2>Modify</h2>
	<form name="modify" action="" method="post">
		<label>Current Booking  #
			<select name="oldBookingNo">
				<option value="">Select a Booking</option>
				<?php foreach ($savedBookingNums as $bookingNum) { ?>
				<option value="<?php echo $bookingNum; ?>"><?php echo $bookingNum; ?></option>
				<?php } ?>
			</select>
		</label>
		<br>
		<label>New #
			<input type="text" name="newBookingNo" maxlength="20">
		</label>
		<button class="button" type="submit" name="submit" value="modify">Save</button>
	</form>
</div>

<?php include "pow_footer.php"; ?>



<?php

function getAwaitingPaper()
{
	global $rfconn;
	$sql = "SELECT DISTINCT
				arrival_num,
				order_num
			FROM booking_additional_data
			WHERE booking_num IS NULL
			ORDER BY
				order_num,
				arrival_num";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	$rolls = Array();
	while (ocifetch($stmt)) {
		$rolls[] = Array(
			'orderNum' => ociresult($stmt, 'ORDER_NUM'),
			'vehicle' => ociresult($stmt, 'ARRIVAL_NUM')
		);
	}
	
	if (empty($rolls)) {
		return false;
	}
	return $rolls;
}

function getSavedBookingNums()
{
	global $rfconn;
	$sql = "SELECT DISTINCT booking_num
			FROM booking_additional_data
			WHERE booking_num IS NOT NULL
			ORDER BY booking_num";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	$bookingNums = Array();
	while (ocifetch($stmt)) {
		$bookingNums[] = ociresult($stmt, 'BOOKING_NUM');
	}
	
	return $bookingNums;
}

function saveNewBookingNum($orderNum, $vehicle, $bookingNum)
{
	global $rfconn;
	$sql = "SELECT COUNT(*) AS the_count
			FROM booking_additional_data
			WHERE
				booking_num = '$bookingNum'
				AND order_num != '$orderNum'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	ocifetch($stmt);
	if (ociresult($stmt, 'THE_COUNT') >= 1) {
		return 'CONFLICT';
	}
		
	$sql = "UPDATE booking_additional_data
			SET booking_num = '$bookingNum'
			WHERE
				arrival_num = '$vehicle'
				AND order_num = '$orderNum'
				AND booking_num IS NULL";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	updateWarehouseCodes($bookingNum);
}

function updateWarehouseCodes($bookingNum)
{
	global $rfconn;
	
	//Insert new warehouse codes into reference table
	$sql = "INSERT INTO booking_warehouse_code
			(warehouse_code, booking_num, sscc_grade_code, width, created_at)
			SELECT DISTINCT
				NVL(wc.warehouse_code,
					LPAD(TO_CHAR((ROW_NUMBER() OVER (ORDER BY MIN(ad.booking_num)))
								  + NVL((SELECT MAX(TO_NUMBER(warehouse_code)) FROM booking_warehouse_code), '00000')),
							5, '0'
					)
				) AS warehouse_code,
				ad.booking_num,
				pg.sscc_grade_code,
				ROUND(ad.width * unit.conversion_factor) AS width_mm,
				NVL(wc.created_at, SYSDATE) AS created_at
			FROM booking_additional_data ad
			INNER JOIN booking_paper_grade_code pg
				ON ad.product_code = pg.product_code
			LEFT JOIN unit_conversion_from_bni unit
				ON ad.width_meas = unit.primary_uom AND secondary_uom = 'MM'
			LEFT JOIN booking_warehouse_code wc
				ON wc.booking_num = ad.booking_num
				AND wc.sscc_grade_code = pg.sscc_grade_code
				AND wc.width = ROUND(ad.width * unit.conversion_factor)
			WHERE
				ad.width IS NOT NULL
				AND ad.booking_num IS NOT NULL
				AND wc.warehouse_code IS NULL
				AND ad.booking_num = '$bookingNum'
			GROUP BY
				ad.booking_num,
				pg.sscc_grade_code,
				wc.warehouse_code,
				wc.created_at,
				ROUND(ad.width * unit.conversion_factor)
			ORDER BY warehouse_code";
				
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	//Get warehouse_code from reference table and put it in main table
	$sql = "UPDATE (
				SELECT ad_.*, pg_.sscc_grade_code
				FROM booking_additional_data ad_
				INNER JOIN booking_paper_grade_code pg_
					ON ad_.product_code = pg_.product_code
			) ad1
			SET warehouse_code = (
				SELECT DISTINCT wc.warehouse_code		  
				FROM booking_additional_data ad
				LEFT JOIN unit_conversion_from_bni unit
					ON ad.width_meas = unit.primary_uom AND secondary_uom = 'MM'
				INNER JOIN booking_paper_grade_code pg
				ON ad.product_code = pg.product_code
				LEFT JOIN booking_warehouse_code wc
					ON wc.booking_num = ad.booking_num
					AND wc.sscc_grade_code = pg.sscc_grade_code
					AND wc.width = ROUND(ad.width * unit.conversion_factor)
				WHERE
					ad1.sscc_grade_code = pg.sscc_grade_code
					AND ad1.booking_num = ad.booking_num
					AND ROUND(ad1.width * unit.conversion_factor)
						= ROUND(ad.width * unit.conversion_factor)
			)
			WHERE booking_num = '$bookingNum'";
	
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
}

function modifyExistingBookingNum($old, $new)
{
	global $rfconn;
	$sql = "UPDATE booking_additional_data
			SET booking_num = '$new'
			WHERE booking_num = '$old'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	$sql = "DELETE FROM booking_warehouse_code
			WHERE booking_num = '$old'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	updateWarehouseCodes($new);
}


?>