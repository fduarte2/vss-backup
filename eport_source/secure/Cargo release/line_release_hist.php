<?
/*
*		Adam Walter, July 2014.
*
*		Line Release and history view.
*********************************************************************************/


  
	$pagename = "line_release";  
	include("cargo_db_def.php");
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
	if($submit == "Set Line Release Time and Save Comments"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
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
				SET LINE_RELEASE = SYSDATE
				WHERE CLR_KEY = '".$key."'
					AND LINE_RELEASE IS NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
		$sql = "INSERT INTO CLR_LINE_RELEASE
					(USERNAME,
					DATE_TIME,
					COMMENTS,
					ACTION_TYPE,
					CLR_KEY)
				VALUES
					('".$user."',
					SYSDATE,
					'".$comments."',
					'RELEASE SET (Manual)',
					'".$key."')";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);
	} elseif($submit == "Remove Line Release"){
//		$vessel = $HTTP_POST_VARS['vessel'];
//		$bol = $HTTP_POST_VARS['bol'];
//		$cust = $HTTP_POST_VARS['cust'];
		$key = $HTTP_POST_VARS['key'];
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
				SET LINE_RELEASE = NULL
				WHERE CLR_KEY = '".$key."'
					AND LINE_RELEASE IS NOT NULL";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);

//					ARRIVAL_NUM,
//					CUSTOMER_ID,
//					BOL_EQUIV)
//					'".$vessel."',
//					'".$cust."',
//					'".$bol."')";
		$sql = "INSERT INTO CLR_LINE_RELEASE
					(USERNAME,
					DATE_TIME,
					COMMENTS,
					ACTION_TYPE,
					CLR_KEY)
				VALUES
					('".$user."',
					SYSDATE,
					'".$comments."',
					'RELEASE REMOVED (Manual)',
					'".$key."')";
		$upd_data = ociparse($rfconn, $sql);
		ociexecute($upd_data);
	}

//			WHERE ARRIVAL_NUM = '".$vessel."'
//				AND CUSTOMER_ID = '".$cust."'
//				AND BOL_EQUIV = '".$bol."'";
	$sql = "SELECT NVL(TO_CHAR(LINE_RELEASE, 'MM/DD/YYYY HH24:MI:SS'), 'NOT RELEASED') THE_REL,
				ARRIVAL_NUM, BOL_EQUIV, CUSTOMER_ID, CONTAINER_NUM
			FROM CLR_MAIN_DATA
			WHERE CLR_KEY = '".$key."'";
	$rel_data = ociparse($rfconn, $sql);
	ociexecute($rel_data);
	ocifetch($rel_data);
	$release = ociresult($rel_data, "THE_REL");
	$cust = ociresult($rel_data, "CUSTOMER_ID");
	$vessel = ociresult($rel_data, "ARRIVAL_NUM");
	$bol = ociresult($rel_data, "BOL_EQUIV");
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
	    <font size="5" face="Verdana" color="#0066CC">Line-Release And History
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
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
<form name="ID_select" action="line_release_hist_index.php" method="post">
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
		<td align="left"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left"><input type="text" name="comments" size="50" maxlength="50"></td>
	</tr>		
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Set Line Release Time and Save Comments">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Remove Line Release"><BR></td>
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
<!--		<td><font size="2" face="Verdana"><b>Line EDI Date/Time</b></font> !-->
		<td><font size="2" face="Verdana"><b>Date/Time</b></font>
		<td><font size="2" face="Verdana"><b>Comments</b></font>
<!--		<td><font size="2" face="Verdana"><b>Type</b></font> !-->
		<td><font size="2" face="Verdana"><b>Description of Action</b></font>
	</tr>
<?
//			WHERE ARRIVAL_NUM = '".$vessel."'
//				AND CUSTOMER_ID = '".$cust."'
//				AND BOL_EQUIV = '".$bol."'
	$sql = "SELECT CLR.*, TO_CHAR(DATE_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_DATE 
			FROM CLR_LINE_RELEASE CLR
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
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "THE_DATE"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "COMMENTS"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "ACTION_TYPE"); ?></font>
	</tr>
<?
		} while(ocifetch($hist_data));
	}
?>
</table>
