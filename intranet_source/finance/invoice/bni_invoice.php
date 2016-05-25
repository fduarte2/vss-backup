<?
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 ora_commitoff($conn);
 $cursor = ora_open($conn);
 $cursor2 = ora_open($conn); 
// $prod_conn = ora_logon("APPS@PROD_BACKUP", "APPS");
 $prod_conn = ora_logon("APPS@PROD", "APPS");
 if($prod_conn < 1){
    printf("Error logging on to the PROD Oracle Server: ");
    printf(ora_errorcode($prod_conn));
    printf("Please try later!");
    exit;
 }
 ora_commitoff($prod_conn);
 $prod_cursor = ora_open($prod_conn);

    $invoice_array= array();
    $customer_array= array();
    $date_array= array();
    $description_array= array();
    $solomon_array= array();
    $lr_num= array();
    $distributions= array();
    $asset_array= array();
    $service_array= array();
    $gl_array= array();
    $commodity_array= array();
    $distro_amount= array();

  $batch_source="BNI";
  $pay_term_id = 4;
  $trx_type = "Invoice - Standard";
  $line_context="BNI";


 // Grab all sent items
  $sql = "select invoice_num, customer_id, invoice_date, asset_code, service_code, gl_code, commodity_code, service_description, tosolomon, service_amount, lr_num from billing where tosolomon is null and service_status in ('INVOICED') order by invoice_num";
//  $sql = "select invoice_num, customer_id, invoice_date, asset_code, service_code, gl_code, commodity_code, service_description, tosolomon, service_amount, lr_num from billing where invoice_num in (980505613)";

