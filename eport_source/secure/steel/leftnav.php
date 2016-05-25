<?
	$eport_user_scanned = $HTTP_COOKIE_VARS["eport_user_scanned"];
?>
<p>
   <a href="/steel/logout.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Logout</font></b>
   </a>
</p>
<?
	if($eport_user_scanned == 'UNSCANNED' || $eport_user_scanned == 'ALL'){
?>
<p>
   <a href="/steel/steel_IH.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">In-House Inventory</font></b>
   </a>
</p>
<p>
   <a href="/steel/steel_activity.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Outbound Activity</font></b>
   </a>
</p>
<?
	}
	if($eport_user_scanned == 'SCANNED' || $eport_user_scanned == 'ALL'){
?>
<p>
   <a href="/steel/steel_IH_scanned.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">In-House Inventory (Scanned)</font></b>
   </a>
</p>
<p>
   <a href="/steel/steel_activity_scanned.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Outbound Activity (Scanned)</font></b>
   </a>
</p>
<?
	}
	if($user == "steel" or $user == "SSAB"){
?>
<p>
   <a href="/steel/InventoryByVesCust/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Inventory (by vessel/customer)</font></b>
   </a>
</p>
<?
	}
?>
<p>
   <a href="/steel/steel_DO_scanned.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">DO history Lookup (In Testing)</font></b>
   </a>
</p>
<p>
   <a href="/steel/steel_DO_outbound.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Steel Outbound by DO# Report</font></b>
   </a>
</p>
<p>
   <a href="/steel/steel_pallet_recon.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Steel Pallet Reconciliation Report</font></b>
   </a>
</p>