<?
/*
*	Adam Walter, Dec 2010
*	
*	Hotlist / meeting agenda combo page.
*	Asked for because one of the frnige benefits of being
*	A director/exec is that they are allowed to demand program changes
*	That anyone else would be told is a waste of time.
*
*	The agenda is "weekly" based, whereas the hot list stays availble
*	Until each item is closed.
*
*	Each new item is an inline addition, making this page a pain, as opposed
*	A page redirection.  Yeargle.
*
*****************************************************************************/




  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";


  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }

  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
//  $user_occ = $userdata['user_occ'];
//  if (stristr($user_occ,"Director") == false && array_search('ROOT', $user_types) === false ){
  if (array_search('DIRE', $user_types) === false && array_search('ROOT', $user_types) === false ){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }

  
  include("connect.php");
/*
   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }

   $username = $userdata['username'];
   $user_email = $userdata['user_email'];
*/

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $INNERcursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	$temp = date('w');
	if($temp >= 3){
	  $TUE_factor = 9 - $temp;
	} else {
	  $TUE_factor = 2 - $temp;
	}
	$next_TUE = mktime(0,0,0,date("m"),date("d") + $TUE_factor, date("Y"));
	$next_TUE_print = date('m/d/Y', $next_TUE);

	// make sure we know which "week" the meeting agenda is for
	$week = $HTTP_POST_VARS['week'];
//	echo "weekform<br>".$HTTP_POST_VARS['week']."<br><br>";
	if($week == ""){
	  $week = $next_TUE_print;
	}

	$submit = $HTTP_POST_VARS['submit'];
