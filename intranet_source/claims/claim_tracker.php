<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Claim Tracker";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Claim Tracker
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
	    <font size="2" face="Verdana"><b>Select Claim Number</b><br />
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td width="20%">&nbsp;</td>
               <td width="30%">&nbsp;</td>
               <td width="15%">&nbsp;</td>
               <td width="35%">&nbsp;</td>
            </tr>
               <form action="reports/claim_tracker.php" method="get" name="claim_form">
            <tr>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Select:</font></td>
               <td align="left">
                  <select name="claim_id" onchange="document.claim_form.submit(this.form)">
                     <option value="" select="selected">Select the Claim</option>
                     <? 
                          $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
                          if (!$bni_conn) {
                             printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
                             printf("Please report to TS!");
                             exit;
                          }
                          $cursor = ora_open($bni_conn);
						  $cursor_main = ora_open($bni_conn);

                        include("connect.php");
                
                        // open a connection to the database server
//                        $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
//                        if (!$conn) {
//                           die("Could not open connection to database server");
//                        }

                        // generate and execute a query
                        $stmt = "select distinct claim_id, customer_invoice_num, customer_id from claim_log_oracle where completed = 'f' or completed is null order by claim_id desc";
					   $ora_success = ora_parse($cursor_main, $stmt);
					   $ora_success = ora_exec($cursor_main);
//					   echo $stmt."<br>";
//                        $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));

                        // get the number of rows in the resultset
//                        $rows = pg_num_rows($result);

                        if(ora_fetch_into($cursor_main, $row)) {
                           // iterate through resultset
						   do {
//                           for ($i=0; $i<$rows; $i++) {
                              $cust = "";
//                              $row = pg_fetch_row($result, $i);
                              $pg_mark = trim($row[0]);
                              $cust_inv = $row[1];
                              $cust = trim($row[2]);

                              // ignore null mark's
                              if($pg_mark == "")
                                 continue;
                              $stmt = "select customer_name from customer_profile where customer_id = '$cust'";
                              $ora_success = ora_parse($cursor, $stmt);
                              $ora_success = ora_exec($cursor);
                              ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                              $customer_name = $row1['CUSTOMER_NAME'];
                              list($junk, $temp_customer_name) = split("-", $customer_name);
                              if($temp_customer_name != "")
                                $customer_name = $temp_customer_name;

                              if($claim_id == $pg_mark){
                                printf("<option value=\"%s\" SELECTED>%s (%s) - %s</option>", $pg_mark, $cust_inv, $pg_mark, $customer_name);
                              }
                              else{
                                printf("<option value=\"%s\">%s (%s) - %s</option>", $pg_mark, $cust_inv, $pg_mark, $customer_name);
                              }
                           } while(ora_fetch_into($cursor_main, $row));
                        }
                     ?>
                  </select>
               </td>
            </tr>
                        <tr>
               <td colspan="4" align="center">
                   <table border="0">
                      <tr>
                         <td width="10%">&nbsp;</td>
                         <td width="30%" align="right"> 
                            <input type="Submit" value="Select Claim">
                         </td>
                         <td width="5%">&nbsp;</td>
                         <td width="10%" align="left">
                            <input type="Reset" value=" Reset ">
                         </td>
                         <td width="10%">&nbsp;</td>
                      </tr>
                    </table>
                   </td>
                 </tr>
                 </form>
         </table>
         </p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
