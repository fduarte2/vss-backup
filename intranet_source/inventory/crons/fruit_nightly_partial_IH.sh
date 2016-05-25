#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for partial-inhouse-pallets
#/usa/morecraf/bin/php /var/www/html/TS_Testing/fruit_nightly_partial_IH.php
/usa/morecraf/bin/php /var/www/html/inventory/crons/fruit_nightly_partial_IH.php
