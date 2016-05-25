<?
/*
*	Adam Walter, Dec 2010
*	
*	Hotlist / meeting agenda printout-formatted page.
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";

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

	$totalrows = $HTTP_POST_VARS['totalrows'];
	$department = $HTTP_POST_VARS['department'];

	$agenda_IDs = $HTTP_POST_VARS['agendarow']; // unneeded, but here for posterity
	$agenda_descs = $HTTP_POST_VARS['agenda'];

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
		if($HLheadID[$i] == "" && $HLitemdesc[$i] != "" && $HLlastnotes[$i] != ""){
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
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
			}
		}
	}

	$week = $HTTP_POST_VARS['week'];

	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','landscape');
//	$pdf = new Cezpdf('letter','portrait');
	$pdf->ezSetMargins(20,20,25,25);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$current_table_rowcount = 0;
	$output_table = array();
	$output_headings = array('dept'=>'',
								'Agenda'=>'<b>AGENDA</b>',
								'space'=>'',
								'Desc'=>'<b>HOT LIST ITEM</b>',
								'Created'=>'<b>CREATED</b>',
								'Update'=>'<b>UPDATED</b>',
								'Notes'=>'<b>UPDATE NOTES</b>');
//								'HL'=>'<b>HL#</b>',


	$sql = "SELECT EDIT_PERMISSION, MIN(SCREEN_ORDER) 
			FROM AGENDA_HOTLIST_AUTH
			GROUP BY EDIT_PERMISSION
			ORDER BY MIN(SCREEN_ORDER)";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// figure out which table has more rows

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM AGENDA_ITEMS
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND TO_CHAR(AGENDA_WEEK, 'MM/DD/YYYY') = '".$week."'
				ORDER BY AG_ROW_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$agenda_count = $Short_Term_row['THE_COUNT'];

		$sql = "SELECT COUNT(*) THE_COUNT 
				FROM HOT_LIST_HEADER
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND STATUS = 'OPEN'
				ORDER BY HL_ROW_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$HL_count = $Short_Term_row['THE_COUNT'];

		$up_to_count = max($agenda_count, $HL_count);




		// AGENDA
		$i = 0;
		$agenda_array = array();

		$sql = "SELECT DESCRIPTION FROM AGENDA_ITEMS
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND TO_CHAR(AGENDA_WEEK, 'MM/DD/YYYY') = '".$week."'
				ORDER BY AG_ROW_ID";
		ora_parse($INNERcursor, $sql);
		ora_exec($INNERcursor);
		if(!ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$agenda_array[0] = "---No Agenda Items";
			$i++;
		} else {
			do {
				$agenda_array[$i] = $INNERrow['DESCRIPTION'];
				$i++;
			} while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		for(;$i < $up_to_count; $i++){
			$agenda_array[$i] = "";
		}



		// HOT LIST
		$i = 0;
		$HL_array = array();

		$sql = "SELECT HL_ROW_ID, ITEM_DESCRIPTION, TO_CHAR(START_DATE, 'MM/DD/YY') THE_START 
				FROM HOT_LIST_HEADER
				WHERE USER_LEVEL = '".$row['EDIT_PERMISSION']."'
				AND STATUS = 'OPEN'
				ORDER BY HL_ROW_ID";
		ora_parse($INNERcursor, $sql);
		ora_exec($INNERcursor);
		if(!ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$HL_array[0]['HL'] = "";
			$HL_array[0]['Created'] = "";
			$HL_array[0]['Desc'] = "---None---";
			$HL_array[0]['Update'] = "";
			$HL_array[0]['Notes'] = "";
			$i++;
		} else {
			do {
				$HL_array[$i]['HL'] = $INNERrow['HL_ROW_ID'];
				$HL_array[$i]['Created'] = $INNERrow['THE_START'];
				$HL_array[$i]['Desc'] = $INNERrow['ITEM_DESCRIPTION'];

				$sql = "SELECT NOTES, TO_CHAR(UPDATE_DATE, 'MM/DD/YY') THE_UPDATE
						FROM HOT_LIST_DETAIL
						WHERE HL_ROW_ID = '".$INNERrow['HL_ROW_ID']."'
						ORDER BY UPDATE_DATE DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$HL_array[$i]['Update'] = $Short_Term_row['THE_UPDATE'];
				$HL_array[$i]['Notes'] = $Short_Term_row['NOTES'];

				$i++;
			} while(ora_fetch_into($INNERcursor, $INNERrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		for(;$i < $up_to_count; $i++){
			$HL_array[$i]['HL'] = "";
			$HL_array[$i]['Created'] = "";
			$HL_array[$i]['Desc'] = "";
			$HL_array[$i]['Update'] = "";
			$HL_array[$i]['Notes'] = "";
		}



		// DISPLAY CURRENT TABLE AND BREAK PAGE IF NEEDED
		if($current_table_rowcount + $up_to_count > 27 && $current_table_rowcount > 0){
			$pdf->ezText("DIRECTOR'S MEETING", 16, $center);
			$pdf->ezSetDy(-5);
			$pdf->ezText("Week of $week", 14, $center);
			$pdf->ezSetDy(-10);

			$pdf->ezTable($output_table, $output_headings, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'colGap'=>2, 'cols'=>array('space'=>array('width'=>5)))); //, 'cols'=>array('HL'=>array('link'=>'url'), 'space'=>array('width'=>5))

			$pdf->ezNewPage();

			$current_table_rowcount = 0;

			$output_table = array();
		}


		// PUT IN THE OUTPUT TABLE
		$i = 0;

		for($i = 0; $i < max($up_to_count, 1); $i++){
			if($i == 0){
				$dept = "<b>".$row['EDIT_PERMISSION']."</b>";
			} else {
				$dept = "";
			}
			array_push($output_table, array('dept'=>$dept,
								'Agenda'=>$agenda_array[$i],
								'space'=>'',
								'Desc'=>$HL_array[$i]['Desc'],
								'Created'=>$HL_array[$i]['Created'],
								'Update'=>$HL_array[$i]['Update'],
								'Notes'=>$HL_array[$i]['Notes']));
			//					'HL'=>$HL_array[$i]['HL'],
			//					'url'=>"http://dspc-s16/director/hotlist_agenda_combo/hotlist_det_close.php?HL=".$INNERrow['HL_ROW_ID']
		}
		array_push($output_table, array('dept'=>'',
							'Agenda'=>'',
							'space'=>'',
							'Desc'=>'',
							'Created'=>'',
							'Update'=>'',
							'Notes'=>''));
			//				'HL'=>'',
			//				'url'=>"http://dspc-s16/director/hotlist_agenda_combo/hotlist_det_close.php?HL=".$INNERrow['HL_ROW_ID']
		$current_table_rowcount += $up_to_count;
	}

	$pdf->ezText("DIRECTOR'S MEETING", 16, $center);
	$pdf->ezSetDy(-5);
	$pdf->ezText("Week of $week", 14, $center);
	$pdf->ezSetDy(-10);

	$pdf->ezTable($output_table, $output_headings, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'colGap'=>2, 'cols'=>array('space'=>array('width'=>5)))); //, 'cols'=>array('HL'=>array('link'=>'url'), 'space'=>array('width'=>5))

	include("redirect_pdf.php");
?>
