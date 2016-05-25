<?
  $path = "/web/web_pages/upload/";	 
  $basefile[0] = "access_point.txt"; 

  
  //get connect
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
        printf("Error logging on to the lcs Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
//   ora_commitoff($conn);
   $cursor = ora_open($conn);

   $detail = "access_point_detail";
   $column = "access_point_col";
   $max_index = 14;
  
$j = 0;
for($i = 0; $i < count($basefile); $i++){
  //parse file
  if ($handle = fopen ($path.$basefile[$i], "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		list($desc, $val) = split("=",$fLine);
		$desc = trim($desc);
		$val = trim($val);
		if ($desc <>""){
			if ($desc == "TCP/IP Settings/IP Address"){
				$arrVal = array();
				$arrVal[1] = $val;
			}else{
				$sql = "select column_index from $column where col_description = '$desc'";
                                $statement = ora_parse($cursor, $sql);
                                ora_exec($cursor);
                                if (ora_fetch($cursor)){
                                        $index = ora_getcolumn($cursor, 0);
				}else{
					$index = $max_index;
				}
				$arrVal[$index] = $val;
			}

			if ($desc == "Security/Passwords/Read Only Password"){
				$sql = "select max(id) from $detail";
				$statement = ora_parse($cursor, $sql);
                		ora_exec($cursor);
				if (ora_fetch($cursor)){
					$id = ora_getcolumn($cursor, 0);
					if ($id > 0){
					   	$id ++;
					}else{
						$id = 1;
					}
				}else{
					$id = 1;
				}
				$sql = "insert into $detail (id, col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14) values ($id, '$arrVal[1]','$arrVal[2]','$arrVal[3]','$arrVal[4]','$arrVal[5]','$arrVal[6]','$arrVal[7]','$arrVal[8]','$arrVal[9]','$arrVal[10]','$arrVal[11]','$arrVal[12]','$arrVal[13]','$arrVal[14]')";
                                $statement = ora_parse($cursor, $sql);
                                ora_exec($cursor);
				$arrVal = array();
			}
		}
	}

  }
}

?>

