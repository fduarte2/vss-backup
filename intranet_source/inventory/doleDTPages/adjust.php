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
 *  Model/View/Controller (MVC) is a very common program design pattern.
 *  The over-arching goal is "separation of concerns". For example, don't put database
 *  calls or modify variables in your display code. This leads to readable, maintable code.
 * 
 *  This program is structured more or less the same as a typical Symfony project,
 *  but a Symfony project is much more formalised and consistent, and spread across more files.
 *  Also, all parts of the project will use the same models, ensuring a "single source of truth",
 *  instead of the typical SQL calls spread out higgledy-piggledy throughout the project.
 */


 
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
	$oldAndNew = updateDolePaperPallet(
		$_GET['pallet-id'],
		$_GET['arrival-num'],
		$_GET['customer-id'],
		$_POST['pallet-id'],
		$_POST['arrival-num'],
		$_POST['weight-lbs'],
		$_POST['po'],
		$_POST['grade-code'],
		$_POST['bol']
	);
}
elseif ($_GET['submit'] == 'continue' && $_GET['pallet-id']) {
    $pallets = fetchDolePaperPallets($_GET['pallet-id'], $_GET['arrival-num'], $_GET['customer-id']);
}



/*
 * VIEW
 */
?>

<h1>Dock Ticket Roll Correction</h1>
<p>Use this program to manually adjust information about Dole paper rolls.</p>
<p>If the PO number, bill of lading number, or grade code is changed, the dock ticket number will be automatically re-calculated.</p>
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
						<th>New
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Roll Id number
						<td><?php echo $pallets[0]->id; ?>
						<td><input type="text" maxLength="32" name="pallet-id"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Arrival number
						<td><?php echo $pallets[0]->arrivalNum; ?>
						<td><input type="text" maxLength="12" name="arrival-num"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
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
						<td><input type="text" maxLength="32" name="grade-code"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Weight (lbs)
						<td><?php echo $pallets[0]->weightLbs; ?>
						<td><input type="number" maxLength="32" name="weight-lbs"<?php echo !$pallets[0]->canBeEdited ? ' disabled' : ''; ?>>
					</tr>
					<tr>
						<th>Dock ticket number
						<td><?php echo $pallets[0]->dockTicket; ?>
						<td>(auto)
					</tr>
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
				<th>Grade code
				<td><?php echo $oldAndNew['old']->gradeCode; ?>
				<td><?php echo $oldAndNew['new']->gradeCode; ?>
			</tr>
			
			<tr>
				<th>Weight (lbs)
				<td><?php echo $oldAndNew['old']->weightLbs; ?>
				<td><?php echo $oldAndNew['new']->weightLbs; ?>
			</tr>
			
			<tr>
				<th>Dock ticket number
				<td><?php echo $oldAndNew['old']->dockTicket; ?>
				<td><?php echo $oldAndNew['new']->dockTicket; ?>
			</tr>
		</tbody>
	</table>
	
<?php } ?>
<?php include "pow_footer.php"; ?>


<?php
/*
 * MODEL
 */


function updateDolePaperPallet($refPalletId, $refArrivalNum, $refCustomerId, $newPalletId, $newArrivalNum, $weightLbs, $po, $gradeCode, $bol)
{
	global $rfconn;
	
	$oldPallet = fetchDolePaperPallets($refPalletId, $refArrivalNum, $refCustomerId);
	$oldPallet = $oldPallet[0];
	
	if (!$newPalletId) $newPalletId = $oldPallet->id;
	if (!$newArrivalNum) $newArrivalNum = $oldPallet->arrivalNum;
	if (!$weightLbs) $weightLbs = $oldPallet->weightLbs;
	if (!$gradeCode) $gradeCode = $oldPallet->gradeCode;
	if (!$bol) $bol = $oldPallet->bol;
	if (!$po) $po = $oldPallet->po;
	$dockTicket = calculateDockTicket($po, $gradeCode, $bol);
	
	$sql = "UPDATE cargo_tracking
			SET
				pallet_id = '$newPalletId',
				arrival_num = '$newArrivalNum',
				weight = '$weightLbs',
				batch_id = '$gradeCode',
				cargo_description = '$po $gradeCode $bol',
				bol = '$dockTicket',
				mark = (SELECT basis_weight FROM dolepaper_edi_import_codes WHERE paper_code = '$gradeCode'),
				cargo_size = (SELECT paper_width FROM dolepaper_edi_import_codes WHERE paper_code = '$gradeCode')
			WHERE
				pallet_id = '{$oldPallet->id}'
				AND arrival_num = '{$oldPallet->arrivalNum}'
				AND receiver_id = '{$oldPallet->customer->id}'
				AND remark = 'DOLEPAPERSYSTEM'";
	
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt, OCI_DEFAULT); //OCI_NO_AUTO_COMMIT in php5
	
	if ($newPalletId != $oldPallet->id || $newArrivalNum != $oldPallet->arrivalNum) {
		$sql = "UPDATE dolepaper_damages
				SET
					roll = '$newPalletId',
					dock_ticket = '$dockTicket'
				WHERE
					roll = '{$oldPallet->id}'
					AND customer_id = '{$oldPallet->customer->id}'";
		$stmt = ociparse($rfconn, $sql);
		ociexecute($stmt);
		
		$sql = "UPDATE cargo_activity
				SET
					pallet_id = '$newPalletId',
					arrival_num = '$newArrivalNum'
				WHERE
					pallet_id = '{$oldPallet->id}'
					AND arrival_num = '{$oldPallet->arrivalNum}'
					AND customer_id = '{$oldPallet->customer->id}'";
		$stmt = ociparse($rfconn, $sql);
		ociexecute($stmt);
		
		$sql = "UPDATE dolepaper_manual_code_change
				SET
					pallet_id = '$newPalletId',
					arrival_num = '$newArrivalNum',
					customer_id = '$refCustomerId'
				WHERE
					pallet_id = '{$oldPallet->id}'
					AND arrival_num = '{$oldPallet->arrivalNum}'
					AND customer_id = '{$oldPallet->customer->id}'";
		$stmt = ociparse($rfconn, $sql);
		ociexecute($stmt);
	}
	
	ocicommit($rfconn);
	
	$newPallet = fetchDolePaperPallets($newPalletId, $newArrivalNum, $refCustomerId);
	
	return Array('old' => $oldPallet, 'new' => $newPallet[0]);
}

