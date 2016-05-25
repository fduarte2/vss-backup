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


	$submit = $HTTP_POST_VARS['submit'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">EDI 309 Lookup/Summary
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="review_309.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">DSPC Arrival#:  </font></td>
		<td colspan="10"><select name="arv_select">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CLEMENTINES', 'CHILEAN', 'ARG FRUIT')
					AND TO_CHAR(LR_NUM) IN
						(SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $arv_select){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
		$arv_select = $HTTP_POST_VARS['arv_select'];
?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Container#</b></font></td>
		<td><font size="2" face="Verdana"><b>Action</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI Count</b></font></td>
	</tr>
<?
		$form_num = -1;
		$sql = "SELECT CONTAINER_NUM, BOL_EQUIV, COUNT(*) NEW_EDI_LINES
				FROM CLR_AMSEDI_DETAIL_309 CAD, CLR_LLOYD_ARRIVAL_MAP CLAM
				WHERE CAD.LLOYD_NUM = CLAM.LLOYD_NUM
					AND CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM
					AND CAD.VESNAME = CLAM.SHIP_NAME
					AND CLAM.ARRIVAL_NUM = '".$arv_select."'
					AND (AMEND_CODE IS NULL OR AMEND_CODE = 'A')
				GROUP BY CONTAINER_NUM, BOL_EQUIV
				ORDER BY BOL_EQUIV, CONTAINER_NUM";
		$rows = ociparse($rfconn, $sql);
		ociexecute($rows);
		while(ocifetch($rows)){
			$form_num++;

			$sql = "SELECT COUNT(*) DELETE_EDI_LINES
					FROM CLR_AMSEDI_DETAIL_309 CAD, CLR_LLOYD_ARRIVAL_MAP CLAM
					WHERE CAD.LLOYD_NUM = CLAM.LLOYD_NUM
						AND CAD.VOYAGE_NUM = CLAM.VOYAGE_NUM
						AND CAD.VESNAME = CLAM.SHIP_NAME
						AND CLAM.ARRIVAL_NUM = '".$arv_select."'
						AND (AMEND_CODE = 'D')
						AND BOL_EQUIV = '".ociresult($rows, "BOL_EQUIV")."'";
			$delete_rows = ociparse($rfconn, $sql);
			ociexecute($delete_rows);
			ocifetch($delete_rows);
?>
<form name="research[<? echo $form_num; ?>]" action="EDI309_links.php" method="post">
<input type="hidden" name="cont_select" value="<? echo ociresult($rows, "CONTAINER_NUM"); ?>">
<input type="hidden" name="bol_select" value="<? echo ociresult($rows, "BOL_EQUIV"); ?>">
<input type="hidden" name="arv_select" value="<? echo $arv_select; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "BOL_EQUIV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($rows, "CONTAINER_NUM"); ?></font></td>
		<td><input type="submit" name="submit" value="Retrieve EDIs"></td>
		<td><font size="2" face="Verdana"><? echo (ociresult($rows, "NEW_EDI_LINES") + ociresult($delete_rows, "DELETE_EDI_LINES")); ?></font></td>
	</tr>
</form>
<?
		}
?>
</table>
<?
	}
	include("pow_footer.php");