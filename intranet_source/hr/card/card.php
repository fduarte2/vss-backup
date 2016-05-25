<?php

include 'functions.php';

$employeeIds = strtoupper($_GET['employeeIds']);
$allIds = convertToArray($employeeIds);

$employees = null;
if (!empty($allIds)) {
	$employees = getEmployees($allIds);
	$trainingTypes = getTrainingTypes();
}

?>

<!DOCTYPE html>

<html moznomarginboxes>
	<head>
		<title>Employee ID Card</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="card.css" type="text/css">
	</head>
	<body>
		<script>
			function showCardSide(side) {
				var hideSide = (side == 'front') ? 'back' : 'front';
				var showSide = (side == 'back') ? 'back' : 'front';
				
				document.querySelector('.button--' + showSide).disabled = true;
				document.querySelector('.button--' + hideSide).disabled = false;
				
				var toHide = document.getElementsByClassName('card--' + hideSide);
				for (i = 0; i < toHide.length; i++) {
					toHide[i].style.display = 'none';
				}
				
				var toShow = document.getElementsByClassName('card--' + showSide);
				for (i = 0; i < toShow.length; i++) {
					toShow[i].style.display = 'inline-block';
				}
			}
		</script>
		
		<div class="noprint">
			<a href="./?employeeIds=<?php echo implode(',', $allIds); ?>">&lt; Back</a>
			
			<h1>Printing Employee ID Cards</h1>
			<p>You must follow these instructions exactly in order to ensure correct results.
			
			<ol>
				<li>Make sure you are using Internet Explorer. If you're not, switch to it.
				<li>Set the page settings:
					<ul><li>click the <strong>gear</strong> at the top right
						<li>go to <strong>Print ></strong>
						<li>click <strong>Page setup</strong>
						<li>Make sure the settings in red match the screenshot below <em>exactly</em>.
							<img style="display: block" src="page-setup.png">
					</ul>
				<li>Make sure two-sided printing is disabled in the printer settings.
				<li>Insert the pop-out card stock into the printer.
				<li>Click <strong>Side 1</strong>, then click <strong>Print</strong>.
				<li>Remove the printed pages from the printer and rotate them <u>horizontally</u>, then re-insert them into the paper tray.
				<li>Click <strong>Side 2</strong>, then click <strong>Print</strong>.
			</ol>
			
			<hr>
			<div>
				<button type="button" class="button--front" onClick="showCardSide('front');" disabled>Side 1</button>
				<button type="button" class="button--back" onClick="showCardSide('back');">Side 2</button>
				<button class="button" type="button" onClick="window.print()">Print</button>
			</div>
		</div>
		
		<div class="cardholder cardholder--front"><?php
			foreach ($employees as $id => $employee) {
		  ?><div class="card card--front">
				<div class="card__logobox">
					<!--<img class="card__logo" src="/images/pow_square.png" height="50px">-->
					<span class="card__title">DSPC Worker's Eligibility Card</span>
				</div>
				<div class="card__name"><?php echo $employee['name']; ?></div>
				<div class="card__name">Employee No. <strong><?php echo $id; ?></strong></div>
				<br>
				<br>
				<img src="/functions/barcode.php?codetype=code39&size=20&text=<?php echo $id; ?>" width="240px">
			</div><?php
			}
	  ?></div>
		<div class="cardholder cardholder--back"><?php
			foreach ($employees as $id => $employee) {
		  ?><div class="card card--back">
				<div class="card__logobox">
					<img class="card__logo" src="/images/pow_square.png" height="50px">
					<span class="card__title">Diamond State Port Corporation</span>
				</div>
				<div class="card__issued"><strong><?php echo $employee['name']; ?></strong></div>
				<div class="card__issued"><strong>Date Issued:</strong> <?php echo date('Y-m-d'); ?></div>
				<div class="card__training">
					<strong>Pre-employment Training</strong>
					<ul>
					<?php foreach ($employee['trainingTypes'] as $type => $details) {
							  $name = $trainingTypes[$type];
					?>
						<li><?php echo $name; ?></li>
					<?php } ?>
					<?php if (!$employee['trainingTypes']) { ?>
						<li>None</li>
					<?php } ?>
					</ul>
				</div>
			</div><?php
			}
	  ?></div>
	</body>
</html>