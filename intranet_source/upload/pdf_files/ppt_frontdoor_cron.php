<?
/*
*
*	Adam Walter, May 2014.
*
*	A cron for MArketing to "upload" a PPT file for showing in the lobby.
*
***********************************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM FRONTDOOR_POWERPOINT
			WHERE PUSHED IS NOT NULL
				AND REMOVED IS NULL";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") == 1){
		CheckIfRevertToDefault($bniconn);
	} elseif(ociresult($stid, "THE_COUNT") < 1) {
		CheckIfMoveNewPPT($bniconn);
	} else {
		// something went wrong.
		mail("awalter@port.state.de.us", "lobby PPT failure", "There is more than 1 'active' PPT file for the lobby in BNI.FRONTDOOR_POWERPOINT", "PoWErrorCheck@port.state.de.us");
	}







function CheckIfRevertToDefault($bniconn){
	// if we are in this function, we know that there is exactly 1 record where pushed is not null, and removed is null.  just need to check its end date.

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM FRONTDOOR_POWERPOINT
			WHERE PUSHED IS NOT NULL
				AND REMOVED IS NULL
				AND END_DATE < SYSDATE";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		// current file stays.  no change.
	} else {
		system("/bin/rm /web/web_pages/lobby_powerpoint/display.pptx");

		system("/bin/cp /web/web_pages/marketing/powerpoints/display.pptx /web/web_pages/lobby_powerpoint/display.pptx");

		$sql = "UPDATE FRONTDOOR_POWERPOINT
				SET REMOVED = SYSDATE
				WHERE PUSHED IS NOT NULL
					AND REMOVED IS NULL
					AND END_DATE < SYSDATE";
		$update = ociparse($bniconn, $sql);
		ociexecute($update);
	}
}

function CheckIfMoveNewPPT($bniconn){
	// there are no non-default files showing right now, see if one is waiting.  This SQL should only return 1 line max, as the upload script blocks overlapping or outdated upload files.

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM FRONTDOOR_POWERPOINT
			WHERE PUSHED IS NULL
				AND REMOVED IS NULL
				AND START_DATE < SYSDATE";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		// no pending powerpoints.  no change.
	} else {
		$sql = "SELECT FILENAME
				FROM FRONTDOOR_POWERPOINT
				WHERE PUSHED IS NULL
					AND REMOVED IS NULL
					AND START_DATE < SYSDATE";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$filename = ociresult($stid, "FILENAME");


		system("/bin/rm /web/web_pages/lobby_powerpoint/display.pptx");

		system("/bin/cp /web/web_pages/marketing/powerpoints/".$filename." /web/web_pages/lobby_powerpoint/display.pptx");

		$sql = "UPDATE FRONTDOOR_POWERPOINT
				SET PUSHED = SYSDATE
				WHERE PUSHED IS NULL
					AND REMOVED IS NULL
					AND START_DATE < SYSDATE
					AND FILENAME = '".$filename."'";
		$update = ociparse($bniconn, $sql);
		ociexecute($update);
	}
}
