<?
// Modified Adam Walter, November 2006

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Security Scan Upload";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $cursor = ora_open($conn);

  $temp_table = "security_log_temp";
  $log_table = "security_log_scanner";
  $time_setup = "security_log_time_setup";

  $upload = $HTTP_GET_VARS['upload'];
  if ($upload =="Y"){
	$sql = "select * from $temp_table where upper(location_code) = 'KEY'";
   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);
	if (ora_fetch($cursor)){
		$sql = "insert into $log_table (login_id, activity_date, location_code) 
			select login_id, activity_date, location_code from $temp_table";
        	$statement = ora_parse($cursor, $sql);
        	ora_exec($cursor);

		$sql = "select count(*) from $temp_table where upper(location_code) <> 'KEY'";
		$statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
		if (ora_fetch($cursor)){
			$count = ora_getcolumn($cursor, 0);
		}

		$sql = "truncate table security_log_temp";
		$statement = ora_parse($cursor, $sql);
                ora_exec($cursor);

	}else{
		$msg = "Please Key-In Security Log First!";
	}
// this is done twice, first time for the "bad" entires, 2nd for all.
		$mailbody = "Time entries with variance greater than 5 minutes to actual:";

		$sql = "select LOGIN_ID, to_char(current_time,'mm:dd:yyyy:hh24:mi') TIME1, to_char(setup_time,'mm:dd:yyyy:hh24:mi') TIME2, to_char(current_time,'mm/dd/yyyy hh24:mi') CURRENT_TM, to_char(setup_time,'mm/dd/yyyy hh24:mi') SETUP_TM from $time_setup where is_email is null or is_email <>'Y'";
		$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$id = $row['LOGIN_ID'];

			$timearray1 = split(":", $row['TIME1']);
			$timearray2 = split(":", $row['TIME2']);
			$time1 = mktime($timearray1[3], $timearray1[4], 0, $timearray1[0], $timearray1[1], $timearray1[2]);
			$time2 = mktime($timearray2[3], $timearray2[4], 0, $timearray2[0], $timearray2[1], $timearray2[2]);
			if(abs($time1 - $time2) > 300){ // I.E. if more than a 5 minute difference
				$mailbody .= "\r\n".$id." setup time to ".$row['SETUP_TM']." @ ".$row['CURRENT_TM'];
			}
		}

		if($mailbody == "Time entries with variance greater than 5 minutes to actual:"){
			$mailbody .= "\r\nNone.";
		}

		$mailbody .= "\r\n\r\nAll recorded time entries:";

        $sql = "select login_id, to_char(current_time,'mm/dd/yyyy hh24:mi'),to_char(setup_time,'mm/dd/yyyy hh24:mi')
		from $time_setup where is_email is null or is_email <>'Y'";
		$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        while (ora_fetch($cursor)){
        	$id = ora_getcolumn($cursor, 0);
                $current_time = ora_getcolumn($cursor, 1);
                $setup_time = ora_getcolumn($cursor, 2);

		$mailbody .= "\r\n".$id." setup time to ".$setup_time." @ ".$current_time;
        }
	$sql = "update $time_setup set is_email = 'Y' where is_email is null or is_email <>'Y'";
	$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
	
	if ($mailbody <> ""){
		$mailTO = "phemphill@port.state.de.us,meskridge@port.state.de.us";
//                $mailTO = "awalter@port.state.de.us";
		$mailsubject = "Security Scanner Time Setup";
		$mailheader  = "From: " . "MailServer@port.state.de.us\r\n";
		$mailheader .= "Cc: lstewart@port.state.de.us, awalter@port.state.de.us, ithomas@port.state.de.us, skennard@port.state.de.us\r\n";
		$mailheader .= "Bcc: " . "hdadmin@port.state.de.us";
		
		mail($mailTO, $mailsubject, $mailbody, $mailheader);
        }

  }
?>

<script language="JavaScript">
function abcd()
{
    document.upload_form.upload.disabled=true;
    document.location = "http://scanner-trm-sec/security_upload/upload.php";		
}
</script>
<script language="JavaScript" src="/functions/calendar.js"></script>
<form name=upload_form>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Security Log Upload
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<p>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="upload" value=" Upload " onclick="abcd()">
        </p>
<?	if ($msg <>""){		?>
        <p>
                <font color=red><?= $msg?></font>
        </p>


<? 	}else if ($count <> ""){	?>
	<p>
		<font color=red> Total upload:<?= $count?></font> 
        </p>
<?	}	?>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
</form>
<br />

<? include("pow_footer.php"); ?>
