<?
// File: run_prebill_process.php
//
// Lynn F. Wang  (08-DEC-03)
// This program generates a PDF file of the prebills of the selected billing type.

// check user authorization
include("pow_session.php");
$user = $userdata['username'];

// Get form data
$billing_type = $HTTP_POST_VARS["selected_type"];

header("Location: run_prebill.php");

?>
