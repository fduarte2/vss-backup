<?
  include("pow_session.php");
 
  $user = $userdata['username'];

  include("utility.php");
  include("defines.php");
  include("connect.php");

   // Connect to Oracle Database
   $conn_bni = ora_logon("SAG_OWNER@$bni", "SAG");
   if($conn_bni < 1){

      printf("Error logging on to the BNI Oracle Server: ");
      printf(ora_errorcode($conn_bni));
      printf("Please try later!");
      exit;
   }

   // Connect to Rf Database
   $conn_rf = ora_logon("SAG_OWNER@$rf", "OWNER");
   if($conn_rf < 1){

      printf("Error logging on to the RF Oracle Server: ");
      printf(ora_errorcode($conn_rf));
      printf("Please try later!");
      exit;
   }

   // Connect to Lcs Database
   $conn_lcs = ora_logon("LABOR@$lcs", "labor");
   if($conn_lcs < 1){

      printf("Error logging on to the LCS Oracle Server: ");
      printf(ora_errorcode($conn_lcs));
      printf("Please try later!");
      exit;
   }


   //Open Cursor
   $cursor = ora_open($conn_bni);
   $cursor1 = ora_open($conn_rf);
   $cursor2 = ora_open($conn_lcs);


  $timestamp = date('Y-m-d H:i:s');
  $today = date('Y-m-d');


  // Get all of the forms changes
  $customer_id = $HTTP_POST_VARS["customer_id"];
  $customer_name = $HTTP_POST_VARS["customer_name"];
  $contact = $HTTP_POST_VARS["contact"];
  $address = $HTTP_POST_VARS["address"];
  $address2 = $HTTP_POST_VARS["address2"];
  $city = $HTTP_POST_VARS["city"];
  $state = $HTTP_POST_VARS["state"];
  $zip = $HTTP_POST_VARS["zip"];
  $c_code = $HTTP_POST_VARS["country_code"];
  $fax = $HTTP_POST_VARS["fax"];
  $phone = $HTTP_POST_VARS["phone"];
  $ext = $HTTP_POST_VARS["ext"];
  $email = $HTTP_POST_VARS["email"];
  $status = $HTTP_POST_VARS["status"];

  $input = $HTTP_POST_VARS["input"];

  //$customer_name = str_replace("\'","''",$customer_name);
  $customer_name = OraSafeString($customer_name);
  $contact = OraSafeString($contact);
  $address = OraSafeString($address);
  $address2 = OraSafeString($address2);
  $city = OraSafeString($city);
  $state = OraSafeString($state);
  $email = OraSafeString($email);

  if ($status == 'HOLD')
    $status_value = 'INACTIVE';
 
 //Modify a customer

  if($input == "modify"){

  //Update to BNI

    $sql = "update customer_profile set customer_name = '$customer_name',
            customer_contact = '$contact', customer_address1 = '$address', customer_address2 = '$address2',
            customer_city = '$city', customer_state = '$state', customer_zip = '$zip', country_code = '$c_code',
            customer_phone = '$phone', customer_contact_ext = '$ext', customer_fax = '$fax',
            customer_email = '$email', customer_status = '$status' 
            where customer_id = $customer_id";
    
    $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_bni);
        ora_close($cursor);
        exit;
     }
     ora_commit($conn_bni);
  

        
  //Update to RF

   $sql = "update customer_profile set customer_name = '$customer_name',
            customer_contact = '$contact', customer_address1 = '$address', customer_address2 = '$address2',
            customer_city = '$city', customer_state = '$state', customer_zip = '$zip', country_code = '$c_code',
            customer_phone = '$phone', customer_contact_ext = '$ext', customer_fax = '$fax',
            customer_email = '$email', customer_status = '$status'
            where customer_id = $customer_id ";
    $stmt = ora_parse($cursor1,$sql);

     if(!ora_exec($cursor1))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_rf);
        ora_close($cursor1);
        exit;
     }
     ora_commit($conn_rf);
   
   // Update to LCS
  
    $sql = "update customer set customer_id = $customer_id , customer_name = '$customer_name'
            where customer_id = $customer_id ";
    $stmt = ora_parse($cursor2,$sql);

     if(!ora_exec($cursor2))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_lcs);
        ora_close($cursor2);
        exit;
     }
     ora_commit($conn_lcs);

  $modify = "true";

  $body = "Attention! $user has modified a BNI Customer - $customer_id\n";
  $mailsubject = "Modified BNI Customer";

  }
