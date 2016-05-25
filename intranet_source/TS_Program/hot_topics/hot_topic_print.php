<?
/* Adam Walter, May, 2007
*
*  The final page of the "Hot Topics" agenda pages, this formats one
*  Departments Topic list into a pdf file for printing.
*
*
*
*  I seem to have made far too many lines for adding comments, so in
*  This space, I shall dictate tomorrow's lottery numbers:
*  32-16-05-19-1-pi
************************************************************************/

// validate user
include("pow_session.php");
$user = $userdata['username'];

$auth = $HTTP_POST_VARS['auth'];
if($auth == ""){
	$auth = $HTTP_GET_VARS['auth'];
}

$today = date("m/d/Y h:i a");
$data = array();


  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
//  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
   	printf(ora_errorcode($conn));
   	exit;
  }
  $cursor = ora_open($conn);

include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','portrait');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
$pdf->ezSetDy(-10);
$pdf->ezStartPageNumbers(65, 65, 12, 'right', 'Confidential - {PAGENUM}', 1);

	$title = "Hot Topics";

	switch($auth) {
	case "EXEC":
		$title .= " - Executive Director";
		break;
	case "OPS":
		$title .= " - Operations";
		break;
	case "MRKT":
		$title .= " - Marketing";
		break;
	case "TECH":
		$title .= " - Tech Solutions";
		break;
	case "HR":
		$title .= " - HR";
		break;
	case "FINA":
		$title .= " - Finance";
		break;
	case "ENG":
		$title .= " - Engineering";
		break;
	case "ALL":
		$title .= " - All Sections";
		break;
	}


	$row_counter = 0;
	$current_topic = "";
	$current_auth = "ENG";  // only used in all-print format


	// this is done if a specific section is selected for printing
	if($auth != 'ALL') {

		$pdf->ezText("<b>$title</b>", 16, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);
		
		$sql = "SELECT HT.TOPIC THE_TOPIC, TO_CHAR(HT.START_DATE, 'MM/DD/YYYY') THE_START, HT.COMPLETION_DATE THE_COMP, TO_CHAR(HTD.STATUS_DATE, 'MM/DD/YYYY') THE_STATUS, HTD.NOTES THE_NOTES FROM HOT_TOPICS HT, HOT_TOPIC_DETAIL HTD WHERE HT.HT_ROW_ID = HTD.HT_ROW_ID AND HT.STATUS = 'OPEN' AND USER_LEVEL = '".$auth."' ORDER BY HT.HT_ROW_ID, HT_DETAIL_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($current_topic != $row['THE_TOPIC']){
				$row_counter++;
				array_push($data, array('#'=>$row_counter, 'Goals / Projects / Tasks'=>$row['THE_TOPIC'], 'Start Date'=>$row['THE_START'],
										'Est. Completion'=>$row['THE_COMP'], 'Status'=>$row['THE_STATUS'],
										'Notes'=>$row['THE_NOTES']));
				$current_topic = $row['THE_TOPIC'];
			} else {
				array_push($data, array('#'=>'', 'Goals / Projects / Tasks'=>'', 'Start Date'=>'',
										'Est. Completion'=>'', 'Status'=>$row['THE_STATUS'],
										'Notes'=>$row['THE_NOTES']));
			}
		}

		$pdf->ezTable($data, array('#'=>'#',
									'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
									'Start Date'=>'Start Date', 
									'Est. Completion'=>'Est. Completion',
									'Status'=>'Status',
									'Notes'=>'Notes'), '', 
									array('showHeadings'=>1, 'shaded'=>0, 'rowGap'=>5, 'colGap'=>5, 'width'=>575,
										'cols'=>array('Status / Date'=>array('showLines'=>2), 'Notes'=>array('showLines'=>2))));
	} else { // do if all
		$pdf->ezSetDy(-180);
		$pdf->ezText("<b>$title</b>", 24, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);

		
		$sql = "SELECT HT.USER_LEVEL THE_USER, HT.TOPIC THE_TOPIC, TO_CHAR(HT.START_DATE, 'MM/DD/YYYY') THE_START, HT.COMPLETION_DATE THE_COMP, TO_CHAR(HTD.STATUS_DATE, 'MM/DD/YYYY') THE_STATUS, HTD.NOTES THE_NOTES FROM HOT_TOPICS HT, HOT_TOPIC_DETAIL HTD WHERE HT.HT_ROW_ID = HTD.HT_ROW_ID AND HT.STATUS = 'OPEN' ORDER BY USER_LEVEL, HT.HT_ROW_ID, HT_DETAIL_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			switch($current_auth) {
			case "EXEC":
				$title = "Hot Topics - Executive Director";
				break;
			case "OPS":
				$title = "Hot Topics - Operations";
				break;
			case "MRKT":
				$title = "Hot Topics - Marketing";
				break;
			case "TECH":
				$title = "Hot Topics - Tech Solutions";
				break;
			case "HR":
				$title = "Hot Topics - HR";
				break;
			case "FINA":
				$title = "Hot Topics - Finance";
				break;
			case "ENG":
				$title = "Hot Topics - Engineering";
				break;
			}

			if($current_auth != $row['THE_USER']){
				$pdf->ezNewPage();

				$pdf->ezText("<b>$title</b>", 16, $center);
				$pdf->ezSetDy(-10);
				$pdf->ezText("<b>Printed On $today</b>", 12, $center);
				$pdf->ezSetDy(-15);

				$pdf->ezTable($data, array('#'=>'#',
											'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
											'Start Date'=>'Start Date', 
											'Est. Date of Completion'=>'Est. Date of Completion',
											'Status / Date'=>'Status / Date',
											'Notes'=>'Notes'), '', 
											array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'rowGap'=>5, 'colGap'=>5, 'width'=>575));

				$data = array();			
				$row_counter = 0;
				$current_auth = $row['THE_USER'];
			}

			if($current_topic != $row['THE_TOPIC']){
				$row_counter++;
				array_push($data, array('#'=>$row_counter, 'Goals / Projects / Tasks'=>$row['THE_TOPIC'], 'Start Date'=>$row['THE_START'],
										'Est. Date of Completion'=>$row['THE_COMP'], 'Status / Date'=>$row['THE_STATUS'],
										'Notes'=>$row['THE_NOTES']));
				$current_topic = $row['THE_TOPIC'];
			} else {
				array_push($data, array('#'=>'', 'Goals / Projects / Tasks'=>'', 'Start Date'=>'',
										'Est. Date of Completion'=>'', 'Status / Date'=>$row['THE_STATUS'],
										'Notes'=>$row['THE_NOTES']));
			}
		}

		// all this needs to be done 1 more time after the loop
		$pdf->ezNewPage();
		$pdf->ezText("<b>$title</b>", 16, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);

		$pdf->ezTable($data, array('#'=>'#',
									'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
									'Start Date'=>'Start Date', 
									'Est. Date of Completion'=>'Est. Date of Completion',
									'Status / Date'=>'Status / Date',
									'Notes'=>'Notes'), '', 
									array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'rowGap'=>5, 'colGap'=>5, 'width'=>575));


	}



   // redirect to a temporary PDF file instead of directly writing to the browser
   include("redirect_pdf.php");


