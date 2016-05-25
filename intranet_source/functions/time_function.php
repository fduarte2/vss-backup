<?
function convert2time($sec)
{
 if( $hours=intval((floor($sec/3600))) )
   $sec = $sec % 3600;
 if( $minutes=intval((floor($sec/60))) )
   $sec = $sec % 60;

   $secs = intval( $sec );

 $sec = array('hours'=>$hours, 'minutes'=>$minutes,'seconds'=>$secs);

 if($sec['seconds'] >= 30)
 {
  $sec['minutes'] = $sec['minutes'] + 1;
 }

 if($sec['minutes'] > 59)
 {
   $sec['hours'] = $sec['hours'] + 1;
   $sec['minutes'] = 00;
 }


 $sec['seconds'] = 00;

// $time = $sec['hours'].":".$sec['minutes'].":".$sec['seconds'];

 $time = $sec['hours'].":".$sec['minutes'];

 return $time;

}
?>
