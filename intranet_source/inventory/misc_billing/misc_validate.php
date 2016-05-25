<?
include("pow_session.php");


  // Define some vars for the skeleton page
  $title = "Misc Billing Import";
  $area_type = "INVENTORY";
  $user = $userdata['username'];


  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  include("connect.php");


  $conn_bni_bckup = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!conn_bni_bckup) {
    printf("Error logging on to the BNI_BACKUP Oracle Server: " . ora_errorcode($ora_conn));
    printf("Please report to TS!");
    exit;
  }


   //Open cursor
   $cursor = ora_open($conn_bni_bckup);
   $cursor1 = ora_open($conn_bni_bckup);
   $cursor2 = ora_open($conn_bni_bckup);
   $cursor3 = ora_open($conn_bni_bckup);
   $cursor4 = ora_open($conn_bni_bckup);
   $cursor5 = ora_open($conn_bni_bckup);


      
      // Grouping the records

       $sql = "select lr_num,cutomer_id,service_code,commodity_code,asset_code,service_date,rate,qty,
               type,qty2,mark from mis_input_temp where user_name = '$user'
               order by lr_num";
       $stmt = ora_parse($cursor,$sql);
       ora_exec($cursor);

       if (!ora_exec($cursor))
       {
          printf("Error in select query: $sql");
          exit;
       }
           
          $ins_cnt = 0;
          $upd_cnt = 0;
          $dup_cnt = 0;
          $insert = 0;
          $update = 0;
          $duplicate = 0;
          
          $lstartbillnum = 0;
          $lendbillnum = 0;
          $glbillnum = -1;
?>
<p><font size="2" face="Verdana" color="#0066CC">
Imported Entries are :
</font></p>

          <table border="0" bgcolor="#f0f0f0" width="100%" cellpadding="1" cellspacing="1">
           <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <style> td{font:13px} </style>
             <tr>
               <td width="1%">&nbsp;</td>
               <td>
                <table border=1 width = "100%">
                <tr>
                    <td width=40><b>Billing#</b></td>
                    <td width=20><b>Vessel#</b></td>
                    <td width=20><b>Customer#</b></td>
                    <td width=20><b>Service</b></td>
                    <td width=20><b>Commodity</b></td>
                    <td width=30><b>Asset</b></td>
                    <td width=40><b>Serv_Date</b></td>
                    <td width=250><b>Service_Description</b></td>
                    <td width=40><b>Amount</b></td>
                </tr>

