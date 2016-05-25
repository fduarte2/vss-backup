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


//   $conn = Ora_Logon("sag_owner@BNITEST", "bnitest238") or die("No se pudo conectar.");
   $conn = Ora_Logon("SAG_OWNER@BNI", "SAG") or die("No se pudo conectar.");
   $cursor = ora_open($conn) or die ('Could not connect.'.ora_error());
   $iContador=0;



$json=json_decode(stripslashes($_POST["_gt_json"]));
//$pageNo = $json->{'pageInfo'}->{'pageNum'};


if($json->{'action'} == 'load'){
  $sql = "select contractid, description, TO_CHAR(STARTDATE, 'MM/DD/YYYY') START_DATE, TO_CHAR(ENDDATE, 'MM/DD/YYYY') END_DATE from contract";
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
  	$sql = "delete from contract where CONTRACTID ='".$value->CONTRACTID . "' ";
  	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
  }
  
  //deal with those updated
  $sql = "";
  $updatedRecords = $json->{'updatedRecords'};
  foreach ($updatedRecords as $value){
    $sql = "UPDATE contract set ".
      "DESCRIPTION ='".$value->DESCRIPTION . "', ".
      "STARTDATE = TO_DATE('".$value->START_DATE . "', 'MM/DD/YYYY'), ".
      "ENDDATE = TO_DATE('".$value->END_DATE . "', 'MM/DD/YYYY') ".
      "where CONTRACTID ='".$value->CONTRACTID . "'";
//	echo $sql;
//	exit;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
    }
  
  //deal with those inserted
  $sql = "";
  $insertedRecords = $json->{'insertedRecords'};
  foreach ($insertedRecords as $value){
      $sql = "insert into contract 
	  (DESCRIPTION, 
	  STARTDATE, 
	  ENDDATE) 
	VALUES ('".
	$value->DESCRIPTION ."',".
	"TO_DATE('".$value->START_DATE . "', 'MM/DD/YYYY'),".
	"TO_DATE('".$value->END_DATE . "', 'MM/DD/YYYY'))" ;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
//      $value->EMPLOYEE."', '".$value->COUNTRY."', '".$value->CUSTOMER."', ".$value->ORDER2005.", ".$value->ORDER2006.", ".$value->ORDER2007.", ".$value->ORDER2008.",  '".$value->DELIVERY_DATE."' )";

  }
  
  $ret = "{success : true,exception:''}";
  echo $ret;
}


?>