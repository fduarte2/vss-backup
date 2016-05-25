<?
/*
*		Adam Walter, July 2014.
*
*		Broker Release, T&E, and history view.
*********************************************************************************/


  
	$pagename = "broker_release";  
	include("cargo_db_def.php");
	include("eport_functions.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$key = $HTTP_GET_VARS['key'];
//	$vessel = $HTTP_GET_VARS['vessel'];
//	$bol = $HTTP_GET_VARS['bol'];
//	$cust = $HTTP_GET_VARS['cust'];
//	$cont = $HTTP_GET_VARS['cont'];
//	$order = $HTTP_GET_VARS['order'];


	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Set Broker Release Time and Save Comments"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
		$noTE = $HTTP_POST_VARS['noTE'];
		$TE_num = $HTTP_POST_VARS['TE_num'];
		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\\", "", $comments);

		$save_flag = true;

		if(($HTTP_POST_FILES['import_file']['name'] == "" || $TE_num == "") && $noTE == "") {
			echo "<font color=\"#FF0000\">Either a T&E must be uploaded, or the \"No T&E Needed\" box must be checked.</font>";
//			echo "TE#: ".$TE_num."   filename: ".$HTTP_POST_FILES['import_file']['name']."    noTE: ".$noTE."<br>";
			$save_flag = false;
		}

		if($HTTP_POST_FILES['import_file']['name'] == ""){
			// no file, do nothing
		} elseif(substr($HTTP_POST_FILES['import_file']['name'], -3) != "pdf"){
			echo "<font color=\"#FF0000\">Uploaded T&E file Not accepted; must be in PDF format.</font>";
			$save_flag = false;
		} else {
			$impfilename = date(mdYhis)."-".$TE_num."-".AlphaNumericAndTick($HTTP_POST_FILES['import_file']['name']);
			$target_path_import = "./upload_clr_t_e/".$impfilename;
//			$target_path_import = "./".$impfilename;

			if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
				system("/bin/chmod a+r $target_path_import");
			} else {
				echo "Error on file upload.  Please contact Port of Wilmington";
				$save_flag = false;
			}
		}

		if($comments == ""){
			$comments = "None";
		}

		if($noTE == "no"){
			$TE_num = "NO";
			$impfilename = "NO";
		} else {
//			$more_set = " T_E_NUM = '".$TE_num."', T_E_FILE = '".$impfilename."'";
		}
		$more_set = " T_E_NUM = '".$TE_num."', T_E_FILE = '".$impfilename."'";

		if($save_flag){
//				WHERE ARRIVAL_NUM = '".$vessel."'
//					AND CUSTOMER_ID = '".$cust."'
//					AND BOL_EQUIV = '".$bol."'
			$sql = "UPDATE CLR_MAIN_DATA
					SET BROKER_RELEASE = SYSDATE, ".$more_set."
					WHERE CLR_KEY = '".$key."'
						AND BROKER_RELEASE IS NULL";
			$upd_data = ociparse($rfconn, $sql);
			ociexecute($upd_data);

//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
			$sql = "INSERT INTO CLR_BROKER_RELEASE
						(USERNAME,
						DATE_TIME,
						COMMENTS,
						T_E_NUM,
						T_E_FILENAME,
						ACTION_TYPE,
						CLR_KEY)
					VALUES
						('".$user."',
						SYSDATE,
						'".$comments."',
						'".$TE_num."',
						'".$impfilename."',
						'RELEASE SET (Manual)',
						'".$key."')";
			$upd_data = ociparse($rfconn, $sql);
			ociexecute($upd_data);
		}

	} elseif($submit == "Remove Broker Release"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
//		$noTE = $HTTP_POST_VARS['noTE'];
//		$TE_num = $HTTP_POST_VARS['TE_num'];
		$comments = trim($HTTP_POST_VARS['comments']);
		$comments = str_replace("'", "`", $comments);
		$comments = str_replace("\\", "", $comments);

		if($comments == ""){
			$comments = "None";
		}

//				WHERE ARRIVAL_NUM = '".$vessel."'
//					AND CUSTOMER_ID = '".$cust."'
//					AND BOL_EQUIV = '".$bol."'
		$sql = "UPDATE CLR_MAIN_DATA
				SET BROKER_RELEASE = NULL, T_E_NUM = NULL, T_E_FILE = NULL
				WHERE CLR_KEY = '".$key."'
					AND BROKER_RELEASE IS NOT NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
		$sql = "INSERT INTO CLR_BROKER_RELEASE
					(USERNAME,
					DATE_TIME,
					COMMENTS,
					T_E_NUM,
					T_E_FILENAME,
					ACTION_TYPE,
					CLR_KEY)
				VALUES
					('".$user."',
					SYSDATE,
					'".$comments."',
					'',
					'',
					'RELEASE REMOVED (Manual)',
					'".$key."')";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);
	}

	$sql = "SELECT T_E_NUM, T_E_FILE
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CUSTOMER_ID = '".$cust."'
				AND BOL_EQUIV = '".$bol."'
				AND BROKER_RELEASE IS NOT NULL";
	$line_data = ociparse($rfconn, $sql);
	ociexecute($line_data);
	if(!ocifetch($line_data)){
		$disp_filename = "";
	} elseif(ociresult($line_data, "T_E_NUM") == "NO") {
		$disp_filename = "NO";
	} else {
		$disp_filename = "<a href=\"./upload_clr_t_e/".ociresult($line_data, "T_E_FILE")."\">".ociresult($line_data, "T_E_NUM")."</a>";
	}

