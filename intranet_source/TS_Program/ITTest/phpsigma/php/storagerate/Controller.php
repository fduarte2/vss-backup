<?php

  // All dspc-s16 files need this session file included
  include("pow_session.php");



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


   $conn = Ora_Logon("sag_owner@BNITEST", "bnitest238") or die("No se pudo conectar.");
   $cursor = ora_open($conn) or die ('Could not connect.'.ora_error());
   $iContador=0;



$json=json_decode(stripslashes($_POST["_gt_json"]));
//$pageNo = $json->{'pageInfo'}->{'pageNum'};


if($json->{'action'} == 'load'){
  $sql = "select * from rate";
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
  
  // deal with those deleted
  $deletedRecords = $json->{'deletedRecords'};
  foreach ($deletedRecords as $value){
  	$sql = "delete from rate where ROW_NUM = '".$value->ROW_NUM."'";
  	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
  }
  
  //deal with those updated
  $sql = "";
  $updatedRecords = $json->{'updatedRecords'};
  foreach ($updatedRecords as $value){
    $sql = "UPDATE rate set ".
      "CONTRACTID ='".$value->CONTRACTID . "', ".
      "DATEENTERED = SYSDATE, ".
      "ENTEREDBY ='".$userdata['username']. "', ".
      "CUSTOMERID =".$value->CUSTOMERID . ", ".
      "COMMODITYCODE =".$value->COMMODITYCODE . ", ".
      "RATEPRIORITY ='".$value->RATEPRIORITY . "', ".
      "FRSHIPPINGLINE ='".$value->FRSHIPPINGLINE . "', ".
      "TOSHIPPINGLINE ='".$value->TOSHIPPINGLINE . "', ".
      "ARRIVAL_NUMBER ='".$value->ARRIVAL_NUMBER . "', ".
      "ARRIVALTYPE ='".$value->ARRIVALTYPE . "', ".
      "FREEDAYS ='".$value->FREEDAYS . "', ".
      "WEEKENDS ='".$value->WEEKENDS . "', ".
      "HOLIDAYS ='".$value->HOLIDAYS . "', ".
      "BILLDURATION ='".$value->BILLDURATION . "', ".
      "BILLDURATIONUNIT ='".$value->BILLDURATIONUNIT . "', ".
      "RATESTARTDATE ='".$value->RATESTARTDATE . "', ".
      "RATE ='".$value->RATE . "', ".
      "SERVICECODE ='".$value->SERVICECODE . "', ".
      "UNIT ='".$value->UNIT . "', ".
      "STACKING ='".$value->STACKING . "', ".
      "WAREHOUSE ='".$value->WAREHOUSE . "', ".
      "BOX ='".$value->BOX . "', ".
      "BILLTOCUSTOMER ='".$value->BILLTOCUSTOMER . "', ".
      "XFRDAYCREDIT ='".$value->XFRDAYCREDIT . "' ".
      "where ROW_NUM =".$value->ROW_NUM;
//	echo $sql;
//	exit;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
    }
  
  //deal with those inserted
  $sql = "";
  $insertedRecords = $json->{'insertedRecords'};
  foreach ($insertedRecords as $value){
      $sql = "insert into rate 
	  (CONTRACTID,
	  DATEENTERED,
	  ENTEREDBY,
	  CUSTOMERID,
	  COMMODITYCODE,
	  RATEPRIORITY, 
	  FRSHIPPINGLINE,
	  TOSHIPPINGLINE, 
	  ARRIVAL_NUMBER, 
	  ARRIVALTYPE, 
	  FREEDAYS, 
	  WEEKENDS, 
	  HOLIDAYS,
	  BILLDURATION, 
	  BILLDURATIONUNIT, 
	  RATESTARTDATE, 
	  RATE, 
	  SERVICECODE, 
	  UNIT, 
	  STACKING, 
	  WAREHOUSE, 
	  BOX, 
	  BILLTOCUSTOMER, 
	  XFRDAYCREDIT) 
	VALUES ('".
	$value->CONTRACTID ."',".
	"SYSDATE,".
	"'".$userdata['username']."',".
	"'".$value->CUSTOMERID ."',".
	"'".$value->COMMODITYCODE ."',".
	"'".$value->RATEPRIORITY ."',".
	"'".$value->FRSHIPPINGLINE ."',".
	"'".$value->TOSHIPPINGLINE ."',".
	"'".$value->ARRIVAL_NUMBER ."',".
	"'".$value->ARRIVALTYPE ."',".
	"'".$value->FREEDAYS ."',".
	"'".$value->WEEKENDS ."',".
	"'".$value->HOLIDAYS ."',".
	"'".$value->BILLDURATION ."',".
	"'".$value->BILLDURATIONUNIT ."',".
	"'".$value->RATESTARTDATE ."',".
	"'".$value->RATE ."',".
	"'".$value->SERVICECODE ."',".
	"'".$value->UNIT ."',".
	"'".$value->STACKING ."',".
	"'".$value->WAREHOUSE ."',".
	"'".$value->BOX ."',".
	"'".$value->BILLTOCUSTOMER ."',".
	"'".$value->XFRDAYCREDIT ."')" ;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
//      $value->EMPLOYEE."', '".$value->COUNTRY."', '".$value->CUSTOMER."', ".$value->ORDER2005.", ".$value->ORDER2006.", ".$value->ORDER2007.", ".$value->ORDER2008.",  '".$value->DELIVERY_DATE."' )";

  }
  
  $ret = "{success : true,exception:''}";
  echo $ret;
}


?>