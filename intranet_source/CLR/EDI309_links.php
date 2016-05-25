<?
/*
*		Adam Walter, DEC 2014.
*
*		AMS-EDI 309 reviews by "primar key" (LR/BOL/CONT)
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "CLR preload";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CLR system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}


	$cont_select = $HTTP_POST_VARS['cont_select'];
	$bol_select = $HTTP_POST_VARS['bol_select'];
	$arv_select = $HTTP_POST_VARS['arv_select'];

	$sql = "SELECT * FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$arv_select."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">EDI 309 Detail
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>DSPC Arrival#:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $arv_select."-".ociresult($short_term_data, "VESSEL_NAME"); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Cont:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cont_select; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>BoL:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $bol_select; ?></font></td>
	</tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Time Received:</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI Amend Code:</b></font></td>
		<td><font size="2" face="Verdana"><b>CBP file:</b></font></td>
		<td><font size="2" face="Verdana"><b>Cargo Segment File:</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI Key-ID#:</b></font></td>
	</tr>
<?
	$sql = "SELECT CAD.KEY_ID, CAD.ENTRY_NUM, TO_CHAR(DATE_FILESPLIT, 'MM/DD/YYYY HH24:MI:SS') THE_REC, DATE_FILESPLIT,
				ORIGINAL_FILENAME, SPLIT_FILENAME, AMEND_CODE
			FROM CLR_AMSEDI_DETAIL_309 CAD, CANADIAN_AMSEDI_HEADER CAH, CLR_LLOYD_ARRIVAL_MAP CLAM
			WHERE CAD.KEY_ID = CAH.KEY_ID
				AND CAD.LLOYD_NUM = CLAM.LLOYD_NUM
				AND CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM
				AND CAD.VESNAME = CLAM.SHIP_NAME
				AND CLAM.ARRIVAL_NUM = '".$arv_select."'
				AND (CAD.CONTAINER_NUM = '".$cont_select."' OR AMEND_CODE = 'D')
				AND CAD.BOL_EQUIV = '".$bol_select."'
			ORDER BY DATE_FILESPLIT";
	$rows = ociparse($rfconn, $sql);
	ociexecute($rows);
	while(ocifetch($rows)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "THE_REC"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($rows, "AMEND_CODE"); ?></font></td>
		<td><font size="2" face="Verdana"><a href="../TS_Program/can_AMSEDI_filesplit/processed/<? echo ociresult($rows, "ORIGINAL_FILENAME"); ?>">
			<? echo ociresult($rows, "ORIGINAL_FILENAME"); ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="../TS_Program/can_AMSEDI_filesplit/split_files/EDI309/processed/<? echo ociresult($rows, "SPLIT_FILENAME"); ?>">
			<? echo ociresult($rows, "SPLIT_FILENAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "KEY_ID")."-".ociresult($rows, "ENTRY_NUM"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");