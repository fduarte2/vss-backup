<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Final Notes";
  $area_type = "CLAI";

   $user = $userdata['username'];

   $claim_id = $HTTP_POST_VARS['claim_select'];

   // free-form takes the lead
   $claim_id_free = $HTTP_POST_VARS['claim_free'];
   if($claim_id_free != "")
     $claim_id = $claim_id_free;

   if($claim_id == ""){
     header("Location: finalize.php?input=1");
     exit;
   }

     // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Final Notes
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>Add Final Notes to this Claim:</b>
               <form action="final_notes3.php" method="Post" name="claim_form">
                     <? 
                        printf("<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\">");
                        include("connect.php");
                
                        // open a connection to the database server
                        $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
                        if (!$conn) {
                           die("Could not open connection to database server");
                        }

                        $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
                        if (!$bni_conn) {
                           printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
                           printf("Please report to TS!");
                           exit;
                        }
                        $cursor = ora_open($bni_conn);

                        // generate and execute a query
                        $stmt = "select * from claim_log where claim_id = '$claim_id'";
                        $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));

                        // get the number of rows in the resultset
                        $rows = pg_num_rows($result);

                        if ($rows > 0) {
                           $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
                           $cust = $row['customer_id'];
                           $system = $row['system'];
                           $letter_date = date('m/d/Y', strtotime($row['letter_date']));
                           $stmt = "select customer_name from customer_profile where customer_id = '$cust'";
                           $ora_success = ora_parse($cursor, $stmt);
                           $ora_success = ora_exec($cursor);
                           ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                           $customer_name = $row1['CUSTOMER_NAME'];
                           list($junk, $customer_name) = split("-", $customer_name);
                           ?>
<br />
<center><b>Customer: <? echo $customer_name; ?></b><br /></center>
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td align="center">
                 Claim Detail:<br />
               <table border="3" bordercolor="#02AE65">
               <th>Inv #</th><th>Comm</th><th>Vessel</th><!-- 1-3 -->
               <th>Voy</th><th>Vessel BL</th><!-- 4-5 -->
<?
               // 6-7
               if($system == "CCDS"){
                 printf("<th>Lot ID</th><th>Mark</th>");
               }
               else if($system == "BNI"){
                 printf("<th>BOL</th><th>Mark</th>");
               }
               else if($system == "RF"){
                 printf("<th>Pallet ID</th><th> </th>");
               }
               // End table headers 6-7
?>
               <th>Weight</th><th>Claimed</th><th>Cost</th><th>Amount</th><!-- 8-11 -->
<?
                 // iterate through resultset
                 $lines_count = 0;
                 $qty_count = 0;
                 $dollar_total = 0;
                 for($i=0; $i<$rows; $i++){
                   $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
                   // Gather information
                   $ccd_lot_id = $row['ccd_lot_id'];
                   $claim_date = date('m/d/Y', strtotime($row['claim_date']));
                   $invoice_num = $row['invoice_num'];
                   $vessel_num = $row['vessel_num'];
                   $commodity_code = $row['commodity_code'];
                   $commodity_name = $row['product_name'];
                   $ccd_mark = $row['ccd_mark'];
                   $rf_pallet_id = $row['rf_pallet_id'];
                   $bni_bl = $row['bni_bl'];
                   $quantity = $row['quantity'];
                   $weight = $row['weight'];
                   $cost = $row['cost'];
                   $amount = $row['amount'];
                   $quantity_claimed = $row['quantity_claimed'];
                   $denied_quantity = $row['denied_quantity'];
                   $customer_invoice_num = $row['customer_invoice_num'];
                   $bni_mark = $row['bni_mark'];
                   $vessel_name = $row['vessel_name'];
                   $voyage = $row['voyage'];
                   $vessel_bl = $row['vessel_bl'];
                   // 1-3
                   printf("<tr><td>$customer_invoice_num</td><td>$commodity_name</td><td>$vessel_name</td>");
                   // 4-5
                   printf("<td>$voyage</td><td>$vessel_bl</td>");
                   // Begin 6-7
                   if($system == "CCDS"){
                     printf("<td>$ccd_lot_id</td><td>$ccd_mark</td>");
                   }
                   else if($system == "BNI"){
                     printf("<td>$bni_bol</td><td>$bni_mark</td>");
                   }
                   else if($system == "RF"){
                     printf("<td>$rf_pallet_id</td><td> </td>");
                   }
                   // 8-11
                   printf("<td>$weight</td><td>$quantity_claimed</td><td>$cost</td><td>$amount</td></tr>");
                   $lines_count++;
                   $qty_count += $quantity_claimed;
                   $dollar_total += $amount;
                 }
               } // if ( row )
               printf("<tr><td>Total:</td><td>$lines_count</td><td>&nbsp;</td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>$qty_count</td><td>&nbsp; </td><td>$dollar_total</td></tr></table>");
?>
               </td>
            </tr>
            <tr>
              <td>
               Final Notes:
              </td>
            </tr>
            <tr>
              <td>
               <textarea name="internal_notes" cols="40" rows="6" maxlength="300"><? echo $row['internal_notes'];?></textarea>
              </td>
            </tr>
            <tr>
              <td>
              <input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="reset" value="Reset">
</center>
</form>
              </td>
            </tr>
         </table>
         </p>
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
