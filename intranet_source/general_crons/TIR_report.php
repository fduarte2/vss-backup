<?
/*
*	Adam Walter, Dec 2015
*
*	Container TIR reports
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT CA.CONTAINER_ID, VP.VESSEL_NAME, CA.ARRIVAL_NUM, CP.CUSTOMER_NAME, CT.TRUCKING_COMPANY, CT.DESTINATION, CT.TRAILER_LICENSE_PLATE, CT.TRAILER_PROV_STATE, CT.CHASSIS_NUM, NVL(CT.DRIVER_EMAIL, 'NOT SIGNED') THE_EMAIL,
				CT.TEMP_SET_POINT, CT.LEFT_SIDE, CT.RIGHT_SIDE, CT.FRONT, CT.REAR, CT.REAR_OPEN, CT.SEAL_NUM, CT.INSPECTION_REMARKS, CT.DRIVER_NAME, CT.TIR_ID, TO_CHAR(TIR_DATE, 'MM/DD/YYYY HH24:MI') THE_DATE,
				CT.BOL_EQUIV || CT.SHIPLINE THE_ORDER, DECODE(CA.SERVICE_CODE, 30, 'OUT', 'IN') THE_IN_OUT
			FROM CONTAINER_ACTIVITY CA, CONTAINER_TIR CT, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP
			WHERE CA.FOREIGN_ID = CT.TIR_ID
				AND CA.ARRIVAL_NUM = VP.ARRIVAL_NUM(+)
				AND CT.CUSTOMER_ID = CP.CUSTOMER_ID(+)
				AND CA.SERVICE_CODE IN ('30', '31')
				AND CT.EMAIL_SENT IS NULL";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		// no rows, end.
	} else {
		do {
			$output = "<table>";
			$output .= "<tr>
							<td colspan=\"2\" align=\"center\"><font size=\"4\" face=\"Verdana\"><b>POW Trailer Interchange Receipt (TIR - ".ociresult($stid, "THE_IN_OUT")." ID:".ociresult($stid, "TIR_ID").")</b></font></td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Customer and Truck Details</b></font></td>
						</tr>";
			$output .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";

			$output .= "<tr>
							<td>Container</td>
							<td>".ociresult($stid, "CONTAINER_ID")."</td>
						</tr>";
			$output .= "<tr>
							<td>Vessel</td>
							<td>".ociresult($stid, "ARRIVAL_NUM")."-".ociresult($stid, "VESSEL_NAME")."</td>
						</tr>";
			$output .= "<tr>
							<td>Customer</td>
							<td>".ociresult($stid, "CUSTOMER_NAME")."</td>
						</tr>";
			$output .= "<tr>
							<td>Order#</td>
							<td>".ociresult($stid, "THE_ORDER")."</td>
						</tr>";
			$output .= "<tr>
							<td>Trucking Company</td>
							<td>".ociresult($stid, "TRUCKING_COMPANY")."</td>
						</tr>";
			$output .= "<tr>
							<td>Destination</td>
							<td>".ociresult($stid, "DESTINATION")."</td>
						</tr>";
			$output .= "<tr>
							<td>Chassis License Plate</td>
							<td>".ociresult($stid, "TRAILER_LICENSE_PLATE")."-".ociresult($stid, "TRAILER_PROV_STATE")."</td>
						</tr>";
			$output .= "<tr>
							<td>Chassis #</td>
							<td>".ociresult($stid, "CHASSIS_NUM")."</td>
						</tr>";
			$output .= "<tr>
							<td>Temperature Set Point (°C)</td>
							<td>".ociresult($stid, "TEMP_SET_POINT")."</td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Inpection Report</b></font></td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\"><font size=\"3\" face=\"Verdana\"><b>Checker:</b>  Inspect each side of the container. Use words such as CUT, DENT, HOLE, PATCH, SCRAPE, BROKEN, MISSING, etc. to describe damages.</font></td>
						</tr>";
			$output .= "<tr>
							<td>Left</td>
							<td>".ociresult($stid, "LEFT_SIDE")."</td>
						</tr>";
			$output .= "<tr>
							<td>Front</td>
							<td>".ociresult($stid, "FRONT")."</td>
						</tr>";
			$output .= "<tr>
							<td>Right</td>
							<td>".ociresult($stid, "RIGHT_SIDE")."</td>
						</tr>";
			$output .= "<tr>
							<td>Rear</td>
							<td>".ociresult($stid, "REAR")."</td>
						</tr>";
			$output .= "<tr>
							<td>Rear w/ Open Doors</td>
							<td>".ociresult($stid, "REAR_OPEN")."</td>
						</tr>";
			$output .= "<tr>
							<td>Seal #</td>
							<td>".ociresult($stid, "SEAL_NUM")."</td>
						</tr>";
			$output .= "<tr>
							<td>Inspection Remarks</td>
							<td>".ociresult($stid, "INSPECTION_REMARKS")."</td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Driver</b></font></td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\"><font size=\"3\" face=\"Verdana\"><b>Checker:</b>  Hand Tablet to Driver.</font></td>
						</tr>";
			$output .= "<tr>
							<td colspan=\"2\"><font size=\"3\" face=\"Verdana\"><b>Driver:</b>  Verify accuracy of above information, then enter your name and email address and press Submit. A copy of this receipt will be sent to you via email.</font></td>
						</tr>";
			$output .= "<tr>
							<td>Driver</td>
							<td>".ociresult($stid, "DRIVER_NAME")."</td>
						</tr>";
			$output .= "<tr>
							<td>Driver Email or Initials</td>
							<td>".ociresult($stid, "THE_EMAIL")."</td>
						</tr>";
			$output .= "<tr>
							<td>Accepted Date&Time</td>
							<td>".ociresult($stid, "THE_DATE")."</td>
						</tr>";
			$output .= "</table>";

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'CONTAINERTIROUT'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "awalter@port.state.de.us";
				$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
			} else {
				$mailTO = ociresult($email, "TO");
				if(ociresult($email, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
				}
				if(ociresult($email, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
				}
			}
			if(strpos(ociresult($stid, "DRIVER_EMAIL"), "@") === false){
				$replace = "";
			} else {
				$replace = ",".ociresult($stid, "DRIVER_EMAIL");
			}
			$mailTO = str_replace("_1_", $replace, $mailTO);
			$mailheaders .= "Content-Type: text/html\r\n";

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", ociresult($stid, "THE_IN_OUT"), $mailSubject);
			$mailSubject = str_replace("_2_", ociresult($stid, "CONTAINER_ID"), $mailSubject);
			$mailSubject = str_replace("_4_", ociresult($stid, "TIR_ID"), $mailSubject);

			$body = ociresult($email, "NARRATIVE");
			$body = str_replace("_3_", $output, $body);

			if(mail($mailTO, $mailSubject, $body, $mailheaders)){
				$sql = "INSERT INTO JOB_QUEUE
							(JOB_ID,
							SUBMITTER_ID,
							SUBMISSION_DATETIME,
							JOB_TYPE,
							JOB_DESCRIPTION,
							DATE_JOB_COMPLETED,
							COMPLETION_STATUS,
							JOB_EMAIL_TO,
							JOB_EMAIL_CC,
							JOB_EMAIL_BCC,
							JOB_BODY)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'INSTANTCRON',
							SYSDATE,
							'EMAIL',
							'CONTAINERTIROUT',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);

				$sql = "UPDATE CONTAINER_TIR
						SET EMAIL_SENT = SYSDATE
						WHERE TIR_ID = '".ociresult($stid, "TIR_ID")."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			}
		} while(ocifetch($stid));
	}