<?
/*
*  Created by Adam Walter, Feb 2007.
*  There are a messload of hard-coded reports out there, and they need to go away.
*  I've created a new "main" page that I will use to instead assign customer and commodity
*  availability from a table in postgres, and then create this page.
*  Currently, only single-customer and single-commodity choices are expected by this page.
*  May the divinity of your choice have mercy on our souls.
*
*  Feb 25, 2007
*  I find myself getting tired of this... but now I have to completely remove CLM_SIZEMAP
*  From the SQL statements, and replace it with CLM_SIZEMAP_EPORT, which doesn't
*  Have all of the same fields.  And then I get to figure out how to make it work.
*
*  March 9, 2007
*  This report is being separated from the Eport one, as Eport now wants a totally different
*  set of search criteria than the Inventory area does.  As such, I am converting this file
*  To work on dspc-s16, and deleting this file from Eport (where another report still exists).
************************************************************************************************/
/*   include("set_values.php");
   $user = $HTTP_COOKIE_VARS["eport_user"];
   $type = $HTTP_COOKIE_VARS["eport_user_type"];
   $eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

   if($user == "" || $type != "CLEMENTINE"){
      header("Location: ../clementine_login.php");
      exit;
   }
*/

/*  // open a connection to the database server
  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");

  if(!$connection){
    printf("Could not open connection to database server.  Please go back to <a href=\"../bni_login.php\">BNI Login Page</a> and try again later!<br />"); 
    exit;
   }
*/
  $customer_list = $HTTP_POST_VARS['customer_list'];
//  echo $customer_list."bb";
  $commodity_choice = $HTTP_POST_VARS['commodity_choice'];
  $report_type = $HTTP_POST_VARS['report_type'];

  if($customer_list == '440'){
	  $incoming_search = "CONTAINER_ID";
	  $report_heading = "Container";
  } else {
	  $incoming_search = "ARRIVAL_NUM";
	  $report_heading = "Vessel";
  }

  if($report_type == "all"){
	  $main_title = "(Full Report)";
  } else {
	  $main_title = "(Totals Only)";
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

/*  $BNIconn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($BNIconn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($BNIconn));
      	exit;
  }
  $BNIcursor = ora_open($BNIconn); */

	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity_choice."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$report_title = $row['COMMODITY_NAME'];



?>
<html>
<head>
<title>Eport of Wilmington - Clementine Inventory</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>
		<td align="center"><font size="3" face="Verdana"><b>Current + Original <? echo $report_title; ?> Inventory <? echo $main_title; ?></b></font></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
	<td align="center">
	<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
<?

/*
  $filename = "ClementineReport.xls";
  $handle = fopen($filename, "w");
  if (!$handle){
	echo "File ".$filename." could not be opened, please contact TS.\n";
	exit;
  } */ ?> <!-- <td align="center"><font size="2" face="Verdana">Alternately, you may <a href="<? echo $filename; ?>">Click Here</a> To download a tab-delimited Excel version of this report <BR>(be sure to Right-Click and choose "Save As" to obtain the latest version of the report; Left-Clicking may cause your Browser to show an older, cached version of the report)</font></td> !-->
  </tr>
<!--  <tr>
	<td>&nbsp;<BR>&nbsp;<BR>&nbsp;</td>
  </tr> !-->


<?


// first we do the overall totals

// first thing to do is get the maximum number of columns necessary for the final viewable output table.
	$current_row_color = "#FFFFFF"; // Alternate row colors mid-code.
	$current_table_cols = 0; // align TD's for given tables
	$max_cols = 2; // one for the Exporter row, one for the Total row
	$sql = "SELECT DISTINCT CS.PRINTED_SIZE DESCRIP FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND ARRIVAL_NUM NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			ORDER BY DESCRIP"; 
			// EXACT SAME sql as the one used later on
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$max_cols++;
	}







// GINOURMOUS if statement here.  basically, from previous-screen user input, either they want to see everything, or just totals.
// There will be no "else" statement attached to this, as the final area (totals) prints regardless.

