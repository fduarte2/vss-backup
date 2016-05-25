<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  $lr_num = trim($HTTP_POST_VARS['lr_num']);
  $date = trim($HTTP_POST_VARS['date']);

  // make database connections
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  $cursor = ora_open($conn);
  
  if ($lr_num <>""){
  	if ($date <> ""){
		$date =  strtotime($date);
		if ($date == -1){
        		$msg = "Invalid Date";
		}else{
			$date = date('m/d/y h:i A', $date);
		}
	}

        if ($date <> -1){
  		$sql = "update voyage set date_departed = to_date('$date','mm/dd/yy hh12:mi AM')
                	where lr_num = $lr_num";
       		ora_parse($cursor, $sql);
       		ora_exec($cursor);
	}
  }
  ora_close($cursor);
  ora_logoff($conn);
  header("location: date_departed.php?lr_num=$lr_num&msg=$msg");

?>
