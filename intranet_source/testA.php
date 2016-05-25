<?php
if (!function_exists('json_encode'))
{
function json_encode($a=false)
{
	if (is_null($a)) return 'null';
	if ($a === false) return 'false';
	if ($a === true) return 'true';
	if (is_scalar($a))
 {
	if (is_float($a))
	{
	// Always use "." for floats.
	return floatval(str_replace(",", ".", strval($a)));
  }
 
	if (is_string($a))
	{
	static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
	return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
	}
 	else
	return $a;
}
	$isList = true;
	for ($i = 0, reset($a); $i < count($a); $i++, next($a))
	{
	if (key($a) !== $i)
	{
	$isList = false;
	break;
}
}
	$result = array();
	if ($isList)
{
	foreach ($a as $v) $result[] = json_encode($v);
	return '[' . join(',', $result) . ']';
}
	else
{
	foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
	return '{' . join(',', $result) . '}';
	}
	}
	
}
/*Simple Service




This is just a simple php script that will return values ,the 
method is selected using the value of HTTP_METHOD
*/
if ($_SERVER['HTTP_METHOD'] === 'getValues'){
 //just return some test values
   $data['value1'] = 1;
   $data['value2'] = "Hello php service world";
   echo json_encode($data);
}
else if ($_SERVER['HTTP_METHOD'] === 'postValues'){ 
   $body;
   /*Sometimes the body data is attached in raw form and is not attached 
   to $_POST, this needs to be handled*/
   if($_POST == null){
      $handle  = fopen('php://input', 'r');
      $rawData = fgets($handle);
      $body = json_decode($rawData);
   }
   else{
      $body == $_POST;
   }
	$data['value1'] = 1;
   $data['value2'] = "Hello php service world";
   echo json_encode($data);

   //echo json_encode($body);//just return the post you sent it for testing purposes
}
else {
   $data['error'] = 'The Service you asked for was not recognized';
   echo json_encode($data);
}
?>