if($report_type == "all"){






// we have, in one file, to break down the report by vessels (with a total at the end).
// first, we get the list of vessels that have current inventory...
// (vessel 4321 is excluded because it is what TS uses as a "test" vessel, the other ships because some people have no idea
// how to scan properly, and it falls to TS to fix their mistakes.

	$distinct_vessels = array();
	$sql = "SELECT DISTINCT ".$incoming_search.", MIN(DATE_RECEIVED) THE_DATE FROM CARGO_TRACKING
			WHERE QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND EXPORTER_CODE IS NOT NULL
			AND COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			GROUP BY ".$incoming_search."
			ORDER BY THE_DATE";
//	echo $sql."aa";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($incoming_search == "ARRIVAL_NUM"){
			array_push($distinct_vessels, $row['ARRIVAL_NUM']);
		} else {
			array_push($distinct_vessels, $row['CONTAINER_ID']);
		}
	}

// MAJOR SECTION - Per-vessel
// first, we do all the stuff per vessel...
for($counter = 0; $counter < sizeof($distinct_vessels); $counter++){
	$current_vessel = $distinct_vessels[$counter];

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS IS NULL
			AND ".$incoming_search." = '".$current_vessel."'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}

// Section header
	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$current_vessel."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	/*fwrite($handle, "Report for Vessel ".$row['VESSEL_NAME']." (Vessel #".$current_vessel.") Good Inventory\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>Report for <? echo $row['VESSEL_NAME']; ?> (<? echo $report_heading; ?> #<? echo $current_vessel; ?>) Good Inventory</b></td></tr><?

// the empty box in the top left
	/*fwrite($handle, "\t"); */?> <tr bgcolor="45ba6f"><td>&nbsp;</td> <?

// print the column headers in order
	$header_order_array = array();
	$current_table_cols = 2;
	$sql = "SELECT DISTINCT CS.PRINTED_SIZE DESCRIP FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND ".$incoming_search." = '".$current_vessel."'
			ORDER BY LENGTH(CS.PRINTED_SIZE), DESCRIP";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the column headers into the file, and store them in order
		array_push($header_order_array, $row['DESCRIP']);
		/*fwrite($handle, $row['DC']."\t"); */?><td><? echo $row['DESCRIP']; ?></td><?
		$current_table_cols++;
	}
// add the totals column header 
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?


// now, each line.  Good Inventory has a CARGO_STATUS of NULL
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the code, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		$current_exporter_total_original = 0;

		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter." Original"; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
// echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total_original += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
			/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total_original; ?></td></tr><?
		} else {
			?><td colspan="<? echo $current_table_cols-1; ?>">No Good Original Entries</td></tr><?
		}

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter." Current"; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		// echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
			/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
		} else {
			?><td colspan="<? echo $current_table_cols-1; ?>">No Good Current Entries</td></tr><?
		}
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $current_table_cols; ?>">&nbsp;</td></tr><tr bgcolor="#38B0DE"><td>ORIGINAL TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Good Entries for this vessel\n"); */?><td colspan="<? echo $current_table_cols-1; ?>">No Original Good Entries for this <? echo $report_heading; ?></td></tr><?
	}

	/*fwrite($handle, "\nTOTALS\t"); */?><tr bgcolor="#99CCFF"><td>CURRENT TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Good Entries for this vessel\n"); */?><td colspan="<? echo $current_table_cols-1; ?>">No Good Current Entries for this <? echo $report_heading; ?></td></tr><?
	}
	/*fwrite($handle, "\n\n"); */?><tr><td colspan="<? echo $current_table_cols; ?>"><font size="5">&nbsp;</font></td></tr><?


// Next up, Hospital product.  Hospital product has a CARGO_STATUS of H
// we already have a $header_order_array, so just print it out, along with title
	/*fwrite($handle, "Report for Vessel ".$row['VESSEL_NAME']." (Vessel #".$current_vessel.") Hospital\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>Report for <? echo $row['VESSEL_NAME']; ?> (<? echo $report_heading; ?> #<? echo $current_vessel; ?>) Hospital</b></td></tr><?

	/*fwrite($handle, "\t"); */?><tr bgcolor="45ba6f"><td>&nbsp;</td><?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'H'
			AND ".$incoming_search." = '".$current_vessel."'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}

