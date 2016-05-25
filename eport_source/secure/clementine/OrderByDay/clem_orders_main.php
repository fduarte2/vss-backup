<?
/*
*			Adam Walter, Oct 2011
*
*	Clementine active order report
********************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$select_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$date = $HTTP_POST_VARS['date'];

	if($submit != ""){
		if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $date)) {
			echo "<font color=\"#FF0000\">Date (entered as ".$date.") must be in MM/DD/YYYY format.</font>";
			$submit = "";
		}
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine Orders</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="data_pick" action="clem_orders.php" method="post">
	<tr>
		<td width="30%" align="left"><font size="3" face="Verdana">Pick-Up Date:</font></td>
		<td><input type="text" name="date" size="12" maxlength="10" value="<? echo $date; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Orders"></td>
	</tr>
</form>
</table>

<?
	if($submit != "" && $date != ""){
		$noslashesdate = str_replace("/", "", $date);
		$filename = "OrdersFor".$noslashesdate."PrintedOn".date('mdYhis').".xls";
		$fullpath = "./files/".$filename;
		$handle = fopen($fullpath, "w");

		$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"".$fullpath."\">Click Here for the Excel File</a></b></font></td>
							</tr>
						</table><br><br>";
		echo $xls_link;

		$output_top = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>DC ORDERS + PACKING STATIONS</b></font></td>
							</tr>
							<tr>
								<td colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Date Of:  ".$date."</b></font></td>
							</tr>
							<tr>
								<td colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Report Created On:  ".date('m/d/Y h:i:s a')."</b></font></td>
							</tr>
						</table><br><br>";

		// CARGO_TRACKING report
		$output_orders = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
//		$output_headerwidth_1 = 3;
//		$output_headerwidth_2 = 5;

		$sql = "SELECT DCO.ORDERNUM THE_ORD, DCO.LOADTYPE THE_TYPE, DCO.ORDERSTATUSID THE_STAT, DCCONS.CONSIGNEENAME THE_CONS,
					DCOD.SIZELOW LOW_SIZE, DCOD.SIZEHIGH HIGH_SIZE, DCOD.ORDERQTY THE_CTNS, DCOD.WEIGHTKG THE_WEIGHT, NVL(DCP.PACKINGHOUSE, '&nbsp;') THE_PKG,
					NVL(TO_CHAR(DCP.PALLETQTY), '&nbsp;') THE_PLTS, NVL(DCP.COMMENTS, '&nbsp;') THE_COMMENTS
				FROM DC_ORDER DCO, DC_ORDERDETAIL DCOD, DC_CONSIGNEE DCCONS, DC_PICKLIST DCP
				WHERE DCO.ORDERNUM = DCOD.ORDERNUM
					AND DCO.CONSIGNEEID = DCCONS.CONSIGNEEID
					AND DCOD.ORDERNUM = DCP.ORDERNUM(+)
					AND DCOD.ORDERDETAILID = DCP.ORDERDETAILID(+)
					AND DCO.PICKUPDATE >= TO_DATE('".$date."', 'MM/DD/YYYY')
					AND DCO.PICKUPDATE <= TO_DATE('".$date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
				ORDER BY DCO.ORDERNUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output_orders .= "<tr><td align=\"center\"><font size=\"2\" face=\"Verdana\">No Orders in Clementine System for given date.</font></td></tr>";
		} else {
			$current_order = "beans";
			do {
				if($current_order != $row['THE_ORD']){
					$output_orders .= "<tr>
										<td>".$row['THE_STAT']."</td>
										<td colspan=\"3\"><b>".$row['THE_ORD']."</b>&nbsp;&nbsp;&nbsp;".$row['THE_CONS']."</td>
										<td colspan=\"3\">".$row['THE_TYPE']."</td>
										<td width=\"40%\">Comments</td>
									   </tr>";
					$current_order = $row['THE_ORD'];
				}

				$output_orders .= "<tr>
									<td colspan=\"2\">&nbsp;</td>
									<td>".$row['LOW_SIZE']." --- ".$row['HIGH_SIZE']."</td>
									<td>".$row['THE_CTNS']." cases</td>
									<td>".$row['THE_WEIGHT']." kg</td>
									<td>".$row['THE_PKG']."</td>
									<td>".$row['THE_PLTS']."</td>
									<td>".$row['THE_COMMENTS']."</td>
								   </tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		$output_orders .= "</table>";

		$the_output = $output_top.$output_orders;

		fwrite($handle, $the_output);
		echo $the_output;
		fclose($handle);
	}