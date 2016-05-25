<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Re-print Invoices";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

   // get form data, in case the page is called
   $from = $HTTP_POST_VARS["from"];
   $to = $HTTP_POST_VARS["to"];

   // adjust the numbers
   if ($from == "" && $to == "") {
     $is_called = false;
   } else {
     $is_called = true;

     if ($from != "") {
       if ($to != "") {
	 if ($from > $to) {
	   $temp = $from;
	   $from = $to;
	   $to = $temp;
	 }
       } else {
	 $to = $from;
       }
     } else {
       $from = $to;
     }
   }

   /* take out this part to speed up the page
   // by default, give the lastest invoice number
   if (!$is_called) {
     // Variables for switching from testing database tables and those of production
     // include("connect.php");
     $bni = "BNI";
     $billing = "billing";
     
     // Make Oracle Database connection
     $ora_conn = ora_logon("SAG_OWNER@$bni", "SAG");
     if (!$ora_conn) {
       printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn));
       printf("Please report to TS!\n");
       exit;
     }
     
     // open up a cursor over this connection
     $cursor = ora_open($ora_conn);
     
     // get the lastest invoice number
     $stmt = "select max(invoice_num) max_inv_num from $billing";
     $ora_success = ora_parse($cursor, $stmt);
     $ora_success = ora_exec($cursor);
     ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
     
     $from = $row['MAX_INV_NUM'];
     $to = $row['MAX_INV_NUM'];
   }
   */
  
?>

<!-- Reprint Invoices - Main page -->

<script type="text/javascript">
   // for IsNumeric function
   <? include("js_func.php") ?>

   function validate_form()
   {
      x = document.select_type
      from = x.from.value
      to = x.to.value
      
      // No invoice # is entered
      if (from == "" && to == "") {
	alert("Please enter the starting invoice number!")
	return false
      } 

      if (from != "" && !(from > 0)) {
	alert("Starting invoice number is not a valid invoice number.  Please reenter it!")
	return false
      }

      if (to != "" && !(to > 0)) {
	alert("Ending invoice number is not a valid invoice number.  Please reenter it!")
	return false
      }

      return true
   }

</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Reprint Invoices</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="80%">
      <form name="select_type" method="post" action="reprint_invoice.php" onsubmit="return validate_form()">
	 <p align="left">Please enter the starting and ending invoice number to reprint invoices.  Enter 
         just one of them to print one invoice.</p></font>
	 <table bgcolor="#f0f0f0" width="90%" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
               <td width="10%">&nbsp;</td>
	       <td width="40%"><font size="2" face="Verdana">From Invoice #:</font></td>
               <td width="10%">&nbsp;</td>
	       <td width="40%"><font size="2" face="Verdana">To Invoivce #:</font></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td><input type="text" name="from" value="<?= $from ?>" size="18"></td>
               <td>&nbsp;</td>
	       <td><input type="text" name="to" value="<?= $to ?>" size="18"></td>
	    </tr>
	    <tr>
	       <td colspan="4" height="12"></td>
	    </tr>
	    <tr>
	       <td colspan="4" align="center">
		   <table border="0" width="80%">
		      <tr>
			 <td width="38%" align="right"> 
	                     <input type="Submit" value="Re-Print  ">
			 </td>
			 <td width="20%">&nbsp;</td>
			 <td width="42%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
	   	</td>
	        </form>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
<?
   if ($is_called) {
?>
   <tr>
      <td colspan="2" height="12"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td valign="top">
         <font size="2" face="Verdana">
	 Please select the file with the invoice batch you wish to view.  The batch will open in a new 
	 window where you can view, print or close and return here.<br /><br />

<?
  include("billing_functions.php");

  // Begin search code
  $directory = "/web/web_pages/invoices";
  $web_root = "/invoices/";
  $dir = opendir($directory);

  // Loop through the directory
  $record = 0;
  while($file = readdir($dir)){
    // Split up the file name
    list($name, $junk) = split("[.]", $file);  // weird that I need the [.] here
    list($start, $end, $type) = split("-", "$name");

    if($start != "" && $end != "" && $type != ""){
      $type_name = getBillName($type);

      if(($from >= $start && $from <= $end) || ($to >= $start && $to <= $end)) {
	$record++;
	printf("Batch $record: <a href=\"%s%s\" target=\"_blank\">$start to $end ($type_name)</a><br /><br />", 
	       $web_root, $file);
      }
    }
  }

  if($record == 0){
    printf("<b>No results were found! &nbsp; Please make sure you enter the right invoice numbers and try again! &nbsp;  
            If you still cannot find it, try reprint from 
            <a href=\"\\\\dspc-s6\\BNI Supervisor\\Billing\\MasterInv.exe\">Master Invoice</a> instead.</b><br />");
  }
?>
	  </font>
      </td>
   </tr>
<?
}
?>

</table>

<? include("pow_footer.php"); ?>
