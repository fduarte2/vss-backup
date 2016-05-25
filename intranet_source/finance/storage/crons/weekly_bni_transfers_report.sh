#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for transfers of ownership in bni
/usa/morecraf/bin/php /var/www/html/finance/storage/crons/weekly_bni_transfers_report.php
