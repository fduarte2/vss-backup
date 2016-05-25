<p>
   <a href="/dole/logout.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Logout</font></b>
   </a>
</p>
<p>
   <a href="/dole/index.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Order Review</font></b>
   </a>
</p>
 <p>
   <a href="/dole/order_entry.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Add Orders</font></b>
   </a>
</p>
<p>
   <a href="/dole/order_mod.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Edit Order</font></b>
   </a>
</p>
<?
	if($sub_type == "PORT"){
?>
<p>
   <a href="/dole/upload_cont.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Upload Valid Containers</font></b>
   </a>
</p>
<p>
   <a href="/dole/order_entry_portact.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Add (Port) Order</font></b>
   </a>
</p>
<p>
   <a href="/dole/order_mod_portact.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Edit (Port) Order</font></b>
   </a>
</p>
<?
	}
	if($sub_type == "CUST"){
?>
<p>
   <a href="/dole/confirm_orders.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Confirm Daily Orders</font></b>
   </a>
</p>
<?
	}
?>
<p>
   <a href="/dole/reports.php">
      <b><font face="Verdana" size="2" color="<? echo $left_text; ?>">Reports</font></b>
   </a>
</p>
