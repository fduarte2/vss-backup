
<?php
// F. Duarte.  1/24/2014.  For Finance.  To be able to get the current number of pallets for over-the-road OTR cargo.

// Oracle connection //

$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER"); 
if (!$rf_conn) { 
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn)); 
	printf("Please report to TS!"); 
	exit; 
	}


$c2 = ora_open($rf_conn);
if (!$c2) { 
	$e = oci_error(); 
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR); 
	}

// Guimarra is Receiver Id 175.  And OTR is Receiving Type T

$count1 = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
			WHERE RECEIVER_ID = '175' 
			AND RECEIVING_TYPE = 'T' 
			AND DATE_RECEIVED IS NOT NULL"; 

$count2 = ora_parse($c2,$count1);
$count2 = ora_exec($c2, $count2);
ora_fetch_into($c2,$data_count, ORA_FECHINTO_NULLS|ORA_FETCHINTO_ASSOC);
echo "<b>"; echo "Total OTR Pallets for Giumarra for this season: "; 
echo "" .$data_count['THE_COUNT']. "" ; echo "</b>"; echo "<p>"; ;


// C1: move sql and related block down and keep together 
// C2: Why distinct? 
// C3: Use GROUP BY Pallet_id, Arrival Num for this customer 
// C4: No use for CARGO_ACTIVITY.  Remove. 
// C5: Use ORDER BY always when you have a list printed.   
// C6: Print in this order:  DATE_RECEIVED, PALLET_ID, COMMODITY_CODE, AND ARRIVAL_NUM (= ORDER_NUM in CARGO_ACTIVITY). 
// C7: Print a Header Line for the list.


$c1 = ora_open($rf_conn); 
if (!$c1) { 
	$e = oci_error(); 
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR); 
	}

$sql1 = "SELECT * FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '175' 
			AND RECEIVING_TYPE = 'T' 
			AND DATE_RECEIVED IS NOT NULL
			ORDER BY DATE_RECEIVED, PALLET_ID, ARRIVAL_NUM, COMMODITY_CODE"; 

$stid = ora_parse($c1, $sql1);
$stid = ora_exec($c1,$stid);

echo "<table border='1'>\n";
echo "<tr>"; 
echo "<td>"; 
echo "DATE RECEIVED"; 
echo "</td>"; 
echo "<td>";  
echo "PALLET ID"; 
echo "</td>"; 
echo "<td>";  
echo "ARRIVAL NUM"; 
echo "</td>"; 
echo "<td>";  
echo "COMMODITY CODE"; 
echo "</td>";
echo "</tr>";     

while(ora_fetch_into($c1,$data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ 
	echo "<tr>"; 
	echo "<td>"; 
	echo $data_row['DATE_RECEIVED']; 
	echo "</td>"; 
	echo "<td>";  
	echo $data_row['PALLET_ID']; 
	echo "</td>"; 
	echo "<td>";  
	echo $data_row['ARRIVAL_NUM']; 
	echo "</td>"; 
	echo "<td>";  
	echo $data_row['COMMODITY_CODE']; 
	echo "</td>";
	echo "</tr>"; 
	}    
echo "</table>";
?>