//	echo $submit."hi<br>";
	if($submit == "Save" || $submit == "Add Row"){
		$totalrows = $HTTP_POST_VARS['totalrows'];
		$department = $HTTP_POST_VARS['department'];

		$agenda_IDs = $HTTP_POST_VARS['agendarow']; // unneeded, but here for posterity
		$agenda_descs = $HTTP_POST_VARS['agenda'];

//		echo "here: ".$totalrows."  ".$department."  ".print_r($agenda_descs)."<br>";
		$sql = "DELETE FROM AGENDA_ITEMS
				WHERE USER_LEVEL = '".$department."'
				AND TO_CHAR(AGENDA_WEEK, 'MM/DD/YYYY') = '".$week."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		$sql = "SELECT NVL(MAX(AG_ROW_ID), 0) THE_MAX
				FROM AGENDA_ITEMS";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$next_id = $row['THE_MAX'] + 1;
		for($i = 0; $i < $totalrows; $i++){
			if($agenda_descs[$i] != ""){
				$sql = "INSERT INTO AGENDA_ITEMS
							(AG_ROW_ID,
							USER_LEVEL,
							INSERT_DATE,
							UPDATE_USER,
							AGENDA_WEEK,
							DESCRIPTION)
						VALUES
							('".$next_id."',
							'".$department."',
							SYSDATE,
							'".$user."',
							TO_DATE('".$week."', 'MM/DD/YYYY'),
							'".$agenda_descs[$i]."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				$next_id++;
			}
		}

		$HLheadID = $HTTP_POST_VARS['HLheadID'];
		$HLdetID = $HTTP_POST_VARS['HLdetID'];
		$HLitemdesc = $HTTP_POST_VARS['HLitemdesc'];
		$HLlastnotes = $HTTP_POST_VARS['HLlastnotes'];

		for($i = 0; $i < $totalrows; $i++){
			if($HLheadID[$i] == "" && $HLitemdesc[$i] != ""){ //  && $HLlastnotes[$i] != ""
				// this is a NEW HOTLIST ITEM.  create as such.
				// first, make sure someone didn't backpage and resubmt the same thing.
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM HOT_LIST_HEADER
						WHERE ITEM_DESCRIPTION = '".$HLitemdesc[$i]."'
						AND STATUS = 'OPEN'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$other_identical_items = $short_term_row['THE_COUNT'];

				// if lastnotes is null, put in a default value
				if($HLlastnotes[$i] == ""){
					$HLlastnotes[$i] = "New Entry";
				}

				if($other_identical_items < 1){
					// alright, this is legitamately new.
					$sql = "SELECT NVL(MAX(HL_ROW_ID), 0) THE_MAX
							FROM HOT_LIST_HEADER";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
					ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$next_id = $row['THE_MAX'] + 1;

					$sql = "INSERT INTO HOT_LIST_HEADER
								(HL_ROW_ID,
								USER_LEVEL,
								ITEM_DESCRIPTION,
								START_DATE,
								STATUS)
							VALUES
								('".$next_id."',
								'".$department."',
								'".$HLitemdesc[$i]."',
								SYSDATE,
								'OPEN')";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);

					$sql = "INSERT INTO HOT_LIST_DETAIL
								(HL_ROW_ID,
								HL_DETAIL_ID,
								UPDATE_DATE,
								UPDATE_USER,
								NOTES)
							VALUES
								('".$next_id."',
								'1',
								SYSDATE,
								'".$user."',
								'".$HLlastnotes[$i]."')";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
				}
			}

			if($HLheadID[$i] != ""){
				// this line already exists, see if new notes have been added...
				// notice the DESC sort qualifier.  This is so I only look at the LATEST update in my check.
				$sql = "SELECT NOTES FROM HOT_LIST_DETAIL
						WHERE HL_ROW_ID = '".$HLheadID[$i]."'
						AND HL_DETAIL_ID = '".$HLdetID[$i]."'
						ORDER BY UPDATE_DATE DESC";
//				echo "looper<br>".$sql."<br><br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['NOTES'] != $HLlastnotes[$i] && $HLlastnotes[$i] != ""){
					$sql = "INSERT INTO HOT_LIST_DETAIL
								(HL_ROW_ID,
								HL_DETAIL_ID,
								UPDATE_DATE,
								UPDATE_USER,
								NOTES)
							VALUES
								('".$HLheadID[$i]."',
								'".($HLdetID[$i] + 1)."',
								SYSDATE,
								'".$user."',
								'".$HLlastnotes[$i]."')";
//					echo "update<br>".$sql."<br><br>";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
				}
			}
		}
	}

	// pre-set each table ...
	// for each department
	$rowcount = array();
	$sql = "SELECT DISTINCT EDIT_PERMISSION FROM AGENDA_HOTLIST_AUTH ORDER BY EDIT_PERMISSION";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// --- AGENDA
		// step 1:  get # of entries
		$sql = "SELECT COUNT(*) THE_COUNT FROM AGENDA_ITEMS 
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND TO_CHAR(AGENDA_WEEK, 'MM/DD/YYYY') = '".$week."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$agenda_rowcount = $Short_Term_Row['THE_COUNT'];

		// --- HOT LIST
		// step 1:  get # of entries
		$sql = "SELECT COUNT(*) THE_COUNT FROM HOT_LIST_HEADER 
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND STATUS = 'OPEN'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$HL_rowcount = $Short_Term_Row['THE_COUNT'];


		$rowcount[$row['EDIT_PERMISSION']] = max($agenda_rowcount, $HL_rowcount);

		// step 2 bring it up to 5 if it is less
		if($rowcount[$row['EDIT_PERMISSION']] < 5){
			$rowcount[$row['EDIT_PERMISSION']] = 5;
		}

		// step 3:  if they are "submitting a new row", override the previous logic with hard-entered value.
		if($submit == "Add Row" && $HTTP_POST_VARS["department"] == $row['EDIT_PERMISSION']){
			$rowcount[$row['EDIT_PERMISSION']] = $HTTP_POST_VARS["totalrows"] + 1;
		}
	}

	// figure out who gets to do what on this page...
	$sql ="SELECT EDIT_PERMISSION FROM AGENDA_HOTLIST_AUTH WHERE INTRANET_LOGIN = '".strtoupper($user)."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$authority_type = "";
	} else {
		$authority_type = $row['EDIT_PERMISSION'];
	}

