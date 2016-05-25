<?php
// PHP Compat stuff
if (!function_exists('json_encode')) {
    require_once 'json.php';
    function json_encode($arg)
    {
            global $services_json;
            if (!isset($services_json)) {
                    $services_json = new Services_JSON();
            }
            return $services_json->encode($arg);
    }
} 

if( !function_exists('json_decode') ) {
    require_once 'json.php';
    function json_decode($data) {
//        if ($bool) {
//            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
//        } else {
            $json = new Services_JSON();
//        }
        return( $json->decode($data) );
    }
}


header('Content-type:text/javascript;charset=UTF-8');


   $conn = Ora_Logon("sag_owner@RF", "owner") or die("No se pudo conectar.");
   $cursor = ora_open($conn) or die ('Could not connect.'.ora_error());
   $iContador=0;



$json=json_decode(stripslashes($_POST["_gt_json"]));
//$pageNo = $json->{'pageInfo'}->{'pageNum'};


if($json->{'action'} == 'load'){
  $sql = "select * from sigma_order";
  $v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
  $v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());

  $handle = $v_execute;		
  $retArray = array();
    while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
    $retArray[] = $row;
  }
  $data = json_encode($retArray);
  $ret = "{data:" . $data .",\n";
  $ret .= "recordType : 'object'}";
  //echo "testing";
  echo $ret;

}else if($json->{'action'} == 'save'){
  $sql = "";
  $params = array();
  $errors = "";
  $deleted_count = 0;
  
  // deal with those deleted
  $deletedRecords = $json->{'deletedRecords'};
  foreach ($deletedRecords as $value){
    $params[] = $value->ORDER_NUM;
    $deleted_count = $deleted_count +1 ;
  }
  if ($deleted_count > 0){
  $sql = "delete from sigma_order where order_num in (" . join(",", $params) . ")";
  $v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
  $v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
  }
  
  //deal with those updated
  $sql = "";
  $updatedRecords = $json->{'updatedRecords'};
  foreach ($updatedRecords as $value){
    $sql = "UPDATE SIGMA_ORDER set ".
      "EMPLOYEE ='".$value->EMPLOYEE . "', ".
      "COUNTRY ='".$value->COUNTRY . "', ".
      "CUSTOMER ='".$value->CUSTOMER . "', ".
      "ORDER2005 =".$value->ORDER2005 . ", ".
      "ORDER2006 =".$value->ORDER2006 . ", ".
      "ORDER2007 =".$value->ORDER2007 . ", ".
      "ORDER2008 =".$value->ORDER2008 . ", ".
      "DELIVERY_DATE ='".$value->DELIVERY_DATE . "' ".
      "where ORDER_NUM =".$value->ORDER_NUM;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
    }
  
  //deal with those inserted
  $sql = "";
  $insertedRecords = $json->{'insertedRecords'};
  foreach ($insertedRecords as $value){
      $sql = "insert into sigma_order (ORDER_NUM, EMPLOYEE, COUNTRY, CUSTOMER, ORDER2005, ORDER2006, ORDER2007, ORDER2008, DELIVERY_DATE) VALUES (100, '".
      $value->EMPLOYEE."', '".$value->COUNTRY."', '".$value->CUSTOMER."', ".$value->ORDER2005.", ".$value->ORDER2006.", ".$value->ORDER2007.", ".$value->ORDER2008.",  '".$value->DELIVERY_DATE."' )";
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
  }
  
  $ret = "{success : true,exception:''}";
  echo $ret;
}


?>