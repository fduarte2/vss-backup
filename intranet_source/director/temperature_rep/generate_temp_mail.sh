#!/bin/tcsh
source ~morecraf/.cshrc


# script 1 of a trio; this generates an html file to be send out via another
# cron job run 30 minutes after this one's completion
# named mail_temp_rep.sh
/usa/morecraf/bin/php /web/web_pages/director/temperature_rep/new_temperature_mail.php



