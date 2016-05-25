<p>
   <a href="/rf/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Home</font></b>
   </a>
</p>
<?php
if ($user != 'tcval') {
?>
<p>
   <a href="/rf/vessel_discharge/index_hatchdeck.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Hatchdeck Summary</font></b>
   </a>
</p>
<p>
   <a href="/rf/tally/fruit_in_tally_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Inbound Tally</font></b>
   </a>
</p>
<p>
   <a href="/rf/tally/fruit_out_tally_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Outbound Tally</font></b>
   </a>
</p>
<p>
   <a href="/rf/warehouse/">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Inventory</font></b>
   </a>
</p>
<p>
   <a href="/rf/warehouse/fumigation_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Fumigation Report</font></b>
   </a>
</p>
<!--<p>
   <a href="/rf/chilean_correction_upload/chilean_pallet_update_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Upload Sort File</font></b>
   </a>
</p>!-->
<p>
   <a href="/rf/chilean_sort-manifest_upload/chilean_pallet_update_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Upload Sort File</font></b>
   </a>
</p>

<? //if ($user == "PAC SEAWAY") { ?>
<!--
<p>
   <a href="activity/">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">PSW Reports</font></b>
   </a>
</p>
!-->
<?// } ?>

<p>
   <a href="/rf/activity/">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Pallet Activity</font></b>
   </a>
</p>
<p>
   <a href="/rf/tag_audit.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Tag Audit</font></b>
   </a>
</p>
<p>
   <a href="/rf/vessel_discharge/">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Vessel Discharge</font></b>
   </a>
</p>
<p>
   <a href="/rf/vessel_discharge/index_hatchdeck.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Vessel Discharge<br>(by hatchdeck)</font></b>
   </a>
</p>
<p>
   <a href="/rf/NONTWICentryeport_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Record NONTWIC Barcode</font></b>
   </a>
</p>
<? if ($user == "wdi" || $user == "wdiinsp" || $user == "powwdi") { ?>
<p>
   <a href="/rf/walmart_info_upload/walmart_upload_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Manifest Upload</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_info_upload/walmart_cargo_lookup_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Review Manifest Uploads</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_info_upload/WM_itemnumbers_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Grower to Walmart Item# Mapping</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_sort_QC/walmart_QC_update_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">QC: INSPECTION PALLETS</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_sort_QC/walmart_QC_statusset_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">QC: HOLDS, RELEASES, REJECTS AND A- </font></b>
   </a>
</p>
<p>
   <a href="/rf/vessel_release/vessel_release_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">QC: RELEASE VESSEL AFTER DISCHARGE</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_ves_detail/walmart_ves_detail_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">QC Vessel Detail Excel File</font></b>
   </a>
</p>
<p>
   <a href="/rf/walmart_po_dept_sum/walmart_ves_po_sum_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Vessel PO summary by Dept</font></b>
   </a>
</p>
<? } ?>
<? if ($user == "wdiinsp" || $user == "powwdi") { ?>
<!--
<p>
   <a href="/rf/walmart_reject/walmart_holdrelease_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Reject (Hold) Maintenance</font></b>
   </a>
</p>
!-->
<? } ?>
<p>
   <a href="/rf/dole_inventory_upload.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Dole Inventory Files</font></b>
   </a>
</p>
<p>
   <a href="/rf/dole_activity_upload.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Dole Activity Files</font></b>
   </a>
</p>
<?php
} //end if user not tcval
?>
<p>
   <a href="/rf/logout.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Logout</font></b>
   </a>
</p>
