<?
/*
*	Adam Walter, August, 2008.
*
*	This script is the Cron job that handles mailing of the
*	Pipescanner Security reports.
*
*************************************************************************/

//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
	$cmd = "echo \"Pipescan upload failed\" -s \"Pipescan upload failed\" awalter@port.state.de.us";
	system($cmd);
	exit;
  }
  $cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

//  $recipient_list = "gbailey@port.state.de.us skennard@port.state.de.us ithomas@port.state.de.us fvignuli@port.state.de.us -c meskridge@port.state.de.us -c jcustis@port.state.de.us -c lstewart@port.state.de.us -c awalter@port.state.de.us";
//  $recipient_list = "ghernandez@port.state.de.us  -c jcustis@port.state.de.us -c skennard@port.state.de.us -c sadu@port.state.de.us  -c ithomas@port.state.de.us ";
  $recipient_list = "ghernandez@port.state.de.us  -c jcustis@port.state.de.us -c skennard@port.state.de.us -c sadu@port.state.de.us  -c archive@port.state.de.us ";
//	$recipient_list = "awalter@port.state.de.us";

	$day_of_week = date("w");
	if($day_of_week != 0 && $day_of_week != 6){  // this isn't a weekend...
		$the_date = date("m/d/Y");
		$sql = "SELECT TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY HH24:MI') THE_UPLOAD, DETAIL_FILE_NAME, EXCEPTION_FILE_NAME, UPLOADED_BY, COMMENTS, SKIP_REASONS FROM PIPESCAN_LOG WHERE TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY') = '".$the_date."' ORDER BY UPLOAD_TIME DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$cmd = "echo \"File uploaded by ".$row['UPLOADED_BY']." at ".$row['THE_UPLOAD']."\r\n\r\nUpload Comments:\r\n".$row['COMMENTS']."\r\n\r\nSkip Reasons:\r\n".$row['SKIP_COMMENTS'];
			$cmd .= " \" | mutt -s \"Pipescan Security File Report\" -a ~/hr/pipescan_report/".$row['DETAIL_FILE_NAME']." -a ~/hr/pipescan_report/".$row['EXCEPTION_FILE_NAME']." ";
//			$cmd .= " \" | mutt -s \"Pipescan Security File Report\" -a ~/TS_Testing/".$row['DETAIL_FILE_NAME']." -a ~/TS_Testing/".$row['EXCEPTION_FILE_NAME']." ";
			$cmd .= $recipient_list;

		} else {
			$cmd = "echo \"NO FILE UPLOADED for date ".date('m/d/Y')."\" | mutt -s \"Pipescan Report:  NOT UPLOADED\" ";
			$cmd .= $recipient_list;

		}

		system($cmd);
	}
