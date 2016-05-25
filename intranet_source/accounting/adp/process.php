<?
  // Seth Morecraft
  // Purpose- Ceridian Payroll Batch Generator for Oracle Applications-GL
  // Creates a Journal to be pasted into ADI's Journal entry application.
  // Start - 02-DEC-02
  // End   - 18-DEC-02
  // Last Modified - 23-JUL-03
  // Added code for ADP 30-DEC-03 - STM

  include("pow_session.php");
  $user = $userdata['username'];
  $email = $userdata['user_email'];
  $bad_codes = "";

  // NO MAGIC NUMBERS!  - not for this code...
  // edit, Adam Walter, Dec2006.  Guess what, we are canning the hardcode ;p
/*  $bene = 0;
  $normal = 0.283;
  $casb = 0.4325;
  $overtime = 0.0765;
*/
  // Init
  // edit, Adam Walter, Dec2006.  These counters are totally obsolete, so to comments they go.
/*  $db2130 = 0; $cr2130 = 0; $db2131 = 0; $cr2131 = 0;
  $dbP24170 = 0; $crP24170 = 0; $dbP44170 = 0; $crP44170 = 0;
*/
   header("Content-type: text/plain");
   header("Content-disposition: attachment; filename=adp_payroll.txt");
   set_time_limit(120);   	// make reasonably sure the script does not time out on large files

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("Please try later!");
		exit;
   }
   $cursor = ora_open($conn);


   $source = trim($HTTP_POST_FILES['file1']['tmp_name']);	// get the uploaded file name
   $now = date('m/d/Y h:i a');

   // process the file if it exists
   if(($source != 'none') && ($source != '' )){
      // open the uploaded file for reading
      $fd = fopen("$source", "rb");
      while(!feof($fd)){
        // NULL out all fields
        $iacct = ""; $iUsera = ""; $iUserb = ""; $iUserc = ""; $iUserd = "";
        $iDesc = ""; $iDebt = ""; $iCrdt = ""; $string = "";
        
        // Read a line
        $line = fgets($fd, 4096);
        if($line != ''){
          $line = trim($line);
          
          list($junk, $account, $junk2, $junk3, $amounts, $junk4, $iDesc) = split(",", $line, 7);
          // The last field should have no commas
          $iDesc = str_replace(",", " ", $iDesc);
          //echo "$junk, $account, $junk2, $junk3, $amounts, $junk4, $iDesc\n";
          // We will use ADI to verify these codes
          //$iUsera
          $iacct = substr($account, 2, 4);
          $iUsera = substr($account, 7, 4);
          $iUserb = substr($account, 12, 4);
          $iUserc = substr($account, 17, 4);
          $iUserd = substr($account, 22, 2);
          if($amounts < 0){
            $iDebt = "";
            $iCrdt = $amounts;
            $iCrdt = abs($iCrdt);
          }
          else{
            $iCrdt = "";
            $iDebt = $amounts;
            $iDebt = abs($iDebt);
          }
          
          if($iUsera == "    "){
             $iUsera = "0000";
          }
          //$iUserb
          if($iUserb == "    "){
             $iUserb = "0000";
          }
          //$iUserc = trim(substr($line, 13, 4));
          if($iUserc == "    "){
             $iUserc = "0000";
          }
          //$iUserd = trim(substr($line, 17, 2));
          if($iUserd == "  "){
             $iUserd = "00";
          }
          else if($iUserd == "0 "){
             $iUserd = "00";
          }
          else if($iUserd == ""){
             $iUserd = "00";
          }

          // Substituions begin...
		  // edit, Adam Walter, Dec2006.  Some of the following substitutions are totally otudated
		  // and as such are being commented out.
/*          if($iacct == "4155"){
            $iacct = "4150";
          }
          if($iacct == "9999"){
            $iacct = "2214";
          }
*/
          if($iCrdt == "0"){
             $iCrdt = "";
          }
          if($iDebt == "0"){
             $iDebt = "";
          }
          if($iUserb == "9699"){
            $iUserb = "0000";
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
          // TS Help Deak item # 191 / 192  NOTE:  no longer needed, as of DEC2006
/*        if($iacct == "2130"){
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
*/
          // Calculate the Benefits
		  // MAJOR edit, Adam Walter, Dec2006.  This hardcoded IF statement just isnt cutting it.
		  // As of now, it is going to be dynamic, and tied to the BNI table FINANCE_ADP_CONVERSION
/*          if($iacct == "4010" || $iacct == "4018" || $iacct == "4021" || $iacct == "4025" || $iacct == "4030" || $iacct == "4175" || $iacct == "4101" || $iacct == "4102" || $iacct == "4103" || $iacct == "4104" || $iacct == "4105" || $iacct == "4015" || $iacct == "4035" || $iacct == "4070"){
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
*/
		  $sql = "SELECT BENEFIT_ALLOCATION FROM FINANCE_ADP_CONVERSION WHERE GL_ACCT = '".$iacct."'";
		  $statement = ora_parse($cursor, $sql);
		  ora_exec($cursor);
		  // check if there is a value in the conversion table for this acct.  If so, do this...
		  if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			  $benefit = ($row['BENEFIT_ALLOCATION'] / 100);

			  printf("00,%s,%s,%s,%s,%s,%s,%s,%s\n", $iacct, $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt, $iDesc);

			  $iDebt = round($iDebt * $benefit, 2);
			  $iCrdt = round($iCrdt * $benefit, 2);
			  
			  $bene += $iCrdt;
			  $bene -= $iDebt;

			  if($iCrdt == "0"){
				 $iCrdt = "";
			  }
			  if($iDebt == "0"){
				 $iDebt = "";
			  }
            
			  printf("00,4199,%s,%s,%s,%s,%s,%s,Benefits (%s%%)\n", $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt, $row['BENEFIT_ALLOCATION']);
		  } else {
				// if no record in allocation table, do this.  (No benefit ofset calculation, since no benefit table entry)

			  if($iCrdt == "0"){
				 $iCrdt = "";
			  }
			  if($iDebt == "0"){
				 $iDebt = "";
			  }

			  printf("00,%s,%s,%s,%s,%s,%s,%s,%s\n", $iacct, $iUsera, $iUserb, $iUserc, $iUserd, $iDebt, $iCrdt, $iDesc);
		  }
		}
	  }

	

   // Print some of the lines that were consolidated
   // MAJOR edit, Adam Walter, Dec2006.  None of these values get used anymore, so... comment!
/*   // 2130
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
*/   
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
