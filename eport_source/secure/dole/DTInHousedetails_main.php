<?
/*
*	Adam Walter, Feb 2009
*
*	This page is a report for in-house rolls of Dole
*	Paper, and current orders against them.
*
*	Displays As-of the time report is generated.
********************************************************/

//	include 'class.ezpdf.php';
//	include("useful_info.php");
//	$short_term_data_cursor = ora_open($conn);
//	$dockticket_cursor = ora_open($conn);

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$DT = $HTTP_GET_VARS['DT'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dockticket Paper REAL TIME InHouse Inventory Details
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet</b></font></td>
		<td><font size="2" face="Verdana"><b>Dock Ticket</b></font></td>
		<td><font size="2" face="Verdana"><b>Date received</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Container#</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Railcar/Truck#</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
	</tr>
<?
	$sql = "SELECT PALLET_ID, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') THE_REC, CONTAINER_ID, ARRIVAL_NUM, WEIGHT || WEIGHT_UNIT THE_WT
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED IS NOT NULL
				AND REMARK = 'DOLEPAPERSYSTEM'
				AND BOL = '".$DT."'
				AND QTY_IN_HOUSE > 0
			ORDER BY PALLET_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
	<tr>
		<td><? echo ociresult($short_term_data, "PALLET_ID"); ?></td>
		<td><? echo $DT; ?></td>
		<td><? echo ociresult($short_term_data, "THE_REC"); ?></td>
<!--		<td><? echo ociresult($short_term_data, "CONTAINER_ID"); ?></td> !-->
		<td><? echo ociresult($short_term_data, "ARRIVAL_NUM"); ?></td>
		<td><? echo ociresult($short_term_data, "THE_WT"); ?></td>
	</tr>
<?
	}
?>
</table>