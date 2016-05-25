<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Finance Applications
          </font>
	  <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can access Finance applications.</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="../TS_Testing/index_finatest.php"><b>Client Testing</b></a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="bni/">BNI System</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
<!--            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="ccds/">CCDS System</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr> !-->
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="rf/">RF System</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="./vessel_arrival_notify.php">Barge Arrival Notification</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="creditdebit/index.php">Credit/Debit Memo Pages</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="Dole9722lookup.php">Dole-9722 EDI Lookup</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                  <a href="invoice/">Load Invoices</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/olap/whs.php">Warehouse Inventory</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/olap/billing.php">Billing information</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
			<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="secu_rate_change.php">IB-Truckload Security Rate</a></font>
               </td>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
			<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="dole_rates.php">Dole Truckloading Rates Edit</a></font>
               </td>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/labor_ticket/">Paid Hours vs. Labor Ticketed</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
<!--            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/olap/ccd_activity.php">CCDS ACTIVITIS</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr> !-->
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/olap/solomon.php">Solomon GL</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="/director/olap/rf_billing.php">RF Storage Billing information</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="reports/">Finance Reports</a></font>
               </td>
	     <tr>
               <td colspan="2" height="10"></td>
            </tr>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="MonthlyMiscCalls.php"> Monthly Non-BNI Vessel Calls</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="./fina_sigma_grids/index.php">Tables</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="UserGuides/">User Guides</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="storage/screens/">Storage Pages</a></font>
               </td>
            </tr>
			<tr>
    		<td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="next_FY_set.php">Start Next Fiscal Year Bill#</a></font>
               </td>
            </tr>
			<tr>
    		<td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="fina_cubers.php">View Finance Cubes</a></font>
               </td>
            </tr>
			<tr>
    		<td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="../invoices/?C=M;O=D">Invoices</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
		<tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2">
                 <a href="fum_insp_bill.php">Create Fumigation/Inspection Bill</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
          </table>
      </td>
      <td valign="middle" width="30%">
         <img border="0" src="images/warehouse_e.jpg" width="218" height="170">
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