// now, each line.  Hospital has a CARGO_STATUS of H
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the ode, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'H'
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $current_table_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>CURRENT TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'H'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td><?
	} else {
		/*fwrite($handle, "\tNo Hospital Entries for this vessel\n"); */?><td colspan="<? echo $current_table_cols-1; ?>">No Hospital Entries for this <? echo $report_heading; ?></td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $current_table_cols; ?>"><font size="10">&nbsp;</td></tr><?


// Last up, Regrade product.  Regrade product has a CARGO_STATUS of R
// we already have a $header_order_array, so just print it out, along with title
	/*fwrite($handle, "Report for Vessel ".$row['VESSEL_NAME']." (Vessel #".$current_vessel.") Regrade\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>Report for <? echo $row['VESSEL_NAME']; ?> (<? echo $report_heading; ?> #<? echo $current_vessel; ?>) Regrade</b></td></tr><?

	/*fwrite($handle, "\t"); */?><tr bgcolor="45ba6f"><td>&nbsp;</td><?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?


// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'R'
			AND ".$incoming_search." = '".$current_vessel."'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}

// now, each line.  Regrade has a CARGO_STATUS of R
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the ode, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'R'
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $current_table_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>CURRENT TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'R'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td><?
	} else {
		/*fwrite($handle, "\tNo Regrade Entries for this vessel\n"); */?><td colspan="<? echo $current_table_cols-1; ?>">No Regrade Entries for this <? echo $report_heading; ?></td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $current_table_cols; ?>"><font size="10">&nbsp;</td></tr><?


// REALLY Last up, Regrade +Hospital product.  Regrade+Hospital product has a CARGO_STATUS of RH
// we already have a $header_order_array, so just print it out, along with title
	/*fwrite($handle, "Report for Vessel ".$row['VESSEL_NAME']." (Vessel #".$current_vessel.") Regrade + Hospital\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>Report for <? echo $row['VESSEL_NAME']; ?> (<? echo $report_heading; ?> #<? echo $current_vessel; ?>) Regrade + Hospital</b></td></tr><?

	/*fwrite($handle, "\t"); */?><tr bgcolor="45ba6f"><td>&nbsp;</td><?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?


// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'RH'
			AND ".$incoming_search." = '".$current_vessel."'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}

// now, each line.  Regrade has a CARGO_STATUS of R
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the ode, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'RH'
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $current_table_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>CURRENT TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS = 'RH'
				AND ".$incoming_search." = '".$current_vessel."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td><?
	} else {
		/*fwrite($handle, "\tNo Regrade Entries for this vessel\n"); */?><td colspan="<? echo $current_table_cols-1; ?>">No Regrade + Hospital Entries for this <? echo $report_heading; ?></td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $current_table_cols; ?>"><font size="10">&nbsp;</td></tr><?

}

}








// MAJOR SECTION
// Now, we do all-vessel totals

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS IS NULL
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}

// section header
	/*fwrite($handle, "All-Vessel TOTALS (Good Inventory)\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>TOTALS (Good Inventory)</b></td></tr><?
// the empty box in the top left
	/*fwrite($handle, "\t"); */?><tr bgcolor="45ba6f"><td>&nbsp;</td> <?

// print the column headers in order 
// (note that we leave out the "CARGO_STATUS IS NULL" portion for this)
// this is so that the columns per ship match up, zero's or not.
	$header_order_array = array();
	$sql = "SELECT DISTINCT CS.PRINTED_SIZE DESCRIP FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND RECEIVER_ID = '".$customer_list."'
			ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the column headers into the file, and store them in order
		array_push($header_order_array, $row['DESCRIP']);
		/*fwrite($handle, $row['DC']."\t"); */?> <td><? echo $row['DESCRIP']; ?></td> <?
	}
// add the totals column header 
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?



