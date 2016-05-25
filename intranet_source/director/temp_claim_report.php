<?
/*
*	Adam Walter, Nov 23, 2007.
*
*	Temporary page for Inigo
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims Prospect";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$total_bad_for_us = 0;
	$fd = fopen("clemclaims.txt", "w");

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Possible Claims, Clementine
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="3" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel#</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Rec.</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Rec.</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Act.</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Dmg (CT)</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Dmg (CA)</b></font></td>
		<td><font size="2" face="Verdana"><b>Diff Dmg (CA - CT)</b></font></td>
	</tr>
<?
	fwrite($fd, "Order#,Vessel#,Pallet,QTY Rec.,Date Rec.,Mark,Date of Act.,QTY Dmg (CT),QTY Dmg (CA),Diff Dmg (CA - CT)\n");
//	these lines removed from SQL below
//			SUBSTR(CA.ACTIVITY_DESCRIPTION, 5, 1) SHIPOUTDMG,
//			(SUBSTR(CA.ACTIVITY_DESCRIPTION, 5, 1) - CT.QTY_DAMAGED) POTENTIALCLM  

	$sql = "SELECT ORDER_NUM, CT.ARRIVAL_NUM THE_VES, 
				CT.PALLET_ID THE_PALLET,  
				CT.QTY_RECEIVED,  
				TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE, 
				NVL(CT.QTY_DAMAGED, 0) RCVDDMG, 
				CT.MARK,
				CA.ACTIVITY_DESCRIPTION,  
				TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') DATE_ACT
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CUSTOMER_ID IN (439, 440, 835)
				AND SERVICE_CODE = 6
				AND ACTIVITY_DESCRIPTION LIKE '%DMG%'
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND	CT.MARK = 'SHIPPED'
			ORDER BY CA.ORDER_NUM, CT.PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$temp = split(":", $row['ACTIVITY_DESCRIPTION']);
		if($temp[1] != $row['RCVDDMG']){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['MARK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_ACT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['RCVDDMG']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[1]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[1] - $row['RCVDDMG']; ?></font></td>
	</tr>
<?
//		if(($temp[1] - $row['RCVDDMG']) > 0){ not doing right now
			fwrite($fd, $row['ORDER_NUM'].",".$row['THE_VES'].",".$row['THE_PALLET'].",".$row['QTY_RECEIVED'].",".$row['THE_DATE'].",".$row['MARK'].",".$row['DATE_ACT'].",".$row['RCVDDMG'].",".$temp[1].",".($temp[1] - $row['RCVDDMG'])."\n");
		}
	}
?>
</table>
<?
	fclose($fd);
	include("pow_footer.php");
?>