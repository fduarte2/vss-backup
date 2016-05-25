<?
/* Created Adam Walter, sept 2006.
*  Page retrieves activity for a cutomer on eport
*  Can only see data for the customer they are logged in as
*  Both 6200 and 6120 status included
*/
   include("set_values.php");
   $user = $HTTP_COOKIE_VARS["eport_user"];
   $eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

   if($user == ""){
      header("Location: ../bni_login.php");
      exit;
   }

   include("connect.php");

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
	    printf("Error logging on to the DB Server: ");
       	printf(ora_errorcode($conn));
       	exit;
   }
   $cursor = ora_open($conn);
   $customerCursor = ora_open($conn);
   $transferRecipientCursor = ora_open($conn);

   $pgconn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pgconn){
	   echo "Unable to connect to PG database.";
	   exit;
   }


   $pgsql = "select customer_list from eport_customer_numbers where username = '".$user."'";
   $pgresult = pg_query($pgconn, $pgsql) or die("Error in query: $pgsql. " .  pg_last_error($pgconn));
   $row = pg_fetch_assoc($pgresult);
   $customerList = $row['customer_list'];


   $submit = $HTTP_POST_VARS['submit'];
   $startDate = $HTTP_POST_VARS['startDate'];
   $endDate = $HTTP_POST_VARS['endDate'];

   if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $startDate) && $submit == 'submit'){
	   $BadStart = 1;
   }
   if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $endDate) && $submit == 'submit'){
	   $BadEnd = 1;
   }

   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $startDate)){
	   $temp = split("/", $startDate);
	   $pgStart = date("Y-m-d", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
	   $oraStart = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
   }
   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $endDate)){
	   $temp = split("/", $endDate);
	   $pgEnd = date("Y-m-d", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
	   $oraEnd = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
   }


// while not the most efficient coding procedure, I'm making both halves of this webpage in this file, 
// one for each half of the "if submitted" statement.
// at least it will be easier to read and modify.  ~Adam Walter, Spetember 2006
   if($submit != 'submit' || $BadStart == 1 || $BadEnd == 1){
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<html>
<head>
<title>Eport of Wilmington - Activity Report</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "500">
		  <? include("leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
		<table width="100%" valign="top">
		<form name="print_form" action="cargo_activity.php" method="post">
		   <tr>
		      <td width="1%">&nbsp;</td>
			  <td colspan="2"><font size="3" face="Verdana">Please select a date range:</font></td>
		   </tr>
		   <tr>
		      <td width="3%" colspan="2">&nbsp;</td>
			  <td><font size="2" face="Verdana">Begin Date:&nbsp;&nbsp;</font>
			      <input type="textbox" name="startDate" size="15" maxlength="20" value="<? echo $startDate; ?>">
				  <a href="javascript:show_calendar('print_form.startDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
				  <? if($BadStart == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?>
			  </td>
		   </tr>
		   <tr>
		      <td width="3%" colspan="2">&nbsp;</td>
			  <td><font size="2" face="Verdana">End Date:&nbsp;&nbsp;</font>
			      <input type="textbox" name="endDate" size="15" maxlength="20" value="<? echo $endDate; ?>">
				  <a href="javascript:show_calendar('print_form.endDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a>
				  <? if($BadEnd == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?>
			  </td>
		   </tr>
		   <tr>
		      <td width="3%" colspan="2">&nbsp;</td>
			  <td><input type="submit" name="submit" value="submit"><br><br><br></td>
		   </tr>
		</form>
		</table>
	  <? include("footer.php"); ?>
	  </td>
   </tr>
</table>

</body>
</html>
<?
   } else {
	$current_customer = "";
	$current_date = "";

// nothing like a 4-table SELECT statement, eh?
/* I get the following fields, in case this is way too confusing:
*
*  CARGO_ACTIVITY -> SERVICE_CODE
*					 LOT_NUM
*					 DATE_OF_ACTIVITY
*					 CUSTOMER_ID (recipient of a transfer)
*					 ORDER_NUM
*					 QTY_CHANGE
*  CARGO_MANIFEST -> RECIPIENT_ID (lot's original owner)
*					 CARGO_BOL
*					 QTY1_UNIT
*					 QTY2_UNIT
*  CARGO_DELIVERY -> DELIVERY_NUM 
*					 DELIVER_TO (withdraw's recipient)
*					 DELIVERY_DESCRIPTION
*  VESSEL_PROFILE -> VESSEL_NAME
*  
*  CARGO_ACTIVITY_EXT -> QTY2
*
*
*/
	$orasql = "SELECT a.SERVICE_CODE SERVICE_CODE, b.RECIPIENT_ID RECIPIENT_ID, a.LOT_NUM LOT_NUM, a.DATE_OF_ACTIVITY DATE_OF_ACTIVITY, d.VESSEL_NAME VESSEL_NAME, a.CUSTOMER_ID CUSTOMER_ID, a.ORDER_NUM ORDER_NUM, c.DELIVERY_NUM DELIVERY_NUM, c.DELIVER_TO DELIVER_TO, c.DELIVERY_DESCRIPTION DELIVERY_DESCRIPTION, b.CARGO_BOL CARGO_BOL, b.QTY1_UNIT QTY1_UNIT, b.CARGO_MARK CARGO_MARK, a.QTY_CHANGE QTY_CHANGE, e.QTY2 QTY2, b.QTY2_UNIT QTY2_UNIT FROM cargo_activity a, cargo_manifest b, cargo_delivery c, vessel_profile d, cargo_activity_ext e where a.DATE_OF_ACTIVITY between '".$oraStart."' and '".$oraEnd."' and a.CUSTOMER_ID IN (".$customerList.") and a.LOT_NUM=b.CONTAINER_NUM and a.LOT_NUM=c.LOT_NUM and b.LR_NUM=d.LR_NUM and a.LOT_NUM=e.LOT_NUM and a.ACTIVITY_NUM=e.ACTIVITY_NUM and a.ACTIVITY_NUM=c.ACTIVITY_NUM ORDER BY b.RECIPIENT_ID, a.DATE_OF_ACTIVITY, c.DELIVERY_NUM, a.ORDER_NUM, b.LR_NUM";
	ora_parse($cursor, $orasql);
	ora_exec($cursor);
?>
<html>
<head>
<title>Eport of Wilmington - Activity Report</title>
</head>

<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table cellpadding="1" cellspacing="1" border="1">
   <tr>
      <td>CUSTOMER</td>
	  <td>DATE</td>
	  <td>W/O#</td>
	  <td>ORD#</td>
	  <td>VESSEL</td>
	  <td>BoL</td>
	  <td>LOT/MARK</td>
	  <td>QTY</td>
	  <td>UNIT</td>
	  <td>QTY2</td>
	  <td>QTY2 UNIT</td>
	  <td>CARRIER</td>
	  <td>DELIVER TO</td>
   </tr>
<?
// and now for your coding pleasure, while's and if's for page formatting goodness!
// note that the CARGO_DELIVERY table's DELIVER TO field doesnt hold recipient information for transfers,
// so I have to run a seperate SQL to figure out who got a transfer.
// then an if statement to determine whether to put deliver_to or recipient_id in the final box.
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	    if($current_customer != $row['RECIPIENT_ID']){
	        $customerSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$row['RECIPIENT_ID']."'";
			ora_parse($customerCursor, $customerSql);
			ora_exec($customerCursor);
			ora_fetch_into($customerCursor, $customerRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
      <tr bgcolor="ba4b45"><td colspan="13"><? echo $customerRow['CUSTOMER_NAME']; ?></td></tr>
	  <tr bgcolor="45ba6f"><td>&nbsp;</td><td colspan="12"><? echo $row['DATE_OF_ACTIVITY']; ?></td></tr>
<?
	        $current_customer = $row['RECIPIENT_ID'];
            $current_date = $row['DATE_OF_ACTIVITY'];
        }
		if($current_date != $row['DATE_OF_ACTIVITY']){
?>
      <tr bgcolor="45ba6f"><td>&nbsp;</td><td colspan="12"><? echo $row['DATE_OF_ACTIVITY']; ?></td></tr>
<?
	        $current_date = $row['DATE_OF_ACTIVITY'];
		}
		$transferSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$row['CUSTOMER_ID']."'";
		ora_parse($transferRecipientCursor, $transferSql);
		ora_exec($transferRecipientCursor);
		ora_fetch_into($transferRecipientCursor, $transferRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
   <tr bgcolor="<? if($alternate == 1){ echo 'f0f0f0'; } else { echo 'ffffff'; } ?>">
      <td><font size="2" face="Verdana">&nbsp;</font></td>
	  <td><font size="2" face="Verdana">&nbsp;</font></td>
	  <td><font size="2" face="Verdana"><nobr><? echo $row['DELIVERY_NUM']; ?></nobr></font></td> 
	  <td><nobr><font size="2" face="Verdana"><? if($row['ORDER_NUM'] == 0){
	            echo 'Trans.';
             } else {
	            echo $row['ORDER_NUM'];
			 }?></font></nobr></td> 
	  <td><font size="2" face="Verdana"><? echo $row['VESSEL_NAME']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['CARGO_BOL']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['CARGO_MARK']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['QTY_CHANGE']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['QTY1_UNIT']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['QTY2']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? echo $row['QTY2_UNIT']; ?></font></td>
	  <td><font size="2" face="Verdana"><? echo $row['DELIVERY_DESCRIPTION']; ?></font></td> 
	  <td><font size="2" face="Verdana"><? if($row['SERVICE_CODE'] == '6200'){
			      echo $row['DELIVER_TO']; 
             } else {
				  echo $transferRow['CUSTOMER_NAME'];
			 }?></font></td> 
   </tr>

<?
	   if($alternate == 1){
	       $alternate = 0;
       } else {
		   $alternate = 1;
	   }
	}
?>
</table>

</body>
</html>
<?
 }
?>