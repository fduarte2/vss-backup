#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for non-freetime-set vessels
/usa/morecraf/bin/php /var/www/html/finance/storage/crons/storage_no_freetimeset.php
