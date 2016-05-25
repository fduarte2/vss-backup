<?
// Adam walter, Sept 2007.
// This program takes all data from
// postgres table cargo_detail, copies any data older than 3 months out to a backup table
// (named cargo_detail_backup_xxxxxx, with xxxxxx being the date of run)
// and then purges the data from cargo_detail.
// this is done so that the Cube generation procedure does not hog so much of s-17's
// resources as to slow down s-17's other major function, Oracle FinApps.

// as was determined by an attempt at this last year, you actually need to leave 1 months's data in
// the table for the cube to generate properly.  We leave 3 months as a safety buffer.

	include("connect_data_warehouse.php");

	$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
	if(!$pg_conn){
		die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
	}

	$sql = "create table cargo_detail_backup_070107 as (select * from cargo_detail where run_date < '2007-07-01')";
	$result = pg_query($pg_conn, $sql) or die("Error in create table query: $sql. " . pg_last_error($pg_conn));

	$sql = "delete from cargo_detail where run_date < '2007-07-01'";
	$result = pg_query($pg_conn, $sql) or die("Error in delete query: $sql. " . pg_last_error($pg_conn));

?>