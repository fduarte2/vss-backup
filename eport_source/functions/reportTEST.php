<?
// 05/14/03 - Created by Lynn F. Wang
// - Then Changed for RF on 28-OCT-03 - STM
// Description: Creates a table show lots' information pulled from RF.CCD_CARGO_TRACKING for a specific
// ship, and/or mark, and/or a specific customer.  

// check if it is an authenticated user
$user = $HTTP_COOKIE_VARS["eport_user"];
if ($user == "") {
  header("Location: ../rf_login.php");
  exit;
}

function UnixToTimecount ($RawTime)
{
	$temp = $rawtime / 86400;
	echo $temp."D ";
	$rawtime = $rawtime % 86400;
	$temp = $rawtime / 3600;
	echo $temp."H ";
	$rawtime = $rawtime % 3600;
	$temp = $rawtime / 60;
	echo $temp."M ";
}


// get cookie and form values ($ship, $condense and $mark)
$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

list($lr_num, $vessel_name) = split("-", $ship, 2);

$today = date("F j, Y, g:i A");


// get the lr number from reference


// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create three cursors
$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}		

$cursor2 = ora_open($ora_conn);
if (!$cursor2) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor2));
  exit;
}		

$cursor3 = ora_open($ora_conn);
if (!$cursor3) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor3));
  exit;
}		


