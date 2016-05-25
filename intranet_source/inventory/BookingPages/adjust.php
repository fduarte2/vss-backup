<?php

// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Inventory System";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
if ($access_denied) {
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
}



 
/*
 * CONTROLLER
 */

$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
// $rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo '<span class="error">Connected to the RFTEST database.</span>';
if ($rfconn < 1) {
    printf("Error logging on to the RF Oracle Server: ");
    exit;
}

if ($_POST['submit'] == 'save') {		
	$oldAndNew = updateBookingPaperPallet(
		$_GET['pallet-id'],
		$_GET['arrival-num'],
		$_GET['customer-id'],
		
		$_POST['pallet-id'],
		$_POST['arrival-num'],
		$_POST['booking-num'],
		$_POST['weight-kg'],
		$_POST['product-code'],
		$_POST['width-cm'],
		$_POST['diameter-cm'],
		$_POST['length'],
		$_POST['length-unit'],
		$_POST['bol'],
		$_POST['po']
	);
}
elseif ($_GET['submit'] == 'continue' && $_GET['pallet-id']) {
    $pallets = fetchBookingPaperPallets($_GET['pallet-id'], $_GET['arrival-num'], $_GET['customer-id']);
	$gradeCodes = fetchGradeCodes();
}



/*
 * VIEW
 */
?>
<h1>Booking Paper Roll Correction</h1>
<p>Use this program to manually adjust information on booking paper rolls.</p>
<p>If the grade code, booking number, or width is changed, the warehouse code will be automatically re-calculated.</p>
<p> A summary of changes will be displayed after submission.</p>
<br>
<form action="" method="get">
    <label>Roll number
        <input name="pallet-id" type="text" maxlength="32" value="<?php echo $_GET['pallet-id'];?>">
    </label>
	<br>
    <label>Arrival number (optional)
        <input name="arrival-num" type="text" maxlength="12" value="<?php echo $_GET['arrival-num'];?>">
    </label>
	<br>
    <label>Customer ID (optional)
        <input name="customer-id" type="text" maxlength="12" value="<?php echo $_GET['customer-id'];?>">
    </label>
	<br>
    <button type="submit" name="submit" value="continue" class="button">Retrieve</button>
</form>