function fetchDolePaperPallets($palletId, $arrivalNum = null, $customerId = null)
{
    global $rfconn;
    
    $sql = "SELECT
                ct.pallet_id,
                ct.arrival_num,
				ct.receiver_id AS customer_id,
				cp.customer_name,
                ct.weight AS weight_lbs,
                ct.batch_id AS grade_code,
				ct.bol AS dock_ticket,
                SUBSTR(ct.cargo_description, INSTR(ct.cargo_description, ' ', 1, 2) + 1) AS bol,
                SUBSTR(ct.cargo_description, 1, INSTR(ct.cargo_description, ' ', 1, 1) - 1) AS po,
					(SELECT MAX(DO.status) AS lowest_status
					FROM dolepaper_order DO
					LEFT JOIN dolepaper_dockticket dt
						ON dt.order_num = DO.order_num
					WHERE dt.dock_ticket = ct.bol
				) AS order_status
            FROM cargo_tracking ct
			LEFT JOIN customer_profile cp
				ON cp.customer_id = ct.receiver_id
            WHERE
                ct.pallet_id = '$palletId'
                AND ct.remark = 'DOLEPAPERSYSTEM' ";
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
            'canBeEdited' => (ociresult($stmt, 'ORDER_STATUS') < 2 || true),
            'weightLbs' => ociresult($stmt, 'WEIGHT_LBS'),
            'gradeCode' => ociresult($stmt, 'GRADE_CODE'),
            'bol' => ociresult($stmt, 'BOL'),
            'po' => ociresult($stmt, 'PO'),
			'dockTicket' => ociresult($stmt, 'DOCK_TICKET'),
			'customer' => new Customer(Array(
				'id' => ociresult($stmt, 'CUSTOMER_ID'),
				'name' => ociresult($stmt, 'CUSTOMER_NAME'),
			))
        ));
    }
    
    return $pallets;
}

function calculateDockTicket($po, $gradeCode, $bol)
{
	global $rfconn;
	
	$sql = "SELECT bol AS dock_ticket
			FROM cargo_tracking
			WHERE cargo_description = '$po $gradeCode $bol'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	if (ocifetch($stmt)) {
		return ociresult($stmt, 'DOCK_TICKET');
	}
	
	$sql = "SELECT MAX(bol) + 1 AS new_dock_ticket
			FROM cargo_tracking
			WHERE remark = 'DOLEPAPERSYSTEM'";
	$stmt = ociparse($rfconn, $sql);
	ociexecute($stmt);
	ocifetch($stmt);
	
	return ociresult($stmt, 'NEW_DOCK_TICKET');
}

class Pallet
{
    var $id;
    var $arrivalNum;
    var $canBeEdited;
    var $weightLbs;
    var $gradeCode;
    var $bol;
    var $po;
	var $dockTicket;
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
		
		
		//...do it manually
        $this->id = $initial['id'];
        $this->arrivalNum = $initial['arrivalNum'];
        $this->canBeEdited = $initial['canBeEdited'];
        $this->weightLbs = $initial['weightLbs'];
        $this->gradeCode = $initial['gradeCode'];
        $this->bol = $initial['bol'];
        $this->po = $initial['po'];
        $this->dockTicket = $initial['dockTicket'];
		$this->customer = $initial['customer'];
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
