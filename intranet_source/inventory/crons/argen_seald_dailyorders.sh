#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for partial-inhouse-pallets
#/usa/morecraf/bin/php /var/www/html/TS_Testing/argen_seald_dailyorders.php
/usa/morecraf/bin/php /var/www/html/inventory/crons/argen_seald_dailyorders.php