?>

<script language="javascript">

function process(form_num){
  if(document.pressed == 'Print'){
	document.buttonpress[form_num].action="hotlist_agenda_pdf.php";
  }else {
	document.buttonpress[form_num].action="hotlist_agenda_combo.php";
  }
  return true;
}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Agenda/HotList
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<form name="change_date" action="hotlist_agenda_combo.php" method="post">
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Meeting Week Of:&nbsp;&nbsp;&nbsp;&nbsp;<select name="week" onchange="document.change_date.submit(this.form)">
<?
			for($i = 0; $i <= 15; $i++){
				$value = date('m/d/Y', mktime(0,0,0,date("m"),date("d") + $TUE_factor - ($i * 7), date("Y")));
?>
								<option value="<? echo $value; ?>"<? if($value == $week){?> selected <?}?>><? echo $value;?></option>
<?
			}
?>
								</select></td>
		</form>
		
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<table border="2" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="25%" align="center"><font size="3" face="Verdana"><b>Agenda</b></font></td>
		<td width="65%" align="center"><font size="3" face="Verdana"><b>Hot List</b></font></td>
	</tr>

<?
	/*
	*	Da Meat of the page.
	*	This is a loop for each "department" on the port, as defined by the list of departments
	*	That have qualified directors to view them.  am I clever or what ;p.
	******************************************************************************/

	$form_num = 0;
	$sql = "SELECT EDIT_PERMISSION, MIN(SCREEN_ORDER) 
			FROM AGENDA_HOTLIST_AUTH
			GROUP BY EDIT_PERMISSION
			ORDER BY MIN(SCREEN_ORDER)";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<form name="buttonpress" action="hotlist_agenda_combo.php" method="post" onSubmit="return process(<? echo $form_num; ?>);">
	<input type="hidden" name="week" value="<? echo $week; ?>">
	<input type="hidden" name="department" value="<? echo $row['EDIT_PERMISSION']; ?>">
	<input type="hidden" name="totalrows" value="<? echo $rowcount[$row['EDIT_PERMISSION']]; ?>">
	<tr>
		<!-- Section Header !-->
		<td width="10%" align="center" valign="center">
			<font size="2" face="Verdana"><b><? echo $row['EDIT_PERMISSION']; ?></b></font><br>
<?
		if($authority_type == $row['EDIT_PERMISSION']){					
?>		
				<br><input name="submit" type="submit" value="Save" onclick="document.pressed=this.value"><br>
				<br><input name="submit" type="submit" value="Print" onclick="document.pressed=this.value"><br>
				<br><input name="submit" type="submit" value="Add Row" onclick="document.pressed=this.value"><br>
<?
		}
?>
		</td>


		<!-- AGENDA SECTION !-->
		<td width="25%" align="center" valign="top">
			<table border="0" cellpadding="2" cellspacing="0"> <!-- width="80"  !-->
				<tr>
					<td align="center"><font size="2" face="Verdana"><b>#</b></font></td>
					<td align="center"><font size="2" face="Verdana"><b>Agenda Item</b></font></td>
				</tr>
<?
		$current_count = 0;
		$sql = "SELECT * FROM AGENDA_ITEMS 
					WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
					AND AGENDA_WEEK = TO_DATE('".$week."', 'MM/DD/YYYY')";
//		echo $sql;
		ora_parse($INNERcursor, $sql);
		ora_exec($INNERcursor);
		while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<input type="hidden" name="agendarow[<? echo $current_count; ?>]" value="<? echo $INNERrow['AG_ROW_ID']; ?>">
				<tr>
					<td><font size="2" face="Verdana"><? echo ($current_count + 1); ?></font></td>
					<td><input name="agenda[<? echo $current_count; ?>]" type="text" size="30" maxlength="30" value="<? echo $INNERrow['DESCRIPTION']; ?>" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
				</tr>
