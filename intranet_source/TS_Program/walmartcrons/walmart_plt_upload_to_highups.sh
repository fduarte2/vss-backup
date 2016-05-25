#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send (yet another) walmart email.  this one is sent to "bigwigs" to prevent an subordinate from missing something we get blamed for.
/usa/morecraf/bin/php /var/www/html/TS_Program/walmartcrons/walmart_plt_upload_to_highups.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/walmart_plt_upload_to_highups.php
