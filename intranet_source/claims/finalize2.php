<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Finalize Claim";
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
	    <font size="5" face="Verdana" color="#0066CC">Finalize Claim
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
	    <font size="2" face="Verdana"><b>Select what you would like to do with this claim:</b>
               <form action="finalize3.php" method="Post" name="claim_form">
                     <? 
                        printf("<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\">");
                        include("connect.php");
                
                        // open a connection to the database server
/*                        $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
                        if (!$conn) {
                           die("Could not open connection to database server");
                        }
*/
                          $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//                          $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
                        if (!$bni_conn) {
                           printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
                           printf("Please report to TS!");
                           exit;
                        }
                        $cursor = ora_open($bni_conn);
						$ex_postgres_cursor = ora_open($bni_conn);

                        // generate and execute a query
//                        $stmt = "select * from claim_log where claim_id = '$claim_id'";
//                        $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));

                        // get the number of rows in the resultset
//                        $rows = pg_num_rows($result);
                       $sql = "select * from claim_log_oracle 
								where (completed = 'f' or completed is null) 
								and CUSTOMER_INVOICE_NUM = 
									(SELECT CUSTOMER_INVOICE_NUM
									from claim_log_oracle
									WHERE CLAIM_ID = '".$claim_id."')
								order by CLAIM_ID";
						ora_parse($ex_postgres_cursor, $sql);
						ora_exec($ex_postgres_cursor);
						if(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

//                        if ($rows > 0) {
//                           $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
                           $cust = $row['CUSTOMER_ID'];
                           $system = $row['SYSTEM'];
                           $letter_date = date('m/d/Y', strtotime($row['LETTER_DATE']));
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
				 do{
//                 for($i=0; $i<$rows; $i++){
//                   $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
                   // Gather information
                   $ccd_lot_id = $row['CCD_LOT_ID'];
                   $claim_date = date('m/d/Y', strtotime($row['CLAIM_DATE']));
                   $invoice_num = $row['INVOICE_NUM'];
                   $vessel_num = $row['VESSEL_NUM'];
                   $commodity_code = $row['COMMODITY_CODE'];
                   $commodity_name = $row['PRODUCT_NAME'];
                   $ccd_mark = $row['CCD_MARK'];
                   $rf_pallet_id = $row['RF_PALLET_ID'];
                   $bni_bl = $row['BNI_BL'];
                   $quantity = $row['QUANTITY'];
                   $weight = $row['WEIGHT'];
                   $cost = $row['COST'];
                   $amount = $row['AMOUNT'];
                   $quantity_claimed = $row['QUANTITY_CLAIMED'];
                   $denied_quantity = $row['DENIED_QUANTITY'];
                   $customer_invoice_num = $row['CUSTOMER_INVOICE_NUM'];
                   $bni_mark = $row['BNI_MARK'];
                   $vessel_name = $row['VESSEL_NAME'];
                   $voyage = $row['VOYAGE'];
                   $vessel_bl = $row['VESSEL_BL'];
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
                 } while(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
               } // if ( row )
               printf("<tr><td>Total:</td><td>$lines_count</td><td>&nbsp;</td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td><td>$qty_count</td><td>&nbsp; </td><td>$dollar_total</td></tr></table>");
?>
               </td>
            </tr>
            <tr>
              <td>
              <input type="checkbox" name="complete"> Complete
Claim&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="checkbox" name="letter">Create Letter<br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
              <center>
              Signature: <select name="signature">
						   <option value="victor">Victor F. Farkas</option>
                           <option value="frank">Frank Vignuli</option>
                           <option value="marty">Marty McLaughlin</option>
                         </select>
              <br /><br />
              Letter Date: <input type="textbox" name="letter_date" size="10" maxlength="25" value="<?= $letter_date ?>"><a href="javascript:show_calendar('claim_form.letter_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a><br /><br />
              Check Number: <input type="textbox" name="check_num" size="10" maxlength="25">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Check Date: <input type="textbox" name="check_date" size="10" maxlength="25" value="<?= date('m/d/Y') ?>">
<a href="javascript:show_calendar('claim_form.check_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a><br /><br />
<center>
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