//			WHERE ARRIVAL_NUM = '".$vessel."'
//				AND CUSTOMER_ID = '".$cust."'
//				AND BOL_EQUIV = '".$bol."'";
	$sql = "SELECT NVL(TO_CHAR(BROKER_RELEASE, 'MM/DD/YYYY HH24:MI:SS'), 'NOT RELEASED') THE_REL,
				ARRIVAL_NUM, BOL_EQUIV, CUSTOMER_ID, BROKER, CONTAINER_NUM
			FROM CLR_MAIN_DATA
			WHERE CLR_KEY = '".$key."'";
	$rel_data = ociparse($rfconn, $sql);
	ociexecute($rel_data);
	ocifetch($rel_data);
	$release = ociresult($rel_data, "THE_REL");
	$cust = ociresult($rel_data, "CUSTOMER_ID");
	$vessel = ociresult($rel_data, "ARRIVAL_NUM");
	$bol = ociresult($rel_data, "BOL_EQUIV");
	$broker = ociresult($rel_data, "BROKER");
	$cont = ociresult($rel_data, "CONTAINER_NUM");

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	if(!ocifetch($ves_data)){
		$vesname = $vessel." - Unknown";
	} else {
		$vesname = ociresult($ves_data, "VESSEL_NAME");
	}

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Broker-Release And History
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Broker:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $broker; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Consignee:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cust; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>BoL:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Container:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $cont; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Cargo ID:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $key; ?></font></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="ID_select" action="broker_release_hist_index.php" method="post">
<!--<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>"> !-->
<input type="hidden" name="key" value="<? echo $key; ?>">
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Current Release Time:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $release; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="checkbox" name="noTE" value="no">&nbsp;<font size="2" face="Verdana"><b>No T&E File Needed</b></font></td>
	</tr>		
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Current T&E:</b></font></td>
		<td align="left"><? echo $disp_filename; ?></td>
	</tr>		
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>New T&E #:</b></font></td>
		<td align="left"><input type="text" name="TE_num" size="20" maxlength="20"></td>
	</tr>		
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>New T&E File:</b></font></td>
		<td align="left"><input type="file" name="import_file"></td>
	</tr>		
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left"><input type="text" name="comments" size="50" maxlength="50"></td>
	</tr>		
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Set Broker Release Time and Save Comments">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Remove Broker Release"><BR></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>UserName</b></font>
		<td><font size="2" face="Verdana"><b>T&E #</b></font> 
		<td><font size="2" face="Verdana"><b>T&E Filename</b></font> 
		<td><font size="2" face="Verdana"><b>Date/Time</b></font>
		<td><font size="2" face="Verdana"><b>Comments</b></font>
		<td><font size="2" face="Verdana"><b>Description of Action</b></font>
	</tr>
<?
	$sql = "SELECT CLR.*, TO_CHAR(DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_DATE 
			FROM CLR_BROKER_RELEASE CLR
			WHERE CLR_KEY = '".$key."'
			ORDER BY DATE_TIME";
	$hist_data = ociparse($rfconn, $sql);
	ociexecute($hist_data);
	if(!ocifetch($hist_data)){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana"><b>No Release Activity Found.</b></font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "USERNAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "T_E_NUM"); ?></font>
		<td><font size="2" face="Verdana"><a href="./upload_clr_t_e/<? echo ociresult($hist_data, "T_E_FILENAME"); ?>"><? echo ociresult($hist_data, "T_E_FILENAME"); ?></a></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "THE_DATE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "COMMENTS"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "ACTION_TYPE"); ?></font>
	</tr>
<?
		} while(ocifetch($hist_data));
	}
?>
</table>
