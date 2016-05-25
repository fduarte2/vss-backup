<?
  // Seth Morecraft
  // Purpose- Ceridian Payroll Batch Generator for Oracle Applications-GL
  // Creates a Journal to be pasted into ADI's Journal entry application.
  // Start - 02-DEC-02
  // End   - 18-DEC-02
  // Last Modified - 23-JUL-03

  // NO MAGIC NUMBERS!  - not for this code...
  $bene = 0;
  $normal = 0.283;
  $casb = 0.4325;
  $overtime = 0.0765;

  // Init
  $db2130 = 0; $cr2130 = 0; $db2131 = 0; $cr2131 = 0;
  $dbP24170 = 0; $crP24170 = 0; $dbP44170 = 0; $crP44170 = 0;

   $user = $HTTP_COOKIE_VARS[accountinguser];
   if($user == ""){
      header("Location: ../../accounting_login.php");
      exit;
   }
   header("Content-type: text/plain");
   header("Content-disposition: attachment; filename=ceridian_payroll.txt");
   set_time_limit(120);   	// make reasonably sure the script does not time out on large files

   $source = trim($HTTP_POST_FILES['file1']['tmp_name']);	// get the uploaded file name
   $today = date('m-j-y h:i:s');				// timestamp for Oracle database

   // process the file if it exists
   if(($source != 'none') && ($source != '' )){
      // open the uploaded file for reading
      $fd = fopen("$source", "rb");
      while(!feof($fd)){
        // NULL out all fields
        $iacct = ""; $iUsera = ""; $iUserb = ""; $iUserc = ""; $iUserd = "";
        $iDate = ""; $iDesc = ""; $iDebt = ""; $iCrdt = ""; $string = "";
        
        // Read a line
        $line = fgets($fd, 4096);
        if($line != ''){
          $line = trim($line);
          // Ok, we have a valid line, let us process it...
          $iacct = substr($line, 0 , 4);
          // Substituions begin...
          if($iacct == "4155"){
            $iacct = "4150";
          }
          if($iacct == "9999"){
            $iacct = "2214";
          }

          // We will use ADI to verify these codes
          $iUsera = trim(substr($line, 5, 4));
          if($iUsera == "    "){
             $iUsera = "0000";
          }
          $iUserb = trim(substr($line, 9, 4));
          if($iUserb == "    "){
             $iUserb = "0000";
          }
          $iUserc = trim(substr($line, 13, 4));
          if($iUserc == "    "){
             $iUserc = "0000";
          }
          $iUserd = trim(substr($line, 17, 2));
          if($iUserd == "  "){
             $iUserd = "00";
          }
          else if($iUserd == "0 "){
             $iUserd = "00";
          }
          // Update the sub-account
          if($iacct < 3000){
            $iUsera = "0000";
            $iUserb = "0000";
            $iUserc = "0000";
            $iUserd = "00";
          }else if($iacct == "4110" || $iacct == "4120" || $iacct == "4140" || 
		   $iacct == "4170" || $iacct == "4190"){
            $iUsera = "9764";
            $iUserb = "0000";
            $iUserc = "0000";
            $iUserd = "00";
          }
          if($iUserb == "9699"){
            $iUserb = "0000";
          }
          // Grab the rest of the values
          $iDate = substr($line, 24, 8);
          $iDesc = trim(substr($line, 33, 22));
          $iDebt = trim(substr($line, 55, 13));
          $iCrdt = trim(substr($line, 68, 12));
          if($iCrdt == "0"){
             $iCrdt = "";
          }
          if($iDebt == "0"){
             $iDebt = "";
          }
          // TS Help Deak item # 191 / 192
          if($iacct == "2130"){
            $cr2130 = $cr2130 + $iCrdt;
            $db2130 = $db2130 + $iDebt;
          }
          else if($iacct == "2131"){
            $cr2131 = $cr2131 + $iCrdt;
            $db2131 = $db2131 + $iDebt;
          }
          else if($iacct == "4170"){
            if(preg_match("/2/", $iDesc)){
              $crP24170 = $crP24170 + $iCrdt;
              $dbP24170 = $dbP24170 + $iDebt;
            }
            else{
              $crP44170 = $crP44170 + $iCrdt;
              $dbP44170 = $dbP44170 + $iDebt;
            }
          }
          else{
            printf("00,%s,%s,%s,%s,%s,%s,%s,%s\n", $iacct, $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt, $iDesc);
          }

          // Calculate the Benefits
          if($iacct == "4010" || $iacct == "4018" || $iacct == "4021" || $iacct == "4025" || $iacct == "4030" || $iacct == "4175" || $iacct == "4101" || $iacct == "4102" || $iacct == "4103" || $iacct == "4104" || $iacct == "4105" || $iacct == "4015" || $iacct == "4035" || $iacct == "4070"){
             // Calculate using 28.3% of pay to benefits & tax
             $iDebt = $iDebt * $normal;
             $iCrdt = $iCrdt * $normal;
             // Round off
             $iDebt = round($iDebt, 2);
             $iCrdt = round($iCrdt, 2);
             $bene += $iCrdt;
             $bene -= $iDebt;
             if($iCrdt == "0"){
                $iCrdt = "";
             }
             if($iDebt == "0"){
                $iDebt = "";
             }
             printf("00,4199,%s,%s,%s,%s,%s,%s,Benefits (28.3)\n", $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt);
          }
          if($iacct == "4022"){
            // 'B' workers pay for Benefits
            $iDebt = $iDebt * $casb;
            $iCrdt = $iCrdt * $casb;
            // Round off
             $iDebt = round($iDebt, 2);
             $iCrdt = round($iCrdt, 2);
             $bene += $iCrdt;
             $bene -= $iDebt;
             if($iCrdt == "0"){
                $iCrdt = "";
             }
             if($iDebt == "0"){
                $iDebt = "";
             }
             printf("00,4199,%s,%s,%s,%s,%s,%s,Benefits (43.25)\n", $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt);
           }
           if($iacct == "4040" || $iacct == "4048" || $iacct == "4051" || $iacct == "4052" || $iacct == "4055" || $iacct == "4060"){
             // 7% for overtime wages
             $iDebt = $iDebt * $overtime;
             $iCrdt = $iCrdt * $overtime;
            // Round off
             $iDebt = round($iDebt, 2);
             $iCrdt = round($iCrdt, 2);
             $bene += $iCrdt;
             $bene -= $iDebt;
             if($iCrdt == "0"){
                $iCrdt = "";
             }
             if($iDebt == "0"){
                $iDebt = "";
             }
             printf("00,4199,%s,%s,%s,%s,%s,%s,Benefits (7.65)\n", $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt);
           }
        }
      }
   // Print some of the lines that were consolidated
   // 2130
   if($db2130 > 0)
     printf("00,2130,0000,0000,0000,00,%s,,ER SS\n", $db2130);
   if($cr2130 > 0)
     printf("00,2130,0000,0000,0000,00,,%s,ER SS\n", $cr2130);
   // 2131
   if($db2131 > 0)
     printf("00,2131,0000,0000,0000,00,%s,,ER MEDICARE\n", $db2131);
   if($cr2131 > 0)
     printf("00,2131,0000,0000,0000,00,,%s,ER MEDICARE\n", $cr2131);
   // 4170
   if($dbP24170 > 0)
     printf("00,4170,9764,0000,0000,00,%s,,PENSION 2 OFFSET\n", $dbP24170);
   if($crP24170 > 0)
     printf("00,4170,9764,0000,0000,00,,%s,PENSION 2 OFFSET\n", $crP24170);
   if($dbP44170 > 0)
     printf("00,4170,9764,0000,0000,00,%s,,PENSION 4 OFFSET\n", $dbP44170);
   if($crP44170 > 0)
     printf("00,4170,9764,0000,0000,00,,%s,PENSION 4 OFFSET\n", $crP44170);
   
   // And finally prinf the Benefit offset
   $bene = abs($bene);
   $bene = round($bene, 2);
   printf("00,4199,9764,0000,0000,00,,%s,Benefit Offset", $bene);

   }
   // If there is no file- how can we process??
   else{
     printf("File not found! Go away! <br />");
   }
?>
