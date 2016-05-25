<?php
// All POW files need this session file included
include "pow_session.php";

// Define some vars for the skeleton page
$title = "Human Resources Applications Access";
$area_type = "HRMS";

// Provides header / leftnav
include "pow_header.php";
if ($access_denied) {
	printf("Access Denied from HRMS system");
	include "pow_footer.php";
	exit;
}
?>

<h1>HRMS Applications</h1>

<p>From here you can access HR applications.

<ul>
	<li><a href="SUPVRevApp.php">Supervisor Time Review and Approval</a>
	<li><a href="/finance/vessel_arrival_notify.php">Barge Arrival Notification</a>
	<li><a href="ATSpages/ATS_AT_Emp.php">Add Employee Information to ATS</a>
	<li><a href="ATSpages/AT_EMP_edit.php">Modify Employee Information in ATS</a>
	<li><a href="ATSpages/ATS_unapprove.php">Unapprove Timesheets in ATS</a>
	<li><a href="ATSpages/anniversary_table.php">Manage Anniversary Notification Dates</a>
	<li><a href="ATSpages/ATS_crosscheck_rerun.php">Re-run Crosscheck Spreadsheet</a>
	<li><a href="hires/weekly_hires.php">Weekly Hire Summary</a>
	<li><a href="pipescan_report/PipeScannerUpload.php">Pipescanner Upload</a>
	<li><a href="ST_to_OT_upgrade.php">MLK Update To Overtime</a>
	<li><a href="security_gate/NONTWICautorefresh.php">NONTWIC Barcode Monitor</a>
	<li><strong><a href="LCSpages/index.php">LCS Rate Manager</a></strong>
	<li><a href="http://172.22.15.98/ADP_payroll_file_convert_and_email.php">ADP Pension File Upload to State of Delaware</a>
	<li><a href="unexcused_absent.php">Unexcused Absences</a>
	<li><a href="card/">Eligibility/Training Card</a>

<?php include "pow_footer.php"; ?>