<?
			$current_count++;
		}
		while($current_count < $rowcount[$row['EDIT_PERMISSION']]){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ($current_count + 1); ?></font></td>
					<td><input name="agenda[<? echo $current_count; ?>]" type="text" size="30" maxlength="30" value="" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
				</tr>
<?
			$current_count++;
		}
?>
			</table>
		</td>


		<!-- HOTLIST SECTION !-->
		<td width="65%" align="center" valign="top">
			<table border="0" width="100%" cellpadding="2" cellspacing="0"> <!-- rules="none"  !-->
				<tr>
					<td><font size="2" face="Verdana"><b><nobr>HL#</nobr></b></font></td>
					<td><font size="2" face="Verdana"><b><nobr>Created</nobr></b></font></td>
					<td><font size="2" face="Verdana"><b><nobr>Item</nobr></b></font></td>
					<td><font size="2" face="Verdana"><b><nobr>Updated</nobr></b></font></td>
					<td><font size="2" face="Verdana"><b><nobr>Update Notes</nobr></b></font></td>
				</tr>
<?
		$current_count = 0;
		$sql = "SELECT HLH.HL_ROW_ID, TO_CHAR(START_DATE, 'MM/DD/YY') ENTRY_DATE, ITEM_DESCRIPTION
				FROM HOT_LIST_HEADER HLH
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND STATUS = 'OPEN'
				ORDER BY HLH.HL_ROW_ID";
		ora_parse($INNERcursor, $sql);
		ora_exec($INNERcursor);
		while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT HL_DETAIL_ID, TO_CHAR(UPDATE_DATE, 'MM/DD/YY') UPDATED, NOTES
					FROM HOT_LIST_DETAIL
					WHERE HL_ROW_ID = '".$INNERrow['HL_ROW_ID']."'
					ORDER BY UPDATE_DATE DESC";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$goto_link = $INNERrow['HL_ROW_ID'];
?>
				<input type="hidden" name="HLheadID[<? echo $current_count; ?>]" value="<? echo $INNERrow['HL_ROW_ID']; ?>"> 
				<input type="hidden" name="HLdetID[<? echo $current_count; ?>]" value="<? echo $Short_Term_row['HL_DETAIL_ID']; ?>">
				<tr>
					<td><font size="2" face="Verdana"><a href="hotlist_det_close.php?HL=<? echo $goto_link; ?>"><? echo $goto_link; ?></a></td>
					<td><font size="2" face="Verdana"><? echo $INNERrow['ENTRY_DATE']; ?></td>
					<td><input name="HLitemdesc[<? echo $current_count; ?>]" type="text" size="20" maxlength="30" value="<? echo $INNERrow['ITEM_DESCRIPTION']; ?>" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
					<td><font size="2" face="Verdana"><? echo $Short_Term_row['UPDATED']; ?></td>
					<td><input name="HLlastnotes[<? echo $current_count; ?>]" type="text" size="40" maxlength="100" value="<? echo $Short_Term_row['NOTES']; ?>" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
				</tr>
<?
			$current_count++;
		}
		while($current_count < $rowcount[$row['EDIT_PERMISSION']]){
?>
				<tr>
					<td><font size="2" face="Verdana">---</td>
					<td><font size="2" face="Verdana">---</td>
					<td><input name="HLitemdesc[<? echo $current_count; ?>]" type="text" size="20" maxlength="30" value="" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
					<td><font size="2" face="Verdana">---</td>
					<td><input name="HLlastnotes[<? echo $current_count; ?>]" type="text" size="40" maxlength="100" value="" <? if($authority_type != $row['EDIT_PERMISSION']){?>readonly<?}?>></td>
				</tr>
<?
			$current_count++;
		}
?>
			</table>
		</td>
	</tr>
	</form>
<?
		$form_num++;
	}
?>
</table>
<?
	include("pow_footer.php");
?>