<!-- Vessel Discharge Report - Main Page -->
<?
	$eport_customer_id = $HTTP_COOKIE_VARS['eport_customer_id'];

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
?>
<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      ship = x.ship.value

      if (ship == "") {
	 alert("You need to select a vessel to view its discharge report!")
         return false
      }
   }
</script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">InHouse Inventory Report</font>
         <br />
	 <hr>
      </td>
   </tr>
<?
   if ($eport_customer_id != 0 && false) {
?>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana">
	 | <a href="../">Home</a>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
<?
   }
//	  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	  $cursor = ora_open($conn);
?>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <form name="report_form" method="post" action="report2.php" onsubmit="return validate_form()">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
         if ($eport_customer_id != 0) {
	   printf("Please select a vessel to view the Inventory Report (You can also select show all vessels to see entire inventory table).");
	 } else {
	   printf("Please select the following options (or select all) to view the Inventory Report.");
?>
		<br><select name="customer"><option value="all">All</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID";
		$custs = ociparse($rfconn, $sql);
		ociexecute($custs);
		while(ocifetch($custs)){
?>
							<option value="<? echo ociresult($custs, "CUSTOMER_ID"); ?>"><? echo ociresult($custs, "CUSTOMER_NAME"); ?></option>
<?
		}
?>
		</select>
<?
	 }
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Ship name:</font></td>
	       <td width="55%" align="left">
	          <select name="ship">
                  <option value="x-x">Show All Inventory</option>
	       <?
                  $year = date('Y');
                  $year_1 = $year + 1;
//								and ship_prefix = 'CHILEAN' 
/*				  $sql = "SELECT VP.LR_NUM, VESSEL_NAME 
							FROM VESSEL_PROFILE VP, VOYAGE VOY 
							WHERE VP.LR_NUM = VOY.LR_NUM 
								AND VP.LR_NUM != '4321'
								AND TO_CHAR(VP.LR_NUM) IN 
									(SELECT DISTINCT ARRIVAL_NUM 
									FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
									WHERE QTY_IN_HOUSE != 0 
										AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
										AND COMMODITY_TYPE IN ('CHILEAN', 'ARG FRUIT')) 
							ORDER BY VP.LR_NUM DESC";*/
				$sql = "SELECT DISTINCT
							   VP.ARRIVAL_NUM THE_LR,
							   LR_NUM || '-' || VESSEL_NAME THE_VESSEL
						FROM VESSEL_PROFILE VP
						INNER JOIN CARGO_TRACKING CT
							  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
						WHERE
							VP.SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT')
						ORDER BY VP.ARRIVAL_NUM";
				$vesses = ociparse($rfconn, $sql);
				ociexecute($vesses);
				while(ocifetch($vesses)){
					$lr_num_ora = ociresult($vesses, "THE_LR");
					$ship_name = ociresult($vesses, "THE_VESSEL");
					printf("<option value=\"%s-%s\">%s - %s</option>", $lr_num_ora, $ship_name, $lr_num_ora, $ship_name);
				}

	          // close Oracle connection
//	          ora_close($cursor);
//	          ora_logoff($conn);
	       ?>
	          </select>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" name="submit" value="View Ship Report">
	       </td>
<!--	       </form> !-->
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="3" face="Verdana"><p><b>---OR---</b></font><br><br><font size="2" face="Verdana">Choose the following options to show Trucked Cargo:</p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
<!--		<form name="report_truck_form" method="get" action="report2.php" onsubmit="return validate_form()"> !-->
<!--		<input type="hidden" name="truck" value="TRUCKS"> !-->
		<tr>
	       <td width="5%">&nbsp;</td>
			<td colspan="2">Received?  <input type="radio" name="truck_rec" value="no"> No     <input type="radio" name="truck_rec" value="yes"> Yes       <input type="radio" name="truck_rec" value="all" checked> All</td>
		</tr>
		<tr>
	       <td width="5%">&nbsp;</td>
			<td colspan="2"><font size="2" face="Verdana"><b>If received, you can specify the following options:</b></font></td>
		</tr>
		<tr>
	       <td width="5%">&nbsp;</td>
			<td>Date Received (from):</td>
			<td><input name="date_from" type="text" size="15" maxlength="10">&nbsp;&nbsp;<font size="2" face="Verdana">(MM/DD/YYYY format)</td>
		</tr>
		<tr>
	       <td width="5%">&nbsp;</td>
			<td>Date Received (to):</td>
			<td><input name="date_to" type="text" size="15" maxlength="10">&nbsp;&nbsp;<font size="2" face="Verdana">(MM/DD/YYYY format)</td>
		</tr>
		<tr>
	       <td width="5%">&nbsp;</td>
	       <td colspan="2"><input type="submit" value="View Truck Report"></td>
		</tr>
	 </table>
	 </form>
      </td>
   </tr>
</table>
