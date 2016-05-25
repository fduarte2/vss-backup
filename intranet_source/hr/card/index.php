<?php

if ($_POST['submit'] == 'save') {
	ob_start(); //because we don't want it to redirect until the changes are actually written
	header("Location: /hr/card?employeeIds=" . implode(',', $_POST['employeeIds']));
}

$title = "Worker's Eligibility Card";
$area_type = "HRMS";
$useHtml5 = true;
include "pow_header.php";

if ($access_denied) {
	printf("Access Denied from the HRMS system");
	include "pow_footer.php";
	exit;
}

include "./functions.php";

if ($_POST['submit'] == 'save') {
	saveEmployees($_POST['trainingTypes'], $userdata['username']);
	ob_get_clean(); //finally, redirect
}

$employeeIds = strtoupper($_GET['employeeIds']);
$allIds = convertToArray($employeeIds);

$employees = null;
if (!empty($allIds)) {
	$employees = getEmployees($allIds);
}
// echo '<pre>'; print_r($employees); echo '</pre>';

if ($employees) {
	$trainingTypes = getTrainingTypes();
}

?>

<h1>DSPC Worker's Eligibility Card</h1>

<p>Use this program to:</p>
<ul>
	<li>Set completed pre-employment training for each employee.</li>
	<li>Batch print two-sided Employee ID/Pre-Employment Training cards.</li>
</ul>
<p>Completed pre-employment training for each employee is saved in the system.</p>

<form method="get" action="">
	<label>
		Enter a list of employee IDs<br>
		(separated by one or more commas, spaces, linebreaks, or semicolons)
		<textarea type="text"
				  name="employeeIds"
				  placeholder="Example: E12345,E654321,E425215"
				  rows="10"
				  cols="65"
		><?php echo implode(", ", $allIds); ?></textarea>
	</label>
	<button type="submit" class="button">
		<?php if (!$employees) { ?>Next ><?php } else { ?>Refresh<?php } ?>
	</button>
</form>

<a href="training.php">Edit Types of Pre-Employment Training</a>

<?php if ($employees) { ?>
<script>
function checkAll(event) {
	var checkedState = event.target.checked;
	var value = event.target.value;
	
	var x = document.getElementsByClassName('type' + value);
	for (i = 0; i < x.length; i++) {
		x[i].checked = checkedState;
	}
}

</script>

<form method="post" action="">
	<table class="aTable top">
		<thead>
			<th>Employee
			<th>Types of Training (<a href="training.php" target="_blank" title="Open in new tab">Edit</a>)
		</thead>
		<tbody>
			<?php if (count($employees) > 1) { ?>
			<tr>
				<td>
					<strong>ALL</strong>
					<div>Bulk actions</div>
				</td>
				<td>
					<ul class="checkbox-list">
					<?php foreach ($trainingTypes as $type => $name) { ?>
						<li>
							<label class="checkbox-label">
								<input type="checkbox"
									   value="<?php echo $type; ?>"
									   onClick="checkAll(event)">
								<?php echo $name; ?>
							</label>
						</li>
					<?php } ?>
					</ul>
				</td>
			</tr>
			<tr><td colspan="2"></tr>
			<?php } ?>
			<?php foreach ($employees as $id => $employee) {
				if ($employee) { ?>
			<tr>
				<td>
					<label>ID
						<div><?php echo $id; ?></div>
						<input type="hidden" name="employeeIds[]" value="<?php echo $id; ?>">
					</label>
					<br>
					<label>Name
						<div><?php echo $employee['name']; ?></div>
					</label>
					<!--<button class="button type="button" name="submit">Remove</button>-->
				</td>
				<td>
					<ul class="checkbox-list">
					<?php foreach ($trainingTypes as $type => $name) { ?>
						<li>
							<label class="checkbox-label">
								<input type="checkbox"
									   class="type<?php echo $type; ?>"
									   name="trainingTypes[<?php echo $id; ?>][]"
									   value="<?php echo $type; ?>"
									   <?php if ($employee['trainingTypes'][$type]) { ?> checked<?php } ?>
								>
								<?php echo $name; ?>
								<?php if ($employee['trainingTypes'][$type]) { ?>
										(added by <em><?php echo $employee['trainingTypes'][$type]['addedBy']; ?></em>
										on <em><?php echo $employee['trainingTypes'][$type]['dateAdded']; ?></em>)
								<?php } ?>
							</label>
						</li>
					<?php } ?>
					</ul>
				</td>
			</tr>
			<?php } else { ?>
			<tr>
				<td colspan="2">
					<div class="error">ID <strong><?php echo $id; ?></strong> is not a valid employee ID.</div>
				</td>
			</tr>
			<?php }
			} ?>
		</tbody>
	</table>
	
	<p>Don't forget to save!</p>
	<!--<a class="button" href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">&lt; Back</a>-->
	<button type="submit" class="button" name="submit" value="save">Save</button>
	<a class="button" href="./card.php?employeeIds=<?php echo implode(', ', $allIds); ?>">
		Print Batch >
	</a>
</form>
<?php } ?>

<?php include "pow_footer.php"; ?>