<?php if ($_GET['submit'] == 'continue') { ?>
    <hr>
    
    <?php if (sizeof($pallets) > 1) { ?>
        <p>There is more than one roll with this number. Please select which one you wanted:</p>
        <ul>
            <?php foreach ($pallets as $pallet) { ?>
                <li>
                    <a href="?pallet-id=<?php echo $pallet->id; ?>&arrival-num=<?php echo $pallet->arrivalNum; ?>&customer-id=<?php echo $pallet->customer->id; ?>&submit=continue">
                        roll <?php echo $pallet->id; ?> from arrival <?php echo $pallet->arrivalNum; ?> for customer <?php echo $pallet->customer->name; ?>
                    </a>
                </li>
            <?php } ?>
		</ul>
    
    <?php } elseif (sizeof($pallets) == 1) { ?>
        
        <?php if (!$pallets[0]->canBeEdited) { ?>
            <p class="error"><strong>This roll has a dock ticket number that is part of a submitted order, so roll info cannot be changed.</strong></p>
        <?php } else { ?>
			<p>Enter any new values. All values are optional.</p>
		<?php } ?>
		
		<form action="?pallet-id=<?php echo $pallets[0]->id; ?>&arrival-num=<?php echo $pallets[0]->arrivalNum; ?>&customer-id=<?php echo $pallets[0]->customer->id; ?>" method="post">
		
			<table class="aTable">
				<thead>
					<tr>
						<th>Value
						<th>Current Value
						<th>New Value
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Roll ID number
						<td><?php echo $pallets[0]->id; ?>
						<td><input type="text" maxLength="32" name="pallet-id"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Arrival number
						<td><?php echo $pallets[0]->arrivalNum; ?>
						<td><input type="text" maxLength="12" name="arrival-num"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Booking number
						<td><?php echo $pallets[0]->bookingNum; ?>
						<td><input type="text" maxLength="32" name="booking-num"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>PO number
						<td><?php echo $pallets[0]->po; ?>
						<td><input type="text" maxLength="32" name="po"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Bill of lading
						<td><?php echo $pallets[0]->bol; ?>
						<td><input type="text" maxLength="32" name="bol"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Grade code
						<td><?php echo $pallets[0]->gradeCode; ?>
						<td>
							<select name='product-code'<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
								<option value=""></option>
								<?php foreach ($gradeCodes as $productCode => $gradeCode) { ?>
									<option value="<?php echo $productCode; ?>"><?php echo $gradeCode; ?></option>
								<?php } ?>
							</select>
					</tr>
					<tr>
						<th>Width (cm)
						<td><?php echo $pallets[0]->widthCm; ?>
						<td><input step="0.1" type="number" name="width-cm"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Diameter (cm)
						<td><?php echo $pallets[0]->diameterCm; ?>
						<td><input step="0.1" type="number" name="diameter-cm"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Length
						<td><?php echo $pallets[0]->length; ?>
						<td><input step="0.1" type="number" name="length"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Length unit (LM or FT)
						<td><?php echo $pallets[0]->lengthUnit; ?>
						<td><input type="text" maxLength="2" name="length-unit"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Weight (Kg)
						<td><?php echo $pallets[0]->weightKg; ?>
						<td><input step="0.1" type="number" name="weight-kg"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Warehouse code
						<td><?php echo $pallets[0]->warehouseCode; ?>
						<td>(auto)
					</tr>
				</tbody>
			</table>
			
			<button type="submit" class="button" name="submit" value="save"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>Save</button>
		</form>
            
    <?php } else { ?>
		<p class="error">No paper rolls matched the search terms above.</p>
	<?php } ?>
<?php } elseif ($_POST['submit'] == 'save') { ?>
	<hr>
	<p>The roll was successfully updated. Below is a summary of changes.</p>
	
	<table class="aTable">
		<thead>
			<th>Value
			<th>Old
			<th>New
		</thead>
		<tbody>
			<tr>
				<th>Roll ID number
				<td><?php echo $oldAndNew['old']->id; ?>
				<td><?php echo $oldAndNew['new']->id; ?>
			</tr>
		
			<tr>
				<th>Arrival number
				<td><?php echo $oldAndNew['old']->arrivalNum; ?>
				<td><?php echo $oldAndNew['new']->arrivalNum; ?>
			</tr>
			
			<tr>
				<th>Booking number
				<td><?php echo $oldAndNew['old']->bookingNum; ?>
				<td><?php echo $oldAndNew['new']->bookingNum; ?>
			</tr>
			
			<tr>
				<th>PO number
				<td><?php echo $oldAndNew['old']->po; ?>
				<td><?php echo $oldAndNew['new']->po; ?>
			</tr>
			
			<tr>
				<th>Bill of lading number
				<td><?php echo $oldAndNew['old']->bol; ?>
				<td><?php echo $oldAndNew['new']->bol; ?>
			</tr>
			
			<tr>
				<th>Width
				<td><?php echo $oldAndNew['old']->widthCm; ?>
				<td><?php echo $oldAndNew['new']->widthCm; ?>
			</tr>
			
			<tr>
				<th>Diameter
				<td><?php echo $oldAndNew['old']->diameterCm; ?>
				<td><?php echo $oldAndNew['new']->diameterCm; ?>
			</tr>
			
			<tr>
				<th>Length
				<td><?php echo $oldAndNew['old']->length; ?>
				<td><?php echo $oldAndNew['new']->length; ?>
			</tr>
			
			<tr>
				<th>Length unit
				<td><?php echo $oldAndNew['old']->lengthUnit; ?>
				<td><?php echo $oldAndNew['new']->lengthUnit; ?>
			</tr>
			
			<tr>
				<th>Grade code
				<td><?php echo $oldAndNew['old']->gradeCode; ?>
				<td><?php echo $oldAndNew['new']->gradeCode; ?>
			</tr>
			
			<tr>
				<th>Weight (Kg)
				<td><?php echo $oldAndNew['old']->weightKg; ?>
				<td><?php echo $oldAndNew['new']->weightKg; ?>
			</tr>
			
			<tr>
				<th>Warehouse code
				<td><?php echo $oldAndNew['old']->warehouseCode; ?>
				<td><?php echo $oldAndNew['new']->warehouseCode; ?>
			</tr>
		</tbody>
	</table>
	
<?php } ?>
<?php include "pow_footer.php"; ?>


