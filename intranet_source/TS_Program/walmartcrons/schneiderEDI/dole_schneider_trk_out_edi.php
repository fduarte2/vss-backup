<?
/*
*		Adam Walter, Jan 2011
*
*		Adding to the list of programs that it's imperative that I program immediately
*		For the 2% chance it will ever get used, here is a Truck Notification
*		EDI script for Walmart (and only 1 specific carrier for them at that
**************************************************************************************/

//	$type = "PROD";
	$type = "TEST";

	if($type == "TEST"){
		$ISA_flag = "T";
		$ISA_GS_ID = "TPOWDE         ";
		$ISA_GS_QUAL = "SLTDT";
		$filename_append = "TEST";

//		$conn = ora_logon("SAG_OWNER@BNI", "SAG");
		$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
		if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			exit;
		}
	
	} else {
		$ISA_flag = "P";
		$ISA_GS_ID = "POWDE          ";
		$ISA_GS_QUAL = "SLTDP";
		$filename_append = "PROD";

		$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//		$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
		if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			exit;
		}
	
	}
	$main_cursor = ora_open($conn2);
	$update_cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$sql = "SELECT * FROM JOB_QUEUE WHERE EMAILID = 'WMSCHTRKOEDI' AND STATUS = 'PENDING'";
	$ora_success = ora_parse($main_cursor, $sql);
	$ora_success = ora_exec($main_cursor, $sql);
	if(!ora_fetch_into($main_cursor, $main_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// end of script
	} else {
		do {
			// step 1:  make the file
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WMSCHTRKOEDI'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$date_6 = date('ymd');
			$time_4 = date('hi');
			$date_8 = date('Ymd');

			$ftp_addr = $short_term_row['TO'];
			$ftp_login = $short_term_row['FROM'];
			$ftp_pass = $short_term_row['CC'];
			$subj = $short_term_row['SUBJECT'];
			$narr = $short_term_row['NARRATIVE'];

			$temp = $main_row['VARIABLE_LIST'];
			$vars = split(";", $temp);

			$bol = $vars[0];
			$CN = $vars[1];
			$seal1 = $vars[2];
			$seal2 = $vars[3];
			$seal3 = $vars[4];
			$seal4 = $vars[5];

			$ST_segment_lines = 0;

			$filename = "BOL".$bol."on".date('mdyhi').$filename_append."txt";
			$fp = fopen($filename, "w");

			$sql = "SELECT WALMART_ISA_SEQ.NEXVAL THE_ISA FROM DUAL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$ISA_num = $short_term_row['THE_ISA'];

			$sql = "SELECT WALMART_GS_SEQ.NEXVAL THE_GS FROM DUAL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$GS_num = $short_term_row['THE_GS'];

			$sql = "SELECT WALMART_ST_SEQ.NEXVAL THE_ST FROM DUAL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$ST_num = $short_term_row['THE_ST'];

			fwrite($fp, "ISA*00*          *00*          *12*".$ISA_GS_ID."*ZZ*".$ISA_GS_QUAL."*".$date_6."*".$time_4."*^*00403*".$ISA_num."*0*".$ISA_flag."*>~");
			fwrite($fp, "GS*QM*".trim($ISA_GS_ID)."*".$ISA_GS_QUAL."*".$date_8."*".$time_4."*".$GS_num."*X*004030~");

			fwrite($fp, "ST*214*".$ST_num."~");
			$ST_segment_lines++;

			fwrite($fp, "B10***POW1~");
			$ST_segment_lines++;



			fwrite($fp, "N1*WH*DIAMOND STATE PORT CORPORATION*12*DC7861~");
			$ST_segment_lines++;




			fwrite($fp, "N4*WILMINGTON*DE*19801~");
			$ST_segment_lines++;

			$ST_segment_lines++; // for the upcoming SE line
			fwrite($fp, "SE*".$ST_segment_lines."*".$ST_num."~");
			fwrite($fp, "GE*1*".$GS_num."~");
			fwrite($fp, "IEA*1*".$ISA_num."~");










			fclose($fp);

			// step 2:  try and put it to the box, and move file (successor not)
			// step 3:  update DB (failed or not)

			// deposit file... hopefully.
			$connection = ftp_connect($ftp_addr);
			if($connection != FALSE){
				$login_status = ftp_login($connection, $ftp_login, $ftp_pass);
				if($login_status != FALSE){
					if(ftp_put($connection, $filename, $filename, FTP_BINARY)){ 
						// all things are go.
						
						$sql = "UPDATE JOB_QUEUE
								SET STATUS = 'COMPLETE',
								DATE_JOB_COMPLETED = SYSDATE
								WHERE JOB_ID = '".$main_row['JOB_ID']."'";
						ora_parse($update_cursor, $sql);
						ora_exec($update_cursor);
						system("/bin/mv $filename success/".$filename);
					} else { // ftp didn't work.  move file, do nothing.
						$sql = "UPDATE JOB_QUEUE
								SET STATUS = 'FAILED',
								COMPLETION_NOTES = 'could not ftp_put file',
								DATE_JOB_COMPLETED = SYSDATE
								WHERE JOB_ID = '".$main_row['JOB_ID']."'";
						ora_parse($update_cursor, $sql);
						ora_exec($update_cursor);

						system("/bin/mv $filename failed/".$filename);
					}
				} else {
					$sql = "UPDATE JOB_QUEUE
							SET STATUS = 'FAILED',
							COMPLETION_NOTES = 'could not log in',
							DATE_JOB_COMPLETED = SYSDATE
							WHERE JOB_ID = '".$main_row['JOB_ID']."'";
					ora_parse($update_cursor, $sql);
					ora_exec($update_cursor);

//					echo "could not log in\n";
					system("/bin/mv $filename failed/".$filename);
				}
			} else {
				$sql = "UPDATE JOB_QUEUE
						SET STATUS = 'FAILED',
						COMPLETION_NOTES = 'could not connect',
						DATE_JOB_COMPLETED = SYSDATE
						WHERE JOB_ID = '".$main_row['JOB_ID']."'";
				ora_parse($update_cursor, $sql);
				ora_exec($update_cursor);

//				echo "could not connect\n";
				system("/bin/mv $filename failed/".$filename);
			}
		} while(ora_fetch_into($main_cursor, $main_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	} 