<?
         while (ora_fetch($cursor))
         {
            $t_lrnum = ora_getcolumn($cursor, 0);
            $t_cid = ora_getcolumn($cursor, 1);
            $t_serv = ora_getcolumn($cursor, 2);
            $t_comm = ora_getcolumn($cursor, 3);
            $t_asset = ora_getcolumn($cursor, 4);
            $t_date = ora_getcolumn($cursor, 5);
            $t_date1 = date('m/d/Y',strtotime($t_date));
            $t_qty = ora_getcolumn($cursor, 7);
            $t_qty2 = ora_getcolumn($cursor, 9);
            $t_mark = ora_getcolumn($cursor, 10);
            $t_rate = ora_getcolumn($cursor, 6);
            $amt = $t_qty * $t_rate;
            $t_type = ora_getcolumn($cursor, 8);

                         

             $desc = $t_mark;  

         //echo "desc is ".$desc; 
                
            // Select from Billing Table
          
            
             $sql = "select * from billing where lr_num = $t_lrnum and customer_id = $t_cid and
                     commodity_code = $t_comm and service_code = $t_serv and service_date = '$t_date'
                     and service_description = '$t_mark' and service_status = 'PREINVOICE' and billing_type = 'MISC' 
                     order by billing_num";         
             $stmt = ora_parse($cursor2,$sql);
             ora_exec($cursor2);

              if (!ora_exec($cursor2))
              {
                printf("Error in select query: $sql");
                exit;
              }
             
             
             $billing = "Addnew";
             while(ora_fetch($cursor2))
             {
               $billing = "Update";
               $billnum = ora_getcolumn($cursor2, 2);
               $serv_desc = ora_getcolumn($cursor2, 8);
               $skip_commit = 0;
                               
               if (!is_null($billnum))
               {
                 if ($serv_desc <> $desc)
                 {
                     $sub_desc = $desc;

                    if (stristr($serv_desc,$sub_desc)) {
                      $dup_cnt = $dup_cnt + 1;
                      $duplicate = 1;
                      $dupcolor = 1;
                      $skip_commit = 1;
                    }
                    else
                    { 
                       if (is_null($serv_desc)) {
                          $sql = "update billing set customer_id = $t_cid,service_code = $t_serv,billing_num = $billnum,
                          employee_id = 4,service_start = '$t_date',
                          service_stop = '$t_date',service_description = '$sub_desc',
                          service_amount = service_amount + $amt,asset_code = '$t_asset',service_status = 'PREINVOICE',
                          lr_num = $t_lrnum,arrival_num = 1,commodity_code = $t_comm,
                          invoice_num = 0,service_date = '$t_date',
                          service_qty = 0,service_num = 1,threshold_qty = 0,
                          lease_num = 0,service_unit = '',service_rate = '',labor_rate_type = '',
                          labor_type = '',page_num = 0,care_of = 1,billing_type = 'MISC'
                          where billing_num = $billnum and billing_type = 'MISC'";
                       }
                       else
                       { 
                          $sql = "update billing set customer_id = $t_cid,service_code = $t_serv,billing_num = $billnum,
                          employee_id = 4,service_start = '$t_date',
                          service_stop = '$t_date',service_description = service_description||'|'||'$sub_desc',
                          service_amount = service_amount + $amt,asset_code = '$t_asset',service_status = 'PREINVOICE',
                          lr_num = $t_lrnum,arrival_num = 1,commodity_code = $t_comm, 
                          invoice_num = 0,service_date = '$t_date',
                          service_qty = 0,service_num = 1,threshold_qty = 0,
                          lease_num = 0,service_unit = '',service_rate = '',labor_rate_type = '',
                          labor_type = '',page_num = 0,care_of = 1,billing_type = 'MISC' 
                          where billing_num = $billnum and billing_type = 'MISC'";
                       } 
                     $upd_cnt = $upd_cnt + 1;
                     $update = 1;
                     $updcolor = 1;
                    }
                  }
                  else {
                   $sql = "update billing set customer_id = $t_cid,service_code = $t_serv,billing_num = $billnum,
                          employee_id = 4,service_start = '$t_date',
                          service_stop = '$t_date',service_description = '$desc',
                          service_amount = $amt,asset_code = '$t_asset',service_status = 'PREINVOICE',
                          lr_num = $t_lrnum,arrival_num = 1,commodity_code = $t_comm,
                          invoice_num = 0,service_date = '$t_date',
                          service_qty = 0,service_num = 1,threshold_qty = 0,
                          lease_num = 0,service_unit = '',service_rate = '',labor_rate_type = '',
                          labor_type = '',page_num = 0,care_of = 1,billing_type = 'MISC'
                          where billing_num = $billnum and billing_type = 'MISC'";
                   $dup_cnt = $dup_cnt + 1;
                   $duplicate = 1;
                   $dupcolor = 1;
                  }
                                
               if ($skip_commit == 0) {   
                  $stmt = ora_parse($cursor3,$sql);
                  if (!ora_exec($cursor3))
                  {
                    printf("Error in update query: $sql");
                    ora_rollback($conn_bni_bckup);
                    exit;
                  }
                  else
                  {
                    ora_commit($conn_bni_bckup);
                  }
               }
               

                if ($updcolor == 1) {
?>
                  <tr bgcolor = "#FFFFCC">
                  <td><?echo $billnum?></td>
                  <td><?echo $t_lrnum?></td>
                  <td><?echo $t_cid?></td>
                  <td><?echo $t_serv?></td>
                  <td><?echo $t_comm?></td>
                  <td><?echo $t_asset?></td>
                  <td><?echo $t_date1?></td>
                  <td><?echo $desc?></td>
                  <td><?echo round($amt,2)?></td>
                 </tr>

<?
                 }
                 else if ($dupcolor == 1) {
?>
                  <tr bgcolor = "#FF0000">
                  <td><?echo $billnum?></td>
                  <td><?echo $t_lrnum?></td>
                  <td><?echo $t_cid?></td>
                  <td><?echo $t_serv?></td>
                  <td><?echo $t_comm?></td>
                  <td><?echo $t_asset?></td>
                  <td><?echo $t_date1?></td>
                  <td><?echo $desc?></td>
                  <td><?echo round($amt,2)?></td>
                  </tr>
 
<?                }  

              $updcolor = 0;
              $dupcolor = 0;
               } // end of if(billnum is not null)
              }  // end of while(billing table)            
             
             

             if ($billing == "Addnew")
             {

               $sql = "select billing_num from billing where lr_num = $t_lrnum and customer_id = $t_cid and
                       commodity_code = $t_comm and service_code = $t_serv and service_date = '$t_date'
                       and service_status = 'PREINVOICE' and billing_type = 'MISC'
                       order by billing_num";
               $stmt = ora_parse($cursor3,$sql);
               ora_exec($cursor3);

               if (!ora_exec($cursor3))
               {
                printf("Error in select query: $sql");
                exit;
               }
               
               if(ora_fetch($cursor3))
               {
                 $glbillnum =  ora_getcolumn($cursor3, 0);
               }
               else
               {
                 $sql = "select max(billing_num) from billing";
                 $stmt = ora_parse($cursor5,$sql);
                 ora_exec($cursor5);
  
                 if (!ora_exec($cursor5))
                 {
                   printf("Error in select query: $sql");
                   exit;
                 }

                 if (ora_fetch($cursor5))
                 {
                   $glbillnum =  ora_getcolumn($cursor5, 0);
                
                   if (is_null($glbillnum))
                     $glbillnum = 1;
                   else
                     $glbillnum = $glbillnum + 1;
                 }   
                 else
                     $glbillnum = 1;

                 if ($lstartbillnum == 0)
                   $lstartbillnum  = $glbillnum;
                }  
           
      
               $sql = "insert into billing(customer_id,service_code,billing_num,employee_id,service_start,
                       service_stop,service_description,service_amount,asset_code,service_status,lr_num,
                       arrival_num,commodity_code,invoice_num,service_date,service_qty,service_num,
                       threshold_qty,lease_num,service_unit,service_rate,labor_rate_type,labor_type,
                       page_num,care_of,billing_type) values($t_cid,$t_serv,$glbillnum,4,'$t_date',
                       '$t_date','$desc',$amt,'$t_asset','PREINVOICE',$t_lrnum,
                       1,$t_comm,0,'$t_date',0,1,0,0,'','','','',0,1,'MISC')";
               $stmt = ora_parse($cursor4,$sql);
            
               
               if (!ora_exec($cursor4))
               {
                  printf("Error in insertion to Billing Table : $sql");
                  printf("<br/>");
                  printf("Please report to TS");
                  ora_rollback($conn_bni_bckup);
                  exit;
               }
               else
               {
                  ora_commit($conn_bni_bckup);
                  $ins_cnt = $ins_cnt + 1;
                  $insert = 1;
 ?>
              <tr  bgcolor = "#CCFFCC">
                  <td><?echo $glbillnum?></td>
                  <td><?echo $t_lrnum?></td>
                  <td><?echo $t_cid?></td>
                  <td><?echo $t_serv?></td>
                  <td><?echo $t_comm?></td>
                  <td><?echo $t_asset?></td>
                  <td><?echo $t_date1?></td>
                  <td><?echo $desc?></td>
                  <td><?echo round($amt,2)?></td>                      
              </tr> 
<?               
               }
         
             } // end of if(billing is addnew)
    
             $mark_val = "";
         } // end of while(mis_input_temp table fetch)