<?php
/*
 * MODEL
 */


function updateBookingPaperPallet(
	$refPalletId,
	$refArrivalNum,
	$refCustomerId,
	
	$palletId,
	$arrivalNum,
	$bookingNum,
	$weightKg,
	$productCode,
	$widthCm,
	$diameterCm,
	$length,
	$lengthUnit,
	$bol,
	$po
)
{
	global $rfconn;
	
	$oldPallet = fetchBookingPaperPallets($refPalletId, $refArrivalNum, $refCustomerId);
	$oldPallet = $oldPallet[0];
	
	if (!$palletId) $palletId = $oldPallet->id;
	if (!$arrivalNum) $arrivalNum = $oldPallet->arrivalNum;
	if (!$bookingNum) $bookingNum = $oldPallet->bookingNum;
	if (!$weightKg) $weightKg = $oldPallet->weightKg;
	if (!$productCode) $productCode = $oldPallet->productCode;
	if (!$widthCm) $widthCm = $oldPallet->widthCm;
	if (!$diameterCm) $diameterCm = $oldPallet->diameterCm;
	if (!$length) $length = $oldPallet->length;
	if (!$lengthUnit) $lengthUnit = $oldPallet->lengthUnit;
	if (!$bol) $bol = $oldPallet->bol;
	if (!$po) $po = $oldPallet->po;
	$warehouseCode = calculateWarehouseCode($bookingNum, $productCode, $widthCm);
	
	

	
	$sql = "UPDATE booking_additional_data
			SET
				pallet_id = '$palletId',
				arrival_num = '$arrivalNum',
				booking_num = '$bookingNum',
				product_code = '$productCode',
				width = '$widthCm',
				width_meas = 'CM',
				diameter = '$diameterCm',
				diameter_meas = 'CM',
				length = '$length',
				length_meas = '$lengthUnit',
				order_num = '$po',
				bol = '$bol',
				warehouse_code = '$warehouseCode'
			WHERE
				pallet_id = '{$oldPallet->id}'
				AND arrival_num = '{$oldPallet->arrivalNum}'
				AND receiver_id = '{$oldPallet->customer->id}'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt, OCI_DEFAULT); //OCI_NO_AUTO_COMMIT in php5
	
	
	$sql = "UPDATE cargo_tracking
			SET
				pallet_id = '$palletId',
				arrival_num = '$arrivalNum',
				weight = '$weightKg',
				weight_unit = 'KG'
			WHERE
				pallet_id = '{$oldPallet->id}'
				AND arrival_num = '{$oldPallet->arrivalNum}'
				AND receiver_id = '{$oldPallet->customer->id}'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt, OCI_DEFAULT); //OCI_NO_AUTO_COMMIT in php5
	
	if ($palletId != $oldPallet->id || $arrivalNum != $oldPallet->arrivalNum) {
		
		$sql = "UPDATE booking_damages
				SET
					pallet_id = '$palletId',
					arrival_num = '$arrivalNum'
				WHERE
					pallet_id = '{$oldPallet->id}'
					AND arrival_num = '{$oldPallet->arrivalNum}'
					AND receiver_id = '{$oldPallet->customer->id}'";
		$stmt = ociparse($rfconn, $sql);
		ociexecute($stmt);
		
		$sql = "UPDATE cargo_activity
				SET
					pallet_id = '$palletId',
					arrival_num = '$arrivalNum'
				WHERE
					pallet_id = '{$oldPallet->id}'
					AND arrival_num = '{$oldPallet->arrivalNum}'
					AND customer_id = '{$oldPallet->customer->id}'";
		$stmt = ociparse($rfconn, $sql);
		ociexecute($stmt, OCI_DEFAULT);
	}
	
	ocicommit($rfconn);
	
	$newPallet = fetchBookingPaperPallets($palletId, $arrivalNum, $refCustomerId);
	
	return Array('old' => $oldPallet, 'new' => $newPallet[0]);
}

