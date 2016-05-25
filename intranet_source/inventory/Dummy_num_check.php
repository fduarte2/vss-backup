<?
/*
*  Adam Walter, July 2007.
*
*  This intranet routine takes as inputs some search criteria, and
*  Displays a list of Dummy (DM) numbers that match it.
*
*  I'd much rather this function be built into the VB Dummy applications,
*  But I'm on a short time to get this out, and know PHP better :(
****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Dummy Number Retrieval";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if(!$conn){
    $body = "Error logging on to the BNI Oracle Server: " . ora_errorcode($conn);
    //mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  $cursor = ora_open($conn);         // general purpose

  $today = date('m/d/Y');

  $date_from = $HTTP_POST_VARS['date_from'];
  $date_to = $HTTP_POST_VARS['date_to'];
  $cust_number = $HTTP_POST_VARS['cust_number'];
  $deliver_to = $HTTP_POST_VARS['deliver_to'];
  $cust_order = $HTTP_POST_VARS['cust_order'];
  $commodity = $HTTP_POST_VARS['commodity'];
  $LR_number = $HTTP_POST_VARS['LR_number'];
  $BoL = $HTTP_POST_VARS['BoL'];
  $submit = $HTTP_POST_VARS['submit'];

  $deliver_to = str_replace("\'", "", $deliver_to);

  if($date_from == "" && $date_to == "" && $cust_number == "" && $deliver_to == "" && $cust_order == "" && $WO_number == "" && $commodity == "" && $LR_number == "" && $BoL == "" && $submit != ""){
	  echo "<font color=\"FF0000\">At least 1 of the search fields has to be entered.";
	  $submit = "";
  }

?>
<!-- <a href="Dummy_num_check.php">Test Refresh</a> !-->

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Dummy Number Lookup</font>
         <br />
	 <hr>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="getdata" action="Dummy_num_check.php" method="post">
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>***Only fill in the fields you know***</b></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="3"><font size="3" face="Verdana">Order Date:  (MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td colspan="2" width="5%">&nbsp;</td>
		<td><font size="2" face="Verdana">From:  </font></td>
		<td><input name="date_from" type="text" size="10" maxlength="10" value="<? echo $date_from; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" width="5%">&nbsp;</td>
		<td><font size="2" face="Verdana">To:  </font></td>
		<td><input name="date_to" type="text" size="10" maxlength="10" value="<? echo $date_to; ?>"></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">Customer #:  </font></td>
		<td><input name="cust_number" type="text" size="5" maxlength="4" value="<? echo $cust_number; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">Deliver To:  </font></td>
		<td><input name="deliver_to" type="text" size="20" maxlength="20" value="<? echo $deliver_to; ?>">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">(may contain partial name)</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">Customer Order #:  </font></td>
		<td><input name="cust_order" type="text" size="20" maxlength="20" value="<? echo $cust_order; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">LR #:  </font></td>
		<td><input name="LR_number" type="text" size="10" maxlength="10" value="<? echo $LR_number; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">Commodity:  </font></td>
		<td><input name="commodity" type="text" size="5" maxlength="4" value="<? echo $commodity; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2" width="15%"><font size="2" face="Verdana">BoL:  </font></td>
		<td><input name="BoL" type="text" size="10" maxlength="15" value="<? echo $BoL; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" width="5%">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Numbers"></td>
	</tr>
</form>
<?
	if($submit != ""){
		$sql = "SELECT DISTINCT D_DEL_NO, NVL(to_char(ORDER_DATE, 'MM/DD/YYYY'), '&nbsp;') THE_ORDER_DATE, COMMODITY_CODE, OWNER_ID, NVL(ORDER_NO, '&nbsp;') THE_ORDER, NVL(DELIVER_TO, '&nbsp;') THE_DELIVERY, NVL(STATUS, '&nbsp;') THE_STATUS FROM BNI_DUMMY_WITHDRAWAL WHERE D_DEL_NO IS NOT NULL ";

		if($date_from != ""){
			$sql .= "AND ORDER_DATE >= to_date('".$date_from." 00:00:00', 'MM/DD/YYYY HH24:MI:SS') ";
		}

		if($date_to != ""){
			$sql .= "AND ORDER_DATE <= to_date('".$date_to." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
		}

		if($cust_number != ""){
			$sql .= "AND OWNER_ID = '".$cust_number."' ";
		}

		if($deliver_to != ""){
			$sql .= "AND DELIVER_TO LIKE '%".$deliver_to."%' ";
		}

		if($cust_order != ""){
			$sql .= "AND ORDER_NO = '".$cust_order."' ";
		}

		if($LR_number != ""){
			$sql .= "AND LR_NUM = '".$LR_number."' ";
		}

		if($commodity != ""){
			$sql .= "AND COMMODITY_CODE = '".$commodity."' ";
		}

		if($BoL != ""){
			$sql .= "AND BOL = '".$BoL."' ";
		}

		$sql .= "ORDER BY D_DEL_NO DESC";
//		echo $sql;

		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor); 
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="4" align="center"><font color="FF0000">There are no Dummy entries that match this criteria.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="4">&nbsp;<br>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td width="10%"><font size="3" face="Verdana">Dummy No:</font></td>
				<td width="10%"><font size="3" face="Verdana">Commodity:</font></td>
				<td width="10%"><font size="3" face="Verdana">Customer:</font></td>
				<td width="10%"><font size="3" face="Verdana">Order Date:</font></td>
				<td width="10%"><font size="3" face="Verdana">Order Number:</font></td>
				<td width="10%"><font size="3" face="Verdana">Status:</font></td>
				<td width="40%"><font size="3" face="Verdana">Deliver To:</font></td>
			</tr>
<?
			do {
?>
			<tr>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['D_DEL_NO']; ?></font></td>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['COMMODITY_CODE']; ?></font></td>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['OWNER_ID']; ?></font></td>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['THE_ORDER_DATE']; ?></font></td>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['THE_ORDER']; ?></font></td>
				<td width="10%"><font size="2" face="Verdana"><? echo $row['THE_STATUS']; ?></font></td>
				<td width="40%"><font size="2" face="Verdana"><? echo $row['THE_DELIVERY']; ?></font></td>
			</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
		</table>
		</td>
	</tr>
<?		}
	}
?>
</table>
<?
	include("pow_footer.php");
?>