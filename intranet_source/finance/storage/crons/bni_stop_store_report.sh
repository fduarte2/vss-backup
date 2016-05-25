#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for "disabled billing" bni lots
#/usa/morecraf/bin/php /var/www/html/TS_Testing/bni_stop_store_report.php
/usa/morecraf/bin/php /var/www/html/finance/storage/crons/bni_stop_store_report.php
