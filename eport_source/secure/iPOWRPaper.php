<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");  echo "TEST DB";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	if($container != ""){
		$display = "";
		$message = "All Status 6 or higher.";
		$bgcolor = "#00FF00";
        $container = preg_replace('/[\s\W]+/','',trim(strtoupper($container)));
		$sql = "SELECT * FROM (
					SELECT BO.ORDER_NUM THE_ORDER, BO.STATUS THE_STATUS, DS.ST_DESCRIPTION THE_STAT_DESC, NVL(TO_CHAR(VFB.DATE_DEPARTED, 'MM/DD/YYYY'), 'Not Departed') THE_DEP_DATE, 
						'B' THE_SYSTEM, VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VES, SUM(QTY_TO_SHIP) THE_QTY
					FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD, DOLEPAPER_STATUSES DS, VOYAGE_FROM_BNI VFB, VESSEL_PROFILE VP
					WHERE BO.CONTAINER_ID = '".$container."'
						AND BO.ORDER_NUM = BOD.ORDER_NUM
						AND BO.STATUS = DS.STATUS
						AND BO.ARRIVAL_NUM = TO_CHAR(VFB.LR_NUM)
						AND VFB.LR_NUM = VP.LR_NUM
					GROUP BY BO.ORDER_NUM, BO.STATUS, DS.ST_DESCRIPTION, NVL(TO_CHAR(VFB.DATE_DEPARTED, 'MM/DD/YYYY'), 'Not Departed'), 'B', VP.LR_NUM || '-' || VP.VESSEL_NAME
					UNION
					SELECT TO_CHAR(DO.ORDER_NUM) THE_ORDER, DO.STATUS THE_STATUS, DS.ST_DESCRIPTION THE_STAT_DESC, NVL(TO_CHAR(VFB.DATE_DEPARTED, 'MM/DD/YYYY'), 'Not Departed') THE_DEP_DATE, 
						'D' THE_SYSTEM, VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VES, SUM(QTY_SHIP) THE_QTY
					FROM DOLEPAPER_ORDER DO, DOLEPAPER_DOCKTICKET DD, DOLEPAPER_STATUSES DS, VOYAGE_FROM_BNI VFB, VESSEL_PROFILE VP
					WHERE DO.CONTAINER_ID = '".$container."'
						AND DO.ORDER_NUM = DD.ORDER_NUM
						AND DO.STATUS = DS.STATUS
						AND DO.ARRIVAL_NUM = TO_CHAR(VFB.LR_NUM)
						AND VFB.LR_NUM = VP.LR_NUM
					GROUP BY DO.ORDER_NUM, DO.STATUS, DS.ST_DESCRIPTION, NVL(TO_CHAR(VFB.DATE_DEPARTED, 'MM/DD/YYYY'), 'Not Departed'), 'D', VP.LR_NUM || '-' || VP.VESSEL_NAME
				)
				ORDER BY THE_VES DESC, THE_ORDER DESC";
		$orders = ociparse($rfconn, $sql);
		ociexecute($orders);
		if(!ocifetch($orders)){
			$display = "No orders found for this Container";
			$message = "Not In System.";
			$bgcolor="#FF0000";
		} else {
			$display .= "<table border =\"1\" cellpadding=\"4\">
							<tr>
								<td><b>Depart Date</b></td>
								<td><b>Vessel</b></td>
								<td><b>Order</b></td>
								<td><b>Status</b></td>
								<td><b>QTY Expected</b></td>
								<td><b>QTY Shipped</b></td>
								<td><b>Sys</b></td>
							</tr>";
			do {
				$temp = explode("-", ociresult($orders, "THE_VES"));
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
						WHERE CA.SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
							AND ORDER_NUM = '".ociresult($orders, "THE_ORDER")."'
							AND CA.PALLET_ID = CT.PALLET_ID
							AND CA.CUSTOMER_ID = CT.RECEIVER_ID
							AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
							AND CT.COMMODITY_CODE LIKE '12%'";
//				echo $sql;
				$pallets = ociparse($rfconn, $sql);
				ociexecute($pallets);
				ocifetch($pallets);

				$display .= "<tr>
								<td>".ociresult($orders, "THE_DEP_DATE")."</td>
								<td>".ociresult($orders, "THE_VES")."</td>
								<td>".ociresult($orders, "THE_ORDER")."</td>
								<td>".ociresult($orders, "THE_STATUS")."-".ociresult($orders, "THE_STAT_DESC")."</td>
								<td>".ociresult($orders, "THE_QTY")."</td>
								<td>".ociresult($pallets, "THE_COUNT")."</td>
								<td>".ociresult($orders, "THE_SYSTEM")."</td>
							</tr>";
				if(ociresult($orders, "THE_STATUS") < 6){
					$bgcolor="#FF0000";
					$message = "Not Status 6 or higher.";
				}
			} while(ocifetch($orders));

			$display .= "</table>";

		}


?>
            <html>
                <meta name="viewport" content="width=device-width">
                <head>
                    <title>iPOWRPaper</title>
                </head>
                <body  bgcolor="<? echo $bgcolor; ?>" topmargin="0" leftmargin="0" link="#336633" vlink="#999999" alink="#999999">
                    <table width="100%" border="0" cellpadding="4" cellspacing="1">
                        <tr>
                            <p align="center">
                                <font size="3" face="Verdana">
                                </font>
                                <font size="2" face="Verdana">
                                </font>
                            </p>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                            <form name="check" method="Post" action="iPOWRPaper.php">
                                <table border="0" cellpadding="4" cellspacing="4">
                                    <tr>
                                        <td align="center">
                                            <font size="2" face="Verdana"><b>PAPER<br>CONTAINER <? echo $container; ?></b></font>
                                        </td>
                                     </tr>
                                    <tr>
                                        <td align="center">
                                            <font size="2" face="Verdana"><b><? echo $display; ?></b></font>
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

		$sql = "INSERT INTO CANADIAN_RELEASE_CHECKED
					(CONTAINER_ID,
					CHECKER,
					DEVICE,
					TIME_CHECKED,
					RESPONSE_GIVEN)
				VALUES
					('".$container."',
					'IPHONE',
					'PAPERPHONE',
					SYSDATE,
					'".$message."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);
    }


	if($container == ""){
        ?>
            <html>
                <meta name="viewport" content="width=device-width">
                <head>
                    <title>iPOWRPaper</title>
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
                                <form name="check" method="Post" action="iPOWRPaper.php">
                                    <table border="0" cellpadding="4" cellspacing="4">
                                        <tr>
                                            <td width="" align="center"><font size="3" face="Verdana" color="#FFFFFF"><b>PAPER<br>CONTAINER:</b></font></td>
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
