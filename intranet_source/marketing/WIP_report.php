<?
/*
*	Screen that lets MRKT look through INV's WIP files.
*	Jul 2012.
********************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "WIP File";
  $area_type = "MKTG";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Marketing system");
    include("pow_footer.php");
    exit;
  }

//	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
																	
   $submit = $HTTP_POST_VARS['submit'];
   $vessel = $HTTP_POST_VARS['vessel'];
   $comm = $HTTP_POST_VARS['comm'];
   $cust = $HTTP_POST_VARS['cust'];





?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">WIP file Report</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($submit == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_rep" action="WIP_report.php" method="post">
	<tr>
		<td><font size="3" face="Verdana">Vessel:</font></td>
			<td><select name="vessel">
				<option value="">All</option>
<? 
		$sql = "SELECT VP.LR_NUM, VESSEL_NAME, TO_CHAR(MAX(UPLOAD_TIME), 'MM/DD/YYYY HH24:MI:SS') THE_TIME
				FROM VESSEL_PROFILE VP, WIP_UPLOADS WU
				WHERE VP.LR_NUM = WU.LR_NUM
				AND WU.VALID = 'Y'
				GROUP BY VP.LR_NUM, VESSEL_NAME
				ORDER BY VP.LR_NUM DESC";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			// no WIPs.
		} else {
			do {
?>
			<option value="<? echo ociresult($stid, "LR_NUM"); ?>"><? echo ociresult($stid, "VESSEL_NAME")." (Last Uploaded ".ociresult($stid, "THE_TIME"); ?></option>
<?
			} while(ocifetch($stid));
		}
?>
				</select></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Commodity:</font></td>
			<td><select name="comm">
				<option value="">All</option>
<? 
		$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
				FROM COMMODITY_PROFILE
				ORDER BY COMMODITY_CODE ASC";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			// no comms...?
		} else {
			do {
?>
			<option value="<? echo ociresult($stid, "COMMODITY_CODE"); ?>"><? echo ociresult($stid, "COMMODITY_NAME"); ?></option>
<?
			} while(ocifetch($stid));
		}
?>
				</select></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Customer:</font></td>
			<td><select name="cust">
				<option value="">All</option>
<? 
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
				FROM CUSTOMER_PROFILE
				ORDER BY CUSTOMER_ID ASC";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			// no custs....?
		} else {
			do {
?>
			<option value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>"><? echo ociresult($stid, "CUSTOMER_NAME"); ?></option>
<?
			} while(ocifetch($stid));
		}
?>
				</select></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Sort File"></td>
	</tr>
</form>
<?
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT * FROM EXCEL_MANIFEST_SORT EMU,
						(SELECT LR_NUM, TO_CHAR(MAX(UPLOAD_TIME), 'MM/DD/YYYY HH24:MI:SS') THE_TIME
						FROM WIP_UPLOADS 
						WHERE VALID = 'Y'
						GROUP BY LR_NUM) WP
				WHERE EMU.LR_NUM = WP.LR_NUM";
		if($vessel != ""){
			$sql .= " AND EMU.LR_NUM = '".$vessel."'";
		}
		if($comm != ""){
			$sql .= " AND COMMODITY_CODE = '".$comm."'";
		}
		if($cust != ""){
			$sql .= " AND CUSTOMER_ID = '".$cust."'";
		}
		$sql .= " ORDER BY EMU.LR_NUM, CUSTOMER_ID, COMMODITY_CODE";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr><td><font size="3" face="Verdana">No uploaded WIP files match the searched criteria.</font></td></tr>
<?
		} else {
			$filename = "./safe_to_delete_tempfiles/WIPreport".date('mdYhis').".xls";
			$handle = fopen($filename, "w");
			if (!$handle){
				echo "File ".$filename." could not be opened, please contact TS.\n";
				exit;
			}

			$output = "<table>					
							<tr>
								<td align=\"center\">Shipping Line Name</td>
								<td align=\"center\">Shipping Line #</td>
								<td align=\"center\">Port</td>
								<td align=\"center\">Customer ID</td>
								<td align=\"center\">Customer Name</td>
								<td align=\"center\">BoL</td>
								<td align=\"center\">Other BoL</td>
								<td align=\"center\">Commodity Code</td>
								<td align=\"center\">Commodity Name</td>
								<td align=\"center\">PLTS</td>
								<td align=\"center\">CTNS</td>
								<td align=\"center\">KGS</td>
								<td align=\"center\">LBS</td>
								<td align=\"center\">Containers</td>
								<td align=\"center\">Container Type</td>
								<td align=\"center\">Nonfume</td>
								<td align=\"center\">Notes</td>
								<td align=\"center\">Container Num</td>
								<td align=\"center\">Action</td>
								<td align=\"center\">LR# (Last Updated On)</td>
							</tr>";
			do {
				$output .= "<tr>
								<td align=\"center\">".ociresult($stid, "SHIP_LINE_NAME")."</td>
								<td align=\"center\">".ociresult($stid, "SHIP_LINE_NUM")."</td>
								<td align=\"center\">".ociresult($stid, "PORT")."</td>
								<td align=\"center\">".ociresult($stid, "CUSTOMER_ID")."</td>
								<td align=\"center\">".ociresult($stid, "CUSTOMER_NAME")."</td>
								<td align=\"center\">".ociresult($stid, "BOL")."</td>
								<td align=\"center\">".ociresult($stid, "OTHER_BOL")."</td>
								<td align=\"center\">".ociresult($stid, "COMMODITY_CODE")."</td>
								<td align=\"center\">".ociresult($stid, "COMMODITY_NAME")."</td>
								<td align=\"center\">".ociresult($stid, "PLTS")."</td>
								<td align=\"center\">".ociresult($stid, "CTNS")."</td>
								<td align=\"center\">".ociresult($stid, "KGS")."</td>
								<td align=\"center\">".ociresult($stid, "LBS")."</td>
								<td align=\"center\">".ociresult($stid, "CONTAINERS")."</td>
								<td align=\"center\">".ociresult($stid, "CONTAINER_TYPE")."</td>
								<td align=\"center\">".ociresult($stid, "NONFUME")."</td>
								<td align=\"center\">".ociresult($stid, "NOTES")."</td>
								<td align=\"center\">".ociresult($stid, "CONTAINER_NUM")."</td>
								<td align=\"center\">".ociresult($stid, "ACTION")."</td>
								<td align=\"center\">".ociresult($stid, "LR_NUM")."</td>
							</tr>";
			} while(ocifetch($stid));

			$output .= "</table";
			fwrite($handle, $output);
			fclose($handle);
?>
	<tr>
		<td>File Generated.  Click <a href="<? echo $filename; ?>">Here</a> To download.<br><br></td>
	</tr>
	<tr>
		<td>Click <a href="WIP_report.php">Here</a> to Return to the selection screen.</td>
	</tr>
<?
		}
	}
	include("pow_footer.php");
?>