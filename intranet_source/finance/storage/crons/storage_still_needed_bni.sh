#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for lot nums needing to be back-run
/usa/morecraf/bin/php /var/www/html/finance/storage/crons/storage_still_needed_bni.php
