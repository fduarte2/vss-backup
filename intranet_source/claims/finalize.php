<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Finalize Claim";
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
<?
  if($input == 1){
    echo "<b>Invalid Claim:</b><br /><br />";
  }
?>
	 <p align="left">
	    <font size="2" face="Verdana"><b>Select or Free-Form Claim Number</b><br />
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td width="20%">&nbsp;</td>
               <td width="30%">&nbsp;</td>
               <td width="15%">&nbsp;</td>
               <td width="35%">&nbsp;</td>
            </tr>
               <form action="finalize2.php" method="Post" name="claim_form">
            <tr>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Free Form:</font></td>
               <td align="left">
                  <INPUT TYPE="text" NAME="claim_free" SIZE="22" value=""><br /></td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Or Select:</font></td>
               <td align="left">
                  <select name="claim_select" onchange="document.claim_form.submit(this.form)">
                     <option value="" select="selected">Select the Claim</option>
                     <? 
                          $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//                          $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
                          if (!$bni_conn) {
                             printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
                             printf("Please report to TS!");
                             exit;
                          }
                          $cursor = ora_open($bni_conn);
						  $ex_postgres_cursor = ora_open($bni_conn);

                        include("connect.php");
                
                        // open a connection to the database server
                        /*$conn = pg_connect ("host=$host dbname=$db user=$dbuser");
                        if (!$conn) {
                           die("Could not open connection to database server");
                        }*/

                        // generate and execute a query
//                        $sql = "select distinct claim_id, customer_invoice_num, customer_id from claim_log where completed = 'f' or completed is null";
//                        $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));

                        // get the number of rows in the resultset
//                        $rows = pg_num_rows($result);
                       $sql = "select distinct CLAIM_ID, CUSTOMER_INVOICE_NUM, CUSTOMER_ID from claim_log_oracle 
							where completed = 'f' or completed is null order by CLAIM_ID";
						ora_parse($ex_postgres_cursor, $sql);
						ora_exec($ex_postgres_cursor);
						while(ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

//                        if ($rows > 0) {
                           // iterate through resultset
//                           for ($i=0; $i<$rows; $i++) {
                              $cust = "";
//                              $row = pg_fetch_row($result, $i);
                              $pg_mark = trim($Short_Term_Row["CLAIM_ID"]);
                              $cust_inv = $Short_Term_Row["CUSTOMER_INVOICE_NUM"];
                              $cust = trim($Short_Term_Row["CUSTOMER_ID"]);

                              // ignore null mark's
                              if($pg_mark == "")
                                 continue;
                              $stmt = "select customer_name from customer_profile where customer_id = '$cust'";
                              $ora_success = ora_parse($cursor, $stmt);
                              $ora_success = ora_exec($cursor);
                              ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                              $customer_name = $row1['CUSTOMER_NAME'];
                              list($junk, $temp_customer_name) = split("-", $customer_name);
                              if($temp_customer_name != ""){
                                $customer_name = $temp_customer_name;
                              }
                              printf("<option value=\"%s\">%s (%s) - %s</option>", $pg_mark, $cust_inv, $pg_mark, $customer_name);
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