?>
</table></td></tr>
</table>

<? 
    $sql = "delete from mis_input_temp where user_name = '$user'";
    $stmt = ora_parse($cursor,$sql);
    ora_exec($cursor);

      if(!ora_exec($cursor))
      {
        printf("Error in deleting the mis_input_temp table");
        ora_rollback($conn_bni_bckup);
        exit;
      }
      else
      ora_commit($conn_bni_bckup);


    $lendbillnum = $glbillnum;

   // Log to Invoicedate table if generated prebills
   if ($lendbillnum >= $lstartbillnum)
   {
      //Function to be called
      AddNew_InvoiceDate("Miscellaneous",$lstartbillnum,$lendbillnum);
   }
 
   if ($insert == 1)
   {
?>
<p><font size="2" face="Verdana">
<? echo $ins_cnt ?> preinvoice(s) inserted in
<b>Green</b>
</font></p>
<?
   }
   if ($update == 1)
   {
?>
<p><font size="2" face="Verdana">
<? echo $upd_cnt ?> preinvoice(s) updated in
<b>Yellow</b>
</font></p>
<?
   }
   if ($duplicate == 1)
   {
?>
<p><font size="2" face="Verdana">
<? echo $dup_cnt ?> duplicate preinvoice(s) in
<b>Red</b>
-- Ignored!!
</font></p>
<?
   }
?>
     <table border = "0">
     <tr>
     <td width="80%" align="center" valign="middle">
     <form action = "index.php" method = "post" name = "retform"> 
     <input type="submit" value="Back to Misc Import Page">&nbsp;&nbsp;
     </td></form>
     </tr>
     </table>
     <br><br>

<p><font size="2" face="Verdana" color="#0066CC"><b>
You can print this page if u want for your records!!!
</b></font></p>

<?

ora_close($cursor);
ora_close($cursor1); 
ora_close($cursor2);
ora_close($cursor3);
ora_close($cursor4);
ora_close($cursor5);


ora_logoff($conn_bni_bckup);



// ************************ FUNCTION DEFINATION ******************************

function AddNew_Invoicedate($type,$sbillno,$ebillno) {
  global $conn_bni_bckup, $cursor2;

  $sql = "select max(id) from invoicedate";
  $stmt = ora_parse($cursor2,$sql);
  ora_exec($cursor2);

  if (ora_fetch($cursor2))
  {
    $id =  ora_getcolumn($cursor2, 0);

      if (is_null($id))
          $id = 0;
      else
          $id = $id + 1;
  }
  else
     $id = 0;

  $sql = "insert into invoicedate(id,run_date,bill_type,type,start_inv_no,end_inv_no) 
          values($id,sysdate,'B','$type','$sbillno','$ebillno')";
  $stmt = ora_parse($cursor2,$sql);
  
   if (!ora_exec($cursor2))
   {
     printf("Error in insertion to Invoicedate Table : $sql");
     printf("<br/>");
     printf("Please report to TS");
     ora_rollback($conn_bni_bckup);
     exit;
   }
   else
     ora_commit($conn_bni_bckup);

 }


include("pow_footer.php");

?>

