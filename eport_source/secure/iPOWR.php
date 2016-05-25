<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
    //	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	if($container != ""){
		$error = "";
        $container = preg_replace('/[\s\W]+/','',trim(strtoupper($container)));
		$sql = "SELECT COUNT(*) THE_COUNT, 
					SUM(DECODE(LINE_RELEASE, NULL, 1, 0)) MISSING_LINE_RELS,
					SUM(DECODE(AMS_RELEASE, NULL, 1, 0)) MISSING_AMS_RELS,
					SUM(DECODE(BROKER_RELEASE, NULL, 1, 0)) MISSING_BRK_RELS
				FROM CLR_MAIN_DATA
				WHERE UPPER(CONTAINER_NUM) = '".$container."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			$error = "NOT IN SYSTEM";
        }elseif(ociresult($short_term_data, "MISSING_LINE_RELS") > 0 || ociresult($short_term_data, "MISSING_AMS_RELS") > 0 || ociresult($short_term_data, "MISSING_BRK_RELS") > 0){
			if(ociresult($short_term_data, "THE_COUNT") >= 2){
				$sql = "SELECT ARRIVAL_NUM, VESSEL_NAME
						FROM VESSEL_PROFILE
						WHERE ARRIVAL_NUM IN
							(SELECT ARRIVAL_NUM
							FROM CLR_MAIN_DATA
							WHERE UPPER(CONTAINER_NUM) = '".$container."'
								AND (LINE_RELEASE IS NULL OR AMS_RELEASE IS NULL OR BROKER_RELEASE IS NULL)
							)";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$error = "NOT RELEASED FOR ".ociresult($short_term_data, "ARRIVAL_NUM")."-".ociresult($short_term_data, "VESSEL_NAME")." - DO NOT OPEN";
//				$error = "ON MULTIPLE VESSELS – DO NOT OPEN";
			} else {
				$error = "ON HOLD – DO NOT OPEN";
			}
        }

		if($error != ""){
			$bgcolor="#FF0000";
			$message = $error;
		} else {
			$bgcolor="#00FF00";
			$message = "RELEASED - OK TO PROCEED";
		}
		$sql = "INSERT INTO CANADIAN_RELEASE_CHECKED
					(CONTAINER_ID,
					CHECKER,
					DEVICE,
					TIME_CHECKED,
					RESPONSE_GIVEN)
				VALUES
					('".$container."',
					'IPHONE',
					'IPHONE',
					SYSDATE,
					'".$message."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);

        ?>
        
            <html>
                <meta name="viewport" content="width=device-width">
                <head>
                    <title>iPOWR</title>
                </head>
                <body  bgcolor="<? echo $bgcolor; ?>" topmargin="0" leftmargin="0" link="#336633" vlink="#999999" alink="#999999">
                    <table width="100%" border="0" cellpadding="4" cellspacing="1">
                        <tr>
                            <p align="center">
                                <font size="3" face="Verdana" color="#F00080">
                                </font>
                                <font size="2" face="Verdana">
                                </font>
                            </p>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                            <form name="check" method="Post" action="iPOWR.php">
                                <table border="0" cellpadding="4" cellspacing="4">
                                    <tr>
                                        <td align="center">
                                            <font size="2" face="Verdana" color="#FFFFFF">CONTAINER <? echo $container; ?></font>
                                        </td>
                                     </tr>
                                    <tr>
                                        <td align="center">
                                            <font size="2" face="Verdana" color="#FFFFFF"><? echo $message; ?></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <table>
                                                <tr>
                                                    <td width="45%" align="center"><input type="Submit" name="submit" value="CONFIRM"></td>
                                                    <td width="10%">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <br /><br />
                        </tr>
                    </table>
                </body>
            </html>
        <?
    }


	if($container == ""){
        ?>
            <html>
                <meta name="viewport" content="width=device-width">
                <head>
                    <title>iPOWR</title>
                </head>
                <body  bgcolor="#4D76A5" topmargin="0" leftmargin="0" link="#336633" vlink="#999999" alink="#999999">
                    <table width="100%" border="0" cellpadding="4" cellspacing="1">
                        <tr>
                            <td>
                                <p align="center">
                                    <font size="3" face="Verdana" color="#000080"></font>
                                    <font size="2" face="Verdana"></font>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form name="check" method="Post" action="iPOWR.php">
                                    <table border="0" cellpadding="4" cellspacing="4">
                                        <tr>
                                            <td width="" align="center"><font size="3" face="Verdana" color="#FFFFFF">CONTAINER:</font></td>
                                            <td width="" align="left"><input type="textbox" name="container" size="24"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center">
                                                <table>
                                                    <tr>
                                                        <td width="60%" align="center"><input type="Submit" value="  CHECK STATUS "></td>
                                                        <td width="10%">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                <br /><br />
                            </td>
                        </tr>
                    </table>
                </body>
            </html>
        <?
	}
?>