<!-- CCDS - Main Page -->

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Welcome to E-Port!
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<?php
if ($user != 'tcval') {
?>
	<tr>
		<td width="1%">&nbsp;</td>
		<td valign="top" width="70%">
			<font size="2" face="Verdana"><b>Information Access:</font>
			<br /><br />
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
				<tr>
					<td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left" width="90%">
						<a href="http://www.eportwms.com/cargo?filter=Vessel" target="http://www.eportwms.com/cargo?filter=Vessel">
							<font face="Verdana" size="2" color="#000080">2015 New Chilean Warehouse Management System</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left" width="90%">
						<a href="warehouse/index.php">
							<font face="Verdana" size="2" color="#000080">Inventory Report</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="warehouse/fumigation_index.php">
							<font face="Verdana" size="2" color="#000080">Fumigation Report</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
<?
	if($user == "PAC SEAWAY") {
?>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="pacific/">
							<font face="Verdana" size="2" color="#000080">Pacific Seaways Reports</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
<?
	}
	if($user == "giumarra") {
?>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="eloads_index.php">
							<font face="Verdana" size="2" color="#000080">eLoad Generation</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
<?
	}
?>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="activity/index.php">
							<font face="Verdana" size="2" color="#000080">Pallet Activity</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="vessel_discharge/index.php">
							<font face="Verdana" size="2" color="#000080">Vessel Discharge Report</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left" width="90%">
						<a href="warehouse/chilean_recon_eport_index.php">
							<font face="Verdana" size="2" color="#000080">Chilean/Argentine Fruit Reconciliation Report</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left" width="90%">
						<a href="9722InvoiceLookup/index.php">
							<font face="Verdana" size="2" color="#000080">Invoice Details</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
<?
} //end if user is not tcval
if($user == 'tcval' || $user == 'TS' || $user == 'schapman') {
?>
				<tr>
					<td valign="middle"><img src="../images/yellowbulletsmall.gif"></td>
					<td valign="middle" align="left">
						<a href="recvd_pallets_vs_carle.php">
							<font face="Verdana" size="2" color="#000080">Received Pallets vs Carle File</font>
						</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
<?php
}
?>
			</table>
		</td>
		<td valign="middle" width="30%">
			<p><img border="0" src="../images/FSI-comp.jpg" width="200" height="160"></p>
		</td>
		<td width="1%">&nbsp;</td>
	</tr>
</table>
<br />