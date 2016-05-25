            <tr>
               <td width="2%">&nbsp;</td>
	       <td>
		<table width="100%">
	       <tr>
               <td>&nbsp;</td>
		<td align="right"><input type="submit" name="save" value="  Save Agenda ">&nbsp;&nbsp; &nbsp; &nbsp; 
			<input type="submit" name="to_print" value = "   Print Agenda  "></td>
		</tr>
		</table>
                </td>
               <td width="1%">&nbsp;</td>
               <input type="hidden" name="id" value="<?php echo $id ?>">
            </tr>
	     <tr>
               <td>&nbsp;</td>
               <td align="center">

	   
		  <table border="1" cellpadding="0" cellspacing="0">

		     <tr bgcolor="#4D76A5"> 
			<td align="center"><b><font size="2" face="Verdana">Daily Hires</font></b></td>
                        <td align="center"><b><font size="2" face="Verdana">Jobs Filled</font></b></td>
                        <td align="center"><b><font size="2" face="Verdana">Vehicles Searched</font></b></td>
                        <td align="center"><b><font size="2" face="Verdana">New Hires</font></b></td>
                        <td align="center"><b><font size="2" face="Verdana">Resignations</font></b></td>
			<td align="center"><b><font size="2" face="Verdana">Newspaper Ads Run</font></b></td>
                     </tr>
			<?php
			      	$data = "meeting_detail";
			      	$query = "SELECT *  FROM $data where meeting_id=$mId";

                              	$result = pg_query($connection, $query) or
                              		die("Error in query: $query. " .  pg_last_error($connection));

                              	// get the number of rows in the resultset
                              	$rows = pg_num_rows($result);
                              

                              	// if records present
				if ($rows > 0){
					$row = pg_fetch_row($result, 0);
					$daily_hires = $row[1];
					$jobs_filled = $row[2];
					$veh_searched = $row[3];
					$new_hires = $row[4];
					$resignations = $row[5];
					$ads_run = $row[6];
					$accidents = $row[7];
					$drug_test = $row[8];
					$bids = $row[9];
					$low_bid = $row[10];
					$accepted = $row[11];
					$rejected = $row[12]; 
					$num_of_scans = $row[13];
					$num_of_checkers = $row[14];
					$network_uptime = $row[15];
					$crane_downtime = $row[16];
					$ave_truck_times = $row[17];
					$ave_num_of_trucks = $row[18];
					$accident_desc = $row[19];
				}
			?>
                     <tr>
			<td> <input type="text" name="daily_hires" size="15" value="<?php echo $daily_hires ?>" <?php echo $hr_disabled ?>></td>
			<td> <input type="text" name="jobs_filled" size="15" value="<?php echo $jobs_filled ?>" <?php echo $hr_disabled ?>></td>
                        <td> <input type="text" name="veh_searched" size="15" value="<?php echo $veh_searched ?>" <?php echo $hr_disabled ?>></td>
                        <td> <input type="text" name="new_hires" size="15" value="<?php echo $new_hires ?>" <?php echo $hr_disabled ?>></td>
                        <td> <input type="text" name="resignations" size="15" value="<?php echo $resignations ?>" <?php echo $hr_disabled ?>></td>
                        <td> <input type="text" name="ads_run" size="15" value="<?php echo $ads_run ?>" <?php echo $hr_disabled ?>></td>

                     </tr>
                  </table>
		<td>&nbsp;</td>
	     </tr>
            <tr>
               <td width="2%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="center">
                  <table border="1" cellpadding="0" cellspacing="0">
                     <tr bgcolor="#4D76A5">
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Accidents*</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Drug Test?</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Bids</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Low Bid</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Accepted</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Rejected</font></b></td>
                     </tr>
                     <tr>
                        <td> <input type="text" name="accidents" size="15" value="<?php echo $accidents ?>" <?php echo $hr_disabled ?>></td>
                        <td> <input type="text" name="drug_test" size="15" value="<?php echo $drug_test ?>"></td>
                        <td> <input type="text" name="bids" size="15" value="<?php echo $bids ?>" <?php echo $eng_disabled ?>></td>
                        <td> <input type="text" name="low_bid" size="15" value="<?php echo $low_bid ?>"></td>
                        <td> <input type="text" name="accepted" size="15" value="<?php echo $accepted ?>"></td>
                        <td> <input type="text" name="rejected" size="15" value="<?php echo $rejected ?>"></td>

                     </tr>
                  </table>
                <td>&nbsp;</td>
             </tr>
            <tr>
               <td width="1%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="center">
                  <table border="1" cellpadding="0" cellspacing="0">
                     <tr bgcolor="#4D76A5">
<!--
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Number of Scans</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Number of Checkers</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Network % Uptime</font></b></td>
-->
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Crane % Downtime</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Average Truck Times</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Average # of Trucks</font></b></td>
                     </tr>
                     <tr>
