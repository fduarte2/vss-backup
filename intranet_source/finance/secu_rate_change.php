<?
/*  Adam Walter, Mar 2013.
*
*	Small screen to allow Finance to edit the Security charge for
*	IB truckloading
**************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - MiscBill Rate Edit";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }


	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Change Rate"){
		$new_rate = $HTTP_POST_VARS['new_rate'];
		$proceed = true;
		if(!is_numeric($new_rate)){
			echo "<font color=\"#FF0000\">New Rate must be a number.<br></font>";
			$proceed = false;
		}
		if($new_rate <= 0){
			echo "<font color=\"#FF0000\">New Rate must be positive.<br></font>";
			$proceed = false;
		}

		if($proceed){
			$sql = "SELECT RATE FROM LU_CHILEAN_MISCBILL_SECURITY";
			$stid = ociparse($bniconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$old_rate = ociresult($stid, "RATE");

			$sql = "UPDATE LU_CHILEAN_MISCBILL_SECURITY
					SET RATE = '".$new_rate."', LAST_MOD_BY = '".$user."'";
			$stid = ociparse($bniconn, $sql);
			ociexecute($stid);

		

			$extra_body = "\r\n\r\nUsername: ".$user."\r\nChanged on: ".date('m/d/Y h:i:s')."\r\nOld Rate: ".$old_rate."\r\nNew Rate: ".$new_rate."\r\n";

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'SECURITYRATEUPDATE'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailTO = ociresult($email, "TO");
			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}

			$mailSubject = ociresult($email, "SUBJECT");

			$body = ociresult($email, "NARRATIVE");
			$body = str_replace("_0_", $extra_body, $body);

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
							'SECURITYRATEUPDATE',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);
			}
		}
	}





	$sql = "SELECT RATE FROM LU_CHILEAN_MISCBILL_SECURITY";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$current_rate = ociresult($stid, "RATE");

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Miscbill IB-Truckload Security Rate
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="inboundtruck" action="secu_rate_change.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Current Security Charge:</font></td>
		<td><font size="2" face="Verdana"><? echo $current_rate; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">New Security Charge:</font></td>
		<td><input type="text" name="new_rate" size="6" maxlength="6" value="<? echo $current_rate; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Change Rate"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");