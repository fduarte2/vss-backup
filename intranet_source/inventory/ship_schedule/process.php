<?
  // All POW files need this session file included
  include("pow_session.php");
  $dest = "/web/web_pages/ship_schedule/POWShipSchedule.pdf";
   set_time_limit(120);   		// make reasonably sure the script does not time out on large files

  $source = trim($HTTP_POST_FILES['file1']['tmp_name']);	// uploaded file name
  system("/bin/cp -f $source $dest");  // Move the file to the production location
  system("/bin/chmod a+r $dest");  // Be sure it's world-readable

	$filename = "/web/web_pages/ship_schedule/POWShipSchedule.pdf";
	$basename = "POWShipSchedule.pdf";

	// deposit file.
	$connection = ftp_connect("www.portofwilmington.com");
	if($connection != FALSE){
		$login_status = ftp_login($connection, "shipschedule", "11Schedule23");
		if($login_status != FALSE){
			if(ftp_put($connection, $basename, $filename, FTP_BINARY)) {
				// all went well.
			} else {
				echo "Could not place Ship Schedule in remote FTP box.  Please contact TS\n";
				exit;
			}
		} else {
			echo "Could not log into remote FTP box.  Please contact TS\n";
			exit;
		}
	} else {
		echo "Could not connect to remote FTP box.  Please contact TS\n";
		exit;
	}

  header("Location: index.php?input=1");
  exit;
?>
