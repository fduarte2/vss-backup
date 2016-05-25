<?
/*
*	Adam Walter, Dec 2014.
*
*	splash page showing release status of containers.
*************************************************************************/

	$pagename = "clr_make_xls";  
	include("cargo_db_def.php");

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];

	if($submit == "Create XLS" && $vessel != ""){
		$filename = "CLR-".date('mdYhis').".xls";;
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "<font color=\"#FF000\">Could not open file.  Please contact TS.</font>";
			exit;
		}

		$output = "<table>";
		fwrite($handle, $output);

		$output = "<tr>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>CARGO ID#</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>LLOYD_NUM</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>VOYAGE_NUM</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>VESNAME</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>BOL_EQUIV</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>CONSIGNEE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>BROKER</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>CONTAINER_NUM</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>QTY</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>COMMODITY</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>PLTCOUNT</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>DESCR</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>SHIPLINE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>LOAD_TYPE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>CARGO_MODE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>MOST_RECENT_EDIT_DATE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>MOST_RECENT_EDIT_BY</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>COMMODITY_CODE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>CUSTOMER_ID</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>ARRIVAL_NUM</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>LINE_RELEASE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>AMS_RELEASE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>BROKER_RELEASE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>T_E_NUM</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>T_E_FILE</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>ORIGIN_M11</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>WEIGHT</b></font></td>
						<td align=\"center\"><font size=\"2\" face=\"Verdana\"><b>WEIGHT_UNIT</b></font></td>
					</tr>";
		fwrite($handle, $output);

		$sql = "SELECT CMD.*,
					DECODE(MOST_RECENT_EDIT_DATE, NULL, 'ON HOLD', TO_CHAR(AMS_RELEASE, 'MM/DD/YYYY HH:MI AM')) EDIT_ON,
					DECODE(AMS_RELEASE, NULL, 'ON HOLD', TO_CHAR(AMS_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
					DECODE(LINE_RELEASE, NULL, 'ON HOLD', TO_CHAR(LINE_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
					DECODE(BROKER_RELEASE, NULL, 'ON HOLD', TO_CHAR(BROKER_RELEASE, 'MM/DD/YYYY HH:MI AM')) THE_BROKER,
					NVL(TO_CHAR(GREATEST(AMS_RELEASE, LINE_RELEASE, BROKER_RELEASE), 'MM/DD/YYYY HH:MI AM'), 'ON HOLD') THE_FINAL
				FROM CLR_MAIN_DATA CMD
				WHERE ARRIVAL_NUM = '".$vessel."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){

		$output = "<tr>
						<td>".ociresult($short_term_data, "CLR_KEY")."</td>
						<td>".ociresult($short_term_data, "LLOYD_NUM")."</td>
						<td>".ociresult($short_term_data, "VOYAGE_NUM")."</td>
						<td>".ociresult($short_term_data, "VESNAME")."</td>
						<td>".ociresult($short_term_data, "BOL_EQUIV")."</td>
						<td>".ociresult($short_term_data, "CONSIGNEE")."</td>
						<td>".ociresult($short_term_data, "BROKER")."</td>
						<td>".ociresult($short_term_data, "CONTAINER_NUM")."</td>
						<td>".ociresult($short_term_data, "QTY")."</td>
						<td>".ociresult($short_term_data, "COMMODITY")."</td>
						<td>".ociresult($short_term_data, "PLTCOUNT")."</td>
						<td>".ociresult($short_term_data, "DESCR")."</td>
						<td>".ociresult($short_term_data, "SHIPLINE")."</td>
						<td>".ociresult($short_term_data, "LOAD_TYPE")."</td>
						<td>".ociresult($short_term_data, "CARGO_MODE")."</td>
						<td>".ociresult($short_term_data, "EDIT_ON")."</td>
						<td>".ociresult($short_term_data, "MOST_RECENT_EDIT_BY")."</td>
						<td>".ociresult($short_term_data, "COMMODITY_CODE")."</td>
						<td>".ociresult($short_term_data, "CUSTOMER_ID")."</td>
						<td>".ociresult($short_term_data, "ARRIVAL_NUM")."</td>
						<td>".ociresult($short_term_data, "THE_LINE")."</td>
						<td>".ociresult($short_term_data, "THE_AMS")."</td>
						<td>".ociresult($short_term_data, "THE_BROKER")."</td>
						<td>".ociresult($short_term_data, "T_E_NUM")."</td>
						<td>".ociresult($short_term_data, "T_E_FILE")."</td>
						<td>".ociresult($short_term_data, "ORIGIN_M11")."</td>
						<td>".ociresult($short_term_data, "WEIGHT")."</td>
						<td>".ociresult($short_term_data, "WEIGHT_UNIT")."</td>
					</tr>";
			fwrite($handle, $output);
		}

		$output = "</table>";
		fwrite($handle, $output);

		fclose($handle);
		echo "<font color=\"#0000FF\">File Created.  <a href=\"".$filename."\">Click Here</a> for the XLS spreadsheet.</font><br>";
	}
			

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face=\"Verdana\" color="#0066CC">Export Vessel Data</font><font size="3" face=\"Verdana\" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?> 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="clr_make_xls_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face=\"Verdana\"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td colspan="10"><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE TO_CHAR(LR_NUM) IN
					(SELECT ARRIVAL_NUM FROM CLR_MAIN_DATA)
				ORDER BY LR_NUM DESC";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $vessel){ ?> selected <? } ?>><? echo ociresult($short_term_data, "THE_VESSEL") ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create XLS"><hr></td>
	</tr>
</form>
</table>
