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


   $conn = Ora_Logon("sag_owner@RFTEST", "rftest238") or die("No se pudo conectar.");
   $cursor = ora_open($conn) or die ('Could not connect.'.ora_error());
   $iContador=0;



$json=json_decode(stripslashes($_POST["_gt_json"]));
//$pageNo = $json->{'pageInfo'}->{'pageNum'};


if($json->{'action'} == 'load'){
  $sql = "select * from whse_layout";
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
  	$sql = "delete from WHSE_LAYOUT where ENTRY_NUM = '".$value->ENTRY_NUM."'";
  	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
  }
  
  //deal with those updated
  $sql = "";
  $updatedRecords = $json->{'updatedRecords'};
  foreach ($updatedRecords as $value){
    $sql = "UPDATE WHSE_LAYOUT set ".
      "ARRIVAL_NUM ='".$value->ARRIVAL_NUM . "', ".
      "FUME_STATUS ='".$value->FUME_STATUS . "', ".
      "HATCH_DECK ='".$value->HATCH_DECK . "', ".
      "RECEIVER_ID =".$value->RECEIVER_ID . ", ".
      "COMMODITY_CODE =".$value->COMMODITY_CODE . ", ".
      "VARIETY ='".$value->VARIETY . "', ".
      "CARGO_LABEL ='".$value->CARGO_LABEL . "', ".
      "CARGO_SIZE ='".$value->CARGO_SIZE . "', ".
      "SPECIAL_HANDLING ='".$value->SPECIAL_HANDLING . "', ".
      "QTY_EXPECTED ='".$value->QTY_EXPECTED . "', ".
      "FUME_SECTION ='".$value->FUME_SECTION . "', ".
      "FUME_ROWS ='".$value->FUME_ROWS . "', ".
      "TRANSFER_TO_WHSE ='".$value->TRANSFER_TO_WHSE . "', ".
      "TRANSFER_TO_BOX ='".$value->TRANSFER_TO_BOX . "', ".
      "TRANSFER_TO_ROWS ='".$value->TRANSFER_TO_ROWS . "' ".
      "where ENTRY_NUM =".$value->ENTRY_NUM;
//	echo $sql;
//	exit;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
    }
  
  //deal with those inserted
  $sql = "";
  $insertedRecords = $json->{'insertedRecords'};
  foreach ($insertedRecords as $value){
      $sql = "insert into whse_layout (ARRIVAL_NUM,FUME_STATUS,HATCH_DECK,RECEIVER_ID,COMMODITY_CODE,VARIETY, CARGO_LABEL, ".
	"CARGO_SIZE, SPECIAL_HANDLING, QTY_EXPECTED, FUME_SECTION, FUME_ROWS, TRANSFER_TO_WHSE,TRANSFER_TO_BOX, TRANSFER_TO_ROWS) ".
	"VALUES ('".
	$value->ARRIVAL_NUM ."','".
	$value->FUME_STATUS ."', '".
	$value->HATCH_DECK ."', '".
	$value->RECEIVER_ID ."', '".
	$value->COMMODITY_CODE ."', '".
	$value->VARIETY ."', '".
	$value->CARGO_LABEL ."', '".
	$value->CARGO_SIZE ."', '".
	$value->SPECIAL_HANDLING ."', '".
	$value->QTY_EXPECTED ."', '".
	$value->FUME_SECTION ."', '".
	$value->FUME_ROWS ."', '".
	$value->TRANSFER_TO_WHSE ."', '".
	$value->TRANSFER_TO_BOX ."', '".
	$value->TRANSFER_TO_ROWS ."')" ;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
//      $value->EMPLOYEE."', '".$value->COUNTRY."', '".$value->CUSTOMER."', ".$value->ORDER2005.", ".$value->ORDER2006.", ".$value->ORDER2007.", ".$value->ORDER2008.",  '".$value->DELIVERY_DATE."' )";

  }
  
  $ret = "{success : true,exception:''}";
  echo $ret;
}


?>