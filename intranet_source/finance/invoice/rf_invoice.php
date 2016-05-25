 <?

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
 //$conn = ora_logon("SAG_OWNER@BNI_BACKUP", "SAG");
 if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 ora_commitoff($conn);
 $cursor = ora_open($conn);

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
 $sql = "select distinct invoice_num, customer_id, invoice_date, asset_code, service_code, commodity_code, service_description, service_amount, tosolomon from rf_billing where tosolomon is null and service_status in ('INVOICED') order by invoice_num";
//  $sql = "select distinct invoice_num, customer_id, invoice_date, asset_code, service_code, commodity_code, service_description, service_amount, tosolomon from rf_billing where tosolomon = 'O' and service_status in ('INVOICED') order by invoice_num";

 $statement = ora_parse($cursor, $sql);
 ora_exec($cursor);

 // Build array of invoices to process.
 $rf_count = 0;
 $i = 0;
 $tmp_invoice = 0;
 $invoice_array[$i] = 0;
 // Duplicate INVOICES still causing trouble...
 while(ora_fetch($cursor)){
   $tmp_invoice = ora_getcolumn($cursor, 0);
   // We have a new invoice
   if($tmp_invoice != $invoice_array[$i - 1]){
    $invoice_array[$i] = ora_getcolumn($cursor, 0);
    $customer_array[$i] = ora_getcolumn($cursor, 1);
    $date_array[$i] = ora_getcolumn($cursor, 2);
    $asset_array[$i] = ora_getcolumn($cursor, 3);
    $service_array[$i] = ora_getcolumn($cursor, 4);
    $commodity_array[$i] = ora_getcolumn($cursor, 5);
    $description_array[$i] = ora_getcolumn($cursor, 6);
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

    //$solomon_array[$i] = ora_getcolumn($cursor, 7);
    $amount_array[$i] = ora_getcolumn($cursor, 7);
    $gl_array[$i] = "0000";
    $i++;
   }
   // else do nothing- we are still looking at records from an old invoice
 }

 // Validate the Customer
 for($h = 0; $h < $i; $h++){
   $temp_cust = $customer_array[$h];
   $cust_sql = "select customer_id from ra_customers where status = 'A' and customer_number = '" . $temp_cust . "'";
   $statement = ora_parse($prod_cursor, $cust_sql);
   ora_exec($prod_cursor);
   if (!ora_fetch($prod_cursor)){
	$invalid_custs = $invalid_custs . "-- " . $temp_cust . " --";
   }else{
   	$cust_id = ora_getcolumn($prod_cursor, 0);
 
        $cust_id_sql = "select address_id from ra_addresses_all where customer_id= '".$cust_id."' and bill_to_flag in ('Y', 'P')";
        $statement = ora_parse($prod_cursor, $cust_id_sql);
        ora_exec($prod_cursor);
        
	if (!ora_fetch($prod_cursor)){
		$invalid_address = $invalid_address. "-- " .$customer_array[$h] . " --";
	}else{
        	$address_id = ora_getcolumn($prod_cursor, 0);
        	// insert into oracle
                $sql = "select sum(service_amount) totalamt from rf_billing where invoice_num = '" . $invoice_array[$h] . "' and service_status = 'INVOICED'";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                $total = ora_getcolumn($cursor, 0);
                //inset total
                $insert_interface="insert into ra_interface_lines_all(batch_source_name, cust_trx_type_name, trx_number, trx_date, description, amount, orig_system_bill_customer_id, orig_system_bill_address_id, interface_line_attribute1, interface_line_context, line_type, set_of_books_id, term_id, conversion_rate, conversion_type, currency_code) values ('".$batch_source."','".$trx_type."','".$invoice_array[$h]."','".$date_array[$h]."','".$description_array[$h]."',$total,$cust_id,$address_id,'".$invoice_array[$h]."','".$line_context."','LINE',1,$pay_term_id, 1, 'User', 'USD')";

                $statement = ora_parse($prod_cursor, $insert_interface);
       
                if (!ora_exec($prod_cursor)){
                        ora_rollback($prod_conn);
                        continue;
                }

		//insert distributions
	   	// Get the Account Code
   		// No account returned from billing, we must get one based on Service Code
  
                //add leading '0'
                $service_array[$h] = sprintf("%04d", $service_array[$h]);

 		$gl_sql = "select gl_code from service_category where service_code = '" .  $service_array[$h] . "'";
   		$statement = ora_parse($cursor, $gl_sql);
   		ora_exec($cursor);
   		$gl_array[$h] = ora_getcolumn($cursor, 0);

   		// Get the Asset Code corrected (Warehouse unknown)
   		if($asset_array[$h] == ""){
     			$asset_array[$h] = "W000";
   		}
                if($asset_array[$h] == "0"){
                        $asset_array[$h] = "0000";
                }
   		// Get the Commodity Code
   		if($commodity_array[$h] == ""){
     			// Go by ship manifest?? - or use 9100
   		}
   		if($commodity_array[$h] == "0"){
     			// No Commodity Involved (Should never happen)
     			$commodity_array[$h] = "0000";
   		}
		//add leading '0'
                if($commodity_array[$h] <> ""){
                	$commodity_array[$h] = sprintf("%04d", $commodity_array[$h]);
		}

   		if($gl_array[$h] < 3000){
     			$asset_array[$h] = "0000";
     			$commodity_array[$h] = "0000";
     			$service_array[$h] = "0000";
   		}
   		if($asset_array[$h] != "OPEN"){
     			ereg_replace("O", "0", $asset_array[$h]);
   		}

             	//validate gl code
             	$sql = "select * from fnd_flex_values where flex_value_set_id ='1005835' and flex_value ='".$gl_array[$h]."'";
          	$statement = ora_parse($prod_cursor, $sql);
            	ora_exec($prod_cursor);
        	if(!ora_fetch($prod_cursor)){
              		// Got trouble
                 	$invalid_gl_code .=  "-- " . $gl_array[$h] . " Invoice(".$invoice_array[$h].") --";
                     	ora_rollback($prod_conn);
                  	continue;
              	}

         	//validate service code
          	$sql = "select * from fnd_flex_values where flex_value_set_id ='1005836' and flex_value ='".$service_array[$h]."'";
         	$statement = ora_parse($prod_cursor, $sql);
          	ora_exec($prod_cursor);
           	if(!ora_fetch($prod_cursor)){
                  	// Got trouble
                   	$invalid_service_code .=  "-- " . $service_array[$h] . " Invoice(".$invoice_array[$h].") --";
                   	ora_rollback($prod_conn);
                     	continue;
            	}

          	//validate commodity
             	$sql = "select * from fnd_flex_values where flex_value_set_id ='1005837' and flex_value='".$commodity_array[$h]."'";
           	$statement = ora_parse($prod_cursor, $sql);
           	ora_exec($prod_cursor);
           	if(!ora_fetch($prod_cursor)){
                     	// Got trouble
                  	$invalid_commodity_code .=  "-- " . $commodity_array[$h] . " Invoice(".$invoice_array[$h].") --";
                       	ora_rollback($prod_conn);
                     	continue;
         	}

         	//validate asset
          	$sql = "select * from fnd_flex_values where flex_value_set_id ='1005838' and flex_value='".$asset_array[$h]."'";
             	$statement = ora_parse($prod_cursor, $sql);
            	ora_exec($prod_cursor);
          	if(!ora_fetch($prod_cursor)){
                	// Got trouble
                     	$invalid_asset_code .=  "-- " . $asset_array[$h] . " Invoice(".$invoice_array[$h].") --";
                    	ora_rollback($prod_conn);
                     	continue;
          	}



                $insert_distributions="insert into ra_interface_distributions_all(account_class, amount, segment1, segment2, segment3, segment4, segment5, segment6, comments,  interface_line_attribute1, interface_line_context) values ('REV', $total, '00','".$gl_array[$h]."','".$service_array[$h]."','".$commodity_array[$h]."','".$asset_array[$h]."','00','Revenue','".$invoice_array[$h]."','".$line_context."')";

                $statement = ora_parse($prod_cursor, $insert_distributions);
               
                if (!ora_exec($prod_cursor)){
                        ora_rollback($prod_conn);
                        continue;
                }

                // Update RF to reflect import
                $sql_update = "update rf_billing set tosolomon = 'L' where invoice_num = '" . $invoice_array[$h] ."'";
                $statement = ora_parse($cursor, $sql_update);
                if (!ora_exec($cursor)){
                        //rollback import
                        ora_rollback($prod_conn);
                }else{
                        //commit
                        if (ora_commit($prod_conn)){
                                ora_commit($conn);
                                $rf_count +=1;
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
