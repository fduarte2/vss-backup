#!/bin/tcsh
source ~morecraf/.cshrc

#run the email fetch before the processing 
#sh /web/web_pages/TS_Program/intranet-fetch.sh
# run the file retrieval script
#/usa/morecraf/bin/php /web/web_pages/TS_Program/DoleEDI/DoleEDI.php





# Dole DT-EDI procedures


#/usa/morecraf/bin/php /var/www/html/TS_Testing/DoleEDI_check.php
#if ( $? == 5 ) then

#/usa/morecraf/bin/php /web/web_pages/TS_Program/DoleEDI/DoleEDI.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/DoleEDI_assign.php

#endif



/usa/morecraf/bin/php /var/www/html/TS_Program/cron_checks/DoleEDI_check.php
if ( $? == 5 ) then

/usa/morecraf/bin/php /web/web_pages/TS_Program/DoleEDI/DoleEDI.php
/usa/morecraf/bin/php /var/www/html/TS_Program/cron_checks/DoleEDI_assign.php

endif
