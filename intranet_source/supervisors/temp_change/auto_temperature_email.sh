#!/bin/tcsh
source ~morecraf/.cshrc

# runs the temperature email cron (SUPV only for review) 
/usr/bin/php /web/web_pages/supervisors/temp_change/auto_temperature_email.php TEMPREPORT1
#/usr/bin/php /web/web_pages/TS_Testing/auto_temperature_email.php TEMPREPORT1