// now, each line.  Good inventory has a CARGO_STATUS of NULL
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the code, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		$current_exporter_total_original = 0;

		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter." Original"; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
// echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total_original += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
			/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total_original; ?></td></tr><?
		} else {
			?><td colspan="<? echo $max_cols-1; ?>">No Good Original Entries</td></tr><?
		}

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter." Current"; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		// echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
			/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
		} else {
			?><td colspan="<? echo $max_cols-1; ?>">No Good Current Entries</td></tr><?
		}
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $max_cols; ?>">&nbsp;</td></tr><tr bgcolor="#38B0DE"><td>ORIGINAL TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Good Entries for this vessel\n"); */?><td colspan="<? echo $max_cols-1; ?>">No Original Good Entries</td></tr><?
	}

	/*fwrite($handle, "\nTOTALS\t"); */?><tr bgcolor="#99CCFF"><td>CURRENT TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND CARGO_STATUS IS NULL
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Good Entries for this vessel\n"); */?><td colspan="<? echo $max_cols-1; ?>">No Good Current Entries</td></tr><?
	}
	/*fwrite($handle, "\n\n"); */?><tr><td colspan="<? echo $current_table_cols; ?>"><font size="5">&nbsp;</font></td></tr><?

// now do the same thing, but for Hospital stuff (CARGO_STATUS == 'H')
// we already have an in-order $header_order_aray, no need to recreate it, just reprint it.
// also, a few "if" clauses have been added to amke sure any hospital stuff even exists.

	/*fwrite($handle, "All-Vessel TOTALS (Hospital)\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>TOTALS (Hospital)</b></td></tr><?
	/*fwrite($handle, "\t"); */?> <tr bgcolor="45ba6f"><td>&nbsp;</td> <?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'H'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}


// now, each line.  Hospital inventory has a CARGO_STATUS of H
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the code, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'H'
				AND RECEIVER_ID = '".$customer_list."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $max_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'H'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Hospital Entries\n"); */?><td colspan="<? echo $max_cols-1; ?>">No Hospital Entries</td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $max_cols; ?>"><font size="10">&nbsp;</td></tr><?

// now do the same thing, but for Regrade stuff (CARGO_STATUS == 'R')
// we already have an in-order $header_order_aray, no need to recreate it, just reprint it.
// also, a few "if" clauses have been added to amke sure any hospital stuff even exists.

	/*fwrite($handle, "All-Vessel TOTALS (Regrade)\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>TOTALS (Regrade)</b></td></tr><?
	/*fwrite($handle, "\t"); */?> <tr bgcolor="45ba6f"><td>&nbsp;</td> <?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'R'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}


// now, each line.  Regrade inventory has a CARGO_STATUS of R
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the code, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'R'
				AND RECEIVER_ID = '".$customer_list."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $max_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'R'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Regrade Entries\n"); */?><td colspan="<? echo $max_cols-1; ?>">No Regrade Entries</td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $max_cols; ?>"><font size="10">&nbsp;</td></tr><?
	

	
// lastly do the same thing, but for Regrade+Hospital stuff (CARGO_STATUS == 'RH')
// we already have an in-order $header_order_aray, no need to recreate it, just reprint it.
// also, a few "if" clauses have been added to amke sure any hospital stuff even exists.

	/*fwrite($handle, "All-Vessel TOTALS (Regrade)\n"); */?><tr bgcolor="ba4b45"><td colspan="<? echo $max_cols; ?>"><b>TOTALS (Regrade + Hospital)</b></td></tr><?
	/*fwrite($handle, "\t"); */?> <tr bgcolor="45ba6f"><td>&nbsp;</td> <?
	for($i = 0; $i < sizeof($header_order_array); $i++){
		/*fwrite($handle, $header_order_array[$i]."\t"); */?><td><? echo $header_order_array[$i]; ?></td><?
	}
	/*fwrite($handle, "TOTALS\n"); */?><td>TOTALS</td></tr><?

