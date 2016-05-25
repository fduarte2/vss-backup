<p>
   <a href="/clementine/logout.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Logout</font></b>
   </a>
</p>
<!--<p>
   <a href="/clementine/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Welcome Screen</font></b>
   </a>
</p> !-->
<p>
   <a href="/clementine/tally/clem_tally_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Outbound Tally</font></b>
   </a>
</p>
<p>
   <a href="/clementine/ClemInventory/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Inventory (by vessel)</font></b>
   </a>
</p>
<p>
   <a href="/clementine/PKGInvNow/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Current Inventory (by PKG House)</font></b>
   </a>
</p>
<? if($eport_customer_id == 835){ ?>
<p>
   <a href="/clementine/PickListToday/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Today's Pick Lists</font></b>
   </a>
</p>
 <p>
   <a href="/clementine/PKGSizePredict/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Expected Activity</font></b>
   </a>
</p> 
<!-- <p>
   <a href="/clementine/OrderByDay/clem_orders.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Orders By Pick-up Date</font></b>
   </a>
</p> !-->
<? } ?>
<p>
   <a href="/clementine/BadPallets/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Reg/Hosp by Vessel</font></b>
   </a>
</p>
<p>
   <a href="/clementine/Recon/clem_recon_eport_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Vessel Outbound</font></b>
   </a>
</p>
<? if($eport_customer_id == 0 || $eport_customer_id == 835){ ?>
 <p>
   <a href="/clementine/CustRecon/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Reconciliation</font></b>
   </a>
</p> 
 <p>
   <a href="/clementine/PickListAnyday/index_picklist.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">PickList By Day</font></b>
   </a>
</p> 
<? } ?>
<? if($eport_customer_id == 0){ ?>
 <p>
   <a href="/clementine/sizepkg_IH/clem_sizepkg_report_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Size / Packinghouse report</font></b>
   </a>
</p> 
<? } ?>
<p>
   <a href="/clementine/DomesticInv/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Domestic Clementine Activity</font></b>
   </a>
</p> 
<p>
   <a href="/clementine/dc_outbound_index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Vessel Summary</font></b>
   </a>
</p> 