function fetchBookingPaperPallets($palletId, $arrivalNum = null, $customerId = null)
{
    global $rfconn;
    
    $sql = "SELECT
				ct.pallet_id,
				ct.arrival_num,
				ct.receiver_id AS customer_id,
				cp.customer_name,
				bd.booking_num,
				ct.weight * u1.conversion_factor AS weight_kg,
				gc.sscc_grade_code AS grade_code,
				bd.product_code,
				bd.width * u2.conversion_factor AS width_cm,
				bd.length,
				bd.length_meas AS length_unit,
				bd.diameter * u3.conversion_factor AS diameter_cm,
				bd.bol,
				bd.order_num AS po,
				bd.warehouse_code,
				(
					SELECT MAX(bo.status) AS max_status
					FROM booking_orders bo
					LEFT JOIN booking_order_details od
						ON od.order_num = bo.order_num
					LEFT JOIN booking_warehouse_code wc
						ON wc.booking_num = od.booking_num
						AND wc.sscc_grade_code = od.sscc_grade_code
						AND wc.width = od.width * 10
					WHERE wc.warehouse_code = bd.warehouse_code
				) AS max_order_status
			FROM cargo_tracking ct
			LEFT JOIN booking_additional_data bd
				ON ct.arrival_num = bd.arrival_num
				AND ct.pallet_id = bd.pallet_id
				AND ct.receiver_id = bd.receiver_id
			LEFT JOIN booking_paper_grade_code gc
				ON gc.product_code = bd.product_code
			LEFT JOIN customer_profile cp
				ON cp.customer_id = ct.receiver_id
			LEFT JOIN unit_conversion_from_bni u1
				ON u1.primary_uom = ct.weight_unit
				AND u1.secondary_uom = 'KG'
			LEFT JOIN unit_conversion_from_bni u2
				ON u2.primary_uom = bd.width_meas
				AND u2.secondary_uom = 'CM'
			LEFT JOIN unit_conversion_from_bni u3
				ON u3.primary_uom = bd.diameter_meas
				AND u3.secondary_uom = 'CM'
			WHERE ct.pallet_id = '$palletId' ";
    if ($arrivalNum) $sql .= "AND ct.arrival_num = '$arrivalNum' ";
    if ($customerId) $sql .= "AND ct.receiver_id = '$customerId' ";
	
	// echo $sql;
    
    $stmt = ociparse($rfconn, $sql);
    ociexecute($stmt);
    
    $pallets = Array();
    while (ocifetch($stmt)) {
        $pallets[] = new Pallet(Array(
            'id' => ociresult($stmt, 'PALLET_ID'),
            'arrivalNum' => ociresult($stmt, 'ARRIVAL_NUM'),
            'bookingNum' => ociresult($stmt, 'BOOKING_NUM'),
            'canBeEdited' => (ociresult($stmt, 'MAX_ORDER_STATUS') < 2 || true),
            'weightKg' => ociresult($stmt, 'WEIGHT_KG'),
            'gradeCode' => ociresult($stmt, 'GRADE_CODE'),
            'productCode' => ociresult($stmt, 'PRODUCT_CODE'),
            'widthCm' => ociresult($stmt, 'WIDTH_CM'),
            'diameterCm' => ociresult($stmt, 'DIAMETER_CM'),
            'length' => ociresult($stmt, 'LENGTH'),
            'lengthUnit' => ociresult($stmt, 'LENGTH_UNIT'),
            'bol' => ociresult($stmt, 'BOL'),
            'po' => ociresult($stmt, 'PO'),
			'warehouseCode' => ociresult($stmt, 'WAREHOUSE_CODE'),
			'customer' => new Customer(Array(
				'id' => ociresult($stmt, 'CUSTOMER_ID'),
				'name' => ociresult($stmt, 'CUSTOMER_NAME'),
			))
        ));
    }
    
    return $pallets;
}

