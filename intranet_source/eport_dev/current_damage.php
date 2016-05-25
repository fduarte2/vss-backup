<?
// Description: Creates a table show damaged pallets pulled from RF.CSS_DISCHARGE for the Current Vessel  

// check if it is an authenticated user
$user = $HTTP_COOKIE_VARS["eport_user"];
if ($user == "") {
  header("Location: ../ccds_login.php");
  exit;
}

$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

$today = date("F j, Y, g:i A");

// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}		

$cursor2 = ora_open($ora_conn);
if (!$cursor2) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor2));
  exit;
}		

/*
//check if the vessel discharge report was confirmed 
$stmt = "select arrival_num from ccd_cargo_damaged_verify_log where arrival_num = '$lr_num'";
ora_parse($cursor1, $stmt);
ora_exec($cursor1);
if (ora_fetch($cursor1)){
	$ccd_cargo_damage = "CCD_CARGO_VERIFIED_DAMAGE";
	$confirm = "";
}else{
	$ccd_cargo_damage = "CCD_CARGO_DAMAGED";
	$confirm = "Vessel discharge is ongoing now. This report is not confirmed.";
	$confirm1 = "Once Confirmed the Damage will be summarized by Lots and posted to the usual reports.";
}
*/

 // check in the ccd_current_vessel

  $stmt = "select * from ccd_current_vessel";
  ora_parse($cursor1, $stmt);
  ora_exec($cursor1);
  if (ora_fetch($cursor1)){
   $confirm = "Vessel discharge is ongoing now. This report is not confirmed.";
   $confirm1 = "Once Confirmed the Damage will be summarized by Lots and posted to the usual reports.";
  
// Get the Vessel Name

   $ora_sql = "select distinct arrival_num from css_discharge";
   $statement = ora_parse($cursor1, $ora_sql);
   ora_exec($cursor1);

    if (ora_fetch($cursor1))
    {
       $lr_num = ora_getcolumn($cursor1, 0);
    }

   if( $lr_num != NULL)
   {   
	$ora_sql = "select vessel_name from vessel_profile
               where lr_num = $lr_num";
   	$statement = ora_parse($cursor1, $ora_sql);
   	ora_exec($cursor1);

   if (ora_fetch($cursor1)){
     $vessel_name = ora_getcolumn($cursor1, 0);
   $confirm = "No Vessel is Being Discharged Currently.";
   $confirm1 = "";

   }
  }
							 

// get infomation from RF.CSS_DISCHARGE, RF.CCD_CARGO_TRACKING


$stmt = " select t.lot_id,pallet_id,c.customer_short_name customer,
                 d.torn,d.exposed,d.frzburn,d.oildmg,d.dropstev,d.dropport,
		 to_char(date_checked, 'MM/DD/YY HH24:MI:SS') date_checked
          from   css_discharge d,ccd_cargo_tracking t,ccd_customer_profile c
          where  t.lot_id = d.lot_id and t.arrival_num = d.arrival_num 
	         and t.receiver_id = c.customer_id(+)";
	  
     													  
if ($eport_customer_id != 0)
{
	$stmt .= " and t.receiver_id = $eport_customer_id ";
}

$stmt .= " group by t.lot_id,pallet_id,c.customer_short_name,date_checked,
          d.torn,d.exposed,d.frzburn,d.oildmg,d.dropstev,d.dropport";
	  
$stmt .= " order by date_checked desc";

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_DAMAGED, CCD_CARGO_TRACKING and 
	  CCD_CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

?>

<html>
<head>
<meta http-equiv="Refresh" content="300">
<title>Eport - Current Vessel Discharge</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
                  <?
                  // write out the intro and print the header
                  if ($condense == "On") {
		    printf("<br />Port of Wilmington Condensed Current Vessel Discharge");
		  } else {
		    printf("<br />Port of Wilmington Current Vessel Discharge");
		  }
                  ?>
	          </font>
	          <br />
	          <hr>
		  <br />
		  <font size = "3" face="Verdana" color="#0066CC">Damage (in cartons)</font>
	          <br /><br />
                  <font size = "3" face="Verdana" color="#0066CC"><?= $lr_num ?> - <?= $vessel_name ?></font>		  
		  <? if ($confirm <>""){?>
		  <br \>
		  <font size = "3" face="Verdana" color="red"><?= $confirm ?></font>
		  <? } ?>
	          <br /><br/>
		  <font size = "3" face="Verdana" color="red"><?= $confirm1 ?> </font>
		  <br /><br/>
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
		  <font size = "2" face="Verdana" color="#0066CC">A Brief Desc. is entered by checker when Pallet Id is missing or if it cannot be scanned</font>
                  <br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
		  <?
		  if ($rows1 == 0) {
		    if ($eport_customer_id == 0) {
		  ?>		      
		  <font size = "2" face="Verdana">No customers have cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?> Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>
		  <?
		    } else {
		  ?>
		  <font size = "2" face="Verdana"><?= $user ?> does not have any cargo shipped with this vessel!  
		  Please go back to select another vessel to view Vessel Discharge Report.</font>
		  <br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Printed by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>

		  <?
		    }
		    exit;
		  }
                  ?>
		  
		  <table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
		  <?
		  if ($eport_customer_id != 0) {
		  ?>
		     <caption align="left">
		       <font size = "3" face="Verdana" color="#0066CC">Customer: <?= $row1['CUSTOMER']  ?></font>
		     </caption>
		  <?
		  }
                  ?>
		     <tr>
		        <th align="left"
                        onmouseout="return nd();"><font size = "2" face="Verdana">Last Scan Time</font></th>
          		<th nowrap align="left"
		            onmouseout="return nd();"><font size = "2" face="Verdana">Lot ID</font></th>
			 <th nowrap align="left"
			    onmouseout="return nd();"><font size = "2" face="Verdana">Pallet ID/Desc</font></th>
		  <?
		     if ($eport_customer_id == 0) {
		  ?>
		        <th align="left"
			    onmouseout="return nd();"><font size = "2" face="Verdana">Customer</font></th>
		  <?
		     }
		  ?>
		  
        	      <th align="center"
		       onmouseout="return nd();"><font size = "2" face="Verdana">Torn</font></th>
                      <th align="center"
		       onmouseout="return nd();"><font size = "2" face="Verdana">Exposed</font></th>
                      <th align="center"
		       onmouseout="return nd();"><font size = "2" face="Verdana">FreezerBurn</font></th>
		       <th align="center"
                       onmouseout="return nd();"><font size = "2" face="Verdana">OilDamage</font></th>
		       <th align="center"
                       onmouseout="return nd();"><font size = "2" face="Verdana">Stevedore Dropped</font></th>
                       <th align="center"
                       onmouseout="return nd();"><font size = "2" face="Verdana">Port Dropped</font></th>
				      
		      </tr>
					     
<?
		 do {

		   if ($row1['TORN'] == 0 and $row1['EXPOSED'] == 0 and $row1['FRZBURN'] == 0 and
		       $row1['OILDMG'] == 0 and $row1['DROPSTEV'] == 0 and $row1['DROPPORT'] == 0)
		     continue;
		   

		   $tot_torn = $tot_torn + $row1['TORN'];
		   $tot_exp = $tot_exp + $row1['EXPOSED'];
		   $tot_frzburn = $tot_frzburn + $row1['FRZBURN'];
		   $tot_oildmg = $tot_oildmg + $row1['OILDMG'];
		   $tot_dropstev = $tot_dropstev + $row1['DROPSTEV'];
		   $tot_dropport = $tot_dropport + $row1['DROPPORT'];
		                
	       ?>		       
	             <tr>
		        <td align="left" nowrap><font size = "2" face="Verdana"><?= $row1['DATE_CHECKED'] ?></font></td>
			<td><font size = "2" face="Verdana"><?= $row1['LOT_ID'] ?></font></td>
			<td><font size = "2" face="Verdana"><?= $row1['PALLET_ID'] ?></font></td> 
	  <?
	              if ($eport_customer_id == 0) {
		  ?>
			<td><font size = "2" face="Verdana"><?= $row1['CUSTOMER'] ?></font></td>
		  <?
		      }
		  ?>
		        <td align="center"><font size = "2" face="Verdana"><?= $row1['TORN'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['EXPOSED'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['FRZBURN'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['OILDMG'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['DROPSTEV'] ?></font></td>
			<td align="center"><font size = "2" face="Verdana"><?= $row1['DROPPORT'] ?></font></td>
		      </tr>
		<?
		 } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		 $grand_tot = $grand_tot + $tot_torn + $tot_exp + $tot_frzburn + $tot_oildmg + $tot_dropstev + $tot_dropport;
	       ?>

                <tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		   <?
		    if ($eport_customer_id == 0) {
		   ?>
		     <td>&nbsp;</td>
		     <td align="left"><font size = "2" face="Verdana" color="red">Total :</font></td>
		   <?
		    } else {
		   ?>
		   <td align="left"><font size = "2" face="Verdana" color="red">Total :</font></td>
		   <? }?>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_torn ?></font></td>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_exp ?></font></td>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_frzburn ?></font></td>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_oildmg ?></font></td>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_dropstev ?></font></td>
		  <td align="center"><font size = "2" face="Verdana" color="red"><?= $tot_dropport ?></font></td>
	        </tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		 <? 
		   if ($eport_customer_id == 0) {
		 ?>
		<td>&nbsp;</td>
		<td align="center"><font size = "2" face="Verdana" color="red">Grand Total :</font></td>
		<td align="left"><font size = "2" face="Verdana" color="red"><?= $grand_tot ?></font></td>
		<? 
		} else {
		?>
		<td align="center"><font size = "2" face="Verdana" color="red">Grand Total :</font></td> 	                  <td align="left"><font size = "2" face="Verdana" color="red"><?= $grand_tot ?></font></td>
		<? } ?>
		</tr>
		 </table>
	          <br /><br />
	          <hr>
	          <font size = "2" face="Verdana">&copy;2003 Port of Wilmington, DE, Diamond State Port 
                  Corporation. All Rights Reserved.</font>
	       </td>
	    </tr>
         </table>
      </td>
   </tr>
</table>

</body>
</html>

<?
}
else
{
?>
<html>
<head>
<meta http-equiv="Refresh" content="300">
<title>Eport - Current Vessel Discharge</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="/functions/overlib.js"></script>

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
     <td width = "100%" valign = "top">
     <table border="0" width="100%" cellpadding="4" cellspacing="0">
     <tr>
       <td align="center">
       <font size="5" face="Verdana" color="#0066CC">
       <?
         printf("<br />Port of Wilmington Current Vessel Discharge");
       ?>
       </font>
        <br />
       <hr>
       <br/>
       <font size = "3" face="Verdana" color="red">
       <?
       printf("No Current Vessel Discharge is Ongoing now");
       ?>
       </font>
       <br />
       <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
       <br /><br/>
       <hr>
      <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Printed by <?= $user ?></font>
     </td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
<?
}
?>
