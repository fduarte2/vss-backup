<?
  include("pow_session.php");
  
  //get connect
   $conn_rf = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn_rf < 1){
        printf("Error logging on to the RF Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   ora_commitoff($conn_rf);
   $cursor_rf = ora_open($conn_rf);

   $source = trim($HTTP_POST_FILES['file1']['tmp_name']);       // get the uploaded file name

  $i = 0;
  $j = 0;
  //parse file
  if ($handle = fopen ($source, "rb")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		$pId = trim($fLine);
		
		if ($pId <>""){ 	
			$sql = "select count(*) from cargo_tracking where pallet_id = '$pId'";
			$statement = ora_parse($cursor_rf, $sql);
  			ora_exec($cursor_rf);
			if (ora_fetch($cursor_rf)){
				$cnt = ora_getcolumn($cursor_rf, 0);
			}else{
				$cnt = 0;
			}
			if ($cnt ==1){
				$sql = "select * from cargo_tracking 
					where pallet_id = '$pId' and cargo_description like 'Z %'";
				$statement = ora_parse($cursor_rf, $sql);
                                ora_exec($cursor_rf);
				
				if (!ora_fetch($cursor_rf)){ 	
	                      		$sql = "update cargo_tracking set cargo_description = 'Z '||cargo_description 
						where pallet_id='$pId'";
					$statement = ora_parse($cursor_rf, $sql);
                       			if (ora_exec($cursor_rf)){
						ora_commit($conn_rf);
						$i++;
					}else{
						ora_rollback($conn_rf);
                                        	continue; 
					}
				}else{
					$k++;
				}
			}else{
				
			}

			$j++;
		}
	}
	$msg ="Total pallets = $j, Updated pallets = $i";
	
  }else{
	$msg = "File not found!";
  }
  ora_close($cursor_rf);
  ora_logoff($conn_rf);

  header("Location: index.php?msg=$msg");

?>