// $sql = "select invoice_num, customer_id, invoice_date, asset_code, service_code, gl_code, commodity_code, service_description, tosolomon, service_amount, lr_num from billing where tosolomon ='L' and invoice_date>to_date('10/01/2003','mm/dd/yyyy') and service_status in ('INVOICED') order by invoice_num";
 //echo "$sql\n";
 $statement = ora_parse($cursor, $sql);
 ora_exec($cursor);

 // Build array of invoices to process.
 $bin_count = 0;
 $i = 0;
 $tmp_invoice = 0;
 $invoice_array[$i] = 0;
 while(ora_fetch($cursor)){
   $tmp_invoice = ora_getcolumn($cursor, 0);
   // We have a new invoice if the last invoice is not the same number.
   if($tmp_invoice != $invoice_array[($i - 1)]){
    $invoice_array[$i] = ora_getcolumn($cursor, 0);
    $customer_array[$i] = ora_getcolumn($cursor, 1);
    $date_array[$i] = ora_getcolumn($cursor, 2);
    $description_array[$i] = ora_getcolumn($cursor, 7);

    // Resize to 20 Characters.
    $description_array[$i] = substr($description_array[$i], 0, 20);

    // Get rid of DOS newline characters and also some Unix ones
    // ereg_replace("old", "new", $string);
    $description_array[$i] = ereg_replace("
", "", $description_array[$i]);
    $description_array[$i] = ereg_replace("\n", "", $description_array[$i]);
    $description_array[$i] = ereg_replace("'", "''", $description_array[$i]);

    
    if($description_array[$i] == ""){ 
      $description_array[$i] = "N/A";
    }

    $solomon_array[$i] = ora_getcolumn($cursor, 8);
    $lr_num[$i] = ora_getcolumn($cursor, 10);
    $i++;
    $distributions[$i] = 0;
   }

   if($distributions[$i - 1] == 0){
     $temp = ora_getcolumn($cursor, 3);
     $asset_array[$i - 1] = $temp . ",";
     $temp = ora_getcolumn($cursor, 4);
     $service_array[$i - 1] = $temp . ",";
     $temp = ora_getcolumn($cursor, 5);
     $gl_array[$i - 1] = $temp . ",";
     $temp = ora_getcolumn($cursor, 6);
     $commodity_array[$i - 1] = $temp . ",";
     $temp = ora_getcolumn($cursor, 9);
     $distro_amount[$i - 1] = $temp . ",";

     $distributions[$i - 1] = $distributions[$i - 1] + 1;
   }
   else{
     $temp = ora_getcolumn($cursor, 3);
     $asset_array[$i - 1] = $asset_array[$i - 1] . $temp . ",";
     $temp = ora_getcolumn($cursor, 4);
     $service_array[$i - 1] = $service_array[$i - 1] . $temp . ",";
     $temp = ora_getcolumn($cursor, 5);
     $gl_array[$i - 1] = $gl_array[$i - 1] . $temp . ",";
     $temp = ora_getcolumn($cursor, 6);
     $commodity_array[$i - 1] = $commodity_array[$i - 1] . $temp . ",";
     $temp = ora_getcolumn($cursor, 9);
     $distro_amount[$i - 1] = $distro_amount[$i - 1] . $temp . ",";

     $distributions[$i - 1] = $distributions[$i - 1] + 1;
   }
 }

 $invalid_custs = "";
 $invalid_address="";

 // Validate the Customers
 for($h = 0; $h < $i; $h++){
   $bni_cust_id = $customer_array[$h];
   $cust_sql = "select customer_id from ra_customers where status = 'A' and customer_number = '" . $customer_array[$h] . "'";
   $statement = ora_parse($prod_cursor, $cust_sql);
   ora_exec($prod_cursor);
 
   if(!ora_fetch($prod_cursor)){
     	// Got trouble
     	$invalid_custs = $invalid_custs . "-- " . $customer_array[$h] . " --";
   }else{
	$cust_id = ora_getcolumn($prod_cursor, 0);
     	$cust_id_sql = "select address_id from ra_addresses_all where customer_id= '".$cust_id."' and bill_to_flag in ('Y','P')";
 
    	$statement = ora_parse($prod_cursor, $cust_id_sql);
     	ora_exec($prod_cursor);
     	if (!ora_fetch($prod_cursor)){
        	$invalid_address = $invalid_address. "-- " .$customer_array[$h] . " --";
     	}else{// insert into oracle
		$address_id = ora_getcolumn($prod_cursor, 0);

   		$sql = "select sum(service_amount) totalamt from billing where invoice_num = '" . $invoice_array[$h] . "' and service_status = 'INVOICED'";
   		$statement = ora_parse($cursor, $sql);
   		ora_exec($cursor);
   		$total = ora_getcolumn($cursor, 0);
		//inset total
                $insert_interface="insert into ra_interface_lines_all(batch_source_name, cust_trx_type_name, trx_number, trx_date, description, amount, orig_system_bill_customer_id, orig_system_bill_address_id, interface_line_attribute1, interface_line_context, line_type, set_of_books_id, term_id, conversion_rate, conversion_type, currency_code) values ('".$batch_source."','".$trx_type."','".$invoice_array[$h]."','".$date_array[$h]."','".$description_array[$h]."',$total,$cust_id,$address_id,'".$invoice_array[$h]."','".$line_context."','LINE',1,$pay_term_id, 1, 'User', 'USD')";
//				echo $insert_distributions."<br>";
   		$statement = ora_parse($prod_cursor, $insert_interface);
   		if (!ora_exec($prod_cursor)){ 
			ora_rollback($prod_conn);
			continue;
		}

		//insert distributions
   		$serviceARRAY = split(",", $service_array[$h]);
   		$glARRAY = split(",", $gl_array[$h]);
   		$commodityARRAY = split(",", $commodity_array[$h]);
   		$assetARRAY = split(",", $asset_array[$h]);
   		$amountARRAY = split(",", $distro_amount[$h]);
   		$current_ship = $lr_num[$h];
 
	   	// The first loop gets the actual values for the account codes
   		for($y = 0; $y < $distributions[$h]; $y++){
     		// Split the lists for this distribution line

     			if($y == 0){
       				$last_commodity = "9100";
     			}
     			// Get the Service Code
     			if($serviceARRAY[$y] == ""){
       				$serviceARRAY[$y] = "0000";
     			}
                        //add leading '0'
                        $serviceARRAY[$y] = sprintf("%04d", $serviceARRAY[$y]);

     			// Get the Account Code
     			if($glARRAY[$y] == ""){
       				// No account returned from billing, we must get one based on Service Code
       				$gl_sql = "select gl_code from service_category where service_code = '" .  $serviceARRAY[$y] . "'";
       				$statement = ora_parse($cursor, $gl_sql);
       				ora_exec($cursor);
       				$glARRAY[$y] = ora_getcolumn($cursor, 0);
     			}

     			// Get the Asset Code corrected (Warehouse unknown)
     			// Some MORON seems to think that it is WAOO- not WA00
     			// Lets just get rid of all O's...
			if($assetARRAY[$y] == "0"){
                                $assetARRAY[$y] = "0000";
                        } 
    			if($assetARRAY[$y] != "OPEN"){
       				$assetARRAY[$y] = ereg_replace("O", "0", $assetARRAY[$y]);
     			}
     			if($assetARRAY[$y] == ""){
       				$assetARRAY[$y] = "W000";
     			}
     			// Also turn all Asset codes into CAPS- prevents more problems...
     			$assetARRAY[$y] = strtoupper($assetARRAY[$y]);

     			// Get the Commodity Code
     			if($commodityARRAY[$y] == ""){
       				// Go by ship manifest??
       				if($glARRAY[$y] == "3020" || $glARRAY[$y] == "3040" || $glARRAY[$y] == "3050"){
         				if($current_ship != ""){
           					$ship_sql = "select commodity_code from cargo_manifest where lr_num = '$current_ship'";
           					$statement = ora_parse($cursor, $ship_sql);
           					ora_exec($cursor);
           					$commodityARRAY[$y] = ora_getcolumn($cursor, 0);
           					if($commodityARRAY[$y] == ""){
             						$commodityARRAY[$y] = $last_commodity;
           					}
         				}else{
           					$commodityARRAY[$y] = $last_commodity;
         				}
       				}else{
         				$commodityARRAY[$y] = $last_commodity;
       				}
     			}
     			if($commodityARRAY[$y] == "0"){
       				// No Commodity Involved (Should never happen)
       				$commodityARRAY[$y] = "0000";
     			}
			//add leading '0'
			$commodityARRAY[$y] = sprintf("%04d", $commodityARRAY[$y]);

     			// This is for the GL rules....
     			if($glARRAY[$y] < 3000){
       				$commodityARRAY[$y] = "0000";
       				$assetARRAY[$y] = "0000";
       				$serviceARRAY[$y] = "0000";
     			}

     			$last_commodity = $commodityARRAY[$y];
  		} // First loop over

  		// Ok, now that we actually have the values, compare and write the guys out
  		for($y = 0; $y < $distributions[$h]; $y++){
     			$service = $serviceARRAY[$y];
     			$gl = $glARRAY[$y];
     			$commodity = $commodityARRAY[$y];
     			$asset = $assetARRAY[$y];
     			$amount = $amountARRAY[$y];

                        //validate gl code
                        $sql = "select * from fnd_flex_values where flex_value_set_id ='1005835' and flex_value ='".$gl."'";
                        $statement = ora_parse($prod_cursor, $sql);
                        ora_exec($prod_cursor);
                        if(!ora_fetch($prod_cursor)){
                                // Got trouble
                                $invalid_gl_code .=  "-- " . $gl . " Invoice(".$invoice_array[$h].") --";
                                ora_rollback($prod_conn);
                                continue 2;
                        }

			//validate service code
			$sql = "select * from fnd_flex_values where flex_value_set_id ='1005836' and flex_value='". $service."'";
			$statement = ora_parse($prod_cursor, $sql);
   			ora_exec($prod_cursor);
			if(!ora_fetch($prod_cursor)){
        			// Got trouble
        			$invalid_service_code .=  "-- " . $service . " Invoice(".$invoice_array[$h].") --";
                            	ora_rollback($prod_conn);
                      		continue 2;
                    	}
			
			//validate commodity
                        $sql = "select * from fnd_flex_values where flex_value_set_id ='1005837' and flex_value = '". $commodity."'";
                        $statement = ora_parse($prod_cursor, $sql);
                        ora_exec($prod_cursor);
                        if(!ora_fetch($prod_cursor)){
                                // Got trouble
                                $invalid_commodity_code .=  "-- " . $commodity . " Invoice(".$invoice_array[$h].") --";
                                ora_rollback($prod_conn);
                                continue 2;
                        }

                        //validate asset 
                        $sql = "select * from fnd_flex_values where flex_value_set_id ='1005838' and flex_value= '". $asset ."'";
                        $statement = ora_parse($prod_cursor, $sql);
                        ora_exec($prod_cursor);
                        if(!ora_fetch($prod_cursor)){
                                // Got trouble
                                $invalid_asset_code .=  "-- " . $asset . " Invoice(".$invoice_array[$h].") --";
                                ora_rollback($prod_conn);
                                continue 2;
                        }

    			// Lets see if we need a new line or not
    			if(($asset == $assetARRAY[$y + 1]) && ($commodity == $commodityARRAY[$y + 1]) && ($gl == $glARRAY[$y + 1]) && ($service == $serviceARRAY[$y + 1])){
      				// In this case, the next line is IDENTICAL to us, so lets combine the
      				// amounts and continue the for loop
      				$amountARRAY[$y + 1] = $amountARRAY[$y + 1] + $amount;
      				continue;
    			}

    			// Should we split 30 / 70 for Crane Revenue?
    			if($commodity == "3102" && $service == "6611"){
      				// Yes, we need to split the Crane Revenue
      				// 70% Goes to 3077-9732
      				$seventy_percent = round($amount * .7, 2);
      				$thirty_percent = $amount - $seventy_percent;
				$insert_distributions="insert into ra_interface_distributions_all(account_class, amount, segment1, segment2, segment3, segment4, segment5, segment6, comments,  interface_line_attribute1, interface_line_context) values ('REV', $seventy_percent, '00','3077','9732','".$commodity."','".$asset."','00','Revenue','".$invoice_array[$h]."','".$line_context."')";      				
//						echo $insert_distributions."<br>";
      				$statement = ora_parse($prod_cursor, $insert_distributions);
      				if (!ora_exec($prod_cursor)){
					ora_rollback($prod_conn);
					continue 2;
				} 
                		
				$insert_distributions="insert into ra_interface_distributions_all(account_class, amount, segment1, segment2, segment3, segment4, segment5, segment6, comments,  interface_line_attribute1, interface_line_context) values ('REV', $thirty_percent, '00','3080','9742','".$commodity."','".$asset."','00','Revenue','".$invoice_array[$h]."','".$line_context."')";
//						echo $insert_distributions."<br>";
      				$statement = ora_parse($prod_cursor, $insert_distributions);
      				if (!ora_exec($prod_cursor)){
					ora_rollback($prod_conn);
                                        continue 2;
                                }

    			}else{
				//check GL_ALLOCATION HEADER IN BNI
				$sql = "select id, amount from gl_allocation_header
					where customer_id = $bni_cust_id and gl_code='$gl' and
					service_code = '$service' and commodity_code = '$commodity'";
 				$statement = ora_parse($cursor, $sql);
 				ora_exec($cursor);	
				if (ora_fetch($cursor)){		
					$id = ora_getcolumn($cursor, 0);
					$tot_amt = ora_getcolumn($cursor,1);
					//get distribution detail
					$sql = "select gl_code, service_code, commodity_code, amount 
						from gl_allocation_body 
						where header_id = $id";
					$statement = ora_parse($cursor, $sql);
	                                ora_exec($cursor);
					$d= 0;
					while (ora_fetch($cursor)){
                                                $d_gl[$d] = ora_getcolumn($cursor, 0);
                                                $d_service[$d] = ora_getcolumn($cursor, 1);
                                                $d_commodity[$d] = ora_getcolumn($cursor, 2);
                                                $d_amt[$d] = ora_getcolumn($cursor, 3);
						$d++;
					}      
					$d_count = $d;
					$t_amt = 0;
					for ($d =0; $d < $d_count; $d++){
						if ($d < $d_count - 1){
							$amt = round($amount * ($d_amt[$d]/$tot_amt), 2);
							$t_amt += $amt;
						}else{
							$amt = round($amount - $t_amt, 2);
						}
						$insert_distributions="insert into ra_interface_distributions_all(account_class, amount, segment1, segment2, segment3, segment4, segment5, segment6, comments,  interface_line_attribute1, interface_line_context) values ('REV', $amt, '00','".$d_gl[$d]."','".$d_service[$d]."','".$d_commodity[$d]."','".$asset."','00','Revenue','".$invoice_array[$h]."','".$line_context."')";
//						echo $insert_distributions."<br>";

                                        	$statement = ora_parse($prod_cursor, $insert_distributions);
                                        	if (!ora_exec($prod_cursor)){
                                                	ora_rollback($prod_conn);
                                                	continue 3;
                                        	}
					}

				}else{  // Normal Transaction
                			$insert_distributions="insert into ra_interface_distributions_all(account_class, amount, segment1, segment2, segment3, segment4, segment5, segment6, comments,  interface_line_attribute1, interface_line_context) values ('REV', $amount, '00','".$gl."','".$service."','".$commodity."','".$asset."','00','Revenue','".$invoice_array[$h]."','".$line_context."')";
//						echo $insert_distributions."<br>";
      					$statement = ora_parse($prod_cursor, $insert_distributions);
                                	if (!ora_exec($prod_cursor)){
                                        	ora_rollback($prod_conn);
                                        	continue 2;
									}
                 }

			}
   		}
		// Update BNI to reflect import
             	$sql_update = "update billing set tosolomon = 'L' where invoice_num = '" .  $invoice_array[$h] . "'";
             	$statement = ora_parse($cursor, $sql_update);
//							echo $sql_update."<br>";
		if (!ora_exec($cursor)){
			//rollback import
			ora_rollback($prod_conn);
		}else{
			//commit
			if (ora_commit($prod_conn)){
				ora_commit($conn);
				$bni_count +=1;
			}else{
				ora_rollback($conn);
			}
		}
	}
 }
}
ora_close($cursor);
ora_close($prod_cursor);
ora_logoff($conn);
ora_logoff($prod_conn);
?>