// Adding a customer
  else if ($input == "add"){

  // Check the customer existence
     $sql = "select * from customer_profile where customer_id = $customer_id";
     $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_close($cursor);
        exit;
     }

     ora_fetch($cursor);
     $rows = ora_numrows($cursor);

  if ($rows == 0) {
  
  // Insert into BNI
     $sql = "insert into customer_profile(customer_id,customer_name,customer_contact,customer_address1,customer_address2,customer_city,customer_state,customer_zip,country_code,customer_phone,customer_contact_ext,customer_fax,customer_email,customer_status) values ($customer_id,'$customer_name','$contact','$address','$address2','$city','$state','$zip','$c_code','$phone','$ext','$fax','$email','$status')";
     $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_bni);
        ora_close($cursor);
        exit;
     }
     ora_commit($conn_bni);

  // Insert into RF
     $sql = "insert into customer_profile(customer_id,customer_name,customer_contact,customer_address1,customer_address2,customer_city,customer_state,customer_zip,country_code,customer_phone,customer_contact_ext,customer_fax,customer_email,customer_status) values ($customer_id,'$customer_name','$contact','$address','$address2','$city','$state','$zip','$c_code','$phone','$ext','$fax','$email','$status')";
     $stmt = ora_parse($cursor1,$sql);

     if(!ora_exec($cursor1))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_rf);
        ora_close($cursor1);
        exit;
     }
     ora_commit($conn_rf);


  // Insert into LCS
     $sql = "insert into customer values($customer_id,'$customer_name')";     
     $stmt = ora_parse($cursor2,$sql);

     if(!ora_exec($cursor2))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_lcs);
        ora_close($cursor2);
        exit;
     }
     ora_commit($conn_lcs);

     $added = "true";
 
  }
  else
  {
   header("Location: customer.php?add=1");
   exit;
  } 
  $body = "Attention! $user has Added a BNI Customer - $customer_id\n";
  $mailsubject = "Added BNI Customer";

  }
 //Delete a Customer
  else
  {
  // Delete from BNI

     $sql = "delete from customer_profile where customer_id = $customer_id";
     $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_bni);
        ora_close($cursor);
        exit;
     }
     ora_commit($conn_bni);


  // Delete from RF

     $sql = "delete from customer_profile where customer_id = $customer_id";
     $stmt = ora_parse($cursor1,$sql);

     if(!ora_exec($cursor1))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_rf);
        ora_close($cursor1);
        exit;
     }
     ora_commit($conn_rf);


  // Delete from LCS

     $sql = "delete from customer where customer_id = $customer_id";
     $stmt = ora_parse($cursor2,$sql);

     if(!ora_exec($cursor2))
     {
        printf("Error in query:$sql");
        ora_rollback($conn_lcs);
        ora_close($cursor2);
        exit;
     }
     ora_commit($conn_lcs);

    $delete = "true";
  
    $body = "Attention! $user has deleted a BNI Customer - $customer_id\n";
    $mailsubject = "Deleted BNI Customer";

  }

  // get the receiving details 
  $sql = "select * from vessel_completion_email";
  $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_close($cursor);
        exit;
     }


  while(ora_fetch($cursor))
  {
     $id = ora_getcolumn($cursor,0);

     if ($id == 4)
     {
       $recv_names = ora_getcolumn($cursor,2);
       $recv_emails = ora_getcolumn($cursor,3);
     }
  }

 list($receiver_name1,$receiver_name2,$receiver_name3,$receiver_name4,$receiver_name5,$receiver_name6,$receiver_name7,$receiver_name8,$receiver_name9) = split(",", $recv_names);
  list($receiver_address1,$receiver_address2,$receiver_address3,$receiver_address4,$receiver_address5,$receiver_address6,$receiver_address7,$receiver_address8,$receiver_address9) = split(",", $recv_emails);

  $receiver = "\"$receiver_name1\" <$receiver_address1>, \"$receiver_name2\" <$receiver_address2>, \"$receiver_name3\" <$receiver_address3>, \"$receiver_name4\" <$receiver_address4>, \"$receiver_name5\" <$receiver_address5>, \"$receiver_name6\" <$receiver_address6>, \"$receiver_name7\" <$receiver_address7>, \"$receiver_name8\" <$receiver_address8>, \"$receiver_name9\" <$receiver_address9>";


// Send a notification of modified customer

if ($address2 != "") {
  $body = $body . "\nNew Information:\nCustomer Name: $customer_name\nContact: $contact\nAddress: $address\n         $address2\n         $city, $state  $zip\nPhone Number: $phone\nFax Number: $fax\nE-mail Address: $email\nStatus: $status_value\n";
}
else
{
  $body = $body . "\nNew Information:\nCustomer Name: $customer_name\nContact: $contact\nAddress: $address\n         $city, $state  $zip\nPhone Number: $phone\nFax Number: $fax\nE-mail Address: $email\nStatus: $status_value\n";

}

  $body = $body . "\nPlease ensure that this is taken care of in the Oracle Financial System\n\nThanks!\n\nBNI Automated Integrety Check System";


  $mailheaders = "From: " . "Mailserver@port.state.de.us\r\n";
  $mailheaders .= "X-Mailer: PHP4 Mail Function on Apache/Linux\n";


 //Sending Email

  mail($receiver, $mailsubject, $body, $mailheaders);

header("Location: /inventory/index.php");

ora_close($cursor);
ora_close($cursor1);
ora_close($cursor2);
ora_logoff($conn_bni);
ora_logoff($conn_rf);
ora_logoff($conn_lcs);
?>

