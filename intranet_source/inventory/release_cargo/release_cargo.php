<?
/*
*
*	Adam Walter, May 2008.
*
*	The "maiden voyage" of PHPGrid's grid-editing functionality.
*
*	Page displays data from new table "RELEASED_CARGO", and allows editing
*	Of "received_date" field.
********************************************************************************/
	if($HTTP_POST_VARS['vessel'] != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
	} else {
		$vessel = $HTTP_COOKIE_VARS['vessel'];
	}
	setcookie("vessel", $vessel, time()+300);

	include("include/phpgrid.php");
	$dbUser = "sag_owner";
	$hostName = "172.22.15.204:1521"; // RF LIVE
//	$hostName = "172.22.15.238:1521"; // RFTEST
	$password = "OWNER";
//	$password = "RFTEST238";
	$dbName  = "RF";
//	$dbName = "RFTEST";

  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Data = ora_open($conn);

	// pre-processing of table.  step 1:  insert records for vessel if none exist (or not enough exist)
	if($vessel != ""){
		$sql = "SELECT DISTINCT ARRIVAL_NUM, SUBSTR(HATCH, 1, 2) THE_HATCH FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND HATCH IS NOT NULL";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql2 = "SELECT COUNT(*) THE_COUNT FROM RELEASED_CARGO WHERE ARRIVAL_NUM = '".$vessel."' AND HATCH_DECK = '".$first_row['THE_HATCH']."'";
			ora_parse($cursor_second, $sql2);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($second_row['THE_COUNT'] == 0){
				$sql = "SELECT NVL(MAX(ROW_NBR), 0) THE_MAX FROM RELEASED_CARGO";
				ora_parse($Short_Term_Data, $sql);
				ora_exec($Short_Term_Data);
				ora_fetch_into($Short_Term_Data, $S_T_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$row_nbr = $S_T_row['THE_MAX'] + 1;

				$insert_sql = "INSERT INTO RELEASED_CARGO (ARRIVAL_NUM, HATCH_DECK, OPS_SET, ROW_NBR) VALUES ('".$vessel."', '".$first_row['THE_HATCH']."', 'N', ".$row_nbr.")";
				ora_parse($cursor_third, $insert_sql);
				ora_exec($cursor_third);
			}
		}
	}
	//pre-process step 2:  Anyone who has just updated this table, add their login name to said update row.
	$sql = "UPDATE RELEASED_CARGO SET LOGIN_ID = '".$userdata['username']."' WHERE ARRIVAL_NUM IS NOT NULL AND HATCH_DECK IS NOT NULL AND RELEASED_DATE IS NOT NULL AND LOGIN_ID IS NULL AND OPS_SET = 'Y'";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);

	//pre-process done.

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Kiwi HatchDeck Release
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="release_cargo.php" method="post">
	<tr>
		<td align="center"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' ORDER BY LR_NUM DESC";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		while(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Retrieve Vessel"><hr></td>
	</tr>
</form>
</table>
<?
	// close oracle connections to prepare for Datagrid usage
	ora_close($cursor_first);
	ora_close($cursor_second);
	ora_close($cursor_third);
	ora_close($Short_Term_Data);
	ora_commit($conn);




	if($vessel != ""){
		$sql = "SELECT ROW_NBR, ARRIVAL_NUM, HATCH_DECK, TO_CHAR(RELEASED_DATE, 'MM/DD/YYYY HH24:MI') THE_DATE, LOGIN_ID, OPS_SET FROM RELEASED_CARGO WHERE ARRIVAL_NUM = '".$vessel."'";
		$dg = new C_DataGrid($hostName, $dbUser, $password, $dbName, "oracle");

		$dg -> set_gridpath     ("../../functions/phpgrid/include/");
//		$dg -> set_gridpath     ("/web/web_pages/functions/phpgrid/include/");
		$dg -> set_sql          ($sql);
		$dg -> set_sql_table    ("RELEASED_CARGO");
		$dg -> set_sql_key      ("ROW_NBR");
		$dg -> set_col_title    ("HATCH_DECK", "HATCH / DECK");
		$dg -> set_col_title    ("THE_DATE", "RELEASED DATE");
		$dg -> set_col_title    ("LOGIN_ID", "USER");
		$dg -> set_col_title    ("OPS_SET", "RELEASED?");
		$dg -> set_col_hidden   ("ARRIVAL_NUM");
		$dg -> set_col_hidden   ("ROW_NBR");

		$dg -> set_allow_actions(true);
		$dg -> set_action_type ("VE");   
		$dg -> set_fields_readonly("ARRIVAL_NUM, HATCH_DECK, RELEASED_DATE, LOGIN_ID");
		$dg -> add_control("OPS_SET",
							RADIO,
							array("N"=>"N",
									"Y"=>"Y"));
		$dg -> set_theme("adam-bone");
		$dg -> display();
	}
	include("pow_footer.php");
?>