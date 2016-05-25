<?

/* Script Task: Date Compare 
 * Author: NOT laurenceveale (Modified the date format)
 * Description: The following function compares two dates in the mm/dd/yyyy format.
 *
 * It uses the PHP function gregoriantojd() as mktime() is unreliable for 
 * dates < 1970. The mcal_date_compare also relies on the mcal libraries being installed.
 *
 * Returns <0, 0, >0 if date a is earlier than date b, date a is equal to date b,date a 
 * is later than date b respectively. 
 */

function compareDate ($i_sFirstDate, $i_sSecondDate){ 
   //Break the Date strings into seperate components 
   $arrFirstDate = explode ("/", $i_sFirstDate); 
   $arrSecondDate = explode ("/", $i_sSecondDate); 

   $intFirstMonth = $arrFirstDate[0];
   $intFirstDay = $arrFirstDate[1]; 
   $intFirstYear = $arrFirstDate[2]; 

   $intSecondMonth = $arrSecondDate[0];
   $intSecondDay = $arrSecondDate[1]; 
   $intSecondYear = $arrSecondDate[2]; 

   // Calculate the diference of the two dates and return the number of days. 
   $intDate1Jul = gregoriantojd($intFirstMonth, $intFirstDay, $intFirstYear); 
   $intDate2Jul = gregoriantojd($intSecondMonth, $intSecondDay, $intSecondYear); 

   return $intDate1Jul - $intDate2Jul; 
} 

/**
 * Get the interval between two dates
 * Interval can be one of:
    d	Day
    w	Weekday
    h	Hour
    n	Minute
    s	Second
 */
function DateDiff($interval,$date1,$date2){
    // get the number of seconds between the two dates
  $timedifference = $date2 - $date1;

    switch ($interval) {
        case 'w':
            $retval = bcdiv($timedifference,604800);
            break;
        case 'd':
            $retval = bcdiv($timedifference,86400);
            break;
        case 'h':
            $retval =bcdiv($timedifference,3600);
            break;
        case 'n':
            $retval = bcdiv($timedifference,60);
            break;
        case 's':
            $retval = $timedifference;
            break;
            
    }
    return $retval;
}

/* Returns a date to which a specified time interval has been added
 * Interval can be one of:
    yyyy	year
    q	Quarter
    m	Month
    y	Day of year
    d	Day
    w	Weekday
    ww	Week of year
    h	Hour
    n	Minute
    s	Second
 */
function DateAdd($interval, $number, $date) {

    $date_time_array = getdate($date);
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($interval) {
    
        case 'yyyy':
            $year+=$number;
            break;
        case 'q':
            $year+=($number*3);
            break;
        case 'm':
            $month+=$number;
            break;
        case 'y':
        case 'd':
        case 'w':
            $day+=$number;
            break;
        case 'ww':
            $day+=($number*7);
            break;
        case 'h':
            $hours+=$number;
            break;
        case 'n':
            $minutes+=$number;
            break;
        case 's':
            $seconds+=$number; 
            break;            
    }
       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
    return $timestamp;
}
 
?>
