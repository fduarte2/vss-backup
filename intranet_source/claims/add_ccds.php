<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Meat Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">CCDS System</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <p><br /><font size="2" face="Verdana">Select a lot by its tracking number or lot & mark information 
	 from the ship manifest.</font><br /><br />
      </td>
   </tr>
</table>

<?
   include("select_lot.php");
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <table border="0" width="95%" cellpadding="4" cellspacing="0">
<? 
   $ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];
   if ($ccd_lot_id != "") {
?>
	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2">You selected the lot with tracking # <?= $ccd_lot_id ?></font>
		  <br /><br />
	       </td>
	    </tr>
<?
     // Make sure this is not a diplicate!
     $sql = "select * from claim_log where ccd_lot_id = '$ccd_lot_id'";
     include("connect.php");

     $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
     if (!$connection){
       die("Could not open connection to database server");
     }
  
     $result = pg_query($connection, $sql) or die("Error in query: $sql. " . pg_last_error($connection));
     $rows = pg_num_rows($result);

     if($rows > 0){
       $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
?>
	    <tr>
	       <td colspan="4">
		  <font face="Verdana" size="2" color="red">Caution! You already made a claim (<?= $row['customer_invoice_num'] ?>) for tracking # <?= $ccd_lot_id ?></font>
		  <br /><br />
	       </td>
	    </tr>

<?
     }
   }
?>
	    <tr>
	       <td width="5%" valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td width="45%" valign="middle">
		   <font face="Verdana" size="2" color="#000080">
		   <a href="add_main.php?system=CCDS">Proceed</a></font>
	       </td>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">
		  <a href="mark_lookup.php">Mark Lookup</a></font>
	       </td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">
		  <a href="track_report.php">Print Tracker Report</a></font>
	       </td>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">
		  <a href="eport_lookup.php">Print E-Port Damage Report</a></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
   </tr>
</table>
<?
	 include("pow_footer.php");
?>
