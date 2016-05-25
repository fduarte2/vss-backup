<?
  include("pow_session.php");

  $user = $userdata['username'];

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

   //Open Cursor
   $cursor = ora_open($conn_bni);

   $close_year = $HTTP_POST_VARS["close_year"];


   $current_year = date("Y");
   $current_month = date("m");


  if ($close_year < $current_year)
  {
     // echo "close year is less than current year";
     $msg = "You should closed it by July $close_year.Now you cannot close it";
  }
  else if ($close_year == $current_year)
   {
    // echo "close year is same as current year";
        if ($current_month > 6)
        { 
          // Check the existence in invoice_num_status table
          $sql = "select * from invoice_num_status where fiscal_year = $close_year";
          $stmt = ora_parse($cursor,$sql);

            if(!ora_exec($cursor))
            {
              printf("Error in query:$sql");
              ora_close($cursor);
              exit;
            }

             if (ora_fetch($cursor))
               $status = ora_getcolumn($cursor,1);
            $rows = ora_numrows($cursor);

              if ($rows > 0)
              {

               if ($status == 'N')
               {
                // update
                $sql = "update invoice_num_status set status = 'Y',user_name = '$user',date_closed = sysdate 
                        where fiscal_year = $close_year";
                $stmt = ora_parse($cursor,$sql);

                   if(!ora_exec($cursor))
                   {
                     printf("Error in query:$sql");
                     ora_rollback($conn_bni);
                     ora_close($cursor);
                     exit;
                    }
                   ora_commit($conn_bni);
                 $msg = "Successfully Closed for the year $close_year.";
                }
                else
                {
                 //return back the message
                 $msg = "You already closed for this year.";
                }
              }
              else
              {
                // insert
                $sql = "insert into invoice_num_status(fiscal_year,status,user_name,date_closed)
                        values($close_year,'Y','$user',sysdate)";
                $stmt = ora_parse($cursor,$sql);
         
                   if(!ora_exec($cursor))
                   {
                     printf("Error in query:$sql");
                     ora_rollback($conn_bni);
                     ora_close($cursor);
                     exit;
                    }
                   ora_commit($conn_bni);
                 $msg = "Successfully Closed for the year $close_year.";
               }
        }
        else
        {
        //return back the message
        $msg = "You can close only in July $close_year.Please try later."; 
        }
   }  
   else
   {
    // echo "close year is greater than current year";
    //return back the message
     $msg = "You can close only in July $close_year.Please try later.";
   }

ora_close($cursor);
ora_logoff($conn_bni);

  
 if ($msg <> "")
  header("Location:closing_fiscal_year.php?msg=$msg");

?>
