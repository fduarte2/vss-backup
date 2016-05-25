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

function compareDate ($i_sFirstDate, $i_sSecondDate) 
{ 
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


?>
