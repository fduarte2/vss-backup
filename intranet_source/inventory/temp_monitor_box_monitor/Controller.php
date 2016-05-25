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


//   $conn = Ora_Logon("sag_owner@BNITEST", "bnitest238") or die("No se pudo conectar.");
   $conn = Ora_Logon("LABOR@LCS", "LABOR") or die("Could Not Connect to DB.");
   $cursor = ora_open($conn) or die ('Could not connect.'.ora_error());
   $iContador=0;



$json=json_decode(stripslashes($_POST["_gt_json"]));
//$pageNo = $json->{'pageInfo'}->{'pageNum'};

$user = $userdata['username'];

if($json->{'action'} == 'load'){
  $sql = "select WHSE, BOX, TEMPERATURE_DISPLAY, DATE_CHANGED, LAST_CHANGE_BY from WAREHOUSE_LOCATION order by WHSE, BOX";
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
    
  //deal with those updated
  $sql = "";
  $updatedRecords = $json->{'updatedRecords'};
  foreach ($updatedRecords as $value){
    $sql = "UPDATE WAREHOUSE_LOCATION set ".
      "TEMPERATURE_DISPLAY ='".$value->TEMPERATURE_DISPLAY . "', ".
      "DATE_CHANGED = SYSDATE, ".
      "LAST_CHANGE_BY ='".$user . "' ".
      "where WHSE ='".$value->WHSE . "' AND BOX = '".$value->BOX."'";
//	echo $sql;
//	exit;
	$v_parse = ora_parse($cursor, $sql) or die ('select '.ora_error());
	$v_execute = ora_exec($cursor) or die ('Execution not made.'.ora_error());
    }
  
  $ret = "{success : true,exception:''}";
  echo $ret;
}


?>