<!--
                        <td> <input type="text" name="num_of_scans" size="14" value="<?php echo $num_of_scans ?>" <?php echo $ts_disabled ?>></td>
                        <td> <input type="text" name="num_of_checkers" size="14" value="<?php echo $num_of_checkers ?>" <?php echo $ts_disabled ?>></td>
                        <td> <input type="text" name="network_uptime" size="14" value="<?php echo $network_uptime ?>" <?php echo $ts_disabled ?>></td>
-->
                        <td> <input type="text" name="crane_downtime" size="34" value="<?php echo $crane_downtime ?>" <?php echo $op_disabled ?>></td>
                        <td> <input type="text" name="ave_truck_times" size="34" value="<?php echo $ave_truck_times ?>" <?php echo $op_disabled ?>></td>
                        <td> <input type="text" name="ave_num_of_trucks" size="34" value="<?php echo $ave_num_of_trucks ?>" <?php echo $op_disabled ?>></td>

                     </tr>
                  </table>
                <td>&nbsp;</td>
             </tr>
            <tr>
               <td width="1%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>

            <tr>
               <td>&nbsp;</td>
               <td align="center">
                  <table border="1" cellpadding="0" cellspacing="0">


                     <tr bgcolor="#4D76A5">
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Visiting Customers</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Date</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Purpose of &nbsp;Visit</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Tours Scheduled</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Date</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Contracts in Negotiation</font></b></td>
                     </tr>

			<?php
                                $data = "mk_visit";
                                $query = "SELECT *  FROM $data where meeting_id=$mId";
                                $result = pg_query($connection, $query) or
                                        die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);


                     	    	for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $customer[$i] = $row[1];
                                        $v_date[$i] = $row[2];
                                        $purpose[$i] = $row[3];
                                        $t_scheduled[$i] = $row[4];
                                        $t_date[$i] = $row[5];
                                        $contracts[$i] = $row[6];
              			}
				for($i=0; $i < 3; $i++){
				
			?>
                     <tr>
                        <td> <input type="text" name="<?php echo ("customer".$i)?>" size="15" value="<?php echo $customer[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("v_date".$i) ?>" size="15" value="<?php echo $v_date[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("purpose".$i) ?>" size="15" value="<?php echo $purpose[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("t_scheduled".$i) ?>" size="15" value="<?php echo $t_scheduled[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("t_date".$i) ?>" size="15" value="<?php echo $t_date[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("contracts".$i) ?>" size="15" value="<?php echo $contracts[$i] ?>" <?php echo $mk_disabled ?>></td>
                     </tr>
			<?php
				}
			?>
                  </table>
                <td>&nbsp;</td>
             </tr>
            <tr>
               <td width="1%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>

            <tr>
               <td>&nbsp;</td>
               <td align="center">
                  <table border="1" cellpadding="0" cellspacing="0">
                     <tr bgcolor="#4D76A5">
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Recent Rate Quotes</font></b></td>
                        <td width="36%" align="center"><b><font size="2" face="Verdana">Description</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Potential New Business</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Departing Business</font></b></td>
                       
                     </tr>
			<?php
                                $data = "mk_quotes";
                                $query = "SELECT *  FROM $data where meeting_id=$mId";
                                $result = pg_query($connection, $query) or
                                        die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);


                                // if records present
                              	for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $rate_quotes[$i] = $row[1];
                                        $desc[$i] = $row[2];
                                        $p_business[$i] = $row[3];
                                        $d_business[$i] = $row[4];
                
				}
				for($i=0; $i < 3; $i++){
                        ?>
                     <tr>
                        <td> <input type="text" name="<?php echo ("rate_quotes".$i)?>" size="15" value="<?php echo $rate_quotes[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("desc".$i) ?>" size="54" value="<?php echo $desc[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("p_business".$i) ?>" size="15" value="<?php echo $p_business[$i] ?>" <?php echo $mk_disabled ?>></td>
                        <td> <input type="text" name="<?php echo ("d_business".$i) ?>" size="15" value="<?php echo $d_business[$i] ?>" <?php echo $mk_disabled ?>></td>
                     </tr>
                        <?php
                                }
                        ?>

                  </table>
                <td>&nbsp;</td>
             </tr>
	     

            <tr>
               <td width="1%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>

            <tr>
               <td>&nbsp;</td>
               <td align="center">
                  <table border="0" cellpadding="0" cellspacing="0">
                     <tr>
                        <td width="100%" align="left"><b><font size="2" face="Verdana">*Description</font></b></td>
                     </tr>
                     <tr>
                        <td align="left"> <textarea name="accident_desc" rows="3" cols="84" wrap <?php echo $hr_disabled ?>><?php echo $accident_desc ?></textarea></td>
                     </tr>
                  </table>
                <td>&nbsp;</td>
             </tr>
            <tr>
               <td width="1%">&nbsp;</td>
               <td></td>
               <td width="1%">&nbsp;</td>
            </tr>
