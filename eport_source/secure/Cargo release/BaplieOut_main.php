<?
/*
*	Adam Walter, Dec 2015
*
*	Generates an output-Baplie file
*****************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");  echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}


	
	$vessel = $HTTP_POST_VARS['vessel'];
	$date_out_baplie = $HTTP_POST_VARS['date_out_baplie'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != ""){
		if(!ereg("(^[0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4}$)", $date_out)){
			echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font>";
			$submit = "";
		}
	}

	if($submit != ""){
		$temp = explode("/", $date_out);
		$date_out_baplie = substr($temp[2], 2).$temp[0].$temp[1];

		$sql = "SELECT *
				FROM CONTAINER_ACTIVITY CA, CONTAINER_DETAILS CD
				WHERE CA.ARRIVAL_NUM = '".$vessel."'
					AND CA.FOREIGN_ID = CD.CONTAINER_DETAIL_ID
					AND SLOT IS NOT NULL
					AND SERVICE_CODE = '11'"; 
/*		$sql = "SELECT * 
				FROM CONTAINER_DETAILS_
				WHERE ARRIVAL_NUM = '".$vessel."'";*/
		$conts = ociparse($rfconn, $sql);
		ociexecute($conts);
		if(!ocifetch($conts)){
			echo "<font color=\"#FF0000\">No data found for entered info.</font>";
		} else {

			$sql = "SELECT VESSEL_NAME, LLOYD_NUM
					FROM VESSEL_PROFILE
					WHERE LR_NUM = '".$vessel."'";
			$the_ves = ociparse($rfconn, $sql);
			ociexecute($the_ves);
			ocifetch($the_ves);
			$vesname = ociresult($the_ves, "VESSEL_NAME");
			$lloyd = ociresult($the_ves, "LLOYD_NUM");

			$localpath = "./BaplieFiles/baplie".$vessel.date('mdyhis').".edi";
			$handle2 = fopen($localpath, "w");

			fwrite($handle2, "UNB+UNOA:2+USILG+TCVAL+".date('ymd:hi')."+VAPUSEC01+++++USILG'\n");
			fwrite($handle2, "UNH+".$vessel."+BAPLIE:D:95B:UN:SMDG20'\n");
			fwrite($handle2, "BGM++".$vessel."+9'\n");
			fwrite($handle2, "DTM+137:".date('ymdhi').":201'\n");
			fwrite($handle2, "TDT+20++++USILG:172:20+++".$lloyd.":146:ZZZ:".$vesname.":'\n");
			fwrite($handle2, "LOC+5+USILG:139:6'\n");
			fwrite($handle2, "LOC+61+CLVAP:139:6'\n");
			fwrite($handle2, "DTM+136:".$date_out_baplie.":101'\n");
			fwrite($handle2, "RFF+VON:".$vessel."'\n");

			$linecount_for_baplie = 8;

			do {
				if(ociresult($conts, "LOADED") == "Full"){
					$filled_status = "5";
				} elseif (ociresult($conts, "LOADED") != "Full" && ociresult($conts, "LOADED") != ""){
					$filled_status = "4";
				} else {
					$filled_status = "";
				}
				$sql = "SELECT VALUE FROM CONTAINER_ACTIVITY
						WHERE SERVICE_CODE = '1'
							AND CONTAINER_ID = '".ociresult($conts, "CONTAINER_ID")."'
						ORDER BY ACTIVITY_DATE DESC";
				$bc = ociparse($rfconn, $sql);
				ociexecute($bc);
				ocifetch($bc);
				$barcode = ociresult($bc, "VALUE");

				fwrite($handle2, "LOC+147+0".ociresult($conts, "SLOT")."::5'\n");			// DB value of storage BaRoTi  --  CONTAINER_DETAILS.SLOT
				fwrite($handle2, "FTX+AAI+++".$barcode."'\n");			// DB value of container barcode
				fwrite($handle2, "FTX+AAA+++".trim(ociresult($conts, "DESCRIPTION_OF_GOODS"))."'\n");						// DB value of whats on the container -- CONTAINER_DETAILS.DESCRIPTION_OF_GOODS 
				fwrite($handle2, "MEA+WT++KGM:".ociresult($conts, "KG")."'\n");			// DB value of weight -- CONTAINER_DETAILS.KG
				fwrite($handle2, "TMP+2+".ociresult($conts, "TEMP_CELSIUS").":CEL'\n");				// temperature to ship at value.   -- CONTAINER_DETAILS.TEMP_CELSIUS
				fwrite($handle2, "LOC+9+USILG:139:6'\n");			
				fwrite($handle2, "LOC+11+".ociresult($conts, "DISCHARGE_PORT").":139:6'\n");			// DB value, unloading destination -- CONTAINER_DETAILS.DISCHARGE_PORT
				fwrite($handle2, "RFF+BM:1'\n");  
				fwrite($handle2, "RFF+BN:".ociresult($conts, "BOOKING_NUMBER")."'\n");			// DB value, booking#
				fwrite($handle2, "EQD+CN+".ociresult($conts, "CONTAINER_ID")."+".ociresult($conts, "TYPE")."+++".$filled_status."'\n");// DB value for the "45R1" value.  last value = "4" or "5" -- CONTAINER_DETAILS.TYPE + LOADED
				fwrite($handle2, "NAD+CA+".ociresult($conts, "CARRIER").":172:20'\n");			// DB value of party responsible for the carrying of container -- CONTAINER_DETAILS.CARRIER

				$linecount_for_baplie += 11;

				$sql = "INSERT INTO CONTAINER_ACTIVITY
							(CONTAINER_ID,
							SERVICE_CODE,
							ACTIVITY_DATE,
							FOREIGN_ID,
							ARRIVAL_NUM,
							USERNAME)
						VALUES
							('".ociresult($conts, "CONTAINER_ID")."',
							'80',
							SYSDATE,
							'".ociresult($conts, "FOREIGN_ID")."',	
							'".$vessel."',
							'".$user."')";
				$export_record = ociparse($rfconn, $sql);
				ociexecute($export_record);						
			} while(ocifetch($conts));

			$linecount_for_baplie += 1;

			fwrite($handle2, "UNT+".$linecount_for_baplie."+".$vessel."'\n");
			fwrite($handle2, "UNZ+1+".$vessel."'\n");


			fclose($handle2);


			$edilink = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
								<tr>
									<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"".$localpath."\">Click Here for the Baplie File</a></b></font></td>
								</tr>
							</table><br><br>";

		}

	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Generate Baplie File 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	echo $edilink;
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="BaplieOut_index.php" method="post">
	<tr>
		<td width="15%" align="left">Vessel:</td>
		<td align="left"><input name="vessel" type="text" size="15" maxlength="15" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left">Departed Date:</td>
		<td align="left"><input name="date_out" type="text" size="15" maxlength="15" value="<? echo $date_out; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date_out');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Baplie"></td>
	</tr>
</form>
</table>