if ($eport_customer_id != 0)
{

if(isset($PATH_INFO)) { 
	print $PATH_INFO;
	$lr_num = explode('/', substr($PATH_INFO,1));    

    $lr = $vardata[1];

}
//    get the vesselname from vessel Profile

if ($lr)
	{
	$stmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '$lr'";
	$ora_success = ora_parse($cursor2, $stmt);
	$ora_success = ora_exec($cursor2);

	ora_fetch_into($cursor2, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel_name = $row1['VESSEL_NAME'];
	ora_close($cursor2);
	}

  $stmt = "SELECT PALLET_ID, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received
FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '$lr' AND SHIPPING_LINE = '8091' AND RECEIVER_ID <> '453' AND DATE_RECEIVED IS NOT NULL";

}

//print $stmt;

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CARGO_TRACKING and 
	  CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

// initialize running totals
$qty_expected_total = 0;
$qty_received_total = 0;
$qty_short_total = 0;
$qty_over_total = 0;
$qty_damaged_total = 0;
$pallets_total = 0;
$pallets_scanned = 0;

// 2 variables to hold time between shipment in and last case shipped out
$dwell_time_unix;
$dwell_time_readable;
// 2 variables to hold Unix time representations, created to be used to make $dwell_time
$date_received_unixtime;
$date_shipped_unixtime;
?>

<html>
<head>
<meta http-equiv="Refresh" content="500">
<title>Eport - Vessel Discharge Reports</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
                  <?
                  // write out the intro and print the header
                  if ($condense == "On") {
		    printf("<br />Port of Wilmington Condensed Vessel Discharge Report");
		  } else {
		    printf("<br />Port of Wilmington Vessel Discharge Report");
		  }
                  ?>
	          </font>
	          <br />
	          <hr color="green">
	          <br />
                  <font size = "3" face="Verdana" color="#0066CC"><?= $lr ?> - <?= $vessel_name ?></font>
	          <br /><br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
		  <?
		  if ($rows1 == 0) {
		    if ($eport_customer_id == 0) {
		  ?>		      
		  <font size = "2" face="Verdana">No customers have cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?> Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>
		  <?
		    } else {
		  ?>
		  <font size = "2" face="Verdana"><?= $user ?> does not have any cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>

		  <?
		    }
		    exit;
		  } // rows == 0
                 
		  if ($eport_customer_id != 0) {
		  ?>
		     <caption align="center">
		       <font size = "3" face="Verdana" color="#0066CC">Exporter: <?= $eport_customer_id ?></font>
		     </caption>
		  <?
		  }
          ?>
		  
		  <table width="85%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
		     <tr>
			<th ><font size = "2" face="Verdana">Pallet ID</font></th>
			<th ><font size = "2" face="Verdana">QTY Received</font></th>
			<th ><font size = "2" face="Verdana">QTY Damaged</font></th>
			<th ><font size = "2" face="Verdana">QTY In House</font></th>
			<th ><font size = "2" face="Verdana">Date Received</font></th>
			<th ><font size = "2" face="Verdana">Date Shipped</font></th>
			<th ><font size = "2" face="Verdana">Dwell Time</font></th>
		     </tr>
	       <?
		 do {
                   $pallets_total++;
                   $qty_expected = 0;                  
                   $qty_received = $row1['QTY_RECEIVED'];
                   $qty_damaged = $row1['QTY_IN_HOUSE'];
                   $date_received = $row1['DATE_RECEIVED'];
		  
                   // Not Received
                   if($row1['DATE_RECEIVED'] == ""){
                     $date_received = "Not Scanned...";
                   }
                   else{
                     $qty_received_total += $row1['QTY_RECEIVED'];
                     $pallets_scanned++;
                   }
                   // Damages			                     
				   $qty_damaged_total += $row1['QTY_DAMAGED'];
                   if($row1['QTY_DAMAGED'] == "0"){
                     $qty_damaged = "";
                   }
                   else{
                     $qty_damaged = $row1['QTY_DAMAGED'];
                   }
                   // IN house			                     
				   $qty_in_house_total += $row1['QTY_IN_HOUSE'];
                   if($row1['QTY_IN_HOUSE'] == "0"){
                     $qty_in_house = "0";
                   }
                   else{
                     $qty_in_house = $row1['QTY_IN_HOUSE'];
                   }
                   
// In the event that $qty_in_house = 0, display a last column which shows the difference
// between the arrival date and the last-out shipment date.  I'm leaving this separate
// from the above loop for simplicity's sake.

				   if($row1['QTY_IN_HOUSE'] == "0"){
					    $temp = $row1['PALLET_ID'];
						$stmt = "SELECT to_char(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI:SS') DATE_OF_ACTIVITY FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '$lr' AND 		PALLET_ID = '$temp' AND SERVICE_CODE = '6' ORDER BY DATE_OF_ACTIVITY DESC";
						$ora_success = ora_parse($cursor3, $stmt);
						$ora_success = ora_exec($cursor3);
						ora_fetch_into($cursor3, $outtime, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$date_shipped_unixtime = strtotime($outtime['DATE_OF_ACTIVITY']);
				   }


		   // change the received String into Time format for comparison
		   $date_received_unixtime = strtotime($date_received);
		   // and some calculations
		   $dwell_time_unix = $date_shipped_unixtime - $date_received_unixtime;


                   // Pallet was not expected	  

//			<td><font size = "1" face="Verdana"><?= $row1['COMMODITY'] 
			?></font></td>			
	          <tr>
				<td><font size = "1" face="Verdana"><?= $row1['PALLET_ID'] ?></font></td>
				<td align="center"><font size = "1" face="Verdana"><?= $qty_received ?></font></td>
				<td align="center"><font size = "1" face="Verdana" color="red"><?= $qty_damaged ?></font></td>
				<td align="center"><font size = "1" face="Verdana" color="red"><?= $qty_in_house ?></font></td>
				<td align="center"><font size = "1" face="Verdana"><?= $date_received ?></font></td>
				<td align="center"><font size = "1" face="Verdana"><?= $outtime['DATE_OF_ACTIVITY'] ?></font></td>
				<td align="center"><font size = "1" face="Verdana"><?= UnixToTimecount($dwell_time_unix) ?></font></td>

		     </tr>
	         <?                    
		   
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       ?>
		<tr> </tr>
		<tr>
				<td><font size = "2" face="Verdana">TOTALS: <?= $pallets_total ?> PLTS</font></td>
				<td align="center"><font size = "2" face="Verdana"><?= $qty_received_total ?> CASES RCVD</font></td>
				<td align="center"><font size = "2" face="Verdana" color="red"><?= $qty_damaged_total ?> CASES DMGD</font></td>
				<td align="center"><font size = "2" face="Verdana" color="red"><?= $qty_in_house_total ?> CASES IN HOUSE</font></td>
				<td align="center"><font size = "2" face="Verdana"></font></td>
				<td align="center"><font size = "2" face="Verdana"></font></td>
				<td align="center"><font size = "2" face="Verdana"></font></td>

		</tr>
		  </table>
	          <br /><br />
			 
	          <hr>
	          <font size = "2" face="Verdana">&copy;2006 Port of Wilmington, DE, Diamond State Port 
                  Corporation. All Rights Reserved.</font>
	       </td>
	    </tr>
		
         </table>
      </td>
   </tr>
</table>


</body>
</html>