function calculateWarehouseCode($bookingNum, $productCode, $widthCm)
{
	global $rfconn;
	
	//Check if there's an existing warehouse code for this combination
	$sql = "SELECT wc.warehouse_code
			FROM booking_warehouse_code wc
			LEFT JOIN booking_paper_grade_code gc
				ON wc.sscc_grade_code = gc.sscc_grade_code
			WHERE
				wc.booking_num = '$bookingNum'
				AND gc.product_code = '$productCode'
				AND wc.width = '$widthCm' * 10";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	//If there is, use it
	if (ocifetch($stmt)) {
		return ociresult($stmt, 'WAREHOUSE_CODE');
	}
	
	//Otherwise:
	
	//Get a new warehouse code
	$sql = "SELECT LPAD(TO_CHAR(MAX(warehouse_code) + 1), 5, '0') AS new_warehouse_code
			FROM booking_warehouse_code";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	ocifetch($stmt);
	$warehouseCode = ociresult($stmt, 'NEW_WAREHOUSE_CODE');
	
	//Get the grade code for this product code
	$sql = "SELECT sscc_grade_code FROM booking_paper_grade_code WHERE product_code = '$productCode'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	ocifetch($stmt);
	$gradeCode = ociresult($stmt, 'SSCC_GRADE_CODE');
	
	//Insert the new warehouse code combination
	$sql = "INSERT INTO booking_warehouse_code
			(warehouse_code, booking_num, sscc_grade_code, width, created_at)
			VALUES
			('$warehouseCode', '$bookingNum', '$gradeCode', '$widthCm' * 10, SYSDATE)";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	return $warehouseCode;
}

function fetchGradeCodes()
{
	global $rfconn;
	
	$sql = "SELECT
				product_code,
				sscc_grade_code AS grade_code
			FROM booking_paper_grade_code
			ORDER BY grade_code";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	
	$array = Array();
	while (ocifetch($stmt)) {
		$array[ociresult($stmt, 'PRODUCT_CODE')] = ociresult($stmt, 'GRADE_CODE');
	}
	
	return $array;
}

class Pallet
{
    var $id;
    var $arrivalNum;
	var $bookingNum;
    var $canBeEdited;
    var $weightKg;
    var $gradeCode;
	var $productCode;
    var $widthCm;
    var $diameterCm;
    var $length;
    var $lengthUnit;
    var $bol;
    var $po;
	var $warehouseCode;
	var $customer;
    
    function Pallet($initial)
    {
		//This works in php5, but this is php4, so...
        /* foreach ($initial as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
            else throw new \Exception(sprintf('Property "%s" does not exist in class "%s".', $key, get_class($this)));
        } */
		
		//...do it without any error checks
		foreach ($initial as $key => $value) {
			$this->$key = $value;
        }
    }
}

class Customer
{
	var $id;
	var $name;
	
	function Customer($initial)
	{
		$this->id = $initial['id'];
		$this->name = $initial['name'];
	}
}
