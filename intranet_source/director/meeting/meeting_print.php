<?php

include("pow_session.php");
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


include 'class.ezpdf.php';
$week = date('m/d/Y');

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












   	// output 
   	$pdfcode = $pdf->ezOutput();

	$File=chunk_split(base64_encode($pdfcode));


        $mailTO = "ithomas@port.state.de.us";

        $mailTo1 = "gbailey@port.state.de.us,";
        $mailTo1 .="fvignuli@port.state.de.us,";
        $mailTo1 .="ithomas@port.state.de.us,";
		$mailTo1 .="rhorne@port.state.de.us,";
        $mailTo1 .="skennard@port.state.de.us,";
        $mailTo1 .="parul@port.state.de.us,";
        $mailTo1 .="tkeefer@port.state.de.us";

        $mailsubject = "Director's Meeting: Agenda and Hotlist";
   
        $mailheaders  = "From: " . "noreplies@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,hdadmin@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
      	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
      	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
      	$mailheaders .= "X-Mailer: PHP4\r\n"; 
      	$mailheaders .= "X-Priority: 3\r\n"; 
      	$maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
      	$maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"AgendaHotList.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n"; 
      

//        mail($mailTO, $mailsubject, $Content, $mailheaders);
        mail($mailTo1, $mailsubject, $Content, $mailheaders);

?>