/*
	// this is done if a specific section is selected for printing
	if($auth != 'ALL') {
		
		$pdf->ezText("<b>$title</b>", 16, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);
		
		$sql = "SELECT TOPIC, UPDATE_DESCRIPTION, LAST_UPDATE_BY, to_char(START_DATE, 'MM/DD/YYYY') THE_START, COMPLETION_DATE THE_COMP, to_char(UPDATE_DATE, 'MM/DD/YYYY') THE_UPDATE FROM HOT_TOPICS WHERE USER_LEVEL = '".$auth."' AND STATUS = 'OPEN' ORDER BY THE_UPDATE ASC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$row_counter++;
			array_push($data, array('#'=>$row_counter, 'Goals / Projects / Tasks'=>$row['TOPIC'], 'Start Date'=>$row['THE_START'],
									'Estimated Date of Completion'=>$row['THE_COMP'], 'Status / Date'=>$row['THE_UPDATE'],
									'Update'=>$row['UPDATE_DESCRIPTION'], 'Last Updated By'=>$row['LAST_UPDATE_BY']));
		}

		$pdf->ezTable($data, array('#'=>'#',
									'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
									'Start Date'=>'Start Date', 
									'Estimated Date of Completion'=>'Estimated Date of Completion',
									'Status / Date'=>'Status / Date',
									'Update'=>'Update',
									'Last Updated By'=>'Last Updated By'), '', 
									array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'rowGap'=>5, 'colGap'=>5, 'width'=>575));

	} else { // do this if it's all
		$pdf->ezSetDy(-180);
		$pdf->ezText("<b>$title</b>", 24, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);

		$sql = "SELECT USER_LEVEL, TOPIC, UPDATE_DESCRIPTION, LAST_UPDATE_BY, to_char(START_DATE, 'MM/DD/YYYY') THE_START, COMPLETION_DATE THE_COMP, to_char(UPDATE_DATE, 'MM/DD/YYYY') THE_UPDATE FROM HOT_TOPICS WHERE STATUS = 'OPEN' ORDER BY USER_LEVEL, THE_UPDATE ASC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			switch($current_auth) {
			case "EXEC":
				$title = "Hot Topics - Executive Director";
				break;
			case "OPS":
				$title = "Hot Topics - Operations";
				break;
			case "MRKT":
				$title = "Hot Topics - Marketing";
				break;
			case "TECH":
				$title = "Hot Topics - Tech Solutions";
				break;
			case "HR":
				$title = "Hot Topics - HR";
				break;
			case "FINA":
				$title = "Hot Topics - Finance";
				break;
			case "ENG":
				$title = "Hot Topics - Engineering";
				break;
			}

			
			if($current_auth != $row['USER_LEVEL']){
				$pdf->ezNewPage();

				$pdf->ezText("<b>$title</b>", 16, $center);
				$pdf->ezSetDy(-10);
				$pdf->ezText("<b>Printed On $today</b>", 12, $center);
				$pdf->ezSetDy(-15);

				$pdf->ezTable($data, array('#'=>'#',
											'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
											'Start Date'=>'Start Date', 
											'Estimated Date of Completion'=>'Estimated Date of Completion',
											'Status / Date'=>'Status / Date',
											'Update'=>'Update',
											'Last Updated By'=>'Last Updated By'), '', 
											array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'rowGap'=>5, 'colGap'=>5, 'width'=>575));

				$data = array();			
				$row_counter = 0;
				$current_auth = $row['USER_LEVEL'];
			}

			$row_counter++;
			array_push($data, array('#'=>$row_counter, 'Goals / Projects / Tasks'=>$row['TOPIC'], 'Start Date'=>$row['THE_START'],
									'Estimated Date of Completion'=>$row['THE_COMP'], 'Status / Date'=>$row['THE_UPDATE'],
									'Update'=>$row['UPDATE_DESCRIPTION'], 'Last Updated By'=>$row['LAST_UPDATE_BY']));
		}

		// all this needs to be done 1 more time after the loop
		$pdf->ezNewPage();
		$pdf->ezText("<b>$title</b>", 16, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>Printed On $today</b>", 12, $center);
		$pdf->ezSetDy(-15);
		
		$pdf->ezTable($data, array('#'=>'#',
									'Goals / Projects / Tasks'=>'Goals / Projects / Tasks', 
									'Start Date'=>'Start Date', 
									'Estimated Date of Completion'=>'Estimated Date of Completion',
									'Status / Date'=>'Status / Date',
									'Update'=>'Update',
									'Last Updated By'=>'Last Updated By'), '', 
									array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'rowGap'=>5, 'colGap'=>5, 'width'=>575));
	}



   // redirect to a temporary PDF file instead of directly writing to the browser
   include("redirect_pdf.php");
*/
?>
