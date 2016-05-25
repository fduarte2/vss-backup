<?
function html($value){
   if ($value ==""){
	$value = "&nbsp;";
   }
   return $value;
}

function OraSafeString($string){
   return str_replace("\'", "''",$string);
}
?>
