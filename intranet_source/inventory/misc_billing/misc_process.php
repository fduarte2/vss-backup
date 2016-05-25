<?
// All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Misc Import";
  $area_type = "INVE";

   $user = $userdata['username'];

  include("pow_header.php");


  $filename = trim($HTTP_POST_FILES['file1']['tmp_name']);  // uploaded file name
  $Vessel = trim($HTTP_POST_VARS['vessel']) ;
  $Service = trim($HTTP_POST_VARS['service']) ;
  $Customer = trim($HTTP_POST_VARS['customer']) ;
  $Comm = trim($HTTP_POST_VARS['comm']) ;
  $Servdate = trim($HTTP_POST_VARS['serv_date']) ;

  if (is_uploaded_file($_FILES['file1']['tmp_name'])) {
   echo "File ". $_FILES['file1']['name'] ." uploaded successfully.\n";
   echo "\nDisplaying the contents\n\n";
  } else {
   echo "Possible file upload attack: ";
   echo "filename '". $_FILES['file1']['name'] . "'.";
  }


  //Database Parameters
  include("connect.php");

  $conn_bni_bckup = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!conn_bni_bckup) {
    printf("Error logging on to the BNI_BACKUP Oracle Server: " . ora_errorcode($ora_conn));
    printf("Please report to TS!");
    exit;
  }

 $conn_prod = ora_logon("APPS@PROD", "APPS");
  if (!conn_prod) {
    printf("Error logging on to the PROD Oracle Server: " . ora_errorcode($ora_conn));
    printf("Please report to TS!");
    exit;
  }

   $cursor = ora_open($conn_bni_bckup);
   $cursor2 = ora_open($conn_bni_bckup);
   $cursor1 = ora_open($conn_prod);

   // Define no of columns in the file
   define ("COLUMN_NUM", 12);

  // process the file if it exists
   if (($filename != 'none') && ($filename != '' ))
   {
      // open the uploaded file for read
      $fd = fopen("$filename", "rb");

      // read the 1st line to get the colume names
      $line = fgets($fd, 4096);
      $line = trim($line);                      // strip whitespace from the beginning and ending
      $line = strtr($line, '\':"*', '    ');    // eliminate special characters
      $column = split(",", $line);              // assign column names to an array
      $num_cols = count($column);

      for ($i=0; $i<$num_cols; $i++)            // strip whitespace and make column names uppercase
      {
        $column[$i] = strtoupper(trim($column[$i]));
      }

      // check if we got all the required columns
     $expected_cols = array('VESSEL', 'BILL TO CUST', 'SERVICE', 'COMMODITY', 'ASSET CODE', 'DATE', 'MARK DESCRIPTION', 
                            '# OF PLTS','# OF BINS/DRUMS','DRUMS/BINS', 'RATE', 'AMOUNT');

     $existing_cols = 0;

      foreach ($column as $value)
      {
         if (in_array($value, $expected_cols))
            $existing_cols++;
      }

      if ($existing_cols != COLUMN_NUM)
      {
         printf("Has only %s columns names exist in the transfer while %s columns names are expected
                 in the first line of the file<br />", $existing_cols,count($expected_cols));
         printf("");
         printf("Please modify the file and go back to <a href=\"index.php\">Misc Billing Import Page</a> to upload it
                 again <br />");
         exit;
      }


      // create an array using the column names for keys
      for ($i=0; $i<COLUMN_NUM; $i++) {
         $data[$column[$i]] = "";
      }

  ?>

<script type="text/javascript">
 function validate(service,scode,comm,ccode,asset,acode)
 {
    if (service == 'True' && comm == 'True' && asset == 'True')
       document.location="misc_validate.php";
    else
    {
      if (service == 'False')
        alert("Invalid Service Code : "+scode);
      if (comm == 'False')
        alert("Invalid Commodity Code : "+ccode);
      if (asset == 'False')
        alert("Invalid Asset Code : "+acode);

    }

 }

</script>


<br><br>
  <form action = "" method = "post" name = "valform">
  <table border="0" bgcolor="#f0f0f0" width="100%" cellpadding="1" cellspacing="1">
           <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
   <style> td{font:11px} </style>
             <tr>
               <td width="1%">&nbsp;</td>
               <td>
                <table border=1 width = "100%">
                <tr>
                    <td width=30><b>Vessel#</b></td>
                    <td width=20><b>Customer</b></td>
                    <td width=20><b>Service</b></td>
                    <td width=20><b>Commodity</b></td>
                    <td width=40><b>Asset</b></td>
                    <td width=30><b>Date</b></td>
                    <td width=200><b>Mark</b></td>
                    <td width=20><b># of Plts</b></td>
                    <td width=20><b># of Bins/Drums</b></td>
                    <td width=20><b>Bin/Drum</b></td>
                    <td width=30><b>Rate</b></td>
                    <td width=30><b>Amount</b></td>
                </tr>
  <?

  /*    // Delete the records from mis_input_temp before storing the input

      $sql = "select * from mis_input_temp where user_name = '$user'";
      $stmt = ora_parse($cursor,$sql);
      ora_exec($cursor);

      if (ora_fetch($cursor))
        $rows = ora_numrows($cursor);


    if ($rows > 0)
    { */

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
/*
    }
*/

      while (!feof($fd))
      {
         // read a line and process it
         $line = fgets($fd, 4096);
         $line = trim($line);                     // strip whitespace from beginning and ending of the line
         $line = strtr($line, '\':"*', '    ');   // eliminate special characters

         if ($line != '')  // skip empty lines
         {
            $temp = split(",", $line, COLUMN_NUM);      // split the line and return an array


          for ($j=0; $j<COLUMN_NUM; $j++)
            $data[$column[$j]] = trim($temp[$j]);    // strip whitespaces and assign values to $data

       //  $dupl = 1;
       //  $first = 0;
         if ($data['RATE'] <> "")
         {
           // Insering the records in a csv file to a temp table

            $flrnum = $data['VESSEL'];
            $fcust = $data['BILL TO CUST'];
            $fservice = $data['SERVICE'];
            $fcomm = $data['COMMODITY'];
            $fasset = $data['ASSET CODE'];
            $fmark = $data['MARK DESCRIPTION'];
            $fdate = $data['DATE'];
            $fqty = $data['# OF PLTS'];
            $fqty2 = $data['# OF BINS/DRUMS'];
            $frate = $data['RATE'];
            $ftype = $data['DRUMS/BINS'];
            $amt = $data['AMOUNT'];


              if ($flrnum <> "")
              {
                 $prev_flrnum = $flrnum;
              }

              if ($flrnum == "")
              {
                 $flrnum = $prev_flrnum;
              }

              if ($fcust <> "")
              {
                 $prev_fcust = $fcust;
              }

              if ($fcust == "")
              {
                 $fcust = $prev_fcust;
              }

              if ($fservice <> "")
              {
                 $prev_fservice = $fservice;
              }
              if ($fservice == "")
              {
                 $fservice = $prev_fservice;
              }

              if ($fcomm <> "")
              {
                 $prev_fcomm = $fcomm;
              }

              if ($fcomm == "")
              {
                 $fcomm = $prev_fcomm;
              }

              if ($fasset <> "")
              {
                 $prev_fasset = $fasset;
              }

              if ($fasset == "")
              {
                 $fasset = $prev_fasset;
              }

              if ($fdate <> "")
              {
                 $prev_fdate = $fdate;
              }

              if ($fdate == "")
              {
                 $fdate = $prev_fdate;
              }
             
             $fdate1 = date('m/d/Y',strtotime($fdate));


             $sql = "insert into mis_input_temp values($flrnum,$fcust,$fservice,$fcomm,
                    '$fasset',to_date('$fdate1','mm/dd/yyyy'),'$fmark',$fqty,$frate,'$ftype',$fqty2,'$user',$amt)";
             $stmt = ora_parse($cursor,$sql);

             if(!ora_exec($cursor))
               {
                 printf("Error in Insertion to mis_input_temp table,$sql");
                 ora_rollback($conn_bni_bckup);
                 exit;
               }
               else
                 ora_commit($conn_bni_bckup);

             // Validate the Service Code

             $sql = "select * from fnd_flex_values where flex_value_set_id = 1005836
                     and enabled_flag = 'Y' and flex_value = '$fservice'";
             $stmt = ora_parse($cursor1,$sql);

             if(!ora_exec($cursor1))
             {
               printf("Error in selecting from fnd_flex_values table");
             }

             if (ora_fetch($cursor1))
                  $valid_service_code = "True";
             else
                  $valid_service_code = "False";

             // Validate the Commodity Code

             $sql = "select * from fnd_flex_values where flex_value_set_id = 1005837
                     and enabled_flag = 'Y' and flex_value = '$fcomm'";
             $stmt = ora_parse($cursor1,$sql);

             if(!ora_exec($cursor1))
             {
               printf("Error in selecting from fnd_flex_values table");
             }

             if (ora_fetch($cursor1))
                  $valid_comm_code = "True";
             else
                  $valid_comm_code = "False";
        
               // Validate the Asset Code

             $sql = "select * from fnd_flex_values where flex_value_set_id = 1005838
                     and enabled_flag = 'Y' and flex_value = '$fasset'";
             $stmt = ora_parse($cursor1,$sql);

             if(!ora_exec($cursor1))
             {
               printf("Error in selecting from fnd_flex_values table");
             }

             if (ora_fetch($cursor1))
                  $valid_asset_code = "True";
             else
                  $valid_asset_code = "False";

        }
        }
    }         // corresponding to while
    fclose($fd);

  // Select the records from mis_input_temp for display
      $sql = "select * from mis_input_temp where user_name = '$user'";
      $stmt = ora_parse($cursor2,$sql);
      ora_exec($cursor2);

/*      if (!ora_fetch($cursor2))
      {
        printf("Error in select query:$sql");
        exit;
      }*/

         while (ora_fetch($cursor2))
         {
            $t_lrnum = ora_getcolumn($cursor2, 0);
            $t_cid = ora_getcolumn($cursor2, 1);
            $t_serv = ora_getcolumn($cursor2, 2);
            $t_comm = ora_getcolumn($cursor2, 3);
            $t_asset = ora_getcolumn($cursor2, 4);
            $t_date = ora_getcolumn($cursor2, 5);
            $t_date1 = date('m/d/Y',strtotime($t_date));
            $t_qty = ora_getcolumn($cursor2, 7);
            $t_type = ora_getcolumn($cursor2, 9);
            $t_mark = ora_getcolumn($cursor2, 6);
            $t_rate = ora_getcolumn($cursor2, 8);
            $t_qty2 = ora_getcolumn($cursor2, 10);
            $t_amt = ora_getcolumn($cursor2, 12);


   ?>
       <tr>
          <td><?echo $t_lrnum?></td>
          <td><?echo $t_cid?></td>
          <td><?echo $t_serv?></td>
          <td><?echo $t_comm?></td>
          <td><?echo $t_asset?></td>
          <td><?echo $t_date1?></td>
          <td><?echo $t_mark?></td>
          <td><?echo $t_qty?></td>
          <td><?echo $t_qty2?></td>
          <td><?echo $t_type?></td>
          <td><?echo $t_rate?></td>
          <td><?echo $t_amt?></td>
       </tr>
<?
      }

?>


</table></td></tr>

  <tr><td>&nbsp;</td></tr>
     <tr>
     <td>&nbsp;</td>
     <td colspan="2" align="center">
     <table border = "0">
     <tr>
     <td width="80%" align="center" valign="middle">
<!--     <form action = "misc_validate.php" method = "post" name = "valform" onsubmit = "return validate()"> -->
     <input type="button" value="Save the imported records" onclick="javascript:validate('<?= $valid_service_code ?>','<?= $fservice ?>','<?= $valid_comm_code ?>','<?= $fcomm ?>','<?= $valid_asset_code ?>','<?= $fasset ?>');"></b>
    <!--- <input type="submit" value="Save the imported records"> -->&nbsp;&nbsp;
     </td></form>
    <td width="40%" align="left" valign="middle">
     <form action="index.php" method="Post" name="cancel_form">
     <input type="submit" value="Cancel">&nbsp;
     </td></form>
     </tr>
     </table>
<p><font size="2" face="Verdana">
Don't click the Save button twice.Please wait !!!
</font></p>
     </td></tr>

</table>
<?
   } else {
printf("<BR>File is not supplied, or file too big.<BR />");
   }

   ora_close($cursor);
   ora_close($cursor1);
   ora_close($cursor2);
   ora_logoff($conn_bni_bckup);
   ora_logoff($conn_prod);

 include("pow_footer.php");
?>