// we get the array of rows
	$row_array = array();
	$sql = "SELECT DISTINCT CT.EXPORTER_CODE EXPCODE FROM CLM_SIZEMAP_EPORT CS, CARGO_TRACKING CT
			WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
			AND CT.EXPORTER_CODE IS NOT NULL
			AND CT.QTY_IN_HOUSE > 20
			AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
			AND CT.COMMODITY_CODE = '".$commodity_choice."'
			AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
			AND RECEIVER_ID = '".$customer_list."'
			AND CARGO_STATUS = 'RH'
			ORDER BY EXPCODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// get the row headers into the array
		array_push($row_array, $row['EXPCODE']);
	}


// now, each line.  Regrade inventory has a CARGO_STATUS of R
// get the initial value, then only fetch next if previous is printed.
// this is the key place where it is important that all ORDER BY clauses are exactly the same.
// Read the code, you'll see why.

	for($temp = 0; $temp < sizeof($row_array); $temp++){

		if($current_row_color == "#FFFFFF"){
			$current_row_color = "#F0F0F0";
		} else {
			$current_row_color = "#FFFFFF";
		}	?> <tr bgcolor="<? echo $current_row_color; ?>"> <?

		$current_exporter = $row_array[$temp];
		$current_exporter_total = 0;
		/*fwrite($handle, $current_exporter."\t"); */?><td><? echo $current_exporter; ?></td><?
		$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND CT.EXPORTER_CODE = '".$current_exporter."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'RH'
				AND RECEIVER_ID = '".$customer_list."'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
				if($row['DESCRIP'] == $header_order_array[$columncounter]){
					/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
					$current_exporter_total += $row['THE_COUNT'];
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				} else {
					/*fwrite($handle, "0\t"); */?><td>0</td><?
				}
			}
		}
		/*fwrite($handle, $current_exporter_total."\n"); */?><td><? echo $current_exporter_total; ?></td></tr><?
	}

// finally, the underlying totals
	/*fwrite($handle, "\nTOTALS\t"); */?><tr><td colspan="<? echo $max_cols; ?>">&nbsp;</td></tr><tr bgcolor="#99CCFF"><td>TOTALS</td><?
	$grand_total = 0;
	$sql = "SELECT CS.PRINTED_SIZE DESCRIP, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, CLM_SIZEMAP_EPORT CS 
				WHERE SUBSTR(CT.PALLET_ID, 10, 4) = CS.FFM
				AND CT.EXPORTER_CODE IS NOT NULL
				AND CT.QTY_IN_HOUSE > 20
				AND RECEIVER_ID = '".$customer_list."'
				AND DATE_RECEIVED > to_date('09/01/2006', 'MM/DD/YYYY')
				AND CT.COMMODITY_CODE = '".$commodity_choice."'
				AND ".$incoming_search." NOT IN ('4321', '9481', '9499', '9507', '9515', '9521', '9529', '9539', '9548')
				AND CARGO_STATUS = 'RH'
				GROUP BY CS.PRINTED_SIZE
				ORDER BY LENGTH(CS.PRINTED_SIZE), CS.PRINTED_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		for($columncounter = 0; $columncounter < sizeof($header_order_array); $columncounter++){
			if($row['DESCRIP'] == $header_order_array[$columncounter]){
				/*fwrite($handle, $row['THE_COUNT']."\t"); */?><td><? echo $row['THE_COUNT']; ?></td><?
				$grand_total += $row['THE_COUNT'];
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			} else {
				/*fwrite($handle, "0\t"); */?><td>0</td><?
			}
		}
		/*fwrite($handle, $grand_total."\n"); */?><td><? echo $grand_total; ?></td></tr><?
	} else {
		/*fwrite($handle, "\tNo Regrade Entries\n"); */?><td colspan="<? echo $max_cols-1; ?>">No Regrade + Hospital Entries</td></tr><?
	}
	/*fwrite($handle, "\n\n\n"); */?><tr><td colspan="<? echo $max_cols; ?>"><font size="10">&nbsp;</td></tr><?


	
	/*fclose($handle);*/

?>
</table></td></tr></table></body></html>
<?
