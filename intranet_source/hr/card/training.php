<?php

if ($_POST['submit'] == 'save') {
	ob_start(); //because we don't want it to redirect until the changes are actually written
	header("Location: " . $_SERVER['SCRIPT_NAME']);
}

$title = "Edit Training Types";
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
	saveTrainingTypes($_POST['type']);
	ob_get_clean(); //finally, redirect
}

$trainingTypes = getTrainingTypes();
?>

<h1>Edit Pre-Employment Training Types</h1>

<p>Use this program to:</p>
<ul>
	<li>Add new types of pre-employment training.</li>
	<li>Rename existing types.</li>
</ul>
<p>To delete training types, please contact Technology Solutions.</p>

<form method="post" action="">
	<ul class="checkbox-list">
		<?php foreach ($trainingTypes as $type => $name) { ?>
		<li>
			<label>Type <?php echo $type . ': ' . $name; ?>
				<input class="long-input" type="text" maxlength="32" value="<?php echo $name; ?>" name="type[<?php echo $type; ?>]">
			</label>
		</li>
		<?php } ?>
		<li>
			<label>Add New
				<input class="long-input" type="text" maxLength="32" name="type[]">
			</label>
		</li>
	</ul>
	<a href="./" class="button">&lt; Back</a>
	<button class="button" type="submit" name="submit" value="save">Save</button>
</form>